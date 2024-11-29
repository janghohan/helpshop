$(document).ready(function () {
    
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