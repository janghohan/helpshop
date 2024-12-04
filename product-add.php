<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product-add.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="./js/common.js"></script>
    <script src="./js/product-add.js"></script>
    <title>카테고리 선택 페이지</title>

</head>
<body>
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
     
     ?>

    <div class="full-content">
        <!-- 메인 컨테이너 -->
    <div class="container">
        <!-- 카테고리 선택 섹션 -->
        <div class="category-section">
            <!-- 거래처 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>거래처</label>
                <select>
                    <?php
                    $accountResult = [];
                    $query = "SELECT * FROM account WHERE user_ix='$user_ix'";
                    $result = $conn->query($query);
            
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $accountResult[] = $row;
                        }
                    }
                    foreach($accountResult as $accountRow) {
                    ?>
                    
                    <option value="<?=htmlspecialchars($accountRow['name'])?>"><?=htmlspecialchars($accountRow['name'])?></option>
                    <?php } 
                    ?>
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
