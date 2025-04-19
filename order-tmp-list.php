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
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    
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
            height: 650px;
            overflow-y: auto;
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

        $orderMarketIx = $_POST['orderMarketIx'] ?? ''; //마켓 ix
        $fileType = $_POST['fileType'] ?? '';

        //네이버 파일인지 쿠팡파일인지 확인
        $marketQuery = "SELECT market_name FROM market WHERE user_ix='$user_ix' AND ix='$orderMarketIx'";
        $result = $conn->query($marketQuery);
        $row = $result->fetch_assoc();
        $marketName = $row['market_name'];

        
        if (isset($_FILES['orderExcelFile'])) {
            // 파일 경로
            $filePath = $_FILES['orderExcelFile']['tmp_name'];

            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($filePath)) { //비번x -> 파일 열리면 

                $fileUploadPath = 'tmpUploads/'.date("Ymd")."/".$marketName.$userIx."orderExcel.xlsx" ;
                //저장위치 -> tmpUploads에 그날 date -> 폴더 없으면 생성성
                $destinationFolder = "./tmpUploads/".date("Ymd");
                if (!is_dir($destinationFolder)) {
                    if (!mkdir($destinationFolder, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                        die("폴더를 생성할 수 없습니다: $destinationFolder");
                    }
                }
                move_uploaded_file($_FILES['orderExcelFile']['tmp_name'], "./".$fileUploadPath);
                $xlsxA = SimpleXLSX::parse($fileUploadPath);
                $dataA = $xlsxA->rows();
            } else {
                $pwd = "0000";

                $inputFile = 'tmpUploads/'.date("Ymd")."/ex".$marketName.$userIx.'orderExcel.xlsx';
                $outputFile = 'tmpUploads/'.date("Ymd")."/".$marketName.$userIx.'orderExcel.xlsx';

                $destinationFolder = "./tmpUploads/".date("Ymd");
                if (!is_dir($destinationFolder)) {
                    if (!mkdir($destinationFolder, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                        die("폴더를 생성할 수 없습니다: $destinationFolder");
                    }
                }

                // 파일 이동 (업로드 처리)
                if (!move_uploaded_file($filePath, $inputFile)) {
                    die("파일 업로드 실패");
                }
                // Python 스크립트 실행 : 비밀번호 삭제
                exec("python ./api/unlock_excel.py $inputFile $outputFile $pwd", $output, $return_var);

                $xlsxA = SimpleXLSX::parse($outputFile);
                $dataA = $xlsxA->rows();

                $fileUploadPath = 'tmpUploads/'.date("Ymd")."/".$marketName.$userIx."orderExcel.xlsx" ;
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
                <p>확인용으로 보여지는 리스트입니다. 
                    <br>최종 주문으로 등록하시려면 왼쪽 상단 "주문등록" 버튼을 눌러주세요.
                    <br>취소한 주문의 경우 엑셀에서 삭제 후 등록하거나, 최종 주문등록 후 삭제할 수 있습니다.
                </p>
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
                                if(!($marketName=='쿠팡' && $fileType=='ex')){ 
                                    foreach ($dataA as $indexA => $rowA) {
                                        if($indexA===0){
                                            continue;
                                        }         

                                        if($marketName=='네이버'){
                                            if($fileType=='realtime'){
                                                $orderNumber = $rowA[1];
                                                $orderDate = $rowA[17];
                                                $orderName = $rowA[19]." / ".$rowA[23];
                                                $orderQuantity = $rowA[25];
                                                $price = $rowA[32];
                                                $orderShipping = $rowA[41];
                                                $currentOrderNumber = $rowA[1]; // 현재 주문번호
                                            }else if($fileType=='ex'){
                                                //구매확정내역
                                                $orderNumber = $rowA[1];
                                                $orderDate = $rowA[10];
                                                $orderName = $rowA[16]." / ".$rowA[20];
                                                $orderQuantity = $rowA[22];
                                                $price = $rowA[28]; // 수량 * 낱개 금액
                                                $orderShipping = $rowA[36];
                                                $currentOrderNumber = $rowA[1]; // 현재 주문번호

                                            }

                                            if(is_numeric($orderQuantity)){
                                                $orderPrice = ceil((int)$price / (int)$orderQuantity);
                                            }else{
                                                $orderPrice = $price;
                                            }
                                            

                                            // $orderPrice = (int)$price / (int)$orderQuantity;

                                        }else if($marketName=='쿠팡'){
                                            if($fileType=='realtime'){
                                                $orderNumber = $rowA[2];
                                                $orderDate = $rowA[9];
                                                $orderName = $rowA[12];
                                                $orderQuantity = $rowA[22];
                                                $orderPrice = $rowA[23];
                                                $orderShipping = $rowA[20];
                                                $currentOrderNumber = $rowA[2]; // 현재 주문번호
                                            }

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

                                <?php }
                                }else if($marketName=='쿠팡' && $fileType=='ex'){ 
                                    //결제일 기준 정산현황
                                    
                                    $groupedOrders = []; // 주문을 저장할 배열

                                    $previousOrderNumber = null; // 이전 주문번호를 저장
                                    foreach ($dataA as $indexA => $rowA) {
                                        if($indexA===0){
                                            continue;
                                        }     

                                        $orderNumber = $rowA[0]; //주문번호
                                       
                                        $currentOrderNumber = $orderNumber;

                                        //settlement dashboard(24-07 이전 주문 엑셀)에서는 optionId가 -1값이다.
                                        $optionID = $rowA[4];
                                        if($optionID==-1){
                                            $optionID = $rowA[6];
                                            $optionID = str_replace("<","",$optionID);
                                            $optionID = str_replace(">","",$optionID);
                                            $orderDate = $rowA[29];
                                            $orderQuantity = $rowA[9];
                                            $orderPrice = $rowA[11];
                                            $orderName = str_replace('"','',$rowA[7]);
                                            $refundQuantity = $rowA[10]; //환불수량
                                        }else{
                                            $optionID = str_replace("<","",$optionID);
                                            $optionID = str_replace(">","",$optionID);
                                            $orderDate = $rowA[19];
                                            $orderQuantity = $rowA[7];
                                            $orderPrice = $rowA[9];
                                            $orderName = str_replace('"','',$rowA[5]);
                                            $refundQuantity = $rowA[8]; //환불수량
                                        }
                                    

                                        if ($currentOrderNumber !== $previousOrderNumber) {
                                            // 주문번호가 변경될 때
                                            $toggle = !$toggle;

                                            //is_numberic : 처음이 기본배송료인 경우, ($orderName!="" && $refundQuantity<0) : 처음이 상품명이지만 반품인경우우
                                            if(!is_numeric($optionID) || ($orderName!="" && $refundQuantity<0) || ($orderName!="" && $orderQuantity==0)){
                                                //이름 없는 반품
                                                $orderName = "반품";
                                                $groupedOrders[$orderNumber] = [
                                                    'order_number' => $orderNumber,
                                                    'shipping_fee' => 0,
                                                    'order_date' => $orderDate,
                                                    'order_name' => [$orderName],    // 상품명 배열
                                                    'order_quantity' => [0], // 수량 배열
                                                    'order_price' => [0],    // 가격 배열
                                                ];

                                                if(!is_numeric($optionID)){
                                                    $groupedOrders[$orderNumber]['shipping_fee'] = (int)$orderPrice + (int)$groupedOrders[$orderNumber]['shipping_fee'];
                                                }
                                                
                                            }else{
                                                $groupedOrders[$orderNumber] = [
                                                    'order_number' => $orderNumber,
                                                    'shipping_fee' => 0,
                                                    'order_date' => $orderDate,
                                                    'order_name' => [],    // 상품명 배열
                                                    'order_quantity' => [], // 수량 배열
                                                    'order_price' => [],    // 가격 배열
                                                ];
                                            }

                                            
                                        }
                                        

                                        if($orderName!="" && $orderQuantity>0){ //상품
                                            $groupedOrders[$orderNumber]['order_name'][] = $orderName;
                                            $groupedOrders[$orderNumber]['order_quantity'][] = $orderQuantity;
                                            $groupedOrders[$orderNumber]['order_price'][] = ceil((int)$orderPrice / (int)$orderQuantity);
                                        }else{ //배송비
                                            if($orderName!="반품"){
                                                $groupedOrders[$orderNumber]['shipping_fee'] = (int)$orderPrice + (int)$groupedOrders[$orderNumber]['shipping_fee'];
                                            }
                                        }
                                        
                                        
                                        

                                        $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신
                                    
                                    }

                                    $previousOrderNumber = null; // 이전 주문번호를 저장
                                    foreach($groupedOrders as $orderNumber => $data){
                                        if($data['order_name'][0]=="반품"){
                                            continue;
                                        }
                                        $currentOrderNumber = $data['order_number'];
                                        if ($currentOrderNumber !== $previousOrderNumber) {
                                            // 주문번호가 변경될 때마다 토글 값을 변경
                                            $toggle = !$toggle;
                                        }
                                        
                                        $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                                        $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신

                                        foreach($data['order_name'] as $index => $eachOrderName){
                                            
                                            if($index>0){
                                                $data['shipping_fee'] = 0;
                                            }
                                ?>
                                    <tr style="background-color: <?= $backgroundColor ?>;">
                                        <td><?=htmlspecialchars($marketName)?></td>
                                        <td><?=htmlspecialchars($data['order_date'])?></td>
                                        <td><?=htmlspecialchars($data['order_number'])?></td>
                                        <td><?=htmlspecialchars($eachOrderName)?></td>
                                        <td><?=htmlspecialchars($data['order_quantity'][$index])?></td>
                                        <td><?=htmlspecialchars($data['order_price'][$index])?></td>
                                        <td><?=htmlspecialchars($data['shipping_fee'])?></td>
                                    </tr>
                                    <?php } } ?>
                                <?php 
                                }
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <form action="./api/order_api.php" id="orderListForm" method="post" style="display:none;">
            <input type="hidden" name="orderType" class="form-control mt-3"  value="dump">
            <input type="hidden" name="fileType" value="<?=$fileType?>">
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

            console.log($("#orderListForm").serialize());

            $.ajax({
                url: './api/order_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType : 'json',
                data: $("#orderListForm").serialize(),
                success: function(response) { 
                    console.log(response);

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
