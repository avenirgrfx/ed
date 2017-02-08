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

$('#optWidget_TempType').change(function(){
	//$('.Fahrenheit_Celcius').html('C');
	alert($('input[name=optWidget_TempType]').val());
});


 
</script>

<div style="font-size:14px; color:#666666; text-decoration:underline; margin-bottom:10px;">TEMPERATURE/HUMIDITY WIDGET LINK</div>
<div style="font-size:12px;">
	TEMPERATURE WIDGET - WTH155303A<br />
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
          <input name="optWidget_TempType" type="radio" id="optWidget_TempType" value="F" checked="checked" />
        </div>
        <div style="float:left; margin-left:3px;">Fahrenheit</div>
        
        <div style="float:left; margin-left:10px;">
          <input type="radio" name="optWidget_TempType" id="optWidget_TempType" value="C" />
        </div>
        <div style="float:left; margin-left:3px; ">Celcius</div>
        <div class="clear"></div>
    </div>
    
	
	<div id="Widget_Behavior_Click" style="cursor:pointer;" class="RightPanelSubTitle">+ BEHAVIOR</div>
    
    <div id="Widget_Behavior" style="display:none; margin-top:5px; margin-bottom:5px;">
    
        <div style="float:left;"><input type="checkbox" id="chkWidget_Alarm" name="chkWidget_Alarm" value="" /></div>
        <div style="float:left; margin-left:8px; margin-top:3px;">Alarm</div>    
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_TempLow" name="txtWidget_TempLow" value="0.0" style="width:15px; height:10px; font-size:10px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666; margin-left:5px;">Low</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_TempHigh" name="txtWidget_TempHigh" value="0.0" style="width:15px; height:10px; font-size:10px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666; margin-left:5px;">High</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_HumidityLow" name="txtWidget_HumidityLow" value="0" style="width:15px; height:10px; font-size:10px; color:#666666;" />%<br /><span style="color:#666666; margin-left:5px;">Low</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_HumidityHigh" name="txtWidget_HumidityHigh" value="0" style="width:15px; height:10px; font-size:10px; color:#666666;" />%<br /><span style="color:#666666; margin-left:5px;">High</span></div>
        <div style="float:left; margin-left:8px; border:1px solid #666666; background-color:#CCCCCC; padding:1px; text-transform:uppercase; font-size:10px; border-radius:2px;">Behavior1</div>
        <div class="clear" style="height:10px;"></div>
        
    
        <div style="float:left;"><input type="checkbox" id="" name="" value="" /></div>
        <div style="float:left; margin-left:8px; margin-top:3px;">Color</div>    
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_ColorTemp1" name="txtWidget_ColorTemp1" value="45" style="width:15px; height:10px; font-size:10px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666;">Temp1<br />Color1</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_ColorTemp2" name="txtWidget_ColorTemp2" value="65" style="width:15px; height:10px;  font-size:10px; color:#009900;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666;">Temp2<br />Color2</span></div>
        <div style="float:left; margin-left:8px; font-size:10px;"><input type="text" id="txtWidget_ColorTemp3" name="txtWidget_ColorTemp3" value="95" style="width:15px; height:10px;  font-size:10px; color:#FF0000;" />&deg;<span class="Fahrenheit_Celcius">F</span><br /><span style="color:#666666;">Temp3<br />Color3</span></div>
        <div style="float:left; margin-left:55px; border:1px solid #666666; background-color:#CCCCCC; padding:1px; text-transform:uppercase; font-size:10px; border-radius:2px;">Behavior2</div>
        <div class="clear"></div>
    </div>
    
    <div id="Widget_Link_Click" style="cursor:pointer;" class="RightPanelSubTitle">+ LINK WIDGET</div>
    <div id="Widget_Link" style="display:none; margin-top:5px; margin-bottom:5px;">
    	,kmfnkfdbfgdmkb
    </div>
    
</div>