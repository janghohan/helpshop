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

    $type = isset($_POST['type']) ? $_POST['type'] : ''; // option-edit 구별 타입입

    //옵션삭제 
    if($type=='opDel'){
        $combIx = isset($_POST['combIx']) ? $_POST['combIx'] : '';
        $mPriceIx = isset($_POST['mPriceIx']) ? $_POST['mPriceIx'] : '';
        
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
        $combIx = isset($_POST['combIx']) ? $_POST['combIx'] : '';
        $mPriceIx = isset($_POST['mPriceIx']) ? $_POST['mPriceIx'] : '';
        $optionValue = isset($_POST['optionValue']) ? $_POST['optionValue'] : '';
        $optionPrice = isset($_POST['optionPrice']) ? $_POST['optionPrice'] : '';
        $optionCost = isset($_POST['optionCost']) ? $_POST['optionCost'] : '';
        $optionStock = isset($_POST['optionStock']) ? $_POST['optionStock'] : '';

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
        $productName = isset($_POST['productName']) ? $_POST['productName'] : '';
        $productMemo = isset($_POST['productMemo']) ? $_POST['productMemo'] : '';
        $accountIx = isset($_POST['accountIx']) ? $_POST['accountIx'] : '';
        $categoryIx = isset($_POST['categoryIx']) ? $_POST['categoryIx'] : '';
        $productIx = isset($_POST['productIx']) ? $_POST['productIx'] : '';
        $updateTime = date("Y-m-d H:i:s");

        //옵션값, 원가, 재고 수정
        $productStmt = $conn->prepare("UPDATE product SET name=?, memo=?, account_ix=?, category_ix=?, update_at=? WHERE ix=? AND user_ix=?");
        $productStmt->bind_param("sssssss",$productName,$productMemo,$accountIx,$categoryIx,$updateTime,$productIx,$userIx);
        if(!$productStmt->execute()){
            throw new Exception("Error executing productUpdateStmt statement: " . $productStmt->error); // *** 수정 ***
        }

        echo json_encode(['status' => 'success', 'message' => 'op update processed successfully'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    
    }else if($type=='matchingEdit'){
        $matchingIx = $_POST['matchingIx'] ?? '';
        $value = $_POST['value'] ?? '';
        $editCol = $_POST['editCol'] ?? '';

        $allowedColumns = ['cost', 'stock', 'category_ix', 'account_ix']; // 수정 가능한 컬럼들 추가
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