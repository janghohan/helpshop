<?php
// SimpleXLSX로 엑셀 파일 읽기
// require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
// require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

// use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
// use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음


// $inputFile = "test.xlsx";
// $outputFile = "unlocked.xlsx";
// $password = "4232";

// // Python 스크립트 실행
// exec("python excel.py $inputFile $outputFile $password", $output, $return_var);

// if ($return_var !== 0) {
//     die("암호 해제 실패");
// }



// if ($xlsx = SimpleXLSX::parse($outputFile)) {
//     print_r($xlsx->rows());
// } else {
//     echo SimpleXLSX::parseError();
// }


// //폴더생성
// $destinationFolder = "./excelFile/tmp/".date("Ymd");
// if (!is_dir($destinationFolder)) {
//     if (!mkdir($destinationFolder, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
//         die("폴더를 생성할 수 없습니다: $destinationFolder");
//     }
// }


require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인
$config = require __DIR__ . '/../config.php';

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음

$fileLocation = "excelFile/useFile/".date("Ymd").'/송장등록(네이버)_07-04_083112_1.xlsx';


// exec("/usr/bin/python3 /var/www/html/api/change_excel.py $fileLocation", $output, $return_var);

// $cmd = $config['python_path'] . ' ' . $config['change_script_path'] . ' ' . $fileLocation;
// exec($cmd. ' 2>&1', $output, $return_var);   

$cmd = "/usr/bin/python3 /var/www/html/api/change_excel.py " . escapeshellarg($fileLocation) . " 2>&1";
exec($cmd, $output, $return_var);

// 결과 출력
echo "Return Code: " . $return_var . "\n";
echo "Output:\n";
print_r($output);

?>
