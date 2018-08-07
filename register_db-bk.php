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
		echo "<a href='register.php'> กลับไปสมัครสมาชิกใหม่อีกครั้ง   </a>";
	}
	
	
	
	
	
	
	
	// upload img profile
	else {
		
		$name = $_FILES["img"]["name"];
		$path = $_FILES["img"]["tmp_name"];
		move_uploaded_file($path, "formember/mimg/".$name);
	}
	
	
		$sql = "INSERT INTO tb_member (Username, Password, Fname, Lname, Email, Phone, AccessLevel,img) 
		VALUES('$Username', '$Password', '$Fname', '$Lname', '$Email','$Phone', '$AccessLevel','$name')";
		
		$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql". mysql_error());
	
	//if(mysql_query($sql)){
		//header("location:register_ok.php?url=register_complete");
			
	mysql_close($connection);
		
	
	echo "<img src='img/loading.gif'>";
	echo "<a href='index.php'> กลับหน้าหลัก </a>";

?>
