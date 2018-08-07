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

if ((isset($_POST['Post_id_img'])) && ($_POST['Post_id_img'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tb_post_img WHERE `Post_id_img`=%s",
                       GetSQLValueString($_POST['Post_id_img'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($deleteSQL, $connection) or die(mysql_error());

  $deleteGoTo = "add_post_img.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


// end delete img

$maxRows_show_post = 10;
$pageNum_show_post = 0;
if (isset($_GET['pageNum_show_post'])) {
  $pageNum_show_post = $_GET['pageNum_show_post'];
}
$startRow_show_post = $pageNum_show_post * $maxRows_show_post;

$colname_show_post = "-1";
if (isset($_GET['Post_id'])) {
  $colname_show_post = $_GET['Post_id'];
}
mysql_select_db($database_connection, $connection);
$query_show_post = sprintf("SELECT * FROM tb_post WHERE Post_id = %s", GetSQLValueString($colname_show_post, "int"));
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
if (isset($_GET['Post_id'])) {
  $colname_post_img = $_GET['Post_id'];
}
mysql_select_db($database_connection, $connection);
$query_post_img = sprintf("SELECT * FROM tb_post_img WHERE Post_id = %s ORDER BY Post_id_img DESC", GetSQLValueString($colname_post_img, "int"));
$post_img = mysql_query($query_post_img, $connection) or die(mysql_error());
$row_post_img = mysql_fetch_assoc($post_img);
$totalRows_post_img = mysql_num_rows($post_img);

mysql_select_db($database_connection, $connection);
$query_post = "SELECT * FROM tb_post";
$post = mysql_query($query_post, $connection) or die(mysql_error());
$row_post = mysql_fetch_assoc($post);
$totalRows_post = mysql_num_rows($post);

$colname_post_id = "-1";
if (isset($_GET['Post_id'])) {
  $colname_post_id = $_GET['Post_id'];
}
mysql_select_db($database_connection, $connection);
$query_post_id = sprintf("SELECT * FROM tb_post WHERE Post_id = %s", GetSQLValueString($colname_post_id, "int"));
$post_id = mysql_query($query_post_id, $connection) or die(mysql_error());
$row_post_id = mysql_fetch_assoc($post_id);
$totalRows_post_id = mysql_num_rows($post_id);
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
    <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp;<a href="index.php">กลับเมนูหลัก</a>&nbsp;<a href="add_post.php">กลับหน้าเพิ่มกระทู้</a>&nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
    </h4>
    <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0" id="top">
      <tr>
        <td height="40" colspan="3" align="center" bgcolor="#D6D5D6">แกลเลอรี่ ID:&nbsp;<?php echo $row_show_post['Post_id']; ?></td>
      </tr>
      <?php do { ?>
        <tr>
          <td width="5%" align="right" valign="top">ภาพปก/ภาพแรก</td>
          <td width="5%" align="left"> &nbsp;<img src="../admin/post/<?php echo $row_show_post['Img_index']; ?>" width="120"></td>
          <td width="40%" align="left" valign="top">คำบรรยาย : <?php echo $row_show_post['Title']; ?><br>
          วัด : <?php echo $row_show_post['Location']; ?><br>
          ว/ด/ป : <?php echo $row_show_post['DateAdd']; ?><br>
            จำนวนเข้าชม : <?php echo $row_show_post['view']; ?><br>
          สถานะ : <?php echo $row_show_post['status']; ?><br>
          โดย : <?php echo $row_show_post['Addby']; ?></td>
        </tr>
        <?php } while ($row_show_post = mysql_fetch_assoc($show_post)); ?>
    </table>
<br>
    <br>
    <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="40" colspan="4" align="center" bgcolor="#D6D5D6">รวมทั้งหมด&nbsp;<?php echo $totalRows_post_img ?> &nbsp; ภาพ&nbsp; <a href="#add_post_img" class="btn btn-info"> + เพิ่มภาพ   </a></td>
      </tr>
      <tr>
        <td width="22%" height="40" align="center" bgcolor="#D6D5D6"><strong>id</strong></td>
        <td width="11%" height="40" align="center" bgcolor="#D6D5D6"><strong>ภาพ</strong></td>
        <td width="48%" height="40" align="center" bgcolor="#D6D5D6"><strong>คำบรรยาย</strong></td>
        <td width="19%" height="40" align="center" bgcolor="#D6D5D6"><strong>ลบ</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center">img_id:<?php echo $row_post_img['Post_id_img']; ?><br>
          date:<?php echo $row_post_img['date_add']; ?></td>
          <td> &nbsp;<img src="../admin/post/<?php echo $row_post_img['img']; ?>" width="100"></td>
          <td align="left" valign="top"><?php echo $row_post_img['Detail']; ?></td>
          <td align="center">
          
          <form action="" method="post" name="del" id="del">
            <input type="submit" name="del2" id="del2" value="ลบ" class="btn btn-danger btn-md">
            <input name="Post_id_img" type="hidden" id="Post_id_img" value="<?php echo $row_post_img['Post_id_img']; ?>">
          </form>
          
          
          </td>
        </tr>
        <?php } while ($row_post_img = mysql_fetch_assoc($post_img)); ?>
    </table>
<br>
    <br>
    <form action="add_post_img_db.php" method="POST" enctype="multipart/form-data" name="add_post_img" id="add_post_img">
      <p>&nbsp;</p>
      <table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
        <tr>
          <td colspan="3" align="right" bgcolor="#EEEEEE"><a href="#top" class="btn btn-success"> กลับขึ้นด้านบน   </a>&nbsp;</td>
        </tr>
        <tr>
          <td height="40" colspan="3" align="center" bgcolor="#EEEEEE"><strong>เพิ่มภาพในแกลเลอรี่ <?php echo $row_post_id['Post_id']; ?></strong></td>
        </tr>
        <tr>
          <td align="right" valign="top" bgcolor="#f1f1f1">รายละเอียด</td>
          <td colspan="2" bgcolor="#fff"><label>
            <textarea name="Detail" cols="60" rows="3" id="Detail" required></textarea>
          </label></td>
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
          <td colspan="2" bgcolor="#f1f1f1"><input name="Post_id" type="hidden" id="Post_id" value="<?php echo $row_post_id['Post_id']; ?>">
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

mysql_free_result($post);

mysql_free_result($post_id);
?>



<!--<strong><a href="del_postery_img.php?id_post_img=<?php //echo $row_post_img['id_post_img']; ?>" 
                onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่')">&nbsp;<span class="glyphicon glyphicon-trash"></span></a></strong>
          -->
          