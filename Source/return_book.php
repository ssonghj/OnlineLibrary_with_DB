<!--책반납하기-->
<?php
session_start();
//DB 접속
$tns = "
   (DESCRIPTION=
      (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
      (CONNECT_DATA= (SERVICE_NAME=XE))
    )
";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'c##madang';
$password = 'madang';
$title = $_GET['title'];
try{
  $conn = new PDO($dsn, $username, $password);
}catch(PDOException $e){
  echo("에러 내용: ".$e -> getMessage());
}

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

//반납 전 정보 가져오는 쿼리
$before = $conn->prepare("SELECT ISBN, DATERENTED FROM EBOOK WHERE TITLE='".$title."'");
$before->execute();
$isbn="";
$dateRented="";
while($row = $before -> fetch(PDO::FETCH_ASSOC)){
  $isbn = $row["ISBN"];
  $dateRented = $row["DATERENTED"];
}
//반납 후 EBOOK 업데이트 쿼리
$stmt = $conn -> prepare("UPDATE EBOOK SET CNO=null,DATERENTED=null,DATEDUE=null,EXTTIMES=null
                             WHERE CNO=".$cno." and title='".$title."'");
$stmt->execute();

//반납 정보를 PREVIOUSRENTAL에 업데이트하는 쿼리
$return = $conn->prepare("INSERT INTO PREVIOUSRENTAL VALUES(".$isbn.",TO_DATE('".$dateRented."','YYYY-MM-DD'),TO_DATE(SYSDATE,'YYYY-MM-DD'),".$cno.")");
$return->execute();
//반납 후 원래 화면으로 돌아가기
echo "<script>alert('반납 되었습니다');</script>";
echo "<script>location.href='./cur_borrow.php'</script>";
?>
