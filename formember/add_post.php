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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "show")) {
  $updateSQL = sprintf("UPDATE tb_post SET status=%s WHERE Post_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['Post_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "add_post.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "hide")) {
  $updateSQL = sprintf("UPDATE tb_post SET status=%s WHERE Post_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['Post_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "add_post.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_show_post = 10;
$pageNum_show_post = 0;
if (isset($_GET['pageNum_show_post'])) {
  $pageNum_show_post = $_GET['pageNum_show_post'];
}
$startRow_show_post = $pageNum_show_post * $maxRows_show_post;

$colname_show_post = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_show_post = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_show_post = sprintf("SELECT * FROM tb_post WHERE Addby = %s ORDER BY Post_id DESC", GetSQLValueString($colname_show_post, "text"));
$query_limit_show_post = sprintf("%s LIMIT %d, %d", $query_show_post, $startRow_show_post, $maxRows_show_post);
$show_post = mysql_query($query_limit_show_post, $connection) or die(mysql_error());
$row_show_post = mysql_fetch_assoc($show_post);

if (isset($_GET['totalRows_show_post'])) {
  $totalRows_show_post = $_GET['totalRows_show_post'];
} else {
  $all_show_post = mysql_query($query_show_post);
  $totalRows_show_post = mysql_num_rows($all_show_post);
}
$totalPages_show_post = ceil($totalRows_show_post/$maxRows_show_post)-1;

$colname_mem = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mem = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_mem = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_mem, "text"));
$mem = mysql_query($query_mem, $connection) or die(mysql_error());
$row_mem = mysql_fetch_assoc($mem);
$totalRows_mem = mysql_num_rows($mem);

$colname_post_img = "-1";
if (isset($_GET['id_gall'])) {
  $colname_post_img = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_post_img = sprintf("SELECT * FROM tb_gallery_img WHERE id_gall = %s", GetSQLValueString($colname_post_img, "int"));
$post_img = mysql_query($query_post_img, $connection) or die(mysql_error());
$row_post_img = mysql_fetch_assoc($post_img);
$totalRows_post_img = mysql_num_rows($post_img);

$queryString_show_post = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_show_post") == false && 
        stristr($param, "totalRows_show_post") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_show_post = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_show_post = sprintf("&totalRows_show_post=%d%s", $totalRows_show_post, $queryString_show_post);
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
<script>
$(function() {
	$(document).on('change', '#Img_index', function() {
		if(this.files[0].size > 512000) {
			alert('ไฟล์ภาพมีขนาดใหญ่เกินกำหนด (500 KB) กรุณา Resize รูปภาพก่อนทำการ Upload ขอบคุณครับ');
			//$(this).replaceWith($(this).clone());
			$('input:file').clearInputs(); 
		}
	});
});
</script>

</head>

<body>
<div class="container">
  <div class="row">
    <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp;<a href="index.php">กลับเมนูหลัก</a>&nbsp; &nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
    </h4>
    <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="40" colspan="7" align="center" bgcolor="#D6D5D6"><h4>จัดการกระทู้</h4>
          &nbsp;&nbsp; <strong><a href="#add_post">+ กระทู้ </a> <br>
แสดงรายการที่ <?php echo ($startRow_show_post + 1) ?>&nbsp; ถึง&nbsp;<?php echo min($startRow_show_post + $maxRows_show_post, $totalRows_show_post) ?>&nbsp;รวมทั้งหมด&nbsp;<?php echo $totalRows_show_post ?> &nbsp;รายการ <br>
<table border="0">
  <tr>
    <td><?php if ($pageNum_show_post > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, 0, $queryString_show_post); ?>"><img src="First.gif"></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_show_post > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, max(0, $pageNum_show_post - 1), $queryString_show_post); ?>"><img src="Previous.gif"></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_show_post < $totalPages_show_post) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, min($totalPages_show_post, $pageNum_show_post + 1), $queryString_show_post); ?>"><img src="Next.gif"></a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_show_post < $totalPages_show_post) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, $totalPages_show_post, $queryString_show_post); ?>"><img src="Last.gif"></a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<br>
          </strong></td>
      </tr>
      <tr>
        <td width="10%" height="40" align="center" bgcolor="#D6D5D6">Img_Index</td>
        <td width="40%" align="center" bgcolor="#D6D5D6"><strong>Title</strong></td>
        <td width="15%" align="center" bgcolor="#D6D5D6"><strong>DateAdd</strong></td>
        <td width="10%" align="center" bgcolor="#D6D5D6"><strong>status</strong></td>
        <td colspan="3" align="center" bgcolor="#D6D5D6"><strong>Manage</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          <td><img src="../admin/post/<?php echo $row_show_post['Img_index']; ?>" width="100"></td>
          <td align="left" valign="top"><?php echo $row_show_post['Title']; ?>วัด<?php echo $row_show_post['Location']; ?>&nbsp; <strong><br>
          +&nbsp;<a href="add_post_img.php?Post_id=<?php echo $row_show_post['Post_id']; ?>">เพิ่มภาพ</a></strong></td>
          <td align="center" valign="top"><br>
          <?php echo $row_show_post['DateAdd']; ?></td>
          <td align="center" valign="top"><br>
          <?php echo $row_show_post['status']; ?></td>
          
          <td width="5%" align="center" valign="top"><br>
         <a href="edit_post.php?Post_id=<?php echo $row_show_post['Post_id']; ?>">
         &nbsp;<span class="glyphicon glyphicon-pencil"></span>
         </a>
          </td>
          <td width="5%" align="center" valign="top"><br>
          
              <a href="del_post.php?Post_id=<?php echo $row_show_post['Post_id']; ?>" 
                onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่ (ID: <?php echo $row_show_post['Post_id']; ?>) ')">&nbsp;<span class="glyphicon glyphicon-trash"></span></a>
          
          
          
          
          
          </td>
        </tr>
        <?php } while ($row_show_post = mysql_fetch_assoc($show_post)); ?>
    </table>
    <strong>
    <br>
    <table border="0" align="center">
      <tr>
        <td><?php if ($pageNum_show_post > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, 0, $queryString_show_post); ?>"><img src="First.gif"></a>
          <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_show_post > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, max(0, $pageNum_show_post - 1), $queryString_show_post); ?>"><img src="Previous.gif"></a>
          <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_show_post < $totalPages_show_post) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, min($totalPages_show_post, $pageNum_show_post + 1), $queryString_show_post); ?>"><img src="Next.gif"></a>
          <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_show_post < $totalPages_show_post) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_show_post=%d%s", $currentPage, $totalPages_show_post, $queryString_show_post); ?>"><img src="Last.gif"></a>
          <?php } // Show if not last page ?></td>
      </tr>
    </table>
    </strong><br>
    <form action="add_post_db.php" method="POST" enctype="multipart/form-data" name="add_post" id="add_post">
      <p>&nbsp;</p>
      <table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
        <tr>
          <td height="40" colspan="2" align="center" bgcolor="#EEEEEE"><strong>เพิ่มกระทู้</strong></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
          <td bgcolor="#f1f1f1">&nbsp;</td>
        </tr>
        <tr>
          <td width="20%" align="right" bgcolor="#f1f1f1">Title</td>
          <td width="80%" bgcolor="#f1f1f1">&nbsp;
            <label for="Title"></label>
            <input name="Title" type="text" required id="Title" placeholder="ชื่ออัลบั้ม *ไม่เกิน 150 ตัวอักษร " size="70" maxlength="150"></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
          <td bgcolor="#f1f1f1">&nbsp;</td>
        </tr>
        <tr>
          <td width="20%" align="right" valign="top" bgcolor="#f1f1f1">Detail</td>
          <td width="80%" bgcolor="#f1f1f1">&nbsp;
            <label for="Detail"></label>
            <textarea name="Detail" cols="70" rows="10" required id="Detail" placeholder="คำอธิบาย"></textarea></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
          <td bgcolor="#f1f1f1">&nbsp;</td>
        </tr>
        <tr>
          <td width="20%" align="right" bgcolor="#f1f1f1">Locatoin</td>
          <td width="80%" bgcolor="#f1f1f1">&nbsp;
            <label for="Location"></label>
            <input name="Location" type="text" id="Location" size="40" placeholder="ชื่อวัด *ไม่เกิน 150 ตัวอักษร " maxlength="150" required></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
          <td bgcolor="#f1f1f1">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" bgcolor="#f1f1f1">Img Index</td>
          <td bgcolor="#f1f1f1">&nbsp; <label>
            <input type="file" name="Img_index" id="Img_index" required>
            * ขนาดไฟล์ภาพไม่เกิน 500kb
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#f1f1f1">&nbsp;</td>
          <td bgcolor="#f1f1f1"><input name="Addby" type="hidden" id="Addby" value="<?php echo $row_mem['Username']; ?>"> <input name="status" type="hidden" id="status" value="Y"></td>
        </tr>
        <tr>
          <td colspan="2" align="center" bgcolor="#f1f1f1">
          <input type="reset" name="button2" id="button2" value="Reset" class="btn btn-default">
            &nbsp;          &nbsp;
            <input type="submit" name="button" id="button" value="+ เพิ่มกระทู้" class="btn btn-primary"></td>
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
    </form>
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


<script src="../js/bootstrap.min.js"></script> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="../js/jquery-1.11.2.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed -->

</body>
</html>
<?php
mysql_free_result($show_post);

mysql_free_result($mem);

mysql_free_result($post_img);
?>
