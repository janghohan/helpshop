<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리 시스템</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/excel-order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
       

        .syncBtn {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #2f3b7e;
            color: white;
            padding: 10px 20px;
        }
        .syncBtn .sync-button {
            padding: 10px 20px;
            background: #7cc576;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .syncBtn .sync-button:hover {
            background: #003f82;
        }

        .data-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-y: auto;
        }
        .data-row {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }
        .source {
            width: 10%;
            color: #555;
            font-weight: bold;
        }
        .input-container {
            width: 40%;
            padding: 0 10px;
            position: relative;
        }
        .input-container input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .checkbox-container {
            width: 10%;
            display: flex;
            justify-content: center;
        }
        .checkbox-container input {
            transform: scale(1.5);
        }


        /* 검색화면 보이기 */
        .autocomplete-results {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            width: calc(100% - 20px);
            background-color: white;
            top: 100%;  
            left: 10px;
            z-index: 100;
        }

        .result-item {
            padding: 8px;
            cursor: pointer;
        }

        .result-item:hover {
            background-color: #f1f1f1;
        }

    </style>
</head>
<body>
    <?php
    include './header.php';
    include './sidebar.html';
    include './dbConnect.php';
    
    $userIx = isset($_SESSION['user_ix']) ? : '1';

    $listStmt = $conn->prepare("SELECT o.order_date, o.global_order_number, m.market_name, od.ix as detailIx, od.name, od.quantity, od.price, o.total_payment, o.total_shipping,
            od.cost, m.basic_fee, m.linked_fee, m.ship_fee
            FROM orders o
            JOIN order_details od ON o.ix = od.orders_ix
            JOIN market m ON m.ix = o.market_ix LEFT JOIN db_match dm ON od.name = dm.name_of_excel
            WHERE o.user_ix = ? AND od.status='completed' AND dm.name_of_excel IS NULL GROUP BY od.name");

    $listStmt->bind_param("s",$userIx);

    // Execute and Fetch Results
    $listStmt->execute();
    $result = $listStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listResult[] = $row;
        }
    }


    ?>
    <div class="full-content">
        <div class="container">
            <div class="main-content">
                <div class="syncBtn">
                <button class="sync-button" onclick="synchronizeChk();">동기화</button>
            </div>
            <div class="data-list">
                <!-- Example Row -->
                <div class="data-row" style="font-weight:bold;">
                    <div class="source">판매처</div>
                    <div class="input-container">상품명</div>
                    <div class="input-container">
                        매칭 상품
                    </div>
                    <div class="checkbox-container">
                        <input type="checkbox" class="allCheckbox">
                    </div>
                </div>
                <form action="" method="post" id="matchingList">
                <?php
                foreach($listResult as $listRow) {

                ?>  
                    <div class="data-row">
                        <div class="source"><?=htmlspecialchars($listRow['market_name'])?></div>
                        <div class="input-container"><?=htmlspecialchars($listRow['name'])?></div>
                        <input type="hidden" name="matchingData[]" value="<?=htmlspecialchars($listRow['name'])?>">
                        <div class="input-container">
                            <input type="text" placeholder="값 입력" class="matchingText">
                            <input type="hidden" name="matchingValue[]" class="matchingValue" >
                            <div class="autocomplete-results"></div>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" class="matchingCheckbox" name="matchingCheckbox[]">
                        </div>
                    </div>
                
                <?php } ?>
                </form>
                <!-- Example Row -->
                
                <!-- More rows dynamically loaded here -->
            </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/common.js"></script>

    <script>

        //매칭할 상품 찾기 
        $(document).on('input', '.matchingText', function() {
            var query = $(this).val();
            var thisInput = $(this);
            if (query.length >= 2) {  // 두 글자 이상 입력 시 자동완성 시작
                $.ajax({
                    url: './api/search_api.php',  // 이 부분은 서버에서 검색 결과를 받을 PHP 파일 경로로 수정
                    method: 'POST',
                    dataType : 'json',
                    data: { searchKeyword: query, searchType :'matching' },
                    success: function(response) {
                        console.log(response);
                        // $(".autocomplete-results").show();
                        var resultsContainer = thisInput.siblings('.autocomplete-results');
                        resultsContainer.empty();  // 기존 결과 초기화

                        
                        if (response.length > 0) {
                            resultsContainer.show();
                            response.forEach(item => {
                                resultsContainer.append('<div class="result-item" data-v='+item.combIx+'>' + item.name+' / '+ item.combination_key +'</div>');
                            });
                        } else {
                            resultsContainer.hide();
                        }
                    }
                });
            } else {
                $('#autocomplete-results').hide();  // 입력이 두 글자 미만이면 결과 숨김
            }
        });


        // 검색된 항목 클릭 시 검색어로 채우기
        $(document).on('click', '.result-item', function() {
            var selectedValue = $(this).text();
            $(this).parent().parent().find(".matchingText").val(selectedValue);
            $(this).parent().parent().find(".matchingValue").val($(this).attr('data-v'));
            $(this).parent().hide();
        });

        $(".allCheckbox").click(function(){
            $(".matchingCheckbox").trigger('click');
        });


        function synchronizeChk(){

            let isCheck = false;
            $(".matchingCheckbox").each(function () {
                console.log($(this).prop('checked'));
                if($(this).prop('checked')==true){
                    isCheck = true;
                    return false;
                }
            });
            if(!isCheck) {
                basicSwal("하나 이상의 상품을 체크해주세요.", true);
                return false;
            }

            swalConfirm('동기화를 진행하시겠습니까?',synchronize);
        }

        function synchronize(){
            // console.log($("#matchingList").serialize());

            var formData = [];

            // 체크된 체크박스가 포함된 div만 선택
            $("#matchingList .data-row").each(function() {
                if ($(this).find(".matchingCheckbox").is(":checked")) {
                    var data = {
                        matchingData: $(this).find('input[name="matchingData[]"]').val(),
                        matchingValue: $(this).find('input[name="matchingValue[]"]').val()
                    };
                    formData.push(data);
                }
            });

            basicSwal("<i class='bi bi-arrow-clockwise rotating-icon' style='font-size:30px;'></i> <p>동기화가 진행중입니다.</p>",false);
            $.ajax({
                url: './api/matching_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                data: {'formData':formData},
                success: function(response) { 
                    if(response.status=='success'){
                        basicSwal("동기화가 완료되었습니다.",true);
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
