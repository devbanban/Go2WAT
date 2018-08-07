<meta charset="utf-8" />
<?php 

include("../Connections/connection.php");
	$id_gall = $_REQUEST["id_gall"];
	//$Detail = $_REQUEST["Detail"];
	
	
	
	
// upload img 
	$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//เพิ่มรูปภาพ 
$upload=$_FILES['img'];
if($upload <> '') {   //not select img

$path="../admin/gallery/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['img']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="../admin/gallery/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['img']['tmp_name'],$path_copy);  
		
	
	}
	
	
		$sql = "INSERT INTO tb_gallery_img (id_gall, img) 
		VALUES('$id_gall', '$newname')";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	mysql_close($connection);
	
	echo "<p align='center'>";
	echo "<img src='../img/loading.gif'>";
	echo "<b> เพิ่มรูปภาพไปยังอัลบั้ม ID : </b>";
	echo "<font color='#0066FF'>  $id_gall </font>";
	echo  "<b> เรียบร้อยแล้วครับ </b>";
	echo "</p>";
	
 echo "<meta http-equiv ='refresh' content = '1.5;add_gallery_img.php?id_gall=$id_gall '> ";
?>
