<?php
session_start();
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    ob_start();

    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $calculateType = isset($_POST['calculateType']) ? $_POST['calculateType'] : '';
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date("Y-m-d");
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date("Y-m-d");

    $orderResult = [];
    $searchParams = [];

    //검색 영역에 값이 들어오면
    if($_POST['searchKeyword']!=""){
        $searchKeyword = $_POST['searchKeyword'];
        $searchType = $_POST['searchType'];

        if ($searchType === 'name') {
            $orderTypeSearchKeyworSql = "AND od.name LIKE ?";
            $searchParams[] = '%' . $searchKeyword . '%';
        } else if($searchType === 'global_order_number'){
            $orderTypeSearchKeyworSql = "AND o." . $searchType . " = ?";
            $searchParams[] = $searchKeyword;
        }

        $orderQuery = "
            SELECT o.order_date, o.global_order_number, m.market_name, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date >= ?
            AND o.order_date <= ?
            $orderTypeSearchKeyworSql
        ";
        $orderStmt = $conn->prepare($orderQuery);
        $bindParams = array_merge([$userIx, $startDate, $endDate], $searchParams);
        $orderStmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
    }else{
        $orderQuery = "
            SELECT o.order_date, o.global_order_number, m.market_name, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix
            WHERE o.user_ix = ? 
            AND o.order_date >= ?
            AND o.order_date <= ?
        ";
        $orderStmt = $conn->prepare($orderQuery);
        $orderStmt->bind_param('sss',$userIx,$startDate,$endDate);
    }


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
    $totalSaleVat = 0; //매출부가세
    $previousOrderNumber = null; // 이전 주문번호를 저장
    $toggle = true; // 색상을 변경하기 위한 토글 변수

    $vatRate = 0.1;  // 부가가치세 (10%)

    $id = 0;
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
        $incomeTaxRate = 0.033; // 소득세 (3.3%)
        
        // 1. 상품가에 대한 수수료 계산 (기본 수수료 + 연동 수수료)
        $totalProductFeeRate = ($basicFee + $linkedFee)/100; // 총 상품가 수수료율
        $totalProductFees = $price * $totalProductFeeRate; // 상품가에 대한 총 수수료

        // 2. 배송비에 대한 수수료 계산
        $shippingFeeCommission = $shipping * (($shipFee)/100);

        // 3. 총 수수료 계산
        $totalFees = $totalProductFees + $shippingFeeCommission;

        // 4. 매출에서 부가세 계산
        $salesVat = ($price + $shipping) * $vatRate;

        // 5. 소득세 계산 (부가세 제외한 매출에서 소득세 적용)
        $taxableIncome = ($price + $shipping) - $totalFees - $salesVat;
        $incomeTax = getIncomTax($taxableIncome);

        // 6. 최종 마진 계산
        $netProfit = ($price + $shipping) - $cost - $totalFees - $salesVat - $incomeTax;


        $id ++;

        // 최종 판매금액, 택배 금액, 마진
        $totalPayment += $orderRow['price']; //매출
        $totalShipping += $orderRow['total_shipping'];
        $totalProfit += $netProfit;
        $totalSaleVat += $salesVat;

    }

    // 지출내역 확인 쿼리
    $expenseStmt = $conn->prepare("SELECT SUM(expense_price) AS totalPurchase FROM expense WHERE user_ix=? AND expense_date >=? AND expense_date<=?");
    $expenseStmt->bind_param('sss',$userIx,$startDate,$endDate);
    $expenseResult = $expenseStmt->get_result();
    $expenseRow = $expenseResult->fetch_assoc();
    $totalPurchase = $expenseRow['totalPurchase'] ?? 0; //매입

    $totalVat = ($totalPayment - $totalPurchase) * $vatRate; //최종 부가세
    

    if($calculateType=='totalPayment'){
        $response['result'] = number_format($totalPayment)."원";
    }else if($calculateType=='totalShipping'){
        $response['result'] =  number_format($totalShipping)."원";
    }else if($calculateType=='totalProfit'){
        $response['result'] =  number_format($totalProfit)."원";
    }else if($calculateType=='totalDuty'){
        $response['result'] =  number_format($salesVat+$incomeTax)."원";
    }else if($calculateType=='totalPurchase'){
        $response['result'] =  number_format($totalPurchase)."원";
    }else if($calculateType=='totalCommission'){
        $response['result'] =  number_format($totalFees)."원";
    }

    // $response['result'] = $totalPayment;
    // $response['totalShipping'] = $totalShipping;
    // $response['totalProfit'] = $totalProfit;
    // $response['startDate'] = $startDate;
    // $response['endDate'] = $endDate;
    // $response['searchType'] = $searchType;
    // $response['searchKeyword'] = $_POST['searchKeyword'];
    // $response['query'] = $orderQuery;
    // $response['result'] = $result->num_rows;

    
    $output = ob_get_clean();
    // file_put_contents('debug_output.txt', $output); // 파일에 기록

    echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}

function getIncomTax($taxableIncome){
    if($taxableIncome<=12000000){
        return $taxableIncome * 0.06;
    }else if($taxableIncome>12000000 && $taxableIncome <=46000000){
        return $taxableIncome * 0.15 + 1080000;
    }else if($taxableIncome>46000000 && $taxableIncome <=88000000){
        return $taxableIncome * 0.24 + 5220000;
    }else if($taxableIncome>88000000 && $taxableIncome <=150000000){
        return $taxableIncome * 0.35 + 14900000;
    }else if($taxableIncome>150000000 && $taxableIncome <=300000000){
        return $taxableIncome * 0.38 + 19400000;
    }else if($taxableIncome>300000000 && $taxableIncome <=500000000){
        return $taxableIncome * 0.40 + 25400000;
    }else if($taxableIncome>500000000 && $taxableIncome <=1000000000){
        return $taxableIncome * 0.42 + 35400000;
    }else if($taxableIncome>1000000000){
        return $taxableIncome * 0.45 + 65400000;
    }
}


?>