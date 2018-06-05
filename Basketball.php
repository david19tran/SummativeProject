<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_Basketball = "localhost";
$database_Basketball = "basketball";
$username_Basketball = "root";
$password_Basketball = "";
$Basketball = @mysql_pconnect($hostname_Basketball, $username_Basketball, $password_Basketball) or trigger_error(mysql_error(),E_USER_ERROR); 
?>