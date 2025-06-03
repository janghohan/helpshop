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
    body {
        background-color: #f9f9f9;
        color: #333;
    }
    .hero {
      background-color: #2f3b7e;
      color: #fff;
      padding: 60px 20px;
      text-align: center;
    }
    .hero h1 {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .hero p {
      font-size: 1.2rem;
    }
    .btn-primary {
      background-color: #2f3b7e;
      border-color: #2f3b7e;
    }
    .feature-icon {
      font-size: 2rem;
      color: #2f3b7e;
    }
    .pricing-card {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .cta {
      background-color: #eef0fa;
      padding: 40px 20px;
      text-align: center;
    }

    /* .plan-header {
      margin-bottom: 20px;
      font-size: 18px;
      color: #333;
    }
    .plans {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: space-between;
    }
    .plan {
      flex: 1;
      min-width: 200px;
      max-width: 220px;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
    }
    .plan h3 {
      margin-top: 0;
      font-size: 20px;
      color: #1d2d6b;
    }
    .price {
      font-size: 24px;
      color: #e60023;
      margin: 10px 0;
    }
    .old-price {
      text-decoration: line-through;
      font-size: 14px;
      color: #999;
    }
    .feature-list {
      font-size: 14px;
      color: #333;
      text-align: left;
      margin-top: 10px;
    }
    .feature-list li {
      margin-bottom: 6px;
    }
    .btn {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #2f3b7e;
      color: white;
      border: none;
      border-radius: 5px;
      text-decoration: none;
    }
    .comparison-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 40px;
      font-size: 14px;
      background: #fff;
    }
    .comparison-table th, .comparison-table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    .comparison-table th {
      background-color: #f0f0f0;
    }
    .highlight {
      color: #2f3b7e;
      font-weight: bold;
    } */
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
        <div class="container">
            <!-- Hero Section -->
            <section class="hero">
                <div class="container">
                <h1>5일 무료 체험 제공</h1>
                <p>간편한 택배처리, 지금 무료로 시작해보세요.</p>
                <a href="./signup.php" class="btn btn-light mt-3">무료 체험 시작하기</a>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-5">
                <div class="container">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="feature-icon mb-2">🧮</div>
                        <p>마진율, ROAS 계산기</p>
                    </div>
                    <div class="col-md-3">
                        <div class="feature-icon mb-2">🛒</div>
                        <p>상품 재고 관리</p>
                    </div>
                    <div class="col-md-3">
                        <div class="feature-icon mb-2">📊</div>
                        <p>손익 관리</p>
                    </div>
                    <div class="col-md-3">
                        <div class="feature-icon mb-2">🚚</div>
                        <p>마켓 → 택배 자동 등록</p>
                    </div>
                </div>
                </div>
            </section>

            <!-- Pricing Plan -->
            <section id="pricing" class="py-5">
                <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                    <div class="pricing-card text-center">
                        <h3>스마트 플랜</h3>
                        <p class="fs-4 fw-bold">월 9,800원</p>
                        <p class="text-muted">5일 무료 체험 포함</p>
                        <ul class="list-unstyled mb-4">
                            <li>✅ 마진율, ROAS 계산기</li>
                            <li>✅ 상품등록 무제한</li>
                            <li>✅ 주문등록 무제한</li>
                            <li>✅ 부족 재고 알림</li>
                            <li>✅ 원스톱 송장입력</li>
                            <li>✅ 순익 및 지출관리</li>
                        </ul>
                        <a href="./signup.php" class="btn btn-primary">지금 가입하기</a>
                    </div>
                    </div>
                </div>
                </div>
            </section>

            <!-- Final CTA -->
            <section class="cta">
                <div class="container">
                <h4>5일 무료 체험을 지금 시작해보세요!</h4>
                <a href="./signup.php" class="btn btn-primary mt-3">무료 체험 시작</a>
                </div>
            </section>
            <!-- <div class="plan-header">
            현재 요금제: <strong>Trial</strong> (연동 쇼핑몰: 0개, 30일 주문수: 0건)
            </div>

            <div class="plans">
            <div class="plan">
                <h3>BASIC</h3>
                <div>30일 주문수: <span class="highlight">1,000건</span></div>
                <ul class="feature-list">
                <li>쇼핑몰 연동: 1개</li>
                <li>대시보드</li>
                <li>정산금 캘린더</li>
                </ul>
                <div class="old-price">22,000원</div>
                <div class="price">19,800원/월 (10%↓)</div>
                <a class="btn" href="#">월 정기 결제</a>
            </div>

            <div class="plan">
                <h3>PRO 3</h3>
                <div>30일 주문수: <span class="highlight">3,000건</span></div>
                <ul class="feature-list">
                <li>쇼핑몰 연동: 3개</li>
                <li>매출정산 상세분석</li>
                <li>리포트 알림</li>
                <li>BASIC의 모든 기능</li>
                </ul>
                <div class="old-price">44,000원</div>
                <div class="price">39,600원/월 (10%↓)</div>
                <a class="btn" href="#">월 정기 결제</a>
            </div>

            <div class="plan">
                <h3>PRO 10</h3>
                <div>30일 주문수: <span class="highlight">10,000건</span></div>
                <ul class="feature-list">
                <li>쇼핑몰 연동: 10개</li>
                <li>주문 상세 분석</li>
                <li>로켓고등 정산 연동</li>
                <li>PRO3의 모든 기능</li>
                </ul>
                <div class="old-price">110,000원</div>
                <div class="price">99,000원/월 (10%↓)</div>
                <a class="btn" href="#">월 정기 결제</a>
            </div>

            <div class="plan">
                <h3>PRO 20</h3>
                <div>30일 주문수: <span class="highlight">20,000건</span></div>
                <ul class="feature-list">
                <li>쇼핑몰 연동: 20개</li>
                <li>전담 매니저 지원</li>
                <li>PRO10의 모든 기능</li>
                </ul>
                <div class="old-price">220,000원</div>
                <div class="price">198,000원/월 (10%↓)</div>
                <a class="btn" href="#">월 정기 결제</a>
            </div>

            <div class="plan">
                <h3>PRO 30</h3>
                <div>30일 주문수: <span class="highlight">30,000건</span></div>
                <ul class="feature-list">
                <li>쇼핑몰 연동: 30개</li>
                <li>전담 매니저 지원</li>
                <li>PRO20의 모든 기능</li>
                </ul>
                <div class="old-price">330,000원</div>
                <div class="price">297,000원/월 (10%↓)</div>
                <a class="btn" href="#">월 정기 결제</a>
            </div>
            </div>

            <table class="comparison-table">
                <thead>
                    <tr>
                    <th>기능</th>
                    <th>BASIC</th>
                    <th>PRO 3</th>
                    <th>PRO 10</th>
                    <th>PRO 20</th>
                    <th>PRO 30</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>30일 주문 수 (최대)</td>
                    <td>1,000건</td>
                    <td>3,000건</td>
                    <td>10,000건</td>
                    <td>20,000건</td>
                    <td>30,000건</td>
                    </tr>
                    <tr>
                    <td>쇼핑몰 연동 수 (최대)</td>
                    <td>1개</td>
                    <td>3개</td>
                    <td>10개</td>
                    <td>20개</td>
                    <td>30개</td>
                    </tr>
                    <tr>
                    <td>일 데이터 수집수 (최대)</td>
                    <td>3회</td>
                    <td>9회</td>
                    <td>30회</td>
                    <td>60회</td>
                    <td>90회</td>
                    </tr>
                    <tr>
                    <td>종합 대시보드</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    </tr>
                    <tr>
                    <td>정산금 캘린더</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    </tr>
                    <tr>
                    <td>원가 관리 (손익 계산)</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    <td>✔</td>
                    </tr>
                </tbody>
            </table>
        </div> -->
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
