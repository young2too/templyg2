<?php
function get_info_from_db(){
  require "db_connect.php";
  $query_str = "
  SELECT NAME
  FROM player_record
  ";

  $result_set = mysqli_query($conn, $query_str);
  while($row=mysqli_fetch_array($result_set)){
    $name_input = $row['NAME'];
    echo "<script>make_dropdown('$name_input');</script>";
  }
}

function get_data_from_db(){
  require "db_connect.php";
  $nick_name = $_GET['NAME'];
  //$nick_name = 'IROHAS';
  echo "<script>push_array_set('Date','$nick_name');</script>";
  echo "<script>player1_name='$nick_name'</script>";

  $sql_get_min_date_query="
  SELECT MIN(Date) as md
  FROM game_record
  WHERE 1st_name='$nick_name'
  or 2nd_name='$nick_name'
  or 3rd_name='$nick_name'
  or 4th_name='$nick_name'
  ";

  $min_date = mysqli_fetch_array(mysqli_query($conn,$sql_get_min_date_query))['md'];

  echo "<script>start_date='$min_date'</script>";

  $init_value = calc_rating_by_date($min_date,$nick_name,$conn);


  $rating_array=array();
  array_push($rating_array,[$min_date,$init_value]);



  while(strtotime($min_date)<=strtotime(date('y-m-d'))){
    $min_date = date("Y-m-d", strtotime($min_date."+1day"));
    $init_value += calc_rating_by_date($min_date,$nick_name,$conn);
    array_push($rating_array,[$min_date,$init_value]);
  }

  for($i=0;$i<count($rating_array);$i++){
    $date = $rating_array[$i][0];
    $rate = $rating_array[$i][1];
    echo "<script>push_array_set('$date',$rate)</script>";
  }
}

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

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>나의 전적</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" media="screen and (max-width: 768px)" href="media_query.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">

  var player1_name;
  var player2_name;
  var start_date;
  var draw_array = new Array();



  function set_p2_data(p2){
    player2_name=p2;
    $.ajax({
      url: 'Relative_Rating_chart.php?p1_name='+player1_name+'&p2_name='+player2_name+'&std='+start_date,
      type:'get',
      dataType : 'json',
      success: function(data){
        draw_array.length=0;

        for(var i=0;i<data.length;i++){
          draw_array.push(data[i]);
        }
        drawChart();
      },
      error : function( jqxhr , status , error ){
        console.log( jqxhr , status , error );
      }
    });
  }

  function push_array_set(){
    var sub_arr = new Array();
    for(var i=0;i<arguments.length;i++){
      sub_arr.push(arguments[i]);
    }
    draw_array.push(sub_arr);
  }

  function drawChart() {
    var data = google.visualization.arrayToDataTable(
      draw_array
    );

    var options = {
      title: '상세 전적',
      curveType: 'function',
      vAxis : {title : '레이팅'},
      hAxis : {title : '날짜', textPosition : 'none'},
      pointSize : 5,
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
  }

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function make_dropdown(name){
    var x = document.getElementsByClassName('name_input');
    var i = 0;
    var option = document.createElement("OPTION");
    option.text=name;
    x[0].add(option);
  }
  </script>
</head>
<body>
  <div class="grid1">
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
    <div class="another_player">
      상대 전적 비교 :
      <select class="name_input" name="find_another" id="player2">
        <option value="" selected = true></option>
      </select>
      <input type="button" name="confirm" value="확인" onclick="set_p2_data(document.getElementById('player2').value)">
    </div>
  </div>

  <?php
  get_data_from_db();//차트를 만들기 위해 db의 데이터를 받아오는 함수
  get_info_from_db();//콤보박스를 채우는 php함수
  ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

  <script type="text/javascript">
  $(document).ready(function() {
    $('.name_input').select2({
      placeholder:"이름 선택",
      allowClear :true
    });
  });

</script>

</body>
</html>
