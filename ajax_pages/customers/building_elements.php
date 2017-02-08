<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$type=Globals::Get('type');
?>

<?php if($type==1){?>
<div style="float:left;">
    <div style="color:#666666; font-weight:bold; font-size:16px;" id="Show_Dynamic_Buildings">Loading...</div>
</div>
<div class="clear"></div>


<div id="Container_SystemsByBuilding" style="margin-top:15px; padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; max-height:338px; overflow-y: auto;" class="myscroll">
</div>

<?php }elseif($type==2){?>



<div style="float:left;">
    <div style="color:#666666; font-weight:bold; font-size:16px;">BUILDING ELEMENTS</div>
</div>
<div class="clear"></div>
<hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">

<div>
<select name="ddlBuildingElemntsList" onchange="UpdateBuildingElementDetails(this.value, 1)" id="ddlBuildingElemntsList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
    <option value="">Select</option>
</select>
</div>

<div style="font-size:16px;" id="Building_Details_Container">Loading...</div>
    
<?php }?>