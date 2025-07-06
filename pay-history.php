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

    $infoStmt = $conn->prepare("SELECT name,ends_at,is_trial,us.status AS status,method,amount FROM user_subscriptions us JOIN payment_plans pp ON pp.ix = us.plan_ix JOIN payment_transactions pt WHERE pt.status='success' AND us.user_ix = ? ORDER BY pt.created_at DESC LIMIT 1");
    $infoStmt->bind_param("s",$userIx);
    $infoStmt->execute();

    $infoResult = $infoStmt->get_result();
    if ($infoResult->num_rows > 0) {
        $infoRow = $infoResult->fetch_assoc(); // 결과에서 한 행을 가져옴
    }

    if($infoRow['is_trial']==1){
        $plan = "체험형(만료일 : ".$infoRow['ends_at'].")";
    }else if($infoRow['status']=='active'){
        $plan = $infoRow['name']."(만료일 : ".$infoRow['ends_at'].")";
    }else if($infoRow['status']=='expired'){
        $plan = "기간만료(만료일 : ".$infoRow['ends_at'].")";
    }else if($infoRow['status']=='cancelled'){
        $plan = "취소";
    }else{
        $plan = "d";
    }

    if($infoRow['method']=='card'){
        $method = "카드";
    }

    $nextPayDate = date('Y-m-d',strtotime($infoRow['ends_at']."-1 day"));

    $historyStmt = $conn->prepare("SELECT pt.method,pt.paid_at,pt.created_at,pt.status,pp.name,pt.amount,pp.duration_days FROM payment_transactions pt JOIN payment_plans pp ON pp.price = pt.amount WHERE pt.user_ix=? ORDER BY pt.created_At DESC;");
    $historyStmt->bind_param("s",$userIx);
    $historyStmt->execute();

    $result = $historyStmt->get_result();
    if ($result->num_rows > 0) {
        while ($historyRow = $result->fetch_assoc()) {
            $historyResult[] = $historyRow;
        }
    }
    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container mt-5">
            <div class="shadow rounded-4 border-0">
                <div class="card-body p-5">
                    <h4 class="mb-4 fw-semibold">결제 내역</h4>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">이용 요금제</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($plan)?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">결제수단</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($method)?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">결제금액</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars(number_format($infoRow['amount']))?>원</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">다음 결제예정일</label>
                            <div class="form-control-plaintext fs-6"><?=htmlspecialchars($nextPayDate)?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-muted">결제내역</label>
                        <?php
                        if(isset($historyResult)){ ?>
                        <div class="main-content m-0 mt-2" id="saved_margin_list" style="display:block; height: 350px; overflow-y: auto;">
                            <div class="d-flex fw-bold text-secondary gap-2 px-2 py-2 border-bottom bg-light rounded-top small">
                                <div class="text-left" style="width: 25%;">결제 날짜</div>
                                <div class="text-left" style="width: 10%;">결제 요금제</div>
                                <div class="text-left" style="width: 20%;">결제금액</div>
                                <div class="text-left" style="width: 15%;">결제수단</div>
                                <div class="text-left" style="width: 15%;">결제상태</div>
                                <div class="text-left" style="width: 15%;">이용기간</div>
                            </div>
                            <?php
                            foreach($historyResult as $historyRow) {

                                if($historyRow['method']=='card'){
                                    $historyMethod = "카드";
                                }

                                if($historyRow['status']=='success'){
                                    $historyStatus = '결제완료';
                                }else if($historyRow['status']=='fail'){
                                    $historyStatus = '결제실패';
                                }if($historyRow['status']=='pending'){
                                    $historyStatus = '결제진행중';
                                }

                            ?>
                            <div class="d-flex align-items-center gap-2 border rounded p-2 mb-1">
                                <!-- 나머지 항목들 (텍스트) -->
                                <span class="small text-left" style="width: 25%;"><?=htmlspecialchars($historyRow['paid_at'])?></span> 
                                <span class="small text-left" style="width: 10%;"><?=htmlspecialchars($historyRow['name'])?></span> 
                                <span class="small text-left" style="width: 20%;"><?=htmlspecialchars(number_format($historyRow['amount']))?></span>     
                                <span class="small text-left" style="width: 15%;"><?=htmlspecialchars($historyMethod)?></span>      
                                <span class="small text-left" style="width: 15%;"><?=htmlspecialchars($historyStatus)?></span>      
                                <span class="small text-left" style="width: 15%;"><?=htmlspecialchars($historyRow['duration_days'])?>일</span>
                            </div>
                        </div>
                        <?php } }?>    
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
