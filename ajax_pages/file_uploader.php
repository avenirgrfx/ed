<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
$DB=new DB;
$Client=new Client;

$arrDeniedFileType=array('application/x-msdownload','application/x-sh');

$fileName = $_FILES["file1"]["name"]; // The file name
$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["file1"]["type"]; // The type of file it is
$fileSize = $_FILES["file1"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true

if(in_array(strtolower($fileType),$arrDeniedFileType))
{
	echo "Invalid File Format";
	exit();
}

$fileName1=time()."_".$fileName;
if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please select a file.";
    exit();
}
if(move_uploaded_file($fileTmpLoc, AbsPath."customer_files/$fileName1"))
{
	$client_id=$_POST['client_id'];
	$folder_id=$_POST['folder_id'];
	$folder_type=1;
	$file_name=$fileName;
	$source_file_name=$fileName1;
	$sub_folder_id=$_POST['sub_folder_id'];
	$folder_name='';
	$created_by=$_SESSION['user_login']->user_id;
	
    $strSQL="Insert into t_client_files_under_folder(client_id, folder_id, file_name, source_file_name, folder_type, sub_folder_id, folder_name, doc, created_by)
	Values($client_id, $folder_id, '$file_name', '$source_file_name', $folder_type, $sub_folder_id, '$folder_name',   now(), $created_by)";
	
	$DB->Execute($strSQL);
	
	echo "DONE";
}
else
{
    echo "FAILED";
}

?>