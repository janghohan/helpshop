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
        <div class="container mt-5">
            <h4 class="mb-3 fw-bold">마켓 등록</h4>
            <button class="btn btn-primary mb-4">+ 새로운 마켓 등록</button>

            <div class="card p-4">
                <h5 class="fw-bold mb-4">내 마켓</h5>

                <!-- 마켓 반복 -->
                <div class="mb-4 border rounded p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold">네이버</h6>
                        <div>
                        <button class="btn btn-sm btn-outline-secondary me-2">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                        <label class="form-label">기본 수수료(%)</label>
                        <input type="number" class="form-control" value="2.75" />
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">연동 수수료(%)</label>
                        <input type="number" class="form-control" value="2" />
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">배송 수수료(%)</label>
                        <input type="number" class="form-control" value="2.75" />
                        </div>
                    </div>
                </div>

                    <!-- 다른 마켓들 동일 구조로 반복 -->

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
