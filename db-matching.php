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
    <style>
       

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
    <?php
    include './header.php';
    include './sidebar.html';
    ?>
    <div class="full-content">
        <div class="container">
            <div class="main-content">
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
            </div>
        </div>
    </div>
</body>
</html>
