<!--예약취소-->
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

//예약삭제를 위한 DELETE 쿼리
$stmt = $conn -> prepare("DELETE FROM RESERVE WHERE CNO=".$cno." AND RESERVE.ISBN=(SELECT EBOOK.ISBN FROM EBOOK WHERE LOWER(EBOOK.TITLE) = LOWER('".$title."'))");
$stmt->execute();
//예약 취소 후 원래 화면으로 돌아가기
echo "<script>alert('예약 취소 되었습니다');</script>";
echo "<script>location.href='./reserve.php'</script>";
?>
