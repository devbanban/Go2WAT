<meta charset="utf-8" />
<?php 

include("../Connections/connection.php");
	$Title = $_REQUEST["Title"];
	$Detail = $_REQUEST["Detail"];
	$Location = $_REQUEST["Location"];	
	$Addby = $_REQUEST["Addby"];
	$status = $_REQUEST["status"];
	
	
// upload img 
	$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//เพิ่มรูปภาพ 
$upload=$_FILES['File_news'];
if($upload <> '') {   //not select img

$path="../admin/file_news/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['File_news']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="../admin/file_news/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['File_news']['tmp_name'],$path_copy);  
		
	
	}
	
	
		$sql = "INSERT INTO tb_news (Title, Detail, Location, File_news, status, Addby) 
		VALUES('$Title', '$Detail', '$Location', '$newname', '$status','$Addby')";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	mysql_close($connection);
	
	echo "<p align='center'>";
	echo "<img src='../img/loading.gif'>";
	echo "<br/>";
	echo  "เพิ่มข่าวเรียบร้อยแล้วครับ";
	echo "</p>";
	
 echo "<meta http-equiv ='refresh' content = '1.5;URL=add_news.php?url=add_ok'> ";
?>
