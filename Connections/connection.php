<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connection = "localhost";
$database_connection = "db_go2wat";
$username_connection = "root";
$password_connection = "";
$connection = mysql_pconnect($hostname_connection, $username_connection, $password_connection) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES UTF8");
?>