<?php
require('db_connect.php');
$input_date = $_GET['which_trash_date'];
$sql_query = "
SELECT question_htm as Q
FROM today_question
WHERE date='$input_date'
";

$result_set = mysqli_query($conn,$sql_query) or die(mysqli_error($conn));
$row=mysqli_fetch_array($result_set);

if(empty($row['Q'])==false){ //검색이 되어서 잘 나왔다면
  echo $row['Q'];//그대로 출력
}
else{//파이썬 파일을 통해 서치해야됨
  $sql_query = "
  update request_date_trash
  set request_date = '$input_date'
  where ID=1
  ";//일단 요청한 날짜를 DB에 올리
  mysqli_query($conn,$sql_query) or die(mysqli_error($conn));
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">``
  <title>ㅎㅎ</title>
</head>
<script language="python">
  //print("hello")
</script>
<body>
</body>
</html>
