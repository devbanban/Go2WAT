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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ban")) {
  $updateSQL = sprintf("UPDATE tb_member SET AccessLevel=%s WHERE Member_id=%s",
                       GetSQLValueString($_POST['AccessLevel'], "text"),
                       GetSQLValueString($_POST['Member_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "manage_member.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_showmember = 10;
$pageNum_showmember = 0;
if (isset($_GET['pageNum_showmember'])) {
  $pageNum_showmember = $_GET['pageNum_showmember'];
}
$startRow_showmember = $pageNum_showmember * $maxRows_showmember;

mysql_select_db($database_connection, $connection);
$query_showmember = "SELECT * FROM tb_member WHERE AccessLevel = 'M' ORDER BY Member_id DESC";
$query_limit_showmember = sprintf("%s LIMIT %d, %d", $query_showmember, $startRow_showmember, $maxRows_showmember);
$showmember = mysql_query($query_limit_showmember, $connection) or die(mysql_error());
$row_showmember = mysql_fetch_assoc($showmember);

if (isset($_GET['totalRows_showmember'])) {
  $totalRows_showmember = $_GET['totalRows_showmember'];
} else {
  $all_showmember = mysql_query($query_showmember);
  $totalRows_showmember = mysql_num_rows($all_showmember);
}
$totalPages_showmember = ceil($totalRows_showmember/$maxRows_showmember)-1;

$colname_mem = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mem = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_mem = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_mem, "text"));
$mem = mysql_query($query_mem, $connection) or die(mysql_error());
$row_mem = mysql_fetch_assoc($mem);
$totalRows_mem = mysql_num_rows($mem);

$queryString_showmember = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_showmember") == false && 
        stristr($param, "totalRows_showmember") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_showmember = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_showmember = sprintf("&totalRows_showmember=%d%s", $totalRows_showmember, $queryString_showmember);
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
    <h4 align="center">
    hi: <?php echo $row_mem['Fname']; ?>&nbsp;<a href="adminpage.php">กลับเมนูหลัก</a>&nbsp;<a href="manage_member_ban.php">สมาชิกที่โดนแบน</a>&nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a></h4>
    
     <div class="col-md-6"> </div>
     
     

    
    
    
    	<div class="col-md-12">
        
        
          <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="40" colspan="11" align="center" bgcolor="#EBEBEB"><h4> บริหารจัดการสมาชิก </h4>                
                แสดงรายการที่&nbsp; &nbsp;<?php echo ($startRow_showmember + 1) ?> ถึงรายการที่&nbsp; <?php echo min($startRow_showmember + $maxRows_showmember, $totalRows_showmember) ?>&nbsp; รวม
              &nbsp; &nbsp;<?php echo $totalRows_showmember ?> &nbsp;&nbsp; &nbsp;รายการ&nbsp;
              <table border="0">
                <tr>
                  <td><?php if ($pageNum_showmember > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, 0, $queryString_showmember); ?>"><img src="First.gif"></a>
                  <?php } // Show if not first page ?></td>
                  <td><?php if ($pageNum_showmember > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, max(0, $pageNum_showmember - 1), $queryString_showmember); ?>"><img src="Previous.gif"></a>
                  <?php } // Show if not first page ?></td>
                  <td><?php if ($pageNum_showmember < $totalPages_showmember) { // Show if not last page ?>
                      <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, min($totalPages_showmember, $pageNum_showmember + 1), $queryString_showmember); ?>"><img src="Next.gif"></a>
                  <?php } // Show if not last page ?></td>
                  <td><?php if ($pageNum_showmember < $totalPages_showmember) { // Show if not last page ?>
                      <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, $totalPages_showmember, $queryString_showmember); ?>"><img src="Last.gif"></a>
                  <?php } // Show if not last page ?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="3%" align="center" bgcolor="#EBEBEB"><strong>img profile</strong></td>
              <td align="center" bgcolor="#EBEBEB"><strong>Username</strong></td>
              <td align="center" bgcolor="#EBEBEB"><strong>Fname</strong></td>
              <td height="40" align="center" bgcolor="#EBEBEB"><strong>Lname</strong></td>
              <td align="center" bgcolor="#EBEBEB"><strong>Email</strong></td>
              <td align="center" bgcolor="#EBEBEB"><strong>Phone</strong></td>
              <td align="center" bgcolor="#EBEBEB"><strong>type</strong></td>
              <td align="center" bgcolor="#EBEBEB"><strong>Regis_date</strong></td>
              <td colspan="3" align="center" bgcolor="#D0D0D0"><strong>Manage</strong></td>
            </tr>
            <?php do { ?>
              <tr>
                <td><img src="../formember/mimg/<?php echo $row_showmember['img']; ?>" width="70"></td>
                <td align="left" valign="top"><?php echo $row_showmember['Username']; ?></td>
                <td align="left" valign="top"><?php echo $row_showmember['Fname']; ?></td>
                <td align="left" valign="top"><?php echo $row_showmember['Lname']; ?></td>
                <td align="left" valign="top"><?php echo $row_showmember['Email']; ?></td>
                <td align="left" valign="top"><?php echo $row_showmember['Phone']; ?></td>
                <td align="center" valign="top"><b><font color="blue"><?php echo $row_showmember['AccessLevel']; ?> </font></b></td>
                <td align="left" valign="top"><?php echo $row_showmember['Regis_date']; ?></td>
                <td width="5%" align="center"><form action="<?php echo $editFormAction; ?>" method="POST" name="ban" id="ban">
                  <input name="Member_id" type="hidden" id="Member_id" value="<?php echo $row_showmember['Member_id']; ?>">
                  <input name="AccessLevel" type="hidden" id="AccessLevel" value="B">
                  <input type="submit" name="BAN" id="BAN" value="BAN" class="btn btn-danger btn-xs" onclick="return confirm('คุณต้องการแบนหรือไม่ ')">
<br>
<input type="hidden" name="MM_update" value="ban">
                </form></td>
                <td align="center"><a href="edit_member.php?Member_id=<?php echo $row_showmember['Member_id']; ?>" class="btn btn-info btn-xs">Edit</a></td>
                <td align="center">
                <a href="del_member.php?Member_id=<?php echo $row_showmember['Member_id']; ?>" 
                onclick="return confirm('คุณต้องการลบข้อมูล <?php echo $row_showmember['Fname']; ?> หรือไม่ ')" class="btn btn-warning btn-xs">del</a>
                </td>
              </tr>
              <?php } while ($row_showmember = mysql_fetch_assoc($showmember)); ?>
          </table>
          <br>
          <table border="0" align="center">
            <tr>
              <td><?php if ($pageNum_showmember > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, 0, $queryString_showmember); ?>"><img src="First.gif"></a>
                <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_showmember > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, max(0, $pageNum_showmember - 1), $queryString_showmember); ?>"><img src="Previous.gif"></a>
                <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_showmember < $totalPages_showmember) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, min($totalPages_showmember, $pageNum_showmember + 1), $queryString_showmember); ?>"><img src="Next.gif"></a>
                <?php } // Show if not last page ?></td>
              <td><?php if ($pageNum_showmember < $totalPages_showmember) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_showmember=%d%s", $currentPage, $totalPages_showmember, $queryString_showmember); ?>"><img src="Last.gif"></a>
                <?php } // Show if not last page ?></td>
            </tr>
          </table>
          <p align="center">&nbsp;</p>        
                
      </div>
        
        
    </div>
 </div> 
  
  
   
   
   
   
   
   
   
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
 <script src="../js/jquery-1.11.2.min.js"></script>
 <!-- Include all compiled plugins (below), or include individual files as needed --> <script src="../js/bootstrap.min.js"></script> 

  </body>
</html>
<?php
mysql_free_result($showmember);

mysql_free_result($mem);
?>
