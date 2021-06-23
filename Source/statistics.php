<!--통계화면-->
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
    //오른쪽 상단에 이름띄우기
        session_start();
        echo "<p style='color:black; position:absolute; font-size:20px; left: 1500px; top: 75px;'>" . $_SESSION["name"] . "님</p>";
    ?>
    <button id="logout" onclick="location.href='./logout.php'">로그아웃</button>
    <?php
    //메인화면가는 버튼
    if(strcmp($_SESSION["manager"],"false")===0){//관리자 아니면 통계기능 없는 화면으로 이동
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login.php\'">메인화면</button>';
    }
    else{//관리자면 통계기능 있는 화면으로 이동
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login_manager.php\'">메인화면</button>';
    }              
    ?>      
    <button id="button_style1" onclick="location.href='./s1.php'">전체 빌린 기록 조회</button>
    <button id="button_style2" onclick="location.href='./s2.php'">ISBN 별 빌린 횟수 조회</button>
    <button id="button_style3" onclick="location.href='./s3.php'">책별 CNO 조회</button>
    <div id="background"></div>
    <div id="s_background">통계</div>
    </body>
</html>