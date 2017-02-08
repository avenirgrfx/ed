<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$strSQL="Select * from t_control_image where image_id=".$_GET['id'];
$strRsImageArr=$DB->Returns($strSQL);
while($strRsImage=mysql_fetch_object($strRsImageArr))
{
	if(file_exists(AbsPath.'images/control-images/'.$strRsImage->image_path))
		unlink(AbsPath.'images/control-images/'.$strRsImage->image_path);
    if(file_exists(AbsPath.'images/control-images/'.$strRsImage->image_path2))
		unlink(AbsPath.'images/control-images/'.$strRsImage->image_path2);
		
	if(file_exists(AbsPath.'uploads/documents/'.$strRsImage->technical_file1))
		unlink(AbsPath.'uploads/documents/'.$strRsImage->technical_file1);
	
	if(file_exists(AbsPath.'uploads/documents/'.$strRsImage->technical_file2))
		unlink(AbsPath.'uploads/documents/'.$strRsImage->technical_file2);
	
	if(file_exists(AbsPath.'uploads/documents/'.$strRsImage->technical_file3))
		unlink(AbsPath.'uploads/documents/'.$strRsImage->technical_file3);
	
	if(file_exists(AbsPath.'uploads/documents/'.$strRsImage->technical_file4))
		unlink(AbsPath.'uploads/documents/'.$strRsImage->technical_file4);
	
	if(file_exists(AbsPath.'uploads/documents/'.$strRsImage->technical_file5))
		unlink(AbsPath.'uploads/documents/'.$strRsImage->technical_file5);
	
}

$strSQL="Delete from t_control_image where image_id=".$_GET['id'];
$DB->Execute($strSQL);
?>