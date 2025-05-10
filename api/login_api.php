<?php
session_start();
include '../dbConnect.php'; // 데이터베이스 연결

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 사용자 입력값 받기
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // 데이터베이스에서 아이디와 비밀번호 확인하기
    $stmt = $conn->prepare("SELECT ix, id, pwd, name FROM user WHERE id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // 비밀번호 확인
        $stmt->bind_result($ix, $user_id, $hashedPassword,$name);
        $stmt->fetch();

        // 입력된 비밀번호와 DB에 저장된 비밀번호 비교
        if (password_verify($password, $hashedPassword)) {
            // 로그인 성공 -> 세션에 사용자 정보 저장
            $_SESSION['user_id'] = $user_id;
            $_SESSION['nickname'] = $name;
            $_SESSION['user_ix'] = $ix;

            $status = true;
            $msg = "login ok";

        } else {
            $status = false;
            $msg = "아이디 또는 비밀번호가 일치하지 않습니다.";
        }
    } else {
        $status = false;
        $msg = "아이디 또는 비밀번호가 일치하지 않습니다.";
    }
    
    $stmt->close();
    $conn->close();

    $data['status'] = $status;
    $data['msg'] = $msg;

    echo json_encode( $data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}
?>
