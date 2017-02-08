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
if (!empty($_GET['mode']) && $_GET['mode'] == "data") {
    $building_id = $_GET['building_id'];
    $page_no = $_GET['page_no'];
    $strSQL = "select system,title,notes from t_building_system_image where building_id=" . $building_id . " and page_no=" . $page_no;
    $strBuilding_data = $DB->Returns($strSQL);
    while ($strBuilding_dataArr = mysql_fetch_object($strBuilding_data)) {
        $system = $strBuilding_dataArr->system;
        $title = $strBuilding_dataArr->title;
        $notes = $strBuilding_dataArr->notes;
    }
    if (!empty($title) || !empty($notes)) {
        echo   $title . "//" . $notes;
    } 
    
    exit;
}
?>
<script>
    function page_no(pageno) {
        // console.log(pageno);
        $.get("<?= URL ?>/ajax_pages/building_systems_images.php",
                {
                    pageno: pageno,
                },
                function (data, status) {
                    $("#save_data_button").attr("page_no", pageno)
                    fetch_data();
                }
        );
    }
    $(document).ready(function () {
        $("#building_link_1").trigger('click');
    });
    function ShowBuildingImages(strSiteId) {
        $.post("<?= URL ?>/ajax_pages/building_systems_images.php",
                {
                    siteid: strSiteId,
                },
                function (data, status) {
                    $('#' + strSiteId).html(data);
                }

        );
    }

    function PlusMinusBuildingImage(strbuildingid, page_no) {
        $.post("<?= URL ?>/ajax_pages/building_systems_images.php",
                {
                    strbuilding: strbuildingid,
                    page_no: page_no,
                },
                function (data, status) {
                    $("#" + strbuildingid).html(data);
                }
        );
    }


    function Inputdata() {

        var title = $('#title_of_image').val();
        var notes = $('#notes_of_image').val();
        var page_no = $('#save_data_button').attr("page_no");
       // var system = $('#ddlSystem' + page_no).val();
        var building_id = $('#save_data_button').attr("building_id");
        $.post("<?= URL ?>/ajax_pages/building_systems_images.php",
                {
                   // system: system,
                    title: title,
                    notes: notes,
                    page_no: page_no,
                    building_id: building_id,
                    mode: "insert_data_image",
                },
                function (data, status) {
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
        for (var i = 1; i <= 10; i++) {
            if ($('#' + "image_file_" + i)[0].files[0]) {
                formData.append('image_' + i, $('#' + "image_file_" + i)[0].files[0]);
            }
            formData.append(i, $('#' + "ddlSystem" + i).val());
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
                PlusMinusBuildingImage(building_id );
//                if(data != ''){
//                 $('#building_image_'+id_num+' img').attr('src', "<?= URL ?>uploads/building/"+$('#' + file_id).val());
//                 $('#building_link_'+id_num).trigger('click');
//                }
                $("#divider").html(data);
            }
        });


    });
</script>
<?php
if (!empty($_POST['mode']) && $_POST["mode"] == "insert_data_image") {

    //$system = $_POST['system'];
    $title = $_POST['title'];
    $notes = $_POST['notes'];
    $building_id = $_POST['building_id'];
    $page_no = $_POST['page_no'];
    echo $strSQL = "insert into  t_building_system_image (building_id,page_no,title,notes) values ($building_id,$page_no,'$title','$notes') ON DUPLICATE KEY UPDATE title=values(title),notes=values(notes)";
    $Insert_data = $DB->Execute($strSQL);
}
?>

<?php
if (!empty($_POST['clientid'])) {

    $strSiteID = $_POST['clientid'];
    $strSQL = "Select * from t_sites where client_id=$strSiteID order by site_name";
    $strRsSiteArr = $DB->Returns($strSQL);
    while ($strRsSite = mysql_fetch_object($strRsSiteArr)) {
        echo "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=ShowBuildingImages('" . $strRsSite->site_id . "')>" . $strRsSite->site_name . "</span></div>
	<div id='" . $strRsSite->site_id . "'></div><div class='clear'></div>";
    }
}
?>

<?php
if (!empty($_POST['siteid'])) {
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
        echo '<div id="' . $strRsBuilding->building_id . '">';
        ?>


        <div class='clear'></div>
        <?php
    }
}
?>
<?php
if (isset($_POST['mode']) && !empty($_POST['mode'])) {
    if ($_POST['mode'] == "add") {
        $ImageFile = '';
        $file_num = $_POST['file_num'];

        foreach ($_POST as $index => $system) {

            if ($_FILES['image_' . $index]['name']) {
                $uploaddir = AbsPath . '/uploads/building/';
                $uploadfile = $uploaddir . basename($_FILES['image_' . $index]['name']);
                if (move_uploaded_file($_FILES['image_' . $index]['tmp_name'], $uploadfile)) {
                    echo $ImageFile = $_FILES['image_' . $index]['name'];
                    echo "uploaded";
                } else {
                    //echo "Couldn't upload Technical File 1 !\n";
                    $ImageFile = '';
                    echo "not_uploaded";
                }
            }


            $building_id = $_POST['building_id'];
            // $column_name = "building_system_image$file_num";        
            $page_no = $index;
            $system1 = $_POST[$index];
            if ($page_no > 0 && $page_no <= 10 && $page_no!="mode") {
            $strSQL = "select * from t_building_system_image where building_id=" . $building_id . " and page_no=" . $page_no;
            $strBuildingImageArr = $DB->Returns($strSQL);
    
                if ((mysql_num_rows($strBuildingImageArr) == 0)) {

                    $strQuery = "Insert into t_building_system_image (building_id,page_no, building_system_image,system) Values (" . $building_id . "," . $page_no . ",'" . $ImageFile . "'," . $system . ")";
                    $DB->Execute($strQuery);
                } else {

                    if ($ImageFile != '') {

                        $strQuery = "update t_building_system_image set building_system_image='$ImageFile' , system =$system1  where building_id=$building_id and page_no=$index";
                        $DB->Execute($strQuery);
                    } else if ($system1>= 0) {

                        $strQuery = "update t_building_system_image set system =$system1  where building_id=$building_id and page_no=$index";
                        $DB->Execute($strQuery);
                    }
                }
                $ImageFile = '';
                $system1 = '';
            }
        }
    }
}
?> 
<?php
if (!empty($_POST['strbuilding'])) {
    $building_id = $_POST['strbuilding'];

    $strSQL = "select * from t_building_system_image where building_id=" . $building_id . " order by page_no";
    $strBuildingImageArr = $DB->Returns($strSQL);


    while ($strBuildingImage = mysql_fetch_object($strBuildingImageArr)) {
        $system["$strBuildingImage->page_no"] = $strBuildingImage->system;
        $image["$strBuildingImage->page_no"] = $strBuildingImage->building_system_image;
    }
    ?>
    <div class="clear"></div>

    <div style="float:left; width:500px;margin-left:30px;">    
        <strong style="margin-left:100px;">Add Building Image Files</strong>

    <?php for ($i = 1; $i <= 10; $i++) { ?>
            <div style="margin:5px;float:left; width:500px;"><span style="float:left; width: 65px;">PAGE <?= $i ?>  :</span> 
        <?php
        if (isset($image["$i"]) && $image["$i"] != "") {
            echo '<input type="button" style="width: 85px; height: 30px;float:left" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="  display: block;float: left;height: 24px;overflow: hidden;width: 110px;"> ' . $image["$i"] . '</span><input type="file" name="file2[]" id="image_file_' . $i . '" building_id="' . $building_id . '" style="display:none;float:left;width: 195px;" page_no="' . $i . '" ">';
        } else {
            ?>
                    <input type="file" name="file2[]" id="image_file_<?= $i ?>" building_id="<?= $building_id ?>" page_no="<?= $i ?>"  style="float:left;width:195px;" >
                  
        <?php } ?>
                <span style="float:left"><select id="ddlSystem<?= $i ?>" name="ddlSystem<?= $i ?>" style="width:150px;">
        <?php $System->ListSystems($system[$i]); ?></select></span></div>           
    <?php } ?>

        <div style="float: right;padding-right: 10px;"><input type="button" building_id="<?= $building_id ?>" id="image_save_button" value="save" style="width:48;height:48;" ></div>
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
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["1"])) {
        echo $image["1"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_2" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["2"])) {
        echo $image["2"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_3" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["3"])) {
        echo $image["3"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_4" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["4"])) {
        echo $image["4"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_5" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["5"])) {
        echo $image["5"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_6" style="width:500px; height:400px;margin-left: 10px;display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["6"])) {
        echo $image["6"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_7" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["7"])) {
        echo $image["7"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_8" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["8"])) {
        echo $image["8"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_9" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["9"])) {
        echo $image["9"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div  id="building_image_10" style="width:500px; height:400px;margin-left: 10px; display:none;">
            <img src="<?= URL ?>/uploads/building/<?php if (isset($image["10"])) {
        echo $image["10"];
    } ?>" alt="Image Not Uploaded" style="width: 100%;height: 100%;">
        </div>
        <div >
            <div style="margin-left: 9px;">Notes</div>
            <textarea rows="4" id="notes_of_image" name="notes_of_image" cols="50"  placeholder="Type Notes Here"style=" width: 490px;margin-bottom: 7px; margin-left: 9px;"></textarea>
        </div>
    </div>
    <div class="clear"></div>
    <div style="float: right;margin-right: 69px;margin-top: 5px;">
        <button id="save_data_button" style="background-color:#CDCDCD;border-radius:5px; " building_id="<?= $building_id ?>" page_no="1" onclick="Inputdata()">Save</button>
    </div>

<?php } ?>
<script>
    $('[id^="building_link_"]').click(function () {
        var id = $(this).attr('id');
        var id_num = id.split('_')[2];
        console.log(id_num);

        $('[id^="building_link_"]').css('border', 'none');
        $(this).css('border', '1px solid gray');

        $('[id^="building_image_"]').hide();
        $('#building_image_' + id_num).show();
    });

    function fetch_data() {
        var page_no = $("#save_data_button").attr("page_no")
        var building_id = $("#save_data_button").attr("building_id");
        $.get("<?= URL ?>/ajax_pages/building_systems_images.php",
                {
                    building_id: building_id,
                    page_no: page_no,
                    mode: "data",
                },
                function (data, status) {

                    var new_data = data.split("//");
                    console.log(new_data[0]+"-----"+new_data[1]+"------"+new_data[2]);
                    // $("#ddlSystem"+page_no).val(new_data[0]);
                    if(data==''){
                    $("#title_of_image").attr("No title given");
                    $("#notes_of_image").attr("No notes given");
                    }
                else{
                    $("#title_of_image").val(new_data[0]);
                    $("#notes_of_image").val(new_data[1]);
                   }
                });
    }
</script>