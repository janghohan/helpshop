<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리 시스템</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/excel-order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
       

        .syncBtn {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #2f3b7e;
            color: white;
            padding: 10px 20px;
        }
        .sync-button {
            padding: 10px 20px;
            background: #7cc576;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .syncBtn .sync-button:hover {
            background: #003f82;
        }

        .data-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-y: auto;
            padding-bottom: 60px;
        }
        .data-row {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }
        .source {
            width: 10%;
            color: #555;
            font-weight: bold;
        }
        .input-container {
            width: 40%;
            padding: 0 10px;
            position: relative;
        }
        .input-container input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .checkbox-container {
            width: 10%;
            display: flex;
            justify-content: center;
        }
        .checkbox-container input {
            transform: scale(1.5);
        }


        /* 검색화면 보이기 */
        .autocomplete-results {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            width: calc(100% - 20px);
            background-color: white;
            top: 100%;  
            left: 10px;
            z-index: 100;
        }

        .result-item {
            padding: 8px;
            cursor: pointer;
        }

        .result-item:hover {
            background-color: #f1f1f1;
        }

    </style>
</head>
<body>
    <?php
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    
    $userIx = isset($_SESSION['user_ix']) ? : '1';

    $page = $_GET['page'] ?? 1;
    $itemsPerPage =  $_GET['itemsPerPage'] ?? 50;

    if(!isset($_SESSION['db_matchingPage_'.$userIx])){
        $totalStmt = "SELECT COUNT(*) as total FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix LEFT JOIN db_match dm ON od.name = dm.name_of_excel
            WHERE o.user_ix = ? AND od.status='completed' AND dm.name_of_excel IS NULL GROUP BY od.name";
        $totalStmt = $conn->prepare($totalStmt);
        $totalStmt->bind_param("s",$userIx);
        $totalStmt->execute();
        $totalItems = $totalStmt->get_result()->num_rows;
        $totalPages = ceil($totalItems / $itemsPerPage); //전체페이지

        $_SESSION['db_matchingPage_'.$userIx] = $totalPages;
    }else{
        $totalPages = $_SESSION['db_matchingPage_'.$userIx];
    }

    $startIndex = ($page - 1) * $itemsPerPage;

    $listStmt = $conn->prepare("SELECT o.order_date, o.global_order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix LEFT JOIN db_match dm ON od.name = dm.name_of_excel
            WHERE o.user_ix = ? AND od.status='completed' AND dm.name_of_excel IS NULL GROUP BY od.name LIMIT ? OFFSET ?");

    $listStmt->bind_param("sss",$userIx,$itemsPerPage,$startIndex);

    // Execute and Fetch Results
    $listStmt->execute();
    $result = $listStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listResult[] = $row;
        }
    }


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
    <div class="full-content">
        <div class="container">
            <div class="main-content">
                <div class="syncBtn d-flex justify-content-between">
                    <span>
                        도움말
                        <button class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                            </svg>
                        </button>
                    </span>
                </div>
            <div class="data-list">
                <!-- Example Row -->
                <div class="data-row" style="font-weight:bold;">
                    <div class="source">판매처</div>
                    <div class="input-container">상품명</div>
                    <div class="input-container">
                        매칭 상품
                    </div>
                    <div class="input-container" style="width:120px;">
                        원가(원)
                    </div>
                    <div class="checkbox-container">
                    </div>
                </div>
                <?php

                if ($result->num_rows > 0) {
                    foreach($listResult as $listRow) {

                    ?>  
                        <div class="data-row" data-ix="<?=htmlspecialchars($listRow['detailIx'])?>">
                            <div class="source"><?=htmlspecialchars($listRow['market_name'])?></div>
                            <div class="input-container"><?=htmlspecialchars($listRow['name'])?></div>
                            <input type="hidden" class="orderName" value="<?=htmlspecialchars($listRow['name'])?>">
                            <div class="input-container">
                                <input type="text" placeholder="값 입력" class="matchingText">
                                <div class="autocomplete-results"></div>
                            </div>
                            <div class="input-container" style="width:120px;">
                                <input type="text" class="form-control localeNumber matchingCost">
                            </div>
                            <div class="checkbox-container">
                                <!-- <input type="checkbox" class="matchingCheckbox" name="matchingCheckbox[]"> -->
                                <button class="btn sync-button">동기화</button>
                            </div>
                        </div>
                    
                    <?php } 
                    ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-3">
                            <?php if($hasPrev): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $prevPage ?>">Previous</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <?php endif; ?>

                            <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <li class="page-item active"><a class="page-link" href="#"><?= $i ?></a></li>
                                <?php else: ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($hasNext): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $nextPage?>">Next</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    
                    <?php }else{?>
                        <p class="text-center pt-5">주문을 등록해주세요.</p>
                    <?php } ?>
                <!-- Example Row -->
                
                <!-- More rows dynamically loaded here -->
            </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/common.js"></script>

    <script>

        //매칭할 상품 찾기 
        $(document).on('input', '.matchingText', function() {
            var query = $(this).val();
            var thisInput = $(this);
            if (query.length >= 2) {  // 두 글자 이상 입력 시 자동완성 시작
                $.ajax({
                    url: './api/search_api.php',  // 이 부분은 서버에서 검색 결과를 받을 PHP 파일 경로로 수정
                    method: 'POST',
                    dataType : 'json',
                    data: { searchKeyword: query, searchType :'matching' },
                    success: function(response) {
                        console.log(response);
                        // $(".autocomplete-results").show();
                        var resultsContainer = thisInput.siblings('.autocomplete-results');
                        resultsContainer.empty();  // 기존 결과 초기화

                        
                        if (response.length > 0) {
                            resultsContainer.show();
                            response.forEach(item => {
                                resultsContainer.append('<div class="result-item" data-v='+item.cost+'>' + item.matching_name+'</div>');
                            });
                        } else {
                            resultsContainer.hide();
                        }
                    }
                });
            } else {
                $('#autocomplete-results').hide();  // 입력이 두 글자 미만이면 결과 숨김
            }
        });


        // 검색된 항목 클릭 시 검색어로 채우기
        $(document).on('click', '.result-item', function() {
            var selectedValue = $(this).text();
            var selectedCost = $(this).attr('data-v');
            console.log(selectedCost);
            $(this).parent().parent().find(".matchingText").val(selectedValue);
            $(this).parent().parent().parent().find(".matchingCost").val(selectedCost);
            $(this).parent().hide();
        });

        $(document).on("click", function (e) {
            if (!$(e.target).closest(".autocomplete-results").length) {
                $(".autocomplete-results").hide();
            }
        });

        $(".allCheckbox").click(function(){
            $(".matchingCheckbox").trigger('click');
        });


        $(".sync-button").click(function(){
            const db = $(this).parent().parent(); // 요소를 한번만 저장

            const orderName = $(db).find('.orderName').val();
            const odIx = $(db).attr('data-ix');
            const matchingName = $(db).find(".matchingText").val();
            const cost = $(db).find('.matchingCost').val();


            if(matchingName=="" || cost==""){
                basicSwal("빈칸을 채워주세요.",true);
                return false;
            }
            
            $.ajax({
                url: './api/matching_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType: 'json',
                data: {'detailIx':odIx, 'matchingName' : matchingName, 'cost':cost, 'orderName' : orderName},
                success: function(response) { 
                    console.log(response);
                    if(response.status=='success'){
                        $(db).css('position', 'relative');

                        // 애니메이션 실행
                        $(db).animate({
                            right: '-100%', // 오른쪽으로 100%만큼 이동
                            opacity: 0 // 투명도 0으로 만들어서 사라지게
                        }, 500, function() {
                            $(db).remove(); // 애니메이션 끝나면 요소 제거
                        });
                    }

                
                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });

            // 부모 요소에 위치 속성 추가 (position: relative)
            

        });
    </script>

</body>
</html>
