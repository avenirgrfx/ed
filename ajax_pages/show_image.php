<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$strID=$_GET['id'];

$strSQL="Select * from t_control_image where category_id=$strID or category_id in (Select category_id from t_category where  parent_id=$strID)";
$strRsCategoryImageArr=$DB->Returns($strSQL);
?>



<div style="height:140px; border-top:1px solid #CCCCCC; padding:3px; margin-top:10px; overflow:auto;">
<?php
$iCtr=0;
while($strRsCategoryImage=mysql_fetch_object($strRsCategoryImageArr))
{
?>
    <button type="button" class="btn image1" onclick="AddAjaxImage('<?php echo $strRsCategoryImage->image_path?>',1,1)" >
    	<?php
        	$SourceImage= (URL."images/control-images/".rawurlencode($strRsCategoryImage->image_path));
			$Title=$strRsCategoryImage->image_name;
			echo Globals::Resize($SourceImage, 100, 125, $Title='',$style='', $align='');
		?>
    
    </button>
    
<?php
}
?>
</div>

<div class="clear"></div>

