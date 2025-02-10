<?php
session_start();
include '../dbConnect.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userIx = isset($_SESSION['user_ix']) ? : '1';

    if (isset($_POST['formData'])) {

        $formData = $_POST['formData'] ?? [];

        foreach ($formData as $index => $data) {
            $productName = htmlspecialchars($data['matchingData'] ?? "");
            $matchingCombix = htmlspecialchars($data['matchingValue'] ?? "");

            if($matchingCombix==""){
                continue;
            }

            $matchStmt = $conn->prepare("INSERT INTO db_match(user_ix,name_of_excel,combination_ix) VALUES(?,?,?)");
            $matchStmt -> bind_param("sss",$userIx,$productName,$matchingCombix);
            if(!$matchStmt->execute()){
                echo json_encode(['status' => 'fail', 'msg' => 'match insert fail'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            }
        }

        echo json_encode(['status' => 'success'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
}


?>