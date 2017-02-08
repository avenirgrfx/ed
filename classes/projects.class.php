<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");

class Project
{
	public $projects_id, $project_name, $client_id, $building_id, $room_id, $account_manager, $created_by, $modified_by, $doc, $dom, $delete_flag;
	public $strQuery;
	
	public function Projects()
	{
		$this->projects_id=0;
		$this->project_name='';
		$this->client_id=0;
		$this->building_id=0;
		$this->room_id=0;
		$this->account_manager=0;
		$this->created_by=0;
		$this->modified_by=0;
		$this->doc=date("Y-m-d 00:00:00");
		$this->dom=date("Y-m-d 00:00:00");
		$this->delete_flag=0;
		$this->strQuery='';
	}
	
	public function setVal($args)
	{
		$this->projects_id=mysql_escape_string($args['projects_id']);
		$this->project_name=mysql_escape_string($args['project_name']);
		$this->client_id=mysql_escape_string($args['client_id']);
		$this->building_id=mysql_escape_string($args['building_id']);
		$this->room_id=mysql_escape_string($args['room_id']);
		$this->account_manager=mysql_escape_string($args['account_manager']);
		$this->created_by=mysql_escape_string($args['created_by']);
		$this->modified_by=mysql_escape_string($args['modified_by']);
		$this->doc=mysql_escape_string($args['doc']);
		$this->dom=mysql_escape_string($args['dom']);
		$this->delete_flag=mysql_escape_string($args['delete_flag']);
	}
	
	
	public function InsertProject()
	{
		$DB=new DB;
		$this->strQuery="Insert into t_projects (project_name, client_id, building_id, room_id, account_manager, created_by, modified_by, doc, dom, delete_flag)
		Values('".$this->project_name."', ".$this->client_id.",". $this->building_id.",". $this->room_id.",". $this->account_manager.",". $this->created_by.",". $this->modified_by.", now(), now(),". $this->delete_flag.")";
		$DB->Execute($this->strQuery);
	}
	
	
	public function DeleteProject($ProjectID)
	{
		$DB = new DB;
		# Delete Systems for this Project
		$strSQL="Delete from t_project_system where project_id=".$ProjectID;
		$DB->Execute($strSQL);
		
		# Delete Project
		$strSQL="Delete from t_projects where projects_id=".$ProjectID;
		$DB->Execute($strSQL);		
		
	}
	
	public function ShowBuildingProjectWithRoom($strBuildingID)
	{
		$DB=new DB;
		$strSQL="Select * from t_projects where building_id=$strBuildingID and delete_flag=0 order by room_id asc";
		$strRsBuildingProjectsArr=$DB->Returns($strSQL);
		print '<option value="">Select Existing Project</option>';
		while($strRsBuildingProjects=mysql_fetch_object($strRsBuildingProjectsArr))
		{
			if($strRsBuildingProjects->room_id==0)
			{
				print '<option value="'.$strRsBuildingProjects->projects_id.'">Bld: '.$strRsBuildingProjects->project_name.'</option>';
			}
			else
			{
				$strSQL="Select room_name from t_room where room_id=".$strRsBuildingProjects->room_id;
				$strRsRoomNameArr=$DB->Returns($strSQL);
				if($strRsRoomName=mysql_fetch_object($strRsRoomNameArr))
				{
					print '<option value="'.$strRsBuildingProjects->projects_id.'">Rm: '.$strRsRoomName->room_name.'=>'.$strRsBuildingProjects->project_name.'</option>';
				}
			}
		}
	}
	
}

?>