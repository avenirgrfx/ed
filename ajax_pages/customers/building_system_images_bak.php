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
<?php
if(!empty($_GET['mode']) && $_GET['mode']=="data"){
        $building_id=$_GET['building_id'];
        $page_no = $_GET['page_no'];
        $strSQL = "select system,title,notes from t_building_system_image where building_id=".$building_id." and page_no=".$page_no;
        $strBuilding_data = $DB->Returns($strSQL);
        while($strBuilding_dataArr = mysql_fetch_object($strBuilding_data)){
                $system = $strBuilding_dataArr->system;
                $title = $strBuilding_dataArr->title;
                $notes = $strBuilding_dataArr->notes;
            
            }
                 if(!empty($title) || !empty($notes)){
                 echo $system."//".$title."//".$notes;
            }
            else{
                $title = "No title specified";
                $notes = "No notes given";
                 echo $title."//".$notes;
            }
      exit;
  }
?>
<script>
    function page_no(pageno){
       // console.log(pageno);
        $.get("<?= URL?>/ajax_pages/building_systems_images.php",
          {
              pageno:pageno,
          },
          
          function(data,status){
              $("#save_data_button").attr("page_no",pageno)
              fetch_data();
          }
          );
    }
    $(document).ready(function(){
        $("#building_link_1").trigger('click');
    });
    function ShowBuildingImages(strSiteId){
        $.post("<?= URL ?>/ajax_pages/building_systems_images.php",
           {
               siteid:strSiteId,
           },    
           function(data,status){
               $('#'+strSiteId).html(data);
           }

        );
    }

    function PlusMinusBuildingImage(strbuildingid,page_no){
        $.post("<?= URL ?>/ajax_pages/building_systems_images.php",
            {
                strbuilding: strbuildingid,
                page_no:page_no,
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
        var page_no = $(imageurl).attr("page_no");
      //  console.log(id_num);
        console.log(page_no);

        var formData = new FormData();
        formData.append('file', $('#' + file_id)[0].files[0]);
        formData.append('mode', "add");
        formData.append('file_num', id_num);
        formData.append('building_id', building_id);
        formData.append('page_no', page_no);

        $.ajax({
            type: "POST",
            url: "<?= URL ?>/ajax_pages/building_systems_images.php",
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
    function Inputdata(){
    
    var title =  $('#title_of_image').val();
    var notes = $('#notes_of_image').val();
    var page_no = $('#save_data_button').attr("page_no");
    var system = $('#ddlSystem'+page_no).val();
    var building_id =$('#save_data_button').attr("building_id");
    $.post("<?= URL ?>/ajax_pages/building_systems_images.php",
      {
         system:system,
         title:title,
         notes:notes,
         page_no:page_no,
         building_id:building_id,
         mode:"insert_data_image",
          },
       function(data,status){
           alert("data saved");
          $('#ddlSystem').val("0");
        //  $('#title_of_image').val("");
        //  $('#notes_of_image').val("");
       }   
    
    
       );
    }

</script>
<?php
 if(!empty($_POST['mode'])){
     
    echo $system=$_POST['system'];
     $title=$_POST['title'];
     $notes=$_POST['notes'];
     $building_id=$_POST['building_id'];
     $page_no=$_POST['page_no'];
    $strSQL="update  t_building_system_image set system='".$system."',title='".$title."',notes='".$notes."' where building_id=".$building_id." and page_no=".$page_no;
     $Insert_data=$DB->Execute($strSQL);
     
 }?>

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
                echo "uploaded";
            } else {
                //echo "Couldn't upload Technical File 1 !\n";
                $ImageFile = '';
                echo "not_uploaded";
            }
        }

        $building_id = $_POST['building_id'];
        $column_name = "building_system_image$file_num";        
        $page_no = $_POST['page_no'];
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
      
        $strSQL = "select * from t_building_system_image where building_id=" . $_POST['building_id']." and page_no=".$page_no ;
        $strBuildingImageArr = $DB->Returns($strSQL);

        if ((mysql_num_rows($strBuildingImageArr) == 0)) {
//            $Gallery->InsertBuildingImage();
            $strQuery = "Insert into t_building_system_image (building_id,page_no, building_system_image) Values (".$building_id.",".$page_no.",'".$ImageFile."')";
            $DB->Execute($strQuery);
        } else {
//            $Gallery->UpdateBuildingImage($_POST['building_id'], $technical_file1, $_POST['file_id']);
            $strQuery = "update t_building_system_image set building_system_image = '$ImageFile' where building_id=$building_id"." and page_no=".$page_no;
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
       
        $strSQL="select * from t_building_system_image where building_id=".$building_id ." order by page_no";
        $strBuildingImageArr = $DB->Returns($strSQL);
         
       // if($strBuildingImage = mysql_fetch_object($strBuildingImageArr)){
            while($strBuildingImage = mysql_fetch_object($strBuildingImageArr)){
               $system["$strBuildingImage->page_no"] = $strBuildingImage->system;
               $image["$strBuildingImage->page_no"]=$strBuildingImage->building_system_image;
            }
          // print_r($image);
        //}
 
        
?>
    <div class="clear"></div>

    <div style="float:left; width:500px;margin-left:30px;">    
        <strong style="margin-left:100px;">Add Building Image Files</strong>
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 1  :</span> <?php if(isset($image["1"]) && $image["1"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["1"].'</span><input type="file" name="file2[]" id="image_file_1" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="1" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_1" building_id="<?=$building_id?>" page_no="1" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem1" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[1]);?></select></span></div>           
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 2  :</span> <?php if(isset($image["2"]) && $image["2"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["2"].'</span><input type="file" name="file2[]" id="image_file_2" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="2" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_2" building_id="<?=$building_id?>" page_no="2" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem2" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[2]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 3  :</span> <?php if(isset($image["3"]) && $image["3"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["3"].'</span><input type="file" name="file2[]" id="image_file_3" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="3" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_3" building_id="<?=$building_id?>" page_no="3" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem3" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[3]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 4  :</span> <?php if(isset($image["4"]) && $image["4"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["4"].'</span><input type="file" name="file2[]" id="image_file_4" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="4" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_4" building_id="<?=$building_id?>" page_no="4" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem4" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[4]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 5  :</span> <?php if(isset($image["5"]) && $image["5"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["5"].'</span><input type="file" name="file2[]" id="image_file_5" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="5" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_5" building_id="<?=$building_id?>" page_no="5" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem5" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[5]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 6  :</span> <?php if(isset($image["6"]) && $image["6"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["6"].'</span><input type="file" name="file2[]" id="image_file_6" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="6" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_6" building_id="<?=$building_id?>" page_no="6" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem6" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[6]);?></select></span></div>           
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 7  :</span> <?php if(isset($image["7"]) && $image["7"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["7"].'</span><input type="file" name="file2[]" id="image_file_7" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="7" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_7" building_id="<?=$building_id?>" page_no="7" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem7" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[7]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 8  :</span> <?php if(isset($image["8"]) && $image["8"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["8"].'</span><input type="file" name="file2[]" id="image_file_8" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="8" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_8" building_id="<?=$building_id?>" page_no="8" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem8" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[8]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 9  :</span> <?php if(isset($image["9"]) && $image["9"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["9"].'</span><input type="file" name="file2[]" id="image_file_9" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="9" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_9" building_id="<?=$building_id?>" page_no="9" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem9" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[9]);?></select></span></div> 
        <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE 10 :</span> <?php if(isset($image["10"]) && $image["10"] != "") { echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> '.$image["10"].'</span><input type="file" name="file2[]" id="image_file_10" building_id="'.$building_id.'" style="display:none;float:left;width: 195px;" page_no="10" onchange="ImageUpload(this)" onclick="$(this).prev().prev().show();$(this).prev().show();$(this).hide();">'; } else { ?><input type="file" name="file2[]" id="image_file_10" building_id="<?=$building_id?>"page_no="10" onchange="ImageUpload(this)" style="float:left;width:195px;"><?php } ?><span style="float:left"><select id="ddlSystem10" name="ddlSystem" style="width:150px;"><?php $System->ListSystems($system[10]);?></select></span></div> 
        <div style="float: right;padding-right: 10px;"><input type="button" id="image_save_button" value="save" style="width:48;height:48;" ></div>
    </div>
    
    <div class="imageNav" style="float:right;margin-right:70px;width:520px;border: 1px solid gray;">
        <span id="building_link_1" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: 1px solid gray; padding: 0px;" onclick="page_no(1)">PAGE 1</span>
        <span id="building_link_2" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(2)">PAGE 2</span>
        <span id="building_link_3" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(3)">PAGE 3</span>
        <span id="building_link_4" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(4)">PAGE 4</span>
        <span id="building_link_5" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(5)">PAGE 5</span>
        <span id="building_link_6" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(6)">PAGE 6</span>
        <span id="building_link_7" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(7)">PAGE 7</span>
        <span id="building_link_8" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(8)">PAGE 8</span>
        <span id="building_link_9" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding: 0px;" onclick="page_no(9)">PAGE 9</span>
        <span id="building_link_10" style="cursor: pointer; color: #000000; background-color:#CDCDCD; border: none; padding:0px;" onclick="page_no(10)">PAGE 10</span>
        <div class="clear" style="margin-top: 12px;"></div>
        <input type="text" id="title_of_image" name="title_of_image" placeholder="Type Title Here" name="Title" style="width:94%;margin-bottom: 4px;margin-left: 7px; ">
        <div  id="building_image_1" style="width:500px; height:400px;padding-left: 4px;margin-left: 10px">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["1"])) { echo $image["1"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_2" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["2"])) { echo $image["2"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_3" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["3"])) { echo $image["3"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_4" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["4"])) { echo $image["4"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_5" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["5"])) { echo $image["5"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_6" style="width:500px; height:400px;margin-left: 10px;display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["6"])) { echo $image["6"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_7" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["7"])) { echo $image["7"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_8" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["8"])) { echo $image["8"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_9" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["9"])) { echo $image["9"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_10" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if(isset($image["10"])) { echo $image["10"]; }?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div >
            <div style="margin-left: 9px;">Notes</div>
            <textarea rows="4" id="notes_of_image" name="notes_of_image" cols="50"  placeholder="Type Notes Here"style=" width: 490px;margin-bottom: 7px; margin-left: 9px;"></textarea>
        </div>
    </div>
    <div class="clear"></div>
    <div style="float: right;margin-right: 69px;margin-top: 5px;">
        <button id="save_data_button" style="background-color:#CDCDCD;border-radius:5px; " building_id="<?=$building_id?>" page_no="1" onclick="Inputdata()">Save</button>
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
    
    function fetch_data(){
        var page_no =  $("#save_data_button").attr("page_no")
        var building_id = $("#save_data_button").attr("building_id");
        $.get("<?= URL ?>/ajax_pages/building_systems_images.php",
            {
             building_id:building_id,
             page_no:page_no,
             mode:"data",
             },
             function(data,status){
               
              var new_data = data.split("//");
              console.log(new_data[0]); 
             // $("#ddlSystem"+page_no).val(new_data[0]);
              $("#title_of_image").val(new_data[1]);
              $("#notes_of_image").val(new_data[2]);
               
             });
    }
</script>