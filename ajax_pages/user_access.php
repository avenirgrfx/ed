<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

if($_POST)
{
	$AccessData="";
	$ArrPostData=$_POST;
	if(is_array($ArrPostData) && count($ArrPostData)>0)
	{
		foreach($ArrPostData as $key=>$Val)
		{
			if($key=="AccessName" or $key=="AccessType" or $key=="user_access_id")
				continue;
			$AccessData.=($key.";".$Val."@~@");
		}
	}
	
	$AccessName=$_POST['AccessName'];
	$AccessType=$_POST['AccessType'];
	
	
	
	if($_POST['user_access_id']=="")
	{
		$strSQL="Insert into t_user_access(user_access_name, user_access_type, user_access, delete_flag, doc, created_by, dom, modified_by) Values('$AccessName',$AccessType,'$AccessData',0,now(),0,now(),0)";
		$Access_ID=$DB->Execute($strSQL);
		
	}
	else
	{
		$strSQL="Update t_user_access set user_access_name='$AccessName', user_access_type=$AccessType, user_access='$AccessData' ,dom=now(), modified_by=0 Where user_access_id=".$_POST['user_access_id'];
		$DB->Execute($strSQL);
		
		$Access_ID=$_POST['user_access_id'];
	}
	
	
	$strSQL="Delete from t_folder_user_access where user_access_id=$Access_ID";
	$DB->Execute($strSQL);
	
	$FolderView=$_POST['FolderView'];
	$FolderUpload=$_POST['FolderUpload'];
	$FolderDelete=$_POST['FolderDelete'];
	
	
	$FolderViewArr=explode(",",$FolderView);
	if(is_array($FolderViewArr) and count($FolderViewArr)>0)
	{
		foreach($FolderViewArr as $folder_id)
		{
			if($folder_id<>"")
			{
				$access_type=1;
				$strSQL="Insert into t_folder_user_access (user_access_id, folder_id, access_type) values($Access_ID, $folder_id, $access_type)";
				$DB->Execute($strSQL);
			}
		}
	}
	
	$FolderUploadArr=explode(",",$FolderUpload);
	if(is_array($FolderUploadArr) and count($FolderUploadArr)>0)
	{
		foreach($FolderUploadArr as $folder_id)
		{
			if($folder_id<>"")
			{
				$access_type=2;
				$strSQL="Insert into t_folder_user_access (user_access_id, folder_id, access_type) values($Access_ID, $folder_id, $access_type)";
				$DB->Execute($strSQL);
			}
		}
	}
	
	$FolderDeleteArr=explode(",",$FolderDelete);
	if(is_array($FolderDeleteArr) and count($FolderDeleteArr)>0)
	{
		foreach($FolderDeleteArr as $folder_id)
		{
			if($folder_id<>"")
			{
				$access_type=3;
				$strSQL="Insert into t_folder_user_access (user_access_id, folder_id, access_type) values($Access_ID, $folder_id, $access_type)";
				$DB->Execute($strSQL);
			}
		}
	}
	
	
	if($_POST['user_access_id']=="")
	{
		echo "Template Created";
	}
	else
	{
		echo "Template Updated";
	}
	exit();
}

if($_GET['edit_id']<>'')
{
	$edit_id= $_GET['edit_id'];
	$strSQL="Select * from t_user_access where user_access_id=$edit_id";
	$strRsUserAccesArr=$DB->Returns($strSQL);	
	
	$arrTemp=array();
	
	while($strRsUserAcces=mysql_fetch_object($strRsUserAccesArr))
	{
		$user_access_id=$strRsUserAcces->user_access_id;
		$Access_Name=$strRsUserAcces->user_access_name;
		$user_access_type=$strRsUserAcces->user_access_type;
		$AccessListArr=explode("@~@",$strRsUserAcces->user_access);
		if(is_array($AccessListArr) && count($AccessListArr)>0)
		{
			foreach($AccessListArr as $Val)
			{
				$AccessListArrVal=explode(";",$Val);
				if($AccessListArrVal[1]==1)
				{
					$arrTemp[ $AccessListArrVal[0] ]=$AccessListArrVal[1];
				}
			}
		}
	}	
}

?>

<style type="text/css">
#AdminAccess_Container tr td
{
	border:1px solid #CCCCCC;
}

#EngineerAccess_Container tr td
{
	border:1px solid #CCCCCC;
}

#UserAccess_Container tr td
{
	border:1px solid #CCCCCC;
}

#User_Access_List tr td
{
	border:1px solid #CCCCCC;
}
</style>

<script type="text/javascript">
$('#ddlAccessType').change(function(){
	if($('#ddlAccessType').val()==1)
	{
		$('#AdminAccess_Container').css('display','block');
		$('#EngineerAccess_Container').css('display','block');
		$('#UserAccess_Container').css('display','block');
		
		$('#AdminAccess_Title').css('display','block');
		$('#EngineerAccess_Title').css('display','block');
		$('#UserAccess_Title').css('display','block');
	}
	else if($('#ddlAccessType').val()==2)
	{
		$('#AdminAccess_Container').css('display','none');
		$('#EngineerAccess_Container').css('display','block');
		$('#UserAccess_Container').css('display','block');
		
		$('#AdminAccess_Title').css('display','none');
		$('#EngineerAccess_Title').css('display','block');
		$('#UserAccess_Title').css('display','block');
	}
	else if($('#ddlAccessType').val()==3)
	{
		$('#AdminAccess_Container').css('display','none');
		$('#EngineerAccess_Container').css('display','none');
		$('#UserAccess_Container').css('display','block');
		
		$('#AdminAccess_Title').css('display','none');
		$('#EngineerAccess_Title').css('display','none');
		$('#UserAccess_Title').css('display','block');
	}
});


$('#Show_Hide_Create_User_Template').click(function(){
	if( $('#Show_Hide_Create_User_Template').html()=="Create User Template +")
	{
		$('#User_Access_Create').slideDown('slow');
		$('#Show_Hide_Create_User_Template').html("Create User Template -");
	}
	else
	{
		$('#User_Access_Create').slideUp('slow');
		$('#Show_Hide_Create_User_Template').html("Create User Template +");
	}
});

$('#btnSubmit').click(function(){

	if($("#chkClient_List").is(':checked'))
		chkClient_List=1;
	else
		chkClient_List=0;
		
	if($("#chkPackage_Manager").is(':checked'))
		PackageManager=1;
	else
		PackageManager=0;
	
	if($("#chkPackage_Credit").is(':checked'))
		PackageCredit=1;
	else
		PackageCredit=0;
	
	if($("#chkPackage_View").is(':checked'))
		PackageView=1;
	else
		PackageView=0;
	
	if($("#chkPackage_Price_Manager").is(':checked'))
		PackagePriceManager=1;
	else
		PackagePriceManager=0;
	
	if($("#chkMasterSystem").is(':checked'))
		MasterSystem=1;
	else
		MasterSystem=0;
	
	if($("#chkMasterEquipment").is(':checked'))
		MasterEquipment=1;
	else
		MasterEquipment=0;
	
	if($("#chkMaster_Equipment_Gallery").is(':checked'))
		Master_Equipment_Gallery=1;
	else
		Master_Equipment_Gallery=0;
	
	if($("#chkUser_Access").is(':checked'))
		User_Access=1;
	else
		User_Access=0;
	
	if($("#chkUsers").is(':checked'))
		Users=1;
	else
		Users=0;
	
	
	if($("#chkProject_Setup").is(':checked'))
		Project_Setup=1;
	else
		Project_Setup=0;
	
	if($("#chkSystem_Management").is(':checked'))
		System_Management=1;
	else
		System_Management=0;
		
	if($("#chkNode_Management").is(':checked'))
		Node_Management=1;
	else
		Node_Management=0;
	
	if($("#chkControl_Workspace").is(':checked'))
		Control_Workspace=1;
	else
		Control_Workspace=0;
	
	if($("#chkControl_Choice").is(':checked'))
		Control_Choice=1;
	else
		Control_Choice=0;
	
	if($("#chkUser_Dashboard_Readonly").is(':checked'))
		User_Dashboard_Readonly=1;
	else
		User_Dashboard_Readonly=0;
	
	
	if($("#chkCRM_ViewClients").is(':checked'))
		CRM_ViewClients=1;
	else
		CRM_ViewClients=0;
	
	if($("#chkCRM_FileTransfer").is(':checked'))
		CRM_FileTransfer=1;
	else
		CRM_FileTransfer=0;
	
	if($("#chkCRM_MessageClients").is(':checked'))
		CRM_MessageClients=1;
	else
		CRM_MessageClients=0;
	
	//alert($('#chkFolder_View_1').val());
	
	var FolderView='';
	var FolderUpload='';
	var FolderDelete='';
	
	<?php
		$strSQL="Select * from t_file_sharing_folders order by folder_name";
		$strRsFolderNamesArr=$DB->Returns($strSQL);
		while($strRsFolderNames=mysql_fetch_object($strRsFolderNamesArr))
		{
			$chkFolderView= "chkFolder_View_".$strRsFolderNames->folder_id;
			$chkFolder_Upload= "chkFolder_Upload_".$strRsFolderNames->folder_id;
			$chkFolder_Delete= "chkFolder_Delete_".$strRsFolderNames->folder_id;
			?>
				if( $('#<?php echo $chkFolderView;?>').is(':checked') )
				{
					FolderView=FolderView+',<?php echo $strRsFolderNames->folder_id;?>';
				}
				if( $('#<?php echo $chkFolder_Upload;?>').is(':checked') )
				{
					FolderUpload=FolderUpload+',<?php echo $strRsFolderNames->folder_id;?>';
				}
				if( $('#<?php echo $chkFolder_Delete;?>').is(':checked') )
				{
					FolderDelete=FolderDelete+',<?php echo $strRsFolderNames->folder_id;?>';
				}	
			<?php
		}
	?>
	
	
	
	$.post('<?php echo URL?>ajax_pages/user_access.php',
		{		
			
			user_access_id:$('#txtuser_access_id').val(),
			AccessName:$('#txtAccessName').val(),
			AccessType:$('#ddlAccessType').val(),
			
			ClientList:chkClient_List,			
			PackageManager:PackageManager,
			PackageCredit:PackageCredit,
			PackageView:PackageView,
			PackagePriceManager:PackagePriceManager,
			MasterSystem:MasterSystem,
			MasterEquipment:MasterEquipment,
			Master_Equipment_Gallery:Master_Equipment_Gallery,
			User_Access:User_Access,
			Users:Users,
			Project_Setup:Project_Setup,
			System_Management:System_Management,
			Node_Management:Node_Management,
			Control_Workspace:Control_Workspace,
			Control_Choice:Control_Choice,
			User_Dashboard_Readonly:User_Dashboard_Readonly,
			CRM_ViewClients:CRM_ViewClients,
			CRM_FileTransfer:CRM_FileTransfer,			
			CRM_MessageClients:CRM_MessageClients,
			FolderView:FolderView,	
			FolderUpload:FolderUpload,
			FolderDelete:FolderDelete,
		},
	
		function(data){

			alert(data);			
			
			$.get('<?php echo URL?>ajax_pages/user_access.php',{},function(data){
					$('#Category_Container').html(data);
			});
			
		});
});


function editUserAccess(AccessID)
{
	$.get('<?php echo URL?>ajax_pages/user_access.php',{edit_id:AccessID},function(data){
		$('#Category_Container').html(data);
		$('#Show_Hide_Create_User_Template').trigger('click');
	});
}

</script>

<div style="font-size:16px; font-weight:bold; margin-bottom:10px; color:#0066FF; text-decoration:underline; border-bottom:1px solid #CCCCCC; cursor:pointer;" id="Show_Hide_Create_User_Template">Create User Template +</div>

<div id="User_Access_Create" style="display:block;">

<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td width="16%"><strong>Template Name</strong></td>
    <td width="33%"><input type="text" name="txtAccessName" id="txtAccessName" value="<?php echo $Access_Name;?>">
    <input type="hidden" name="txtuser_access_id" id="txtuser_access_id" value="<?php echo $user_access_id?>" />
    </td>
    <td width="10%"><strong>Access Type</strong></td>
    <td width="41%">
    <select name="ddlAccessType" id="ddlAccessType">
      <option value="1" <?php if($user_access_type==1){?>selected="selected"<?php }?> >Administrator</option>
      <option value="2" <?php if($user_access_type==2){?>selected="selected"<?php }?>>Engineer</option>
      <option value="3" <?php if($user_access_type==3){?>selected="selected"<?php }?>>User</option>
    </select>    </td>
  </tr>
  <tr>
    <td valign="top"><strong>Access</strong></td>
    <td colspan="3"><div style="font-size:16px; font-weight:bold;" id="AdminAccess_Title">Administrator Module</div>
      <table id="AdminAccess_Container" width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td colspan="6" style="background-color:#EFEFEF;"><strong>Client Access</strong></td>
        </tr>
      <tr>
        <td width="2%"><input name="chkClient_List" type="checkbox" id="chkClient_List" value="1" <?php if(array_key_exists("ClientList",$arrTemp)){?>checked="checked"<?php }?> ></td>
        <td width="37%">Client List</td>
        <td width="3%"><input name="chkPackage_Manager" type="checkbox" id="chkPackage_Manager" value="1" <?php if(array_key_exists("PackageManager",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td width="30%">Package Manager</td>
        <td width="3%"><input name="chkPackage_Price_Manager" type="checkbox" id="chkPackage_Price_Manager" value="1" <?php if(array_key_exists("PackagePriceManager",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td width="25%">Package Price Manager</td>
      </tr>
      <tr>
        <td><input name="chkPackage_Credit" type="checkbox" id="chkPackage_Credit" value="1" <?php if(array_key_exists("PackageCredit",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>Package Credit</td>
        <td><input name="chkPackage_View" type="checkbox" id="chkPackage_View" value="1" <?php if(array_key_exists("PackageView",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>Package View</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" style="height:10px;"></td>
        </tr>
      <tr>
        <td colspan="6" style="background-color:#EFEFEF;"><strong>System Access</strong></td>
        </tr>
      <tr>
        <td><input name="chkMasterSystem" type="checkbox" id="chkMasterSystem" value="1" <?php if(array_key_exists("MasterSystem",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>Master System</td>
        <td><input name="chkMasterEquipment" type="checkbox" id="chkMasterEquipment" value="1" <?php if(array_key_exists("MasterEquipment",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>Master Equipment</td>
        <td><input name="chkMaster_Equipment_Gallery" type="checkbox" id="chkMaster_Equipment_Gallery" value="1" <?php if(array_key_exists("Master_Equipment_Gallery",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>Master Equipment Gallery</td>
      </tr>
      <tr>
        <td colspan="6" style="height:10px;"></td>
        </tr>
      <tr>
        <td colspan="6" style="background-color:#EFEFEF;"><strong>User Access</strong></td>
        </tr>
      <tr>
        <td><input name="chkUser_Access" type="checkbox" id="chkUser_Access" value="1" <?php if(array_key_exists("User_Access",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>User Access</td>
        <td><input name="chkUsers" type="checkbox" id="chkUsers" value="1" <?php if(array_key_exists("Users",$arrTemp)){?>checked="checked"<?php }?>  ></td>
        <td>Users</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" style="height:10px;"></td>
        </tr>
    </table>
      <br><div style="font-size:16px; font-weight:bold;" id="EngineerAccess_Title">Engineer Module</div>
      <table id="EngineerAccess_Container" width="100%" border="0" cellspacing="1" cellpadding="3">
        
        <tr>
          <td colspan="6" style="background-color:#EFEFEF;"><strong>Project</strong></td>
        </tr>
        <tr>
          <td width="2%"><input name="chkProject_Setup" type="checkbox" id="chkProject_Setup" value="1" <?php if(array_key_exists("Project_Setup",$arrTemp)){?>checked="checked"<?php }?>  ></td>
          <td width="37%">Project Setup</td>
          <td width="3%">&nbsp;</td>
          <td width="30%">&nbsp;</td>
          <td width="3%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" style="height:10px;"></td>
        </tr>
        <tr>
          <td colspan="6" style="background-color:#EFEFEF;"><strong>Design</strong></td>
        </tr>
        <tr>
          <td><input name="chkSystem_Management" type="checkbox" id="chkSystem_Management" value="1" <?php if(array_key_exists("System_Management",$arrTemp)){?>checked="checked"<?php }?>  ></td>
          <td>System Management</td>
          <td><input name="chkNode_Management" type="checkbox" id="chkNode_Management" value="1" <?php if(array_key_exists("Node_Management",$arrTemp)){?>checked="checked"<?php }?>  ></td>
          <td>Node Management</td>
          <td><input type="checkbox" name="chkControl_Workspace" id="chkControl_Workspace" value="1" <?php if(array_key_exists("Control_Workspace",$arrTemp)){?>checked="checked"<?php }?>  ></td>
          <td>Control Workspace</td>
        </tr>
        <tr>
          <td colspan="6" style="height:10px;"></td>
        </tr>
        <tr>
          <td colspan="6" style="background-color:#EFEFEF;"><strong>Controls</strong></td>
        </tr>
        <tr>
          <td><input type="checkbox" name="chkControl_Choice" id="chkControl_Choice" value="1" <?php if(array_key_exists("Control_Choice",$arrTemp)){?>checked="checked"<?php }?>  ></td>
          <td>Control Choice</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" style="background-color:#EFEFEF;"><strong>CRM</strong></td>
          </tr>
        <tr>
          <td><input type="checkbox" name="chkCRM_ViewClients" id="chkCRM_ViewClients" value="1" <?php if(array_key_exists("CRM_ViewClients",$arrTemp)){?>checked="checked"<?php }?> /></td>
          <td>View (All Clients)</td>
          <td><input type="checkbox" name="chkCRM_FileTransfer" id="chkCRM_FileTransfer" value="1" <?php if(array_key_exists("CRM_FileTransfer",$arrTemp)){?>checked="checked"<?php }?> /></td>
          <td>File Management (All Clients)</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><input type="checkbox" name="chkCRM_MessageClients" id="chkCRM_MessageClients" value="1" <?php if(array_key_exists("CRM_MessageClients",$arrTemp)){?>checked="checked"<?php }?> /></td>
          <td valign="top">Send Message (All Clients)</td>
          <td colspan="4" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
              <td width="51%"><strong>Folder</strong></td>
              <td width="15%" align="center"><strong>View</strong></td>
              <td width="19%" align="center"><strong>Upload</strong></td>
              <td width="15%" align="center"><strong>Delete</strong></td>
            </tr>
			<?php
            	$strSQL="Select * from t_file_sharing_folders order by folder_name";
				$strRsFolderNamesArr=$DB->Returns($strSQL);
				while($strRsFolderNames=mysql_fetch_object($strRsFolderNamesArr))
				{
					if($edit_id<>"")
					{
						$ViewFolder=false;
						$UploadFolder=false;
						$DeleteFolder=false;
						
						$strSQL="Select * from t_folder_user_access where user_access_id=$edit_id and folder_id=".$strRsFolderNames->folder_id;
						$strRsFolderExistArr=$DB->Returns($strSQL);
						while($strRsFolderExist=mysql_fetch_object($strRsFolderExistArr))
						{
							$access_type=$strRsFolderExist->access_type;
							if($access_type==1)
							{
								$ViewFolder=true;
							}
							if($access_type==2)
							{
								$UploadFolder=true;
							}
							if($access_type==3)
							{
								$DeleteFolder=true;
							}
						}
					}
			?>
          
            <tr>
              <td><?php echo $strRsFolderNames->folder_name;?></td>
              <td align="center"><input type="checkbox" name="chkFolder_View_<?php echo $strRsFolderNames->folder_id;?>" id="chkFolder_View_<?php echo $strRsFolderNames->folder_id;?>" value="1" <?php if($ViewFolder==true){?>checked="checked"<?php }?> /></td>
              <td align="center"><input type="checkbox" name="chkFolder_Upload_<?php echo $strRsFolderNames->folder_id;?>" id="chkFolder_Upload_<?php echo $strRsFolderNames->folder_id;?>" value="1" <?php if($UploadFolder==true){?>checked="checked"<?php }?> /></td>
              <td align="center"><input type="checkbox" name="chkFolder_Delete_<?php echo $strRsFolderNames->folder_id;?>" id="chkFolder_Delete_<?php echo $strRsFolderNames->folder_id;?>" value="1" <?php if($DeleteFolder==true){?>checked="checked"<?php }?> /></td>
            </tr>
            <?php }?>
            
          </table></td>
          </tr>
      </table>
      <br>
      <div style="font-size:16px; font-weight:bold;" id="UserAccess_Title">User Module<br>
      </div>
      <table id="UserAccess_Container" width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr>
          <td colspan="6" style="background-color:#EFEFEF;"><strong>Dashboard &amp; Reports</strong></td>
        </tr>
        <tr>
          <td width="3%"><input name="chkUser_Dashboard_Readonly" type="checkbox" id="chkUser_Dashboard_Readonly" value="1" <?php if(array_key_exists("User_Dashboard_Readonly",$arrTemp)){?>checked="checked"<?php }?>  ></td>
          <td width="37%">Read Only</td>
          <td width="2%">&nbsp;</td>
          <td width="30%">&nbsp;</td>
          <td width="2%">&nbsp;</td>
          <td width="26%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" style="height:10px;"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td colspan="3"><input type="button" name="btnSubmit" id="btnSubmit" value="Submit"></td>
  </tr>
</table>
</div>


<div id="User_Access_List">
<div style="font-size:16px; font-weight:bold; margin-bottom:10px; margin-top:20px; border-bottom:1px solid #CCCCCC;">User Template List</div>

<?php
	
	$arrColor=array('#333333','#0000FF', '#0000DD','#0099CC','#33CCCC','#666666','#6699FF');
	
	$ArrAccessListDisplay=array(
	'ClientList'=>'Client List', 'PackageManager'=>'Package Manager', 
	'PackageCredit'=>'Package Credit',
	'PackageView'=>'Package View',
	'PackagePriceManager'=>'Package Price Manager',
	'MasterSystem'=>'Master System',
	'MasterEquipment'=>'Master Equipment',
	'Master_Equipment_Gallery'=>'Master Equipment Gallery',
	'User_Access'=>'User Access',
	'Users'=>'Users',
	'Project_Setup'=>'Project Setup',
	'System_Management'=>'System Management',
	'Node_Management'=>'Node Management',
	'Control_Workspace'=>'Control Workspace',
	'Control_Choice'=>'Control Choice',
	'User_Dashboard_Readonly'=>'User Dashboard Readonly',
	'CRM_ViewClients'=>'CRM ViewClients',
	'CRM_FileTransfer'=>'CRM FileTransfer',			
	'CRM_MessageClients'=>'CRM MessageClients',
	);
	
	
	$strSQL="Select * from t_user_access order by user_access_name";
	$strRsUserAccesArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsUserAccesArr)>0)
	{
	?>    
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr style="background-color:#EFEFEF;">
        <td width="12%"><strong>Template Name</strong></td>
        <td width="79%"><strong>Access</strong></td>
        <td width="9%"><strong>Action</strong></td>
      </tr>
     
      
      <?php
      	while($strRsUserAcces=mysql_fetch_object($strRsUserAccesArr))
		{
			
		?>
         <tr>
            <td style="font-weight:bold;"><?php echo $strRsUserAcces->user_access_name;?></td>
            <td style="font-size:12px;">
			<?php
				$AccessListArr=explode("@~@",$strRsUserAcces->user_access);
				if(is_array($AccessListArr) && count($AccessListArr)>0)
				{
					foreach($AccessListArr as $Val)
					{
						$AccessListArrVal=explode(";",$Val);
						if($AccessListArrVal[1]==1)
						{
							$RandomColor=$arrColor[rand(0,count($arrColor)-1)];
							//print $AccessListArrVal[0].", ";
							print "<span style='color:".$RandomColor."'>".$ArrAccessListDisplay[$AccessListArrVal[0]]."</span>, ";
						}
					}
				}
			?></td>
            <td style="font-size:12px;"><a href="javascript:editUserAccess('<?php echo $strRsUserAcces->user_access_id;?>')">Edit</a> | <a href="#">Delete</a></td>
          </tr>
        <?php
		
	}
	  ?>
    </table>
    
    <?php
	}
	
?>

</div>
