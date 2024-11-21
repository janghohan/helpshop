<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공통 레이아웃</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/account.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/common.js"></script>
</head>
<body>
    <?php include './sidebar.html'?>

    <!-- 헤더 -->
    <div class="header">
        <div class="header-left">
            <img src="logo.png" alt="로고" class="logo">
        </div>
        <div class="header-right">
            <input type="text" placeholder="검색" class="search-bar">
            <button class="ai-chatbot">카페24 AI 챗봇</button>
        </div>
    </div>

    <div class="full-content">
        <div class="main-content">
            <div class="account-info-section">
                <div class="row mb-4">
                    <h2 class="col-md-10">거래처 등록</h2>
                    <button class="btn btn-primary col-md-2">등록</button>
                </div>
                <div class="account-info">
                    <div class="account-fields">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">거래처명</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="거래처 or 소싱 업체">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">담당자</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="담당자 이름을 입력하세요.">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">담당자 연락처</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="담당자 직통 연락처를 입력하세요.">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">대표번호</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="거래처 대표번호를 입력하세요.">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">주소</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="주소를 입력하세요.">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">사이트</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="http://aaa.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">사업자번호</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="staticEmail" placeholder="111-55-44444">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">메모</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
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
