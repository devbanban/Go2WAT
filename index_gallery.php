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

$maxRows_rs_gallery = 8;
$pageNum_rs_gallery = 0;
if (isset($_GET['pageNum_rs_gallery'])) {
  $pageNum_rs_gallery = $_GET['pageNum_rs_gallery'];
}
$startRow_rs_gallery = $pageNum_rs_gallery * $maxRows_rs_gallery;

mysql_select_db($database_connection, $connection);
$query_rs_gallery = "SELECT * FROM tb_gallery WHERE status = 'Y' ORDER BY id_gall DESC";
$query_limit_rs_gallery = sprintf("%s LIMIT %d, %d", $query_rs_gallery, $startRow_rs_gallery, $maxRows_rs_gallery);
$rs_gallery = mysql_query($query_limit_rs_gallery, $connection) or die(mysql_error());
$row_rs_gallery = mysql_fetch_assoc($rs_gallery);

if (isset($_GET['totalRows_rs_gallery'])) {
  $totalRows_rs_gallery = $_GET['totalRows_rs_gallery'];
} else {
  $all_rs_gallery = mysql_query($query_rs_gallery);
  $totalRows_rs_gallery = mysql_num_rows($all_rs_gallery);
}
$totalPages_rs_gallery = ceil($totalRows_rs_gallery/$maxRows_rs_gallery)-1;
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">


<div class="container">
	<div class="row">
  <?php do { ?>
  <div class="col-md-3">
  <p align="center"> <a href="detail_gallery.php?id_gall=<?php echo $row_rs_gallery['id_gall']; ?>#gall"><img src="admin/gallery/<?php echo $row_rs_gallery['Img_index']; ?>" width="300" height="200"  title="<?php echo $row_rs_gallery['Title']; ?>"></a></p>
  
  </div>
  <?php } while ($row_rs_gallery = mysql_fetch_assoc($rs_gallery)); ?>
  
 </div>
</div>
<?php
mysql_free_result($rs_gallery);
?>
