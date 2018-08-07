
<meta charset="UTF-8">
<?php
include("../Connections/connection.php");

$News_id = $_REQUEST["News_id"];

$sql = "DELETE FROM tb_news WHERE News_id='$News_id' ";
$result = mysql_db_query($database_connection, $sql) or die ("Error in query: $sql " . mysql_error());

mysql_close($connection);

if($result){
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลเรียบร้อยแล้ว');";
	echo "window.location = 'add_news.php'; ";
	echo "</script>";
}
else{
	echo "<script type='text/javascript'>";
	echo "alert('ลบข้อมูลไม่สำเร็จ');";
	echo "</script>";
}
?> 

