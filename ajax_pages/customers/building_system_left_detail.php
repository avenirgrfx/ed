<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_system_id = $_GET['building_system_id'];

if($building_system_id=="" or $building_system_id==0)
	exit();

$strSQL="select BS.*, S.system_name from t_building_system BS inner join t_system S on S.system_id = BS.system_id where id=".$building_system_id;
    $strBuildingSystemArr = $DB->Returns($strSQL);
    if ($strBuildingSystem = mysql_fetch_object($strBuildingSystemArr)) 
?>
<div>
    <div style="color:#666666; font-weight:bold; font-size:16px; padding: 10px 10px 2px 0;">
        <div style="float:left; margin-left:10px; text-decoration: underline;"><?=$strBuildingSystem->system_display_name?></div>
        <div style="float:left; font-size:12px; margin-left:10px;">LAST RUN: 1/16/16 04:49 AM - 2.3 HRS <!--<input type="text" placeholder="<?=date('F Y')?>" style="width: 110px; font-size: 12px; height: 13px;">--></div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<div style="height:200px; background: url('<?= URL?>images/airturnover_bg.png') no-repeat; background-size: 355px 200px; margin: 15px 10px;">
    <span id="screen_1" style="display: inline-block; width: 65px; margin-top: 23px; border: 1px solid #fff; cursor: pointer; padding-left: 5px; margin-left: 20px; background: #fff;">SUMMARY</span>
    <span id="screen_2" style="display: inline-block; width: 60px; margin-top: 23px; border: 1px solid #fff; cursor: pointer; padding-left: 10px; margin-left: -3px; color: #000;">ENERGY</span>
    <span id="screen_3" style="display: inline-block; width: 60px; margin-top: 23px; border: 1px solid #fff; cursor: pointer; padding-left: 10px; margin-left: -3px; color: #000;">HEALTH</span>
    <span id="screen_4" style="display: inline-block; width: 55px; margin-top: 23px; border: 1px solid #fff; cursor: pointer; padding-left: 10px; margin-left: -3px; color: #000;">MOTOR</span>
    <span style="display: inline-block; margin-top: 23px; padding-left: 5px;"><img src="<?= URL?>images/system_on_symbol.png"></span>
    
    <div id="container_screen_1" style="color: #fff; margin-left: 30px; font-weight: bold; display:none;">
        <div>
            <span>TYPE:</span>
            <span>ROTARY SCREW</span>
        </div>
        <div>
            <span>CAPACITY:</span>
            <span><?=$strBuildingSystem->capacity?>HP</span>
        </div>
        <div>
            <span>MOTOR:</span>
            <span>AV. VOLTAGE 496 &nbsp; AV. AMP: 0.00</span>
            <span style="margin-left: 60px;">VOLTAGE: L1-396. L2-392. L3-393</span>
            <span style="margin-left: 60px;">AMPERAGE: L1-4.2. L2-4.1. L30.2</span>
        </div>
        <div>
            <span>LAST SERVICE:</span>
            <span>02/22/2016</span>
        </div>
    </div>
    
    <div id="container_screen_2" style="color: #fff; margin-left: 30px; font-weight: bold; display:none;">
        <div>
            <span>TYPE:</span>
            <span>ROTARY SCREW</span>
        </div>
        <div>
            <span>CAPACITY:</span>
            <span><?=$strBuildingSystem->capacity?>HP</span>
        </div>
        <div>
            <span>MOTOR:</span>
            <span>AV. VOLTAGE 496 &nbsp; AV. AMP: 0.00</span>
            <span style="margin-left: 60px;">VOLTAGE: L1-396. L2-392. L3-393</span>
            <span style="margin-left: 60px;">AMPERAGE: L1-4.2. L2-4.1. L30.2</span>
        </div>
        <div>
            <span>LAST SERVICE:</span>
            <span>02/22/2016</span>
        </div>
    </div>
    
    <div id="container_screen_3" style="color: #fff; margin-left: 30px; font-weight: bold; display:none;">
        <div>
            <span>TYPE:</span>
            <span>ROTARY SCREW</span>
        </div>
        <div>
            <span>CAPACITY:</span>
            <span><?=$strBuildingSystem->capacity?>HP</span>
        </div>
        <div>
            <span>MOTOR:</span>
            <span>AV. VOLTAGE 496 &nbsp; AV. AMP: 0.00</span>
            <span style="margin-left: 60px;">VOLTAGE: L1-396. L2-392. L3-393</span>
            <span style="margin-left: 60px;">AMPERAGE: L1-4.2. L2-4.1. L30.2</span>
        </div>
        <div>
            <span>LAST SERVICE:</span>
            <span>02/22/2016</span>
        </div>
    </div>
    
    <div id="container_screen_4" style="color: #fff; margin-left: 30px; font-weight: bold; display:none;">
        <div>
            <span>TYPE:</span>
            <span>ROTARY SCREW</span>
        </div>
        <div>
            <span>CAPACITY:</span>
            <span><?=$strBuildingSystem->capacity?>HP</span>
        </div>
        <div>
            <span>MOTOR:</span>
            <span>AV. VOLTAGE 496 &nbsp; AV. AMP: 0.00</span>
            <span style="margin-left: 60px;">VOLTAGE: L1-396. L2-392. L3-393</span>
            <span style="margin-left: 60px;">AMPERAGE: L1-4.2. L2-4.1. L30.2</span>
        </div>
        <div>
            <span>LAST SERVICE:</span>
            <span>02/22/2016</span>
        </div>
    </div>
    
</div>

<div style="margin-left: 10px; float:left;font-weight: bold; width: 250px;">ENERGY CONSUMPTION</div>
<div style="margin-right: 5px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">8,776 kWh</div><div class="clear"></div>

<script>
    $('[id^="screen_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[1]; 
        console.log(id_num);

        $('[id^="screen_"]').css('color', '#fff');
        $('[id^="screen_"]').css('background', 'none');
        $(this).css('color', '#000');
        $(this).css('background', '#fff');

        $('[id^="container_screen_"]').hide();
        $('#container_screen_'+id_num).show();
    });    
    
    $('#screen_1').trigger('click');
</script>