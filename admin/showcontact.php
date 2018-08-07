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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_links")) {
  $insertSQL = sprintf("INSERT INTO tb_links (Wat_name, Website, stutus, Addby) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['Wat_name'], "text"),
                       GetSQLValueString($_POST['Website'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['Addby'], "text"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  $insertGoTo = "add_links.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tb_links SET stutus=%s WHERE id_links=%s",
                       GetSQLValueString($_POST['noshow'], "text"),
                       GetSQLValueString($_POST['id_links'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "add_links.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE tb_links SET stutus=%s WHERE id_links=%s",
                       GetSQLValueString($_POST['shows'], "text"),
                       GetSQLValueString($_POST['id_links'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "add_links.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_member = 10;
$pageNum_member = 0;
if (isset($_GET['pageNum_member'])) {
  $pageNum_member = $_GET['pageNum_member'];
}
$startRow_member = $pageNum_member * $maxRows_member;

$colname_member = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_member = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_member = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_member, "text"));
$query_limit_member = sprintf("%s LIMIT %d, %d", $query_member, $startRow_member, $maxRows_member);
$member = mysql_query($query_limit_member, $connection) or die(mysql_error());
$row_member = mysql_fetch_assoc($member);

if (isset($_GET['totalRows_member'])) {
  $totalRows_member = $_GET['totalRows_member'];
} else {
  $all_member = mysql_query($query_member);
  $totalRows_member = mysql_num_rows($all_member);
}
$totalPages_member = ceil($totalRows_member/$maxRows_member)-1;

$maxRows_contact = 10;
$pageNum_contact = 0;
if (isset($_GET['pageNum_contact'])) {
  $pageNum_contact = $_GET['pageNum_contact'];
}
$startRow_contact = $pageNum_contact * $maxRows_contact;

mysql_select_db($database_connection, $connection);
$query_contact = "SELECT * FROM tb_contact ORDER BY id_contact DESC";
$query_limit_contact = sprintf("%s LIMIT %d, %d", $query_contact, $startRow_contact, $maxRows_contact);
$contact = mysql_query($query_limit_contact, $connection) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);

if (isset($_GET['totalRows_contact'])) {
  $totalRows_contact = $_GET['totalRows_contact'];
} else {
  $all_contact = mysql_query($query_contact);
  $totalRows_contact = mysql_num_rows($all_contact);
}
$totalPages_contact = ceil($totalRows_contact/$maxRows_contact)-1;

$queryString_contact = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_contact") == false && 
        stristr($param, "totalRows_contact") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_contact = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_contact = sprintf("&totalRows_contact=%d%s", $totalRows_contact, $queryString_contact);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Go 2 wat</title>

<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/wat.css" rel="stylesheet" />
<style type="text/css">
input {
	margin: 5px;
}
</style>

<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/jquery.form.min.js"> </script>
</head>




<body>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h4 align="center">hi :&nbsp; <?php echo $row_member['Fname']; ?>&nbsp;<a href="adminpage.php">กลับเมนูหลัก</a>&nbsp; <a href="<?php echo $logoutAction ?>">Log out</a>&nbsp; &nbsp;<br>
      </h4>
      <br>
      <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="40" colspan="4" align="center" bgcolor="#B8B8B8"><h4>&nbsp;</h4>
แสดงรายการที่&nbsp; <?php echo ($startRow_contact + 1) ?> ถึงรายการที่&nbsp; <?php echo min($startRow_contact + $maxRows_contact, $totalRows_contact) ?>&nbsp; รวม&nbsp; <?php echo $totalRows_contact ?> &nbsp;รายการ <br>
<p align="center">&nbsp;</p>


</td>
        </tr>
        <tr>
          <td width="12%" height="40" align="center" bgcolor="#B8B8B8"><strong>id</strong></td>
          <td width="59%" height="40" align="center" bgcolor="#B8B8B8"><strong>รายละเอียด</strong></td>
          <td width="20%" align="center" bgcolor="#B8B8B8"><strong>ว/ด/ป ที่แจ้ง</strong></td>
          <td width="9%" height="40" align="center" bgcolor="#B8B8B8"><strong>addby</strong></td>
        </tr>
        <?php do { ?>
          <tr>
            <td align="center" bgcolor="#F0F0F0"><?php echo $row_contact['id_contact']; ?></td>
            <td height="30" align="left" bgcolor="#F0F0F0"><?php echo $row_contact['detail']; ?></td>
            <td align="center" bgcolor="#F0F0F0"><?php echo $row_contact['DateAdd']; ?></td>
            <td align="center" bgcolor="#F0F0F0"><?php echo $row_contact['addby']; ?></td>
          </tr>
          <?php } while ($row_contact = mysql_fetch_assoc($contact)); ?>
      </table>
      <br>
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="add_links" onsubmit="passvalidate()" id="add_links">
      </form>
      <h4 align="center">&nbsp; </h4>
    </div>
  </div>
</div>
</div>

<!-- Footer -->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-12">
      <?php include ("../footer.php"); ?>
      </div>
    </div>
  </div>
</div>
<!-- end Footer --> 

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="../js/jquery-1.11.2.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php
mysql_free_result($member);

mysql_free_result($contact);
?>
