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


</style>
<body>
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
     
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $productIx = isset($_GET['ix']) ? $_GET['ix'] : '';

    // 상품품
    $productStmt = $conn->prepare("SELECT * FROM product WHERE ix=? AND user_ix=?");
    $productStmt->bind_param("ss",$productIx, $userIx);

    if (!$productStmt->execute()) {
        throw new Exception("Error executing list statement: " . $productStmt->error); // *** 수정 ***
    }

    $productResult = $productStmt->get_result();

    if ($productResult->num_rows > 0) {
        while ($productRow = $productResult->fetch_assoc()) {
            $productName = $productRow['name'];
            $accountIx = $productRow['account_ix'];
            $categoryIx = $productRow['category_ix'];
            $memo = $productRow['memo'];
        }
        $productStmt -> close();
    }

    // 옵션
    $productOptionStmt = $conn->prepare("SELECT * FROM product_option WHERE product_ix=? GROUP BY name");
    $productOptionStmt->bind_param("s",$productIx);

    if (!$productOptionStmt->execute()) {
        throw new Exception("Error executing list statement: " . $productOptionStmt->error); // *** 수정 ***
    }

    $result = $productOptionStmt->get_result();
    $optionName = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($optionName,$row['name']);
            
        }
        $productOptionStmt-> close();

    }
    $optionNameText = implode(",",$optionName);


    // 옵션 키 
    $productOptionCombiStmt = $conn->prepare("SELECT poc.ix as combIx, pomp.market_ix, pomp.ix as mPriceIx, m.market_name,poc.combination_key,poc.cost_price,poc.stock,pomp.price 
    FROM product_option_combination poc JOIN product_option_market_price pomp ON poc.product_ix=? AND poc.ix = pomp.product_option_comb_ix 
    JOIN market m ON m.ix = pomp.market_ix ORDER BY poc.ix");
    $productOptionCombiStmt->bind_param("s",$productIx);
    if (!$productOptionCombiStmt->execute()) {
        throw new Exception("Error executing list statement: " . $productOptionCombiStmt->error); // *** 수정 ***
    }

    $result = $productOptionCombiStmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listResult[] = $row;
        }
        
    }


    // $productOptionCombiStmt = $conn->prepare("SELECT * FROM product_option_combination WHERE product_ix=?");
    // $productOptionCombiStmt->bind_param("s",$productIx);
    // if (!$productOptionCombiStmt->execute()) {
    //     throw new Exception("Error executing list statement: " . $productOptionCombiStmt->error); // *** 수정 ***
    // }

    // $result = $productOptionCombiStmt->get_result();

    // $optionValue = [];
    // if ($result->num_rows > 0) {

    //     while ($row = $result->fetch_assoc()) {
    //         $option= [
    //             'combination_key' => $row['combination_key'],
    //             'cost' => $row['cost_price'],
    //             'stoc' => $row['stock'],
    //             'combi_ix' => $row['ix'],
    //         ];

    //         array_push($optionValue,$option);
    //     }
    //     $productOptionCombiStmt->close();
    // }

    // foreach($optionValue as $index => $data){
    //     $combiIx = $data['combi_ix'];
    //     $marktPriceStmt = $conn->prepare("SELECT * FROM product_option_market_price WHERE product_option_comb_ix=?");
    //     $marktPriceStmt->bind_param("s",$combiIx);
        
    //     $marketPrice = [];
    //     if ($marktPriceStmt->execute()) {
    //         $marketPriceResult = $marktPriceStmt->get_result();

            
    //         if ($marketPriceResult->num_rows > 0) {
    //             while ($marketPriceRow = $marketPriceResult->fetch_assoc()) {
    //                 $marketPrice[$marketPriceRow['market_ix']] = $marketPriceRow['price'];
    //             }

    //             $marktPriceStmt->close();
    //         }
            
    //     }

    //     $optionValue[$index]['price'] = $marketPrice;

    // }
    
    


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
                        <option value="0">선택안함</option>
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
                    <select class="category" name="categoryIx" id="categoryIx">
                    <option value="0">선택안함</option>
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
                                <input type="text" placeholder="상품명" maxlength="100" name="productName" value="<?=htmlspecialchars($productName)?>">
                                <div class="char-count">0 / 100</div>
                            </div>
                            <div class="field">
                                <label>상품별 메모</label>
                                <textarea placeholder="상품별 메모" maxlength="200" name="productMemo"><?=htmlspecialchars($memo)?></textarea>
                                <div class="char-count">0 / 200</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="option-info-section">
                    <div class="option-combination">
                        <label>옵션</label>
                        <div class="d-flex gap-sm-2">
                            <div class="col-sm-1">
                                <input type="text" class="optionName form-control" readonly value="판매처"> <!-- 인풋(1) -->
                            </div> 
                            <div class="col-sm-3">
                                <input type="text" class="optionName form-control" readonly placeholder="색상,크기" value="옵션명"> <!-- 인풋(1) -->
                            </div> 
                            <div class="col-sm-3">
                                <input type="text" class="optionValue form-control" readonly value="옵션값"> <!-- 인풋(2) -->
                            </div>
                            <div class="col-sm-1">
                                <input type="text" class="optionValue form-control" readonly value="원가"> <!-- 인풋(2) -->
                            </div>
                            <div class="col-sm-1">
                                <input type="text" class="optionValue form-control" readonly value="수량"> <!-- 인풋(2) -->
                            </div>
                            <div class="col-sm-1">
                                <input type="text" class="optionValue form-control" readonly value="판매가">
                            </div>
                        </div>
                        <?php 
                        

                        if(isset($listResult)){

                            $previousCombIx = null;
                            foreach ($listResult as $index => $row) {

                                $currentCombIx = $row['combIx'];
                                if($previousCombIx!=$currentCombIx) {
                                ?>
                                <div class="d-flex gap-sm-2" data-op-com="<?=htmlspecialchars($row['combIx'])?>" data-op-m="<?=htmlspecialchars($row['mPriceIx'])?>" data-m="<?=htmlspecialchars($row['market_ix'])?>">
                                    <div class="col-sm-1">
                                        <input type="text" class="form-control" readonly placeholder="판매처" value="<?=htmlspecialchars($row['market_name'])?>"> <!-- 인풋(1) -->
                                    </div> 
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" readonly placeholder="옵션명" value="<?=htmlspecialchars($optionNameText)?>"> <!-- 인풋(1) -->
                                    </div> 
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="optionValue" value="<?=htmlspecialchars($row['combination_key'])?>"> <!-- 인풋(2) -->
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="text" class="form-control localeNumber" name="optionCost" value="<?=htmlspecialchars(number_format($row['cost_price']))?>"> <!-- 인풋(2) -->
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="text" class="form-control " name="optionStock" value="<?=htmlspecialchars($row['stock'])?>"> <!-- 인풋(2) -->
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="text" class="form-control localeNumber" name="optionPrice" value="<?=htmlspecialchars(number_format($row['price']))?>">
                                    </div>
                                    <div class="col-sm-0">
                                        <button class="btn btn-light option-edit me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="col-sm-0">
                                        <button class="btn btn-light option-del">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <?php }else{
                                ?>
                                <div class="d-flex gap-sm-2" data-op-com="<?=htmlspecialchars($row['combIx'])?>" data-op-m="<?=htmlspecialchars($row['mPriceIx'])?>" data-m="<?=htmlspecialchars($row['market_ix'])?>">
                                    <div class="col-sm-1">
                                        <input type="text" class="form-control" readonly value="<?=htmlspecialchars($row['market_name'])?>"> <!-- 인풋(1) -->
                                    </div> 
                                    <div class="col-sm-3">
                                        
                                    </div> 
                                    <div class="col-sm-3">
                                        
                                    </div>
                                    <div class="col-sm-1">
                                    
                                    </div>
                                    <div class="col-sm-1">
                                        
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="text" name="optionPrice" class="form-control localeNumber" value="<?=htmlspecialchars(number_format($row['price']))?>">
                                    </div>
                                    <div class="col-sm-0">
                                        <button class="btn btn-light option-edit me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="col-sm-0">
                                        <button class="btn btn-light option-del">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <?php } 
                                $previousCombIx = $currentCombIx;
                                } } ?>
                        <!-- <button class="apply-btn" onclick="generateCombinations()">적용</button> -->
                    </div>

                    <!-- <div class="option-list">
                        <label>옵션 목록 (총 4개)</label>
                        <button class="add-row-btn">추가</button>
                        <div class="option-table">
                            <div class="option-row op-header">
                                <div class="option-name-group">
                                    <div class="main-header">옵션명</div>
                                    <div class="sub-headers">
                                    </div>
                                </div>
                                <div class="option-price">매입가</div>
                                <div class="stock">재고수량</div>
                                <div class="buying-price">판매가</div>
                                <div class="delete">삭제</div>
                            </div>
                        </div>
                    </div>
                    <div id="result">

                    </div> -->

                    <div id="myToast" class="toast text-bg-primary position-fixed top-50 start-50 translate-middle border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="500">
                        <div class="d-flex">
                            <div class="toast-body">
                            수정 되었습니다.
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                    <button class="btn btn-primary float-md-end" onclick="productEdit();">수정</button>
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
                                <div class="d-flex justify-content-center mb-3">
                                    <h5>수정이 완료되었습니다.</h5>
                                </div>
                                <div class="d-flex justify-content-center" style="gap:15px;">
                                    <button class="btn btn-outline-secondary"  onclick = "location.href ='./product.php';">상품 목록으로 가기</button>
                                    <button class="btn btn-primary" onclick = "location.reload();">상품 다시 수정하기</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>
<script src="./js/product-add.js"></script>
<script>
    
    $(document).ready(function(){
        $("#categoryIx").val("<?=$categoryIx?>").trigger('change');
        $("#accountIx").val("<?=$accountIx?>").trigger('change');
    });
    //옵션 목록중 옵션 삭제
    $(document).on('click','.option-del',function(){
        if(confirm('삭제 하시겠습니까?')){
            const thisDiv = $(this).parent().parent();
            const combIx = $(this).parent().parent().attr('data-op-com');
            const mPriceIx = $(this).parent().parent().attr('data-op-m');

            $.ajax({
                url: './api/product_edit_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'opDel', 'combIx':combIx,'mPriceIx':mPriceIx},
                success: function(response) {
                    if(response.status=='success'){
                        $(thisDiv).remove();
                    }
                },
                error: function(xhr, status, error) {
                    alert('전송 실패: ' + error);
                }
            });
        }
    });
    

    
    //옵션 수정
    $(document).on('click','.option-edit',function(){
        const btn = $(this);
        const thisDiv = $(this).parent().parent();
        const combIx = $(this).parent().parent().attr('data-op-com');
        const mPriceIx = $(this).parent().parent().attr('data-op-m');
        const value = $(thisDiv).find("input[name='optionValue']").val();
        const cost = $(thisDiv).find("input[name='optionCost']").val();
        const stock = $(thisDiv).find("input[name='optionStock']").val();
        const price = $(thisDiv).find("input[name='optionPrice']").val();

        console.log(value,cost,stock,price);
        $.ajax({
            url: './api/product_edit_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            data: {'type':'opEdit', 'combIx':combIx,'mPriceIx':mPriceIx,'optionValue':value,'optionCost':cost,'optionStock':stock,'optionPrice':price},
            success: function(response) {
                console.log(response.status);
                if(response.status=='success'){
                    toast(btn);
                }
            },
            error: function(xhr, status, error) {
                alert('전송 실패: ' + error);
            }
        });
    });

    function toast(button){
        const toastElement = document.getElementById('myToast');
        const toast = new bootstrap.Toast(toastElement);

        // 토스트 표시
        toast.show();
    }

    function productEdit(){
        const productName = $("input[name='productName']").val();
        const productMemo = $("textarea[name='productMemo']").val();
        const accountIx = $("select[name='accountIx']").val();
        const categoryIx = $("select[name='categoryIx']").val();

        if(productName==""){
            alert('상품명을 입력해주세요.');
            return false;
        }


        $.ajax({
            url: './api/product_edit_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            data: {'type':'productEdit','productName':productName,'productMemo':productMemo, 'accountIx':accountIx, 'categoryIx':categoryIx, 'productIx':"<?=$productIx?>"},
            success: function(response) {
                console.log(response.status);
                if(response.status=='success'){
                    modalOpen("completeModal");
                }
            },
            error: function(xhr, status, error) {
                alert('전송 실패: ' + error);
            }
        });
    }

</script>
</html>
