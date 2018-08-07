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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "register")) {
  $updateSQL = sprintf("UPDATE tb_member SET Username=%s, Password=%s, Fname=%s, Lname=%s, Email=%s, Phone=%s, AccessLevel=%s WHERE Member_id=%s",
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString(MD5($_POST['Password']), "text"),
                       GetSQLValueString($_POST['Fname'], "text"),
                       GetSQLValueString($_POST['Lname'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Phone'], "text"),
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

$colname_edit_member = "-1";
if (isset($_GET['Member_id'])) {
  $colname_edit_member = $_GET['Member_id'];
}
mysql_select_db($database_connection, $connection);
$query_edit_member = sprintf("SELECT * FROM tb_member WHERE Member_id = %s", GetSQLValueString($colname_edit_member, "int"));
$edit_member = mysql_query($query_edit_member, $connection) or die(mysql_error());
$row_edit_member = mysql_fetch_assoc($edit_member);
$totalRows_edit_member = mysql_num_rows($edit_member);
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




<body onload="test()">

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h4 align="center"> Edit Member <br>
      </h4>
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="register" onsubmit="passvalidate()" id="register">
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
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td width="24%" align="right"> Username &nbsp;</td>
            <td colspan="3"><input name="Username" type="text" required id="Username" placeholder="ภาษาอังกฤษหรือตัวเลข" value="<?php echo $row_edit_member['Username']; ?>" size="30"></td>
          </tr>
          <tr>
            <td align="right"> Password &nbsp;</td>
            <td colspan="3"><input name="Password" type="password"  required id="Password" placeholder="อย่างน้อย 8 ตัว" value="<?php echo $row_edit_member['Password']; ?>" size="30"></td>
          </tr>
          <tr>
            <td align="right"> ชื่อ &nbsp;</td>
            <td colspan="3"><input name="Fname" type="text" required id="Fname" placeholder="ภาษาไทยหรืออังกฤษ" value="<?php echo $row_edit_member['Fname']; ?>" size="50">
              &nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> นามสกุล &nbsp;</td>
            <td colspan="3"><input name="Lname" type="text" required id="Lname" placeholder="ภาษาไทยหรืออังกฤษ" value="<?php echo $row_edit_member['Lname']; ?>" size="50"></td>
          </tr>
          <tr>
            <td align="right"> E-mail &nbsp; </td>
            <td colspan="3"><input name="Email" type="email" required id="Email" placeholder="เช่น abc@gmail.com " value="<?php echo $row_edit_member['Email']; ?>" size="50">
              &nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td align="right">เบอร์โทรศัพท์ &nbsp; </td>
            <td colspan="3"><input name="Phone" type="text" required id="Phone" placeholder="เช่น 891 999 9999" value="<?php echo $row_edit_member['Phone']; ?>" size="50"></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="center"><input name="Member_id" type="hidden" id="Member_id" value="<?php echo $row_edit_member['Member_id']; ?>"> <input name="AccessLevel" type="hidden" id="AccessLevel" value="M"></td>
          </tr>
          <tr>
            <td colspan="4" align="center">
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-lg" value="update"></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td width="15%">&nbsp;</td>
            <td width="17%">&nbsp;</td>
            <td width="44%">&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="register">
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
mysql_free_result($edit_member);
?>
