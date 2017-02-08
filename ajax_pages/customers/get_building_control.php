<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_id=$_GET['building_id'];
$strSQL="Select distinct(parent_parent_parent_id) from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsBuildingSystemsArr=$DB->Returns($strSQL);

$arrControl=array();

while($strRsBuildingSystems=mysql_fetch_object($strRsBuildingSystemsArr))
{
	//print $strRsBuildingSystems->parent_parent_parent_id."<br>";
	$strSQL="Select * from t_system  where system_id=".$strRsBuildingSystems->parent_parent_parent_id;
	$strRssystemNameArr=$DB->Returns($strSQL);
	
	
	while($strRssystemName=mysql_fetch_object($strRssystemNameArr))
	{
		$arrControl[]=strtoupper(trim($strRssystemName->system_name));
	}
}

$strSQL="Select * from t_controls";
$strRsControlsArr=$DB->Returns($strSQL);

?>



<div style="width:96%; border-radius:5px; border:1px solid #CCCCCC; margin-top:40px;">
    <div style="float:left; width:65%; margin-top:25px; margin-left:5%; ">
        <select name="ddlControls" id="ddlControls">
        	<?php while($strRsControls=mysql_fetch_object($strRsControlsArr)){?>
				<?php if(in_array($strRsControls->controls,$arrControl)){?>
                    <option value="<?php echo $strRsControls->controls;?>"><?php echo $strRsControls->controls;?></option>
                <?php }?>
            <?php }?>
            
            
        </select>
    </div>
    <div style="float:left; width:30%;">
    	<a class="mylink" href="#" target="_blank"><img src="<?php echo URL?>images/launch_button.png" border="0" /></a>
	</div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#ddlControls').change(function(){
			if(this.value=='AIR TURNOVERS')
			{
				$("a.mylink").attr("href", "<?php echo URL?>customer/air_turnover.php");
			}
			else
			{
				$("a.mylink").attr("href", "");
			}
		});
		
		if($('#ddlControls').val()=='AIR TURNOVERS')
		{
			$("a.mylink").attr("href", "<?php echo URL?>customer/air_turnover.php");
		}
		else
		{
			$("a.mylink").attr("href", "");
		}
		
		
	});
</script>

<div style="text-align:right; color:#666666; margin-right:20px; font-style:italic;">Launch detailed controls installed in building by Site</div>