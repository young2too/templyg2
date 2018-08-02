<?php
session_start();
function MBCK(){
  $mobilechk = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/i';

   // 모바일 접속인지 PC로 접속했는지 체크합니다.
   if(preg_match($mobilechk, $_SERVER['HTTP_USER_AGENT'])) {
      return true;
   } else {
      return false;
   }
}


$PAGE_MAX_REVEAL = 20;
function is_logined(){
  if(empty($_SESSION['is_login']) == false){
    echo ("<script>change_form_logout2login()</script>");
    return true;
  }
  else{
    echo ("<script>change_form_login2logout()</script>");
    return true;
  }
}

function show_ranking(){
  $row_num = 1;
  require "db_connect.php";
  $sql_ranking_query = "
  SELECT  *
  FROM `player_record`
  ORDER BY `player_record`.`UMA` DESC
  ";
  $result_set = mysqli_query($conn, $sql_ranking_query) or die(mysqli_error($conn));
  while($row = mysqli_fetch_array($result_set)){
    $print_Name = $row['NAME'];

    $print_1st = mysqli_fetch_array(mysqli_query($conn,"
    SELECT COUNT('1st_name') as 1st
    FROM game_record
    WHERE game_record.1st_name='$print_Name'
    "))['1st'];

    $print_2nd = mysqli_fetch_array(mysqli_query($conn,"
    SELECT COUNT('2nd_name') as 2nd
    FROM game_record
    WHERE game_record.2nd_name='$print_Name'
    "))['2nd'];
    $print_3rd = mysqli_fetch_array(mysqli_query($conn,"
    SELECT COUNT('3rd_name') as 3rd
    FROM game_record
    WHERE game_record.3rd_name='$print_Name'
    "))['3rd'];

    $print_4th = mysqli_fetch_array(mysqli_query($conn,"
    SELECT COUNT('4th_name') as 4th
    FROM game_record
    WHERE game_record.4th_name='$print_Name'
    "))['4th'];
    $print_Game_Count = $print_1st+$print_2nd+$print_3rd+$print_4th;

    $temp_1st_score_sum = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.1st_score),0) as 1st_sum
    FROM game_record
    WHERE game_record.1st_name='$print_Name')
    "))['1st_sum'];
    $temp_2nd_score_sum = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.2nd_score),0) as 2nd_sum
    FROM game_record
    WHERE game_record.2nd_name='$print_Name')
    "))['2nd_sum'];
    $temp_3rd_score_sum = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.3rd_score),0) as 3rd_sum
    FROM game_record
    WHERE game_record.3rd_name='$print_Name')
    "))['3rd_sum'];
    $temp_4th_score_sum = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.4th_score),0) as 4th_sum
    FROM game_record
    WHERE game_record.4th_name='$print_Name')
    "))['4th_sum'];

    $print_Sum_Score = $temp_1st_score_sum+$temp_2nd_score_sum+$temp_3rd_score_sum+$temp_4th_score_sum;



    $temp_1st_star = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.1st_star),0) as 1st_star
    FROM game_record
    WHERE game_record.1st_name='$print_Name')
    "))['1st_star'];
    $temp_2nd_star = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.2nd_star),0) as 2nd_star
    FROM game_record
    WHERE game_record.2nd_name='$print_Name')
    "))['2nd_star'];
    $temp_3rd_star = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.3rd_star),0) as 3rd_star
    FROM game_record
    WHERE game_record.3rd_name='$print_Name')
    "))['3rd_star'];
    $temp_4th_star = mysqli_fetch_array(mysqli_query($conn,"
    (SELECT IFNULL(SUM(game_record.4th_star),0) as 4th_star
    FROM game_record
    WHERE game_record.4th_name='$print_Name')
    "))['4th_star'];



    $print_Star = $temp_1st_star+$temp_2nd_star+$temp_3rd_star+$temp_4th_star;

    //$print_UMA = ($print_Sum_Score-($print_Game_Count*25000))/1000+($print_1st*30+$print_2nd*10-$print_3rd*10-$print_4th*30);
    $print_UMA=$row['UMA'];

    if($print_Game_Count!=0){
      $print_Ave_Score = round($print_Sum_Score/$print_Game_Count,2);
      $print_Ave_UMA = round($print_UMA/$print_Game_Count,2);
    }
    else{
      $print_Ave_Score=0;
      $print_Ave_UMA=0;
    }

    if(MBCK()==true){
      echo "<script>
      print_result_into_cell('$row_num')
      print_result_into_cell('$print_Name')
      print_result_into_cell('$print_Ave_UMA')
      print_result_into_cell('$print_Game_Count')
      </script>";
    }
    else{
      echo "<script>
      print_result_into_table('$row_num')
      print_result_into_table('$print_Name')
      print_result_into_table('$print_Ave_Score')
      print_result_into_table('$print_Sum_Score')
      print_result_into_table('$print_UMA')
      print_result_into_table('$print_Star')
      print_result_into_table('$print_Ave_UMA')
      print_result_into_table('$print_1st')
      print_result_into_table('$print_2nd')
      print_result_into_table('$print_3rd')
      print_result_into_table('$print_4th')
      print_result_into_table('$print_Game_Count')
      </script>";
    }
    //데스크탑 페이지 표시
    $row_num++;
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>
    마작기록사이트
  </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="http://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script type="text/javascript" src="fetch-2.0.2/fetch.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
  <link rel="stylesheet" href="table.css" />
  <script type="text/javascript" src="http://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" media="screen and (max-device-width: 768px)" href="media_query.css" />

</head>
<script>
var MAX_ROWS = 12;
var _index = 0;
var _rowcount = 1;

function getCookie(name){
  var wcname = name + '=';
  var wcstart, wcend, end;
  var i = 0;
  while(i <= document.cookie.length) {
    wcstart = i;
    wcend   = (i + wcname.length);
    if(document.cookie.substring(wcstart, wcend) == wcname) {
      if((end = document.cookie.indexOf(';', wcend)) == -1)
      end = document.cookie.length;
      return document.cookie.substring(wcend, end);
    }

    i = document.cookie.indexOf('', i) + 1;

    if(i == 0)
    break;
  }
  return '';
}//하루지났나 쿠키 재는 함수인데 어차피 호출할일 없으니 이대로 내비둔다



if(getCookie('record_site') != 'rangs') {
  var centerX = window.outerWidth/2;
  var centerY = window.outerHeight/2;
  window.open('pop_up_index.html','','width=380,height=300,top='+centerY+',left='+centerX);
}
//쿠키를 파악해서 팝업을 띄우는 함수 이제 팝업창은 필요음서 하지만 쓴다

function login_ok() {
  var input_id = document.getElementById('loginid').value;
  var input_pw = document.getElementById('loginpw').value;
  if (input_id == "" || input_pw == "") {
    alert("아이디 또는 비밀번호를 입력하십시오.");
    return;
  }
  else{
    document.getElementById('login_form').submit();
  }
}

function check_admin() {
  var pass = prompt("최고관리자 비밀번호를 입력");
  super_pw_frm.location.href ="./check_super_admin.php?admin_pass="+pass;
}

function change_form_logout2login(){
  var logdiv = document.getElementById('loginform');
  logdiv.style.visibility = "hidden";
  var logeddiv = document.getElementById('loginedform');
  logeddiv.style.visibility = "visible";
}

function change_form_login2logout(){
  var logdiv = document.getElementById("loginform");
  logdiv.style.visibility = "visible";
  var logeddiv = document.getElementById("loginedform");
  logeddiv.style.visibility = "hidden";
}


function make_init_table(){
  var theTable = document.getElementById('table_output');
  var thead = document.createElement('THEAD');
  var tbody = document.createElement('TBODY');
  thead.setAttribute('id','tb_head');
  tbody.setAttribute('id','tb_body');
  theTable.appendChild(thead);
  theTable.appendChild(tbody);
  if(_rowcount==1){
    var newTR1 = document.createElement('TR');
    newTR1.setAttribute('class','theadrow');
    var newtd1 = document.createElement('TH');
    newtd1.innerText = '순위';
    newTR1.appendChild(newtd1);
    var newtd2 = document.createElement('TH');
    newtd2.innerText = '이름';
    newTR1.appendChild(newtd2);
    var newtd3 = document.createElement('TH');
    newtd3.innerText = '평점';
    newTR1.appendChild(newtd3);
    var newtd4 = document.createElement('TH');
    newtd4.innerText = '전체점수';
    newTR1.appendChild(newtd4);
    var newtd5 = document.createElement('TH');
    newtd5.innerText = '우마';
    newTR1.appendChild(newtd5);
    var newtd6 = document.createElement('TH');
    newtd6.innerText = '별';
    newTR1.appendChild(newtd6);
    var newtd7 = document.createElement('TH');
    newtd7.innerText = '평우마';
    newTR1.appendChild(newtd7);
    var newtd8 = document.createElement('TH');
    newtd8.innerText = '1등';
    newTR1.appendChild(newtd8);
    var newtd9 = document.createElement('TH');
    newtd9.innerText = '2등';
    newTR1.appendChild(newtd9);
    var newtd10 = document.createElement('TH');
    newtd10.innerText = '3등';
    newTR1.appendChild(newtd10);
    var newtd11 = document.createElement('TH');
    newtd11.innerText = '4등';
    newTR1.appendChild(newtd11);
    var newtd12 = document.createElement('TH');
    newtd12.innerText = '대국 수';
    newTR1.appendChild(newtd12);
    thead.appendChild(newTR1);
    _rowcount++;
  }
}

function print_result_into_table(record_result){
  if(_index==0){
    var newTR = document.createElement('TR');
    newTR.setAttribute('class','table_row');
    newTR.setAttribute('id','table_row'+_rowcount);
    var the_tbody=document.getElementById('tb_body');
    the_tbody.appendChild(newTR);
  }
  var newTD = document.createElement('TD');
  newTD.innerText = record_result;
  var setTR = document.getElementById('table_row'+_rowcount);
  setTR.appendChild(newTD);
  _index++;
  if(_index==12){
    _rowcount++;
    _index=0;
  }
}


function print_result_into_cell(record_result){
  var newDiv=document.createElement('DIV'); // DIV 객체 생성
  // if(_index==1){
  //   newDiv.setAttribute('class','divTableCell_name'); // class 지정
  //   newDiv.onclick=function(){
  //     window.open("Rating_chart.php?NAME="+record_result,"_blank","width=900, height=600");
  //   }
  // }
  // else{
  //   newDiv.setAttribute('class','divTableCell'); // class 지정
  // }
  newDiv.setAttribute('class','divTableCell');
  newDiv.innerHTML=record_result; // 객체에 포함할 텍스트
  //모바일 웹에서 innerhtml이 안되는듯 한데
  document.getElementsByClassName('divTableRow')[_rowcount].appendChild(newDiv); // row의 자식 노드로 첨부 (필수)
  _index ++;
  if(_index==4){
    _index=0;
    var newRowDiv=document.createElement('DIV'); // DIV 객체 생성
    newRowDiv.setAttribute('class','divTableRow'); // id 지정
    document.getElementsByClassName('divTableBody')[0].appendChild(newRowDiv);
    _rowcount++;
  }
}

function hide_not_imp(){
  var x = document.getElementsByClassName('divTableHead');
  for(var i=0;i<x.length;i++){
    x[i].style.display="none";
  }
  var y = document.getElementsByClassName('divTableCell');
  for(var i=0;i<y.length;i++){
    y[i].style.display="none";
  }
}

function join_room(){
  var room_no = prompt("방번호 입력");
  if(room_no>1000&&room_no<10000){
    window.open("http://tenhou.net/0/?"+room_no,"_blank","width=750,height=650,top=300,left=500");
  }
  else{
    alert("1000이상, 10000이하의 숫자 입력");
  }
}

function which_trash_page(date){
  window.open('which_trash_page.php?which_trash_date='+date,'_blank','width=600, height=400');
}
</script>
<body>
  <h1><a class="title" href="index.php">MADE_BY_LYG</a></h1>
  <h2>메인 페이지</h2>
  <hr>
  <div class="which_trash_div">
      무엇을 버릴까요?
      <input type="date" name="which_trash_date" id="which_trash_date" onclick="none"/>
      <input type="button" name="which_trash_date_btn" value="문제 보기" onclick="which_trash_page(document.getElementById('which_trash_date').value)">
      <input type="button" name="which_trash_close_btn" value="닫기" onclick="document.getElementsByClassName('which_trash_div')[0].style.display='none'">
  </div>
  <div class="top_menu">
    <div class="top_menu_btn_div">
      <div class="top_menu_child" onclick="window.open('notice_page.php','_blank','width=600px,height=800px')">
        공지사항
      </div>
      <div class="top_menu_child">
        룰 설명
      </div>
    </div>
  </div>
  <div class="grid1">
    <div class="adminlogin">
      <div class="loginedform" id="loginedform">
        <div class="login_manager_text_div">
          <?php
          if(isset($_SESSION['is_login'])==true){
            echo $_SESSION['admin_name'] . " 관리자님 로그인 되었습니다";
          }
          ?>
          <br>
        </div>
        <div class="wrapping_index_btn">
          <input type="button" name="score_reg" value="관리자메뉴" onclick="window.location.href='admin_page.php'">
          <input type="button" name="logout" value="로그아웃" onclick="location.href='logout.php'">
        </div>
        <hr>
      </div>
      <div class="loginform" id="loginform">
        <div class="admin_login_is_here_div">
          관리자 로그인
        </div>
        <form action="validate_login_page.php" accept-charset="utf-8" id="login_form" method="POST">
          <input type="text" placeholder="관리자 ID 입력" id="loginid" name="loginid"><br class="PC_only">
          <input type="password" id="loginpw" name="loginpw"><br>
          <input type="button" value="&nbsp&nbsp&nbsp 로그인 &nbsp&nbsp" name="loginok" onclick="login_ok()">
          <input type="button" value="관리자등록" name="reg_admin" onclick="check_admin()">
        </form>
        <hr>
      </div>
      <div class="sidemenu">
        <ul id="sideul">

        </ul>
      </div>
    </div>

    <div class="main_content">
      <table id="table_output">
      </table>
      <div class="divTable blueTable">
        <div class="divTableHeading">
          <div class="divTableRow">
            <div class="divTableHead">순위</div>
            <div class="divTableHead">이름</div>
            <div class="divTableHead">평우마</div>
            <div class="divTableHead">대국 수</div>
          </div>
        </div>
        <div class="divTableBody">
          <div class="divTableRow">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <nav>
      <ul id="footul">
      </ul>
    </nav>
  </div>

  <iframe id="super_pw_frm" scrolling="no" frameborder="no" width="0" height="0" name="super_pw_frm"></iframe>
  <script>
  fetch('footlist').then(function(response){
    response.text().then(function(text){
      document.querySelector('#footul').innerHTML = text;
    })
  });
  var filter = "win16|win32|win64|mac|macintel";
  if(navigator.platform){//모바일기기
    if(filter.indexOf(navigator.platform.toLowerCase()) < 0){
      fetch('mobileside').then(function(response){
        response.text().then(function(text){
          document.querySelector('#sideul').innerHTML = text;
        })
      });
      document.getElementById('table_output_wrapper').style.display="none";
    } else{//웹
      fetch('sidelist').then(function(response){
        response.text().then(function(text){
          document.querySelector('#sideul').innerHTML = text;
        })
      });
      make_init_table();
      var x = document.getElementsByClassName('blueTable')[0];
      x.style.display="none";
      $(document).ready( function () {
        $('#table_output').DataTable();
      } );
    }
  }
  var today = new Date();
  document.getElementById('which_trash_date').valueAsDate=today;
</script>
<?php
is_logined();
show_ranking();
?>
</body>

</html>
