<?php
  function update_player_DB(){
    require "db_connect.php";

    $sql_start_query="
    SELECT NAME, GAME_COUNT
    FROM player_record
    ";
    $start_set = mysqli_query($conn, $sql_start_query);

    while($row=mysqli_fetch_array($start_set)){
      $update_user_name = $row['NAME'];
      $sql_query = "
      UPDATE player_record
      SET 1ST=(SELECT COUNT('1st_name')
              FROM game_record
              WHERE game_record.1st_name='$update_user_name')
      , 2ND=(SELECT COUNT('2nd_name')
              FROM game_record
              WHERE game_record.2nd_name='$update_user_name')
      , 3RD=(SELECT COUNT('3rd_name')
              FROM game_record
              WHERE game_record.3rd_name='$update_user_name')
      , 4TH=(SELECT COUNT('4th_name')
            FROM game_record
            WHERE game_record.4th_name='$update_user_name')
      , GAME_COUNT=(SELECT COUNT(*)
                    FROM game_record
                    WHERE (game_record.1st_name='$update_user_name'
                    OR game_record.2nd_name='$update_user_name'
                    OR game_record.3rd_name='$update_user_name'
                    OR game_record.4th_name='$update_user_name'))
      , SUM_SCORE=((SELECT IFNULL(SUM(game_record.1st_score),0)
                  FROM game_record
                  WHERE game_record.1st_name='$update_user_name')+
                  (SELECT IFNULL(SUM(game_record.2nd_score),0)
                  FROM game_record
                  WHERE game_record.2nd_name='$update_user_name')+
                  (SELECT IFNULL(SUM(game_record.3rd_score),0)
                  FROM game_record
                  WHERE game_record.3rd_name='$update_user_name')+
                  (SELECT IFNULL(SUM(game_record.4th_score),0)
                  FROM game_record
                  WHERE game_record.4th_name='$update_user_name'))
      , AVE_SCORE = IF(player_record.GAME_COUNT=0,0,(player_record.SUM_SCORE)/(player_record.GAME_COUNT))
      , UMA = ((player_record.SUM_SCORE)-(player_record.GAME_COUNT)*25000)/1000+
      (player_record.1ST*30)+(player_record.2ND*10)-(player_record.3RD*10)-(player_record.4TH*30)
      , AVE_UMA = IF(player_record.GAME_COUNT=0,0,(player_record.UMA/player_record.GAME_COUNT))
      WHERE player_record.NAME = '$update_user_name'
      ";
      mysqli_query($conn,$sql_query) or die(mysqli_error($conn));
      echo "<script>window.open('index.php','_self')</script>";
    }
  }
 ?>
