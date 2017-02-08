<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/gallery.class.php');


$DB=new DB;
$Category=new Category;
?>

<strong style="font-size:14px;">Add a New Equipment Category</strong>
    <br><br>

<form id="frmCategory" name="frmCategory" action="" method="post">
        <div style="float:left; width:230px;">
            <select id="ddlCategroy" name="ddlCategroy">    	
                <?php $Category->ListCategory();?>
            </select>
        </div>
        <div style="float:left; width:230px;">
        	<input type="text" id="txtCategroyName" name="txtCategroyName" placeholder="New Category Name" />
        </div>
        
        
        <div style="float:left; width:500px;">
        	<input type="submit" id="btnSubmit" name="btnSubmit" value="Add" style="float:left;" />
            <input type="button" id="btnDelete" name="btnDelete" value="Delete" style="display:none; float:left; margin-left:5px;"  />
            <div style="float:left; margin-left:10px; font-size:12px; color:#666666; margin-top:5px;" id="CannotDelete"></div>
            <div class="clear"></div>
            
        </div>
        <div class="clear">
          <input type="hidden" name="type" id="type" value="Category">
          <input type="hidden" name="Category_ID" id="Category_ID" value="" />
        </div>
    </form>
    
    
 
    <hr style="border-bottom:1px #999999 dotted;">
    
    <form id="frmCategorySearch" name="frmCategorySearch" action="" method="post">
    	<div style="float:left; text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Equipment Database Search</div>
        <input type="text" id="txtCategroyName" name="txtCategroyName" placeholder="Search Equipment Category" style="float:left; margin-left:10px;" />
        <input type="submit" id="btnSubmit" name="btnSubmit" value="Search" style="float:left; margin-left:10px;"  />
    	<input type="hidden" name="type" id="type" value="CategorySearch">
        
        <div class="clear"></div>
        
    </form>
    
    
    <ul style="cursor:pointer; width:500px;">
    <?php
		$iCtr=0;
    	$strSQL="Select * from t_category where parent_id=0 order  by category_name asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{
			/*$iCtr++;
			if($iCtr>0) break;*/
			print "<li><b> <span onclick=CategoryOptions('".$strRsCategory->category_id."')>". $strRsCategory->category_name."</span></b> &nbsp;&nbsp;<span id='".$strRsCategory->category_id."'></span><ul>";
			$strSQL="Select * from t_category where parent_id=".$strRsCategory->category_id." order  by category_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				print "<li><span onclick=CategoryOptions('".$strRsSubCat1->category_id."')>".$strRsSubCat1->category_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat1->category_id."'></span><ul>";				
				$strSQL="Select * from t_category where parent_id=".$strRsSubCat1->category_id." order  by category_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{					
					print "<li><span onclick=CategoryOptions('".$strRsSubCat2->category_id."')>".$strRsSubCat2->category_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat2->category_id."'></span><ul>";				
					$strSQL="Select * from t_category where parent_id=".$strRsSubCat2->category_id." order  by category_name asc";	
					$strRsSubCat3Arr=$DB->Returns($strSQL);
					while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
					{
						print "<li><span onclick=CategoryOptions('".$strRsSubCat3->category_id."')>".$strRsSubCat3->category_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat3->category_id."'></span></li>";
					}
					print "</ul></li>";
				}
				print "</ul></li>";
				
			}
			print "</ul></li>";
		}
	?>
    </ul>