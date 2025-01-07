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
                            <input type="text" class="form-control" id="datepicker" placeholder="MM/DD/YYYY">
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <label for="datepicker">직접 입력 등록</label>
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
                    <label for="datepicker">검색 입력 등록</label>
                    <div class="filter-group col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="상품명" id="searchKeyword">
                        </div>
                    </div>
                    <div class="filter-group col-md-1">
                        <div class="form-group">
                            <button class="btn btn-primary" id="search-btn2">검색</button>
                        </div>
                    </div>
                </div>
                <div class="row" id="search-list">
                    <div class="d-flex">
                        <span>ns 로드스 알파 메탈리코 S-682MH-ST</span>
                        <span>원가 : 45,000원</span>
                        <span>판맴가 : 68,000원</span>
                        <span>마켓 : 네이버</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 메인 컨테이너 -->
        <div class="container">
            <!-- 메인 콘텐츠 -->
            <div class="main-content">
                <h2>주문 리스트</h2>
                <form action="" id="order-form">
                    <div class="row single-order">
                        <div class="filter-group col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="orderName" placeholder="상품명" readonly>
                            </div>
                        </div>
                        <div class="filter-group col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control localeNumber" name="orderCost" placeholder="원가">
                            </div>
                        </div>
                        <div class="filter-group col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control localeNumber" name="orderPrice" placeholder="판매가">
                            </div>
                        </div>
                        <div class="filter-group col-md-1">
                            <div class="form-group">
                                <input type="number" class="form-control" name="orderQuantity" placeholder="수량">
                            </div>
                        </div>
                        <div class="filter-group col-md-2">
                            <div class="form-group">
                                <select name="" class="form-control" id="">
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
            console.log(orderDiv.html());
            $("#order-form").append(orderDiv);
            var productName = $("input[name='direct-name']").val();
            $(".single-order").last().find("input[name='orderName']").val(productName);
        });

        $(document).on('click','.remove-btn',function(){
            $(this).parent().parent().parent().remove();
        });

        $("#search-btn2").click(function(){
            var searchKeyword = $("#searchKeyword").val();
            $.ajax({
                url: './api/search_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'searchType':'product','searchKeyword':searchKeyword},
                success: function(response) {
                    console.log(response);
                    // modalOpen("completeModal");
                    // alert('전송 성공: ' + response);
                },
                error: function(xhr, status, error) {
                    alert('전송 실패: ' + error);
                }
            });
        });
    </script>
</body>
</html>
