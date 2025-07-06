<?php
session_start();
include '../dbConnect.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = $_SESSION['user_ix'] ?? '1';
    // JSON 문자열로 받은 데이터를 파싱
    $searchType = $_POST['searchType'] ?? '';
    $searchKeyword = $_POST['searchKeyword'] ?? '';

    if($searchType=='product'){
        $searchTerm = $conn->real_escape_string($searchKeyword); // 사용자 입력값
        $likeKeyword = "%$searchTerm%";
        $query = "SELECT * FROM matching_name WHERE user_ix = '$userIx' AND matching_name LIKE '$likeKeyword'";
        $result = $conn->query($query);

        $searchResult = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $searchResult[] = $row;
            }
        }

        ob_clean();
        echo json_encode($searchResult, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    //db-matching에서 매칭상품 검색
    }else if($searchType=='matching'){
        $searchTerm = $conn->real_escape_string($searchKeyword); // 사용자 입력값
        $likeKeyword = "%$searchTerm%";
        $query = "SELECT * FROM matching_name WHERE user_ix = '$userIx' AND matching_name LIKE '$likeKeyword'";

        $result = $conn->query($query);

        $searchResult = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $searchResult[] = $row;
            }
        }

        ob_clean();
        echo json_encode($searchResult, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    // $searchStmt->close();
    $conn->close();

}
?>
