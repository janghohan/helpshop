<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./js/product.js"></script>
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
                <h2>상품 조회</h2>
                <div class="row">
                    <div class="filter-group col-md-6">
                        <label for="account-cate">거래처</label>
                        <select id="account-cate">
                            <option>전체</option>
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
                            <?php }?>
                        </select>
                    </div>
                    <div class="filter-group col-md-6">
                        <label for="product-cate">카테고리</label>
                        <select id="product-cate">
                            <option>전체</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
                <div class="filter-group">
                    <input type="text" placeholder="상품명을 입력하세요." id="search-input">
                </div>
                <div class="buttons">
                    <button class="btn-primary" id="search-btn">조회</button>
                    <button class="btn-secondary" id="reset-btn">초기화</button>
                </div>
            </div>
        </div>

        <!-- 메인 컨테이너 -->
        <div class="container">
            <!-- 메인 콘텐츠 -->
            <div class="main-content">
                <h2>상품관리</h2>
                <div class="product-list">
                    <!-- 상품 아이템 -->
                    <div class="product-item">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h3>키우라 아레이기</h3>
                            </div>
                            <div class=" flex-shrink-0">
                                <button class="btn btn-secondary toggle-detail-btn">보기</button>
                                <button class="btn btn-secondary">수정</button>
                            </div>
                        </div>
                        <div class="product-detail" style="display: none;">
                            <div class="d-flex flex-column">
                                <label for="">메모</label>
                                <textarea name="" id=""></textarea>
                            </div>
                            <div class="d-flex text-c">
                                <div class="flex-item-1">
                                    <div class="d-flex flex-column">
                                        <label for="">옵션</label>
                                        <span>색상 : 빨강, 크기 : 55mm</span>
                                    </div>
                                </div>
                                <div class="flex-item-2 text-end">
                                    <div class="d-flex flex-column">
                                        <label for="">원가</label>
                                        <span>4,500</span>
                                    </div>
                                </div>
                                <div class="flex-item-2 text-end">
                                    <div class="d-flex flex-column">
                                        <label for="">판매가</label>
                                        <span>6000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h3>키우라 아레이기</h3>
                            </div>
                            <div class=" flex-shrink-0">
                                <button class="btn btn-secondary toggle-detail-btn">보기</button>
                                <button class="btn btn-secondary">수정</button>
                            </div>
                        </div>
                        <div class="product-detail" style="display: none;">
                            <div class="d-flex flex-column">
                                <label for="">메모</label>
                                <textarea name="" id=""></textarea>
                            </div>
                            <div class="d-flex text-c">
                                <div class="flex-item-1">
                                    <div class="d-flex flex-column">
                                        <label for="">옵션</label>
                                        <span>색상 : 빨강, 크기 : 55mm</span>
                                    </div>
                                </div>
                                <div class="flex-item-2 text-end">
                                    <div class="d-flex flex-column">
                                        <label for="">원가</label>
                                        <span>4,500</span>
                                    </div>
                                </div>
                                <div class="flex-item-2 text-end">
                                    <div class="d-flex flex-column">
                                        <label for="">판매가</label>
                                        <span>6000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 페이지네이션 -->
                <div class="pagination">
                    <select>
                        <option>10개 보기</option>
                    </select>
                    <span>1 / 1</span>
                </div>
            </div>
        </div>

        <!-- 추가 버튼 -->
        <div class="add-button">+</div>
    </div>
    <script>
        $(document).ready(function () {
            $('.toggle-detail-btn').on('click', function () {
                // 현재 버튼에서 가까운 product-item 내부의 product-detail을 슬라이드 토글
                $(this).closest('.product-item').find('.product-detail').slideToggle();

                // 버튼 텍스트 변경
                if ($(this).text() === "보기") {
                    $(this).text("닫기");
                } else {
                    $(this).text("보기");
                }
            });
        });
    </script>
</body>
</html>
