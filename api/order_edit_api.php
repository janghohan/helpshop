<?php
session_start();
include '../dbConnect.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    // JSON 문자열로 받은 데이터를 파싱
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $orderList = isset($_POST['checkList']) ? $_POST['checkList'] : '';

    if($type=='orderCancel'){

        foreach($orderList as $orderIx){

            $updateCancelStmt = $conn->prepare("UPDATE order_details SET status='cancelled' WHERE ix=?");
            $updateCancelStmt->bind_param("s", $orderIx);
            if(!$updateCancelStmt->execute()){
                throw new Exception("Error executing orderCancel statement: " . $updateCancelStmt->error); // *** 수정 ***
            }

        }
        echo json_encode(['status' => 'success', 'message' => 'op update processed successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    // echo json_encode(['status1'=>$type, 'status' => $orderList, 'message' => 'op update processed successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    // $orderDate = isset($_POST['orderDate']) ? $_POST['orderDate'] : date("Y-m-d");
    // $orderNames = isset($_POST['orderName']) ? $_POST['orderName'] : '';
    // $orderCosts = isset($_POST['orderCost']) ? $_POST['orderCost'] : '1';
    // $orderPrices = isset($_POST['orderPrice']) ? $_POST['orderPrice'] : '';
    // $orderQuantitys = isset($_POST['orderQuantity']) ? $_POST['orderQuantity'] : 1;
    // $orderMarkets = isset($_POST['orderMarket']) ? $_POST['orderMarket'] : '';
    // $orderShipping = isset($_POST['orderShipping']) ? $_POST['orderShipping'] : 0;

    // $totalShipping = 0;
    // $totalPrice = 0;
    // $globalOrderNumber = generateOrderNumber($userIx);
    // foreach ($orderNames as $index => $orderName){
    //     $totalPrice = $totalPrice + (changeTextToIntForMoney($orderPrices[$index]) * changeTextToIntForMoney($orderQuantitys[$index]));
    //     $totalShipping = $totalShipping + changeTextToIntForMoney($orderShipping[$index]);

    
    // }

    // $orderStmt = $conn->prepare("INSERT INTO orders(global_order_number,order_number,order_date,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?)");
    // $orderStmt->bind_param("sssssss", $globalOrderNumber,$globalOrderNumber,$orderDate,$orderMarkets[0],$userIx,$totalPrice,$totalShipping);
    // $orderStmt->execute();
    

    // echo "<script>alert('등록이 완료되었습니다.'); location.href='../single-order.php';</script>";
 
} else {
    echo json_encode(['status' => 'fail', 'message' => 'Invalid request!'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}


function generateOrderNumber($userId) {
    $timestamp = date('YmdHis'); // 현재 시간
    return $timestamp . sprintf('%04d', $userId); // 예: 202401011230451001
}

function changeTextToIntForMoney($moneyText) {
    return (Int)str_replace(",", "", $moneyText);

}

?>