<meta charset="utf-8" />
<?php 

include("../Connections/connection.php");
	$Post_id = $_REQUEST["Post_id"];
	$Detail = $_REQUEST["Detail"];
	
	
	
	
// upload img 
	$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//เพิ่มรูปภาพ 
$upload=$_FILES['img'];
if($upload <> '') {   //not select img

$path="../admin/post/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['img']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="../admin/post/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['img']['tmp_name'],$path_copy);  
		
	
	}
	
	
		$sql = "INSERT INTO tb_post_img (Post_id, Detail, img) 
		VALUES('$Post_id', '$Detail', '$newname')";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	mysql_close($connection);
	
	echo "<p align='center'>";
	echo "<img src='../img/loading.gif'>";
	echo "<b> เพิ่มรูปภาพไปยังอัลบั้ม ID : </b>";
	echo "<font color='#0066FF'>  $Post_id </font>";
	echo  "<b> เรียบร้อยแล้วครับ </b>";
	echo "</p>";
	
echo "<meta http-equiv ='refresh' content = '1.5;add_post_img.php?Post_id=$Post_id '> ";?>
