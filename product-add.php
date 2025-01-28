<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product-add.css" data-n-g="">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="./js/common.js"></script>
    <title>카테고리 선택 페이지</title>

</head>
<style>
    .marketPriceDiv{
        display: none;
    }
    .marketPriceForm{display:none;}
    .marketPriceForm.active{
        display: block;
    }

</style>
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
        <div class="product-section">
            <!-- 거래처 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>거래처</label>
                <select name="accountIx">
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
                    
                    <option value="<?=htmlspecialchars($accountRow['ix'])?>"><?=htmlspecialchars($accountRow['name'])?></option>
                    <?php } 
                    ?>
                    <!-- 카테고리 옵션 추가 가능 -->
                </select>
            </div>

            <!-- 카테고리 선택 -->
            <div class="category-box">
                <label><span class="required">•</span>카테고리</label>
                <select class="category" name="categoryIx">
                <?php
                    $categoryResult = [];
                    $query = "SELECT * FROM category WHERE user_ix='$user_ix'";
                    $result = $conn->query($query);
            
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $categoryResult[] = $row;
                        }
                    }
                    foreach($categoryResult as $categoryRow) {
                    ?>
                    
                    <option value="<?=htmlspecialchars($categoryRow['ix'])?>"><?=htmlspecialchars($categoryRow['name'])?></option>
                    <?php } 
                    ?>
                    <!-- 카테고리 옵션 추가 가능 -->
                </select>
            </div>

            <div class="product-info-section">
                <div class="product-info">
                    <div class="product-fields">
                        <div class="field">
                            <label>상품명</label>
                            <input type="text" placeholder="상품명" maxlength="100" name="productName">
                            <div class="char-count">0 / 100</div>
                        </div>
                        <div class="field">
                            <label>상품별 메모</label>
                            <textarea placeholder="상품별 메모" maxlength="200" name="productMemo"></textarea>
                            <div class="char-count">0 / 200</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="option-info-section">
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
                            <!-- <div class="option-checkbox"><input type="checkbox"></div> -->
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
                <button class="btn btn-primary float-md-end" onclick="productAdd();">등록</button>
            </div>
            <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-id="">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">판매가 등록</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $marketResult = [];
                        $query = "SELECT * FROM market WHERE user_ix='$user_ix'";
                        $result = $conn->query($query);
                
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $marketResult[] = $row;
                            }
                        }
                        foreach($marketResult as $marketRow) {
                        ?>
                        <div class="market-row">
                            <label for=""><?=htmlspecialchars(string: $marketRow['market_name'])?></label>
                            <input type="hidden" name="market_ix" value="<?=htmlspecialchars(string: $marketRow['ix'])?>">
                            <input type="number" class="price_by_market form-control" name="price_by_market" class="form-control" placeholder="판매가 입력">
                        </div>
                        <?php }?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="newMarketCreate()">등록</button>
                    </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                       <div class="d-flex justify-content-center" style="gap:15px;">
                            <button class="btn btn-outline-secondary"  onclick = "location.href ='./product.php';">상품 목록으로 가기</button>
                            <button class="btn btn-primary" onclick = "location.href ='./product-add.php';">상품 새로 등록하기</button>
                       </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="marketPriceFormDiv" >
                <div class="marketPriceForm">
                    <?php
                    $marketResult = [];
                    $query = "SELECT * FROM market WHERE user_ix='$user_ix'";
                    $result = $conn->query($query);
            
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $marketResult[] = $row;
                        }
                    }
                    foreach($marketResult as $marketRow) {
                    ?>
                    <div class="market-row">
                        <label for=""><?=htmlspecialchars(string: $marketRow['market_name'])?></label>
                        <input type="hidden" name="market_ix" value="<?=htmlspecialchars(string: $marketRow['ix'])?>">
                        <input type="number" class="price_by_market" name="price_by_market" class="form-control" placeholder="판매가 입력" >
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<script src="./js/product-add.js"></script>
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

    //옵션 조합 삭제
    $(document).on('click', '.del-option-btn', function() {
        $(".option-fields").last().remove();
        $(".option-fields").last().find(".buttons").append('<button class="add-option-btn">+</button>');

        
    });

    
    //옵션 목록중 옵션 삭제
    $(document).on('click','.op-delete',function(){
        var delId = $(this).parent().attr('data-id');
        console.log(delId);
        $(this).parent().closest('.option-row').remove();
        
        // formCombinations = formCombinations.filter(item => item.id !== delId);
        const index = formCombinations.findIndex(item => item.id === delId);

        // 인덱스가 존재하면 해당 항목 삭제
        if (index !== -1) {
            formCombinations.splice(index, 1);
        }
        console.log(formCombinations,"formCombinations after del")
    });

    function productAdd(){
        const productName = $("input[name='productName']").val();
        const productMemo = $("textarea[name='productMemo']").val();
        const accountIx = $("select[name='accountIx']").val();
        const categoryIx = $("select[name='categoryIx']").val();

        if(productName==""){
            alert('상품명을 입력해주세요.');
            return false;
        }
        insertPriceAndStock();
        

        console.log(formCombinations);
        $.ajax({
            url: './api/product_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            data: {'addCount':1,'productName':productName,'productMemo':productMemo, 'accountIx':accountIx, 'categoryIx':categoryIx, 'options':JSON.stringify(optionsArray), 'formCombination':JSON.stringify(formCombinations)},
            success: function(response) {
                modalOpen("completeModal");
                alert('전송 성공: ' + response);
            },
            error: function(xhr, status, error) {
                alert('전송 실패: ' + error);
            }
        });
    }

</script>
</html>
