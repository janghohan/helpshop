<?php
//A파일은 쿠팡 주문 파일
//B파일은 CJ에서 만든 쿠팡 템플릿


require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 택배사
    $deliveryCompany = $_POST['deliveryCompany'];
    $type = $_POST['type'];
    $processedFiles = [];

    // 주문한꺼번에 확인 하는 파일 만들기기
    $combinedData = [];
    $combinedData[] = ['판매처','수량','옵션','수취인','전화번호','주소','배송메모'];

    if($deliveryCompany=='cj'){
        $basicFile = './cjBasic.xlsx';
    }

    // 네이버파일로 택배사에 넣을 excel파일 만들기
    if (isset($_FILES['fileNaver'])) {
        // 파일 경로
        $fileAPath = $_FILES['fileNaver']['tmp_name'];
        // $fileBPath = $_FILES['fileB']['tmp_name'];
        // $fileBPath = './cjBasic.xlsx';
        

        // 엑셀 파일 읽기
        if ($xlsxA = SimpleXLSX::parse($fileAPath)) {
            $dataA = $xlsxA->rows();
        } else {
            echo "Error reading Excel A: " . SimpleXLSX::parseError();
            exit;
        }

        if ($xlsxB = SimpleXLSX::parse($basicFile)) {
            $dataB = $xlsxB->rows();
        } else {
            echo "Error reading Excel B: " . SimpleXLSX::parseError();
            exit;
        }

        // B 파일의 B 컬럼과 A 파일의 B 컬럼을 비교하여 A 파일을 업데이트
        foreach ($dataA as $indexA => $rowA) {
            if($indexA===0){
                continue;
            }         

            $name = $rowA[12]; // A 파일의 수취인 컬럼 값
            $phone = $rowA[46]; // A 파일의 전화번호 컬럼 값
            $code = $rowA[52]; // A 파일의 우편번호 컬럼 값
            $address = $rowA[48]; // A 파일의 주소 값
            $memo = $rowA[53]; // A 파일의 배송메세지 컬럼 값


            $dataB[$indexA] = []; //초기화


            $dataB[$indexA-1][0] = $name;
            $dataB[$indexA-1][1] = $phone;
            $dataB[$indexA-1][2] = "";
            $dataB[$indexA-1][3] = $address;
            $dataB[$indexA-1][4] = "극소";
            $dataB[$indexA-1][5] = "낚시용품";
            $dataB[$indexA-1][6] = $memo;

            $combinedData[] = ["네이버",$rowA[24], $rowA[19]." : ".$rowA[22], $rowA[12], extractMiddlePhoneNumber($rowA[46]),$rowA[48],$rowA[53]];
        }

        // SimpleXLSXGen을 사용하여 업데이트된 A 데이터를 엑셀 파일로 저장
        $xlsx = SimpleXLSXGen::fromArray($dataB);
        $day = date("m-d");
        $newFileName = '택배사등록파일(네이버)_'.$day.'.xlsx';
        $xlsx->saveAs($newFileName);

        $processedFiles[] = [
            'name' => $newFileName,
            'url' => 'api/'.$newFileName
        ];

        // 결과 링크 출력
    }

    // 쿠팡파일로 택배사에 넣을 excel파일 만들기
    if (isset($_FILES['fileCoupang'])) {
        // 파일 경로
        $fileAPath = $_FILES['fileCoupang']['tmp_name'];
        // $fileBPath = $_FILES['fileB']['tmp_name'];
        // $fileBPath = './cjBasic.xlsx';
        

        // 엑셀 파일 읽기
        if ($xlsxA = SimpleXLSX::parse($fileAPath)) {
            $dataA = $xlsxA->rows();
        } else {
            echo "Error reading Excel A: " . SimpleXLSX::parseError();
            exit;
        }

        if ($xlsxB = SimpleXLSX::parse($basicFile)) {
            $dataB = $xlsxB->rows();
        } else {
            echo "Error reading Excel B: " . SimpleXLSX::parseError();
            exit;
        }

        // B 파일의 B 컬럼과 A 파일의 B 컬럼을 비교하여 A 파일을 업데이트
        foreach ($dataA as $indexA => $rowA) {
            if($indexA===0){
                continue;
            }         

            $name = $rowA[26]; // A 파일의 수취인 컬럼 값
            $phone = $rowA[27]; // A 파일의 전화번호 컬럼 값
            $code = $rowA[28]; // A 파일의 우편번호 컬럼 값
            $address = $rowA[29]; // A 파일의 주소 값
            $memo = $rowA[30]; // A 파일의 배송메세지 컬럼 값


            $dataB[$indexA] = []; //초기화


            $dataB[$indexA][0] = $name;
            $dataB[$indexA][1] = $phone;
            $dataB[$indexA][2] = "";
            $dataB[$indexA][3] = $address;
            $dataB[$indexA][4] = "극소";
            $dataB[$indexA][5] = "낚시용품";
            $dataB[$indexA][6] = $memo;

            $combinedData[] = ["쿠팡",$rowA[22], $rowA[10]." : ".$rowA[11], $rowA[26], extractMiddlePhoneNumber($rowA[27]),$rowA[29],$rowA[30]];
            
        }

        // SimpleXLSXGen을 사용하여 업데이트된 A 데이터를 엑셀 파일로 저장
        $xlsx = SimpleXLSXGen::fromArray($dataB);
        $day = date("m-d");
        $newFileName = '택배사등록파일(쿠팡)_'.$day.'.xlsx';
        $xlsx->saveAs($newFileName);

        $processedFiles[] = [
            'name' => $newFileName,
            'url' => 'api/'.$newFileName
        ];

        // 결과 링크 출력
    }

    // 쿠팡,네이버 합친 엑셀 파일 생성
    $xlsx = $combinedData;

    // D 컬럼 값으로 행을 그룹화
    $dColumnGroups = [];
    for ($i = 1; $i < count($xlsx); $i++) {
         $dValue = $xlsx[$i][5];  // D 컬럼 값 (4번째 컬럼)
         
         if ($dValue !== "") {  // D 컬럼 값이 비어 있지 않을 경우만 처리
             // D 컬럼 값이 이미 배열에 있다면 해당 그룹에 추가
             if (!isset($dColumnGroups[$dValue])) {
                 $dColumnGroups[$dValue] = [];
             }
             $dColumnGroups[$dValue][] = $i;
         }
    }

     // 행 재배치
    $newData = [$xlsx[0]];  // 첫 번째 행(헤더)은 그대로 둠
    $usedRows = [];

    foreach ($dColumnGroups as $group) {
         // 그룹에 속한 행들을 한꺼번에 이동
        foreach ($group as $rowIndex) {
            if (!in_array($rowIndex, $usedRows)) {  // 중복된 행 이동 방지
                $newData[] = $xlsx[$rowIndex];
                $usedRows[] = $rowIndex;
            }
        }
    }

    // 남은 행들 추가 (중복되지 않은 행)
    for ($i = 1; $i < count($xlsx); $i++) {
        if (!in_array($i, $usedRows)) {
            $newData[] = $xlsx[$i];
        }
    }

    // 새로운 엑셀 파일 생성 및 저장
    $xlsx = SimpleXLSXGen::fromArray($newData);
    $day = date("m-d");
    $filename = '주문파일(합본)'.$day.'.xlsx';
    $xlsx->saveAs($filename);

    $processedFiles[] = [
        'name' => $filename,
        'url' => 'api/'.$filename
    ];

    echo json_encode($processedFiles);
}



function extractMiddlePhoneNumber($phoneNumber) {
    // 전화번호에서 하이픈(-)을 기준으로 분리
    $parts = explode('-', $phoneNumber);

    // 배열이 3부분으로 나누어졌는지 확인
    if (count($parts) === 3) {
        // 가운데 번호를 반환
        return $parts[1];
    }

    // 유효하지 않은 전화번호 형식일 경우 빈 문자열 반환
    return '';
}
?>