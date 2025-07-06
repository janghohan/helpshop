<?php
header('Content-Type: application/json; charset=UTF-8');

$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (!isset($_FILES['upload']) || $_FILES['upload']['error'] != 0) {
    http_response_code(400);
    echo json_encode(['error' => ['message' => '파일 업로드 실패']]);
    exit;
}

// 파일명 안전하게
$filename = $_FILES['upload']['name'];
$filepath = $upload_dir . $filename;

if (move_uploaded_file($_FILES['upload']['tmp_name'], $filepath)) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $location = $protocol . $domain . "/helpshop/api/" . $filepath;

    echo json_encode([
        'url' => $location
    ],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode(['error' => ['message' => '파일 저장 실패']]);
}
?>
