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
$MM_authorizedUsers = "A";
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "y")) {
  $updateSQL = sprintf("UPDATE tb_news SET status=%s WHERE News_id=%s",
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['News_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "add_news.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "n")) {
  $updateSQL = sprintf("UPDATE tb_news SET status=%s WHERE News_id=%s",
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['News_id'], "int"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());

  $updateGoTo = "add_news.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

mysql_select_db($database_connection, $connection);
$query_listnews = "SELECT * FROM tb_news ORDER BY News_id DESC";
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

$queryString_listnews = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_listnews") == false && 
        stristr($param, "totalRows_listnews") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_listnews = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_listnews = sprintf("&totalRows_listnews=%d%s", $totalRows_listnews, $queryString_listnews);
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
  <h4 align="center"> hi: <?php echo $row_mem['Fname']; ?>&nbsp; <a href="adminpage.php">กลับเมนูหลัก</a>&nbsp; &nbsp; <a href="<?php echo $logoutAction ?>">ออกจากระบบ</a><br>
  </h4>
  <table border="1" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="40" colspan="11" align="center" bgcolor="#D6D6D6"><h4>จัดการข่าวสาร</h4>&nbsp;&nbsp;  <strong><a href="#add_news">+ข่าว</a></strong><br>
แสดงรายการที่ <?php echo ($startRow_listnews + 1) ?>&nbsp; ถึง&nbsp; <?php echo min($startRow_listnews + $maxRows_listnews, $totalRows_listnews) ?>&nbsp;รวมทั้งหมด&nbsp; <?php echo $totalRows_listnews ?> &nbsp;รายการ <br>

 <table border="0" align="center">
   <tr>
     <td><?php if ($pageNum_listnews > 0) { // Show if not first page ?>
       <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, 0, $queryString_listnews); ?>"><img src="First.gif"></a>
       <?php } // Show if not first page ?></td>
     <td><?php if ($pageNum_listnews > 0) { // Show if not first page ?>
       <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, max(0, $pageNum_listnews - 1), $queryString_listnews); ?>"><img src="Previous.gif"></a>
       <?php } // Show if not first page ?></td>
     <td><?php if ($pageNum_listnews < $totalPages_listnews) { // Show if not last page ?>
       <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, min($totalPages_listnews, $pageNum_listnews + 1), $queryString_listnews); ?>"><img src="Next.gif"></a>
       <?php } // Show if not last page ?></td>
     <td><?php if ($pageNum_listnews < $totalPages_listnews) { // Show if not last page ?>
       <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, $totalPages_listnews, $queryString_listnews); ?>"><img src="Last.gif"></a>
       <?php } // Show if not last page ?></td>
   </tr>
 </table></td>
      </tr>
    <tr>
      <td width="3%" height="40" align="center" bgcolor="#D6D6D6"><strong>id</strong></td>
      <td width="30%" height="40" align="center" bgcolor="#D6D6D6"><strong>Title</strong></td>
      <td width="14%" height="40" align="center" bgcolor="#D6D6D6"><strong>Location</strong></td>
      <td width="12%" height="40" align="center" bgcolor="#D6D6D6"><strong>Addby</strong></td>
      <td width="14%" height="40" align="center" bgcolor="#D6D6D6"><strong>DateAdd</strong></td>
      <td width="5%" height="40" align="center" bgcolor="#D6D6D6"><strong>view</strong></td>
      <td width="5%" height="40" align="center" bgcolor="#D6D6D6"><strong>status</strong></td>
      <td colspan="2" align="center" bgcolor="#D6D6D6"><strong>status</strong></td>
      <td width="5%" align="center" bgcolor="#D6D6D6"><strong>del</strong></td>
      <td width="5%" align="center" bgcolor="#D6D6D6"><strong>Edit</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_listnews['News_id']; ?></td>
        <td><a href="detail_news.php?News_id=<?php echo $row_listnews['News_id']; ?>" title="คลิกเพื่อดูรายละเอียดทั้งหมด"><?php echo $row_listnews['Title']; ?></a></td>
        <td><?php echo $row_listnews['Location']; ?></td>
        <td align="center"><?php echo $row_listnews['Addby']; ?></td>
        <td align="center"><?php echo $row_listnews['DateAdd']; ?></td>
        <td align="center"><?php echo $row_listnews['view']; ?></td>
        <td align="center"><?php echo $row_listnews['status']; ?></td>
        <td><form action="<?php echo $editFormAction; ?>" method="POST" name="y" id="y">
          <input type="submit" name="y2" id="y2" value="show" class="btn btn-primary btn-xs">
          <br>
          <input name="Status" type="hidden" id="Status" value="Y">
          <input name="News_id" type="hidden" id="News_id" value="<?php echo $row_listnews['News_id']; ?>">
          <input type="hidden" name="MM_update" value="y">
        </form></td>
        <td><form action="<?php echo $editFormAction; ?>" method="POST" name="n" id="n">
          <input type="submit" name="n2" id="n2" value="hide" class="btn btn-warning btn-xs">
          <br>
          <input name="News_id" type="hidden" id="News_id" value="<?php echo $row_listnews['News_id']; ?>">
          <input name="Status" type="hidden" id="Status" value="N">
          <input type="hidden" name="MM_update" value="n">
        </form></td>
        <td align="center">
        <a href="del_news.php?News_id=<?php echo $row_listnews['News_id']; ?>" 
                onclick="return confirm('คุณต้องการลบข้อมูล <?php echo $row_listnews['News_id']; ?> หรือไม่ ')"><span class="glyphicon glyphicon-trash"> </span></a></strong>
        </td>
        <td align="center"><a href="edit_news.php?News_id=<?php echo $row_listnews['News_id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
      </tr>
      <?php } while ($row_listnews = mysql_fetch_assoc($listnews)); ?>
  </table>
<br>
<table border="0" align="center">
  <tr>
     <td><?php if ($pageNum_listnews > 0) { // Show if not first page ?>
         <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, 0, $queryString_listnews); ?>"><img src="First.gif"></a>
         <?php } // Show if not first page ?></td>
     <td><?php if ($pageNum_listnews > 0) { // Show if not first page ?>
         <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, max(0, $pageNum_listnews - 1), $queryString_listnews); ?>"><img src="Previous.gif"></a>
         <?php } // Show if not first page ?></td>
     <td><?php if ($pageNum_listnews < $totalPages_listnews) { // Show if not last page ?>
         <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, min($totalPages_listnews, $pageNum_listnews + 1), $queryString_listnews); ?>"><img src="Next.gif"></a>
         <?php } // Show if not last page ?></td>
     <td><?php if ($pageNum_listnews < $totalPages_listnews) { // Show if not last page ?>
         <a href="<?php printf("%s?pageNum_listnews=%d%s", $currentPage, $totalPages_listnews, $queryString_listnews); ?>"><img src="Last.gif"></a>
         <?php } // Show if not last page ?></td>
   </tr>
 </table>
 </p>
<br>
 <br>
 <form method="POST" action="add_news_db.php" enctype="multipart/form-data" name="add_news" id="add_news">
   <p>&nbsp;</p>
   <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3">
     <tr>
       <td height="40" colspan="2" align="center" bgcolor="#EEEEEE"><strong>เพิ่มข่าวสาร</strong></td>
       </tr>
     <tr>
       <td width="20%" align="right" bgcolor="#f1f1f1">หัวข้อข่าว&nbsp; </td>
       <td width="80%" bgcolor="#f1f1f1"><label for="Title"></label>
         <input name="Title" type="text" id="Title" size="70" required></td>
     </tr>
     <tr>
       <td align="right" valign="top" bgcolor="#f1f1f1">รายละเอียด&nbsp; </td>
       <td bgcolor="#f1f1f1"><label for="Detail"></label>
         <textarea name="Detail" cols="70" rows="10" id="Detail"></textarea></td>
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
         <input name="Location" type="text" id="Location" size="50" placeholder="ระบุชื่อวัด" required>
       </label></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1">&nbsp;</td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">ภาพประกอบข่าว&nbsp; </td>
       <td bgcolor="#f1f1f1">
          <input type="file" name="File_news" id="File_news">
          &nbsp; <font color="red">   * ไม่เกิน 300KB  Width = 800px  </font></td>
     </tr>
     <tr>
       <td align="right" bgcolor="#f1f1f1">&nbsp;</td>
       <td bgcolor="#f1f1f1"><input name="status" type="hidden" id="status" value="Y"> <input name="Addby" type="hidden" id="Addby" value="<?php echo $row_mem['Username']; ?>"></td>
     </tr>
     <tr>
       <td colspan="2" align="center" bgcolor="#f1f1f1"><input type="reset" name="button2" id="button2" value="Reset" class="btn btn-default"> 
         &nbsp;          &nbsp;  <input type="submit" name="button" id="button" value="+ เพิ่มข่าว" class="btn btn-primary"></td>
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
