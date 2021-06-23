<!--로그인 화면-->
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <!--css불러오기-->
        <link rel="stylesheet" href=style.css type="text/css">
    </head>

    <body>
    <div id="logo"><img src="image\logo.png"></div>
    <div id="logbody"></div>

    <form id="loginform" action="login.php">  <!--submit 누를 시 login.php로 넘어감-->
            <label id="log_id">ID</label>
            <input type="text" id="id_text1" name="id"> <!--아이디 입력-->
            <label id="log_id2">PW</label>
            <input type="password" id="id_text2" name ="password">  <!--password입력-->
            <input type="submit" id="log_button" value="로그인">
    </form>

    <div id="main_title">충남대 온라인 도서관 방문을 환영합니다</div>
    </body>
</html>