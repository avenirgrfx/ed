<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');
$DB=new DB;
$Building=new Building;
$serial=$_GET['serial'];
$strSiteNamearr=$Building->GetClientSitesByClientID( $_SESSION['client_id'], $serial);
print $strSiteNamearr[0][1];
?>