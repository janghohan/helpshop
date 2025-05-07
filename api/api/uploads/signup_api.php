<?php
include '../dbConnect.php';

$type = $_POST['type'] ?? '';

if($type=="idCheck"){
    $user_id = $_POST['user_id'] ?? '';

    $checkStmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $checkStmt->bind_param("s",$user_id);
    if($checkStmt->execute()){
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $status = "exist";
        }else{
            $status = "none";
        }
        
    }else{
        $status = "db fail";
    }

    $data['status'] = $status; 
    echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}else if($type=="nickCheck"){
    $nickname = $_POST['nickname'] ?? '';

    $checkStmt = $conn->prepare("SELECT * FROM user WHERE name = ?");
    $checkStmt->bind_param("s",$nickname);
    if($checkStmt->execute()){
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $status = "exist";
        }else{
            $status = "none";
        }
        
    }else{
        $status = "db fail";
    }

    $data['status'] = $status; 
    echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}


?>