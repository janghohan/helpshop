<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/product.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <title>대량 상품 등록</title>
    <style>
        body {
            background-color: #f9f9f9;
        }
        @media (min-width: 1400px) {
            .container{
                max-width: 98%;
                font-size: 14px;
            }
        }
        .search-options, .summary-cards, .filter-options {
            padding: 20px;
            margin-bottom: 20px;
        }
        .summary-cards .card {
            border: none;
            background: #f9f9f9;
            border-radius: 10px;
            text-align: center;
            font-size: 0.9rem;
            width: 30%;
        }
        .summary-cards .card h5 {
            margin: 10px 0;
        }
        .summary-cards .card .value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .table-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ui-datepicker {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-family: 'Arial', sans-serif;
        }
        .ui-datepicker-header {
            background: #007bff;
            color: white;
        }
        .ui-datepicker-calendar .ui-state-hover {
            background: #28a745;
            color: white;
        }

        .table-container{
            max-height: 620px;
            overflow-y: auto;
        }

    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';

    require_once __DIR__ . '/api/SimpleXLSX.php'; // 실제 경로 확인
    require_once __DIR__ . '/api/SimpleXLSXGen.php'; // 실제 경로 확인

    use Shuchkin\SimpleXLSX; // 네임스페이스가 있는 경우 사용할 수 있음
    use Shuchkin\SimpleXLSXGen; // 네임스페이스가 있는 경우 사용할 수 있음
    
    $userIx = isset($_SESSION['user_ix']) ? : '1';
    $today = date("Y-m-d");

    $infoText = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : date("Y-m-d");
        $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : date("Y-m-d");
       
        $listStmt = $conn->prepare("SELECT c.name as categoryName, a.name as accountName, mn.matching_name,mn.cost,mn.stock,mn.alarm_stock FROM matching_name mn JOIN account a ON mn.account_ix = a.ix JOIN category c ON c.ix = mn.category_ix WHERE mn.user_ix=? AND mn.created_at>=? AND mn.created_at<=?");
        if (!$listStmt) {
            throw new Exception("Error preparing list statement: " . $conn->error); // *** 수정 ***
        }
        $listStmt->bind_param("sss",$userIx,$startTime,$endTime);
        if (!$listStmt->execute()) {
            throw new Exception("Error executing list statement: " . $listStmt->error); // *** 수정 ***
        }

        $result = $listStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listResult[] = $row;
            }
        }else{
            $infoText = "중복된 데이터는 제외하고 새로 등록상품만 보여집니다.";
        }
    }else{
        $infoText = "";
    }

    
    ?>
    <!-- 헤더 -->

  

    <div class="full-content">

        <div class="main-content">
            <div class="container mt-4">
                <!-- Search Options -->
                <div class="search-options d-flex justify-content-between">
                    <h2>대량 상품 등록</h2>
                    <div class="row justify-content-end ">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary w-100">
                                <a href="./제품등록양식.xlsx" class="text-white">엑셀 양식 받기</a>
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary w-100" id="excel-btn">상품 엑셀 등록</button>
                        </div>
                    </div>

                </div>

                <!-- Order Table -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>거래처</th>
                            <th>카테고리</th>
                            <th>제품이름</th>
                            <th>원가</th>
                            <th>재고수량</th>
                            <th>알림재고</th>
                            <!-- <th></th> -->
                        </tr>
                        </thead>
                        <tbody id="product-list">
                        <!-- Order rows will be added dynamically -->
                            <?php
                                $previousProductName = null; // 이전 주문번호를 저장
                                $toggle = true; // 색상을 변경하기 위한 토글 변수

                                if(isset($listResult)){
                                    foreach ($listResult as $index => $row) {

                                    


                                ?>        
                                <tr style="background-color: <?= $backgroundColor ?>;">
                                    <td><?=htmlspecialchars($row['accountName'])?></td>
                                    <td><?=htmlspecialchars($row['categoryName'])?></td>
                                    <td><?=htmlspecialchars($row['matching_name'])?></td>
                                    <td><?=htmlspecialchars(number_format($row['cost']))."원"?></td>
                                    <td><?=htmlspecialchars(number_format($row['stock']))?></td>
                                    <td><?=htmlspecialchars(number_format($row['alarm_stock']))?></td>
                                    <!-- <td>
                                        <button class="btn btn-light option-edit me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"></path>
                                            </svg>
                                        </button>
                                        <button class="btn btn-light product-del">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                                            </svg>
                                        </button>
                                    </td> -->
                                </tr>
                                
                            <?php
                                }}
                            ?>
                        </tbody>
                    </table>
                    <p class="text-center pt-3"><?=htmlspecialchars($infoText)?></p>
                </div>
            </div>
        </div>
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">파일 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                </div>
                <div class="modal-body">
                    <form action="./api/product_api.php" id="productExcelForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="addCount" value="100">
                        <input type="file" name="productExcelFile" class="form-control mt-3"  accept=".xlsx, .xls">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="dumpExcel()">등록</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title fs-5" id="loadingText">파일이 등록중입니다. 창을 닫지 마세요.</p>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="display:none;">닫기</button>
                </div>
                </div>
            </div>
        </div>

        <form action="./product-add-dump.php" id="listForm" method="POST" style="display:none;">
            <input type="hidden" name="startTime">
            <input type="hidden" name="endTime">
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="./js/common.js"></script>
    
    
    <script>



        function dumpExcel(){
            // $("#productExcelForm").submit();
            modalClose("productModal");
            modalOpen("loadingModal");

            var formData = new FormData($("#productExcelForm")[0]); 
            $.ajax({
                url: './api/product_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) { 
                    console.log(response);
                    if(response.status=="success"){
                        listUp(response.startTime,response.endTime);
                        $("#loadingText").text("파일등록이 완료되었습니다.");
                        // $("#loadingModal .modal-body").css("display","none");
                        $("#loadingModal .btn").css("display","block");
                    }else(
                        console.log(response.message)
                    )

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

        function listUp(startTime,endTime){
            $("input[name='startTime']").val(startTime);
            $("input[name='endTime']").val(endTime);
            $("#listForm").submit();
        }

        $("#excel-btn").click(function(){
            modalOpen("productModal");
        });

       
        $("#search-btn").click(function(){
            searchOrderList();
        });

        function changeRageText(dateRange){
			const dates = dateRange.split(' ~ ');
			startDate = dates[0];
			endDate = dates[1];

		}
          
        function searchOrderList(){
            console.log("start",startDate);
            console.log("end",endDate);
            
            if($("#order-search").val()==''){
                location.href = './order.php?start='+startDate+"&end="+endDate;
            }else{
                const searchType = $("#order-filter option:selected").val();
                location.href = './order.php?start='+startDate+"&end="+endDate+"&searchType="+searchType+"&searchKeyword="+$("#order-search").val();
            }
        }

       
    </script>
</body>
</html>
