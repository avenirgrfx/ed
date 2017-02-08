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

                $('#ddlBillingSummary').change(function(){
                    $('#ddlBillingSummary2').val($(this).val());
                    $('#ddlMonthlyProfileYear').val($(this).val());
                    ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                });
                                
                $('#ddlBillingSummary2').change(function(){
                    $('#ddlBillingSummary').val($(this).val());
                    $('#ddlMonthlyProfileYear').val($(this).val());
                    ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                });
                                
                $('#ddlMonthlyProfileYear').change(function(){
                    $('#ddlBillingSummary').val($(this).val());
                    $('#ddlBillingSummary2').val($(this).val());
                    ChangeBuildingDropdown($('#ddlBuildingForSite').val());
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
                    
//                    // Get the year dropdowns
//                    $.get("<?php echo URL ?>ajax_pages/customers/billing_year_dropdown.php",
//                            {
//                                building_id: $('#ddlBuildingForSite').val()
//                            },
//                    function (data, status) {
//                        // Set Dropdowns
//                        
//                        $('#ddlBillingSummary').html(data);
//                        $('#ddlBillingSummary2').html(data);
//                        $('#ddlMonthlyProfileYear').html(data);
//                        
//                        
//                    });

                      ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                      //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    
                    
                    // Get the building current time in its timezone
                    $.get("<?php echo URL ?>ajax_pages/customers/building_details.php",
                            {
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#Building_Details_Container').html(data);
                        //ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                        //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    });
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
                
                $('#accounts_dropdowns').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/utility_accounts_dropdown.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlBillingSummary2').val(),
                        },
                function (data, status) {
                    $('#accounts_dropdowns').html(data);
                });
                
                $('#Window_Container_Bg').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/building_billing_summary_graph.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                        },
                function (data, status) {
                    $('#Window_Container_Bg').html(data);
                });
                
                $('#building_billing_summary').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/building_billing_summary.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                        },
                function (data, status) {
                    $('#building_billing_summary').html(data);
                });
                
            }

        </script>
    </head>
    <body>

        <div id="Customer_Main_Container">
            <div id="Customer_Header_Section">
                <div style="float:left; border-right:1px solid #333333; padding-right:10px;margin-top:37px;">
                    <?php echo Globals::Resize('../uploads/customer/' . $client_logo, 150, 70); ?>
                </div>
                <div style="float:left; margin-left:50px;margin-top:17px;margin-top:32px;">
                    <h5 style="text-transform:uppercase;"><?php echo $client_name; ?></h5>
                    <span style="font-size:24px;"><?php echo $software_version ?> - <?php echo $client_type; ?></span>
                </div>
                <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
                    <div id="Logo">
                <a href="<?php echo URL ?>"><img src="<?php echo URL ?>images/logo.png" border="0" width="185px" height="70px" /></a>
            </div>
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
                                <div style="float:left; margin-top: -7px;  margin-left: 10px; font-size: 13px;"><p style="margin: 0;"><a href="<?php echo URL ?>uploads/sample/Accounts_&_Billing_CSV_Electric_Sample.csv">Download</a> Electric Sample CSV</p><p style="margin: 0;"><a href="<?php echo URL ?>uploads/sample/Accounts_&_Billing_CSV_Nat_Gas_Sample.csv">Download</a> Nat. Gas Sample CSV</p></div>

                                <div class="clear"></div>

                                <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>

                            </div>
                            <div style="margin-left:20px;">

                                <div style="color:#666666; font-weight:bold; font-size:16px; padding:0px 10px 10px 0px;">
                                    <div style="float:left; margin-top:4px;">BUILDING BILLING SUMMARY</div>
                                    <div style="float:left; margin-left:10px;">
                                        <select name="ddlBillingSummary" id="ddlBillingSummary" style="font-size:16px; width:200px; font-weight:bold; color:#666666; font-family: UsEnergyEngineers;">                                  
                                            <?php Globals::Year(date("Y"), 2012, date("Y")); ?>
                                        </select>                                  
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div style="float:left; width:150px;">&nbsp;</div>
                                <div style="float:left; text-decoration:underline; margin-left:10px; width:90px; padding:2px 5px;">METERED</div>
                                <div style="float:left; text-decoration:underline; margin-left:15px; width:90px; padding:2px 5px;">BILLED</div>
                                <div style="float:left; text-decoration:underline; margin-left:10px; width:105px; padding:2px 0px; font-size: 12px;">TOTAL BILLED COST</div>
                                <div class="clear"></div>
                                
                                <div id="building_billing_summary">
                                    Loading...
                                </div>
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
                                        <?php Globals::Year(date("Y"), 2012, date("Y")); ?>
                                    </select>
                                </span></div>
                            <div class="clear"></div>

                            <div style="height:10px;"></div>                      


                            <div style="font-size:16px; margin-left:10px; font-weight:bold;">                        
                                <div style="float:left; width:170px; margin-left:0px;">BUILDING COMBINED</div>
                                <div style="float:left; width:170px; margin-left:10px;">
                                    <div style="float:left;">ELECTRICTY</div>
                                    <div style="float:left; margin-left:10px; cursor:pointer; font-size:12px; padding:0px 5px; background-color:#CCCCCC; border-radius:3px;"><!--Update--></div>
                                    <div class="clear"></div>
                                </div>
                                <div style="float:left; width:170px; margin-left:10px;">                            	
                                    <div style="float:left;">NATURAL GAS</div>
                                    <div style="float:left; margin-left:10px; cursor:pointer; font-size:12px; padding:0px 5px; background-color:#CCCCCC; border-radius:3px;"><!--Update--></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>
                            </div>

                            <div id="accounts_dropdowns" style="font-size:15px; margin-left:10px; line-height:25px; margin-top:5px;">
                                Loading...
                            </div>

                            <div id="combind_utility_data" style="margin-left:10px; margin-top:5px; width:170px; float:left;">
                                
                                <div style="font-size:11px; line-height:20px;">
                                    <div>COMBINED ENERGY: 0 MBTU</div>
                                    <div>COMBINED COSTS: $0</div>
                                    <div>ANALYSIS DAYS: 265</div>
                                </div> 
                                <div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                                        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MBTU</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MBTU</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                            <div id="electric_utility_data" style="margin-left:10px; margin-top:5px; width:170px; float:left;">
                                
                                <div style="font-size:11px; line-height:20px;">
                                    <div>ELECTRIC KWH: 0</div>
                                    <div>ELECTRIC COSTS: $0</div>
                                    <div>AVERAGE COST/KWH: $0.00</div>
                                </div>
                                
                                <div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                                        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MBTU</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MBTU</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                            <div id="gas_utility_data" style="margin-left:10px; margin-top:5px; width:170px; float:left;">
                                
                                <div style="font-size:11px; line-height:20px;">
                                    <div>NAT. GAS THERMS: 0</div>
                                    <div>NAT. GAS COSTS: $0</div>
                                    <div>AVERAGE COST/THERM: $0.00</div>
                                </div>
                                
                                <div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                                        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">THERMS</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/THERM</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                        <tr>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
                                            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>

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
                                        <?php Globals::Year(date("Y"), 2012, date("Y")); ?>
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
                        <div class="Window_Container_Bg" id="Window_Container_Bg">
                            Loading...
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