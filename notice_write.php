<?php
session_start();

  if(empty($_SESSION['is_login']) == true){
    echo ("<script>alert('글쓰기 권한이 없습니다!')</script>");
    echo ("<script>window.open('notice_page.php','_self')</script>");
  }

 ?>

<html>
<head>
<title>공지사항</title>
<link rel="stylesheet" href="notice_style.css">
</head>
<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<form action='insert_notice.php' method='post'>
<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
    <tr>
        <td height=20 align=center bgcolor=#999999>
        <font color=white><B>글 쓰 기</B></font>
        </td>
    </tr>
    <!-- 입력 부분 -->
    <tr>
        <td bgcolor=white>&nbsp;
        <table>
            <tr>
                <td width=60 align=left >제 목</td>
                <td align=left >
                    <INPUT type=text name=title size=63 maxlength=35>
                </td>
            </tr>
            <tr>
                <td width=60 align=left >내용</td>
                <td align=left >
                    <TEXTAREA name=content cols=65 rows=15></TEXTAREA>
                </td>
            </tr>
            <tr>
                <td colspan=10 align=center>
                    <INPUT type=submit value="글 저장하기">
                    &nbsp;&nbsp;
                    <INPUT type=reset value="다시 쓰기">
                    &nbsp;&nbsp;
                    <INPUT type=button value="되돌아가기"
                    onclick="history.back(-1)">
                </td>
            </tr>
        </TABLE>
</td>
</tr>
</table>
</form>
</center>
</body>
</html>
</html>
