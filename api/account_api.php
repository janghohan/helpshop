<?php
session_start();
include '../dbConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = $_SESSION['user_ix'] ?? '1';

    $accoutIx = $_POST['accountIx'] ?? '';
    $memo = $_POST['memo'] ?? '';

    $updateStmt = $conn->prepare("UPDATE account SET memo = ? WHERE user_ix = ? AND ix = ?");
    $updateStmt->bind_param("sss",$memo,$userIx, $accoutIx);
    if(!$updateStmt->execute()){
        echo json_encode(['status'=>'fail', 'msg'=>'update Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }else{
        echo json_encode(['status'=>'success'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
}
?>