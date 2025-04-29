<?php
header('Content-Type: application/json');
require_once '../db.php'; // DB 연결

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;
$view = $_GET['view'] ?? 'daily';

if (!$start || !$end) {
    http_response_code(400);
    echo json_encode(['error' => '시작일과 종료일이 필요합니다.']);
    exit;
}

$groupBy = $view === 'monthly' ? '%Y-%m' : '%Y-%m-%d';

$sql = "
    SELECT 
        DATE_FORMAT(o.order_date, ?) AS period,
        SUM(od.price * od.quantity + o.total_shipping) AS total_revenue,
        SUM(
            (od.price - IFNULL(mn.cost, 0) 
            - ROUND(od.price * IFNULL(mk.fee_rate, 0) / 100, 0) 
            - ROUND(o.total_shipping * IFNULL(mk.shipping_fee_rate, 0) / 100, 0)
            ) * od.quantity
        ) AS total_profit
    FROM order_details od
    INNER JOIN orders o ON od.order_id = o.id
    LEFT JOIN db_match dm ON dm.name_of_excel = od.name
    LEFT JOIN matching_name mn ON dm.matching_ix = mn.ix
    LEFT JOIN market mk ON o.market_ix = mk.ix
    WHERE o.order_date BETWEEN ? AND ?
    GROUP BY period
    ORDER BY period ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $groupBy, $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    'labels' => [],
    'revenue' => [],
    'profit' => []
];

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['period'];
    $data['revenue'][] = (int)$row['total_revenue'];
    $data['profit'][] = (int)$row['total_profit'];
}

echo json_encode($data);
