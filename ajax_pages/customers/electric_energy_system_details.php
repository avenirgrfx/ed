<?php 
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;
$system_id=$_GET['SystemID'];
$building_id=$_GET['building_id'];
$strSQL="Select *  from t_system_node where delete_flag=0 and system_id=$system_id and building_id=$building_id";
$strRsSystemsArr=$DB->Returns($strSQL);
while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
{
?>
    <div style="float:left; width:5%; text-align:center; cursor:pointer;">&nbsp;</div>
    <div style="float:left; width:31%;" title="<?php echo $strRsSystems->custom_name . "(".$strRsSystems->node_serial.")" ; ?>"><?php echo ($strRsSystems->custom_name=='' ? $strRsSystems->node_serial : Globals::PrintDescription_1($strRsSystems->custom_name,15) ) ;?></div>
    <div style="float:left; width:15%;" class="system_on">ON</div>
    <div style="float:left; width:20%;  margin:0px 2%;">36%</div>
    <div style="float:left; width:20%;">44%</div>
    <div class="clear"></div>
<?php }?>