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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <title>주문 관리</title>
    <style>
        @media (min-width: 1400px) {
            .container{
                max-width: 98%;
                font-size: 14px;
            }
        }
        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
        .rotate {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        .search-options, .summary-cards, .filter-options {
            padding: 10px;
        }
        .summary-cards button{
            font-size:18px;
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
                                <button class="btn btn-primary" id="profit-btn">조회하기</button>
                            </div>
                        </div>
                    </div>
                    <!-- Summary Cards -->
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                        <div class="card">
                            <h4>총 결제금액(상품)</h4>
                            <div class="value">
                                <button class="btn btn-lg plus" id="totalPayment">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 결제금액(택배비)</h4>
                            <div class="value">
                                <button class="btn btn-lg plus" id="totalShipping">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>평균 객단가</h4>
                            <div class="value">
                                <button class="btn btn-lg plus" id="avePerPrice">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                        <div class="card">
                            <h4>총 매입내역</h4>
                            <div class="value">
                                <button class="btn btn-lg minus" id="totalCost">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 마켓수수료</h4>
                            <div class="value">
                                <button class="btn btn-lg minus" id="totalCommission">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 지출내역</h4>
                            <div class="value">
                                <button class="btn btn-lg minus" id="totalExpense">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="summary-cards d-flex justify-content-between" id="marginCard">
                     <div class="card">
                            <h4>총 세금액(부가세+소득세)</h4>
                            <div class="value">
                                <button class="btn btn-lg minus" id="totalTax">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>마진율(%)</h4>
                            <div class="value">
                                <button class="btn btn-lg plus" id="marginRate">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <h4>총 예상순수익</h4>
                            <div class="value">
                                <button class="btn btn-lg plus" id="totalProfit">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="main-content">
                <h2>마켓별 지표</h2>
                <!-- 마켓별 수익 분석 테이블 -->
                <div class="container my-5">
                    <div class="table-responsive shadow rounded-4">
                        <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                            <th>마켓명</th>
                            <th>총매출</th>
                            <th>수수료</th>
                            <th>원가</th>
                            <th class="text-success">순수익</th>
                            <th>순이익률</th>
                            </tr>
                        </thead>
                        <tbody id="market-data">
                            <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td class="text-success fw-bold">-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <h2>매출 및 순수익</h2>
                <div class="container my-5">
                    <form class="row g-3 align-items-end mb-4" id="filterForm">
                        <div class="col-md-3">
                            <label class="form-label">시작일</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">종료일</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">보기 방식</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="viewType" id="daily" value="daily" checked>
                                    <label class="form-check-label" for="daily">일별</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="viewType" id="monthly" value="monthly">
                                    <label class="form-check-label" for="monthly">월별</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">그래프 보기</button>
                        </div>
                    </form>

                    <canvas id="revenueChart" height="120"></canvas>
                </div>

                <script>
                    
                    // 폼 제출 시 차트 업데이트
                    document.getElementById('filterForm').addEventListener('submit', function(e) {
                        e.preventDefault();

                        const start = new Date(document.getElementById('startDate').value);
                        const end = new Date(document.getElementById('endDate').value);
                        const viewType = document.querySelector('input[name="viewType"]:checked').value;


                        // 유효성 검사: 일별은 31일 초과 금지
                        if (viewType === 'daily') {
                            const diffDays = (end - start) / (1000 * 60 * 60 * 24);
                            if (diffDays > 30) {
                                alert("일별 보기의 최대 기간은 31일입니다.");
                                return;
                            }
                        }

                        loadRevenueData(formatDateToYMD(start), formatDateToYMD(end), viewType, 'revenueChart');

                        // // 더미 데이터 생성 (서버에서 가져오는 부분 대체)
                        // const labels = [];
                        // const revenueData = [];
                        // const profitData = [];

                        // const formatter = new Intl.NumberFormat('ko-KR');



                        // if (viewType === 'daily') {
                        //     for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                        //         const dateStr = d.toISOString().split('T')[0];
                        //         console.log("dateStr",dateStr);
                        //         labels.push(dateStr);
                        //         revenueData.push(Math.floor(Math.random() * 1000000) + 500000); // 매출
                        //         profitData.push(Math.floor(Math.random() * 400000) + 100000);   // 순수익
                        //     }
                        // } else {
                        //     const startMonth = new Date(start.getFullYear(), start.getMonth(), 1);
                        //     const endMonth = new Date(end.getFullYear(), end.getMonth(), 1);
                        //     for (let m = new Date(startMonth); m <= endMonth; m.setMonth(m.getMonth() + 1)) {
                        //         const monthStr = `${m.getFullYear()}-${String(m.getMonth() + 1).padStart(2, '0')}`;
                        //         console.log("monthStr",monthStr);
                        //         labels.push(monthStr);
                        //         revenueData.push(Math.floor(Math.random() * 30000000) + 10000000); // 월 매출
                        //         profitData.push(Math.floor(Math.random() * 10000000) + 3000000);   // 월 순수익
                        //     }
                        // }

                        // // 차트 업데이트
                        // revenueChart.data.labels = labels;
                        // revenueChart.data.datasets[0].data = revenueData;
                        // revenueChart.data.datasets[1].data = profitData;
                        // revenueChart.update();
                    });

                    function loadRevenueData(startDate, endDate, viewType, targetCanvasId) {
                        $.ajax({
                            url: './api/margin_api.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                'startDate': startDate,
                                'endDate': endDate,
                                'viewType': viewType,
                                'type':'graph'
                            },
                            success: function (res) {
                                const labels = res.map(row => row.period);
                                const revenues = res.map(row => row.total_revenue);
                                const profits = res.map(row => row.total_profit);

                                const ctx = document.getElementById(targetCanvasId).getContext('2d');

                                if (window.revenueChartInstance) {
                                    window.revenueChartInstance.destroy();
                                }

                                window.revenueChartInstance = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [
                                            {
                                                label: '매출',
                                                data: revenues,
                                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                                yAxisID: 'y',
                                            },
                                            {
                                                label: '순수익',
                                                data: profits,
                                                type: 'bar',
                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                yAxisID: 'y',
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            x: {
                                                ticks: {
                                                    autoSkip: false
                                                }
                                            },
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            },
                            error: function () {
                                alert('데이터를 불러오는 데 실패했습니다.');
                            }
                        });
                    }
                </script>
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

        $("#profit-btn").click(function(){  
            console.log(startDate,endDate);
            $(".bi-arrow-clockwise").show();
            $(".bi-arrow-clockwise").addClass('rotate');
            $.ajax({
                url: './api/margin_api.php', // 데이터를 처리할 서버 URL
                dataType:'json',
                type: 'POST',
                data: {'type' : 'basic', 'startDate' : startDate, 'endDate' : endDate, 'searchKeyword':""},
                success: function(response) {
                    console.log(response);
                    $(".bi-arrow-clockwise").hide();
                    $("#totalPayment").text(response.totalPayment);
                    $("#totalShipping").text(response.totalShipping);
                    $("#avePerPrice").text(response.avePerPrice);
                    $("#totalCost").text(response.totalCost);
                    $("#totalCommission").text(response.totalMarketFee);
                    $("#totalExpense").text(response.totalPurchase);
                    $("#totalTax").text(response.totalTax);
                    $("#marginRate").text(response.totalMarginRate);
                    $("#totalProfit").text(response.totalProfit);

                    // console.log(response.marketResult.);
                    if (response.marketResult.length > 0) {
                        $("#market-data").html("");
                        response.marketResult.forEach(item => {
                            $("#market-data").append('<tr><td>'+item.market+'</td><td>'+number_format(item.totalProductRevenue)+'</td><td>'+number_format(item.totalPriceFee)+'</td><td>'+number_format(item.totalProductCost)+'</td><td class="text-success fw-bold">'+number_format(item.totalProfit)+'</td><td>'+item.totalMarginRate+'%</td></tr>');
                            console.log(item.market);
                        });
                    } 
                    
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
