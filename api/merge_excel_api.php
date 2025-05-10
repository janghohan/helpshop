<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 파일 업로드 확인
if (!isset($_FILES['excel_files'])) {
    exit('엑셀 파일이 업로드되지 않았습니다.');
}

$files = $_FILES['excel_files'];
$uniqueRows = []; // 전체 행 문자열을 키로 저장
$resultSpreadsheet = new Spreadsheet();
$resultSheet = $resultSpreadsheet->getActiveSheet();
$resultRow = 1;

for ($i = 0; $i < count($files['name']); $i++) {
    if ($files['error'][$i] !== UPLOAD_ERR_OK) {
        continue;
    }

    $tmpPath = $files['tmp_name'][$i];

    try {
        $spreadsheet = IOFactory::load($tmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        $colCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 1; $col <= $colCount; $col++) {
                $value = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                $rowData[] = trim((string)$value);
            }

            $rowKey = implode('||', $rowData); // 행 전체 문자열로 변환

            if (isset($uniqueRows[$rowKey])) {
                continue; // 중복된 행은 스킵
            }

            $uniqueRows[$rowKey] = true;

            // 결과 시트에 행 추가
            for ($col = 1; $col <= $colCount; $col++) {
                $resultSheet->setCellValueByColumnAndRow($col, $resultRow, $rowData[$col - 1]);
            }
            $resultRow++;
        }
    } catch (Exception $e) {
        echo "파일 처리 중 오류 발생: " . $files['name'][$i] . " - " . $e->getMessage() . "<br>";
    }
}

// 결과 저장
$outputFile = 'merged_result.xlsx';
$writer = new Xlsx($resultSpreadsheet);
$writer->save($outputFile);

echo "<p>중복 없이 병합 완료: <a href='$outputFile' download>결과 다운로드</a></p>";
