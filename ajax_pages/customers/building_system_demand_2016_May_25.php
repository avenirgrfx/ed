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
        <div>FROM<input type="text" style="width: 75px; font-size: 10px; height: 11px; margin: 1px;" placeholder="<?= date('d F Y') ?>"></div>
        <div>TO<input type="text" style="width: 75px; font-size: 10px; height: 11px; float: right; margin: 1px;" placeholder="<?= date('d F Y') ?>"></div>
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

    <div style="margin: 0px 0px 9px; border: 1px solid #DDDDDD; height: 525px; border-radius: 10px; text-align: center;">

        <div style="margin: 8px; border: 1px solid #000; height: 505px; text-align: center;position:relative;">
            <div style="display:-moz-box;margin: 8px; border: 1px solid #DDDDDD; height: 320px; width:622px; text-align: center; overflow-x: scroll;">
                <div style="margin: 8px; border: 1px solid #DDDDDD; height: 320px; width:480px;">
                    <object width="430" height="280" data="<?= URL ?>uploads/nodemap/Letterhead Press - Temperature & Humidity Layout.pdf#toolbar=0"></object>
                    
                </div>
                <div style="margin: 8px; border: 1px solid #DDDDDD; height: 320px; width:110px;">
                    <div><input type="button" value="Expand" style="background: #fff none repeat scroll 0 0;border-radius: 5px;color: #000;width: 100%;border-color:#cdcdcd"></div>
                    <div style="height: 115px; text-align: center; float:left; width: 100%;border:1px solid #cdcdcd;border-radius: 8px">
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
                    <div class="clear"  style="margin:5px 0px;"></div>
                    <div style="border:1px solid #DDDDDD;border-radius:5px;">
                        <p style="margin:0px;font-size:13px;text-decoration: underline">LEAK POTENTIAL</p>
                        <p style="margin:0px;font-size:12px">LEAK LOSS 12 psi</p>
                        <p style="margin:0px;font-size:12px"><span >LOSS($):</span><span style="color:#FF0000;">$3.456/Yr</span></p>

                    </div>
                    <div class="clear"  style="margin:10px 0px;"></div>
                    <div style="background-color: #CDCDCD;font-size:12px;border-radius:5px">LEAK MANAGEMENT PROGRAM</div>
                </div>
            </div>

            <div style="margin: 0 8px; border: 1px solid #DDDDDD; height: 160px; text-align: center; float: left; width: 96%;">
                <div style="width:79%;height:160px;border:1px solid #DDDDDD;float:left">
                    <div style="display:-moz-box;margin: 8px; border: 1px solid #DDDDDD; height: 90%; width:96%; text-align: center; overflow-x: scroll;">

                        <div style="float:left; /*width: 155px;*/ width: 140px; height: 140px; padding: 5px; border: 1px solid #dddddd;">
                            <div style="text-align: center; font-size: 15px;border: 1px solid;">Machine PW036</div>
                            <div onclick="ShowSystemDetail(14)" style="/*width:145px;*/ width:100px; height:68px; min-height:68px; position: relative;margin:0px 10px">
                                <img width="100%"  style="cursor:pointer; max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;" alt="" src="http://localhost/EnergyDAS-/uploads/building/Main Press.jpg">
                            </div>
                            <div style="width:100%; margin: 5px auto 2px; float: left;text-align: left;">
                                <div style="width:100%; float:left;">
                                    <div style="font-size: 11px;border:1px solid;width: 100%">
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">CMD</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">I-4</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">DB</span>
                                        <span style="width: 22%;;text-align:center;display: inline-block;">S6</span>
                                    </div>
                                </div>
                                <div style="margin-top: 7px; float:left;">
                                    
                                </div>
                            </div>
                        </div>
                        <div style="float:left; /*width: 155px;*/ width: 140px; height: 140px; padding: 5px; border: 1px solid #dddddd;">
                            <div style="text-align: center; font-size: 15px;border: 1px solid;">Machine PW036</div>
                            <div onclick="ShowSystemDetail(14)" style="/*width:145px;*/ width:100px; height:68px; min-height:68px; position: relative;margin:0px 10px">
                                <img width="100%"  style="cursor:pointer; max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;" alt="" src="http://localhost/EnergyDAS-/uploads/building/Main Press.jpg">
                            </div>
                            <div style="width:100%; margin: 5px auto 2px; float: left;text-align: left;">
                                <div style="width:100%; float:left;">
                                    <div style="font-size: 11px;border:1px solid;width: 100%">
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">CMD</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">I-4</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">DB</span>
                                        <span style="width: 22%;;text-align:center;display: inline-block;">S6</span>
                                    </div>
                                </div>
                                <div style="margin-top: 7px; float:left;">
                                    
                                </div>
                            </div>
                        </div>
                        <div style="float:left; /*width: 155px;*/ width: 140px; height: 140px; padding: 5px; border: 1px solid #dddddd;">
                            <div style="text-align: center; font-size: 15px;border: 1px solid;">Machine PW036</div>
                            <div onclick="ShowSystemDetail(14)" style="/*width:145px;*/ width:100px; height:68px; min-height:68px; position: relative;margin:0px 10px">
                                <img width="100%"  style="cursor:pointer; max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;" alt="" src="http://localhost/EnergyDAS-/uploads/building/Main Press.jpg">
                            </div>
                            <div style="width:100%; margin: 5px auto 2px; float: left;text-align: left;">
                                <div style="width:100%; float:left;">
                                    <div style="font-size: 11px;border:1px solid;width: 100%">
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">CMD</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">I-4</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">DB</span>
                                        <span style="width: 22%;;text-align:center;display: inline-block;">S6</span>
                                    </div>
                                </div>
                                <div style="margin-top: 7px; float:left;">
                                    
                                </div>
                            </div>
                        </div>
<div style="float:left; /*width: 155px;*/ width: 140px; height: 140px; padding: 5px; border: 1px solid #dddddd;">
                            <div style="text-align: center; font-size: 15px;border: 1px solid;">Machine PW036</div>
                            <div onclick="ShowSystemDetail(14)" style="/*width:145px;*/ width:100px; height:68px; min-height:68px; position: relative;margin:0px 10px">
                                <img width="100%"  style="cursor:pointer; max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;" alt="" src="http://localhost/EnergyDAS-/uploads/building/Main Press.jpg">
                            </div>
                            <div style="width:100%; margin: 5px auto 2px; float: left;text-align: left;">
                                <div style="width:100%; float:left;">
                                    <div style="font-size: 11px;border:1px solid;width: 100%">
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">CMD</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">I-4</span>
                                        <span style="width: 22%;border-right:1px solid;text-align:center;display: inline-block;">DB</span>
                                        <span style="width: 22%;;text-align:center;display: inline-block;">S6</span>
                                    </div>
                                </div>
                                <div style="margin-top: 7px; float:left;">
                                    
                                </div>
                            </div>
                        </div>


                    </div>
                    
                </div>
                <div style="width:19%;float:left;font-size: 20px;">
                    <div>NAVIGATION</div>
                </div>
            </div>
            <div id="max_size_meter" style="width:640px;;height: 460px;position: absolute;background-color: rgba(0, 0, 0, 0.948);top:0px;z-index:1;top:1;display:none">
                <div id="wrapper" style="margin: 50px auto auto; height: 80%;width: 80%;">

                    <div id="gauge" class="bg" style="margin: 60px auto auto;float: none;">
                        <div id="needle" style="border:0px solid red"><img src="<?= URL ?>images/needle.png" alt="" id="needleimg" /></div>
                    </div>

                </div>
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
</script>