<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/system.class.php");
require_once(AbsPath."classes/projects.class.php");

$DB=new DB;

if($_POST['id']){
    $strSiteID=$_POST['id'];
    $building_id = $_POST['building_id'];
    $energy_cost = $_POST['energy_cost'];
    $gas_cost = $_POST['gas_cost'];
    
    $strSQL = "insert into t_energy_cost (building_id, energy_cost, gas_cost, created) values ('$building_id', '$energy_cost', '$gas_cost', now()) ON DUPLICATE KEY UPDATE energy_cost = values(energy_cost), gas_cost = values(gas_cost)";
    $DB->Returns($strSQL);
}else{
    $strSiteID=$_GET['id'];
}
?>


<script type="text/javascript">
function set_cost(building_id, site_id)
{
    var energy_cost = $('#ecost_' + building_id).val();
    var gas_cost = $('#gcost_' + building_id).val();
    $('#'+site_id).html("Loading...");
    $.post("<?php echo URL ?>ajax_pages/show_building_mv_cost.php",
            {
                id: site_id,
                building_id: building_id,
                energy_cost: energy_cost,
                gas_cost: gas_cost,
            },
    function (data, status) {
        $('#'+site_id).html(data);
    });
}
</script>

<?php
$strSQL="Select * from t_building where site_id=$strSiteID";
$strRsBuildingArr=$DB->Returns($strSQL);
while($strRsBuilding=mysql_fetch_object($strRsBuildingArr))
{
	//echo "<div class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:normal;'>Building:</span> ".$strRsBuilding->building_name."</div>";
	echo "<div onclick='PlusMinusBuilding(".$strRsBuilding->building_id.")' class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:bold; font-size:20px;' id='Building_Details_Plus_Minus_".$strRsBuilding->building_id."'>-</span><span style='font-weight:normal;'>Building:</span> <span style='text-decoration:underline;'>".$strRsBuilding->building_name."</span></div>";
    
    $strSQL="Select * from t_energy_cost where building_id=".$strRsBuilding->building_id;
    $costArr=$DB->Returns($strSQL);
    $cost=mysql_fetch_object($costArr);
?>
    <div class='clear'></div>
    
    <div style='float:left; margin-left:50px;'><b style='text-decoration:underline;'>SET COST</b></div>
    <div class='clear'></div>
    <div id="Building_Node_Details_<?php echo $strRsBuilding->building_id;?>">

        <div style="float: left;">
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:200px;">Electricity Cost ($/kwh):</span>
                <input type="text" style="float:left; margin-left:15px;" value="<?=$cost->energy_cost?>" id="ecost_<?=$strRsBuilding->building_id;?>">
            </div>
            <div class='clear'></div>
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:200px;">Natural Gas Cost ($/therm):</span>
                <input type="text" style="float:left; margin-left:15px;" value="<?=$cost->gas_cost?>" id="gcost_<?=$strRsBuilding->building_id;?>">
            </div>
            <div class='clear'></div>
        </div>
        <div style="float: left; margin-top: 25px;">
            <input type="button" style="float:left; margin-left:30px;" value="SET" name="btnSET" id="btnSET" onclick="set_cost(<?=$strRsBuilding->building_id;?>, <?=$strSiteID?>)">
        </div>
        <div class='clear'></div>
    </div>
    <div class='clear'></div>
<?php } ?>