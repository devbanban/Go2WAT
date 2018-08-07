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

mysql_select_db($database_connection, $connection);
$query_rs_post = "SELECT * FROM tb_post WHERE status = 'Y' ORDER BY Post_id DESC";
$rs_post = mysql_query($query_rs_post, $connection) or die(mysql_error());
$row_rs_post = mysql_fetch_assoc($rs_post);
$totalRows_rs_post = mysql_num_rows($rs_post);
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
 img{
	 padding-bottom:10px;
 }
.glyphicon{
	color:#ccc;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>


<div class="container">
	<div class="row">
  <?php do { ?>
  <div class="col-md-3" style="text-align:center"> 
  <a href="detail_post.php?Post_id=<?php echo $row_rs_post['Post_id']; ?>#post">
  <img src="admin/post/<?php echo $row_rs_post['Img_index']; ?>#post" width="300" height="200" title="<?php echo $row_rs_post['Title']; ?> ">
  </a><br />
   <a href="detail_post.php?Post_id=<?php echo $row_rs_post['Post_id']; ?>#post"><?php echo $row_rs_post['Title']; ?></a> <br />
   	<span class="glyphicon glyphicon-user"></span><?php echo $row_rs_post['Addby']; ?>  &nbsp;
   	<span class="glyphicon glyphicon-calendar"></span><?php echo date('d-m-Y', strtotime($row_rs_post['DateAdd'])); ?>  &nbsp;
	<span class="glyphicon glyphicon-eye-open"></span><?php echo $row_rs_post['view']; ?>
    <br /><br />
  
 
 
  
  </div>
  <?php } while ($row_rs_post = mysql_fetch_assoc($rs_post)); ?>
  
 </div>
</div>
<?php
mysql_free_result($rs_post);
?>
