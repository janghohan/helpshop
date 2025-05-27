<?php
//A파일은 쿠팡 주문 파일
//B파일은 CJ에서 만든 쿠팡 템플릿


require_once __DIR__ . '/SimpleXLSX.php'; // 실제 경로 확인
require_once __DIR__ . '/SimpleXLSXGen.php'; // 실제 경로 확인

use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음



// mysqli_query($conn,$tmpOrdersSql);
$userIx = $_SESSION['user_ix'] ?? '1';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 택배사
    $deliveryCompany = $_POST['deliveryCompany'];
    $type = $_POST['type'];

    if($type=='deliver'){
        $processedFiles = [];

        // 주문한꺼번에 확인 하는 파일 만들기기
        $combinedData = [];
        $combinedData[] = ['판매처','수량','옵션','수취인','전화번호','주소','배송메모'];

        if($deliveryCompany=='cj'){
            $basicFile = './cjBasic.xlsx';
        }

        // 네이버파일로 택배사에 넣을 excel파일 만들기
        if (isset($_FILES['fileNaver'])  && $_FILES["fileNaver"]["error"] == UPLOAD_ERR_OK) {
            // 파일 경로
            $fileAPath = $_FILES['fileNaver']['tmp_name'];

            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($fileAPath)) {
                $dataA = $xlsxA->rows();
            } else {
                //비밀번호 설정으로 못 열면 다시 설정
                $pwd = "0000";
                $originalFileName = $_FILES["fileNaver"]["name"];

                $inputFile = 'excelFile/tmp/'.date("Ymd")."/".'smartStoreExcel_'.date("Y-m-d_His").'_'.$userIx.'.xlsx';
                $outputFile = 'excelFile/tmp/'.date("Ymd")."/"."unlocked_" . basename($inputFile);

                //폴더생성
                $destinationFolder = "./excelFile/tmp/".date("Ymd");
                if (!is_dir($destinationFolder)) {
                    if (!mkdir($destinationFolder, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                        die("폴더를 생성할 수 없습니다: $destinationFolder");
                    }
                }

                $destinationFolder2 = "./excelFile/useFile/".date("Ymd");
                if (!is_dir($destinationFolder2)) {
                    if (!mkdir($destinationFolder2, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                    }
                }

                // 파일 이동 (업로드 처리)
                if (!move_uploaded_file($fileAPath, $inputFile)) {
                    die("파일 업로드 실패");
                }
                // Python 스크립트 실행 : 비밀번호 삭제
                exec("python unlock_excel.py $inputFile $outputFile $pwd", $output, $return_var);

                $xlsxA = SimpleXLSX::parse($outputFile);
                $dataA = $xlsxA->rows();
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
                // 1 : 주문번호, 10 : 구매자, 17 : 주문일, 25 : 수량, 30 : 할인후 옵션별 주문금액, 40 : 배송비, 51 : 구매자연락처
                $name = $rowA[12]; // A 파일의 수취인 컬럼 값
                $phone = $rowA[47]; // A 파일의 전화번호 컬럼 값
                $code = $rowA[52]; // A 파일의 우편번호 컬럼 값
                $address = $rowA[49]; // A 파일의 주소 값
                $memo = $rowA[54]; // A 파일의 배송메세지 컬럼 값


                $dataB[$indexA] = []; //초기화


                $dataB[$indexA-1][0] = $name;
                $dataB[$indexA-1][1] = $phone;
                $dataB[$indexA-1][2] = "";
                $dataB[$indexA-1][3] = $address;
                $dataB[$indexA-1][4] = "극소";
                $dataB[$indexA-1][5] = "낚시용품";
                $dataB[$indexA-1][6] = $memo;

                $combinedData[] = ["네이버",$rowA[25], $rowA[19]." : ".$rowA[23], $rowA[12], extractMiddlePhoneNumber($rowA[53]),$rowA[49],$rowA[54]];


                // $tmpInsertStmt = $conn->prepare("INSERT INTO temp_orders(market_ix,order_number,order_date,user_ix,payment,shipping,product_name,quantity,buyer_name,buyer_phone,address) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                // $tmpInsertStmt->bind_param("sssssssssss",$)

                // $productStmt = $conn->prepare("INSERT INTO product(user_ix,account_ix,category_ix,name,memo) VALUES(?,?,?,?,?)");
                // $productStmt->bind_param("sssss",$userIx,$accountIx,$categoryIx,$productName,$productMemo);
                // $productStmt->execute();
            }

            // SimpleXLSXGen을 사용하여 업데이트된 A 데이터를 엑셀 파일로 저장
            $xlsx = SimpleXLSXGen::fromArray($dataB);
            $day = date("m-d");
            $fileLocation = 'excelFile/useFile/'.date("Ymd")."/".'택배사등록파일(네이버)_'.$userIx.'_'.$day.'.xlsx';
            $fileName = "택배사등록파일(네이버)_".$day.".xlsx";
            $xlsx->saveAs($fileLocation);

            $processedFiles[] = [
                'name' => $fileName,
                'url' => 'api/'.$fileLocation
            ];

            // 결과 링크 출력
        }

        // 쿠팡파일로 택배사에 넣을 excel파일 만들기
        if (isset($_FILES['fileCoupang']) && $_FILES["fileCoupang"]["error"] == UPLOAD_ERR_OK) {
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

            $destinationFolder2 = "./excelFile/useFile/".date("Ymd");
            if (!is_dir($destinationFolder2)) {
                if (!mkdir($destinationFolder2, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                }
            }

            // SimpleXLSXGen을 사용하여 업데이트된 A 데이터를 엑셀 파일로 저장
            $xlsx = SimpleXLSXGen::fromArray($dataB);
            $day = date("m-d");
            $fileLocation = 'excelFile/useFile/'.date("Ymd").'/택배사등록파일(쿠팡)_'.$userIx.'_'.$day.'.xlsx';
            $fileName = "택배사등록파일(쿠팡)_".$day.".xlsx";
            $xlsx->saveAs($fileLocation);

            $processedFiles[] = [
                'name' => $fileName,
                'url' => 'api/'.$fileLocation
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
        $fileLocation = "excelFile/useFile/".date("Ymd").'/주문파일(합본)'.$userIx.'_'.$day.'.xlsx';
        $fileName = "주문파일(합본)_".$day.".xlsx";
        $xlsx->saveAs($fileLocation);

        $processedFiles[] = [
            'name' => $fileName,
            'url' => 'api/'.$fileLocation,
        ];

        echo json_encode($processedFiles);
    
    
    // 
    }else if($type=='songjang'){
       

        if (isset($_FILES['naverFile']) && isset($_FILES['naverSongjang']) && $_FILES["naverFile"]["error"] == UPLOAD_ERR_OK && $_FILES["naverSongjang"]["error"] == UPLOAD_ERR_OK) {

            // 파일 경로
            $fileAPath = $_FILES['naverFile']['tmp_name'];
            $fileBPath = $_FILES['naverSongjang']['tmp_name'];

            $inputFile = "./excelFile/tmp/".date("Ymd").'/smartStoreSongjang_'.date("Y-m-d_His").'_'.$userIx.'.xlsx';
            $outputFile = "./excelFile/tmp/".date("Ymd")."/unlocked_naver" . basename($inputFile);
            $pwd = '0000';

            //폴더생성
            $destinationFolder = "./excelFile/tmp/".date("Ymd");
            if (!is_dir($destinationFolder)) {
                if (!mkdir($destinationFolder, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                    die("폴더를 생성할 수 없습니다: $destinationFolder");
                }
            }
            $destinationFolder2 = "./excelFile/useFile/".date("Ymd");
            if (!is_dir($destinationFolder2)) {
                if (!mkdir($destinationFolder2, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                }
            }

            // 파일 이동 (업로드 처리)
            if (!move_uploaded_file($fileAPath, $inputFile)) {
                die("파일 업로드 실패");
            }

            // Python 스크립트 실행 : 비밀번호 삭제
            exec("python unlock_excel.py $inputFile $outputFile $pwd", $output, $return_var);
    
            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($outputFile)) {
                $dataA = $xlsxA->rows();
            } else {
                echo "Error reading Excel A: " . SimpleXLSX::parseError();
                exit;
            }


            if ($xlsxB = SimpleXLSX::parse($fileBPath)) {
                $dataB = $xlsxB->rows();
            } else {
                echo "Error reading Excel B: " . SimpleXLSX::parseError();
                exit;
            }
    
            // B 파일의 B 컬럼과 A 파일의 B 컬럼을 비교하여 A 파일을 업데이트
            foreach ($dataB as $rowB) {
                $valueInBColumn = $rowB[23]; // B 파일의 B 컬럼 값
                foreach ($dataA as &$rowA) {
                    if ($rowA[49] == $valueInBColumn) { // A 파일의 B 컬럼 값과 비교
                        $rowA[6] = "CJ대한통운";
                        $rowA[7] = $rowB[7]; // B 파일의 A 컬럼 값을 A 파일의 A 컬럼에 삽입
                    }
                }
            }
    
            // SimpleXLSXGen을 사용하여 업데이트된 A 데이터를 엑셀 파일로 저장
            $xlsx = SimpleXLSXGen::fromArray($dataA);
            $day = date("m-d_His");
            $fileLocation = "excelFile/useFile/".date("Ymd").'/송장등록(네이버)_'.$day.'_'.$userIx.'.xlsx';
            $fileName = "송장등록(네이버)_".$day.".xlsx";
            $xlsx->saveAs($fileLocation);
            
            exec("python change_excel.py $fileLocation", $output, $return_var);

            $processedFiles[] = [
                'name' => $fileName,
                'url' => 'api/'.$fileLocation,
            ];

        }
        
        
        if (isset($_FILES['coupangFile']) && isset($_FILES['coupangSongjang']) && $_FILES["coupangFile"]["error"] == UPLOAD_ERR_OK && $_FILES["coupangSongjang"]["error"] == UPLOAD_ERR_OK) {
            // 파일 경로
            $fileAPath = $_FILES['coupangFile']['tmp_name'];
            $fileBPath = $_FILES['coupangSongjang']['tmp_name'];
    
    
            // 엑셀 파일 읽기
            if ($xlsxA = SimpleXLSX::parse($fileAPath)) {
                $dataA = $xlsxA->rows();
            } else {
                echo "Error reading Excel A: " . SimpleXLSX::parseError();
                exit;
            }
    
            if ($xlsxB = SimpleXLSX::parse($fileBPath)) {
                $dataB = $xlsxB->rows();
            } else {
                echo "Error reading Excel B: " . SimpleXLSX::parseError();
                exit;
            }
    
            foreach ($dataB as $rowB) {
                $valueInBColumn = $rowB[23]; // B 파일의 x 컬럼 값 : 주소
                // echo $valueInBColumn;
                foreach ($dataA as $rowIndex => &$rowA) {
                    if ($rowA[29] == $valueInBColumn) { // A 파일의 AD 컬럼 값과 비교
                        // echo removeHyphens($rowA[1]);
                        $rowA[4] = removeHyphens($rowB[7]); // B 파일의  H 컬럼 값을 A 파일의 E 컬럼에 삽입 : 송장번호
                        
    
                    }
                }
            }


            $destinationFolder2 = "./excelFile/useFile/".date("Ymd");
            if (!is_dir($destinationFolder2)) {
                if (!mkdir($destinationFolder2, 0777, true)) { // true는 하위 디렉토리도 생성하도록 설정
                }
            }
    
    
            // SimpleXLSXGen을 사용하여 업데이트된 A 데이터를 엑셀 파일로 저장
            $xlsx = SimpleXLSXGen::fromArray($dataA);
            $day = date("m-d_His");
            $fileLocation = "excelFile/useFile/".date("Ymd").'/송장등록(쿠팡)_'.$day.'_'.$userIx.'.xlsx';
            $fileName = "송장등록(쿠팡)_".$day.".xlsx";
            $xlsx->saveAs($fileLocation);

            $processedFiles[] = [
                'name' => $fileName,
                'url' => 'api/'.$fileLocation,
            ];        
        }

        // $response = [
        //     'status'=>'success',
        // ];
        ob_clean();
        echo json_encode($processedFiles);

    }
    
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


function removeHyphens($phoneNumber) {
    // str_replace 함수를 사용하여 '-'를 제거
    return str_replace('-', '', $phoneNumber);
}
?>