<?php
$sup_pass = $_GET['admin_pass'];
require "db_connect.php";
$sql_query ="
SELECT *
FROM super_admin
WHERE SuperPW=PASSWORD('$sup_pass')
LIMIT 1
";

$result_set = mysqli_query($conn,$sql_query) or die(mysqli_error($conn));
$row = mysqli_fetch_array($result_set);
if($row){
  echo "<script>parent.alert('최고관리자 인증되었습니다');</script>";
  echo "<script>parent.window.open('admin_reg_page.html','_self');</script>";
}
else{
  echo "<script>parent.alert('최고관리자 인증에 실패했습니다');</script>";
}
 ?>
