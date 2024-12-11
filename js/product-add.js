
// 옵션 로우
var marketPriceDiv = $(".marketPriceFormDiv").clone();
var marketPriceHtml = marketPriceDiv.html();

console.log(marketPriceHtml);
var newOptionNames = '<div class="option-row" data-id=""><div class="option-name-group"></div><div class="option-price"><input type="text" class="option-input localeNumber price-input" value="0"></div><div class="stock"><input type="text" class="option-input localeNumber stock-input" value="0"></div><div class="buying-price"><button class="btn btn-outline-dark priceModal">입력</button>'+marketPriceHtml+'</div><div class="op-delete"><button class="btn btn-secondary">×</button></div></div>';
{/* <div class="option-checkbox"><input type="checkbox"></div> */}
//옵션 적용버튼 누를때

let optionNamesArr = [{}];
let optionValuesArr = [{}];
let formCombinations = [];
let options = [];
let optionsArray = [];

function generateCombinations() {
    options = [];
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


    var subHeader = $(".sub-header").length;
    $(".sub-header").css("width",100/subHeader+'%');
    $(".sub-item").css("width",100/subHeader+'%');

}

// 추가 버튼 누를때 옵션 수동으로 추가하기.
$(document).on('click','.add-row-btn',function(){
    var addOption;
    if($(".option-row").length==1){
        alert('조합 생성 후 다시 시도해주세요.');
    }else{
        addOption = $(".option-table").find('.option-row').last().clone();
        addOption.find('.option-input').val('');
        $(".option-table").append(addOption);
    }
});


//판매가 입력 버튼
let priceParent = null;
let priceArr = [];
$(document).on("click", ".priceModal", function () {
    var id = $(this).parents().parents().attr('data-id');
    $("#priceModal").attr('data-id',id);

    priceParent = $(this).parent(".buying-price");

    priceParent.find('.price_by_market').each(function(index, element){
        // priceArr.push($(this).val());
        console.log($(this).val(),"price");
        console.log(index,"index");
        $(".modal-body").find('.price_by_market').eq(index).val($(this).val());
    });
    


    modalOpen("priceModal");

});

// 마켓별 가격 등록 버튼 (모달 닫기)
function newMarketCreate(){

    $(".modal-body .price_by_market").each(function(index, element){
        // tmpValue.push($(this).val());
        priceParent.find('.price_by_market').eq(index).val($(this).val());
    });
    

    modalClose("priceModal");
    priceParent.find('button').focus();
    priceParent.find('button').toggleClass('active');
}

//상품 등록 버튼
function insertPriceAndStock(){
    //옵션값 배열 만들기
    formCombinations = [];
    optionsArray  = [];
    $(".sub-header").each(function(index, element){
        const subHeaderText = $(this).text().trim(); // sub-header의 텍스트 값
    
        // 초기 객체 생성
        const resultObject = {
            name: subHeaderText,
            value: [] // 나중에 value 배열을 채울 예정
        };

        $(".option-name-group").each(function(index2,element){
            if(index2===0) return;
            
            const inputValues = $(this).find("input").map(function() {
                return $(this).val().trim(); // input 값 수집
            }).get(); // jQuery 객체를 배열로 변환
            

            // 중복 제거 및 대응 값 추가
            if (inputValues[index] && !resultObject.value.includes(inputValues[index])) {
                resultObject.value.push(inputValues[index]);
            }
        });

        optionsArray.push(resultObject);
        console.log(optionsArray,"optionsArray");
    });

    

    
    //옵션명 배열 만들기
    $(".option-name-group").each(function(index, element){
        if(index===0) return;
        const inputs = $(this).find("input");
        let values = [];

        // input 값을 배열에 저장
        inputs.each(function () {
            values.push($(this).val());
        });

        // 결과 처리
        let result;
        if (values.length === 1) {
            result = values[0]; // 하나라면 그 값만
        } else if (values.length > 1) {
            result = values.join(","); // 여러 개라면 쉼표로 구분
        } else {
            result = ""; // input이 없는 경우 빈 값
        }

        // div별 결과 출력
        formCombinations.push({ 'name': result });
    });


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
    });

    $(".buying-price .marketPriceForm").each(function(index, element){
        const rows = $(this).find(".market-row");

        const resultArray = [];
        
        rows.each(function(){
            const resultObject  = {}
            const inputIx = $(this).find("input[name='market_ix']").val();
            const inputValue = $(this).find("input[name='price_by_market']").val(); 
            resultObject['ix'] = inputIx;
            resultObject['value'] = inputValue;

            resultArray.push(resultObject);
        });
        formCombinations[index].selling = resultArray;
        console.log(resultArray,"resultArray")
    });

    //배열 체크
    console.log(formCombinations,"formCombinations2");
}