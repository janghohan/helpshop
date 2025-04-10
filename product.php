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

    $userIx = $_SESSION['user_ix'] ?? '1';
    $page = $_GET['page'] ?? 1;
    $itemsPerPage =  $_GET['itemsPerPage'] ?? 20;

    $totalItems = $conn->query("SELECT COUNT(*) as total FROM matching_name WHERE user_ix='$userIx'")->fetch_assoc()['total'];
    
    $totalPages = ceil($totalItems / $itemsPerPage); //전체페이지
    $startIndex = ($page - 1) * $itemsPerPage;
    
    $where = ["mn.user_ix = ?"];
    $params = [$userIx];
    $paramTypes = "i"; // user_ix는 정수형이므로 "i"

    if (!empty($_GET['category']) && $_GET['category']!=="전체") {
        $where[] = "c.ix = ?";
        $params[] = $_GET['category'];
        $paramTypes .= "s"; // 문자열이므로 "s"
    }
    
    if (!empty($_GET['account']) && $_GET['account']!=="전체") {
        $where[] = "a.ix = ?";
        $params[] = $_GET['account'];
        $paramTypes .= "s"; // 문자열이므로 "s"
    }
    
    if (!empty($_GET['name'])) {
        $where[] = "mn.matching_name LIKE ?";
        $params[] = "%".$_GET['name']."%";
        $paramTypes .= "s"; // 문자열이므로 "s"
    }

    $limit = $_GET['itemsPerPage'] ?? 20;
    $offset = $startIndex;

    $whereClause = implode(" AND ", $where);

    $listStmt = $conn->prepare("SELECT mn.ix AS mnIx, mn.matching_name AS productName, a.name AS acName, c.name AS cateName, mn.stock, mn.cost FROM matching_name mn 
    JOIN account a ON a.ix = mn.account_ix JOIN category c ON c.ix = mn.category_ix WHERE $whereClause LIMIT ? OFFSET ?");

    $params[] = $limit;
    $params[] = $offset;
    $paramTypes .= "ii";

// SELECT IFNULL(a.name,'') as acName,IFNULL(c.name,'') as cateName, poc.ix as combIx,p.ix as pIx, p.name, p.create_at, poc.combination_key, poc.cost_price, poc.stock FROM product p JOIN product_option_combination poc ON p.ix = poc.product_ix AND p.user_ix=1 LEFT JOIN account a ON p.account_ix = a.ix LEFT JOIN category c ON p.category_ix = c.ix LIMIT 20 OFFSET 0;

    if (!$listStmt) {
        throw new Exception("Error preparing list statement: " . $conn->error); // *** 수정 ***
    }
    $listStmt->bind_param($paramTypes,...$params);
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
                        <select class="product-account">
                            <option value="0">전체</option>
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
                            <?php }?>
                        </select>
                    </div>
                    <div class="filter-group col-md-6">
                        <label for="product-cate">카테고리</label>
                        <select class="product-cate">
                            <option value="0">전체</option>
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
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="filter-group">
                    <input type="text" placeholder="상품명을 입력하세요." id="search-input">
                </div>
                <div class="buttons">
                    <button class="btn-primary" id="search-btn">조회</button>
                    <button class="btn-secondary" id="reset-btn" onclick="location.href='./product.php';">초기화</button>
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
                <div class="table-container table-responsive">
                    <table class="table table-hover">
                        <colgroup>
                            <col style="width: 50%;"> <!-- 첫 번째 열은 30% -->
                            <col style="width: 10%;"> <!-- 두 번째 열은 50% -->
                            <col style="width: 15%;"> <!-- 세 번째 열은 20% -->
                            <col style="width: 10%;"> <!-- 세 번째 열은 20% -->
                            <col style="width: 10%;"> <!-- 세 번째 열은 20% -->
                            <col style="width: 5%;"> <!-- 세 번째 열은 20% -->
                        </colgroup>
                        <thead class=" table-light">
                            <tr>
                                <th>제품이름</th>
                                <th>도매처</th>
                                <th>카테고리</th>
                                <th>원가</th>
                                <th>재고</th>
                                <!-- <th>생성일</th> -->
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            <?php
                            // $previousProductName = null; // 이전 주문번호를 저장
                            // $toggle = true; // 색상을 변경하기 위한 토글 변수

                            if(isset($listResult)){
                                foreach ($listResult as $index => $row) {

                                    $product_ix = $row['mnIx'];
                                    $cate_name = $row['cateName'];
                                    $account_name = $row['acName'];
                                    $product_name = $row['productName'];
                                    $total_stock = $row['stock'];
                                    $cost = $row['cost'];
                                    // $create_at = $row['create_at'];
                                                                   

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
                                <td>
                                    <a href="./product-edit.php?ix=<?=htmlspecialchars($product_ix)?>" style="color:#0069d9;"><?=htmlspecialchars($product_name)?></a>
                                </td>
                                <td class="acTd" data-t="account_ix" data-ix="<?=htmlspecialchars($product_ix)?>"><?=htmlspecialchars($account_name)?></td>
                                <td class="caTd" data-t="category_ix" data-ix="<?=htmlspecialchars($product_ix)?>"><?=htmlspecialchars($cate_name)?></td>
                                <td class="editTd" data-t="cost" data-ix="<?=htmlspecialchars($product_ix)?>"><?=htmlspecialchars(number_format($cost))?></td>
                                <td class="editTd" data-t="stock" data-ix="<?=htmlspecialchars($product_ix)?>"><?=htmlspecialchars($total_stock)?></td>
                                <!-- <td><?=htmlspecialchars($create_at)?></td> -->
                                <td>
                                    <button class="btn btn-light product-del" data-ix="<?=htmlspecialchars($product_ix)?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                                        </svg>
                                    </button>
                                </td>
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
        <div id="myToast" style="z-index:-1" class="toast text-bg-primary position-fixed top-50 start-50 translate-middle border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="500">
            <div class="d-flex">
                <div class="toast-body">
                삭제 되었습니다.
                </div>
            </div>
        </div>

        <!-- 추가 버튼 -->
        <div class="add-button">+</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        $(".product-del").click(function(){
            productIx = $(this).attr('data-ix');
            btn = $(this);
            swalConfirm("복구가 불가능합니다. 삭제하시겠습니까?", function(){
                productDelete(productIx,btn)
            },function(){});
        });

        function productDelete(productIx,btn){
            $.ajax({
                url: './api/product_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'addCount':'0', 'productIx':productIx },
                success: function(response) { 
                    console.log(response);
                    if(response.status=='success'){
                        $(btn).parent().parent().remove();
                        toast();
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

        // 검색
        $("#search-btn").click(function(){
            searchOrderList();
        });

        function searchOrderList(){
            location.href = './product.php?account='+$(".product-account option:selected").val()+'&category='+$(".product-cate option:selected").val()+'&name='+$("#search-input").val()+'&itemsPerPage='+$("#itemsPerPage option:selected").val()+'&page='+<?=$page?>;
        }

        // 항목 더블클릭시 수정 가능하도록
        $(".editTd").dblclick(function(){
            let td = $(this);
            let originalText = td.text().trim();
            let input = $("<input type='text' class='form-control localeNumber'>").val(originalText);

            td.html(input);
            input.focus();
            
            function saveData(input,originalText) {
                let newValue = "";
                newValue = input.val().trim();
                let ix = td.attr('data-ix');
                let type = td.attr('data-t');

                td.text(newValue); // 성공하면 td에 새로운 값 적용

                if (newValue !== originalText) { // 값이 변경된 경우에만 AJAX 실행
                    $.ajax({
                        url: "./api/product_edit_api.php",  // 데이터를 처리할 PHP 파일
                        type: "POST",
                        data: { 'matchingIx': ix, 'value': newValue, 'type':'matchingEdit', 'editCol':type },
                        success: function(response) {
                            console.log("서버 응답:", response);  // 응답 확인
                            td.text(newValue); // 성공하면 td에 새로운 값 적용
                        },
                        error: function(xhr, status, error) {
                            console.error("에러 발생:", error);
                            td.text(originalText); // 에러 발생 시 원래 값 유지
                        }
                    });
                } else {
                    td.text(originalText); // 변경이 없으면 원래 값으로 돌아감
                }
            }

            // Enter 키 입력 시 저장
            input.keypress(function(event) {
                if (event.which == 13) {
                    saveData(input,td,originalText,'input');
                }
            });

            // 포커스를 잃으면 저장 후 td로 변경
            input.blur(function() {
                saveData(input,td,originalText,'input');
            });
        });

        $(".acTd").dblclick(function(){
            let td = $(this);
            let originalText = td.text().trim();
            let accountDiv = $(".product-account").clone();
            let ix = td.attr('data-ix');
            td.html(accountDiv);
            let select = td.find('.product-account');
            select.focus();

            function saveData(select) {
                let newValue = "";
                newValue = select.val();
                let newText = select.find("option:selected").text();
                td.text(newText); // 성공하면 td에 새로운 값 적용

                if (newText !== originalText && newValue!=0) { // 값이 변경된 경우에만 AJAX 실행
                    $.ajax({
                        url: "./api/product_edit_api.php",  // 데이터를 처리할 PHP 파일
                        type: "POST",
                        data: { 'matchingIx': ix, 'value': newValue, 'type':'matchingEdit', 'editCol': 'account_ix' },
                        success: function(response) {
                            console.log("서버 응답:", response);  // 응답 확인
                            td.text(newText); // 성공하면 td에 새로운 값 적용
                        },
                        error: function(xhr, status, error) {
                            console.error("에러 발생:", error);
                            td.text(originalText); // 에러 발생 시 원래 값 유지
                        }
                    });
                } else {
                    td.text(originalText); // 변경이 없으면 원래 값으로 돌아감
                }
            }

            // Enter 키 입력 시 저장
            select.keypress(function(event) {
                if (event.which == 13) {
                    saveData(select);
                }
            });

            // 포커스를 잃으면 저장 후 td로 변경
            select.blur(function() {
                saveData(select);
            });

        });

        $(".caTd").dblclick(function(){
            let td = $(this);
            let originalText = td.text().trim();
            let cateDiv = $(".product-cate").clone();
            let ix = td.attr('data-ix');
            console.log(originalText,'original');
            td.html(cateDiv);
            let select = td.find('.product-cate');
            select.focus();

            function saveData(select) {
                let newValue = "";
                newValue = select.val();
                let newText = select.find("option:selected").text();
                td.text(newText); // 성공하면 td에 새로운 값 적용

                console.log(ix,'ix', newValue,'newValue', originalText,'originalText');
                if (newText !== originalText && newValue!=0) { // 값이 변경된 경우에만 AJAX 실행
                    $.ajax({
                        url: "./api/product_edit_api.php",  // 데이터를 처리할 PHP 파일
                        type: "POST",
                        data: { 'matchingIx': ix, 'value': newValue, 'type':'matchingEdit', 'editCol': 'category_ix' },
                        success: function(response) {
                            console.log("서버 응답:", response);  // 응답 확인
                            td.text(newText); // 성공하면 td에 새로운 값 적용
                        },
                        error: function(xhr, status, error) {
                            console.error("에러 발생:", error);
                            td.text(originalText); // 에러 발생 시 원래 값 유지
                        }
                    });
                } else {
                    td.text(originalText); // 변경이 없으면 원래 값으로 돌아감
                }
            }

            // Enter 키 입력 시 저장
            select.keypress(function(event) {
                if (event.which == 13) {
                    saveData(select);
                }
            });

            // 포커스를 잃으면 저장 후 td로 변경
            select.blur(function() {
                saveData(select);
            });

        });

        // 수정 데이터 전송
        

        function toast(){
            const toastElement = document.getElementById('myToast');
            const toast = new bootstrap.Toast(toastElement);
            // 토스트 표시
            $("#myToast").css("z-index",1);
            toast.show();
        }
    </script>
</body>
</html>
