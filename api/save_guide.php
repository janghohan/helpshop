<?php
// DB 연결 (예시용 — 필요에 따라 수정)
include '../dbConnect.php';

try {
    // POST 데이터 수신
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $contents = isset($_POST['contents']) ? trim($_POST['contents']) : '';

    // 유효성 검사
    if (empty($title) || empty($contents)) {
        throw new Exception('제목과 내용을 모두 입력해야 합니다.');
    }

    // DB 저장
    $stmt = $conn->prepare("INSERT INTO board (type,title, contents,author_name,password) VALUES ('guide',?, ?,'관리자','')");
    $stmt->bind_param("ss",$title,$contents);
    $stmt->execute();

    // 등록 완료 후 리다이렉트
    header('Location: ../tmp_editor.php');
    exit;

} catch (Exception $e) {
    echo '에러: ' . htmlspecialchars($e->getMessage());
}
?>
