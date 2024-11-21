
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

$(document).ready(function () {
// 사이드바 접고 펼치기 기능
    $('#toggle-sidebar').click(function () {
        $('.sidebar').toggleClass('collapsed');
        $('.header').toggleClass('collapsed');
        $('.main-content').toggleClass('collapsed');
    });

    // 하위 메뉴 토글 기능
    $('.submenu-toggle').click(function () {
        const submenu = $(this).closest('li').find('.submenu');
        submenu.slideToggle(200); // 서브메뉴 슬라이드 토글
        $(this).toggleClass('collapsed'); // 버튼 상태 변경
    });
});