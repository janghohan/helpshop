<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <title>주문 관리</title>
    <style>
        body {
            background-color: #f9f9f9;
        }
        .search-options, .summary-cards, .filter-options {
            padding: 20px;
            margin-bottom: 20px;
        }
        .summary-cards .card {
            border: none;
            background: #f9f9f9;
            border-radius: 10px;
            text-align: center;
            font-size: 0.9rem;
            width: 30%;
        }
        .summary-cards .card h5 {
            margin: 10px 0;
        }
        .summary-cards .card .value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .table-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
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
    <!-- 헤더 -->
   

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>주문 조회</h2>
                <div class="container mt-4">
                    <!-- Search Options -->
                    <div class="search-options">
                        <div class="row justify-content-between ">
                            <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" id="datepicker" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-primary w-100" id="excel-btn">주문 엑셀 등록</button>
                            </div>
                            <!-- <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">이번 주</button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">지난 주</button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">이번 달</button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-outline-secondary w-100">지난 달</button>
                            </div> -->
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <select class="form-select" id="order-filter">
                                    <option value="">주문번호</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="주문번호 검색" id="order-search">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="search-btn">조회하기</button>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="summary-cards d-flex justify-content-between">
                        <div class="card">
                            <h5>총 결제금액(상품)</h5>
                            <div class="value">0</div>
                        </div>
                        <div class="card">
                            <h5>총 결제금액(택배비)</h5>
                            <div class="value">0</div>
                        </div>
                        <div class="card">
                            <h5>총 예상순수익</h5>
                            <div class="value">0</div>
                        </div>
                    </div>

                    <!-- Filter Options and Table -->
                    <div class="filter-options">
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option>결제상태</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option>전체 주문처</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary w-100">주문제품 펼쳐보기</button>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn btn-custom">엑셀 출력</button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Table -->
                    <div class="table-container">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>판매처</th>
                                <th>주문일시</th>
                                <th>주문번호</th>
                                <th>주문종류</th>
                                <th>주문제품</th>
                                <th>수량</th>
                                <th>주문금액</th>
                                <th>택배비</th>
                            </tr>
                            </thead>
                            <tbody id="order-list">
                            <!-- Order rows will be added dynamically -->
                                <tr>
                                    <td>네이버</td>
                                    <td>2025-01-13</td>
                                    <td>2025011134451</td>
                                    <td>인터넷</td>
                                    <td>다미끼 랜스 롱 지그</td>
                                    <td>3개</td>
                                    <td>15,000원</td>
                                    <td>3,000원</td>
                                </tr><tr>
                                    <td>네이버</td>
                                    <td>2025-01-13</td>
                                    <td>2025011134451</td>
                                    <td>인터넷</td>
                                    <td>다미끼 랜스 롱 지그</td>
                                    <td>3개</td>
                                    <td>15,000원</td>
                                    <td>3,000원</td>
                                </tr>
                                <tr>
                                    <td>네이버</td>
                                    <td>2025-01-13</td>
                                    <td>2025011134451</td>
                                    <td>인터넷</td>
                                    <td>다미끼 랜스 롱 지그</td>
                                    <td>3개</td>
                                    <td>15,000원</td>
                                    <td>3,000원</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./order-tmp-list.php" id="orderExcelForm" method="post" enctype="multipart/form-data">
                        <select name="orderExcelType" class="form-control" id="">
                            <option value="naver">네이버 파일</option>
                            <option value="coupang">쿠팡 파일</option>
                        </select>
                        <input type="file" name="orderExcelFile" class="form-control mt-3"  accept=".xlsx, .xls">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="tmpExcel()">등록</button>
                </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="./js/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="./js/product.js"></script>
    <script src="./js/common.js"></script>
    
    <script>

        function tmpExcel(){
            $("#orderExcelForm").submit();
        }

        $("#excel-btn").click(function(){
            modalOpen("excelModal");
        });

        $(document).ready(function() {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'ko'
            });

        });

       
    </script>
</body>
</html>
