<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>내정보 페이지</title>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    ?>


    <!-- 메인 컨테이너 -->
    <div class="full-content">
        <div class="container">
            <!-- 메인 콘텐츠 -->
            <div class="main-content">
                <h2>마켓 등록</h2>

                <!-- 기본정보 섹션 -->
                <div class="info-section">
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#martketModal">+ 새로운 마켓 등록</button>
                </div>

                <!-- 마케팅 정보 수신 동의 섹션 -->
                <h3>내 마켓</h3>
                <div class="product-list">
                    <!-- 상품 아이템 -->

                    <?php
                    $searchResult = [];
                    
                    $query = "SELECT * FROM market WHERE user_ix='$user_ix'";
                    $result = $conn->query($query);
            
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $searchResult[] = $row;
                        }
                    }

                    foreach($searchResult as $marketRow){
                    ?>
                    <div class="d-flex">
                        <div class="flex-grow-1 justify-content-between">
                            <h5><?=htmlspecialchars($marketRow['market_name'])?></h5>
                            <div class="row">
                                <div class="col-lg-3">
                                    <label class="charge-label" for="">기본 수수료(%)</label>
                                    <input type="text" class="field-input" name="basicFee" value="<?=htmlspecialchars($marketRow['basic_fee'])?>">
                                </div>
                                <div class="col-lg-3">
                                    <label class="charge-label" for="">연동 수수료(%)</label>
                                    <input type="text" class="field-input" name="linkedFee" value="<?=htmlspecialchars($marketRow['linked_fee'])?>">
                                </div>
                                <div class="col-lg-3">
                                    <label class="charge-label" for="">배송 수수료(%)</label>
                                    <input type="text" class="field-input" name="shipFee" value="<?=htmlspecialchars($marketRow['ship_fee'])?>">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-shrink-0">
                            <input type="hidden" name="market_ix" value="<?=htmlspecialchars($marketRow['ix'])?>">
                            <button class="btn btn-light market-edit me-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                </svg>
                            </button>
                            <button class="btn btn-light market-del">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php }?>
                    
                </div>
            </div>
            <div class="modal fade" id="martketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">마켓 등록</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="newMarketForm" method="post">
                            <input type="hidden" name="type" value="create">
                            <p class="mt-4 mb-1 mb-n2">마켓명을 입력하세요.</p>
                            <select name="marketName" id="marketName" class="form-control">
                                <option value="네이버">네이버</option>
                                <option value="쿠팡">쿠팡</option>
                            </select>
                            <p class="mt-4 mb-1 mb-n2">수수료 입력(숫자만 입력하세요)</p>
                            <div class="row">
                                <div class="col-4">
                                    <input type="text" name="basicFee" class="form-control" placeholder="기본 수수료(%)">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="linkedFee" class="form-control" placeholder="연동 수수료(%)">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="shipFee" class="form-control" placeholder="배송 수수료(%)">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="newMarketCreate()">등록</button>
                    </div>
                    </div>
                </div>
            </div>
            <div id="myToast" class="toast text-bg-primary position-fixed top-50 start-50 translate-middle border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="500">
                <div class="d-flex">
                    <div class="toast-body">
                    수정 되었습니다.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function newMarketCreate(){
        $.ajax({
            url: "./api/market_api.php",
            type: "post",
            dataType: "json",
            data: $("#newMarketForm").serialize(),
            success: function(data){
                if(data['msg']=='suc'){					
                    location.reload();		
                }else if(data['msg']=='exist')  {
                    alert('error');
                }
                $('#newMarketForm')[0].reset();
            },
            error: function (request, status, error){
                alert('정보 신청에 실패하셨습니다.');    

            }                     
        });
    }

    $(".market-edit").click(function(){
        const parent = $(this).parent().parent();
        const inputs = parent.find("input");

        const formData = new FormData();
        formData.append('type', 'edit');
        inputs.each(function() {
            console.log($(this).attr('name'), $(this).val());
            formData.append($(this).attr('name'), $(this).val());
        });

        $.ajax({
            url: './api/market_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            data: formData,
            processData: false, // FormData 객체를 문자열로 변환하지 않음
            contentType: false, // 기본 Content-Type 설정을 막음
            success: function(response) {
                const result = JSON.parse(response);
                if(result.status=='suc'){
                    toast();
                }
            },
            error: function(xhr, status, error) {
                alert('전송 실패: ' + error);
            }
        });

    });

    function toast(){
        const toastElement = document.getElementById('myToast');
        const toast = new bootstrap.Toast(toastElement);

        // 토스트 표시
        toast.show();
    }

    $(".market-del").click(function(){
        if(confirm("삭제하시겠습니까?")){
            console.log($(this).closest("input").val());
        }
    });
    
</script>
</html>
