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
$upload=$_FILES['Img_index'];
if($upload <> '') {   //not select img

$path="../admin/post/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['Img_index']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="../admin/post/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['Img_index']['tmp_name'],$path_copy);  
		
	
	}
	
	
		$sql = "INSERT INTO tb_post (Title, Detail, Location, Img_index, status, Addby) 
		VALUES('$Title', '$Detail', '$Location', '$newname', '$status','$Addby')";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	mysql_close($connection);
	
	echo "<p align='center'>";
	echo "<img src='../img/loading.gif'>";
	echo "<br/>";
	echo  "เพิ่มแกลเลอรี่เรียบร้อยแล้วครับ";
	echo "</p>";
	
 echo "<meta http-equiv ='refresh' content = '1;URL=add_post.php?url=add_ok'> ";
?>
