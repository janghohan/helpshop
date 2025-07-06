<?php
include '../dbConnect.php';
$user_id = $_POST['user_id'] ?? '';
if (!$user_id) exit;

$today = date('Y-m-d');
$checkStmt = $conn->prepare("SELECT checkin_date FROM checkin_logs WHERE user_id = ? AND checkin_date=?");
$checkStmt->bind_param("ss",$user_id,$today);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $insertStmt = $conn->prepare("INSERT INTO checkin_logs(user_id, checkin_date) VALUES(?,?)");
    $insertStmt->bind_param("ss",$user_id,$today);
    $insertStmt->execute();

    echo 'success';
}
