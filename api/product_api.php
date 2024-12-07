<?php
session_start();
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    // JSON 문자열로 받은 데이터를 파싱
    $formCombination = isset($_POST['formCombination']) ? json_decode($_POST['formCombination'], true) : [];
    $accountIx = isset($_POST['accountIx']) ? : '';
    $categoryIx = isset($_POST['categoryIx']) ? : '';
    $productName = isset($_POST['productName']) ? : '';
    $productMemo = isset($_POST['productMemo']) ? : '';


    if (!empty($formCombination)) {
        //상품명에 넣는다. 
        $productStmt = $conn->prepare("INSERT INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
        $productStmt->bind_param("sssss",$userIx,$accountIx,$categoryIx,$productName,$productMemo);
        $productStmt->execute();

        $productIx = $productStmt->insert_id;
        $productResult = $productStmt->get_result();

        //product_option
        $options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
        foreach ($options as $option){
            $optionName = $option['name'];
            $optionValues = $option['values'];
            foreach($optionValues as $optionValue) {
                $optionStmt = $conn->prepare("INSERT INTO product_option(product_ix,name,value) VALUES(?,?,?)");
                $optionStmt->bind_param("sss",$productIx,$optionName,$optionValue);
                $optionStmt->execute();
            }
        }

        //product_option_combination
        foreach ($formCombination as $combination) {
            $id = $combination['id'];
            $name = $combination['name'];
            $value = $combination['value'];
            $price = $combination['price'];
            $stock = $combination['stock'];
            $selling = $combination['selling'];

            $combiStmt = $conn->prepare("INSERT INTO product_option_combination(product_ix,combination_key,cost_price,stock) VALUES(?,?,?,?)");
            $combiStmt -> bind_param("ssss",$productIx,$value,$price,$stock);
            $combiStmt->execute();

            $combiIx = $combiStmt->insert_id;
            
            // 옵션 데이터
            foreach ($selling as $marketIx => $sellingPrice) {
                $marketStmt = $conn->prepare("INSERT INTO product_option_market_price(product_option_comb_ix,market_ix,price) VALUES(?,?,?)");
                $marketStmt->bind_param("sss",$combiIx,$marketIx,$sellingPrice);
                $marketStmt->execute();
                // echo "Market ID $marketIx: $sellingPrice<br>";
            }
        }

        $conn->commit();

        
    } else {
        echo "No data received!";
    }
} else {
    echo "Invalid request!";
}


?>