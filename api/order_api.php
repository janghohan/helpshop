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
    $orderType = isset($_POST['type']) ? $_POST['type'] : '';

    if($orderType=='single'){
        $orderDate = isset($_POST['orderDate']) ? $_POST['orderDate'] : date("Y-m-d");
        $orderNames = isset($_POST['orderName']) ? $_POST['orderName'] : '';
        $orderCosts = isset($_POST['orderCost']) ? $_POST['orderCost'] : '1';
        $orderPrices = isset($_POST['orderPrice']) ? $_POST['orderPrice'] : '';
        $orderQuantitys = isset($_POST['orderQuantity']) ? $_POST['orderQuantity'] : 1;
        $orderMarkets = isset($_POST['orderMarket']) ? $_POST['orderMarket'] : '';
        $orderShipping = isset($_POST['orderShipping']) ? $_POST['orderShipping'] : 0;

        $totalShipping = 0;
        $totalPrice = 0;
        $globalOrderNumber = generateOrderNumber($userIx);
        foreach ($orderNames as $index => $orderName){
            $totalPrice = $totalPrice + (changeTextToIntForMoney($orderPrices[$index]) * changeTextToIntForMoney($orderQuantitys[$index]));
            $totalShipping = $totalShipping + changeTextToIntForMoney($orderShipping[$index]);

        
        }

        $orderStmt = $conn->prepare("INSERT INTO orders(global_order_number,order_number,order_date,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?)");
        $orderStmt->bind_param("sssssss", $globalOrderNumber,$globalOrderNumber,$orderDate,$orderMarkets[0],$userIx,$totalPrice,$totalShipping);
        $orderStmt->execute();
        

        $ordersIx = $orderStmt->insert_id;
        $ordersResult = $orderStmt->get_result();

        foreach ($orderNames as $index => $orderName){
            if($index==0) continue;

            $orderPrice = changeTextToIntForMoney($orderPrices[$index]);
            $orderCost = changeTextToIntForMoney($orderCosts[$index]);
            $orderDetailStmt = $conn->prepare("INSERT INTO order_details(orders_ix,name,cost,quantity,price) VALUES(?,?,?,?,?)");
            $orderDetailStmt->bind_param("ssiii",$ordersIx,$orderName,$orderCost,$orderQuantitys[$index],$orderPrice);
            $orderDetailStmt->execute();
        }
        
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

            $totalOrders = count($groupedOrders);
            $currentOrder = 0;
            $orderIndex = 0;
            foreach($groupedOrders as $orderNumber => $data){
                
                $orderIndex ++;
                $globalOrderNumber = generateOrderNumber($userIx).$orderIndex;
                $orderStmt = $conn->prepare("INSERT INTO orders(global_order_number,order_number,order_date,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?)");
                $orderStmt->bind_param("sssssss", $globalOrderNumber,$orderNumber,$data['order_date'],$orderMarketIx,$userIx,$data['total_payment'],$data['total_shipping']);
                $orderStmt->execute();
                $conn->commit(); // 성공 시 커밋
                
                // if (!$orderStmt->execute()) {
                //     echo "Error: " . $orderStmt->error;
                // }
        
                $ordersIx = $orderStmt->insert_id;
                $ordersResult = $orderStmt->get_result();

                $orderIxMap[$orderNumber] = $ordersIx;
                
                $currentOrder++;
                $_SESSION['orderDumpProgress'] = 30 + intval((40 * $currentOrder) / $totalOrders);
                
            }
            //order_details table

            $currentDetail = 0;
            $isOrderNumberStart = false; //실제 주문번호부터 데이터를 저장하기 위한 구별 변수
            foreach ($dataA as $indexA => $rowA) {     
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

                if($orderNumber=='주문번호'){
                    $isOrderNumberStart = true;
                    continue;
                }
                if(!$isOrderNumberStart) {
                    continue;
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