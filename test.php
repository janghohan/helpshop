<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리 시스템</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }
        header {
            background: #007BFF;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
        }
        header .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        header .account-info {
            display: flex;
            gap: 15px;
            align-items: center;
            padding-right: 30px;
        }
        aside {
            width: 250px;
            background: #F4F4F4;
            padding-top: 60px;
            position: fixed;
            top: 0;
            bottom: 0;
        }
        aside ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        aside ul li {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }
        aside ul li:hover {
            background: #E0E0E0;
        }
        main {
            margin-left: 250px;
            padding: 70px 20px;
            flex-grow: 1;
            background: #FFFFFF;
        }
        .dashboard {
            background: #EFEFEF;
            padding: 20px;
            border-radius: 8px;
        }

        .section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            margin: 0 0 15px;
            color: #333;
        }
        .upload-group {
            margin-bottom: 15px;
        }
        .upload-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .upload-group input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .select-group {
            margin-bottom: 15px;
        }
        .select-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .convert-button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .convert-button:hover {
            background: #0056b3;
        }
        .generated-files {
            margin-top: 20px;
            background: #f1f1f1;
            padding: 15px;
            border-radius: 4px;
        }
        .generated-files ul {
            list-style: none;
            padding: 0;
        }
        .generated-files ul li {
            margin-bottom: 10px;
        }
        .generated-files ul li a {
            color: #007bff;
            text-decoration: none;
        }
        .generated-files ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">관리 시스템</div>
        <div class="account-info">
            <span>🔔 알림</span>
            <span>👤 계정 정보</span>
        </div>
    </header>
    <aside>
        <ul>
            <li>📦 상품등록</li>
            <li>📇 거래처등록</li>
            <li>📊 매출관리</li>
            <li>💰 순수익</li>
        </ul>
    </aside>
    <main>
        <!-- 주문 파일 등록 -->
        <div class="section">
            <h2>주문 파일 등록</h2>
            <div class="upload-group">
                <label for="file-naver">네이버:</label>
                <input type="file" id="file-naver">
            </div>
            <div class="upload-group">
                <label for="file-coupang">쿠팡:</label>
                <input type="file" id="file-coupang">
            </div>
            <div class="upload-group">
                <label for="file-auction">옥션:</label>
                <input type="file" id="file-auction">
            </div>
            <div class="upload-group">
                <label for="file-gmarket">G마켓:</label>
                <input type="file" id="file-gmarket">
            </div>
            <div class="select-group">
                <label for="delivery-company">택배사 선택:</label>
                <select id="delivery-company">
                    <option value="cj">CJ대한통운</option>
                    <option value="lotte">롯데택배</option>
                    <option value="hanjin">한진택배</option>
                </select>
            </div>
            <button class="convert-button">변환</button>
            <div class="generated-files">
                <h3>생성된 파일</h3>
                <ul>
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
    </main>
</body>
</html>
