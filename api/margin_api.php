<?php
session_start();
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // header('Content-Type: application/json');
    // ob_start();

    $type = $_POST['type'] ?? 'basic';
    $userIx = $_SESSION['user_ix'] ?? '1';
    if($type=='basic'){

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
                IFNULL(mn.cost, 0) AS cost, m.basic_fee, m.linked_fee, m.ship_fee
                FROM orders o
                JOIN order_details od ON o.ix = od.orders_ix
                JOIN market m ON m.ix = o.market_ix LEFT JOIN db_match dm ON dm.name_of_excel = od.name LEFT JOIN matching_name mn ON mn.ix = dm.matching_ix
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
                IFNULL(mn.cost, 0) AS cost, m.basic_fee, m.linked_fee, m.ship_fee
                FROM orders o
                JOIN order_details od ON o.ix = od.orders_ix
                JOIN market m ON m.ix = o.market_ix LEFT JOIN db_match dm ON dm.name_of_excel = od.name LEFT JOIN matching_name mn ON mn.ix = dm.matching_ix
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


        //상품판매 매출액 
        $totalPayment = 0;
        //배송비 매출액
        $totalShipping = 0;
        //주문 수
        $orderCount = 0;
        //총 상품 매입내역
        $totalCost = 0;
        //총 마켓 수수료
        $totalMarketFee = 0;
        //총 지출내역
        $totalExpense = 0;

        //총 예상 순수익
        $totalProfit = 0;
        //총(상품)부가세
        $totalPriceSurtax = 0; 

        $previousOrderNumber = null; // 이전 주문번호를 저장
        $toggle = true; // 색상을 변경하기 위한 토글 변수

        $vatRate = 0.1;  // 부가가치세 (10%)

        $marketResult = [];

        $id = 0;
        foreach($orderResult as $orderRow) {
            $currentOrderNumber = $orderRow['global_order_number'];
            if ($currentOrderNumber !== $previousOrderNumber) {
                // 주문번호가 변경될 때마다 토글 값을 변경.
                $orderCount ++;
                $toggle = !$toggle;
                $shipping = $orderRow['total_shipping'];
            }else{
                $shipping = 0;
            }

            // 상품 수수료율
            $feeRate = $orderRow['basic_fee'] + $orderRow['linked_fee'];
            // 배송비 수수료율
            $shipRate = $orderRow['ship_fee'];

            // 총 상품 매출
            $totalProductRevenue = $orderRow['price'] * $orderRow['quantity'];
            //총 배송비 매출
            $totalShipRevenue = $shipping;

            // 총 매출(상품+배송비)
            $totalRevenue = $totalProductRevenue + $totalShipRevenue;
            // 총 매출 부가세
            $totalRevenueSurtax = $totalRevenue - ($totalRevenue / 1.1);
            
            //총 상품 원가
            $totalProductCost = $orderRow['cost'] * $orderRow['quantity'];
            

            // 마진 계산
            $incomeTaxRate = 0.033; // 소득세 (3.3%)
            
            // 1. 상품가에 대한 수수료 계산 (기본 수수료 + 연동 수수료)
            $totalPriceFee = ($totalProductRevenue * $feeRate) / 100; 
            
            // 2. 배송비에 대한 수수료 계산
            $totalShipFee = ($totalShipRevenue * $shipRate) / 100;

            // 3. 총 수수료 계산
            $totalFees = $totalPriceFee + $totalShipFee;

            // 매입부가세
            $totalPurchaseSurtax = $totalProductCost - ($totalProductCost / 1.1);

            // 지출부가세
            $surtax = $totalRevenueSurtax - $totalPurchaseSurtax;

            $id ++;

            // 최종 판매금액, 택배 금액, 마진
            $totalPayment += $totalProductRevenue; //매출
            $totalShipping += $totalShipRevenue;
            $totalCost += $totalProductCost;
            $totalPriceSurtax += $surtax;
            $totalMarketFee += $totalFees;

            if (isset($marketResult[$orderRow['market_name']])) {
                $marketResult[$orderRow['market_name']]['totalProductRevenue'] += $totalProductRevenue;
                $marketResult[$orderRow['market_name']]['totalPriceFee'] += $totalPriceFee;
                $marketResult[$orderRow['market_name']]['totalProductCost'] += $totalProductCost;

                $calProductRevenue = $marketResult[$orderRow['market_name']]['totalProductRevenue'];
                $calPriceFee = $marketResult[$orderRow['market_name']]['totalPriceFee'];
                $calProductCost = $marketResult[$orderRow['market_name']]['totalProductCost'];

                $marketResult[$orderRow['market_name']]['totalProfit'] = ($calProductRevenue - $calPriceFee - $calProductCost);
                $marketResult[$orderRow['market_name']]['totalMarginRate'] =  number_format((($calProductRevenue - ($calPriceFee + $calProductCost)) / $calProductRevenue ) * 100);
            } else {
                // 없던 마켓이면 새로 추가
                $marketResult[$orderRow['market_name']] = [
                    'market' => $orderRow['market_name'],
                    'totalProductRevenue' => $totalProductRevenue,
                    'totalPriceFee' => $totalPriceFee,
                    'totalProductCost' => $totalProductCost,
                    'totalProfit' => $totalProductRevenue - $totalPriceFee - $totalProductCost,
                    'totalMarginRate' => ($totalProductRevenue - ($totalPriceFee + $totalProductCost) / $totalProductRevenue) * 100,
                ];
            }

            $previousOrderNumber = $currentOrderNumber;
        }

        // 지출내역 확인 쿼리
        $expenseStmt = $conn->prepare("SELECT SUM(expense_price) AS totalPurchase FROM expense WHERE user_ix=? AND expense_date >=? AND expense_date<=?");
        $expenseStmt->bind_param('sss',$userIx,$startDate,$endDate);
        $expenseStmt->execute();
        $expenseResult = $expenseStmt->get_result();

        $expenseRow = $expenseResult->fetch_assoc();

        //광고비, 택배비, 자재비 등 부가 매입내역
        $totalPurchase = $expenseRow['totalPurchase'] ?? 0; //매입

        //최종 부가세 (판매가-원가 부가세 - 기타매입내역 부가세)
        $totalSurtax = $totalPriceSurtax - ($totalPurchase - ($totalPurchase / 1.1)); //최종 부가세

        //최종 세전 순수익
        $totalExNetProfit = $totalPayment + $totalShipping - $totalCost - $totalMarketFee;

        //최종 부가세 제외 순수익
        $totalProfitMinusSurtax = $totalExNetProfit - $totalSurtax;

        //최종 순수익에 따른 예상상 소득세
        $totalIncomTax = calculateIncomeTax($totalProfitMinusSurtax);

        //세금을 제외한 최종 예상 소득세
        $totalProfit = $totalProfitMinusSurtax - $totalIncomTax;

        $response['msg'] = 'suc';
        $response['totalPayment'] = number_format($totalPayment); //총 결제금액(상품)
        $response['totalShipping'] = number_format($totalShipping); //총 결제금액(택배비)
        $response['avePerPrice'] = number_format($orderCount != 0 ? $totalPayment / $orderCount : 0); //평균객단가
        $response['totalCost'] = number_format($totalCost); //총 매입내역
        $response['totalMarketFee'] = number_format($totalMarketFee); //총 마켓수수료
        $response['totalPurchase'] = number_format($totalPurchase); //총 지출내역
        $response['totalTax'] = number_format($totalSurtax + $totalIncomTax);
        $response['totalProfit'] = number_format($totalProfit);
        $response['imcomTaxRate'] = number_format(getIncomeTaxRate($totalProfitMinusSurtax));
        $response['totalMarginRate'] = number_format($totalPayment != 0 ? ($totalProfit / $totalPayment) * 100 : 0);
        $response['totalIncomeTax'] = number_format($totalIncomTax); //소득세
        $response['marketResult'] = array_values($marketResult);

        // $response['startDate'] = $startDate;
        // $response['endDate'] = $endDate;
        // $response['searchType'] = $searchType;
        // $response['searchKeyword'] = $_POST['searchKeyword'];
        // $response['query'] = $orderQuery;
        // $response['result'] = $result->num_rows;

        
        // $output = ob_get_clean();
        // file_put_contents('debug_output.txt', $output); // 파일에 기록

        echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    
    }else if($type=='graph'){
        
        $startDate = $_POST['startDate'] ?? date("Y-m-d");
        $endDate = $_POST['endDate'] ?? date("Y-m-d");
        $viewType = $_POST['viewType'] ?? 'daily';

        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            exit;
        }

        // Grouping 조건
        $dateFormat = $viewType === 'monthly' ? '%Y-%m' : '%Y-%m-%d';

        // 서브쿼리로 배송비 중복 제거 처리
        $sql = "
            SELECT 
                DATE_FORMAT(od.order_date, '{$dateFormat}') AS period,
                
                -- 상품 판매가: 상품가격 * 수량
                SUM(od.price * od.quantity) AS product_revenue,
                
                -- 배송비: 주문별 1회만 계산
                SUM(CASE WHEN od.row_number = 1 THEN shipping_cost.total_shipping ELSE 0 END) AS total_shipping,
                
                -- 총 매출: 상품 판매가 + 배송비
                SUM(od.price * od.quantity) + SUM(CASE WHEN od.row_number = 1 THEN shipping_cost.total_shipping ELSE 0 END) AS total_revenue,
                
                -- 순수익 계산
                SUM((
                    (od.price - IFNULL(mn.cost, 0)                                           -- 매출 - 공급가
                    - ROUND(od.price * (IFNULL(mk.basic_fee, 0) + IFNULL(mk.linked_fee, 0)) / 100, 0) -- 상품 수수료
                    - CASE 
                        WHEN od.row_number = 1 THEN ROUND(shipping_cost.total_shipping * IFNULL(mk.ship_fee, 0) / 100, 0) 
                        ELSE 0
                    END
                    ) * od.quantity
                )) AS total_profit

            FROM (
                SELECT 
                    od.price, 
                    od.quantity, 
                    od.name,
                    o.order_number, 
                    o.order_date,
                    o.market_ix,
                    ROW_NUMBER() OVER (PARTITION BY o.order_number ORDER BY od.ix) AS row_number
                FROM order_details od
                INNER JOIN orders o ON od.orders_ix = o.ix
                WHERE o.order_date BETWEEN ? AND ?
                AND o.user_ix = ?
            ) AS od

            -- 배송비 테이블 (order_number 단위)
            LEFT JOIN (
                SELECT order_number, MAX(total_shipping) AS total_shipping
                FROM orders
                WHERE order_date BETWEEN ? AND ?
                AND user_ix = ?
                GROUP BY order_number
            ) shipping_cost ON od.order_number = shipping_cost.order_number

            -- 상품명 매칭
            LEFT JOIN db_match dm ON dm.name_of_excel = od.name
            LEFT JOIN matching_name mn ON dm.matching_ix = mn.ix

            -- 마켓 정보
            LEFT JOIN market mk ON od.market_ix = mk.ix

            GROUP BY period
            ORDER BY period ASC

        ";
        // $startDate = "2025-05-01";
        // $endDate = '2025-05-02';

        // $sql = "SELECT * FROM orders WHERE order_date >= ? AND order_date <= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $startDate, $endDate,$userIx, $startDate, $endDate,$userIx);
        $stmt->execute();
        $result = $stmt->get_result();

        // 결과 출력
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
}

function calculateIncomeTax($taxableIncome)
{
    // 세율 구간 및 누진공제표 (2024년 기준)
    $taxBrackets = [
        ['limit' => 12000000, 'rate' => 0.06, 'deduction' => 0],
        ['limit' => 46000000, 'rate' => 0.15, 'deduction' => 1080000],
        ['limit' => 88000000, 'rate' => 0.24, 'deduction' => 5220000],
        ['limit' => 150000000, 'rate' => 0.35, 'deduction' => 14900000],
        ['limit' => 300000000, 'rate' => 0.38, 'deduction' => 19400000],
        ['limit' => 500000000, 'rate' => 0.40, 'deduction' => 25400000],
        ['limit' => 1000000000, 'rate' => 0.42, 'deduction' => 35400000],
        ['limit' => PHP_INT_MAX, 'rate' => 0.45, 'deduction' => 65400000],
    ];

    foreach ($taxBrackets as $bracket) {
        if ($taxableIncome <= $bracket['limit']) {
            $tax = ($taxableIncome * $bracket['rate']) - $bracket['deduction'];
            return max(0, round($tax)); // 세액은 음수가 될 수 없으니 0으로 보정
        }
    }

    // 만약 위에서 리턴되지 않았다면 (논리적으로 도달하지 않음)
    return 0;
}

function rateIncomeTax($taxableIncome)
{
    // 세율 구간 및 누진공제표 (2024년 기준)
    $taxBrackets = [
        ['limit' => 12000000, 'rate' => 0.06, 'deduction' => 0],
        ['limit' => 46000000, 'rate' => 0.15, 'deduction' => 1080000],
        ['limit' => 88000000, 'rate' => 0.24, 'deduction' => 5220000],
        ['limit' => 150000000, 'rate' => 0.35, 'deduction' => 14900000],
        ['limit' => 300000000, 'rate' => 0.38, 'deduction' => 19400000],
        ['limit' => 500000000, 'rate' => 0.40, 'deduction' => 25400000],
        ['limit' => 1000000000, 'rate' => 0.42, 'deduction' => 35400000],
        ['limit' => PHP_INT_MAX, 'rate' => 0.45, 'deduction' => 65400000],
    ];

    foreach ($taxBrackets as $bracket) {
        if ($taxableIncome <= $bracket['limit']) {
            $tax = ($taxableIncome * $bracket['rate']) - $bracket['deduction'];
            return max(0, round($tax)); // 세액은 음수가 될 수 없으니 0으로 보정
        }
    }

    // 만약 위에서 리턴되지 않았다면 (논리적으로 도달하지 않음)
    return 0;
}

function getIncomeTaxRate($income) {
    $tax_brackets = [
        ['limit' => 12000000, 'rate' => 6],
        ['limit' => 46000000, 'rate' => 15],
        ['limit' => 88000000, 'rate' => 24],
        ['limit' => 150000000, 'rate' => 35],
        ['limit' => 300000000, 'rate' => 38],
        ['limit' => 500000000, 'rate' => 40],
        ['limit' => 1000000000, 'rate' => 42],
        ['limit' => PHP_INT_MAX, 'rate' => 45]
    ];

    foreach ($tax_brackets as $bracket) {
        if ($income <= $bracket['limit']) {
            return $bracket['rate'];
        }
    }

    // 이론적으로 도달하지 않지만, 안전장치
    return null;
}



?>