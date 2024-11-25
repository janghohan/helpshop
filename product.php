<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/product.js"></script>

    <title>상품 관리</title>
    <style>
  
        /* 검색 컨테이너 */
        .filter-group {
            margin-bottom: 5px;
        }
        .filter-group label {
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }
        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .buttons button {
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>상품 조회</h2>
                <div class="row">
                    <div class="filter-group col-md-6">
                        <label for="account-cate">거래처</label>
                        <select id="account-cate">
                            <option>전체</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                    <div class="filter-group col-md-6">
                        <label for="product-cate">카테고리</label>
                        <select id="product-cate">
                            <option>전체</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
                <div class="filter-group">
                    <input type="text" placeholder="상품명을 입력하세요." id="search-input">
                </div>
                <div class="buttons">
                    <button class="btn-primary" id="search-btn">조회</button>
                    <button class="btn-secondary" id="reset-btn">초기화</button>
                </div>
            </div>
        </div>

        <!-- 메인 컨테이너 -->
        <div class="container">
            <!-- 메인 콘텐츠 -->
            <div class="main-content">
                <h2>상품관리</h2>
                <div class="product-list">
                    <!-- 상품 아이템 -->
                    <div class="product-item">
                        <div class="product-info">
                            <h3>키우라 아레이기</h3>
                            <span>거래처: 5f5021e7lf4b99e7xfx</span>
                            <span>태그: 예기 / 키우라</span>
                        </div>
                        <div class="product-controls">
                            <button>▼</button>
                        </div>
                    </div>
                    <div class="product-item">
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
    </div>
</body>
</html>
