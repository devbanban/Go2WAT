<?php include ('Connections/connection.php');

	$Username = $_REQUEST["Username"];
	$Password = MD5($_REQUEST["Password"]);
	$Password2 = MD5($_REQUEST["Password2"]);	
	$Fname = $_REQUEST["Fname"];
	$Lname = $_REQUEST["Lname"];
	$Email = $_REQUEST["Email"];
	$Phone = $_REQUEST["Phone"];
	$AccessLevel = $_REQUEST["AccessLevel"];
	//ตรวจสอบ confirm password
	if($Password != $Password2){
		echo "<font color='red'> *password  ไม่ตรงกันครับ กรุณากรอกใหม่อีกครั้ง' </font>";
		echo "<br>";
		echo "<a href='register.php'> กลับไปสมัครสมาชิกใหม่อีกครั้ง   </a>";
	} 
	//ตรวจสอบ Username
	$sql = "SELECT * FROM tb_member WHERE Username = '$Username' ";
	$rs = mysql_db_query($database_connection, $sql);
	$data = mysql_fetch_array($rs);
	if ($data[0] != 0){
		echo "<font color='red'> *Username นี้เป็นสมาชิกอยู่แล้ว </font>";
		echo "<a href='index.php#regis'> กลับไปสมัครสมาชิกใหม่อีกครั้ง   </a>";
	}

// upload img profile
	$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//เพิ่มรูปภาพ 
$upload=$_FILES['img'];
if($upload <> '') {   //not select img

$path="formember/mimg/";

//เอาชื่อไฟล์ที่มีอักขระแปลกๆออก
	$remove_these = array(' ','`','"','\'','\\','/','_');
	$newname = str_replace($remove_these, '', $_FILES['img']['name']);
 
	//Make the filename unique
	$newname = time().'-'.$newname;

	//Save the uploaded the file to another location

$path_copy=$path.$newname;
$path_link="formember/mimg/".$newname;

//คัดลอกไฟล์ไปเก็บที่เว็บเซริ์ฟเวอร์
move_uploaded_file($_FILES['img']['tmp_name'],$path_copy);  
	
	
	
	
	

//	else {
//		$date = date("d-m-Y"); //กำหนดวันที่และเวลา
//		//$name = str_replace($remove_these, '', $_FILES['img']['name']);
//		$name = $_FILES['img']['name'];
//
//		$name = time().'-'.$name;
//
//		//$name = $_FILES["img"]["name"].$data;
//		$path_copy=$path.$name;
//		$path = $_FILES["img"]["tmp_name"];
//		//move_uploaded_file($path.$name, "formember/mimg/".$name);
//		$path_link="formember/mimg/".$name;
//		move_uploaded_file($_FILES['img']['tmp_name'],$path_copy);  

		
	
	}
	
	
		$sql = "INSERT INTO tb_member (Username, Password, Fname, Lname, Email, Phone, AccessLevel,img) 
		VALUES('$Username', '$Password', '$Fname', '$Lname', '$Email','$Phone', '$AccessLevel','$newname')";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	
	//if(mysql_query($sql)){
		//header("location:register_ok.php?url=register_complete");
			
	mysql_close($connection);
	echo "<p align='center'> ";	
	echo " สมัครสมาชิกเรียบร้อยแล้วครับ <br/>";
	echo "<img src='img/loading.gif'>";
	echo "<a href='index.php'> กลับหน้าหลัก </a>";
	
	
	echo "</p>";
	echo "<meta http-equiv ='refresh' content = '3;URL=index.php'> ";

?>
