<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

if(isset($_POST) && !empty($_POST)){
    if($_POST['mode'] == "add"){
        $strSQL="insert into t_category (parent_id, category_name) values (0, '".$_POST['txtCategoryName']."')";
        $DB->Returns($strSQL);
        
        $strSQL="Select * from t_category where parent_id=0";
        $strRsCategoryArr=$DB->Returns($strSQL);
        print '<option value="0">Select Category</option>';
        
        while($strCategory=mysql_fetch_object($strRsCategoryArr)) {
            print '<option value="'.$strCategory->category_id.'">'.$strCategory->category_name.'</option>';
        }
    }else if($_POST['mode'] == "delete"){
        $strSQL="delete from t_category where category_id = '".$_POST['txtCategoryId']."'";
        $DB->Returns($strSQL);
    }else if($_POST['mode'] == "update"){
        $strSQL="update t_category set category_name = '".$_POST['txtCategoryName']."' where category_id = '".$_POST['txtCategoryId']."'";
        $DB->Returns($strSQL);
    }
    exit;
}
?>
<span style="background: rgb(153, 153, 153) none repeat scroll 0% 0%; text-align: center; border-radius: 13px; font-size:16px; height: 26px; width: 26px; float: right; cursor: pointer; margin-top: 10px;" onclick="closePopup();">X</span>
<div style="text-align: center;"><h2>Manage Category</h2></div>    
<?php
$strSQL="Select * from t_category where parent_id=0";
$strRsCategoryArr=$DB->Returns($strSQL);
while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
{
?>
<div style="width: 600px; padding: 10px 5px;">
    <span><?=$strRsCategory->category_name?></span>
    <span>
        <input type="button" style="float:right; padding: 2px 5px;" value="Delete" onclick="DeleteCategory('<?=$strRsCategory->category_id?>')">
        <input type="button" style="float:right; padding: 2px 5px;" value="Edit" onclick="EditCategory(this)">
        <input type="button" style="float:right; padding: 2px 5px; display: none;" value="Update" onclick="UpdateCategory(this, '<?=$strRsCategory->category_id?>')">
    </span>
</div>
<?php } ?>
<div class="clear" style="margin-bottom: 10px;"></div>