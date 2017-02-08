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

$strSQL="Select * from t_category where parent_id = 0";	
$strCategoryArr=$DB->Returns($strSQL);

if($DeleteSystemNodeID<>'' and $_GET['mode']=='delete')
{
	$strSQL="Update t_system_node set delete_flag=1 where system_node_id=$DeleteSystemNodeID";
	$DB->Execute($strSQL);
	print "Done";
}

if($_GET['EditNodeID'] <>"" and $_GET['EditCustomName']<>"" and $_GET['mode']=='update')
{
	$strSQL="Update t_system_node set custom_name='".$_GET['EditCustomName']."',description='".$_GET['EditDescription']."' where system_node_id=".$_GET['EditNodeID'];
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
    $description=mysql_escape_string($_POST['txtnode_Description_name']);
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
	
	$strSQL="Insert into t_system_node(system_id, year_of_creation, node_serial, custom_name,description,client_id, 
	site_id, building_id, room_id, project_id, doc, created_by, date_linked, linked_by, parent_id, parent_parent_id, parent_parent_parent_id, available_system_node_serial)
	Values($system_id, $year_of_creation, '$node_serial', '$custom_name','$description', $client_id, 
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
	


    $parent_id=$_GET['parent_id'];
    $strSQL="select system_name from t_system where system_id=".$parent_id;
    $strRsName=$DB->Returns($strSQL);
    if($strRsSystemName=mysql_fetch_object($strRsName))
    {
        $sys_name=$strRsSystemName->system_name;
    }
    $name=$sys_name;
    $string = split(" ", $name);
    $length=count($string);
    for($i=0;$i<=$length;$i++){
      $prefix=$prefix.$string[$i][0];
    }
    $strSQL="select count(prefix) as prefix from t_system where prefix='".$prefix."'";
    $prefixResult=$DB->Returns($strSQL);
    if($prefixResultARR = mysql_fetch_object($prefixResult)){
        $num_of_times = $prefixResultARR->prefix;
    }
      $x = 'A';
      $x=ord($x)+($num_of_times);
      $x=chr($x);
      $x=$x."A";
    
   	$strSQL="Select count(*) as SerialNumbers from t_system_node where system_id=$strSystemID and year_of_creation=".date("Y");
	// THN150001A to THN159999A then THN150001B and so on
	$strRsNodeSerialArr=$DB->Returns($strSQL);
    
	if($strRsNodeSerial=mysql_fetch_object($strRsNodeSerialArr))
	{
         
        
	$AvailableCount=$strRsNodeSerial->SerialNumbers;
        
    
    //echo $x;
    //chr($x[0]);
       
   $strSQL="select count(1) as count from t_system where parent_id=".$_GET['parent_parent_id']." and level=4";
   $num_of_fourth_levels=$DB->Returns($strSQL);
   $num_of_fourth_levelsARR = mysql_fetch_object($num_of_fourth_levels);
   $len= $num_of_fourth_levelsARR->count;
       for($i=1;$i<=$len-1;$i++){
         $x++;
         }
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
	
	$SerialNumber=$strRsSystems->prefix.$x.date("y").$SerialNumber;
    
	?>
    
    <script type="text/javascript">
		function LinkSystemNode()
		{
			
			var node_serial_number=document.getElementById('txtnode_serial_number').value;
			var node_custom_name=document.getElementById('txtnode_custom_name').value;
			var system_id=document.getElementById('txt_system_id').value;
            var parent_id = $("#txtnode_serial_number").attr("parent_id");
            var txtnode_Description_name=document.getElementById('txtnode_Description_name').value;
            var ddlCategory=document.getElementById('ddlCategory').value;
			//var ddlAvailableNodes=document.getElementById('ddlAvailableNodes').value;
			$.post("<?php echo URL?>ajax_pages/system_node_mapping.php",
			{
				txtnode_serial_number:node_serial_number,
				txtnode_custom_name:node_custom_name,
				txtsystem_id:system_id,	
                parent_id:parent_id,
				//ddlAvailableNodes:ddlAvailableNodes,
                ddlCategory:ddlCategory,
                txtnode_Description_name:txtnode_Description_name,
			},
				function(data,status){
				alert(data);				
				//LoadSystemNodeDetails(system_id)
                 LoadEquipmentNodeDetails(system_id,parent_id);
			 });
		}
		
		function DeleteSystemNode(DeleteSystemNodeID, strSystemID)
		{
            var parent_id = $("#txtnode_serial_number").attr("parent_id");
			if(!confirm("Are you sure you want to Delete?"))
				return;
			$.get("<?php echo URL?>ajax_pages/system_node_mapping.php",
			{
				DeleteSystemNodeID:DeleteSystemNodeID,
				mode:'delete',
			},
				function(data,status){							
				//LoadSystemNodeDetails(strSystemID)
                LoadEquipmentNodeDetails(strSystemID,parent_id);
			 });
		}
		
		function EditNodeCustomName(strNodeID)
		{
			var strNodeCustomeName=document.getElementById('CustomName_'+strNodeID).innerHTML;
			document.getElementById('CustomName_'+strNodeID).innerHTML='<input style="width:150px;" type="text" name="CustomName_Edit_'+strNodeID+'" id="CustomName_Edit_'+strNodeID+'" value="'+strNodeCustomeName+'" />';
		    var strNodeDescription=document.getElementById('Description_'+strNodeID).innerHTML;
			document.getElementById('Description_'+strNodeID).innerHTML='<input style="width:150px;" type="text" name="Description_Edit_'+strNodeID+'" id="Description_Edit_'+strNodeID+'" value="'+strNodeDescription+'" />';
		    document.getElementById('CustomName_Control_'+strNodeID).innerHTML='<a href="javascript:EditNodeCustomName_Cancel('+strNodeID+')">Cancel</a> | <a href="javascript:EditNodeCustomName_Update('+strNodeID+')">Update</a>';
		   
		
        }
		
		function EditNodeCustomName_Cancel(strNodeID)
		{
			document.getElementById('CustomName_'+strNodeID).innerHTML=document.getElementById('CustomName_Edit_'+strNodeID).value;
		    document.getElementById('Description_'+strNodeID).innerHTML=document.getElementById('Description_Edit_'+strNodeID).value;
            document.getElementById('CustomName_Control_'+strNodeID).innerHTML='<a href="javascript:EditNodeCustomName('+strNodeID+')">Edit</a>';
		 
			
		
    
        }
        
        
		
		function EditNodeCustomName_Update(strNodeID)
		{
			var strEditCustomName=document.getElementById('CustomName_Edit_'+strNodeID).value;
            var strEditDescription=document.getElementById('Description_Edit_'+strNodeID).value;
			
			$.get("<?php echo URL?>ajax_pages/system_node_mapping.php",
			{
				EditNodeID:strNodeID,
				EditCustomName:strEditCustomName,
                EditDescription:strEditDescription,
				mode:'update',
			},
				function(data,status){							
					document.getElementById('CustomName_'+strNodeID).innerHTML=strEditCustomName;
                    document.getElementById('Description_'+strNodeID).innerHTML=strEditDescription;
					document.getElementById('CustomName_Control_'+strNodeID).innerHTML='<a href="javascript:EditNodeCustomName('+strNodeID+')">Edit</a>';
			 });
					
			
		}
        
		
	</script>
	
  
         <div style=" width:900px;">
           <div style="float:left; width:90px; margin-top:4px;">Node Serial</div>    
           <div style="float:left; margin-left:3px;"><input style="width:120px;" type="text" readonly="readonly" name="txtnode_serial_number" id="txtnode_serial_number" value="<?php echo $SerialNumber;?>" parent_id="<?=$_GET['parent_id']?>" /></div>
           <div style="float:left; width:100px; margin-left:20px; margin-top:4px;">Custom Name</div>
           <div style="float:left; margin-left:3px;"><input style="width:362px;" type="text" name="txtnode_custom_name" id="txtnode_custom_name" /></div><br><br>
           <div class="clear"></div>
           <select id="ddlCategory" name="ddlCategory" style=" float:left; width:190px;">    	
             <option value="0">Select Category</option>
            <?php while($strCategory=mysql_fetch_object($strCategoryArr)) {
                print '<option value="'.$strCategory->category_id.'">'.$strCategory->category_name.'</option>';
            } ?>
           </select>
           <div style="float:left; width:190px; margin-left: 10px;">
              <input type="text" id="txtCategoryName" name="txtSystemName" placeholder="Add New Category"  style="width:130px;"/>
              <input type="button" style="float:right; padding: 2px 5px;" value="Add" name="btnAdd" id="btnAdd" onclick="AddNewCategory()">
           </div>
           <div style="float:left; width:100px; margin-left:20px; margin-top:4px;">Description</div>
           <div style="float:left; margin-left:3px;"><input style="width:200px;" type="text" name="txtnode_Description_name" id="txtnode_Description_name" /></div>
    	   <div style="margin-top:0px;">
    	      <input type="button" style="float:left; padding: 2px 5px; margin-left:10px;margin-bottom:1px;" value="Add Node" name="btnAdd" id="LinkSystemNodeButton" onclick="LinkSystemNode()">
              <div class="clear"></div>     
           </div>
         </div>

    <div class="clear"></div>
    
	
    
    <?php
    	//$strSQL="Select * from t_system_node where system_id=$strSystemID and client_id=0 and delete_flag=0 order by system_node_id";
		$strSQL="Select tsn.system_node_id,tsn.node_serial,tsn.custom_name,tsn.description,tsn.doc,tc.category_name from t_system_node tsn left join t_category tc on tsn.category_id=tc.category_id  where tsn.system_id=$strSystemID and tsn.client_id=0 and delete_flag=0 order by tsn.system_node_id";
        $strRsSystemNodeSerialArr=$DB->Returns($strSQL);
		$iCtr=0;
	?>
    
    
    <style type="text/css">
		#NodeListTable td
		{
			border:1px solid #EFEFEF;
		}
	</style>
  
	
    <div>
<!--          <hr style="border-bottom:1px dashed #CCCCCC; margin:5px 0px;" />-->
    <?php if(mysql_num_rows($strRsSystemNodeSerialArr)>0){?>
    <b style="font-size:14px;">Available Linked Nodes</b>
    <table id="NodeListTable" width="100%" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;">
      <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
        <td width="19%">Node Serial</td>
<!--        <td width="18%">Linked with</td>-->
        <td width="18%">Description</td>
        <td width="10%">Category</td>
        <td width="20%">Date Created</td>
        <td width="15%">Action</td>
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
            
            <div class="clear"></div>
		</td>
        
        <td><div style="float:left; font-style:italic;" id="Description_<?php echo $strRsSystemNodeSerial->system_node_id;?>"><?php echo  $strRsSystemNodeSerial->description?></div></td>
        <td><?php echo $strRsSystemNodeSerial->category_name?></td>
<!--        <td>
			<?php // echo $strRsSystemNodeSerial->available_system_node_serial;?>
        </td>-->
        <td><?php echo Globals::DateFormat($strRsSystemNodeSerial->doc,1);?></td>
        <td><div id="CustomName_Control_<?php echo $strRsSystemNodeSerial->system_node_id;?>" style="float:left;padding: 0 4px;"><a href="javascript:EditNodeCustomName('<?php echo $strRsSystemNodeSerial->system_node_id;?>')" style="padding-left:4px;">Edit</a></div>/<a href="javascript:DeleteSystemNode('<?php echo $strRsSystemNodeSerial->system_node_id;?>','<?php echo $strSystemID?>')" style="padding-left:4px;">Delete</a></td>
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