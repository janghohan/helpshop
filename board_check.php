<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>계산기</title>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    // include './dbConnect.php';

    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container">
            <div class="main-content d-flex align-items-center justify-content-center vh-100">
                <form class="card shadow p-4 bg-white" style="width: 100%; max-width: 400px;">
                    <h5 class="mb-4">비밀번호 확인</h5>
                    <div class="mb-3">
                    <label for="checkPassword" class="form-label">비밀번호</label>
                    <input type="password" class="form-control" id="checkPassword">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">확인</button>
                </form>
            </div>
            <div class="modal fade" id="martketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">마켓 등록</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="newMarketForm" method="post">
                            <input type="hidden" name="type" value="create">
                            <p class="mt-4 mb-1 mb-n2">마켓명을 입력하세요.</p>
                            <select name="marketName" id="marketName" class="form-control">
                                <option value="네이버">네이버</option>
                                <option value="쿠팡">쿠팡</option>
                            </select>
                            <p class="mt-4 mb-1 mb-n2">수수료 입력(숫자만 입력하세요)</p>
                            <div class="row">
                                <div class="col-4">
                                    <input type="text" name="basicFee" class="form-control" placeholder="기본 수수료(%)">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="linkedFee" class="form-control" placeholder="연동 수수료(%)">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="shipFee" class="form-control" placeholder="배송 수수료(%)">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="newMarketCreate()">등록</button>
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        $(".calRow input").attr("disabled",true);
    });
    

    
    
</script>
</html>
