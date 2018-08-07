<meta charset="utf-8" />
<?php 

include("../Connections/connection.php");
	$Post_id = $_REQUEST["Post_id"];
	$Title = $_REQUEST["Title"];
	$Detail = $_REQUEST["Detail"];
	$Location = $_REQUEST["Location"];	
	
	
	
// upload img 
	$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//เพิ่มรูปภาพ 
$upload=$_FILES['Img_index'];
if($upload <> '') {   //not select img

$path="post/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['Img_index']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="post/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['Img_index']['tmp_name'],$path_copy);  
		
	
	}
	
	
		$sql = "UPDATE  tb_post SET Title='$Title', Detail='$Detail', Location='$Location', Img_index='$newname' WHERE Post_id='$Post_id'
		"; 

		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	mysql_close($connection);
	
	echo "<p align='center'>";
	echo "<img src='../img/loading.gif'>";
	echo "<br/>";
	echo  "แก้ไขเรียบร้อยแล้วครับ";
	echo "</p>";
	
 echo "<meta http-equiv ='refresh' content = '2;URL=add_post.php?url=edit_ok'> ";
?>
