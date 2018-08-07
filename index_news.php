<?php require_once('Connections/connection.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$maxRows_rs_news = 6;
$pageNum_rs_news = 0;
if (isset($_GET['pageNum_rs_news'])) {
  $pageNum_rs_news = $_GET['pageNum_rs_news'];
}
$startRow_rs_news = $pageNum_rs_news * $maxRows_rs_news;

mysql_select_db($database_connection, $connection);
$query_rs_news = "SELECT * FROM tb_news WHERE status = 'Y' ORDER BY News_id DESC";
$query_limit_rs_news = sprintf("%s LIMIT %d, %d", $query_rs_news, $startRow_rs_news, $maxRows_rs_news);
$rs_news = mysql_query($query_limit_rs_news, $connection) or die(mysql_error());
$row_rs_news = mysql_fetch_assoc($rs_news);

if (isset($_GET['totalRows_rs_news'])) {
  $totalRows_rs_news = $_GET['totalRows_rs_news'];
} else {
  $all_rs_news = mysql_query($query_rs_news);
  $totalRows_rs_news = mysql_num_rows($all_rs_news);
}
$totalPages_rs_news = ceil($totalRows_rs_news/$maxRows_rs_news)-1;
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
 img{
	 padding:10px;
 }

</style>


<div class="container">
	<div class="row">
  <?php do { ?>
  <div class="col-md-6"> <a href="detail_news.php?News_id=<?php echo $row_rs_news['News_id']; ?>#detailnews"><img src="admin/file_news/<?php echo $row_rs_news['File_news']; ?>" width="170" height="106" style="float:left"></a><br />
  
  
  <p align="left">   <?php echo $row_rs_news['Title']; ?><br />
<a href="detail_news.php?News_id=<?php echo $row_rs_news['News_id']; ?>#detailnews" target="_blank" class="btn btn-info btn-xs"> รายละเอียดเพิ่มเติม </a></p>
 
  
  </div>
  <?php } while ($row_rs_news = mysql_fetch_assoc($rs_news)); ?>
  
 </div>
</div>
<?php
mysql_free_result($rs_news);
?>
