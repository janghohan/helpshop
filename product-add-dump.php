<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product.css">
    
    <title>대량 상품 등록</title>
    <style>
        body {
            background-color: #f9f9f9;
        }
        @media (min-width: 1400px) {
            .container{
                max-width: 98%;
                font-size: 14px;
            }
        }
        .search-options, .summary-cards, .filter-options {
            padding: 20px;
            margin-bottom: 20px;
        }
        .summary-cards .card {
            border: none;
            background: #f9f9f9;
            border-radius: 10px;
            text-align: center;
            font-size: 0.9rem;
            width: 30%;
        }
        .summary-cards .card h5 {
            margin: 10px 0;
        }
        .summary-cards .card .value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .table-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ui-datepicker {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-family: 'Arial', sans-serif;
        }
        .ui-datepicker-header {
            background: #007bff;
            color: white;
        }
        .ui-datepicker-calendar .ui-state-hover {
            background: #28a745;
            color: white;
        }

    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    require_once __DIR__ . '/api/SimpleXLSX.php'; // 실제 경로 확인
    require_once __DIR__ . '/api/SimpleXLSXGen.php'; // 실제 경로 확인

    use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
    use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음
    
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $today = date("Y-m-d");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $productMarketIx = isset($_POST['productMarketIx']) ? $_POST['productMarketIx'] : ''; //마켓 ix

        //네이버 파일인지 쿠팡파일인지 확인
        $marketQuery = "SELECT market_name FROM market WHERE user_ix='$user_ix' AND ix='$productMarketIx'";
        $result = $conn->query($marketQuery);
        $row = $result->fetch_assoc();
        $marketName = $row['market_name'];

        
        if (isset($_FILES['productExcelFile'])) {
            // 파일 경로
            $filePath = $_FILES['productExcelFile']['tmp_name'];

            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($filePath)) {
                $dataA = $xlsxA->rows();
            } else {
                echo "Error reading Excel A: " . SimpleXLSX::parseError();
                exit;
            }
        }

               
    }else{
        
    }

    if(isset($dataA)){

        $groupedProducts = [];
        foreach ($dataA as $indexA => $rowA) {
            if($indexA<=1){
                continue;
            }

            $name = $rowA[0];
            $option = $rowA[1];
            $optionValue = $rowA[2];
            $price = $rowA[3];
            $cost = $rowA[4];
            $quantity = $rowA[5];

            $currentProductName = $name;
            if ($currentProductName !== $previousProductName) {
                // 상품명이 바뀐다.
                $productStmt = $conn->prepare("INSERT INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
                $productStmt->bind_param("sssss",$userIx,'','',$name,'');
                $productStmt->execute();
                
                $productIx = $productStmt->insert_id;
                $productResult = $productStmt->get_result();
            }



            if (!isset($groupedProducts[$orderNumber])) {
                $groupedProducts[$orderNumber] = [
                    'global_order_number' => generateOrderNumber($userIx),
                    'order_date' => '',
                    'total_payment' => 0,
                    'total_shipping' => 0,
                ];
            }


            $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
            $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신




            $productStmt = $conn->prepare("INSERT INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
            $productStmt->bind_param("sssss",$userIx,$accountIx,$categoryIx,$productName,$productMemo);
            $productStmt->execute();

            $productIx = $productStmt->insert_id;
            $productResult = $productStmt->get_result();

            //product_option
            $options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
            foreach ($options as $option){
                $optionName = $option['name'];
                $optionValues = $option['value'];
                foreach($optionValues as $optionValue) {
                    $optionStmt = $conn->prepare("INSERT INTO product_option(product_ix,name,value) VALUES(?,?,?)");
                    $optionStmt->bind_param("sss",$productIx,$optionName,$optionValue);
                    $optionStmt->execute();
                }
            }

            //product_option_combination
            foreach ($formCombination as $combination) {
                $name = $combination['name'];
                $price = str_replace(",", "", $combination['price']);
                $stock = $combination['stock'];
                $sellings = $combination['selling'];   

                $combiStmt = $conn->prepare("INSERT INTO product_option_combination(product_ix,combination_key,cost_price,stock) VALUES(?,?,?,?)");
                $combiStmt -> bind_param("ssss",$productIx,$name,$price,$stock);
                $combiStmt->execute();

                $combiIx = $combiStmt->insert_id;

                // 옵션 데이터
                foreach ($sellings as $selling) {
                    $marketStmt = $conn->prepare("INSERT INTO product_option_market_price(product_option_comb_ix,market_ix,price) VALUES(?,?,?)");
                    $sellingPrice = str_replace("","",$selling['value']);
                    $marketStmt->bind_param("sss",$combiIx,$selling['ix'],$sellingPrice);
                    $marketStmt->execute();
                    // echo "Market ID $marketIx: $sellingPrice<br>";
                }
            }

            $conn->commit();

        }
    }
    ?>
    <!-- 헤더 -->

  

    <div class="full-content">

        <div class="main-content">
            <div class="container mt-4">
                <!-- Search Options -->
                <div class="search-options d-flex justify-content-between">
                    <h2>대량 상품 등록</h2>
                    <div class="row justify-content-end ">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary w-100">
                                <a href="./제품 등록 양식.xlsx" class="text-white">엑셀 양식 받기</a>
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary w-100" id="excel-btn">상품 엑셀 등록</button>
                        </div>
                    </div>

                </div>

                <!-- Order Table -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>판매처</th>
                            <th>제품이름</th>
                            <th>옵션구별값</th>
                            <th>옵션값</th>
                            <th>판매가</th>
                            <th>원가</th>
                            <th>재고수량</th>
                        </tr>
                        </thead>
                        <tbody id="order-list">
                        <!-- Order rows will be added dynamically -->
                            <?php
                                $previousProductName = null; // 이전 주문번호를 저장
                                $toggle = true; // 색상을 변경하기 위한 토글 변수

                                if(isset($dataA)){
                                    foreach ($dataA as $indexA => $rowA) {
                                        if($indexA<=1){
                                            continue;
                                        }

                                        $name = $rowA[0];
                                        $option = $rowA[1];
                                        $optionValue = $rowA[2];
                                        $price = $rowA[3];
                                        $cost = $rowA[4];
                                        $quantity = $rowA[5];

                                        $currentProductName = $name;
                                        if ($currentProductName !== $previousProductName) {
                                            // 상품명이 바뀐다.
                                            $toggle = !$toggle;
                                            $shipping = $orderRow['total_shipping'];
                                        }else{
                                            $shipping = 0;
                                        }
                                        $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                                        $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신


                                ?>        
                                <tr style="background-color: <?= $backgroundColor ?>;">
                                    <td><?=htmlspecialchars($orderRow['market_name'])?></td>
                                    <td><?=htmlspecialchars($orderRow['order_date'])?></td>
                                    <td><?=htmlspecialchars($orderRow['global_order_number'])?></td>
                                    <td><?=htmlspecialchars($orderRow['name'])?></td>
                                    <td><?=htmlspecialchars($orderRow['quantity'])?></td>
                                    <td><?=htmlspecialchars(number_format($orderRow['price']))."원"?></td>
                                    <td><?=htmlspecialchars(number_format($shipping))."원"?></td>
                                </tr>
                                
                            <?php
                                }}
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                </div>
                <div class="modal-body">
                    <form action="./product-add-dump.php" id="productExcelForm" method="post" enctype="multipart/form-data">
                        <select name="productMarketIx" class="form-control" id="">
                            <?php
                                $searchResult = [];
                                
                                $query = "SELECT * FROM market WHERE user_ix='$userIx'";
                                $result = $conn->query($query);
                        
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $searchResult[] = $row;
                                    }
                                }

                                foreach($searchResult as $marketRow){
                            
                            ?>
                                <option value="<?=htmlspecialchars($marketRow['ix'])?>"><?=htmlspecialchars($marketRow['market_name'])?></option>
                            <?php }?>
                        </select>
                        <input type="file" name="productExcelFile" class="form-control mt-3"  accept=".xlsx, .xls">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="tmpExcel()">등록</button>
                </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="./js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="./js/product.js"></script>
    <script src="./js/common.js"></script>
    
    
    <script>
        var startDate = "<?=$startDate?>";
        var endDate = "<?=$endDate?>";

        $(document).ready(function() {
            flatpickr("#flatpickr", {
                defaultDate: ["<?=$startDate?>","<?=$endDate?>"],
                dateFormat: "Y-m-d",
                mode: "range",
                altInput: true,
                theme: "material_blue",
                locale: "ko",
                onChange: function(selectedDates, dateStr, instance) {
                    if(dateStr.includes('~')) changeRageText(dateStr);
                    
                }
            });

        });


        function tmpExcel(){
            $("#productExcelForm").submit();
        }

        $("#excel-btn").click(function(){
            modalOpen("excelModal");
        });

       
        $("#search-btn").click(function(){
            searchOrderList();
        });

        function changeRageText(dateRange){
			const dates = dateRange.split(' ~ ');
			startDate = dates[0];
			endDate = dates[1];

		}
          
        function searchOrderList(){
            console.log("start",startDate);
            console.log("end",endDate);
            
            if($("#order-search").val()==''){
                location.href = './order.php?start='+startDate+"&end="+endDate;
            }else{
                const searchType = $("#order-filter option:selected").val();
                location.href = './order.php?start='+startDate+"&end="+endDate+"&searchType="+searchType+"&searchKeyword="+$("#order-search").val();
            }
        }
        $("#marginCard .btn").click(function(){
            console.log("button");
            calculateType = $(this).attr('data-v');
            const thisBtn = $(this);
            const formData = new FormData();
            formData.append('calculateType', calculateType);
            formData.append('startDate', startDate);
            formData.append('endDate', endDate);
            formData.append('searchType', $("#order-filter option:selected").val());
            formData.append('searchKeyword', $("#order-search").val());

            $(thisBtn).find('i').addClass("rotating-icon");
            $.ajax({
                url: './api/margin_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) { 
                    console.log(response);
                    $(thisBtn).text(response.result);

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });

        });

       
    </script>
</body>
</html>
