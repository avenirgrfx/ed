<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$WidgetSerailNumber=Globals::ShowWidgetSerialBeforeLinked('thn');

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



function AddWidgetImage_Active_Inactive(bgImage,first,second)
{
	if(iWidgetLinked==1)
	{
		AddWidgetImage(bgImage,1,1);
	}
} 

function AddWidgetImage_Active_Inactive_Humidity(bgImage,first,second)
{
	if(iWidgetLinked==1)
	{
		AddWidgetImage_Humidity(bgImage,1,1);
	}
}


</script>


<div id="img-out"></div>

<div id="Widget_Link_Response"></div>

<form name="" id="" method="post" action="">

<div style="font-size:16px; font-weight:bold; color:#666666; text-decoration:underline; margin-bottom:10px; text-align:center;">TEMPERATURE/HUMIDITY WIDGET LINK</div>



<div style="float:left; width:48%; margin:1%; font-size:12px;" id="Temperature_Meter">
	<div style="font-size:14px; text-decoration:underline;">TEMPERATURE</div>
	<span id="Temperature_Widget_ID"><?php echo $WidgetSerailNumber."T"?></span>
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Temperature_Preview">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;"><span id="Temperature_In_Degree">65</span>&deg;<span class="Fahrenheit_Celcius" id="Temperature_In_Degree_Type">F</span></div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton1" onclick="AddWidgetImage_Active_Inactive('meter.png',1,1)" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>

<div style="float:left; width:48%; margin:1%; font-size:12px;">
	<div style="font-size:14px; text-decoration:underline;">HUMIDITY</div>
	<span id="Humidity_Widget_ID"><?php echo $WidgetSerailNumber."H"?></span>
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Humidity_Preview">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;"><span id="Humidity_In_Percentage">58</span>%</div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton2" onclick="AddWidgetImage_Active_Inactive_Humidity('meter.png',1,1)" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>

<div class="clear"></div>

<div style="font-size:12px;">
	<div style="float:left; margin-top:10px;">NODE Serial</div>
    <div style="float:left; margin-left:10px; font-weight:bold; margin-top:10px;" id="Widget_Serial_Number_Full">THN</div>
    <div style="float:left; margin-top:5px;" id="Widget_Serial_Number_Input"><input type="text" name="txtWidget_NodeSerial" id="txtWidget_NodeSerial" value="" style="width:100px; height:20px;" /></div>
    <div style="float:left; margin-left:10px; margin-top:5px; cursor:pointer;" id="Linked_Button"><img src="<?php echo URL?>images/link.png" /><span class="Widget_Link_Button">Link</span></div>
    <div class="clear"></div>
    
	<hr style="margin:5px 0px;" />
	<div id="Widget_Settings_Click" style="cursor:pointer;" class="RightPanelSubTitle">+ SETTINGS</div>
    <div id="Widget_Settings" style="display:none; margin-top:5px; margin-bottom:5px;">
        <div style="float:left;">Temperature</div>
        
        <div style="float:left; margin-left:10px;">
          <input name="optWidget_TempType" type="radio" id="optWidget_TempType" class="optWidget_TempType" value="F" checked="checked" />
        </div>
        <div style="float:left; margin-left:3px;">Fahrenheit</div>
        
        <div style="float:left; margin-left:10px;">
          <input type="radio" name="optWidget_TempType" id="optWidget_TempType" class="optWidget_TempType" value="C" />
        </div>
        <div style="float:left; margin-left:3px; ">Celcius</div>
        <div class="clear"></div>
    </div>
    
	
	<div id="Widget_Behavior_Click" style="cursor:pointer;" class="RightPanelSubTitle">+ BEHAVIOR</div>
    
    <div id="Widget_Behavior" style="display:none; margin-top:5px; margin-bottom:5px;">
    
        <div style="float:left;"><input type="checkbox" id="chkWidget_Alarm" name="chkWidget_Alarm" value="" /></div>
        <div style="float:left; margin-left:8px; margin-top:3px;">Alarm</div>    
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_TempLow" name="txtWidget_TempLow" value="0.0" style="width:15px; height:10px; font-size:9px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666; margin-left:5px;">Low</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_TempHigh" name="txtWidget_TempHigh" value="0.0" style="width:15px; height:10px; font-size:9px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666; margin-left:5px;">High</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_HumidityLow" name="txtWidget_HumidityLow" value="0" style="width:15px; height:10px; font-size:9px; color:#666666;" />%<br /><span style="color:#666666; margin-left:5px;">Low</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_HumidityHigh" name="txtWidget_HumidityHigh" value="0" style="width:15px; height:10px; font-size:9px; color:#666666;" />%<br /><span style="color:#666666; margin-left:5px;">High</span></div>
        <div style="float:left; margin-left:8px; border:1px solid #666666; background-color:#CCCCCC; padding:1px; text-transform:uppercase; font-size:10px; border-radius:2px;">Behavior1</div>
        <div class="clear" style="height:10px;"></div>
        
    
        <div style="float:left;"><input type="checkbox" id="" name="" value="" /></div>
        <div style="float:left; margin-left:8px; margin-top:3px;">Color</div>    
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_ColorTemp1" name="txtWidget_ColorTemp1" value="45" style="width:15px; height:10px; font-size:9px; color:#000000;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666;">Temp1<br /><div id="Temp1_Color1">Color1</div></span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_ColorTemp2" name="txtWidget_ColorTemp2" value="65" style="width:15px; height:10px;  font-size:9px; color:#009900;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666;">Temp2<br />Color2</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_ColorTemp3" name="txtWidget_ColorTemp3" value="95" style="width:15px; height:10px;  font-size:9px; color:#FF0000;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666;">Temp3<br />Color3</span></div>
        <div style="float:left; margin-left:55px; border:1px solid #666666; background-color:#CCCCCC; padding:1px; text-transform:uppercase; font-size:10px; border-radius:2px;">Behavior2</div>
        <div class="clear"></div>
    </div>
    
    <div id="Widget_Link_Click" style="cursor:pointer;" class="RightPanelSubTitle">+ LINK WIDGET</div>
    <div id="Widget_Link" style="display:none; margin-top:5px; margin-bottom:5px;">
    	<div id="Widget_Link_Not_Available"><h3>Nothing to Add</h3></div>
        <div id="Widget_Link_Available" style="display:none;">
        	<div style="float:left;">Link Widget</div> <div style="background-color:#cccccc; float:left; padding:0px 5px; border:1px solid #333333; margin-left:10px;">Add</div>
			<div class="clear" style="height:10px;"></div>
            <span id="Widget_Link_Widget_Serial_Text" style="font-weight:bold; margin-right:10px;">WTHN14151401</span> WITH <input type="text" id="" name="" value="" placeholder="Widget ID" style="width:70px; height:14px; font-size:12px; color:#000000;" /> Linked
        </div>
    </div>
    
</div>
</form>

