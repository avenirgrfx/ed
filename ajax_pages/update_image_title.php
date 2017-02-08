<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$strSQL="Update t_control_image set image_name='".$_POST['image_name']."', dom=now() where image_id=".$_POST['id'];
$DB->Execute($strSQL);
?>