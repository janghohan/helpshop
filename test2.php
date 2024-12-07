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

        .syncBtn {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #007bff;
            color: white;
            padding: 10px 20px;
        }
        .syncBtn .sync-button {
            padding: 10px 20px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .syncBtn .sync-button:hover {
            background: #003f82;
        }

        .data-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-y: auto;
        }
        .data-row {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .data-row:last-child {
            border-bottom: none;
        }
        .source {
            width: 10%;
            color: #555;
            font-weight: bold;
        }
        .input-container {
            width: 40%;
            padding: 0 10px;
        }
        .input-container input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .checkbox-container {
            width: 10%;
            display: flex;
            justify-content: center;
        }
        .checkbox-container input {
            transform: scale(1.5);
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
        <div class="syncBtn">
            <button class="sync-button">동기화</button>
        </div>
        <div class="data-list">
            <!-- Example Row -->
            <div class="data-row" style="font-weight:bold;">
                <div class="source">판매처</div>
                <div class="input-container">상품명</div>
                <div class="input-container">
                    매칭 상품
                </div>
                <div class="checkbox-container">
                    체크박스
                </div>
            </div>
            <div class="data-row">
                <div class="source">쿠팡</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <!-- Example Row -->
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <div class="data-row">
                <div class="source">네이버</div>
                <div class="input-container">아부가르시아 새턴3 802l</div>
                <div class="input-container">
                    <input type="text" placeholder="값 입력">
                </div>
                <div class="checkbox-container">
                    <input type="checkbox">
                </div>
            </div>
            <!-- More rows dynamically loaded here -->
        </div>
    </main>
</body>
</html>
