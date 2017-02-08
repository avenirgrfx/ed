
<?php

require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/gallery.class.php');
$DB=new DB;
$Category=new Category;
$Gallery=new Gallery;

$strCategoryID=$_GET['id'];
$Gallery->category_id=$strCategoryID;
$strRsImageArr=$Gallery->ShowImage();

while($strImageArr=mysql_fetch_object($strRsImageArr))
{
?>			
	
    <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #CCCCCC; cursor:default; font-size:12px;">
  <tr>
    <td>
    	
        <table  style="text-align:left;" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="font-weight:bold;">
    <td width="45%"><div style="cursor:pointer;text-align:left" onClick="EditImageTitle('<?php echo $strImageArr->image_id?>')" id="ImageTitle-Edit-<?php echo $strImageArr->image_id?>"><?php echo "Image Name : " .$strImageArr->image_name?></div>
    <input type="hidden" name="edit-image-title-<?php echo $strImageArr->image_id?>" id="edit-image-title-<?php echo $strImageArr->image_id?>" value="" /></td>
    <td width="16%">Technical Files: </td>
    <td width="15%">3D Files: </td>
    <td width="15%">File</td>
    <td width="9%" align="center">Action</td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%" valign="top" style="padding:5px;">   
            <div class ="image"  onclick="lightbox(this)" style="cursor:pointer">
        <?php
			$SourceImage= (URL."images/control-images/".rawurlencode($strImageArr->image_path));
			$Title=$strImageArr->image_name;
			echo Globals::Resize($SourceImage, 200, 250, $Title='',$style='', $align='')?>
        <br>
        <?php echo $strImageArr->image_path;?>
            </div>
        </td>
       <td width="50%" valign="top" style="padding:5px;">  
           <div class="image" onclick="lightbox(this)" style="cursor:pointer">
            <?php
			$SourceImage= (URL."images/control-images/".rawurlencode($strImageArr->image_path2));
			$Title=$strImageArr->image_name;
			echo Globals::Resize($SourceImage, 200, 250, $Title='',$style='', $align='')?>
        <br>
         <?php echo $strImageArr->image_path2;?>
           </div>
        </td>
      </tr>
      <tr>
          <td colspan="2" style="text-align:left"><b>Description: </b>
   			<span style="cursor:pointer;" onClick="EditDescription('<?php echo $strImageArr->image_id?>')" id="Description-<?php echo $strImageArr->image_id?>"><?php echo $strImageArr->image_description;?></span>
            <input type="hidden" name="edit-desc-<?php echo $strImageArr->image_id?>" id="edit-desc-<?php echo $strImageArr->image_id?>" value="" />
             
          </td>
      </tr>
    </table>
    </td>
    <td valign="top">
    	<?php if($strImageArr->technical_file1<>""){			
			$strFileExtensionArr=explode(".",$strImageArr->technical_file1);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			
		?>       	
        	<a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->technical_file1;?>"><?php echo $strImageArr->technical_file1;?></a><br>
		<?php }?>
        
		<?php if($strImageArr->technical_file2<>"")
		{
			$strFileExtensionArr=explode(".",$strImageArr->technical_file2);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
		?>
        <a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->technical_file2;?>"><?php echo $strImageArr->technical_file2;?></a><br><?php }?>
		
		<?php if($strImageArr->technical_file3<>""){
		$strFileExtensionArr=explode(".",$strImageArr->technical_file3);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
		?>
        <a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->technical_file3;?>"><?php echo $strImageArr->technical_file3;?></a><br><?php }?>
		
		<?php if($strImageArr->technical_file4<>""){
			$strFileExtensionArr=explode(".",$strImageArr->technical_file4);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
		?>        
        <a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->technical_file4;?>"><?php echo $strImageArr->technical_file4;?></a><br><?php }?>
		
		
		<?php if($strImageArr->technical_file5<>""){
			$strFileExtensionArr=explode(".",$strImageArr->technical_file5);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
		?>
        <a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->technical_file5;?>"><?php echo $strImageArr->technical_file5;?></a><?php }?>
    </td>
    <td valign="top">
        	<?php if($strImageArr->	td_file1<>""){			
			$strFileExtensionArr=explode(".",$strImageArr->	td_file1);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			
		?>       	
        	<a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->td_file1;?>"><?php echo $strImageArr->td_file1;?></a><br>
		<?php }?>
        
		<?php if($strImageArr->	td_file2<>"")
		{
			$strFileExtensionArr=explode(".",$strImageArr->	td_file2);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
		?>
        <a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->	td_file2;?>"><?php echo $strImageArr->td_file2;?></a><br><?php }?>
		
		<?php if($strImageArr->	td_file3<>""){
		$strFileExtensionArr=explode(".",$strImageArr->	td_file3);
			$strFileExtension=strtolower($strFileExtensionArr[ count($strFileExtensionArr) -1]);
			
			if($strFileExtension=='pdf'){print '<img src="'.URL.'images/pdf.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='doc' or $strFileExtension=='docx'){print '<img src="'.URL.'images/word.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='xls' or $strFileExtension=='xlsx'){print '<img src="'.URL.'images/excel.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='ppt' or $strFileExtension=='pptx'){print '<img src="'.URL.'images/ppt.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='txt' or $strFileExtension=='rtf'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='dwg'){print '<img src="'.URL.'images/dwg.png" width="30px" style="margin-bottom:3px;" />';}
			elseif($strFileExtension=='jpg' or $strFileExtension=='png' or $strFileExtension=='bmp' or $strFileExtension=='tiff' or $strFileExtension=='jpeg' or $strFileExtension=='gif'){print '<img src="'.URL.'images/text.png" width="30px" style="margin-bottom:3px;" />';}
		?>
        <a target="_blank" href="<?php echo URL."uploads/documents/".$strImageArr->	td_file3;?>"><?php echo $strImageArr->td_file3;?></a><br><?php }?>
		
        
    </td>
    <td valign="top">Added By: Admin<br>
      File Date: <?php echo Globals::DateFormat($strImageArr->doc);?><br>
      Latest Update: <?php echo Globals::DateFormat($strImageArr->dom);?></td>
    <td align="center" valign="top"><a href="javascript:DeleteImage('<?php echo $strImageArr->image_id;?>','<?php echo $strCategoryID;?>')">Delete</a> | <a href="javascript:CloseImage('<?php echo $strCategoryID;?>')">Close</a></td>
  </tr>
</table>
    
    </td>
  </tr>
</table>



       
                


<?php
    }		
?>






