<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="./js/common.js"></script>
    <script src="./js/product-add.js"></script>
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
            display: table;
            flex-direction: column;
        }


        /* 수정 */
        .col-sm-6, .col-sm-3{
            float: left;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-3{
            width: 25%;
        }

        .buttons {
            flex-direction: column;
            gap: 5px;
            text-align: right;
        }

        .add-option-btn, .del-option-btn, .apply-btn {
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
            width: 250px;
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

        .add-row-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            float:right;
            display: block;
            
        }

        .excel-upload-btn, .add-row-btn {
            margin-top: 10px;
        }


        /* 테이블 */
        .option-table {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .option-row {
            display: table-row;
            border-bottom: 1px solid #e0e0e0;
        }
        .op-header {
            font-weight: bold;
            background-color: #f8f8f8;
        }
        .option-checkbox, .option-price, .stock, .buying-price, .memo, .sale-status, .manage-code, .use-status, .delete {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #e0e0e0;
            vertical-align:middle;
        }
        .option-checkbox:last-child, .option-price:last-child, .stock:last-child, .memo:last-child, .sale-status:last-child, .manage-code:last-child, .use-status:last-child, .delete:last-child {
            border-right: none; /* Remove right border from the last header cell */
        }
        .option-name-group {
            display: table-cell;
            padding: 10px;
            border-left: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
        }
        .main-header {
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #e0e0e0; /* Bottom border for main header */
            padding-bottom: 5px;
        }
        .sub-headers {
            display: flex;
            justify-content: space-between;
            padding-top: 5px;
        }
        .sub-header {
            /* width: 32%; */
            text-align: center;
            font-weight: normal;
            border-right: 1px solid #e0e0e0; /* Right border between sub-headers */
            padding: 5px 0;
        }
        .sub-header:last-child {
            border-right: none;
        }
        .sub-item {
            width: 32%;
            text-align: center;
            display: inline-block;
            border-right: 1px solid #e0e0e0;
        }
        .sub-item:last-child {
            border-right: none;
        }
        .delete button {
            background: none;
            border: none;
            color: red;
            font-size: 16px;
            cursor: pointer;
        }

        .option-input{
            background: none;
            text-align: center;
            padding: 0px 10px;
            box-sizing: border-box;
            width: 100%;
            height: 34px;
            border: none;
            outline: none;
            cursor: pointer;
            border-radius: 5px;
            word-break: keep-all;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .op-delete{
            text-align: center;
        }

    </style>
</head>
<body>
    <?php 
    include './sidebar.html';
    include './header.php';
     
     ?>

    <!-- 메인 컨테이너 -->
    <div class="container">
        <!-- 카테고리 선택 섹션 -->
        <div class="category-section">
            <!-- 거래처 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>거래처</label>
                <select>
                    <option value="">선택</option>
                    <!-- 카테고리 옵션 추가 가능 -->
                </select>
            </div>

            <!-- 카테고리 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>카테고리</label>
                <select>
                    <option value="">선택</option>
                    <!-- 카테고리 옵션 추가 가능 -->
                </select>
            </div>

            <div class="product-info-section">
                <div class="product-info">
                    <div class="product-fields">
                        <div class="field">
                            <label>상품명</label>
                            <input type="text" placeholder="상품명" maxlength="100">
                            <div class="char-count">0 / 100</div>
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
                    <div class="option-fields row">
                        <div class="col-sm-3">
                            <input type="text" class="optionName form-control" placeholder="색상,크기"> <!-- 인풋(1) -->
                        </div> 
                        <div class="col-sm-3">
                            <input type="text" class="optionValue form-control" placeholder="빨강,노랑"> <!-- 인풋(2) -->
                        </div>
                        <div class="col-sm-3">
                            <div class="buttons">
                                <button class="add-option-btn">+</button>
                            </div>
                        </div>
                    </div>
                    <button class="apply-btn" onclick="generateCombinations()">적용</button>
                </div>

                <div class="option-list">
                    <label>옵션 목록 (총 4개)</label>
                    <button class="add-row-btn">추가</button>
                    <div class="option-table">
                        <div class="option-row op-header">
                            <div class="option-checkbox"><input type="checkbox"></div>
                            <div class="option-name-group">
                                <div class="main-header">옵션명</div>
                                <div class="sub-headers">
                                    <!-- <div class="sub-header">색상</div>
                                    <div class="sub-header">크기</div> -->
                                    <!-- <div class="sub-header">호환</div> -->
                                </div>
                            </div>
                            <div class="option-price">매입가</div>
                            <div class="stock">재고수량</div>
                            <div class="buying-price">판매가</div>
                            <div class="memo">메모</div>
                            <div class="delete">삭제</div>
                        </div>

                        <!-- Row example -->
                        <!-- <div class="option-row" >
                            <div class="option-checkbox"><input type="checkbox"></div>
                            <div class="option-name-group">
                            </div>
                            <div class="option-price">
                                <input type="text" class="option-input" value="0">
                            </div>
                            <div class="stock">
                                <input type="text" class="option-input" value="0">
                            </div>
                            <div class="memo">
                                <input type="text" class="option-input" value="메모">
                            </div>
                            <div class="op-delete"><button>×</button></div>
                        </div> -->
                    </div>
                </div>
                <div id="result">

                </div>
            </div>
            <div>
                <button class="btn btn-primary float-md-end">등록</button>
            </div>

        </div>
    </div>
</body>
<script>
    $(document).on('click', '.add-option-btn', function() {
        // $(this).remove();
        // 현재 .option-fields를 복제
        var delBtn = '<button class="del-option-btn">x</button>';
        var newOptionFields = $(this).closest('.option-fields').clone();
        
        // 복제한 요소의 입력 필드를 초기화
        newOptionFields.find('input').val('');
        
        // 새로 생성된 요소를 현재 요소의 아래에 추가
        $(this).closest('.option-fields').after(newOptionFields);
        $(this).remove();

        var len = $(".option-fields").length;
        if(len==2){
            $(".option-fields").last().find(".buttons").prepend(delBtn);
        }else if(len==3){
            $(".option-fields").last().find('.add-option-btn').remove();
        }
    });

    $(document).on('click', '.del-option-btn', function() {
        // $(this).remove();
        $(".option-fields").last().remove();
        $(".option-fields").last().find(".buttons").append('<button class="add-option-btn">+</button>');

        
    });


    
    
    $(document).on('click','.op-delete',function(){
        $(this).parent().closest('.option-row').remove();
    });

    // $(document).on('keyup','input', function(){
    //     // newNumber = comma($(this).val());
    //     console.log($(this).val().toLocaleString('ko-KR'));
    //     $(this).val($(this).val().toLocaleString('ko-KR'));
    // });
</script>
</html>
