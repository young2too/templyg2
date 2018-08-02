<?php
require "db_connect.php";

$id = $_GET['id'];
$query = "
DELETE FROM
notice WHERE
notice_id='$id'
";
$result=mysqli_query($conn,$query);

mysqli_close($conn);
?>
<center>
<meta http-equiv='Refresh' content='1; URL=notice_page.php'>
<FONT size=2 >삭제되었습니다.</font>
