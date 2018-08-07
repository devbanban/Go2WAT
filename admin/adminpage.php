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

$colname_member = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_member = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_member = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_member, "text"));
$member = mysql_query($query_member, $connection) or die(mysql_error());
$row_member = mysql_fetch_assoc($member);
$totalRows_member = mysql_num_rows($member);

mysql_select_db($database_connection, $connection);
$query_admin = "SELECT * FROM tb_member WHERE AccessLevel = 'A' ORDER BY Member_id ASC";
$admin = mysql_query($query_admin, $connection) or die(mysql_error());
$row_admin = mysql_fetch_assoc($admin);
$totalRows_admin = mysql_num_rows($admin);

mysql_select_db($database_connection, $connection);
$query_totolmem = "SELECT * FROM tb_member WHERE AccessLevel = 'M' ORDER BY Member_id DESC";
$totolmem = mysql_query($query_totolmem, $connection) or die(mysql_error());
$row_totolmem = mysql_fetch_assoc($totolmem);
$totalRows_totolmem = mysql_num_rows($totolmem);

mysql_select_db($database_connection, $connection);
$query_news = "SELECT * FROM tb_news";
$news = mysql_query($query_news, $connection) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);

mysql_select_db($database_connection, $connection);
$query_post = "SELECT * FROM tb_post";
$post = mysql_query($query_post, $connection) or die(mysql_error());
$row_post = mysql_fetch_assoc($post);
$totalRows_post = mysql_num_rows($post);

mysql_select_db($database_connection, $connection);
$query_gall = "SELECT * FROM tb_gallery";
$gall = mysql_query($query_gall, $connection) or die(mysql_error());
$row_gall = mysql_fetch_assoc($gall);
$totalRows_gall = mysql_num_rows($gall);

mysql_select_db($database_connection, $connection);
$query_links = "SELECT * FROM tb_links";
$links = mysql_query($query_links, $connection) or die(mysql_error());
$row_links = mysql_fetch_assoc($links);
$totalRows_links = mysql_num_rows($links);
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
  </head>
  <body>
  <div class="container">
  	<div class="row">

        	<div class="col-md-3">
            <br>
                <h4><span class="glyphicon glyphicon-user" aria-hidden="true"></span> สวัสดีคุณ <?php echo $row_member['Fname']; ?></h4>
                  <a href="edit_member.php?Member_id=<?php echo $row_member['Member_id']; ?>"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> แก้ไขข้อมูลของคุณ </a><br>
                    <p> <a href="<?php echo $logoutAction ?>">&nbsp;Log out</a> <br>
                      <br>
                      <br>
                      <br>
                    </p>
      

          </div>
            
      <div class="col-md-9">    
        <div class="list-group">
          
          <li class="list-group-item active"> Menu&nbsp; For Administrator</li>
                <a href="add_admin.php" class="list-group-item"> + ผู้ดูแลระบบ (<?php echo $totalRows_admin ?>)</a>
                <a href="manage_member.php" class="list-group-item"> + จัดการสมาชิก (<?php echo $totalRows_totolmem ?> )</a>
                <a href="add_news.php" class="list-group-item"> + จัดการข่าว (<?php echo $totalRows_news ?>)</a>
                <a href="add_post.php" class="list-group-item"> + จัดการกระทู้ (<?php echo $totalRows_post ?>)</a>
                <a href="add_gallery.php" class="list-group-item"> + จัดการแกลเลอรี่ (<?php echo $totalRows_gall ?> )</a>
                <a href="add_links.php" class="list-group-item"> + เพิ่มลิงค์ (<?php echo $totalRows_links ?> )</a>
                <a href="showcontact.php" class="list-group-item"> + รายการแจ้งปัญหาการใช้งาน</a>

        </div>
      </div>
    </div>
 </div> 
  
  
   
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
 <script src="../js/jquery-1.11.2.min.js"></script>
 <!-- Include all compiled plugins (below), or include individual files as needed --> <script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php
mysql_free_result($member);

mysql_free_result($admin);

mysql_free_result($totolmem);

mysql_free_result($news);

mysql_free_result($post);

mysql_free_result($gall);

mysql_free_result($links);
?>
