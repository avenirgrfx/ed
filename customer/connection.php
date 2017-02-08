<?php
$hostname = "localhost";
$username = "root";
$password = "K$[=Sv6/*H82+qt&";
$db_name = "db_energydas2";

$conn = mysql_connect($hostname,$username,$password) ;
if(!$conn)
{
    die("Unable to connect.");
}
mysql_select_db($db_name,$conn) or die(mysql_error());
?>