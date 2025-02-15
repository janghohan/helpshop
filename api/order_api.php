<?php
session_start();
include '../dbConnect.php';

require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    // JSON 문자열로 받은 데이터를 파싱
    $orderType = $_POST['orderType'] ?? '';
    $fileType = $_POST['fileType'] ?? '';

    if($orderType=='single'){
        $orderDate = $_POST['orderDate'] ?? date("Y-m-d");
        $orderNames = $_POST['orderName'] ?? '';
        $orderNumbers = $_POST['orderNumber'] ?? '';
        $orderCosts = $_POST['orderCost'] ?? '1';
        $orderPrices = $_POST['orderPrice'] ?? '';
        $orderQuantitys = $_POST['orderQuantity'] ?? 1;
        $orderMarkets = $_POST['orderMarket'] ?? '';
        $orderShipping = $_POST['orderShipping'] ?? 0;

        $totalShipping = 0;
        $totalPrice = 0;
        $globalOrderNumber = generateOrderNumber($userIx);

        //총 택배비와 총 상품금액 구하기
        foreach ($orderNames as $index => $orderName){
            $totalPrice = $totalPrice + (changeTextToIntForMoney($orderPrices[$index]) * changeTextToIntForMoney($orderQuantitys[$index]));
            $totalShipping = $totalShipping + changeTextToIntForMoney($orderShipping[$index]);

        }

        // orders 테이블에 저장
        $orderStmt = $conn->prepare("INSERT INTO orders(global_order_number,order_number,order_date,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?)");
        $orderStmt->bind_param("sssssss", $globalOrderNumber,$orderNumbers[1],$orderDate,$orderMarkets[1],$userIx,$totalPrice,$totalShipping);
        if(!$orderStmt->execute()){
            $response['status'] = 'fail';
            $response['msg'] = 'order Stmt Fail';
        }
        

        $ordersIx = $orderStmt->insert_id;
        $ordersResult = $orderStmt->get_result();

        //order_detail 테이블 저장
        foreach ($orderNames as $index => $orderName){
            if($index==0) continue;

            $orderPrice = changeTextToIntForMoney($orderPrices[$index]) * $orderQuantitys[$index];
            $orderCost = changeTextToIntForMoney($orderCosts[$index]);
            $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,?,?,?)");
            $orderDetailStmt->bind_param("ssiii",$ordersIx,$orderName,$orderCost,$orderQuantitys[$index],$orderPrice);
            if(!$orderDetailStmt->execute()){
                $response['status'] = 'fail';
                $response['msg'] = 'orderDetail Stmt Fail';
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
            $dataA = $xlsxA->rows();
        } else {
            echo "Error reading Excel A: " . SimpleXLSX::parseError();
            exit;
        }

        $groupedOrders = [];

        // 진행률 바
        $_SESSION['orderDumpProgress'] = 0;

        $isOrderNumberStart = false;
        $totalDetails = 0; //details의 progress바 진행률 확인을 위한 주문 리스트 총 숫자

        foreach ($dataA as $indexA => $rowA) {   

            if($marketName=='네이버'){
                if($fileType=='realtime'){
                    $orderNumber = $rowA[1];
                    $orderDate = $rowA[17];
                    $orderName = $rowA[19]." / ".$rowA[22];
                    $orderQuantity = $rowA[24];
                    $orderPrice = $rowA[30];
                    $orderShipping = $rowA[40];
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
                    $orderName = $rowA[16]." / ".$rowA[19];
                    $orderQuantity = $rowA[21];
                    $orderPrice = $rowA[27]; // 수량 * 낱개 금액
                    $orderShipping = $rowA[35];
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
                $orderNumber = $rowA[2];
                $orderDate = $rowA[9];
                $orderName = $rowA[12];
                $orderQuantity = $rowA[22];
                $orderPrice = $rowA[23];
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
                $orderStmt = $conn->prepare("INSERT IGNORE INTO orders(global_order_number,order_number,order_date,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?)");
                $orderStmt->bind_param("sssssss", $globalOrderNumber,$orderNumber,$data['order_date'],$orderMarketIx,$userIx,$data['total_payment'],$data['total_shipping']);
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
            $currentDetail = 0;
            $isOrderNumberStart = false; //실제 주문번호부터 데이터를 저장하기 위한 구별 변수
            foreach ($dataA as $indexA => $rowA) {     
                if($rowA[1]=='주문번호'){
                    $isOrderNumberStart = true;
                    continue;
                }
                if(!$isOrderNumberStart) {
                    continue;
                }

                if (in_array($rowA[1], $duplicateOrders)) {
                    $currentDetail++;
                    $_SESSION['orderDumpProgress'] = 70 + intval((30 * $currentDetail) / $totalDetails);
                    continue;
                }

                if($marketName=='네이버'){
                    if($fileType=='realtime'){
                        $orderNumber = $rowA[1];
                        $orderDate = $rowA[17];
                        $orderName = $rowA[19]." / ".$rowA[22];
                        $orderQuantity = $rowA[24];
                        $orderPrice = $rowA[30]; // 수량 * 가격
                        $orderShipping = $rowA[40];
                        $currentOrderNumber = $rowA[1]; // 현재 주문번호

                    }else if($fileType=='ex'){
                        $orderNumber = $rowA[1];
                        $orderDate = $rowA[10];
                        $orderName = $rowA[16]." / ".$rowA[19];
                        $orderQuantity = $rowA[21];
                        $orderPrice = $rowA[27]; // 수량 * 낱개 금액
                        $orderShipping = $rowA[35];
                        $currentOrderNumber = $rowA[1]; // 현재 주문번호
    
                    }

                }else if($marketName=='쿠팡'){
                    $orderNumber = $rowA[2];
                    $orderDate = $rowA[9];
                    $orderName = $rowA[12];
                    $orderQuantity = $rowA[22];
                    $orderPrice = $rowA[23];
                    $orderShipping = $rowA[20];
                    $currentOrderNumber = $rowA[2]; // 현재 주문번호
                }

                

                $zeroCost = 0;

                $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,?,?,?)");
                $orderDetailStmt->bind_param("ssiii",$orderIxMap[$orderNumber],$orderName,$zeroCost,$orderQuantity,$orderPrice);
                $orderDetailStmt->execute();
                
                $currentDetail++;
                $_SESSION['orderDumpProgress'] = 70 + intval((30 * $currentDetail) / $totalDetails);
                // $orderDetailStmt->close();
            }
        }   catch (Exception $e) {
            // 롤백
            $conn->rollback();
            $_SESSION['orderDumpProgress'] = -1;
            echo "오류 발생: " . $e->getMessage();
        }    
        
        $conn->close();

        $response['msg'] = 200;
        echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
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

?>