<?php
$pdo = new PDO('mysql:host=localhost;dbname=helpshop;charset=utf8', 'root', '');



// 검색 처리
$searchResult = [];
if (isset($_GET['searchKey']) && !empty($_GET['searchKey'])) {

    $stmt = "SELECT * FROM account WHERE name LIKE '%:searchKeyword%'";
    $stmt->execute(['searchKeyword' => $_GET['searchKey']]);

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResult[] = $row;
        }
    }


    $stmt = $pdo->prepare("SELECT * FROM account WHERE ix = :ix");
    $stmt->execute(['ix' => $_GET['ix']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        $message = "상품을 찾을 수 없습니다.";
        $account = ['ix'=> '', 'user_ix'=> '', 'name'=> '', 'account_manager'=>'', 'manager_contact'=> '', 'contact'=> '', 'site'=> '','address'=>'', 'account_number'=>'', 'memo'=>''];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공통 레이아웃</title>
    <link rel="stylesheet" href="./css/account.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/common.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./js/common.js"></script>
</head>
<body>
    <?php 
    include './sidebar.html';
    include './header.php';
    
    $pdo = new PDO('mysql:host=localhost;dbname=helpshop;charset=utf8', 'root', '');

    ?>
    
    <!-- 헤더 -->
    <!-- <div class="header">
        <div class="header-left">
            <img src="logo.png" alt="로고" class="logo">
        </div>
        <div class="header-right">
            <input type="text" placeholder="검색" class="search-bar">
            <button class="ai-chatbot">카페24 AI 챗봇</button>
        </div>
    </div> -->

    <div class="full-content">
        <div class="main-content">
            <h2>거래처 조회</h2>
            <div class="row">
                <div class="col-5">
                    <input type="text" class="col-5 form-control" id="search-input">
                </div>
                <button class="btn btn-primary col-2" id="search-btn">조회</button>
            </div>
        </div>
    
        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <div class="d-flex mb-3">
                <h2 class="flex-grow-1">거래처 관리</h2>
                <button class="btn flex-shrink-0">
                    <a href="./account-manage.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                    </a>
                </button>
            </div>
            <div class="account-list">
                <!-- 상품 아이템 -->

                <?php
                    $sql = "SELECT * FROM account WHERE user_ix = :ix";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':ix' => '1']);

                    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($accounts as $account) {
                ?>

                <div class="account-item">
                    <div class="account-info col-10">
                        <h3><?=$account['name']?></h3>
                        <table class="table">
                            <thead>
                                <th>대표번호</th>
                                <th>담당자</th>
                                <th>담당자 번호</th>
                                <th>홈페이지</th>
                                <th>주소</th>
                            </thead>
                            <tbody>
                                <td><?=$account['contact']?></td>
                                <td><?=$account['account_manager']?></td>
                                <td><?=$account['manager_contact']?></td>
                                <td>
                                    <a href="<?=$account['site']?>" target="_blank"><?=$account['site']?></a>
                                </td>
                                <td>
                                    <?=$account['address']?>
                                </td>
                            </tbody>
                        </table>
                    </div>
                    <div class="account-controls col-2">
                        <button class="btn btn-primary btn-memo"  data-bs-toggle="modal" data-bs-target="#accountModal">메모</button>
                        <button class="btn btn-secondary">
                            <a href="./account-manage.php?ix=<?=$account['ix']?>" class="text-white">수정</a>
                        </button>
                    </div>
                    <textarea name="memo" class="memo" style="visibility:hidden;">
                        <?=$account['memo']?>
                    </textarea>
                </div>
                <?php } ?>            
            </div>
        </div>
        <!-- 페이지네이션 -->
        <div class="pagination">
            <select>
                <option>10개 보기</option>
            </select>
            <span>1 / 1</span>
        </div>
        <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">메모</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="newMarketForm" method="post">
                        <textarea name="modalMemo" id="modalMemo" class="form-control" rows="5" readonly></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".btn-memo").click(function(){
            $("#modalMemo").val($(this).parent().parent().find(".memo").val().trim());
        });

        $("#search-btn").click(function(){
            location.href = ='./account.php?searchKeyword=';
        });
    </script>
</body>
<script>
   
</script>
</html>
