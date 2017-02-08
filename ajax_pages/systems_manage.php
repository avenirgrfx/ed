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
        $.get("<?= URL?>/ajax_pages/systems_manage.php",
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
        $.post("<?= URL ?>/ajax_pages/systems_manage.php",
           {
               siteid:strSiteId,
           },    
           function(data,status){
               $('#'+strSiteId).html(data);
           }

        );
    }

    function PlusMinusBuildingImage(strbuildingid,page_no){
        $.post("<?= URL ?>/ajax_pages/systems_manage.php",
            {
                strbuilding: strbuildingid,
                page_no:page_no,
            },
            function (data, status) {
                $("#" + strbuildingid).html(data);
            }
        );
    }


    function Inputdata(){
    
    var title =  $('#title_of_image').val();
    var notes = $('#notes_of_image').val();
    var page_no = $('#save_data_button').attr("page_no");
    var system = $('#ddlSystem'+page_no).val();
    var building_id =$('#save_data_button').attr("building_id");
    $.post("<?= URL ?>/ajax_pages/systems_manage.php",
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

 $("#image_save_button").click(function ImageUpload() {

        var building_id = $("#image_save_button").attr("building_id");
        console.log(page_no);
        var formData = new FormData();
        for(var i=1;i<=10;i++){
            if($('#' + "image_file_"+i)[0].files[0]){
        formData.append('image_'+i, $('#' + "image_file_"+i)[0].files[0]);
            }
        formData.append(i,$('#'+"ddlSystem"+i).val());
         }
        formData.append('mode', "add");
        formData.append('building_id', building_id);
       
          console.log(formData)
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
                 $("#divider").html(data);
            }
        });

      
  });
</script>
<?php
 if(!empty($_POST['mode']) && $_POST["mode"]=="insert_data_image"){
     
     $system=$_POST['system'];
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
    
    while($strRsSite=mysql_fetch_object($strRsSiteArr)){
        echo "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=ShowBuildingImages('".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
        <div id='".$strRsSite->site_id."'></div><div class='clear'></div>";
    }
}

if(!empty($_POST['siteid'])){
    $strSiteID = $_POST['siteid'];

    $strSQL = "Select * from t_building where site_id=$strSiteID";
    $strRsBuildingArr = $DB->Returns($strSQL);

    while ($strRsBuilding = mysql_fetch_object($strRsBuildingArr)) {
         echo '<div class="clear"></div>';
         echo '<hr style="border-bottom:1px #999999 dotted;">';
         echo "<div onclick='PlusMinusBuildingImage(" . $strRsBuilding->building_id . ")' class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:bold; font-size:20px;' id='Building_Details_Plus_Minus_" . $strRsBuilding->building_id . "'>-</span><span style='font-weight:normal;'>Building:</span> <span style='text-decoration:underline;'>" . $strRsBuilding->building_name . "</span></div>";
         echo '<div id="'.$strRsBuilding->building_id.'">';
         echo '<div class="clear"></div>';
    }
}

if(!empty($_POST['strbuilding'])){
    $building_id=$_POST['strbuilding'];

    $strSQL="select * from t_building_system where building_id=".$building_id ." order by system_no";
    $strBuildingSystemArr = $DB->Returns($strSQL);

    while($strBuildingSystem = mysql_fetch_object($strBuildingSystemArr)){
       //print_r($strBuildingSystem);
    }     
?>
    <div class="clear"></div>

    <div style="margin-left:30px;">    
        <select id="system_id" name="system_id" style="width:150px; float:left;" onchange="SetSystemName()">
            <?php $System->ListSystemForEquipments(); ?>
        </select>
<!--        <select id="system_type" name="system_type" style="width:150px; float:left;margin-left:20px;" onchange="SetSystemName()">
            <option value="Supply">Supply</option>
            <option value="Demand">Demand</option>
        </select>-->
        <div style='float: left; background:#DEDEDD; margin-left:20px;padding: 5px;display:none;' id="TxtSystemName"></div>
    </div>
    
    <div class="clear"></div>
    
    <div id="Main_Container" style="display: none;">
    
        <div style="margin-top:20px;margin-left:30px;">
            <?php for($i=1; $i<=10; $i++){ ?>
            <span id="System_Button_<?=$i?>" style="margin-right: -5px; padding: 5px;cursor: pointer; color: #000000; background-color:#CDCDCD; border: 1px solid #000;" onclick="/*click_system(<?=$i?>);*/">System <?=$i?></span>
            <?php } ?>
            <span id="System_Button_0" style="padding: 5px;cursor: pointer; color: #000000; background-color:#CDCDCD; border: 1px solid #000;" onclick="add_system()">ADD</span>
        </div>
        <div class="clear" style="margin-top: 5px;"></div>
        <div id="new_form_data"></div>

        <div class="clear" style='margin-bottom: 20px;'></div>
    </div>
<?php } ?>
    
<script>
    $('[id^="System_Button_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[2]; 
        var system_id = $('#system_id').val();
        var system_parent_name = $('#system_id option:selected').parent().attr('label');
        var system_name = $('#system_id option:selected').text();
        var system_name_array = system_name.split('-');
        if(system_name_array[1]){
            var system_type = $.trim(system_name_array[1]);
        }else{
            var system_type = "";
        }
//        var value = $('#system_id').val();
//        var new_value = value.split('`#`');
//        var system_id = new_value[0];
//        var system_type = new_value[1];
       
        $('[id^="System_Button_"]').css('background-color', '#CDCDCD');
        $('[id^="System_Button_"]').css('color', '#000');
        $(this).css('background-color', '#000');
        $(this).css('color', '#fff');
        
        $('#new_form_data').html("");
        if(system_type == "DEMAND"){
            $.get("<?php echo URL ?>ajax_pages/system_manage_demand_form.php",
            {
                building_id: "<?= $_POST['strbuilding'] ?>",
                system_id: system_id,
                system_type: system_type,
                system_no: id_num
            },function(data,status){
                $('#new_form_data').html(data);			
            });
        }else if(system_type == "SUPPLY"){
            $.get("<?php echo URL ?>ajax_pages/system_manage_form.php",
            {
                building_id: "<?= $_POST['strbuilding'] ?>",
                system_id: system_id,
                system_type: system_type,
                system_no: id_num
            },function(data,status){
                $('#new_form_data').html(data);			
            });
        }else if(system_parent_name == "LIGHTING"){
            $.get("<?php echo URL ?>ajax_pages/system_manage_lighting_form.php",
            {
                building_id: "<?= $_POST['strbuilding'] ?>",
                system_id: system_id,
                system_type: "LIGHTING",
                system_no: id_num
            },function(data,status){
                $('#new_form_data').html(data);			
            });
        }
    });
    
    function SetSystemName(){
        var system_id = $('#system_id').val();
//        var value = $('#system_id').val();
//        var new_value = value.split('`#`');
//        var system_id = new_value[0];
//        var system_type = new_value[1];
        if(system_id > 0){
            var sys_name = $('#system_id option:selected').html()
           // var supply = $('#system_type').val();
            $('#TxtSystemName').html(sys_name);//+" - "+supply);
            $('#TxtSystemName').show();
            $('#Main_Container').show();
            
            $('#System_Button_1').trigger('click');
        }else{
            $('#TxtSystemName').hide();
            $('#Main_Container').hide();
        }
    }
</script>