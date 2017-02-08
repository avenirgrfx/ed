<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

$strSQL="Select * from t_project_details where project_details_id>0 order by project_details_id desc";
$strRsProjectDetailsArr=$DB->Returns($strSQL);
if($strRsProjectDetails=mysql_fetch_object($strRsProjectDetailsArr))
{
	$json=$strRsProjectDetails->json_data;
	$arrJson=json_decode($json);
	$arrJsonTemp=array();
	$arrJsonTemp=$arrJson;
	
	$iCtrArr=0;
	foreach($arrJson as $Arr1)
	{
		if($arrJsonTemp->objects[$iCtrArr]->is_widget==1)
		{		
			$arrWidgetTemp=array();
			$strWidgetValTemp='';
			$strWidgetSerialTemp='';
			$strWidgetValIndex=0;
			$strWidgetSerialIndex=0;
			
			
			$WidgetObjectsCount=count($arrJsonTemp->objects[$iCtrArr]->objects);
			for($iCtrTemp=0; $iCtrTemp<$WidgetObjectsCount; $iCtrTemp++)
			{				
				if($arrJsonTemp->objects[$iCtrArr]->objects[$iCtrTemp]->widget_serial==1)
				{
					$strWidgetSerialTemp=$arrJsonTemp->objects[$iCtrArr]->objects[$iCtrTemp]->text;
					$strWidgetSerialIndex=$iCtrTemp;
				}
				if($arrJsonTemp->objects[$iCtrArr]->objects[$iCtrTemp]->widget_value1==1)
				{
					$strWidgetValTemp=$arrJsonTemp->objects[$iCtrArr]->objects[$iCtrTemp]->text;
					$strWidgetValIndex=$iCtrTemp;
				}		
			}
			
			$strSQL="Select * from t_thn_widget where widget_serial_number='$strWidgetSerialTemp'";
			$strRsWidgetDetailsArr=$DB->Returns($strSQL);
			while($strRsWidgetDetails=mysql_fetch_object($strRsWidgetDetailsArr))
			{
				$arrJsonTemp->objects[$iCtrArr]->objects[$strWidgetValIndex]->text=$strRsWidgetDetails->temperature_alarm_low;
			}					
		}
		
		$iCtrArr++;
	}
	
	
	$arrJson=$arrJsonTemp;
	$json=json_encode($arrJson);
	
}
?>

<canvas id="canvas" width="800" height="500"></canvas>
<div id="RefreshWorkspace" style="position:absolute; width:800px; height:500px; top:0px; left:0px; opacity: 0.6; filter: alpha(opacity=60); background-color:#FFFFFF; display:none; text-align:center;">
	Loading...
    <img src="<?php echo URL?>/images/workspace-preloader.GIF" />
</div>

<script type="text/javascript">
var kitchensink = { };
var canvas = new fabric.Canvas('canvas');
var json='<?php echo $json; ?>';
canvas.loadFromJSON(json,canvas.renderAll.bind(canvas));
</script>
