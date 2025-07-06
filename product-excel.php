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

    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 20;

    $totalItems = $conn->query("SELECT COUNT(*) as total FROM product p JOIN product_option_combination poc ON p.ix = poc.product_ix JOIN product_option_market_price pomp ON poc.ix = pomp.product_option_comb_ix AND p.user_ix='$userIx'")->fetch_assoc()['total'];
    
    $totalPages = ceil($totalItems / $itemsPerPage); //전체페이지
    $startIndex = ($page - 1) * $itemsPerPage;
    
    $listStmt = $conn->prepare("SELECT IFNULL(a.name,'') as acName,IFNULL(c.name,'') as cateName, poc.ix as combIx, pomp.ix as mpIx, 
    m.market_name, p.name, poc.combination_key, poc.cost_price, poc.stock, pomp.price FROM product p 
    JOIN product_option_combination poc ON p.ix = poc.product_ix AND p.user_ix=? 
    JOIN product_option_market_price pomp ON poc.ix = pomp.product_option_comb_ix 
    JOIN market m ON m.ix = pomp.market_ix LEFT JOIN account a ON p.account_ix = a.ix LEFT JOIN category c ON p.category_ix = c.ix LIMIT ? OFFSET ?");

    if (!$listStmt) {
        throw new Exception("Error preparing list statement: " . $conn->error); // *** 수정 ***
    }
    $listStmt->bind_param("sss",$userIx,$itemsPerPage,$startIndex);
    if (!$listStmt->execute()) {
        throw new Exception("Error executing list statement: " . $listStmt->error); // *** 수정 ***
    }

    $result = $listStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listResult[] = $row;
        }
    }

    // 페이지 링크 범위 설정 (예: 현재 페이지를 기준으로 ±2개의 링크 표시)
    $visibleRange = 2;
    $startPage = max(1, $page - $visibleRange);
    $endPage = min($totalPages, $page + $visibleRange);

    // 이전/다음 페이지 계산
    $hasPrev = $page > 1;
    $hasNext = $page < $totalPages;
    $prevPage = $hasPrev ? $page - 1 : null;
    $nextPage = $hasNext ? $page + 1 : null;

    
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
                <div class="d-flex justify-content-between mb-3">
                <h2>상품관리</h2>
                <div>
                    <select class="form-control" name="itemsPerPage" id="itemsPerPage">
                        <option value="20">20개 보기</option>
                        <option value="50">50개 보기</option>
                        <option value="100">100개 보기</option>
                        <option value="100">300개 보기</option>
                    </select>
                </div>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>판매처</th>
                            <th>거래처</th>
                            <th>카테고리</th>
                            <th>제품이름</th>
                            <th>옵션값</th>
                            <th>판매가</th>
                            <th>원가</th>
                            <th>재고수량</th>
                        </tr>
                        </thead>
                        <tbody id="product-list">
                            <?php
                            // $previousProductName = null; // 이전 주문번호를 저장
                            // $toggle = true; // 색상을 변경하기 위한 토글 변수

                            if(isset($listResult)){
                                foreach ($listResult as $index => $row) {

                                    $combIx = $row['combIx'];
                                    $mpIx = $row['mpIx'];
                                    $cate_name = $row['cateName'];
                                    $account_name = $row['acName'];
                                    $market_name = $row['market_name'];
                                    $name = $row['name'];
                                    $combination_key = $row['combination_key'];
                                    $cost = $row['cost_price'];
                                    $stock = $row['stock'];
                                    $price = $row['price'];
                                                                   

                                    // $currentProductName = $name;
                                    // if ($currentProductName !== $previousProductName) {
                                    //     // 상품명이 바뀐다.
                                    //     $toggle = !$toggle;
                                    //     $shipping = $orderRow['total_shipping'];
                                    // }else{
                                    //     $shipping = 0;
                                    // }
                                    // $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                                    // $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신


                            ?>        
                            <tr>
                                <td class=""><?=htmlspecialchars($market_name)?></td>
                                <td class=""><?=htmlspecialchars($account_name)?></td>
                                <td class=""><?=htmlspecialchars($cate_name)?></td>
                                <td><?=htmlspecialchars($name)?></td>
                                <td><?=htmlspecialchars($combination_key)?></td>
                                <td><?=htmlspecialchars(number_format($price))."원"?></td>
                                <td><?=htmlspecialchars(number_format($cost))."원"?></td>
                                <td><?=htmlspecialchars(number_format($stock))?></td>
                            </tr>
                            
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- 페이지네이션 -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php if($hasPrev): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $prevPage ?>&itemsPerPage=<?= $itemsPerPage ?>">Previous</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <?php endif; ?>

                        <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i == $page): ?>
                                <li class="page-item active"><a class="page-link" href="#"><?= $i ?></a></li>
                            <?php else: ?>
                                <li class="page-item"><a class="page-link" href="?page=<?= $i ?>&itemsPerPage=<?= $itemsPerPage ?>"><?= $i ?></a></li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($hasNext): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&itemsPerPage=<?= $itemsPerPage ?>">Next</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- 추가 버튼 -->
        <div class="add-button">+</div>
    </div>
    <script>
        $(document).ready(function () {
            $("#itemsPerPage").val("<?=$itemsPerPage?>");

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

            $("#itemsPerPage").change(function(){
                location.href='./product.php?itemsPerPage='+$(this).val();
            });
        });
    </script>
</body>
</html>
