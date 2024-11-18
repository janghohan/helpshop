<style>
    /* 헤더 */
    .header {
        background-color: #274BDB;
        padding: 15px 20px;
        color: grey;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
    }
    .header .menu {
        display: flex;
        gap: 20px;
    }
    .header .menu a {
        color: white;
        text-decoration: none;
        font-size: 14px;
    }
    .header .menu a:hover {
        text-decoration: underline;
    }
    .header button {
        background: none;
        border: 1px solid #fff;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px;
    }
</style>

<!-- 헤더 -->
<div class="header">
    <div class="menu">
        <a href="#">마진율 계산기</a>
        <a href="#">엑셀 변환기</a>
        <a href="#">스토어 랭킹</a>
        <a href="#">통합 재고 관리</a>
        <a href="#">통합 판매 분석</a>
        <a href="#">셀툴 이용 가이드</a>
    </div>
    <div>
        <button>내 정보</button>
        <button>로그아웃</button>
    </div>
</div>