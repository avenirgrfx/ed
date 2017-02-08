<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

if($_GET['action']=='UpdateTHN_Settings' and $_GET['widget_id']<>"")
{
	$strSQL="Update t_thn_widget set temperature_type=".$_GET['temp_type']." where thn_widget_id=".$_GET['widget_id'];
	$DB->Execute($strSQL);
	exit();
}

if($_GET['action']=='UpdateTHN_Behavior' and $_GET['widget_id']<>"")
{
	if($_GET['UpdateType']=='0')
	{
		$strSQL="Update t_thn_widget set alarm=".$_GET['temp_val']." where thn_widget_id=".$_GET['widget_id'];
	}
	elseif($_GET['UpdateType']=='1')
	{
		$temp_val=Globals::ConvertTempToSave($_GET['temp_val'], $_GET['TempType']);
		$strSQL="Update t_thn_widget set temperature_alarm_low=".$temp_val." where thn_widget_id=".$_GET['widget_id'];
	}
	elseif($_GET['UpdateType']=='2')
	{
		$temp_val=Globals::ConvertTempToSave($_GET['temp_val'], $_GET['TempType']);
		$strSQL="Update t_thn_widget set temperature_alarm_high=".$temp_val." where thn_widget_id=".$_GET['widget_id'];
	}
	elseif($_GET['UpdateType']=='3')
	{
		$strSQL="Update t_thn_widget set humidity_low=".$_GET['temp_val']." where thn_widget_id=".$_GET['widget_id'];
	}
	elseif($_GET['UpdateType']=='4')
	{
		$strSQL="Update t_thn_widget set humidity_high=".$_GET['temp_val']." where thn_widget_id=".$_GET['widget_id'];
	}
	$DB->Execute($strSQL);
	exit();
}
elseif($_GET['action']=='LinkWidgetWithWidget')
{
	$LinkWidgetID=$_GET['LinkWidgetID'];
	$ToWidgetID=$_GET['ToWidgetID'];
	
	$strSQL="Insert into t_widget_linked_with_widgets(widget_id, linked_widget_id, doc) values($ToWidgetID,$LinkWidgetID, now())";
	$DB->Execute($strSQL);
	
	$strSQL="Select system_node_id from t_thn_widget where thn_widget_id=$ToWidgetID";
	$strRsSystemNodeDetailsArr=$DB->Returns($strSQL);
	if($strRsSystemNodeDetails=mysql_fetch_object($strRsSystemNodeDetailsArr))
	{
		print $strRsSystemNodeDetails->system_node_id;
	}
	exit();	
}
elseif($_GET['action']=='DeLinkWidgetWithWidget')
{
	$LinkWidgetID=$_GET['LinkWidgetID'];
	$ToWidgetID=$_GET['ToWidgetID'];
	
	$strSQL="Delete from t_widget_linked_with_widgets where widget_id=$ToWidgetID and linked_widget_id=$LinkWidgetID";
	$DB->Execute($strSQL);
	
	$strSQL="Select system_node_id from t_thn_widget where thn_widget_id=$ToWidgetID";
	$strRsSystemNodeDetailsArr=$DB->Returns($strSQL);
	if($strRsSystemNodeDetails=mysql_fetch_object($strRsSystemNodeDetailsArr))
	{
		print $strRsSystemNodeDetails->system_node_id;
	}
	
	exit();
}



$strWidgetType=$_GET['strWidgetType']; // Widghet Type . Eg. THN
$strWidgetID=$_GET['strWidgetID']; // Widget ID in specific widget table. E.g. t_thn_widget
$strType=$_GET['strType']; // Action Type. E.g. Settings, Behavior or Link
$strSystemNodeID=$_GET['strSystemNodeID']; // Node ID in t_system_node table


if(strtolower($strWidgetType)=='thn')
{?>

<script type="text/javascript">
function UpdateTHN_Settings(strTempType, strWidgetID)
{
	$.get("<?php echo URL?>ajax_pages/widget_settings_behavior_link.php",
	{
		action:'UpdateTHN_Settings',
		temp_type:strTempType,
		widget_id: strWidgetID,
	},
	function(data,status){			
	});
}

function UpdateTHN_Behavior(strTempVal, strWidgetID, UpdateType, TempType)
{
	if(UpdateType==0)
	{
		var isChecked=document.getElementById('chkWidget_Alarm_'+strWidgetID).checked;
		if(isChecked==true)
		{
			strTempVal=1;
		}
		else
		{
			strTempVal=0;
		}
	}
	
	$.get("<?php echo URL?>ajax_pages/widget_settings_behavior_link.php",
	{
		action:'UpdateTHN_Behavior',
		temp_val:strTempVal,
		widget_id: strWidgetID,
		UpdateType:UpdateType,
		TempType: TempType,
	},
	function(data,status){
			
	});
}



function LinkWidgetWithWidget(strWidgetID)
{
	var LinkWidgetID=document.getElementById('ddlLinkWidgetID_'+strWidgetID).value
	var ToWidgetID=strWidgetID;
	
	$.get("<?php echo URL?>ajax_pages/widget_settings_behavior_link.php",
	{
		action:'LinkWidgetWithWidget',
		LinkWidgetID:LinkWidgetID,
		ToWidgetID: ToWidgetID,
	},
	function(data,status){
		document.getElementById('Node_Settings_Behavior_Link_'+strWidgetID).style.display='none';
		THN_Node_Settings_Behavior_Link(data,3,strWidgetID);		
	});
	
}

function DelinkWidget(strLinkedWidgetID, strWidgetID)
{
	if(!(confirm("Are you sure you want to Delink?")))
		return;
	
	$.get("<?php echo URL?>ajax_pages/widget_settings_behavior_link.php",
	{
		action:'DeLinkWidgetWithWidget',
		LinkWidgetID:strLinkedWidgetID,
		ToWidgetID: strWidgetID,
	},
	function(data,status){
		document.getElementById('Node_Settings_Behavior_Link_'+strWidgetID).style.display='none';
		THN_Node_Settings_Behavior_Link(data,3,strWidgetID);	
	});
}

</script>

<?php
	$strSQL="Select * from t_thn_widget where thn_widget_id=$strWidgetID";
	$strRsWidgetDetailsArr=$DB->Returns($strSQL);
	if($strRsWidgetDetails=mysql_fetch_object($strRsWidgetDetailsArr))
	{
		if($strType==1)
		{
			if($strRsWidgetDetails->temperature_flag==1)
			{
		?>
                Temperature
                <input onclick="UpdateTHN_Settings(this.value,<?php echo $strWidgetID?>)" type="radio" name="Temperature_Type_<?php echo $strWidgetID?>" id="Temperature_Type_<?php echo $strWidgetID?>" value="1" <?php if($strRsWidgetDetails->temperature_type==1){?>checked<?php }?> />Fahrenheit
                &nbsp;&nbsp;&nbsp;&nbsp;<input onclick="UpdateTHN_Settings(this.value,<?php echo $strWidgetID?>)" type="radio" name="Temperature_Type_<?php echo $strWidgetID?>" id="Temperature_Type_<?php echo $strWidgetID?>" value="0" <?php if($strRsWidgetDetails->temperature_type==0){?>checked<?php }?> />Celcius 
        		<div class="clear" style="height:10px;"></div>
		<?php
			}
			elseif($strRsWidgetDetails->humidity_flag==1)
			{
			?>
            	Humidity (%)
                <div class="clear" style="height:10px;"></div>
			<?php	
			}
		}
		elseif($strType==2)
		{
			if($strRsWidgetDetails->temperature_flag==1)
			{
		?>
                <div style="float:left;"><input onclick="UpdateTHN_Behavior(this.value,<?php echo $strWidgetID?>,0)" type="checkbox" id="chkWidget_Alarm_<?php echo $strWidgetID?>" name="chkWidget_Alarm_<?php echo $strWidgetID?>" value="1" <?php if($strRsWidgetDetails->alarm==1) { print 'Checked'; }?> /></div>
                <div style="float:left; margin-left:8px; margin-top:3px;">Alarm</div>    
                <div style="float:left; margin-left:8px; font-size:12px;"><input onchange="UpdateTHN_Behavior(this.value,<?php echo $strWidgetID?>,1, <?php echo $strRsWidgetDetails->temperature_type;?>)" type="text" id="txtWidget_TempLow_<?php echo $strWidgetID?>" name="txtWidget_TempLow_<?php echo $strWidgetID?>" value="<?php echo Globals::ConvertTemp($strRsWidgetDetails->temperature_alarm_low,  $strRsWidgetDetails->temperature_type); ?>" style="width:50px; height:15px; font-size:12px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius"><?php if($strRsWidgetDetails->temperature_type==1){ print 'F'; } else {print 'C';}?></span><br /><span style="color:#666666; margin-left:5px;">Low</span></div>
                <div style="float:left; margin-left:8px; font-size:12px;"><input onchange="UpdateTHN_Behavior(this.value,<?php echo $strWidgetID?>,2,  <?php echo $strRsWidgetDetails->temperature_type;?>)" type="text" id="txtWidget_TempHigh_<?php echo $strWidgetID?>" name="txtWidget_TempHigh_<?php echo $strWidgetID?>" value="<?php echo Globals::ConvertTemp($strRsWidgetDetails->temperature_alarm_high, $strRsWidgetDetails->temperature_type); ?>" style="width:50px; height:15px; font-size:12px; color:#666666;" />&deg;<span class="Fahrenheit_Celcius"><?php if($strRsWidgetDetails->temperature_type==1){ print 'F'; } else {print 'C';}?></span><br /><span style="color:#666666; margin-left:5px;">High</span></div>  
                <div style="float:left; margin-left:8px; border:1px solid #666666; background-color:#CCCCCC; padding:1px; text-transform:uppercase; font-size:12px; border-radius:2px;">Behavior</div>
                <div class="clear" style="height:10px;"></div>
        <?php
			}
			elseif($strRsWidgetDetails->humidity_flag==1)
			{
			?>
            	<div style="float:left;"><input onclick="UpdateTHN_Behavior(this.value,<?php echo $strWidgetID?>,0)" type="checkbox" id="chkWidget_Alarm_<?php echo $strWidgetID?>" name="chkWidget_Alarm_<?php echo $strWidgetID?>" value="1" <?php if($strRsWidgetDetails->alarm==1) { print 'Checked'; }?> /></div>
                <div style="float:left; margin-left:8px; margin-top:3px;">Alarm</div>  
            	<div style="float:left; margin-left:8px; font-size:12px;"><input onchange="UpdateTHN_Behavior(this.value,<?php echo $strWidgetID?>,3,0)" type="text" id="txtWidget_HumidityLow_<?php echo $strWidgetID?>" name="txtWidget_HumidityLow_<?php echo $strWidgetID?>" value="<?php echo $strRsWidgetDetails->humidity_low; ?>" style="width:50px; height:15px; font-size:12px; color:#666666;" />%<br /><span style="color:#666666; margin-left:5px;">Low</span></div>
                <div style="float:left; margin-left:8px; font-size:12px;"><input onchange="UpdateTHN_Behavior(this.value,<?php echo $strWidgetID?>,4,0)" type="text" id="txtWidget_HumidityHigh_<?php echo $strWidgetID?>" name="txtWidget_HumidityHigh_<?php echo $strWidgetID?>" value="<?php echo $strRsWidgetDetails->humidity_high; ?>" style="width:50px; height:15px; font-size:12px; color:#666666;" />%<br /><span style="color:#666666; margin-left:5px;">High</span></div>
            	<div style="float:left; margin-left:8px; border:1px solid #666666; background-color:#CCCCCC; padding:1px; text-transform:uppercase; font-size:12px; border-radius:2px;">Behavior</div>
                <div class="clear" style="height:10px;"></div>
			<?php
			}	
		}
		elseif($strType==3)
		{
			$strSQL="Select t_system_node.site_id from t_system_node, t_thn_widget where t_thn_widget.system_node_id=t_system_node.system_node_id and t_thn_widget.system_node_id=".$_GET['strSystemNodeID'];
			$strRsSystemDetailsArr=$DB->Returns($strSQL);
			if($strRsSystemDetails=mysql_fetch_object($strRsSystemDetailsArr))
			{
				print '<strong>Link New Widget</strong><br />
				<div style="float:left; margin-bottom:10px;">
				<select name="ddlLinkWidgetID_'.$strWidgetID.'" id="ddlLinkWidgetID_'.$strWidgetID.'">';
				$strSQL="Select widget_serial_number, thn_widget_id from t_thn_widget where thn_widget_id<>$strWidgetID And system_node_id in (select system_node_id from t_system_node where site_id=".$strRsSystemDetails->site_id.") order by widget_serial_number";
				$strRsWidgetDetailsArr=$DB->Returns($strSQL);
				while($strRsWidgetDetails=mysql_fetch_object($strRsWidgetDetailsArr))
				{				
					print "<option value='".$strRsWidgetDetails->thn_widget_id."'>".$strRsWidgetDetails->widget_serial_number."</option>";
				}
				print '</select>
				</div>';
				
				print '<div onclick="LinkWidgetWithWidget('.$strWidgetID.')" style="float:left; margin-left:10px; background-color:#CCCCCC; border:1px solid #333333; padding:2px 10px; cursor:pointer; ">LINK</div>';
				
				print '<div class="clear"></div>';
				
			}
			
			print "<strong>Linked Widgets</strong><br />";
			$strSQL="Select t_widget_linked_with_widgets.*, t_thn_widget.widget_serial_number, t_system_node.custom_name
			from t_widget_linked_with_widgets, t_thn_widget, t_system_node			
			where t_system_node.system_node_id=t_thn_widget.system_node_id
			And t_thn_widget.thn_widget_id=t_widget_linked_with_widgets.linked_widget_id
			And widget_id=$strWidgetID";
			
			$strRsLinkedWidgetsArr=$DB->Returns($strSQL);
			while($strRsLinkedWidgets=mysql_fetch_object($strRsLinkedWidgetsArr))
			{
				print "<div style='float:left; width:280px;'>".$strRsLinkedWidgets->widget_serial_number. ($strRsLinkedWidgets->custom_name<>''? " (".$strRsLinkedWidgets->custom_name.")" : " " ). "</div> <div style='float:left; font-style:italic; color:#CCCCCC;'>".Globals::DateFormat($strRsLinkedWidgets->doc)."</div> <div style='float:left; font-size:12px; margin-left:20px;'><a href='#'>Properties</a> | <a href='javascript:DelinkWidget(".$strRsLinkedWidgets->linked_widget_id.",".$strWidgetID.")'>Delink</a></div>";
				print '<div class="clear"></div>';
			}
			
		}
	}
}

?>