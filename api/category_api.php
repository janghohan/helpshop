<?php
session_start();
include '../dbConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = $_SESSION['user_ix'] ?? '1';
    $type = $_POST['type'] ?? '';

    if($type=='create'){
        $categoryName = $_POST['categoryName'] ?? '';

        $categoryStmt = $conn->prepare("INSERT INTO category(user_ix, name) VALUES(?,?)");
        $categoryStmt->bind_param("ss",$userIx,$categoryName);
        if(!$categoryStmt->execute()){
            echo json_encode(['status'=>'fail', 'msg'=>'category Create Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            $categoryIx = $categoryStmt->insert_id;
            echo json_encode(['status'=>'success', 'ix'=>$categoryIx], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }else if($type=='delete'){
        $categoryIx = $_POST['categoryIx'];

        $categoryStmt = $conn->prepare("DELETE FROM category WHERE user_ix=? AND ix=?");
        $categoryStmt->bind_param("ss",$userIx,$categoryIx);
        if(!$categoryStmt->execute()){
            echo json_encode(['status'=>'fail', 'msg'=>'category delete Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(['status'=>'success'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }

}
?>