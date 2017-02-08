<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/building.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
require_once(AbsPath . "classes/customer.class.php");
require_once(AbsPath . "classes/widget_category.class.php");

$DB = new DB;
$Category = new Category;
$System = new System;
$Building = new Building;
$Gallery = new Gallery;
$Client = new Client;
$WidgetCategory = new WidgetCategory;

if ($_SESSION['user_login']->login_id == "") {
    Globals::SendURL(URL . 'login.php');
}

if (Globals::Get('login_id') <> "") {
    $_SESSION['client_details']->client_id = Globals::Get('login_id');
}

$strClientID = $_SESSION['client_id'] = $_SESSION['client_details']->client_id;

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

$strSQL = "Select t_client.*, t_client_type.client_type from t_client, t_client_type where t_client.client_type=t_client_type.client_type_id and client_id=$strClientID";
//print $strSQL;

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

        <link rel="stylesheet" href="../css/prism.css">
        <link rel="stylesheet" href="../css/bootstrap.css">	
        <link rel="stylesheet" href="../css/master.css">
        <link rel="stylesheet" href="../css/tree.css">
        <link href="../css/jquery.circliful.css" rel="stylesheet" type="text/css" />
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../css/jquery.switchButton.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>

        <style type="text/css">
            *
            {
                font-family:UsEnergyEngineers;
            }

            #MenuBar_Gray ul li
            {
                margin: 10px 6px;
            }
            #Building_EUI_Box_1
            {
                background-color:#248BCA;
                float:left;
                width:23%;							
                text-align:center;
                padding:1px;
                border:1px solid #CCCCCC;
            }
            #Building_EUI_Box_2
            {
                background-color:#ffcd31;
                float:left;
                width:23%;
                margin-left:2px;
                text-align:center;
                padding:1px;
                border:1px solid #CCCCCC;
            }
            #Building_EUI_Box_3
            {
                background-color:#f8981d;
                float:left;
                width:24%;
                margin-left:2px;
                text-align:center;
                padding:1px;
                border:1px solid #CCCCCC;
            }
            #Building_EUI_Box_4
            {
                background-color:#ef3823;
                float:left;
                width:23%;
                margin-left:2px;
                text-align:center;
                padding:1px;
                border:1px solid #CCCCCC;
            }
            .popup_w {
                background: rgba(0, 0, 0, 0.8); padding: 80px 0;
                position: fixed;
                top: 0px;
                left:0px;
                width:100%;
                z-index:9999; 
            }
            
            .popup_container {
                background: none repeat scroll 0 0 #fff;
                border-radius: 15px;
                margin: 0 auto;
                max-width: 800px;
                padding: 20px;border:10px solid #ddd;

            }
            .popup_w #popup #style-2 {
                height: 300px !important;
            }

            .popup_w #popup .electric_Gas{ width: 66% !important;}

            .close_btn {
                float: right;
                padding-top: 20px;
            }
        </style>

        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/prism.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/fabric.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/jquery.js"></script>  
        <script type='text/javascript' src="<?php echo URL ?>js/bootstrap.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/paster.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/angular.min.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/font_definitions.js"></script>    
        <script type='text/javascript' src="<?php echo URL ?>js/utils.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/app_config.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/controller.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/tree.jquery.js"></script>
        <script src="<?php echo URL ?>js/jquery-ui.js"></script>
        <script src="<?php echo URL ?>js/jquery.circliful.min.js"></script>
        <script src="<?php echo URL ?>js/jquery.switchButton.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

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
                        ChangeBuildingDropdown($('#ddlBuildingForSite').val());
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
                $('#Gray_Button').click(function () {
                    $('#Show_Dynamic_Sites').html('Buildings');
                    $('#Gray_Button').css('z-index', 1);
                    $('#Blue_Button').css('z-index', 0);
                    $('#Building_Elements').css('display', 'block');
                    $('#Building_Elements_Details').css('display', 'none');
                });

                $('#Blue_Button').click(function () {
                    $('#Show_Dynamic_Sites').html('Elements');
                    $('#Blue_Button').css('z-index', 1);
                    $('#Gray_Button').css('z-index', 0);

                    $.get("<?php echo URL ?>ajax_pages/customers/building_elements.php",
                            {
                                type: 2
                            },
                    function (data, status) {
                        $('#Building_Elements_Details').html(data);
                        $('#Building_Elements_Details').css('display', 'block');
                        $("#ddlBuildingElemntsList").empty();
                        $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
                        $("#ddlBuildingElemntsList").val($('#ddlBuildingForSite').val());
                        
                        $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                                {
                                    building_id: $("#ddlBuildingElemntsList").val()
                                },
                        function (data, status) {
                            $('#Building_Details_Container').html(data);
                        });
                        $('#Building_Elements').css('display', 'none');
                    });

                });

//                $('#Gray_Button_Text').click(function () {
//                    $('#Gray_Button').trigger('click');
//                });

//                $('#Blue_Button_Text').click(function () {
//                    $('#Blue_Button').trigger('click');
//                });
                $('#Site_Details_Summary_Button').click(function () {

                    $('#site_details_dynamic_content').html('Loading....');

                    $('#site_details_dynamic_title').html('SITE SUMMARY');

                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 3);
                    $('#Site_Details_Summary_Button').css('z-index', 4);
                    $.get("<?php echo URL ?>ajax_pages/customers/site_details.php",
                            {
                                type: 1,
                                building_id: $('#ddlBuildingForSite').val(),
                                month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                                year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                            },
                    function (data, status) {
                        $('#site_details_dynamic_content').html(data);
                    });

                });

                $('#Site_Details_GHG_Button').click(function () {
                    $('#site_details_dynamic_content').html('Loading....');
                    $('#site_details_dynamic_title').html('GREENHOUSE GAS');

                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 2);
                    $('#Site_Details_Summary_Button').css('z-index', 3);
                    $('#Site_Details_GHG_Button').css('z-index', 4);

                    $.get("<?php echo URL ?>ajax_pages/customers/site_details.php",
                            {
                                type: 2,
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#site_details_dynamic_content').html(
                                data
                                );
                    });

                });

                $('#Site_Details_Metrics_Button').click(function () {
                    $('#site_details_dynamic_content').html('Loading....');
                    $('#site_details_dynamic_title').html('SITE METRICS');

                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 4);
                    $('#Site_Details_Summary_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 3);

                    $.get("<?php echo URL ?>ajax_pages/customers/site_details.php",
                            {
                                type: 3,
                                building_id: $('#ddlBuildingForSite').val(),
                                month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                                year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                            },
                    function (data, status) {
                        $('#site_details_dynamic_content').html(
                                data
                                );
                    });

                });

                $('#Site_Details_Energy_Button').click(function () {
                    $('#site_details_dynamic_content').html('Loading....');
                    $('#site_details_dynamic_title').html('SITE CONSUMPTION');

                    $('#Site_Details_Energy_Button').css('z-index', 4);
                    $('#Site_Details_Metrics_Button').css('z-index', 3);
                    $('#Site_Details_Summary_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 1);

                    $.get("<?php echo URL ?>ajax_pages/customers/site_details.php",
                            {
                                type: 4,
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#site_details_dynamic_content').html(
                                data
                                );
                    });

                });

//                $('#Site_Details_Summary_Text').click(function () {
//                    $('#Site_Details_Summary_Button').trigger('click');
//                });

//                $('#Site_Details_GHG_Text').click(function () {
//                    $('#Site_Details_GHG_Button').trigger('click');
//                });

//                $('#Site_Details_Metrics_Text').click(function () {
//                    $('#Site_Details_Metrics_Button').trigger('click');
//                });

//                $('#Site_Details_Energy_Text').click(function () {
//                    $('#Site_Details_Energy_Button').trigger('click');
//                });

                //$('#Site_Details_Summary_Button').trigger('click');
                //$('#Gray_Button').trigger('click');

                $('#ddlBenchMarkBuilding').change(function () {
                    var selectedBuilding = $('#ddlBenchMarkBuilding').val();
                    //$("#ddlBuildingForSite").val(selectedBuilding);
                    //$("#ddlBuildingForSite").val(selectedBuilding);
                });

                $('#Energy_Cost_Index_ECI_Button').click(function () {
                    $('#Energy_Use_Intensity_EUI_Button').removeClass('benchmark_button_active');
                    $('#Energy_Use_Intensity_EUI_Button').addClass('benchmark_button');
                    $('#Energy_Use_Intensity_EUI_Button').css('background-color','#A9A9A9');
                    
                    $('#Energy_Cost_Index_ECI_Button').removeClass('benchmark_button');
                    $('#Energy_Cost_Index_ECI_Button').addClass('benchmark_button_active');
                    $('#Energy_Cost_Index_ECI_Button').css('background-color','#526D9A');

                    $('#Today_Site_EUI_ECI').html('Today SITE Cost');
                    $('#Yesterday_Site_EUI_ECI').html('Yesterday SITE Cost');
                    $('#Month_Site_EUI_ECI').html('Month SITE Cost');
                    $('#Target_Site_EUI_ECI').html('Target SITE ECI');
                    
                    $('#Today_Site_EUI_ECI_B').html('Today Baseline Cost');
                    $('#Yesterday_Site_EUI_ECI_B').html('Yesterday Baseline Cost');
                    $('#Month_Site_EUI_ECI_B').html('Month Baseline Cost');
                    $('#Target_Site_EUI_ECI_B').html('Target ECI');

//                    var Building_Square_Feet_For_Calculation = parseFloat($('#Building_Square_Feet_For_Calculation').html());
//                    var electric_energy_consumption_now = $('#electric_energy_consumption_now').html();
//                    electric_energy_consumption_now = electric_energy_consumption_now.replace(",", "");
//                    electric_energy_consumption_now = electric_energy_consumption_now.replace("kWh", "");
//                    electric_energy_consumption_now = parseFloat(electric_energy_consumption_now);
//
//                    if (Building_Square_Feet_For_Calculation > 0)
//                    {
//                        $('#Month_Site_EUI_ECI_Value_Amount').html("$" + ((electric_energy_consumption_now * 0.07) / Building_Square_Feet_For_Calculation).toFixed(3) + "/ft<sup>2</sup>");
//                    }
//                    else
//                    {
//                        $('#Month_Site_EUI_ECI_Value_Amount').html('0');
//                    }
                    $('.EUI_Val').css('display', 'none');
                    $('.ECI_Val').css('display', 'block');

                });

                $('#Energy_Use_Intensity_EUI_Button').click(function () {

                    $('#Energy_Cost_Index_ECI_Button').removeClass('benchmark_button_active');
                    $('#Energy_Cost_Index_ECI_Button').addClass('benchmark_button');

                    $('#Energy_Use_Intensity_EUI_Button').removeClass('benchmark_button');
                    $('#Energy_Use_Intensity_EUI_Button').addClass('benchmark_button_active');
                    $('#Energy_Use_Intensity_EUI_Button').css('background-color','#526D9A');
                    $('#Energy_Cost_Index_ECI_Button').css('background-color','#A9A9A9');

                    $('#Today_Site_EUI_ECI').html('Today Energy Use');
                    $('#Yesterday_Site_EUI_ECI').html('Yesterday Energy Use');
                    $('#Month_Site_EUI_ECI').html('Month Energy Use');
                    $('#Target_Site_EUI_ECI').html('2015 EUI');
                    
                    $('#Today_Site_EUI_ECI_B').html('Day Baseline Energy');
                    $('#Yesterday_Site_EUI_ECI_B').html('Yesterday Baseline Energy');
                    $('#Month_Site_EUI_ECI_B').html('Month Baseline Energy');
                    $('#Target_Site_EUI_ECI_B').html('Target EUI');

                    $('.EUI_Val').css('display', 'block');
                    $('.ECI_Val').css('display', 'none');

                });
                $('#ddlSitesPortfolio2').html($('#ddlSitesPortfolio1').html());
                
                $('#ddlSitesPortfolio1').trigger('change');
                $('#ddlSitesPortfolio1').change(function(){
                    $('#ddlSitesPortfolio2').val($('#ddlSitesPortfolio1').val());
                });
                $('#ddlSitesPortfolio2').change(function(){
                    $('#ddlSitesPortfolio1').val($('#ddlSitesPortfolio2').val());
                });
            });
            
            var Month_To_Date_Electric_Consumption="";
            var Last_Month_Electric_Consumption="";
            var Month_To_Date_NaturalGas_Consumption="";
            var Last_Month_NaturalGas_Consumption="";
            
            function ChangeSiteDropdown(site_id){              
                Month_To_Date_Electric_Consumption="";
                Last_Month_Electric_Consumption="";
                Month_To_Date_NaturalGas_Consumption="";
                Last_Month_NaturalGas_Consumption="";
                
                $.get("<?php echo URL ?>ajax_pages/customers/dynamic_building_name_new.php",
                        {
                            site_id: site_id
                        },
                function (data, status) {
                    $('#Show_Dynamic_Buildings').html(data);
                    ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    UpdateBuildingElementDetails($('#ddlBuildingForSite').val(), 0);
                });
            }

//            function GrayButtonClickLoad()
//            {
//                $.get("<?php echo URL ?>ajax_pages/customers/building_elements.php",
//                        {
//                            type: 1
//                        },
//                function (data, status) {
//                    $('#Building_Elements').html(data);
//                });
//            }

            function UpdateBuildingElementDetails(strBuildingID, UpdateOtherBuildingDropDown)
            {
                $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                        {
                            building_id: strBuildingID
                        },
                function (data, status) {
                    $('#Building_Details_Container').html(data);
                    $('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                    $('#ddlFilterElectric_Gas').val('1');
                    if (UpdateOtherBuildingDropDown == 1)
                    {
                        UpdateAllBuildingDropdown(strBuildingID);
                    }
                });
            }

            //GrayButtonClickLoad();

            function UpdateAllBuildingDropdown(strBuildingID)
            {
                $("#ddlBuildingForSite").val(strBuildingID);
                $("#ddlBenchMarkBuilding").val(strBuildingID);
                $("#ddlSiteSummaryBuilding").val(strBuildingID);
                $("#ddlConsumptionBuilding").val(strBuildingID);
                $("#ddlBuildingElemntsList").val(strBuildingID);
                UpdateBuildingElementDetails(strBuildingID, 0);

                $('#ddlFilterElectric_Gas').trigger('change');
                $('#ddlMetricsType').trigger('change');
            }

            function ChangeBuildingDropdown(strBuildingID)
            {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined;
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                
                $("#ddlBenchMarkBuilding").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBenchMarkBuilding');
                $("#ddlBenchMarkBuilding").val(strBuildingID);


                $("#ddlBuildingElemntsList").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
                $("#ddlBuildingElemntsList").val(strBuildingID);


                $("#ddlSiteSummaryBuilding").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlSiteSummaryBuilding');
                $("#ddlSiteSummaryBuilding").val(strBuildingID);

                $("#ddlConsumptionBuilding").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlConsumptionBuilding');
                $("#ddlConsumptionBuilding").val(strBuildingID);
                
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_electric_system.php",
                {
                    building_id: strBuildingID,
                    type: 2,
                    month: month,
                    year: year
                },
                function (data, status) {
                    $('#Consumption_Electric_System').html(data);
                });

                $('#Container_SystemsByBuilding').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_child_system.php",
                        {
                            building_id: strBuildingID
                        },
                function (data, status) {
                    $('#Container_SystemsByBuilding').html(data);
                });


                $('#Building_BenchMark_Container').html('Loading....');
                $.get("<?php echo URL ?>ajax_pages/customers/building_benchmark_eui.php", {building_id: strBuildingID}, function (data) {
                    $('#Building_BenchMark_Container').html(data);
                });
                
                $('#consumption_chart_container').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/consumption_chart.php",
                        {
                            building_id: strBuildingID,
                            type: 1,
                            month: month,
                            year: year
                        },
                function (data, status) {
                    $('#consumption_chart_container').html(data);
                });
                
                $('#Consumption_Electric_System').html('Loading...');
                
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_electric_system.php",
                        {
                            building_id: strBuildingID,
                            type: 1,
                            month: month,
                            year: year
                        },
                function (data, status) {
                    $('#Consumption_Electric_System').html(data);
                    var strType = 1;
                    if (strType == 1)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Electric');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 kWh');
                        $('#Main_Utility_Electric_Gas_Label').html('Electric Disconnect');
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 kWh');
                    }
                    else if (strType == 2)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Natural Gas');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 Therms');
                        $('#Main_Utility_Electric_Gas_Label').html("Main's Natural Gas");
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 Therms');
                    }
                });
                
                $('#Site_Details_Summary_Button').trigger('click');
            }


            function SwitchElectricGasSystem(strType)
            {
                if (strType == 1)
                {
                    $('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                } 
                else if (strType == 2)
                {
                    $('#graph_header_title').html('Natural Gas Consumption (MMBTU)');
                }
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined;
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $('#Consumption_Electric_System').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_electric_system.php",
                {
                    building_id: $('#ddlBuildingForSite').val(),
                    type: strType,
                    month: month,
                    year: year
                },
                function (data, status) {
                    $('#Consumption_Electric_System').html(data);

                    if (strType == 1)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Electric');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 kWh');
                        $('#Main_Utility_Electric_Gas_Label').html('Electric Disconnect');
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 kWh');
                    } 
                    else if (strType == 2)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Natural Gas');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 Therms');
                        $('#Main_Utility_Electric_Gas_Label').html("Main's Natural Gas");
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 Therms');
                    }

                });
                
                $('#consumption_chart_container').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/consumption_chart.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            type: strType,
                            month: month,
                            year: year
                        },
                function (data, status) {
                    $('#consumption_chart_container').html(data);
                });
            }
            function showBuildingSystemChild(strParentSystemID, strBuildingID)
            {
                //$('#'+strParentSystemID+'_content').html(strParentSystemID);
                $('#' + strParentSystemID + '_content').html('Loading...');


                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_child_system.php",
                        {
                            parent_id: strParentSystemID,
                            building_id: strBuildingID
                        },
                function (data, status) {
                    $('#' + strParentSystemID + '_content').html(data);
                });
            }

            function Expand_Collapse_System_Node_For_Building(strSystemID)
            {
                if ($('.System_ID_' + strSystemID).css('display') == 'none')
                {
                    $('.System_ID_' + strSystemID).slideDown('slow');
                    $('.System_ID_Expand_' + strSystemID).html('-');

                    //$('.System_ID_'+strSystemID).css('display','block');
                }
                else
                {
                    //$('.System_ID_'+strSystemID).css('display','none');
                    $('.System_ID_' + strSystemID).slideUp('slow');
                    $('.System_ID_Sub_' + strSystemID).slideUp('slow');
                    $('.System_ID_Expand_' + strSystemID).html('+');
                }
                //$('.noclick').attr('onclick','').unbind('click');
            }

            function showPopup(){
                var drop_value = $('#ddlFilterElectric_Gas').val();
                $('#popup').html($('#consumption_systems').html());
                $('#consumption_systems').html("");
                $('#ddlFilterElectric_Gas').val(drop_value);
                $('.popup_button').hide();
                $('.close_btn').show();
                $('body').css("overflow","hidden");
                $('.popup_w').show();
            }
            
            function close_popup(){   
                var drop_value = $('#ddlFilterElectric_Gas').val();
                $('#consumption_systems').html($('#popup').html());
                $('#popup').html("");
                $('#ddlFilterElectric_Gas').val(drop_value);
                $('.popup_button').show();
                $('.close_btn').hide();
                $('body').css("overflow","auto");
                $('.popup_w').hide();
            }
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
                    <div id="date_with_time_zone">
                        <?php 
                        //date_default_timezone_set('Asia/Kolkata');
                        //echo date("g:i a F dS, Y");
                        ?>
                    </div>

                </div>
                <div class="clear"></div>
            </div>

            <div class="GrayBackground">

                <?php require_once("menu.php"); ?>


                <div id="Customer_Left_Panel" style="height:490px;">

                    <div class="Windows_Left" style="position:relative; width:40px;">

                        <div style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(<?php echo URL ?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Gray_Button_Text">Buildings</div>
                        </div>
                        <div style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(<?php echo URL ?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Blue_Button_Text">Elements</div>
                        </div>

                    </div>


                    <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                        <div class="Window_Title_Bg">

                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                <div class="heading">PORTFOLIO</div>

                            </div>

                            <div style="float:left; margin-left:20px;">
                                <img src="<?php echo URL ?>/images/window_title_divider.png" />
                            </div>


                            <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;" id="Show_Dynamic_Sites">                        	
                                Buildings
                            </div>

<!--                            <div style="float:right; margin-top:15px; margin-right:15px;">
                                <img src="<?php echo URL ?>/images/previous_next_arrow.png" border="0" usemap="#Map" />
                                <map name="Map">
                                    <area shape="circle" coords="23,20,16" href="javascript:LeftArrow_Click();">
                                    <area shape="circle" coords="61,22,15" href="javascript:RightArrow_Click();">
                                </map>
                            </div>-->
                            <div style="float:right; margin-top:15px; margin-right:15px;">
                                <div style="color:#666666; font-weight:bold; font-size:16px;">
                                    <select id="ddlSitesPortfolio1" style="width:180px; margin-top: 5px; font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;" onchange="ChangeSiteDropdown(this.value)">
                                    <?php while ($rsSite = mysql_fetch_object($rsSiteArr)) {
                                        echo "<option value='$rsSite->site_id'>SITE - $rsSite->site_name</option>";
                                    }?>
                                    </select>
                                </div>
                            </div>

                            <div class="clear"></div>

                        </div>
                        <div class="Window_Container_Bg" style="height:423px;">

                            <div style="padding:15px 10px 10px 20px; min-height:310px;" id="Building_Elements">
                                <div style="float:left;">
                                    <div style="color:#666666; font-weight:bold; font-size:16px;" id="Show_Dynamic_Buildings">Loading...</div>
                                </div>
                                <div class="clear"></div>
                                <div id="Container_SystemsByBuilding" style="margin-top:15px; padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; max-height:338px; overflow-y: auto;" class="myscroll"></div>
                            </div>
                            <div style="padding:15px 10px 10px 20px; display:none; min-height:310px;" id="Building_Elements_Details">&nbsp;</div>


                        </div>

                    </div>

                    <div class="clear"></div>


                    <br>


                </div>


                <div id="Customer_Right_Panel" style="min-height:500px;">

                    <div style="width:94%; padding:3%; border-radius:10px; min-height:300px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                        <div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:250px; border-radius:5px;">


                            <div style="float:left; margin-top:5px;" class="heading">BENCHMARK<?php ?></div>

                            <div style="float:left; margin-left:25px;  margin-top:5px;">

                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBenchMarkBuilding" id="ddlBenchMarkBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select Building</option>
                                </select>
                            </div>

                            <div style="float:right; margin-top:5px;">                        	   
                                <input type="text" name="txt_Benchmark_Date" id="txt_Benchmark_Date" placeholder="Select Month and Year" value="<?php echo date('F Y') ?>" style="width:130px; font-size:12px; height:12px;" class="monthPicker" />                     	

                            </div>
                            <div class="clear"></div>
                            <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;" />                      

                            <div id="Building_BenchMark_Container"></div>                    


                        </div>

                        <div style="margin-top:10px;">
                            <div class="benchmark_button_active" style="float:left;width:150px;text-align: center;background-color:#526D9A" id="Energy_Use_Intensity_EUI_Button"><b>Energy Use</b></div>
                            <div style="float:left; margin-left:10px; padding:5px;">|</div>     
                            <div class="benchmark_button" style="float:left;width:150px;text-align: center; margin-left:10px; padding:5px;background-color:#A9A9A9 " id="Energy_Cost_Index_ECI_Button"><b>Energy Cost</b></div>
                            <div style="float:right; background-color:#FFFFFF; border-radius:10px; padding:5px 5px; border:1px solid #CCCCCC; width:120px; text-align:center;">
                                <a href="#Portfolii_Link" target="_blank"><img src="<?php echo URL ?>/images/portfolio_manager_logo.png" border="0" /></a>
                            </div>
                            <div class="clear"></div>
                        </div>

                    </div>

                </div>
                <div class="clear"  style="height:20px;"></div>



                <div id="Customer_Left_Panel">

                    <div class="Windows_Left" style="position:relative; width:40px;">

                        <div style="position:absolute; z-index:3; width:35px; height:159px; top:70px; background-image:url(<?php echo URL ?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Summary_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Site_Details_Summary_Text">Summary</div>
                        </div>
                        <div style="position:absolute; z-index:2; width:35px; height:159px; top:180px; background-image:url(<?php echo URL ?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_GHG_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:105px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Site_Details_GHG_Text">GHG</div>
                        </div>
                        <div style="position:absolute; z-index:1; width:35px; height:159px; top:290px; background-image:url(<?php echo URL ?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Metrics</div>
                        </div>
                        <div style="position:absolute; z-index:0; width:35px; height:159px; top:400px; background-image:url(<?php echo URL ?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Energy_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Energy_Text">Energy</div>
                        </div>

                    </div>


                    <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                        <div class="Window_Title_Bg">
                            <div style="float:left; margin-top:20px; margin-left:15px; color:#666666;">
                                <div class="heading">SITE DETAILS</div>
                            </div>
                            <div style="float:left; margin-left:15px;">
                                <img src="<?php echo URL ?>/images/window_title_divider.png" />
                            </div>
                            <div style="float:left; margin-left:15px; margin-top:20px; font-size:16px; color:#666666;" id="site_details_dynamic_title">
                                SITE SUMMARY
                            </div>
                            <div style="float:right; margin-top:15px; margin-right:15px;">
                                <div style="color:#666666; font-weight:bold; font-size:16px;">
                                    <select id="ddlSitesPortfolio2" style="width:180px; margin-top: 5px; font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;" onchange="ChangeSiteDropdown(this.value)">
                                    <?php while ($rsSite = mysql_fetch_object($rsSiteArr)) {
                                        echo "<option value='$rsSite->site_id'>SITE - $rsSite->site_name</option>";
                                    }?>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="Window_Container_Bg">

                            <div style="padding:15px 10px 10px 20px; min-height:575px;">

                                <div style="float:left;">

                                    <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlSiteSummaryBuilding" id="ddlSiteSummaryBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">

                                    </select>

                                </div>

                                <div style="float:right; margin-left:30px;">
                                    <input type="text" name="txt_SiteDetails_Date" id="txt_SiteDetails_Date" placeholder="Select Month and Year" value="<?php echo date('F Y') ?>" style="width:130px; font-size:12px; height:12px;" class="monthPicker">
                                </div>

                                <div class="clear"></div>


                                <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">

                                <div id="site_details_dynamic_content" style="width: 470px;">

                                    Loading...

                                </div>

                            </div>


                        </div>

                    </div>

                    <div class="clear"></div>


                    <br>


                </div>

                <div id="Customer_Right_Panel">

                    <div style="width:94%; padding:3%; border-radius:10px; min-height:300px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                        <div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:625px; border-radius:5px;">


                            <div style="float:left; margin-top:5px;" class="heading">CONSUMPTION</div>

                            <div style="float:left; margin-left:25px;  margin-top:5px;">

                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlConsumptionBuilding" id="ddlConsumptionBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Zeeland Mainsite</option>
                                </select>
                            </div>

                            <div style="float:right; margin-top:5px;">
                                <input type="text" name="txt_Consumptions_Date" id="txt_Consumptions_Date" placeholder="Select Month and Year" value="<?php echo date('F Y') ?>" style="width:130px; font-size:12px; height:12px;" class="monthPicker">                        	
                            </div>
                            <div class="clear"></div>
                            <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px;" />                      


                            <div>                        
                                <div style="float:left; margin-top:2px;">ELECTRIC CONSUMPTION</div>
                                <div style="float:left; margin-left:3px;" class="light_blue_box_for_value" id="electric_energy_consumption_now">0 kWh</div>


                                <div style="float:left; margin-left:6px; margin-top:2px;">GAS CONSUMPTION</div>
                                <div style="float:left; margin-left:3px;" class="gray_box_for_value" id="natural_gas_energy_consumption_now">0 Therms</div>
                                <div class="clear"></div>


                                <div id="graph_header_title" style="margin-bottom:5px; margin-top:10px; color:#666666; font-weight:bold; font-size:16px; text-decoration:underline; text-align:center;">Electrical Systems Consumption (MMBTU)</div>

                                <div style="margin:10px 0px; height:250px;" id="consumption_chart_container">
                                    Loading...
                                </div>
                               
                                <div id="consumption_systems" style="float:left; width:96%; margin:1%;">

                                    <div style="float:left; font-weight:bold;">                                	
                                        <select name="ddlFilterElectric_Gas" id="ddlFilterElectric_Gas" onChange="SwitchElectricGasSystem(this.value)" style="font-weight:bold;">
                                            <option value="1" selected>Electric System</option>
                                            <option value="2">Natural Gas System</option>
                                        </select>
                                    </div>
                                    <div style="float:left; margin-left: 10px;" class="popup_button"><a class="btn btn-default" href="javascript:showPopup();">expand</a></div>
                                    <div style="float:right; margin-right:20px; font-weight:bold;"> % Total</div><div class="clear"></div>
                                    <div style=" padding-bottom:10px; padding-top:5px; margin-top:5px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:100px; overflow-y: scroll;" id="style-2">

                                        <div id="Consumption_Electric_System">
                                            Loading...
                                        </div>

                                    </div>

                                    <div style="float:left; width:90%;" class="electric_Gas">                                
                                        <div class="clear" style="margin-top:10px;"></div>
                                        <div style="float:left; width:325px; text-align:right; margin-top:3px; font-weight:bold; margin-right:5px;" id="Total_Electric_Gas_Label">Metered Electric</div>
                                        <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value" id="Total_Electric_Gas_Value">0 kWh</div> 
                                        <div class="clear"></div>

                                        <div class="clear" style="margin-top:3px;"></div>
                                        <div style="float:left; width:325px;  text-align:right; font-size:12px; margin-top:3px; margin-right:5px;" id="Main_Utility_Electric_Gas_Label">Electric Disconnect</div>
                                        <div class="light_blue_box_for_value" style="float:left; min-width:104px; font-weight:normal; background:none; border:1px solid #DDDDDD;" id="Main_Utility_Electric_Gas_Value">0 kWh</div>

                                        <div class="clear"></div>
                                       
                                        
                                    </div>

                                    <div style="float:left; margin-left:3px;" class="right_bracket_bg">
                                        <div style="margin-top:25px; background-color:#FFFFFF;" id="actualPercent">0%</div>
                                    </div>
                                    <div class="close_btn" style="display:none;"><a href="javascript:close_popup()" class="btn btn-default">close</a></div>
                                    <div class="clear"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>

                </div>

                <div class="clear"></div>
            </div>
        </div>
        <div id="Building_Details_Container" style="display:none;"></div>
        <div class="popup_w" style="display:none;"> <div id="popup" class="popup_container">  </div></div>
        <script src="<?php echo URL ?>highstock/js/highstock.js"></script>
        <script src="<?php echo URL ?>highstock/js/modules/exporting.js"></script>
        <!--<script src="<?php echo URL ?>highcharts/js/highcharts.js"></script>-->
        <script src="<?php echo URL ?>highcharts/js/modules/exporting.js"></script>  
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    </body>

</html>