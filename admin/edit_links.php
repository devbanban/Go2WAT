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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_links")) {
  $insertSQL = sprintf("INSERT INTO tb_links (Wat_name, Website, stutus, Addby) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['Wat_name'], "text"),
                       GetSQLValueString($_POST['Website'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['Addby'], "text"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  $insertGoTo = "adminpage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "add_editlinks")) {
  $updateSQL = sprintf("UPDATE tb_links SET Wat_name=%s, Website=%s, DateAdd=%s, stutus=%s, Addby=%s WHERE id_links=%s",
                       GetSQLValueString($_POST['Wat_name'], "text"),
                       GetSQLValueString($_POST['Website'], "text"),
                       GetSQLValueString($_POST['DateAdd'], "date"),
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['Addby'], "text"),
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

$maxRows_showlinks = 10;
$pageNum_showlinks = 0;
if (isset($_GET['pageNum_showlinks'])) {
  $pageNum_showlinks = $_GET['pageNum_showlinks'];
}
$startRow_showlinks = $pageNum_showlinks * $maxRows_showlinks;

$colname_showlinks = "-1";
if (isset($_GET['id_links'])) {
  $colname_showlinks = $_GET['id_links'];
}
mysql_select_db($database_connection, $connection);
$query_showlinks = sprintf("SELECT * FROM tb_links WHERE id_links = %s ORDER BY id_links DESC", GetSQLValueString($colname_showlinks, "int"));
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
<script src="../js/jquery.form.min.js"> </script>

</head>




<body>



<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h4 align="center">ADD LINKS&nbsp; <a href="<?php echo $logoutAction ?>">Log out</a>&nbsp; &nbsp; hi :&nbsp; <?php echo $row_member['Fname']; ?><br>
      </h4>
      <br>
      <br>
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="add_editlinks" onsubmit="passvalidate()" id="add_editlinks">
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
            <td align="right">&nbsp;</td>
            <td colspan="3"></td>
          </tr>
          <tr>
            <td width="24%" align="right">ชื่อวัด&nbsp;</td>
            <td colspan="3"><input name="Wat_name" type="text"  required id="Wat_name" value="<?php echo $row_showlinks['Wat_name']; ?>" size="50"></td>
          </tr>
          <tr>
            <td align="right">เว็บไซต์&nbsp;</td>
            <td colspan="3"><input name="Website" type="text"   required id="Website" value="<?php echo $row_showlinks['Website']; ?>" size="60"></td>
          </tr>
          <tr>
            <td align="center"><input name="Status" type="hidden" id="Status" value="n">
              &nbsp;
              <input name="Addby" type="hidden" id="Addby" value="<?php echo $row_showlinks['Addby']; ?>"> <input name="DateAdd" type="hidden" id="DateAdd" value="<?php echo $row_showlinks['DateAdd']; ?>"> <input name="id_links" type="hidden" id="id_links" value="<?php echo $row_showlinks['id_links']; ?>"></td>
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
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-md" value="update"></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td width="15%">&nbsp;</td>
            <td width="17%">&nbsp;</td>
            <td width="44%">&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="add_editlinks">
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

mysql_free_result($showlinks);
?>
