<?php
session_start();
include '../dbConnect.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = $_SESSION['user_ix'] ?? '1';

    $detailIx = $_POST['detailIx'] ?? '';
    $matchingName = $_POST['matchingName'] ?? '';
    $cost = $_POST['cost'] ?? '';
    $orderName = $_POST['orderName'] ?? '';
    
    //matching_name table 체크 후 저장 
    $chkStmt = $conn->prepare("SELECT * FROM matching_name WHERE user_ix=? AND matching_name=?");
    $chkStmt->bind_param("ss",$userIx,$matchingName);
    $chkStmt->execute();

    $result = $chkStmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // 결과에서 한 행을 가져옴
        $nameIx = $row['ix'];

        //db_match table 저장
        $matchStmt = $conn->prepare("INSERT INTO db_match(user_ix,details_ix,name_of_excel,matching_ix) VALUES(?,?,?,?)");
        $matchStmt->bind_param("ssss",$userIx,$detailIx,$orderName,$nameIx);
        if(!$matchStmt->execute()){
            echo json_encode(['status' => 'fail', 'msg' => '동기화 오류, 오류 내용과 함께 문의해주세요.'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(['status' => 'success'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }

    }else{
        //
        $accountStmt = $conn->prepare("SELECT * FROM account WHERE user_ix=?");
        $accountStmt->bind_param("s",$userIx);
        $accountStmt->execute();
        $accountResult = $accountStmt->get_result();
        if ($accountResult->num_rows > 0) {
            $accountRow = $accountResult->fetch_assoc(); // 결과에서 한 행을 가져옴
            $accountIx = $accountRow['ix'];
        }

        $categoryStmt = $conn->prepare("SELECT * FROM category WHERE user_ix=?");
        $categoryStmt->bind_param("s",$userIx);
        $categoryStmt->execute();
        $categoryResult = $categoryStmt->get_result();
        if ($categoryResult->num_rows > 0) {
            $categoryRow = $categoryResult->fetch_assoc(); // 결과에서 한 행을 가져옴
            $categoryIx = $categoryRow['ix'];
        }

        $cost = str_replace(",","",$cost);
        $nameStmt = $conn->prepare("INSERT INTO matching_name(user_ix,category_ix,account_ix,matching_name,cost) VALUES(?,?,?,?,?)");
        $nameStmt->bind_param("sssss", $userIx,$categoryIx,$accountIx,$matchingName,$cost);
        $nameStmt->execute();
        
        $nameIx = $nameStmt->insert_id;
        
        //db_match table 저장
        $matchStmt = $conn->prepare("INSERT INTO db_match(user_ix,details_ix,name_of_excel,matching_ix) VALUES(?,?,?,?)");
        $matchStmt->bind_param("ssss",$userIx,$detailIx,$orderName,$nameIx);
        if(!$matchStmt->execute()){
            echo json_encode(['status' => 'fail', 'msg' => '동기화 오류, 오류 내용과 함께 문의해주세요.'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(['status' => 'success'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    }

    

    // if (isset($_POST['formData'])) {

    //     $formData = $_POST['formData'] ?? [];

    //     foreach ($formData as $index => $data) {
    //         $productName = htmlspecialchars($data['matchingData'] ?? "");
    //         $matchingCombix = htmlspecialchars($data['matchingValue'] ?? "");

    //         if($matchingCombix==""){
    //             continue;
    //         }

    //         $matchStmt = $conn->prepare("INSERT INTO db_match(user_ix,name_of_excel,combination_ix) VALUES(?,?,?)");
    //         $matchStmt -> bind_param("sss",$userIx,$productName,$matchingCombix);
    //         if(!$matchStmt->execute()){
    //             echo json_encode(['status' => 'fail', 'msg' => 'match insert fail'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    //             break;
    //         }
    //     }

    // }
}


?>