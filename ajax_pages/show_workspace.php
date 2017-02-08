<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$client_id=$_GET['client_id'];
$project_id=$_GET['project_id'];

$strSQL="Select project_details_id, project_details_name from t_project_details where project_id=$project_id and client_id=$client_id";
$strRsWorkspaceArr=$DB->Returns($strSQL);
?>
<select name="ddlExistingWorkspace" id="ddlExistingWorkspace" onChange="LoadWorkspaceForEdit(this.value)">
    <option value="">Select a Workspace</option>
    <?php
	if(mysql_num_rows($strRsWorkspaceArr)>0)
	{
		while($strRsWorkspace=mysql_fetch_object($strRsWorkspaceArr))
		{
			print '<option value="'.$strRsWorkspace->project_details_id.'">'.$strRsWorkspace->project_details_name.'</option>';
		}
	}
	else
	{
		print '<option value="">Create New Workspace</option>';
	}
	?>
</select>
<input type="hidden" name="EditWorkspace_ID" id="EditWorkspace_ID" value="0" />