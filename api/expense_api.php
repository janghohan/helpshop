<?php
session_start();
include '../dbConnect.php';
header('Content-Type: application/json');
$userIx = isset($_SESSION['user_ix']) ? : '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $expenseDate = isset($_POST['expenseDate']) ? $_POST['expenseDate'] : '';
    $expenseType = isset($_POST['expenseType']) ? $_POST['expenseType'] : '';
    $expenseMemo = isset($_POST['expenseMemo']) ? $_POST['expenseMemo'] : '';
    $expensePrice = isset($_POST['expensePrice']) ? $_POST['expensePrice'] : '';

    $$expensePrice = str_replace(",","",$expensePrice);

    $expenseStmt = $conn->prepare("INSERT INTO expense(user_ix,expense_type,expense_price,expense_memo,expense_date) VALUES(?,?,?,?,?)");
    $expenseStmt->bind_param("sssss", $userIx,$expenseType,$expensePrice,$expenseMemo,$expenseDate);
    if(!$expenseStmt->execute()){
        // throw new Exception("Error executing expense statement: " . $expenseStmt->error); // *** 수정 ***
        echo json_encode(['status' => 'fail'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }else{

        echo json_encode(['status' => 'success'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

}
?>