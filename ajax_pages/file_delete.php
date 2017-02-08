<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
$DB=new DB;
$Client=new Client;

$file_id=$_GET['file_id'];
$folder_id=$_GET['folder_id'];
if($file_id<>"")
{
	$strSQL="Select * from t_client_files_under_folder where client_files_under_folder_id= $file_id";
	$strRsFileToDeleteArr=$DB->Returns($strSQL);
	if($strRsFileToDelete=mysql_fetch_object($strRsFileToDeleteArr))
	{
		unlink(AbsPath."customer_files/".$strRsFileToDelete->source_file_name);
		$strSQL="Delete from t_client_files_under_folder where client_files_under_folder_id= $file_id";
		$DB->Execute($strSQL);
	}
}
elseif($folder_id<>"")
{
	$strSQL="Select count(*) as Total from t_client_files_under_folder where sub_folder_id= $folder_id";
	$strRsFileToDeleteArr=$DB->Returns($strSQL);
	$strRsFileToDelete=mysql_fetch_object($strRsFileToDeleteArr);
	if($strRsFileToDelete->Total>0)
	{
		exit("Can't Delete");
	}
	else
	{
		$strSQL="Delete from t_client_files_under_folder where client_files_under_folder_id= $folder_id";
		$DB->Execute($strSQL);
	}
}
?>