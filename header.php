<?php
session_start();
include './dbConnect.php';

$is_login = $_SESSION['is_login'] ?? false;

if($is_login){
    $userIx = $_SESSION['user_ix'] ?? '1';

    $alarmStmt = $conn->prepare("SELECT * FROM stock_alarm sa JOIN matching_name mn ON mn.ix = sa.matching_ix WHERE mn.user_ix = ? AND sa.is_resolved=0");
    $alarmStmt->bind_param("s",$userIx);
    $alarmStmt->execute();

    $alarmResult = $alarmStmt->get_result();
}



?>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
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
    .header.collapsed{
        margin-left: 0;
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
        background-color: #fffffe;
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
    <?php
    if($is_login){
    ?>
    <div>
        <button class="position-relative" onclick="location.href='./alarm-list.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
            </svg>
            <?php
            if ($alarmResult->num_rows > 0) {
            ?>
            <span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle">
                <span class="visually-hidden">New alerts</span>
            </span>
            <?php }?>
        </button>
        <button id="infoBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </button>
    </div>
    <?php }else{?>
    <div>
        <button onclick="location.href='./login.php'">
            로그인
        </button>
    </div>
    <?php }?>
</div>

<div class="info_pop">
    <div>
        <a href="./mypage.php">
            <p>내 정보</p>
        </a>
    </div>
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
    <!-- <div>
        <a href="./ordering.php">
            <p>발주 관리</p>
        </a>
    </div> -->
    <div>
        <a href="./memo.php">
            <p>메모</p>
        </a>
    </div>
</div>


<script>
    $("#infoBtn").click(function(){
        $(".info_pop").toggle();
    });
</script>