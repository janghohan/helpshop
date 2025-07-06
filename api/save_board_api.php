<?php
// 저장할 폴더 경로
include '../dbConnect.php';
$uploadDir = __DIR__ . '/../boardRequest/';

// boardRequest 폴더 없으면 생성
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// POST 데이터 받기
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$password = $_POST['password'] ?? '';
$isPrivate = isset($_POST['isPrivate']) ? 1 : 0; // 체크박스

// 필수값 체크
if (empty($title) || empty($content) || empty($password)) {
    die('필수 입력값이 누락되었습니다.');
}

// 파일 업로드 처리
$imagePaths = [];
if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        $originalName = $_FILES['images']['name'][$key];
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $newFileName = uniqid('img_', true) . '.' . $ext;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            // 저장할 때 상대 경로로 저장
            $imagePaths[] = './boardRequest/' . $newFileName;
        }
    }
}

// 이미지 경로를 JSON 문자열로 저장
$imageJson = json_encode($imagePaths);

// 비밀번호는 해시해서 저장 (보안을 위해)
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// DB에 저장
$stmt = $conn->prepare("INSERT INTO board (type, title, contents, image_paths,author_name, password, is_secret) VALUES ('board',?,?,?,'',?,?)");
$stmt->bind_param("sssss",$title,$content,$imageJson,$passwordHash,$isPrivate);
$stmt->execute();

// 저장 완료 후 리다이렉트
header('Location: ../board.php');
exit;
?>
