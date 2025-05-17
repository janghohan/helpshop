<?php
// DB 연결 (객체지향 방식)
include '../dbConnect.php';
$userIx = $_SESSION['user_ix'] ?? '1';

// 1. 알림이 필요한 상품 조회
$sql = "
    SELECT mn.ix AS matching_ix
    FROM matching_name mn
    WHERE mn.stock < mn.alarm_stock
      AND NOT EXISTS (
          SELECT 1 FROM stock_alarm sa
          WHERE sa.matching_ix = mn.ix
            AND sa.is_resolved = 0
      )
";

$alarmStmt = $conn->prepare("SELECT mn.ix AS matching_ix FROM matching_name mn WHERE mn.user_ix=? AND mn.stock < mn.alarm_stock 
AND NOT EXISTS(SELECT * FROM stock_alarm sa WHERE sa.matching_ix = mn.ix AND sa.is_resolved=0)");
$alarmStmt->bind_param("s",$userIx);
$alarmStmt->execute();

$result = $alarmStmt->get_result();

// 2. 알림 생성
$insert_sql = "
    INSERT INTO stock_alarm (matching_ix, alarm_type, created_at, is_resolved)
    VALUES (?, 'stock', NOW(), 0)
";
$insertStmt = $conn->prepare("INSERT INTO stock_alarm (matching_ix, alarm_type, created_at)
    VALUES (?, 'stock', NOW())");

$count = 0;
while ($row = $result->fetch_assoc()) {
    $matching_ix = $row['matching_ix'];

    $insertStmt->bind_param("i",  $matching_ix);
    $insertStmt->execute();
    $count++;
}

echo ($count > 0) ? "$count 건의 유저별 재고 알림이 생성되었습니다." : "새로 생성할 유저별 재고 알림이 없습니다.";

$alarmStmt->close();
$insertStmt->close();
$conn->close();

?>
