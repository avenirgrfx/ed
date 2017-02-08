<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
$DB=new DB;
$Client=new Client;

$folder_id=$_GET['sub_folder_id'];
$delete_flag=$_GET['delete_flag'];
$edit_flag=$_GET['edit_flag'];
//$_SESSION['SubFolder_View_History']='';

/*if(array_key_exists ($folder_id,$_SESSION['SubFolder_View_History']))
{
}
else
{
	if($delete_flag<>"" and $edit_flag<>"")
	{
		$_SESSION['SubFolder_View_History'][$folder_id]=array(1,$delete_flag,$edit_flag);
	}
}*/

$strSQL="Select * from t_client_files_under_folder where sub_folder_id=$folder_id and folder_name='' and file_name<>''";
$strRsSubFolderFilesArr=$DB->Returns($strSQL);
$iCtr=0;
while($strRsSubFolderFiles=mysql_fetch_object($strRsSubFolderFilesArr))
{
	$iCtr++;
	if($iCtr % 2==0) {$bgcolor='#EFEFEF';} else {$bgcolor='#FFFFFF';}
?>

<div style="font-size:12px; border-bottom:1px solid #CCCCCC; background-color:<?php echo $bgcolor;?>">
    <div style="margin-left:0px; float:left;">
        <?php echo $iCtr;?>. <a target="_blank" href="<?php echo URL;?>/download.php?file_id=<?php echo $strRsSubFolderFiles->client_files_under_folder_id;?>" title="<?php echo $strRsSubFolderFiles->file_name;?>"><?php echo Globals::PrintDescription_1($strRsSubFolderFiles->file_name,20)?></a>
    </div>
    <?php if($delete_flag==1){?>
    	<div style="margin-right:5px; float:right;"><a href=javascript:DeleteFile('<?php echo $strRsSubFolderFiles->client_files_under_folder_id?>','<?php echo $strRsSubFolderFiles->client_id?>')>
        <img border='0' width='12' src='<?php echo URL?>images/delete-blue.png' title='Delete File' alt='Delete File' style='margin-top:-2px;' />
        </a></div>
    <?php }?>
    
    <div class="clear"></div>
</div>

<?php
}

$strSQL="Select * from t_client_files_under_folder where sub_folder_id=$folder_id and file_name=''";
$strRsSubFolderFilesArr=$DB->Returns($strSQL);
$iCtr=0;
while($strRsSubFolderFiles=mysql_fetch_object($strRsSubFolderFilesArr))
{
	$iCtr++;
	$sub_folder_id=$strRsSubFolderFiles->client_files_under_folder_id;
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

?>
<div style="font-size:12px; border-bottom:1px solid #CCCCCC; background-color:<?php echo $bgcolor;?>; background-image:url(<?php echo URL?>images/folder_icon_gray.png); background-repeat:no-repeat;">
	<div style="margin-left:20px; float:left;">
    <a href="javascript:ShowContentOfSubFolder('<?php echo $sub_folder_id;?>','<?php echo $TotalFilesFolder;?>','<?php echo $delete_flag;?>','<?php echo $edit_flag;?>')" style="font-weight:bold; color:<?php echo $FolderNameColor?>;">
		<?php echo $strRsSubFolderFiles->folder_name;?>
    </a>
    
    <?php if($TotalFilesFolder==0 and $delete_flag==1){ ?>
		&nbsp;<a href=javascript:DeleteFolder('<?php echo $strRsSubFolderFiles->client_files_under_folder_id?>','<?php echo $strRsSubFolderFiles->client_id?>')>
        	<img border='0' width='12' src='<?php echo URL?>images/delete-email.png' title='Delete Folder' alt='Delete Folder' style='margin-top:-2px;' />
        </a>
	<?php } ?>
    
    </div>
    
    
    <?php if($edit_flag==1){?>
    
    <div style="float:right; margin-right:10px; font-weight:bold;" class="file_transfer_link">
    
    <a href="javascript:CreateFolder('<?php echo $folder_id;?>','<?php echo $sub_folder_id;?>')" title="Create Folder">New Folder</a></div>
    
    <div style="float:right; margin-right:10px; font-weight:bold;" class="file_transfer_link">
    <a href="javascript:ShowUploadFile('<?php echo $folder_id;?>','<?php echo $sub_folder_id;?>')" title="Upload File">New File</a></div>
    <?php }?>
    
    <div class="clear"></div><div style="float:left; width:300px; display:none; margin-left:5px; padding:0px 0px 3px 0px; font-weight:normal; font-size: 12px;" id="create_folder_<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>">
        <input type="text" name="txtCreateFolder_<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>" id="txtCreateFolder_<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>" placeholder="New Folder" style="font-size: 12px; width: 150px; height: 20px; padding: 0px 5px;">
        <a href="javascript:SaveCreateFolder('<?php echo $folder_id;?>','<?php echo $strRsSubFolderFiles->client_id;?>','<?php echo $sub_folder_id;?>')">Save</a> | <a href="javascript:CloseCreateFolder('<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>')">Cancel</a>
    </div><div class="clear"></div>
    
     	                 
    
    <div style="display:none;" id="UploadFile_Container_<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>">
      <div class="upload_custom" style="margin-top:2px;">  
            <input type="file" name="file<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>" id="file<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>">
      </div>
      
      <div style="float:left; margin-left:5px; margin-top:3px;">
            <img src="<?php echo URL?>images/upload_icon.png" onclick="uploadFile('<?php echo $folder_id;?>','<?php echo $strRsSubFolderFiles->client_id;?>','<?php echo $sub_folder_id;?>')">
      </div>                  
      
      <div style="float:left; margin-left:10px; margin-top:4px;">
            <progress id="progressBar<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>" value="0" max="100" style="width:150px;"></progress>
            <a href="javascript:CloseUploadFile('<?php echo $folder_id;?>','<?php echo $sub_folder_id;?>')" style="font-size:12px;">Cancel</a>
            <div style="font-size:12px; color:#999999;" id="status<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>"></div>
            <span style="display:none;" id="loaded_n_total<?php echo $folder_id;?>_<?php echo $sub_folder_id;?>"></span>
      </div>
      
      <div class="clear"></div>		
     	
    </div>
    
    
           
</div>

<div style='display:none; margin-left:20px;' id='subfolder_content_<?php echo $sub_folder_id?>'>Loading...</div>

<?php }?>