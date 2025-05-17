<?php
session_start();
include '../dbConnect.php';

require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음
header('Content-Type: application/json');

$userIx = $_SESSION['user_ix'] ?? '1';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON 문자열로 받은 데이터를 파싱

    $type =  $_POST['type'] ?? ''; // option-edit 구별 타입입

    //옵션삭제 
    if($type=='opDel'){
        $combIx = $_POST['combIx'] ?? '';
        $mPriceIx = $_POST['mPriceIx'] ?? '';
        
        $totalCount = $conn->query("SELECT COUNT(*) as c FROM product_option_market_price WHERE product_option_comb_ix='$combIx'")->fetch_assoc()['c'];
    

        //해당 comIx로 값이 하나이상면 pomp값만 삭제
        if ($totalCount > 1 ) {
            $pompDelStmt = $conn->prepare("DELETE FROM product_option_market_price WHERE ix=?");
            $pompDelStmt->bind_param("s",$mPriceIx);
            if(!$pompDelStmt->execute()){
                throw new Exception("Error executing pompDeleStmt statement: " . $pompDelStmt->error); // *** 수정 ***
            }
        }else{ //하나면 combination까지 같이 삭제
            $combDeleStmt = $conn->prepare("DELETE FROM product_option_combination WHERE ix = ?");
            $combDeleStmt->bind_param("s",$combIx);
            if(!$combDeleStmt->execute()){
                throw new Exception("Error executing combDelStmt statement: " . $combDeleStmt->error); // *** 수정 ***
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'op delete processed successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    //옵션 수정
    }else if($type=='opEdit'){
        $combIx = $_POST['combIx'] ?? '';
        $mPriceIx = $_POST['mPriceIx'] ?? '';
        $optionValue = $_POST['optionValue'] ?? '';
        $optionPrice = $_POST['optionPrice'] ?? '';
        $optionCost = $_POST['optionCost'] ?? '';
        $optionStock = $_POST['optionStock'] ?? '';

        $optionPrice = (Int)str_replace(",","",$optionPrice);
        $optionCost = (Int)str_replace(",","",$optionCost);

        //옵션값, 원가, 재고 수정
        $combStmt = $conn->prepare("UPDATE product_option_combination SET combination_key=?, cost_price=?, stock=? WHERE ix=?");
        $combStmt->bind_param("ssss",$optionValue,$optionCost,$optionStock,$combIx);
        if(!$combStmt->execute()){
            throw new Exception("Error executing combUpdateStmt statement: " . $combStmt->error); // *** 수정 ***
        }


        //가격 수정
        $marketPriceStmt = $conn->prepare("UPDATE product_option_market_price SET price=? WHERE ix=?");
        $marketPriceStmt->bind_param("ss",$optionPrice,$mPriceIx);
        if(!$marketPriceStmt->execute()){
            throw new Exception("Error executing priceUpdateStmt statement: " . $marketPriceStmt->error); // *** 수정 ***
        }

        echo json_encode(['status' => 'success', 'message' => 'op update processed successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    
    }else if($type=='productEdit'){
        $productName = $_POST['productName'] ?? '';
        $accountIx = $_POST['accountIx'] ?? '';
        $categoryIx = $_POST['categoryIx'] ?? '';
        $productIx = $_POST['productIx'] ?? '';
        $productCost =  $_POST['cost'] ?? 0;
        $productStock = $_POST['stock'] ?? 0;
        $updateTime = date("Y-m-d H:i:s");

        //옵션값, 원가, 재고 수정
        $productStmt = $conn->prepare("UPDATE matching_name SET matching_name=?, account_ix=?, category_ix=?, cost =?, stock=? WHERE ix=? AND user_ix=?");
        $productStmt->bind_param("sssssss",$productName,$accountIx,$categoryIx,$productCost,$productStock,$productIx,$userIx);
        if(!$productStmt->execute()){
            $response['status'] = false;
            $response['msg'] = '상품 수정 실패';
        }else{

            $response['status'] = true;
            $response['msg'] = '상품 수정 완료';

            $chkStmt = $conn->prepare("SELECT * FROM matching_name WHERE ix = ? AND user_ix = ?");
            $chkStmt->bind_param("ss",$productIx,$userIx);
            $chkStmt->execute();

            $chkResult = $chkStmt->get_result();

            if ($chkResult->num_rows > 0) {
                while ($chkRow = $chkResult->fetch_assoc()) {
                    $editStock = $chkRow['stock'];
                    $alarmStock = $chkRow['alarm_stock'];
                }

                if($editStock>=$alarmStock){
                    $updateStmt = $conn->prepare("UPDATE stock_alarm SET is_resolved=1 WHERE matching_ix = ?");
                    $updateStmt->bind_param("s",$productIx);
                    if(!$updateStmt->execute()){
                        $response['msg'] = "알람 재고 수정 실패";
                    }
                }

                $chkStmt -> close();
            }
        }

        echo json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    
    }else if($type=='matchingEdit'){
        $matchingIx = $_POST['matchingIx'] ?? '';
        $value = $_POST['value'] ?? '';
        $editCol = $_POST['editCol'] ?? '';

        $allowedColumns = ['cost', 'stock', 'category_ix', 'account_ix','alarm_stock']; // 수정 가능한 컬럼들 추가
        $value = str_replace(",","",$value);

        // 2️⃣ 컬럼명 검증
        if (!in_array($editCol, $allowedColumns)) {
            echo json_encode(['status' => 'fail', 'message' => 'no allowed column'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            die("잘못된 컬럼명입니다.");
            
        }
  

        //옵션값, 원가, 재고 수정
        $sql = "UPDATE matching_name SET `$editCol`=? WHERE ix=? AND user_ix=?";
        $matchingStmt = $conn->prepare($sql);
        $matchingStmt->bind_param("sss",$value,$matchingIx,$userIx);
        if(!$matchingStmt->execute()){
            // throw new Exception("Error executing productUpdateStmt statement: " . $productStmt->error); // *** 수정 ***
            echo json_encode(['status' => 'fail', 'message' => 'Error executing matchingUpdate statement'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(['status' => 'success', 'message' => 'matching update processed successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }

        

        // matchingIx': ix, 'value': newValue, 'type':'matchingEdit', 'editCol':type 
    }
    
} else {
    echo "Invalid request!";
}


?>