<?php
include_once('update_player_record.php');
$Rank_count = 4;
function compare($x, $y){
  if ($x[1] == $y[1] )//두번째 인자가 점수이다. 점수순 정렬
  return 0;
  else if ( $x[1] > $y[1] )//오름차순 정렬이다
  return -1;
  else
  return 1;
}

function write_record(){
  $Dong_Name= $_POST['dong_name'];
  $Nam_Name= $_POST['nam_name'];
  $Seo_Name= $_POST['seo_name'];
  $Buk_Name=$_POST['buk_name'];
  //이름 받아오는 구간
  $Dong_Score= $_POST['dong_score'];
  $Nam_Score= $_POST['nam_score'];
  $Seo_Score= $_POST['seo_score'];
  $Buk_Score=$_POST['buk_score'];
  //점수 받아오는 구간
  $Dong_Star= $_POST['dong_star'];
  $Nam_Star= $_POST['nam_star'];
  $Seo_Star= $_POST['seo_star'];
  $Buk_Star= $_POST['buk_star'];
  //별 받아오는 구간

  $Records = array(
    array($Dong_Name, $Dong_Score, $Dong_Star),
    array($Nam_Name, $Nam_Score, $Nam_Star),
    array($Seo_Name, $Seo_Score, $Seo_Star),
    array($Buk_Name, $Buk_Score, $Buk_Star),
  );



  usort($Records, 'compare');
  //이차원 배열에서 점수순으로 정렬되어있음

  $first_score = (int)($Records[0][1]);
  $first_star = (int)($Records[0][2]);
  $first_name = $Records[0][0];

  $second_score = (int)($Records[1][1]);
  $second_star = (int)($Records[1][2]);
  $second_name = $Records[1][0];

  $third_score = (int)($Records[2][1]);
  $third_star = (int)($Records[2][2]);
  $third_name = $Records[2][0];

  $fourth_score = (int)($Records[3][1]);
  $fourth_star = (int)($Records[3][2]);
  $fourth_name = $Records[3][0];





  //$conn = mysqli_connect("localhost", "id6538259_root", "12301230", "id6538259_lyg");
  $conn = mysqli_connect("localhost", "root", "12301230", "lyg");
  $sql_query = "
  INSERT INTO game_record (
    game_id,
    1st_name,2nd_name,3rd_name,4th_name,
    1st_score,2nd_score,3rd_score,4th_score,
    1st_star,2nd_star,3rd_star,4th_star,
    Date
    ) VALUES(
      NULL,
      '$first_name','$second_name','$third_name','$fourth_name',
      '$first_score','$second_score','$third_score','$fourth_score',
      '$first_star','$second_star','$third_star','$fourth_star',
      NOW()
      )
      ";

    //한달이 지난 게임기록은 과감하게 삭제 게임을 삭제하면서 생기는 이름없는 오류기록도 삭''
    $sql_delete_old = "
    DELETE FROM game_record
    WHERE DATE < DATE_ADD(NOW(), INTERVAL -1 MONTH)
    OR NAME = ''
    ";
      mysqli_query($conn, $sql_query);
      mysqli_query($conn, $sql_delete_old);
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <title>
        기록 등록중
      </title>
    </head>
    <body>
      <?php
      write_record();
      update_player_DB();
      ?>
      <script>
        alert("등록되었습니다!");
        window.open("index.php","_self");
      </script>
    </body>
    </html>
