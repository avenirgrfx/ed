<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');


$DB=new DB;
$System=new System;
?>
<div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Serial Management</div>
<strong style="font-size:14px;">Add a New System</strong>
    <br><br>

<form id="frmSystem" name="frmSystem" action="<?php echo URL?>engineers/" method="post">
        <div style="float:left; width:220px;">
            <select id="ddlSystem" name="ddlSystem" style="width:200px;">    	
                <?php $System->ListSystem();?>
            </select>
        </div>
        <div style="float:left; width:220px;">
        	<input type="text" id="txtSystemName" name="txtSystemName" placeholder="New System Name" style="width:200px;" />
        </div>
        <div style="float:left; margin-right:5px">
        	<select id="ddlUtilityClass" name="ddlUtilityClass" style=" width:160px;">
            	<option value="0">Select Utility Class</option>
                <option value="1">Electrical</option>
                <option value="2">Natural Gas</option>
                <option value="3">Water</option>
                <option value="4">Steam</option>
                <option value="5">Fuel</option>
                <option value="6">Other Gases</option>
            </select>
        </div>
        
        <div style="float:left; margin-right:5px;">
        	<select id="ddlUtilityUOM" name="ddlUtilityUOM" style="width:170px;">
            	<option value="">Unit of Measurement</option>
                <option value="CCF">CCF</option>
                <option value="CNG">CNG</option>
                <option value="DGE">DGE</option>
                <option value="Gallons">Gallons</option>
                <option value="gWh">gWh</option>                
                <option value="GGE">GGE</option>                 
                <option value="kWh">kWh</option>
                <option value="MCF">MCF</option>
                <option value="Metric Tons">Metric Tons</option>
                <option value="MMBTU">MMBTU</option>                
                <option value="mWh">mWh</option>               
                <option value="Tons">Tons</option>                
            </select>
        </div>
        
        <div style="float:left; margin-right:5px;">
        	<select id="ddlUnitComplexityLevel" name="ddlUnitComplexityLevel" style="width:110px;">
            	<option value="0" selected="selected">Node Level</option>
                <option value="1">Simple</option>
                <option value="2">Complex</option>
                <option value="3">Specialized</option>                         
            </select>
        </div>
       
       	<div style="float:left; margin-right:10px;">
        	<div style="float:left;"><input type="checkbox" name="chkExcludeCalculation" id="chkExcludeCalculation" value="1" /></div>
			<div style="float:left; margin-top:2px; margin-left:2px;"><span style="font-size:12px;">Exclude in Consumption</span></div>
            <div class="clear"></div>
        </div>
       
        <div style="float:left; width:200px;">
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
    
    
    
    <hr style="border-bottom:1px #999999 dotted;">
    
    
    <form id="frmCategorySearch" name="frmCategorySearch" action="" method="post">
    	<div style="float:left; text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">System Database Search</div>
        <input type="text" id="txtCategroyName" name="txtCategroyName" placeholder="Search Master System" style="float:left; margin-left:10px;" />
        <input type="submit" id="btnSubmit" name="btnSubmit" value="Search" style="float:left; margin-left:10px;"  />
    	<input type="hidden" name="type" id="type" value="CategorySearch">
        
        <div class="clear"></div>
        
    </form>
    
    
    <ul style="cursor:pointer; width:600px;">
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
					
					$iAlternate=0;
					while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
					{
						$iAlternate++;
						$strHasNodeStyle="";
						if($strRsSubCat3->has_node==1)
						{
							$strHasNodeStyle='text-decoration:underline; font-style: italic; ';
						}
						
						
						if($iAlternate % 2==0)
							$strAlternate='background-color:#EFEFEF;';
						else
							$strAlternate='background-color:#CDCDCD;';
						
						
						$complexity=($strRsSubCat3->complexity==1 ? 'Simple' : ($strRsSubCat3->complexity==2 ? 'Complex' : ($strRsSubCat3->complexity==3 ? 'Specialized': '' ) ));
						if($complexity<>"")
							$complexity="($complexity)";
						
						print "<li style='$strAlternate '>
						<div style='width:350px; float:left; margin-top:-20px;'><span style='$strHasNodeStyle' onclick=SystemOptions('".$strRsSubCat3->system_id."')>".$strRsSubCat3->system_name."</span> <span style='margin-left:5px; font-size:11px;'>".$complexity."</span></div>
						<div style='float:left; margin-top:-20px; margin-left:350px;'><a href='javascript:LoadSystemNodeDetails(".$strRsSubCat3->system_id.")'>Manage Node</a></div> 
						<span id='".$strRsSubCat3->system_id."'></span>
						<div class='clear'></div>
						<ul>";
						
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