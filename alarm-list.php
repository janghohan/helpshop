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
        max-width: 600px;
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
                <div class="stock-alert-header">재고 부족 상품</div>
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
                    <div class="item-status">부족</div>
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

    
</script>
</html>
