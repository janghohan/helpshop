<?php
include ("../dbConnect.php");

$type = isset($_POST['type']) ? $_POST['type'] : '';
$now = date('Y-m-d H:i:s');
$userIx = $_SESSION['user_ix'] ?? '1';

if($type=="create"){
    header('Content-Type: application/json');
    $marketName = $_POST['marketName'] ?? '';
    $basicFee = $_POST['basicFee'] ?? 0;
    $linkedFee = $_POST['linkedFee'] ?? 0;
    $shipFee = $_POST['shipFee'] ?? 0;

    $marketStmt = $conn->prepare("INSERT INTO market(user_ix,market_name,basic_fee,linked_fee,ship_fee,market_c_time) VALUES(?,?,?,?,?,?)");
    $marketStmt->bind_param("ssssss",$userIx,$marketName,$basicFee,$linkedFee,$shipFee,$now);
    if($marketStmt->execute()){
        $data['status'] = "suc";
    }else{
        $data['status'] = 'fail';
        $data['msg'] = 'create fail';
    }

}else if($type=="edit"){
    $marketIx = $_POST['market_ix'] ?? '';
    $basicFee = $_POST['basicFee'] ?? 0;
    $linkedFee = $_POST['linkedFee'] ?? 0;
    $shipFee = $_POST['shipFee'] ?? 0;

    $marketStmt = $conn->prepare("UPDATE market SET basic_fee=?, linked_fee=?, ship_fee=? WHERE user_ix =? AND ix = ?");
    $marketStmt->bind_param("sssss",$basicFee,$linkedFee,$shipFee,$userIx,$marketIx);
    if($marketStmt->execute()){
        $data['status'] = "suc";
    }else{
        $data['status'] = 'fail';
        $data['msg'] = 'edit fail';
    }
    

}else if($type=="delete"){

}

ob_clean();
echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>