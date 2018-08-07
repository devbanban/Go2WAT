<?php require_once("../Connections/connection.php");
		$Member_id = $_REQUEST["Member_id"];
		
		$name = $_FILES["img"]["name"];
		$path = $_FILES["img"]["tmp_name"];
		move_uploaded_file($path, "mimg/".$name);
		
		$sql = "
		UPDATE tb_member SET
		img='$name'
		
		";
		
		if(!empty($name)){
			//edit img name
				}
		$sql = " WHERE Member_id = $Member_id ";
		
		
		
			
	mysql_close();
	
	if(mysql_query($sql)){
		header("location: index.php?url=saveok"); }
		else {
			echo mysql_error();
		}
		
	
?>
