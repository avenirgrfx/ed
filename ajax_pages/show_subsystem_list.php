<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/widgets.class.php");


$DB=new DB;
$Widgets=new Widgets;

$strParentID=$_GET['strMasterSystemID'];
$strBuildingID=$_GET['strBuildingID'];

if( $_GET['project_id']<>'' and  $_GET['mode']=='link_node' and $_GET['node_id']<>'')
{
	$Project_ID=$_GET['project_id'];
	$Node_ID=$_GET['node_id'];
	$room_ID=$_GET['room_id'];
	$strSQL="Select t_projects.*, t_building.site_id from t_projects , t_building where t_projects.projects_id=$Project_ID And t_building.building_id=t_projects.building_id";
	$strRsProjectDetailsArr=$DB->Returns($strSQL);
	while($strRsProjectDetails=mysql_fetch_object($strRsProjectDetailsArr))
	{
		$client_id=$strRsProjectDetails->client_id;
		$site_id=$strRsProjectDetails->site_id;
		$building_id=$strRsProjectDetails->building_id;
		//$room_id=$strRsProjectDetails->room_id;
		
		# Check Type of Node and Insert into Related DB Table
		$strSQL="Select t_system_node.*, t_system.prefix from t_system_node, t_system where t_system.system_id=t_system_node.system_id And system_node_id=$Node_ID";
		$strRsNodeDetailsArr=$DB->Returns($strSQL);
		if($strRsNodeDetails=mysql_fetch_object($strRsNodeDetailsArr))
		{
			$Widgets->system_node_id=$strRsNodeDetails->system_node_id;
			
			# Adding THN type node
			if(strtolower($strRsNodeDetails->prefix)=='thn')
			{
				$Widgets->Add_THN();
			}
		}
		
		# Mark Node Linked to a Project and thereafter unavailable for other projects
		$strSQL="Update t_system_node set client_id=$client_id, site_id=$site_id, building_id=$building_id, room_id=$room_ID, project_id=$Project_ID where system_node_id=$Node_ID";
		$DB->Execute($strSQL);	
	}
	
	print $site_id;
	exit();
}


if($strParentID<>'')
{
?>

<script type="text/javascript">
function ShowAvailableSystemNodes(strNodeClassID, strParentID, strBuildingID)
{	
	$.get("<?php echo URL?>ajax_pages/show_subsystem_list.php",
	{
		strNodeClassID:strNodeClassID,
		mode:'node_list',
		strBuildingID: strBuildingID,
	},
		function(data,status){							
			$('#NodeClass_Container_'+strParentID+'_'+strBuildingID).html(data);
	});
	
}

function LinkSystemNodes(strBuildingID)
{
	var project_id=document.getElementById('project_list_'+strBuildingID).value;
	var node_id=document.getElementById('txtUnitIDFor_'+strBuildingID).value;
	var room_id=document.getElementById('RoomList').value;
	if(project_id=='' || project_id==0)
	{
		alert("Please select a Project");
		document.getElementById('project_list_'+strBuildingID).focus();
		return;
	}
	if(node_id=='' || node_id==0)
	{
		alert("Please select a Node");
		document.getElementById('txtUnitIDFor_'+strBuildingID).focus();
		return;
	}
    if(room_id=='' || room_id==0)
	{
		alert("Please select a Room");
		document.getElementById('ddlRoomList_'+strBuildingID).focus();
		return;
	}
	
	$.get("<?php echo URL?>ajax_pages/show_subsystem_list.php",
	{
		project_id:project_id,
		mode:'link_node',
		node_id: node_id,
        room_id:room_id,
	},
		function(data,status){
		
			/*$('#TempCheck').html(data);*/
			
			ShowBuildingName(data);
			alert("Node Added");
	});
}

</script>


<?php
	$strSQL="Select * from t_system where parent_id=$strParentID";
	$strRsSubSystemsArr=$DB->Returns($strSQL);
	print '<div style="float:left;">';
	print '<select name="" id="" onchange="ShowAvailableSystemNodes(this.value,'.$strParentID.','.$strBuildingID.')">';
	print '<option value="0">Choose Node Class</option>';
	while($strRsSubSystems=mysql_fetch_object($strRsSubSystemsArr))
	{
		print '<option value="'.$strRsSubSystems->system_id.'">'.$strRsSubSystems->system_name.'</option>';
	}
	print '</select>';
	print '</div>';
	
	print '<div style="margin-left:20px; float:left;" id="NodeClass_Container_'.$strParentID.'_'.$strBuildingID.'"></div>';
}

elseif($_GET['strNodeClassID']<>'' and $_GET['mode']=='node_list')
{
	$strSQL="Select * from t_system_node where system_id=".$_GET['strNodeClassID']." and delete_flag=0 and client_id=0";
	$strRsAvailableNodesArr=$DB->Returns($strSQL);
	
	print '<div style="float:left;">';
	print '<select name="txtUnitIDFor_'.$strBuildingID.'"  id="txtUnitIDFor_'.$strBuildingID.'" onchange="ShowRooms('.$strBuildingID.')">';
	print '<option value="0">Choose from Available List</option>';
	while($strRsAvailableNodes=mysql_fetch_object($strRsAvailableNodesArr))
	{
		print '<option value="'.$strRsAvailableNodes->system_node_id.'">'.$strRsAvailableNodes->node_serial.($strRsAvailableNodes->custom_name<>"" ? " - ".$strRsAvailableNodes->custom_name : "").'</option>';
	}
	print '</select>';	
	print '</div>';
	
	print "<div style='float:left; margin-left:10px;'><img src='".URL."images/link.png' /></div>
	<div style='float:left; margin-left:10px;  padding:2px 5px; margin-top:3px; cursor:pointer; border:1px solid #CCCCCC;' onclick=LinkSystemNodes('".$strBuildingID."')>Link</div>";
	
	print '<div class="clear"></div> <div id="TempCheck"></div>';
}

elseif($_GET['mode']=='room_list'){

      //  $strParentID=$_GET['strMasterSystemID'];
        $strBuildingID=$_GET['strBuildingID'];
        $strSQL = "Select * from t_room where building_id=$strBuildingID";
        $strRsRoomArr = $DB->Returns($strSQL);
        print '<div style="float:left;">';
	    print '<select name="RoomList" id="RoomList">';
	    //print '<option value="0">Choose Rooms</option>';
	    print '<option value="0">Tag Room</option>';
	    while ($strRsRoom = mysql_fetch_object($strRsRoomArr)) {
            print '<option value="'.$strRsRoom->room_id.'">'.$strRsRoom->room_name.'</option>';
        }
       
        print '</select>';
	    print '</div>'; 
       
}
