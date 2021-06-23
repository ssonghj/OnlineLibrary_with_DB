<!--대출 현황-->
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
<!--검색 결과 표 만들기-->
<table style='position:absolute; top:300px; left:250px; background-color: white;'>
        <tr>
            <th>ISBN</th>
            <th>도서이름</th>
            <th>대출날짜</th>
            <th>반납날짜</th>
            <th>연장횟수</th>
            <th>반납</th>
            <th>연장</th>
        </tr>
    <tbody>

<?php
//CNO불러오기용도
if(file_exists("data/person.json")){//json파일이 존재할 시
$userInfoJSON = fopen("data/person.json", "r");//json파일 열기
while(!feof($userInfoJSON)){
    if(strlen($line = fgets($userInfoJSON)) == 0 ) break;          
    $userInfo = json_decode(trim($line),true);
    if((strcmp($userInfo["id"],$_SESSION["id"]))===0){
            $cno = $userInfo["cno"];
            break;
        }    
    
    }
}
fclose($userInfoJSON); //json파일닫기 

//대출현황확인쿼리
$stmt = $conn -> prepare("SELECT ISBN, TITLE, DATERENTED, DATEDUE, EXTTIMES FROM EBOOK WHERE CNO = ".$cno);
$stmt -> execute(); 
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {    
?>
    <tr>
        <!--while문을 돌면서 table 각 행마다 값을 넣어줌-->
        <td><?= $row['ISBN']?></td>
        <td><?= $row['TITLE']?></td>
        <td><?= $row['DATERENTED']?></td>
        <td><?= $row['DATEDUE']?></td>
        <td><?= $row['EXTTIMES']?></td>
        <!--반납하기 버튼-->
        <td><button onclick="location.href='./return_book.php?title=<?= $row['TITLE'] ?>'" style='color: black; background:skyblue; font-size:20px;'>반납하기</button></td>
<?php
$a = $row['ISBN'];
$b = $row['EXTTIMES'];
    //예약한 사람이 있는지 확인하는 쿼리
    $check = $conn -> prepare("SELECT COUNT(CASE WHEN ISBN=".$a." THEN 1 END) AS CNT_C FROM RESERVE");
    $check->execute();
    $row = $check->fetch(PDO::FETCH_ASSOC);
    if($b>=2){//이미 2번연장했으면
        echo "<td>연장불가</td>";//연장 불가
    }
    else if($row['CNT_C'] >= 1){//연장하려는 책을 다른 사람이 이미 예약했다면
        echo "<td>연장불가</td>";//연장 불가
    }
    else{//2번이상 연장하지도 않고 다른 사람이 예약해두지도 않았다면
        //연장가능
        echo '<td><button onclick="location.href=\'./extend_return.php?isbn='.$a.'\'" style="color: black; background:#90AFEC; font-size:20px;">연장신청</button></td>';
    }
?>
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
    if(strcmp($_SESSION["manager"],"false")===0){//관리자 아니면 통계기능 없는 메인화면
        echo '<button style="color:black;position:absolute;font-size:20px;left:1350px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login.php\'">메인화면</button>';
    }
    else{//관리자면 통계기능 있는 메인화면
        echo '<button style="color:black;position:absolute;font-size:20px;left:150px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login_manager.php\'">메인화면</button>';
    }              
    ?>     
    <div id="s_background">대출현황</div>
    <div id="background"></div>
    </body>
</html>