<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
require_once(AbsPath . "classes/customer.class.php");
require_once(AbsPath."classes/widget_category.class.php");

$DB = new DB;
$Category = new Category;
$System = new System;
$Gallery = new Gallery;
$Client = new Client;
$WidgetCategory=new WidgetCategory;

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

            #Container_SystemsByBuilding
            {
                display:none;
            }

        </style>

        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
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

                $('#Add_New_Billing_Account').click(function () {
                    var BuildingID = $('#ddlBuildingForSite').val();
                    $('#frmManualBillingEntry').attr('src', '<?php echo URL ?>customer/manual_billing_entry.php?building_id=' + BuildingID);
                    $('#billing-account-modal').modal();
                    $('#simplemodal-container').css("width", "900px")
                    $('#simplemodal-container').css("height", "375px")
                    $('#simplemodal-container').css("left", "15%")
                    $('#simplemodal-container').css("top", "20%")
                    return false;
                });
                
                $('#Add_New_Billing_Account_CSV').click(function () {
                    var BuildingID = $('#ddlBuildingForSite').val();
                    $('#frmCSVBillingEntry').attr('src', '<?php echo URL ?>customer/csv_billing_entry.php?building_id=' + BuildingID);
                    $('#billing-account-csv-import-modal').modal();
                    $('#simplemodal-container').css("width", "900px")
                    $('#simplemodal-container').css("height", "375px")
                    $('#simplemodal-container').css("left", "15%")
                    $('#simplemodal-container').css("top", "20%")
                    return false;
                });

                $('#Gray_Button').click(function () {
                    $('#Gray_Button').css('z-index', 1);
                    $('#Blue_Button').css('z-index', 0);
                    $('#Building_Elements').css('display', 'block');
                    $('#Building_Elements_Details').css('display', 'none');

                });

                $('#Blue_Button').click(function () {
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
                        UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                        $('#Building_Elements').css('display', 'none');
                    });

                });

                $('#Gray_Button_Text').click(function () {
                    $('#Gray_Button').trigger('click');
                });

                $('#Blue_Button_Text').click(function () {
                    $('#Blue_Button').trigger('click');
                });

            /*    $('#Site_Details_Summary_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 3);
                    $('#Site_Details_Summary_Button').css('z-index', 4);

                    $('#ddlGraphType').val('1');
                    $('#ddlGraphType').trigger('change');

                });

                $('#Site_Details_GHG_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 2);
                    $('#Site_Details_Summary_Button').css('z-index', 3);
                    $('#Site_Details_GHG_Button').css('z-index', 4);
                    $('#ddlGraphType').val('2');
                    $('#ddlGraphType').trigger('change');
                });

                $('#Site_Details_Metrics_Button').click(function () {
                    $('#Site_Details_Energy_Button').css('z-index', 1);
                    $('#Site_Details_Metrics_Button').css('z-index', 4);
                    $('#Site_Details_Summary_Button').css('z-index', 2);
                    $('#Site_Details_GHG_Button').css('z-index', 3);
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
            */            
                $('#ddlGraphType').change(function () {

                    $('#Graph_Summary_Window').html('Loading...');
                    $('#Large_Graph_Area').html('Loading...');

                });


                $('#ddlTemperature_Humidty_Chart').change(function () {
                    $.get("<?php echo URL ?>ajax_pages/customers/large_graphs_type.php",
                            {
                                type: 1,
                                building_id: $('#ddlBuildingConsoleList').val(),
                                strType: this.value,
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
                    }
                    else
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
                    }
                    else
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
                    }
                    else
                    {
                        var getElectricNGasButton = $('#Natural_Gas_Saving_Button').attr('class');
                        getElectricNGasButton = getElectricNGasButton.indexOf("benchmark_button_active");
                        if (getElectricNGasButton < 0)
                        {
                            $('#Natural_Gas_Saving_Button').trigger('click');
                        }
                    }

                });


                $('#Electric_Consumption_Button').click(function () {
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

/*                $('#Site_Details_Summary_Text').click(function () {
                    $('#Site_Details_Summary_Button').trigger('click');
                });

                $('#Site_Details_GHG_Text').click(function () {
                    $('#Site_Details_GHG_Button').trigger('click');
                });

                $('#Site_Details_Metrics_Text').click(function () {
                    $('#Site_Details_Metrics_Button').trigger('click');
                });

                $('#Site_Details_Energy_Text').click(function () {
                    $('#Site_Details_Energy_Button').trigger('click');
                });
*/
                //$('#Site_Details_Summary_Button').trigger('click');
                //$('#Gray_Button').trigger('click');
                
                $('#ddlSitesPortfolio').trigger('change');

            });
            
            function ChangeSiteDropdown(site_id){              
                
                $.get("<?php echo URL ?>ajax_pages/customers/dynamic_building_name_new.php",
                        {
                            site_id: site_id
                        },
                function (data, status) {
                    $('#Show_Dynamic_Buildings').html(data);
                    $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                            {
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#Building_Details_Container').html(data);
                        ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                        //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    });
                });

            }

            function GrayButtonClickLoad()
            {
                $.get("<?php echo URL ?>ajax_pages/customers/building_elements.php",
                        {
                            type: 1
                        },
                function (data, status) {
                    $('#Building_Elements').html(data);
                });
            }

            function UpdateBuildingElementDetails(strBuildingID, UpdateOtherBuildingDropDown)
            {

                $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                        {
                            building_id: strBuildingID
                        },
                function (data, status) {
                    $('#Building_Details_Container').html(data);

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
                }
                else if ($('#ddlGraphType').val() == 3)
                {
                    getElectricNGasButton = $('#Electric_Cost_Button').attr('class');
                }
                else if ($('#ddlGraphType').val() == 4)
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
                }
                else
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

            <div class="GrayBackground">    		

                <?php require_once("menu.php"); ?>

                <div id="Customer_Left_Panel">

                    <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                        <div class="Window_Title_Bg" style="width:515px;">

                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                <div class="heading">PORTFOLIO</div>

                            </div>

                            <div style="float:left; margin-left:20px;">
                                <img src="../images/window_title_divider.png" />
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
                        <div class="Window_Container_Bg" style="min-height:365px;">
                            <div style="padding:15px 10px 10px 20px;" id="Building_Elements">
                                <div style="float:left;">
                                    <div style="color:#666666; font-weight:bold; font-size:16px;" id="Show_Dynamic_Buildings">Loading...</div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div>
                                <div id="Add_New_Billing_Account" style="padding:3px; border:1px solid #666666; border-radius:3px; text-align: center; font-weight: bold; margin-bottom:3px; float:left; margin-left:20px; cursor:pointer;" title="Create account manually">MANUALLY CREATE ACCOUNT </div>

                                <div id="Add_New_Billing_Account_CSV" style="padding:3px; border:1px solid #666666; border-radius:3px; text-align: center; font-weight: bold; margin-bottom:3px; float:left; margin-left:10px; cursor:pointer;" title="Create account & import bill through CSV.">CSV IMPORT</div>
                                <div style="float:left; margin-top: 4px;  margin-left: 10px;"><a href="<?php echo URL ?>uploads/sample/Accounts_&_Billing_CSV_Sample.csv">Download</a> Sample CSV</div>

                                <div class="clear"></div>

                                <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>

                            </div>
                            <div style="margin-left:20px;">

                                <div style="color:#666666; font-weight:bold; font-size:16px; padding:0px 10px 10px 0px;">
                                    <div style="float:left; margin-top:4px;">BUILDING BILLING SUMMARY</div>
                                    <div style="float:left; margin-left:10px;">
                                        <select name="ddlBillingSummary" id="ddlBillingSummary" style="font-size:16px; width:200px; font-weight:bold; color:#666666; font-family: UsEnergyEngineers;">                                  
                                            <?php Globals::Year(date("Y"), 2013, date("Y")); ?>
                                        </select>                                  
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div style="float:left; width:150px;">&nbsp;</div>
                                <div style="float:left; text-decoration:underline; margin-left:10px; width:90px; padding:2px 5px;">METERED</div>
                                <div style="float:left; text-decoration:underline; margin-left:15px; width:90px; padding:2px 5px;">BILLED</div>
                                <div style="float:left; text-decoration:underline; margin-left:15px; width:90px; padding:2px 5px;">TOTAL COST</div>
                                <div class="clear"></div>

                                <div style="float:left; width:150px;">ELECTRICITY USAGE</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;">234,567 kWh</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">201,567 kWh</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">$68,567</div>
                                <div class="clear" style="margin-top:3px;"></div>

                                <div style="float:left; width:150px;">NATURAL GAS USAGE</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;">4,567 kWh</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">1,567 kWh</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">$21,567</div>
                                <div class="clear" style="margin-top:3px;"></div>

                                <div style="float:left; width:150px;">HEATING DEGREE DAYS</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;">54</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">34</div>

                                <div class="clear" style="margin-top:3px;"></div>

                                <div style="float:left; width:150px;">COOLING DEGREE DAYS</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;">54</div>
                                <div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">34</div>

                                <div class="clear" style="margin-top:3px;"></div>

                                <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:10px 0px;"></div>

                            </div>

                            <br>
                        </div>
                    </div>

                    <div class="clear"></div>
                    <br>
                </div>

                <div id="Customer_Right_Panel" style="margin-left:10px;">

                    <div style="width:96%; padding:3% 2%; border-radius:10px; min-height:355px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">

                        <div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:300px; border-radius:5px;">


                            <div style="float:left; margin-left:10px;  margin-top:5px;">

                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBuildingConsoleList" id="ddlBuildingConsoleList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div style="float:right; margin-top:5px;"><span style="float:left; margin-left:10px;">
                                    <select name="ddlBillingSummary2" id="ddlBillingSummary2" style="font-size:16px; width:200px; font-weight:bold; color:#666666; font-family: UsEnergyEngineers;">
                                        <?php Globals::Year(date("Y"), 2013, date("Y")); ?>
                                    </select>
                                </span></div>
                            <div class="clear"></div>

                            <div style="height:10px;"></div>                      


                            <div style="font-size:16px; margin-left:10px; font-weight:bold;">                        
                                <div style="float:left; width:170px; margin-left:0px;">BUILDING COMBINED</div>
                                <div style="float:left; width:170px; margin-left:10px;">
                                    <div style="float:left;">ELECTRICTY</div>
                                    <div style="float:left; margin-left:10px; cursor:pointer; font-size:12px; padding:0px 5px; background-color:#CCCCCC; border-radius:3px;">Update</div>
                                    <div class="clear"></div>
                                </div>
                                <div style="float:left; width:170px; margin-left:10px;">                            	
                                    <div style="float:left;">NATURAL GAS</div>
                                    <div style="float:left; margin-left:10px; cursor:pointer; font-size:12px; padding:0px 5px; background-color:#CCCCCC; border-radius:3px;">Update</div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>
                            </div>

                            <div style="font-size:15px; margin-left:10px; line-height:25px; margin-top:10px;">
                                <div style="float:left; width:170px;">&nbsp;</div>
                                <div style="float:left; width:170px; margin-left:10px;">
                                    <div style="float:left;">ALL ACCOUNTS</div>
                                    <div style="float:left; margin-left:5px; background-color:#CCCCCC; padding:0px 5px; border-radius:15px;">10</div>
                                    <div class="clear"></div>
                                </div>
                                <div style="float:left; width:170px; margin-left:10px;">
                                    <div style="float:left;">ALL ACCOUNTS</div>
                                    <div style="float:left; margin-left:5px; background-color:#CCCCCC; padding:0px 5px; border-radius:15px;">10</div>
                                    <div class="clear"></div>
                                </div>
                            </div>


                            <div style="font-size:11px; margin-left:10px; line-height:20px; margin-top:40px;">
                                <div style="float:left; width:170px;">
                                    <div>COMBINED ENERGY: 345,876 MBTU</div>
                                    <div>COMBINED COSTS: $85,876</div>
                                    <div>ANALYSIS DAYS: 265</div>
                                </div>  

                                <div style="float:left; width:170px; margin-left:10px;">
                                    <div>ELECTRIC KWH: 345,876</div>
                                    <div>ELECTRIC COSTS: $185,876</div>
                                    <div>AVERAGE COST/KWH: $0.65</div>
                                </div>

                                <div style="float:left; width:170px; margin-left:10px;">
                                    <div>NAT. GAS THERMS: 5,876</div>
                                    <div>NAT. GAS COSTS: $185,876</div>
                                    <div>AVERAGE COST/THERM: $0.95</div>
                                </div>

                                <div class="clear"></div>

                            </div>


                            <div style="font-size:10px; margin-left:10px; margin-top:10px; width:170px; float:left; border:1px solid #666666;">

                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                                    <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MBTU</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MBTU</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">41465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>                          
                                </table>

                            </div>

                            <div style="font-size:10px; margin-left:10px; margin-top:10px; width:170px; float:left; border:1px solid #666666;">

                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                                    <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">                              KWH</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/KWH</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">41465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>                          
                                </table>

                            </div>

                            <div style="font-size:10px; margin-left:10px; margin-top:10px; width:170px; float:left; border:1px solid #666666;">

                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                                    <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">                              THERMS</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/THERM</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">41465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">8/1/2015</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">23,456</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$41,465</td>
                                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.57</td>
                                    </tr>                          
                                </table>

                            </div>
                            <div class="clear"></div>
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
                <div class="clear" style="margin-top:20px;"></div>
                <div id="Customer_Left_Panel" style="width:93%;">
                    <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px; width:100%;">
                        <div class="Window_Title_Bg" style="width:100%;">

                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                <div class="heading" id="Large_Graph_Type">MONTHLY PROFILE</div>							
                            </div>                        
                            <div style="float:left; margin-left:20px;">
                                <img src="../images/window_title_divider.png" />
                            </div>
                            <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;">
                                <div id="SubTitle_Temperature"><!-- ALL AVAILABLE NODES -->
                                    <select name="ddlMonthlyProfileYear" id="ddlMonthlyProfileYear" style="font-size:16px; width:200px; font-weight:bold; color:#666666; font-family: UsEnergyEngineers;">
                                        <?php Globals::Year(date("Y"), 2013, date("Y")); ?>
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

                            <div style="float:right; margin-right:20px; margin-top:20px;">
                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBuildingForChartList" id="ddlBuildingForChartList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="clear"></div>                        
                        </div>
                        <div class="Window_Container_Bg">

                            <script type="text/javascript">
                                $(function () {
                                    $('#container_electric_chart').highcharts({
                                        chart: {
                                            zoomType: 'xy'
                                        },
                                        title: {
                                            text: 'Electric Consumption Profile - ' + $('#ddlMonthlyProfileYear').val()
                                        },
                                        xAxis: [{
                                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                            }],
                                        yAxis: [{// Primary yAxis
                                                labels: {
                                                    format: '{value}',
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                },
                                                title: {
                                                    text: 'Consumption (kWh)',
                                                    style: {
                                                        color: '#000000',
                                                        fontWeight: 'normal',
                                                    }
                                                }
                                            }, {// Secondary yAxis
                                                title: {
                                                    text: 'Energy Cost ($)',
                                                    style: {
                                                        color: '#000000',
                                                        fontWeight: 'normal',
                                                    }
                                                },
                                                labels: {
                                                    format: '${value}',
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                },
                                                opposite: true
                                            }],
                                        tooltip: {
                                            shared: true
                                        },
                                        legend: {
                                            layout: 'horizontal',
                                            backgroundColor: '#FFFFFF'
                                        },
                                        series: [{
                                                name: 'Energy Cost ($)',
                                                color: '#e3b601',
                                                type: 'column',
                                                yAxis: 1,
                                                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                                                tooltip: {
                                                    valueSuffix: ' mm'
                                                }

                                            }, {
                                                name: 'Consumption (kWh)',
                                                color: '#801617',
                                                type: 'spline',
                                                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
                                                tooltip: {
                                                    valueSuffix: '°C'
                                                }
                                            }]
                                    });



                                    $('#container_natural_gas_chart').highcharts({
                                        chart: {
                                            zoomType: 'xy'
                                        },
                                        title: {
                                            text: 'Natural Gas Consumption Profile -' + $('#ddlMonthlyProfileYear').val()
                                        },
                                        xAxis: [{
                                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                            }],
                                        yAxis: [{// Primary yAxis
                                                labels: {
                                                    format: '{value}',
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                },
                                                title: {
                                                    text: 'Consumption (Therms)',
                                                    style: {
                                                        color: '#000000',
                                                        fontWeight: 'normal',
                                                    }
                                                }
                                            }, {// Secondary yAxis
                                                title: {
                                                    text: 'Energy Costs ($)',
                                                    style: {
                                                        color: '#000000',
                                                        fontWeight: 'normal',
                                                    }
                                                },
                                                labels: {
                                                    format: '${value}',
                                                    style: {
                                                        color: '#000000'
                                                    }
                                                },
                                                opposite: true
                                            }],
                                        tooltip: {
                                            shared: true
                                        },
                                        legend: {
                                            layout: 'horizontal',
                                            backgroundColor: '#FFFFFF'
                                        },
                                        series: [{
                                                name: 'Energy Costs ($)',
                                                color: '#4572A7',
                                                type: 'column',
                                                yAxis: 1,
                                                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                                                tooltip: {
                                                    valueSuffix: ' mm'
                                                }

                                            }, {
                                                name: 'Consumption (Therms)',
                                                color: '#666666',
                                                type: 'spline',
                                                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
                                                tooltip: {
                                                    valueSuffix: '°C'
                                                }
                                            }]
                                    });
                                });


                            </script>


                            <div style="padding:10px 10px 10px 20px; min-height:450px;" id="Large_Graph_Area">        

                                <div style="float:left; width:46%;">
                                    <div style="font-weight:bold; font-size:18px;">ELECTRICTY</div>




                                    <div style="border:1px solid #999999; width:500px;">
                                        <div id="container_electric_chart" style="min-width: 380px; height: 300px; margin: 0 auto"></div>
                                    </div>

                                    <div style="font-weight:bold;">
                                        <div style="float:left; width:40%; color:#801617;">Electrical ECI:</div> <div style="float:left; width:50%; color:#801617;"> 0.37 per sq. ft. /yr</div>
                                        <div class="clear"></div>
                                        <div style="float:left; width:40%;">Electric Energy Usage:</div><div style="float:left; width:50%;"> 1 ,311,556.2 kBTU/yr</div>
                                        <div class="clear"></div>
                                        <div style="float:left; width:40%; color:#801617;">Electrical EUI:</div> <div style="float:left; width:50%; color:#801617;">3.32 kWh/sq. ft. /Yr</div>
                                        <div class="clear"></div>
                                        <div style="float:left; width:40%;">Average Cost:</div><div style="float:left; width:50%;"> $ 0 .112 /kWh</div>
                                        <div class="clear"></div>
                                    </div>

                                </div>

                                <div style="float:left; width:46%; margin-left:4%;">
                                    <div style="font-weight:bold; font-size:18px;">NATURAL GAS</div>
                                    <div style="border:1px solid #999999; width:500px;">
                                        <div id="container_natural_gas_chart" style="min-width: 380px; height: 300px; margin: 0 auto"></div>
                                    </div>

                                    <div style="font-weight:bold;">
                                        <div style="float:left; width:40%; color:#801617;">Natural Gas ECI:</div> <div style="float:left; width:50%; color:#801617;"> 0.37 per sq. ft. /yr</div>
                                        <div class="clear"></div>
                                        <div style="float:left; width:40%;">Gas Energy Usage:</div><div style="float:left; width:50%;"> 1 ,311,556.2 kBTU/yr</div>
                                        <div class="clear"></div>
                                        <div style="float:left; width:40%; color:#801617;">Natural Gas EUI:</div> <div style="float:left; width:50%; color:#801617;">3.32 Therms/sq. ft. /Yr</div>
                                        <div class="clear"></div>
                                        <div style="float:left; width:40%;">Average Cost:</div><div style="float:left; width:50%;"> $ 0 .112 /Therm</div>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                                <div class="clear"></div>

                            </div>
                        </div>                    
                    </div>

                    <div class="clear"></div>

                    <br>

                </div>

                <div class="clear"></div>

                <div id="billing-account-modal"  style="display:none;">
                    <iframe id="frmManualBillingEntry" src="<?php echo URL ?>customer/manual_billing_entry.php" width="100%" height="350px" scrolling="no" style="border:none;"></iframe>
                </div>
                <div id="billing-account-csv-import-modal"  style="display:none;">
                    <iframe id="frmCSVBillingEntry" src="<?php echo URL ?>customer/csv_billing_entry.php" width="100%" height="350px" scrolling="no" style="border:none;"></iframe>
                </div>
            </div>
        </div>
        <div id="Building_Details_Container" style="display: none;"></div>
        <script src="<?php echo URL ?>highstock/js/highstock.js"></script>
        <script src="<?php echo URL ?>highstock/js/modules/exporting.js"></script>
        <!--<script src="<?php echo URL ?>highcharts/js/highcharts.js"></script>-->
        <!--<script src="<?php echo URL ?>highcharts/js/modules/exporting.js"></script>-->  
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    </body>
</html>