<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$year = $_GET['year'];

$strSQL="Select square_feet from t_building where building_id=$building_id";
$strRsBuildingAreaArr=$DB->Returns($strSQL);
if($strRsBuildingArea=mysql_fetch_object($strRsBuildingAreaArr))
{
	$square_feet=$strRsBuildingArea->square_feet;
}

$strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$building_id' AND utility_account_type = 3 AND year = '$year' group by month order by month asc";
$strWaterAccountArr=$DB->Returns($strSQL);

$utitlity_water_consumption_total = 0;
$utitlity_water_cost_total = 0;
?>

<script type="text/javascript">
    $(function () {
        var data_consumption = [];
        var data_cost = [];
        
        <?php while($strWaterAccount=mysql_fetch_object($strWaterAccountArr)) { 
            $utitlity_water_consumption_total += $strWaterAccount->consumption;
            $utitlity_water_cost_total += $strWaterAccount->cost;
        ?>
            data_consumption.push(<?=$strWaterAccount->consumption; ?>);
            data_cost.push(<?=$strWaterAccount->cost;?>);
        <?php } ?>
            
        $('#container_electric_chart').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Water Consumption Profile - ' + $('#ddlMonthlyProfileYear').val()
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
                        text: 'Consumption (GLN)',
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
                    color: '#0088cc',
                    type: 'column',
                    yAxis: 1,
                    data: data_cost,
                    tooltip: {
                        valueSuffix: ''
                    }

                }, {
                    name: 'Consumption (GLN)',
                    color: '#801617',
                    type: 'spline',
                    data: data_consumption,
                    tooltip: {
                        valueSuffix: ''
                    }
                }]
        });
    });
       
</script>

<div style="font-weight:bold; font-size:18px;">WATER</div>

<div style="border:1px solid #999999; width:500px;">
    <div id="container_electric_chart" style="min-width: 380px; height: 300px; margin: 0 auto"></div>
</div>

<div style="font-weight:bold;">
    <div style="float:left; width:40%; color:#801617;">WATER ECI:</div> <div style="float:left; width:50%; color:#801617;">$ <?=number_format($utitlity_water_cost_total/$square_feet,2)?> per sq. ft. /yr</div>
    <div class="clear"></div>
    <div style="float:left; width:40%;">WATER Usage:</div><div style="float:left; width:50%;"> <?=number_format($utitlity_water_consumption_total,0)?> GLN/yr</div>
    <div class="clear"></div>
    <div style="float:left; width:40%; color:#801617;">WATER EUI:</div> <div style="float:left; width:50%; color:#801617;"><?=number_format($utitlity_water_consumption_total/$square_feet,2)?> GLN/sq. ft. /Yr</div>
    <div class="clear"></div>
    <div style="float:left; width:40%;">Average Cost:</div><div style="float:left; width:50%;"> $ <?=number_format($utitlity_water_cost_total/$utitlity_water_consumption_total,2)?> /GLN</div>
    <div class="clear"></div>
</div>