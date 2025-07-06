<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$inputData = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($inputData['type']) ? $inputData['type'] : '';

    if($type=='orderList'){
        // 진행률 반환
        $progress = $_SESSION['orderDumpProgress'] ?? 0;
        echo json_encode(['progress' => $progress],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        exit();
    }else if($type=='dbMatching'){
        // 진행률 반환
        $progress = $_SESSION['matchingProgress'] ?? 0;
        echo json_encode(['progress' => $progress],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// echo json_encode(['progress' => $type],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);


?>