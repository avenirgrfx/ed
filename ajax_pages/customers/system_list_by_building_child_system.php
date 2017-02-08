<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$building_id=$_GET['building_id'];
$parent_id=$_GET['parent_id'];

if($building_id=="" or $building_id==0)
	exit();



$level1Arr=array();
$level2Arr=array();
$level3Arr=array();
$level4Arr=array();

/*
$strSQL="select Distinct parent_parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{
	$level1Arr[]=$strRsLevel1->parent_parent_parent_id;
}

$strSQL="select Distinct parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{
	$level2Arr[]=$strRsLevel1->parent_parent_id;
}

$strSQL="select Distinct parent_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{
	$level3Arr[]=$strRsLevel1->parent_id;
}

$strSQL="select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{
	$level4Arr[]=$strRsLevel1->system_id;
}

print "<pre>";
print_r($level1Arr);
print "</pre>";
*/

$strSQL="Select * from t_system where system_id in (select Distinct parent_parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id)";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{			
	print "<div style='font-weight:bold;'>".$strRsLevel1->system_name."</div>";
	$strSQL="Select * from t_system where system_id in(select Distinct parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id) and parent_id=".$strRsLevel1->system_id;
	$strRsLevel2Arr=$DB->Returns($strSQL);
	while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
	{						
		print "<div style='margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold; ' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel2->system_id.")'><span class='System_ID_Expand_".$strRsLevel2->system_id."'>+</span>".$strRsLevel2->system_name."</div>";
		
		$strSQL="Select * from t_system where system_id in(select Distinct parent_id from t_system_node where delete_flag=0 and building_id=$building_id) and parent_id=".$strRsLevel2->system_id;
		$strRsLevel3Arr=$DB->Returns($strSQL);
		while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
		{									
			print "<div style='margin-left:30px; display:none; cursor:pointer; color:#0088cc;' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel3->system_id.")' class='System_ID_".$strRsLevel2->system_id."'><span class='System_ID_Expand_".$strRsLevel3->system_id."'>+</span>".$strRsLevel3->system_name."</div>";									
			
			$strSQL="Select * from t_system where system_id in(select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id) and parent_id=".$strRsLevel3->system_id;
			$strRsLevel4Arr=$DB->Returns($strSQL);
			while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
			{												
				print "<div style='margin-left:45px; display:none; font-style:italic;' class='System_ID_".$strRsLevel3->system_id." System_ID_Sub_".$strRsLevel2->system_id."'><span>".$strRsLevel4->system_name."</span>";
				$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
				$strRsSystemNodesArr=$DB->Returns($strSQL);
				$iCtr=0;
				while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
				{
					$iCtr++;
					if($iCtr % 2==0)
						$bgColor='#FFFFFF';
					else
						$bgColor='#EFEFEF';
					print "<div style='margin-left:10px; font-style:normal; text-decoration:none;  color:#0088cc; text-decoration:underline; background-color:".$bgColor."'>".($strRsSystemNodes->custom_name=='' ? $strRsSystemNodes->node_serial : $strRsSystemNodes->custom_name." (".$strRsSystemNodes->node_serial.")")."</div>";
				}
				print "</div>";
			}
		}					
	}
	
}
?>