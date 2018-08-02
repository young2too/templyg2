<html>
<head>
<title>공지사항</title>
<link rel="stylesheet" href="notice_style.css">
<link rel="stylesheet" media="screen and (max-device-width: 768px)" href="media_query.css" />

</head>

<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>
<!-- 입력된 값을 다음 페이지로 넘기기 위해 FORM을 만든다. -->
<form action=update_notice.php?id=<?=$_GET['id']?> method='post'>
<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
    <tr>
        <td height=20 align=center bgcolor=#999999>
            <font color=white><B>글 수 정 하 기</B></font>
        </td>
    </tr>
<?php
    //데이터 베이스 연결하기
    require "db_connect.php";

    $id = $_GET['id'];

    // 먼저 쓴 글의 정보를 가져온다.
    $result=mysqli_query($conn, "
    SELECT *
    FROM notice
    WHERE notice_id='$id'"
    );
    $row=mysqli_fetch_array($result);
?>
<!-- 입력 부분 -->
    <tr>
        <td bgcolor=white>&nbsp;
        <table>
            <tr>
                <td width=60 align=left >제 목</td>
                <td align=left >
                    <INPUT type=text name=title size=63
                    value="<?=$row['notice_title']?>">
                </td>
            </tr>
            <tr>
                <td width=60 align=left >내용</td>
                <td align=left >
                    <TEXTAREA name=content cols=65 rows=15><?=$row['notice_content']?></TEXTAREA>
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
