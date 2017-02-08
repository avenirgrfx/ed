<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$strSQL="Select * from t_system_node order by system_node_id";
$strRsSystemNodeArr=$DB->Returns($strSQL);
while($strRsSystemNode=mysql_fetch_object($strRsSystemNodeArr))
{
	//$strSQL="Update t_system_node set parent_id=(select parent_id from t_system where system_id=".$strRsSystemNode->system_id.") where system_node_id=".$strRsSystemNode->system_node_id;
	//$strSQL="Update t_system_node set parent_parent_id=(select parent_id from t_system where system_id=".$strRsSystemNode->parent_id.") where system_node_id=".$strRsSystemNode->system_node_id;
	$strSQL="Update t_system_node set parent_parent_parent_id=(select parent_id from t_system where system_id=".$strRsSystemNode->parent_parent_id.") where system_node_id=".$strRsSystemNode->system_node_id;
	$DB->Execute($strSQL);
}
print "Done";
?>