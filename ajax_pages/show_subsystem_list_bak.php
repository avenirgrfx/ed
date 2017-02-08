<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
$strParentID=$_GET['strMasterSystemID'];
if($strParentID<>'')
{
?>

<script type="text/javascript">
function ShowAvailableSystemNodes(strNodeClassID, strParentID, strRoomID)
{
	
	$.get("<?php echo URL?>ajax_pages/show_subsystem_list.php",
	{
		strNodeClassID:strNodeClassID,
		mode:'node_list',
		strRoomID:<?php echo $strRoomID?>,
	},
		function(data,status){							
			$('#NodeClass_Container_'+strParentID).html(data);
	 });
	
}
</script>


<?php

	$strSQL="Select * from t_system where parent_id=$strParentID";
	$strRsSubSystemsArr=$DB->Returns($strSQL);
	print '<div style="float:left;">';
	print '<select name="" id="" onchange="ShowAvailableSystemNodes(this.value,'.$strParentID.')">';
	print '<option value="0">Choose Node Class</option>';
	while($strRsSubSystems=mysql_fetch_object($strRsSubSystemsArr))
	{
		print '<option value="'.$strRsSubSystems->system_id.'">'.$strRsSubSystems->system_name.'</option>';
	}
	print '</select>';
	print '</div>';
	
	print '<div style="margin-left:20px; float:left;" id="NodeClass_Container_'.$strParentID.'"></div>';
}

elseif($_GET['strNodeClassID']<>'' and $_GET['mode']=='node_list')
{
	$room_id=$_GET['strRoomID'];
	$strSQL="Select * from t_system_node where system_id=".$_GET['strNodeClassID']." and delete_flag=0 and client_id=0";
	$strRsAvailableNodesArr=$DB->Returns($strSQL);
	print '<select name="txtUnitIDFor_'.$room_id.'" id="">';
	print '<option value="0">Choose from Available List</option>';
	while($strRsAvailableNodes=mysql_fetch_object($strRsAvailableNodesArr))
	{
		print '<option value="'.$strRsAvailableNodes->system_node_id.'">'.$strRsAvailableNodes->node_serial.($strRsAvailableNodes->custom_name<>"" ? " - ".$strRsAvailableNodes->custom_name : "").'</option>';
	}
	print '</select>';	
}

?>