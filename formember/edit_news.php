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

$colname_mem = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_mem = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_mem = sprintf("SELECT * FROM tb_member WHERE Username = %s", GetSQLValueString($colname_mem, "text"));
$mem = mysql_query($query_mem, $connection) or die(mysql_error());
$row_mem = mysql_fetch_assoc($mem);
$totalRows_mem = mysql_num_rows($mem);

$maxRows_listnews = 10;
$pageNum_listnews = 0;
if (isset($_GET['pageNum_listnews'])) {
  $pageNum_listnews = $_GET['pageNum_listnews'];
}
$startRow_listnews = $pageNum_listnews * $maxRows_listnews;

$colname_listnews = "-1";
if (isset($_GET['News_id'])) {
  $colname_listnews = $_GET['News_id'];
}
mysql_select_db($database_connection, $connection);
$query_listnews = sprintf("SELECT * FROM tb_news WHERE News_id = %s ORDER BY News_id DESC", GetSQLValueString($colname_listnews, "int"));
$query_limit_listnews = sprintf("%s LIMIT %d, %d", $query_listnews, $startRow_listnews, $maxRows_listnews);
$listnews = mysql_query($query_limit_listnews, $connection) or die(mysql_error());
$row_listnews = mysql_fetch_assoc($listnews);

if (isset($_GET['totalRows_listnews'])) {
  $totalRows_listnews = $_GET['totalRows_listnews'];
} else {
  $all_listnews = mysql_query($query_listnews);
  $totalRows_listnews = mysql_num_rows($all_listnews);
}
$totalPages_listnews = ceil($totalRows_listnews/$maxRows_listnews)-1;
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
  <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp; <a href="index.php"> กลับเมนูหลัก</a>&nbsp; <a href="add_news.php">กลับหน้าเพิ่มข่าว</a>&nbsp; &nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
  </h4>
  <br>
 <form method="POST" action="edit_news_db.php" enctype="multipart/form-data" name="edit_news" id="edit_news">
   <p>&nbsp;</p>
   <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3">
     <tr>
       <td height="40" colspan="2" align="center" bgcolor="#EEEEEE"><strong>แก้ไขข่าวสาร</strong></td>
       </tr>
     <tr>
       <td colspan="2" align="center" bgcolor="#f1f1f1"><img src="../admin/file_news/<?php echo $row_listnews['File_news']; ?>" width="100"><?php echo $row_listnews['File_news']; ?></td>
     </tr>
     <tr>
       <td width="20%" align="right" bgcolor="#f1f1f1">หัวข้อข่าว&nbsp; </td>
       <td width="80%" bgcolor="#f1f1f1"><label for="Title"></label>
         <input name="Title" type="text" required id="Title" value="<?php echo $row_listnews['Title']; ?>" size="70"></td>
     </tr>
     <tr>
       <td align="right" valign="top" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td align="right" valign="top" bgcolor="#f1f1f1">รายละเอียด&nbsp; </td>
       <td bgcolor="#f1f1f1"><label for="Detail"></label>
         <textarea name="Detail" cols="70" rows="10" id="Detail"><?php echo $row_listnews['Detail']; ?></textarea></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;
       
       
       <!--สำหนดคุณสมบัติ ckeitor-->
        <script type="text/javascript">
        //<![CDATA[
            CKEDITOR.replace( 'Detail',{

            skin            : 'kama',
            language        : 'en',
            extraPlugins    : 'uicolor',
            uiColor            : '#006699',
            height            : 300,
            width            : 550,

            toolbar :
                [

                //    ['Source','-','Templates'],
                   // ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
                    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                //    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],

                ],

      /*      filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
*/
            } );


        //]]>
        </script>
        <!--//-->
       
       
       
       </td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">สถานที่จัดงาน&nbsp; </td>
       <td bgcolor="#f1f1f1"><label>
         <input name="Location" type="text" required id="Location" placeholder="ระบุชื่อวัด" value="<?php echo $row_listnews['Location']; ?>" size="50">
       </label></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">ภาพประกอบข่าว&nbsp; </td>
       <td bgcolor="#f1f1f1">
          <input name="File_news" type="file" id="File_news" value="<?php echo $row_listnews['File_news']; ?>">          &nbsp; <font color="red">   * ไม่เกิน 300KB  Width = 800px  </font></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1"><input name="News_id" type="hidden" id="News_id" value="<?php echo $row_listnews['News_id']; ?>"></td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td colspan="2" align="center" bgcolor="#f1f1f1">
         
         <input type="button" value="ยกเลิก" class="btn btn-default"  onclick="window.location='add_news.php' " />
         
         &nbsp;          &nbsp;  <input type="submit" name="button" id="button" value="บันทึก" class="btn btn-primary"></td>
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
mysql_free_result($mem);

mysql_free_result($listnews);
?>
