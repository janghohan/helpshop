<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <title>상품 관리</title>
</head>
<style>
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
                <div class="row">
                    <div class="filter-group col-md-2">
                        <div class="form-group">
                            <label for="datepicker">날짜 선택</label>
                            <input type="text" class="form-control" id="datepicker" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <label for="datepicker">상품 직접 입력</label>
                    <div class="filter-group col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="direct-name" placeholder="상품명">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <button class="btn btn-primary" id="direct-btn">적용</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <label for="datepicker">상품 검색 입력</label>
                    <div class="filter-group col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="상품명" id="search-input">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <button class="btn btn-primary" id="search-btn">검색</button>
                        </div>
                    </div>
                </div>
                <div class="row" id="search-list">
                    <div class="d-flex">
                        <div class="col-sm-4">
                            <span>상품명</span>
                        </div>
                        <div class="col-sm-2">
                            <span>규격</span>
                        </div>
                        <div class="col-sm-1">
                            <span>단가</span>
                        </div>
                        <div class="col-sm-1">
                            <span>상품가</span>
                        </div>
                        <div class="col-sm-2">
                            <span>마켓</span>
                        </div>
                        <div class="col-sm-1">
                            <span>선택</span>
                        </div>
                    </div>
                    <div class="result-basic show" id="result-show">
                        <span>검색결과가 보여집니다.</span>
                    </div>
                    <div class="result-basic" id="result-no">
                        <span>검색결과가 없습니다.</span>
                    </div>
                    <div id="result-box">
                        <!-- <div class="d-flex">
                            <div class="col-sm-4">
                                <span>NS 로드스 알파 메탈리코</span>
                            </div>
                            <div class="col-sm-2">
                                <span>S-682MH-ST</span>
                            </div>
                            <div class="col-sm-1">
                                <span>45,000</span>
                            </div>
                            <div class="col-sm-1">
                                <span>68,000</span>
                            </div>
                            <div class="col-sm-2">
                                <span>네이버</span>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="col-sm-4">
                                <span>NS 로드스 알파 메탈리코</span>
                            </div>
                            <div class="col-sm-2">
                                <span>S-682MH-ST</span>
                            </div>
                            <div class="col-sm-1">
                                <span>45,000</span>
                            </div>
                            <div class="col-sm-1">
                                <span>68,000</span>
                            </div>
                            <div class="col-sm-2">
                                <span>네이버</span>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                                    </svg>
                                </button>
                            </div>
                        </div> -->
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- 메인 컨테이너 -->
        <div class="container">
            <!-- 메인 콘텐츠 -->
            <div class="main-content">
                <h2>주문 리스트</h2>
                <form action="./api/order_api.php" id="order-form" method="post">
                    <input type="hidden" name="type" value="single">
                    <input type="hidden" name="orderDate" >
                    <div class="row single-order" style="display:none;">
                        <div class="filter-group col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="orderName[]" placeholder="상품명" readonly>
                            </div>
                        </div>
                        <div class="filter-group col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control localeNumber" name="orderCost[]" placeholder="원가">
                            </div>
                        </div>
                        <div class="filter-group col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control localeNumber" name="orderPrice[]" placeholder="판매가">
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
                        </div>
                        <div class="filter-group col-md-2">
                            <div class="form-group">
                                <select name="orderMarket[]" class="form-control" id="">
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
                                    <option value="<?=htmlspecialchars($marketRow['ix'])?>"><?=htmlspecialchars($marketRow['market_name'])?></option>
                                <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="filter-group col-md-1">
                            <div class="form-group">
                                <button class="btn btn-secondary remove-btn">X</button>
                            </div>
                        </div>
                    </div>
                   
                </form>
                <button class="btn btn-primary mt-5" id="order-add">주문 등록</button>
            </div>
        </div>

        <!-- 추가 버튼 -->
        <div class="add-button">+</div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="./js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="./js/product.js"></script>
    
    <script>

        $(document).ready(function() {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'ko'
            });

        });

        $("#direct-btn").click(function(){
            var orderDiv = $(".single-order").eq(0).clone();
            orderDiv.css("display",'flex');
            $("#order-form").append(orderDiv);
            var productName = $("input[name='direct-name']").val();
            $(".single-order").last().find("input[name='orderName[]']").val(productName);
        });

        $(document).on('click','.remove-btn',function(){
            $(this).parent().parent().parent().remove();
        });

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
                                <span>${item.product_name}</span>
                            </div>
                            <div class="col-sm-2">
                                <span>${item.combination_key}</span>
                            </div>
                            <div class="col-sm-1">
                                <span>${parseInt(item.cost_price).toLocaleString()} 원</span>
                            </div>
                            <div class="col-sm-1">
                                <span>${parseInt(item.price).toLocaleString()} 원</span>
                            </div>
                            <div class="col-sm-2">
                                <span>${item.market_name}</span>
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

                // 버튼에 저장된 JSON 데이터 가져오기
                const productData = JSON.parse(button.getAttribute("data-product"));

                // 주문 리스트의 HTML 구조 생성
                const orderRow = document.createElement("div");
                orderRow.className = "row single-order mb-2";
                orderRow.innerHTML = `
                    <div class="filter-group col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="orderName[]" placeholder="상품명" readonly="" value="${productData.product_name} - ${productData.combination_key}">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control localeNumber" name="orderCost[]" readonly="" placeholder="원가" value="${parseInt(productData.cost_price).toLocaleString()}">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control localeNumber" name="orderPrice[]" readonly="" placeholder="판매가" value="${parseInt(productData.price).toLocaleString()}">
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
                    </div>
                    <div class="filter-group col-md-2">
                        <div class="form-group">
                            <select cass="form-control" name="orderMarket[]">
                                <option value="${productData.market_ix}">${productData.market_name}</option>
                            </select>
                        </div>
                    </div>
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

        $("#order-add").click(function(){
            $("input[name='orderDate']").val($("#datepicker").val());

            console.log($("#order-form").serialize());
            $("#order-form").submit();

            
        });
    </script>
</body>
</html>
