<?php
// DB 연결
include './dbConnect.php';
if ($conn->connect_error) {
    die('DB 연결 실패: ' . $conn->connect_error);
}

// POST 데이터 가져오기
$title = $conn->real_escape_string($_POST['title']);
$content = $conn->real_escape_string($_POST['content']);

// 저장
$sql = "INSERT INTO posts (title, content, created_at) VALUES ('$title', '$content', NOW())";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('저장되었습니다.'); location.href='글목록페이지.php';</script>";
} else {
    echo "오류: " . $conn->error;
}

$conn->close();
?>