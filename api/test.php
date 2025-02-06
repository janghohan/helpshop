<?php
// SimpleXLSX로 엑셀 파일 읽기
require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음


$inputFile = "test.xlsx";
$outputFile = "unlocked.xlsx";
$password = "4232";

// Python 스크립트 실행
exec("python excel.py $inputFile $outputFile $password", $output, $return_var);

if ($return_var !== 0) {
    die("암호 해제 실패");
}



if ($xlsx = SimpleXLSX::parse($outputFile)) {
    print_r($xlsx->rows());
} else {
    echo SimpleXLSX::parseError();
}
?>
