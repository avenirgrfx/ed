<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_id= $_GET['building_id'];
$navType= $_GET['navType'];

if($building_id=="" or $building_id==0)
	exit();

if($navType == 1){
    $strSQL="select * from t_building_image where building_id=".$building_id;
    $strBuildingImageArr = $DB->Returns($strSQL);

    if($strBuildingImage = mysql_fetch_object($strBuildingImageArr))

    ?>

    <script>
        $(function () {
            $('[id^="building_link_"]').click(function(){
                var id = $(this).attr('id');
                var id_num = id.split('_')[2]; 
                console.log(id_num);

                $('[id^="building_link_"]').parent().css('z-index', '1');
                $('[id^="building_link_"]').css('color', '#666666');
                $('[id^="building_link_"]').parent().css('background-image', 'url("../images/gray_button.png")');
                $(this).parent().css('z-index', '2');
                $(this).css('color', '#ffffff');
                $(this).parent().css('background-image', 'url("../images/blue_button.png")');

                $('[id^="building_image_"]').hide();
                $('#building_image_'+id_num).show();
            });
        });

        function active_button(strid){
            var page_no = $(strid).attr("id");
            page_no = page_no.split("_");
            var new_page_no=page_no[2];
            var building_id = $("#page_no_"+page_no[2]).attr("building_id");
            $('[id^="page_no_"]').css("background-color","#fff");
            $(strid).css("background-color","#000");
            $('[id^="building_image_"]').hide();
            $('[id^="title_"]').hide();
            $('[id^="notes_"]').hide();
            $("#building_image_"+page_no[2]).show();
            $("#title_"+page_no[2]).show();
            $("#notes_"+page_no[2]).show();
            $("#map1").attr("page",page_no[2]);
            $.get("<?= URL ?>/ajax_pages/customers/dynamic_building_name_new.php",{
                mode:"building_system_dropdown",
                page_no:new_page_no,
                building_id:building_id,

            },function (data,status){
                $("#Show_Buildings_system").show();
                $("#Show_system").show();
                $("#Show_Buildings_system").html(data);
            });

        }
    </script>

    <div>
        <div style="border-radius: 10px;margin: 15px 30px; float:left; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY ASSISTANT</div>
        <div style="border-radius: 10px;margin: 15px 30px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">SCHEDULES</div>
        <div style="border-radius: 10px;margin: 15px -15px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY FORECAST</div>

        <div class="clear" style="border-bottom:2px solid #DDDDDD; margin:5px 0px;"></div>

    </div>
    <div style="padding:0px 20px; height: 565px;" >
                                
        <div class="Windows_Top" style="position:relative; margin-top:25px;">

            <div style="transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg); position:absolute; z-index:4; width:35px; height:159px; left:178px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left: 5px; font-size:14px; font-weight:bold; color:#FFFFFF;" id="building_link_1">ISOMETRIC</div>
            </div>
            <div style="transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg); position:absolute; z-index:3; width:35px; height:159px; left:295px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:125px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="building_link_2">FRONT&nbsp;VIEW</div>
            </div>
            <div style="transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg); position:absolute; z-index:2; width:35px; height:159px; left:410px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:112px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="building_link_3">TOP&nbsp;VIEW</div>
            </div>
            <div style="transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg); position:absolute; z-index:1; width:35px; height:159px; left:525px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:125px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="building_link_4">RIGHT&nbsp;VIEW</div>
            </div>
            <div style="transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg); position:absolute; z-index:0; width:35px; height:159px; left:640px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                <div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left: 5px; font-size:14px; font-weight:bold; color:#666666;" id="building_link_5">LEFT&nbsp;VIEW</div>
            </div>

        </div>

        <br>

        <div style="margin: 13px 0px 9px; border: 1px solid #DDDDDD; height: 502px; border-radius: 10px; text-align: center;">
            <div  id="building_image_1" style="border: 2px solid; height: 430px; margin: 35px 15px; width: 625px;">
                <img style="width:100%; height: 100%" src="<?= URL ?>uploads/building/<?php if (isset($strBuildingImage->building_image1)) { echo $strBuildingImage->building_image1;} ?>" alt="Image Not Uploaded">        
            </div>
            <div  id="building_image_2" style="display:none; border: 2px solid; height: 430px; margin: 35px 15px; width: 625px;">
                <img style="width:100%; height: 100%" src="<?= URL ?>uploads/building/<?php if (isset($strBuildingImage->building_image2)) { echo $strBuildingImage->building_image2;} ?>" alt="Image Not Uploaded">
            </div>
            <div  id="building_image_3" style="display:none; border: 2px solid; height: 430px; margin: 35px 15px; width: 625px;">
                <img style="width:100%; height: 100%" src="<?= URL ?>uploads/building/<?php if (isset($strBuildingImage->building_image3)) { echo $strBuildingImage->building_image3;} ?>" alt="Image Not Uploaded">
            </div>
            <div  id="building_image_4" style="display:none; border: 2px solid; height: 430px; margin: 35px 15px; width: 625px;">
                <img style="width:100%; height: 100%" src="<?= URL ?>uploads/building/<?php if (isset($strBuildingImage->building_image4)) { echo $strBuildingImage->building_image4;} ?>" alt="Image Not Uploaded">
            </div>
            <div  id="building_image_5" style="display:none; border: 2px solid; height: 430px; margin: 35px 15px; width: 625px;">
                <img style="width:100%; height: 100%" src="<?= URL ?>uploads/building/<?php if (isset($strBuildingImage->building_image5)) { echo $strBuildingImage->building_image5;} ?>" alt="Image Not Uploaded">
            </div>
        </div>
    </div>
    <br>
    
<?php } else if ($navType == 2){ 
     $strSQL="select * from t_building_system_image where building_id=".$building_id." order by page_no desc";
       $strBuildingSystemImageArr = $DB->Returns($strSQL);
       $NewstrBuildingSystemImageArr = $DB->Returns($strSQL);
    ?>
    
    <div>
        <div style="border-radius: 10px;margin: 15px 30px; float:left; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY ASSISTANT</div>
        <div style="border-radius: 10px;margin: 15px 30px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">SCHEDULES</div>
        <div style="border-radius: 10px;margin: 15px -15px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY FORECAST</div>

        <div class="clear" style="border-bottom:2px solid #DDDDDD; margin:5px 0px;"></div>

    </div>
    <div style="padding:0px 20px; height: 565px;" >
        
        <br>

        <div style="margin: 0px 0px 9px; border: 1px solid #DDDDDD; height: 480px; border-radius: 10px; text-align: center;  background: #EDEFEF; 
         /* For browsers that do not support gradients */
        background: -webkit-linear-gradient(#EDEFEF, #ffffff); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#EDEFEF, #ffffff); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#EDEFEF, #ffffff); /* For Firefox 3.6 to 15 */
        background: linear-gradient(#EDEFEF, #ffffff); /* Standard syntax (must be last) */background-repeat: repeat-x;">
            <div>             
                <span style="float:right; margin-top:2px; margin-right:15px;width: 70px;height: 35px">
                    <img border="0" usemap="#Map" src="<?= URL ?>images/previous_next_arrow.png" style="width:100%; height:100%">
                    <map id="map1" name="Map">
                      <area href="javascript:LeftArrow_Click();" coords="18,14,12" shape="circle">
                      <area href="javascript:RightArrow_Click();" coords="51,14,12" shape="circle">
                    </map>
                </span>
                <span style="float: right;margin-left: 10px;margin-top: 10px;">
                    <?php while($strBuildingSystem = mysql_fetch_object($NewstrBuildingSystemImageArr)) { ?>
                     <div id="page_no_<?=$strBuildingSystem->page_no?>" building_id="<?=$building_id?>" style="border-radius:50%;float:right;border:1px solid gray; min-height:10px;min-width:10px;margin:2px;margin-top:3px;" onclick="active_button(this)"></div>
                     <?php } ?>
                </span>           
            </div>

           <?php while($strBuildingSystemImage = mysql_fetch_object($strBuildingSystemImageArr)) { ?>
            <span id="title_<?=$strBuildingSystemImage->page_no?>" style="display:none;float: left;margin-left: 10px;margin-top: 2px;font-size:20px;"><?=$strBuildingSystemImage->title?></span>
            <div  id="building_image_<?=$strBuildingSystemImage->page_no?>" style="display:none;border: 2px solid; height: 430px; margin: 25px 15px;margin-top:37px; width: 625px;">
                <img style="width:100%; height: 100%" src="<?= URL ?>uploads/building/<?php if (isset($strBuildingSystemImage->building_system_image)) { echo $strBuildingSystemImage->building_system_image;} ?>" alt="Image Not Uploaded">        
            </div>
            <div id="notes_<?=$strBuildingSystemImage->page_no?>" style="display:none;">
                <div style="text-align: left;"><strong>Notes</strong></div>
                <div style="float: left;"><?=$strBuildingSystemImage->notes?></div>
            </div>
            <?php }?>

        </div>
        
    </div>
    <br>
    
    <script>   
        $('#page_no_1').trigger( "click" ); 
      
        function LeftArrow_Click(){
          var page = $("#map1").attr("page");
          page=parseInt(page);
          var next_page_no = $("div#page_no_"+page).next().attr("id");
          $('#'+next_page_no).trigger( "click" ); 

        }
    
        function RightArrow_Click(){
         var page = $("#map1").attr("page");
         page=parseInt(page);
         var pev_page_no = $("div#page_no_"+page).prev().attr("id");
         $('#'+pev_page_no).trigger( "click" ); 
        }
    </script>
    
 <?php  } else if ($navType == 3){ 
    $strSQL="select BS.system_id, BS.system_type, S.system_name from t_building_system BS inner join t_system S on S.system_id=BS.system_id where building_id=".$building_id." group by system_id, system_type order by system_id, system_type";
    $strBuildingSystemArr = $DB->Returns($strSQL);
    
    $options = "<select id='ddlSystemManage' onchange='getBuildingSystems(this.value)'>";
    while($strBuildingSystem = mysql_fetch_object($strBuildingSystemArr)){
        $options .= "<option value='$strBuildingSystem->system_id-#-$strBuildingSystem->system_type-#-$strBuildingSystem->system_name'>$strBuildingSystem->system_name</option>";
    }
    $options .= '</select>';
?>  
    <script>
        function getBuildingSystems(ddVal){
            var ddValArray = ddVal.split('-#-');
            $('#right_side_container').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/building_system.php",
            {
                building_id: $('#ddlBuildingForSite').val(),
                system_id: ddValArray[0],
                system_type: ddValArray[1],
                system_name: ddValArray[2],
            },
            function (data, status) {
                $('#right_side_container').html(data);
            });
            
            $('#for_right_nav_3').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/building_system_left.php",
            {
                building_id: $('#ddlBuildingForSite').val(),
                system_id: ddValArray[0],
                system_type: ddValArray[1],
                system_name: ddValArray[2],
            },
            function (data, status) {
                $('#for_right_nav_3').html(data);
            });
        }

        $('#right_side_container').html("");
        $('#Show_Buildings_system').html("<?= $options ?>");
        $('#Show_Buildings_system').show();
        $('#ddlSystemManage').trigger('change');
    </script>
<?php } else { ?>
    <div>
        <div style="border-radius: 10px;margin: 15px 30px; float:left; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY ASSISTANT</div>
        <div style="border-radius: 10px;margin: 15px 30px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">SCHEDULES</div>
        <div style="border-radius: 10px;margin: 15px -15px; float:right; background: #607BA7;padding: 5px 15px;color:#fff">ENERGY FORECAST</div>

        <div class="clear" style="border-bottom:2px solid #DDDDDD; margin:5px 0px;"></div>

    </div>
    <div style="padding:0px 20px; height: 565px;" >
    Not yet implemented
    </div>
    <br>
<?php }?>