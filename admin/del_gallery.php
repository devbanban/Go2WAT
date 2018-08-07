
<meta charset="UTF-8">
<?php
include("../Connections/connection.php");

$id_gall = $_REQUEST["id_gall"];

$sql = "DELETE FROM tb_gallery WHERE id_gall='$id_gall' ";
$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql " . mysql_error());

mysql_close($connection);

if($result){
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลเรียบร้อยแล้ว');";
	echo "window.location = 'add_gallery.php'; ";
	echo "</script>";
}
else{
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลไม่สำเร็จ');";
	echo "</script>";
}
?>