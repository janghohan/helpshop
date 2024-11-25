$(document).ready(function () {
    // 특정 input 요소에 포커스를 자동으로 설정
    $('#search-input').focus();

    $('#search-input').on('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Enter 키의 기본 동작 방지 (form submit 방지)
            $('#search-btn').click(); // 버튼 클릭 이벤트 트리거
        }
    });
    
    // 버튼 클릭 이벤트 처리
    $('#search-btn').on('click', function () {
        alert('Button clicked!');
    });

    //리셋 버튼
    $('#reset-btn').on('click', function () {
        $('#search-input').val("");
        $('#account-cate').prop('selectedIndex', 0);
        $('#product-cate').prop('selectedIndex', 0);
    });
});