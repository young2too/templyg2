<?php
function write_db_name(){
  $NAME = $_POST['user_name'];
  #다른 주소라면 localhost 대신에 mysql 서버 컴퓨터의 ip를 적어넣'
  require "db_connect.php";
  mysqli_query($conn, "
  INSERT INTO player_record (
    NAME
  ) VALUES(
      '$NAME'
  )
  ");
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>이름 등록중</title>
  </head>
  <body>
    <?php
    write_db_name();
     ?>
     <script>
     alert("닉네임 등록되었습니다!");
     window.open('index.php','_self');
     </script>
  </body>
</html>
