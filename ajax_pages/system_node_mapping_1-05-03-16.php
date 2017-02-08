<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/widget_category.class.php");


$DB=new DB;
$WidgetCategory=new WidgetCategory;

$strSystemID=$_GET['SystemID'];
$DeleteSystemNodeID=$_GET['DeleteSystemNodeID'];

if($DeleteSystemNodeID<>'' and $_GET['mode']=='delete')
{
	$strSQL="Update t_system_node set delete_flag=1 where system_node_id=$DeleteSystemNodeID";
	$DB->Execute($strSQL);
	print "Done";
}

if($_GET['EditNodeID'] <>"" and $_GET['EditCustomName']<>"" and $_GET['mode']=='update')
{
	$strSQL="Update t_system_node set custom_name='".$_GET['EditCustomName']."' where system_node_id=".$_GET['EditNodeID'];
	$DB->Execute($strSQL);
	print "Done";
}

if($_POST['txtnode_serial_number']<>"")
{	
	$system_id=$_POST['txtsystem_id'];
	$year_of_creation=date("Y");
	$node_serial=$_POST['txtnode_serial_number'];
	$available_system_node_serial=$_POST['ddlAvailableNodes'];
	$custom_name=mysql_escape_string($_POST['txtnode_custom_name']);
	$client_id=0;
	$site_id=0;
	$building_id=0;
	$room_id=0;
	$project_id=0;
	$created_by=$_SESSION['user_login']->user_id;
	$linked_by=0;
	
	
	$strSQL="Select parent_id from t_system where system_id=$system_id";
	$strRsParentIDsArr=$DB->Returns($strSQL);
	if($strRsParentIDs=mysql_fetch_object($strRsParentIDsArr))
	{
		$parent_id=$strRsParentIDs->parent_id;
	}
	
	
	$strSQL="Select parent_id from t_system where system_id=$parent_id";
	$strRsParentIDsArr=$DB->Returns($strSQL);
	if($strRsParentIDs=mysql_fetch_object($strRsParentIDsArr))
	{
		$parent_parent_id=$strRsParentIDs->parent_id;
	}
	
	$strSQL="Select parent_id from t_system where system_id=$parent_parent_id";
	$strRsParentIDsArr=$DB->Returns($strSQL);
	if($strRsParentIDs=mysql_fetch_object($strRsParentIDsArr))
	{
		$parent_parent_parent_id=$strRsParentIDs->parent_id;
	}
	
	$strSQL="Insert into t_system_node(system_id, year_of_creation, node_serial, custom_name, client_id, 
	site_id, building_id, room_id, project_id, doc, created_by, date_linked, linked_by, parent_id, parent_parent_id, parent_parent_parent_id, available_system_node_serial)
	Values($system_id, $year_of_creation, '$node_serial', '$custom_name', $client_id, 
	$site_id, $building_id, $room_id, $project_id, now(), $created_by, now(), $linked_by, $parent_id, $parent_parent_id, $parent_parent_parent_id, '$available_system_node_serial')";
	
	$DB->Execute($strSQL);
	
	$strSQL="Update t_available_system_nodes set linked_with_system_flag=1, linked_with_system_date=now() where node_serial='$available_system_node_serial'";
	$DB->Execute($strSQL);
	print "Node Created";
}


if($strSystemID<>"")
{
	print "<div style='padding:3px;'>";
	$strSQL="Select * from t_system where system_id=$strSystemID";
	$strRsSystemsArr=$DB->Returns($strSQL);
	if($strRsSystems=mysql_fetch_object($strRsSystemsArr))
	{
		print "<h3 style='text-align:center;'>Manage Nodes for ".$strRsSystems->system_name."</h3>";
	}
	
	$strSQL="Select count(*) as SerialNumbers from t_system_node where system_id=$strSystemID and year_of_creation=".date("Y");
	// THN150001A to THN159999A then THN150001B and so on
	$strRsNodeSerialArr=$DB->Returns($strSQL);
	if($strRsNodeSerial=mysql_fetch_object($strRsNodeSerialArr))
	{
		$AvailableCount=$strRsNodeSerial->SerialNumbers;
		$AvailableCount++;
		if($AvailableCount>=0 and $AvailableCount<10)
			$SerialNumber='000'.$AvailableCount."A";
		elseif($AvailableCount>=10 and $AvailableCount<100)
			$SerialNumber='00'.$AvailableCount."A";
		elseif($AvailableCount>=100 and $AvailableCount<1000)
			$SerialNumber='0'.$AvailableCount."A";
		elseif($AvailableCount>=1000 and $AvailableCount<10000)
			$SerialNumber=$AvailableCount."A";
	}
	
	$SerialNumber=$strRsSystems->prefix.date("y").$SerialNumber;
	
	?>
    
    <script type="text/javascript">
		function LinkSystemNode()
		{
			var node_serial_number=document.getElementById('txtnode_serial_number').value;
			var node_custom_name=document.getElementById('txtnode_custom_name').value;
			var system_id=document.getElementById('txt_system_id').value;
			var ddlAvailableNodes=document.getElementById('ddlAvailableNodes').value;
			$.post("<?php echo URL?>ajax_pages/system_node_mapping.php",
			{
				txtnode_serial_number:node_serial_number,
				txtnode_custom_name:node_custom_name,
				txtsystem_id:system_id,	
				ddlAvailableNodes:ddlAvailableNodes,
			},
				function(data,status){
				alert(data);				
				LoadSystemNodeDetails(system_id)
			 });
		}
		
		function DeleteSystemNode(DeleteSystemNodeID, strSystemID)
		{
			if(!confirm("Are you sure you want to Delete?"))
				return;
			$.get("<?php echo URL?>ajax_pages/system_node_mapping.php",
			{
				DeleteSystemNodeID:DeleteSystemNodeID,
				mode:'delete',
			},
				function(data,status){							
				LoadSystemNodeDetails(strSystemID)
			 });
		}
		
		function EditNodeCustomName(strNodeID)
		{
			var strNodeCustomeName=document.getElementById('CustomName_'+strNodeID).innerHTML;
			document.getElementById('CustomName_'+strNodeID).innerHTML='<input style="width:150px;" type="text" name="CustomName_Edit_'+strNodeID+'" id="CustomName_Edit_'+strNodeID+'" value="'+strNodeCustomeName+'" />';
			document.getElementById('CustomName_Control_'+strNodeID).innerHTML='<a href="javascript:EditNodeCustomName_Cancel('+strNodeID+')">Cancel</a> | <a href="javascript:EditNodeCustomName_Update('+strNodeID+')">Update</a>';
		}
		
		function EditNodeCustomName_Cancel(strNodeID)
		{
			document.getElementById('CustomName_'+strNodeID).innerHTML=document.getElementById('CustomName_Edit_'+strNodeID).value;
			document.getElementById('CustomName_Control_'+strNodeID).innerHTML='<a href="javascript:EditNodeCustomName('+strNodeID+')">Edit</a>';
		}
		
		function EditNodeCustomName_Update(strNodeID)
		{
			var strEditCustomName=document.getElementById('CustomName_Edit_'+strNodeID).value;
			
			$.get("<?php echo URL?>ajax_pages/system_node_mapping.php",
			{
				EditNodeID:strNodeID,
				EditCustomName:strEditCustomName,
				mode:'update',
			},
				function(data,status){							
					document.getElementById('CustomName_'+strNodeID).innerHTML=strEditCustomName;
					document.getElementById('CustomName_Control_'+strNodeID).innerHTML='<a href="javascript:EditNodeCustomName('+strNodeID+')">Edit</a>';
			 });
					
			
		}
		
	</script>
	
    <div style="font-size:14px; float:left; font-weight:bold;">Add New Node</div>
    <div style="float:left; margin-left:20px;">
    	<select name="ddlAvailableNodes" id="ddlAvailableNodes" style="width:250px;" >
        	<option value="">Select One from Available Node</option>
        <?php
        	$strSQL="Select available_system_nodes_id, node_serial from t_available_system_nodes where linked_with_system_flag=0 and linked_with_building_flag=0";
			$strRsAvialableSystemsArr=$DB->Returns($strSQL);
			while($strRsAvialableSystems=mysql_fetch_object($strRsAvialableSystemsArr))
			{
				print "<option value='".$strRsAvialableSystems->node_serial."'>".$strRsAvialableSystems->node_serial."</option>";
			}
		?>
        </select>
    
    </div>
    <div class="clear"></div>
    
	<div style="margin-top:5px;">
    	<div style="float:left; width:90px; margin-top:4px;">Node Serial</div>
        <div style="float:left; margin-left:3px;"><input style="width:120px;" type="text" readonly="readonly" name="txtnode_serial_number" id="txtnode_serial_number" value="<?php echo $SerialNumber;?>" /></div>
        <div style="float:left; width:100px; margin-left:20px; margin-top:4px;">Custom Name</div>
        <div style="float:left; margin-left:3px;"><input style="width:200px;" type="text" name="txtnode_custom_name" id="txtnode_custom_name" /></div>
        <div style="float:left; margin-left:5px; cursor:pointer;" id="LinkSystemNodeButton" onclick="LinkSystemNode()"><img src="<?php echo URL?>images/link.png" alt="Link" title="Link" border="0" /></div>
        <div class="clear"></div>     
    </div>
    
    <?php
    	$strSQL="Select * from t_system_node where system_id=$strSystemID and client_id=0 and delete_flag=0 order by system_node_id";
		$strRsSystemNodeSerialArr=$DB->Returns($strSQL);
		$iCtr=0;
	?>
    
    
    <style type="text/css">
		#NodeListTable td
		{
			border:1px solid #EFEFEF;
		}
	</style>
    <hr style="border-bottom:1px dashed #CCCCCC; margin:5px 0px;" />
	
    <div style=" height:280px; overflow:auto;">
    <?php if(mysql_num_rows($strRsSystemNodeSerialArr)>0){?>
    <b style="font-size:14px;">Available Linked Nodes</b>
    <table id="NodeListTable" width="100%" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;">
      <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
        <td width="29%">Node Serial</td>
        <td width="36%">Linked with</td>
        <td width="25%">Date Created</td>
        <td width="10%">Delete</td>
      </tr>
      
      <?php 
	  	while($strRsSystemNodeSerial=mysql_fetch_object($strRsSystemNodeSerialArr))
	  	{ 
	  		$iCtr ++;
			if($iCtr % 2 ==1)
				$strRowClass="OddRow";
			else
				$strRowClass="EvenRow";
	 ?>
       <tr class="<?php echo $strRowClass;?>">
        <td>
			<?php echo $strRsSystemNodeSerial->node_serial;?><br />
            <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsSystemNodeSerial->system_node_id;?>"><?php echo $strRsSystemNodeSerial->custom_name;?></div>
            <div id="CustomName_Control_<?php echo $strRsSystemNodeSerial->system_node_id;?>" style="float:right;"><a href="javascript:EditNodeCustomName('<?php echo $strRsSystemNodeSerial->system_node_id;?>')">Edit</a></div>
            <div class="clear"></div>
		</td>
        <td>
			<?php echo $strRsSystemNodeSerial->available_system_node_serial;?>
        </td>
        <td><?php echo Globals::DateFormat($strRsSystemNodeSerial->doc,1);?></td>
        <td><a href="javascript:DeleteSystemNode('<?php echo $strRsSystemNodeSerial->system_node_id;?>','<?php echo $strSystemID?>')">Delete</a></td>
      </tr>
      <?php }?>
    </table>
	<?php }?>
    </div>
    
    
	<input type="hidden" name="txt_system_id" id="txt_system_id" value="<?php echo $strSystemID?>" />
	<?php
	print "</div>";
	?>

<?php 
}

?>