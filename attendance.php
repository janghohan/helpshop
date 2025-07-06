<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <title>출석 체크</title>
    <style>
    body { font-family: sans-serif; text-align: center; padding: 1rem; }
    .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; max-width: 400px; margin: auto; margin-top: 25px;}
    .day { border: 1px solid #ccc; padding: 10px; border-radius: 5px; min-height: 40px; }
    .checked { background-color: #a0e0a0; font-weight: bold; }
    .today { border: 2px solid #0077cc; }
    input { padding: 0.5rem; margin-bottom: 1rem; width: 80%; }
    button { padding: 0.5rem 1rem; }
    #inputArea{
        max-width: 720px;
        margin: 0 auto;
        margin-top: 3rem;
    }
    
  </style>
</head>
<body>

  <h2>낚시맨 출석 체크</h2>

  <div id="inputArea">
    <input type="text" class="form-control" id="user_id_input" placeholder="아이디를 입력하세요">
    <button class="btn btn-primary" style="width: 100%;" onclick="saveUserId()">입력 완료</button>
  </div>

  <div id="calendarArea" style="display:none;">
    <p><strong id="user_id_display"></strong>님의 출석 달력</p>
    <button class="btn btn-primary" onclick="checkIn()">오늘 출석하기</button>
    <div id="calendar" class="calendar"></div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
  <script>
    localStorage.clear();
    const userIdKey = 'checkin_user_id';

    function saveUserId() {
      const id = document.getElementById('user_id_input').value.trim();
      if (id) {
        localStorage.setItem(userIdKey, id);
        initCalendar();
      }
    }

    function initCalendar() {
        const id = localStorage.getItem(userIdKey);
        if (!id) return;
        document.getElementById('inputArea').style.display = 'none';
        document.getElementById('calendarArea').style.display = 'block';
        document.getElementById('user_id_display').innerText = id;

        $.ajax({
            url: "./api/load_checkins.php",  // 데이터를 처리할 PHP 파일
            type: "POST",
            data: { 'user_id': id},
            success: function(response) {
                renderCalendar(response);
            },
            error: function(xhr, status, error) {
                console.error("에러 발생:", error);
            }
        });
    }

    function checkIn() {
        const id = localStorage.getItem(userIdKey);
        if (!id) return alert('ID가 없습니다.');

        $.ajax({
            url: "./api/checkin.php",  // 데이터를 처리할 PHP 파일
            type: "POST",
            data: { 'user_id': id},
            success: function(response) {
                console.log(response);
                initCalendar();
            },
            error: function(xhr, status, error) {
                console.error("에러 발생:", error);
            }
        });
    }

    function renderCalendar(checkedDates) {
        console.log(checkedDates);
      const now = new Date();
      const year = now.getFullYear();
      const month = now.getMonth();
      const today = now.getDate();

      const firstDay = new Date(year, month, 1).getDay();
      const lastDate = new Date(year, month + 1, 0).getDate();

      const cal = document.getElementById('calendar');
      cal.innerHTML = '';

      for (let i = 0; i < firstDay; i++) {
        cal.innerHTML += `<div></div>`;
      }

      for (let d = 1; d <= lastDate; d++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        const isChecked = checkedDates.includes(dateStr);
        const isToday = d === today;

        cal.innerHTML += `<div class="day ${isChecked ? 'checked' : ''} ${isToday ? 'today' : ''}">
            ${d} ${isChecked ? '✔' : ''}
          </div>`;
      }
    }

    window.onload = initCalendar;
  </script>
</body>
</html>
