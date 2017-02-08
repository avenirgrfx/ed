<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$strTemplate_ID=$_GET['id'];
$strSQL="Select json_data from t_project_template where project_template_id=$strTemplate_ID";
$strRsWidgetStringArr=$DB->Returns($strSQL);
while($strRsWidgetString=mysql_fetch_object($strRsWidgetStringArr))
{
	print $strRsWidgetString->json_data;
}
?>