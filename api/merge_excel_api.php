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

$type = $_POST['type'] ?? '';
if($type=='coupang'){
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
    $outputFile = '쿠팡 주문파일(합본).xlsx';
    $writer = new Xlsx($resultSpreadsheet);
    $writer->save($outputFile);

    // echo "<p>중복 없이 병합 완료: <a href='$outputFile' download>결과 다운로드</a></p>";

    // 자동 다운로드 처리
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($outputFile) . '"');
    header('Content-Length: ' . filesize($outputFile));
    readfile($outputFile);

}else if($type=='growthSale'){ //로켓그로스 판매수수료 파일 합치기
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
            $sheetCount = $spreadsheet->getSheetCount();

            for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
                $sheet = $spreadsheet->getSheet($sheetIndex);
                $rows = $sheet->toArray(null, true, true, false); // null: 빈 셀 포함, true: 계산식 계산

                // 결과 시트 생성 또는 가져오기
                if ($resultSpreadsheet->getSheetCount() <= $sheetIndex) {
                    $resultSpreadsheet->createSheet($sheetIndex);
                }
                $resultSheet = $resultSpreadsheet->getSheet($sheetIndex);
                $resultSheet->setTitle('Sheet' . ($sheetIndex + 1)); // 이름 설정

                // 현재 시트의 포인터 위치
                if (!isset($sheetRowPointers[$sheetIndex])) {
                    $sheetRowPointers[$sheetIndex] = 1;
                }

                foreach ($rows as $rowData) {
                    // 전체 행을 문자열로 변환 (중복 체크)
                    $rowKey = implode('||', array_map('trim', $rowData));
                    if (isset($uniqueRowKeys[$sheetIndex][$rowKey])) {
                        continue;
                    }
                    $uniqueRowKeys[$sheetIndex][$rowKey] = true;

                    // 결과 시트에 행 추가
                    $colNum = 1;
                    foreach ($rowData as $cellValue) {
                        $resultSheet->setCellValueByColumnAndRow($colNum, $sheetRowPointers[$sheetIndex], $cellValue);
                        $colNum++;
                    }
                    $sheetRowPointers[$sheetIndex]++;
                }
            }

        } catch (Exception $e) {
            echo "파일 처리 중 오류 발생: " . $files['name'][$i] . " - " . $e->getMessage() . "<br>";
        }
    }

    // 결과 저장
    $outputFile = '로켓그로스_주문파일(합본)_판매수수료.xlsx';
    $writer = new Xlsx($resultSpreadsheet);
    $writer->save($outputFile);

    // 자동 다운로드 처리
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($outputFile) . '"');
    header('Content-Length: ' . filesize($outputFile));
    readfile($outputFile);

}else if($type=='growthShip'){
    $files = $_FILES['excel_files'];
    $uniqueRowKeys = []; // 시트별 중복 방지 키
    $resultSpreadsheets = []; // 결과용 시트 인덱스별 시트
    $sheetRowPointers = []; // 결과 시트별 현재 행 위치

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmpPath = $files['tmp_name'][$i];

        try {
            $spreadsheet = IOFactory::load($tmpPath);
            $sheetCount = $spreadsheet->getSheetCount();

            for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
                $sheet = $spreadsheet->getSheet($sheetIndex);
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();
                $colCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

                // 결과용 시트가 없다면 생성
                if (!isset($resultSpreadsheets[$sheetIndex])) {
                    $resultSpreadsheets[$sheetIndex] = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(new Spreadsheet(), 'Sheet' . ($sheetIndex + 1));
                    $sheetRowPointers[$sheetIndex] = 1;
                    $uniqueRowKeys[$sheetIndex] = [];
                }

                $resultSheet = $resultSpreadsheets[$sheetIndex];

                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = [];

                    for ($col = 1; $col <= $colCount; $col++) {
                        $cell = $sheet->getCellByColumnAndRow($col, $row);
                        $formatted = $cell->getValue(); // 엑셀 화면에 보이는 그대로
                        $rowData[] = (string)$formatted;
                    }

                    $rowKey = implode('||', $rowData);

                    if (isset($uniqueRowKeys[$sheetIndex][$rowKey])) {
                        continue; // 중복 스킵
                    }

                    $uniqueRowKeys[$sheetIndex][$rowKey] = true;

                    for ($col = 1; $col <= $colCount; $col++) {
                        $resultSheet->setCellValueByColumnAndRow($col, $sheetRowPointers[$sheetIndex], $rowData[$col - 1]);
                    }

                    $sheetRowPointers[$sheetIndex]++;
                }
            }
        } catch (Exception $e) {
            echo "파일 처리 중 오류 발생: " . $files['name'][$i] . " - " . $e->getMessage() . "<br>";
        }
    }

    // 최종 결과 파일 생성
    $finalSpreadsheet = new Spreadsheet();
    $finalSpreadsheet->removeSheetByIndex(0); // 기본 시트 제거

    foreach ($resultSpreadsheets as $sheetIndex => $sheet) {
        $finalSpreadsheet->addSheet($sheet);
    }

    // 저장 및 다운로드
    $outputFile = '로켓그로스_주문파일(합본)_입출고,배송비.xlsx';
    $writer = new Xlsx($finalSpreadsheet);
    $writer->save($outputFile);

    // 자동 다운로드 처리
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($outputFile) . '"');
    header('Content-Length: ' . filesize($outputFile));
    readfile($outputFile);
}

exit;