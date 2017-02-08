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
$strSiteID=$strSiteNamearr[0][0];

if($strSiteID=="" or $strSiteID==0)
	exit();

$strSQL="Select * from t_building where site_id=$strSiteID";

$strRsBuildingsArr=$DB->Returns($strSQL);
?>
<select onchange="ChangeBuildingDropdown(this.value)" name="ddlBuildingForSite" id="ddlBuildingForSite" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
	<?php 
	while($strRsBuildings=mysql_fetch_object($strRsBuildingsArr))
	{
	?>
	<option value="<?php echo $strRsBuildings->building_id;?>"><?php echo $strRsBuildings->building_name;?></option>
    <?php  }?>
</select>