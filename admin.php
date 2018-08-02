<?php
function write_db_admin(){
  $ID= $_POST['admin_id'];
  $PW = $_POST['admin_pw'];
  $NAME = $_POST['admin_name'];
  #다른 주소라면 localhost 대신에 mysql 서버 컴퓨터의 ip를 적어넣'
  require "db_connect.php";
  mysqli_query($conn, "
  INSERT INTO admin (
    ID,
    PW,
    NAME
  ) VALUES(
      '$ID',
      PASSWORD('$PW'),
      '$NAME'
  )
  ");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>관리자 등록중</title>
</head>
<script>
  window.open('index.php','_self');
  alert("등록 완료!");
</script>
<body>
  <?php
  write_db_admin();
  ?>
</body>
</html>
