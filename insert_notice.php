<?php
    //데이터 베이스 연결하기
    require "db_connect.php";

    $title = addslashes($_POST['title']);
    $content = addslashes($_POST['content']);



    $query = "
    INSERT INTO notice(
      notice_title,
      notice_content,
      notice_date)
    VALUES (
      '$title',
      '$content',
      NOW()
    )";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));

    mysqli_close($conn);

    echo ("<meta http-equiv='Refresh' content='1; URL=notice_page.php'>");
?>
<center>
<font size=2>정상적으로 저장되었습니다.</font>
