<?php
header('Content-Type: application/json; charset=UTF-8');

// 파일 업로드 처리
if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
    http_response_code(400);
    echo json_encode(['error' => '파일 업로드 실패']);
    exit;
}

$upload_dir = 'api/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 파일명 안전 처리
$filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $_FILES['file']['name']);
$filepath = $upload_dir . $filename;

if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $location = $protocol . $domain . '/' . $filepath;

    echo json_encode([
        'location' => $location
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => '파일 저장 실패']);
}
?>
