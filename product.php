<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <title>상품 관리</title>
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
            background-color: #2f3b7e;
            padding: 10px 20px;
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
        }
        .header .menu a:hover {
            text-decoration: underline;
        }

        /* 메인 컨테이너 */
        .container {
            display: flex;
            padding: 20px;
        }

        /* 사이드바 */
        .sidebar {
            width: 20%;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }
        .sidebar h2 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        .sidebar .filter-group {
            margin-bottom: 20px;
        }
        .sidebar .filter-group label {
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }
        .sidebar .filter-group select,
        .sidebar .filter-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .sidebar .buttons {
            display: flex;
            gap: 10px;
        }
        .sidebar .buttons button {
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #2f3b7e;
            color: white;
        }
        .btn-secondary {
            background-color: #ccc;
        }

        /* 메인 콘텐츠 */
        .main-content {
            flex: 1;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .main-content h2 {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .product-item {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .product-image {
            width: 60px;
            height: 60px;
            background-color: #ddd;
            border-radius: 8px;
            margin-right: 20px;
        }
        .product-info {
            flex: 1;
        }
        .product-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .product-info p {
            font-size: 14px;
            color: #666;
        }
        .product-controls {
            display: flex;
            align-items: center;
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
        }
        .pagination select, .pagination span {
            font-size: 14px;
        }
        .add-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2f3b7e;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
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
            <h2>상품 조회</h2>
            <div class="filter-group">
                <label for="category">카테고리</label>
                <select id="category">
                    <option>전체</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="subcategory">서브 카테고리</label>
                <select id="subcategory">
                    <option>전체</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="search">조회 조건</label>
                <select id="search">
                    <option>선택</option>
                </select>
                <input type="text" placeholder="검색어를 입력하세요.">
            </div>
            <div class="buttons">
                <button class="btn-primary">조회</button>
                <button class="btn-secondary">초기화</button>
            </div>
        </div>

        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <h2>상품관리</h2>
            <div class="product-list">
                <!-- 상품 아이템 -->
                <div class="product-item">
                    <div class="product-image"></div>
                    <div class="product-info">
                        <h3>키우라 아레이기</h3>
                        <p>코드: 5f5021e7lf4b99e7xfx<br>태그: 예기 / 키우라</p>
                    </div>
                    <div class="product-controls">
                        <button>▼</button>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-image"></div>
                    <div class="product-info">
                        <h3>인스트라 R2</h3>
                        <p>코드: 5f502e6d7b9e0bn9y2<br>태그: 바다무하사대, 류어대</p>
                    </div>
                    <div class="product-controls">
                        <button>▼</button>
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
    </div>

    <!-- 추가 버튼 -->
    <div class="add-button">+</div>
</body>
</html>
