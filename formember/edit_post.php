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

$colname_edit_post = "-1";
if (isset($_GET['Post_id'])) {
  $colname_edit_post = $_GET['Post_id'];
}
mysql_select_db($database_connection, $connection);
$query_edit_post = sprintf("SELECT * FROM tb_post WHERE Post_id = %s", GetSQLValueString($colname_edit_post, "int"));
$edit_post = mysql_query($query_edit_post, $connection) or die(mysql_error());
$row_edit_post = mysql_fetch_assoc($edit_post);
$totalRows_edit_post = mysql_num_rows($edit_post);

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
    <title>Go 2 wat </title>

    <!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/wat.css" rel="stylesheet" />
<script type="text/javascript" src="ckeditor/ckeditor.js"></script> <!--สร้าง ckeditor-->
<script src="../js/jquery-2.1.1.min.js"></script>
<script src="../js/jquery.form.min.js"> </script>
<script>
$(function() {
	$(document).on('change', '#File_news', function() {
		if(this.files[0].size > 307200) {
			alert('ไฟล์ภาพข่าวมีขนาดใหญ่เกินกำหนด (300 KB) กรุณา Resize รูปภาพก่อนทำการ Upload ขอบคุณครับ');
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
  <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp; <a href="index.php">กลับเมนูหลัก</a>&nbsp; &nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
  </h4>
  <br>
 <form method="POST" action="edit_post_db.php" enctype="multipart/form-data" name="edit_post" id="edit_post">
   <p>&nbsp;</p>
   <table width="70%" border="0" align="center" cellpadding="3" cellspacing="3">
     <tr>
       <td height="40" colspan="2" align="center" bgcolor="#EEEEEE"><strong>แก้ไขกระทู้</strong></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">id: </td>
       <td bgcolor="#f1f1f1"><?php echo $row_edit_post['Post_id']; ?></td>
     </tr>
     <tr>
       <td width="20%" align="right" bgcolor="#f1f1f1">Title</td>
       <td width="80%" bgcolor="#f1f1f1">&nbsp;
         <label for="Title"></label>
         <input name="Title" type="text" required id="Title" placeholder="ชื่ออัลบั้ม *ไม่เกิน 150 ตัวอักษร " value="<?php echo $row_edit_post['Title']; ?>" size="70" maxlength="150"></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td width="20%" align="right" valign="top" bgcolor="#f1f1f1">Detail</td>
       <td width="80%" bgcolor="#f1f1f1">&nbsp;
         <label for="Detail"></label>
         <textarea name="Detail" cols="70" rows="10" required id="Detail" placeholder="คำอธิบาย"><?php echo $row_edit_post['Detail']; ?></textarea></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td width="20%" align="right" bgcolor="#f1f1f1">Locatoin</td>
       <td width="80%" bgcolor="#f1f1f1">&nbsp;
         <label for="Location"></label>
         <input name="Location" type="text" required id="Location" placeholder="ชื่อวัด *ไม่เกิน 150 ตัวอักษร " value="<?php echo $row_edit_post['Location']; ?>" size="40" maxlength="150"></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1"><input name="status" type="hidden" id="status" value="Y">
         <input name="Post_id" type="hidden" id="Post_id" value="<?php echo $row_edit_post['Post_id']; ?>"></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">Img Index</td>
       <td bgcolor="#f1f1f1">&nbsp;
         <label>
           <input name="Img_index" type="file" required id="Img_index" value="<?php echo $row_edit_post['Img_index']; ?>">
           * ขนาดไฟล์ภาพไม่เกิน 500kb </label></td>
     </tr>
     <tr>
       <td bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td colspan="2" align="center" bgcolor="#f1f1f1"><input type="reset" name="button2" id="button2" value="Reset" class="btn btn-default">
         &nbsp;          &nbsp;
         <input type="submit" name="button" id="button" value="+ ปรับปรุง" class="btn btn-primary"></td>
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
 
 
 
 
 <script src="../js/bootstrap.min.js"></script> 
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
 <script src="../js/jquery-1.11.2.min.js"></script>
 <!-- Include all compiled plugins (below), or include individual files as needed -->


  </body>
</html>
<?php
mysql_free_result($edit_post);

mysql_free_result($mem);
?>
