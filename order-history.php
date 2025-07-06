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
    
    $today = date("Y-m-d");
   
    $historyQuery = "SELECT DATE(o.created_at) AS created_date, m.market_name, m.market_icon FROM orders o JOIN market m ON m.ix = o.market_ix WHERE o.user_ix = ? 
    GROUP BY DATE(o.created_at), o.market_ix ORDER BY created_date DESC";
    $historyStmt = $conn->prepare($historyQuery);
    $historyStmt->bind_param("s",$userIx);

    // Execute and Fetch Results
    $historyStmt->execute();
    $result = $historyStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $historyResult[] = $row;
        }
    }

    ?>
    <!-- 헤더 -->

  

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>주문 등록 내역</h2>
                <div class="container mt-4">
                    <div class="table-container" style="caret-color: transparent;">
                        <table class="table">
                            <colgroup>
                                <col style="width: 10%;">
                                <col style="width: 20%;">
                                <col style="width: 70%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>판매처</th>
                                    <th>등록날짜</th>
                                    <th class="text-center">내역보기</th>
                                </tr>
                            </thead>
                            <tbody id="history-list">
                            <!-- Order rows will be added dynamically -->
                                
                            </tbody>
                        </table>
                    </div>
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
    
    
    <script>
        var page = 1;
        let loading = false;
        let endReached = false;

        function loadMore() {
            if (loading || endReached) return;
            loading = true;
            $('#loading').show();
            $.ajax({
                url: './api/order_history_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'page':page},
                success: function(response) {
                    if(response.trim()===""){
                        endReached = true;
                    }else{
                        $('#history-list').append(response);
                        page ++;
                    }

                    loading = false;

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

        $(window).on('scroll', function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                loadMore();
                console.log("scroll");
            }
        });

        $(document).ready(function() {
            loadMore(); // 첫 페이지 로드
        });

        
    </script>
</body>
</html>
