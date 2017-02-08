<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$strSQL="Select parent_id from t_category where category_id=".$_GET['id'];
$strRsCategoryArr=$DB->Returns($strSQL);
while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
{
	$parent_id=$strRsCategory->parent_id;
}

$strSQL="Delete from t_category where category_id=".$_GET['id'];
$DB->Execute($strSQL);

$strSQL="Update t_category set parent_id=$parent_id where parent_id=".$_GET['id'];
$DB->Execute($strSQL);

?>