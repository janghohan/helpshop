<?php


session_start();
session_unset();  // 모든 세션 변수 제거
session_destroy(); // 세션 종료
header("Location: ../login.php");
exit();

?>