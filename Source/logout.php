<?php
session_start();//세션시작
session_destroy();//세션지우기
echo "<script>alert('로그아웃 되었습니다.');</script>";//로그아웃 팝업창 띄우기
echo "<script>location.href='./main.php'</script>";//메인으로 돌아감
?>