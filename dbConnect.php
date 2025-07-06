<?php 

// 데이터베이스 연결 설정
$servername = "my-db.c1ewoc0o0w94.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "market4232!!";
$dbname = "market";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 ?>