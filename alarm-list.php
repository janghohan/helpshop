<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>내정보 페이지</title>
    <style>
    .stock-alert-list {
        max-width: 860px;
        margin: 20px auto;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .stock-alert-header {
        background-color: #2f3b7e;
        color: white;
        padding: 16px;
        font-size: 18px;
        font-weight: bold;
    }

    .stock-alert-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        border-bottom: 1px solid #eee;
    }

    .stock-alert-item:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 500;
    }

    .item-qty {
        font-size: 14px;
        color: #555;
    }

    .item-status {
        background-color: #dc3545;
        color: white;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        height: 20px;
        position: relative;
        top: 10px;
    }

    .item-name:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    $listStmt = $conn->prepare("SELECT * FROM matching_name mn JOIN stock_alarm sa ON sa.matching_ix = mn.ix WHERE mn.user_ix = ? AND sa.is_resolved = 0");
    $listStmt->bind_param("s",$userIx);
    $listStmt->execute();

    $result = $listStmt->get_result();
    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container mt-5">
            <div class="stock-alert-list">
                <div class="stock-alert-header">재고 부족 상품
                    <span style="float: right; cursor:pointer;" id="all-del">전체삭제</span>
                </div>
                <?php
                if ($result->num_rows > 0) {
                    while ($listRow = $result->fetch_assoc()) {
                        $listResult[] = $listRow;
                    }
                
                foreach($listResult as $listRow){
                ?>
                <div class="stock-alert-item">
                    <div>
                        <div class="item-name">
                            <a class="item-name" href="product-edit.php?ix=<?=htmlspecialchars($listRow['matching_ix'])?>"><?=htmlspecialchars($listRow['matching_name'])?></a>
                        </div>
                        <div class="item-qty">남은 재고: <?=htmlspecialchars($listRow['stock'])?> / 알림 기준: <?=htmlspecialchars($listRow['alarm_stock'])?></div>
                    </div>
                    <div class="d-flex">
                        <div class="item-status">부족</div>
                        <button class="btn alarm-del" data-ix="<?=htmlspecialchars($listRow['ix'])?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php }
                }else {
                ?>
                <span class="p-3 d-flex">* 재고가 부족한 상품이 없습니다.</span>  
                <?php }?>
                </div>
            </div>
        </div>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#logout").click(function(){
        swalConfirm('로그아웃 하시겠습니까?',function() {
            location.href='./api/signout.php';
        }, function(){});
    });

    $(".alarm-del").click(function(){
        btn = $(this).parent().parent();
        alarmIx = $(this).attr('data-ix');
        $.ajax({
            url: './api/alarm_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            dataType: 'json',
            data: {'type':'alarmDel', 'alarmIx':alarmIx },
            success: function(response) { 
                if(response.status=='success'){
                    $(btn).css('position', 'relative');

                    // 애니메이션 실행
                    $(btn).animate({
                        right: '-100%', // 오른쪽으로 100%만큼 이동
                        opacity: 0 // 투명도 0으로 만들어서 사라지게
                    }, 500, function() {
                        $(btn).remove(); // 애니메이션 끝나면 요소 제거
                    });
                }

            },
            error: function(xhr, status, error) {                  
                // alert("관리자에게 문의해주세요.");
                console.log(error);
            }
        });
    });

    $("#all-del").click(function(){
        swalConfirm("전체 알람을 삭제하시겠습니까?", function(){
            allDelete();
        },function(){});
    });
    
    function allDelete(){
        $.ajax({
            url: './api/alarm_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            dataType: 'json',
            data: {'type':'alarmDel', 'alarmIx':'a' },
            success: function(response) { 
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
</script>
</html>
