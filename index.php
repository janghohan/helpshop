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
    <style>
        .hero { background-color: #f8f9fa; padding: 100px 0; text-align: center; }
        .section { padding: 100px 0; }
        .section img { max-width: 100%; height: auto; border-radius: 12px; }
        .footer { background-color: #2f3b7e; color: #fff; padding: 40px 0; }
        .footer a { color: #bbb; text-decoration: none; }
        #main-section p{
            margin-bottom: 0 !important;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
	include './sidebar.html';
    include './dbConnect.php';

    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container" id="main-section">
			<!-- Hero Banner -->
            <section class="hero">
                <div class="container">
                <h1 class="display-5 fw-bold">현직 네이버,쿠팡 셀러가 직접만든 프로그램</h1>
                <p class="lead mt-3">엑셀로 주문 업로드하고, 송장까지 처리되는 올인원 시스템</p>
                <p class="lead mt-3">상품 관리는 기본, 손익현황까지 한번에</p>
                <a href="./login.php" class="btn btn-primary btn-lg mt-4">무료 체험 시작하기</a>
                </div>
            </section>
            <!-- Section 1: Text Left, Image Right -->
            <section class="section">
                <div class="container">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="col-md-5">
                        <h2>마진율은 확실하게</h2>
                        <p>언제 어디서나 마진율을 계산하고 <span class="font-primary">저장</span>할 수 있습니다.</p>
                        <p>마켓별 마진율 관리로 손해보는일은 절대 없도록!</p>
                    </div>
                    <div class="col-md-5">
                        <img src="./img/main/cal.png" alt="Excel Upload">
                    </div>
                </div>
                </div>
            </section>

            <section class="section bg-light">
                <div class="container">
                <div class="d-flex justify-content-around align-items-center flex-md-row-reverse">
                    <div class="col-md-5">
                        <h2>주문등록은 빠르게</h2>
                        <p>실시간 주문뿐 아니라, 이전 주문까지 등록하세요.</p>
                        <p>연동은 안되지만, 주문 제한은 없습니다.</p>
                    </div>
                    <div class="col-md-5">
                        <img src="./img/main/order.png" alt="Excel Upload">
                    </div>
                </div>
                </div>
            </section>

            <!-- Section 1: Text Left, Image Right -->
            <section class="section">
                <div class="container">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="col-md-5">
                        <h2>송장 관리는 간편하게</h2>
                        <p>기존처럼 하나하나 주문 넣을 필요 없이, 엑셀로 한 번에 송장을 입력하세요.</p>
                        <p>10건이든 1000건이든 3분안에 처리하세요.</p>
                    </div>
                    <div class="col-md-5">
                        <img src="./img/main/taekbae.png" alt="Excel Upload">
                    </div>
                </div>
                </div>
            </section>

            <!-- Section 2: Image Left, Text Right -->
            <section class="section bg-light">
                <div class="container">
                <div class="d-flex justify-content-around align-items-center flex-md-row-reverse">
                    <div class="col-md-5">
                        <h2>상품 및 재고 관리는 간단하게</h2>
                        <p>상품을 등록하고 주문마다 빠지는 재고를 관리하세요.</p>
                        <p>부족한 재고는 알림 기능으로 빠르게 확인하세요.</p>
                    </div>
                    <div class="col-md-5">
                        <img src="./img/main/product.png" alt="Excel Upload">
                    </div>
                </div>
                </div>
            </section>

            <section class="section">
                <div class="container">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="col-md-5">
                        <h2>손익은 확실하게</h2>
                        <p>순이익을 잘못 계산하면 나도 모르는 사이 손해를 볼 수 있습니다.</p>
                        <p>지출관리와 수수료별 순이익까지 한번에 확인하세요.</p>
                    </div>
                    <div class="col-md-5">
                        <img src="./img/main/profit.png" alt="Excel Upload">
                    </div>
                </div>
                </div>
            </section>

            <!-- Section 3: Text Left, Image Right -->
            

            <!-- Footer -->
            <footer class="footer text-center">
                <div class="container">
                <p class="mb-1">© 2025 빠른택배솔루션</p>
                <p class="mb-0"><a href="#">이용약관</a> · <a href="#">개인정보처리방침</a> · <a href="#">문의하기</a></p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
