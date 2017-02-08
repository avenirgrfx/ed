<?php
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/gallery.class.php');

$DB = new DB;

$site_id = $_GET['site_id'];

$strSQL="Select * from t_building where site_id = $site_id order by building_name";
$strRsBuildingArr=$DB->Returns($strSQL);

echo "<option value=''>Select Building</option>";

while($strRsBuilding=mysql_fetch_object($strRsBuildingArr)){
    echo "<option value='$strRsBuilding->building_id' portfolio_status='$strRsBuilding->portfolio_status'>$strRsBuilding->building_name</option>";
}