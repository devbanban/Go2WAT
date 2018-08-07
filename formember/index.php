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

$colname_member = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_member = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_member = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_member, "text"));
$member = mysql_query($query_member, $connection) or die(mysql_error());
$row_member = mysql_fetch_assoc($member);
$totalRows_member = mysql_num_rows($member);

$colname_gallery = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_gallery = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_gallery = sprintf("SELECT * FROM tb_gallery WHERE Addby = %s", GetSQLValueString($colname_gallery, "text"));
$gallery = mysql_query($query_gallery, $connection) or die(mysql_error());
$row_gallery = mysql_fetch_assoc($gallery);
$totalRows_gallery = mysql_num_rows($gallery);

$colname_news = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_news = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_news = sprintf("SELECT * FROM tb_news WHERE Addby = %s", GetSQLValueString($colname_news, "text"));
$news = mysql_query($query_news, $connection) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);

$colname_post = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_post = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_post = sprintf("SELECT * FROM tb_post WHERE Addby = %s", GetSQLValueString($colname_post, "text"));
$post = mysql_query($query_post, $connection) or die(mysql_error());
$row_post = mysql_fetch_assoc($post);
$totalRows_post = mysql_num_rows($post);

$colname_links = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_links = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_links = sprintf("SELECT * FROM tb_links WHERE Addby = %s", GetSQLValueString($colname_links, "text"));
$links = mysql_query($query_links, $connection) or die(mysql_error());
$row_links = mysql_fetch_assoc($links);
$totalRows_links = mysql_num_rows($links);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>go2wat</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/my.css" rel="stylesheet">
</head>
<body>




	<div class="container">
<div class="row">
        	<div class="col-md-3">
            
           	  <h4><span class="glyphicon glyphicon-user" aria-hidden="true"></span> สวัสดีคุณ <?php echo $row_member['Fname']; ?></h4>
              <a href="edit_profile.php?Member_id=<?php echo $row_member['Member_id']; ?>"> แก้ไขข้อมูลของคุณ </a><br> <br> 
          
        	 <a href="editimgprofile.php?Member_id=<?php echo $row_member['Member_id']; ?>"> <img src="mimg/<?php echo $row_member['img']; ?>" width="150" class="img-circle"> <br>
        	 แก้ไขภาพโปรไฟล์<br>
            
              </a>
              <h4><span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span> สถิติการโพส </h4>
             <li> จำนวนการเพิ่มข่าว  :<span class="static"> <?php echo $totalRows_news ?> </span>ครั้ง</li>
             <li> จำนวนการเพิ่มกระทู้  :<span class="static"> <?php echo $totalRows_post ?>    </span>ครั้ง</li>
             <li> จำนวนการเพิ่มแกลเลอรี่  :<span class="static"> <?php echo $totalRows_gallery ?>  </span>ครั้ง</li>
             <li> จำนวนการเพิ่มลิงค์  :<span class="static"> <?php echo $totalRows_links ?>   </span>ครั้ง</li>
             <br>
             <p align="center">
        	  <a href="<?php echo $logoutAction ?>">Log out</a>
              </p>
       	    
      </div>
  
      <div class="col-md-9">
      <br>
      <div class="list-group">
      
  <li class="list-group-item active"> Menu&nbsp;สำหรับสมาชิก</li>
  <a href="add_news.php" class="list-group-item"> + จัดการข่าว </a>
  <a href="add_post.php" class="list-group-item"> + จัดการกระทู้</a>
  <a href="add_gallery.php" class="list-group-item"> + จัดการแกลเลอรี่</a>
  <a href="add_links.php" class="list-group-item"> + เพิ่มลิงค์</a>
  <a href="contact.php" class="list-group-item"> + แจ้งปัญหาการใช้งาน</a>

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
</h1>
</body>
</html>


<?php
mysql_free_result($member);

mysql_free_result($gallery);

mysql_free_result($news);

mysql_free_result($post);

mysql_free_result($links);
?>
