<?php
session_start();

if (!isset($_SESSION['user_ix'])) {
    // header('Location: login.php'); // 로그인 페이지로 리디렉션
    // exit;
    $user_ix = '1';
}

?>

<style>
    /* 헤더 */
    .header {
        padding: 15px 20px;
        height: 60px;
        color: grey;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        margin-left: 250px; /* 사이드바 넓이 */
        border-bottom: 1px solid #E2E5EA;
        transition: margin-left 0.3s;
    }
    .header .menu {
        display: flex;
        gap: 20px;
    }
    .header .menu a {
        color: #343434;
        text-decoration: none;
        font-size: 14px;
    }
    .header .menu a:hover {
        text-decoration: underline;
    }
    .header button {
        background: none;
        border: 1px solid #fff;
        color: #343434;
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
        <button>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
            </svg>
        </button>
        <button>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </button>
    </div>
</div>