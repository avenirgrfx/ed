<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");


class System
{
	public $system_id, $parent_id, $system_name, $display_type, $exclude_in_calculation, $uom, $complexity, $system_description, $level, $has_node, $strQuery;
	
	public function system()
	{
		$this->system_id=0;
		$this->parent_id=0;
		$this->system_name='';
		$this->display_type=0;
		$this->exclude_in_calculation=0;
		$this->uom='';
		$this->complexity='';
		$this->system_description='';
		$this->level=0;
		$this->has_node=0;
		$this->strQuery;
	}
	
	public function setVal($args)
	{
		$this->system_id=mysql_escape_string($args['system_id']);
		$this->parent_id=mysql_escape_string($args['parent_id']);
		$this->system_name=mysql_escape_string($args['system_name']);		
		$this->display_type=mysql_escape_string($args['display_type']);
		$this->exclude_in_calculation=mysql_escape_string($args['exclude_in_calculation']);
		$this->uom=mysql_escape_string($args['uom']);
		$this->complexity=mysql_escape_string($args['complexity']);
		$this->system_description=mysql_escape_string($args['system_description']);
		$this->level=mysql_escape_string($args['level']);	
		$this->has_widget=mysql_escape_string($args['has_node']);
	}
	
	
	public function NodeSerialPrefixGen($strSystemName, $ByName=0)
	{
		$DB=new DB;
		$Prefix='';
		$First='';
		$Second='';
		$Third='';
		
		if( $ByName ==0 )
		{
			$strSystemNameArr=explode(" ", trim($strSystemName));
			if(count($strSystemNameArr)>=3)
			{
				$First=strtoupper(substr($strSystemNameArr[0],0,1));
				$Second=strtoupper(substr($strSystemNameArr[1],0,1));
				$Third=strtoupper(substr($strSystemNameArr[2],0,1));
				$Prefix=$First."".$Second."".$Third;
			}
		}
		else
		{
			$Prefix=Globals::RandomString_1(3);
		}
		
		
		if($Prefix=="")
		{
			$Prefix=$this->NodeSerialPrefixGen($strSystemName, 1);
		}
		
		$strSQL="Select * from t_system where prefix='$Prefix'";
		$strRsPrefixCheckArr=$DB->Returns($strSQL);
		if($strRsPrefixCheck=mysql_fetch_object($strRsPrefixCheckArr))
		{
			$Prefix=$this->NodeSerialPrefixGen($strSystemName, 1);
		}
	
		return $Prefix;
	}
	
	public function Insert()
	{
		$DB = new DB;
		$this->level=1;
		if($this->parent_id<>0)
		{			
			$this->strQuery="Select parent_id from t_system where system_id=".$this->parent_id;
			$strRsFirstLevelArr=$DB->Returns($this->strQuery);
			if($strRsFirstLevel=mysql_fetch_object($strRsFirstLevelArr))
			{
				$this->level++; // Level 2
				$this->strQuery="Select parent_id from t_system where system_id=".$strRsFirstLevel->parent_id;
				$strRsSecondLevelArr=$DB->Returns($this->strQuery);
				if($strRsSecondLevel=mysql_fetch_object($strRsSecondLevelArr))
				{
					$this->level++; // Level 3
					$this->strQuery="Select parent_id from t_system where system_id=".$strRsSecondLevel->parent_id;
					$strRsThirdLevelArr=$DB->Returns($this->strQuery);
					if($strRsThirdLevel=mysql_fetch_object($strRsThirdLevelArr))
					{
						$this->level++; // Level 4
					}
				}
			}
		}
		
		
		$Prefix='';
		if($this->level==4)
		{
			$this->has_node=1;
			$Prefix=$this->NodeSerialPrefixGen($this->system_name);
		}
		else
		{
			$this->has_node=0;
		}
		
		
		
		$this->strQuery="Insert into t_system (parent_id,system_name, system_description, level, has_node, display_type, exclude_in_calculation, uom, complexity, prefix)
		Values (".$this->parent_id.",'".$this->system_name."','".$this->system_description."', ".$this->level.", ".$this->has_node.", ".$this->display_type.",".$this->exclude_in_calculation.",'".$this->uom."','".$this->complexity."','".$Prefix."')";		
		$DB->Execute($this->strQuery);
		
	}
	
	public function Update()
	{
		$DB = new DB;
		
		$this->level=1;
		if($this->parent_id<>0)
		{			
			$this->strQuery="Select parent_id from t_system where system_id=".$this->parent_id;
			$strRsFirstLevelArr=$DB->Returns($this->strQuery);
			if($strRsFirstLevel=mysql_fetch_object($strRsFirstLevelArr))
			{
				$this->level++; // Level 2
				$this->strQuery="Select parent_id from t_system where system_id=".$strRsFirstLevel->parent_id;
				$strRsSecondLevelArr=$DB->Returns($this->strQuery);
				if($strRsSecondLevel=mysql_fetch_object($strRsSecondLevelArr))
				{
					$this->level++; // Level 3
					$this->strQuery="Select parent_id from t_system where system_id=".$strRsSecondLevel->parent_id;
					$strRsThirdLevelArr=$DB->Returns($this->strQuery);
					if($strRsThirdLevel=mysql_fetch_object($strRsThirdLevelArr))
					{
						$this->level++; // Level 4
					}
				}
			}
		}
		
        $Prefix='';
		if($this->level==4)
		{
			$this->has_node=1;
			
			$strSQL="Select * from t_system where system_id=".$this->system_id." and prefix<>''";			
			$strRsPrefixCheckArr=$DB->Returns($strSQL);
			if($strRsPrefixCheck=mysql_fetch_object($strRsPrefixCheckArr))
			{
				$Prefix=$strRsPrefixCheck->prefix;
			}
			else
			{
				
				$Prefix=$this->NodeSerialPrefixGen($this->system_name);
				
			}
		}
		else
		{
			$this->has_node=0;
		}
		
		$this->strQuery="Update t_system  set parent_id=".$this->parent_id.", system_name='".$this->system_name."', level=".$this->level.", has_node=".$this->has_node.", display_type=".$this->display_type.", exclude_in_calculation=".$this->exclude_in_calculation.", uom='".$this->uom."', complexity='".$this->complexity."', prefix='$Prefix' where system_id=".$this->system_id;
		
		$DB->Execute($this->strQuery);		
	}
	
	public function ListSystem()
	{
		print '<option value="0">Select System</option>';
		$DB=new DB;
		$strSQL="Select * from t_system where parent_id=0 order  by system_name asc";	
		$strRssystemArr=$DB->Returns($strSQL);		
		while($strRssystem=mysql_fetch_object($strRssystemArr))
		{
			print '<option value="'.$strRssystem->system_id.'">'.$strRssystem->system_name.'</option>';
			$strSQL="Select * from t_system where parent_id=".$strRssystem->system_id."  order  by system_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				print '<option value="'.$strRsSubCat1->system_id.'">&nbsp;&nbsp;=>'.$strRsSubCat1->system_name.'</option>';
				$strSQL="Select * from t_system where parent_id=".$strRsSubCat1->system_id."  order  by system_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					print '<option value="'.$strRsSubCat2->system_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;->'.$strRsSubCat2->system_name.'</option>';
				}
				
			}
		}
	}
    
    public function ListSystems($selected="")
	{
		print '<option value="0">Select System</option>';
		$DB=new DB;
		$strSQL="Select * from t_system where parent_id=0 order by system_name asc";	
		$strRssystemArr=$DB->Returns($strSQL);		
		while($strRssystem=mysql_fetch_object($strRssystemArr))
		{  
            if($selected==$strRssystem->system_id)
               print '<option value="'.$strRssystem->system_id.'"'.'selected="selected"'.'>'.$strRssystem->system_name.'</option>';
            else
               print '<option value="'.$strRssystem->system_id.'">'.$strRssystem->system_name.'</option>';
            
        }
	}
    
    
    public function ListSystemForGallary()
	{
		print '<option value="0">Select System</option>';
		$DB=new DB;
		$strSQL="Select * from t_system where parent_id=0 order  by system_name asc";	
		$strRssystemArr=$DB->Returns($strSQL);		
		while($strRssystem=mysql_fetch_object($strRssystemArr))
		{
			print '<optgroup label="'.$strRssystem->system_name.'">';
            $strSQL="Select * from t_system where parent_id=".$strRssystem->system_id."  order  by system_name asc";	
            $strRsSubCat1Arr=$DB->Returns($strSQL);
            while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
            {
                print '<optgroup label="&nbsp;&nbsp;->'.$strRsSubCat1->system_name.'">';
				$strSQL="Select * from t_system where has_gallery = 1 AND parent_id=".$strRsSubCat1->system_id."  order  by system_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					print '<option value="'.$strRsSubCat2->system_id.'">&nbsp;&nbsp;'.$strRsSubCat2->system_name.'</option>';
				}
                print '</optgroup>';
            }
            print '</optgroup>';
		}
	}
	
    public function ListSystemForEquipments()
	{
		print '<option value="0">Select System</option>';
		$DB=new DB;
		$strSQL="Select * from t_system where parent_id=0 order by system_name asc";	
		$strRssystemArr=$DB->Returns($strSQL);		
		while($strRssystem=mysql_fetch_object($strRssystemArr))
		{
			print '<optgroup label="'.$strRssystem->system_name.'">';
            $strSQL="Select * from t_system where parent_id=".$strRssystem->system_id." order  by system_name asc";	
            $strRsSubCat1Arr=$DB->Returns($strSQL);
            while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
            {
                print '<option value="'.$strRsSubCat1->system_id.'" >'.$strRsSubCat1->system_name.'</option>';               
            }
            print '</optgroup>';
		}
	}
    
//     public function ListSystemForEquipmentsSupplyNDemand()
//	{
//		print '<option value="0">Select System</option>';
//		$DB=new DB;
//		$strSQL="Select * from t_system where parent_id=0 order by system_name asc";	
//		$strRssystemArr=$DB->Returns($strSQL);		
//		while($strRssystem=mysql_fetch_object($strRssystemArr))
//		{
//			print '<optgroup label="'.$strRssystem->system_name.'">';
//            $strSQL="Select * from t_system where parent_id=".$strRssystem->system_id." order  by system_name asc";	
//            $strRsSubCat1Arr=$DB->Returns($strSQL);
//            while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
//            {
//                print '<option value="'.$strRsSubCat1->system_id."`#`"."Supply".'" >'.$strRsSubCat1->system_name."- Supply".'</option>';
//                print '<option value="'.$strRsSubCat1->system_id."`#`"."Demand".'" >'.$strRsSubCat1->system_name."- Demand".'</option>';
//            }
//            print '</optgroup>';
//		}
//	}
    
	public function ListSystemForWidget($strProjectID=0)
	{
		if($strProjectID==0)
		{		
			print '<option value="0">Select System</option>';
			$DB=new DB;
			$strSQL="Select * from t_system where parent_id=0 order  by system_name asc";	
			$strRssystemArr=$DB->Returns($strSQL);		
			while($strRssystem=mysql_fetch_object($strRssystemArr))
			{
				print '<option value="'.$strRssystem->system_id.'">'.$strRssystem->system_name.'</option>';
				$strSQL="Select * from t_system where parent_id=".$strRssystem->system_id."  order  by system_name asc";	
				$strRsSubCat1Arr=$DB->Returns($strSQL);
				while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
				{
					print '<option value="'.$strRsSubCat1->system_id.'">&nbsp;&nbsp;=>'.$strRsSubCat1->system_name.'</option>';
					$strSQL="Select * from t_system where parent_id=".$strRsSubCat1->system_id."  order  by system_name asc";	
					$strRsSubCat2Arr=$DB->Returns($strSQL);
					while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
					{
						print '<option value="'.$strRsSubCat2->system_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;->'.$strRsSubCat2->system_name.'</option>';
						$strSQL="Select * from t_system where parent_id=".$strRsSubCat2->system_id."  order  by system_name asc";	
						$strRsSubCat3Arr=$DB->Returns($strSQL);
						while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
						{
							print '<option value="'.$strRsSubCat3->system_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;->'.$strRsSubCat3->system_name.'</option>';
						}
					}
					
				}
			}
		}
		else
		{
			print '<option value="0">Select System</option>';
			$DB=new DB;
			$strSQL="Select Distinct(t_system.system_name), t_system.* from t_system,t_system_node  where t_system.system_id=t_system_node.system_id and t_system_node.project_id=$strProjectID order by system_name asc";	
			$strRssystemArr=$DB->Returns($strSQL);		
			while($strRssystem=mysql_fetch_object($strRssystemArr))
			{
				print '<option value="'.$strRssystem->system_id.'">'.$strRssystem->system_name.'</option>';
			}
		}
		
	}
	
	

	
	
	
	public function ListSystemForTree()
	{
		print '<option value="0">Select System</option>';
		$DB=new DB;
		$strSQL="Select * from t_system where parent_id=0 order  by system_name asc";	
		$strRssystemArr=$DB->Returns($strSQL);		
		while($strRssystem=mysql_fetch_object($strRssystemArr))
		{
			print '<optgroup label="'.$strRssystem->system_name.'">';
			$strSQL="Select * from t_system where parent_id=".$strRssystem->system_id."  order  by system_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				print '<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;'.$strRsSubCat1->system_name.'">';
				$strSQL="Select * from t_system where parent_id=".$strRsSubCat1->system_id."  order  by system_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					print '<option value="'.$strRsSubCat2->system_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;->'.$strRsSubCat2->system_name.'</option>';
				}
				print '</optgroup>';
				
			}
			print '</optgroup>';
		}
	}
	
	public function ListParentSystem()
	{
		print '<option value="0">Select System</option>';
		$DB=new DB;
		$strSQL="Select * from t_system where parent_id=0  order  by system_id asc";	
		$strRssystemArr=$DB->Returns($strSQL);		
		while($strRssystem=mysql_fetch_object($strRssystemArr))
		{
			print '<option value="'.$strRssystem->system_id.'">'.$strRssystem->system_name.'</option>';
		}
	}
	
}

?>

