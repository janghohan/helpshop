<?php

// include './dbConnect.php';
$pdo = new PDO('mysql:host=localhost;dbname=helpshop;charset=utf8', 'root', '');


//기본값 설정
$account = ['ix'=> '', 'user_ix'=> '', 'name'=> '', 'account_manager'=>'', 'manager_contact'=> '', 'contact'=> '', 'site'=> '','address'=>'', 'account_number'=>'', 'memo'=>''];
$message = '';

// 수정 모드: GET 요청에 id가 있을 경우 해당 상품 정보 불러오기
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ix'])) {
    $stmt = $pdo->prepare("SELECT * FROM account WHERE ix = :ix");
    $stmt->execute(['ix' => $_GET['ix']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        $message = "상품을 찾을 수 없습니다.";
        $account = ['ix'=> '', 'user_ix'=> '', 'name'=> '', 'account_manager'=>'', 'manager_contact'=> '', 'contact'=> '', 'site'=> '','address'=>'', 'account_number'=>'', 'memo'=>''];
    }
}

// POST 요청 처리: 데이터 저장 또는 업데이트
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ix = $_POST['ix'] ?? '';
    $name = $_POST['accountName'];
    $account_manager = $_POST['accountManager'];
    $manager_contact = $_POST['managerContact'];
    $contact = $_POST['accountContact'];
    $address = $_POST['accountAddress'];
    $site = $_POST['accountSite'];
    $account_number = $_POST['accountBNumber'];
    $memo = $_POST['accountMemo'];

    if ($ix) {
        // 수정 처리
        $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'description' => $description]);
        $message = "상품이 수정되었습니다.";
    } else {
        // 생성 처리
        $stmt = $pdo->prepare("INSERT INTO account (user_ix,name,account_manager,manager_contact,contact,site,address,account_number,memo) VALUES (:user_ix, :name, :account_manager, :manager_contact, :contact, :site, :address, :account_number, :memo)");
        $stmt->execute([
            'user_ix' => '1', 
            'name' => $name, 
            'account_manager' => $account_manager,
            'manager_contact' => $manager_contact,
            'contact' => $contact,
            'site' => $site,
            'address' => $address,
            'account_number' => $account_number,
            'memo' => $memo
        ]);
        if($stmt){
            $message = "거래처가 등록 되었습니다.";
        }else{
            $message = "거래처 등록이 실패했습니다.";
        }
        
    }

    // // 초기화 또는 리다이렉트
    // header('Location: ' . $_SERVER['PHP_SELF']);
    // exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공통 레이아웃</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/account.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/common.js"></script>
</head>
<body>
    <?php 
    include './sidebar.html';
    include './header.php';
    ?>

    <!-- 헤더 -->

    <?php

    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        if($ix) {
            $location = './account-manage.php?ix='+$ix;
        }else{
            $location = './account-manage.php?';
        }
    ?>
       <script>
        alert("<?=$message?>");
        location.href= "<?=$location?>";
        </script>
    
    <?php }
    ?>



    <div class="full-content">
        <div class="main-content">
            <div class="account-info-section">
                <div class="row mb-4">
                    <h2 class="col-md-10"><?= $account['ix'] ? '거래처 수정' : '거래처 생성' ?></h2>
                </div>
                <div class="account-info">
                    <form id="accountForm" action="" method="post">
                        <?php if ($account['ix']): ?>
                            <input type="hidden" name="ix" value="<?= htmlspecialchars($account['ix']) ?>" />
                        <?php endif; ?>
                        <div class="account-fields">
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">거래처명</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="accountName" placeholder="거래처 or 소싱 업체" value="<?= htmlspecialchars($account['name']) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">담당자</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="accountManager" placeholder="담당자 이름을 입력하세요." value="<?= htmlspecialchars($account['account_manager']) ?>">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">담당자 연락처</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="managerContact"  placeholder="담당자 직통 연락처를 입력하세요." value="<?= htmlspecialchars($account['manager_contact']) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">대표번호</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="accountContact" placeholder="거래처 대표번호를 입력하세요." value="<?= htmlspecialchars($account['contact']) ?>">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">주소</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="accountAddress" placeholder="주소를 입력하세요." value="<?= htmlspecialchars($account['address']) ?>">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">사이트</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="accountSite" placeholder="http://aaa.com" value="<?= htmlspecialchars($account['site']) ?>">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">사업자번호</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="accountBNumber" placeholder="111-55-44444" value="<?= htmlspecialchars($account['account_number']) ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">메모</label>
                                <textarea class="form-control" name="accountMemo" rows="5" value="<?= htmlspecialchars($account['memo']) ?>"></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><?= $account['ix'] ? '수정' : '등록' ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
