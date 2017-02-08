<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

$project_details_id=$_GET['id'];

$strSQL="Select * from t_project_details where project_details_id=$project_details_id";
$strRsProjectDetailsArr=$DB->Returns($strSQL);
if($strRsProjectDetails=mysql_fetch_object($strRsProjectDetailsArr))
{
	$json=$strRsProjectDetails->json_data;
	//$json=json_encode($arrJson);
	
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
