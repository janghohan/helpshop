

// 옵션 로우
var newOptionNames = '<div class="option-row" ><div class="option-checkbox"><input type="checkbox"></div><div class="option-name-group"></div><div class="option-price"><input type="text" class="option-input localeNumber" value="0"></div><div class="stock"><input type="text" class="option-input localeNumber" value="0"></div><div class="buying-price"><button class="btn btn-secondary">입력</button></div><div class="memo"><button class="btn btn-secondary">입력</button></div><div class="op-delete"><button class="btn btn-secondary">×</button></div></div>';

//옵션 적용버튼 누를때
function generateCombinations() {
    // 입력 필드에서 값을 가져와 콤마로 분리하여 배열로 변환
    const options = [];
    $('.option-fields').each(function() {
        const optionName = $(this).find('.optionName').val().trim();
        const optionValues = $(this).find('.optionValue').val().split(',').map(val => val.trim());
        
        // 옵션명과 옵션값이 모두 있는 경우만 추가
        if (optionName && optionValues.length > 0) {
            options.push({ name: optionName, values: optionValues });
        }
    });
    // 조합 생성

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

    // $(".option-row").remove();
    values.forEach(value => {
        
        $(".option-table").append(newOptionNames);
        value.forEach(eachValue => {
            console.log(eachValue);
            $('.option-row').last().find('.option-name-group').append('<div class="sub-item"><input type="text" class="option-input" value="'+eachValue+'"></div>')
        });
    });

    var subHeader = $(".sub-header").length;
    console.log(subHeader);
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