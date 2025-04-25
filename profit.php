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
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    
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
        .summary-cards .card h4 {
            font-size: 18px;
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
                <h2>손익 현황</h2>
                <div class="container mt-4">
                    <!-- Search Options -->
                    <div class="search-options">
                        <div class="row justify-content-start ">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="flatpickr" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="search-btn">조회하기</button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                        <div class="card">
                            <h4>총 결제금액(상품)</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalPayment">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 결제금액(택배비)</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalShipping">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>평균 객단가</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="avePerPrice">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                        <div class="card">
                            <h4>총 매입내역</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalPurchase">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 마켓수수료</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalCommission">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 지출내역</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="incomeRate">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                     <div class="card">
                            <h4>총 세금액(부가세+소득세)</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalDuty">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>적용 소득률(%)</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="incomeRate">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 예상순수익</h4>
                            <div class="value">
                                <button class="btn btn-lg" data-v="totalProfit">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/common.js"></script>
    
    
    <script>
        var startDate = "<?=$startDate?>";
        var endDate = "<?=$endDate?>";

        $(document).ready(function() {
            flatpickr("#flatpickr", {
                defaultDate: ["<?=$startDate?>","<?=$endDate?>"],
                dateFormat: "Y-m-d",
                mode: "range",
                allowInput: true,
                theme: "material_blue",
                locale: "ko",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        startDate = formatDateToYMD(selectedDates[0]);
                        endDate = selectedDates[1] ? formatDateToYMD(selectedDates[1]) : startDate;
                    }

                }   
                
            });

        });

        $(".btn").click(function(){
            $.ajax({
                url: './api/margin_api.php', // 데이터를 처리할 서버 URL
                dataType:'json',
                type: 'POST',
                data: {'startDate' : startDate, 'endDate' : endDate, 'searchKeyword':""},
                success: function(response) {
                    console.log(response);
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
s