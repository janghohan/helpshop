<?php
include ("../dbConnect.php");

$type = isset($_POST['type']) ? $_POST['type'] : '';
$now = date('Y-m-d H:i:s');

$data['msg'] = 'none';

if($type=="create"){
    $marketName = isset($_POST['marketName']) ? $_POST['marketName'] : '';
    $basicFee = isset($_POST['basicFee']) ? $_POST['basicFee'] : 0;
    $linkedFee = isset($_POST['linkedFee']) ? $_POST['linkedFee'] : 0;
    $shipFee = isset($_POST['shipFee']) ? $_POST['shipFee'] : 0;

    $ins_sql = "INSERT INTO market(user_ix,market_name,basic_fee,linked_fee,ship_fee,market_c_time) VALUES('1','$marketName','$basicFee','$linkedFee','$shipFee','$now')";
    if(mysqli_query($conn,$ins_sql)){
        $data['msg'] = "suc";
    }else{
        $data['msg'] = "fail";
    }

}

echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>