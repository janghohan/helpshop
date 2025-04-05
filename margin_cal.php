<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>계산기</title>
    <style>
        /* 모든 브라우저에서 number input의 화살표(스피너) 활성화 */
        input[type="number"].form-control {
            -moz-appearance: textfield;
        }
        input[type="number"].form-control::-webkit-inner-spin-button,
        input[type="number"].form-control::-webkit-outer-spin-button {
            -webkit-appearance: auto;
        }

        .plus{
            color:#0d6efd;
        }
        .minus{
            color:#ff0000;
        }
        .grey{
            background:#efefef;
        }
        .marketBtn.selected{
            background: #2f3b7e;
            color: #fff;
        }
        .nav-link{
            color: #000;
            border: 1px solid #e9ecef !important;
        }

        .nav-link.active{
            color: #fff;
            background-color: #2f3b7e !important;
        }
        .explain{
            margin-left: 20px;
            font-size: 14px;
            display: grid;
            color: #2C3E50;
            font-weight: 600;
            
        }
        
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container">
            <ul class="nav nav-pills text-center" style="margin:20px; width: 400px;">
                <li class="nav-item col-sm-6">
                    <a class="nav-link active" aria-current="page" href="./margin_cal.php">마진율 계산기</a>
                </li>
                <li class="nav-item col-sm-6">
                    <a class="nav-link" href="./roas_cal.php">광고비 계산기</a>
                </li>
            </ul>
            <!-- 메인 콘텐츠 -->
            <div class="explain">
                <span>* 소득세는 고려하지 않은 값입니다.</span>
                <span>* 비과세 상품의 경우 비과세를 먼저 체크후 값을 입력해주세요.</span>
                <span>* 상품 원가와 판매가 값이 바뀔때 새로이 계산됩니다.</span>
                <span>* 배송수수료는 기본 3.3%(VAT 포함)으로 계산됩니다.</span>
            </div>
            <div class="main-content">
                <div>
                <?php
                    $searchResult = [];
                    
                    $query = "SELECT * FROM market WHERE user_ix='$user_ix'";
                    $result = $conn->query($query);
            
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $searchResult[] = $row;
                        }
                    }

                    foreach($searchResult as $marketRow){
                    ?>
                    <button type="button" class="btn btn-outline-secondary me-2 marketBtn" data-basic="<?=htmlspecialchars($marketRow['basic_fee'])?>" data-link="<?=htmlspecialchars($marketRow['linked_fee'])?>" data-ship="<?=htmlspecialchars($marketRow['ship_fee'])?>"><?=htmlspecialchars($marketRow['market_name'])?></button>
                    <?php }?>
                </div>
                <div class="product-list">
                    <div class="d-flex">
                        <div class="flex-grow-1 justify-content-between" id="cal-box" style="max-width:1200px;">
                            <span class="text-info" style="font-size:12px;">네이버</span>
                            
                            <div class="row g-4 calRow">
                                <!-- 왼쪽 섹션: 입력 필드 -->
                                <div class="col-md-3">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <div>
                                            <div>
                                                <label class="form-label">상품 원가(원)</label>
                                            </div>
                                            <div class="w-100">
                                                <input type="text" class="form-control cost localeNumber" value="0">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <div>
                                                <label class="form-label">지출 배송비(원)</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control myship localeNumber" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex mt-3 d-flex gap-2 justify-content-center">
                                        <div>
                                            <div>
                                                <label class="form-label">수량(개)</label>
                                            </div>
                                            <div>
                                                <input type="number" class="form-control quantity" value="1">
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <label class="form-label">기타(재료비)</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control etc localeNumber" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 중간 섹션: 계산 결과 -->
                                <div class="col-md-5">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <div>
                                            <div>
                                                <label class="form-label">판매가(원)</label>
                                            </div>
                                            <div class="w-100">
                                                <input type="text" class="form-control price localeNumber" value="0">
                                            </div>
                                        </div>
                                        
                                        <div>        
                                            <div>
                                                <label class="form-label">총 지출비용(원)</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control totalExpense localeNumber" value="0" readOnly>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <label class="form-label">순이익(원)</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control profit localeNumber" value="0" readOnly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center mt-3">
                                        <div>
                                            <div>
                                                <label class="form-label">마진율(%)</label>
                                            </div>
                                            <div class="w-100">
                                                <input type="text" class="form-control marginRate grey" value="0" readOnly>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <div>
                                                <label class="form-label">배송비</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control ship localeNumber" value="0">
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <label class="form-label">수수료(%)</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control fee" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <!-- 오른쪽 섹션: 판매 부가세 -->
                                <div class="col-md-2">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <div>
                                            <div>
                                                <label class="form-label">부가세(원)</label>
                                            </div>
                                            <div>
                                                <input type="text" class="form-control localeNumber surtax">
                                            </div>
                                            <div>
                                                <input type="checkbox" name="surtaxCheck" class="surtaxCheck">
                                                <label for="surtaxCheck">비과세</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-2 d-flex align-items-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <div >
                                            <div>
                                                <button class="btn ">저장</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
            <div class="modal fade" id="martketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">마켓 등록</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="newMarketForm" method="post">
                            <input type="hidden" name="type" value="create">
                            <p class="mt-4 mb-1 mb-n2">마켓명을 입력하세요.</p>
                            <select name="marketName" id="marketName" class="form-control">
                                <option value="네이버">네이버</option>
                                <option value="쿠팡">쿠팡</option>
                            </select>
                            <p class="mt-4 mb-1 mb-n2">수수료 입력(숫자만 입력하세요)</p>
                            <div class="row">
                                <div class="col-4">
                                    <input type="text" name="basicFee" class="form-control" placeholder="기본 수수료(%)">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="linkedFee" class="form-control" placeholder="연동 수수료(%)">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="shipFee" class="form-control" placeholder="배송 수수료(%)">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="newMarketCreate()">등록</button>
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        $(".calRow input").attr("disabled",true);
    });
    

    $(".marketBtn").click(function(){
        $(".marketBtn").removeClass("selected");
        const basicFee = $(this).data('basic');
        const linkedFee = $(this).data('link');
        const shipFee = $(this).data('ship');

        var calDiv = $("#cal-box");

        calDiv.find('span').text($(this).text());
        calDiv.find(".fee").val(linkedFee+basicFee);
        calDiv.find(".shipFee").val(shipFee);

        $(this).addClass("selected");

        $(".calRow input").attr("disabled",false);

    });
    
    // 입력값 변경 시 계산 실행
    $(document).on('input', '.cost, .quantity, .price, .marginRate', function() {
        let row = $(this).closest('.calRow');
        calculateRow(row);
    });

    function calculateRow(row) {
        let cost = parseNumber(row.find('.cost').val()) || 0;
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let sellingPrice = parseNumber(row.find('.price').val()) || 0;
        let sellingShipping = parseNumber(row.find('.ship').val()) || 0;
        let myShipping = parseNumber(row.find('.myship').val()) || 0;
        let etc = parseNumber(row.find('.etc').val()) || 0;
        let feeRate = parseFloat(row.find('.fee').val()) || 0;
        let shipRate = 3.3;
    

        console.log(sellingPrice,cost);
        
        // 총 매출
        let totalRevenue = sellingPrice * quantity;
        
        // 총 원가
        let totalCost = cost * quantity;
        
        // 상품 수수료 계산 (판매가의 % 적용)
        let totalPriceFee = (totalRevenue * feeRate) / 100;
        // 배송비 수수료 계산
        let totalShipFee = ((sellingShipping+myShipping) * shipRate) / 100;

        // 총 부가세
        let surtax = (totalRevenue+sellingShipping-totalCost-myShipping-etc) * 0.1;
        if($('input:checkbox[name="surtaxCheck"]').is(':checked')){
            surtax = (sellingShipping-myShipping-etc) * 0.1;
        }
        
        // 순수익 계산
        let netProfit = totalRevenue + sellingShipping - totalCost - myShipping - totalPriceFee - totalShipFee - surtax - etc;
        
        // 마진율 계산
        let marginRate = totalRevenue > 0 ? ((netProfit / totalRevenue) * 100).toFixed(2) : 0;

        let totalExpense = totalPriceFee + totalShipFee + surtax + etc;
        // 결과 반영
        if(netProfit>=0){
            row.find('.profit').addClass("plus");
            row.find('.profit').removeClass("minus");
        }else{
            row.find('.profit').addClass("minus");
            row.find('.profit').removeClass("plus");
        }

        if(totalExpense>=0){
            row.find('.totalExpense').addClass("minus");
            row.find('.totalExpense').removeClass("plus");
        }else{
            row.find('.totalExpense').addClass("plus");
            row.find('.totalExpense').removeClass("minus");
        }
        row.find('.profit').val(netProfit.toFixed(2));  // 소수점 2자리까지 표시
        row.find('.marginRate').val(marginRate);
        row.find('.totalExpense').val(totalExpense);
        row.find('.surtax').val(surtax);
    }

    function parseNumber(value) {
        return parseFloat(value.replace(/,/g, '')) || 0; // 콤마 제거 후 숫자로 변환
    }

    function toast(){
        const toastElement = document.getElementById('myToast');
        const toast = new bootstrap.Toast(toastElement);

        // 토스트 표시
        toast.show();
    }

    $(".market-del").click(function(){
        if(confirm("삭제하시겠습니까?")){
            console.log($(this).closest("input").val());
        }
    });
    
</script>
</html>
