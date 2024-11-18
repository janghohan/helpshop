
// 숫자에 , 찍기
$(document).on('keyup','.localeNumber',function(){
    input = $(this);
    let value =$(this).val();
    value = Number(value.replaceAll(',', ''));
    if(isNaN(value)) {
        input.val(0);
    }else {
        const formatValue = value.toLocaleString('ko-KR');
        input.val(formatValue);
    }
})