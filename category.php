<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>카테고리</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/common.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./js/common.js"></script>
</head>
<body>
    <?php 
    include './header.php';
    include './sidebar.html';

    include './dbConnect.php';

    // 검색 처리
    // DB 연결 오류 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $userIx = $_SESSION['user_ix'] ?? '1';

    $categoryStmt = $conn->prepare("SELECT * FROM category WHERE user_ix=?");
    $categoryStmt->bind_param("s",$userIx);
    $categoryStmt->execute();

    $result = $categoryStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categoryResult[] = $row;
        }
    }
    ?>

    <div class="full-content">
        <div class="main-content">
            <h2>카테고리 등록</h2>
            <div class="row">
                <div class="col-5">
                    <input type="text" class="col-5 form-control" id="cate-input">
                </div>
                <button class="btn btn-primary col-2" id="create-btn">등록</button>
            </div>
        </div>
    
        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <div class="d-flex mb-3">
                <h2 class="flex-grow-1">카테고리</h2>
            </div>
            <div class="category-list" style="line-height: 50px;">
                <!-- 상품 아이템 -->

                <?php 
                 if(isset($categoryResult)){
                    foreach($categoryResult as $categoryRow) {
                ?>
                <button type="button" class="btn btn-primary position-relative me-3">
                    <?=htmlspecialchars($categoryRow['name'])?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cate-del" data-ix="<?=htmlspecialchars($categoryRow['ix'])?>">
                        X
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>
                <?php } } ?>
                <div id="myToast" class="toast text-bg-primary position-fixed top-50 start-50 translate-middle border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="500">
                    <div class="d-flex">
                        <div class="toast-body">
                        삭제 되었습니다.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on("click", ".cate-del", function () {
            const thisCate = $(this);
            const cateIx = $(this).attr('data-ix');
            swalConfirm("삭제 하시겠습니까?", function(){
                cateDel(cateIx ,thisCate);
            }, function() {} );

        });

        function toast(){
            const toastElement = document.getElementById('myToast');
            const toast = new bootstrap.Toast(toastElement);

            // 토스트 표시
            toast.show();
        }

        // 삭제
        function cateDel(cate_ix,cate){
            console.log(cate_ix);
            $.ajax({
                url: './api/category_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'delete','categoryIx':cate_ix},
                success: function(response) { 
                    if(response.status=='success'){
                        $(cate).parent().remove();
                        toast();
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }
        // 생성
        $("#create-btn").click(function(){
            $.ajax({
                url: './api/category_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'create', 'categoryName':$("#cate-input").val()},
                success: function(response) { 
                    if(response.status=='success'){
                        $(".category-list").append('<button type="button" class="btn btn-primary position-relative me-3">'+$("#cate-input").val()+'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cate-del" data-ix="'+response.ix+'">X<span class="visually-hidden">unread messages</span></span></button>');
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        });
        
        



    </script>
</body>
<script>
   
</script>
</html>
