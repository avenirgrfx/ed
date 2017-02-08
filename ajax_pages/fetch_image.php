<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/gallery.class.php');
$DB=new DB;
$Category=new Category;
$Gallery=new Gallery;
?>

<strong style="font-size:14px;">Manage Gallery</strong>
    <br><br>
    <form action="" method="post" enctype="multipart/form-data" name="frmGallery" id="frmGallery">
        
        
        <div style="float:left; width:700px;">
        
      <div style="float:left; width:230px;">
            <select id="ddlImageCategroy" name="ddlImageCategroy" onblur="PreviewImage()" onchange="PreviewImage()" >    	
                <?php $Category->ListCategory();?>
            </select>
        </div>
        <div style="float:left; width:230px;">
        	<input type="text" id="txtGalleryName" name="txtGalleryName" placeholder="Enter Image Name" onblur="PreviewImage()" onchange="PreviewImage()" />
        </div>
        
         <div style="float:left; width:230px;">
        	<input type="text" id="txtGalleryDescription" name="txtGalleryDescription" placeholder="Enter Image Description" onblur="PreviewImage()" onchange="PreviewImage()" />
        </div>
        
        <div class="clear"></div>
        
        <div style="float:left; width:230px;">
        	<input type="text" id="txtTagName" name="txtTagName" placeholder="Enter Search Tags" onblur="PreviewImage()" onchange="PreviewImage()" />
        </div>       
        
        <div style="float:left; width:330px;">        	
        	  Image: <input type="file" name="file1" id="file1" onchange="PreviewImage();">
        </div>
        
        <div class="clear"></div>
        
        <strong>Add Technical Files</strong><br />
        
        <div style="float:left; width:330px;">        	
			File1: <input type="file" name="file2[]" id="file2[]"><br />           
            File3: <input type="file" name="file2[]" id="file2[]"><br />            
            File5: <input type="file" name="file2[]" id="file2[]">
        </div>
        
        <div style="float:left; width:330px;">
        	 File2: <input type="file" name="file2[]" id="file2[]"><br />
             File4: <input type="file" name="file2[]" id="file2[]"><br />
			 <input type="submit" id="btnSubmit" name="btnSubmit" value="Add to Gallery" style="font-weight:bold; font-size:14px;" />
        </div>
        
        
        <div class="clear"></div>
        
        <div style="float:left; width:680px; text-align:right;">
        	
        </div>
        
        <div class="clear">
          <input type="hidden" name="type" id="type" value="Gallery">
        </div>
        
        </div>
        
        <div style="float:left; border:1px solid #999999; border-radius:5px; padding:5px;">
        	<div style="float:left; width:100px;">
        		<img src="<?php echo URL?>images/no-image-selected.png" name="uploadPreview" id="uploadPreview" width="100" height="125" />
            </div>
            
            <div style="float:left; margin-left:10px; min-width:340px; font-size:12px;">
            	Name: <span id="Show_Image_Name" style="font-weight:bold; font-size:14px;"></span><br />
				Category: <span id="Show_Image_Category" style="font-weight:bold; font-size:14px;"></span><br />
				Description: <span id="Show_Image_Description" style="font-style:italic; width:250px; overflow:hidden;"></span><br />
				Tags: <span id="Show_Image_Tags"></span>
            </div>
            
            <div class="clear"></div>
            
        </div>
        
        <div class="clear"></div>
        
        
    </form>
    
    <hr style="border-bottom:1px #999999 dotted;">
    

    <div class="clear"></div>
    
    
    <ul style="cursor:pointer;">
    <?php
		$iCtr=0;
    	$strSQL="Select * from t_category where parent_id=0 order  by category_name asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{
			/*$strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsCategory->category_id;
			$strRsCount1Arr=$DB->Returns($strSQL);
			if($strRsCount1=mysql_fetch_object($strRsCount1Arr)){ $count1= $strRsCount1->Total; }*/
			
			$count1=$Category->CountGallery($strRsCategory->category_id);
			
			print "<li><b> <span onclick=ShowImages('".$strRsCategory->category_id."')>". $strRsCategory->category_name." (".$count1.")"."</span></b> &nbsp;&nbsp;<span id='Image-".$strRsCategory->category_id."'></span><ul>";
			$strSQL="Select * from t_category where parent_id=".$strRsCategory->category_id." order  by category_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				/*$strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsSubCat1->category_id;
				$strRsCount2Arr=$DB->Returns($strSQL);
				if($strRsCount2=mysql_fetch_object($strRsCount2Arr)){ $count2= $strRsCount2->Total; }*/
				
				$count2=$Category->CountGallery($strRsSubCat1->category_id);
				
				print "<li><span onclick=ShowImages('".$strRsSubCat1->category_id."')>".$strRsSubCat1->category_name." (".$count2.")"."</span>&nbsp;&nbsp;<span id='Image-".$strRsSubCat1->category_id."'></span><ul>";				
				$strSQL="Select * from t_category where parent_id=".$strRsSubCat1->category_id." order  by category_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					/*$strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsSubCat2->category_id;
					$strRsCount3Arr=$DB->Returns($strSQL);
					if($strRsCount3=mysql_fetch_object($strRsCount3Arr)){ $count3= $strRsCount3->Total; }*/
					
					$count3=$Category->CountGallery($strRsSubCat2->category_id);
					
					print "<li><span onclick=ShowImages('".$strRsSubCat2->category_id."')>".$strRsSubCat2->category_name." (".$count3.")"."</span>&nbsp;&nbsp;<span id='Image-".$strRsSubCat2->category_id."'></span><ul>";				
					$strSQL="Select * from t_category where parent_id=".$strRsSubCat2->category_id." order  by category_name asc";	
					$strRsSubCat3Arr=$DB->Returns($strSQL);
					while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
					{
						/*$strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsSubCat3->category_id;
						$strRsCount4Arr=$DB->Returns($strSQL);
						if($strRsCount4=mysql_fetch_object($strRsCount4Arr)){ $count4= $strRsCount4->Total; }*/
						
						$count4=$Category->CountGallery($strRsSubCat3->category_id);
						print "<li><span onclick=ShowImages('".$strRsSubCat3->category_id."')>".$strRsSubCat3->category_name." (".$count4.")"."</span>&nbsp;&nbsp;<span id='Image-".$strRsSubCat3->category_id."'></span></li>";
					}
					print "</ul></li>";
				}
				print "</ul></li>";
				
			}
			print "</ul></li>";
		}
	?>
    </ul>
    