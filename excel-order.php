<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리 시스템</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/excel-order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./js/common.js"></script>

</head>
<body>
    <?php
    include './header.php';
    include './sidebar.html';

    ?>
    <div class="full-content">
        <!-- 주문 파일 등록 -->
        <div class="section">
            <h2>주문 파일 등록</h2>
            <form action="" method="post" id="file_to_deliver">
                <input type="hidden" name="type" value="deliver">
                <div class="upload-group">
                    <label for="file-naver">네이버:</label>
                    <input type="file" name="fileNaver" id="file-naver" accept=".xlsx, .xls">
                </div>
                <div class="upload-group">
                    <label for="file-coupang">쿠팡:</label>
                    <input type="file" name="fileCoupang" id="file-coupang" accept=".xlsx, .xls">
                </div>
                <div class="select-group">
                    <label for="delivery-company">택배사 선택:</label>
                    <select id="delivery-company" name="deliveryCompany">
                        <option value="cj">CJ대한통운</option>
                        <option value="lotte">롯데택배</option>
                        <option value="hanjin">한진택배</option>
                    </select>
                </div>
            </form>
            <button class="convert-button" id="taekbaeBtn">변환</button>
            <div class="generated-files">
                <h3>생성된 파일</h3>
                <ul id="downloadLink1">
                    <li><a href="#">주문파일_1.xlsx</a></li>
                    <li><a href="#">주문파일_2.xlsx</a></li>
                </ul>
            </div>
        </div>

        <!-- 송장 파일 등록 -->
        <div class="section">
            <h2>송장 파일 등록</h2>
            <div class="upload-group">
                <label for="invoice-naver">네이버:</label>
                <input type="file" id="invoice-naver">
            </div>
            <div class="upload-group">
                <label for="invoice-coupang">쿠팡:</label>
                <input type="file" id="invoice-coupang">
            </div>
            <div class="upload-group">
                <label for="invoice-auction">옥션:</label>
                <input type="file" id="invoice-auction">
            </div>
            <div class="upload-group">
                <label for="invoice-gmarket">G마켓:</label>
                <input type="file" id="invoice-gmarket">
            </div>
            <button class="convert-button">변환</button>
            <div class="generated-files">
                <h3>생성된 파일</h3>
                <ul>
                    <li><a href="#">송장파일_1.xlsx</a></li>
                    <li><a href="#">송장파일_2.xlsx</a></li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        $("#taekbaeBtn").click(function(){
            let formData = new FormData($('#file_to_deliver')[0]);

            console.log(formData,"formDAta");
            $.ajax({
                url: './api/excel_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    let files = JSON.parse(response);
                    console.log(response);
                    $("#downloadLink1").empty();
                    
                    if (files.length > 0) {
                        files.forEach(file => {
                            let link = $('<a></a>')
                                .attr('href', file.url)
                                .attr('download', file.name)
                                .text(`Download ${file.name}`);
                            $('#downloadLink1').append(link).append('<br>');
                        });
                    } else {
                        $('#downloadLinks').text('No files returned.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('전송 실패: ' + error);
                }
            });
        });
    </script>
</body>
</html>