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
            SELECT o.order_date, o.global_order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date >= ?
            AND o.order_date <= ? AND od.status='completed'
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

        $orderQuery = "SELECT o.order_date,o.global_order_number,m.market_name,od.ix as detailIx, od.name,od.quantity,od.price,o.total_payment,o.total_shipping 
        FROM orders o JOIN order_details od ON o.order_date='$today' AND o.user_ix='$userIx' AND o.ix = od.orders_ix $orderTypeSearchKeyworSql JOIN market m ON m.ix = o.market_ix";
        
        $orderQuery = "
            SELECT o.order_date, o.global_order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date = ? AND od.status='completed'
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
                        <div class="d-flex justify-content-start gap-3">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="flatpickr" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="order-filter">
                                    <option value="광고비">광고비</option>
                                    <option value="자재비">자재비</option>
                                    <option value="택배비">택배비</option>
                                    <option value="매입비용">매입비용</option>
                                    <option value="기타">기타</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="search-btn">조회하기</button>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Filter Options and Table -->
                    <div class="filter-options mb-1 pb-2">
                        <div class="text-end">
                            <button class="btn btn-secondary" id="expenseOpenBtn">지출 내역 등록</button>
                        </div>
                    </div>

                    <!-- Order Table -->
                    <div class="table-container" style="caret-color: transparent;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>지출내역</th>
                                <th>금액</th>
                                <th>메모</th>
                                <th>날짜</th>
                            </tr>
                            </thead>
                            <tbody id="expense-list">
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
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="orderCheck[]" value="<?=htmlspecialchars($orderRow['detailIx'])?>">
                                        </td>
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

        <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">지출내역 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./api/expense_api.php" id="expenseForm" method="post" enctype="multipart/form-data">
                        <input type="text" class="form-control mb-3" name="expenseDate" id="expenseFlatpickr" placeholder="MM/DD/YYYY">
                        <select name="expenseType" class="form-control" id="">
                            <option value="광고비">광고비</option>
                            <option value="자재비">자재비</option>
                            <option value="택배비">택배비</option>
                            <option value="매입비용">매입비용</option>
                            <option value="기타">기타</option>
                        </select>
                        <input type="text" name="expenseMemo" class="form-control mt-3"  placeholder="메모">                       
                        <input type="text" class="localeNumber form-control mt-3" name="expensePrice" placeholder="금액">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="expensAddBtn()">등록</button>
                </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            flatpickr("#expenseFlatpickr", {
                defaultDate: "today",
                dateFormat: "Y-m-d",
                theme: "material_blue",
                locale: "ko",
            });

        });

       
        $("#search-btn").click(function(){
            searchOrderList();
        });

        function changeRageText(dateRange){
			const dates = dateRange.split(' ~ ');
			startDate = dates[0];
			endDate = dates[1];

		}




        // 지출내역
        $("#expenseOpenBtn").click(function(){
            modalOpen("expenseModal");
        });

        //지출 등록
        function expensAddBtn(){
            $.ajax({
                url: './api/expense_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: $("#expenseForm").serialize(),
                success: function(response) { 
                    // console.log(response);
                    if(response.status=='success'){
                        Swal.fire({
                            html: `
                                <div style="font-size: 16px; text-align: left;">
                                    <strong>등록 완료. 계속 하시겠습니까? </strong><br><br>
                                    
                                </div>
                            `,
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-danger"
                            },
                            showCancelButton: true,
                            confirmButtonText: "예",
                            cancelButtonText: "아니오", 
                            reverseButtons: true,
                            allowOutsideClick:false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                
                            }else{
                                modalClose('expenseModal');
                            }
                        });
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

    </script>
</body>
</html>
s