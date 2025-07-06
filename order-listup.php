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
            width: 45%;
            height: 100px;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    
    $date = $_GET['date'] ?? date("Y-m-d");
    $page = $_GET['page'] ?? 1;
    
    $orderResult = [];

    $itemsPerPage =  20;
    $startIndex = ($page - 1) * $itemsPerPage;

    $orderQuery = "
            SELECT o.order_date, o.global_order_number, o.order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee, m.market_icon
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? AND DATE(o.created_at) = ? AND od.status='completed' ORDER BY o.order_time DESC LIMIT ? OFFSET ?
        ";
    $orderStmt = $conn->prepare($orderQuery);
    $orderStmt->bind_param("ssss",$userIx,$date,$itemsPerPage,$startIndex);


    $totalQuery = "
        SELECT o.order_date, o.global_order_number, o.order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
        od.cost, m.basic_fee, m.linked_fee, m.ship_fee
        FROM orders o
        JOIN order_details od ON o.ix = od.orders_ix
        JOIN market m ON m.ix = o.market_ix
        WHERE o.user_ix = ? AND DATE(o.created_at) = ? AND od.status='completed'
    ";
    $totalStmt = $conn->prepare($totalQuery);
    $totalStmt->bind_param("ss",$userIx,$date);

    // Execute and Fetch Results
    $orderStmt->execute();
    $result = $orderStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderResult[] = $row;
        }
    }

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
    
    ?>
    <!-- 헤더 -->

  

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>주문 조회 <span>(<?=$date?>)</span> </h2>
                <div class="container mt-4">
                    <!-- Search Options -->
                        
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
                                            <img src="./img/icon/<?=htmlspecialchars($orderRow['market_icon'])?>" style="width: 20px;" alt="아이콘">
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
                                <li class="page-item"><a class="page-link" href="?page=<?= $prevPage ?>&date=<?= $date ?>">Previous</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <?php endif; ?>

                            <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <li class="page-item active"><a class="page-link" href="#"><?= $i ?></a></li>
                                <?php else: ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $i ?>&date=<?= $date ?>"><?= $i ?></a></li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($hasNext): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&date=<?= $date ?>">Next</a></li>
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
                        <p>병합할 파일을 전부 선택해주세요. 파일은 바로 다운로드됩니다.</p>
                        <form action="./api/merge_excel_api.php" class="d-flex justify-content-between" method="post" enctype="multipart/form-data">
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
</body>
</html>
