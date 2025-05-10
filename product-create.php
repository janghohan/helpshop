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
                <select name="accountIx" id="accountIx">
                    <?php
                    $accountResult = [];
                    $query = "SELECT * FROM account WHERE user_ix='$userIx'";
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
                <select class="category" name="categoryIx" id="categoryIx">
                <?php
                    $categoryResult = [];
                    $query = "SELECT * FROM category WHERE user_ix='$userIx'";
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
                            <label>상품명<span style="font-size: 14px;">(옵션까지 입력해주세요)</span></label>
                            <input type="text" placeholder="상품명" maxlength="100" name="productName">
                        </div>
                        <div class="field">
                            <label>원가</label>
                            <input type="text" class="localeNumber" placeholder="원가" name="productCost">
                        </div>
                        <div class="field">
                            <label>재고</label>
                            <input type="text" class="localeNumber" placeholder="재고" name="productStock">
                        </div>
                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 저장된 값 불러오기
        if (localStorage.getItem('selectedCategory')) {
            $('#categoryIx').val(localStorage.getItem('selectedCategory'));
        }
        if (localStorage.getItem('selectedAccount')) {
            $('#accountIx').val(localStorage.getItem('selectedAccount'));
        }
    });
    
    function productAdd(){
        const productName = $("input[name='productName']").val();
        const productCost = $("input[name='productCost']").val();
        const productStock = $("input[name='productStock']").val();
        const accountIx = $("select[name='accountIx']").val();
        const categoryIx = $("select[name='categoryIx']").val();

        localStorage.setItem('selectedAccount', accountIx);
        localStorage.setItem('selectedCategory', categoryIx);

        if(productName==""){
            alert('상품명을 입력해주세요.');
            return false;
        }

        $.ajax({
            url: './api/product_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            data: {'addCount':1,'productName':productName,'productCost':productCost, 'productStock':productStock, 'accountIx':accountIx, 'categoryIx':categoryIx},
            success: function(response) {
                if(response.status=='success'){
                    basicFunctionSwal('상품이 등록되었습니다.',function() {
                        location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                alert('전송 실패: ' + error);
            }
        });
    }


</script>
</html>
