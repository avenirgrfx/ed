<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath.'classes/building.class.php');
$DB=new DB;
$Building=new Building();
$System= new System();
$strSiteID=$_GET['site_id'];
?>
<?php 
if($_GET['mode']=="building_system_dropdown"){
   $building_id = $_GET['building_id'];
   $page_no =  $_GET['page_no'];
   $strSQL="select system from t_building_system_image where building_id=".$building_id." and page_no =".$page_no;
   $strSystemImagedropdown = $DB->Returns($strSQL);
   while($strSystemImagedropdownArr = mysql_fetch_object($strSystemImagedropdown)){
       $selectedSystem = $strSystemImagedropdownArr->system;
   }
   ?>
<select id="ddlSystem1" name="ddlSystem" style="width: 150px;font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;"><?php $System->ListSystems($selectedSystem);?></select>
<?php exit;
}
?>
<?php
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
	<option value="<?php echo $strRsBuildings->building_id;?>">Building - <?php echo $strRsBuildings->building_name;?></option>
    <?php  }?>
</select>

