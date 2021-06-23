 <!--로그인 기능 php-->
<?php
session_start(); //세션 시작
$loginSuccess = false; //초깃값은 로그인 실패
if (!isset($_SESSION["id"])) { //session아이디가 없을때
  if (isset($_GET["id"])) { //id가 널이 아니면
    //json 파일 로드
    if (file_exists("data/person.json")) { //json파일이 존재할 시
      $userInfoJSON = fopen("data/person.json", "r"); //json파일 열기
      while (!feof($userInfoJSON)) {
        if (strlen($line = fgets($userInfoJSON)) == 0)
          break;

        $userInfo = json_decode(trim($line), true);
        //main.php에서 입력했던 id,password를 불러와서 userInfo의 값과 같은지 비교
        if ((strcmp($userInfo["id"], $_GET["id"]) === 0) &&
        (strcmp($userInfo["pw"], $_GET["password"]) === 0)) {
          $_SESSION["id"] = $userInfo["id"];
          $_SESSION["name"] = $userInfo["name"];
          $_SESSION["manager"] = $userInfo["manager"];
          $_SESSION["cno"] = $userInfo["cno"];
          $loginSuccess = true; //true로 변경함
          break;
        }
      }
      fclose($userInfoJSON); //json파일닫기 
    }
  }
}
else { //기존 세션 아이디가 있으면 그대로 로그인 지속
  $loginSuccess = true;
}

//로그인 성공 & manager일시 통계기능이 있는 after_login_manager.php로 넘어감
if ($loginSuccess && (strcmp($userInfo["manager"], "true") === 0)) {
  echo "<script>alert('로그인성공');</script>";//로그인 성공 팝업창 띄우기
  echo "<script>location.href='./after_login_manager.php'</script>";

}
//로그인 성공 & 일반 사용자면 통계기능이 없는 after_login.php로 넘어감
else if ($loginSuccess && (strcmp($userInfo["manager"], "false") === 0)) {
  echo "<script>alert('로그인성공');</script>";//로그인 성공 팝업창 띄우기
  echo "<script>location.href='./after_login.php'</script>";
}
else { //로그인 실패시 경고창 띄우고 첫화면으로 다시 돌아감
  echo "<script>alert('로그인실패');</script>";//로그인 실패 팝업창 띄우기
  session_destroy(); //세션지우기
  echo "<script>location.href='./main.php'</script>";//로그인 화면으로 돌아감
}
?>
