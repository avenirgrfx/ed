<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_id = Globals::Get('building_id');

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}

//$strSQL = "Select * from t_system_node where building_id=$building_id and delete_flag=0";
$strSQL = "Select system_node_id, node_serial, custom_name from t_system_node where delete_flag=0 and building_id=".$building_id." and temperature_humidity=1 and project_id in (Select projects_id from t_projects where t_projects.room_id=0 and building_id=".$building_id.")";
$strSystemNodesArr = $DB->Lists(array("Query"=>$strSQL));

?>
<link rel="stylesheet" type="text/css" href="<?php echo URL?>css/master.css">
<div style="background-color:#FFFFFF; border-radius:10px; height:350px; overflow-y: scroll;" class="myscroll">
    <div style="text-decoration: underline; font-weight: bold; font-size: 20px; padding: 20px; text-align: center;">BUILDING NODES</div>
    <div>
        <table align="center" cellpadding="3px;">
            <tr style="background: #d3d3d3;">
                <th style="padding: 0 25px; border: 1px solid #d3d3d3;">NODE</th>
                <th style="padding: 0 25px;">TEMP</th>
                <th style="padding: 0 25px;">HUMIDITY</th>
                <th style="padding: 0 25px;">UPDATED</th>
                <th style="padding: 0 25px;">TIME</th>
            </tr>

            <?php $i=1; foreach ($strSystemNodesArr as $strSystemNode) {
                $strSQL = "Select * from t_lhnode_$strSystemNode->node_serial order by synctime desc limit 1";
                $strBuildingNodesArr = $DB->Returns($strSQL);
                while ($strBuildingNode = mysql_fetch_object($strBuildingNodesArr)) {          
                    date_default_timezone_set('UTC');
                    $latest_date = strtotime($strBuildingNode->synctime);
                    date_default_timezone_set($time_zone);
                    $day = date("m/d/Y", $latest_date);
                    $time = date("H:i", $latest_date);
                ?>
            <tr>
                <td style="padding: 0 25px;" title='<?=$strSystemNode->custom_name. " (" .$strSystemNode->node_serial . ")"?>'><?="Node ".$i?></td>
                <td style="padding: 0 25px;"><?=  number_format($strBuildingNode->temperature)?>&deg;F</td>
                <td style="padding: 0 25px;"><?=  number_format($strBuildingNode->humidity)?>%</td>
                <td style="padding: 0 25px;"><?=$day?></td>
                <td style="padding: 0 25px;"><?=$time?></td>
            </tr>
            <?php $i++; } }?>
        </table>
    </div>
</div>