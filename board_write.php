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
        #fileList{
            font-size:14px;
        }
    </style>
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
                <form class="card shadow p-4 bg-white" method="post" action="./api/save_board_api.php">
                    <h5 class="mb-4">게시글 작성</h5>
                    <div class="mb-3">
                        <label for="title" class="form-label">제목</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">내용</label>
                        <textarea class="form-control" id="content" rows="5" name="content"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">이미지 첨부</label>
                        <input class="form-control" type="file" id="images" name="images[]" multiple>
                        <ul class="list-group list-group-flush mt-2" id="fileList"></ul>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">비밀번호</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="isPrivate" name="isPrivate">
                        <label class="form-check-label" for="isPrivate">비공개 글로 작성</label>
                    </div>
                    <button type="submit" class="btn btn-primary">등록</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('images').addEventListener('change', function () {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';
        var num = 1;
        Array.from(this.files).forEach(file => {
            const li = document.createElement('li');
            li.classList.add('list-group-item');
            li.textContent = num+". "+file.name;
            fileList.appendChild(li);
            num ++;
        });
    });

    
    
</script>
</html>
