<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    
    <title>상품 관리</title>
</head>
<style>
    #search-list{
        max-height: 300px;
        overflow-y: auto;
    }
    .result-basic{
        display: none;
        padding: 50px 0 10px;
        text-align: center;
    }
    .result-basic.show{
        display: block;
    }
    #search-list #result-box{
        margin-top: 20px;
    }
    #search-list #result-box .d-flex{
        border-bottom: 1px solid #aaa;
        padding-bottom: 5px;
        padding-top: 5px;
    }
    #search-list #result-box .d-flex:first-child{
        border-bottom:1px solid #aaa;
        border-top:1px solid #aaa;
        padding-bottom: 5px;
        padding-top: 5px;
    }

    /* 배송비 swal */
    .ship-swal-title{
        font-size: 18px;
        padding-top: 15px !important;
    }
    .ship-swal-input{
        height: 2.3rem !important;
        margin: 5px;
    }
</style>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    
    ?>
    <!-- 헤더 -->
   

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>단일 주문 등록</h2>
                <div class="card-body">

                    <!-- 날짜 선택 -->
                    <div class="mb-3">
                        <label for="order_date" class="form-label">주문일자</label>
                        <input type="date" class="form-control" id="flatpickr">
                    </div>

                    <!-- 마켓 선택 -->
                    <div class="mb-3">
                        <label for="market_select" class="form-label">마켓</label>
                        <select id="market_select" class="form-select">
                        <option value="">-- 마켓 선택 --</option>
                        <?php
                        $searchResult = [];
                        
                        $query = "SELECT * FROM market WHERE user_ix='$userIx'";
                        $result = $conn->query($query);
                
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $searchResult[] = $row;
                            }
                        }

                        foreach($searchResult as $marketRow){
                        ?>
                        <option value="<?=htmlspecialchars($marketRow['ix'])?>"><?=htmlspecialchars($marketRow['market_name'])?></option>
                        <?php } ?>
                        </select>
                    </div>

                    <!-- 상품명 자동매칭 -->
                    <div class="mb-3 position-relative">
                        <label for="product_name" class="form-label">상품명</label>
                        <input type="text" id="product_name" class="form-control" placeholder="상품명을 입력하세요">
                        <div id="product_suggestions" class="list-group position-absolute w-100" style="z-index: 1000; display: none;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 메인 컨테이너 -->
        <div class="container" id="selected_products" style="display: none;">
            <!-- 메인 콘텐츠 -->
            <div class="main-content">
                <h2>주문 리스트</h2>
                <form action="./api/order_api.php" id="order-form" method="post">
                    <input type="hidden" name="orderType" value="single">
                    <input type="hidden" name="orderDate">
                    <input type="hidden" name="orderMarket">
                    <input type="hidden" name="shipPrice">
                    <input type="hidden" name="orderNumber">
                    
                   
                </form>
                <div class="d-grid mt-3">
                    <button class="btn btn-primary" id="order-add">주문 등록</button>
                </div>
                
            </div>
        </div>

        <!-- 추가 버튼 -->
        <div class="add-button">+</div>
    </div>
    
    <script src="./js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/product.js"></script>
    
    
    <script>

        $(document).ready(function() {
            flatpickr("#flatpickr", {
                defaultDate: "today",
                dateFormat: "Y-m-d",
                altInput: true,
                theme: "material_blue",
                locale: "ko",
            });

            $('#product_name').on('input', function () {
                let searchKeyword = $(this).val();
                if (searchKeyword.length < 2) {
                    $('#product_suggestions').hide();
                    return;
                }

                $.ajax({
                    url: './api/search_api.php',
                    method: 'POST',
                    dataType : 'json',
                    data: {'searchType':'product','searchKeyword':searchKeyword},
                    success: function (res) {
                        console.log(res);
                        let suggestionBox = $('#product_suggestions');
                        suggestionBox.empty();

                        if (res.length > 0) {
                            res.forEach(item => {
                                suggestionBox.append(`<button type="button" data-c="`+item.cost+`" class="list-group-item list-group-item-action">`+item.matching_name+`</button>`);
                            });
                        } else {
                            suggestionBox.append(`<button type="button" class="list-group-item plus-item list-group-item-action text-muted">+ "`+searchKeyword+`" 추가 등록</button>`);
                        }

                        suggestionBox.show();
                    }
                    });
                });

            // 클릭 시 선택 처리
            let selectedProducts = [];
            $(document).on('click', '#product_suggestions .list-group-item', function () {
                $("#selected_products").show();
                let selectedName = $(this).text();
                let cost = $(this).attr('data-c');
                if($(this).hasClass("plus-item")){
                    let matched = selectedName.match(/"([^"]+)"/);
                    if (matched) {
                        let newSelectedName = matched[1]; // "sadf"
                        selectedName = newSelectedName;
                    }
                }
                console.log(selectedName);
                // 중복 선택 방지
                if (selectedProducts.includes(selectedName)) return;

                selectedProducts.push(selectedName);

                // DOM에 추가
                $('#order-form').append(`
                    <div class="card p-2 selected-product-item d-flex flex-row align-items-center justify-content-between mb-2" data-name="${selectedName}">
                        <div>
                        <strong>${selectedName}</strong><br>
                        <input type="hidden" name="orderName[]" class="form-control form-control-sm d-inline-block mt-1" placeholder="원가" value="${selectedName}" style="width: 100px;" />
                        <input type="number" name="orderCost[]" class="form-control form-control-sm d-inline-block mt-1" placeholder="원가" value="${cost}" style="width: 100px;" />
                        <input type="number" name="orderQuantity[]" class="form-control form-control-sm d-inline-block mt-1" placeholder="수량" value="1" style="width: 80px;" />
                        <input type="number" name="orderPrice[]" class="form-control form-control-sm d-inline-block mt-1" placeholder="판매가" style="width: 100px;" />
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-product">삭제</button>
                    </div>
                    `);
                $('#product_suggestions').hide();
                // $('#product_name').val('');
            });

            $(document).on('click', '.remove-product', function () {
                let parent = $(this).closest('.selected-product-item');
                let name = parent.data('name');
                selectedProducts = selectedProducts.filter(item => item !== name);
                parent.remove();
            });

            // 외부 클릭 시 숨김
            // $(document).click(function (e) {
            //     if (!$(e.target).closest('#product_name, #product_suggestions').length) {
            //         $('#product_suggestions').hide();
            //     }
            // });

            $("#order-add").click(function(event){
                event.preventDefault();
                $("input[name='orderDate']").val($("#flatpickr").val());
                $("input[name='orderMarket']").val($("#market_select option:selected").val());

                // console.log($("#order-form .selected-product-item").length);
                if($("#order-form .selected-product-item").length<1){
                    basicSwal("주문을 등록해주세요.",true);
                    return false;
                }   
                
                let isValid = true; // 모든 input이 채워졌는지 체크하는 변수
                $("#order-form .selected-product-item").each(function(index, element) {

                    $(element).find("input").each(function () {
                        if ($(this).val().trim() === "") {
                            isValid = false; // 하나라도 비어 있으면 false 설정
                            return false; // 반복문 종료
                        }
                    });

                    if (!isValid) {
                        basicSwal("주문에 대한 값을 입력해주세요.",true);
                        return false; // `each` 루프 종료
                    }

                });
                if($("#market_select option:selected").val()==""){
                    basicSwal("마켓을 선택해주세요.",true);
                }


                Swal.fire({
                    title: '추가 정보를 입력하세요',
                    html:
                        `<input id="orderNumber-swal-input" class="ship-swal-input swal2-input" placeholder="마켓 주문번호 (선택)">` +
                        `<input id="ship-swal-input" class="ship-swal-input swal2-input" placeholder="배송비 (필수)" type="number">`,
                    showCancelButton: true,
                    confirmButtonText: '등록',
                    cancelButtonText: '취소',
                    customClass: {
                        title: 'ship-swal-title',
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        addOrder();
                        // 여기서 입력값을 활용하면 됩니다.
                    }
                });
  
                
                function addOrder(){
                    
                    $("input[name='shipPrice']").val($("#ship-swal-input").val());
                    $("input[name='orderNumber']").val($("#orderNumber-swal-input").val());

                    if( $("input[name='shipPrice']").val()==""){
                        basicSwal("배송비를 등록해주세요.",true);
                        return false;
                    }
                    $.ajax({
                        url: './api/order_api.php', // 데이터를 처리할 서버 URL
                        type: 'POST',
                        dataType : 'json',
                        data: $("#order-form").serialize(),
                        success: function(response) { 
                            console.log(response);
                            if(response.status=='success'){
                                basicFunctionSwal('주문이 등록되었습니다.',function() {
                                    location.reload();
                                });
                            }else{
                                alert(response.msg);
                            }

                        },
                        error: function(xhr, status, error) {
                            // alert('전송 실패: ' + error);
                        }
                    });
                }
                
            });
        });


        // 옛날꺼꺼

        // 상품 검색 
        $("#search-btn").click(function(){
            var searchKeyword = $("#search-input").val();
            $.ajax({
                url: './api/search_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType : 'json',
                data: {'searchType':'product','searchKeyword':searchKeyword},
                success: function(response) { 
                    console.log(response);
                    if(response.length==0){
                        $("#result-show").removeClass("show");
                        $("#result-no").addClass("show");
                    }else{
                        $(".result-basic").removeClass("show");
                    }
                    
                    const resultsContainer = document.getElementById("result-box");
                    resultsContainer.innerHTML = ""; // 기존 내용을 초기화

                    // 검색 결과 동적 추가
                    response.forEach(item => {
                        // 각 상품의 HTML 구조 생성
                        const resultDiv = document.createElement("div");
                        resultDiv.className = "d-flex mb-2"; // 레이아웃 클래스 추가
                        resultDiv.innerHTML = `
                            <div class="col-sm-4">
                                <span>${item.matching_name}</span>
                            </div>
                            <div class="col-sm-1">
                                <span>${parseInt(item.cost).toLocaleString()} 원</span>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-outline-secondary btn-sm add-to-order-btn" data-product='${JSON.stringify(item)}'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                                    </svg>
                                </button>
                            </div>
                        `;
                        // 결과 영역에 추가
                        resultsContainer.appendChild(resultDiv);
                    });
                    // modalOpen("completeModal");
                    // alert('전송 성공: ' + response);
                },
                error: function(xhr, status, error) {
                    alert('전송 실패: ' + error);
                }
            });
        });


        document.addEventListener("click", function (e) {
            if (e.target.closest(".add-to-order-btn")) {
                const button = e.target.closest(".add-to-order-btn");
                var market = $("#martketData").clone().prop("outerHTML");

                // 버튼에 저장된 JSON 데이터 가져오기
                const productData = JSON.parse(button.getAttribute("data-product"));

                // 주문 리스트의 HTML 구조 생성
                const orderRow = document.createElement("div");
                orderRow.className = "d-flex gap-2 single-order mb-2";
                orderRow.innerHTML = `
                    <div class="filter-group col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="orderName[]" placeholder="상품명" readonly="" value="${productData.matching_name}">
                        </div>
                    </div>
                    <div class="filter-group col-md-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="orderNumber[]" placeholder="주문번호" value="">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control localeNumber" name="orderCost[]" readonly="" placeholder="원가" value="${parseInt(productData.cost).toLocaleString()}">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control localeNumber" name="orderPrice[]" placeholder="판매가" value="">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <input type="number" class="form-control" name="orderQuantity[]" placeholder="수량">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control localeNumber" name="orderShipping[]" placeholder="택배비">
                        </div>
                    </div>`+market+`
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <button class="btn btn-secondary remove-btn">X</button>
                        </div>
                    </div>
                `;
                
                console.log(orderRow);
                // 주문 리스트에 추가
                const orderList = document.getElementById("order-form");
                orderList.appendChild(orderRow);
            }
        });

        
    </script>
</body>
</html>
