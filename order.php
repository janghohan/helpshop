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
    
    <title>주문 관리</title>
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
    
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $today = date("Y-m-d");

    $startDate = isset($_POST['start']) ? $_POST['start'] : date("Y-m-d");
    $endDate = isset($_POST['end']) ? $_POST['end'] : date("Y-m-d");

    $orderResult = [];
    $orderTypeSearchKeyworSql = "";
    $searchParams = [];

    //검색 영역에 값이 들어오면
    if(isset($_GET['searchKeyword'])){
        $searchKeyword = $_GET['searchKeyword'];
        $searchType = $_GET['searchType'];

        if ($searchType === 'name') {
            $orderTypeSearchKeyworSql = "AND od.name LIKE ?";
            $searchParams[] = '%' . $searchKeyword . '%';
        } else {
            $orderTypeSearchKeyworSql = "AND o." . $searchType . " = ?";
            $searchParams[] = $searchKeyword;
        }
    }

    if (isset($_GET['start']) && isset($_GET['end'])) {
        $startDate = $_GET['start'];
        $endDate = $_GET['end'];
        
        $orderQuery = "
            SELECT o.order_date, o.global_order_number, m.market_name, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date >= ?
            AND o.order_date <= ?
            $orderTypeSearchKeyworSql
        ";
        $orderStmt = $conn->prepare($orderQuery);
        $bindParams = array_merge([$userIx, $startDate, $endDate], $searchParams);
        $orderStmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
    
        // Execute and Fetch Results
        $orderStmt->execute();
        $result = $orderStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orderResult[] = $row;
            }
        }
    }else{

        $orderQuery = "SELECT o.order_date,o.global_order_number,m.market_name,od.name,od.quantity,od.price,o.total_payment,o.total_shipping 
        FROM orders o JOIN order_details od ON o.order_date='$today' AND o.user_ix='$userIx' AND o.ix = od.orders_ix $orderTypeSearchKeyworSql JOIN market m ON m.ix = o.market_ix";
        
        $orderQuery = "
            SELECT o.order_date, o.global_order_number, m.market_name, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date = ?
            $orderTypeSearchKeyworSql";
        
        $orderStmt = $conn->prepare($orderQuery);

        $orderStmt = $conn->prepare($orderQuery);
        $bindParams = array_merge([$userIx, $today], $searchParams);
        $orderStmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
    
        // Execute and Fetch Results
        $orderStmt->execute();
        $result = $orderStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orderResult[] = $row;
            }
        }
    }
    ?>
    <!-- 헤더 -->

  

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>주문 조회</h2>
                <div class="container mt-4">
                    <!-- Search Options -->
                    <div class="search-options">
                        <div class="row justify-content-between ">
                            <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" id="flatpickr" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-primary w-100" id="excel-btn">주문 엑셀 등록</button>
                            </div>
                            <!-- <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">이번 주</button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">지난 주</button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">이번 달</button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">지난 달</button>
                            </div> -->
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <select class="form-select" id="order-filter">
                                    <option value="global_order_number">주문번호</option>
                                    <option value="name">상품명</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="주문번호 검색" id="order-search">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="search-btn">조회하기</button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                        <button class="btn">테스트</button>
                        <div class="card">
                            <h5>총 결제금액(상품)</h5>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalPayment" onclick="calculateMargin()">
                                    <i class="bi bi-arrow-clockwise rotating-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h5>총 결제금액(택배비)</h5>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalShipping">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h5>총 예상순수익</h5>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalProfit">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Options and Table -->
                    <div class="filter-options">
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option>결제상태</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option>전체 주문처</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary w-100">주문제품 펼쳐보기</button>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn btn-custom">엑셀 출력</button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Table -->
                    <div class="table-container">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>판매처</th>
                                <th>주문일시</th>
                                <th>주문번호</th>
                                <th>주문제품</th>
                                <th>수량</th>
                                <th>주문금액</th>
                                <th>택배비</th>
                            </tr>
                            </thead>
                            <tbody id="order-list">
                            <!-- Order rows will be added dynamically -->
                                <?php
                                    $previousOrderNumber = null; // 이전 주문번호를 저장
                                    $toggle = true; // 색상을 변경하기 위한 토글 변수

                                    if(isset($orderResult)){
                                        foreach($orderResult as $orderRow) {

                                            $currentOrderNumber = $orderRow['global_order_number'];
                                            if ($currentOrderNumber !== $previousOrderNumber) {
                                                // 주문번호가 변경될 때마다 토글 값을 변경
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
        </div>
        <div class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./order-tmp-list.php" id="orderExcelForm" method="post" enctype="multipart/form-data">
                        <select name="orderMarketIx" class="form-control" id="">
                            <?php
                                $searchResult = [];
                                
                                $query = "SELECT * FROM market WHERE user_ix='$user_ix'";
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
                        <input type="file" name="orderExcelFile" class="form-control mt-3"  accept=".xlsx, .xls">
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
            $("#orderExcelForm").submit();
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
            console.log("<?=$orderQuery?>");
            $.ajax({
                url: './api/margin_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType : 'json',
                data: {'calculateType':calculateType, 'query':"<?=$orderQuery?>"},
                success: function(response) { 
                    console.log(response);

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        });

        // function calculateMargin(){
        //     calculateType = $(this).attr('data-v');
        //     console.log("<?=$orderQuery?>");
        //     $.ajax({
        //         url: './api/margin_api.php', // 데이터를 처리할 서버 URL
        //         type: 'POST',
        //         dataType : 'json',
        //         data: {'calculateType':calculateType, 'query':"<?=$orderQuery?>"},
        //         success: function(response) { 
        //             console.log(response);

        //         },
        //         error: function(xhr, status, error) {                  
        //             // alert("관리자에게 문의해주세요.");
        //             console.log(error);
        //         }
        //     });
        // }
       
    </script>
</body>
</html>
