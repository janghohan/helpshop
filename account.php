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
    include './header.php';
    include './sidebar.html';

    include './dbConnect.php';

    // 검색 처리
    // DB 연결 오류 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 검색 처리
    $searchResult = [];
    if (isset($_GET['searchKey']) && !empty($_GET['searchKey'])) {
        $searchTerm = $conn->real_escape_string($_GET['searchKey']); // 사용자 입력값
        $query = "SELECT * FROM account WHERE name LIKE '%$searchTerm%' AND user_ix='$user_ix'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $searchResult[] = $row;
            }
        }
    }else{
        $query = "SELECT * FROM account WHERE user_ix='1'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $searchResult[] = $row;
            }
        }
    }

    
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
                <a href="./account.php" class="btn btn-secondary col-2 ms-1">전체보기</a>
            </div>
        </div>
    
        <!-- 메인 콘텐츠 -->
        <div class="main-content">
            <div class="d-flex mb-3">
                <h2 class="flex-grow-1">거래처 관리</h2>
                <button class="btn flex-shrink-0">
                    <a href="./account-manage.php">
                        <!-- <span>+ NEW</span> -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                    </a>
                </button>
            </div>
            <div class="account-list">
                <!-- 상품 아이템 -->

                <table class="table">
                    <thead>
                        <th>이름</th>
                        <th>대표번호</th>
                        <th>홈페이지</th>
                        <th>주소</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                            foreach($searchResult as $accountRow) {
                        ?>
                        <tr>
                            <td><?=htmlspecialchars($accountRow['name'])?></td>
                            <td><?=htmlspecialchars($accountRow['contact'])?></td>
                            <td>
                                <a href="<?=htmlspecialchars($accountRow['site'])?>" target="_blank">바로가기</a>
                            </td>
                            <td><?=htmlspecialchars($accountRow['address'])?></td>
                            <td>
                                <textarea style="display:none;" class="memo" data-ix="<?=htmlspecialchars($accountRow['ix'])?>">
                                    <?=htmlspecialchars($accountRow['memo'])?>
                                </textarea>
                                <button class="btn btn-primary btn-memo"  data-bs-toggle="modal" data-bs-target="#accountModal">메모</button>
                                <button class="btn btn-secondary">
                                    <a href="./account-manage.php?ix=<?=htmlspecialchars($accountRow['ix'])?>" class="text-white">수정</a>
                                </button>
                            </td>
                        </tr>
                        <?php } 
                        if(count($searchResult)===0){
                        ?>       
                        <tr>
                            <td>
                                <br><br>
                                <p>검색 결과가 없습니다.</p>    
                            </td>
                        </tr> 
                        <?php }?>


                    </tbody>
                </table>
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
                        <textarea name="modalMemo" id="modalMemo" class="form-control" rows="5"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary memoEditBtn" >수정</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var dataIx;
        $(".btn-memo").click(function(){
            dataIx = $(this).parent().find(".memo").attr('data-ix');
            $("#modalMemo").val($(this).parent().find(".memo").text().trim());
        });

        $("#search-btn").click(function(){
            keyword = $("#search-input").val();
            if (keyword.trim() !== '') {
                // 현재 페이지로 GET 요청 전달
               location.href = `./account.php?searchKey=${encodeURIComponent(keyword)}`;
            } else {
                alert('검색어를 입력하세요.');
            }
        });

        $(".memoEditBtn").click(function(){
            console.log(dataIx);
            console.log($("#modalMemo").val() );
            $.ajax({
                url: './api/account_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'accountIx':dataIx, 'memo':$("#modalMemo").val() },
                success: function(response) { 
                    console.log(response);
                    if(response.status=='success'){
                        console.log('suc');
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        });


    </script>
</body>
<script>
   
</script>
</html>
