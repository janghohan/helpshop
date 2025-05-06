<?php
session_start();
include '../dbConnect.php';

require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    // JSON 문자열로 받은 데이터를 파싱
    $formCombination = isset($_POST['formCombination']) ? json_decode($_POST['formCombination'], true) : [];
    $accountIx = $_POST['accountIx'] ?? '';
    $categoryIx = $_POST['categoryIx'] ?? '1';
    $productName = $_POST['productName'] ?? '';
    $productMemo = $_POST['productMemo'] ?? '';
    $addCount = $_POST['addCount'] ?? ''; // 단일등록인지, 대량등록인지 구별 [단일 : 1]

    // echo $formCombination[0]['name'];
    // echo $_POST['productName'];
    if($addCount==1){
        $productName = $_POST['productName'] ?? '';
        $productCost = $_POST['productCost'] ?? '';
        $productStock = $_POST['productStock'] ?? '';
        $accountIx = $_POST['accountIx'] ?? '';
        $categoryIx = $_POST['categoryIx'] ?? '';

        $productCost = str_replace(",","",$productCost);
        $productStock = str_replace(",","",$productStock);

        $productStmt = $conn->prepare("INSERT INTO matching_name(user_ix,category_ix,account_ix,matching_name,cost,stock) VALUES(?,?,?,?,?,?)");
        $productStmt->bind_param("ssssss",$userIx,$categoryIx,$accountIx,$productName,$productCost,$productStock);
        if( $productStmt->execute()){
            $response['status'] = 'success';
        }else{
            $response['status'] = 'fail';
        }

        echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        // if (!empty($formCombination)) {
        //     //상품명에 넣는다. 
        //     $productStmt = $conn->prepare("INSERT INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
        //     $productStmt->bind_param("sssss",$userIx,$accountIx,$categoryIx,$productName,$productMemo);
        //     $productStmt->execute();
    
        //     $productIx = $productStmt->insert_id;
        //     $productResult = $productStmt->get_result();
    
        //     //product_option
        //     $options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
        //     foreach ($options as $option){
        //         $optionName = $option['name'];
        //         $optionValues = $option['value'];
        //         foreach($optionValues as $optionValue) {
        //             $optionStmt = $conn->prepare("INSERT INTO product_option(product_ix,name,value) VALUES(?,?,?)");
        //             $optionStmt->bind_param("sss",$productIx,$optionName,$optionValue);
        //             $optionStmt->execute();
        //         }
        //     }
    
        //     //product_option_combination
        //     foreach ($formCombination as $combination) {
        //         $name = $combination['name'];
        //         $price = str_replace(",", "", $combination['price']);
        //         $stock = $combination['stock'];
        //         $sellings = $combination['selling'];   
    
        //         $combiStmt = $conn->prepare("INSERT INTO product_option_combination(product_ix,combination_key,cost_price,stock) VALUES(?,?,?,?)");
        //         $combiStmt -> bind_param("ssss",$productIx,$name,$price,$stock);
        //         $combiStmt->execute();
    
        //         $combiIx = $combiStmt->insert_id;
    
        //         // 옵션 데이터
        //         foreach ($sellings as $selling) {
        //             $marketStmt = $conn->prepare("INSERT INTO product_option_market_price(product_option_comb_ix,market_ix,price) VALUES(?,?,?)");
        //             $sellingPrice = str_replace("","",$selling['value']);
        //             $marketStmt->bind_param("sss",$combiIx,$selling['ix'],$sellingPrice);
        //             $marketStmt->execute();
        //             // echo "Market ID $marketIx: $sellingPrice<br>";
        //         }
        //     }
    
        //     $conn->commit();

        //     ob_clean();
        //     $response['status'] = 'success';
        //     echo json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    
            
        // } else {
        //     echo "No data received!";
        // }
    }else if($addCount==100){
        //대량등록 
        $startTime = date("Y-m-d H:i:s");
        $endTime = date("Y-m-d H:i:s");
        $productMarketIx = isset($_POST['productMarketIx']) ? $_POST['productMarketIx'] : ''; //마켓 ix

        //네이버 파일인지 쿠팡파일인지 확인
        // $marketQuery = "SELECT market_name FROM market WHERE user_ix='$userIx' AND ix='$productMarketIx'";
        // $result = $conn->query($marketQuery);
        // $row = $result->fetch_assoc();
        // $marketName = $row['market_name'];

        // 마지막에 product_option_market_price에 값을 넣기위하여 해당 유저의 market수를 체크
        $marketStmt = $conn->prepare("SELECT * FROM market WHERE user_ix=?");
        $marketStmt->bind_param("s",$userIx);
        $marketStmt->execute();

        $tmpResult = $marketStmt->get_result();

        if ($tmpResult->num_rows > 0) {
            while ($row = $tmpResult->fetch_assoc()) {
                $marketResult[] = $row;
            }
        }

        $listResult = [];
        if (isset($_FILES['productExcelFile'])) {
            // 파일 경로
            try {
                $filePath = $_FILES['productExcelFile']['tmp_name'];

                // 엑셀 파일 읽기
                if ($xlsxA = SimpleXLSX::parse($filePath)) {
                    $dataA = $xlsxA->rows();
                } else {
                    throw new Exception("Error reading Excel A: " . SimpleXLSX::parseError());
                }
            


                if(isset($dataA)){
                    foreach ($dataA as $indexA => $rowA) {
                        if($indexA<=2){
                            continue;
                        }
                        
                        $account = $rowA[0]; //거래처
                        $category = $rowA[1]; //카테고리
                        $name = $rowA[2];
                        $optionValue = $rowA[3];
                        $cost = $rowA[4];
                        $quantity = $rowA[5];
                        $alarmQuantity = $rowA[6];

                        if($name=="" || $cost=="") {
                            continue;
                        }

                        //거래처, 카테고리 빈칸시 기타
                        if($account==""){
                            $account = "기타";
                        }
                        if($category==""){
                            $category = "기타";
                        }
                        if($quantity=="") {
                            $quantity = 0;
                        }
                        if($alarmQuantity==""){
                            $alarmQuantity = 0;
                        }
                        
                        $productName = $name." ".$optionValue;

                        //account 중복값 무시 삽입
                        $accountStmt = $conn->prepare("INSERT IGNORE INTO account(user_ix,name) VALUES(?,?)");
                        $accountStmt->bind_param("ss",$userIx,$account);
                        $accountStmt->execute();

                        // account ix select
                        $accountIx = $conn->query("SELECT ix FROM account WHERE user_ix='$userIx' AND name='$account'")->fetch_assoc()['ix'];

                        //category 중복값 무시 삽입
                        $categoryStmt = $conn->prepare("INSERT IGNORE INTO category(user_ix,name) VALUES(?,?)");
                        $categoryStmt->bind_param("ss",$userIx,$category);
                        $categoryStmt->execute();

                        // category ix select
                        $cateIx = $conn->query("SELECT ix FROM category WHERE user_ix='$userIx' AND name='$category'")->fetch_assoc()['ix'];



                        $productStmt = $conn->prepare("INSERT IGNORE INTO matching_name(user_ix,category_ix,account_ix,matching_name,cost,stock,alarm_stock) VALUES(?,?,?,?,?,?,?)");
                        $productStmt->bind_param("sssssss",$userIx,$cateIx,$accountIx,$productName,$cost,$quantity,$alarmQuantity);
                        if (!$productStmt->execute()) {
                            throw new Exception("Error executing product statement: " . $productStmt->error); // *** 수정 ***
                        }

                    }
            
            
                    ob_clean();
                    echo json_encode(['status' => 'success', 'message' => 'Excel data processed successfully', 'startTime'=>$startTime,'endTime'=>$endTime],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE); // *** 수정 ***
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Data parsing failed. Please check the Excel format.', 'startTime'=>$startTime,'endTime'=>$endTime],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                    // throw new Exception("Data parsing failed. Please check the Excel format.");
                }
            } catch (Exception $e){
                echo json_encode(['status' => 'error', 'message1' => $e->getMessage(), 'file'=>$e->getFile(), 'line'=>$e->getLine()],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }


    // 삭제
    }else if($addCount==0){
        $productIx = $_POST['productIx'] ?? '';

        $productDelStmt = $conn->prepare("DELETE FROM product WHERE user_ix=? AND ix=?");
        $productDelStmt->bind_param("ss",$userIx,$productIx);
        if($productDelStmt->execute()){
            echo json_encode(['status' => 'success', 'message' => 'product delete successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'product delete failed'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'no post file'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}


?>