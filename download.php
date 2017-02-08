<?php
ob_start();
session_start();
require_once("configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
$DB=new DB;
$file_id=$_GET['file_id'];

$strSQL="Select * from t_client_files_under_folder where client_files_under_folder_id=$file_id";
$strRsFileArr=$DB->Returns($strSQL);
while($strRsFile=mysql_fetch_object($strRsFileArr))
{
	$filename=AbsPath."customer_files/".$strRsFile->source_file_name;
}
//echo $filename;
$mime_type= mime_content_type ( $filename );
header('Content-Type: '.$mime_type);
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($filename) . "\""); 
ob_clean();
flush();
readfile($filename);
?>