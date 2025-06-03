<?php
session_start();
include '../dbConnect.php';

$userIx = $_SESSION['user_ix'] ?? 1;
$page = $_POST['page'] ?? 1;
$limit = 30;
$offset = ($page - 1) * $limit;

$historyStmt = $conn->prepare("SELECT DATE(o.created_at) AS created_date, m.market_name, m.market_icon FROM orders o JOIN market m ON m.ix = o.market_ix WHERE o.user_ix = ? 
    GROUP BY DATE(o.created_at), o.market_ix ORDER BY created_date DESC LIMIT ? OFFSET ?");

$historyStmt->bind_param("sss",$userIx,$limit,$offset);
$historyStmt->execute();
$result = $historyStmt->get_result();


while ($historyRow = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td><img style='width:20px;' src='./img/icon/" . htmlspecialchars($historyRow['market_icon']) . "'</td>";
    echo "<td>" . htmlspecialchars($historyRow['created_date'])."</td>";
    echo "<td class='text-center'><a href='./order-listup.php?date=".htmlspecialchars($historyRow['created_date'])."' style='color:#0d6efd;'>내역보기</a></td>";
    echo "</tr>";
}

?>