<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
$DB=new DB;
$Client=new Client;

if($_GET['client_id']<>"" and $_GET['FolderName']<>"")
{
	$client_id=$_GET['client_id'];
	$folder_id=$_GET['folder_id'];
	$file_name='';
	$sub_folder_id=($_GET['sub_folder_id']=="" ? "0" : $_GET['sub_folder_id'] );
	$folder_name=$_GET['FolderName'];
	$created_by=$_SESSION['user_login']->user_id;
	
	$strSQL="Insert into t_client_files_under_folder(client_id, folder_id, file_name, sub_folder_id, folder_name, doc, created_by)
	Values($client_id, $folder_id, '$file_name', $sub_folder_id, '$folder_name', now(), $created_by)";
	
	$DB->Execute($strSQL);
}

$arrPreLoadFolderOpenViewArr=$_SESSION['SubFolder_View_History'];

/*print "<pre>";
print_r($_SESSION['client_details']->customer_user_access_id);
print "</pre>";*/
$_SESSION['client_details']->AllowedSitesByCustomerUserAccess=array();
if($_SESSION['client_details']->customer_user_access_id<>0 or $_SESSION['client_details']->customer_user_access_id<>"")
{
	$strSQL="Select site_id from t_customer_user_access_site where customer_user_access_id=".$_SESSION['client_details']->customer_user_access_id." and type=5";
	$strRsAllowedSitesByCustomerUserAccessArr=$DB->Returns($strSQL);
	while($strRsAllowedSitesByCustomerUserAccess=mysql_fetch_object($strRsAllowedSitesByCustomerUserAccessArr))
	{
		if($strRsAllowedSitesByCustomerUserAccess->site_id==0)
		{
			$_SESSION['client_details']->AllowedSitesByCustomerUserAccess=array();
			break;
		}
		else
		{
			$strSQL="Select site_name from t_sites where site_id=".$strRsAllowedSitesByCustomerUserAccess->site_id;
			$strRsAllowedSitesNameArr=$DB->Returns($strSQL);
			if($strRsAllowedSitesName=mysql_fetch_object($strRsAllowedSitesNameArr))
			{
				$_SESSION['client_details']->AllowedSitesByCustomerUserAccess[]=$strRsAllowedSitesName->site_name;
			}
		}
	}
}

?>

<style type="text/css">
div.upload_custom {
    width: 26px;
    height: 26px;
    background: url(<?php echo URL?>images/file_add_icon_gray.png);	
    overflow: hidden;
	float:left;
	cursor:pointer;
}

div.upload_custom input {
    display: block !important;
    width: 26px !important;
    height: 26px !important;
    opacity: 0 !important;
    overflow: hidden !important;
	cursor:pointer;
}

form
{
	margin:0px !important;
}

.file_transfer_link a:link,  .file_transfer_link a:visited
{
	color:#666666;
	font-size:12px;
	font-weight:normal;
	text-decoration:underline;
}

</style>

<script type="text/javascript">
$('#ddlClientListForFiles').change(function(){
	$('#Files_Folder_View').html('Loading...');
	$.get('<?php echo URL?>ajax_pages/files.php',{client_id:this.value},function(data){
		$('#Files_Folder_View').html(data);
	});
});

function CreateFolder(folder_id,sub_folder_id)
{
	//alert(folder_id);
	$('#create_folder_'+folder_id+'_'+sub_folder_id).slideDown('slow');
}

function CloseCreateFolder(folder_id)
{
	$('#create_folder_'+folder_id).slideUp('slow');
}

function SaveCreateFolder(folder_id, client_id, sub_folder_id)
{
	var FolderName=$('#txtCreateFolder_'+folder_id+'_'+sub_folder_id).val();
	if(FolderName=="")
	{
		alert("Please enter New Folder Name");
		$('#txtCreateFolder_'+folder_id+'_'+sub_folder_id).focus();
		return false;
	}
	
	$('#Files_Folder_View').html('Loading...');
	$.get('<?php echo URL?>ajax_pages/files.php',{client_id:client_id, FolderName: FolderName, folder_id:folder_id, sub_folder_id:sub_folder_id},function(data){
		$('#Files_Folder_View').html(data);
	});
	
}


/* For File Upload */

function _(el){
	return document.getElementById(el);
}

var upload_folder_id=0;
var client_id_upload=0;
var sub_folder_id=0;
var folder_id_sub=0;

function uploadFile(folder_id, client_id, sub_folder_id){
	
	upload_folder_id=folder_id;
	client_id_upload=client_id;
	sub_folder_id=sub_folder_id;
	folder_id_sub=sub_folder_id;
	
	var file = _("file"+upload_folder_id+"_"+sub_folder_id).files[0];
	
	// alert(file.name+" | "+file.size+" | "+file.type);
	var formdata = new FormData();
	formdata.append("file1", file);
	formdata.append("folder_id", folder_id);
	formdata.append("client_id", client_id);
	formdata.append("sub_folder_id", sub_folder_id);	
	
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "<?php echo URL?>ajax_pages/file_uploader.php");
	ajax.send(formdata);
}
function progressHandler(event){
	_("loaded_n_total"+upload_folder_id+"_"+folder_id_sub).innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
	var percent = (event.loaded / event.total) * 100;
	_("progressBar"+upload_folder_id+"_"+folder_id_sub).value = Math.round(percent);	
	_("status"+upload_folder_id+"_"+folder_id_sub).innerHTML = Math.round(percent)+"% uploaded... please wait";	
}
function completeHandler(event){	
	_("status"+upload_folder_id+"_"+folder_id_sub).innerHTML = event.target.responseText;
	_("progressBar"+upload_folder_id+"_"+folder_id_sub).value = 0;
	
	$('#Files_Folder_View').html('Loading...');
	$.get('<?php echo URL?>ajax_pages/files.php',{client_id:client_id_upload},function(data){
		$('#Files_Folder_View').html(data);
	});
	
}
function errorHandler(event){
	_("status"+upload_folder_id+"_"+sub_folder_id).innerHTML = "Upload Failed";
}
function abortHandler(event){
	_("status"+upload_folder_id+"_"+sub_folder_id).innerHTML = "Upload Aborted";
}

function ShowUploadFile(folder_id,sub_folder_id)
{
	$('#UploadFile_Container_'+folder_id+'_'+sub_folder_id).slideDown('slow');
} 

function CloseUploadFile(folder_id, sub_folder_id)
{
	
	$('#UploadFile_Container_'+folder_id+'_'+sub_folder_id).slideUp('slow');
} 

function ShowContentOfSubFolder(sub_folder_id, count, delete_flag, edit_flag)
{
	if(count==0)
	{
		alert("There is no File or Sub Folder in the selected Folder");
		return;
	}
	
	$.get('<?php echo URL?>ajax_pages/sub_folder_content.php',{sub_folder_id:sub_folder_id, delete_flag:delete_flag, edit_flag:edit_flag},function(data){
		$('#subfolder_content_'+sub_folder_id).html(data);
	});
	
	$('#subfolder_content_'+sub_folder_id).slideDown('slow');
}

function ShowFileNames_Container(folder_id)
{
	if($('#FileNames_Container_'+folder_id).css('display')=='none')
	{
		$('#FileNames_Container_'+folder_id).slideDown('slow');
		$('#Plus_Minus_Folder_'+folder_id).html('-');
	}
	else
	{
		$('#FileNames_Container_'+folder_id).slideUp('slow');
		$('#Plus_Minus_Folder_'+folder_id).html('+');
	}
}

function DeleteFile(file_id, client_id_upload)
{
	if(!confirm("Are you sure you want to Permanently Delete?"))
		return false;
	
	$.get('<?php echo URL?>ajax_pages/file_delete.php',{file_id:file_id},function(data){
		$('#Files_Folder_View').html('Loading...');
		$.get('<?php echo URL?>ajax_pages/files.php',{client_id:client_id_upload},function(data){
			$('#Files_Folder_View').html(data);
		});
	});
}

function DeleteFolder(folder_id, client_id_upload)
{
	if(!confirm("Are you sure you want to Permanently Delete?"))
		return false;
	
	$.get('<?php echo URL?>ajax_pages/file_delete.php',{folder_id:folder_id},function(data){
		$('#Files_Folder_View').html('Loading...');
		$.get('<?php echo URL?>ajax_pages/files.php',{client_id:client_id_upload},function(data){
			$('#Files_Folder_View').html(data);
		});
	});
	
}

$(document).ready(function(){
	
	
});


</script>

<?php
$showClientList=true;
if($_GET['show_client']==1)
{
	$showClientList=false;
}
	
if($_GET['client_id']=="")
{
	print "<div style='padding:5px;'>";
	if($showClientList==true)
	{
		print "<h2>Client File Transfer Manager</h2>";
		print "<select name='ddlClientListForFiles' id='ddlClientListForFiles'>";
			$Client->ListClient();
		print "</select><br />";
	}
	print "<div style='height:5px;'></div>";
	
}
else
{
	$client_id=$_GET['client_id'];
}



?>
<div id='Files_Folder_View'>
<form id="upload_form" enctype="multipart/form-data" method="post"> 
<?php

if($client_id<>"")
{	
	
	if($_SESSION['user_login']->user_type==1)
	{
		$strCustomerTable="customer_";
	}
	else
	{
		$strCustomerTable="";
	}
	
	
	$user_access_id= $_SESSION['user_login']->user_access_id;
	$strSQL="Select * from t_folder_".$strCustomerTable."user_access where user_access_id=$user_access_id order by access_type";
	
	$strRsFoldersArr=$DB->Returns($strSQL);
	while($strRsFolders=mysql_fetch_object($strRsFoldersArr))
	{
		if($strRsFolders->access_type==1)
		{
			
			$strSQL="Select * from t_folder_".$strCustomerTable."user_access where user_access_id=$user_access_id and folder_id=".$strRsFolders->folder_id." and access_type=3";
			$strRsFoldersDeleteArr=$DB->Returns($strSQL);
			if($strRsFoldersDelete=mysql_fetch_object($strRsFoldersDeleteArr))
			{
				$Delete_Flag=1;
			}
			else
			{
				$Delete_Flag=0;
			}
			
			$strSQL="Select * from t_folder_".$strCustomerTable."user_access where user_access_id=$user_access_id and folder_id=".$strRsFolders->folder_id." and access_type=2";
			$strRsFoldersDeleteArr=$DB->Returns($strSQL);
			if($strRsFoldersDelete=mysql_fetch_object($strRsFoldersDeleteArr))
			{
				$Edit_Flag=1;
			}
			else
			{
				$Edit_Flag=0;
			}
			
			$strSQL="Select * from t_file_sharing_folders where folder_id=".$strRsFolders->folder_id;
			$strRsFolderNameArr=$DB->Returns($strSQL);
			if($strRsFolderName=mysql_fetch_object($strRsFolderNameArr))
			{
					
					print "<div style='max-height:200px; overflow-y: scroll;'>";
					
					
					print "<div style='background-color:#EFEFEF; min-height:30px; padding:0px 5px; margin-top:1px;'>
					<div style='float:left; width:140px; font-weight:bold;'><span style='cursor:pointer;' onclick=ShowFileNames_Container('".$strRsFolderName->folder_id."')><span id='Plus_Minus_Folder_".$strRsFolderName->folder_id."'>+</span> <span style='text-decoration:underline;'>".$strRsFolderName->folder_name."</span></span>
					
					<div style='float:left; width:300px; display:none; margin-left:5px; padding:0px 0px 3px 0px; font-weight:normal; font-size: 12px;' id='create_folder_".$strRsFolderName->folder_id."_0'>
					<input type='text' name='txtCreateFolder_".$strRsFolderName->folder_id."_0' id='txtCreateFolder_".$strRsFolderName->folder_id."_0' placeholder='New Folder' style='font-size: 12px; width: 150px; height: 20px; padding: 0px 5px;' />
					<a href=javascript:SaveCreateFolder('".$strRsFolderName->folder_id."','$client_id','0')>Save</a> | <a href=javascript:CloseCreateFolder('".$strRsFolderName->folder_id."_0')>Cancel</a>
					</div>
					
					</div>";
					
					if($Edit_Flag==1)
					{
						print "<div style='float:right; margin-right:10px; font-weight:bold;' class='file_transfer_link'>
						<a href=javascript:CreateFolder('".$strRsFolderName->folder_id."','0') title='Create Folder'>New Folder</a></div>
						
						<div style='float:right; margin-right:10px; font-weight:bold;' class='file_transfer_link'>
						<a href=javascript:ShowUploadFile('".$strRsFolderName->folder_id."','0') title='Upload File'>New File</a></div>
						
						<div class='clear'></div>";
					}
					?>
					
					<div style="display:none;" id="UploadFile_Container_<?php echo $strRsFolderName->folder_id;?>_0">
						  <div class="upload_custom" style="margin-top:2px;">  
								<input type="file" name="file<?php echo $strRsFolderName->folder_id;?>_0" id="file<?php echo $strRsFolderName->folder_id;?>_0">
						  </div>
						  
						  <div style="float:left; margin-left:5px; margin-top:3px;">
								<img src="<?php echo URL?>images/upload_icon.png" onclick="uploadFile('<?php echo $strRsFolderName->folder_id;?>','<?php echo $client_id;?>','0')" />
						  </div>                  
						  
						  <div style="float:left; margin-left:10px; margin-top:4px;">
								<progress id="progressBar<?php echo $strRsFolderName->folder_id;?>_0" value="0" max="100" style="width:150px;"></progress>
								<a href="javascript:CloseUploadFile('<?php echo $strRsFolderName->folder_id;?>','0')" style="font-size:12px;">Cancel</a>
								<div style="font-size:12px; color:#999999;" id="status<?php echo $strRsFolderName->folder_id;?>_0"></div>
								<span style="display:none;" id="loaded_n_total<?php echo $strRsFolderName->folder_id;?>_0"></span>
						  </div>
						  
						  <div class="clear"></div>				
					</div>
					
					
					
					<?php			
					
					print "</div>";
					
					print "<div style='display:none;' id='FileNames_Container_".$strRsFolderName->folder_id."'>";
					
					$strSQL="Select * from t_client_files_under_folder where folder_id=".$strRsFolderName->folder_id." and folder_type=1 and file_name<>'' and sub_folder_id=0 and client_id=$client_id";
					
					$strRsFilesUnderFolderArr=$DB->Returns($strSQL);
					$iCtr=0;
					while($strRsFilesUnderFolder=mysql_fetch_object($strRsFilesUnderFolderArr))
					{
						$iCtr++;
						if($iCtr % 2==0) {$bgcolor='#EFEFEF';} else {$bgcolor='#FFFFFF';} 
						print "<div style='font-size:12px; border-bottom:1px solid #CCCCCC; background-color:$bgcolor'>";
						print "<div style='margin-left:0px; float:left;'>$iCtr. <a target='_blank' href='".URL."download.php?file_id=".$strRsFilesUnderFolder->client_files_under_folder_id."' title='".$strRsFilesUnderFolder->file_name."'>".Globals::PrintDescription_1($strRsFilesUnderFolder->file_name,30)."</a></div>";
						
						if($Delete_Flag==1)
						{
							print "<div style='margin-right:5px; float:right;'><a href=javascript:DeleteFile('".$strRsFilesUnderFolder->client_files_under_folder_id."','".$client_id."')>
							<img border='0' width='12' src='".URL."images/delete-blue.png' title='Delete File' alt='Delete File' style='margin-top:-2px;' />
							</a></div>";
						}
						print "<div class='clear'></div>";
						print "</div>";
					}
					
					print '</div>';
					
					# folder_type= [1 = System Folders], [ 0= Folders Created]
					
					$strSQL="Select * from t_client_files_under_folder where folder_id=".$strRsFolderName->folder_id." and folder_type=0 and sub_folder_id=0 and file_name='' and client_id=$client_id";
					$strRsFilesUnderFolderArr=$DB->Returns($strSQL);
					$iCtr=0;
					while($strRsFilesUnderFolder=mysql_fetch_object($strRsFilesUnderFolderArr))
					{
						
						if($strRsFolderName->folder_id==6 and is_array($_SESSION['client_details']->AllowedSitesByCustomerUserAccess) && count($_SESSION['client_details']->AllowedSitesByCustomerUserAccess)>0)
						{
							# For Site Folder
							if(!in_array($strRsFilesUnderFolder->folder_name , $_SESSION['client_details']->AllowedSitesByCustomerUserAccess))
							{
								continue;
							}
						}
						
						$iCtr++;
						$sub_folder_id=$strRsFilesUnderFolder->client_files_under_folder_id;
						
						$strSQL="Select count(*) as Total from t_client_files_under_folder where sub_folder_id=$sub_folder_id";
						$strRsTotalFilesFolderArr=$DB->Returns($strSQL);
						if($strRsTotalFilesFolder=mysql_fetch_object($strRsTotalFilesFolderArr))
						{
							$TotalFilesFolder=$strRsTotalFilesFolder->Total;
							if($TotalFilesFolder>0)
							{
								$FolderNameColor='#666666';
							}
							else
							{
								$FolderNameColor='#CCCCCC';
							}
								
						}
						if($iCtr % 2==0) {$bgcolor='#EFEFEF';} else {$bgcolor='#FFFFFF';} 
				
						print "<div style='font-size:12px; border-bottom:1px solid #CCCCCC; background-color:$bgcolor; background-image:url(".URL."images/folder_icon_gray.png); background-repeat:no-repeat;'>";
						print "<div style='margin-left:20px; float:left;'><a href=javascript:ShowContentOfSubFolder('$sub_folder_id','$TotalFilesFolder','$Delete_Flag','$Edit_Flag') style='font-weight:bold; color:$FolderNameColor;'>".$strRsFilesUnderFolder->folder_name."</a>";
						if($TotalFilesFolder==0 and $Delete_Flag==1){
							print "&nbsp;&nbsp;<a href=javascript:DeleteFolder('".$strRsFilesUnderFolder->client_files_under_folder_id."','".$strRsFilesUnderFolder->client_id."')><img border='0' width='12' src='".URL."images/delete-email.png' title='Delete Folder' alt='Delete Folder' style='margin-top:-2px;' /></a>";
						}
						print "</div>";	
						
						if($Edit_Flag==1)
						{
							print "<div style='float:right; margin-right:10px; font-weight:bold;' class='file_transfer_link'>
							<a href=javascript:CreateFolder('".$strRsFolderName->folder_id."','".$strRsFilesUnderFolder->client_files_under_folder_id."') title='Create Folder'>New Folder</a></div>
						
							<div style='float:right; margin-right:10px; font-weight:bold;' class='file_transfer_link'>
							<a href=javascript:ShowUploadFile('".$strRsFolderName->folder_id."','".$strRsFilesUnderFolder->client_files_under_folder_id."') title='Upload File'>New File</a></div>";
						}
						
						print "<div class='clear'></div>";
						
						
						print "<div style='float:left; width:300px; display:none; margin-left:5px; padding:0px 0px 3px 0px; font-weight:normal; font-size: 12px;' id='create_folder_".$strRsFolderName->folder_id."_$sub_folder_id'>
							<input type='text' name='txtCreateFolder_".$strRsFolderName->folder_id."_$sub_folder_id' id='txtCreateFolder_".$strRsFolderName->folder_id."_$sub_folder_id' placeholder='New Folder' style='font-size: 12px; width: 150px; height: 20px; padding: 0px 5px;' />
							<a href=javascript:SaveCreateFolder('".$strRsFolderName->folder_id."','$client_id','$sub_folder_id')>Save</a> | <a href=javascript:CloseCreateFolder('".$strRsFolderName->folder_id."_$sub_folder_id')>Cancel</a>
						</div>";
										
						print "<div class='clear'></div>";
						?>
						
						
						<div style="display:none;" id="UploadFile_Container_<?php echo $strRsFolderName->folder_id;?>_<?php echo $sub_folder_id;?>">
						  <div class="upload_custom" style="margin-top:2px;">  
								<input type="file" name="file<?php echo $strRsFolderName->folder_id;?>_<?php echo $sub_folder_id;?>" id="file<?php echo $strRsFolderName->folder_id;?>_<?php echo $sub_folder_id;?>">
						  </div>
						  
						  <div style="float:left; margin-left:5px; margin-top:3px;">
								<img src="<?php echo URL?>images/upload_icon.png" onclick="uploadFile('<?php echo $strRsFolderName->folder_id;?>','<?php echo $client_id;?>','<?php echo $sub_folder_id;?>')" />
						  </div>                  
						  
						  <div style="float:left; margin-left:10px; margin-top:4px;">
								<progress id="progressBar<?php echo $strRsFolderName->folder_id;?>_<?php echo $sub_folder_id;?>" value="0" max="100" style="width:150px;"></progress>
								<a href="javascript:CloseUploadFile('<?php echo $strRsFolderName->folder_id;?>','<?php echo $sub_folder_id;?>')" style="font-size:12px;">Cancel</a>
								<div style="font-size:12px; color:#999999;" id="status<?php echo $strRsFolderName->folder_id;?>_<?php echo $sub_folder_id;?>"></div>
								<span style="display:none;" id="loaded_n_total<?php echo $strRsFolderName->folder_id;?>_<?php echo $sub_folder_id;?>"></span>
						  </div>
						  
						  <div class="clear"></div>				
						</div>
						
						
						<?php				
						print "</div>
						
						<div style='display:none; margin-left:20px; font-size:12px;' id='subfolder_content_$sub_folder_id'>Loading...</div>
						
						";
					}
					
					
					print "</div>";
					
					print "</div>";
				
				
			
			}
		}
	}
}
else
{
	print "Please select a Client";
}


?>
</form>	
</div>