<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>내정보 페이지</title>
    <style>
        /* 기본 스타일 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f5f7;
        }


        /* 메인 컨테이너 */
        .container {
            display: flex;
            padding: 20px;
        }

        /* 사이드바 */
        .sidebar {
            width: 220px;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-right: 15px;
        }
        .sidebar h2 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 8px;
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }
        .sidebar ul li:hover,
        .sidebar ul li.active {
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        /* 메인 콘텐츠 */
        .main-content {
            flex: 1;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .main-content h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .info-section h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-item label {
            color: #666;
        }
        .info-item button {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }


        /* 내마켓 */
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding-top: 25px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            flex: 1;
            max-width: 850px;
        }
        .product-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .product-info p {
            font-size: 14px;
            color: #666;
        }
        .product-controls {
            display: flex;
            align-items: center;
        }


        .basic-btn{
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .charge-label{
            display: inline-block;
            margin: 10px 0;
        }
        
    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php include './header.php'?>

    <!-- 메인 컨테이너 -->
    <div class="container">
        <!-- 사이드바 -->
        <div class="sidebar">
            <h2>내정보</h2>
            <ul>
                <li>기본정보</li>
                <li class="active">마켓등록</li>
                <li>결제내역</li>
            </ul>
        </div>

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
                <div class="product-item row">
                    <div class="product-info col-md-11">
                        <h3>네이버 스마트스토어</h3>
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="charge-label" for="">기본 수수료(%)</label>
                                <input type="text" class="field-input" value="2.6">
                            </div>
                            <div class="col-lg-3">
                                <label class="charge-label" for="">연동 수수료(%)</label>
                                <input type="text" class="field-input" value="2">
                            </div>
                            <div class="col-lg-3">
                                <label class="charge-label" for="">배송 수수료(%)</label>
                                <input type="text" class="field-input" value="3.3">
                            </div>
                        </div>
                    </div>
                    <div class="product-controls col-md-1">
                        <button class="btn btn-secondary market-edit">수정</button>
                    </div>
                </div>
                <div class="product-item row">
                    <div class="product-info col-md-11">
                        <h3>쿠팡</h3>
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="charge-label" for="">기본 수수료(%)</label>
                                <input type="text" class="field-input" value="10.8">
                            </div>
                            <div class="col-lg-3">
                                <label class="charge-label" for="">연동 수수료(%)</label>
                                <input type="text" class="field-input" value="0">
                            </div>
                            <div class="col-lg-3">
                                <label class="charge-label" for="">배송 수수료(%)</label>
                                <input type="text" class="field-input" value="3.3">
                            </div>
                        </div>
                    </div>
                    <div class="product-controls col-md-1">
                        <button class="btn btn-secondary market-edit">수정</button>
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
                        <input type="text" name="marketName" class="form-control col-3" placeholder="ex) 스마트스토어">
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

    $(".")
    
</script>
</html>
