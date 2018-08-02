<?php
    //데이터 베이스 연결하기
    include "db_connect.php";
    $id = $_GET['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $query = "
    UPDATE notice
    SET notice_title='$title',
    notice_content='$content'
    WHERE notice_id='$id'
    ";
    $result=mysqli_query($conn,$query);

    mysqli_close($conn);

    echo ("<meta http-equiv='Refresh' content='1;
    URL=notice_read.php?id=$id'>");
?>
<center>
<font size=2>정상적으로 수정되었습니다.</font>
