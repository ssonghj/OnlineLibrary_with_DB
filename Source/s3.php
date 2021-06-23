<!--내림차순 CNO 랭크 조회-->
<?php
session_start();
//DB접속
$tns = "
   (DESCRIPTION=
      (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
      (CONNECT_DATA= (SERVICE_NAME=XE))
    )
";
$dsn = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'c##madang';
$password = 'madang';

try {
    $conn = new PDO($dsn, $username, $password);
}
catch (PDOException $e) {
    echo("에러 내용: " . $e->getMessage());
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <style>table,th,td{ text-align:center; width:1200px; border:1px solid black; font-size: 25px;}</style>
        <link rel="stylesheet" href=style.css type="text/css">
    </head>

    <body>
<table style='position:absolute; top:300px; left:250px; background-color: white;'>
        <tr>
            <th>ISBN</th>
            <th>빌린날짜</th>
            <th>cno</th>
            <th>cno순위(내림차순)</th>
        </tr>
    <tbody>

<?php
//내림차순 CNO 순위 조회 SELECT 쿼리
$stmt = $conn -> prepare("SELECT ISBN , DATERENTED, CNO,
                            RANK() OVER (ORDER BY CNO DESC) CNO_RANK FROM PREVIOUSRENTAL");
$stmt -> execute(); 
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?>
    <tr>
        <td><?= $row['ISBN']?></td>
        <td><?= $row['DATERENTED']?></td>
        <td><?= $row['CNO']?></td>
        <td><?= $row['CNO_RANK']?></td>
    </tr>
<?php
}
?>
    </tbody>    
</table>        

    <div id="logo"><img src="image\logo.png"></div>
    <?php
    //오른쪽 상단에 이름띄우기
    echo "<p style='color:black; position:absolute; font-size:20px; left: 1500px; top: 75px;'>".$_SESSION["name"]."님</p>";
    ?>
    <button id="logout" onclick="location.href='./logout.php'">로그아웃</button>
    
    <?php
    //메인화면가는 버튼
    if(strcmp($_SESSION["manager"],"false")===0){//관리자 아니면 통계기능 없는 화면 이동
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login.php\'">메인화면</button>';
    }
    else{//관리자면 통계 기능이 있는 화면으로 이동
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login_manager.php\'">메인화면</button>';
    }
    //뒤로가기 버튼 구현
    echo '<button style="color:black;position:absolute;font-size:20px;left:1250px; top:250px; background: #E0B852;"
                    onclick="location.href=\'./statistics.php\'">뒤로가기</button>';            
    ?>     
    <div id="background"></div>
    </body>
</html>