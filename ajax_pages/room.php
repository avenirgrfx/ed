<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
require_once(AbsPath."classes/building.class.php");
$Building=new Building;

$DB=new DB;

if(Globals::Get('client_id')<>'')
{
	$client_id=Globals::Get('client_id');
}

if(Globals::Get('site_id')<>'')
{
	$site_id=Globals::Get('site_id');
}

if(Globals::Get('building_id')<>'')
{
	$building_id=Globals::Get('site_id');
}

if(Globals::Get('room_id')<>'' and Globals::Get('mode')=='')
{
	$room_id=Globals::Get('room_id');
	$strSQL="Select * from t_room where room_id=$room_id";
	$strRsRoomDetailsArr=$DB->Returns($strSQL);
	if($strRsRoomDetails=mysql_fetch_object($strRsRoomDetailsArr))
	{		
		$room_name=$strRsRoomDetails->room_name;
		$building_id=$strRsRoomDetails->building_id;	
	}
}
elseif(Globals::Get('room_id')<>'' and Globals::Get('mode')=='delete' and Globals::Get('client_id'))
{
	$strSQL="Delete from t_room where room_id=".Globals::Get('room_id');
	$DB->Execute($strSQL);
	?>
    
     <script type="text/javascript">
		$(document).ready(function(){
			ProjectDetails('<?php echo Globals::Get('client_id');?>');
		});
	</script>  
    
    <?php
}
?>

<?php
if($_POST)
{
	if($_POST['room_id']=='')
	{
		$strSQL="Insert into t_room(client_id, building_id, room_name, doc, dom) Values(".$_POST['client_id'].",".$_POST['ddlBuildingName'].",'".$_POST['txtRoom']."', now(), now())";
		$DB->Execute($strSQL);
	}
	else
	{
		$strSQL="Update t_room set  building_id=".$_POST['ddlBuildingName'].", room_name='".$_POST['txtRoom']."', dom=now() where room_id=".$_POST['room_id'];
		$DB->Execute($strSQL);
	}
	print '<div style="font-family:Arial, Helvetica, sans-serif; color:#006600; margin:45px 0px 0px 0px; font-size:18px;">
		Successfully Updated!</div>';
	?>
    
 <script type="text/javascript">
		$(document).ready(function(){
			ProjectDetails('<?php echo $_POST['client_id'];?>');
		});
</script>  
    
    <?php
	exit();
}

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#cmdClose').click(function(){
		$('#Building_Container').slideUp();
	});
	
	$('#cmdSubmit').click(function(){
	
		$.post("ajax_pages/room.php",
		{
			ddlBuildingName:$('#ddlBuildingName').val(),			
			txtBuildingName:$('#txtBuildingName').val(),			
			txtRoom:$('#txtRoom').val(),			
			client_id:$('#client_id').val(),			
			room_id:$('#room_id').val()			
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	
	});	
	
});


function ValidBuilding()
{
	var frm=document.frmBuilding;
	if(frm.ddlBuildingName.value=="")
	{
		alert("Select building name");
		frm.ddlBuildingName.focus();
		return false;
	}
	else if(frm.txtRoom.value=="")
	{
		alert("Enter Room Name");
		frm.txtRoom.focus();
		return false;
	}
	
	return true;
}


</script>

<form id="frmBuilding" name="frmBuilding" method="post" action="" onsubmit="return ValidBuilding();">

<table width="100%" border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td><strong>Add Room</strong></td>
  </tr>
  <tr>
    <td width="26%">
    <select name="ddlBuildingName" id="ddlBuildingName"><?php $Building->FetchBuildingSites($client_id, $building_id)?></select></td>
    </tr>
  
  
  <tr>
    <td>
      <input type="text" name="txtRoom" id="txtRoom" placeholder="Room" class="TextBox" value="<?php echo $room_name;?>" />    </td>
    </tr>
  
  <tr>
    <td><input type="button" name="cmdSubmit" id="cmdSubmit" value="Save Room Data" class="Button" style="font-weight:bold; padding:3px;" />
      <input type="button" name="cmdClose" id="cmdClose" value="Close" style="font-weight:bold; padding:3px;" /></td>
  </tr>
</table>
  <input name="client_id" type="hidden" id="client_id" value="<?php echo $client_id;?>" />
  <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id?>" />
</form>