<?php
//데이터 베이스 연결하기
require "db_connect.php";

$page_size=10;

$page_list_size = 10;
if(isset($_GET['no'])== true)$no = $_GET['no'];
else{
  $no = 0;
}

$query = "SELECT * FROM notice ORDER BY notice_id DESC LIMIT $no, $page_size";
$result = mysqli_query($conn,$query);


// 총 게시물 수 를 구한다.
$result_count=mysqli_query($conn, "SELECT count(*) FROM notice");
$result_row=mysqli_fetch_row($result_count);
$total_row = $result_row[0];



if ($total_row <= 0) $total_row = 0;
$total_page = ceil($total_row / $page_size);//1개면

$current_page = ceil(($no+1)/$page_size);
?>
<html>
<head>
<title>공지사항 </title>
<link rel="stylesheet" href="notice_style.css">
</head>
<script>
  var logined = false;
</script>
<body topmargin=0 leftmargin=0 text=#464646>
<center>
<BR>

<h1>공지사항</h1>
<BR>
<BR>

<table class="notice_table">

<tr height=20 bgcolor=#999999>
    <td width=30 align=center>
        <font color=white>번호</font>
    </td>
    <td width=370 align=center>
        <font color=white>제 목</font>
    </td>
    <td width=60 align=center>
        <font color=white>날 짜</font>
    </td>
</tr>
<?php
while($row=mysqli_fetch_array($result)){

?>
<tr>
    <td height=20 bgcolor=white align=center>
        <a href="notice_read.php?id=<?=$row['notice_id']?>&no=<?=$no?>">
        <?=$row['notice_id']?></a>
    </td>

    <td height=20 bgcolor=white>&nbsp;
        <a href="notice_read.php?id=<?=$row['notice_id']?>&no=<?=$no?>">
        <?=strip_tags($row['notice_title'], '<b><i>');?></a>
    </td>

    <td align=center height=20 bgcolor=white>
        <font color=black><?=$row['notice_date']?></font>
    </td>

</tr>
<?php
}
mysqli_close($conn);
?>
</table>

<table border=0>
<tr>
    <td width=600 height=20 align=center rowspan=4>
    <font color=gray>
    &nbsp;
<?php
$start_page = floor(($current_page - 1) / $page_list_size) * $page_list_size + 1;
$end_page = $start_page + $page_list_size - 1;
if ($total_page <$end_page) $end_page = $total_page;
if ($start_page >= $page_list_size) {
    $prev_list = ($start_page - 2)*$page_size;
    $PHP_SELF = $_SERVER['PHP_SELF'];
    echo "<a href=$PHP_SELF?no=$prev_list>◀</a> ";
}
for ($i=$start_page;$i <= $end_page;$i++) {
    $page= ($i-1) * $page_size;
    if ($no!=$page){
      $PHP_SELF = $_SERVER['PHP_SELF'];
        echo "<a href=$PHP_SELF?no=$page>";
    }

    echo " $i ";

    if ($no!=$page){
        echo "</a>";
    }
}
if($total_page >$end_page){
    $next_list = $end_page * $page_size;
    echo "<a href='$PHP_SELF'.'?no='.'$next_list'>▶</a><p>";
}
?>
    </font>
    </td>
</tr>
</table>
<input type="button" name="write_btn" value="글쓰기" onclick="window.open('notice_write.php','_self')">
</center>
</body>
</html>
