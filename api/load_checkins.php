<?php
include '../dbConnect.php';

$user_id = $_POST['user_id'] ?? '';
if (!$user_id) exit;
$year = date('Y');
$month = date('m');
$yearMonth = "$year-$month";

$checkStmt = $conn->prepare("SELECT checkin_date FROM checkin_logs WHERE user_id = ? AND DATE_FORMAT(checkin_date, '%Y-%m') = ?");
$checkStmt->bind_param("ss",$user_id,$yearMonth);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    while ($row = $checkResult->fetch_assoc()) {
        $dates[] = $row['checkin_date'];
    }
}


// $dates = [];
// $dates = ['2025-04-07','2025-04-08'];
header('Content-Type: application/json');
echo json_encode($dates, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
$conn->close();
