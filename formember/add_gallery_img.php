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


//del img 

if ((isset($_POST['id_gall_img'])) && ($_POST['id_gall_img'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tb_gallery_img WHERE `id_gall_img`=%s",
                       GetSQLValueString($_POST['id_gall_img'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($deleteSQL, $connection) or die(mysql_error());

  $deleteGoTo = "add_gallery_img.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


// end delete img

$maxRows_show_gall = 10;
$pageNum_show_gall = 0;
if (isset($_GET['pageNum_show_gall'])) {
  $pageNum_show_gall = $_GET['pageNum_show_gall'];
}
$startRow_show_gall = $pageNum_show_gall * $maxRows_show_gall;

$colname_show_gall = "-1";
if (isset($_GET['id_gall'])) {
  $colname_show_gall = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_show_gall = sprintf("SELECT * FROM tb_gallery WHERE id_gall = %s", GetSQLValueString($colname_show_gall, "int"));
$query_limit_show_gall = sprintf("%s LIMIT %d, %d", $query_show_gall, $startRow_show_gall, $maxRows_show_gall);
$show_gall = mysql_query($query_limit_show_gall, $connection) or die(mysql_error());
$row_show_gall = mysql_fetch_assoc($show_gall);

if (isset($_GET['totalRows_show_gall'])) {
  $totalRows_show_gall = $_GET['totalRows_show_gall'];
} else {
  $all_show_gall = mysql_query($query_show_gall);
  $totalRows_show_gall = mysql_num_rows($all_show_gall);
}
$totalPages_show_gall = ceil($totalRows_show_gall/$maxRows_show_gall)-1;

$colname_mem = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mem = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_mem = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_mem, "text"));
$mem = mysql_query($query_mem, $connection) or die(mysql_error());
$row_mem = mysql_fetch_assoc($mem);
$totalRows_mem = mysql_num_rows($mem);

$colname_gall_img = "-1";
if (isset($_GET['id_gall'])) {
  $colname_gall_img = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_gall_img = sprintf("SELECT * FROM tb_gallery_img WHERE id_gall = %s ORDER BY id_gall_img DESC", GetSQLValueString($colname_gall_img, "int"));
$gall_img = mysql_query($query_gall_img, $connection) or die(mysql_error());
$row_gall_img = mysql_fetch_assoc($gall_img);
$totalRows_gall_img = mysql_num_rows($gall_img);

$colname_gall = "-1";
if (isset($_GET['id_gall'])) {
  $colname_gall = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_gall = sprintf("SELECT * FROM tb_gallery WHERE id_gall = %s", GetSQLValueString($colname_gall, "int"));
$gall = mysql_query($query_gall, $connection) or die(mysql_error());
$row_gall = mysql_fetch_assoc($gall);
$totalRows_gall = mysql_num_rows($gall);
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
	$(document).on('change', '#img', function() {
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
    <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp;<a href="index.php">กลับเมนูหลัก</a>&nbsp;<a href="add_gallery.php"> กลับหน้าแกลเลอรี่</a>&nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
    </h4>
    <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0" id="top">
      <tr>
        <td height="40" colspan="3" align="center" bgcolor="#D6D5D6">แกลเลอรี่ ID:&nbsp; <?php echo $row_show_gall['id_gall']; ?></td>
      </tr>
      <?php do { ?>
        <tr>
          <td width="5%" align="right" valign="top">ภาพปก/ภาพแรก</td>
          <td width="5%" align="left"> &nbsp;<img src="../admin/gallery/<?php echo $row_show_gall['Img_index']; ?>" width="120"></td>
          <td width="40%" align="left" valign="top">คำบรรยาย : <?php echo $row_show_gall['Title']; ?><br>
          วัด : <?php echo $row_show_gall['Location']; ?><br>
          ว/ด/ป : <?php echo $row_show_gall['DateAdd']; ?><br>
            จำนวนเข้าชม : <?php echo $row_show_gall['view']; ?><br>
          สถานะ : <?php echo $row_show_gall['status']; ?><br>
          โดย : <?php echo $row_show_gall['Addby']; ?></td>
        </tr>
        <?php } while ($row_show_gall = mysql_fetch_assoc($show_gall)); ?>
    </table>
<br>
    <br>
    <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="40" colspan="4" align="center" bgcolor="#D6D5D6">รวมทั้งหมด&nbsp; <?php echo $totalRows_gall_img ?> &nbsp; ภาพ&nbsp; <a href="#add_gall_img" class="btn btn-info"> + เพิ่มภาพ   </a></td>
      </tr>
      <tr>
        <td width="5%" height="40" align="center" bgcolor="#D6D5D6"><strong>id</strong></td>
        <td width="30%" height="40" align="center" bgcolor="#D6D5D6"><strong>ภาพ</strong></td>
        <td width="15%" height="40" align="center" bgcolor="#D6D5D6"><strong>ว/ด/ป</strong></td>
        <td width="10%" height="40" align="center" bgcolor="#D6D5D6"><strong>ลบ</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_gall_img['id_gall_img']; ?></td>
          <td> &nbsp;<img src="../admin/gallery/<?php echo $row_gall_img['Img']; ?>" width="100"></td>
          <td><?php echo $row_gall_img['date_add']; ?></td>
          <td align="center">
          
          <form action="" method="post" name="del" id="del">
            <input type="submit" name="del2" id="del2" value="ลบ" class="btn btn-danger btn-md">
            <input name="id_gall_img" type="hidden" id="id_gall_img" value="<?php echo $row_gall_img['id_gall_img']; ?>">
          </form>
          
          
          </td>
        </tr>
        <?php } while ($row_gall_img = mysql_fetch_assoc($gall_img)); ?>
    </table>
<br>
    <br>
    <form action="add_gallery_img_db.php" method="POST" enctype="multipart/form-data" name="add_gall_img" id="add_gall_img">
      <p>&nbsp;</p>
      <table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
        <tr>
          <td colspan="3" align="right" bgcolor="#EEEEEE"><a href="#top" class="btn btn-success"> กลับขึ้นด้านบน   </a>&nbsp;</td>
        </tr>
        <tr>
          <td height="40" colspan="3" align="center" bgcolor="#EEEEEE"><strong>เพิ่มภาพในแกลเลอรี่ ID : <?php echo $row_gall['id_gall']; ?></strong></td>
        </tr>
        <tr>
          <td width="20%" align="right" bgcolor="#f1f1f1">ไฟล์ภาพ</td>
          <td width="31%" bgcolor="#fff"><label>
            <input type="file" name="img" id="img" required>
          </label></td>
          <td width="49%" bgcolor="#f1f1f1">* ขนาดไฟล์ภาพไม่เกิน 500kb </td>
        </tr>
        <tr>
          <td bgcolor="#f1f1f1">&nbsp;</td>
          <td colspan="2" bgcolor="#f1f1f1"><input name="id_gall" type="hidden" id="id_gall" value="<?php echo $row_gall['id_gall']; ?>">
          <input type="submit" name="button" id="button" value="+ เพิ่มภาพ" class="btn btn-primary btn-sm"></td>
        </tr>
        <tr>
          <td colspan="3" align="center" bgcolor="#f1f1f1">&nbsp;          &nbsp;</td>
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
mysql_free_result($show_gall);

mysql_free_result($mem);

mysql_free_result($gall_img);

mysql_free_result($gall);
?>



<!--<strong><a href="del_gallery_img.php?id_gall_img=<?php //echo $row_gall_img['id_gall_img']; ?>" 
                onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่')">&nbsp;<span class="glyphicon glyphicon-trash"></span></a></strong>
          -->
          