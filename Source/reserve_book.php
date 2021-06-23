<!--대출 예약-->
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
$isbn = $_GET['isbn'];
try{
  $conn = new PDO($dsn, $username, $password);
}catch(PDOException $e){
  echo("에러 내용: ".$e -> getMessage());
}


$title2 = $_GET["book_name"] ?? ""; //검색용 title
$c1 = $_GET["choice"] ?? "";
$author = $_GET["author"] ?? "";
$c2 = $_GET["choice2"] ?? "";
$publisher = $_GET["publisher"] ?? "";
$c3 = $_GET["choice3"] ?? "";
$f_year = $_GET["first_year"] ?? "";
$l_year = $_GET["last_year"] ?? "";

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
//대출예약 권수 확인 쿼리
$check = $conn -> prepare("SELECT COUNT(CASE WHEN CNO=".$cno." THEN 1 END) AS CNT_C FROM RESERVE");
$check -> execute();

while ($row = $check -> fetch(PDO::FETCH_ASSOC)) {
if($row['CNT_C']  >= 3 ){//빌린책 3권 이상일 시 대출 불가능 팝업띄우기
    echo "<script>alert('3권 이상 예약은 불가능합니다.');</script>";
}
else{//빌린책 3권이하면 예약 가능
    $stmt = $conn -> prepare("INSERT INTO RESERVE VALUES(".$isbn.",".$cno.",TO_DATE(SYSDATE,'YYYY-MM-DD'))");
    $stmt->execute();
    echo "<script>alert('예약되었습니다.');</script>";
}
//검색조건들도 같이 다시 넘기기
echo "<script>location.href='./after_search.php?book_name=" . $title2 . "&&c1=" . $c1 . "&&author=" . $author . "&&c2=" . $c2 . "&&publisher=" . $publisher . "&&c3=" . $c3 . "&&f_year=" . $f_year . "&&l_year=" . $l_year . "'</script>";
}
?>

