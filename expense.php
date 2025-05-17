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
    
    <title>지출 내역</title>
    <style>
        @media (min-width: 1400px) {
            .container{
                max-width: 98%;
                font-size: 14px;
            }
        }
        .search-options{
            padding: 20px;
            padding-left: 0;
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

    </style>
</head>
<body>
    <!-- 헤더 -->
    <?php 
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    
    $today = date("Y-m-d");

    $startDate = isset($_POST['start']) ? $_POST['start'] : date("Y-m-d");
    $endDate = isset($_POST['end']) ? $_POST['end'] : date("Y-m-d");

    $orderResult = [];
    $orderTypeSearchKeyworSql = "";
    $searchParams = [];

    //검색 영역에 값이 들어오면
    if(isset($_GET['searchType'])){
        $searchType = $_GET['searchType'];

        if ($searchType === '') {
            $orderTypeSearchKeyworSql = "";
        } else {
            $orderTypeSearchKeyworSql = "AND ep.expense_type = '$searchType'";
        }
    }

    if (isset($_GET['start']) && isset($_GET['end'])) {
        $startDate = $_GET['start'];
        $endDate = $_GET['end'];
        
        $expenseQuery = "
            SELECT ep.ix as expense_ix, ep.expense_type, ep.expense_price, ep.expense_memo, ep.expense_date FROM expense ep JOIN user u ON ep.user_ix = u.ix WHERE u.ix = ? AND ep.expense_date >= ? AND ep.expense_date <= ?
            $orderTypeSearchKeyworSql";
        $expenseStmt = $conn->prepare($expenseQuery);
        $expenseStmt->bind_param("sss",$userIx, $startDate,$endDate);
    
        // Execute and Fetch Results
        $expenseStmt->execute();
        $result = $expenseStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $expenseResult[] = $row;
            }
        }
    }else{

        $expenseQuery = "
            SELECT * FROM expense ep JOIN user u ON ep.user_ix = u.ix WHERE u.ix = ? AND ep.expense_date = ?
            $orderTypeSearchKeyworSql";
        $expenseStmt = $conn->prepare($expenseQuery);
        $expenseStmt->bind_param("ss",$userIx, $today);
    
        // Execute and Fetch Results
        $expenseStmt->execute();
        $result = $expenseStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $expenseResult[] = $row;
            }
        }
    }
    ?>
    <!-- 헤더 -->

  

    <div class="full-content">

        <!-- 검색 컨테이너 -->
        <div class="container">
            <!-- 사이드바 -->
            <div class="main-content">
                <h2>지출 조회</h2>
                <div class="container mt-4">
                    <!-- Search Options -->
                    <div class="search-options">
                        <div class="d-flex justify-content-start gap-3">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="flatpickr" placeholder="MM/DD/YYYY" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="expense-filter">
                                    <option value="">전체</option>
                                    <option value="광고비">광고비</option>
                                    <option value="자재비">자재비</option>
                                    <option value="택배비">택배비</option>
                                    <option value="매입비용">매입비용</option>
                                    <option value="기타">기타</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="search-btn">조회하기</button>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Filter Options and Table -->
                    <div class="filter-options mb-1 pb-2">
                        <div class="text-end">
                            <button class="btn btn-secondary" id="expenseOpenBtn">지출 내역 등록</button>
                        </div>
                    </div>

                    <!-- Order Table -->
                    <div class="table-container" style="caret-color: transparent;">
                        <table class="table">
                            <colgroup>
                                <col style="width: 30%;">
                                <col style="width: 20%;">
                                <col style="width: 30%;">
                                <col style="width: 20%;">
                                <col style="width: 10%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>지출타입</th>
                                    <th>금액</th>
                                    <th>메모</th>
                                    <th>날짜</th>
                                    <th class="text-center">
                                        -
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="expense-list">
                            <!-- Order rows will be added dynamically -->
                                <?php
                                    $previousDate = null; // 이전 주문번호를 저장
                                    $toggle = true; // 색상을 변경하기 위한 토글 변수

                                    if(isset($expenseResult)){
                                        foreach($expenseResult as $expenseRow) {

                                            $currentDate = $expenseRow['expense_date'];
                                            if ($currentDate !== $previousDate) {
                                                // 주문번호가 변경될 때마다 토글 값을 변경
                                                $toggle = !$toggle;
                                            }
                                            $backgroundColor = $toggle ? '#f0f0f0' : '#ffffff'; // 흰색(#ffffff)과 회색(#f0f0f0)으로 구분
                                            $previousDate = $currentDate; // 현재 주문번호를 이전 주문번호로 갱신


                                    ?>        
                                    <tr style="background-color: <?= $backgroundColor ?>;">
                                        <td><?=htmlspecialchars($expenseRow['expense_type'])?></td>
                                        <td><?=htmlspecialchars(number_format($expenseRow['expense_price']))."원"?></td>
                                        <td><?=htmlspecialchars($expenseRow['expense_memo'])?></td>
                                        <td><?=htmlspecialchars($expenseRow['expense_date'])?></td>
                                        <td>
                                            <button class="btn btn-light expense-del" data-ix="<?=htmlspecialchars($expenseRow['expense_ix'])?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"></path>
                                                </svg>
                                            </button>
                                        </td>                           
                                    </tr>
                                    
                                <?php
                                    }}
                                ?>
                            </tbody>
                        </table>
                        <?php
                        if(!isset($expenseResult)){ ?>
                        <p class="text-center pt-3" id="no-data">지출 내역이 없습니다.</p>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">지출내역 등록</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./api/expense_api.php" id="expenseForm" method="post" enctype="multipart/form-data">
                        <input type="text" class="form-control mb-3" name="expenseDate" id="expenseFlatpickr" placeholder="MM/DD/YYYY">
                        <input type="hidden" name="type" value="create">
                        <select name="expenseType" class="form-control" id="">
                            <option value="광고비">광고비</option>
                            <option value="자재비">자재비</option>
                            <option value="택배비">택배비</option>
                            <option value="매입비용">매입비용</option>
                            <option value="기타">기타</option>
                        </select>
                        <input type="text" name="expenseMemo" class="form-control mt-3"  placeholder="메모">                       
                        <input type="text" class="localeNumber form-control mt-3" name="expensePrice" placeholder="금액">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="expensAddBtn()">등록</button>
                </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/ko.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/product.js"></script>
    <script src="./js/common.js"></script>
    
    
    <script>
        var startDate = "<?=$startDate?>";
        var endDate = "<?=$endDate?>";

        $(document).ready(function() {
            flatpickr("#flatpickr", {
                defaultDate: ["<?=$startDate?>","<?=$endDate?>"],
                dateFormat: "Y-m-d",
                mode: "range",
                allowInput: true,
                theme: "material_blue",
                locale: "ko",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        startDate = formatDateToYMD(selectedDates[0]);
                        endDate = selectedDates[1] ? formatDateToYMD(selectedDates[1]) : startDate;
                    }

                }   
                
            });

        });

        $(document).ready(function() {
            flatpickr("#expenseFlatpickr", {
                defaultDate: "today",
                dateFormat: "Y-m-d",
                allowInput: true,
                theme: "material_blue",
                locale: "ko"
                
            });

        });

       
        $("#search-btn").click(function(){
            searchExpenseList();
        });

        function searchExpenseList(){
            console.log("start",startDate);
            console.log("end",endDate);
            
            const searchType = $("#expense-filter option:selected").val();
            location.href = './expense.php?start='+startDate+"&end="+endDate+"&searchType="+searchType;
        }

        // 지출내역
        $("#expenseOpenBtn").click(function(){
            modalOpen("expenseModal");
        });

        //지출 등록
        function expensAddBtn(){
            $.ajax({
                url: './api/expense_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: $("#expenseForm").serialize(),
                success: function(response) { 
                    // console.log(response);
                    if(response.status=='success'){
                        Swal.fire({
                            html: `
                                <div style="font-size: 16px; text-align: left;">
                                    <strong>등록 완료. 계속 하시겠습니까? </strong><br><br>
                                    
                                </div>
                            `,
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-danger"
                            },
                            showCancelButton: true,
                            confirmButtonText: "예",
                            cancelButtonText: "아니오", 
                            reverseButtons: true,
                            allowOutsideClick:false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#expenseForm input").val("");
                            }else{
                                modalClose('expenseModal');
                            }
                        });
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

        $(".expense-del").click(function(){
            expenseIx = $(this).attr('data-ix');
            btn = $(this);
            swalConfirm("복구가 불가능합니다. 삭제하시겠습니까?", function(){
                expenseDelete(expenseIx,btn)
            },function(){});
        });

        function expenseDelete(expenseIx,btn){
            $.ajax({
                url: './api/expense_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'type':'delete', 'expenseIx':expenseIx },
                success: function(response) { 
                    console.log(response);
                    if(response.status=='success'){
                        $(btn).parent().parent().remove();
                        toast();
                    }

                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }

    </script>
</body>
</html>
