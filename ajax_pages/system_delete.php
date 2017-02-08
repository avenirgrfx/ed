<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$strSQL="Select parent_id from t_system where system_id=".$_GET['id'];
$strRsSystemArr=$DB->Returns($strSQL);
while($strRsSystem=mysql_fetch_object($strRsSystemArr))
{
	$parent_id=$strRsSystem->parent_id;
}

$strSQL="Delete from t_system where system_id=".$_GET['id'];
$DB->Execute($strSQL);

$strSQL="Update t_system set parent_id=$parent_id where parent_id=".$_GET['id'];
$DB->Execute($strSQL);

?>