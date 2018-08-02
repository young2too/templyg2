<html>
<head>
<title>공지사항</title>
<link rel="stylesheet" href="notice_style.css">
</head>

<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<?php
    //데이터 베이스 연결하기
    require "db_connect.php";

    $id = $_GET['id'];
    if(isset($_GET['no'])==true){
      $no=$_GET['no'];
    }
    else{
      $no=0;
    }

    // 글 정보 가져오기
    $result=mysqli_query($conn,"
    SELECT *
    FROM notice
    WHERE notice_id='$id'");
    $row=mysqli_fetch_array($result);
?>
<table width=580 border=0 cellpadding=2 cellspacing=1
bgcolor=#777777>
<tr>
    <td height=20 colspan=4 align=center bgcolor=#999999>
        <font color=white><B><?=$row['notice_title']?></B></font>
    </td>
</tr>
<tr>
    <td width=50 height=20 align=center bgcolor=#EEEEEE>
    날&nbsp;&nbsp;&nbsp;짜</td><td width=240
    bgcolor=white><?=$row['notice_date']?></td>
</tr>
<tr>
    <td bgcolor=white colspan=4>
    <font color=black>
    <pre><?=$row['notice_content']?></pre>
    </font>
    </td>
</tr>
<!-- 기타 버튼 들 -->
<tr>
    <td colspan=4 bgcolor=#999999>
    <table width=100%>
        <tr>
            <td width=200 align=left height=20>
                <a href=notice_page.php?no=<?=$no?>><font color=white>
                [목록보기]</font></a>
                <a href=notice_write.php><font color=white>
                [글쓰기]</font></a>
                <a href=notice_edit.php?id=<?=$id?>><font color=white>
                [수정]</font></a>
                <a href=notice_del.php?id=<?=$id?>>
                <font color=white>[삭제]</font></a>
            </td>
            <!-- 기타 버튼 끝 -->
            <!-- 이전 다음 표시 -->
            <td align=right>
<?php
    // 현재 글보다 id 값이 큰 글 중 가장 작은 것을 가져온다. 삭제됬을때를 생각해서 이렇게 구현함
    // 즉 바로 이전 글 ORDER BY id ASC가 함축됨 즉 오름차순으로 정렬되있음
    $query=mysqli_query($conn, "
    SELECT notice_id
    FROM notice
    WHERE notice_id >'$id'
    LIMIT 1");
    $prev_id=mysqli_fetch_array($query);

    if ($prev_id['notice_id']){
      $temp = $prev_id['notice_id'];
        echo "<a href='notice_read.php?id=$temp'>
        <font color=white>[이전]</font></a>";
    }
    else{
        echo "[이전]";
    }

    //내림차순으로 정렬하고 작은 것 한개 가져옴
    $query=mysqli_query($conn, "
    SELECT notice_id
    FROM notice
    WHERE notice_id <'$id'
    ORDER BY notice_id DESC LIMIT 1");
    $next_id=mysqli_fetch_array($query);

    if ($next_id['notice_id']){
      $temp = $next_id['notice_id'];
      echo "<a href='notice_read.php?id=$temp'>
        <font color=white>[다음]</font></a>";
    }
    else{
        echo "[다음]";
    }
?>
            </td>
        </tr>
    </table>
    </b></font>
    </td>
</tr>
</table>
</center>
</body>
</html>
