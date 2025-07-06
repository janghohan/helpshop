<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>내정보 페이지</title>
    <style>
        #pay-history{
            border: 1px solid #2f3b7e;
            padding: 2px 10px;
            border-radius: 30px;
            background: #2f3b7e;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    if(!$is_login){
        echo "<script>alert('이용할 수 없는 페이지입니다.'); location.href='./';</script>";
    }

    $infoStmt = $conn->prepare("SELECT * FROM user u LEFT JOIN user_subscriptions us ON u.ix = us.user_ix WHERE u.ix = ?");
    $infoStmt->bind_param("s",$userIx);
    $infoStmt->execute();

    $infoResult = $infoStmt->get_result();
    if ($infoResult->num_rows > 0) {
        $infoRow = $infoResult->fetch_assoc(); // 결과에서 한 행을 가져옴
    }

    if($infoRow['is_trial']==1){
        $plan = "체험형(만료일 : ".$infoRow['ends_at'].")";
    }else if($infoRow['status']=='active'){
        $plan = "일반형(만료일 : ".$infoRow['ends_at'].")";
    }
    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container mt-5">
            <div class="shadow rounded-4 border-0">
                <div class="card-body p-5">
                    <h4 class="mb-4 fw-semibold">👤 회원 정보</h4>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">아이디</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($infoRow['id'])?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">닉네임</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($infoRow['name'])?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">휴대폰번호</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($infoRow['contact'])?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">가입일</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($infoRow['create_at'])?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">사용플랜</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($plan)?>
                                <a href="./pay-history.php" id="pay-history">결제내역</a>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-outline-dark px-4 py-2 rounded-pill shadow-sm" id="logout">
                        로그아웃
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#logout").click(function(){
        swalConfirm('로그아웃 하시겠습니까?',function() {
            location.href='./api/signout.php';
        }, function(){});
    });

    
</script>
</html>
