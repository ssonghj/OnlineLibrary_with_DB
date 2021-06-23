<!--책 연장하기-->
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
$isbn = $_GET['isbn'];
try{
  $conn = new PDO($dsn, $username, $password);
}catch(PDOException $e){
  echo("에러 내용: ".$e -> getMessage());
}

//ebook 업데이트 쿼리
$stmt = $conn -> prepare("UPDATE EBOOK SET EXTTIMES=EXTTIMES+1, DATEDUE=TO_DATE(DATEDUE+10) WHERE ISBN='".$isbn."'");
$stmt->execute();
echo "<script>alert('연장되었습니다.');</script>";//연장되었습니다 팝업창 띄우기
echo "<script>location.href='./cur_borrow.php'</script>";//다시 cur_borrow.php로 돌아감
?>

