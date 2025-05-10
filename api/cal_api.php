<?php
session_start();
include '../dbConnect.php';

require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

$userIx = $_SESSION['user_ix'] ?? '1';
$type = $_POST['type'] ?? '';
if($type=='create'){
    $cost = changeTextToIntForMoney($_POST['cost'] ?? 0);
    $shipCost = changeTextToIntForMoney($_POST['shipCost'] ?? 0);
    $quantity = changeTextToIntForMoney($_POST['quantity'] ?? 0);
    $feeRate = $_POST['feeRate'] ?? 0;
    $expense = changeTextToIntForMoney($_POST['expense'] ?? 0);
    $price = changeTextToIntForMoney($_POST['price'] ?? 0);
    $shipPrice = changeTextToIntForMoney($_POST['shipPrice'] ?? 0);
    $profit = changeTextToIntForMoney($_POST['profit'] ?? 0);
    $marginRate = $_POST['marginRate'] ?? 0;

    $calStmt = $conn->prepare("INSERT INTO margin_list(user_ix,cost,ship_cost,quantity,fee_rate,expense,price,ship_price,profit,margin_rate) VALUES(?,?,?,?,?,?,?,?,?,?)");
    $calStmt->bind_param("ssssssssss",$userIx, $cost,$shipCost,$quantity,$feeRate,$expense,$price,$shipPrice,$profit,$marginRate);

    $response['type'] = 'create';
    if($calStmt->execute()){
        $response['status'] = 'success';
        $calIx = $calStmt->insert_id;
        $response['cal'] = $calIx;
    }else{
        $response['status'] = 'fail';
    }
    echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

}else if($type=='update'){
    $cal = $_POST['cal'] ?? '';
    $productName = $_POST['productName'] ?? '';

    $calStmt = $conn->prepare("UPDATE margin_list SET product_name=? WHERE user_ix=? AND ix=?");
    $calStmt->bind_param("sss",$productName,$userIx,$cal);

    $response['type'] = 'update';
    if($calStmt->execute()){
        $response['status'] = 'success';
    }else{
        $response['status'] = 'fail';
    }
    echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}
else if($type=='delete'){
    $cal = $_POST['cal'] ?? '';

    $calStmt = $conn->prepare("DELETE FROM margin_list WHERE user_ix=? AND ix=?");
    $calStmt->bind_param("ss",$userIx,$cal);

    $response['type'] = 'delete';
    if($calStmt->execute()){
        $response['status'] = 'success';
    }else{
        $response['status'] = 'fail';
    }
    echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}


function changeTextToIntForMoney($moneyText) {
    return (Int)str_replace(",", "", $moneyText);

}



?>