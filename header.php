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
        border: none;
        color: #343434;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px;
    }

    /* 내정보 */
    .info_pop{
        width: 130px;
        position: fixed;
        right: 5px;
        color: #000;
        background-color: #f9f9f9;
        border-radius: 5px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        font-size: 15px;
        box-shadow: 0px 2px 3px #aaa;
        display: none;
    }
    .info_pop div{
        padding: 10px 22px;
        display: flex;
        height: 42px;
    }
</style>

<!-- 헤더 -->
<div class="header">
    <div class="menu">
    </div>
    <div>
        <button>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
            </svg>
        </button>
        <button id="infoBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </button>
    </div>
</div>

<div class="info_pop">
    <div>
        <a href="./market.php">
            <p>마켓 관리</p>
        </a>
    </div>
    <div>
        <a href="./account.php">
            <p>거래처 관리</p>
        </a>
    </div>
    <div>
        <a href="./ordering.php">
            <p>발주 관리</p>
        </a>
    </div>
</div>

<script>
    $("#infoBtn").click(function(){
        $(".info_pop").toggle();
    });
</script>