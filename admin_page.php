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

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>관리자 페이지</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" media="screen and (max-width: 768px)" href="media_query.css" />
  <script type="text/javascript" src="fetch-2.0.2/fetch.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
</head>
<script>
var is_all_id_checked = false;

function check_submit(){
  var putin = document.getElementsByClassName('score_putin');
  var i;
  for(i=0;i<putin.length;i++){//입력을 다 했는지를 확인하는 부분
    if(putin[i].value == ""){
      alert("채우지 않은 값이 있습니다!");
      return putin[i].focus();
    }
  }
  if(parseInt(document.getElementsByName('dong_score')[0].value)
  + parseInt(document.getElementsByName('nam_score')[0].value)
  + parseInt(document.getElementsByName('seo_score')[0].value)
  + parseInt(document.getElementsByName('buk_score')[0].value)
  != 100000){//점수 합이맞는지 확인하는 부분
    alert("점수 합이 맞지 않습니다!");
    return;
  }
  else{//이름이 등록되어있는지 확인하는 부분
    var temp_dong_name = document.getElementsByName('dong_name')[0].value;
    var temp_nam_name = document.getElementsByName("nam_name")[0].value;
    var temp_seo_name = document.getElementsByName("seo_name")[0].value;
    var temp_buk_name = document.getElementsByName("buk_name")[0].value;
    is_name_registed.location.href ="./check_name_registed.php?dong_name="+temp_dong_name+"&nam_name="+temp_nam_name+"&seo_name="+temp_seo_name+"&buk_name="+temp_buk_name;
  }

  if(is_all_id_checked == true){
    document.getElementById('score_form').submit();
    is_all_id_checked=false;
  }
}
function make_dropdown(name){
  var x = document.getElementsByClassName('name_input');
  var i = 0;

  var options = new Array();
  for(i=0;i<x.length;i++){
    options[i]=document.createElement("OPTION");
    options[i].text=name;
    x[i].add(options[i]);
  }
}

function calc_amount_score(){
  var scores = document.getElementsByClassName('score_putin');
  var i, amount;
  amount = 0;
  for(i=0;i<scores.length;i++){
    amount+=Number(scores[i].value);
  }
  print_amount_sum(amount);
}

function print_amount_sum(amount){
  var sum_div = document.getElementsByClassName('score_sum_div')[0];
  sum_div.innerHTML="합 : "+amount;
}
</script>

<body>
  <h1><a class="title" href="index.php">MADE_BY_LYG</a></h1>
  <h2>전적등록 페이지</h2>
  <hr>
  <div class="grid1">
    <div class="sidemenu">
      <ul id="sideul">

      </ul>
    </div>
    <div class="main_content">
      <div class="reg">
        <form action="record.php" method="post" id="score_form">
          <fieldset>
            <legend>점수 입력(전란 입력 필수)</legend>
            동 이름 :
            <select class="name_input" name="dong_name" required="true">
              <option value="" selected=true></option>
            </select><br class="only_M">
            점수 : <input class="score_putin" type="number" step="100" onkeyup="calc_amount_score()" name="dong_score">
            별 : <input class="star_putin" type="number" name="dong_star"><br>

            <hr>

            남 이름 :
            <select class="name_input" name="nam_name" required="true" >
              <option value="" selected=true></option>
            </select><br>
            점수 : <input class="score_putin" type="number" name="nam_score"
            step="100" onkeyup="calc_amount_score()">
            별 : <input class="star_putin" type="number" name="nam_star"><br>

            <hr>

            서 이름 :
            <select class="name_input" name="seo_name" required="true">
              <option value="" selected=true></option>
            </select><br>
            점수 : <input class="score_putin" type="number" step="100"  onkeyup="calc_amount_score()" name="seo_score">
            별 : <input class="star_putin" type="number" name="seo_star"><br>

            <hr>

            북 이름 :
            <select class="name_input" name="buk_name" required="true">
              <option value="" selected=true></option>
            </select><br>
            점수 : <input class="score_putin" type="number" step="100" name="buk_score" onkeyup="calc_amount_score()">
            별 : <input class="star_putin" type="number" name="buk_star"><br>
            <input class="submit_btn" type="button" name="submit_btn" value="제출" onclick="check_submit()" style="margin:10px 170px">
          </fieldset>
        </form>
        <div class="score_sum_div">
          합 : 0
        </div>
      </div>
    </div>

  </div>

  <div class="footer">
    <nav>
      <ul>
        <li class="horizen" onclick="window.open('index.php','_self')">처음으로</li>
        <li class="horizen">young2too13@gmail.com</li>
        <li class="horizen">Tel 010-123-1234</li>
        <li class="horizen">ver MADE_BY_LYG</li>
      </ul>
    </nav>
  </div>
  <?php
  get_info_from_db();
  ?>
  <iframe id="is_name_registed" scrolling="no" frameborder="no" width="0" height="0" name="is_name_registed"></iframe>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

  <script type="text/javascript">
  $(document).ready(function() {
    $('.name_input').select2({
      placeholder:"닉네임",
      allowClear :true
    });
  });
  fetch('manager_sidelist').then(function(response){
    response.text().then(function(text){
      document.querySelector('#sideul').innerHTML = text;
    })
  });
  </script>

</body>
</html>
