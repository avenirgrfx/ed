<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
require_once(AbsPath . "classes/customer.class.php");
require_once(AbsPath . "classes/widget_category.class.php");

$DB = new DB;
$Category = new Category;
$System = new System;
$Gallery = new Gallery;
$Client = new Client;
$WidgetCategory = new WidgetCategory;

if ($_SESSION['user_login']->login_id == "") {
    Globals::SendURL(URL . 'login.php');
}

$_SESSION['client_id'] = $_SESSION['client_details']->client_id;
$strClientID = $_SESSION['client_id'];

if ($_POST['type'] == 'System') {
    $System->parent_id = $_POST['ddlSystem'];
    $System->system_name = $_POST['txtSystemName'];
    $System->has_node = ($_POST['chkHasWidget'] == "" ? 0 : 1);
    if ($_POST['System_ID'] == '') {
        $System->Insert();
    } else {
        $System->system_id = $_POST['System_ID'];
        $System->Update();
    }
    Globals::SendURL(URL . "engineers/?type=system");
}

$strQuery = "Select * from t_sites where client_id=" . $strClientID . " order by site_name asc";
if (is_array($_SESSION['Allowed_Sites_Operations']) && count($_SESSION['Allowed_Sites_Operations']) > 0) {
    if ($_SESSION['Allowed_Sites_Operations'][0] <> 0) {
        $strQuery.=" and site_id in (" . implode(',', $_SESSION['Allowed_Sites_Operations']) . ")";
    }
}
$rsSiteArr = $DB->Returns($strQuery);
$strSiteCount = mysql_num_rows($rsSiteArr);

$strSQL = "Select t_client.*, t_client_type.client_type from t_client, t_client_type where t_client.client_type=t_client_type.client_type_id and client_id=$strClientID";
$strRsClientDetailsArr = $DB->Returns($strSQL);
while ($strRsClientDetails = mysql_fetch_object($strRsClientDetailsArr)) {
    $client_name = $strRsClientDetails->client_name;
    $client_type = $strRsClientDetails->client_type;
    $client_logo = $strRsClientDetails->logo;
    $strSQL = "Select software_version from t_software_version where software_version_id=" . $strRsClientDetails->software_version_id;
    $strRsSoftwareVersionDetailsArr = $DB->Returns($strSQL);
    if ($strRsSoftwareVersionDetails = mysql_fetch_object($strRsSoftwareVersionDetailsArr)) {
        $software_version = $strRsSoftwareVersionDetails->software_version;
    }
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
    <head>
        <meta charset="utf-8">

        <title>energyDAS Customer</title>
        <link rel="stylesheet" href="../css/vroom.css">
        <link rel="stylesheet" href="../css/prism.css">
        <link rel="stylesheet" href="../css/bootstrap.css">	
        <link rel="stylesheet" href="../css/master.css">
        <link rel="stylesheet" href="../css/tree.css">
        <link rel="stylesheet" href="../css/basic.css">
        
 
        <link href="../css/jquery.circliful.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
         
        <style type="text/css">
            *
            {
                font-family:UsEnergyEngineers;
            }

            #Container_SystemsByBuilding
            {
                display:none;
            }

        </style>
       
        <script type='text/javascript' src="<?php echo URL ?>js/prism.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/fabric.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/jquery.js"></script>  
        <script type='text/javascript' src="<?php echo URL ?>js/bootstrap.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/paster.js"></script>
        <script type='text/javascript' src='<?php echo URL ?>js/jquery.simplemodal.js'></script>
        <script type='text/javascript' src="<?php echo URL ?>js/angular.min.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/font_definitions.js"></script>    
        <script type='text/javascript' src="<?php echo URL ?>js/utils.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/app_config.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/controller.js"></script>
        <script type='text/javascript' src='<?php echo URL ?>js/tree.jquery.js'></script>
        <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
        <script src="<?php echo URL ?>js/jquery.circliful.min.js"></script>
        


        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.15.0/plugins/CSSPlugin.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.15.0/easing/EasePack.min.js"></script>
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.15.0/TweenLite.min.js"></script>
	

        <script type="text/javascript">
            $(function () {
                var month = new Array();
                month[0] = "January";
                month[1] = "February";
                month[2] = "March";
                month[3] = "April";
                month[4] = "May";
                month[5] = "June";
                month[6] = "July";
                month[7] = "August";
                month[8] = "September";
                month[9] = "October";
                month[10] = "November";
                month[11] = "December";

                $(".monthPicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: false,
                    dateFormat: 'MM yy',
                    maxDate: new Date(),
                    beforeShowMonth: function () {
                        $(this).datepicker('setDate', $(this).val());
                    },
                    onClose: function (dateText, inst) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1));
                        $(".monthPicker").datepicker('setDate', new Date(year, month, 1));
                        
                        $('[id^="system_link_"]').each(function(){
                            if($(this).css('color') == 'rgb(255, 255, 255)'){
                                $(this).trigger('click');
                            }
                        });
                    }
                });
                $(".monthPicker").focus(function () {
                    $(".ui-datepicker-calendar").hide();
                    $("#ui-datepicker-div").position({
                        my: "center top",
                        at: "center bottom",
                        of: $(this)
                    });
                });
            });
            
            $(document).ready(function () {

                $('[id^="system_link_"]').click(function(){
                    var id = $(this).attr('id');
                    var strType = id.split('_')[2]; 
                    console.log(strType);

                    $('[id^="system_link_"]').parent().css('z-index', '1');
                    $('[id^="system_link_"]').css('color', '#666666');
                    $('[id^="system_link_"]').parent().css('background-image', 'url("../images/gray_button.png")');
                    $(this).parent().css('z-index', '2');
                    $(this).css('color', '#ffffff');
                    $(this).parent().css('background-image', 'url("../images/blue_button.png")');
                    
                    if(strType == 1){
                        $('#left_system_heading').html('ELECTRIC SYSTEMS');
                    }else if(strType == 2){
                        $('#left_system_heading').html('NATURAL GAS SYSTEMS');
                    }else{
                        $('#left_system_heading').html('WATER SYSTEMS');
                    }
                              
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined;
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    
                    $('#left_system_container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/customers/system_info.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        strType: strType,
                        month: month,
                        year: year
                    },
                    function (data, status) {
                        $('#left_system_container').html(data);
                    });  
                });
                
                $('[id^="right_navigation_"]').click(function(){
                    var id = $(this).attr('id');
                    var navType = id.split('_')[2]; 
                    console.log(navType);

                    $('[id^="right_navigation_"]').parent().css('z-index', '1');
                    $('[id^="right_navigation_"]').css('color', '#666666');
                    $('[id^="right_navigation_"]').parent().css('background-image', 'url("../images/gray_button.png")');
                    $(this).parent().css('z-index', '2');
                    $(this).css('color', '#ffffff');
                    $(this).parent().css('background-image', 'url("../images/blue_button.png")');
                    
                    //$('#Show_system').html("");
                    $('#Show_system').hide();
                    $('#Show_Buildings_system').html("");
                    $('#Show_Buildings_system').hide();
                    $('#right_side_container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/customers/system_building_right.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        navType: navType
                    },
                    function (data, status) {
                        $('#right_side_container').html(data);
                    });  
                    
                    if(navType == 3){
                        $('#Windows_Left').hide();
                        $('#for_right_nav_1').hide();
                        $('#for_right_nav_3').show();
                        $('#for_right_nav_3').html("");
                    }else{
                        $('#Windows_Left').show();
                        $('#for_right_nav_3').hide();
                        $('#for_right_nav_1').show();
                        $('#system_link_1').trigger('click');
                    }
                    
                });
    
                $('#ddlSitesPortfolio').trigger('change');

            });

            function ChangeSiteDropdown(site_id) {

                $.get("<?php echo URL ?>ajax_pages/customers/dynamic_building_name_new.php",
                        {
                            site_id: site_id
                        },
                function (data, status) {
                    $('#Show_Dynamic_Buildings').html(data);

                    ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    
                    // Get the building current time in its timezone
                    $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                            {
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#Building_Details_Container').html(data);
                    });
                });

            }

            function ChangeBuildingDropdown(strBuildingID)
            {
                $('#right_navigation_1').trigger('click');
                $('#txt_Energy_FromDate').trigger('change');
                $('#txt_Energy_ToDate').trigger('change');
            }

            $(function(){
                var txt_Energy_FromDate = $("#txt_Energy_FromDate").datepicker({
                    maxDate: new Date("<?=date('m/d/Y')?>")
                });
                var txt_Energy_ToDate = $("#txt_Energy_ToDate").datepicker({
                    maxDate: new Date("<?=date('m/d/Y')?>")
                });

                txt_Energy_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y', strtotime('-1 day'))?>"));
                txt_Energy_ToDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
        
                $('#txt_Energy_FromDate').change(function(){
                    $('#left_chart').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/customers/building_energy_consumption.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        date: $('#txt_Energy_FromDate').val(),
                        type: 1
                    },
                    function (data, status) {
                        $('#left_chart').html(data);
                    }); 
                });
                
                $('#txt_Energy_ToDate').change(function(){
                    $('#right_chart').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/customers/building_energy_consumption.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        date: $('#txt_Energy_ToDate').val(),
                        type: 2
                    },
                    function (data, status) {
                        $('#right_chart').html(data);
                    }); 
                });
                
            });
        </script>
         
    </head>

    <body>

        <div id="Customer_Main_Container">
            <div id="Customer_Header_Section">
                <div style="float:left; border-right:1px solid #333333; padding-right:10px;">
                    <?php echo Globals::Resize('../uploads/customer/' . $client_logo, 150, 70); ?>
                </div>
                <div style="float:left; margin-left:50px;">
                    <h5 style="text-transform:uppercase;"><?php echo $client_name; ?></h5>
                    <span style="font-size:24px;"><?php echo $software_version ?> - <?php echo $client_type; ?></span>
                </div>
                <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
                    energyDAS<br>
                    <div id="date_with_time_zone"></div>

                </div>
                <div class="clear"></div>
            </div>

            <div class="GrayBackground" style="padding-bottom: 40px;">            

                <?php require_once("menu.php"); ?>

                <div id="Customer_Left_Panel" style="margin-left: 5px; width: 405px;">

                    <div id="Windows_Left" class="Windows_Left" style="position:relative; width:30px">

                        <div style="position:absolute; z-index:2; width:30px; height:159px; top:25px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="system_link_1">ELECTRIC</div>
                        </div>
                        <div style="position:absolute; z-index:1; width:30px; height:159px; top:135px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:130px; margin-left: 5px; font-size:14px; font-weight:bold; color:#FFFFFF;" id="system_link_2">NATURAL&nbsp;GAS</div>
                        </div>
                        <div style="position:absolute; z-index:0; width:30px; height:159px; top:245px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:105px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="system_link_3">WATER</div>
                        </div>

                    </div>


                    <div class="Windows_Main" style="margin-left:30px; border:1px solid #999999; border-radius:10px; width:375px;">
                        <div class="Window_Title_Bg" style="width: auto;">

                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                <div class="heading">SYSTEMS</div>

                            </div>

                            <div style="float:left; margin-left:20px;">
                                <img src="../images/window_title_divider.png" />
                            </div>


                            <div style="float:right; margin-top:20px; margin-right:20px; color: rgb(102, 102, 102); font-size: 18px;">
                                <div>
                                    <select id="ddlSitesPortfolio" style="width:200px; font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;" onchange="ChangeSiteDropdown(this.value)">
                                        <?php
                                        while ($rsSite = mysql_fetch_object($rsSiteArr)) {
                                            echo "<option value='$rsSite->site_id'>SITE - $rsSite->site_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="clear"></div>

                        </div>
                        
                        <div class="Window_Container_Bg" style="min-height:327px;" id="for_right_nav_1">
                            <div>
                                <div style="color:#666666; font-weight:bold; font-size:16px; padding: 10px 10px 2px 0;">
                                    <div style="float:left; margin-left:10px;" id="left_system_heading">ELECTRIC SYSTEMS</div>
<!--                                    <div style="float:right; margin-right:10px;">
                                        <input type="text" name="txt_Benchmark_Date" id="txt_Benchmark_Date" placeholder="Select Month and Year" value="<?php echo date('F Y') ?>" style="width:130px; font-size:12px; height:12px;" class="monthPicker" />                                  
                                    </div>-->
                                    <div class="clear"></div>
                                </div>

                                <div class="clear"></div>

                                <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>

                            </div>
                            <div style="margin-left:20px;" id="left_system_container">
                                Loading...
                            </div>

                            <br>
                        </div>
                        
                        <div class="Window_Container_Bg" style="min-height:327px;display:none;" id="for_right_nav_3">
                            Loading...
                        </div>

                    </div>

                    <div class="clear"></div>
                    
                    <br>
                    
                    <div class="Window_Container_Bg" style="min-height:300px; margin-left: 30px;background-image:none; border-radius:10px;">
                        <div style="padding:15px 10px 10px 20px;font-weight: bold; font-size:13px;">
                            BUILDING ENERGY CONSUMPTION COMPARISON
                            <img src='<?=URL?>images/settings_icon.png'>
                        </div>
                        
                        <div style="margin-left:20px; border: 1px solid #DDDDDD; width:335px; height: 180px">
                            <div style='width: 50%; float:left;'>
                                <input type="text" id="txt_Energy_FromDate" placeholder="Pick Date1" value="<?=date('m/d/Y', strtotime('-1 day'))?>" style="width:130px; font-size:12px; height:12px; margin:10px;" />
                                <div id="left_chart">
                                    
                                </div>
                            </div>
                            
                            <div style='width: 50%; float:left;'>
                                <input type="text" id="txt_Energy_ToDate" placeholder="Pick Date2" value="<?=date('m/d/Y')?>" style="width:130px; font-size:12px; height:12px; margin:10px;" />
                                <div id="right_chart">
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div style="font-size: 11px; margin: 10px 0px 0px 20px; float:left;">HIGHEST ENERGY USAGE NOW:</div> <div style="font-size: 11px; margin: 10px 0px 0px 0px; float:left;color:#607BA7;"> HVAC (ELECTRIC) (AIR TURNOVER UNIT #2)</div><div class="clear"></div>
                        <div style="font-size: 11px; margin: 10px 0px 0px 20px; float:left;">HIGHEST ENERGY USAGE TODAY:</div> <div style="font-size: 11px; margin: 10px 0px 0px 0px; float:left;color:#607BA7;"> DEHUMIDIFICATION SYSTEM (SANWA)</div><div class="clear"></div>
                        <br>
                    </div>
                    
                    <div class="clear"></div>
                    
                </div>

                <div id="Customer_Right_Panel" style='width: 735px;margin-left: 25px;'>
                    <div class="Windows_Left" style="position:relative; width:30px;">

                        <div style="position:absolute; z-index:3; width:30px; height:200px; top:85px; background-image:url(../images/gray_button.png); background-size: 30px 200px; background-repeat:no-repeat; cursor:pointer;">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:135px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="right_navigation_1">BUILDING</div>
                        </div>
                        <div style="position:absolute; z-index:2; width:30px; height:215px; top:227px; background-image:url(../images/blue_button.png); background-size: 30px 220px; background-repeat:no-repeat; cursor:pointer;">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:180px; margin-left: 5px; font-size:14px; font-weight:bold; color:#FFFFFF;" id="right_navigation_2">BUILDING&nbsp;SYSTEMS</div>
                        </div>
                        <div style="position:absolute; z-index:1; width:30px; height:200px; top:388px; background-image:url(../images/gray_button.png); background-size: 30px 200px; background-repeat:no-repeat; cursor:pointer;">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:160px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="right_navigation_3">SYSTEM&nbsp;MANAGE</div>
                        </div>
                        <div style="position:absolute; z-index:0; width:30px; height:200px; top:540px; background-image:url(../images/blue_button.png); background-size: 30px 180px; background-repeat:no-repeat; cursor:pointer;">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:140px; margin-left: 5px; font-size:14px; font-weight:bold; color:#FFFFFF;" id="right_navigation_4">FLOOR&nbsp;PLANS</div>
                        </div>

                    </div>

                    <div class="Windows_Main" style="width: 700px; margin-left:30px; border:1px solid #999999; border-radius:10px;">
                        <div class="Window_Title_Bg" style='width: auto;'>

                            <div style="padding:15px 10px 10px 20px;">
                                <div style="float:left;width: 670px;">
                                    <div style="color:#666666; font-weight:bold; font-size:16px;float:left" id="Show_Dynamic_Buildings">Loading...</div>
                                    <div style="color:#666666; font-weight:bold; font-size:16px;float:right;display: none" id="Show_system">
                                        <select style="width: 150px;"><option value="0">Select System</option></select>
                                    </div>
                                    <div style="color:#666666; font-weight:bold; font-size:16px;float:right;display: none" id="Show_Buildings_system">Loading...</div>
                                    
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>

                        </div>
                        <div class="Window_Container_Bg" id="right_side_container">
                                Loading...
                        </div>

                    </div>
                </div>
                    
                <div class="clear"></div>
            </div>
        </div>
        
        <div id="Building_Details_Container" style="display:none;"></div>
        <script src="<?php echo URL ?>highstock/js/highstock.js"></script>
        <script src="<?php echo URL ?>highstock/js/modules/exporting.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    </body>
</html>
