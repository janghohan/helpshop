<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>카테고리 선택 페이지</title>
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
            padding: 20px;
        }

        /* 카테고리 선택 섹션 */
        .category-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 960px;
            margin: 0 auto;
            margin-top: 20px;
        }
        .category-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .category-box label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .category-box select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            appearance: none;
            background-color: #f9f9f9;
            cursor: pointer;
        }
        .category-box select:focus {
            outline: none;
            border-color: #274BDB;
        }
        .category-box .required {
            color: #dc3545;
            margin-right: 5px;
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
        <!-- 카테고리 선택 섹션 -->
        <div class="category-section">
            <!-- 카테고리 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>카테고리</label>
                <select>
                    <option value="">선택</option>
                    <!-- 카테고리 옵션 추가 가능 -->
                </select>
            </div>

            <!-- 서브 카테고리 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>서브 카테고리</label>
                <select>
                    <option value="">선택</option>
                    <!-- 서브 카테고리 옵션 추가 가능 -->
                </select>
            </div>
        </div>
    </div>
</body>
</html>
