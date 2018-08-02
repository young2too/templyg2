<?php

//날짜를 인수로 그 날짜의 레이팅변화량을 구하는 함수
function calc_rating_by_date($date,$name,$DBcon){
  $game_count_on_date = mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT COUNT(*) as GC
  FROM game_record
  WHERE (1st_name='$name'
  or 2nd_name='$name'
  or 3rd_name='$name'
  or 4th_name='$name')
  and Date='$date'
  "))['GC'];


  $temp_1st_sum=mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT IFNULL(SUM(game_record.1st_score),0) as 1sum
  FROM game_record
  WHERE game_record.1st_name='$name'
  and game_record.Date='$date'
  "))['1sum'];

  $temp_2nd_sum=mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT IFNULL(SUM(game_record.2nd_score),0) as 2sum
  FROM game_record
  WHERE game_record.2nd_name='$name'
  and Date='$date'
  "))['2sum'];

  $temp_3rd_sum=mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT IFNULL(SUM(game_record.3rd_score),0) as 3sum
  FROM game_record
  WHERE game_record.3rd_name='$name'
  and Date='$date'
  "))['3sum'];

  $temp_4th_sum=mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT IFNULL(SUM(game_record.4th_score),0) as 4sum
  FROM game_record
  WHERE game_record.4th_name='$name'
  and Date='$date'
  "))['4sum'];

  $sum_score = $temp_1st_sum+$temp_2nd_sum+$temp_3rd_sum+$temp_4th_sum;

  $temp_1st = mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT COUNT('1st_name') as 1st
  FROM game_record
  WHERE game_record.1st_name = '$name'
  and game_record.Date = '$date'
  "))['1st'];

  $temp_2nd = mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT COUNT('2nd_name') as 2nd
  FROM game_record
  WHERE game_record.2nd_name = '$name'
  and game_record.Date = '$date'
  "))['2nd'];

  $temp_3rd = mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT COUNT('3rd_name') as 3rd
  FROM game_record
  WHERE game_record.3rd_name = '$name'
  and game_record.Date = '$date'
  "))['3rd'];

  $temp_4th = mysqli_fetch_array(mysqli_query($DBcon,"
  SELECT COUNT('4th_name') as 4th
  FROM game_record
  WHERE game_record.4th_name = '$name'
  and game_record.Date = '$date'
  "))['4th'];

  $result = (($sum_score - 25000*$game_count_on_date)/1000)+$temp_1st*30 + $temp_2nd*10 - $temp_3rd*10 - $temp_4th*30;

  return $result;
}
  require "db_connect.php";
  $p1_name = $_GET['p1_name'];
  $p2_name = $_GET['p2_name'];
  $start_date = $_GET['std'];


  $init1_value = calc_rating_by_date($start_date,$p1_name,$conn);
  $init2_value = calc_rating_by_date($start_date,$p2_name,$conn);

  $rating_array = array();
  array_push($rating_array,['Date',$p1_name,$p2_name]);
  array_push($rating_array,[$start_date,$init1_value,$init2_value]);
  while(strtotime($start_date)<=strtotime(date('y-m-d'))){
    $start_date = date("Y-m-d", strtotime($start_date."+1day"));
    $init1_value += calc_rating_by_date($start_date,$p1_name,$conn);
    $init2_value += calc_rating_by_date($start_date,$p2_name,$conn);
    array_push($rating_array,[$start_date,$init1_value,$init2_value]);
  }
  echo json_encode($rating_array);
?>
