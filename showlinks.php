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

$maxRows_showlinks = 10;
$pageNum_showlinks = 0;
if (isset($_GET['pageNum_showlinks'])) {
  $pageNum_showlinks = $_GET['pageNum_showlinks'];
}
$startRow_showlinks = $pageNum_showlinks * $maxRows_showlinks;

mysql_select_db($database_connection, $connection);
$query_showlinks = "SELECT * FROM tb_links WHERE stutus = 'Y' ORDER BY id_links DESC";
$query_limit_showlinks = sprintf("%s LIMIT %d, %d", $query_showlinks, $startRow_showlinks, $maxRows_showlinks);
$showlinks = mysql_query($query_limit_showlinks, $connection) or die(mysql_error());
$row_showlinks = mysql_fetch_assoc($showlinks);

if (isset($_GET['totalRows_showlinks'])) {
  $totalRows_showlinks = $_GET['totalRows_showlinks'];
} else {
  $all_showlinks = mysql_query($query_showlinks);
  $totalRows_showlinks = mysql_num_rows($all_showlinks);
}
$totalPages_showlinks = ceil($totalRows_showlinks/$maxRows_showlinks)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<p>showlinks</p>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>id_links</td>
    <td>Wat_name</td>
    <td>Website</td>
    <td>DateAdd</td>
    <td>stutus</td>
    <td>Addby</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_showlinks['id_links']; ?></td>
      <td><?php echo $row_showlinks['Wat_name']; ?></td>
      <td><?php echo $row_showlinks['Website']; ?></td>
      <td><?php echo $row_showlinks['DateAdd']; ?></td>
      <td><?php echo $row_showlinks['stutus']; ?></td>
      <td><?php echo $row_showlinks['Addby']; ?></td>
    </tr>
    <?php } while ($row_showlinks = mysql_fetch_assoc($showlinks)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($showlinks);
?>
