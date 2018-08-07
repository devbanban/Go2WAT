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

mysql_select_db($database_connection, $connection);
$query_Recordset1 = "SELECT * FROM tb_contact";
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
  $MM_redirectLoginSuccess = "adminpage.php";
  $MM_redirectLoginFailed = "index.php";
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
</head>
  <body>

<div class="container">
    <div class="row">
      <div class="col-md-12">
          <h3 align="center"> เข้าสู่ระบบ   </h3>
            <form  name="formlogin" action="<?php echo $loginFormAction; ?>" method="POST" id="login">
              <div class="row">
              <label class="col-md-4" style="text-align:right"> Username :  </label>
                <div class="col-md-4">
                <input type="text"  name="Username" class="form-control" required placeholder="Username" />
                </div>
                </div>


              <div class="row">
              <br>
              <label class="col-md-4" style="text-align:right"> Password :  </label>
                <div class="col-md-4">
                     <input type="password" name="Password" class="form-control" required placeholder="Password" />
            </div>
              </div>



          
              &nbsp; &nbsp; &nbsp; <br /> 
              <div class="col-md-12">
              <p align="center">
              <button type="submit" class="btn btn-primary btn-md" id="btn" value="Signin"> เข้าสู่ระบบ </button> 
              </p>
              </div>
              <br>
              
            </form>
      </div>
    </div>
</div>    


<div class="container">
  <div class="row">
  <div class="col-md-12">
       
        <br>
        <?php include("../footer.php"); ?>
    </div>
  </div>
</dvi>


            
          
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
 <script src="../js/jquery-1.11.2.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="../js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_free_result($Recordset1);
?>
