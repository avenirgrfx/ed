<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$strWidget_ID=$_GET['id'];
$strSQL="Select json_data from t_project_widget where project_widget_id=$strWidget_ID";
$strRsWidgetStringArr=$DB->Returns($strSQL);
while($strRsWidgetString=mysql_fetch_object($strRsWidgetStringArr))
{
	print $strRsWidgetString->json_data;
}
?>