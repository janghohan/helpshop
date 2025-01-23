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

        $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : date("Y-m-d");
        $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : date("Y-m-d");
       
        $listStmt = $conn->prepare("SELECT poc.ix as combIx, pomp.ix as mpIx, 
        m.market_name, p.name, poc.combination_key, poc.cost_price, poc.stock, pomp.price FROM product p 
        JOIN product_option_combination poc ON p.ix = poc.product_ix AND p.user_ix=? 
        JOIN product_option_market_price pomp ON poc.ix = pomp.product_option_comb_ix 
        JOIN market m ON m.ix = pomp.market_ix AND p.create_at >= ? AND p.create_at <= ?");
        if (!$listStmt) {
            throw new Exception("Error preparing list statement: " . $conn->error); // *** 수정 ***
        }
        $listStmt->bind_param("sss",$userIx,$startTime,$endTime);
        if (!$listStmt->execute()) {
            throw new Exception("Error executing list statement: " . $listStmt->error); // *** 수정 ***
        }

        $result = $listStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listResult[] = $row;
            }
        }
    }else{
        
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
                            <th>옵션값</th>
                            <th>판매가</th>
                            <th>원가</th>
                            <th>재고수량</th>
                        </tr>
                        </thead>
                        <tbody id="product-list">
                        <!-- Order rows will be added dynamically -->
                            <?php
                                // $previousProductName = null; // 이전 주문번호를 저장
                                // $toggle = true; // 색상을 변경하기 위한 토글 변수

                                if(isset($listResult)){
                                    foreach ($listResult as $index => $row) {

                                        $combIx = $row['combIx'];
                                        $mpIx = $row['mpIx'];
                                        $market_name = $row['market_name'];
                                        $name = $row['name'];
                                        $combination_key = $row['combination_key'];
                                        $cost = $row['cost_price'];
                                        $stock = $row['stock'];
                                        $price = $row['price'];

                                        // $currentProductName = $name;
                                        // if ($currentProductName !== $previousProductName) {
                                        //     // 상품명이 바뀐다.
                                        //     $toggle = !$toggle;
                                        //     $shipping = $orderRow['total_shipping'];
                                        // }else{
                                        //     $shipping = 0;
                                        // }
                                        // $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                                        // $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신


                                ?>        
                                <tr>
                                    <td><?=htmlspecialchars($market_name)?></td>
                                    <td><?=htmlspecialchars($name)?></td>
                                    <td><?=htmlspecialchars($combination_key)?></td>
                                    <td><?=htmlspecialchars(number_format($price))."원"?></td>
                                    <td><?=htmlspecialchars(number_format($cost))."원"?></td>
                                    <td><?=htmlspecialchars(number_format($stock))?></td>
                                </tr>
                                
                            <?php
                                }}
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                </div>
                <div class="modal-body">
                    <form action="./api/product_api.php" id="productExcelForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="addCount" value="100">
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
                    <button type="button" class="btn btn-primary" onclick="dumpExcel()">등록</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title fs-5" id="loadingText">파일이 등록중입니다. 창을 닫지 마세요.</p>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="display:none;">닫기</button>
                </div>
                </div>
            </div>
        </div>

        <form action="./product-add-dump.php" id="listForm" method="post" style="display:none;">
            <input type="hidden" name="startTime">
            <input type="hidden" name="endTime">
        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="./js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="./js/product.js"></script>
    <script src="./js/common.js"></script>
    
    
    <script>



        function dumpExcel(){
            // $("#productExcelForm").submit();
            modalClose("productModal");
            modalOpen("loadingModal");

            var formData = new FormData($("#productExcelForm")[0]); 
            $.ajax({
                url: './api/product_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) { 
                    console.log(response);
                    if(response.status=="success"){
                        listUp(response.startTime,response.endTime);
                        $("#loadingText").text("파일등록이 완료되었습니다.");
                        // $("#loadingModal .modal-body").css("display","none");
                        $("#loadingModal .btn").css("display","block");
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

        function listUp(startTime,endTime){
            $("input[name='startTime']").val(startTime);
            $("input[name='endTime']").val(endTime);
            $("#listForm").submit();
        }

        $("#excel-btn").click(function(){
            modalOpen("productModal");
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

       
    </script>
</body>
</html>
