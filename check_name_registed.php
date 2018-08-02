<?php
$Dong_Name = $_GET['dong_name'];
$Nam_Name = $_GET['nam_name'];
$Seo_Name = $_GET['seo_name'];
$Buk_Name = $_GET['buk_name'];


//각각의 이름들을 모두 DB에서 비교해서 빠르게 그냥 세기만 한다
//이게 더 빠르다는듯. 왜인지 EXISTS 함수는 듣질 않는다
$query_str_dong = "
SELECT *
FROM player_record
WHERE NAME = '$Dong_Name'
";
$query_str_nam = "
SELECT *
FROM player_record
WHERE NAME = '$Nam_Name'
";
$query_str_seo = "
SELECT *
FROM player_record
WHERE NAME = '$Seo_Name'
";
$query_str_buk = "
SELECT *
FROM player_record
WHERE NAME = '$Buk_Name'
";

require "db_connect.php";
//각각의 쿼리문을 모두 돌려 다른 결과에 저장
$result_set_dong = mysqli_query($conn, $query_str_dong) or die(mysqli_error($conn));
$result_set_nam = mysqli_query($conn, $query_str_nam) or die(mysqli_error($conn));
$result_set_seo = mysqli_query($conn, $query_str_seo) or die(mysqli_error($conn));
$result_set_buk = mysqli_query($conn, $query_str_buk) or die(mysqli_error($conn));

$row_set_dong = mysqli_fetch_array($result_set_dong);
$row_set_nam = mysqli_fetch_array($result_set_nam);
$row_set_seo = mysqli_fetch_array($result_set_seo);
$row_set_buk = mysqli_fetch_array($result_set_buk);

if($row_set_dong &&$row_set_nam &&$row_set_seo &&$row_set_buk){
  echo "<script>parent.is_all_id_checked=true;</script>";
}
else{
  echo "<script>parent.alert('등록되지 않은 닉네임이 있습니다');</script>";
}
?>
