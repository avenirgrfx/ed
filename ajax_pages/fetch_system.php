<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');


$DB=new DB;
$System=new System;
?>

<script type="text/javascript">
$(document).ready(function(){

	$('input[name="chkHasWidget"]').on('click', function(){
		if ( $(this).is(':checked') ) {
		   $('#Available_Widget_List').slideDown('slow');
		} 
		else {
		   $('#Available_Widget_List').slideUp('slow');
		}

	});

});
</script>
<div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Serial Management</div>
<strong style="font-size:14px;">Add a New System</strong>
    <br><br>

<form id="frmSystem" name="frmSystem" action="" method="post">
        <div style="float:left; width:230px;">
            <select id="ddlSystem" name="ddlSystem">    	
                <?php $System->ListSystem();?>
            </select>
        </div>
        <div style="float:left; width:230px;">
        	<input type="text" id="txtSystemName" name="txtSystemName" placeholder="New System Name" />
        </div>
        <div style="float:left; width:150px;">
        	<input type="checkbox" value="1" name="chkHasWidget" id="chkHasWidget" /> Node
        </div>
        
        <div style="float:left; width:500px;">
        	<input type="submit" id="btnSubmit" name="btnSubmit" value="Add" style="float:left;" />
            <input type="button" id="btnDelete" name="btnDelete" value="Delete" style="display:none; float:left; margin-left:5px;"  />
            <div style="float:left; margin-left:10px; font-size:12px; color:#666666; margin-top:5px;" id="CannotDelete"></div>
            <div class="clear"></div>
            
        </div>
        <div class="clear">
          <input type="hidden" name="type" id="type" value="System">
          <input type="hidden" name="System_ID" id="System_ID" value="" />
        </div>
    </form>
    
    <div id="Available_Widget_List" style="display:none;">
    <?php
    	$strSQL="Select * from t_widgets order by widget_name asc";
		$strRsWidgetsArr=$DB->Returns($strSQL);
		$iCtrSystem=0;
		while($strRsWidgets=mysql_fetch_object($strRsWidgetsArr))
		{
			$iCtrSystem++;			
			print '<div style="float:left; width:300px;"><input type="checkbox" value="1" name="chkWidgetID_'.$strRsWidgets->widget_id.'" id="chkWidgetID_'.$strRsWidgets->widget_id.'" />'.$strRsWidgets->widget_name.'</div>';
			if($iCtrSystem % 3==0)
				print '<div class="clear;"></div>';
		}
	?>
    <div class="clear"></div>
 	</div>
    
    <hr style="border-bottom:1px #999999 dotted;">
    
    
    <form id="frmCategorySearch" name="frmCategorySearch" action="" method="post">
    	<div style="float:left; text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">System Database Search</div>
        <input type="text" id="txtCategroyName" name="txtCategroyName" placeholder="Search Master System" style="float:left; margin-left:10px;" />
        <input type="submit" id="btnSubmit" name="btnSubmit" value="Search" style="float:left; margin-left:10px;"  />
    	<input type="hidden" name="type" id="type" value="CategorySearch">
        
        <div class="clear"></div>
        
    </form>
    
    
    <ul style="cursor:pointer; width:500px;">
    <?php
		$iCtr=0;
    	$strSQL="Select * from t_system where parent_id=0 order  by system_name asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{
			print "<li><b> <span onclick=SystemOptions('".$strRsCategory->system_id."')>". $strRsCategory->system_name."</span></b> &nbsp;&nbsp;<span id='".$strRsCategory->system_id."'></span><ul>";
			$strSQL="Select * from t_system where parent_id=".$strRsCategory->system_id." order  by system_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				$strHasNodeStyle="";
				if($strRsSubCat1->has_node==1)
				{
					$strHasNodeStyle='text-decoration:underline; font-style: italic; ';
				}
				print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('".$strRsSubCat1->system_id."')>".$strRsSubCat1->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat1->system_id."'></span><ul>";				
				$strSQL="Select * from t_system where parent_id=".$strRsSubCat1->system_id." order  by system_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					$strHasNodeStyle="";
					if($strRsSubCat2->has_node==1)
					{
						$strHasNodeStyle='text-decoration:underline; font-style: italic; ';
					}
					print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('".$strRsSubCat2->system_id."')>".$strRsSubCat2->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat2->system_id."'></span><ul>";				
					$strSQL="Select * from t_system where parent_id=".$strRsSubCat2->system_id." order  by system_name asc";	
					$strRsSubCat3Arr=$DB->Returns($strSQL);
					while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
					{
						$strHasNodeStyle="";
						if($strRsSubCat3->has_node==1)
						{
							$strHasNodeStyle='text-decoration:underline; font-style: italic; ';
						}
						print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('".$strRsSubCat3->system_id."')>".$strRsSubCat3->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat3->system_id."'></span><ul>";
						$strSQL="Select * from t_system where parent_id=".$strRsSubCat3->system_id." order  by system_name asc";	
						$strRsSubCat4Arr=$DB->Returns($strSQL);
						while($strRsSubCat4=mysql_fetch_object($strRsSubCat4Arr))
						{
							$strHasNodeStyle="";
							if($strRsSubCat4->has_node==1)
							{
								$strHasNodeStyle='text-decoration:underline; font-style: italic; ';
							}
							print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('".$strRsSubCat4->system_id."')>".$strRsSubCat4->system_name."</span>&nbsp;&nbsp;<span  id='".$strRsSubCat4->system_id."'></span></li>";
						}
						print "</ul></li>";
						
					}
					print "</ul></li>";
				}
				print "</ul></li>";
				
			}
			print "</ul></li>";
		}
	?>
    </ul>