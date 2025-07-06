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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src='./js/common.js' ></script>
    <title>에디터</title>
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
                <form id="postForm" method="POST" enctype="multipart/form-data" action="./api/save_guide.php">
                    <input type="text" name="title" placeholder="제목" style="width:100%;padding:10px;margin-bottom:10px;">
                    <textarea name="contents" id="editor"></textarea>
                    <br>
                    <button type="submit">저장하기</button>
                </form>

            </div>
        </div>
    </div>
</body>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: './api/upload_image.php' // 이미지 업로드할 서버 경로
            },
            language: 'ko'
        })
        .then(editor => {
            // 커스텀 업로드 어댑터 등록
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        })
        .catch(error => {
            console.error(error);
    });
    
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);

                    fetch('./api/upload_image.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(json => {
                        console.log("서버에서 받은 JSON:", json); // ✅ 여기서 서버 응답 출력

                        if (json.url) {
                            resolve({ default: json.url });
                        } else {
                            reject('업로드 실패: ' + (json.error?.message || '알 수 없는 오류'));
                        }
                    })
                    .catch(error => {
                        reject('업로드 실패: ' + error.message);
                    });
                }));
        }

        abort() {
            // 업로드 취소 기능
        }
    }
</script>
</html>
