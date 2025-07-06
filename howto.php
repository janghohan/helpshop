<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>사용방법</title>
    <style>

        .explain{
            font-size: 14px;
            display: grid;
            color: #2C3E50;
            font-weight: 300;
            margin-bottom:20px;
        }
        .card h5{
            font-size:18px;
        }
        .postCard{
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    // $page = $_GET['page'] ?? 1;
    // $itemsPerPage =  $_GET['itemsPerPage'] ?? 5;

    $dbQuery = "SELECT * FROM board WHERE type='guide' ORDER BY created_at DESC";

    $dbStmt = $conn->prepare($dbQuery);
    $dbStmt->execute();
    $result = $dbStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listResult[] = $row;
        }
    }
    // $totalItems = $result->num_rows;
    // $totalPages = ceil($totalItems / $itemsPerPage); //전체페이지

    // // 페이지 링크 범위 설정 (예: 현재 페이지를 기준으로 ±2개의 링크 표시)
    // $visibleRange = 2;
    // $startPage = max(1, $page - $visibleRange);
    // $endPage = min($totalPages, $page + $visibleRange);

    // // 이전/다음 페이지 계산
    // $hasPrev = $page > 1;
    // $hasNext = $page < $totalPages;
    // $prevPage = $hasPrev ? $page - 1 : null;
    // $nextPage = $hasNext ? $page + 1 : null;

    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container">
            <div class="main-content">
                <div class="container ps-2 pe-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="fw-bold">헬프샵 이용법</h2>
                    </div>
                    <div class="explain">
                        <span>* 헬프샵 서비스를 이용해주셔서 감사합니다.</span>
                        <span>* 원활한 서비스 사용을 위해 한번 정독해주시기 바랍니다.</span>
                    </div>

                    <?php
                    if ($result->num_rows > 0) {
                        foreach($listResult as $listRow) {                    
                    ?>
                    <div class="card shadow-sm mb-3 postCard" v-for="post in posts" data-v="<?=htmlspecialchars($listRow['ix'])?>">
                        <div class="card-body">
                            <h5 class="card-title mb-2"><?=htmlspecialchars($listRow['title'])?></h5>
                        </div>
                    </div>

                    <?php }}?>

                    <!-- 페이징 -->
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- <?php if($hasPrev): ?>
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
                            <?php endif; ?> -->
                        </ul>
                    </nav>
                </div>
            
            </div>
        </div>
    </div>
</body>
<script>
    $(".postCard").click(function(){
        location.href='./board_view.php?guide='+$(this).attr('data-v');
    });
</script>
</html>
