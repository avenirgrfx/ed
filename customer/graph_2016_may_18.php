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
        <link rel="stylesheet" href="../css/basic.css">
        <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>

        <style type="text/css">
            *
            {
                font-family:UsEnergyEngineers;
            }

        </style>

        <!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
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

        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

        <script type="text/javascript">

            $(document).ready(function () {
                
                $('#Graph_Bottom_Options_1').click(function(){
                    var BuildingID = $('#ddlBuildingForSite').val();
                    $('#frmBuildingNodesEntry').attr('src', '<?php echo URL ?>customer/building_nodes_entry.php?building_id=' + BuildingID);
                    $('#building-nodes-modal').modal();
                    $('#simplemodal-container').css("width", "600px")
                    $('#simplemodal-container').css("height", "375px")
                    $('#simplemodal-container').css("left", "30%")
                    $('#simplemodal-container').css("top", "20%")
                    return false;
                });
                
                $('#Graph_Bottom_Options_2').click(function(){
                    var BuildingID = $('#ddlBuildingForSite').val();
                    $('#frmRoomNodesEntry').attr('src', '<?php echo URL ?>customer/room_nodes_entry.php?building_id=' + BuildingID);
                    $('#room-nodes-modal').modal();
                    $('#simplemodal-container').css("width", "600px")
                    $('#simplemodal-container').css("height", "375px")
                    $('#simplemodal-container').css("left", "30%")
                    $('#simplemodal-container').css("top", "20%")
                    return false;
                });
                
                $('#Graph_Bottom_Options_3').click(function(){
                    var BuildingID = $('#ddlBuildingForSite').val();
                    $('#frmAlarmSettingsEntry').attr('src', '<?php echo URL ?>customer/alarm_settings_entry.php?building_id=' + BuildingID);
                    $('#alarm-settings-modal').modal();
                    $('#simplemodal-container').css("width", "640px")
                    $('#simplemodal-container').css("height", "375px")
                    $('#simplemodal-container').css("left", "26%")
                    $('#simplemodal-container').css("top", "20%")
                    return false;
                });
                
//                $('#Graph_Bottom_Options_4').click(function(){
//                   console.log(4); 
//                });
                
                $('#Gray_Button').click(function () {
                    //$('#Show_Dynamic_Sites').html('Buildings');
                    $('#Gray_Button').css('z-index', 1);
                    $('#Blue_Button').css('z-index', 0);
                    $('#Gray_Button').css('background-image', 'url("../images/blue_button.png")');
                    $('#Gray_Button div').css('color', '#FFFFFF');
                    $('#Blue_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Blue_Button div').css('color', '#666666');
                    $('#Building_Elements').css('display', 'block');
                    $('#Building_Elements_Details').css('display', 'none');
                });

                $('#Blue_Button').click(function () {
                    //$('#Show_Dynamic_Sites').html('Elements');
                    $('#Blue_Button').css('z-index', 1);
                    $('#Gray_Button').css('z-index', 0);
                    
                    $('#Blue_Button').css('background-image', 'url("../images/blue_button.png")');
                    $('#Blue_Button div').css('color', '#FFFFFF');
                    $('#Gray_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Gray_Button div').css('color', '#666666');

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

                $('#Site_Details_Summary_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 3);
                    $('#Site_Details_Summary_Button').css('z-index', 4);
                    
                    $('#Site_Details_Summary_Button').css('background-image', 'url("../images/blue_button.png")');
                    $('#Site_Details_Summary_Button div').css('color', '#FFFFFF');
                    $('#Site_Details_GHG_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Site_Details_GHG_Button div').css('color', '#666666');
                    $('#Site_Details_Metrics_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Site_Details_Metrics_Button div').css('color', '#666666');

                    $('#ddlGraphType').val('1');
                    $('#ddlGraphType').trigger('change');

                });

                $('#Site_Details_GHG_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 2);
                    $('#Site_Details_Summary_Button').css('z-index', 3);
                    $('#Site_Details_GHG_Button').css('z-index', 4);
                    
                    $('#Site_Details_GHG_Button').css('background-image', 'url("../images/blue_button.png")');
                    $('#Site_Details_GHG_Button div').css('color', '#FFFFFF');
                    $('#Site_Details_Summary_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Site_Details_Summary_Button div').css('color', '#666666');
                    $('#Site_Details_Metrics_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Site_Details_Metrics_Button div').css('color', '#666666');
                    
                    $('#ddlGraphType').val('2');
                    $('#ddlGraphType').trigger('change');
                });

                $('#Site_Details_Metrics_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 4);
                    $('#Site_Details_Summary_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 3);
                    
                    $('#Site_Details_Metrics_Button').css('background-image', 'url("../images/blue_button.png")');
                    $('#Site_Details_Metrics_Button div').css('color', '#FFFFFF');
                    $('#Site_Details_Summary_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Site_Details_Summary_Button div').css('color', '#666666');
                    $('#Site_Details_GHG_Button').css('background-image', 'url("../images/gray_button.png")');
                    $('#Site_Details_GHG_Button div').css('color', '#666666');
                    
                    $('#ddlGraphType').val('3');
                    $('#ddlGraphType').trigger('change');
                });

                $('#Site_Details_Energy_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 4);
                    $('#Site_Details_Metrics_Button').css('z-index', 3);
                    $('#Site_Details_Summary_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 1);
                    $('#ddlGraphType').val('4');
                    $('#ddlGraphType').trigger('change');
                });

                $('#ddlGraphType').change(function () {

                    $('#Graph_Summary_Window').html('Loading...');
                    $('#Large_Graph_Area').html('Loading...');

                    if (this.value == 1)
                    {
                        $('#ddlNodesForChartList').parent().hide();
                        $('#Large_Graph_Type').html('TEMPERATURE & HUMIDITY');
                        $('#SubTitle_Temperature').css('display', 'block');
                        $('#SubTitle_Consumption').css('display', 'none');
                        $('#SubTitle_Cost').css('display', 'none');
                        $('#SubTitle_savings').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_1').css('display', 'block');
                        $('#Graph_Type_Bottom_Options_2').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_3').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_4').css('display', 'none');

                        $('#Site_Details_Energy_Button').css('z-index', 1);
                        $('#Site_Details_Metrics_Button').css('z-index', 2);
                        $('#Site_Details_GHG_Button').css('z-index', 3);
                        $('#Site_Details_Summary_Button').css('z-index', 4);

                    } else if (this.value == 2)
                    {
                        $('#ddlNodesForChartList').parent().show();
                        $('#Large_Graph_Type').html('ENERGY CONSUMPTION');
                        $('#SubTitle_Temperature').css('display', 'none');
                        $('#SubTitle_Consumption').css('display', 'block');
                        $('#SubTitle_Cost').css('display', 'none');
                        $('#SubTitle_savings').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_1').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_2').css('display', 'block');
                        $('#Graph_Type_Bottom_Options_3').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_4').css('display', 'none');

                        $('#Site_Details_Energy_Button').css('z-index', 1);
                        $('#Site_Details_Metrics_Button').css('z-index', 2);
                        $('#Site_Details_Summary_Button').css('z-index', 3);
                        $('#Site_Details_GHG_Button').css('z-index', 4);

                    } else if (this.value == 3)
                    {
                        $('#ddlNodesForChartList').parent().show();
                        //$('#Large_Graph_Type').html('ENERGY COST BY SYSTEM');
                        $('#Large_Graph_Type').html('ENERGY DISCONNECT');
                        $('#SubTitle_Temperature').css('display', 'none');
                        $('#SubTitle_Consumption').css('display', 'none');
                        $('#SubTitle_Cost').css('display', 'block');
                        $('#SubTitle_savings').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_1').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_2').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_3').css('display', 'block');
                        $('#Graph_Type_Bottom_Options_4').css('display', 'none');

                        $('#Site_Details_Energy_Button').css('z-index', 1);
                        $('#Site_Details_Metrics_Button').css('z-index', 4);
                        $('#Site_Details_Summary_Button').css('z-index', 2);
                        $('#Site_Details_GHG_Button').css('z-index', 3);

                    } else if (this.value == 4)
                    {
                        $('#ddlNodesForChartList').parent().show();
                        $('#Large_Graph_Type').html('ENERGY SAVINGS BY SYSTEM');
                        $('#SubTitle_Temperature').css('display', 'none');
                        $('#SubTitle_Consumption').css('display', 'none');
                        $('#SubTitle_Cost').css('display', 'none');
                        $('#SubTitle_savings').css('display', 'block');
                        $('#Graph_Type_Bottom_Options_1').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_2').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_3').css('display', 'none');
                        $('#Graph_Type_Bottom_Options_4').css('display', 'block');

                        $('#Site_Details_Energy_Button').css('z-index', 4);
                        $('#Site_Details_Metrics_Button').css('z-index', 3);
                        $('#Site_Details_Summary_Button').css('z-index', 2);
                        $('#Site_Details_GHG_Button').css('z-index', 1);


                    }

                    var getElectricNGasButton1 = '';
                    var getElectricNGasButton2 = '';
                    var getElectricNGasButton3 = '';
                    var graphtype = 1;

                    getElectricNGasButton1 = $('#Electric_Consumption_Button').attr('class');
                    getElectricNGasButton2 = $('#Electric_Cost_Button').attr('class');
                    getElectricNGasButton3 = $('#Electric_Saving_Button').attr('class');

                    getElectricNGasButton1 = getElectricNGasButton1.indexOf("benchmark_button_active");
                    getElectricNGasButton2 = getElectricNGasButton2.indexOf("benchmark_button_active");
                    getElectricNGasButton3 = getElectricNGasButton3.indexOf("benchmark_button_active");

                    if (getElectricNGasButton1 > 0 || getElectricNGasButton2 > 0 || getElectricNGasButton3 > 0)
                    {
                        graphtype = 1;

                        $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");


                        $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Electric_Consumption_Button').addClass("benchmark_button_active");
                        $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                        $('#Electric_Cost_Button').addClass("benchmark_button_active");
                        $('#Electric_Saving_Button').removeClass("benchmark_button_active");
                        $('#Electric_Saving_Button').addClass("benchmark_button_active");
                    } else
                    {
                        graphtype = 2;

                        $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                        $('#Electric_Saving_Button').removeClass("benchmark_button_active");


                        $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");
                        $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
                        $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");

                    }


                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: this.value,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: graphtype,
                            },
                            function (data, status) {
                                $('#Graph_Summary_Window').html(data);
                            });



                    $('#ddlConsumption_Chart').val(graphtype);
                    $('#ddlCost_Chart').val(graphtype);
                    $('#ddlSaving_Chart').val(graphtype);

                    $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                            {
                                type: this.value,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                strType: graphtype,
                                node_id: $('#ddlNodesForChartList').val()
                            },
                            function (data, status) {
                                $('#Large_Graph_Area').html(data);
                            });

                });


                $('#ddlTemperature_Humidty_Chart').change(function () {
                    $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                            {
                                type: 1,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                strType: this.value,
                                node_id: $('#ddlNodesForChartList').val()
                            },
                            function (data, status) {
                                $('#Large_Graph_Area').html(data);
                            });
                });

                $('#ddlConsumption_Chart').change(function () {
                    $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                            {
                                type: 2,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                strType: this.value,
                                node_id: $('#ddlNodesForChartList').val()
                            },
                            function (data, status) {
                                $('#Large_Graph_Area').html(data);
                            });

                    if (this.value == 1)
                    {
                        var getElectricNGasButton = $('#Electric_Consumption_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Electric_Consumption_Button').trigger('click');
                        }
                    } else
                    {
                        var getElectricNGasButton = $('#Natural_Gas_Consumption_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Natural_Gas_Consumption_Button').trigger('click');
                        }
                    }

                });

                $('#ddlCost_Chart').change(function () {
                    $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                            {
                                type: 3,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                strType: this.value,
                                node_id: $('#ddlNodesForChartList').val()
                            },
                            function (data, status) {
                                $('#Large_Graph_Area').html(data);
                            });


                    if (this.value == 1)
                    {
                        var getElectricNGasButton = $('#Electric_Cost_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Electric_Cost_Button').trigger('click');
                        }
                    } else
                    {
                        var getElectricNGasButton = $('#Natural_Gas_Cost_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Natural_Gas_Cost_Button').trigger('click');
                        }
                    }

                });


                $('#ddlSaving_Chart').change(function () {
                    $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                            {
                                type: 4,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                strType: this.value,
                                node_id: $('#ddlNodesForChartList').val()
                            },
                            function (data, status) {
                                $('#Large_Graph_Area').html(data);
                            });


                    if (this.value == 1)
                    {
                        var getElectricNGasButton = $('#Electric_Saving_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Electric_Saving_Button').trigger('click');
                        }
                    } else
                    {
                        var getElectricNGasButton = $('#Natural_Gas_Saving_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Natural_Gas_Saving_Button').trigger('click');
                        }
                    }

                });


                $('#Electric_Consumption_Button').click(function ()
                {
                    graphtype = 1;

                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Consumption_Button').addClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').addClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').addClass("benchmark_button_active");

                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: 2,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: 1,
                            },
                            function (data, status) {
                                $('#Natural_Gas_Consumption_Button').removeClass('benchmark_button_active');
                                $('#Electric_Consumption_Button').addClass('benchmark_button_active');
                                $('#Graph_Summary_Window').html(data);
                            });
                            
                    $.get("<?php echo URL ?>ajax_pages/customers/node_list_by_building.php",
                        {
                            building_id: $('#ddlBuildingConsoleList').val(),
                            graphtype: 1
                        },
                        function (data, status) {
                            $('#ddlNodesForChartList').html(data);
                        });        

                    $('#ddlConsumption_Chart').val(1);
                    $('#ddlConsumption_Chart').trigger('change');

                });


                $('#Natural_Gas_Consumption_Button').click(function () {

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");


                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");

                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: 2,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: 2,
                            },
                            function (data, status) {
                                $('#Electric_Consumption_Button').removeClass('benchmark_button_active');
                                $('#Natural_Gas_Consumption_Button').addClass('benchmark_button_active');
                                $('#Graph_Summary_Window').html(data);
                            });
                            
                     $.get("<?php echo URL ?>ajax_pages/customers/node_list_by_building.php",
                        {
                            building_id: $('#ddlBuildingConsoleList').val(),
                            graphtype: 2
                        },
                        function (data, status) {
                            $('#ddlNodesForChartList').html(data);
                        });

                    $('#ddlConsumption_Chart').val(2);
                    $('#ddlConsumption_Chart').trigger('change');

                });


                $('#Electric_Cost_Button').click(function () {

                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Consumption_Button').addClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').addClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').addClass("benchmark_button_active");

                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: 3,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: 1,
                            },
                            function (data, status) {
                                $('#Natural_Gas_Cost_Button').removeClass('benchmark_button_active');
                                $('#Electric_Cost_Button').addClass('benchmark_button_active');
                                $('#Graph_Summary_Window').html(data);
                            });

                    $('#ddlCost_Chart').val(1);
                    $('#ddlCost_Chart').trigger('change');

                });


                $('#Natural_Gas_Cost_Button').click(function () {

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");


                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");

                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: 3,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: 2,
                            },
                            function (data, status) {
                                $('#Electric_Cost_Button').removeClass('benchmark_button_active');
                                $('#Natural_Gas_Cost_Button').addClass('benchmark_button_active');
                                $('#Graph_Summary_Window').html(data);
                            });

                    $('#ddlCost_Chart').val(2);
                    $('#ddlCost_Chart').trigger('change');

                });

                $('#Electric_Saving_Button').click(function () {

                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Consumption_Button').addClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').addClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').addClass("benchmark_button_active");

                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: 4,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: 1,
                            },
                            function (data, status) {
                                $('#Natural_Gas_Saving_Button').removeClass('benchmark_button_active');
                                $('#Electric_Saving_Button').addClass('benchmark_button_active');
                                $('#Graph_Summary_Window').html(data);
                            });

                    $('#ddlSaving_Chart').val(1);
                    $('#ddlSaving_Chart').trigger('change');

                });


                $('#Natural_Gas_Saving_Button').click(function () {

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");


                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");

                    $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                            {
                                type: 4,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                graphtype: 2,
                            },
                            function (data, status) {
                                $('#Electric_Saving_Button').removeClass('benchmark_button_active');
                                $('#Natural_Gas_Saving_Button').addClass('benchmark_button_active');
                                $('#Graph_Summary_Window').html(data);
                            });

                    $('#ddlSaving_Chart').val(2);
                    $('#ddlSaving_Chart').trigger('change');

                });
                
                $('#ddlSitesPortfolio').trigger('change');

            });

            function ChangeSiteDropdown(site_id){              
                $.get("<?php echo URL ?>ajax_pages/customers/dynamic_building_name_new.php",
                        {
                            site_id: site_id
                        },
                function (data, status) {
                    $('#Show_Dynamic_Buildings').html(data);
                    //ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    //UpdateBuildingElementDetails($('#ddlBuildingForSite').val(), 0);
                    $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                            {
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#Building_Details_Container').html(data);
                        $('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                        $('#ddlFilterElectric_Gas').val('1');
                        ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    });
                });
            }
            
            function UpdateAllBuildingDropdown(strBuildingID)
            {
                $("#ddlBuildingForSite").val(strBuildingID);
                $("#ddlBuildingConsoleList").val(strBuildingID);
                $("#ddlBuildingForChartList").val(strBuildingID);
                $("#ddlBuildingElemntsList").val(strBuildingID);

                $('#ddlBuildingForSite').trigger('change');

                UpdateBuildingElementDetails(strBuildingID, 0);


                var graphtype = 1;
                var getElectricNGasButton = '';
                if ($('#ddlGraphType').val() == 2)
                {
                    getElectricNGasButton = $('#Electric_Consumption_Button').attr('class');
                } else if ($('#ddlGraphType').val() == 3)
                {
                    getElectricNGasButton = $('#Electric_Cost_Button').attr('class');
                } else if ($('#ddlGraphType').val() == 4)
                {
                    getElectricNGasButton = $('#Electric_Saving_Button').attr('class');
                }

                getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                if (getElectricNGasButton > 0)
                {
                    graphtype = 1;

                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");


                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Consumption_Button').addClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').addClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').addClass("benchmark_button_active");
                } else
                {
                    graphtype = 2;

                    $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                    $('#Electric_Saving_Button').removeClass("benchmark_button_active");


                    $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
                    $('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");

                }


                $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                        {
                            type: $('#ddlGraphType').val(),
                            building_id: $('#ddlBuildingConsoleList').val(),
                            graphtype: graphtype,
                        },
                        function (data, status) {
                            $('#Graph_Summary_Window').html(data);
                        });

                $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                        {
                            type: $('#ddlGraphType').val(),
                            building_id: $('#ddlBuildingConsoleList').val(),
                            strType: graphtype,
                            node_id: $('#ddlNodesForChartList').val()
                        },
                        function (data, status) {
                            $('#Large_Graph_Area').html(data);
                        });


            }


            function ChangeBuildingDropdown(strBuildingID)
            {
                $("#ddlBuildingConsoleList").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingConsoleList');
                $("#ddlBuildingConsoleList").val(strBuildingID);


                $("#ddlBuildingElemntsList").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
                $("#ddlBuildingElemntsList").val(strBuildingID);


                /*$("#ddlSiteSummaryBuilding").empty();
                 $('#ddlBuildingForSite option').clone().appendTo('#ddlSiteSummaryBuilding');
                 $("#ddlSiteSummaryBuilding").val(strBuildingID);*/

                $("#ddlBuildingForChartList").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingForChartList');
                $("#ddlBuildingForChartList").val(strBuildingID);

                $.get("<?php echo URL ?>ajax_pages/customers/node_list_by_building.php",
                        {
                            building_id: strBuildingID
                        },
                        function (data, status) {
                            $('#ddlNodesForChartList').html(data);
                        });
                
                $('#Container_SystemsByBuilding').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_child_system.php",
                        {
                            building_id: strBuildingID
                        },
                        function (data, status) {
                            $('#Container_SystemsByBuilding').html(data);
                        });

                
                var graphtype = 1;

                if ($('#ddlGraphType').val() != 1)
                {
                    var getElectricNGasButton = '';
                    if ($('#ddlGraphType').val() == 2)
                    {
                        getElectricNGasButton = $('#Electric_Consumption_Button').attr('class');
                    } else if ($('#ddlGraphType').val() == 3)
                    {
                        getElectricNGasButton = $('#Electric_Cost_Button').attr('class');
                    } else if ($('#ddlGraphType').val() == 4)
                    {
                        getElectricNGasButton = $('#Electric_Saving_Button').attr('class');
                    }

                    getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                    if (getElectricNGasButton > 0)
                    {
                        graphtype = 1;

                        $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");


                        $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Electric_Consumption_Button').addClass("benchmark_button_active");
                        $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                        $('#Electric_Cost_Button').addClass("benchmark_button_active");
                        $('#Electric_Saving_Button').removeClass("benchmark_button_active");
                        $('#Electric_Saving_Button').addClass("benchmark_button_active");
                    } else
                    {
                        graphtype = 2;

                        $('#Electric_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Electric_Cost_Button').removeClass("benchmark_button_active");
                        $('#Electric_Saving_Button').removeClass("benchmark_button_active");


                        $('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");
                        $('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
                        $('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
                        $('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");

                    }
                }


                $.get("<?php echo URL ?>ajax_pages/customers/graphs_type.php",
                        {
                            type: $('#ddlGraphType').val(),
                            building_id: $('#ddlBuildingConsoleList').val(),
                            graphtype: graphtype,
                        },
                        function (data, status) {
                            $('#Graph_Summary_Window').html(data);
                        });

                $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                        {
                            type: $('#ddlGraphType').val(),
                            building_id: $('#ddlBuildingConsoleList').val(),
                            strType: graphtype,
                            node_id: $('#ddlNodesForChartList').val()
                        },
                        function (data, status) {
                            $('#Large_Graph_Area').html(data);
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
                } else
                {
                    //$('.System_ID_'+strSystemID).css('display','none');
                    $('.System_ID_' + strSystemID).slideUp('slow');
                    $('.System_ID_Sub_' + strSystemID).slideUp('slow');
                    $('.System_ID_Expand_' + strSystemID).html('+');
                }
                //$('.noclick').attr('onclick','').unbind('click');
            }
            
            function ddlNodesChanged(node_id){
                
                $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                {
                    type: $('#ddlGraphType').val(),
                    building_id: $('#ddlBuildingConsoleList').val(),
                    strType: $('#ddlConsumption_Chart').val(),
                    node_id: node_id
                },
                function (data, status) {
                    $('#Large_Graph_Area').html(data);
                });

            }
            
            function viewBuilding(){
                window.location.href = "<?php echo URL?>customer/systems.php";
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

                        <div style="position:absolute; z-index:1; width:35px; height:160px; background-size: 30px 160px; top:70px; background-image:url(<?php echo URL ?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Gray_Button_Text">Buildings</div>
                        </div>
                        <div style="position:absolute; z-index:0; width:35px; height:160px; background-size: 30px 160px; top:185px; background-image:url(<?php echo URL ?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Blue_Button_Text">Elements</div>
                        </div>

                    </div>

                    <div class="Windows_Main" style="margin-left:30px; border:1px solid #999999; border-radius:10px;">
                        <div class="Window_Title_Bg">

                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                <div class="heading">PORTFOLIO</div>
                            </div>

                            <div style="float:left; margin-left:20px;">
                                <img src="<?php echo URL ?>/images/window_title_divider.png" />
                            </div>
                            
                            <div style="float:right; margin-top:20px; margin-right:20px; color: rgb(102, 102, 102); font-size: 18px;">
                                <div>
                                    <select id="ddlSitesPortfolio" style="width:200px; font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;" onchange="ChangeSiteDropdown(this.value)">
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
                                <div id="view_building" style="margin-right: 10px; border-radius: 4px; cursor: pointer; float:right; font-size: 14px; text-align: center; padding:4px;background-color:#CCCCCC" onclick="viewBuilding();">View Building</div>
                                <div class="clear"></div>
                                <div id="Container_SystemsByBuilding" style="margin-top:15px; padding-top:3px; border-top:1px solid #999999; max-height:338px; overflow-y: auto;" class="myscroll"></div>
                            </div>
                            <div style="padding:15px 10px 10px 20px; display:none; min-height:310px;" id="Building_Elements_Details">&nbsp;</div>

                        </div>

                    </div>

                    <div class="clear"></div>

                    <br>

                </div>


                <div id="Customer_Right_Panel">

                    <div style="width:96%; padding:3% 2%; border-radius:10px; min-height:355px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">

                        <div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:392px; border-radius:5px;">


                            <div style="float:left; margin-left:10px;  margin-top:5px;">

                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBuildingConsoleList" id="ddlBuildingConsoleList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div style="float:right; margin-top:5px;">
                                <select name="ddlGraphType" id="ddlGraphType" style="width:200px; font-size:14px; font-family: UsEnergyEngineers;">
                                    <option value="1">TEMPERATURE & HUMIDITY</option>
                                    <option value="2">METERED CONSUMPTION</option>
<!--                                    <option value="3">COST BY SYSTEM</option>
                                    <option value="4">SAVINGS BY SYSTEM</option>-->
                                    <option value="3">Disconnect</option>
                                </select>                        	
                            </div>
                            <div class="clear"></div>

                            <div style="height:10px;"></div>                      


                            <div id="Graph_Summary_Window">Loading...</div>

                        </div>

                        <div id="Graph_Type_Bottom_Options_1">
                            <div id="Graph_Bottom_Options_1" style="color: #555555; width: 130px; cursor: pointer; border: 2px solid green; padding: 6px; margin-top:10px; margin-right:5px;  border-radius:7px; text-align:center; font-size:15px; text-transform:uppercase; float:left; font-weight: bold;" >BUILDING NODES</div>
                            <div id="Graph_Bottom_Options_2" style="color: #555555; width: 130px; cursor: pointer; border: 2px solid green; padding: 6px; margin-top:10px; margin-right:5px;  border-radius:7px; text-align:center; font-size:15px; text-transform:uppercase; float:left; font-weight: bold;" >ROOM NODES</div>
                            <div id="Graph_Bottom_Options_4" style="color: #555555; width: 110px; cursor: pointer; border: 1px solid #666666; padding: 6px; margin-top:10px; margin-left:5px; border-radius:7px; text-align:center; font-size:15px; text-transform:uppercase; float:right;" ><a href="javascript:void();" target="_blank" style="color: #555555;">NODE MAP</a></div>
                            <div id="Graph_Bottom_Options_3" style="color: #555555; width: 110px; cursor: pointer; border: 1px solid #666666; padding: 6px; margin-top:10px; margin-left:5px; border-radius:7px; text-align:center; font-size:15px; text-transform:uppercase; float:right;" >ALARM SETTINGS</div>
                        </div>
                        
                        <div class="clear"></div>

                        <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_2">
                            <div class="benchmark_button benchmark_button_active" style="float:left; cursor:pointer;" id="Electric_Consumption_Button">Electric Consumption</div>
                            <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                            <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px; cursor:pointer;" id="Natural_Gas_Consumption_Button">Natural Gas Consumption</div>                       
                            <div class="clear"></div>
                        </div>

                        <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_3">
                            <div class="benchmark_button benchmark_button_active" style="float:left;  cursor:pointer;" id="Electric_Cost_Button">Electric Cost</div>
                            <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                            <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px; cursor:pointer;" id="Natural_Gas_Cost_Button">Natural Gas Cost</div>                       
                            <div class="clear"></div>
                        </div>

                        <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_4">
                            <div class="benchmark_button benchmark_button_active" style="float:left; cursor:pointer;" id="Electric_Saving_Button">Electric Savings</div>
                            <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                            <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px; cursor:pointer;" id="Natural_Gas_Saving_Button">Natural Gas Savings</div>                       
                            <div class="clear"></div>
                        </div>

                    </div>

                </div>
                <div class="clear" style="height:30px;"></div>


                <div id="Customer_Left_Panel" style="width:93%;">


                    <div class="Windows_Left" style="position:relative; width:40px;">

                        <div style="position:absolute; z-index:3; width:35px; height:170px; background-size: 30px 175px; top:70px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Summary_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:135px; margin-left:5px; font-size:15px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Summary_Text">Temperature</div>
                        </div>
                        <div style="position:absolute; z-index:2; width:35px; height:260px; background-size: 30px 260px; top:190px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_GHG_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:215px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_GHG_Text">Metered&nbsp;Consumption</div>
                        </div>
<!--                        <div style="position:absolute; z-index:1; width:35px; height:159px; top:290px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:100px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Costs</div>
                        </div>
                        <div style="position:absolute; z-index:0; width:35px; height:159px; top:400px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Energy_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:110px; margin-left:5px; font-size:15px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Energy_Text">Savings</div>
                        </div>-->
                        <div style="position:absolute; z-index:1; width:35px; height:170px; background-size: 30px 170px; top:390px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                            <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:125px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Disconnect</div>
                        </div>

                    </div>


                    <div class="Windows_Main" style="margin-left:30px; border:1px solid #999999; border-radius:10px; width:100%;">
                        <div class="Window_Title_Bg" style="width:100%;">

                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                <div class="heading" id="Large_Graph_Type">TEMPERATURE & HUMIDITY</div>							
                            </div>                        
                            <div style="float:left; margin-left:20px;">
                                <img src="../images/window_title_divider.png" />
                            </div>
                            <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;">
                                <div id="SubTitle_Temperature"><!-- ALL AVAILABLE NODES -->
                                    <select id="ddlTemperature_Humidty_Chart" name="ddlTemperature_Humidty_Chart" style="text-transform:uppercase;">
                                        <option value="1">Temperature</option>
                                        <option value="2">Humidity</option>
                                    </select>
                                </div>
                                <div id="SubTitle_Consumption" style="display:none;"> 
                                    <select id="ddlConsumption_Chart" name="ddlConsumption_Chart" style="width:270px;">
                                        <option value="1">ELECTRIC CONSUMPTION</option>
                                        <option value="2">NATURAL GAS CONSUMPTION</option>
                                    </select>
                                </div>
                                <div id="SubTitle_Cost" style="display:none;"> 
                                    <select id="ddlCost_Chart" name="ddlCost_Chart">
                                        <option value="1">ELECTRIC SYSTEM</option>
                                        <option value="2">NATURAL GAS SYSTEM</option>
                                    </select>
                                </div>

                                <div id="SubTitle_savings" style="display:none;"> 
                                    <select id="ddlSaving_Chart" name="ddlSaving_Chart">
                                        <option value="1">ELECTRIC SAVINGS</option>
                                        <option value="2">NATURAL GAS SAVINGS</option>
                                    </select>
                                </div>

                            </div>
                            
                            <div style="float:left; margin-left:20px; margin-top:20px; display: none;">
                                <select onChange="ddlNodesChanged(this.value)" name="ddlNodesForChartList" id="ddlNodesForChartList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            
                            <div style="float:right; margin-right:20px; margin-top:20px;">
                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBuildingForChartList" id="ddlBuildingForChartList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="clear"></div>                        
                        </div>


                        <div class="Window_Container_Bg">                    
                            <div style="padding:15px 10px 10px 20px; min-height:500px;" id="Large_Graph_Area">        

                                Loading...

                            </div>
                        </div>                    
                    </div>

                    <div class="clear"></div>

                    <br>

                </div>

                <div class="clear"></div>
                
                <div id="building-nodes-modal"  style="display:none;">
                    <iframe id="frmBuildingNodesEntry" src="<?php echo URL ?>customer/building_nodes_entry.php" width="100%" height="350px" scrolling="no" style="border:none;"></iframe>
                </div>
                <div id="room-nodes-modal"  style="display:none;">
                    <iframe id="frmRoomNodesEntry" src="<?php echo URL ?>customer/room_nodes_entry.php" width="100%" height="350px" scrolling="no" style="border:none;"></iframe>
                </div>
                <div id="alarm-settings-modal"  style="display:none;">
                    <iframe id="frmAlarmSettingsEntry" src="<?php echo URL ?>customer/alarm_settings_entry.php" width="100%" height="350px" scrolling="no" style="border:none;"></iframe>
                </div>
            </div>
        </div>
        <div id="Building_Details_Container" style="display:none;"></div>
        <script src="<?php echo URL ?>highstock/js/highstock.js"></script>
        <script src="<?php echo URL ?>highstock/js/modules/exporting.js"></script>
<!--    <script src="<?php echo URL ?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo URL ?>highcharts/js/modules/exporting.js"></script>  -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
        
    </body>
</html>
