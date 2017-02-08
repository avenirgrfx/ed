<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');

$DB = new DB;
$type = Globals::Get('type');
//$building_id = Globals::Get('building_id');

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$_GET['building_id'];
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);
?>

<script type="text/javascript">
    $(function () {
        var txt_GHG_FromDate = $("#txt_GHG_FromDate").datepicker({
            maxDate: "<?=date('m/d/Y')?>",
        });
        var txt_GHG_ToDate = $("#txt_GHG_ToDate").datepicker({
            maxDate: "<?=date('m/d/Y')?>",
            onSelect: function( selectedDate ) {
                txt_GHG_FromDate.datepicker( "option", "maxDate", selectedDate);
            }
        });
        
        var d = new Date("<?=date('m/d/Y')?>");
        var e = new Date("<?=date('m/d/Y')?>");
        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined;
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        if(month && month != d.getMonth()+1){
            d.setMonth(month-1);
            e.setMonth(month-1);
            e = new Date(e.getFullYear(), e.getMonth()+1, 0);
        }
        
        d.setDate(1);
        txt_GHG_FromDate.datepicker("setDate", d);
        txt_GHG_ToDate.datepicker("setDate", e);
        
        var txt_Energy_FromDate = $("#txt_Energy_FromDate").datepicker({
            maxDate: new Date("<?=date('m/d/Y')?>")
        });
        var txt_Energy_ToDate = $("#txt_Energy_ToDate").datepicker({
            maxDate: new Date("<?=date('m/d/Y')?>")
        });
        
        txt_Energy_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
        txt_Energy_ToDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
        
        if (<?php echo $type ?> == 1){
            $('#green_house_gas_summary').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/green_house_gas_summary.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                        year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                    },
            function (data, status) {
                $('#green_house_gas_summary').html(data);
            });
            
            $('#site_energy_metrics_by_fuel').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/site_energy_metrics_by_fuel.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                        year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                    },
            function (data, status) {
                $('#site_energy_metrics_by_fuel').html(data);
                setSiteEnergyConsumption();
            });
           //setSiteEnergyConsumption();
           
        } else if (<?php echo $type ?> == 2) {
            
            $('#green_house_gas_summary').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/green_house_gas_summary.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                        year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                    },
            function (data, status) {
                $('#green_house_gas_summary').html(data);
            });
            showGHG();
            
        } else if (<?php echo $type ?> == 3) {
            $('#site_energy_metrics_by_fuel').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/site_energy_metrics_by_fuel.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                        year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                    },
            function (data, status) {
                $('#site_energy_metrics_by_fuel').html(data);
            });
            
            $('#Electric_System_Container').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_metrics.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        type: 1,
                        month: $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined,
                        year: $("#ui-datepicker-div .ui-datepicker-year :selected").val()
                    },
            function (data, status) {
                $('#Electric_System_Container').html(data);
            });

        } else if (<?php echo $type ?> == 4) {
            
            getEnergyInfo();
            setSiteEnergyConsumption();
            
        }
        
        $('#ddlEnergy_Electric_NaturalGas').change(function () {

            var type = this.value;

            $('#Energy_Electric_Calculation').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_energy.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        type: type,
                        first_date: $('#txt_Energy_FromDate').val(),
                        second_date: $('#txt_Energy_ToDate').val()
                    },
                    function (data, status) {
                        $('#Energy_Electric_Calculation').html(data);
                        if (type == 1)
                        {
                            $('#Energy_Electric_NaturalGas').html('Electrical System');
                            $('#Energy_Total_Electric_NaturalGas').html('Total Electric');
                        }
                        else
                        {
                            $('#Energy_Electric_NaturalGas').html('Natural Gas System');
                            $('#Energy_Total_Electric_NaturalGas').html('Total Natural Gas');
                        }
                    });

        });


        $('#ddlMetricsType').change(function () {
            var type = this.value;

            if (type == 1)
            {
                /* Electric Type*/

                $('#Electric_System_Container').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_metrics.php",
                        {
                            building_id: $('#ddlSiteSummaryBuilding').val(),
                            type: 1,
                        },
                function (data, status) {
                    $('#Electric_System_Container').html(data);
                });

                $('#NaturalGas_System_Container').css('display', 'none');
                $('#Electric_System_Container').css('display', 'block');
                $('#Natural_Gas_Calculated_Value').css('display', 'none');
                $('#Natural_Gas_Table_Heading').css('display', 'none');
                $('#Electric_Calculated_Value').css('display', 'block');
                $('#Electric_Table_Heading').css('display', 'block');
            }
            else if (type == 2)
            {
                /* Natural Gas Type*/

                $('#NaturalGas_System_Container').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_metrics.php",
                        {
                            building_id: $('#ddlSiteSummaryBuilding').val(),
                            type: 2,
                        },
                        function (data, status) {
                            $('#NaturalGas_System_Container').html(data);
                        });

                $('#Electric_System_Container').css('display', 'none');
                $('#NaturalGas_System_Container').css('display', 'block');
                $('#Natural_Gas_Calculated_Value').css('display', 'block');
                $('#Natural_Gas_Table_Heading').css('display', 'block');
                $('#Electric_Calculated_Value').css('display', 'none');
                $('#Electric_Table_Heading').css('display', 'none');

            }
        });
        
    
    });
    
    function setSiteEnergyConsumption(){
        if(Month_To_Date_NaturalGas_Consumption != ""){
            $('#Month_To_Date_Electric_Consumption').html(Month_To_Date_Electric_Consumption);
            $('#Last_Month_Electric_Consumption').html(Last_Month_Electric_Consumption);
            $('#Month_To_Date_NaturalGas_Consumption').html(Month_To_Date_NaturalGas_Consumption);
            $('#Last_Month_NaturalGas_Consumption').html(Last_Month_NaturalGas_Consumption);
        }else{
            setTimeout(100, setSiteEnergyConsumption());
        }
    }
    
    function getEnergyInfo(){
        if($('#txt_Energy_FromDate').val() != "" && $('#txt_Energy_ToDate').val() != ""){
            $('#Energy_Electric_Calculation').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/system_list_by_building_energy.php",
                    {
                        building_id: $('#ddlSiteSummaryBuilding').val(),
                        type: $('#ddlEnergy_Electric_NaturalGas').val(),
                        first_date: $('#txt_Energy_FromDate').val(),
                        second_date: $('#txt_Energy_ToDate').val()
                    },
                    function (data, status) {
                        $('#Energy_Electric_Calculation').html(data);
                    });
        }
    }
    
    function showGHG(){
        if($('#txt_GHG_FromDate').val() != "" && $('#txt_GHG_ToDate').val() != ""){
            $.get("<?php echo URL ?>ajax_pages/customers/ghg_details.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        from_date: $('#txt_GHG_FromDate').val(),
                        to_date: $('#txt_GHG_ToDate').val(),
                    },
            function (data, status) {
                $('#ghg_container').html(
                        data
                        );
            });
        }
    }
    
    function findGHG(){
        if($('#txt_GHG_FromDate').val() != "" && $('#txt_GHG_ToDate').val() != ""){
            $.get("<?php echo URL ?>ajax_pages/customers/ghg_details_left.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        from_date: $('#txt_GHG_FromDate').val(),
                        to_date: $('#txt_GHG_ToDate').val(),
                    },
            function (data, status) {
                $('#ghg_given_dateline').html(
                        data
                        );
            });
        }
    }
</script>

<?php if ($type == 1) { ?>
    <div style="font-size:16px;">

        <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">GREENHOUSE GAS SUMMARY (CO2)</div>
        <div id="green_house_gas_summary">
            Loading...
        </div>
    </div>

    <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">

    <div style="font-size:16px;">

        <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">SITE ENERGY METRICS BY FUEL</div>
        <div id="site_energy_metrics_by_fuel">
            Loading...
        </div>    
    </div>
    <div class="clear"></div>
    <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">

    <div style="font-size:15px;">

        <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">SITE ENERGY CONSUMPTION</div>
        <div style="float:left; text-align:right; width:65%;">Month to date ELECTRIC Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value" id="Month_To_Date_Electric_Consumption">0 kWh</div>                                 
        <div class="clear" style="margin-bottom:5px;"></div>
        
        <div style="float:left; text-align:right; width:65%;">Last Month ELECTRIC Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value" id="Last_Month_Electric_Consumption">0 kWh</div>                                 
        <div class="clear" style="margin-bottom:5px;"></div>

        <div style="float:left; text-align:right; width:65%;">Month to date NATURAL GAS Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value" id="Month_To_Date_NaturalGas_Consumption">0 Therms</div>                                 
        <div class="clear" style="margin-bottom:5px;"></div>

        <div style="float:left; text-align:right; width:65%;">Last Month NATURAL GAS Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value" id="Last_Month_NaturalGas_Consumption">0 Therms</div>                                 
        <div class="clear"></div>

    </div>

<?php } elseif ($type == 2) { ?>


    <div style="font-size:16px;">

        <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">GREENHOUSE GAS SUMMARY (CO2)</div>

        <div id="green_house_gas_summary">
            Loading...
        </div>
    </div>

    <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;">

    <div style="color:#666666; font-weight:bold; font-size:16px; margin-bottom:5px;">
        GREENHOUSE GAS EMISSIONS & SAVINGS
    </div>

    <div style="float:left; width:42%; margin:1%;">
        <div style="float:left;">From</div>
        <div style="float:left; margin-left:10px;"><input type="text" name="txt_GHG_FromDate" id="txt_GHG_FromDate" placeholder="Pick Date" value="" style="width:130px; font-size:12px; height:12px;" /></div>
        <div class="clear"></div>
    </div>

    <div style="float:left; width:42%; margin:1%;">
        <div style="float:left;">To</div>
        <div style="float:left; margin-left:10px;"><input type="text" name="txt_GHG_ToDate" id="txt_GHG_ToDate" placeholder="Pick Date" value="" style="width:130px; font-size:12px; height:12px;" /></div>
        <div class="clear"></div>
    </div>
    <div  style="float:left; width:7%; margin:1%;">
        <input type="button" value="GO" name="btnGo" id="btnGo" onclick="findGHG();">
    </div>
    <div class="clear" style="margin-bottom:5px;"></div>
    
    <div id="ghg_container">
       Loading...
    </div>
    
    <div style="font-size:10px; max-width:450px; text-align:justify;">All calculations are estimates based on the best available information.The greenhouse gas calculations based
        in part on emission factors from the United States Department Of Energy EIA 1605 (b) program. Comparison
        calculations are based in part on data obtained from the United States Environmental Protection Agency. Comparison calculations are based in part on data obtained from the International Energy Agency.</div> 

    <div class="clear"></div>

<?php } elseif ($type == 3) {?>

    <div style="font-size:16px;">

        <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">SITE ENERGY METRICS BY FUEL</div>
        <div id="site_energy_metrics_by_fuel">
            Loading...
        </div>    
    </div>
    <div class="clear"></div>
    <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">

    <select id="ddlMetricsType" name="ddlMetricsType" style="font-weight:bold; float: left;width:220px;">
        <option value="1">ELECTRIC METRICS</option>
        <option value="2">NATURAL GAS METRICS</option>
    </select>
    
    <!--<div style="margin-left: 10px; float:left; width:270px;">Adjusted with a calculated average multiplier</div>-->
    <div class="clear"></div>
    <div id="Electric_Table_Heading">
        <div style="float:left; font-weight:bold; width:250px">Electric System</div>
        <div style="float:left; margin-left: 5px; font-weight:bold;">Month Metrics*</div>
        <div style="float:right; margin-right:35px; font-weight:bold;">Costs</div>
        <div class="clear"></div>                              
    </div>

    <div style="width:98%; margin:1%; height:180px; overflow-y: scroll; border-top:1px solid #CCCCCC; border-bottom:1px solid #CCCCCC;" id="Electric_System_Container" class="myscroll">
        Loading...
    </div>

    <div id="Electric_Calculated_Value">

        <div style="float:left; width:82%;">                                
            <div class="clear" style="margin-top:10px;"></div>
            <div style="float:left; width:240px; margin-top:3px; margin-right:5px; text-align:right; font-weight:bold;">Total Electric</div>
            <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value" id="total_electric_site_detail">0 kWh</div>
            <div class="clear"></div>

            <div class="clear" style="margin-top:3px;"></div>
            <div style="float:left; width:240px; text-align:right; margin-right:5px;  font-size:12px; margin-top:3px;">Electric Disconnect</div>
            <div class="" style="float:left; padding:2px 4px; font-weight:normal; background:none; border:1px solid #DDDDDD; min-width:106px" id="total_electric_main_site_detail">0 kWh</div>

            <div class="clear"></div>
        </div>

        <div style="float:left;">
            <div style="float:right; margin-top: 10px;  min-width: 73px;" class="normal_blue_box_for_value" id="cost_total_electric_site_detail">$0</div>
            <div class="clear"></div>
            <div class="" style="float:left; margin-top:5px; padding:2px 4px; font-weight:normal; background:none; border:1px solid #DDDDDD; min-width:73px" id="cost_total_electric_main_site_detail">$0</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div id="Natural_Gas_Table_Heading" style="display:none;">
        <div style="float:left; font-weight:bold; width:250px">Natural Gas System</div>
        <div style="float:left; margin-left: 5px; font-weight:bold;">Month Metrics*</div>
        <div style="float:right; margin-right:35px; font-weight:bold;">Costs</div>
        <div class="clear"></div>                              
    </div>

    <div style="width:98%; margin:1%; height:180px; overflow-y: scroll; border-top:1px solid #CCCCCC; border-bottom:1px solid #CCCCCC; display:none;" class="myscroll" id="NaturalGas_System_Container">
        <div style="float:left; font-weight:bold;">Natural Gas System</div>
        <div style="float:left; margin-left:75px; font-weight:bold;">Month Metrics*</div>
        <div style="float:right; margin-right:20px; font-weight:bold;">Month Costs</div>
        <div class="clear"></div>
        <div style=" padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:150px; overflow-y: scroll;" id="style-2">
            Loading...
        </div>

        <div style="float:left; width:66%;">                                
            <div class="clear" style="margin-top:10px;"></div>
            <div style="float:left; width:167px; margin-top:3px; font-weight:bold;">Total Electric</div>
            <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value">0 kWh</div>
            <div class="clear"></div>

            <div class="clear" style="margin-top:3px;"></div>
            <div style="float:left; width:167px;  font-size:12px; margin-top:3px;">Utility Disconnect</div>
            <div class="light_blue_box_for_value" style="float:left; min-width:104px; font-weight:normal; background:none; border:1px solid #DDDDDD;" >0 kWh</div>

            <div class="clear"></div>
        </div>

        <div style="float:left; margin-left:3px;" class="right_bracket_bg">
            <div style="margin-top:25px; background-color:#FFFFFF;">0%</div>
        </div>

        <div style="float:left; margin-top: 10px; margin-left: 35px; min-width: 73px; text-align: center;" class="normal_blue_box_for_value">$0</div>


        <div class="clear"></div>

    </div>


    <div class="clear"></div>


    <div id="Natural_Gas_Calculated_Value" style="display:none;">

        <div style="float:left; width:82%;">                                
            <div class="clear" style="margin-top:10px;"></div>
            <div style="float:left; width:240px; margin-top:3px; margin-right:5px; text-align:right; font-weight:bold;">Total Natural Gas</div>
            <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value" id="total_gas_site_detail">0 Therms</div>
            <div class="clear"></div>

            <div class="clear" style="margin-top:3px;"></div>
            <div style="float:left; width:240px; text-align:right; margin-right:5px; font-size:12px; margin-top:3px;">Main's Natural Gas</div>
            <div class="" style="float:left; padding:2px 4px; font-weight:normal; background:none; border:1px solid #DDDDDD; min-width: 106px" id="total_gas_main_site_detail">0 Therms</div>

            <div class="clear"></div>
        </div>

        <div style="float:left;">
            <div style="float:right; margin-top: 10px;  min-width: 73px;" class="normal_blue_box_for_value" id="cost_total_gas_site_detail">$0</div>
            <div class="clear"></div>
            <div class="" style="float:left; margin-top:5px; padding:2px 4px; font-weight:normal; background:none; border:1px solid #DDDDDD; min-width: 73px" id="total_gas_main_site_detail">$0</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div style="margin-left: 15px; margin-top: 5px; float:left;">*Adjusted with a calculated average utility rate multiplier</div>

<?php } elseif ($type == 4) { ?>

    <div style="font-size:15px;">

        <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">SITE ENERGY CONSUMPTION</div>

        <div style="float:left; text-align:right; width:65%;">Month to date ELECTRIC Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value" id="Month_To_Date_Electric_Consumption">0 kWh</div>                                 
        <div class="clear" style="margin-bottom:5px;"></div>


        <div style="float:left; text-align:right; width:65%;">Last Month ELECTRIC Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value" id="Last_Month_Electric_Consumption">0 kWh</div>                                 
        <div class="clear" style="margin-bottom:5px;"></div>

        <div style="float:left; text-align:right; width:65%;">Month to date NATURAL GAS Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value" id="Month_To_Date_NaturalGas_Consumption">0 Therms</div>                                 
        <div class="clear" style="margin-bottom:5px;"></div>

        <div style="float:left; text-align:right; width:65%;">Last Month NATURAL GAS Consumption</div>
        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value" id="Last_Month_NaturalGas_Consumption">0 Therms</div>                                 
        <div class="clear"></div>

    </div>

    <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;">

    <div style="float:left;">
        <select id="ddlEnergy_Electric_NaturalGas" name="ddlEnergy_Electric_NaturalGas" style="font-size:12px; width:190px;">
            <option value="1">ELECTRIC COMPARISON</option>
            <option value="2">NATURAL GAS COMPARISON</option>
        </select>
    </div>

    <div style="float:left; margin-left:5px; margin-top:5px;"><input type="text" name="txt_Energy_FromDate" id="txt_Energy_FromDate" placeholder="Pick Date1" value="" style="width:120px; font-size:12px; height:12px;" onchange="getEnergyInfo();"/></div>
    <div style="float:left; margin-left:5px; margin-top:5px;"><input type="text" name="txt_Energy_ToDate" id="txt_Energy_ToDate" placeholder="Pick Date2" value="" style="width:120px; font-size:12px; height:12px;"  onchange="getEnergyInfo();"/></div>


    <div class="clear"></div>
    
    <div style="float:left; margin-left: 196px; margin-top: 5px; padding-left: 10px; width: 132px;" id="no_of_days1"></div>
    <div style="float:left; margin-left: 7px; margin-top: 5px; width: 110px;" id="no_of_days2"></div>
    <div class="clear"></div>

    <div id="Energy_Pick_Date_1"></div>              
    <div class="clear" style="margin-bottom:5px;"></div>

    <div id="Energy_Pick_Date_2"></div>               
    <div class="clear" style="margin-bottom:5px;"></div>

    <div style="float:left; width:98%; margin:1%;">
        <div style="float:left; font-weight:bold; width:175px;" id="Energy_Electric_NaturalGas">Electric System</div>
        <div style="float:left; font-weight:bold; width:135px;" id="Energy_Pick_Month_1"><?php echo date("F Y"); ?></div>
        <div style="float:left; font-weight:bold; width:135px;" id="Energy_Pick_Month_2"><?php echo date("F Y"); ?></div>
        <div class="clear"></div>
        <div style=" padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:150px; overflow-y: scroll;" class="myscroll" id="Energy_Electric_Calculation">
            Loading...
        </div>

        <div style="float:left; width:100%;">                                
            <div class="clear" style="margin-top:10px;"></div>
            <div style="float:left; width:175px; margin-top:3px; font-weight:bold;" id="Energy_Total_Electric_NaturalGas">Total Electric</div>
            <div style="float:left; min-width: 120px;" class="normal_blue_box_for_value" id="Total_Electric_NaturalGas_Value_1">0 kWh</div>
            <div style="float:left; min-width: 120px; margin-left: 10px" class="normal_blue_box_for_value" id="Total_Electric_NaturalGas_Value_2">0 kWh</div>
            <div class="clear"></div>


            <div class="clear"></div>
        </div>

        <div class="clear"></div>

    </div>

<?php
}?>