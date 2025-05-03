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
        .container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    $guideIx = $_GET['guide'] ?? 0;
    $boardIx = $_GET['board'] ?? 0;

    if($guideIx==0 && $boardIx==0){
        echo "<script> alert('존재하지 않는 페이지입니다.'); location.href='./howto.php';</script>";
    }else if(isset($_GET['guide'])){
        $ix = $guideIx;
    }else if(isset($_GET['board'])){
        $ix = $boardIx;
    }


    // 가이드 or 게시판

    $guideStmt = $conn->prepare("SELECT * FROM board WHERE ix=?");
    $guideStmt->bind_param("s",$ix);

    if (!$guideStmt->execute()) {
        throw new Exception("Error executing list statement: " . $guideStmt->error); // *** 수정 ***
    }

    $guideResult = $guideStmt->get_result();

    if ($guideResult->num_rows > 0) {
        while ($guideRow = $guideResult->fetch_assoc()) {
            $boardIx = $guideRow['ix'];
            $title = $guideRow['title'];
            $contents = $guideRow['contents'];
            $createdDate = $guideRow['created_at'];
            $is_replyed = $guideRow['is_replyed'];
        }
        $guideStmt -> close();
    }

    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container">
            <div class="main-content">
                <div class="container me-2 ms-2">
                    <div class="card-body">
                        <h5 class="card-title"><?=htmlspecialchars($title)?></h5>
                        <p class="text-muted">작성일: <?=htmlspecialchars($createdDate)?></p>
                        <div class="mb-4">
                            <?=$contents?>
                        </div>

                        <?php
                        if($is_replyed==1){
                            $replyStmt = $conn->prepare("SELECT * FROM board_reply WHERE board_ix=?");
                            $replyStmt->bind_param("s",$boardIx);

                            if (!$replyStmt->execute()) {
                                throw new Exception("Error executing list statement: " . $replyStmt->error); // *** 수정 ***
                            }

                            $replyResult = $replyStmt->get_result();

                            if ($replyResult->num_rows > 0) {
                                while ($replyRow = $replyResult->fetch_assoc()) {
                                    $replyContents = $replyRow['reply_contents'];
                                    $replyDate = $replyRow['created_at'];
                                }
                                $replyStmt -> close();
                            }
                        ?>
                        <!-- 답변 영역 (관리자만 작성) -->
                        <div class="mt-5 p-4 bg-light rounded-4 border">
                        <h5 class="mb-3">관리자 답변</h5>
                        <?=$replyContents?>
                        <p class="text-muted">답변일: <?=htmlspecialchars($replyDate)?></p>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                        <a href="board.php" class="btn btn-secondary">목록으로</a>
                        </div>

                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    
    
</script>
</html>
