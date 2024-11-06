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

        /* 상품정보 */
        .product-info-section {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            display: flex;
            gap: 20px;
        }

        .image-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .image-box {
            position: relative;
            width: 150px;
            height: 150px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-box img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }

        .image-actions {
            position: absolute;
            bottom: 8px;
            display: flex;
            gap: 8px;
        }

        .zoom-btn, .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        .product-fields {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        .field label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .field input, .field textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            resize: none;
        }

        .char-count {
            font-size: 12px;
            color: #777;
            text-align: right;
            margin-top: 3px;
        }

        /* 옵션정보 */
        .option-info-section {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .option-combination {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .option-combination label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .option-fields {
            display: flex;
            flex-direction: column;
        }

        .top-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .column {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .top-row input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 150px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .add-option-btn, .apply-btn {
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }



        .apply-btn {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .option-list {
            margin-top: 20px;
        }

        .option-list label {
            font-weight: bold;
            margin-bottom: 10px;
            display: inline-block;
        }

        .option-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .option-list th, .option-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .option-list th {
            background-color: #f7f7f7;
        }

        .delete-row-btn, .excel-upload-btn, .add-row-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .excel-upload-btn, .add-row-btn {
            margin-top: 10px;
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

            <div class="product-info-section">
                <div class="section-title">상품 정보</div>
                <div class="product-info">
                    <div class="image-container">
                        <div class="image-box">
                            <img src="placeholder.png" alt="대표 이미지">
                            <div class="image-actions">
                                <button class="zoom-btn">🔍</button>
                                <button class="delete-btn">🗑️</button>
                            </div>
                        </div>
                        <label>대표 이미지</label>
                    </div>
                    <div class="product-fields">
                        <div class="field">
                            <label>상품명</label>
                            <input type="text" placeholder="상품명" maxlength="100">
                            <div class="char-count">0 / 100</div>
                        </div>
                        <div class="field">
                            <label>상품 태그</label>
                            <input type="text" placeholder="상품 태그" maxlength="100">
                            <div class="char-count">0 / 100</div>
                        </div>
                        <div class="field">
                            <label>매입 사이트 URL</label>
                            <input type="text" placeholder="매입 사이트 URL" maxlength="400">
                            <div class="char-count">0 / 400</div>
                        </div>
                        <div class="field">
                            <label>상품별 메모</label>
                            <textarea placeholder="상품별 메모" maxlength="200"></textarea>
                            <div class="char-count">0 / 200</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="option-info-section">
                <div class="section-title">옵션 정보</div>
                <div class="option-combination">
                    <label>옵션명 조합 생성</label>
                    <div class="option-fields">
                        <div class="top-row">
                            <input type="text" placeholder="색상,크기"> <!-- 인풋(1) -->
                            <div class="column">
                                <input type="text" placeholder="빨강,노랑"> <!-- 인풋(2) -->
                                <input type="text" placeholder="55mm,75mm"> <!-- 인풋(3) -->
                            </div>
                            <div class="buttons">
                                <button class="add-option-btn">+</button>
                                <button class="apply-btn">적용</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="option-list">
                    <label>옵션 목록 (총 4개)</label>
                    <table>
                        <thead>
                            <tr>
                                <th>삭제</th>
                                <th>옵션명</th>
                                <th>옵션태그</th>
                                <th>판매가격</th>
                                <th>매입가격</th>
                                <th>출고지</th>
                                <th>상태</th>
                                <th>메모</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button class="delete-row-btn">🗑️</button></td>
                                <td>빨강색상,크기55mm</td>
                                <td><input type="text" placeholder="옵션태그를 입력"></td>
                                <td>0</td>
                                <td>0</td>
                                <td><input type="text" placeholder="출고지를 입력"></td>
                                <td><input type="text" placeholder="상태를 입력"></td>
                                <td><input type="text" placeholder="메모를 입력"></td>
                            </tr>
                            <!-- 추가 항목들 -->
                        </tbody>
                    </table>
                    <button class="excel-upload-btn">엑셀 일괄등록</button>
                    <button class="add-row-btn">추가</button>
                </div>
            </div>


        </div>
    </div>
</body>
</html>
