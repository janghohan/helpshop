<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    if($type=='orderList'){
        // 진행률 반환
        $progress = isset($_SESSION['orderDumpProgress']) ? $_SESSION['orderDumpProgress'] : 0;
        echo json_encode(['progress' => $progress],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
}

?>