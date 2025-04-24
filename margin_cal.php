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

    $userIx = isset($_SESSION['user_ix']) ? : '1';

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
                            <form action="" id="margin-form" method="post">
                                <input type="hidden" value="create" name="type">
                                <div class="row g-4 calRow">
                                    <!-- 왼쪽 섹션: 입력 필드 -->
                                    <div class="col-md-3">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <div>
                                                <div>
                                                    <label class="form-label">상품 원가(원)</label>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control cost localeNumber" value="0" name="cost">
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <div>
                                                    <label class="form-label">지출 배송비(원)</label>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control myship localeNumber" value="0" name="shipCost">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex mt-3 d-flex gap-2 justify-content-center">
                                            <div>
                                                <div>
                                                    <label class="form-label">수량(개)</label>
                                                </div>
                                                <div>
                                                    <input type="number" class="form-control quantity" value="1" name="quantity">
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
                                                    <label class="form-label">배송비</label>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control ship localeNumber" value="0" name="shipPrice">
                                                </div>
                                            </div>
                                            <div>
                                                <div>
                                                    <label class="form-label">판매가(원)</label>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control price localeNumber" value="0" name="price">
                                                </div>
                                            </div>
                                            
                                            
                                            <div>
                                                <div>
                                                    <label class="form-label">순이익(원)</label>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control profit localeNumber" value="0" readOnly name="profit">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center mt-3">
                                            
                                            <div>
                                                <div>
                                                    <label class="form-label">마진율(%)</label>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control marginRate grey" value="0" readOnly name="marginRate">
                                                </div>
                                            </div>
                                            <div>        
                                                <div>
                                                    <label class="form-label">총 지출비용(원)</label>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control totalExpense localeNumber" value="0" readOnly name="expense">
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <div>
                                                    <label class="form-label">수수료(%)</label>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control fee" value="0" name="feeRate">
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
                                    <div class="col-md-1 d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary" id="create-btn">저장</button>
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
                            </form>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
            <?php
            $calStmt = $conn->prepare("SELECT * FROM margin_list WHERE user_ix=?");
            $calStmt->bind_param("s",$userIx);
    
            $calStmt->execute();
            $result = $calStmt->get_result();
            
            $savDataDisplay = "none";
            if ($result->num_rows > 0) {
                $savDataDisplay = "block";
                while ($row = $result->fetch_assoc()) {
                    $calResult[] = $row;
                }
            }

            
            ?>
            
            <div class="main-content mt-5" id="saved_margin_list" style="display:<?=$savDataDisplay?>">
                <h6>저장목록</h6>       
                <div class="d-flex fw-bold text-secondary gap-2 px-2 py-2 border-bottom bg-light rounded-top small">
                    <div style="width: 200px;">상품명</div>
                    <div class="text-center" style="width: 80px;">원가</div>
                    <div class="text-center" style="width: 80px;">지출배송비</div>
                    <div class="text-center" style="width: 80px;">수수료</div>
                    <div class="text-center" style="width: 80px;">지출비용</div>
                    <div class="text-center" style="width: 80px;">판매가</div>
                    <div class="text-center" style="width: 80px;">판매배송비</div>
                    <div class="text-center" style="width: 80px;">순이익</div>
                    <div class="text-center" style="width: 80px;">마진율</div>
                    <div class="text-center ms-auto" style="width: 60px;">작업</div>
                </div>
                <?php 
                if ($result->num_rows > 0) {
                    foreach($calResult as $calRow) {
                ?>
                <div class="d-flex align-items-center gap-2 border rounded p-2 mb-1">
      
                    <!-- 상품이름 (Input) -->
                    <input type="text" class="form-control form-control-sm product_name" placeholder="상품명" value="<?=htmlspecialchars($calRow['product_name'])?>" style="width: 200px;"/>

                    <!-- 나머지 항목들 (텍스트) -->
                    <span class="small text-center" style="width: 80px;"><?=htmlspecialchars(number_format($calRow['cost']))?></span> <!-- 원가 -->
                    <span class="small text-center" style="width: 80px;"><?=htmlspecialchars(number_format($calRow['ship_cost']))?></span> <!-- 지출배송비 -->
                    <span class="small text-center" style="width: 80px;"><?=htmlspecialchars($calRow['fee_rate'])?>%</span>      <!-- 수수료 -->
                    <span class="small text-center minus" style="width: 80px;"><?=htmlspecialchars(number_format($calRow['expense']))?></span>      <!-- 지출비용 -->
                    <span class="small text-center" style="width: 80px;"><?=htmlspecialchars(number_format($calRow['price']))?></span> <!-- 판매가 -->
                    <span class="small text-center" style="width: 80px;"><?=htmlspecialchars(number_format($calRow['ship_price']))?></span> <!-- 판매배송비 -->
                    <span class="small text-center plus" style="width: 80px;"><?=htmlspecialchars(number_format($calRow['profit']))?></span> <!-- 순이익 -->
                    <span class="small text-center plus" style="width: 80px;"><?=htmlspecialchars($calRow['margin_rate'])?>%</span>      <!-- 마진율 -->

                    <!-- 삭제 버튼 -->
                    <button type="button" class="btn btn-outline-danger btn-sm ms-auto remove_list" data-v="<?=htmlspecialchars($calRow['ix'])?>">삭제</button>
                </div>
                <?php }} ?>
                
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
        let shipRate = 3;
    

        console.log(sellingPrice,cost);
        
        // 총 상품 매출
        let totalProductRevenue = sellingPrice * quantity;
        // 총 배송비 매출
        let totalShipRevenue = sellingPrice;

        // 총 매출
        let totalRevenue = totalProductRevenue + totalShipRevenue;
        // 총 매출 부가세
        let totalRevenueSurtax = totalRevenue - (totalRevenue / 1.1);

        //총 상품 원가
        let totalProductCost = cost * quantity;
        //총 배송비 원가
        let totalShipCost = myShipping;

        //상품 매출 수수료
        let totalPriceFee = (totalProductRevenue * feeRate) / 100;
        //배송비 매출 수수료
        let totalShipFee = (totalShipRevenue * shipRate) / 100;

        //총 매입금액(vat 포함)
        let totalPurchase = totalProductCost + totalShipCost + (totalPriceFee*1.1) + (totalShipFee*1.1) + etc;

        //총 매입 부가세
        let totalPurchaseSurtax = totalPurchase - (totalPurchase / 1.1);


        // 총 지출 부가세
        let surtax = totalRevenueSurtax - totalPurchaseSurtax;
        if($('input:checkbox[name="surtaxCheck"]').is(':checked')){
            surtax = (totalPurchase) * 0.1;
        }
        
        // 세전순수익 계산
        let exNetProfit = totalRevenue  - totalPurchase;
        let afterNetProfit = exNetProfit - surtax;
        
        // 마진율 계산
        let marginRate = totalRevenue > 0 ? ((afterNetProfit / totalRevenue) * 100).toFixed(2) : 0;

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


    $("#create-btn").click(function(){
        $.ajax({
            url: './api/cal_api.php', // 데이터를 처리할 서버 URL
            dataType:'json',
            type: 'POST',
            data: $("#margin-form").serialize(),
            success: function(response) {
                
                if(response.status=='success'){
                    $("#saved_margin_list").show();
                    $("#saved_margin_list").append('<div class="d-flex align-items-center gap-2 border rounded p-2 mb-1">'
                    +'<input type="text" class="form-control form-control-sm product_name" placeholder="상품명" value="" style="width: 200px;"/>'+
                    '<span class="small text-center" style="width: 80px;">'+$("input[name='cost']").val()+'</span>'+
                    '<span class="small text-center" style="width: 80px;">'+$("input[name='shipCost']").val()+'</span>'+
                    '<span class="small text-center" style="width: 80px;">'+$("input[name='feeRate']").val()+'%</span>'+
                    '<span class="small text-center minus" style="width: 80px;">'+$("input[name='expense']").val()+'</span>'+
                    '<span class="small text-center" style="width: 80px;">'+$("input[name='price']").val()+'</span>'+
                    '<span class="small text-center" style="width: 80px;">'+$("input[name='shipPrice']").val()+'</span>'+
                    '<span class="small text-center plus" style="width: 80px;">'+$("input[name='profit']").val()+'</span>'+
                    '<span class="small text-center plus" style="width: 80px;">'+$("input[name='marginRate']").val()+'%</span>'+
                    '<button type="button" class="remove_list btn btn-outline-danger btn-sm ms-auto" data-v="'+response.cal+'">삭제</button></div>');
                }

            },
            error: function(xhr, status, error) {                  
                // alert("관리자에게 문의해주세요.");
                console.log(error);
            }
        });
    });

    $(document).on('click', '.remove_list', function() {
        const data = $(this).parent();
        $.ajax({
            url: './api/cal_api.php', // 데이터를 처리할 서버 URL
            dataType:'json',
            type: 'POST',
            data: {'cal':$(this).attr('data-v'),'type':'delete'},
            success: function(response) {
                console.log(response); 
                if(response.status=='success'){
                    data.remove();
                }

            },
            error: function(xhr, status, error) {                  
                // alert("관리자에게 문의해주세요.");
                console.log(error);
            }
        });
    });

    $(document).on('input', '.product_name', function() {
        let productName = $(this).val();
        let cal = $(this).siblings('.remove_list').attr('data-v');

        console.log(productName,cal);
        $.ajax({
            url: './api/cal_api.php', // 데이터를 처리할 서버 URL
            dataType:'json',
            type: 'POST',
            data: {'cal': cal,'type':'update','productName':productName},
            success: function(response) {

            },
            error: function(xhr, status, error) {                  
                // alert("관리자에게 문의해주세요.");
                console.log(error);
            }
        });
    });

    
</script>
</html>
