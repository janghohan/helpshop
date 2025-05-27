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
    <style>
        /* 모든 브라우저에서 number input의 화살표(스피너) 활성화 */
        input[type="number"].form-control {
            -moz-appearance: textfield;
        }
        input[type="number"].form-control::-webkit-inner-spin-button,
        input[type="number"].form-control::-webkit-outer-spin-button {
            -webkit-appearance: auto;
        }
        .grey{
            background:#efefef;
        }
        .marketBtn.selected{
            background: #2f3b7e;
            color: #fff;
        }
        .nav-link{
            color: #000;
            border: 1px solid #e9ecef !important;
        }

        .nav-link.active{
            color: #fff;
            background-color: #2f3b7e !important;
        }
        .explain{
            margin-left: 20px;
            font-size: 14px;
            display: grid;
            color: #2C3E50;
            font-weight: 600;
            
        }
        
        .tooltip-icon {
            background-color: #fff;
            border: 1px solid #acacac;
            color: #acacac;
            border-radius: 50%;
            padding: 0px 7px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            display: none;
        }

        .tooltip-text {
            display: none;
            width: 220px;
            background-color: #333;
            color: #fff;
            text-align: left;
            padding: 8px;
            border-radius: 5px;

            position: absolute;
            z-index: 1;
            bottom: 125%; /* 위치 조정 */
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
            transition: opacity 0.3s;
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
        <div class="container">
			<div id="dashboard">
				<img src="./img/main1.jpg" alt="">
			</div>
        </div>
    </div>
</body>
</html>
