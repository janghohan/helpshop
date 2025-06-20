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
        @media (min-width: 1400px) {
            .container{
                max-width: 98%;
                font-size: 14px;
            }
        }
        .search-options{
            padding: 20px;
            padding-left: 0;
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


        /* 초이스 모달 */
        .choice-btn{
            height: 100px;
            width: 200px;
            font-size: 18px;
            font-weight: bold;
        }
        .choice-btn img{
            border-radius: 5px;
            margin-bottom: 3px;
        }
        

    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    
    $today = date("Y-m-d");
    $page = $_GET['page'] ?? 1;
    
    $orderResult = [];
    $orderTypeSearchKeyworSql = "";
    $searchParams = [];

    $itemsPerPage =  20;
    $startIndex = ($page - 1) * $itemsPerPage;

    $startDate = $_GET['start'] ?? date("y-m-d");
    $endDate = $_GET['end'] ?? date("y-m-d");

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

        $orderQuery = "
            SELECT o.order_date, o.global_order_number, o.order_number, m.market_name, m.market_icon, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? AND od.status='completed'
            $orderTypeSearchKeyworSql
        ";

        $orderStmt = $conn->prepare($orderQuery);
        $bindParams = array_merge([$userIx], $searchParams);
        $orderStmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);

    }else{     
        $orderQuery = "
            SELECT o.order_date, o.global_order_number, o.order_number, m.market_name, m.market_icon, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date >= ?
            AND o.order_date <= ? AND od.status='completed' ORDER BY o.order_time DESC LIMIT ? OFFSET ?
        ";
        $orderStmt = $conn->prepare($orderQuery);
        $orderStmt->bind_param("sssss",$userIx,$startDate,$endDate,$itemsPerPage,$startIndex);


        $totalQuery = "
            SELECT o.order_date, o.global_order_number, o.order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date >= ?
            AND o.order_date <= ? AND od.status='completed'
        ";
        $totalStmt = $conn->prepare($totalQuery);
        $totalStmt->bind_param("sss",$userIx,$startDate,$endDate);
    }   

    // Execute and Fetch Results
    $orderStmt->execute();
    $result = $orderStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderResult[] = $row;
        }
    }

    if(!isset($_GET['searchKeyword'])){
        $totalStmt->execute();
        $totalItems = $totalStmt->get_result()->num_rows;
        $totalPages = ceil($totalItems / $itemsPerPage); //전체페이지

        // 페이지 링크 범위 설정 (예: 현재 페이지를 기준으로 ±2개의 링크 표시)
        $visibleRange = 2;
        $startPage = max(1, $page - $visibleRange);
        $endPage = min($totalPages, $page + $visibleRange);

        // 이전/다음 페이지 계산
        $hasPrev = $page > 1;
        $hasNext = $page < $totalPages;
        $prevPage = $hasPrev ? $page - 1 : null;
        $nextPage = $hasNext ? $page + 1 : null;
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
                        <div class="d-flex justify-content-between mb-3">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="flatpickr" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <a href="./order-history.php" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-clock-history"></i>업로드 기록</a>
                                <div class="d-grid position-relative">
                                    <button class="btn btn-primary w-100" id="excel-btn">주문 엑셀 등록</button>
                                    <span class="position-absolute" style="cursor:pointer; font-size: 14px; text-align: right;color: #0069d9; right: 0; bottom: -23px;" id="merge-btn"><a>쿠팡파일 병합하기</a></span>
                                </div>
                             </div>
                            <!-- <div class="col-md-2 mb-3">
                                <button class="btn btn-primary w-100" id="excel-btn">주문 엑셀 등록</button>
                            </div> -->
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
                                    <option value="order_number">주문번호</option>
                                    <option value="name">상품명</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="주문번호 검색" id="search-input">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="search-btn">조회하기</button>
                            </div>
                        </div>
                    </div>
                    <!-- Order Table -->
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle mt-5 mb-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        선택한 상품 일괄적용
                    </button>
                    <button class="btn btn-sm btn-outline-secondary mt-5 mb-3" type="button" onclick="location.href='./order-cancel.php'">
                        취소내역
                    </button>
                    <div class="table-container" style="caret-color: transparent;">
                        <div class="dropdown float-end mb-3">
                            
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" id="orderCancel">주문취소</a>
                                </li>
                            </ul>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>주문</th>
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

                                            $currentOrderNumber = $orderRow['order_number'];
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
                                        <td>
                                            <img style="width:20px;" src="./img/icon/<?=htmlspecialchars($orderRow['market_icon'])?>" alt="아이콘">
                                        </td>
                                        <td><?=htmlspecialchars($orderRow['order_date'])?></td>
                                        <td><?=htmlspecialchars($orderRow['order_number'])?></td>
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
                    <!-- 페이지네이션 -->
                    <?php 
                    if(!isset($_GET['searchKeyword'])){
                    ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php if($hasPrev): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $prevPage ?>&start=<?= $startDate ?>&end=<?= $endDate ?>">Previous</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <?php endif; ?>

                            <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <li class="page-item active"><a class="page-link" href="#"><?= $i ?></a></li>
                                <?php else: ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $i ?>&start=<?= $startDate ?>&end=<?= $endDate ?>"><?= $i ?></a></li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($hasNext): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&start=<?= $startDate ?>&end=<?= $endDate ?>">Next</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="modal fade" id="choiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex justify-content-evenly">
                            <button class="btn btn-primary choice-btn" id="realtimeBtn">실시간 주문</button>
                            <button class="btn btn-warning choice-btn" id="exBtn">이전 주문</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="choiceModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex justify-content-evenly">
                            <button class="btn choice-btn" id="naverBtn">
                                <img src="./img/icon/naver1.png" alt="" style="width: 100px;">
                                <p class="mb-0">네이버</p>
                            </button>
                            <button class="btn choice-btn" id="coupangBtn">
                                <img src="./img/icon/coupang1.png" alt="" style="width: 100px;">
                                <p class="mb-0">쿠팡</p>
                            </button>
                            <button class="btn choice-btn" id="rocketBtn">
                                <img src="./img/icon/rocket1.png" alt="" style="width: 100px;">
                                <p class="mb-0">로켓그로스</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="realTimeExcelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록 (오늘 주문파일)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./order-tmp-list.php" id="realOrderExcelForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="fileType" value="realtime">
                        <select name="orderMarketIx" class="form-control" id="">
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
                        <input type="file" name="orderExcelFile" class="form-control mt-3"  accept=".xlsx, .xls">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="realTmpExcel()">등록</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exExcelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록 (이전 주문파일)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./order-tmp-list.php" id="exOrderExcelForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="fileType" value="ex">
                        <select name="orderMarketIx" class="form-control" id="">
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
                        <input type="file" name="orderExcelFile" class="form-control mt-3"  accept=".xlsx, .xls">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="exTmpExcel()">등록</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mergeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <p class="mb-0" style="color:#ff0000;">병합할 파일을 전부 선택해주세요.</p>
                        <p style="color:#ff0000;">병합된 파일은 바로 다운로드됩니다.</p>
                        <h6>쿠팡 이전 매출 파일(정산현황)</h6>
                        <form action="./api/merge_excel_api.php" class="d-flex justify-content-between" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="coupang">
                            <input type="file" name="excel_files[]" multiple>
                            <button class="btn btn-primary" type="submit">병합하기</button>
                        </form>
                    </div>
                    <div class="modal-body p-4">
                        <h6>로켓 그로스 파일(입출고/배송비 리포트)</h6>
                        <form action="./api/merge_excel_api.php" class="d-flex justify-content-between" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="growthShip">
                            <input type="file" name="excel_files[]" multiple>
                            <button class="btn btn-primary" type="submit">병합하기</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
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

        // 엑셀 등록 버튼 
        $("#excel-btn").click(function(){
            modalOpen("choiceModal");
        });


        $("#realtimeBtn").click(function(){
            modalClose("choiceModal");
            modalOpen("realTimeExcelModal");
        });
        $("#exBtn").click(function(){
            modalClose("choiceModal");
            modalOpen("exExcelModal");
        });

        function realTmpExcel(){
            $("#realOrderExcelForm").submit();
        }

        function exTmpExcel(){
            $("#exOrderExcelForm").submit();
        }
       
        $("#search-btn").click(function(){
            searchOrderList();
        });

        function searchOrderList(){
            if($("#search-input").val()==''){
                location.href = './order.php?start='+startDate+"&end="+endDate;
            }else{
                const searchType = $("#order-filter option:selected").val();
                location.href = './order.php?searchType='+searchType+'&searchKeyword='+$("#search-input").val();
            }
        }

        $(document).on("click", "#orderCancel", function (event) {
            event.preventDefault(); // 기본 동작(링크 이동) 막기
            orderSwal();
        });

        function orderSwal(){          
            Swal.fire({
                html: `
                    <div style="font-size: 16px; text-align: left;">
                        <strong>주문을 취소하시겠습니까? </strong><br><br>
                        
                    </div>
                `,
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-danger"
                },
                showCancelButton: true,
                confirmButtonText: "확인",
                cancelButtonText: "취소", 
                reverseButtons: true,
                allowOutsideClick:false,
            }).then((result) => {
                if (result.isConfirmed) {
                    orderCancel();
                }
            });

        }

        function orderCancel(){
            let checkedValues = [];

            $("input[name='orderCheck[]']:checked").each(function () {
                checkedValues.push($(this).val());
            });

            $.ajax({
                url: './api/order_edit_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'orderCancel', 'checkList':checkedValues },
                success: function(response) { 
                    console.log(response);
                    if(response.status=='success'){
                        location.reload();
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });

            
        }

        // 엑셀 등록 버튼 
        $("#merge-btn").click(function(){
            modalOpen("mergeModal");
        });
        
    </script>
</body>
</html>
