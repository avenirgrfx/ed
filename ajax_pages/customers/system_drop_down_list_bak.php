<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
$building_id=$_GET['building_id'];

$strParentID=25; // Exclude Indoor Air Quality

$strSQL="Select * from t_system where parent_id =0 and system_id not in ($strParentID) order by system_name";
$strRsSystemNamesArr=$DB->Returns($strSQL);
?>


<select name="ddlGraphType" id="ddlGraphType" onchange="ShowSystemAndDetails()" style="width:200px; font-size:14px; font-family: UsEnergyEngineers; background-color:#EFEFEF" >

<?php
while($strRsSystemNames=mysql_fetch_object($strRsSystemNamesArr))
{
	$strParentID=$strRsSystemNames->system_id;
	$arrSystemIDs=Globals::GetSystemIDs($strParentID,4);
	if(is_array($arrSystemIDs) && count($arrSystemIDs)>0)
	{
		$arrSystemIDs=implode(",",$arrSystemIDs);
	}
	else
	{
		continue;
	}
	$strSQL="Select * from t_system_node where system_id in (".$arrSystemIDs.") and building_id=$building_id";
	$strRsSystemIDsInSystemNodeArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsSystemIDsInSystemNodeArr)>0)
	{
		print '<option value="'.$strRsSystemNames->system_id.'">'.$strRsSystemNames->system_name.'</option>';
	}
}
?>
</select> 