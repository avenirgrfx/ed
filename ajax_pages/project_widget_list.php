<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$widget_category_id=$_GET['id'];
$project_id=$_GET['project_id'];
?>

<script type="text/javascript">
function ShowWidgetDetails(strNodeSerial, strNodeType, strNodeWidgetID)
{
	$('#Node_Widget_Workspace_').slideUp('slow');
	$('#Node_Widget_Workspace_').html('Loading');
	if(strNodeType=='thn')
	{
		$.get("<?php echo URL?>ajax_pages/widget_templates/temperature_humidity.php",
		{
			strNodeSerial:strNodeSerial				
		},
		function(data,status){
			$('#Node_Widget_Workspace_').slideDown('slow');
			$('#Node_Widget_Workspace_').html(data);
		});
	}
	
}
</script>

<div id="Node_Widget_Workspace_"></div>
<div class="RightPanelTitle" style="font-size:16px; font-weight:bold; text-transform:uppercase; margin-top:0px; padding-left:5px; background-color:#66CCFF;" >Select Active Node</div>
<?php
$strSQL="Select system_node_id, node_serial, t_system.prefix from t_system_node, t_system 
where t_system.system_id=t_system_node.system_id
And project_id=$project_id 
and t_system_node.system_id=$widget_category_id";
$strRsNodeDetailsArr=$DB->Returns($strSQL);
while($strRsNodeDetails=mysql_fetch_object($strRsNodeDetailsArr))
{	
	if(strtolower($strRsNodeDetails->prefix)=='thn')
	{?>
    	<div style="color:#3366FF; cursor:pointer;" Onclick="ShowWidgetDetails('<?php echo $strRsNodeDetails->node_serial?>', 'thn','<?php echo $strRsNodeDetails->system_node_id?>')" >
			<?php echo $strRsNodeDetails->node_serial?>
        </div>
        
    <?php
		
	}
	else
	{
		print $strRsNodeDetails->node_serial." - ".$strRsNodeDetails->prefix."<br />";
	}
	
}
exit();
?>

