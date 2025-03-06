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
    <title>계산기</title>
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
            <ul class="nav nav-pills text-center" style="margin:20px;">
                <li class="nav-item col-sm-6">
                    <a class="nav-link active" aria-current="page" href="#">마진율 계산기</a>
                </li>
                <li class="nav-item col-sm-6">
                    <a class="nav-link" href="#">광고비 계산기</a>
                </li>
            </ul>
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
                    <div class="d-flex">
                        <div class="flex-grow-1 justify-content-between">
                            <span>네이버</span>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="charge-label" for="">원가</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                                <div class="col-sm-1">
                                    <label class="charge-label" for="">수량</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label class="charge-label" for="">판매가</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                                <div class="col-sm-1">
                                    <label class="charge-label" for="">마진율</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label class="charge-label" for="">순수익</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label class="charge-label" for="">수수료(%)</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label class="charge-label" for="">배송수수료(%)</label>
                                    <input type="text" class="form-control" name="basicFee" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
