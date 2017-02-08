<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
$strRoomID=$_GET['id'];
$strWidgetID=$_GET['widgetID'];

$strSQL="Select prefix from t_widgets where widget_id =(select widget_type from t_project_widget_links where widget_id=$strWidgetID)";
$strRsCheckWidgetTypeArr=$DB->Returns($strSQL);
if($strRsCheckWidgetType=mysql_fetch_object($strRsCheckWidgetTypeArr))
{
	$strPrefix=$strRsCheckWidgetType->prefix;
}



if(strtolower($strPrefix)=='thn')
{
	# For THN Type Widgets
	$strSQL="Select * from t_thn_widget where thn_widget_id=$strWidgetID";
	$strRsWidgetForRoomArr=$DB->Returns($strSQL);
	while($strRsWidgetForRoom=mysql_fetch_object($strRsWidgetForRoomArr))
	{
	?>
		<?php if($strRsWidgetForRoom->temperature_flag==1){?>
			<div style=" float:left; margin-left:250px; width:173px; margin-top:6px;"><?php echo $strRsWidgetForRoom->widget_serial_number;?>T</div>
			<div style='float:left; width:100px; text-align:center; text-transform:uppercase; background-color:#989898; color:#FFFFFF; border-radius:5px; padding:2px 5px; margin-top:3px;'>Settings</div>
			<div style='float:left; width:80px; text-align:center; margin-left:20px; text-transform:uppercase; background-color:#989898; color:#FFFFFF; border-radius:5px; padding:2px 5px; margin-top:3px;'>Behavior</div>
			<div style='float:left; width:60px; text-align:center; margin-left:20px; text-transform:uppercase; background-color:#989898; color:#FFFFFF; border-radius:5px; padding:2px 5px; margin-top:3px;'>Link</div>
			<div class="clear"></div>
		<?php }?>
		
		<?php if($strRsWidgetForRoom->humidity_flag==1){?>
			<div style=" float:left; margin-left:250px; width:173px; margin-top:6px;"><?php echo $strRsWidgetForRoom->widget_serial_number;?>H</div>
			<div style='float:left; width:100px; text-align:center; text-transform:uppercase; background-color:#989898; color:#FFFFFF; border-radius:5px; padding:2px 5px; margin-top:3px;'>Settings</div>
			<div style='float:left; width:80px; text-align:center; margin-left:20px; text-transform:uppercase; background-color:#989898; color:#FFFFFF; border-radius:5px; padding:2px 5px; margin-top:3px;'>Behavior</div>
			<div style='float:left; width:60px; text-align:center; margin-left:20px; text-transform:uppercase; background-color:#989898; color:#FFFFFF; border-radius:5px; padding:2px 5px; margin-top:3px;'>Link</div>
			<div class="clear"></div>
		<?php }?>		
	<?php }?>
    

<?php }else{?>
	Under construction
<?php }?>