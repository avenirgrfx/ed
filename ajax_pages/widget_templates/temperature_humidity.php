<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

//$WidgetSerailNumber=Globals::ShowWidgetSerialBeforeLinked('thn');
$WidgetSerailNumber=$_GET['strNodeSerial'];

$strSQL="Select t_thn_widget.* from t_thn_widget, t_system_node 
where t_system_node.system_node_id=t_thn_widget.system_node_id 
And t_system_node.node_serial='$WidgetSerailNumber'";

$strRsNodeWidgetDetailsArr=$DB->Returns($strSQL);

$arrWidgets=array();

while($strRsNodeWidgetDetails=mysql_fetch_object($strRsNodeWidgetDetailsArr))
{
	if($strRsNodeWidgetDetails->external_flag==1 and $strRsNodeWidgetDetails->external_visible==0)
		continue;
	//print $strRsNodeWidgetDetails->widget_serial_number."<br />";
	
	$arrWidgets[]=array(
					$strRsNodeWidgetDetails->widget_serial_number, 
					$strRsNodeWidgetDetails->temperature_flag, 
					$strRsNodeWidgetDetails->temperature_type,
					$strRsNodeWidgetDetails->temperature_alarm_low,
					$strRsNodeWidgetDetails->temperature_alarm_high,
					$strRsNodeWidgetDetails->humidity_low,
					$strRsNodeWidgetDetails->humidity_high,
					);
	
}

?>
<script type="text/javascript">
var iWidgetLinked=0;

var iSettingOpen=0;
$('#Widget_Settings_Click').click(function(){
	if(iSettingOpen==0)
	{
		$('#Widget_Settings').slideDown('slow');
		$('#Widget_Settings_Click').html('- SETTINGS');
		iSettingOpen=1;
	}
	else
	{
		$('#Widget_Settings').slideUp('slow');
		$('#Widget_Settings_Click').html('+ SETTINGS');
		iSettingOpen=0;
	}
});




var iBehaviorOpen=0;
$('#Widget_Behavior_Click').click(function(){
	if(iBehaviorOpen==0)
	{
		$('#Widget_Behavior').slideDown('slow');
		$('#Widget_Behavior_Click').html('- BEHAVIOR');
		iBehaviorOpen=1;
	}
	else
	{
		$('#Widget_Behavior').slideUp('slow');
		$('#Widget_Behavior_Click').html('+ BEHAVIOR');
		iBehaviorOpen=0;
	}
});

var iLinkOpen=0;
$('#Widget_Link_Click').click(function(){
	if(iLinkOpen==0)
	{
		$('#Widget_Link').slideDown('slow');
		$('#Widget_Link_Click').html('- LINK WIDGET');
		iLinkOpen=1;
	}
	else
	{
		$('#Widget_Link').slideUp('slow');
		$('#Widget_Link_Click').html('+ LINK WIDGET');
		iLinkOpen=0;
	}
});

$('.optWidget_TempType').change(function(){
	var Degree=$('input[name=optWidget_TempType]:checked').val();
	$('.Fahrenheit_Celcius').html(Degree);
});

/*$('#temp1_Color1').colorpicker({
	
});*/

$('#txtWidget_ColorTemp1').click(function(){
	var color = $( this ).css( "color" );
	$('#Temperature_Preview').css('color',color);
});

$('#txtWidget_ColorTemp2').click(function(){
	var color = $( this ).css( "color" );
	$('#Temperature_Preview').css('color',color);
});

$('#txtWidget_ColorTemp3').click(function(){
	var color = $( this ).css( "color" );
	$('#Temperature_Preview').css('color',color);
});


$('#Linked_Button').click(function(){
	var NodeSerial= $('#txtWidget_NodeSerial').val();
	var TempType=$('#optWidget_TempType').val();
	var TempLow=$('#txtWidget_TempLow').val();
	var TempHigh=$('#txtWidget_TempHigh').val();
	var HumidityLow=$('#txtWidget_HumidityLow').val();
	var HumidityHigh=$('#txtWidget_HumidityHigh').val();
	
	var Widget_Temp1=$('#txtWidget_ColorTemp1').val();
	var Widget_Temp2=$('#txtWidget_ColorTemp2').val();
	var Widget_Temp3=$('#txtWidget_ColorTemp3').val();
	
	var Widget_Temp_Color_1='#000000';
	var Widget_Temp_Color_2='#009900';
	var Widget_Temp_Color_3='#FF0000';
	
	var Widget_Humidity1=$('#txtWidget_ColorTemp1').val();
	var Widget_Humidity2=$('#txtWidget_ColorTemp2').val();
	var Widget_Humidity3=$('#txtWidget_ColorTemp3').val();
	
	var Widget_Humidity_Color_1='#000000';
	var Widget_Humidity_Color_2='#009900';
	var Widget_Humidity_Color_3='#FF0000';
	
	var ProjectID=1;
	
	if(iWidgetLinked==0 && NodeSerial!='')
	{
		$.post("<?php echo URL?>ajax_pages/widget_linked.php",
		  {
			id:ProjectID,
			NodeSerial:NodeSerial,
			TempType:TempType,
			TempLow:TempLow,
			TempHigh:TempHigh,
			HumidityLow:HumidityLow,
			HumidityHigh:HumidityHigh,
			Widget_Temp1:Widget_Temp1,
			Widget_Temp2:Widget_Temp2,
			Widget_Temp3:Widget_Temp3,
			Widget_Temp_Color_1:Widget_Temp_Color_1,
			Widget_Temp_Color_2:Widget_Temp_Color_2,
			Widget_Temp_Color_3:Widget_Temp_Color_3,
			Widget_Humidity1:Widget_Humidity1,
			Widget_Humidity2:Widget_Humidity2,
			Widget_Humidity3:Widget_Humidity3,
			Widget_Humidity_Color_1:Widget_Humidity_Color_1,
			Widget_Humidity_Color_2:Widget_Humidity_Color_2,
			Widget_Humidity_Color_3:Widget_Humidity_Color_3
		  },
		  function(data,status){
				var dataArr=data.split("~");				
				
				$('#Temperature_Widget_ID').html(dataArr[1]+"T");
				$('#Humidity_Widget_ID').html(dataArr[1]+"H");
				
				$('#Widget_Serial_Number_Input').css('display','none');
				$('#Widget_Serial_Number_Full').html('THN'+NodeSerial);
				$('#Linked_Button img').css('opacity','0.4');
				
				$('#Widget_Link_Not_Available').css('display','none');
				$('#Widget_Link_Available').css('display','block');
				$('#Widget_Link_Widget_Serial_Text').html(dataArr[1]);
					
				
		  });
		  iWidgetLinked=1;
	  }
	  
	  
	  
	  $('.Widget_Link_Button').css('background-color','#149b47');
	  $('.Widget_Link_Button').html('Linked');
	  
	 
	
});



function AddWidgetImage_Active_Inactive(bgImage,first,second,External)
{
	AddWidgetImage(bgImage,1,1,External);
} 

function AddWidgetImage_Active_Inactive_Humidity(bgImage,first,second, External)
{
	AddWidgetImage_Humidity(bgImage,1,1,External);
}


</script>




<div id="img-out"></div>

<div id="Widget_Link_Response"></div>

<form name="" id="" method="post" action="">

<div style="font-size:16px; font-weight:bold; color:#666666; text-decoration:underline; margin-bottom:10px; text-align:center;">TEMPERATURE/HUMIDITY WIDGET LINK</div>

<?php
$iCtr=0;
if( is_array($arrWidgets[$iCtr]) and count($arrWidgets[$iCtr])>0)
{
?>
<div style="float:left; width:48%; margin:1%; font-size:12px;" id="Temperature_Meter">
	<div style="font-size:14px; text-decoration:underline;">TEMPERATURE</div>
	<span id="Temperature_Widget_ID"><?php echo $arrWidgets[$iCtr][0]?></span>
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Temperature_Preview">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;"><span id="Temperature_In_Degree"><?php echo $arrWidgets[$iCtr][3]?></span>&deg;<span class="Fahrenheit_Celcius" id="Temperature_In_Degree_Type"><?php echo ($arrWidgets[$iCtr][2]==1 ? 'F' : 'C');?></span></div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton1" onclick="AddWidgetImage_Active_Inactive('meter.png',1,1,0)" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>
<?php
}
$iCtr++;
if( is_array($arrWidgets[$iCtr]) and count($arrWidgets[$iCtr])>0)
{
?>

<div style="float:left; width:48%; margin:1%; font-size:12px;">
	<div style="font-size:14px; text-decoration:underline;">HUMIDITY</div>
	<span id="Humidity_Widget_ID"><?php echo $arrWidgets[$iCtr][0]?></span>
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Humidity_Preview">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;"><span id="Humidity_In_Percentage"><?php echo $arrWidgets[$iCtr][5]?></span>%</div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton2" onclick="AddWidgetImage_Active_Inactive_Humidity('meter.png',1,1,0)" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>
<?php }?>
<div class="clear"></div>

<?php
$iCtr++;
if( is_array($arrWidgets[$iCtr]) and count($arrWidgets[$iCtr])>0)
{
?>
<div style="float:left; width:48%; margin:1%; font-size:12px;" id="Temperature_Meter_Ext">
	<div style="font-size:14px; text-decoration:underline;">TEMPERATURE</div>
	<span id="Temperature_Widget_ID_Ext"><?php echo $arrWidgets[$iCtr][0]?></span>
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Temperature_Preview_Ext">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;"><span id="Temperature_In_Degree_Ext"><?php echo $arrWidgets[$iCtr][3]?></span>&deg;<span class="Fahrenheit_Celcius" id="Temperature_In_Degree_Type_Ext"><?php echo ($arrWidgets[$iCtr][2]==1 ? 'F' : 'C');?></span></div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton1_Ext" onclick="AddWidgetImage_Active_Inactive('meter.png',1,1,1)" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>
<?php
}
$iCtr++;
if( is_array($arrWidgets[$iCtr]) and count($arrWidgets[$iCtr])>0)
{
?>

<div style="float:left; width:48%; margin:1%; font-size:12px;">
	<div style="font-size:14px; text-decoration:underline;">HUMIDITY</div>
	<span id="Humidity_Widget_ID_Ext"><?php echo $arrWidgets[$iCtr][0]?></span>
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Humidity_Preview_Ext">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;"><span id="Humidity_In_Percentage_Ext"><?php echo $arrWidgets[$iCtr][5]?></span>%</div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton2_Ext" onclick="AddWidgetImage_Active_Inactive_Humidity('meter.png',1,1,1)" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>
<?php }?>
<div class="clear"></div>

</form>

