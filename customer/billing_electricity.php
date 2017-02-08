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
                
                $('#Update_Meter_Data_CSV').click(function () {
                    var BuildingID = $('#ddlBuildingForSite').val();
                    var account_id = $('#ddlAccountList').val();
                    var meter_id = $('#ddlMeterList').val();
                    $('#frmCSVBillingEntry').attr('src', '<?php echo URL ?>customer/csv_billing_entry_meter.php?building_id=' + BuildingID + '&account_id=' + account_id + '&meter_id=' + meter_id);
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
                
                $('#ddlAccountList').change(function(){
                    $('#ddlMeterList').html('');
                    $.get("<?php echo URL ?>ajax_pages/customers/building_billing_meters.php",
                            {
                                account_id: $('#ddlAccountList').val(),
                                year: $('#ddlBillingSummary2').val(),
                            },
                    function (data, status) {
                        $('#ddlMeterList').html(data);
                        $('#ddlMeterList').trigger('change');
                    });
                });
                
                $('#ddlMeterList').change(function(){
                    $('#utility_account_meter_data').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/customers/utility_electric_account_meter_data.php",
                            {
                                year: $('#ddlBillingSummary2').val(),
                                meter_id: $('#ddlMeterList').val(),
                            },
                    function (data, status) {
                        $('#utility_account_meter_data').html(data);
                    });
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
                
                $('#ddlAccountList').html('');
                $.get("<?php echo URL ?>ajax_pages/customers/building_billing_accounts.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                            account_type: 1
                        },
                function (data, status) {
                    $('#ddlAccountList').html(data);
                    $('#ddlAccountList').trigger('change');
                });
                
                $('#building_billing_summary').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/building_billing_summary_electric.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                        },
                function (data, status) {
                    $('#building_billing_summary').html(data);
                });
                
                $('#graph_container').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/building_billing_summary_electric_graph.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                        },
                function (data, status) {
                    $('#graph_container').html(data);
                });
                
                $('#degree_days_data').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/building_degree_days.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                        },
                function (data, status) {
                    $('#degree_days_data').html(data);
                });
                
                $('#electricity_meter_vs_billed').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/utility_electricity_meter_vs_billed.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            year: $('#ddlMonthlyProfileYear').val(),
                        },
                function (data, status) {
                    $('#electricity_meter_vs_billed').html(data);
                });
                
            }
            
            function editBillData(id){
                $('#edit_'+id).hide();
                $('#update_'+id).show();
                
                $('#from_'+id).css("width", "90px");
                $('#to_'+id).css("width", "90px");
                $('#consumption_'+id).css("width", "90px");
                $('#cost_'+id).css("width", "90px");
                
                $('#from_'+id).html('<input type="text" id="text_from_'+id+'" value="'+$('#from_'+id).html()+'" style="width:80%; height:12px;">');
                $('#to_'+id).html('<input type="text" id="text_to_'+id+'" value="'+$('#to_'+id).html()+'" style="width:80%; height:12px;">');
                $('#consumption_'+id).html('<input type="text" id="text_consumption_'+id+'" value="'+$('#consumption_'+id).html()+'" style="width:80%; height:12px;">');
                $('#cost_'+id).html('<input type="text" id="text_cost_'+id+'" value="'+$('#cost_'+id).html()+'" style="width:80%; height:12px;">');
            }
            
            function updateBillData(id){
                
                $.post("<?php echo URL ?>ajax_pages/customers/utility_electric_account_meter_data.php",
                        {
                            'id'            : id,
                            'from'          : $('#text_from_'+id).val(),
                            'to'            : $('#text_to_'+id).val(),
                            'consumption'   : $('#text_consumption_'+id).val(),
                            'cost'          : $('#text_cost_'+id).val(),
                            'year'          : $('#ddlBillingSummary2').val(),
                            'meter_id'      : $('#ddlMeterList').val()
                        },
                function (data, status) {
                    console.log(data);
                    $('#ddlMeterList').trigger('change');
                    alert("successfully updated.");
                });
                
                $('#update_'+id).hide();
                $('#edit_'+id).show();
                
                $('#from_'+id).css("width", "auto");
                $('#to_'+id).css("width", "auto");
                $('#consumption_'+id).css("width", "auto");
                $('#cost_'+id).css("width", "auto");
                
                $('#from_'+id).html($('#text_from_'+id).val());
                $('#to_'+id).html($('#text_to_'+id).val());
                $('#consumption_'+id).html($('#text_consumption_'+id).val());
                $('#cost_'+id).html($('#text_cost_'+id).val());
            }
            
            function cancelBillData(id){
                $('#update_'+id).hide();
                $('#edit_'+id).show();
                
                $('#from_'+id).css("width", "auto");
                $('#to_'+id).css("width", "auto");
                $('#consumption_'+id).css("width", "auto");
                $('#cost_'+id).css("width", "auto");
                
                $('#from_'+id).html($('#text_from_'+id).val());
                $('#to_'+id).html($('#text_to_'+id).val());
                $('#consumption_'+id).html($('#text_consumption_'+id).val());
                $('#cost_'+id).html($('#text_cost_'+id).val());
            }
            
            function editNewBillData(id){
                $('#edit_'+id).hide();
                $('#update_'+id).show();
                
                $('#from_'+id).css("width", "100px");
                $('#to_'+id).css("width", "100px");
                $('#consumption_'+id).css("width", "90px");
                $('#cost_'+id).css("width", "90px");
                
                $('#from_'+id).html('<input type="text" id="text_from_'+id+'" value="" placeholder="mm/dd/yyyy" style="width:80%; height:14px;">');
                $('#to_'+id).html('<input type="text" id="text_to_'+id+'" value="" placeholder="mm/dd/yyyy" style="width:80%; height:14px;">');
                $('#consumption_'+id).html('<input type="text" id="text_consumption_'+id+'" value="'+$('#consumption_'+id).html()+'" style="width:80%; height:14px;">');
                $('#cost_'+id).html('<input type="text" id="text_cost_'+id+'" value="'+$('#cost_'+id).html()+'" style="width:80%; height:14px;">');
            }
            
            function addBillData(id){
                
                $.post("<?php echo URL ?>ajax_pages/customers/utility_electric_account_meter_data.php",
                        {
                            'id'            : '',
                            'from'          : $('#text_from_'+id).val(),
                            'to'            : $('#text_to_'+id).val(),
                            'consumption'   : $('#text_consumption_'+id).val(),
                            'cost'          : $('#text_cost_'+id).val(),
                            'year'          : $('#ddlBillingSummary2').val(),
                            'meter_id'      : $('#ddlMeterList').val()
                        },
                function (data, status) {
                    console.log(data);
                    $('#ddlMeterList').trigger('change');
                    alert("successfully updated.");
                });
                
                $('#update_'+id).hide();
                $('#edit_'+id).show();
                
                $('#from_'+id).css("width", "auto");
                $('#to_'+id).css("width", "auto");
                $('#consumption_'+id).css("width", "auto");
                $('#cost_'+id).css("width", "auto");
                
                $('#from_'+id).html($('#text_from_'+id).val());
                $('#to_'+id).html($('#text_to_'+id).val());
                $('#consumption_'+id).html($('#text_consumption_'+id).val());
                $('#cost_'+id).html($('#text_cost_'+id).val());
            }
            
            function cancelNewBillData(id){
                $('#update_'+id).hide();
                $('#edit_'+id).show();
                
                $('#from_'+id).css("width", "auto");
                $('#to_'+id).css("width", "auto");
                $('#consumption_'+id).css("width", "auto");
                $('#cost_'+id).css("width", "auto");
                
                $('#from_'+id).html("--");
                $('#to_'+id).html("--");
                $('#consumption_'+id).html("0");
                $('#cost_'+id).html("$0");
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
                        <div class="Window_Container_Bg" style="min-height:445px;">
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

                                <div id="building_billing_summary">
                                    Loading...
                                </div>

                            </div>

                            <br>

                        </div>

                    </div>

                    <div class="clear"></div>

                    <br>

                </div>

                <div id="Customer_Right_Panel" style="margin-left:10px;">

                    <div style="width:96%; padding:3% 2%; border-radius:10px; min-height:355px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">

                        <div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:465px; border-radius:5px;">


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
                                <div style="float:left; width:250px; margin-left:0px;">ELECTRICITY BILLING</div>
                                <div class="clear" style="margin:5px 0px;"></div>
                            </div>

                            <div>
                                <div style="float:left;">
                                    <select  name="ddlAccountList" id="ddlAccountList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                        
                                    </select>
                                </div>

                                <div style="float:left; margin-left:10px;">
                                    <select  name="ddlMeterList" id="ddlMeterList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                        
                                    </select>
                                </div>

                                <div style="float:left; margin-left:10px;">
                                    <div style="cursor:pointer; width:70px; margin:0px auto; font-size:15px; padding:0px 3px; background-color:#CCCCCC; border-radius:3px; text-align: center; font-weight: bold; margin-top: 3px;" id="Update_Meter_Data_CSV">Update</div>
                                </div>

                                <div class="clear" style="margin:5px 0px; height:5px;"></div>

                            </div>

                            <div style="margin-bottom:5px;">
                                UTILITY: <strong id="utility_name"></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACCOUNT#: <strong id="utility_account_number"></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; METER# <strong id="utility_meter_number"></strong>
                            </div>

                            <div id="utility_account_meter_data">  
                                Loading...
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
                            </div>

                            <div style="float:right; margin-right:20px; margin-top:20px;">
                                <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBuildingForChartList" id="ddlBuildingForChartList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="clear"></div>                        
                        </div>


                        <div class="Window_Container_Bg">

                            <div style="padding:10px 10px 10px 20px; min-height:450px;" id="Large_Graph_Area">        

                                <div style="float:left; width:46%;" id="graph_container">
                                    Loading...
                                </div>

                                <div style="float:left; width:50%; margin-left:4%;">
                                    <div style="font-weight:bold; font-size:18px; margin-left:180px;">ELECTRICITY METERED VS. BILLED</div>                                

                                    <div style="float:left; width:160px;" id="degree_days_data">
                                        Loading...
                                    </div>

                                    <div style="float:left; margin-left:20px; width:330px;" id="electricity_meter_vs_billed">
                                        Loading...
                                    </div>

                                    <div class="clear"></div>

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
<!--        <script src="<?php echo URL ?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo URL ?>highcharts/js/modules/exporting.js"></script>  -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    </body>
</html>
