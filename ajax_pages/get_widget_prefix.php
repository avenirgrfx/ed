<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

$strID=$_GET['id'];
$strSQL="Select * from t_widgets where widget_id=$strID";
$strRsWidgetArr=$DB->Returns($strSQL);
while($strRsWidget=mysql_fetch_object($strRsWidgetArr))
{
	echo $strRsWidget->prefix;
}
?>