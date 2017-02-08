<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$system_id = $_GET['system_id'];
$system_type = $_GET['system_type'];
$system_name = $_GET['system_name'];

if($building_id=="" or $building_id==0 or $system_id=="" or $system_id==0)
	exit();

$strSQL="select * from t_building_system where building_id=".$building_id." and system_id = $system_id and system_type = '$system_type' order by system_no asc";
    $strBuildingSystemArr = $DB->Lists(array("Query"=>$strSQL));
    $count = sizeof($strBuildingSystemArr);
?>
<div>
    <div style="color:#666666; font-weight:bold; font-size:16px; padding: 10px 10px 2px 0;">
        <div style="float:left; margin-left:10px; text-decoration: underline; width:60%;"><?=$system_name?> - <?=$system_type?></div>
        <div style="float: left; font-size: 12px;">
            <div style="font-size: 9px; font-weight: bold; text-align: center;">ACTIVE PERIOD</div>
            <div>FROM<input type="text" id="building_system_left_FromDate" placeholder="<?=date('m/d/Y')?>" style="width: 75px; font-size: 10px; height: 11px; margin: 1px;"></div>
            <div>TO<input type="text" id="building_system_left_ToDate" placeholder="<?=date('m/d/Y')?>" style="width: 75px; font-size: 10px; height: 11px; float: right; margin: 1px;"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<div style="height:200px; background: url('<?= URL?>images/airturnover_bg.png') no-repeat; background-size: 355px 200px; margin: 10px;">
    <div style="margin-left:23px;">
        
        <?php foreach($strBuildingSystemArr as $index=>$strBuildingSystem){ ?>
        <span id="screen_<?=$index+1?>" style="display: <?=($index+1)<4?'inline-block;':'none;'?> width: 60px; margin-top: 23px; border: 1px solid #fff; cursor: pointer; padding-left: 5px; margin-left: -3px; background: #fff;"><?=$strBuildingSystem->screen_name?></span>
        <?php } ?>
        
        <?php if($count>4){ ?>
        <span style="display: inline-block; width: 45px; height:20px; margin-top: 23px; border: 1px solid #fff; margin-left: -3px; background: #fff;">
            <img border="0" usemap="#Map" src="<?= URL ?>images/previous_next_arrow.png" style="width:100%; height:100%">
            <map id="map1" name="Map">
              <area href="javascript:LeftArrow_Click();" coords="11,8,8" shape="circle">
              <area href="javascript:RightArrow_Click();" coords="32,8,8" shape="circle">
            </map>
        </span>
        <?php } ?>
    </div>
    
    <?php foreach($strBuildingSystemArr as $index=>$strBuildingSystem){ ?>
    <div id="container_screen_<?=$index+1?>" style="color: #fff; margin-left: 30px; font-weight: bold; display:none;">
        <div>
            <span>SIZE:</span>
            <span><?=$strBuildingSystem->capacity?>HP</span>
        </div>
        <div>
            <span>STATUS:</span>
            <span>LOADING</span>
            <span style="margin-left: 20px;">MODE:</span>
            <span>SETBACK</span>
        </div>
        <div>
            <span>MOTOR:</span>
            <span>ON (5HP VARIABLE SPEED)</span>
        </div>
        <div>
            <span>ACTIVITY:</span>
            <span>POWER: 1.5 KW AV. AMP: 2.30</span>
            <span style="margin-left: 65px;">VOLTAGE: L1-396. L2-392. L3-393</span>
            <span style="margin-left: 65px;">AMPERAGE: L1-4.2. L2-4.1. L30.2</span>
        </div>
    </div>
    <?php } ?>
    
</div>

<div style="margin-left: 10px; float:left;font-weight: bold; width: 250px;">COMPRESSED AIR ENERGY CONSUMPTION</div>
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
    
    var current_nav = 4;
    var max_nav = <?=$count?>;
    
    function LeftArrow_Click(){
        if(current_nav > 4){
            $('#screen_'+current_nav).css('display', 'none');
            $('#screen_'+(current_nav-4)).css('display', 'inline-block');
            current_nav--;
        }
    }
    
    function RightArrow_Click(){
        if(current_nav < max_nav){
            current_nav++;
            $('#screen_'+current_nav).css('display', 'inline-block');
            $('#screen_'+(current_nav-4)).css('display', 'none');
        }
    }
    
    var building_system_left_FromDate = $("#building_system_left_FromDate").datepicker({
        maxDate: new Date("<?=date('m/d/Y')?>")
    });
    var building_system_left_ToDate = $("#building_system_left_ToDate").datepicker({
        maxDate: new Date("<?=date('m/d/Y')?>")
    });

    //txt_Energy_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y', strtotime('-1 day'))?>"));
    building_system_left_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
    building_system_left_ToDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
</script>