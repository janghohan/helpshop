<?php
include '../dbConnect.php';

$user_id = $_POST['user_id'] ?? '';
if (!$user_id) exit;
$year = date('Y');
$month = date('m');

$checkStmt = $conn->query("SELECT checkin_date FROM checkin_logs WHERE user_id = ? AND DATE_FORMAT(checkin_date, '%Y-%m') = '?-?'");
$checkStmt->bind_params("sss",$user_id,$year,$month);

$user_id = $mysqli->real_escape_string($user_id);


// $query = "
//   SELECT checkin_date 
//   FROM checkin_logs 
//   WHERE user_id = '$user_id' 
//     AND DATE_FORMAT(checkin_date, '%Y-%m') = '$year-$month'
// ";

// $result = $mysqli->query($query);
// $dates = [];

// while ($row = $result->fetch_assoc()) {
//   $dates[] = $row['checkin_date'];
// }

$dates = [];
$dates = ['2025-04-07','2025-04-08'];
header('Content-Type: application/json');
echo json_encode($dates);
$mysqli->close();
