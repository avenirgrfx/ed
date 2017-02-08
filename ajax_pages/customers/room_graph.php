<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');

$DB = new DB;
$building_id = Globals::Get('building_id');
$room_id = Globals::Get('room_id');

$t2=0;
$h2=0;

//$strSQL="SELECT available_system_node_serial FROM `t_system_node` where building_id = $building_id and delete_flag = 0";
$strSQL = "Select available_system_node_serial from t_system_node where delete_flag=0 and building_id=".$building_id." and project_id in (Select project_id from t_system_node where room_id=$room_id and building_id=".$building_id.")";
$resultArr =  $DB->Lists(array("Query"=>$strSQL));

foreach ($resultArr as $result) {
    // echo "t_".$result->available_system_node_serial;
    $strSQL = "select temperature,humidity from t_" . $result->available_system_node_serial . " order by created_date desc limit 1 ";
    $resultArr2 = $DB->Returns($strSQL);
    $result = mysql_fetch_object($resultArr2);
    if ($result->temperature != 0) {
        $t1[] = $result->temperature;
    }
    if ($result->humidity) {
        $h1[] = $result->humidity;
    }
    $t2 += $result->temperature;
    $h2 += $result->humidity;
}

$avg_temp = $t2/count($t1);
$avg_humidity = $h2/count($h1);
rsort($t1);
rsort($h1);


$strSQL = "Select * from t_room where building_id=$building_id";
$strRsRoomsArr = $DB->Returns($strSQL);
?>

<div style="border-radius:5px; margin-bottom: 8px;  padding: 5px;">

    <div style="margin-bottom:5px; float:left; text-align: center;"><span style="text-decoration: underline;">ROOM</span>&nbsp;
        <div style="border: 1px solid #cccccc;  margin-bottom: 5px; border-radius: 13px; float: right; height: 26px; width: 26px; line-height: 26px; text-align: center; font-size: 11px;">OK</div>

        <select name="ddlBuildingRoomList" id="ddlBuildingRoomList" style="width: 190px; height:30px; font-size:15px; font-family: UsEnergyEngineers;" onchange="getRoomData(<?=$building_id?>, this.value)">
            <?php
            while ($strRsRooms = mysql_fetch_object($strRsRoomsArr)) {
                print '<option value="' . $strRsRooms->room_id . '" '.($strRsRooms->room_id==$room_id?"selected":"").'>' . $strRsRooms->room_name . '</option>';
            }
            ?>                                    
        </select>
    </div>

    
    
    
    
    
    <div style="float:left; height: 67px; margin-top: -4px;">
        <div style="font-weight: bold; background-position: 8px 15px; background-size: 13px auto; float:left; margin-left: -6px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 45px; color: #999999; background-position-y: 7px;"><?=  number_format($avg_temp)?>&deg;F</div>
        <div style="margin-top: 10px; font-weight: bold; background-position: 3px 11px; margin-left: 5px; background-size: 15px auto; float:left; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 33px; color: #999999; background-position-y: 7px;"><?=  number_format($avg_humidity)?>%</div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>

</div>

<div class="clear"></div>

<div style="opacity: 0.4; background-position: 0px 15px; padding-top: 5px; border-top:1px solid #CCCCCC; background-image:url(<?php echo URL ?>images/alarm_gray_icon_small.png); background-repeat:no-repeat;">
    <div style="width: 40px; margin-left: 30px; float: left; font-size: 18px; font-weight: bold; color: #999999;">HIGH</div>
    <div style="float:left; margin-left:5px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;"><?= number_format($t1[0])?>&deg;F</div>
    <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;"><?= number_format($h1[0])?>%</div>
    <div class="clear"></div>

    <div style="width: 40px; margin-left: 30px; float: left; font-size: 18px; font-weight: bold; color: #999999;">LOW</div>
    <div style="float:left; margin-left:5px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;"><?php if(!empty($t1)){echo number_format($t1[sizeof($t1)-1]);}?>&deg;F</div>
    <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;"><?php if(!empty($h1)){echo number_format($h1[sizeof($h1)-1]);}?>%</div>
    <div class="clear"></div>
</div>