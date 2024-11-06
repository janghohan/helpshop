<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내정보 페이지</title>
    <style>
        /* 기본 스타일 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f5f7;
        }

        /* 헤더 */
        .header {
            background-color: #274BDB;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .menu {
            display: flex;
            gap: 20px;
        }
        .header .menu a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .header .menu a:hover {
            text-decoration: underline;
        }
        .header button {
            background: none;
            border: 1px solid #fff;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        /* 메인 컨테이너 */
        .container {
            display: flex;
            padding: 20px;
        }

        /* 사이드바 */
        .sidebar {
            width: 220px;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-right: 15px;
        }
        .sidebar h2 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 8px;
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }
        .sidebar ul li:hover,
        .sidebar ul li.active {
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        /* 메인 콘텐츠 */
        .main-content {
            flex: 1;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .main-content h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .info-section h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-item label {
            color: #666;
        }
        .info-item button {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .toggle-button {
            background-color: #f8d7da;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- 헤더 -->
    <div class="header">
        <div class="menu">
            <a href="#">마진율 계산기</a>
            <a href="#">엑셀 변환기</a>
            <a href="#">스토어 랭킹</a>
            <a href="#">통합 재고 관리</a>
            <a href="#">통합 판매 분석</a>
            <a href="#">셀툴 이용 가이드</a>
        </div>
        <div>
            <button>내 정보</button>
            <button>로그아웃</button>
        </div>
    </div>

    <!-- 메인 컨테이너 -->
    <div class="container">
        <!-- 사이드바 -->
        <div class="sidebar">
            <h2>내정보</h2>
            <ul>
                <li class="active">기본정보</li>
                <li>마켓등록</li>
                <li>결제내역</li>
            </ul>
        </div>

        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <h2>기본정보</h2>

            <!-- 기본정보 섹션 -->
            <div class="info-section">
                <h3>장호호호</h3>
                <p>ID: wkdgh5430</p>
                <div class="info-item">
                    <label>이름을 등록해 주세요.</label>
                    <button>수정</button>
                </div>
                <div class="info-item">
                    <label>01056135430</label>
                    <button>수정</button>
                </div>
                <div class="info-item">
                    <label>이메일</label>
                    <button>수정</button>
                </div>
                <div class="info-item">
                    <label>비밀번호</label>
                    <button>수정</button>
                </div>
            </div>

            <!-- 마케팅 정보 수신 동의 섹션 -->
            <h3>마케팅 정보수신 동의</h3>
            <div class="info-section">
                <div class="info-item">
                    <label>휴대전화</label>
                    <button class="toggle-button">OFF</button>
                </div>
                <div class="info-item">
                    <label>이메일</label>
                    <button class="toggle-button">OFF</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
