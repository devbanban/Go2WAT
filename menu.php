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
$query_Recordset1 = "SELECT * FROM tb_member";
$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Username'])) {
  $loginUsername=$_POST['Username'];

  $password=MD5($_POST['Password']);
  $MM_fldUserAuthorization = "AccessLevel";
  $MM_redirectLoginSuccess = "formember/index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_connection, $connection);
  	
  $LoginRS__query=sprintf("SELECT Username, Password, AccessLevel FROM tb_member WHERE Username=%s AND Password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $connection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'AccessLevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<meta charset="utf-8" />
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="index.php"> ท่องเที่ยววัด</a></div>
    <div class="navbar-collapse collapse navbar-inverse-collapse">
      <ul class="nav navbar-nav">
        <li class=" visible-xs"> <a href="regismo.php" class="btn btn-info btn-sm ; "> สมัครสมาชิก </a></li>
        <li class="hidden-xs"><a href="index.php"> <span class="glyphicon glyphicon-home"></span> หน้าหลัก</a></li>
        <li><a href="http://www.watnakprok.com/%E0%B8%AA%E0%B8%B7%E0%B9%88%E0%B8%AD%E0%B8%98%E0%B8%A3%E0%B8%A3%E0%B8%A1/" target="_blank">สื่อธรรมะ</a></li>
        <li><a href="http://www.kalyanamitra.org/th/chadok_list.php" target="_blank">บทความธรรมะ</a></li>
        <li><a href="#">ติดต่อ</a></li>
      </ul>
      
      <!--Login-->
      <ul class="nav navbar-nav navbar-right">
       	<li><a href="register.php">สมัครสมาชิก</a></li>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">เข้าสู่ระบบ <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu" style="background-color:#f4f4f4">
            <li style="text-align:center">
<form action="<?php echo $loginFormAction; ?>" method="POST" id="frmLogin">
  Username : 
  
  
  
  
  
  
    <label>
      <input type="text" name="Username" id="Username" />
    </label>
    <br />
    Password : 
    <label>
      <input type="password" name="Password" id="Password" />
    </label>
    <br />
    <br />
   

    <input type="reset" name="reset" id="reset" value="Reset"  class="btn btn-warning btn-xs"/>
    <input type="submit" name="Login" id="Login" value="เข้าสู่ระบบ"  class="btn btn-success btn-xs"/>
</form>
            </li>
            <li style="text-align:center"> <br /><a href="#" class="btn btn-success">ลืมรหัสผ่าน</a></li>
            <li style="text-align:center"><a href="register.php" class="btn btn-info">สมัครสมาชิก</a></li>
          </ul>
        </li>
      </ul>
      
      <!---end Login --> 
      
    </div>
  </div>
  </div>
</nav>
<?php
mysql_free_result($Recordset1);
?>
