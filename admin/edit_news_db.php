<meta charset="utf-8" />
<?php 

include("../Connections/connection.php");
	$News_id = $_REQUEST["News_id"];
	$Title = $_REQUEST["Title"];
	$Detail = $_REQUEST["Detail"];
	$Location = $_REQUEST["Location"];	
	
	
	
// upload img 
	$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//เพิ่มรูปภาพ 
$upload=$_FILES['File_news'];
if($upload <> '') {   //not select img

$path="file_news/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['File_news']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="file_news/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['File_news']['tmp_name'],$path_copy);  
		
	
	}
	
	
		$sql = "UPDATE  tb_news SET Title='$Title', Detail='$Detail', Location='$Location', File_news='$newname' WHERE News_id='$News_id' 
		";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	mysql_close($connection);
	
	echo "<p align='center'>";
	echo "<img src='../img/loading.gif'>";
	echo "<br/>";
	echo  "แก้ข่าวเรียบร้อยแล้วครับ";
	echo "</p>";
	
 echo "<meta http-equiv ='refresh' content = '3;URL=add_news.php?url=edit_ok'> ";
?>
