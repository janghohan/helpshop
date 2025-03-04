<?php
session_start();
include '../dbConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $type = $_POST['type'] ?? '';

    if($type=='create'){
        $memoTitle = $_POST['memoTitle'] ?? '';

        $memoStmt = $conn->prepare("INSERT INTO memo(user_ix, title) VALUES(?,?)");
        $memoStmt->bind_param("ss",$userIx,$memoTitle);
        if(!$memoStmt->execute()){
            echo json_encode(['status'=>'fail', 'msg'=>'memo Create Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            $memoIx = $memoStmt->insert_id;
            echo json_encode(['status'=>'success', 'ix'=>$memoIx], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }else if($type=='delete'){
        $memoIx = $_POST['memoIx'];

        $memoStmt = $conn->prepare("DELETE FROM memo WHERE user_ix=? AND ix=?");
        $memoStmt->bind_param("ss",$userIx,$memoIx);
        if(!$memoStmt->execute()){
            echo json_encode(['status'=>'fail', 'msg'=>'memo delete Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(['status'=>'success'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }else if($type=='get'){
        $memoIx = $_POST['memoIx'];
        $memoTitle = $_POST['memoTitle'];

        $memoStmt = $conn->prepare("SELECT * FROM memo WHERE user_ix=? AND ix=? AND title=?");
        $memoStmt->bind_param("sss",$userIx,$memoIx,$memoTitle);
        if(!$memoStmt->execute()){
            echo json_encode(['status'=>'fail', 'msg'=>'memo delete Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            $memoResult = $memoStmt->get_result();
            if ($memoResult->num_rows > 0) {
                $memoRow = $memoResult->fetch_assoc(); // 결과에서 한 행을 가져옴
                $memoContents = $memoRow['contents'];
            }
            echo json_encode(['status'=>'success','contents'=>$memoContents], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }else if($type=='edit'){
        $memoIx = $_POST['memoIx'];
        $memoContents = $_POST['memoContents'];

        $memoStmt = $conn->prepare("UPDATE memo SET contents=? WHERE user_ix =? AND ix=?");
        $memoStmt->bind_param("sss",$memoContents,$userIx,$memoIx);
        if(!$memoStmt->execute()){
            echo json_encode(['status'=>'fail', 'msg'=>'memo delete Stmt Fail'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{       
            echo json_encode(['status'=>'success'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }

}
?>