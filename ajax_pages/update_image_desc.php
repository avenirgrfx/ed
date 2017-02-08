<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$strSQL="Update t_control_image set image_description='".$_POST['desc']."', dom=now() where image_id=".$_POST['id'];
$DB->Execute($strSQL);
?>