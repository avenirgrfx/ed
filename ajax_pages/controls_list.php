<?php
ob_start();
session_start();

require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

if($_POST)
{
	$control_name=$_POST['control_name'];
	if($control_name<>"")
	{
		$strSQL="Insert into t_controls (controls) values ('$control_name')";
		$DB->Execute($strSQL);
	}
	
	exit();
}

if($_GET['del_id']<>"")
{
	$strSQL="Delete from t_controls where control_id=".$_GET['del_id'];
	$DB->Execute($strSQL);
	exit();
}

$strSQL="Select Distinct system_name from t_system where parent_id=0 and system_name not in(select controls from t_controls) order by system_name";
$strRsSystemsArr=$DB->Returns($strSQL);

?>

<script type="text/javascript">
$(document).ready(function(){

	$('#btnAddControl').click(function(){
		
		  $.post("<?php echo URL?>ajax_pages/controls_list.php",
		  {
			 control_name:$('#ddlControlSystem').val(),
		  },
		  function(data,status){
				$('#Controls_Container_Post').html(data);
				$('#Controls_Main_Menu').trigger('click');
				//$("#ddlControlSystem option[value='"+data+"']").remove();
		  });
		
	});
	
});

function DeleteControl(strID)
{
	if(!confirm("Are you sure you want to Delete?"))
		return false;

	
	 $.get("<?php echo URL?>ajax_pages/controls_list.php",
	  {
		 del_id:strID,
	  },
	  function(data,status){
		$('#Controls_Main_Menu').trigger('click');
	  });
	
}

</script>


<div style="padding:5px; margin:20px 0px 0px 0px;">


<div style="float:left; width:500px;">

<?php
$strSQL="Select * from t_controls order by controls";
$strRsControlsArr=$DB->Returns($strSQL);
if(mysql_num_rows($strRsControlsArr)>0)
{
	print "<h2>Active Control Choices</h2>";
}

$iCtr=0;
while($strRsControls=mysql_fetch_object($strRsControlsArr))
{
	$iCtr++;
	
	if($iCtr % 2 ==0)
	{
		print "<div style='background-color:#EFEFEF; padding:5px;'>";
	}
	else
	{
		print "<div style='background-color:#CCCCCC; padding:5px;'>";
	}
	
	print "<div style='float:left; width:300px;'>".$strRsControls->controls."</div>";
	print "<div style='float:left;'><a href='javascript:DeleteControl(".$strRsControls->control_id.")'>Delete</a></div>";
	print "<div class='clear'></div>";
	print "</div>";
}
?>
</div>
<div style="float:left; margin-left:50px;">
<h2>Add Control Choices</h2>
<form name="frmControlSystem" id="frmControlSystem" action="" method="post">
	<select id="ddlControlSystem" name="ddlControlSystem">
    	<option value="">Select System</option>
    	<?php while($strRsSystems=mysql_fetch_object($strRsSystemsArr)){?>
    		<option value="<?php echo $strRsSystems->system_name;?>"><?php echo $strRsSystems->system_name;?></option>
        <?php }?>
    </select>
    
    <input type="button" id="btnAddControl" name="btnAddControl" value="Add" />
</form>
</div>

<div class="clear" style="border-bottom:1px solid #CCCCCC; height:5px;"></div>

<div id="Controls_Container_Post"></div>

</div>