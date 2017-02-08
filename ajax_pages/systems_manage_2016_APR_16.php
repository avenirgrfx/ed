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
        <select id="ddlSystem" name="ddlSystem" style="width:150px; float:left;" onchange="SetSystemName()">
            <?php $System->ListSystems();?>
        </select>
        <select id="ddlSuppply" name="ddlSuppply" style="width:150px; float:left;margin-left:20px;" onchange="SetSystemName()">
            <option>Supply</option>
            <option>Demand</option>
        </select>
        <div style='float: left; background:#DEDEDD; margin-left:20px;padding: 5px;display:none;' id="TxtSystemName"></div>
    </div>
    
    <div class="clear"></div>
    
    <div id="Main_Container" style="display: none;">
    
        <div style="margin-top:20px;margin-left:30px;">
            <?php for($i=1; $i<=10; $i++){ ?>
            <span id="System_Button_<?=$i?>" style="margin-right: -5px; padding: 5px;cursor: pointer; color: #000000; background-color:#CDCDCD; border: 1px solid #000;" onclick="/*click_system(<?=$i?>);*/">System <?=$i?></span>
            <?php } ?>
            <span id="System_Button_Add" style="padding: 5px;cursor: pointer; color: #000000; background-color:#CDCDCD; border: 1px solid #000;" onclick="add_system()">ADD</span>
        </div>
        <div class="clear" style="margin-top: 5px;"></div>
        <div class="imageNav" style="margin-left:30px; width:1000px;border: 1px solid gray; float:left; position: relative;">
            <div style='float: left; width: 638px; border-right: 1px solid #DEDEDD'>
                <div style="padding: 5px;">Choose Equipment - System 1</div>
    <!--            <select id="ddlSystem<?=$i?>" name="ddlSystem" style="width:150px;margin-left:5px;">
                    <?php $System->ListSystems();?>
                </select>
                <select id="ddlSystem<?=$i?>" name="ddlSystem" style="width:150px;margin-left:10px;">
                    <?php $System->ListSystems();?>
                </select>-->
                <span style="margin-left: 20px; padding: 5px;">System Image</span>
                <input type='button' value='Change' style='width: 80px; padding: 2px;'>
                <span style="padding: 5px;">1.jpg</span>

                <div style='float:left; width: 60%'> 
                    <input type='text' placeholder='System Display Name' style='margin: 5px; width: 300px;'>
                    <input type='text' placeholder='System Description' style='margin: 5px; width: 300px;font-size: 13px;'>

                    <div style="padding: 5px;">Small Screen Information</div>
                    <input type='text' placeholder='6-CHAR NAME' style='margin: 5px; width: 120px;'>
                    <input type='text' placeholder='CAPACITY' style='margin: 5px; width: 120px;'>
                    <span>HP</span>

                    <div style="padding: 5px;">Link Equipment Node - System 1</div>
                    <select id="ddlSystem<?=$i?>" name="ddlSystem" style='width: 180px; margin: 5px;'>
                        <option>Select System Node</option>
                    </select>
                    <span style="color: #75C493">IRM160001A Linked</span>

    <!--                <select id="ddlSystem<?=$i?>" name="ddlSystem" style='width: 180px; margin: 5px;'>
                        <option>Select System Node</option>
                    </select>-->
                    <div class='clear'></div>

                    <div style="padding: 5px; float:left;">Unit Air Pressure</div>
                    <select id="ddlSystem<?=$i?>" name="ddlSystem" style='width: 180px;'>
                        <option>Select System Node</option>
                    </select>
                    <br><br>
                </div>
                <div style='float:left; width:38%; padding: 5px;'>
                    <img src="<?= URL ?>uploads/building/ren_01.jpg" width="100%"> 
                </div>

                <div class='clear'></div>
                <div>
                    <div style="padding: 5px;">Specifications - System 1</div>
                    <select id="ddlSystem<?=$i?>" name="ddlSystem" style="width:150px;margin-left:5px;">
                        <option>Choose from Gallery Files</option>
                    </select>
                    <select id="ddlSystem<?=$i?>" name="ddlSystem" style="width:150px;margin-left:10px;">
                        <option>Technical Files</option>
                    </select>
                    <button id="add_data_button" style="background-color:#CDCDCD;border: 1px solid #000;margin-left: 10px; padding: 3px 10px;" building_id="<?=$building_id?>" onclick="Inputdata1()">Add</button>

                    <div style="padding: 5px 10px;"><span>1.Technical File:</span><span style="margin-left: 20px;">CompAir Specication Sheet & Consumption.pdf</span></div>
                    <div style="padding: 5px 10px;"><span>2.Technical File:</span><span style="margin-left: 20px;">Compressed Air Evaluation.pdf</span></div>
                    <div style="padding: 5px 10px;"><span>3.Technical File:</span><span style="margin-left: 20px;">Compressed Air Evaluation.pdf</span></div>
                </div>
            </div>
            <div style='float: left; width: 360px;'>
                <div style="padding: 5px;">Controls - System 1</div>
                <div style="position: absolute; right: 10px; bottom: 10px;">
                    <button id="save_data_button" style="background-color:#CDCDCD;border-radius:10px;border: 1px solid #000;" building_id="<?=$building_id?>" onclick="Inputdata1()">Save</button>
                </div>
            </div>
        </div>

        <div style="position:relative; width:30px; float:left; margin-left: 31px;transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg);">

            <div id="right_button_1" style="padding: 5px; height: 20px; position:absolute; left:0; color: #000000; background-color:#CDCDCD; border: 1px solid #000; cursor:pointer;">CONTROLS</div>
            <div id="right_button_2" style="padding: 5px; height: 20px; position:absolute; left:85px; color: #000000; background-color:#CDCDCD; border: 1px solid #000; cursor:pointer;">SCHEDULES</div>
            <div id="right_button_3" style="padding: 5px; height: 20px; position:absolute; left:174px; color: #000000; background-color:#CDCDCD; border: 1px solid #000; cursor:pointer;">PERFORMANCE</div>
            <div id="right_button_4" style="padding: 5px; height: 20px; position:absolute; left:287px;; color: #000000; background-color:#CDCDCD; border: 1px solid #000; cursor:pointer;">ANALYSIS</div>

        </div>

        <div class="clear"></div>

        <div style="margin-left:30px; width:980px;border: 1px solid gray; float:left;padding: 10px;margin-top: 5px;">
            SYSTEM PRESSURE AVERAGE: Average Pressure <span style='background: #000; color: rgb(255, 255, 255); padding: 8px 20px;'>121</span> psi
            <select style='width: 135px; float:right;'><option>Select System Node</option></select>
            <select style='width: 135px; float:right;'><option>Select System Node</option></select>
            <select style='width: 135px; float:right;'><option>Select System Node</option></select>
            <select style='width: 135px; float:right;'><option>Select System Node</option></select>
        </div>

        <div class="clear" style='margin-bottom: 20px;'></div>
    </div>
<?php } ?>
    
<script>
    $('[id^="System_Button_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[2]; 
        console.log(id_num);
        
        $('[id^="System_Button_"]').css('background-color', '#CDCDCD');
        $('[id^="System_Button_"]').css('color', '#000');
        $(this).css('background-color', '#000');
        $(this).css('color', '#fff');
       
//        $('[id^="building_image_"]').hide();
//        $('#building_image_'+id_num).show();
    });
    
    $('#System_Button_1').trigger('click');
    
    $('[id^="right_button_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[2]; 
        console.log(id_num);
        
        $('[id^="right_button_"]').css('background-color', '#CDCDCD');
        $('[id^="right_button_"]').css('color', '#000');
        $(this).css('background-color', '#000');
        $(this).css('color', '#fff');
       
//        $('[id^="building_image_"]').hide();
//        $('#building_image_'+id_num).show();
    });
    
    $('#right_button_1').trigger('click');
    
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
    
    function SetSystemName(){
        if($('#ddlSystem').val() > 0){
            var sys_name = $('#ddlSystem option:selected').html()
            var supply = $('#ddlSuppply').val();
            $('#TxtSystemName').html(sys_name+" - "+supply);
            $('#TxtSystemName').show();
            $('#Main_Container').show();
        }else{
            $('#TxtSystemName').hide();
            $('#Main_Container').hide();
        }
    }
</script>