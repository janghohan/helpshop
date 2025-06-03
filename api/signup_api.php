<?php
session_start();
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

}else if($type=="sendCode"){
    $phone = $_POST['phone'] ?? '';
    
    if (!preg_match('/^01[016789]\d{7,8}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => '잘못된 휴대폰 번호']);
        exit;
    }

    // 랜덤 6자리 인증번호 생성
    // $code = rand(100000, 999999);
    $code = "123456";

    // 실제 문자 발송 로직 대신 세션에 저장 (실서비스에선 SMS API 연동)
    $_SESSION['verify'][$phone] = $code;

    echo json_encode(['success' => true]);


}else if($type=="checkCode"){
    $phone = $_POST['phone'] ?? '';
    $code = $_POST['code'] ?? '';

    $validCode = $_SESSION['verify'][$phone] ?? null;

    if ($validCode && $validCode == $code) {
        unset($_SESSION['verify'][$phone]); // 사용 후 삭제
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

}else if($type=="signup"){
    $user_id = $_POST['user_id'] ?? '';
    $nickname = $_POST['nickname'] ?? '';
    $password = $_POST['password'] ?? '';
    $contact = $_POST['contact'] ?? '';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkStmt = $conn->prepare("SELECT * FROM user WHERE id = ? OR name= ?");
    $checkStmt->bind_param("ss",$user_id,$nickname);
    if($checkStmt->execute()){
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $status = false;
            $msg = "id or name exist";
        }else{
            $insertStmt = $conn->prepare("INSERT INTO user(id,pwd,name,contact) VALUES(?,?,?,?)");
            $insertStmt->bind_param("ssss",$user_id,$hashedPassword,$nickname,$contact);
            if($insertStmt->execute()){
                $val = "기타";
                $userIx = $insertStmt->insert_id;

                $cateStmt = $conn->prepare("INSERT INTO category(user_ix,name) VALUES(?,?)");
                $cateStmt->bind_param("ss",$userIx,$val);
                $cateStmt->execute();

                $accountStmt = $conn->prepare("INSERT INTO account(user_ix,name) VALUES(?,?)");
                $accountStmt->bind_param("ss",$userIx,$val);
                $accountStmt->execute();

                $trialStart = date('Y-m-d H:i:s');
                // 5일 후 날짜
                $trialEnd = date($trialStart, strtotime('+5 days'));
                $trialStmt = $conn->prepare("INSERT INTO user_subscriptions(user_ix,plan_ix,started_at,ends_at,is_trial,status) VALUES(?,1,?,?,1,'trial')");
                $trialStmt->bind_param("sss",$userIx,$trialStart,$trialEnd);
                $trialStmt->execute();

                $status = true;
                $msg = "signup ok";
            }else{
                $status = false;
                $msg = "signup fail";
            }
        }
        
    }else{
        $status = false;
        $msg = "db fail";
    }

    $data['status'] = $status; 
    $data['msg'] = $msg;
    echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}


?>