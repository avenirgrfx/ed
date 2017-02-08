<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");


class System
{
	public $system_id, $parent_id, $system_name, $system_description, $has_node, $strQuery;
	
	public function system()
	{
		$this->system_id=0;
		$this->parent_id=0;
		$this->system_name='';
		$this->system_description='';
		$this->has_node=0;
		$this->strQuery;
	}
	
	public function setVal($args)
	{
		$this->system_id=mysql_escape_string($args['system_id']);
		$this->parent_id=mysql_escape_string($args['parent_id']);
		$this->system_name=mysql_escape_string($args['system_name']);
		$this->has_widget=mysql_escape_string($args['has_node']);		
		$this->system_description=mysql_escape_string($args['system_description']);
	}
	
	public function Insert()
	{
		$DB = new DB;
		$this->strQuery="Insert into t_system (parent_id,system_name,system_description, has_node)
		Values (".$this->parent_id.",'".$this->system_name."','".$this->system_description."', ".$this->has_node.")";		
		$DB->Execute($this->strQuery);
		
	}
	
	public function Update()
	{
		$DB = new DB;
		$this->strQuery="Update t_system  set parent_id=".$this->parent_id.", system_name='".$this->system_name."', has_node=".$this->has_node." where system_id=".$this->system_id;
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