<?php
session_start();
include '../dbConnect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    // JSON 문자열로 받은 데이터를 파싱
    $searchType = isset($_POST['searchType']) ? $_POST['searchType'] : '';
    $searchKeyword = isset($_POST['searchKeyword']) ? $_POST['searchKeyword'] : '';

    // $searchStmt = $conn->prepare("SELECT * FROM product_search_view WHERE market_user_ix = '?' AND product_user_ix = '?' AND product_name LIKE ?");
    // $likeKeyword = "%$searchKeyword%";
    // $searchStmt->bind_param("iis",$userIx,$userIx,$likeKeyword);
    // $searchStmt->execute();

    // $searchResult = $searchStmt->get_result();

    // $data = [];
    // while ($row = $searchResult->fetch_assoc()) {
    //     $data[] = $row;
    // }


    $searchTerm = $conn->real_escape_string($searchKeyword); // 사용자 입력값
    $likeKeyword = "%$searchTerm%";
    $query = "SELECT * FROM product_search_view WHERE market_user_ix = '$userIx' AND product_user_ix = '$userIx' AND product_name LIKE '$likeKeyword'";
    $result = $conn->query($query);

    $searchResult = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResult[] = $row;
        }
    }

    echo json_encode($searchResult, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    // $searchStmt->close();
    $conn->close();

}
?>