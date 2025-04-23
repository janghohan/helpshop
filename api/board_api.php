<?php
include '../dbConnect.php';

$type = $_POST['type'] ?? '';

if($type=="create"){
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $password = $_POST['password'] ?? '';
    $is_private = $_POST['is_private'] ?? '0';
    $created_at = date('Y-m-d H:i:s');

    // 이미지 업로드 처리
    $uploadedFiles = [];
    $uploadDir = '../boardRequest/'; // write.html과 같은 레벨
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $name) {
            $tmp_name = $_FILES['images']['tmp_name'][$key];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $newName = uniqid('img_') . '.' . $ext;
            $destination = $uploadDir . $newName;

            if (move_uploaded_file($tmp_name, $destination)) {
            $uploadedFiles[] = 'boardRequest/' . $newName; // 상대경로 저장
            }
        }
    }

    // JSON 배열 형태로 저장 (DB에 저장 시 추후 파싱 용이)
    $image_paths = json_encode($uploadedFiles);

    // DB 저장
    $stmt = $conn->prepare("INSERT INTO board (title, content, password, is_private, image_paths, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $content, $password, $is_private, $image_paths, $created_at);

    if ($stmt->execute()) {
    echo "<script>alert('게시글이 등록되었습니다.'); location.href='../list.html';</script>";
    } else {
    echo "<script>alert('등록 실패: " . $conn->error . "'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();

}

?>