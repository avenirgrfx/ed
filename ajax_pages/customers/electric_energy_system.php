<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
$building_id=Globals::Get('building_id');

/*$strParentID=25; // Exclude Indoor Air Quality
$arrSystemExclude=Globals::GetSystemIDs($strParentID, 3);
$arrSystemExclude=implode(",",$arrSystemExclude);*/



$system_id=Globals::Get('system_type');
if($system_id=='' or $system_id==0) $system_id=4;

$strParentID=$system_id; // Exclude Indoor Air Quality
/*$arrSystemInclude=Globals::GetSystemIDs($strParentID, 3);
$arrSystemInclude=implode(",",$arrSystemInclude);
$strSQL="Select system_name, system_id from t_system where system_id in (Select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id and system_id in ($arrSystemInclude))  order by system_name ASC";
*/

$strSQL="Select  distinct(t_system.system_name), t_system.system_id  from t_system_node, t_system 
where t_system.system_id=t_system_node.system_id 
and t_system_node.parent_parent_parent_id=$strParentID
and building_id=$building_id";

/*$strSQL="Select system_name, system_id from t_system where parent_parent_parent_id=$strParentID";
print $strSQL;*/
$strRsSystemIDsArr=$DB->Returns($strSQL);

$strSQL="Select * from t_system where system_id=$strParentID";
$strRsSystemArr=$DB->Returns($strSQL);
if($strRsSystem=mysql_fetch_object($strRsSystemArr))
{
	$system_name=$strRsSystem->system_name;
}
?>
<script type="text/javascript">
function ShowSystemEnergyDetails(strSystemID)
{
	if($('#system_energy_plus_minus_'+strSystemID).html()=='+')
	{
		$.get("<?php echo URL?>ajax_pages/customers/electric_energy_system_details.php",
		{
			SystemID:strSystemID,
			building_id: <?php echo $building_id; ?>,
		},
		function(data,status){
			$('#system_energy_plus_minus_'+strSystemID).html('-');
			$('#ShowSystemEnergyDetails_'+strSystemID).html(data);		
		});	
	}
	else
	{
		$('#system_energy_plus_minus_'+strSystemID).html('+');
			$('#ShowSystemEnergyDetails_'+strSystemID).html('');
	}
}
</script>




<div style="font-size:20px; text-decoration:underline;"><?php echo $system_name?></div>
    
<div style="background-color:#666666; margin-top:5px;">
    <div style="float:left; width:5%;">&nbsp;</div>
    <div style="float:left; width:35%;" class="header">COMPONENT</div>
    <div style="float:left; width:15%;" class="header">STATUS</div>
    <div style="float:left; width:20%; margin:0px 2%;" class="header">EFFICIENCY</div>
    <div style="float:left; width:20%;" class="header">OPTIMUM</div>
    <div class="clear"></div>
</div>
    
<div style="height:170px; overflow-y:scroll;" class="myscroll">
    
    
    <?php
		$iCtr==0;
    	while($strRsSystemIDs=mysql_fetch_object($strRsSystemIDsArr))
		{
			$iCtr++;
	?>
    <div class="<?php if($iCtr % 2==1){?>odd<?php }else{?>even<?php }?>">
        <div style="float:left; width:5%; text-align:center; font-weight:bold; cursor:pointer;" onclick="ShowSystemEnergyDetails('<?php echo $strRsSystemIDs->system_id?>')" id="system_energy_plus_minus_<?php echo $strRsSystemIDs->system_id?>">+</div>
        <div style="float:left; width:35%;" title="<?php echo $strRsSystemIDs->system_name;?>"><?php echo Globals::PrintDescription_1($strRsSystemIDs->system_name,15);?></div>
        <div style="float:left; width:15%;">CHK</div>
        <div style="float:left; width:20%;  margin:0px 2%;">36%</div>
        <div style="float:left; width:20%;">44%</div>
        <div class="clear"></div>
    </div>
    
    <div id="ShowSystemEnergyDetails_<?php echo $strRsSystemIDs->system_id?>"></div>
    
    <?php }?>
    
</div>