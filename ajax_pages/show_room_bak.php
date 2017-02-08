<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/widget_category.class.php");
require_once(AbsPath."classes/system.class.php");

$DB=new DB;
$WidgetCategory=new WidgetCategory;
$System=new System;

$strBuildingID=$_GET['id'];
$strSQL="Select * from t_room where building_id=$strBuildingID";
$strRsRoomArr=$DB->Returns($strSQL);
while($strRsRoom=mysql_fetch_object($strRsRoomArr))
{
	echo "
	<div class='room_folder' id='room_icon_".$strRsRoom->room_id."'>
	<div style='float:left; width:200px; cursor:pointer; margin-top:3px; text-decoration:underline; font-weight:bold;' onclick=ShowRoomNodeDetails('".$strRsRoom->room_id."')><span style='font-weight:normal;'>Room: </span>".$strRsRoom->room_name."</div>
	<div style='float:left; width:720px; text-align:center; padding:2px 5px;'>"
	?>    	
        <div style="float:left;"><select name='ddlMasterSystemList' id='ddlMasterSystemList_<?php echo $strRsRoom->room_id;?>' onchange="SubSystemList(this.value,<?php echo $strRsRoom->room_id;?>)"><?php $System->ListSystemForTree();?></select></div>
        <div style="float:left; margin-left:20px;" id='ddlSubSystemList_<?php echo $strRsRoom->room_id;?>'></div>
		<div class="clear"></div>
    <?php echo
	"</div>
	<div style='float:left;  text-align:center;  width:35px; margin-top:3px;' id='WidgetPrefix_".$strRsRoom->room_id."'></div>
	<div style='float:left; margin-left:10px;'><img src='".URL."images/link.png' /></div>
	<div style='float:left; margin-left:10px;  padding:2px 5px; margin-top:3px; cursor:pointer; border:1px solid #CCCCCC;' onclick=LinkUnitNode('".$strRsRoom->room_id."')>Link</div>
	<div class='clear'></div>
	</div>
	<div id='room_".$strRsRoom->room_id."'></div>";
}
?>
<div class='clear'></div>