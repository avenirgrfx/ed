<?php
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

if($_GET['mode']=='del' and $_GET['id']<>'')
{
	$strSQL="Delete from t_software_version_relation where software_version_id=".$_GET['id'];
	$DB->Execute($strSQL);
	exit();
}

if($_POST)
{
	$SoftwareVersion=$_POST['SoftwareVersion'];
	$RelationArr=explode(",",$_POST['Relation']);
	if(is_array($RelationArr) && count($RelationArr)>0)
	{
		foreach($RelationArr as $Val)
		{
			if($Val==0)
				continue;
				
			$strSQL="Insert into t_software_version_relation (software_version_id,relation_id)
			Values($SoftwareVersion,$Val)";
			$DB->Execute($strSQL);
		}
	}
	print "Updated";
	exit();
}


$strSQL="Select * from t_software_version where software_version_id not in(select distinct software_version_id from  t_software_version_relation ) order by software_version";
$strRsSoftwareVersionArr=$DB->Returns($strSQL);
?>

<script type="text/javascript">

var CheckedVal=new Array();

$(function(){
	$('#btnAddVersion').click(function(){
		var SoftwareVersion=$('#ddlSoftwareVersion').val();
		var Relation='0';
		
		//alert(CheckedVal[0]);
		for(var i=0; i<CheckedVal.length; i++)
		{
			Relation=Relation+','+CheckedVal[i];
		}
		
		$.post('<?php echo URL?>ajax_pages/add_new_package_view.php',{SoftwareVersion:SoftwareVersion,Relation:Relation},
		
			function(data){
			
				$.get("<?php echo URL?>ajax_pages/add_new_package_view.php",
			    {
					id:0
			    },
			    function(data,status){						
					$('#Category_Container').html(data);
				});
		});
	});
});

function CheckVersionVal(strID)
{
	if($('#chkVersion_'+strID).is(':checked')) 
	{
		CheckedVal[CheckedVal.length]=strID;
	}
}

function DeletePackageView(strID)
{
	if(!confirm("Are you sure you want to Delete?"))
		return false;
	
	$.get('<?php echo URL?>ajax_pages/add_new_package_view.php',{mode:'del',id:strID},function(data){
		
		$.get("<?php echo URL?>ajax_pages/add_new_package_view.php",
		{
			id:0
		},
		function(data,status){						
			$('#Category_Container').html(data);
		});
		
	});
}

</script>

<style type="text/css">
table  tr td
{
	border:1px solid #CCCCCC;
	padding:3px;
	color:#666666;
}

</style>

<strong style="font-size:16px;">Package View</strong><br />

<select name="ddlSoftwareVersion" id="ddlSoftwareVersion">
	<option value="">Select Version</option>
<?php
while($strRsSoftwareVersion=mysql_fetch_object($strRsSoftwareVersionArr))
{
?>
	<option value="<?php echo $strRsSoftwareVersion->software_version_id; ?>"><?php echo $strRsSoftwareVersion->software_version;?></option>
<?php
}
?>
</select>


<br />

<?php
$iCtr=0;
$strSQL="Select * from t_software_version order by software_version";
$strRsSoftwareVersionArr=$DB->Returns($strSQL);
while($strRsSoftwareVersion=mysql_fetch_object($strRsSoftwareVersionArr))
{
	$iCtr++;
	print "<div style='float:left; width:200px;'>
		<div style='float:left;'>
			<input type='checkbox' onclick='CheckVersionVal(".$strRsSoftwareVersion->software_version_id.")' value='1' name='chkVersion_".$strRsSoftwareVersion->software_version_id."' id='chkVersion_".$strRsSoftwareVersion->software_version_id."' />
		</div>
		<div style='float:left; margin-left:3px; margin-top:4px;'>".$strRsSoftwareVersion->software_version."</div>
		<div class='clear'></div>
	</div>";
	
	if($iCtr % 5==0)
	{
		print "<div class='clear'></div>";
	}
}
?>
<div class="clear" style="margin-bottom:5px;"></div>
<input type="button" name="btnAddVersion" id="btnAddVersion" value="Add" />

<?php
$strSQL="Select Distinct(t_software_version_relation.software_version_id),  t_software_version.software_version from t_software_version_relation, t_software_version where t_software_version_relation.software_version_id=t_software_version.software_version_id";
$strRsPackageViewListArr=$DB->Returns($strSQL);	
if(mysql_num_rows($strRsPackageViewListArr)>0)
{
?>
<br><br>
<strong style="font-size:16px;">Active Package View</strong><br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#EFEFEF;">
    <td><strong>Version</strong></td>
    <td><strong>View Option</strong></td>
  </tr>
  <?php while($strRsPackageViewList=mysql_fetch_object($strRsPackageViewListArr)){?>
   <tr>
    <td>
	
		<div style="font-weight:bold;float:left;"><?php echo $strRsPackageViewList->software_version; ?></div>
        <div style="font-size:12px;float:right;"><a href="javascript:DeletePackageView('<?php echo $strRsPackageViewList->software_version_id;?>')">Delete</a></div>
    	<div class="clear"></div>
    </td>
    <td>
    	<?php
			$strSQL="Select relation_id from t_software_version_relation where software_version_id=".$strRsPackageViewList->software_version_id;
			$strRsRelationsArr1=$DB->Returns($strSQL);
			while($strRsRelations1=mysql_fetch_object($strRsRelationsArr1))
			{
				$strSQL="Select software_version from t_software_version where software_version_id=".$strRsRelations1->relation_id;
				$strRsRelationsArr=$DB->Returns($strSQL);
				while($strRsRelations=mysql_fetch_object($strRsRelationsArr))
				{
					print $strRsRelations->software_version.", ";
				}
			}
			
		?>
    </td>
  </tr>
  <?php }?>
</table>
<?php }?>
