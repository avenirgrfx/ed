<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
?>
<script type="text/javascript">
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
 
</script>

<form name="" id="" method="post" action="">

<div style="font-size:14px; color:#666666; text-decoration:underline; margin-bottom:10px; text-align:center;">TEMPERATURE/HUMIDITY WIDGET LINK</div>

<div style="float:left; width:48%; margin:1%; font-size:12px;">
	<div style="font-size:15px; text-decoration:underline;">TEMPERATURE</div>
	WTH1234566
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;" id="Temperature_Preview">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;">65&deg;<span class="Fahrenheit_Celcius">F</span></div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>

<div style="float:left; width:48%; margin:1%; font-size:12px;">
	<div style="font-size:15px; text-decoration:underline;">HUMIDITY</div>
	WTH1234566
	<div style="width:128px; height:119px; background-image:url(<?php echo URL?>images/Meter.png); background-repeat:no-repeat;">
    	<div style="font-family:UsEnergyEngineersDigital; font-size:36px; padding:24px 0px 0px 0px; text-align:center;">58%</div>
    </div>
    <div class="ControlBoxWithIcon" id="ImportWidgetButton" style="margin-left:30px; margin-top:5px; margin-bottom:10px;">Place <img src="<?php echo URL?>images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
</div>

<div class="clear"></div>

<div style="font-size:12px;">
	<div style="float:left; margin-top:5px;">NODE Serial</div>
    <div style="float:left; margin-left:10px; font-weight:bold; margin-top:5px;">THN</div>
    <div style="float:left; margin-left:10px;"><input type="text" name="txtWidget_NodeSerial" id="txtWidget_NodeSerial" value="" style="width:100px; height:20px;" /></div>
    <div style="float:left; margin-left:10px; margin-top:5px;">Linked</div>
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
    	,kmfnkfdbfgdmkb
    </div>
    
</div>
</form>