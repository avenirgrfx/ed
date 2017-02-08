<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');
$DB=new DB;
$Building=new Building;
$site_id=$_GET['site_id'];
$strSiteNamearr=$Building->GetClientSitesByClientIDAndSiteId( $_SESSION['client_id'], $site_id);
print $strSiteNamearr[0][1];
?>