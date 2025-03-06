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
            <ul class="nav nav-pills text-center" style="margin:20px;">
                <li class="nav-item col-sm-6">
                    <a class="nav-link active" aria-current="page" href="#">마진율 계산기</a>
                </li>
                <li class="nav-item col-sm-6">
                    <a class="nav-link" href="#">광고비 계산기</a>
                </li>
            </ul>
            <!-- 메인 콘텐츠 -->
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
                        <div class="flex-grow-1 justify-content-between" id="cal-box">
                            <div class="calRow mb-2" style="display:none;">
                                <span style="font-size:12px;" class="text-info">네이버</span>
                                <div class="row g-2">
                                    <div class=" col-lg">
                                        <label class="form-label">원가</label>
                                        <input type="text" class="form-control localeNumber cost">
                                    </div>
                                    <div class=" col-lg">
                                        <label class="form-label">수량</label>
                                        <input type="number" class="form-control quantity">
                                    </div>
                                    <div class=" col-lg">
                                        <label class="form-label">판매가</label>
                                        <input type="text" class="form-control localeNumber price">
                                    </div>
                                    <div class=" col-lg">
                                        <label class="form-label">마진율(%)</label>
                                        <input type="text" class="form-control marginRate">
                                    </div>
                                    <div class=" col-lg">
                                        <label class="form-label">순수익</label>
                                        <input type="text" class="form-control profit">
                                    </div>
                                    <div class=" col-lg">
                                        <label class="form-label">수수료(%)</label>
                                        <input type="text" class="form-control fee">
                                    </div>
                                    <!-- <div class=" col-lg">
                                        <label class="form-label">배송수수료(%)</label>
                                        <input type="text" class="form-control shipFee">
                                    </div> -->
                                    <div class=" col-lg">
                                        <label class="form-label">*</label>
                                        <div class="d-flex gap-2">
                                            <button class="btn d-block">저장</button>
                                            <button class="btn d-block">reset</button>
                                        </div>
                                    </div>
                                </div>
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
    

    $(".marketBtn").click(function(){
        const basicFee = $(this).data('basic');
        const linkedFee = $(this).data('link');
        const shipFee = $(this).data('ship');

        var calDiv = $(".calRow").eq(0).clone();
        calDiv.css("display",'block');
        $("#cal-box").append(calDiv);

        calDiv.find('span').text($(this).text());
        calDiv.find(".fee").val(linkedFee+basicFee);
        calDiv.find(".shipFee").val(shipFee);

        // const formData = new FormData();
        // formData.append('type', 'edit');
        // inputs.each(function() {
        //     console.log($(this).attr('name'), $(this).val());
        //     formData.append($(this).attr('name'), $(this).val());
        // });

        // $.ajax({
        //     url: './api/market_api.php', // 데이터를 처리할 서버 URL
        //     type: 'POST',
        //     data: formData,
        //     processData: false, // FormData 객체를 문자열로 변환하지 않음
        //     contentType: false, // 기본 Content-Type 설정을 막음
        //     success: function(response) {
        //         const result = JSON.parse(response);
        //         if(result.status=='suc'){
        //             toast();
        //         }
        //     },
        //     error: function(xhr, status, error) {
        //         alert('전송 실패: ' + error);
        //     }
        // });

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
        let feeRate = parseFloat(row.find('.fee').val()) || 0;

        console.log(sellingPrice,cost);
        
        // 총 매출
        let totalRevenue = sellingPrice * quantity;
        
        // 총 원가
        let totalCost = cost * quantity;
        
        // 수수료 계산 (판매가의 % 적용)
        let totalFee = (totalRevenue * feeRate) / 100;

        // 총 부가세세
        let surtax = (totalRevenue-totalCost) * 0.1;
        
        // 순수익 계산
        let netProfit = totalRevenue - totalCost - totalFee - surtax;
        
        // 마진율 계산
        let marginRate = totalRevenue > 0 ? ((netProfit / totalRevenue) * 100).toFixed(2) : 0;

        // 결과 반영
        row.find('.profit').val(netProfit.toFixed(2));  // 소수점 2자리까지 표시
        row.find('.marginRate').val(marginRate);
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
