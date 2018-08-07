
<meta charset="UTF-8">
<?php
include("../Connections/connection.php");

$Member_id = $_REQUEST["Member_id"];

$sql = "DELETE FROM tb_member WHERE Member_id='$Member_id' ";
$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql " . mysql_error());

mysql_close($connection);

if($result){
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลเรียบร้อยแล้ว');";
	echo "window.location = 'add_admin.php'; ";
	echo "</script>";
}
else{
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลไม่สำเร็จ');";
	echo "</script>";
}
?> 













?>
