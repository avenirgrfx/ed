<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

if ($_POST && !empty($_POST)) {
    //echo $_POST['building_id'];
    $alarmArray = json_decode($_POST['alarm_json']);
    //print_r($alarmArray);exit;
    $strSQL="Delete from t_alarms where building_id=".$_POST['building_id'];
	$DB->Execute($strSQL);
    
    if(is_array($alarmArray) && count($alarmArray)>0){
        $strSQL="Insert into t_alarms (building_id, status, type, low, high, behavior, area, `trigger`) Values ";
        foreach($alarmArray as $alarm){
            $strSQL.="('".$_POST['building_id']."', '$alarm->status', '$alarm->type', '$alarm->low', '$alarm->high', '$alarm->behavior', '$alarm->area', '$alarm->trigger'), ";
        }
        $strSQL = trim($strSQL, ", ");
        //echo $strSQL;exit;
        $alarm_id = $DB->Execute($strSQL);
    }
    echo "success";exit;
}

$building_id = Globals::Get('building_id');

$strRsRoomsArr = $DB->Lists(array('Query' => "Select * from t_room where building_id=$building_id"));
//$strSQL = "Select * from t_room where building_id=$building_id";
//$strRsRoomsArr = $DB->Returns($strSQL);
//$strRsRoomsArr2 = $strRsRoomsArr3 = $strRsRoomsArr;

?>

<link rel="stylesheet" type="text/css" href="<?php echo URL?>css/master.css">
<script type='text/javascript' src="<?php echo URL ?>js/jquery.js"></script>

<script>
    var count = 0;
    var rooms_options = '<option value="">All Areas</option>';
    <?php foreach ($strRsRoomsArr as $strRsRooms) { ?>
            rooms_options += '<option value="' + '<?=$strRsRooms->room_id?>' + '">' + '<?=$strRsRooms->room_name?>' + '</option>';
    <?php } ?>
    
    function remove_alarm(id){
       $('#alarm_container_'+id).remove(); 
    }
    
    function toggleBehavior(id){
       $('#alarm_behavior_'+id).toggle(); 
    }
    
    function changeType(id, type){
       if(type){
           $('.alarm_unit_'+id).html('%');
       } else {
           $('.alarm_unit_'+id).html('<sup>o</sup>F');
       }
    }
    
    function triggerEvent(id){
       //do nothing for now
    }
    
    function addAlarm(){
        count++;
        var html = '<div style="padding: 10px;" id="alarm_container_'+count+'">';
        html+= '<div style="color: black; background-color: #d3d3d3">';
        html+= '<div style="padding-left: 10px; float: left; width: 67px; background-color: #d3d3d3;" id="alarm_name_'+count+'">ALARM '+count+'</div>';
        html+= '<div style="padding-left: 10px; float: left; width: 325px; background-color: #d3d3d3;">';
        html+= '    <input type="radio" value="1" checked name="alarm_type_'+count+'" onchange="changeType('+count+', 1)"><label>Humidity</label>';
        html+= '    <input type="radio" value="2" name="alarm_type_'+count+'" onchange="changeType('+count+', 0)"><label>Temperature</label>';
        html+= '</div>';
        html+= '<div style="float: left; width: 175px; background-color: #d3d3d3;">AREA</div>';
        html+= '</div>';

        html+= '<div style="margin: 30px 5px; padding: 5px;">';
        html+= '<div style="float: left; width: 70px;">';
        html+= '    <input type="checkbox" id="alarm_status_'+count+'"> Alarm';
        html+= '</div>';
        html+= '<div style="float: left; width: 100px; margin-left: 10px;">';
        html+= '    <input type="textbox" value="0" style="width: 85px;" id="alarm_low_'+count+'"><span class="alarm_unit_'+count+'">%</span>';
        html+= '    <label>Low</label>';
        html+= '</div>';
        html+= '<div style="float: left; width: 100px; margin-left: 10px;">';
        html+= '    <input type="textbox" value="0" style="width: 85px;" id="alarm_high_'+count+'"><span class="alarm_unit_'+count+'">%</span>';
        html+= '    <label>High</label>';
        html+= '</div>';
        html+= '<div style="float: left; width: 70px; margin-left: 10px; background-color: #d6d6d6; padding: 2px; cursor: pointer;" onclick="toggleBehavior('+count+')">BEHAVIOR</div>';
        html+= '<select name="ddlBuildingRoomList" id="alarm_place_'+count+'" style="float: left; width: 160px; margin-left: 10px; font-size:15px; font-family: UsEnergyEngineers;">';
        html+= rooms_options;
                                           
        html+= '</select>';
        //html+= '<div style="float: left; width: 58px; margin-left: 10px; background-color: #d6d6d6; padding: 2px; cursor: pointer;" onclick="triggerEvent('+count+')">TRIGGER</div>';
        html+= '<div style="float: left; width: 8px; margin-left: 10px; padding: 2px; cursor: pointer;" onclick="remove_alarm('+count+')">X</div>';
        html+= '</div>';

        html+= '<div style="margin: 30px 5px; padding: 5px; display:none;" id="alarm_behavior_'+count+'">';
//        html+= '<div style="float: left; margin-left: 10px;">';
//        html+= '    <input type="text" placeholder="Enter comma separated Emails" style="width: 245px;" id="alarm_mail_'+count+'">';
//        html+= '</div>';
        html+= '<div style="float: left; margin-left: -65px;">';
        html+= '    <input type="text" placeholder="Enter comma separated Emails" style="float:left; width: 245px;" id="alarm_mail_'+count+'">';
        html+= '    <div style="float: left; width: 58px; margin-left: 10px; background-color: #d6d6d6; padding: 2px; cursor: pointer;" onclick="triggerEvent('+count+')">TRIGGER</div>';
        html+= '</div>';
        html+= '</div>';
        html+= '</div>';
                
        $('#main_container_alarm').append(html); 
    };
    
    function setAlarm(){
        var alarmArray = [];
        
        $('[id^="alarm_container_"]').each(function (){
            var alarm = {
                status      : $(this).find('[id^="alarm_status_"]').is(':checked') ? 1 : 0,
                type        : $(this).find('[name^="alarm_type_"]:checked').val(),
                low         : $(this).find('[id^="alarm_low_"]').val(),
                high        : $(this).find('[id^="alarm_high_"]').val(),
                behavior    : $(this).find('[id^="alarm_mail_"]').val(),
                area        : $(this).find('[id^="alarm_place_"]').val(),
                trigger     : '',
            };
            alarmArray.push(alarm);
        });
        
        console.log(alarmArray);
        
        $.post("<?php echo URL ?>customer/alarm_settings_entry.php",
                {
                    building_id : "<?=$building_id?>",
                    alarm_json   : JSON.stringify(alarmArray),
                },
        function (data, status) {
            if(data == 'success'){
                alert('Alarms added successfully');
                location.reload();
            }else{
                alert(data);
            }
        });
    }
</script>

<div style="background-color:#FFFFFF; border-radius:10px; height:350px; overflow-y: scroll;" class="myscroll">
    <div style="text-decoration: underline; font-weight: bold; font-size: 20px; text-align: center;">ALARMS</div>
    <div id="main_container_alarm">
        
    <?php
    $strSQL = "Select * from t_alarms where building_id=$building_id";
    $strAlarmArr = $DB->Returns($strSQL);
    $i=1;
    while($strAlarm = mysql_fetch_object($strAlarmArr)){ ?>
        <div style="padding: 10px;" id="alarm_container_<?=$i?>">
            <div style="color: black; background-color: #d3d3d3">
                <div style="padding-left: 10px; float: left; width: 67px; background-color: #d3d3d3;" id="alarm_name_<?=$i?>">ALARM <?=$i?></div>
                <div style="padding-left: 10px; float: left; width: 325px; background-color: #d3d3d3;">
                    <input type="radio" value="1" <?=$strAlarm->type!=2?"checked":""?> name="alarm_type_<?=$i?>" onchange="changeType(<?=$i?>, 1)"><label>Humidity</label>
                    <input type="radio" value="2" <?=$strAlarm->type==2?"checked":""?> name="alarm_type_<?=$i?>" onchange="changeType(<?=$i?>, 0)"><label>Temperature</label>
                </div>
                <div style="float: left; width: 175px; background-color: #d3d3d3;">AREA</div>
            </div>
            
            <div style="margin: 30px 5px; padding: 5px;">
                <div style="float: left; width: 70px;">
                    <input type="checkbox" id="alarm_status_<?=$i?>" <?=$strAlarm->status==1?"checked":""?> name="alarm_status_<?=$i?>"> Alarm
                </div>
                <div style="float: left; width: 100px; margin-left: 10px;">
                    <input type="textbox" value="<?=$strAlarm->low?>" style="width: 85px;" id="alarm_low_<?=$i?>"><span class="alarm_unit_<?=$i?>"><?=$strAlarm->type==2?"<sup>o</sup>F":"%"?></span>
                    <label>Low</label>
                </div>
                <div style="float: left; width: 100px; margin-left: 10px;">
                    <input type="textbox" value="<?=$strAlarm->high?>" style="width: 85px;" id="alarm_high_<?=$i?>"><span class="alarm_unit_<?=$i?>"><?=$strAlarm->type==2?"<sup>o</sup>F":"%"?></span>
                    <label>High</label>
                </div>
                <div style="float: left; width: 70px; margin-left: 10px; background-color: #d6d6d6; padding: 2px; cursor: pointer;" onclick="toggleBehavior(<?=$i?>)">BEHAVIOR</div>
                <select name="ddlBuildingRoomList" id="alarm_place_<?=$i?>" style="float: left; width: 160px; margin-left: 10px; font-size:15px; font-family: UsEnergyEngineers;">
                    <?php
                    print '<option value="">All Areas</option>';
                    foreach ($strRsRoomsArr as $strRsRooms) {
                        print '<option value="' . $strRsRooms->room_id . '" '.($strRsRooms->room_id==$strAlarm->area?"selected":"").'>' . $strRsRooms->room_name . '</option>';
                    }
                    ?>                                    
                </select>
<!--                <div style="float: left; width: 58px; margin-left: 10px; background-color: #d6d6d6; padding: 2px; cursor: pointer;" onclick="triggerEvent(<?=$i?>)">TRIGGER</div>-->
                <div style="float: left; width: 8px; margin-left: 10px; padding: 2px; cursor: pointer;" onclick="remove_alarm(<?=$i?>)">X</div>
            </div>
            
            <div style="margin: 30px 5px; padding: 5px; display:none;" id="alarm_behavior_<?=$i?>">
<!--                <div style="float: left; margin-left: 10px;">
                    <input type="text" placeholder="Enter comma separated Emails" style="width: 245px;" id="alarm_mail_<?=$i?>">
                </div>-->
                <div style="float: left; margin-left: -65px;">
                    <input type="text" placeholder="Enter comma separated Emails" value="<?=$strAlarm->behavior;?>" style="float: left; width: 245px;" id="alarm_mail_<?=$i?>">
                    <div style="float: left; width: 58px; margin-left: 10px; background-color: #d6d6d6; padding: 2px; cursor: pointer;" onclick="triggerEvent(<?=$i?>)">TRIGGER</div>
                </div>
            </div>
        </div>
    <?php $i++; } ?>   
    <script>
        count = "<?=$i-1?>";
    </script>
    </div>
    <div class="clear"></div>
    <div>
        <div style="float: left; margin-left: 10px; margin-bottom: 10px; color: #555555; width: 110px; background-color: #d3d3d3; cursor: pointer; border: 1px solid #666666; padding: 4px; margin-top:10px;border-radius:7px; text-align:center; font-size:15px; text-transform:uppercase;" id="Graph_Bottom_Options_4" onclick="setAlarm()">SET ALARM</div>
        <div style="float: right; margin-right: 10px; margin-bottom: 10px; color: #555555; width: 110px; background-color: #d3d3d3; cursor: pointer; border: 1px solid #666666; padding: 4px; margin-top:10px;border-radius:7px; text-align:center; font-size:15px; text-transform:uppercase;" id="Graph_Bottom_Options_4" onclick="addAlarm()">ADD ALARM</div>
    </div>
</div>