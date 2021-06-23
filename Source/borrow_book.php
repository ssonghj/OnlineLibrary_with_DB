<!--책 빌리기-->
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
$title = $_GET['title'];//쿼리 사용 title
try{
  $conn = new PDO($dsn, $username, $password);
}catch(PDOException $e){
  echo("에러 내용: ".$e -> getMessage());
}

//검색 결과에서 정보가 갱신이 되어도 검색조건들이 유지되어 검색 결과가 나타날 수 있도록
//검색에 사용했던 값들을 저장함 
$title2 = $_GET["book_name"]?? "";//검색용 title
$c1 = $_GET["choice"]?? "";
$author = $_GET["author"]?? "";
$c2 = $_GET["choice2"]?? "";
$publisher = $_GET["publisher"]?? "";
$c3 = $_GET["choice3"]?? "";
$f_year = $_GET["first_year"]?? "";
$l_year = $_GET["last_year"]?? "";

//CNO불러오기용도
if (file_exists("data/person.json")) { //json파일이 존재할 시  
$userInfoJSON = fopen("data/person.json", "r"); //json파일 열기  
while (!feof($userInfoJSON)) {
    if (strlen($line = fgets($userInfoJSON)) == 0)
      break;
    $userInfo = json_decode(trim($line), true);
    if ((strcmp($userInfo["id"], $_SESSION["id"])) === 0) {
      $cno = $userInfo["cno"];//변수에 cno저장
      break;
    }

  }
}
fclose($userInfoJSON); //json파일닫기 

//대출권수 확인 쿼리
$check = $conn -> prepare("SELECT COUNT(CASE WHEN CNO=".$cno." THEN 1 END) AS CNT_C FROM EBOOK");
$check -> execute();
while ($row = $check -> fetch(PDO::FETCH_ASSOC)) {
if($row['CNT_C']  >= 3 ){//대출권수 3권 이상일 경우
    echo "<script>alert('3권 이상 대출은 불가능합니다.');</script>";//대출 불가능
}
else{//빌린책 3권이하 일 경우
    //update 쿼리로 EBOOK 내용 갱신
    $stmt = $conn -> prepare("UPDATE EBOOK SET CNO=".$cno.", EXTTIMES=0, DATERENTED=TO_DATE(SYSDATE,'YYYY-MM-DD') ,DATEDUE=TO_DATE(SYSDATE+10) 
                                WHERE TITLE='".$title."'");
    $stmt->execute();
    echo "<script>alert('대출되었습니다.');</script>";
}
//검색 결과 페이지로 다시 돌아간다. 검색조건들을 그대로 다시 가지고 넘어가서
//조건이 초기화되지 않도록 한다.
echo "<script>location.href='./after_search.php?book_name=".$title2."&&c1=".$c1."&&author=".$author."&&c2=".$c2."&&publisher=".$publisher."&&c3=".$c3."&&f_year=".$f_year."&&l_year=".$l_year."'</script>";
}
?>

