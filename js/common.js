
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
    // 특정 input 요소에 포커스를 자동으로 설정
    $('#search-input').focus();

    //엔터 누르면 검색
    $('#search-input').on('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Enter 키의 기본 동작 방지 (form submit 방지)
            $('#search-btn').click(); // 버튼 클릭 이벤트 트리거
        }
    });

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