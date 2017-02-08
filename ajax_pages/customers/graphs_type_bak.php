<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$type=Globals::Get('type');
$building_id=Globals::Get('building_id');
?>

<?php 
if($type==1)
{
	if($building_id=='') $building_id=0;
	$strSQL="Select * from t_room where building_id=$building_id";
	$strRsRoomsArr=$DB->Returns($strSQL);
?>

<div style="margin-bottom:5px;">Select Room:&nbsp; 
<?php if(mysql_num_rows($strRsRoomsArr)>0){?>
<select name="ddlBuildingRoomList" id="ddlBuildingRoomList" style="width:200px; height:25px; font-size:12px; font-family: UsEnergyEngineers;">
	  <?php
      	while($strRsRooms=mysql_fetch_object($strRsRoomsArr))
		{
			print '<option value="'.$strRsRooms->room_id.'">'.$strRsRooms->room_name.'</option>';
		}
	  ?>                                    
</select>
<?php }else{?>
	No Room
<?php }?>
</div>

<div style="float:left; width:25%; color:#666666;">
    <div style="border:1px solid #CCCCCC; width:100%; height:100px;">
        <div style="text-decoration:underline; font-size:12px; text-align:center; margin:3px 0px;">CURRENT TEMPERATURE</div>
        <div style="float:left; width:62%; margin-left:3%;">Building T<sup>&deg;</sup></div>
        <div style="float:right; width:30%; margin-right:5%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
        <div style="float:left; width:62%; margin-left:3%;">Room T<sup>&deg;</sup></div>
        <div style="float:right; width:30%; margin-right:5%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
        <div style="float:left; width:62%; margin-left:3%;">Outside T<sup>&deg;</sup></div>
        <div style="float:right; width:30%; margin-right:5%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
    </div>
    <div style="border:1px solid #CCCCCC; width:100%; height:100px; margin-top:10px;">
        <div style="text-decoration:underline; font-size:12px; text-align:center; margin:3px 0px;">CURRENT HUMIDITY</div>
        
        <div style="float:left; width:62%; margin-left:3%;">Building RH%</div>
        <div style="float:right; width:30%; margin-right:5%; font-weight:bold;">26%</div>
        <div class="clear"></div>
        
        <div style="float:left; width:62%; margin-left:3%;">Room RH%</div>
        <div style="float:right; width:30%; margin-right:5%; font-weight:bold;">26%</div>
        <div class="clear"></div>
        
        <div style="float:left; width:62%; margin-left:3%;">Outside RH%</div>
        <div style="float:right; width:30%; margin-right:5%; font-weight:bold;">26%</div>
        <div class="clear"></div>
        
    </div>
</div>

<div style="float:left; width:36%; margin:0px 1%; color:#666666;">
    <div style="border:1px solid #CCCCCC; width:100%; height:130px; background-image:url(../images/oa_conditions_icon.png); background-repeat:no-repeat; background-position: 3px 45px;">
        <div style="text-decoration:underline; font-size:12px; text-align:center; margin:3px 0px;">OA CONDITIONS</div>
        
        
        <div style="float:left; width:45%; margin-left:28%;">Outside T<sup>&deg;</sup></div>
        <div style="float:right; width:20%; margin-right:3%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
        <div style="float:left; width:45%; margin-left:28%;">Outside RH%</div>
        <div style="float:right; width:20%; margin-right:3%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
        <div style="float:left; width:45%; margin-left:28%;">Day High T<sup>&deg;</sup></div>
        <div style="float:right; width:20%; margin-right:3%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
        <div style="float:left; width:45%; margin-left:28%;">Day High RH%</div>
        <div style="float:right; width:20%; margin-right:3%; font-weight:bold;">53.8<sup>&deg;</sup>F</div>
        <div class="clear"></div>
        
        
    </div>
    <div style="border:1px solid #CCCCCC; width:100%; height:75px; margin-top:5px;">
        <div style="text-decoration:underline; font-size:12px; text-align:center;">NODE ALARMS</div>
        <div style="padding:0px 5px;">
            <div style="float:left;">Alarm 1: THNH15776</div> 
            <div class="clear"></div>
            <div style="float:left;">Detail: 55.1&deg;F</div>  
            <div class="clear"></div>                                  
            <div style="float:left; font-size:12px; font-style:italic; margin-top:-3px;">Jan 31 2015 2:33pm</div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div style="float:left; width:37%;">
    <div style="border:1px solid #CCCCCC; width:100%; height:210px; overflow-y:scroll;" class="myscroll">
    	
        <div style="text-decoration:underline; font-size:12px; text-align:center;">BUILDING NODES</div>
        <div style="text-transform:uppercase; font-size:12px; padding:0px 2px;">
            
            <?php
				$strRsRoomsArr=$DB->Returns($strSQL);
				
			
					
				$strSQL="Select * from t_system_node where delete_flag=0 and node_serial like 'THN1%' And room_id=0 and building_id=$building_id";
				$strRsBuildingNodesArr=$DB->Returns($strSQL);
				
				if(mysql_num_rows($strRsBuildingNodesArr)>0)
				{
			?>
                <div style="background-color:#CCCCCC;">
                    <div style="float:left; width:45%; font-weight:bold;" title="">Node</div>
                    <div style="float:left; width:25%;">&deg;F</div>
                    <div style="float:left; width:17%;">%</div>
                    <div style="float:left; width:10%;">&nbsp;</div>
                    <div class="clear"></div>
                </div>
            	<?php }?>
            <div>
            	<?php while($strRsBuildingNodes=mysql_fetch_object($strRsBuildingNodesArr)){?>
                <div style="float:left; width:45%;"><?php echo $strRsBuildingNodes->node_serial; ?></div>
                <div style="float:left; width:25%;">55.1&deg;F</div>
                <div style="float:left; width:17%;">22%</div>
                <div style="float:left; width:10%; font-weight:bold;" class="green_font">OK</div>
                <div class="clear"></div>
                <?php }?>
                                                       
            </div>
                    
        </div>
        
        <div style="text-decoration:underline; font-size:12px; text-align:center;">ROOMS & NODES</div>
        <div style="text-transform:uppercase; font-size:12px; padding:0px 2px;">
            
            <?php 
			
			while($strRsRooms=mysql_fetch_object($strRsRoomsArr))
			{
				$strSQL="Select * from t_system_node where delete_flag=0 and node_serial like 'THN1%' And room_id=".$strRsRooms->room_id;
				$strRsRoomNodesArr=$DB->Returns($strSQL);
				
				if(mysql_num_rows($strRsRoomNodesArr)>0)
				{
			?>
                <div style="background-color:#CCCCCC;">
                    <div style="float:left; width:45%; font-weight:bold;" title="<?php echo $strRsRooms->room_name;?>"><?php echo Globals::PrintDescription_1($strRsRooms->room_name,10); ?></div>
                    <div style="float:left; width:25%;">&deg;F</div>
                    <div style="float:left; width:17%;">%</div>
                    <div style="float:left; width:10%;">&nbsp;</div>
                    <div class="clear"></div>
                </div>
            	<?php }?>
            <div>
            	<?php while($strRsRoomNodes=mysql_fetch_object($strRsRoomNodesArr)){?>
                <div style="float:left; width:45%;"><?php echo $strRsRoomNodes->node_serial; ?></div>
                <div style="float:left; width:25%;">55.1&deg;F</div>
                <div style="float:left; width:17%;">22%</div>
                <div style="float:left; width:10%; font-weight:bold;" class="green_font">OK</div>
                <div class="clear"></div>
                <?php }?>
                                                       
            </div>
            <?php }?>
           
            
            
            
        </div>
    </div>
    
</div>

<div class="clear"></div>
<?php }elseif($type==2){?>
	
    <?php 
	
	
	global $level1Arr;
	global $level2Arr;
	global $level3Arr;
	global $level4Arr;
	
	if(Globals::Get('graphtype')=='')
	{
		$strType=1;
	}
	else
	{
		$strType=Globals::Get('graphtype');
	}
	
	function getParent($strChild)
	{	
		global $level1Arr;
		global $level2Arr;
		global $level3Arr;
		global $level4Arr;
		
		$DB=new DB;
		$strSQL="Select parent_id, system_name, system_id, level from t_system where system_id=$strChild ";
		$strRsGetParentIDArr=$DB->Returns($strSQL);
		while($strRsGetParentID=mysql_fetch_object($strRsGetParentIDArr))
		{		
			if($strRsGetParentID->level==1)
			{
				if(is_array($level1Arr) && count($level1Arr)>0)
				{
					if(! in_array($strRsGetParentID->system_id, $level1Arr))
					{
						$level1Arr[]=$strRsGetParentID->system_id;
					}				
				}
				else
				{
					$level1Arr[]=$strRsGetParentID->system_id;
				}
			}
			elseif($strRsGetParentID->level==2)
			{
				 //$level2Arr[]=$strRsGetParentID->system_id;
				 
				if(is_array($level2Arr) && count($level2Arr)>0)
				{
					if(! in_array($strRsGetParentID->system_id, $level2Arr))
					{
						$level2Arr[]=$strRsGetParentID->system_id;
					}				
				}
				else
				{
					$level2Arr[]=$strRsGetParentID->system_id;
				}
				 
			}
			elseif($strRsGetParentID->level==3)
			{
				// $level3Arr[]=$strRsGetParentID->system_id;
				if(is_array($level3Arr) && count($level3Arr)>0)
				{
					if(! in_array($strRsGetParentID->system_id, $level3Arr))
					{
						$level3Arr[]=$strRsGetParentID->system_id;
					}				
				}
				else
				{
					$level3Arr[]=$strRsGetParentID->system_id;
				}
			}
			elseif($strRsGetParentID->level==4)
			{
				 //$level4Arr[]=$strRsGetParentID->system_id;
				if(is_array($level4Arr) && count($level4Arr)>0)
				{
					if(! in_array($strRsGetParentID->system_id, $level4Arr))
					{
						$level4Arr[]=$strRsGetParentID->system_id;
					}				
				}
				else
				{
					$level4Arr[]=$strRsGetParentID->system_id;
				}
			}
			
			getParent($strRsGetParentID->parent_id);
		}
	}
	
	
	$strSQL="select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
	$strRsSystemsArr=$DB->Returns($strSQL);
	while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
	{
		getParent($strRsSystems->system_id);
	}
	
	
?>
    
    <div style="  text-align: center;  font-size: 14px;  text-transform: uppercase;  font-weight: bold;"><?php echo ($strType==1 ? "Electric" : "Natural Gas" ); ?> Consumption by System</div>
    
    <script type="text/javascript">
      //google.load("visualization", "1", {packages:["corechart"]});
	  google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['System', 'Consumption'],
		  
		  
		  <?php
		  
		  	
	if(is_array($level1Arr) && count($level1Arr)>0)
	{
		foreach($level1Arr as $val1)
		{		
			$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType";
			$strRsLevel1Arr=$DB->Returns($strSQL);
			while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
			{	
				
				if(is_array($level2Arr) && count($level2Arr)>0)
				{
					foreach($level2Arr as $val2)
					{		
						$strSQL="Select * from t_system where system_id=$val2 and parent_id=".$strRsLevel1->system_id;
						$strRsLevel2Arr=$DB->Returns($strSQL);
						while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
						{							
							if(is_array($level3Arr) && count($level3Arr)>0)
							{
								foreach($level3Arr as $val3)
								{		
									$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
									$strRsLevel3Arr=$DB->Returns($strSQL);
									while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
									{
									?>
										['<?php echo $strRsLevel3->system_name." (".$strRsLevel2->system_name.") - 100,000 ". ($strType==1 ? "kWh" : "Therms" ) ?>',     670.56],
									<?php
										
									}
									
								}
							}
							
						}
						
					}
				}
	
				
			}
			
		}
	}
		  
		  ?>
		  
		  
      
          
        
        ]);
		
		
		
		
		
		 /*var dataTable = new google.visualization.DataTable();
		  dataTable.addColumn('string', 'System');
		  dataTable.addColumn('number', 'Consumption');
		 
		  dataTable.addColumn({type: 'string', role: 'tooltip'});
		  dataTable.addRows([
			['Compressed Air', 70,'70,000 kWh'],
			['HVAC', 140, '140, 000 kWh'],
			['process', 800, '$800K in 2012.'],
			
		  ]);*/
		
		
		
		
        var options = {
          title: '',
          pieHole: 0.4,
		  width: 540,
		  height: 240,
		  colors: ['#00004d','#000066','#000080','#000099','#0000b3','#000066','#191975','#323284','#4c4c93','#6666a3','#7f7fb2','#9999c1','#b2b2d1','#cccce0','#e5e5ef'],
		  chartArea: {width: '100%', left:30, top:30},		 
		  tooltip: { text: 'both' },
		  vAxis: {maxValue: 10},
		  legend:{position:'left', width:'100%'},
		 
		 
          
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
		//chart.draw(dataTable, options);
      }
    </script>

    <div id="donutchart"></div>





<?php }elseif($type==3){?>
<div style="float:left;">
	<img src="<?php echo URL?>/images/graph_placeholder_3.png" />
</div>
<div style="float:left; margin-left:20px;">
	
    <div style="padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:200px; overflow-y: scroll;" class="myscroll">
                            
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>    
        
    </div>
	
    <div class="clear"></div>   
                              
    <div class="clear" style="margin-top:5px;"></div>
    <div style="float:left; text-align:center; margin-top:3px; font-weight:bold;">Total</div>
    <div style="float:left; min-width: 106px; margin-left:30px;" class="normal_blue_box_for_value">11,181,865 kWh</div>
    <div style="float:left; margin-left:7px; font-weight:bold;">$3,21,45</div>
    <div class="clear"></div>
    
</div>
<div class="clear"></div>

<?php }elseif($type==4){?>
<div style="float:left;">
	<img src="<?php echo URL?>/images/graph_placeholder_3.png" />
</div>
<div style="float:left; margin-left:20px;">
	
    <div style="padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:200px; overflow-y: scroll;" class="myscroll">
                            
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>
        <div style="float:left; width:80px;"><span style="font-weight:bold;">+</span>HVAC:</div>
        <div class="light_blue_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">60,015 kWh</div>
        <div style="float:right; margin-right:20px; margin-left:20px;">$4,596</div>
        <div class="clear" style="margin-bottom:2px;"></div>    
        
    </div>
	
    <div class="clear"></div>   
                              
    <div class="clear" style="margin-top:5px;"></div>
    <div style="float:left; text-align:center; margin-top:3px; font-weight:bold;">Total</div>
    <div style="float:left; min-width: 106px; margin-left:30px;" class="normal_blue_box_for_value">11,181,865 kWh</div>
    <div style="float:left; margin-left:7px; font-weight:bold;">$3,21,45</div>
    <div class="clear"></div>
    
</div>
<div class="clear"></div>
<?php }?>



