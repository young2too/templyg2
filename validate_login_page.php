<?php
session_start();
function check_login(){
  $logined_id = $_POST['loginid'];
  $logined_pw = $_POST['loginpw'];
  $query_str = "
   SELECT *
   FROM admin
   WHERE ID= '$logined_id' and
         PW= PASSWORD('$logined_pw')
         ";
  require "db_connect.php";
  $result_set = mysqli_query($conn, $query_str) or die(mysqli_error($conn));
  while ($row = mysqli_fetch_array($result_set)){
    if($row['ID'] == $logined_id){
      valid_login($row['NAME']);
      mysqli_close($conn);
      return true;
    }
  }
  echo "<script>
  alert('아이디 또는 패스워드가 잘못되었습니다.');
  history.back();
  </script>";
  mysqli_close($conn);
  return false;
}

function valid_login($Name){
  $_SESSION['is_login']=true;
  $_SESSION['admin_name']=$Name;
  echo "<script>window.open('index.php','_self');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>로그인 중</title>
</head>
<body>
  <?php
    check_login();
  ?>
</body>
</html>
