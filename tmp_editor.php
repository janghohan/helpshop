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
    <script src="https://cdn.tiny.cloud/1/8oeic3nlxssyeu9vojq0ifeh9ixyd3lzad585dmfadmjsa1m/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src='./js/common.js' ></script>
    <title>계산기</title>
    <style>
        #fileList{
            font-size:14px;
        }
    </style>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'image code',
            toolbar: 'undo redo | link image | code',
            images_upload_url: './api/upload_image.php',
            automatic_uploads: true,
            language: 'ko', // 한국어 설정
        automatic_uploads: true,
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', './api/upload_image.php');

            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
        });
    </script>
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
                <form action="save_post.php" method="POST">
                    <textarea id="content" name="content" rows="15"></textarea>
                    <br>
                    <button type="submit">저장하기</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>

    
</script>
</html>
