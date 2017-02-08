<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

if($_POST['type']=="edit")
{
	$strIDArr= explode("~", $_POST['id']);
	$strID=$strIDArr[1];
	$strText=$_POST['updatedText'];
	$strSQL="Update t_client_type set client_type='".$strText."' where client_type_id=$strID";
	$DB->Execute($strSQL);
	print "Updated";
}
else
{
	$strIDArr= explode("~", $_GET['id']);
	$strID=$strIDArr[1];
	
	if($strID=="" or $strID==0)
	{
		print "0";
		exit();
	}	
	$strSQL="Delete from t_client_type where client_type_id=$strID";
	$DB->Execute($strSQL);
	print "1";
}



?>