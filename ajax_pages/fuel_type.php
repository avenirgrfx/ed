<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

if(isset($_POST) && !empty($_POST)){
    if($_POST['mode'] == "add"){
        $strSQL="insert into t_fuel_type (fuel_type, unit) values ('".$_POST['txtFuelTypeName']."', '".$_POST['ddlFuelTypeUnit']."')";
        
        $DB->Returns($strSQL);
        
        $strSQL="Select * from t_fuel_type";	
        $strFuelTypeArr=$DB->Returns($strSQL);
        
        print '<option value="0">Select Fuel Type</option>';
        
        while($strFuelType=mysql_fetch_object($strFuelTypeArr)) {
            print '<option value="'.$strFuelType->fuel_type_id.'">'.$strFuelType->fuel_type.' - '.$strFuelType->unit.'</option>';
        }
    }else if($_POST['mode'] == "delete"){
        $strSQL="delete from t_fuel_type where fuel_type_id = '".$_POST['txtFuelTypeId']."'";
        $DB->Returns($strSQL);
    }else if($_POST['mode'] == "update"){
        $strSQL="update t_fuel_type set fuel_type = '".$_POST['txtFuelTypeName']."', unit = '".$_POST['ddlFuelTypeUnit']."' where fuel_type_id = '".$_POST['txtFuelTypeId']."'";
        $DB->Returns($strSQL);
    }
    exit;
}

$strSQL="Select distinct unit from t_fuel_type";	
$strUnitArr=$DB->Returns($strSQL);

$unitArray = array();
while($strUnit=mysql_fetch_object($strUnitArr)) {
    $unitArray[] = $strUnit->unit;
}

?>
<span style="background: rgb(153, 153, 153) none repeat scroll 0% 0%; text-align: center; border-radius: 13px; font-size:16px; height: 26px; width: 26px; float: right; cursor: pointer; margin-top: 10px;" onclick="closePopup();">X</span>
<div style="text-align: center;"><h2>Manage Fuel Type</h2></div>    
<?php

$strSQL="Select * from t_fuel_type";
$strFuelTypeArr=$DB->Returns($strSQL);
while($strFuelType=mysql_fetch_object($strFuelTypeArr))
{
?>
<div style="width: 600px; padding: 10px 5px;">
    <span style="width: 220px; display: inline-block;"><?=$strFuelType->fuel_type?></span>
    <span>
        <select>
            <option>Units</option>
            <?php foreach($unitArray as $unit) {?>
            <option <?=$unit==$strFuelType->unit?"selected":""?>><?=$unit?></option>
            <?php } ?>
        </select>
    </span>
    <span>
        <input type="button" style="float:right; padding: 2px 5px;" value="Delete" onclick="DeleteFuelType('<?=$strFuelType->fuel_type_id?>')">
        <input type="button" style="float:right; padding: 2px 5px;" value="Edit" onclick="EditFuelType(this)">
        <input type="button" style="float:right; padding: 2px 5px; display: none;" value="Update" onclick="UpdateFuelType(this, '<?=$strFuelType->fuel_type_id?>')">
    </span>
</div>
<?php } ?>
<div class="clear" style="margin-bottom: 10px;"></div>