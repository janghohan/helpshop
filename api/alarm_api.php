<?php
session_start();
include '../dbConnect.php';
header('Content-Type: application/json');

$userIx = $_SESSION['user_ix'] ?? '1';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON 문자열로 받은 데이터를 파싱

    $type =  $_POST['type'] ?? ''; // option-edit 구별 타입입

    //옵션삭제 
    if($type=='alarmDel'){
        $alarmIx = $_POST['alarmIx'] ?? '';

        if($alarmIx=='a'){
            $deleteStmt = $conn->prepare("DELETE FROM stock_alarm WHERE user_ix=?");
            $deleteStmt->bind_param("s",$userIx);
        }else{
            $deleteStmt = $conn->prepare("DELETE FROM stock_alarm WHERE user_ix =? AND ix=?");
            $deleteStmt->bind_param("ss",$userIx,$alarmIx);
        }
        
        if(!$deleteStmt->execute()){
            $response['status'] = 'fail';
            $response['msg'] = '알람 삭제 실패';
        }else{
            $response['status'] = 'success';
            $response['msg'] = '알람 삭제 성공';
        }
        

        echo json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

   
    }
    
} else {
    echo "Invalid request!";
}


?>