<?php
include './dbConnect.php';



$start = microtime(true);
$result = $conn->query("SELECT MAX(o.order_date) AS order_date, MAX(o.global_order_number) AS global_order_number, MAX(m.market_name) AS market_name, MAX(od.ix) AS detailIx,
                od.name, SUM(od.quantity) AS total_quantity, SUM(od.price) AS total_price, SUM(o.total_payment) AS total_payment, SUM(o.total_shipping) AS total_shipping, SUM(od.cost) AS total_cost,
                MAX(m.basic_fee) AS basic_fee, MAX(m.linked_fee) AS linked_fee, MAX(m.ship_fee) AS ship_fee FROM orders o JOIN order_details od ON o.ix = od.orders_ix JOIN market m ON m.ix = o.market_ix
                LEFT JOIN db_match dm ON od.name = dm.name_of_excel WHERE o.user_ix = 1 AND od.status='completed' AND dm.name_of_excel IS NULL GROUP BY od.name ORDER BY MAX(o.order_time)");
$end = microtime(true);
echo "쿼리 시간: " . round($end - $start, 4) . "초";


?>