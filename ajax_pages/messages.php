<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
?>

<div style="border:1px solid #CCCCCC; padding:50px; height:200px;">MESSAGES</div>