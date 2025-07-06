<?php 


include './dbConnect.php';

$a = '1131313';
$b = '2025-06-27';
$c = '2025-06-24 11:55:55';
$d = '1';
$e = '3000';

$orderStmt = $conn->prepare("INSERT IGNORE INTO orders(global_order_number,order_number,order_date,order_time,market_ix,user_ix,total_payment,total_shipping) VALUES(?,?,?,?,?,?,?,?)");
$orderStmt->bind_param("ssssssss", $a,$a,$b,$c,$d,$d,$e,$e);
$orderStmt->execute();



 ?>