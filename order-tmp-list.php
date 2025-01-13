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

    require_once __DIR__ . '/api/SimpleXLSX.php'; // 실제 경로 확인
    require_once __DIR__ . '/api/SimpleXLSXGen.php'; // 실제 경로 확인

    use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
    use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음
    
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $orderExcelType = isset($_POST['orderExcelType']) ? $_POST['orderExcelType'] : '';

        echo  $orderExcelType;
        if (isset($_FILES['orderExcelFile'])) {
            // 파일 경로
            $filePath = $_FILES['orderExcelFile']['tmp_name'];
            // $fileBPath = $_FILES['fileB']['tmp_name'];
            // $fileBPath = './cjBasic.xlsx';
            
    
            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($filePath)) {
                $dataA = $xlsxA->rows();
            } else {
                echo "Error reading Excel A: " . SimpleXLSX::parseError();
                exit;
            }
        }

        
        //네이버 파일
        if($orderExcelType=='naver'){
            $marketName = "네이버";
            
        }
    }else{
        
    }

    function generateOrderNumber($userId) {
        $timestamp = date('YmdHis'); // 현재 시간
        return $timestamp . sprintf('%04d', $userId); // 예: 202401011230451001
    }
    
    
    ?>
    <!-- 헤더 -->
   

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>주문 리스트</h2>
                <div class="container mt-4">
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
                            <?php
                            foreach ($dataA as $indexA => $rowA) {
                                if($indexA===0){
                                    continue;
                                }         
                                // 1 : 주문번호, 10 : 구매자, 17 : 주문일, 24 : 수량, 30 : 할인후 옵션별 주문금액, 40 : 배송비, 51 : 구매자연락처, 19:상품명, 22:옵션
                                // $name = $rowA[12]; // A 파일의 수취인 컬럼 값
                                // $phone = $rowA[46]; // A 파일의 전화번호 컬럼 값
                                // $code = $rowA[52]; // A 파일의 우편번호 컬럼 값
                                // $address = $rowA[48]; // A 파일의 주소 값
                                // $memo = $rowA[53]; // A 파일의 배송메세지 컬럼 값
                
                
                                // $dataB[$indexA] = []; //초기화
                
                
                                // $dataB[$indexA-1][0] = $name;
                                // $dataB[$indexA-1][1] = $phone;
                                // $dataB[$indexA-1][2] = "";
                                // $dataB[$indexA-1][3] = $address;
                                // $dataB[$indexA-1][4] = "극소";
                                // $dataB[$indexA-1][5] = "낚시용품";
                                // $dataB[$indexA-1][6] = $memo;
                
                                // $combinedData[] = ["네이버",$rowA[24], $rowA[19]." : ".$rowA[22], $rowA[12], extractMiddlePhoneNumber($rowA[46]),$rowA[48],$rowA[53]];
                
                
                                // $tmpInsertStmt = $conn->prepare("INSERT INTO temp_orders(market_ix,order_number,order_date,user_ix,payment,shipping,product_name,quantity,buyer_name,buyer_phone,address) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                                // $tmpInsertStmt->bind_param("sssssssssss",$)
                
                                // $productStmt = $conn->prepare("INSERT INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
                                // $productStmt->bind_param("sssss",$userIx,$accountIx,$categoryIx,$productName,$productMemo);
                                // $productStmt->execute();
                            
                            
                            ?>
                                <tr>
                                    <td><?=htmlspecialchars($marketName)?></td>
                                    <td><?=htmlspecialchars($rowA[17])?></td>
                                    <td><?=htmlspecialchars($rowA[1])?></td>
                                    <td>인터넷</td>
                                    <td><?=htmlspecialchars($rowA[19])." / ".htmlspecialchars($rowA[22])?></td>
                                    <td><?=htmlspecialchars($rowA[24])?></td>
                                    <td><?=htmlspecialchars($rowA[30])?></td>
                                    <td><?=htmlspecialchars($rowA[40])?></td>
                                </tr>

                            <?php }?>
                                <!-- <tr>
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
                                </tr> -->
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
                    <form action="./order-tmp-list.php" id="orderExcelForm" method="post">
                        <input type="file">
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
