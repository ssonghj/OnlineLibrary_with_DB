<!--자료 검색 후 결과 화면-->
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
<style>table,th,td{ text-align:center; width:1500px; border:1px solid black; font-size: 25px;}</style>
<link rel="stylesheet" href=style.css type="text/css">
</head>
<body>
<!--책 표만들기-->
<table style='position:absolute; top:300px; left:200px;background-color: white;'>
        <tr>
            <th>ISBN</th>
            <th>서명</th>
            <th>저자</th>
            <th>출판사</th>
            <th>발행년도</th>
            <th>대출여부</th>
            <th>대출 신청/예약</th>
        </tr>
    <tbody>
<?php
//검색 값으로 넘겨진 값들
$title = $_GET["book_name"]?? "";//서명
$c1 = $_GET["choice"]?? "";//콤보박스1
$author = $_GET["author"]?? "";//저자
$c2 = $_GET["choice2"]?? "";//콤보박스2
$publisher = $_GET["publisher"]?? "";//출판사
$c3 = $_GET["choice3"]?? "";//콤보박스3
$f_year = $_GET["first_year"]?? "";//검색 시작 년도
$l_year = $_GET["last_year"]?? "";//검색 마지막 년도

$a = "";//검색 조건 별 where절 만들기 용 변수
//c1,c2,c3가 모두 값이 없을 때
if(strcmp($c1,"")===0 && strcmp($c2,"")===0 && strcmp($c3,"")===0){
    if(strcmp($title,"")===0){//타이틀에 값이 없고
        if(strcmp($author,"")===0){//저자에 값이 없고
            if(strcmp($publisher,"")===0){//출판사에 값이 없고
                if(strcmp($f_year,"")===0){//년도에도 값이 없을때 전체검색
                    $a = 'WHERE Ebook.ISBN=AUTHORS.ISBN';
                }else{//년도만 검색
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";}
            }else{//출판사에 값이 있을때 출판사 검색
                $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(publisher) like LOWER('%".$publisher."%')";}
        }else{//저자에 값이 있을때 저자 검색
            $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(author) like LOWER('%".$author."%') ";}
    }else{//타이틀에 값이 있을때 타이틀 검색
        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') ";}
}
else{//c1,c2,c3에 하나라도 값이 있을 때
    if(strcmp($c1,"")===0){//c1에 값이 없고
        if(strcmp($c2,"")===0){//c2에 값이 없고
            if(strcmp($c3,"")!==0){//c3에 값이 있다면 xxo
                if(strcmp($c3,"not")===0){//c3값이 not이라면
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(publisher) like LOWER('%".$publisher."%') and not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                }
                else{//c3값이 and나 or이라면
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (LOWER(publisher) like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                }
            }
        }
        else{//c1에는 값이 없고 c2에 값이 있고 
            if(strcmp($c3,"")===0){//c3에는 값이 없고 xox
                if(strcmp($c2,"not")===0){//c2값이 not이라면             
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(author) like LOWER('%".$author."%') AND LOWER(publisher) not like LOWER('%".$publisher."%')";
                }
                else{//c2값이 and나 or이라면
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (LOWER(AUTHOR) LIKE LOWER('%".$author."%') ".$c2." LOWER(PUBLISHER) LIKE LOWER('%".$publisher."%'))";
                }
            }
            else{//c3에는 값이 있다면 xoo
                if(strcmp($c2,"not")===0 && strcmp($c3,"not")===0 ){//둘다 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(author) like LOWER('%".$author."%') AND LOWER(publisher) not like LOWER('%".$publisher."%') AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                }
                else if(strcmp($c2,"not")===0 && strcmp($c3,"not")!==0){//c2만 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(author) like LOWER('%".$author."%') AND (LOWER(publisher) not like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                }
                else if(strcmp($c2,"not")!==0 && strcmp($c3,"not")===0){//c3만 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (LOWER(author) like LOWER('%".$author."%') ".$c2." LOWER(publisher) like LOWER('%".$publisher."%')) AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                }
                else{//둘다 not 아닐때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(author) like LOWER('%".$author."%') ".$c2." LOWER(publisher) like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                }
            }

        }
    }
    else{//c1에 값이 있고
        if(strcmp($c2,"")===0){//c2에는 값이 없고
            if(strcmp($c3,"")===0){//c3에 값이 없을때  oxx
                if(strcmp($c1,"not")===0){//c1이 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND lower(title) like lower('%".$title."%') and LOWER(author) not like LOWER('%".$author."%') ";
                }
                else{//c1이 and,or 일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (lower(title) like lower('%".$title."%') ".$c1." LOWER(author) like LOWER('%".$author."%'))";
                }           
            }
            else{//c3에 값이 있을 때 oxo
                if(strcmp($c1,"not")===0 && strcmp($c3,"not")===0 ){//둘다 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND LOWER(publisher) not like LOWER('%".$publisher."%') AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                }
                else if(strcmp($c1,"not")===0 && strcmp($c3,"not")!==0){//c1만 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND (LOWER(publisher) not like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                }
                else if(strcmp($c1,"not")!==0 && strcmp($c3,"not")===0){//c3만 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(publisher) like LOWER('%".$publisher."%')) AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                }
                else{//둘다 not 아닐때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND ((LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(publisher) like LOWER('%".$publisher."%')) ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                }
            }
        }
        else{//c2에는 값이 있고
            if(strcmp($c3,"")===0){//c3에는 값이 없을때 oox
                if(strcmp($c1,"not")===0 && strcmp($c2,"not")===0 ){//둘다 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND LOWER(author) not like LOWER('%".$author."%') AND LOWER(publisher) not like LOWER('%".$publisher."%')";
                }
                else if(strcmp($c1,"not")===0 && strcmp($c2,"not")!==0){//c1만 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND (LOWER(author) not like LOWER('%".$author."%') ".$c2." LOWER(publisher) like LOWER('%".$publisher."%'))";
                }
                else if(strcmp($c1,"not")!==0 && strcmp($c2,"not")===0){//c2만 not일때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(author) like LOWER('%".$author."%')) AND LOWER(publisher) not like LOWER('%".$publisher."%')";
                }
                else{//둘다 not 아닐때
                    $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND ((LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(author) like LOWER('%".$author."%')) ".$c2." LOWER(publisher) like LOWER('%".$publisher."%'))";
                }                
            }
            else{//c3에 값이 있을 때 ooo
                if(strcmp($c1,"not")===0){//c1이 not 일때 
                    if(strcmp($c2,"not")===0 && strcmp($c3,"not")===0 ){//둘다 not일때 nnn
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND LOWER(author) not like LOWER('%".$author."%') AND LOWER(publisher) not like LOWER('%".$publisher."%') AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                    }
                    else if(strcmp($c2,"not")===0 && strcmp($c3,"not")!==0){//c2만 not일때 nna
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND LOWER(author) not like LOWER('%".$author."%') AND (LOWER(publisher) not like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                    }
                    else if(strcmp($c2,"not")!==0 && strcmp($c3,"not")===0){//c3만 not일때 nan
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') AND (LOWER(author) not like LOWER('%".$author."%') ".$c2." LOWER(publisher) like LOWER('%".$publisher."%')) AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                    }
                    else{//둘다 not 아닐때 naa
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND ((LOWER(title) like LOWER('%".$title."%') AND LOWER(author) not like LOWER('%".$author."%') ".$c2." LOWER(publisher) like LOWER('%".$publisher."%')) ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                    }                          
                }
                else{//c1이 not이 아닐때
                    if(strcmp($c2,"not")===0 && strcmp($c3,"not")===0 ){//둘다 not일때 ann
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(author) like LOWER('%".$author."%') AND LOWER(publisher) not like LOWER('%".$publisher."%') AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                    }
                    else if(strcmp($c2,"not")===0 && strcmp($c3,"not")!==0){//c2만 not일때 ana
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND (LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(author) not like LOWER('%".$author."%')) AND (LOWER(publisher) not like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                    }
                    else if(strcmp($c2,"not")!==0 && strcmp($c3,"not")===0){//c3만 not일때 aan
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND ((LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(author) not like LOWER('%".$author."%')) ".$c2." LOWER(publisher) like LOWER('%".$publisher."%')) AND not year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY')";
                    }
                    else{//둘다 not 아닐때 aaa
                        $a = "WHERE EBOOK.ISBN=AUTHORS.ISBN AND ((LOWER(title) like LOWER('%".$title."%') ".$c1." LOWER(author) like LOWER('%".$author."%')) ".$c2." LOWER(publisher) like LOWER('%".$publisher."%') ".$c3." year between TO_DATE(".$f_year.",'YYYY') and TO_DATE(".$l_year.",'YYYY'))";
                    }
                }
            }
        }
    }
}

//prepare로 db에 쿼리 전달
$stmt = $conn -> prepare('SELECT EBOOK.ISBN,TITLE, AUTHOR, PUBLISHER, YEAR, DATERENTED FROM Ebook, AUTHORS '.$a);//책 검색 쿼리
$stmt -> execute(); //쿼리문 실행
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    if (strcmp($row['DATERENTED'], null) === 0) { //대출날이 없으면 대출중인게 아니므로
        $row['DATERENTED'] = '대출가능';//대출가능 출력
    }    
    else {//대출날이 있다면 대출 중이므로
        $row['DATERENTED'] = '대출중';//대출 중 출력
    }
?>
    <tr>
        <td><?= $row['ISBN']?></td><!--while문을 돌면서 table 각 행마다 값을 넣어줌-->
        <td><?= $row['TITLE']?></td>
        <td><?= $row['AUTHOR']?></td>
        <td><?= $row['PUBLISHER']?></td>
        <td><?= $row['YEAR']?></td>
        <td><?= $row['DATERENTED']?></td>
<?php
$a = $row['TITLE'];
$b = $row['ISBN'];

    if(strcmp($row['DATERENTED'],'대출가능')===0){//대출가능하면
        //대출신청
        echo '<td><button onclick="location.href=\'./borrow_book.php?title='.$a.'&&book_name='.$title.'&&c1='.$c1.'&&author='.$author.'&&c2='.$c2.'&&publisher='.$publisher.'&&c3='.$c3.'&&f_year='.$f_year.'&&l_year='.$l_year.'\'" style="color: black; background:skyblue; font-size:20px;">대출신청</button></td>';
    }
    //대출이 불가능하다면
    else{
        $st = $conn -> prepare("SELECT CNO FROM EBOOK WHERE EBOOK.ISBN=".$b);//책 검색 쿼리
        $st -> execute(); 
        $row = $st -> fetch(PDO::FETCH_ASSOC);
        //대출예약을 하는데 대출한 사람이 나 자신이라면 대출현황버튼으로 띄우기
        if(strcmp($_SESSION["cno"],$row['CNO'])===0){
        echo '<td><button onclick="location.href=\'./cur_borrow.php?isbn='.$b.'\'" style="color: black; background:yellow; font-size:20px;">대출현황</button></td>';        
        }
        else{//내가 대출한게 아니면 대출 예약 띄우기
            //내가 이미 예약을 한 책이면
            $st2 = $conn -> prepare("SELECT CNO FROM RESERVE WHERE RESERVE.ISBN=".$b);//책 검색 쿼리
            $st2 -> execute(); 
            $row = $st2 -> fetch(PDO::FETCH_ASSOC);            
            if(strcmp($_SESSION["cno"], $row['CNO']) === 0){//내가 이미 예약했다면 예약현황띄우기
                echo '<td><button onclick="location.href=\'./reserve.php?isbn='.$b.'\'" style="color: black; background:orange; font-size:20px;">예약현황</button></td>';        
            }
            else{//내가 예약한 책이 아니라면 대출예약 버튼으로 띄우기
                echo '<td><button onclick="location.href=\'./reserve_book.php?isbn='.$b.'&&book_name='.$title.'&&c1='.$c1.'&&author='.$author.'&&c2='.$c2.'&&publisher='.$publisher.'&&c3='.$c3.'&&f_year='.$f_year.'&&l_year='.$l_year.'\'" style="color: black; background:#90AFEC; font-size:20px;">대출예약</button></td>';        
            }
        }
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
    //오른쪽 상단에 접속자이름띄우기
    echo "<p style='color:black; position:absolute; font-size:20px; left: 1500px; top: 75px;'>" . $_SESSION["name"] . "님</p>";
    ?>
    <button id="logout" onclick="location.href='./logout.php'">로그아웃</button>
    <button style='color:black;position:absolute;font-size:20px;left:1600px; top:250px; background: #E0B852;'
                         onclick="location.href='./search_book.php'">뒤로가기</button>
    <?php
    if(strcmp($_SESSION["manager"],"false")===0){//관리자 아니면
        echo '<button style="color:black;position:absolute;font-size:20px;left:1500px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login.php\'">메인화면</button>';
    }
    else{//관리자면
        echo '<button style="color:black;position:absolute;font-size:20px;left:1500px; top:250px; background: #E0B852;"
                         onclick="location.href=\'./after_login_manager.php\'">메인화면</button>';
    }              
    ?>         
    <div id="s_background">검색결과</div>
    </body>
</html>