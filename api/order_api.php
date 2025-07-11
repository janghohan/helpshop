<?php
session_start();
include '../dbConnect.php';

require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음
header('Content-Type: application/json');
$userIx = $_SESSION['user_ix'] ?? '1';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON 문자열로 받은 데이터를 파싱
    $orderType = $_POST['orderType'] ?? '';
    $fileType = $_POST['fileType'] ?? '';

    if($orderType=='single'){
        $orderDate = $_POST['orderDate'] ?? date("Y-m-d");
        $orderMarket = $_POST['orderMarket'] ?? date("Y-m-d");

        $orderNames = $_POST['orderName'] ?? '';
        $orderCosts = $_POST['orderCost'] ?? '1';
        $orderPrices = $_POST['orderPrice'] ?? '';
        $orderQuantitys = $_POST['orderQuantity'] ?? 1;
        $shipPrice = $_POST['shipPrice'] ?? 0;

        $totalShipping = $shipPrice;
        $totalPrice = 0;
        $globalOrderNumber = generateOrderNumber($userIx);

        $orderNumber = $_POST['orderNumber'] ?? $globalOrderNumber;
        // 빈 문자열일 경우 처리
        if($orderNumber=="") $orderNumber = $globalOrderNumber;

        //총 상품금액 구하기
        foreach ($orderNames as $index => $orderName){
            $totalPrice = $totalPrice + (changeTextToIntForMoney($orderPrices[$index]) * changeTextToIntForMoney($orderQuantitys[$index]));
        }

        // orders 테이블에 저장
        $orderStmt = $conn->prepare("INSERT INTO orders(global_order_number,order_number,order_date,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?)");
        $orderStmt->bind_param("sssssss", $globalOrderNumber,$orderNumber,$orderDate,$orderMarket,$userIx,$totalPrice,$totalShipping);
        if(!$orderStmt->execute()){
            $response['status'] = 'fail';
            $response['msg'] = 'order Stmt Fail';

            echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

            
        }
        

        $ordersIx = $orderStmt->insert_id;
        $ordersResult = $orderStmt->get_result();

        //order_detail 테이블 저장
        foreach ($orderNames as $index => $orderName){

            $orderPrice = changeTextToIntForMoney($orderPrices[$index]);
            $orderCost = changeTextToIntForMoney($orderCosts[$index]);
            $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,?,?,?)");
            $orderDetailStmt->bind_param("ssiii",$ordersIx,$orderName,$orderCost,$orderQuantitys[$index],$orderPrice);
            if(!$orderDetailStmt->execute()){
                $response['status'] = 'fail';
                $response['msg'] = 'orderDetail Stmt Fail';

                // echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            }else{
                $detailIx = $orderDetailStmt->insert_id;

                $accountStmt = $conn->prepare("SELECT * FROM account WHERE user_ix=?");
                $accountStmt->bind_param("s",$userIx);
                $accountStmt->execute();
                $accountResult = $accountStmt->get_result();
                if ($accountResult->num_rows > 0) {
                    $accountRow = $accountResult->fetch_assoc(); // 결과에서 한 행을 가져옴
                    $accountIx = $accountRow['ix'];
                }

                $categoryStmt = $conn->prepare("SELECT * FROM category WHERE user_ix=?");
                $categoryStmt->bind_param("s",$userIx);
                $categoryStmt->execute();
                $categoryResult = $categoryStmt->get_result();
                if ($categoryResult->num_rows > 0) {
                    $categoryRow = $categoryResult->fetch_assoc(); // 결과에서 한 행을 가져옴
                    $categoryIx = $categoryRow['ix'];
                }

                // matching_name table 저장
                $matchingStmt = $conn->prepare("INSERT IGNORE INTO matching_name(user_ix,category_ix,account_ix,matching_name,cost) VALUES(?,?,?,?,?)");
                $matchingStmt->bind_param("sssss",$userIx,$categoryIx,$accountIx,$orderName,$orderCost);
                $matchingStmt->execute();

                //matching_name에 값이 있는경우
                if ($matchingStmt->affected_rows === 0) {
                    $stockStmt = $conn->prepare("UPDATE matching_name SET stock = stock - ? WHERE matching_name = ? AND user_ix=?");
                    $stockStmt->bind_param("sss", $orderQuantitys[$index], $orderName,$userIx);
                    if ($stockStmt->execute()) {
                        $status = true;
                        $msg = "재고 차감 성공";

                        //알람 재고 체크
                        stockCheck();
                    } else {
                        $status = true;
                        $msg = "재고 차감 실패";
                    }

                    
                }else{
                    $nameIx = $matchingStmt->insert_id;
                    // db_match table 저장
                    $dbStmt = $conn->prepare("INSERT INTO db_match(user_ix,details_ix,name_of_excel,matching_ix) VALUES(?,?,?,?)");
                    $dbStmt->bind_param("ssss",$userIx,$detailIx,$orderName,$nameIx);
                    $dbStmt->execute();
                }

               

            }
        }

        $response['status'] = 'success';
        echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        
    }else if($orderType=='dump'){
        $orderExcelFile = isset($_POST['orderExcelFile']) ? $_POST['orderExcelFile'] :'';
        $orderMarketIx = isset($_POST['orderMarketIx']) ? $_POST['orderMarketIx'] :'';

        //네이버 파일인지 쿠팡파일인지 확인
        $marketQuery = "SELECT market_name FROM market WHERE user_ix='$userIx' AND ix='$orderMarketIx'";
        $result = $conn->query($marketQuery);
        $row = $result->fetch_assoc();
        $marketName = $row['market_name'];

        // 엑셀 파일 읽기
        if ($xlsxA = SimpleXLSX::parse("../".$orderExcelFile)) {
            $dataA = $xlsxA->rows(0);
        } else {
            $response['error'] =  "Error reading Excel A: ";
            exit;
        }

        $groupedOrders = [];

        // 진행률 바
        $_SESSION['orderDumpProgress'] = 0;

        $isOrderNumberStart = false;
        $totalDetails = 0; //details의 progress바 진행률 확인을 위한 주문 리스트 총 숫자

        if($marketName=='네이버' || ($marketName=='쿠팡' && $fileType=='realtime')){
            foreach ($dataA as $indexA => $rowA) {   

                if($marketName=='네이버'){
                    if($fileType=='realtime'){
                        $orderNumber = $rowA[1];
                        $orderDate = $rowA[17];
                        $orderName = $rowA[19]." / ".$rowA[23];
                        $orderQuantity = $rowA[25];
                        $price = $rowA[32];
                        $orderShipping = $rowA[41];
                        $currentOrderNumber = $rowA[1]; // 현재 주문번호
                        
                        $orderPerson = $rowA[10]; //구매자
                        $orderContact = $rowA[51]; // 구매자연락처
                        $receiver = $rowA[12]; //수취인
                        $receiverContact = $rowA[46]; //수령인 연락처
                        $postCode = $rowA[52]; //우편번호
                        $address = $rowA[49]; //주소
                        $addressDetail = $rowA[50]; //상세주소
    
                    }else if($fileType=='ex'){
                        $orderNumber = $rowA[1];
                        $orderDate = $rowA[10];
                        $orderName = $rowA[16]." / ".$rowA[20];
                        $orderQuantity = $rowA[22];
                        $orderPrice = $rowA[28]; // 수량 * 낱개 금액
                        $orderShipping = $rowA[36];
                        $currentOrderNumber = $rowA[1]; // 현재 주문번호
    
                        $orderPerson = $rowA[7]; //구매자
                        $orderContact = ''; // 구매자연락처
                        $receiver = $rowA[9]; //수취인
                        $receiverContact = ''; //수령인 연락처
                        $postCode = ''; //우편번호
                        $address = ''; //주소
                        $addressDetail = ''; //상세주소

                    }
                    
                    
                }else if($marketName=='쿠팡'){
                    if($fileType=='realtime'){
                        $orderNumber = $rowA[2];
                        $orderDate = $rowA[9];
                        $orderName = $rowA[10].",".$rowA[11];
                        $orderQuantity = $rowA[22];
                        $orderPrice = $rowA[18]; //수량 * 낱개금액
                        $orderShipping = $rowA[20];
                        $currentOrderNumber = $rowA[2]; // 현재 주문번호
    
                        $orderPerson = $rowA[24]; //구매자
                        $orderContact = $rowA[25]; // 구매자연락처
                        $receiver = $rowA[26]; //수취인
                        $receiverContact = $rowA[27]; //수령인 연락처
                        $postCode = $rowA[28]; //우편번호
                        $address = $rowA[29]; //주소
                        $addressDetail = ''; //상세주소
                    }
                }
    
                if($orderNumber=='주문번호'){
                    $isOrderNumberStart = true;
                    continue;
                }
                if(!$isOrderNumberStart) {
                    continue;
                }
    
    
                $totalDetails++;
                if (!isset($groupedOrders[$orderNumber])) {
                    $groupedOrders[$orderNumber] = [
                        'global_order_number' => generateOrderNumber($userIx),
                        'order_date' => '',
                        'total_payment' => 0,
                        'total_shipping' => 0,
                        'order_person' => $orderPerson,
                        'order_contact' => $orderContact,
                        'receiver' => $receiver,
                        'receiver_contact' => $receiverContact,
                        'post_code'=>$postCode,
                        'address' => $address,
                        'address_detail'=>$addressDetail,
                    ];
                }
    
                $_SESSION['orderDumpProgress'] = intval((30 * $indexA) / count($dataA));
    
                $groupedOrders[$orderNumber]['total_payment'] += (Int)$orderPrice;
                if($groupedOrders[$orderNumber]['total_shipping'] == 0){
                    $groupedOrders[$orderNumber]['total_shipping'] += (Int)$orderShipping;
                }
                $groupedOrders[$orderNumber]['order_date'] = $orderDate;
                
    
            }
    
            $conn->begin_transaction();
    
            //orders table
            
            try {
                // $conn->rollback();
                $orderIxMap = [];
                $duplicateOrders = []; //중복된 주문번호는 체크하는 배열
    
                $totalOrders = count($groupedOrders);
                $currentOrder = 0;
                $orderIndex = 0;
                foreach($groupedOrders as $orderNumber => $data){
                    
                    $orderIndex ++;
                    $globalOrderNumber = generateOrderNumber($userIx).$orderIndex;
    
                    // orders 테이블
                    $orderStmt = $conn->prepare("INSERT IGNORE INTO orders(global_order_number,order_number,order_date,order_time,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?,?)");
                    $orderStmt->bind_param("ssssssss", $globalOrderNumber,$orderNumber,$data['order_date'],$data['order_date'],$orderMarketIx,$userIx,$data['total_payment'],$data['total_shipping']);
                    $orderStmt->execute();
                    
                    $ordersIx = $orderStmt->insert_id;
                    if ($orderStmt->affected_rows === 0) {
                        $duplicateOrders[] = $orderNumber;
                        // throw new Exception("중복된 주문으로 인해 삽입되지 않았습니다.");
                    }else{
                    
                        // order_address 테이블
                        $addressStmt = $conn->prepare("INSERT INTO order_address(order_ix,order_name,order_contact,receiver_name,receiver_contact,post_code,address,address_detail) VALUES(?,?,?,?,?,?,?,?)");
                        $addressStmt->bind_param("ssssssss",$ordersIx,$data['order_person'],$data['order_contact'],$data['receiver'],$data['receiver_contact'],$data['post_code'],$data['address'],$data['address_detail']);
                        $addressStmt->execute();
                    }
                    
                    $conn->commit(); // 성공 시 커밋
    
                    $ordersResult = $orderStmt->get_result();
    
                    $orderIxMap[$orderNumber] = $ordersIx;
                    
                    $currentOrder++;
                    $_SESSION['orderDumpProgress'] = 30 + intval((40 * $currentOrder) / $totalOrders);
                    
                }
    
    
                //order_details table
                $testNum = 0;
                $currentDetail = 0;
                $isOrderNumberStart = false; //실제 주문번호부터 데이터를 저장하기 위한 구별 변수
                
                foreach ($dataA as $indexA => $rowA) {     
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
    
                    }else if($marketName=='쿠팡'){
                        if($fileType=='realtime'){
                            $orderNumber = $rowA[2];
                            $orderDate = $rowA[9];
                            $orderName = $rowA[12];
                            $orderQuantity = $rowA[22];
                            $orderPrice = $rowA[23]; //낱개가격
                            $orderShipping = $rowA[20];
                            $currentOrderNumber = $rowA[2]; // 현재 주문번호
        
                        }else if($fileType=='ex'){
                            $orderNumber = $rowA[0];
                            $orderDate = $rowA[19];
                            $orderName = $rowA[5];
                            $orderQuantity = $rowA[7];
                            
                            if($orderQuantity==0){
                                $orderShipping = $rowA[7];
                                $orderPrice = 0;
                            }else if($orderQuantity>0){
                                $orderShipping = 0;
                                $orderPrice = ($rowA[9] / $orderQuantity); //낱개가격
                            }else{
                                $orderPrice = 0;
                            }
                            $currentOrderNumber = $rowA[0]; // 현재 주문번호
        
                        }
    
                    }
    
                    if($orderNumber=='주문번호'){
                        $isOrderNumberStart = true;
                        continue;
                    }
                    if(!$isOrderNumberStart) {
                        continue;
                    }
                    
                    if (in_array($orderNumber, $duplicateOrders)) {
                        $currentDetail++;
                        $_SESSION['orderDumpProgress'] = 70 + intval((30 * $currentDetail) / $totalDetails);
                        continue;
                    }
    
                    
    
                    $zeroCost = 0;
    
                    $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,?,?,?)");
                    $orderDetailStmt->bind_param("ssiii",$orderIxMap[$orderNumber],$orderName,$zeroCost,$orderQuantity,$orderPrice);
                    $orderDetailStmt->execute();

                    //실시간 주문에 대해서만 재고 체크
                    if($fileType=='realtime'){
                        // $stockStmt = $conn->prepare("UPDATE matching_name SET stock = stock -? WHERE ix = ( SELECT mn.ix FROM db_match db
                        //     JOIN matching_name mn ON db.matching_ix = mn.ix WHERE db.name_of_excel = ? AND db.user_ix = ?
                        //     LIMIT 1
                        // )");

                        $stockStmt = $conn->prepare("UPDATE matching_name mn
                            JOIN db_match db ON db.matching_ix = mn.ix
                            SET mn.stock = mn.stock - ?
                            WHERE db.name_of_excel = ? AND db.user_ix = ?
                            LIMIT 1");
                        $stockStmt->bind_param("sss", $orderQuantity, $orderName,$userIx);
                        if ($stockStmt->execute()) {
                            $status = true;
                            $msg = "재고 차감 성공";

                            //알람 재고 체크
                            stockCheck();
                        } else {
                            $status = true;
                            $msg = "재고 차감 실패";
                        }
                    }
                    
                    $currentDetail++;
                    $_SESSION['orderDumpProgress'] = 70 + intval((30 * $currentDetail) / $totalDetails);
                    // $orderDetailStmt->close();
                }
            }   catch (Exception $e) {
                // 롤백
                $conn->rollback();
                $_SESSION['orderDumpProgress'] = -1;
    
                $response['msg'] = 'fail';
                $response['line'] = $e->getLine();
    
                echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            }    
            
            $conn->close();
    
            $response['msg'] = $testNum;

            echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        

        //쿠팡 이전 매출 파일
        }else if($marketName=='쿠팡' && $fileType=='ex'){
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
                    if(!is_numeric($optionID) || ($orderName!="" && $refundQuantity<0)){
                        //이름 없는 반품
                        $orderName = "반품";
                        $groupedOrders[$orderNumber] = [
                            'order_number' => $orderNumber,
                            'total_shipping' => 0,
                            'order_date' => $orderDate,
                            'order_name' => [$orderName],    // 상품명 배열
                            'order_quantity' => [0], // 수량 배열
                            'order_price' => [0],    // 가격 배열
                        ];

                        if(!is_numeric($optionID)){
                            $groupedOrders[$orderNumber]['total_shipping'] = (int)$orderPrice + (int)$groupedOrders[$orderNumber]['total_shipping'];
                        }
                        
                    }else{
                        $groupedOrders[$orderNumber] = [
                            'order_number' => $orderNumber,
                            'total_shipping' => 0,
                            'order_date' => $orderDate,
                            'order_name' => [],    // 상품명 배열
                            'order_quantity' => [], // 수량 배열
                            'order_price' => [],    // 가격 배열
                            'total_payment' => 0,
                        ];
                    }

                    
                }
                

                if($orderName!="" && $orderQuantity>0){ //상품
                    $totalDetails ++;
                    $groupedOrders[$orderNumber]['order_name'][] = $orderName;
                    $groupedOrders[$orderNumber]['order_quantity'][] = $orderQuantity;
                    $groupedOrders[$orderNumber]['order_price'][] = ceil((int)$orderPrice / (int)$orderQuantity);
                    $groupedOrders[$orderNumber]['total_payment'] = (int)$orderPrice + $groupedOrders[$orderNumber]['total_payment'];
                }else{ //배송비
                    if($orderName!="반품"){
                        $groupedOrders[$orderNumber]['total_shipping'] = (int)$orderPrice + (int)$groupedOrders[$orderNumber]['total_shipping'];
                    }
                }
                
                $_SESSION['orderDumpProgress'] = intval((30 * $indexA) / count($dataA));

                $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신

            }

            // 테이블 저장
            $previousOrderNumber = null; // 이전 주문번호를 저장
            $orderIxMap = [];
            $duplicateOrders = []; //중복된 주문번호는 체크하는 배열
            $totalOrders = count($groupedOrders);
            $orderIndex = 0;
            $currentDetail = 0;

            foreach($groupedOrders as $orderNumber => $data){
                if($data['order_name'][0]=="반품"){
                    continue;
                }
                $currentOrderNumber = $data['order_number'];
                if ($currentOrderNumber !== $previousOrderNumber) {
                    // 주문번호가 변경될 때마다 토글 값을 변경
                    $toggle = !$toggle;

                    // order table, order_address table
                    $orderIndex ++;
                    $globalOrderNumber = generateOrderNumber($userIx).$orderIndex;
    
                    // orders 테이블
                    $orderStmt = $conn->prepare("INSERT IGNORE INTO orders(global_order_number,order_number,order_date,order_time,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?,?)");
                    $orderStmt->bind_param("ssssssss", $globalOrderNumber,$orderNumber,$data['order_date'],$data['order_date'],$orderMarketIx,$userIx,$data['total_payment'],$data['total_shipping']);
                    $orderStmt->execute();
                    
                    $ordersIx = $orderStmt->insert_id;
                    if ($orderStmt->affected_rows === 0) {
                        $duplicateOrders[] = $orderNumber;
                        // throw new Exception("중복된 주문으로 인해 삽입되지 않았습니다.");
                    }else{
                    
                        // order_address 테이블
                        $addressStmt = $conn->prepare("INSERT INTO order_address(order_ix) VALUES(?)");
                        $addressStmt->bind_param("s",$ordersIx);
                        $addressStmt->execute();
                    }
                    
                    $conn->commit(); // 성공 시 커밋
    
                    $ordersResult = $orderStmt->get_result();
    
                    $orderIxMap[$orderNumber] = $ordersIx;
                    

                }

                $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                $previousOrderNumber = $currentOrderNumber; // 현재 주문번호를 이전 주문번호로 갱신

                foreach($data['order_name'] as $index => $eachOrderName){
                    
                    if($index>0){
                        $data['shipping_fee'] = 0;
                    }

                    //중복된 주문번호 : order_details에 안넣음음
                    if (in_array($orderNumber, $duplicateOrders)) {
                        $currentDetail++;
                        $_SESSION['orderDumpProgress'] = 70 + intval((30 * $currentDetail) / $totalDetails);
                        continue;
                    }
    
    
                    $zeroCost = 0;
    
                    $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,?,?,?)");
                    $orderDetailStmt->bind_param("ssiii",$orderIxMap[$orderNumber],$eachOrderName,$zeroCost,$data['order_quantity'][$index],$data['order_price'][$index]);
                    $orderDetailStmt->execute();
                    
                    $currentDetail++;
                    $_SESSION['orderDumpProgress'] = 30 + intval((70 * $currentDetail) / $totalDetails);

                }
            }
        
        }else if($marketName=='로켓그로스'){
            $dataB = $xlsxA->rows(1); //로켓그로스 배송비 내역
            $maxRows = max(count($dataA), count($dataB)); // 둘 중 더 많은 행 수만큼 반복
            $totalOrders = $maxRows;
            $currentOrder = 0;
            $duplicateOrders = []; //중복된 주문번호는 체크하는 배열
            $response['status'] = 'success';
            $datePrices = []; // 지출비용 날짜=>총합
            for ($i = 0; $i < $maxRows; $i++) {
                if($dataA[$i][0]!='주정산' || $dataA[$i][17]<0){
                    $currentOrder ++;
                    continue;
                }     

                $orderNumber = $dataA[$i][6]; //주문번호
                
                $currentOrderNumber = $orderNumber;

                //settlement dashboard(24-07 이전 주문 엑셀)에서는 optionId가 -1값이다.

                $orderDate = $dataA[$i][8];
                $orderQuantity = $dataA[$i][17];
                $orderPrice = $dataA[$i][14];
                $orderName = $dataA[$i][12].",".$dataA[$i][13];
                $etc = $dataA[$i][22];
                $orderShip = $dataB[$i][23];
                $totalPayment = $orderPrice * $orderQuantity;

                $globalOrderNumber = generateOrderNumber($userIx).$currentOrder;

                // orders 테이블
                $orderStmt = $conn->prepare("INSERT IGNORE INTO orders(global_order_number,order_number,order_date,order_time,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?,?)");
                $orderStmt->bind_param("ssssssss", $globalOrderNumber,$orderNumber,$orderDate,$orderDate,$orderMarketIx,$userIx,$totalPayment,$orderShip);
                if(!$orderStmt->execute()){
                    $response['status'] = 'fail';
                    $response['msg'] = 'order upload fail';
                }
                
                $ordersIx = $orderStmt->insert_id;
                if ($orderStmt->affected_rows === 0) {
                    $duplicateOrders[] = $orderNumber;
                    // throw new Exception("중복된 주문으로 인해 삽입되지 않았습니다.");
                }else{

                    //날짜별 입출고비를 지출비용으로 등록
                    if (!isset($datePrices[$orderDate])) {
                        $datePrices[$orderDate] = 0;
                    }
                    $datePrices[$orderDate] += $etc;

                    $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,0,?,?)");
                    $orderDetailStmt->bind_param("ssii",$ordersIx,$orderName,$orderQuantity,$orderPrice);
                    if(!$orderDetailStmt->execute()){
                        $response['status'] = 'fail';
                        $response['msg'] = 'detail upload fail';
                    }else{
                        $currentOrder ++;
                    }
                }

                if (in_array($orderNumber, $duplicateOrders)) {
                    $currentOrder++;
                    $_SESSION['orderDumpProgress'] = intval((100 * $currentOrder) / $totalOrders);
                    continue;
                }
                
                // $expenseStmt = $conn->prepare("INSERT INTO expense(user_ix,expense_type,expense_price,expense_memo")

                $conn->commit(); // 성공 시 커밋

                $ordersResult = $orderStmt->get_result();
                $_SESSION['orderDumpProgress'] = intval((100 * $currentOrder) / $totalOrders);
                
    
            }

            //지출비용 등록
            foreach ($datePrices as $date => $totalPrice) {
                $expenseStmt = $conn->prepare("INSERT INTO expense(user_ix,expense_type,expense_price,expense_memo,expense_date) VALUES(?,'로켓그로스',?,'입출고비',?)");
                $expenseStmt->bind_param("sss",$userIx,$totalPrice,$date);
                if(!$expenseStmt->execute()){
                    $response['status'] = 'fail';
                    $response['msg'] = "expense error";
                }
            }
    
            $conn->begin_transaction();   
            $conn->close();
    
            echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
        
    }
    

    // echo "<script>alert('등록이 완료되었습니다.'); location.href='../single-order.php';</script>";
 
} else {
    echo "Invalid request!";
}


function generateOrderNumber($userId) {
    $timestamp = date('YmdHis'); // 현재 시간
    return $timestamp . sprintf('%04d', $userId); // 예: 202401011230451001
}

function changeTextToIntForMoney($moneyText) {
    return (Int)str_replace(",", "", $moneyText);

}

function stockCheck(){
    global $conn;
    global $userIx;
    // 1. 알림이 필요한 상품 조회

    $alarmStmt = $conn->prepare("SELECT mn.ix AS matching_ix FROM matching_name mn WHERE mn.user_ix=? AND mn.stock < mn.alarm_stock 
    AND NOT EXISTS(SELECT * FROM stock_alarm sa WHERE sa.matching_ix = mn.ix AND sa.is_resolved=0)");
    $alarmStmt->bind_param("s",$userIx);
    $alarmStmt->execute();

    $result = $alarmStmt->get_result();

    // 2. 알림 생성
    $now = date("Y-m-d H:i:s");
    $insertStmt = $conn->prepare("INSERT INTO stock_alarm (user_ix,matching_ix, alarm_type, created_at) VALUES (?,?, 'stock', '$now')");

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $matching_ix = $row['matching_ix'];

        $insertStmt->bind_param("ii",  $userIx,$matching_ix);
        $insertStmt->execute();
        $count++;
    }

    $alarmStmt->close();
    $insertStmt->close();

}

?>