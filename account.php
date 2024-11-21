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
            <h2>거래서 조회</h2>
            <div class="row">
                <div class="col-5">
                    <input type="text" class="col-5 form-control">
                </div>
                <button class="btn btn-primary col-2">조회</button>
            </div>
        </div>
    
        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <div class="row mb-3">
                <h2 class="col-10">거래처 관리</h2>
                <button class="btn btn-primary col-2">+ 거래처 등록</button>
            </div>
            <div class="account-list">
                <!-- 상품 아이템 -->
                <div class="account-item">
                    <div class="account-info col-10">
                        <h3>NS</h3>
                        <table class="table">
                            <thead>
                                <th>대표번호</th>
                                <th>담당자</th>
                                <th>담당자 번호</th>
                                <th>홈페이지</th>
                                <th>주소</th>
                            </thead>
                            <tbody>
                                <td>032-554-5541</td>
                                <td>정석현</td>
                                <td>010-5544-5412</td>
                                <td>
                                    <a href="">http://dlasdf.com</a>
                                </td>
                                <td>
                                    전남 순천시 우석로 56 낚시타운
                                </td>
                            </tbody>
                        </table>
                    </div>
                    <div class="account-controls col-2">
                        <button class="btn btn-primary">메모</button>
                        <button class="btn btn-secondary">수정</button>
                    </div>
                </div>
                <div class="account-item">
                    <div class="account-info col-10">
                        <h3>다이나미스</h3>
                        <table class="table">
                            <thead>
                                <th>대표번호</th>
                                <th>담당자</th>
                                <th>담당자 번호</th>
                                <th>홈페이지</th>
                                <th>주소</th>
                            </thead>
                            <tbody>
                                <td>032-554-5541</td>
                                <td>정석현</td>
                                <td>010-5544-5412</td>
                                <td>
                                    <a href="">http://dlasdf.com</a>
                                </td>
                                <td>
                                    전남 순천시 우석로 56 낚시타운
                                </td>
                            </tbody>
                        </table>
                    </div>
                    <div class="account-controls col-2">
                        <button class="btn btn-primary">메모</button>
                        <button class="btn btn-secondary">수정</button>
                    </div>
                </div>
            
            </div>
        </div>
        <!-- 페이지네이션 -->
        <div class="pagination">
            <select>
                <option>10개 보기</option>
            </select>
            <span>1 / 1</span>
        </div>
    </div>

    <script src="script.js"></script>
</body>
<script>
   
</script>
</html>
