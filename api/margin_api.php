<?php
session_start();
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calculateType = isset($_POST['calculateType']) ? $_POST['calculateType'] : '';
    $query = isset($_POST['query']) ? $_POST['query'] : '';

    $orderStmt = $conn->prepare($query);
    $bindParams = array_merge([$userIx, $startDate, $endDate], $searchParams);
    $orderStmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);

    // Execute and Fetch Results
    $orderStmt->execute();
    $result = $orderStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderResult[] = $row;
        }
    }

    $totalPayment = 0;
    $totalShipping = 0;
    $totalProfit = 0;
    $previousOrderNumber = null; // 이전 주문번호를 저장
    $toggle = true; // 색상을 변경하기 위한 토글 변수

    foreach($orderResult as $orderRow) {
        $currentOrderNumber = $orderRow['global_order_number'];
        if ($currentOrderNumber !== $previousOrderNumber) {
            // 주문번호가 변경될 때마다 토글 값을 변경
            $toggle = !$toggle;
            $shipping = $orderRow['total_shipping'];
        }else{
            $shipping = 0;
        }

        
        $price = $orderRow['price'];
        $shipping = $orderRow['total_shipping'];
        $basicFee = $orderRow['basic_fee'];
        $linkedFee = $orderRow['linked_fee'];
        $shipFee = $orderRow['ship_fee'];
        $cost = $orderRow['cost'];

        // 마진 계산
        $vatRate = 0.1;  // 부가가치세 (10%)
        $incomeTaxRate = 0.033; // 소득세 (3.3%)
        
        // 1. 상품가에 대한 수수료 계산 (기본 수수료 + 연동 수수료)
        $totalProductFeeRate = $basicFee + $linkedFee; // 총 상품가 수수료율
        $totalProductFees = $price * $totalProductFeeRate; // 상품가에 대한 총 수수료

        // 2. 배송비에 대한 수수료 계산
        $shippingFeeCommission = $shipping * $shipFee;

        // 3. 총 수수료 계산
        $totalFees = $totalProductFees + $shippingFeeCommission;

        // 4. 매출에서 부가세 계산
        $salesVat = ($price + $shipping) * $vatRate;

        // 5. 소득세 계산 (부가세 제외한 매출에서 소득세 적용)
        $taxableIncome = ($price + $shipping) - $totalFees - $salesVat; 
        $incomeTax = $taxableIncome * $incomeTaxRate;

        // 6. 최종 마진 계산
        $netProfit = ($price + $shipping) - $cost - $totalFees - $salesVat - $incomeTax;




        // 최종 판매금액, 택배 금액, 마진
        $totalPayment += $orderRow['price'];
        $totalShipping += $orderRow['total_shipping'];
        $totalProfit += $netProfit;

    }

    $response = ['totalPayment' => $totalPayment, 'totalShipping'=> $totalShipping, 'totalProfit'=>$totalProfit];

    echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}



?>