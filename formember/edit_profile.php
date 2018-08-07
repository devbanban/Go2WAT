<?php require_once('../Connections/connection.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "M";
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

$MM_restrictGoTo = "../login.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "edit_profile")) {
  $updateSQL = sprintf("UPDATE tb_member SET Password=%s, Fname=%s, Lname=%s, Email=%s, Phone=%s, AccessLevel=%s WHERE Member_id=%s",
                       GetSQLValueString(MD5($_POST['Password']), "text"),
                       GetSQLValueString($_POST['Fname'], "text"),
                       GetSQLValueString($_POST['Lname'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Phone'], "text"),
                       GetSQLValueString($_POST['AccessLevel'], "text"),
                       GetSQLValueString($_POST['Member_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edit_profile = "-1";
if (isset($_GET['Member_id'])) {
  $colname_edit_profile = $_GET['Member_id'];
}
mysql_select_db($database_connection, $connection);
$query_edit_profile = sprintf("SELECT * FROM tb_member WHERE Member_id = %s", GetSQLValueString($colname_edit_profile, "int"));
$edit_profile = mysql_query($query_edit_profile, $connection) or die(mysql_error());
$row_edit_profile = mysql_fetch_assoc($edit_profile);
$totalRows_edit_profile = mysql_num_rows($edit_profile);
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
<link href="../css/my.css" rel="stylesheet" />
<script type="text/javascript">

function passvalidate() {
if (document.register.Password.value != document.register.Password2.value)
{
    alert('Passwords ไม่ตรงกันกรุณากรอกใหม่อีกครั้ง  !');
    return true;
  }
else
{
return true;
}
}
</script>
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript"><!--
function checkPasswordMatch() {
    var password = $("#Password").val();
    var confirmPassword = $("#Password2").val();

    if (password != confirmPassword)
        $("#divCheckPasswordMatch").html(" * Password ไม่ตรงกันกรุณากรอกใหม่!");
    else
        $("#divCheckPasswordMatch").html("");
}
//--></script>



</head>




<body onload="test()">

<!--banner-->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-12"> <img src="../img/index/960-100.png" width="100%"  class="img-responsive" /> </div>
  </div>
</div>
<!--banner--> 

<!--menu-->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-12">
     
  </div>
</div>
</div>
<!--end menu -->

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h4 align="center"> แก้ไขโปรไฟล์ <br>
      </h4>
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="edit_profile"  id="edit_profile">
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><img src="mimg/<?php echo $row_edit_profile['img']; ?>" width="100"></td>
            <td colspan="3"><?php echo $row_edit_profile['Member_id']; ?></td>
          </tr>
          <tr>
            <td width="24%" align="right"> Username &nbsp;</td>
            <td colspan="3"><input name="Username" type="text"  required id="Username" value="<?php echo $row_edit_profile['Username']; ?>" disabled size="30">
              *&nbsp;ไม่อนุญาตให้เปลี่ยนครับ</td>
          </tr>
          <tr>
            <td align="right"> Password &nbsp;</td>
            <td colspan="3"><input name="Password" type="password"   required id="Password" value="<?php echo $row_edit_profile['Password']; ?>" size="30"></td>
          </tr>
          <tr>
            <td align="right">Confirm Password&nbsp; &nbsp;</td>
            <td colspan="3"><input name="Password2" type="password"   required id="Password2" size="30" onkeyup="checkPasswordMatch();">
            <div class="registrationFormAlert" id="divCheckPasswordMatch">  
            </td>
          </tr>
          <tr>
            <td align="right"> ชื่อ &nbsp;</td>
            <td colspan="3"><input name="Fname" type="text"  required id="Fname" value="<?php echo $row_edit_profile['Fname']; ?>" size="50">
              &nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> นามสกุล &nbsp;</td>
            <td colspan="3"><input name="Lname" type="text"  required id="Lname" value="<?php echo $row_edit_profile['Lname']; ?>" size="50"></td>
          </tr>
          <tr>
            <td align="right"> E-mail &nbsp; </td>
            <td colspan="3"><input name="Email" type="email"  required id="Email" value="<?php echo $row_edit_profile['Email']; ?>" size="50">
              &nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td align="right">เบอร์โทรศัพท์ &nbsp; </td>
            <td colspan="3"><input name="Phone" type="text"  required id="Phone" value="<?php echo $row_edit_profile['Phone']; ?>" size="50"></td>
          </tr>
          <tr>
            <td align="center"><input name="Member_id" type="hidden" id="Member_id" value="<?php echo $row_edit_profile['Member_id']; ?>"> <input name="AccessLevel" type="hidden" id="AccessLevel" value="M"></td>
            <td colspan="3" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="3" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="center">
            
             <input type="reset" name="reset" id="reset" class="btn btn-warning btn-md" value="เคลียร์">
             &nbsp;&nbsp; &nbsp;
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-md" value="ปรับปรุงโปรไฟล์"></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td width="15%">&nbsp;</td>
            <td width="17%">&nbsp;</td>
            <td width="44%">&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="edit_profile">
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
mysql_free_result($edit_profile);
?>
