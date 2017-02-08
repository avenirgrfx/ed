<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

$client_id=$_SESSION['client_details']->client_id;
$strSQL="Select * from t_sites where client_id=$client_id";
$strRsClientSitesArr=$DB->Returns($strSQL);
$SitesCorporateArr=$DB->Returns($strSQL);
$SitesOperationArr=$DB->Returns($strSQL);
$SitesBillingArr=$DB->Returns($strSQL);
$SitesProgramsArr=$DB->Returns($strSQL);
$SitesFilesArr=$DB->Returns($strSQL); 

if($_POST)
{
	$AccessData="";
	$ArrPostData=$_POST;
	if(is_array($ArrPostData) && count($ArrPostData)>0)
	{
		foreach($ArrPostData as $key=>$Val)
		{
			if($key=="AccessName" or $key=="AccessType" or $key=="user_access_id" or $key=="GetAllSites_Corporate" or $key=="GetAllSites_Operations" or $key=="GetAllSites_Billing" or $key=="GetAllSites_Programs" or $key=="GetAllSites_Files")
				continue;
			$AccessData.=($key.";".$Val."@~@");
		}
	}
	
	
	$AccessName=$_POST['AccessName'];
	$GetAllSites_Corporate=str_replace("Site_Add_Div_Corporate_","",$_POST['GetAllSites_Corporate']);
	$GetAllSites_Operations=str_replace("Site_Add_Div_Operations_","",$_POST['GetAllSites_Operations']);
	$GetAllSites_Billing=str_replace("Site_Add_Div_Billing_","",$_POST['GetAllSites_Billing']);
	$GetAllSites_Programs=str_replace("Site_Add_Div_Programs_","",$_POST['GetAllSites_Programs']);
	$GetAllSites_Files=str_replace("Site_Add_Div_Files_","",$_POST['GetAllSites_Files']);
	
	if($_POST['user_access_id']=="")
	{
		$strSQL="Insert into t_customer_user_access(client_id, user_access_name, user_access, delete_flag, doc, created_by, dom, modified_by) Values($client_id, '$AccessName','$AccessData',0,now(),0,now(),0)";
		$Access_ID=$DB->Execute($strSQL);		
	}
	else
	{		
		$strSQL="Update t_customer_user_access set user_access_name='$AccessName', user_access='$AccessData' ,dom=now(), modified_by=0 Where user_access_id=".$_POST['user_access_id'];
		$DB->Execute($strSQL);		
		$Access_ID=$_POST['user_access_id'];		
	}
	
	$strSQL="Delete from t_customer_user_access_site where customer_user_access_id=$Access_ID";
	$DB->Execute($strSQL);
	
	
	if(is_array($GetAllSites_Corporate) && count($GetAllSites_Corporate)>0)
	{
		foreach($GetAllSites_Corporate as $Val1)
		{
			$strSQL="Insert into t_customer_user_access_site(customer_user_access_id, type, site_id) values($Access_ID,1,$Val1)";
			$DB->Execute($strSQL);
		}
	}
			

	if(is_array($GetAllSites_Operations) && count($GetAllSites_Operations)>0)
	{
		foreach($GetAllSites_Operations as $Val1)
		{
			$strSQL="Insert into t_customer_user_access_site(customer_user_access_id, type, site_id) values($Access_ID,2,$Val1)";
			$DB->Execute($strSQL);
		}
	}
	
	
	if(is_array($GetAllSites_Billing) && count($GetAllSites_Billing)>0)
	{
		foreach($GetAllSites_Billing as $Val1)
		{
			$strSQL="Insert into t_customer_user_access_site(customer_user_access_id, type, site_id) values($Access_ID,3,$Val1)";
			$DB->Execute($strSQL);
		}
	}
	
	if(is_array($GetAllSites_Programs) && count($GetAllSites_Programs)>0)
	{
		foreach($GetAllSites_Programs as $Val1)
		{
			$strSQL="Insert into t_customer_user_access_site(customer_user_access_id, type, site_id) values($Access_ID,4,$Val1)";
			$DB->Execute($strSQL);
		}
	}
	
	if(is_array($GetAllSites_Files) && count($GetAllSites_Files)>0)
	{
		foreach($GetAllSites_Files as $Val1)
		{
			$strSQL="Insert into t_customer_user_access_site(customer_user_access_id, type, site_id) values($Access_ID,5,$Val1)";
			$DB->Execute($strSQL);
		}
	}
	
	
	
	$strSQL="Delete from t_folder_customer_user_access where user_access_id=$Access_ID";
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
				$strSQL="Insert into t_folder_customer_user_access (user_access_id, folder_id, access_type) values($Access_ID, $folder_id, $access_type)";
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
				$strSQL="Insert into t_folder_customer_user_access (user_access_id, folder_id, access_type) values($Access_ID, $folder_id, $access_type)";
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
				$strSQL="Insert into t_folder_customer_user_access (user_access_id, folder_id, access_type) values($Access_ID, $folder_id, $access_type)";
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
	$strSQL="Select * from t_customer_user_access where user_access_id=$edit_id";
	$strRsUserAccesArr=$DB->Returns($strSQL);	
	
	$arrTempCustomerUserAccess=array();
	
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
					$arrTempCustomerUserAccess[ $AccessListArrVal[0] ]=$AccessListArrVal[1];
				}
			}
		}
	}
	
	
	$strSQL="SELECT t_customer_user_access_site.*, t_sites.site_name FROM t_customer_user_access_site LEFT JOIN t_sites on t_sites.site_id=t_customer_user_access_site.site_id WHERE customer_user_access_id=$edit_id And t_customer_user_access_site.type=1 order by site_name";
	$strRsSitesAllocatedArr1=$DB->Returns($strSQL);
	
	$strSQL="SELECT t_customer_user_access_site.*, t_sites.site_name FROM t_customer_user_access_site LEFT JOIN t_sites on t_sites.site_id=t_customer_user_access_site.site_id WHERE customer_user_access_id=$edit_id And t_customer_user_access_site.type=2 order by site_name";
	$strRsSitesAllocatedArr2=$DB->Returns($strSQL);
	
	$strSQL="SELECT t_customer_user_access_site.*, t_sites.site_name FROM t_customer_user_access_site LEFT JOIN t_sites on t_sites.site_id=t_customer_user_access_site.site_id WHERE customer_user_access_id=$edit_id And t_customer_user_access_site.type=3 order by site_name";
	$strRsSitesAllocatedArr3=$DB->Returns($strSQL);
	
	$strSQL="SELECT t_customer_user_access_site.*, t_sites.site_name FROM t_customer_user_access_site LEFT JOIN t_sites on t_sites.site_id=t_customer_user_access_site.site_id WHERE customer_user_access_id=$edit_id And t_customer_user_access_site.type=4 order by site_name";
	$strRsSitesAllocatedArr4=$DB->Returns($strSQL);
	
	$strSQL="SELECT t_customer_user_access_site.*, t_sites.site_name FROM t_customer_user_access_site LEFT JOIN t_sites on t_sites.site_id=t_customer_user_access_site.site_id WHERE customer_user_access_id=$edit_id And t_customer_user_access_site.type=5 order by site_name";
	$strRsSitesAllocatedArr5=$DB->Returns($strSQL);
	
}


?>

<style type="text/css">
#Template_Create tr td
{
	border:1px solid #CCCCCC;
}


#User_Access_List tr td
{
	border:1px solid #CCCCCC;
}

.Site_Add_Tag
{
	float:left; 
	background-color:#666666; 
	border-radius:2px; 
	padding:0px 2px; 
	color:#FFFFFF;
	margin:2px 3px;
}

</style>

<script type="text/javascript">

$('#btnSubmit').click(function(){
	
	
	if($("#chkSites_Corporate").is(':checked'))
		Sites_Corporate=1;
	else
		Sites_Corporate=0;
		
	if($("#chkSites_Operations").is(':checked'))
		Sites_Operations=1;
	else
		Sites_Operations=0;
	
	if($("#chkSites_Billing").is(':checked'))
		Sites_Billing=1;
	else
		Sites_Billing=0;
	
	if($("#chkSites_Programs").is(':checked'))
		Sites_Programs=1;
	else
		Sites_Programs=0;
	
	if($("#chkSites_Files").is(':checked'))
		Sites_Files=1;
	else
		Sites_Files=0;
	
	if($("#chkCorporate_Summary").is(':checked'))
		Corporate_Summary=1;
	else
		Corporate_Summary=0;
	
	if($("#chkOperations_Summary").is(':checked'))
		Operations_Summary=1;
	else
		Operations_Summary=0;
	
	if($("#chkOperations_Systems").is(':checked'))
		Operations_Systems=1;
	else
		Operations_Systems=0;
	
	if($("#chkOperations_Projects").is(':checked'))
		Operations_Projects=1;
	else
		Operations_Projects=0;
	
	if($("#chkOperations_Graphs").is(':checked'))
		Operations_Graphs=1;
	else
		Operations_Graphs=0;	
	
	if($("#chkOperations_Controls").is(':checked'))
		Operations_Controls=1;
	else
		Operations_Controls=0;
	
	if($("#chkOperations_Reports").is(':checked'))
		Operations_Reports=1;
	else
		Operations_Reports=0;
		
	if($("#chkBilling_Summary").is(':checked'))
		Billing_Summary=1;
	else
		Billing_Summary=0;
	
	if($("#chkPrograms_Summary").is(':checked'))
		Programs_Summary=1;
	else
		Programs_Summary=0;
	
	if($("#chkHomeUser_Template").is(':checked'))
		HomeUser_Template=1;
	else
		HomeUser_Template=0;
	
	if($("#chkHome_Users").is(':checked'))
		Home_Users=1;
	else
		Home_Users=0;
	

	var FolderView='';
	var FolderUpload='';
	var FolderDelete='';
	var GetAllSites_Corporate=GetAllSites('Corporate');
	var GetAllSites_Operations=GetAllSites('Operations');
	var GetAllSites_Billing=GetAllSites('Billing');
	var GetAllSites_Programs=GetAllSites('Programs');
	var GetAllSites_Files=GetAllSites('Files');
	
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
	
	
	
	$.post('<?php echo URL?>ajax_pages/customers/customer_user_access.php',
		{		
			
			user_access_id:$('#txtuser_access_id').val(),
			AccessName:$('#txtAccessName').val(),
			client_id:$('#client_id').val(),
			Sites_Corporate:Sites_Corporate, 
			Sites_Operations:Sites_Operations, 
			Sites_Billing:Sites_Billing, 
			Sites_Programs:Sites_Programs,
			Sites_Files:Sites_Files, 
			Corporate_Summary:Corporate_Summary, 
			Operations_Summary:Operations_Summary, 
			Operations_Systems:Operations_Systems, 
			Operations_Projects:Operations_Projects, 
			Operations_Graphs:Operations_Graphs, 
			Operations_Controls:Operations_Controls, 
			Operations_Reports:Operations_Reports, 
			Billing_Summary:Billing_Summary, 
			Programs_Summary:Programs_Summary, 
			HomeUser_Template:HomeUser_Template, 
			Home_Users:Home_Users,
			
			GetAllSites_Corporate:GetAllSites_Corporate,
			GetAllSites_Operations:GetAllSites_Operations,
			GetAllSites_Billing:GetAllSites_Billing,
			GetAllSites_Programs:GetAllSites_Programs,
			GetAllSites_Files:GetAllSites_Files,
			
			FolderView:FolderView,	
			FolderUpload:FolderUpload,
			FolderDelete:FolderDelete,
		},
	
		function(data){

			alert(data);
			$('#User_Template_Container').html(data);			
			
			$.get('<?php echo URL?>ajax_pages/customers/customer_user_access.php',{},function(data){
					$('#User_Template_Container').html(data);
			});
			
		});
});


function editUserAccess(AccessID)
{
	$.get('<?php echo URL?>ajax_pages/customers/customer_user_access.php',{edit_id:AccessID},function(data){
		$('#User_Template_Container').html(data);
	});
}

function AddSites(strType)
{	
	var Site_ID=$('#ddlSites_'+strType).val();
	
	if(Site_ID==0)
	{
		if(!confirm("This action will unselect all active sites. Continue?"))
			return false;
	}
	
	var Site_Name=$('#ddlSites_'+strType+' option:selected').text().trim();
	if(Site_ID==-1)
	{
		alert("Select a Site to Add");
		return;
	}
	
	if(Site_ID==0)
	{
		var AddSites_Div_Content='';
	}
	else
	{	
		var AddSites_Div_Content=$('#AddSites_'+strType+'_Div').html();
	}
	AddSites_Div_Content=AddSites_Div_Content+"<div class='Site_Add_Tag' id='Site_Add_Div_"+strType+"_"+Site_ID+"'>"+Site_Name;
	AddSites_Div_Content=AddSites_Div_Content+'<img onClick=DeleteSiteAdd("'+Site_ID+'","'+encodeURI(Site_Name)+'","'+strType+'") style="margin-left:2px; cursor:pointer;" src="<?php echo URL?>images/close_icon.png" alt="Delete" title="Delete" />';
	AddSites_Div_Content=AddSites_Div_Content+"</div>";
	$('#AddSites_'+strType+'_Div').html(AddSites_Div_Content);
	$('#ddlSites_'+strType+' option[value="'+Site_ID+'"]').remove();
	
	if(Site_ID==0)
	{
		$('#ddlSites_'+strType).attr('disabled', true);
		$('#Add_Link_Sites_'+strType).attr("href", "#");
		$('#Add_Link_Sites_'+strType).html('');
	}
	
}

function DeleteSiteAdd(Site_ID, Site_Name, strType)
{
	if(Site_ID==0)
	{
		$('#ddlSites_'+strType).attr('disabled', false);
		$('#Add_Link_Sites_'+strType).attr("href", "javascript:AddSites('"+strType+"');");
		$('#Add_Link_Sites_'+strType).html('Add');		
	}
	$('#Site_Add_Div_'+strType+'_'+Site_ID).remove();
	$("#ddlSites_"+strType).append(new Option(decodeURI(Site_Name), Site_ID));
	
	if(Site_ID==0)
	{
		var $options = $("#ddlSites_Client > option").clone();
		$('#ddlSites_'+strType).find('option').remove().end().append($options);
	}
}

function GetAllSites(strType)
{
	var ids = $.map($('#AddSites_'+strType+'_Div > div'), function(child) { return child.id; });
	return ids;
}

</script>

<div style="width:600px; float:left; margin-left:10px;">


<table width="98%" border="0" cellspacing="1" cellpadding="3" id="Template_Create" style="border:none;">
  
  <tr>
    <td width="83%"><strong>New Template Name</strong>
      <input type="text" name="txtAccessName" id="txtAccessName" value="<?php echo $Access_Name;?>" style="margin-left:5px;" />
    <input type="hidden" name="txtuser_access_id" id="txtuser_access_id" value="<?php echo $user_access_id?>" /> </td>
    </tr>
  
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td colspan="4" style="background-color:#EFEFEF;"><strong>Main Tabs</strong></td>
        <td style="background-color:#EFEFEF;">Allowed Sites</td>
      </tr>
      <tr>
        <td width="5%"><input name="chkSites_Corporate" type="checkbox" id="chkSites_Corporate" value="1" <?php if(array_key_exists("Sites_Corporate",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> ></td>
        <td width="25%">Corporate</td>
        <td colspan="2">
          
           <select name="ddlSites_Client" id="ddlSites_Client" style="width:150px; display:none;">
          		<option value="-1">Select Site</option>
                <option value="0">All Sites</option>
            <?php while($strRsClientSites=mysql_fetch_object($strRsClientSitesArr)){?>
            	<option value="<?php echo $strRsClientSites->site_id;?>"><?php echo $strRsClientSites->site_name;?></option>
            <?php }?>
          </select> 
          
          <select name="ddlSites_Corporate" id="ddlSites_Corporate" style="width:150px;">
          		<option value="-1">Select Site</option>
          		<option value="0">All Sites</option>
            <?php while($SitesCorporate=mysql_fetch_object($SitesCorporateArr)){?>
            	<option value="<?php echo $SitesCorporate->site_id;?>"><?php echo $SitesCorporate->site_name;?></option>
            <?php }?>
          </select> 
          <a href="javascript:AddSites('Corporate');" id="Add_Link_Sites_Corporate">Add</a>          </td>
        <td width="35%">
        	<div id="AddSites_Corporate_Div">
            <?php
            	while($strRsSitesAllocated1=mysql_fetch_object($strRsSitesAllocatedArr1))
				{					
					$Site_Name=rawurlencode($strRsSitesAllocated1->site_name=='' ? 'All Sites' : $strRsSitesAllocated1->site_name);
					$Site_ID=$strRsSitesAllocated1->site_id;					
					print "<div class='Site_Add_Tag' id='Site_Add_Div_Corporate_".$Site_ID."'>".str_replace("%20"," ",$Site_Name);
		            print '<img onClick=DeleteSiteAdd("'.$Site_ID.'","'.$Site_Name.'","Corporate") style="margin-left:2px; cursor:pointer;" src="'.URL.'images/close_icon.png" alt="Delete" title="Delete" />';
					print '</div>';
				?>
                	<script type="text/javascript">
                    	$('#ddlSites_Corporate option[value="'+<?php echo $Site_ID?>+'"]').remove();
                    </script>
                <?php			
				}			
			?>
            </div>        </td>
      </tr>
      <tr>
        <td>
        
        
        <input name="chkSites_Operations" type="checkbox" id="chkSites_Operations" value="1" <?php if(array_key_exists("Sites_Operations",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Operations</td>
        <td colspan="2">
          <select name="ddlSites_Operations" id="ddlSites_Operations" style="width:150px;">
          	<option value="-1">Select Site</option>
          	<option value="0">All Sites</option>
            <?php while($SitesOperation=mysql_fetch_object($SitesOperationArr)){?>
            	<option value="<?php echo $SitesOperation->site_id;?>"><?php echo trim($SitesOperation->site_name);?></option>
            <?php }?>
          </select>
          <a href="javascript:AddSites('Operations');" id="Add_Link_Sites_Operations">Add</a>        </td>
        <td><div id="AddSites_Operations_Div">
        <?php
			while($strRsSitesAllocated2=mysql_fetch_object($strRsSitesAllocatedArr2))
			{					
				$Site_Name=rawurlencode($strRsSitesAllocated2->site_name=='' ? 'All Sites' : $strRsSitesAllocated2->site_name);
				$Site_ID=$strRsSitesAllocated2->site_id;									
				print "<div class='Site_Add_Tag' id='Site_Add_Div_Operations_".$Site_ID."'>".str_replace("%20"," ",$Site_Name);
				print '<img onClick=DeleteSiteAdd("'.$Site_ID.'","'.$Site_Name.'","Operations") style="margin-left:2px; cursor:pointer;" src="'.URL.'images/close_icon.png" alt="Delete" title="Delete" />';
				print '</div>';	
			?>
            	<script type="text/javascript">
                    	$('#ddlSites_Operations option[value="'+<?php echo $Site_ID?>+'"]').remove();
                </script>
            <?php				
			}			
		?>        
        </div></td>
      </tr>
      <tr>
        <td><input name="chkSites_Billing" type="checkbox" id="chkSites_Billing" value="1" <?php if(array_key_exists("Sites_Billing",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Billing</td>
        <td colspan="2"><select name="ddlSites_Billing" id="ddlSites_Billing" style="width:150px;">
        	<option value="-1">Select Site</option>
            <option value="0">All Sites</option>
            <?php while($SitesBilling=mysql_fetch_object($SitesBillingArr)){?>
            <option value="<?php echo $SitesBilling->site_id;?>"><?php echo $SitesBilling->site_name;?></option>
            <?php }?>
          </select> 
          <a href="javascript:AddSites('Billing');" id="Add_Link_Sites_Billing">Add</a>          </td>
        <td><div id="AddSites_Billing_Div">
        
        <?php
			while($strRsSitesAllocated3=mysql_fetch_object($strRsSitesAllocatedArr3))
			{					
				$Site_Name=rawurlencode($strRsSitesAllocated3->site_name=='' ? 'All Sites' : $strRsSitesAllocated3->site_name);
				$Site_ID=$strRsSitesAllocated3->site_id;									
				print "<div class='Site_Add_Tag' id='Site_Add_Div_Billing_".$Site_ID."'>".str_replace("%20"," ",$Site_Name);
				print '<img onClick=DeleteSiteAdd("'.$Site_ID.'","'.$Site_Name.'","Billing") style="margin-left:2px; cursor:pointer;" src="'.URL.'images/close_icon.png" alt="Delete" title="Delete" />';
				print '</div>';
			?>
            	<script type="text/javascript">
                    $('#ddlSites_Billing option[value="'+<?php echo $Site_ID?>+'"]').remove();
                </script>
            <?php				
			}			
		?>
        </div></td>
      </tr>
      <tr>
        <td><input name="chkSites_Programs" type="checkbox" id="chkSites_Programs" value="1" <?php if(array_key_exists("Sites_Programs",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Programs</td>
        <td colspan="2"><select name="ddlSites_Programs" id="ddlSites_Programs" style="width:150px;">
        	<option value="-1">Select Site</option>
            <option value="0">All Sites</option>
            <?php while($SitesPrograms=mysql_fetch_object($SitesProgramsArr)){?>
            <option value="<?php echo $SitesPrograms->site_id;?>"><?php echo $SitesPrograms->site_name;?></option>
            <?php }?>
          </select>
          <a href="javascript:AddSites('Programs');" id="Add_Link_Sites_Programs">Add</a>           </td>
        <td><div id="AddSites_Programs_Div">
        <?php
			while($strRsSitesAllocated4=mysql_fetch_object($strRsSitesAllocatedArr4))
			{					
				$Site_Name=rawurlencode($strRsSitesAllocated4->site_name=='' ? 'All Sites' : $strRsSitesAllocated4->site_name);
				$Site_ID=$strRsSitesAllocated4->site_id;									
				print "<div class='Site_Add_Tag' id='Site_Add_Div_Programs_".$Site_ID."'>".str_replace("%20"," ",$Site_Name);
				print '<img onClick=DeleteSiteAdd("'.$Site_ID.'","'.$Site_Name.'","Programs") style="margin-left:2px; cursor:pointer;" src="'.URL.'images/close_icon.png" alt="Delete" title="Delete" />';
				print '</div>';
			?>
            	<script type="text/javascript">
                    $('#ddlSites_Programs option[value="'+<?php echo $Site_ID?>+'"]').remove();
                </script>
			<?php				
			}			
		?>        
        </div></td>
      </tr>
      <tr>
        <td><input name="chkSites_Files" type="checkbox" id="chkSites_Files" value="1" <?php if(array_key_exists("Sites_Files",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Files</td>
        <td colspan="2"><select name="ddlSites_Files" id="ddlSites_Files" style="width:150px;">
        	<option value="-1">Select Site</option>
            <option value="0">All Sites</option>
            <?php while($SitesFiles=mysql_fetch_object($SitesFilesArr)){?>
            <option value="<?php echo $SitesFiles->site_id;?>"><?php echo $SitesFiles->site_name;?></option>
            <?php }?>
          </select>
          <a href="javascript:AddSites('Files');" id="Add_Link_Sites_Files">Add</a>          </td>
        <td><div id="AddSites_Files_Div">
        <?php
			while($strRsSitesAllocated5=mysql_fetch_object($strRsSitesAllocatedArr5))
			{					
				$Site_Name=rawurlencode($strRsSitesAllocated5->site_name=='' ? 'All Sites' : $strRsSitesAllocated5->site_name);
				$Site_ID=$strRsSitesAllocated5->site_id;									
				print "<div class='Site_Add_Tag' id='Site_Add_Div_Files_".$Site_ID."'>".str_replace("%20"," ",$Site_Name);
				print '<img onClick=DeleteSiteAdd("'.$Site_ID.'","'.$Site_Name.'","Files") style="margin-left:2px; cursor:pointer;" src="'.URL.'images/close_icon.png" alt="Delete" title="Delete" />';
				print '</div>';
			?>
            	<script type="text/javascript">
                    $('#ddlSites_Files option[value="'+<?php echo $Site_ID?>+'"]').remove();
                </script>
            <?php				
			}			
		?>        
        </div></td>
      </tr>
      <tr>
        <td colspan="5" style="height:10px;"></td>
        </tr>
      <tr>
        <td colspan="5" style="background-color:#EFEFEF;"><strong>Corporate</strong></td>
        </tr>
      <tr>
        <td><input name="chkCorporate_Summary" type="checkbox" id="chkCorporate_Summary" value="1" <?php if(array_key_exists("Corporate_Summary",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?>  ></td>
        <td>Summary</td>
        <td width="5%">&nbsp;</td>
        <td width="30%">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" style="height:10px;"></td>
        </tr>
      <tr>
        <td colspan="5" style="background-color:#EFEFEF;"><strong>Operations</strong></td>
        </tr>
      <tr>
        <td><input name="chkOperations_Summary" type="checkbox" id="chkOperations_Summary" value="1" <?php if(array_key_exists("Operations_Summary",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Summary</td>
        <td><input name="chkOperations_Graphs" type="checkbox" id="chkOperations_Graphs" value="1" <?php if(array_key_exists("Operations_Graphs",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Graphs</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input name="chkOperations_Systems" type="checkbox" id="chkOperations_Systems" value="1" <?php if(array_key_exists("Operations_Systems",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Systems</td>
        <td><input name="chkOperations_Controls" type="checkbox" id="chkOperations_Controls" value="1" <?php if(array_key_exists("Operations_Controls",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Controls</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input name="chkOperations_Projects" type="checkbox" id="chkOperations_Projects" value="1" <?php if(array_key_exists("Operations_Projects",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Projects</td>
        <td><input name="chkOperations_Reports" type="checkbox" id="chkOperations_Reports" value="1" <?php if(array_key_exists("Operations_Reports",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Reports</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" style="background-color:#EFEFEF;"><strong>Billing</strong></td>
        </tr>
      <tr>
        <td><input name="chkBilling_Summary" type="checkbox" id="chkBilling_Summary" value="1" <?php if(array_key_exists("Billing_Summary",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Summary</td>
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
      </tr>
      <tr>
        <td colspan="5" style="background-color:#EFEFEF;"><strong>Programs</strong></td>
        </tr>
      <tr>
        <td><input name="chkPrograms_Summary" type="checkbox" id="chkPrograms_Summary" value="1" <?php if(array_key_exists("Programs_Summary",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Summary</td>
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
      </tr>
      <tr>
        <td colspan="5" style="background-color:#EFEFEF;"><strong>Home</strong></td>
        </tr>
      <tr>
        <td><input name="chkHomeUser_Template" type="checkbox" id="chkHomeUser_Template" value="1" <?php if(array_key_exists("HomeUser_Template",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>User Template</td>
        <td><input name="chkHome_Users" type="checkbox" id="chkHome_Users" value="1" <?php if(array_key_exists("Home_Users",$arrTempCustomerUserAccess)){?>checked="checked"<?php }?> /></td>
        <td>Users</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" style="background-color:#EFEFEF;"><strong>Files</strong></td>
        </tr>
      <tr>
        <td colspan="5">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
						
						$strSQL="Select * from t_folder_customer_user_access where user_access_id=$edit_id and folder_id=".$strRsFolderNames->folder_id;
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
          </table>        </td>
        </tr>
      
      <tr>
        <td colspan="5" style="height:10px;"></td>
        </tr>
    </table>
      <br>
      <br></td>
  </tr>
  <tr>
    <td><input type="button" name="btnSubmit" id="btnSubmit" value="Submit">
      <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id;?>" /></td>
  </tr>
</table>
</div>


<div id="User_Access_List" style="width:450px; float:left;">
<div style="font-size:16px; font-weight:bold; margin-bottom:10px; margin-top:10px; border-bottom:1px solid #CCCCCC;">User Template List</div>

<?php
	
	$arrColor=array('#333333','#0000FF', '#0000DD','#0099CC','#33CCCC','#666666','#6699FF');
	
	$ArrAccessListDisplay=array(
	'Sites_Corporate'=>'Sites Corporate', 
	'Sites_Operations'=>'Sites Operations', 
	'Sites_Billing'=>'Sites Billing', 
	'Sites_Programs'=>'Sites Programs',
	'Sites_Files'=>'Sites Files', 
	'Corporate_Summary'=>'Corporate Summary', 
	'Operations_Summary'=>'Operations Summary', 
	'Operations_Systems'=>'Operations Systems', 
	'Operations_Projects'=>'Operations Projects', 
	'Operations_Graphs'=>'Operations Graphs', 
	'Operations_Controls'=>'Operations Controls', 
	'Operations_Reports'=>'Operations Reports', 
	'Billing_Summary'=>'Billing Summary', 
	'HomeUser_Template'=>'HomeUser Template', 
	'Home_Users'=>'Home Users', 
	);
	
	
	$strSQL="Select * from t_customer_user_access where client_id=$client_id order by user_access_name";
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

<div class="clear"></div>
