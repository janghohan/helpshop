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
    <title>계산기</title>
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

    $dbQuery = "SELECT * FROM board WHERE type='board' ORDER BY created_at DESC";

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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">요청 게시판</h2>
                        <a href="board_write.php" class="btn btn-primary">글쓰기</a>
                    </div>
                    <div class="explain">
                        <span>* 필요한 기능을 요청하는 게시판입니다.</span>
                    </div>
                    <?php
                    if ($result->num_rows > 0) {
                        foreach($listResult as $listRow) {                    
                            if($listRow['is_secret']==0){
                                $secret = "공개";
                            }else{
                                $secret = "비공개";
                            }
                    ?>
                    <div class="card shadow-sm mb-3" v-for="post in posts">
                        <div class="card-body">
                            <h5 class="card-title mb-2"><?=htmlspecialchars($listRow['title'])?></h5>
                            <p class="card-text text-muted small"><?=htmlspecialchars($listRow['created_at'])?> | <?=htmlspecialchars($listRow['author_name'])?> | <?=$secret?></p>
                            <a href="board_view.php?board=<?=htmlspecialchars($listRow['ix'])?>" class="stretched-link"></a>
                        </div>
                    </div>
                    <?php } }?>

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
    
</script>
</html>
