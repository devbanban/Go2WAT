<?php require_once('../Connections/connection.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "A";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$currentPage = $_SERVER["PHP_SELF"];

$colname_mem = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mem = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_mem = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_mem, "text"));
$mem = mysql_query($query_mem, $connection) or die(mysql_error());
$row_mem = mysql_fetch_assoc($mem);
$totalRows_mem = mysql_num_rows($mem);

$maxRows_listnews = 10;
$pageNum_listnews = 0;
if (isset($_GET['pageNum_listnews'])) {
  $pageNum_listnews = $_GET['pageNum_listnews'];
}
$startRow_listnews = $pageNum_listnews * $maxRows_listnews;

$colname_listnews = "-1";
if (isset($_GET['News_id'])) {
  $colname_listnews = $_GET['News_id'];
}
mysql_select_db($database_connection, $connection);
$query_listnews = sprintf("SELECT * FROM tb_news WHERE News_id = %s", GetSQLValueString($colname_listnews, "int"));
$query_limit_listnews = sprintf("%s LIMIT %d, %d", $query_listnews, $startRow_listnews, $maxRows_listnews);
$listnews = mysql_query($query_limit_listnews, $connection) or die(mysql_error());
$row_listnews = mysql_fetch_assoc($listnews);

if (isset($_GET['totalRows_listnews'])) {
  $totalRows_listnews = $_GET['totalRows_listnews'];
} else {
  $all_listnews = mysql_query($query_listnews);
  $totalRows_listnews = mysql_num_rows($all_listnews);
}
$totalPages_listnews = ceil($totalRows_listnews/$maxRows_listnews)-1;

$queryString_listnews = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_listnews") == false && 
        stristr($param, "totalRows_listnews") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_listnews = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_listnews = sprintf("&totalRows_listnews=%d%s", $totalRows_listnews, $queryString_listnews);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Go 2 wat </title>

    <!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/wat.css" rel="stylesheet" />
<script type="text/javascript" src="ckeditor/ckeditor.js"></script> <!--สร้าง ckeditor-->
<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/jquery.form.min.js"> </script>
<script>
$(function() {
	$(document).on('change', '#File_news', function() {
		if(this.files[0].size > 307200) {
			alert('ไฟล์ภาพข่าวมีขนาดใหญ่เกินกำหนด (300 KB) กรุณา Resize รูปภาพก่อนทำการ Upload ขอบคุณครับ');
			//$(this).replaceWith($(this).clone());
			$('input:file').clearInputs(); 
		}
	});
});
</script>

<style type="text/css">
body{
	background-color:#f4f4f4;
}
.row{
	background-color:#fff;
}

</style>


  </head>
  
  
  
  <body>
  
  <div class="container">
  	<div class="row">
     	<div class="col-md-12">
          <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp; <a href="adminpage.php">กลับเมนูหลัก</a>&nbsp;<a href="add_news.php">กลับหน้าเพิ่มข่าว</a>&nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
            <strong><br>
            แสดงตัวอย่าง</strong><br>
            <img src="file_news/<?php echo $row_listnews['File_news']; ?>" width="400" height="100"><br>
          </h4>
          <br>
          <strong>หัวข้อข่าว</strong> : <?php echo $row_listnews['Title']; ?><br>
          
          <strong>รายละเอียด </strong>:<?php echo $row_listnews['Detail']; ?><br>
          
          <strong>สถานที่จัดงาน</strong> : <?php echo $row_listnews['Location']; ?>&nbsp; <br>
          
           <strong>addby</strong>:<?php echo $row_listnews['Addby']; ?>
           &nbsp;&nbsp;<strong>date_add</strong>: <?php echo $row_listnews['DateAdd']; ?>
           &nbsp;&nbsp; <strong>status</strong>:<?php echo $row_listnews['status']; ?> 
           &nbsp; &nbsp;<strong>view</strong>: <?php echo $row_listnews['view']; ?><br>
           
           
           
           <p style="padding-bottom:200px"></p>
           
           
           
           
           
 			</div>
        </div>
 </div>
 
 
 
 
 <script src="../js/bootstrap.min.js"></script> 
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
 <script src="../js/jquery-1.11.2.min.js"></script>
 <!-- Include all compiled plugins (below), or include individual files as needed -->


  </body>
</html>
<?php
mysql_free_result($mem);

mysql_free_result($listnews);
?>
