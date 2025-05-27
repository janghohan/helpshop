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

    .plan-header {
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
        <div class="container mt-5">
            <div class="plan-header">
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
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
