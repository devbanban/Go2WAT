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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "contact")) {
  $insertSQL = sprintf("INSERT INTO tb_contact (detail, q_name, email, addby) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['detail'], "text"),
                       GetSQLValueString($_POST['q_name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['addby'], "text"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  $insertGoTo = "contact_ok.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_mem = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mem = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_mem = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_mem, "text"));
$mem = mysql_query($query_mem, $connection) or die(mysql_error());
$row_mem = mysql_fetch_assoc($mem);
$totalRows_mem = mysql_num_rows($mem);
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
<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/jquery.form.min.js"> </script>
    </head>
	
	
<body>
      
<!--#register-->
        <section id="contact"><br>
          <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp;<a href="index.php">กลับเมนูหลัก</a>&nbsp; &nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br> </h4>

                	<div class="col-md-12">

                    <div class="section-title text-center wow fadeInDown">
                        <h4><b>แจ้งปัญหาการใช้งาน</b>
                        </h4>
                    </div>
                    
                    
<div class="container">
  <div class="row">
    <div class="col-md-12">
    
      <form action="<?php echo $editFormAction; ?>" method="POST"  name="contact"  id="contact">
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="18%" align="right" valign="top">รายละเอียด&nbsp;</td>
            <td colspan="2"><textarea name="detail" rows="3" required class="form-control" id="detail" placeholder="กรุณากรอกข้อมูล"></textarea></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> ชื่อ &nbsp;</td>
            <td colspan="2"><input name="q_name" type="text" id="q_name"  class="form-control" placeholder="กรุณากรอกข้อมูล" required></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> E-mail &nbsp; </td>
            <td width="31%"><input name="email" type="email" id="Email" class="form-control" placeholder="เช่น abc@gmail.com " required></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><input name="addby" type="hidden" id="addby" value="<?php echo $row_mem['Username']; ?>"></td>
            <td colspan="3" align="left">
            
            <input type="reset" name="reset" id="reset" class="btn btn-warning btn-sm" value="Reset">
             &nbsp;&nbsp; &nbsp;
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-sm" value="แจ้งปัญหา" >
            
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center"></td>
          </tr>
          <tr>
            <td align="right"><br /></td>
            <td>&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="41%">&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="contact">
      </form>                         
                    </div>
 
				</div>
         
        </section>
<!--End #register-->





        
        
        
<!--#footer-->
        <footer id="footer">
            <div class="container">
                <div class="row">
                
               
                            
                        <?php include("../footer.php"); ?>
                        
                    
              </div>
            </div>
        </footer>
<!--End #footer-->
<script src="../js/bootstrap.min.js"></script> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="../js/jquery-1.11.2.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed -->


		
		
    </body>
</html>
<?php
mysql_free_result($mem);
?>
