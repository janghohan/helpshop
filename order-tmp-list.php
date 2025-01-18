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

        @media (min-width: 1400px) {
            .container{
                max-width: 98%;
                font-size: 14px;
            }
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

        $orderMarketIx = isset($_POST['orderMarketIx']) ? $_POST['orderMarketIx'] : ''; //마켓 ix

        //네이버 파일인지 쿠팡파일인지 확인
        $marketQuery = "SELECT market_name FROM market WHERE user_ix='$user_ix' AND ix='$orderMarketIx'";
        $result = $conn->query($marketQuery);
        $row = $result->fetch_assoc();
        $marketName = $row['market_name'];

        
        if (isset($_FILES['orderExcelFile'])) {
            // 파일 경로
            $filePath = $_FILES['orderExcelFile']['tmp_name'];

            $fileUploadPath = 'tmpUploads/'.date("Ymd")."/".$marketName.$userIx."orderExcel.xls" ;

            //저장위치 -> tmpUploads에 그날 date -> 폴더 없으면 생성성
            $destinationFolder = "./tmpUploads/".date("Ymd");
            if (!is_dir($destinationFolder)) {
                if (!mkdir($destinationFolder, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                    die("폴더를 생성할 수 없습니다: $destinationFolder");
                }
            }

            move_uploaded_file($_FILES['orderExcelFile']['tmp_name'], "./".$fileUploadPath);
            // $fileBPath = $_FILES['fileB']['tmp_name'];
            // $fileBPath = './cjBasic.xlsx';
            
    
            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($fileUploadPath)) {
                $dataA = $xlsxA->rows();
            } else {
                echo "Error reading Excel A: " . SimpleXLSX::parseError();
                exit;
            }
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
                <div class="d-flex justify-content-between">
                    <h2>주문 리스트</h2>
                    <div class="d-flex">
                        <button class="btn btn-secondary me-3" id="excel-btn">엑셀 재등록</button>
                        <button class="btn btn-primary" id="upload-btn">주문등록</button>
                    </div>
                </div>
                <p>확인용으로 보여지는 리스트입니다. 최종 주문으로 등록하시려면 왼쪽 상단 "주문등록" 버튼을 눌러주세요.</p>
                <div class="container mt-4">
                    <!-- Order Table -->
                    <div class="table-container">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>판매처</th>
                                <th>주문일시</th>
                                <th>주문번호</th>
                                <th>주문제품</th>
                                <th>수량</th>
                                <th>주문금액</th>
                                <th>택배비</th>
                            </tr>
                            </thead>
                            <tbody id="order-list">
                            <!-- Order rows will be added dynamically -->
                            <?php
                            $previousOrderNumber = null; // 이전 주문번호를 저장
                            $toggle = true; // 색상을 변경하기 위한 토글 변수

                            if(isset($dataA)){
                                foreach ($dataA as $indexA => $rowA) {
                                    if($indexA===0){
                                        continue;
                                    }         

                                    if($marketName=='네이버'){
                                        $orderNumber = $rowA[1];
                                        $orderDate = $rowA[17];
                                        $orderName = $rowA[19]." / ".$rowA[22];
                                        $orderQuantity = $rowA[24];
                                        $orderPrice = $rowA[30];
                                        $orderShipping = $rowA[40];
                                        $currentOrderNumber = $rowA[1]; // 현재 주문번호
                                    }else if($marketName=='쿠팡'){
                                        $orderNumber = $rowA[2];
                                        $orderDate = $rowA[9];
                                        $orderName = $rowA[12];
                                        $orderQuantity = $rowA[22];
                                        $orderPrice = $rowA[23];
                                        $orderShipping = $rowA[20];
                                        $currentOrderNumber = $rowA[2]; // 현재 주문번호
                                    }
                                    
                                
                                    if ($currentOrderNumber !== $previousOrderNumber) {
                                        // 주문번호가 변경될 때마다 토글 값을 변경
                                        $toggle = !$toggle;
                                    }
                                    $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                                    $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신


                                
                                ?>
                                    <tr style="background-color: <?= $backgroundColor ?>;">
                                        <td><?=htmlspecialchars($marketName)?></td>
                                        <td><?=htmlspecialchars($orderDate)?></td>
                                        <td><?=htmlspecialchars($orderNumber)?></td>
                                        <td><?=htmlspecialchars($orderName)?></td>
                                        <td><?=htmlspecialchars($orderQuantity)?></td>
                                        <td><?=htmlspecialchars($orderPrice)?></td>
                                        <td><?=htmlspecialchars($orderShipping)?></td>
                                    </tr>

                            <?php }}?>
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
                        <select name="orderMarketIx" class="form-control" id="">
                            <?php
                                $searchResult = [];
                                
                                $query = "SELECT * FROM market WHERE user_ix='$user_ix'";
                                $result = $conn->query($query);
                        
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $searchResult[] = $row;
                                    }
                                }

                                foreach($searchResult as $marketRow){
                            
                            ?>
                                <option value="<?=htmlspecialchars($marketRow['ix'])?>"><?=htmlspecialchars($marketRow['market_name'])?></option>
                            <?php }?>
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
        <form action="./api/order_api.php" id="orderListForm" method="post" style="display:none;">
            <input type="hidden" name="type" class="form-control mt-3"  value="dump">
            <input type="hidden" name="orderMarketIx" class="form-control" value="<?=$orderMarketIx?>">
            <input type="hidden" name="orderExcelFile" class="form-control mt-3"  value="<?=$fileUploadPath?>">
        </form>


        <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">주문 등록 진행률</h1>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" id="progress-bar" aria-label="Example with label" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>
                    <div class="text-primary mt-2" id="ok-text" style="display:none;">주문 등록이 완료되었습니다.</div>
                    <div class="text-danger mt-2" id="error-text" style="display:none;">문제가 발생했습니다. 관리자에게 문의해주세요.
                        <p>중복된 주문은 등록할 수 없습니다.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.href='./order-tmp-list.php'">새로 등록하기</button>
                    <button type="button" class="btn btn-primary" onclick="location.href='./order.php'">목록 보기</button>
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

    
    <script>
        var interval;
        $(document).ready(function() {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'ko'
            });

        });
        
        // 주문 등록 버튼
        $("#upload-btn").click(function(){
            // $("#orderListForm").submit();
            modalOpen("progressModal");
            checkProgress();
            $.ajax({
                url: './api/order_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType : 'json',
                data: $("#orderListForm").serialize(),
                success: function(response) { 
                    // console.log(response);

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        });

        //엑셀 등록 버튼
        $("#excel-btn").click(function(){
            modalOpen("excelModal");
        });

        // 엑셀 모달에서 등록버튼
        function tmpExcel(){
            $("#orderExcelForm").submit();
        }

        function checkProgress(){
            interval = setInterval(() => {
                console.log("1");
                fetch('./api/progress_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ type: 'orderList' }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    // console.log("response",response);
                    return response.json(); // JSON 변환 시도
                })
                .then(data => {
                    if(data.progress < 0){
                        clearInterval(interval);
                        $("#error-text").css("display","block");
                    }
                    const progressBarInner = document.getElementById('progress-bar');
                    progressBarInner.style.width = data.progress + '%';
                    progressBarInner.textContent = data.progress + '%';

                    if (data.progress >= 100) {
                        clearInterval(interval);
                        $("#ok-text").css("display","block");
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
            }, 500); // 3초마다 상태 확인
        }
       

       
    </script>
</body>
</html>
