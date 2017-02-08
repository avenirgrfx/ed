<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$type=Globals::Get('type');


if(Globals::Get('building_id')<>"")
{
	$building_id=Globals::Get('building_id');
    $strSQL="Select time_zone, daylight_saving from t_building where building_id=$building_id ";
    $strTime_zoneArr=$DB->Returns($strSQL);
    while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
        $daylight_saving = $strTime_zone->daylight_saving;
        $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
        $timezoneOffset = Globals::GetTimezoneOffset($strTime_zone->time_zone, $daylight_saving);
    }
    date_default_timezone_set($time_zone);
    $timezoneOffsetMilliSec = $timezoneOffset*60*60*1000;
    
    //$start_date = gmdate('Y-m-1 00:00:00', strtotime("-2 Months"));
    $start_date = gmdate('Y-m-d 00:00:00', strtotime("-2 weeks"));
    $end_date = gmdate('Y-m-d H:i:s', strtotime(date("Y-m-d 23:59:59")));
    //$start_date = gmdate('Y-m-20 00:00:00');
    //$end_date = gmdate('Y-m-d H:i:s');
}
else
{
	exit();
}

?>

<?php if($type==1){?>

<?php	
	$strType=1;
	
	if(Globals::Get('strType')<>'')
	{
		$strType=Globals::Get('strType');
	}
	
	$strSQL="Select * from t_building where building_id=$building_id";
	$strRsBuildingNameArr=$DB->Returns($strSQL);
	if($strRsBuildingName=mysql_fetch_object($strRsBuildingNameArr))
	{
		$building_name=$strRsBuildingName->building_name;
	}
	
	$DeviceListArr=array();
	
	$strSQL="select custom_name, system_node_id from t_system_node where delete_flag=0 and building_id=$building_id and node_serial like 'THN%'";
	$strRsSystemsArr=$DB->Returns($strSQL);
	
	$loop=300;
	
	
	if($strType==1)
	{
		# For Temperature
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$strTimeInterval=(time())*1000;
			$strTimeInterval=$strTimeInterval- ($loop*1000*60);
			# Generating Placeholder Data to Draw the chart
			$arrTempD='[';
//			for($i=1; $i<=$loop; $i++)
//			{
//				$arrTempD.="[".( $strTimeInterval ).",".rand(30,40)."],";
//				$strTimeInterval=$strTimeInterval+(1000*60);
//			}
			$arrTempD.=']';
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
			
		}
	}
	elseif($strType==2)
	{
		# For Humidity
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$strTimeInterval=(time())*1000;
			$strTimeInterval=$strTimeInterval- ($loop*1000*60);
			# Generating Placeholder Data to Draw the chart
			$arrTempD='[';
//			for($i=1; $i<=$loop; $i++)
//			{
//				$arrTempD.="[".( $strTimeInterval ).",".rand(20,60)."],";
//				$strTimeInterval=$strTimeInterval+(1000*60);
//			}
			$arrTempD.=']';
			
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
		}
	}
	
		
?>


<script type="text/javascript">

$(document).ready(function(){
	
	var seriesOptions = [],
	yAxisOptions = [],
	seriesCounter = 0,				
	colors = Highcharts.getOptions().colors;						
	
	<?php foreach($DeviceListArr as $key=>$DeviceList){?>			
		seriesOptions[<?php echo $key;?>] = {
			name: "<?php echo $DeviceList[2];?>",
			data: <?php echo $DeviceList[1];?>
		};
	<?php }?>					
	
	<?php if($strType==1){?>
		createTemperatureChart();
	<?php }else{?>
		createHumidityChart();
	<?php }?>
	
	function createTemperatureChart()
	{	
		$('#temperature_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 50,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Temperature (°F)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}	
	
	function createHumidityChart()
	{	
		$('#temperature_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 50,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			offset: 30,
			title: {
			text: 'Humidity (%)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}
	
});

</script>

<div style="font-weight: bold; font-size: 16px; margin-left: 10px; margin-bottom: 10px;">REAL-TIME <?php if($strType==1){?>TEMPERATURE<?php }else{?>HUMIDITY<?php }?> TREND FOR <u><?php echo strtoupper($building_name);?></u></div>

<div id="temperature_chart_ajax" style="width:1000px; height:450px; float:left; border:1px solid #CCCCCC; margin-left:10px;"></div>
<div class="clear"></div>

    
    
<?php }elseif($type==2){?>
<?php	
	$strType=1;
	
	if(Globals::Get('strType')<>'')
	{
		$strType=Globals::Get('strType');
	}
	
	$strSQL="Select * from t_building where building_id=$building_id";
	$strRsBuildingNameArr=$DB->Returns($strSQL);
	if($strRsBuildingName=mysql_fetch_object($strRsBuildingNameArr))
	{
		$building_name=$strRsBuildingName->building_name;
	}
	
	if(Globals::Get('node_id')<>'')
	{
		$node_id=Globals::Get('node_id');
	}
	
    if($node_id != ''){
        $strSQL="Select system_id from t_system where parent_id = $node_id";
    }else{
        $strSQL="Select system_id from t_system where parent_id in(Select system_id from t_system where parent_id in(Select system_id from t_system where parent_id in (Select system_id from t_system where display_type=$strType)))";
    }
	
    $strRsGetSystemsArr=$DB->Returns($strSQL);
	$strSystemIDs='';
	while($strRsGetSystems=mysql_fetch_object($strRsGetSystemsArr))
	{
		$strSystemIDs.=$strRsGetSystems->system_id.",";
		
	}
	$strSystemIDs=$strSystemIDs."0";
	
	
	$DeviceListArr=array();
	
	$strSQL="select custom_name, system_node_id, available_system_node_serial from t_system_node where delete_flag=0 and building_id=$building_id and system_id in($strSystemIDs)";
	$strRsSystemsArr=$DB->Returns($strSQL);
	
	
    if($strType==1)
	{
		# For Temperature
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$arrTempD='[';
            
            if($strRsSystems->available_system_node_serial<>'')
            {
                $strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date') as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date') as B on ((A.`device_status_id`)+1=B.`device_status_id`) and A.synctime < B.synctime)";
                $consumptionArr=$DB->Returns($strSQL);
                while($consumption=mysql_fetch_object($consumptionArr))
                {
                    $arrTempD.="[".( intval($consumption->synctime)*1000+$timezoneOffsetMilliSec ).",". floatval($consumption->kwh) ."],";
                }
            }
            
			$arrTempD.=']';
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
			
		}
	}
	elseif($strType==2)
	{
		# For Humidity
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$arrTempD='[';
            
            if($strRsSystems->available_system_node_serial<>'')
            {
                $strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date') as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date') as B on ((A.`device_status_id`)+1=B.`device_status_id`) and A.synctime < B.synctime)";
                $consumptionArr=$DB->Returns($strSQL);
                while($consumption=mysql_fetch_object($consumptionArr))
                {
                    $arrTempD.="[".( intval($consumption->synctime)*1000+$timezoneOffsetMilliSec ).",". floatval($consumption->kwh)/50 ."],";
                }
            }
            
			$arrTempD.=']';
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
		}
	}
	
		
?>


<script type="text/javascript">

$(document).ready(function(){
	
	var seriesOptions = [],
	yAxisOptions = [],
	seriesCounter = 0,				
	colors = Highcharts.getOptions().colors;						
	
	<?php foreach($DeviceListArr as $key=>$DeviceList){?>			
		seriesOptions[<?php echo $key;?>] = {
			name: "<?php echo $DeviceList[2];?>",
			data: <?php echo $DeviceList[1];?>
		};
	<?php }?>					
	
	<?php if($strType==1){?>
		createElectricConsumptionChart();
	<?php }else{?>
		createNaturalGasConsumptionChart();
	<?php }?>
	
	function createElectricConsumptionChart()
	{	
		$('#consumption_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 100,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Electric (kWh)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}	
	
	function createNaturalGasConsumptionChart()
	{	
		$('#consumption_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 50,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Natural Gas (Therms)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}
	
});

</script>

<div style="font-weight: bold; font-size: 16px; margin-left: 10px; margin-bottom: 10px;">REAL-TIME <?php if($strType==1){?>ELECTRIC<?php }else{?>NATURAL GAS<?php }?> CONSUMPTION FOR <u><?php echo strtoupper($building_name);?></u></div>

<div id="consumption_chart_ajax" style="width:1000px; height:450px; float:left; border:1px solid #CCCCCC; margin-left:10px;"></div>
<div class="clear"></div>
    
    
<?php }elseif($type==3){?>
<?php	
	$strType=1;
	
	if(Globals::Get('strType')<>'')
	{
		$strType=Globals::Get('strType');
	}
	
	$strSQL="Select * from t_building where building_id=$building_id";
	$strRsBuildingNameArr=$DB->Returns($strSQL);
	if($strRsBuildingName=mysql_fetch_object($strRsBuildingNameArr))
	{
		$building_name=$strRsBuildingName->building_name;
	}
	
    $strSQL="Select * from t_energy_cost where building_id=".$building_id;
    $costArr=$DB->Returns($strSQL);
    $coststr=mysql_fetch_object($costArr);

    if($strType==1)
    {
        $cost = $coststr->energy_cost?$coststr->energy_cost:0;
    }
    else
    {
        $cost = $coststr->gas_cost?$coststr->gas_cost:0;
    }
    
	if(Globals::Get('node_id')<>'')
	{
		$node_id=Globals::Get('node_id');
	}
	
    if($node_id != ''){
        $strSQL="Select system_id from t_system where parent_id = $node_id";
    }else{
        $strSQL="Select system_id from t_system where parent_id in(Select system_id from t_system where parent_id in(Select system_id from t_system where parent_id in (Select system_id from t_system where display_type=$strType)))";
    }
	
	$strRsGetSystemsArr=$DB->Returns($strSQL);
	$strSystemIDs='';
	while($strRsGetSystems=mysql_fetch_object($strRsGetSystemsArr))
	{
		$strSystemIDs.=$strRsGetSystems->system_id.",";
		
	}
	$strSystemIDs=$strSystemIDs."0";
	
	
	$DeviceListArr=array();
	
	$strSQL="select custom_name, system_node_id, available_system_node_serial from t_system_node where delete_flag=0 and building_id=$building_id and system_id in($strSystemIDs)";
	$strRsSystemsArr=$DB->Returns($strSQL);
	
	if($strType==1)
	{
		# For Temperature
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$arrTempD='[';
            
            if($strRsSystems->available_system_node_serial<>'')
            {
                $strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date') as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date') as B on ((A.`device_status_id`)+1=B.`device_status_id`) and A.synctime < B.synctime)";
                $consumptionArr=$DB->Returns($strSQL);
                while($consumption=mysql_fetch_object($consumptionArr))
                {
                    $arrTempD.="[".( intval($consumption->synctime)*1000+$timezoneOffsetMilliSec ).",". floatval($consumption->kwh)*$cost ."],";
                }
            }
            
			$arrTempD.=']';
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
			
		}
	}
	elseif($strType==2)
	{
		# For Humidity
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$arrTempD='[';
            
            if($strRsSystems->available_system_node_serial<>'')
            {
                $strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date') as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystems->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date') as B on ((A.`device_status_id`)+1=B.`device_status_id`) and A.synctime < B.synctime)";
                $consumptionArr=$DB->Returns($strSQL);
                while($consumption=mysql_fetch_object($consumptionArr))
                {
                    $arrTempD.="[".( intval($consumption->synctime)*1000+$timezoneOffsetMilliSec ).",". floatval($consumption->kwh)*$cost/50 ."],";
                }
            }
            
			$arrTempD.=']';
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
		}
	}
	
		
?>


<script type="text/javascript">

$(document).ready(function(){
	
	var seriesOptions = [],
	yAxisOptions = [],
	seriesCounter = 0,				
	colors = Highcharts.getOptions().colors;						
	
	<?php foreach($DeviceListArr as $key=>$DeviceList){?>			
		seriesOptions[<?php echo $key;?>] = {
			name: "<?php echo $DeviceList[2];?>",
			data: <?php echo $DeviceList[1];?>
		};
	<?php }?>					
	
	<?php if($strType==1){?>
		createElectricCostChart();
	<?php }else{?>
		createNaturalGasCostChart();
	<?php }?>
	
	function createElectricCostChart()
	{	
		$('#cost_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 100,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Cost ($)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}	
	
	function createNaturalGasCostChart()
	{	
		$('#cost_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 50,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Cost($)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}
	
});

</script>

<div style="font-weight: bold; font-size: 16px; margin-left: 10px; margin-bottom: 10px;">REAL-TIME <?php if($strType==1){?>ELECTRIC<?php }else{?>NATURAL GAS<?php }?> COST FOR <u><?php echo strtoupper($building_name);?></u></div>

<div id="cost_chart_ajax" style="width:1000px; height:450px; float:left; border:1px solid #CCCCCC; margin-left:10px;"></div>
<div class="clear"></div>
    
<?php }elseif($type==4){?>
	<?php	
	$strType=1;
	
	if(Globals::Get('strType')<>'')
	{
		$strType=Globals::Get('strType');
	}
	
	$strSQL="Select * from t_building where building_id=$building_id";
	$strRsBuildingNameArr=$DB->Returns($strSQL);
	if($strRsBuildingName=mysql_fetch_object($strRsBuildingNameArr))
	{
		$building_name=$strRsBuildingName->building_name;
	}
	
	if(Globals::Get('node_id')<>'')
	{
		$node_id=Globals::Get('node_id');
	}
	
    if($node_id != ''){
        $strSQL="Select system_id from t_system where parent_id = $node_id";
    }else{
        $strSQL="Select system_id from t_system where parent_id in(Select system_id from t_system where parent_id in(Select system_id from t_system where parent_id in (Select system_id from t_system where display_type=$strType)))";
    }
	
	$strRsGetSystemsArr=$DB->Returns($strSQL);
	$strSystemIDs='';
	while($strRsGetSystems=mysql_fetch_object($strRsGetSystemsArr))
	{
		$strSystemIDs.=$strRsGetSystems->system_id.",";
		
	}
	$strSystemIDs=$strSystemIDs."0";
	
	
	$DeviceListArr=array();
	
	$strSQL="select custom_name, system_node_id from t_system_node where delete_flag=0 and building_id=$building_id and system_id in($strSystemIDs)";
	$strRsSystemsArr=$DB->Returns($strSQL);
	
	$loop=300;
	
	
	if($strType==1)
	{
		# For Temperature
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$strTimeInterval=(time())*1000;
			$strTimeInterval=$strTimeInterval- ($loop*1000*60);
			# Generating Placeholder Data to Draw the chart
			$arrTempD='[';
//			for($i=1; $i<=$loop; $i++)
//			{
//				$arrTempD.="[".( $strTimeInterval ).",".rand(1,100)."],";
//				$strTimeInterval=$strTimeInterval+(1000*60);
//			}
			$arrTempD.=']';
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
			
		}
	}
	elseif($strType==2)
	{
		# For Humidity
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			$strTimeInterval=(time())*1000;
			$strTimeInterval=$strTimeInterval- ($loop*1000*60);
			# Generating Placeholder Data to Draw the chart
			$arrTempD='[';
//			for($i=1; $i<=$loop; $i++)
//			{
//				$arrTempD.="[".( $strTimeInterval ).",".rand(1,100)."],";
//				$strTimeInterval=$strTimeInterval+(1000*60);
//			}
			$arrTempD.=']';
			
			
			$DeviceListArr[]=array(1101,$arrTempD,$strRsSystems->custom_name);
		}
	}
	
		
?>


<script type="text/javascript">

$(document).ready(function(){
	
	var seriesOptions = [],
	yAxisOptions = [],
	seriesCounter = 0,				
	colors = Highcharts.getOptions().colors;						
	
	<?php foreach($DeviceListArr as $key=>$DeviceList){?>			
		seriesOptions[<?php echo $key;?>] = {
			name: "<?php echo $DeviceList[2];?>",
			data: <?php echo $DeviceList[1];?>
		};
	<?php }?>					
	
	<?php if($strType==1){?>
		createElectricSavingChart();
	<?php }else{?>
		createNaturalGasSavingChart();
	<?php }?>
	
	function createElectricSavingChart()
	{	
		$('#saving_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 100,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Savings ($)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}	
	
	function createNaturalGasSavingChart()
	{	
		$('#saving_chart_ajax').highcharts('StockChart', {		
		chart: {   
   			events: {
     			load: function() {
        			/*this.renderer.image('<?php echo URL;?>images/graph_bg.png', -10, 30, 490, 370).add();*/
   					}
 				}   
			},
		
		legend: {
		enabled: true,
		align: 'right',
		backgroundColor: '#FCFFC5',
		borderColor: 'black',
		borderWidth: 1,
		layout: 'vertical',
		verticalAlign: 'top',
		y: 50,
		shadow: true
		},
				
		rangeSelector : {
            buttons : [{
                type : 'hour',
                count : 1,
                text : '1h'
            }, {
                type : 'day',
                count : 1,
                text : '1D'
            }, {
                type : 'week',
                count : 1,
                text : '1W'    
            }, {
                type : 'week',
                count : 2,
                text : '2W'
            }],
            selected : 1,
            inputEnabled : false
        },
	
		yAxis: {
			labels: {
				formatter: function() {
					return this.value;
				}					
			},
			
			offset: 30,
			
			title: {
			text: 'Savings ($)'
			}			
		},		
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			valueDecimals: 2
		},		
		series: seriesOptions
	});	
	}
	
});

</script>

<div style="font-weight: bold; font-size: 16px; margin-left: 10px; margin-bottom: 10px;">REAL-TIME <?php if($strType==1){?>ELECTRIC<?php }else{?>NATURAL GAS<?php }?> SAVINGS FOR <u><?php echo strtoupper($building_name);?></u></div>

<div id="saving_chart_ajax" style="width:1000px; height:450px; float:left; border:1px solid #CCCCCC; margin-left:10px;"></div>
<div class="clear"></div>
<?php }?>