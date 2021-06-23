<!--자료검색 기능-->
<?php
session_start();//세션 시작
//DB접속
$tns = "
   (DESCRIPTION=
      (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
      (CONNECT_DATA= (SERVICE_NAME=XE))
    )
";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'c##madang';
$password = 'madang';

try{
  $conn = new PDO($dsn, $username, $password);
}catch(PDOException $e){
  echo("에러 내용: ".$e -> getMessage());
}
?>


<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link rel="stylesheet" href=style.css type="text/css">
    </head> 

    <body>
    <div id="logo"><img src="image\logo.png"></div>
    <?php
    //오른쪽 상단에 이름띄우기
    echo "<p style='color:black; position:absolute; font-size:20px; left: 1500px; top: 75px;'>" . $_SESSION["name"] . "님</p>";
    ?>
    <button id="logout" onclick="location.href='./logout.php'">로그아웃</button>
    <div id="s_background">자료검색</div>
    <div id="background"></div>
    <div id="background2"></div>
    <div id="name">서명</div>
    <div id="author">저자</div>
    <div id="publisher">출판사</div>
    <div id="year">발행년도</div>
    <p style="font-size: 40px;color: white;position:absolute;left: 1010px;top: 660px;">~</p>

    <!--submit누를 시 after_search.php로 넘어감-->
    <form id="loginform" action="./after_search.php">
      <!--서명-->
      <input autocomplete="off" type="text" id="text1" name="book_name">
      <!--서명 입력란 오른쪽의 콤보박스-->
      <select name="choice" style="font-size: 45px; position: absolute; left:1340px; top:403px; width: 130px; height:55px;">
          <option value="">----</option>
          <option value="and">and</option>
          <option value="or">or</option>
          <option value="not">not</option>
      </select>
      
      <!--저자-->
      <input autocomplete="off" type="text" id="text2" name="author">
      <!--저자 입력란 오른쪽의 콤보박스-->
      <select name="choice2" style="font-size: 45px; position: absolute; left:1340px; top:500px; width: 130px; height:55px;">
          <option value="">----</option>
          <option value="and">and</option>
          <option value="or">or</option>
          <option value="not">not</option>
        </select>

      <!--출판사-->
      <input autocomplete="off" type="text" id="text3" name="publisher">
      <!--출판사 입력란 오른쪽의 콤보박스-->
      <select name="choice3" style="font-size: 45px; position: absolute; left:1340px; top:600px; width: 130px; height:55px;">
          <option value="">----</option>
          <option value="and">and</option>
          <option value="or">or</option>
          <option value="not">not</option>
      </select>

      <!--발행년도-->
      <input autocomplete="off" type="text" id="text4" name="first_year">
      <input autocomplete="off" type="text" id="text5" name="last_year">
      <!--검색버튼-->
      <input type="submit" id="search" value="검색">
    </form>

    </body>
</html>