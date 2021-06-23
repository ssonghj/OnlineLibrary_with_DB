<!--예약조회/취소 페이지-->
<?php
session_start();
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
        <title></title>
        <!--검색 결과표 형식 지정-->
        <style>table,th,td{ text-align:center; width:1200px; border:1px solid black; font-size: 25px;}</style>
        <link rel="stylesheet" href=style.css type="text/css">
    </head>

    <body>
<table style='position:absolute; top:300px; left:250px;background-color: white;'>
        <tr>
            <th>책이름</th>
            <th>저자</th>
            <th>출판사</th>
            <th>발행년도</th>
            <th>예약취소</th>
        </tr>
    <tbody>

<?php
//CNO불러오기용도
if (file_exists("data/person.json")) { //json파일이 존재할 시          
    $userInfoJSON = fopen("data/person.json", "r"); //json파일 열기         
    while (!feof($userInfoJSON)) {
        if (strlen($line = fgets($userInfoJSON)) == 0)
            break;
        $userInfo = json_decode(trim($line), true);
        if ((strcmp($userInfo["id"], $_SESSION["id"])) === 0) {
            $cno = $userInfo["cno"];
            break;
        }

    }
}
fclose($userInfoJSON); //json파일닫기 

//현재 접속한 사용자의 CNO에 따른 SELECT 쿼리
$stmt = $conn -> prepare('SELECT TITLE, AUTHOR, PUBLISHER, YEAR FROM EBOOK, AUTHORS,RESERVE WHERE EBOOK.ISBN=RESERVE.ISBN AND EBOOK.ISBN=AUTHORS.ISBN AND RESERVE.CNO ='.$cno);
$stmt -> execute(); 
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?>
    <tr>
        <td><?= $row['TITLE']?></td>
        <td><?= $row['AUTHOR']?></td>
        <td><?= $row['PUBLISHER']?></td>
        <td><?= $row['YEAR']?></td>
        <!--예약취소 버튼-->
        <td><button onclick="location.href='./reserve_cancle.php?title=<?= $row['TITLE'] ?>'" style='color: black; background:skyblue; font-size:20px;'>예약취소</button></td>
        
    </tr>
<?php
}
?>

    </tbody>    
</table>  
    <div id="logo"><img src="image\logo.png"></div>
    <?php
    //오른쪽 상단에 이름띄우기
    echo "<p style='color:black; position:absolute; font-size:20px; left: 1500px; top: 75px;'>" . $_SESSION["name"] . "님</p>";
    ?>
    <button id="logout" onclick="location.href='./logout.php'">로그아웃</button>
    <?php
    //메인화면가는 버튼
    if(strcmp($_SESSION["manager"],"false")===0){//관리자 아니면 통계기능 없는 화면으로 가는 버튼
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login.php\'">메인화면</button>';
    }
    else{//관리자면 통계기능 있는 화면으로 가는 버튼
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login_manager.php\'">메인화면</button>';
    }              
    ?>     
    <div id="s_background2">예약조회/취소</div>
    <div id="background"></div>
    </body>
</html>