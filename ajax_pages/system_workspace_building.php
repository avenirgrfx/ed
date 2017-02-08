<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
$DB = new DB;
$System = new System();
$Category = new Category;
$Gallery = new Gallery;

?>

<script>
    function ShowBuildingImages(strSiteId){
        $.post("<?= URL ?>/ajax_pages/system_workspace_building.php",
           {
               siteid:strSiteId,
           },    
           function(data,status){
               $('#'+strSiteId).html(data);
           }

        );
    }

    function PlusMinusBuildingImage(strbuildingid){
        $.post("<?= URL ?>/ajax_pages/system_workspace_building.php",
            {
                strbuilding: strbuildingid,
            },
            function (data, status) {
                $("#" + strbuildingid).html(data);
            }
        );
    }

    function ImageUpload(imageurl) {
        var file_id = $(imageurl).attr("id");
        var building_id = $(imageurl).attr("building_id");
        var id_num = file_id.split('_')[2];
        console.log(id_num);

        var formData = new FormData();
        formData.append('file', $('#' + file_id)[0].files[0]);
        formData.append('mode', "add");
        formData.append('file_num', id_num);
        formData.append('building_id', building_id);

        $.ajax({
            type: "POST",
            url: "<?= URL ?>/ajax_pages/system_workspace_building.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if(data != ''){
                   $('#building_image_'+id_num+' img').attr('src', "<?= URL ?>uploads/building/"+$('#' + file_id).val());
                   $('#building_link_'+id_num).trigger('click');
                }
                //$("#divider").html(data);
            }
        });

    }
    
</script>

<?php
if(!empty($_POST['clientid'])){
   
$strSiteID=$_POST['clientid'];
$strSQL="Select * from t_sites where client_id=$strSiteID order by site_name";
$strRsSiteArr=$DB->Returns($strSQL);
while($strRsSite=mysql_fetch_object($strRsSiteArr))
{
	echo "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=ShowBuildingImages('".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
	<div id='".$strRsSite->site_id."'></div><div class='clear'></div>";
}}
?>

<?php
if(!empty($_POST['siteid'])){
$strSiteID = $_POST['siteid'];

$strSQL = "Select * from t_building where site_id=$strSiteID";
$strRsBuildingArr = $DB->Returns($strSQL);

while ($strRsBuilding = mysql_fetch_object($strRsBuildingArr)) {
    ?>
     <div class="clear"></div>
    
    <hr style="border-bottom:1px #999999 dotted;">
    <?php 
    //echo "<div class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:normal;'>Building:</span> ".$strRsBuilding->building_name."</div>";
    echo "<div onclick='PlusMinusBuildingImage(" . $strRsBuilding->building_id . ")' class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:bold; font-size:20px;' id='Building_Details_Plus_Minus_" . $strRsBuilding->building_id . "'>-</span><span style='font-weight:normal;'>Building:</span> <span style='text-decoration:underline;'>" . $strRsBuilding->building_name . "</span></div>";
    echo '<div id="'.$strRsBuilding->building_id.'">';
    ?>
   

    <div class='clear'></div>
<?php 
}}
?>
<?php
if (isset($_POST['mode']) && !empty($_POST['mode'])) {
    if ($_POST['mode'] == "add") {
        $ImageFile = '';
        $file_num = $_POST['file_num'];
        
        if ($_FILES['file']['name'][0]) {
            $uploaddir = AbsPath . '/uploads/building/';
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $ImageFile = $_FILES['file']['name'];
            } else {
                //echo "Couldn't upload Technical File 1 !\n";
                $ImageFile = '';
            }
        }

        $building_id = $_POST['building_id'];
        $column_name = "building_image$file_num";        
        
//        if ($_POST['file_num'] == "file2_1") {
//            $Gallery->building_image1 = $technical_file1;
//        } elseif ($_POST['file_id'] == "file2_2") {
//            $Gallery->building_image2 = $technical_file1;
//        } elseif ($_POST['file_id'] == "file2_3") {
//            $Gallery->building_image3 = $technical_file1;
//        } elseif ($_POST['file_id'] == "file2_4") {
//            $Gallery->building_image4 = $technical_file1;
//        } elseif ($_POST['file_id'] == "file2_5") {
//            $Gallery->building_image5 = $technical_file1;
//        }

        $strSQL = "select * from t_building_image where building_id=" . $_POST['building_id'];
        $strBuildingImageArr = $DB->Returns($strSQL);

        if ((mysql_num_rows($strBuildingImageArr) == 0)) {
//            $Gallery->InsertBuildingImage();
            $strQuery = "Insert into t_building_image (building_id, $column_name) Values (".$building_id.",'".$ImageFile."')";
            $DB->Execute($strQuery);
        } else {
//            $Gallery->UpdateBuildingImage($_POST['building_id'], $technical_file1, $_POST['file_id']);
            $strQuery = "update t_building_image set $column_name = '$ImageFile' where building_id=$building_id";
            $DB->Execute($strQuery);
        }
        echo $ImageFile;
    } else if ($_POST['mode'] == "delete") {
//        $strSQL="delete from t_system where fuel_type_id = '".$_POST['txtFuelTypeId']."'";
//        $DB->Returns($strSQL);
    } else if ($_POST['mode'] == "update") {
//        $strSQL="update t_system set fuel_type = '".$_POST['txtFuelTypeName']."', unit = '".$_POST['ddlFuelTypeUnit']."' where fuel_type_id = '".$_POST['txtFuelTypeId']."'";
//        $DB->Returns($strSQL);
    }
    exit;
}
?> 
<?php if(!empty($_POST['strbuilding'])){
        $building_id=$_POST['strbuilding'];
        
        $strSQL="select * from t_building_image where building_id=".$building_id;
        $strBuildingImageArr = $DB->Returns($strSQL);
        
        if($strBuildingImage = mysql_fetch_object($strBuildingImageArr))
?>
    <div class="clear"></div>

    <div style="float:left; width:500px;margin-left:30px;">    
        <strong style="margin-left:100px;">Add Building Image Files</strong>
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 95px;">ISOMETRIC  :</span> <?php if(isset($strBuildingImage->building_image1) && $strBuildingImage->building_image1 != "") { echo '<input type="button" style="width: 85px; height: 30px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span> '.$strBuildingImage->building_image1.'</span><input type="file" name="file2[]" id="image_file_1" building_id="'.$building_id.'" style="display:none;" onchange="ImageUpload(this)">'; } else { ?><input type="file" name="file2[]" id="image_file_1" building_id="<?=$building_id?>" onchange="ImageUpload(this)"><?php } ?></div>           
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 95px;">FRONT VIEW :</span> <?php if(isset($strBuildingImage->building_image2) && $strBuildingImage->building_image2 != "") { echo '<input type="button" style="width: 85px; height: 30px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span> '.$strBuildingImage->building_image2.'</span><input type="file" name="file2[]" id="image_file_2" building_id="'.$building_id.'" style="display:none;" onchange="ImageUpload(this)">'; } else { ?><input type="file" name="file2[]" id="image_file_2" building_id="<?=$building_id?>" onchange="ImageUpload(this)"><?php } ?></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 95px;">TOP VIEW   :</span> <?php if(isset($strBuildingImage->building_image3) && $strBuildingImage->building_image3 != "") { echo '<input type="button" style="width: 85px; height: 30px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span> '.$strBuildingImage->building_image3.'</span><input type="file" name="file2[]" id="image_file_3" building_id="'.$building_id.'" style="display:none;" onchange="ImageUpload(this)">'; } else { ?><input type="file" name="file2[]" id="image_file_3" building_id="<?=$building_id?>" onchange="ImageUpload(this)"><?php } ?></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 95px;">RIGHT VIEW :</span> <?php if(isset($strBuildingImage->building_image4) && $strBuildingImage->building_image4 != "") { echo '<input type="button" style="width: 85px; height: 30px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span> '.$strBuildingImage->building_image4.'</span><input type="file" name="file2[]" id="image_file_4" building_id="'.$building_id.'" style="display:none;" onchange="ImageUpload(this)">'; } else { ?><input type="file" name="file2[]" id="image_file_4" building_id="<?=$building_id?>" onchange="ImageUpload(this)"><?php } ?></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 95px;">LEFT VIEW  :</span> <?php if(isset($strBuildingImage->building_image5) && $strBuildingImage->building_image5 != "") { echo '<input type="button" style="width: 85px; height: 30px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span> '.$strBuildingImage->building_image5.'</span><input type="file" name="file2[]" id="image_file_5" building_id="'.$building_id.'" style="display:none;" onchange="ImageUpload(this)">'; } else { ?><input type="file" name="file2[]" id="image_file_5" building_id="<?=$building_id?>" onchange="ImageUpload(this)"><?php } ?></div> 
    </div>
    
    <div class="imageNav" style="float:right;margin-right:70px;width:550px">
        <span id="building_link_1" style="cursor: pointer; color: #000000; border: 1px solid gray; padding: 10px;">ISOMETRIC </span>
        <span id="building_link_2" style="cursor: pointer; color: #000000; border: none; padding: 10px;">FRONT VIEW</span>
        <span id="building_link_3" style="cursor: pointer; color: #000000; border: none; padding:10px;">TOP VIEW</span>
        <span id="building_link_4" style="cursor: pointer; color: #000000; border: none; padding: 10px;">RIGHT VIEW</span>
        <span id="building_link_5" style="cursor: pointer; color: #000000; border: none; padding: 10px;">LEFT VIEW</span>
        
        <div class="clear" style="margin-top: 12px;"></div>
        
        <div  id="building_image_1" style="width:500px; height:400px;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($strBuildingImage->building_image1)) { echo $strBuildingImage->building_image1; }?>" alt="Image Not Uploaded">
        </div>
        <div  id="building_image_2" style="width:500px; height:400px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($strBuildingImage->building_image2)) { echo $strBuildingImage->building_image2; }?>" alt="Image Not Uploaded">
        </div>
        <div  id="building_image_3" style="width:500px; height:400px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($strBuildingImage->building_image3)) { echo $strBuildingImage->building_image3; }?>" alt="Image Not Uploaded">
        </div>
        <div  id="building_image_4" style="width:500px; height:400px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($strBuildingImage->building_image4)) { echo $strBuildingImage->building_image4; }?>" alt="Image Not Uploaded">
        </div>
        <div  id="building_image_5" style="width:500px; height:400px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($strBuildingImage->building_image5)) { echo $strBuildingImage->building_image5; }?>" alt="Image Not Uploaded">
        </div>
    </div>
        
<?php }?>
<script>
    $('[id^="building_link_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[2]; 
        console.log(id_num);
        
        $('[id^="building_link_"]').css('border', 'none');
        $(this).css('border', '1px solid gray');
        $('[id^="building_image_"]').hide();
        $('#building_image_'+id_num).show();
    });
</script>