<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');

$DB = new DB;

$building_id = $_GET['building_id'];
$system_id = $_GET['system_id'];
$system_type = $_GET['system_type'];
$system_name = $_GET['system_name'];

if ($building_id == "" or $building_id == 0 or $system_id == "" or $system_id == 0)
    exit();

$strSQL = "select * from t_building_system where building_id=" . $building_id . " and system_id = $system_id and system_type = '$system_type' order by system_no asc";
$strBuildingSystemArr = $DB->Returns($strSQL);
?>
<script type="text/javascript" src="<?php echo URL ?>js/vroom.js"></script>
<div>
    <div style="float: left; font-weight: bold; text-decoration: underline; margin: 10px 10px 0 30px; padding: 5px 0px; width: 195px;"><?= $system_name ?></div>
    <div style="border-radius: 10px; margin: 15px 0px; font-weight: bold; float:left; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY ASSISTANT</div>
    <div style="border-radius: 10px; margin: 15px 20px; font-weight: bold; float:left; background: #607BA7;padding: 5px 15px;color:#fff">DEMAND SIDE</div>

    <div style="float: left; font-size: 12px; padding:10px;">
        <div style="font-size: 9px; font-weight: bold; text-align: center;">ACTIVE PERIOD</div>
        <div>FROM<input type="text" id="building_system_FromDate" placeholder="<?=date('m/d/Y')?>" style="width: 75px; font-size: 10px; height: 11px; margin: 1px;"></div>
        <div>TO<input type="text"  id="building_system_ToDate" placeholder="<?=date('m/d/Y')?>" style="width: 75px; font-size: 10px; height: 11px; float: right; margin: 1px;"></div>
    </div>

    <div class="clear" style="border-bottom:2px solid #DDDDDD; margin:5px 0px;"></div>
</div>

<div style="padding:0px 20px; height: 565px;" >

    <div>
        <div style="border-radius: 5px;margin: 5px 10px; float:left; background: #CCCCCB; padding: 2px 15px;">Controls</div>
        <div style="border-radius: 5px;margin: 5px 10px; float:left; background: #CCCCCB; padding: 2px 15px;">Schedules</div>
        <div style="border-radius: 5px;margin: 5px 10px; float:left; background: #CCCCCB; padding: 2px 15px;">Performance</div>
        <div style="border-radius: 5px;margin: 5px 10px; float:left; background: #CCCCCB; padding: 2px 15px;">Analysis</div>
        <div style="border-radius: 5px;margin: 5px 10px; float:right; border: 1px solid #CCCCCB; padding: 2px 15px;">Specifications</div>

        <div style="margin:5px 0px;" class="clear"></div>
    </div>

    <div style="margin: 0px 0px 9px; border: 1px solid #DDDDDD; height: 480px; border-radius: 10px; text-align: center;">

        <div style="margin: 8px; border: 1px solid #000; height: 460px; text-align: center;position:relative;">
            <div style="display:-moz-box;margin: 8px; border: 1px solid #DDDDDD; height: 320px; width:622px; text-align: center; overflow-x: scroll;">

                <?php while ($strBuildingSystem = mysql_fetch_object($strBuildingSystemArr)) { ?>
                    <div style="float:left; /*width: 155px;*/ width: 208px; height: 295px; padding: 5px;">
                        <div style="text-align: left; padding-top: 15px; font-size: 15px"><?= $strBuildingSystem->system_display_name ?></div>
                        <div style="text-align: left; padding-bottom: 15px; font-size: 12px"><?= $strBuildingSystem->system_description ?></div>
                        <div style="/*width:145px;*/ width:198px; height:170px; min-height:170px; position: relative;" onclick="ShowSystemDetail(<?= $strBuildingSystem->id ?>)">
                            <img src="<?= URL ?>uploads/building/<?= $strBuildingSystem->system_image ?>" alt="" width="100%" style="cursor:pointer; max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;">
                        </div>
                        <div style="width:100%; margin: 5px auto 2px; float: left; background: #E4E4E4;text-align: left;">
                            <div style="width:80%; float:left;">
                                <div style="padding-left: 5px; font-size: 11px;"><span style="width: 40%; float: left; margin-right: 10px;">STATUS:</span><span>IDLE</span></div>
                                <div style="padding-left: 5px; font-size: 11px;"><span style="width: 40%; float: left; margin-right: 10px;">MODE:</span><span>SETBACK</span></div>
                                <div style="padding-left: 5px; font-size: 11px;"><span style="width: 40%; float: left; margin-right: 10px;">MOTOR:</span><span>ON</span></div>
                            </div>
                            <div style="margin-top: 7px; float:left;"><img width="35px" src="<?= URL ?>images/system_button_on.png"></div>
                        </div>
                    </div>
                <?php } ?>

            </div>

            <div style="margin: 0 8px; border: 1px solid #DDDDDD; height: 110px; text-align: center; float: left; width: 80%;">
                <div style="font-size: 12px; border-right: 1px solid #DDDDDD; height: 110px; text-align: left; float: left; width: 49%;">
                    <div style="text-decoration: underline; font-size: 12px; text-align: center;"><?= $system_name ?> (ACTIVE PERIOD)</div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 40%; float: left;">TOTAL ENERGY:</span><span>1,124,768 MMBTU</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 40%; float: left;">ELECTRICITY:</span><span>3,456 kWh</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 40%; float: left;">TOTAL COST:</span><span>$458.98 (2% OF ELECTRIC)</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 60%; float: left;">SYSTEM ELECTRIC UNITS:</span><span>4</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 40%; float: left;">SYSTEM PRESSURE</span><span>121</span></div>
                </div>
                <div style="border-left: 1px solid #DDDDDD; height: 110px; text-align: left; float: right; width: 49%;">
                    <div style="text-decoration: underline; font-size: 12px; text-align: center;"><?= $system_name ?> (ACTIVE PERIOD)</div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 40%; float: left;"><strong>COMPRESSOR 1:</strong></span><span>660 kWh; $18 (80% SYSTEM)</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 40%; float: left;"><strong>COMPRESSOR 2:</strong></span><span>199 kWh; $8 (3% SYSTEM)</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 30%; float: left;"><strong>AIR DRYER:</strong></span><span>247 kWh; $119 (12% SYSTEM)</span></div>
                    <div style="padding-left: 10px; font-size: 12px;"><span style="width: 30%; float: left;"><strong>SYSTEM:</strong></span><span>1,047 kWh (2% OF ELECTRIC)</span></div>
                </div>
            </div>
            <div id="max_size_meter" style="width:640px;;height: 460px;position: absolute;background-color: rgba(0, 0, 0, 0.948);top:0px;z-index:1;top:1;display:none">
                <div id="wrapper" style="margin: 50px auto auto; height: 80%;width: 80%;">

                    <div id="gauge" class="bg" style="margin: 60px auto auto;float: none;">
                        <div id="needle" style="border:0px solid red"><img src="<?= URL ?>images/needle.png" alt="" id="needleimg" /></div>
                    </div>

                </div>
            </div>
            <div style="height: 110px; text-align: center; float:left; width: 16%;">
                <div style="font-size: 12px;">SYSTEM PRESSURE</div>
                <div id="wrapper2"  onclick="OpenMaxSizeMeter()">
                    <input type="hidden" name="miles" id="miles" value="121" />
                    <div id="gauge2" class="bg" style="  background-size: 100px 85px;width:100px;height:85px;margin-left: 0px;margin-top: -8px;">
                        <div id="needle2" style="border:0px solid red;left: 5.5px;width: 43px;height: 3px;bottom: 46px;"><img src="<?= URL ?>images/needle.png" alt="" id="needleimg" style=" max-width: 78%;top: 1px; left: 22px;"/></div>
                    </div>
                </div>
                <div id="wrapper3" style="display:none">
                    <div id="gauge3" class="bgbar">
                        <div id="needle1" style="border:0px solid red"><img src="<?= URL ?>images/needle.png" alt="" id="needleimg1" /></div>
                    </div>
                </div>

<!--                <img src="<?= URL ?>images/pascal.png" width="60%">-->
                <div>121 psi</div>
            </div>
        </div>

    </div>

</div>

<br>

<script>
    function ShowSystemDetail(id) {
        $('#right_side_container').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/customers/building_system_detail.php",
                {
                    building_system_id: id,
                }, function (data, status) {
            $('#right_side_container').html(data);
        });

        $('#for_right_nav_3').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/customers/building_system_left_detail.php",
                {
                    building_system_id: id,
                }, function (data, status) {
            $('#for_right_nav_3').html(data);
        });
    }
    $(document).ready(function () {
        $("#miles").keyup();

    });
    function OpenMaxSizeMeter() {
        $("#max_size_meter").show();
    }
    $(document).click(function (e)
    {

        var container = $("#max_size_meter");
        var container1 = $("#wrapper2");

        if (container.has(e.target).length === 0 && container1.has(e.target).length === 0)
        {
            container.hide();
        }
    });
    
    var building_system_FromDate = $("#building_system_FromDate").datepicker({
        maxDate: new Date("<?=date('m/d/Y')?>")
    });
    var building_system_ToDate = $("#building_system_ToDate").datepicker({
        maxDate: new Date("<?=date('m/d/Y')?>")
    });

    //txt_Energy_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y', strtotime('-1 day'))?>"));
    building_system_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
    building_system_ToDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
</script>