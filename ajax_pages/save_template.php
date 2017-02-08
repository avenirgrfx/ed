<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$project_template_name=$_POST['name'];
$json_data=$_POST['d'];

$client_id=1;
$created_by=$modified_by=$_SESSION['user_login']->user_id;

$strSQL="Insert into  t_project_template (project_template_name, json_data, created_by, doc) 
values('".$project_template_name."','".$json_data."', $created_by, now())";
$DB->Execute($strSQL);

?>