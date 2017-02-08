<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;


$strSQL="Select * from t_category where category_id=".$_GET['id'];
$strRsCategoryArr=$DB->Returns($strSQL);
while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
{
	$strSQL="Select count(*) as TotalImage from t_control_image where category_id=".$_GET['id'];
	$strRsTotlaImageArr=$DB->Returns($strSQL);
	if($strRsTotlaImage=mysql_fetch_object($strRsTotlaImageArr))
	{
		$TotalImage=$strRsTotlaImage->TotalImage;
	}
	print  $strRsCategory->parent_id."~#~".$strRsCategory->category_name."~#~".$strRsCategory->category_id."~#~".$TotalImage;
}
?>