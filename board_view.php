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
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    // include './dbConnect.php';

    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container">
            <div class="main-content">
                <div class="container me-2 ms-2">
                    <div class="card-body">
                        <h5 class="card-title">게시글 제목</h5>
                        <p class="text-muted">작성일: 2025-04-23</p>
                        <div class="mb-4">
                        <p>게시글 내용이 여기에 표시됩니다. 이미지, 텍스트 등 포함됩니다.</p>
                        <img src="/path/to/uploaded/image.jpg" class="img-fluid rounded" alt="첨부 이미지">
                        </div>

                        <!-- 답변 영역 (관리자만 작성) -->
                        <div class="mt-5 p-4 bg-light rounded-4 border">
                        <h5 class="mb-3">관리자 답변</h5>
                        <p>여기에 관리자가 작성한 답변이 표시됩니다. 사용자에게 안내나 회신을 보여주는 영역입니다.</p>
                        <p class="text-muted">답변일: 2025-04-23</p>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                        <a href="board.php" class="btn btn-secondary">목록으로</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    
    
</script>
</html>
