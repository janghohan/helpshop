

// 옵션 로우
var newOptionNames = '<div class="option-row" data-id=""><div class="option-checkbox"><input type="checkbox"></div><div class="option-name-group"></div><div class="option-price"><input type="text" class="option-input localeNumber price-input" value="0"></div><div class="stock"><input type="text" class="option-input localeNumber stock-input" value="0"></div><div class="buying-price"><button class="btn btn-secondary priceModal" data-bs-toggle="modal" data-bs-target="#priceModal">입력</button></div><div class="op-delete"><button class="btn btn-secondary">×</button></div></div>';

//옵션 적용버튼 누를때

let optionNamesArr = [{}];
let optionValuesArr = [{}];
let formCombinations = [];
let options = [];

function generateCombinations() {
    formCombinations = [];
    // 입력 필드에서 값을 가져와 콤마로 분리하여 배열로 변환
    
    $('.option-fields').each(function() {
        const optionName = $(this).find('.optionName').val().trim();
        const optionValues = $(this).find('.optionValue').val().split(',').map(val => val.trim());
        
        console.log("옵션명", optionName);
        optionNamesArr.push(optionName);
        // 옵션명과 옵션값이 모두 있는 경우만 추가
        if (optionName && optionValues.length > 0) {
            options.push({ name: optionName, values: optionValues });
        }
    });
    // 조합 생성

    console.log(options,"option"); // options = [{name:색상, values:[]}, {name:크기, values:[]}]

    let combinations = [{}]; // 초기 조합은 빈 객체로 시작
    options.forEach(option => { //ex)  { name: "색상", values: ["빨강", "파랑"] } 각 옵션에 대해 처리
        const tempCombinations = [];
        combinations.forEach(comb => {
            option.values.forEach(value => { // ex) values : [빨강,파랑] 옵션값들에 대한 처리
                tempCombinations.push({ ...comb, [option.name]: value });
            });
        });
        combinations = tempCombinations;
    });

    const keys = Object.keys(combinations[0]); // 첫 번째 조합의 키를 가져옴
    const values = combinations.map(combination => Object.values(combination)); // 모든 값만 배열로 분리

    $(".sub-headers").html("");
    keys.forEach(key => {
        $(".sub-headers").append('<div class="sub-header">'+key+'</div>');
    });
    $(".option-row:not(:eq(0))").remove();
    
    //옵션명 생성
    var tempId = 1;
    values.forEach(value => {
        // newOptionNames.find('.option-row').attr('data-id',tempId);
        $(".option-table").append(newOptionNames);
        value.forEach(eachValue => {
            $('.option-row').last().find('.option-name-group').append('<div class="sub-item"><input type="text" class="option-input" value="'+eachValue+'"></div>');
            $('.option-row').last().attr('data-id',tempId);
        });
        tempId ++;
    });

    //상품등록을 위해 form에 맞춰 key-value 조합 정리
    const keyVal = keys.join('-'); //key만 - 로 구분해서 문자열로 변환
    const valuesCombi = values.map(item => Object.values(item).join(','));  //['값,값' , '값,값', '값,값'] 형태로 바꿈
    for(var i=1; i<tempId; i++){
        formCombinations.push({'id':i, 'name':keyVal, 'value':valuesCombi[i-1]});
    }
    // 

    console.log(formCombinations,"formCombinations");

    var subHeader = $(".sub-header").length;
    $(".sub-header").css("width",100/subHeader+'%');
    $(".sub-item").css("width",100/subHeader+'%');

}

// 추가 버튼 누를때 옵션 수동으로 추가하기.
$(document).on('click','.add-row-btn',function(){
    var addOption;
    if($(".option-row").length==1){
    }else{
        addOption = $(".option-table").find('.option-row').last().clone();
    }
    addOption.find('input').val('');
    $(".option-table").append(addOption);
});


//마켓별 가격 입력
$(document).on("click", ".priceModal", function () {
    var id = $(this).parents().parents().attr('data-id');
    $("#priceModal").attr('data-id',id);

    //기존값을 그대로 가져오고, 새로운건 0으로 
    if(formCombinations[id-1].selling){
        $(".market-row").each(function(index, element) {
            var tempKey = $(this).find('input[name="market_ix"]').val();

            var tempPriceVal = formCombinations[id-1]['selling'][tempKey];
            // console.log(formCombinations[id-1]['selling'][$(this).find('input[name="market_ix"]').val()],'selling');
            $(this).find('input[name="price_by_market"]').val(tempPriceVal);           
        });
    }else{
        $(".market-row").each(function(index, element) {
            $(this).find('input[name="price_by_market"]').val("");   
        });
    }

    modalOpen("priceModal");

});

// 마켓별 가격 등록 버튼
function newMarketCreate(){
    const optionId = $("#priceModal").attr('data-id');

    const resultObject = {}; // 결과를 담을 배열
    $(".market-row").each(function(index, element) {
        const ixValue = $(this).find('input[name="market_ix"]').val(); // name="ix"의 값
        const valueValue = $(this).find('input[name="price_by_market"]').val(); // name="value"의 값

        if (ixValue && valueValue) {
            resultObject[ixValue] = valueValue; //객체로 key:value 저장
        }
    });

    console.log(resultObject,"resultObject");
    formCombinations[optionId-1].selling = resultObject;

    console.log(formCombinations,"formCombinations");
    modalClose("priceModal");
    $(".priceModal").focus();

    
    
}

//상품 등록 버튼
function productAdd(){

    //매입가를 배열에 넣는다.
    $(".price-input").each(function(index, element) {
        const $element = $(element); // 현재 반복 중인 요소를 jQuery 객체로 변환
        
        // 요소의 값을 읽기
        const value = $element.val();
        
        formCombinations[index].price = value;
    });

    //재고수량을 배열에 넣는다.
    $(".stock-input").each(function(index, element) {
        const $element = $(element); // 현재 반복 중인 요소를 jQuery 객체로 변환
        
        // 요소의 값을 읽기
        const value = $element.val();
        
        console.log(value,"stock");
        formCombinations[index].stock = value;

        formCombinations[index].selling = {};
    });

    //배열 체크
    console.log(formCombinations,"formCombinations");
}