<?php
include_once('simple_html_dom.php');
include_once('update_player_record.php');
require "db_connect.php";



$refresh_name = $_GET['name_4_search'];//사용자에게서 이름 입력받음
$get_room_no = " L".$_GET['room_no_4_search']." ";//방번호도 입력받음
$get_start_date = $_GET['date_4_search_start'];
$get_end_date = $_GET['date_4_search_end'];

$url = 'http://arcturus.su/tenhou/ranking/ranking.pl?name='.$refresh_name;
$html = file_get_html($url);

$record_set = $html->getElementById("records")->plaintext;
$record_explode = explode("|",$record_set);

$room_no_flag = false;
$room_4ma_flag = false;
$date_flag = false;

for($i=1;$i<count($record_explode);$i++){
  switch($i%8){
    case 0:
    $game_result = $record_explode[$i];
    break;
    case 1:
    $room_no = $record_explode[$i];
    //방번호가 동일한지 체크합니다
    if($room_no == $get_room_no){
      $room_no_flag=true;
    }
    break;
    case 3:
    $date_set = $record_explode[$i];
    $date = explode(" ",$date_set)[1];
    if (($date > $get_start_date) && ($date < $get_end_date)){
      $date_flag = true;
    }
    else{
      continue;
    }
    break;
    case 5:
    //4마반장전인지를 체크합니다
    if($record_explode[$i]===' 四般南喰赤－－ '){
      $room_4ma_flag=true;
    }
    else{
      continue;
    }
    break;
    default:
    continue;
  }

  // 한 대국을 다 스캔 했고, 그 대국의 방번호가 일치, 그리고 4인마작이라면 등록한다
  if($i%8 == 0 && $room_no_flag == true && $room_4ma_flag == true&& $date_flag == true){

    $explode_game_result = explode(" ",$game_result);
    //0번에는 공백밖에 없다 시작이 공백이라
    $name_uma_1st = $explode_game_result[1];//1등기록 파싱
    $name_uma_2nd = $explode_game_result[2];//2등기록 파싱
    $name_uma_3rd = $explode_game_result[3];//3등기록 파싱
    $name_uma_4th = $explode_game_result[4];//4등기록 파싱

    $first_name = explode("(", $name_uma_1st)[0];
    $second_name = explode("(", $name_uma_2nd)[0];
    $third_name = explode("(", $name_uma_3rd)[0];
    $fourth_name = explode("(", $name_uma_4th)[0];

    if($first_name==""||$second_name==""||$third_name==""||$fourth_name=="")continue;

    $uma_1st = explode(")",explode("(", $name_uma_1st)[1])[0];
    $uma_2nd = explode(")",explode("(", $name_uma_2nd)[1])[0];
    $uma_3rd = explode(")",explode("(", $name_uma_3rd)[1])[0];
    $uma_4th = explode(")",explode("(", $name_uma_4th)[1])[0];

    $first_score = score_calc_from_uma($uma_1st,1);
    $second_score = score_calc_from_uma($uma_2nd,2);
    $third_score = score_calc_from_uma($uma_3rd,3);
    $fourth_score = score_calc_from_uma($uma_4th,4);

    $first_star = 0;
    $second_star = 0;
    $third_star = 0;
    $fourth_star = 0;



    $names_arr = array();
    array_push($names_arr,$first_name,$second_name,$third_name,$fourth_name);

    check_name_reg_if_not_regist($names_arr);

    if(check_game_dupe($first_name,$second_name,$third_name,$fourth_name,$first_score,$second_score,$third_score,$fourth_score,$date) == false){
      $sql_query = "
      INSERT INTO game_record (
        game_id,
        1st_name,2nd_name,3rd_name,4th_name,
        1st_score,2nd_score,3rd_score,4th_score,
        1st_star,2nd_star,3rd_star,4th_star,
        Date
        )
        VALUES(
          NULL,
          '$first_name','$second_name','$third_name','$fourth_name',
          '$first_score','$second_score','$third_score','$fourth_score',
          '$first_star','$second_star','$third_star','$fourth_star',
          '$date'
          )
          ";

          mysqli_query($conn, $sql_query);
        }


        //한바퀴 돌면 플래그 초기화
        $room_no_flag=false;
        $room_4ma_flag=false;
        $date_flag = false;
      }
    }

    //점수 역산 함수
    function score_calc_from_uma($uma, $rank){
      switch($rank){
        case 1:
        return ($uma-30)*1000+30000;
        break;
        case 2:
        return ($uma-5)*1000+30000;
        break;
        case 3:
        return ($uma+5)*1000+30000;
        break;
        case 4:
        return ($uma+30)*1000+30000;
        break;
      }
    }

    //게임 그 자체가 중복이 되어있는가를 체크하는 함수
    function check_game_dupe($first_n, $second_n, $third_n, $fourth_n, $first_sc, $second_sc, $third_sc, $fourth_sc, $game_date){
      require ("db_connect.php");

      $sql_check_game_dupe_query="
      SELECT COUNT(*) as C
      FROM game_record
      WHERE 1st_name = '$first_n'
      and 2nd_name = '$second_n'
      and 3rd_name = '$third_n'
      and 4th_name = '$fourth_n'
      and 1st_score = '$first_sc'
      and 2nd_score = '$second_sc'
      and 3rd_score = '$third_sc'
      and 4th_score = '$fourth_sc'
      and Date = '$game_date'
      ";

      $result_set = mysqli_query($conn,$sql_check_game_dupe_query);
      $count_dupe = mysqli_fetch_array($result_set)['C'];
      if($count_dupe != 0){
        return true;
      }
      else{
        return false;
      }
    }

    //이름 등록 안되있으면 등록한다
    function check_name_reg_if_not_regist($names_ary){
      require 'db_connect.php';

      for($i=0;$i<count($names_ary);$i++){

        $input_name = $names_ary[$i];
        $sql_name_reg_query="
        INSERT IGNORE INTO player_record
        SET NAME = '$input_name'
        ";
        mysqli_query($conn,$sql_name_reg_query);
      }
    }


    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
    </head>
    <body>
      <?php
      update_player_DB();
        ?>
    </body>
    </html>
