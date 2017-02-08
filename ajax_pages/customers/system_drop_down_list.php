<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
$building_id=$_GET['building_id'];

$strParentID=25; // Exclude Indoor Air Quality
$strSQL="Select Distinct parent_parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id and parent_parent_parent_id<>$strParentID";
$strRsSystemIDArr=$DB->Returns($strSQL);
?>
<select name="ddlGraphType" id="ddlGraphType" onchange="ShowSystemAndDetails()" style="width:200px; font-size:14px; font-family: UsEnergyEngineers; background-color:#EFEFEF" >
<?php
	while($strRsSystemID=mysql_fetch_object($strRsSystemIDArr))
	{
		$strSQL="Select system_id, system_name from t_system where system_id=".$strRsSystemID->parent_parent_parent_id;
		$strRsSystemNamesArr=$DB->Returns($strSQL);
		while($strRsSystemNames=mysql_fetch_object($strRsSystemNamesArr))
		{
			print '<option value="'.$strRsSystemNames->system_id.'">'.$strRsSystemNames->system_name.'</option>';
		}
	}
?>
</select> 