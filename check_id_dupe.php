<?php
$admin_id = $_GET['admin_id'];
$query_str = "
SELECT *
FROM admin
WHERE ID= '$admin_id'
";
require "db_connect.php";
$result_set = mysqli_query($conn, $query_str) or die(mysqli_error($conn));
$row = mysqli_fetch_array($result_set);
mysqli_close($conn);
if($row){
  echo "<script>parent.alert('이미 사용중인 아이디입니다');</script>";

}
else{
  echo "<script>parent.alert('사용가능한 아이디입니다');</script>";
  echo "<script>parent.is_check_dupe=true;</script>";
}
?>
