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
$strBuildingID=$_GET['building_id'];
?>

<?php
if($strBuildingID=="" or $strBuildingID==0)
	exit();

$strSQL="Select * from t_projects where building_id=$strBuildingID and room_id = 0" ;

$strRsProjectsArr=$DB->Returns($strSQL);
?>
 <select id="ddlBuildingProjects" name="ddlBuildingProjects" onchange="ChangeProjectDropdown(this.value)" style="width:160px; margin-left:15px; margin-top: 5px; font-size:14px; font-weight:bold; font-family: UsEnergyEngineers;">
                         
	<?php 
	while($strRsProjects=mysql_fetch_object($strRsProjectsArr))
	{
	?>
	<option value="<?php echo $strRsProjects->projects_id;?>"><?php echo $strRsProjects->project_name;?></option>
    <?php  }?>
</select>

