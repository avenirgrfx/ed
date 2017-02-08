<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;


$strSQL="Select * from t_system where system_id=".$_GET['id'];
$strRsSystemArr=$DB->Returns($strSQL);
$TotalImage=0;
while($strRsSystem=mysql_fetch_object($strRsSystemArr))
{
	/*$strSQL="Select count(*) as TotalImage from t_control_image where system_id=".$_GET['id'];
	$strRsTotlaImageArr=$DB->Returns($strSQL);
	if($strRsTotlaImage=mysql_fetch_object($strRsTotlaImageArr))
	{
		$TotalImage=$strRsTotlaImage->TotalImage;
	}*/
	
	
	$strSQL="Select * from t_controls where controls='".strtoupper(trim($strRsSystem->system_name))."'";
	$strRsControlsArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsControlsArr)>0)
	{
		$Control_Flag=1;
	}
	else
	{
		$Control_Flag=0;
	}
	
	print  $strRsSystem->parent_id."~#~".$strRsSystem->system_name."~#~".$strRsSystem->system_id."~#~".$TotalImage."~#~".$strRsSystem->has_node."~#~".$strRsSystem->display_type."~#~".$strRsSystem->uom."~#~".$strRsSystem->exclude_in_calculation."~#~".$Control_Flag."~#~".$strRsSystem->complexity;
}
?>