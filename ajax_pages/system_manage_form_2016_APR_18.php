<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/system.class.php');
$DB = new DB;
$System = new System();

if(isset($_POST['mode']) && $_POST['mode'] == 'add'){
    $ImageFile = '';
    $uploaddir = AbsPath . '/uploads/building/';
    if (isset($_FILES['system_image']['name'])) {
        $uploadfile = $uploaddir . basename($_FILES['system_image']['name']);
        if (move_uploaded_file($_FILES['system_image']['tmp_name'], $uploadfile)) {
            $ImageFile = $_FILES['system_image']['name'];
            echo "uploaded";
        } else {
            //echo "Couldn't upload Technical File 1 !\n";
            $ImageFile = '';
            echo "not_uploaded";
        }
    }
    
    $building_id = $_POST['building_id'];
    $system_id = $_POST['system_id'];
    $system_type = $_POST['system_type'];
    $system_no = $_POST['system_no'];
    $system_display_name = $_POST['system_display_name'];
    $system_description = $_POST['system_description'];
    $screen_name = $_POST['screen_name'];
    $capacity = $_POST['capacity'];
    $linked_node = $_POST['linked_node'];   

    if($ImageFile != ''){
        $strSQL = "Insert into t_building_system (building_id,system_id, system_type, system_no,system_display_name,system_description,screen_name,capacity,linked_node,system_image) Values (" . $building_id . "," . $system_id . ",'" . $system_type . "'," . $system_no . ", '".$system_display_name."','".$system_description."','".$screen_name."','".$capacity."','".$linked_node."','".$ImageFile."') on duplicate key update system_id=values(system_id), system_type=values(system_type), system_display_name=values(system_display_name),system_description=values(system_description),screen_name=values(screen_name),capacity=values(capacity),linked_node=values(linked_node),system_image=values(system_image)";
    }else{
        $strSQL = "Insert into t_building_system (building_id,system_id, system_type, system_no,system_display_name,system_description,screen_name,capacity,linked_node) Values (" . $building_id . "," . $system_id . ",'" . $system_type . "'," . $system_no . ", '".$system_display_name."','".$system_description."','".$screen_name."','".$capacity."','".$linked_node."') on duplicate key update system_id=values(system_id), system_type=values(system_type), system_display_name=values(system_display_name),system_description=values(system_description),screen_name=values(screen_name),capacity=values(capacity),linked_node=values(linked_node)";
    }
    $DB->Execute($strSQL);exit;
}

$building_id = $_GET['building_id'];
$system_no = $_GET['system_no'];

$strSQL="select * from t_building_system where building_id=".$building_id ." and system_no = $system_no";
$strBuildingSystemArr = $DB->Returns($strSQL);

$strBuildingSystem = mysql_fetch_object($strBuildingSystemArr)   
?>

<div class="imageNav" style="margin-left:30px; width:1000px;border: 1px solid gray; float:left; position: relative;">
    <input type="hidden" id="system_no" value="">
    <div style='float: left; width: 638px; border-right: 1px solid #DEDEDD'>
        <div style="padding: 5px;">Choose Equipment - System 1</div>
        <span style="padding: 5px;">System Image</span>
        <?php if ($strBuildingSystem && $strBuildingSystem->system_image !="") {
                echo '<input type="button" style="width: 80px; padding: 2px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="overflow: hidden; padding-left: 5px;width: 100px;display:-moz-stack" title="' . $strBuildingSystem->system_image . '"> ' . $strBuildingSystem->system_image . '</span><input type="file" name="file" id="image_file"  style="display:none;width: 135px; padding: 2px;" >';
            } else { ?>
                <input type="file" name="file" id="image_file"  style="width: 190px; padding: 2px;" onchange="loadFile(event);">
        <?php } ?>

        <div style='float:left; width: 52%'> 
            <input type='text' id="system_display_name" placeholder='System Display Name' style='margin: 5px; width: 300px;' value="<?=$strBuildingSystem->system_display_name?>">
            <input type='text' id="system_description" placeholder='System Description' style='margin: 5px; width: 300px;font-size: 13px;' value="<?=$strBuildingSystem->system_description?>">

            <div style="padding: 5px;">Small Screen Information</div>
            <input type='text' id="screen_name" placeholder='6-CHAR NAME' style='margin: 5px; width: 120px;' value="<?=$strBuildingSystem->screen_name?>">
            <input type='text' id="capacity" placeholder='CAPACITY' style='margin: 5px; width: 120px;' value="<?=$strBuildingSystem->capacity?>">
            <span>HP</span>

            <div style="padding: 5px;">Link Equipment Node - System 1</div>
            <select id="linked_node" name="linked_node" style='width: 180px; margin: 5px;'>
                <option value="">Select System Node</option>
            </select>
            <span style="color: #75C493">IRM160001A Linked</span>

            <div class='clear'></div>

            <div style="padding: 5px; float:left;">Unit Air Pressure</div>
            <select id="ddlSystem<?= $i ?>" name="ddlSystem" style='width: 180px;'>
                <option>Select System Node</option>
            </select>
            <br><br>
        </div>
        <div style='float:left; margin: 10px; width:285px; height:220px;position: relative;'>
            <img id="preview" src="<?= URL ?>uploads/building/<?=$strBuildingSystem->system_image?>" width="100%" alt="" style="max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;"> 
        </div>

        <div class='clear'></div>
        <div>
            <div style="padding: 5px;">Specifications - System 1</div>
            <select id="ddlSystem<?= $i ?>" name="ddlSystem" style="width:150px;margin-left:5px;">
                <option>Choose from Gallery Files</option>
            </select>
            <select id="ddlSystem<?= $i ?>" name="ddlSystem" style="width:150px;margin-left:10px;">
                <option>Technical Files</option>
            </select>
            <button id="add_data_button" style="background-color:#CDCDCD;border: 1px solid #000;margin-left: 10px; padding: 3px 10px;" building_id="<?= $building_id ?>" onclick="Inputdata1()">Add</button>

            <div style="padding: 5px 10px;"><span>1.Technical File:</span><span style="margin-left: 20px;">CompAir Specication Sheet & Consumption.pdf</span></div>
            <div style="padding: 5px 10px;"><span>2.Technical File:</span><span style="margin-left: 20px;">Compressed Air Evaluation.pdf</span></div>
            <div style="padding: 5px 10px;"><span>3.Technical File:</span><span style="margin-left: 20px;">Compressed Air Evaluation.pdf</span></div>
        </div>
    </div>
    <div style='float: left; width: 360px;'>
        <div style="padding: 5px;">Controls - System 1</div>
        <div style="position: absolute; right: 10px; bottom: 10px;">
            <button id="save_data_button" style="background-color:#CDCDCD;border-radius:10px;border: 1px solid #000;" building_id="<?= $building_id ?>" onclick="save_system()">Save</button>
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

<script>
    $('[id^="right_button_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[2]; 
        console.log(id_num);

        $('[id^="right_button_"]').css('background-color', '#CDCDCD');
        $('[id^="right_button_"]').css('color', '#000');
        $(this).css('background-color', '#000');
        $(this).css('color', '#fff');
    });

    $('#right_button_1').trigger('click');
    
    function save_system(){
        var building_id = "<?=$building_id?>";
        var system_no = "<?=$system_no?>";
        
        var formData = new FormData();
        formData.append('mode', "add");
        formData.append('building_id', building_id);
        formData.append('system_id', $('#system_id').val());
        formData.append('system_type', $('#system_type').val());
        formData.append('system_no', system_no);
        formData.append('system_display_name', $('#system_display_name').val());
        formData.append('system_description', $('#system_description').val());
        formData.append('screen_name', $('#screen_name').val());
        formData.append('capacity', $('#capacity').val());
        formData.append('linked_node', $('#linked_node').val());
        formData.append('system_image', $('#image_file')[0].files[0]);

        console.log(formData)
        $.ajax({
            type: "POST",
            url: "<?= URL ?>/ajax_pages/system_manage_form.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $("#divider").html(data);
            }
        });
    }
    
    var loadFile = function(event) {
        var output = document.getElementById('preview');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
    
    <?php if($strBuildingSystem) { ?>
        $('#system_id').val(<?=$strBuildingSystem->system_id?>);
        $('#system_type').val("<?=$strBuildingSystem->system_type?>");
        $('#system_id').trigger('change');
    <?php } ?>
</script>