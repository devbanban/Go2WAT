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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_admin")) {
  $insertSQL = sprintf("INSERT INTO tb_member (Username, Password, Fname, Lname, Email, Phone, AccessLevel) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString (MD5($_POST['Password']), "text"),
                       GetSQLValueString($_POST['Fname'], "text"),
                       GetSQLValueString($_POST['Lname'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Phone'], "text"),
                       GetSQLValueString($_POST['AccessLevel'], "text"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  $insertGoTo = "add_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_showadmin = 10;
$pageNum_showadmin = 0;
if (isset($_GET['pageNum_showadmin'])) {
  $pageNum_showadmin = $_GET['pageNum_showadmin'];
}
$startRow_showadmin = $pageNum_showadmin * $maxRows_showadmin;

mysql_select_db($database_connection, $connection);
$query_showadmin = "SELECT * FROM tb_member WHERE AccessLevel = 'A'";
$query_limit_showadmin = sprintf("%s LIMIT %d, %d", $query_showadmin, $startRow_showadmin, $maxRows_showadmin);
$showadmin = mysql_query($query_limit_showadmin, $connection) or die(mysql_error());
$row_showadmin = mysql_fetch_assoc($showadmin);

if (isset($_GET['totalRows_showadmin'])) {
  $totalRows_showadmin = $_GET['totalRows_showadmin'];
} else {
  $all_showadmin = mysql_query($query_showadmin);
  $totalRows_showadmin = mysql_num_rows($all_showadmin);
}
$totalPages_showadmin = ceil($totalRows_showadmin/$maxRows_showadmin)-1;

$colname_member = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_member = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_member = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_member, "text"));
$member = mysql_query($query_member, $connection) or die(mysql_error());
$row_member = mysql_fetch_assoc($member);
$totalRows_member = mysql_num_rows($member);

$queryString_showadmin = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_showadmin") == false && 
        stristr($param, "totalRows_showadmin") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_showadmin = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_showadmin = sprintf("&totalRows_showadmin=%d%s", $totalRows_showadmin, $queryString_showadmin);
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
                  hi: <?php echo $row_member['Fname']; ?>&nbsp;<a href="adminpage.php">กลับเมนูหลัก</a>&nbsp; &nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
              </h4>
   
   
   
   
   
 <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr>
            <td height="40" colspan="8" align="center" bgcolor="#EEEEEE"><h4>บริหารจัดการผู้ดูแลระบบ </h4><a href="#add_admin"> +เพิ่มผู้ดูแลระบบ<br>
     </a>แสดงรายการที่ <?php echo ($startRow_showadmin + 1) ?>&nbsp; ถึง&nbsp; <?php echo min($startRow_showadmin + $maxRows_showadmin, $totalRows_showadmin) ?>&nbsp; รวมทั้งหมด&nbsp;&nbsp;<?php echo $totalRows_showadmin ?> รายการ&nbsp; <br>
     <table border="0" align="center">
       <tr>
         <td><?php if ($pageNum_showadmin > 0) { // Show if not first page ?>
           <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, 0, $queryString_showadmin); ?>"><img src="First.gif"></a>
           <?php } // Show if not first page ?></td>
         <td><?php if ($pageNum_showadmin > 0) { // Show if not first page ?>
           <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, max(0, $pageNum_showadmin - 1), $queryString_showadmin); ?>"><img src="Previous.gif"></a>
           <?php } // Show if not first page ?></td>
         <td><?php if ($pageNum_showadmin < $totalPages_showadmin) { // Show if not last page ?>
           <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, min($totalPages_showadmin, $pageNum_showadmin + 1), $queryString_showadmin); ?>"><img src="Next.gif"></a>
           <?php } // Show if not last page ?></td>
         <td><?php if ($pageNum_showadmin < $totalPages_showadmin) { // Show if not last page ?>
           <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, $totalPages_showadmin, $queryString_showadmin); ?>"><img src="Last.gif"></a>
           <?php } // Show if not last page ?></td>
       </tr>
     </table></td>
      </tr>

      <tr>
             <td width="17%" align="center" bgcolor="#EEEEEE"><strong>id</strong></td>
             <td width="17%" align="center" bgcolor="#EEEEEE"><strong>Username</strong></td>
             <td width="14%" height="40" align="center" bgcolor="#EEEEEE"><strong>name</strong></td>
             <td width="13%" align="center" bgcolor="#EEEEEE"><strong>Email</strong></td>
             <td width="14%" align="center" bgcolor="#EEEEEE"><strong>Phone</strong></td>
             <td width="17%" align="center" bgcolor="#EEEEEE"><strong>Regis_date</strong></td>
             <td colspan="2" align="center" bgcolor="#EEEEEE"><strong>manage</strong></td>
     </tr>

   <?php do { ?>


     <tr>
             <td align="center"><?php echo $row_showadmin['Member_id']; ?></td>
             <td><?php echo $row_showadmin['Username']; ?></td>
             <td><?php echo $row_showadmin['Fname']; ?>&nbsp; <?php echo $row_showadmin['Lname']; ?></td>
             <td><?php echo $row_showadmin['Email']; ?></td>
             <td><?php echo $row_showadmin['Phone']; ?></td>
             <td><?php echo $row_showadmin['Regis_date']; ?></td>
             <td width="4%" align="center"><strong><a href="edit_admin.php?Member_id=<?php echo $row_showadmin['Member_id']; ?>" class="btn btn-warning btn-xs">
             &nbsp;edit</a></strong></td>
             <td width="4%" align="center"><strong><a href="del_admin.php?Member_id=<?php echo $row_showadmin['Member_id']; ?>" 
                      onclick="return confirm('คุณต้องการลบข้อมูล <?php echo $row_showadmin['Fname']; ?> หรือไม่ ')" class="btn btn-danger btn-xs">&nbsp;del</a></strong></td>
     </tr>
     <?php } while ($row_showadmin = mysql_fetch_assoc($showadmin)); ?>
 </table>



 <br>
 <table border="0" align="center">
   <tr>
     <td><?php if ($pageNum_showadmin > 0) { // Show if not first page ?>
         <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, 0, $queryString_showadmin); ?>"><img src="First.gif"></a>
         <?php } // Show if not first page ?></td>
     <td><?php if ($pageNum_showadmin > 0) { // Show if not first page ?>
         <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, max(0, $pageNum_showadmin - 1), $queryString_showadmin); ?>"><img src="Previous.gif"></a>
         <?php } // Show if not first page ?></td>
     <td><?php if ($pageNum_showadmin < $totalPages_showadmin) { // Show if not last page ?>
         <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, min($totalPages_showadmin, $pageNum_showadmin + 1), $queryString_showadmin); ?>"><img src="Next.gif"></a>
         <?php } // Show if not last page ?></td>
     <td><?php if ($pageNum_showadmin < $totalPages_showadmin) { // Show if not last page ?>
         <a href="<?php printf("%s?pageNum_showadmin=%d%s", $currentPage, $totalPages_showadmin, $queryString_showadmin); ?>"><img src="Last.gif"></a>
         <?php } // Show if not last page ?></td>
   </tr>
 </table>
<br>
 <br>
 <br>


 <form action="<?php echo $editFormAction; ?>" method="POST" name="add_admin" id="add_admin">
         <p>&nbsp;</p>
         <table width="50%" border="0" align="center" cellpadding="3" cellspacing="3">
           <tr>
             <td height="40" colspan="2" align="center" bgcolor="#EEEEEE"><strong>เพิ่มผู้ดูแลระบบ</strong></td>
             </tr>
           <tr>
             <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td width="20%" align="right" bgcolor="#f1f1f1">Username</td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp; <label for="Username"></label>
               <input type="text" name="Username" id="Username" required placeholder="ภาษาอังกฤษหรือตัวเลข"></td>
           </tr>
           <tr>
             <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td width="20%" align="right" bgcolor="#f1f1f1">Password</td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp; <label for="Password"></label>
               <input type="password" name="Password" id="Password" required placeholder="อย่างน้อย 6 ตัว"></td>
           </tr>
           <tr>
             <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td width="20%" align="right" bgcolor="#f1f1f1">Name</td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp; <label for="Fname"></label>
               <input name="Fname" type="text" id="Fname" size="40" placeholder="ภาษาไทยหรืออังกฤษ"></td>
           </tr>
           <tr>
             <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td width="20%" align="right" bgcolor="#f1f1f1">Lastname</td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp; <label for="Lname"></label>
               <input name="Lname" type="text" id="Lname" size="40" required placeholder="ภาษาไทยหรืออังกฤษ"></td>
           </tr>
           <tr>
             <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td width="20%" align="right" bgcolor="#f1f1f1">E-mail</td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp; <label for="Email"></label>
               <input name="Email" type="email" id="Email" size="40" placeholder="ตัวอย่าง pisit.bow@gmail.com "></td>
           </tr>
           <tr>
             <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td width="20%" align="right" bgcolor="#f1f1f1">Phone</td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp; <label for="Phone"></label>
               <input name="Phone" type="tel" id="Phone" size="40" placeholder=" เช่น 0818546264"></td>
           </tr>
           <tr>
             <td width="20%" bgcolor="#f1f1f1"><input name="AccessLevel" type="hidden" id="AccessLevel" value="A"></td>
             <td width="80%" bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
           <tr>
             <td colspan="2" align="center" bgcolor="#f1f1f1"><input type="reset" name="button2" id="button2" value="Reset" class="btn btn-default"> 
              &nbsp;          &nbsp;  <input type="submit" name="button" id="button" value="+ เพิ่มข้อมูล" class="btn btn-primary"></td>
             </tr>
           <tr>
             <td bgcolor="#f1f1f1">&nbsp;</td>
             <td bgcolor="#f1f1f1">&nbsp;</td>
           </tr>
   </table>



   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   
   <input type="hidden" name="MM_insert" value="add_admin">
 </form>
 
    </div>
 </div>
 
 
 
 
 <script src="../js/bootstrap.min.js"></script> 
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
 <script src="../js/jquery-1.11.2.min.js"></script>
 <!-- Include all compiled plugins (below), or include individual files as needed -->


  </body>
</html>
<?php
mysql_free_result($showadmin);

mysql_free_result($member);
?>
