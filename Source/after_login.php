<!--일반 사용자의 로그인 후 화면 (통계기능 x)-->
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href=style.css type="text/css">
    </head>

    <body>
    <div id="logo"><img src="image\logo.png"></div>
    <?php
    session_start();//세션 시작
    //오른쪽 상단에 이름띄우기
    echo "<p style='color:black; position:absolute; font-size:20px; left: 1500px; top: 75px;'>" . $_SESSION["name"] . "님</p>";
    ?>
    <!--각 버튼을 누르면 설정한 각 php파일로 넘어감-->
    <button id="logout" onclick="location.href='./logout.php'">로그아웃</button>
    <button id="circle1" onclick="location.href='./search_book.php' ">자료검색</button>
    
    <div id="circle2">MyLibrary<br>
        <button id="font_" onclick="location.href='./cur_borrow.php' ">1. 대출현황</button>
        <button id="font_" onclick="location.href='./reserve.php' ">2. 예약조회/취소</button>
     </div>
     <div id="background"></div>
    </body>
</html>