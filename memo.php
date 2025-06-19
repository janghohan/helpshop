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
    <style>
        .memo-title.active{
            background:#2f3b7e;
        }
    </style>
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

    $memoStmt = $conn->prepare("SELECT ix,title FROM memo WHERE user_ix=?");
    $memoStmt->bind_param("s",$userIx);
    $memoStmt->execute();

    $result = $memoStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $memoResult[] = $row;
        }
    }
    ?>

    <div class="full-content">
        <div class="main-content">
            <h2>메모 등록</h2>
            <div class="row">
                <div class="col-5">
                    <input type="text" class="col-5 form-control" id="memo-input">
                </div>
                <button class="btn btn-primary col-2" id="create-btn">등록</button>
            </div>
        </div>
    
        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <div class="category-list">
                <!-- 상품 아이템 -->

                <?php 
                 if(isset($memoResult)){
                    foreach($memoResult as $memoRow) {
                ?>
                <button type="button" class="btn btn-secondary position-relative me-3 mb-2 memo-title" data-ix="<?=htmlspecialchars($memoRow['ix'])?>">
                    <?=htmlspecialchars($memoRow['title'])?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger memo-del" data-ix="<?=htmlspecialchars($memoRow['ix'])?>">
                        X
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>
                <?php } }else{ ?>
                    <span id="emptyMemo">* 메모를 등록해주세요.</span>  
                <?php }?>


                <div id="myToast" class="toast text-bg-primary position-fixed top-50 start-50 translate-middle border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="500">
                    <div class="d-flex">
                        <div class="toast-body">
                        삭제 되었습니다.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <textarea class="form-control" name="memoContents" id="memoContents" style="min-height:400px;" readOnly=true></textarea>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var editMemoIx = "";
        $(document).on("click", ".memo-del", function () {
            const thisMemo = $(this);
            const memoIx = $(this).attr('data-ix');
            swalConfirm("삭제 하시겠습니까?", function(){
                memoDel(memoIx ,thisMemo);
            }, function() {} );

        });

        function toast(){
            const toastElement = document.getElementById('myToast');
            const toast = new bootstrap.Toast(toastElement);

            // 토스트 표시
            toast.show();
        }

        // 삭제
        function memoDel(memo_ix,memo){
            console.log(memo_ix);
            $.ajax({
                url: './api/memo_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'delete','memoIx':memo_ix},
                success: function(response) { 
                    if(response.status=='success'){
                        $(memo).parent().remove();
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
                url: './api/memo_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'create', 'memoTitle':$("#memo-input").val()},
                success: function(response) { 
                    if(response.status=='success'){
                        $("#emptyMemo").hide();
                        $(".category-list").append('<button type="button" class="btn btn-secondary position-relative me-3 memo-title" data-ix="'+response.ix+'">'+$("#memo-input").val()+'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger memo-del" data-ix="'+response.ix+'">X<span class="visually-hidden">unread messages</span></span></button>');
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        });
        
        
        $(document).on('click','.memo-title',function(){
            $(".memo-title").removeClass("active"); // 모든 버튼에서 'active' 제거
            $(this).addClass("active"); // 클릭한 버튼에 'active    
            let title = $(this).contents().filter(function() {
                return this.nodeType === 3; // TEXT_NODE (3)
            }).text().trim();
            let memoIx = $(this).attr('data-ix');
            editMemoIx = memoIx;
            $.ajax({
                url: './api/memo_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'get', 'memoTitle':title, 'memoIx':memoIx},
                success: function(response) { 
                    console.log(response);
                    if(response.status=='success'){
                        $("#memoContents").val(response.contents);
                        $("#memoContents").attr('readOnly',false);
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        });

        $(document).ready(function () {
            let timeout = null; // 딜레이 타이머 설정

            $("#memoContents").on("input", function () {
                clearTimeout(timeout); // 기존 타이머 제거
                let memoText = $(this).val();

                timeout = setTimeout(function () {
                    $.ajax({
                        url: "./api/memo_api.php", // 데이터 저장할 서버 주소
                        type: "POST",
                        data: {'type':'edit', memoContents: memoText, 'memoIx':editMemoIx},
                        success: function (response) {
                            console.log("저장 완료:", response);
                        },
                        error: function (xhr, status, error) {
                            console.error("저장 실패:", error);
                        }
                    });
                }, 1000); // 1초 후 서버 요청 (사용자가 입력을 멈추면 저장)
            });
        });

    </script>
</body>
<script>
   
</script>
</html>
