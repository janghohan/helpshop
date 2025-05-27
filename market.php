<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/market.css" data-n-g="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src='./js/common.js' ></script>
    <title>내정보 페이지</title>
    <style>
        .card{
            position: static;
        }
    </style>
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
        <div class="main-content">
            <h4 class="mb-3 fw-bold">마켓 등록</h4>
            <button class="btn btn-primary mb-4" type="button" data-bs-toggle="modal" data-bs-target="#martketModal">+ 새로운 마켓 등록</button>
        </div>
        <div class="container">
            <!-- 메인 콘텐츠 -->
            <div class="card p-4">
                <h5 class="fw-bold mb-4">내 마켓</h5>

                <?php
                    $searchResult = [];
                    
                    $query = "SELECT * FROM market WHERE user_ix='$userIx'";
                    $result = $conn->query($query);
            
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $searchResult[] = $row;
                        }
                        

                    foreach($searchResult as $marketRow){
                        if($marketRow['market_name']=='네이버'){
                            $img = "naver.png";
                        }else if($marketRow['market_name']=='쿠팡'){
                            $img = "coupang.png";
                        }else if($marketRow['market_name']=='쿠팡 로켓그로스'){
                            $img = "rocket.png";
                        }
                    ?>
                <!-- 마켓 반복 -->
                <div class="mb-4 border rounded p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex justify-start gap-1">
                            <input type="hidden" name="market_ix" value="<?=htmlspecialchars($marketRow['ix'])?>">
                            <img src="./img/icon/<?=htmlspecialchars($img)?>" alt="" style="width:20px;">
                            <h6 class="mb-0 fw-semibold"><?=htmlspecialchars($marketRow['market_name'])?></h6>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-2 market-edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger market-del">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                        <label class="form-label">기본 수수료(%)</label>
                        <input type="number" class="form-control" name="basicFee" value="<?=htmlspecialchars($marketRow['basic_fee'])?>" />
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">연동 수수료(%)</label>
                        <input type="number" class="form-control" name="linkedFee" value="<?=htmlspecialchars($marketRow['linked_fee'])?>" />
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">배송 수수료(%)</label>
                        <input type="number" class="form-control" name="shipFee" value="<?=htmlspecialchars($marketRow['ship_fee'])?>"/>
                        </div>
                    </div>
                </div>

                <?php } }else{?>
                    <span>* 마켓을 등록해주세요.</span>  
                <?php } ?>

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
                                <option value="쿠팡 로켓그로스">쿠팡 로켓그로스</option>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function newMarketCreate(){
        $.ajax({
            url: "./api/market_api.php",
            type: "post",
            dataType: "json",
            data: $("#newMarketForm").serialize(),
            success: function(data){
                if(data['status']=='suc'){					
                    location.reload();		
                }else if(data['status']=='fail')  {
                    console.log(data['msg']);
                }
                $('#newMarketForm')[0].reset();
            },
            error: function (request, status, error){
                alert('정보 신청에 실패하셨습니다.');    

            }                     
        });
    }

    $(".market-edit").click(function(){
        const parent = $(this).parent().parent().parent();
        const inputs = parent.find("input");
        const formData = new FormData();
        formData.append('type', 'edit');
        inputs.each(function() {
            // console.log($(this).attr('name'), $(this).val());
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
        const deleteIx = $(this).parent().parent().find("input").val();

        console.log(deleteIx);
        swalConfirm("삭제하시겠습니까?",delete(deleteIx));

        swalConfirm('삭제 하시겠습니까?',function() {
            const formData = new FormData();
            formData.append('type', 'delete');
            formData.append('deleteIx', deleteIx);
            $.ajax({
                url: './api/market_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: formData,
                processData: false, // FormData 객체를 문자열로 변환하지 않음
                contentType: false, // 기본 Content-Type 설정을 막음
                success: function(response) {
                    const result = JSON.parse(response);
                    if(result.status=='suc'){
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert('전송 실패: ' + error);
                }
            });
        }, function(){});
    });

    
</script>
</html>
