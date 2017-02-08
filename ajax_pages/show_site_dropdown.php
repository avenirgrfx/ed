<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/projects.class.php");
require_once(AbsPath.'classes/system.class.php');
$DB=new DB;
$Project=new Project;
$System=new System;

if($_GET['id']<>"")
{
	?>    
    <script type="text/javascript">
	function LoadBuildingForController(strSiteID)
	{
		 $.get("<?php echo URL?>ajax_pages/show_site_dropdown.php",
		  {
			site_id:strSiteID				
		  },
		  function(data,status){						
				$('#ddlClientBuildingForController').html(data);				
		  });
	}	
	
	</script>
    
    <?php
	# For List of Sites
	$strSQL="Select * from t_sites where client_id=".$_GET['id'];
	$strRsClientSitesArr=$DB->Returns($strSQL);
	print "<select name='ddlSiteForController_".$_GET['id']."' id='ddlSiteForController_".$_GET['id']."' onchange='LoadBuildingForController(this.value)'>";
	print "<option value=''>Select Site</option>";
	while($strRsClientSites=mysql_fetch_object($strRsClientSitesArr))
	{
		print "<option value='".$strRsClientSites->site_id."'>".$strRsClientSites->site_name."</option>";	
	}
	print "</select>";
	
	
}
elseif($_GET['site_id']<>"")
{
?>
	<script type="text/javascript">
	function LoadBuildingProjectForController(strBuildingID)
	{
		 $.get("<?php echo URL?>ajax_pages/show_site_dropdown.php",
		  {
			building_id:strBuildingID				
		  },
		  function(data,status){						
				$('#ddlClientBuildingProjectForController').html(data);				
		  });
	}
	</script>
<?php
	# For List of Building
	$strSQL="Select * from t_building where site_id=".$_GET['site_id'];
	$strRsBuildingSitesArr=$DB->Returns($strSQL);
	print "<select name='ddlBuildingForController_".$_GET['site_id']."' id='ddlBuildingForController_".$_GET['site_id']."' onchange='LoadBuildingProjectForController(this.value)'>";
	print "<option value=''>Select Building</option>";
	while($strRsBuildingSites=mysql_fetch_object($strRsBuildingSitesArr))
	{
		print "<option value='".$strRsBuildingSites->building_id."'>".$strRsBuildingSites->building_name."</option>";	
	}
	print "</select>";
}
elseif($_GET['building_id']<>"")
{?>

<script type="text/javascript">
$('#ddlBuildingProjectForController').change(function(){
	//alert( $('#ddlBuildingProjectForController').value() );
	//$('#ddlSystemForWorkspace').trigger('change');
});


function MapBuildingRoomProjectWidgets(strProjectID)
{	
	$('#BuildingRoomProjectWidgetList').html('...');
	$.get("<?php echo URL?>ajax_pages/show_site_dropdown.php",
	  {
		project_id:strProjectID			
	  },
	  function(data,status){
			$('#BuildingRoomProjectWidgetList').html(data);
			$('#ddlSystemForWorkspace').trigger('change');		
	  });
}
	
	

	
</script>

<?php
	# For List of Building
	$strSQL="Select * from t_building where site_id=".$_GET['building_id'];
	$strRsBuildingSitesArr=$DB->Returns($strSQL);
	print "<select name='ddlBuildingProjectForController' id='ddlBuildingProjectForController' onchange='MapBuildingRoomProjectWidgets(this.value)'>";
	$Project->ShowBuildingProjectWithRoom($_GET['building_id']);
	print "</select>";
}

elseif($_GET['project_id']<>"")
{?>
<script type="text/javascript">
function ShowWidgetListForBuildingProject(strWidgetID)
{	
	iWidgetOpen=0;
			
	if(!$('#ddlBuildingProjectForController').val())
	{
		alert("Please select a Project");
		return;
	}
	
	$('#WidgetDetailsByCategory').slideUp('fast');
	 $.get("<?php echo URL?>ajax_pages/project_widget_list.php",
	  {
		id:strWidgetID,
		project_id:$('#ddlBuildingProjectForController').val(),				
	  },
	  function(data,status){						
			$('#WidgetDetailsByCategory').html(data);
			$('#WidgetDetailsByCategory').slideDown('slow');
			iWidgetOpen=1;
			$('#Widget_Box_Click').html('- WIDGETS');
	  });
}
</script>
	<select id="ddlSystemForWorkspace" name="ddlSystemForWorkspace" onchange="ShowWidgetListForBuildingProject(this.value)">    	
        <?php /*$System->ListSystemForWidget($_GET['project_id']);*/ ?>
    </select>
<?php
}

?>