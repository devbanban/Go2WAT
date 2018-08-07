<?php require_once('../Connections/connection.php'); ?>
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

$colname_show_gall = "-1";
if (isset($_GET['id_gall'])) {
  $colname_show_gall = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_show_gall = sprintf("SELECT * FROM tb_gallery WHERE id_gall = %s", GetSQLValueString($colname_show_gall, "int"));
$show_gall = mysql_query($query_show_gall, $connection) or die(mysql_error());
$row_show_gall = mysql_fetch_assoc($show_gall);
$totalRows_show_gall = mysql_num_rows($show_gall);
?>

<meta charset="UTF-8">
<?php
include("../Connections/connection.php");

$id_gall_img = $_REQUEST["id_gall_img"];

$sql = "DELETE FROM tb_gallery_img WHERE id_gall_img='$id_gall_img' ";
$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql " . mysql_error());

mysql_close($connection);

if($result){
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลเรียบร้อยแล้ว');";
	echo "window.location = 'add_gallery_img.php?id_gall=[$id_gall_img'];' ";

	
	
	
	
	
	
	echo "</script>";
}
else{
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลไม่สำเร็จ');";
	echo "</script>";
}
?>
<?php
mysql_free_result($show_gall);
?>
