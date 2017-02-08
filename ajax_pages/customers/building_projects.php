<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath."classes/all.php");

$DB=new DB;
$buildingID=$_GET['building_id'];
$strSQL="Select * from t_projects where building_id=".$buildingID." and room_id=0";
$strRsBuildingProjectsArr=$DB->Returns($strSQL);
$roomSectionID = $buildingID +500;
//print '<div style="font-weight:bold; border-top: 1px solid rgb(153, 153, 153);padding-top: 4px; margin-top: 10px;">Active Building Projects</div>';
echo '<div onclick="Expand_Collapse_System_Node_For_Building('.$buildingID.')" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">                                            <span class="System_ID_Expand_'.$buildingID.'">+</span>Building Projects
                                             </div>';
print '<div class="System_ID_'.$buildingID.' " style="margin-left:45px;font-style:italic; display:none;">';
	if(mysql_num_rows($strRsBuildingProjectsArr)>0)
	{
		
		print '<span>Active Projects </span>';
	}
	else
	{
		print '<span>No Project </span>';
	}
	while($strRsBuildingProjects=mysql_fetch_object($strRsBuildingProjectsArr))
	{
		print '<div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">'.$strRsBuildingProjects->project_name.'</div>';
	}
	echo "</div>";
	//echo '<div class="System_ID_268 System_ID_Sub_267" style="margin-left:45px;font-style:italic;">
//		             <span>Room Projects </span>';
//	echo '</div>';
echo '<div onclick="Expand_Collapse_System_Node_For_Building('.$roomSectionID.')" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">                                            <span class="System_ID_Expand_'.$roomSectionID.'">+</span>Room Projects
                                             </div>';
	
	$strSQL2="Select * from t_room where building_id=".$buildingID."";
	$strRsRoomArr=$DB->Returns($strSQL2);
	while($strRsRoom=mysql_fetch_object($strRsRoomArr))
	{
		echo '<div class="System_ID_'.$roomSectionID.'" onclick="Expand_Collapse_System_Node_For_Building('.$strRsRoom->room_id.')" style="margin-left:30px;display:none;cursor:pointer; color:#0088cc;">
		               <span class="System_ID_Expand_'.$strRsRoom->room_id.'">+</span>'.$strRsRoom->room_name.' 
              </div>
              <div class="System_ID_'.$strRsRoom->room_id.' System_ID_Sub_'.$roomSectionID.'" style="margin-left:45px; display:none; font-style:italic;">
                       ';
					   
	$strSQL3="Select * from t_projects where room_id=".$strRsRoom->room_id;
	$strRsRoomProjectArr=$DB->Returns($strSQL3);
	if(mysql_num_rows($strRsRoomProjectArr)>0)
	{
		echo '<span>Active Projects</span>';
	}
	else
	{
		echo '<span>No Project</span>';
	}
	while($strRsRoomProject=mysql_fetch_object($strRsRoomProjectArr))
	{		   
                  echo '<div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">                               '.$strRsRoomProject->project_name.'
                        </div>';
	}
                       
        echo '</div>';
	}
	

?>