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
        $productName = $_POST['productName'] ?? '';

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
                    $groupedProducts = [];
                    $previousProductName = null; // 이전 이름을 저장

                    $duplicationNameArray = [];
                    foreach ($dataA as $indexA => $rowA) {
                        if($indexA<=3){
                            continue;
                        }
                        
                        $account = $rowA[0];
                        $category = $rowA[1];
                        $name = $rowA[2];
                        $option = $rowA[3];
                        $optionValue = $rowA[4];
                        $price = $rowA[5];
                        $cost = $rowA[6];
                        $quantity = $rowA[7];
            
                        $emptyValue = "";
            
                        $currentProductName = $name;
                        if ($currentProductName !== $previousProductName) {

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


                            // 상품명이 바뀐다.
                            $productStmt = $conn->prepare("INSERT IGNORE INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
                            if (!$productStmt) {
                                throw new Exception("Error preparing product statement: " . $conn->error); // *** 수정 ***
                            }
                            $productStmt->bind_param("sssss",$userIx,$accountIx,$cateIx,$name,$emptyValue);
                            if (!$productStmt->execute()) {
                                throw new Exception("Error executing product statement: " . $productStmt->error); // *** 수정 ***
                            }
                        
                            if ($productStmt->affected_rows === 0) {
                                $duplicationNameArray[] = $name;
                                // throw new Exception("중복된 주문으로 인해 삽입되지 않았습니다.");
                            }else{
                                $productIx = $productStmt->insert_id;

                                if (!isset($groupedProducts[$name])) {
                                    $groupedProducts[$name] = [
                                        'product_ix' => $productIx,
                                    ];
                                }
                            }
                            
                        }
            
                        $previousProductName = $currentProductName; // 현재 주문번호를 이전 주문번호로 갱신          
                    }
            
            
                    $previousProductName = null; // 이전 이름을 저장
                    foreach($dataA as $indexA => $rowA){
                        if($indexA<=2){
                            continue;
                        }
                        // 중복된 상품은 패스
                        if (in_array($rowA[2], $duplicationNameArray)) {
                            continue;
                        }

                        $name = $rowA[2];
                        $option = $rowA[3];
                        $optionValue = $rowA[4];
                        $price = $rowA[5];
                        $cost = $rowA[6];
                        $quantity = $rowA[7];
                        $trackQuantity = $rowA[8];
                        $productIx = $groupedProducts[$name]['product_ix']; //미리 저장한 product table의 ix
                              
                        $currentProductName = $name;

                        
                        if ($currentProductName !== $previousProductName) {
                            // 상품명이 바뀐다.
                            
                            // 구별옵션 갯수만큼 배열 확보
                            $eachOptionArray = explode(",",str_replace(" ", "", $option)); //배열에 넣었다. ex) ['색상','크기']
                            foreach($eachOptionArray as $i => $op){
                                $valueArray[$i] = []; //옵션명에 맞게 옵션값을 담는 배열을 생성
                            }
                        }
            
                        $eachOptionValue = explode(',' , $optionValue); //ex) ['레드','90mm']
                        foreach($eachOptionValue as $index => $eachValue){
                            $eachValue = str_replace(" ", "", $eachValue);
                            if(!in_array($eachValue,$valueArray[$index])){ //색상에 해당하는 배열안에 '레드'라는 값이 없으면 
                                $insertOp = $eachOptionArray[$index]; //색상
                                $insertOptionValue = $eachValue; //레드
            
                                $optionStmt = $conn->prepare("INSERT INTO product_option(product_ix,name,value) VALUES(?,?,?)");
                                if (!$optionStmt) {
                                    throw new Exception("Error preparing option statement: " . $conn->error); // *** 수정 ***
                                }
                                $optionStmt->bind_param("sss",$productIx,$insertOp,$insertOptionValue);
                                if (!$optionStmt->execute()) {
                                    throw new Exception("Error executing option statement: " . $optionStmt->error); // *** 수정 ***
                                }
            
                                array_push($valueArray[$index],$eachValue);
                            }
                        }
            
            
                        $previousProductName = $currentProductName; // 현재 상품명을 이전 상품명으로 갱신
            
            
                        $combiStmt = $conn->prepare("INSERT INTO product_option_combination(product_ix,combination_key,cost_price,stock,track_stock) VALUES(?,?,?,?,?)");
                        if (!$combiStmt) {
                            throw new Exception("Error preparing combination statement: " . $conn->error); // *** 수정 ***
                        }
                        $combiStmt -> bind_param("sssss",$productIx,$optionValue,$cost,$quantity,$trackQuantity);
                        if (!$combiStmt->execute()) {
                            throw new Exception("Error executing combination statement: " . $combiStmt->error); // *** 수정 ***
                        }
            
                        $combiIx = $combiStmt->insert_id;
            
            
                        
                        // product_option_market_price 데이터

                        foreach($marketResult as $market){
                            $marketPriceStmt = $conn->prepare("INSERT INTO product_option_market_price(product_option_comb_ix,market_ix,price) VALUES(?,?,?)");
                            if (!$marketPriceStmt) {
                                throw new Exception("Error preparing market statement: " . $conn->error); // *** 수정 ***
                            }
                            $marketPriceStmt->bind_param("sss",$combiIx,$market['ix'],$price);
                            if (!$marketPriceStmt->execute()) {
                                throw new Exception("Error executing market statement: " . $marketPriceStmt->error); // *** 수정 ***
                            }
                            // echo "Market ID $marketIx: $sellingPrice<br>";
                    
                            $conn->commit();
                        }
                        
                        $endTime = date("Y-m-d H:i:s");
                    }
                    
                    // $listStmt = $conn->prepare("SELECT poc.ix as combIx, pomp.ix as mpIx, 
                    // m.market_name, p.name, poc.combination_key, poc.cost_price, poc.stock, pomp.price FROM product p 
                    // JOIN product_option_combination poc ON p.ix = poc.product_ix AND p.user_ix=? 
                    // JOIN product_option_market_price pomp ON poc.ix = pomp.product_option_comb_ix 
                    // JOIN market m ON m.ix = pomp.market_ix AND p.create_at >= ? AND p.create_at <= ?");
                    // if (!$listStmt) {
                    //     throw new Exception("Error preparing list statement: " . $conn->error); // *** 수정 ***
                    // }
                    // $listStmt->bind_param("sss",$userIx,$startTime,$endTime);
                    // if (!$listStmt->execute()) {
                    //     throw new Exception("Error executing list statement: " . $listStmt->error); // *** 수정 ***
                    // }

                    // $result = $listStmt->get_result();

                    // if ($result->num_rows > 0) {
                    //     while ($row = $result->fetch_assoc()) {
                    //         $listResult[] = $row;
                    //     }
                    // }
                    
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
        $productDelStmt->bind_param("ss",$userIx,$producIx);
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