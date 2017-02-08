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

global $level1Arr;
global $level2Arr;
global $level3Arr;
global $level4Arr;

function getParent($strChild)
{	
	global $level1Arr;
	global $level2Arr;
	global $level3Arr;
	global $level4Arr;
	
	$DB=new DB;
	$strSQL="Select parent_id, system_name, system_id, level from t_system where system_id=$strChild";
	$strRsGetParentIDArr=$DB->Returns($strSQL);
	while($strRsGetParentID=mysql_fetch_object($strRsGetParentIDArr))
	{		
		if($strRsGetParentID->level==1)
		{
			if(is_array($level1Arr) && count($level1Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level1Arr))
				{
					$level1Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level1Arr[]=$strRsGetParentID->system_id;
			}
		}
		elseif($strRsGetParentID->level==2)
		{
			 //$level2Arr[]=$strRsGetParentID->system_id;
			 
			if(is_array($level2Arr) && count($level2Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level2Arr))
				{
					$level2Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level2Arr[]=$strRsGetParentID->system_id;
			}
			 
		}
		elseif($strRsGetParentID->level==3)
		{
			// $level3Arr[]=$strRsGetParentID->system_id;
			if(is_array($level3Arr) && count($level3Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level3Arr))
				{
					$level3Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level3Arr[]=$strRsGetParentID->system_id;
			}
		}
		elseif($strRsGetParentID->level==4)
		{
			 //$level4Arr[]=$strRsGetParentID->system_id;
			if(is_array($level4Arr) && count($level4Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level4Arr))
				{
					$level4Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level4Arr[]=$strRsGetParentID->system_id;
			}
		}
		
		getParent($strRsGetParentID->parent_id);
	}
}

$strSQL="select Distinct system_id from t_system_node where building_id=$building_id";
$strRsSystemsArr=$DB->Returns($strSQL);
while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
{
	getParent($strRsSystems->system_id);
}



if(is_array($level1Arr) && count($level1Arr)>0)
{
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1";
		$strRsLevel1Arr=$DB->Returns($strSQL);
		while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
		{			
			print "<div style='font-weight:bold;'>".$strRsLevel1->system_name."</div>";
			
			if(is_array($level2Arr) && count($level2Arr)>0)
			{
				foreach($level2Arr as $val2)
				{		
					$strSQL="Select * from t_system where system_id=$val2 and parent_id=".$strRsLevel1->system_id;
					$strRsLevel2Arr=$DB->Returns($strSQL);
					while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
					{						
						print "<div style='margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold; ' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel2->system_id.")'><span class='System_ID_Expand_".$strRsLevel2->system_id."'>+</span>".$strRsLevel2->system_name."</div>";
						
						if(is_array($level3Arr) && count($level3Arr)>0)
						{
							foreach($level3Arr as $val3)
							{		
								$strSQL="Select * from t_system where system_id=$val3 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{									
									print "<div style='margin-left:30px; display:none; cursor:pointer; color:#0088cc;' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel3->system_id.")' class='System_ID_".$strRsLevel2->system_id."'><span class='System_ID_Expand_".$strRsLevel3->system_id."'>+</span>".$strRsLevel3->system_name."</div>";									
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										foreach($level4Arr as $val4)
										{		
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{												
												print "<div style='margin-left:45px; display:none; font-style:italic;' class='System_ID_".$strRsLevel3->system_id." System_ID_Sub_".$strRsLevel2->system_id."'><span>".$strRsLevel4->system_name."</span>";
												
												$strSQL="Select * from t_system_node where building_id=$building_id and system_id=".$strRsLevel4->system_id;
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
									//print "</div>";
									
								}
								
							}
						}
						
					}
					
				}
			}

			
		}
		
	}
}

?>