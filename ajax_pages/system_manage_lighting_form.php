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
            //echo "uploaded";
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
    $technical_files = $_POST['technical_files'];   
    $pa_system1 = $_POST['pa_system1'];   
    $pa_system2 = $_POST['pa_system2'];   
    $pa_system3 = $_POST['pa_system3'];   
    $pa_system4 = $_POST['pa_system4'];   
    
    $variable1_name = $_POST['variable1_name'];
    $variable1_unit = $_POST['variable1_unit'];
    $variable1_node = $_POST['variable1_node'];
    $variable1_calc = $_POST['variable1_calc'];
    
    $variable2_name = $_POST['variable2_name'];
    $variable2_unit = $_POST['variable2_unit'];
    $variable2_node = $_POST['variable2_node'];
    $variable2_calc = $_POST['variable2_calc'];
    
    $variable3_name = $_POST['variable3_name'];
    $variable3_unit = $_POST['variable3_unit'];
    $variable3_node = $_POST['variable3_node'];
    $variable3_calc = $_POST['variable3_calc'];
    
    $variable4_name = $_POST['variable4_name'];
    $variable4_unit = $_POST['variable4_unit'];
    $variable4_node = $_POST['variable4_node'];
    $variable4_calc = $_POST['variable4_calc'];   
    
    $lighting = json_decode($_POST['lighting']);
    
    //print_r($lighting);exit;
     
    if($ImageFile != ''){
        $strSQL = "Insert into t_building_system (building_id, system_id, system_type, system_no, system_display_name, system_description, screen_name, capacity, linked_node, system_image, "
            . "technical_files, pa_system1, pa_system2, pa_system3, pa_system4, "
            . "variable1_name, variable1_unit, variable1_node, variable1_calc, "
            . "variable2_name, variable2_unit, variable2_node, variable2_calc, "
            . "variable3_name, variable3_unit, variable3_node, variable3_calc, "
            . "variable4_name, variable4_unit, variable4_node, variable4_calc) Values (" 
            . $building_id . "," . $system_id . ",'" . $system_type . "'," . $system_no . ", '".$system_display_name."','".$system_description."','".$screen_name."','".$capacity."','".$linked_node."','".$ImageFile."',"
            . "'".$technical_files."','".$pa_system1."','".$pa_system2."','".$pa_system3."','".$pa_system4."',"
            . "'".$variable1_name."','".$variable1_unit."','".$variable1_node."','".$variable1_calc."',"
            . "'".$variable2_name."','".$variable2_unit."','".$variable2_node."','".$variable2_calc."',"
            . "'".$variable3_name."','".$variable3_unit."','".$variable3_node."','".$variable3_calc."',"
            . "'".$variable4_name."','".$variable4_unit."','".$variable4_node."','".$variable4_calc."'"
            . ") on duplicate key update system_display_name=values(system_display_name),system_description=values(system_description),screen_name=values(screen_name),capacity=values(capacity),linked_node=values(linked_node),system_image=values(system_image),"
            . "technical_files=values(technical_files),pa_system1=values(pa_system1),pa_system2=values(pa_system2),pa_system3=values(pa_system3),pa_system4=values(pa_system4),"
            . "variable1_name=values(variable1_name),variable1_unit=values(variable1_unit),variable1_node=values(variable1_node),variable1_calc=values(variable1_calc),"
            . "variable2_name=values(variable2_name),variable2_unit=values(variable2_unit),variable2_node=values(variable2_node),variable2_calc=values(variable2_calc),"
            . "variable3_name=values(variable3_name),variable3_unit=values(variable3_unit),variable3_node=values(variable3_node),variable3_calc=values(variable3_calc),"
            . "variable4_name=values(variable4_name),variable4_unit=values(variable4_unit),variable4_node=values(variable4_node),variable4_calc=values(variable4_calc)";
    }else{
        $strSQL = "Insert into t_building_system (building_id, system_id, system_type, system_no, system_display_name, system_description, screen_name, capacity, linked_node, "
            . "technical_files, pa_system1, pa_system2, pa_system3, pa_system4, "
            . "variable1_name, variable1_unit, variable1_node, variable1_calc, "
            . "variable2_name, variable2_unit, variable2_node, variable2_calc, "
            . "variable3_name, variable3_unit, variable3_node, variable3_calc, "
            . "variable4_name, variable4_unit, variable4_node, variable4_calc) Values (" 
            . $building_id . "," . $system_id . ",'" . $system_type . "'," . $system_no . ", '".$system_display_name."','".$system_description."','".$screen_name."','".$capacity."','".$linked_node."',"
            . "'".$technical_files."','".$pa_system1."','".$pa_system2."','".$pa_system3."','".$pa_system4."',"
            . "'".$variable1_name."','".$variable1_unit."','".$variable1_node."','".$variable1_calc."',"
            . "'".$variable2_name."','".$variable2_unit."','".$variable2_node."','".$variable2_calc."',"
            . "'".$variable3_name."','".$variable3_unit."','".$variable3_node."','".$variable3_calc."',"
            . "'".$variable4_name."','".$variable4_unit."','".$variable4_node."','".$variable4_calc."'"
            . ") on duplicate key update system_display_name=values(system_display_name),system_description=values(system_description),screen_name=values(screen_name),capacity=values(capacity),linked_node=values(linked_node),"
            . "technical_files=values(technical_files),pa_system1=values(pa_system1),pa_system2=values(pa_system2),pa_system3=values(pa_system3),pa_system4=values(pa_system4),"
            . "variable1_name=values(variable1_name),variable1_unit=values(variable1_unit),variable1_node=values(variable1_node),variable1_calc=values(variable1_calc),"
            . "variable2_name=values(variable2_name),variable2_unit=values(variable2_unit),variable2_node=values(variable2_node),variable2_calc=values(variable2_calc),"
            . "variable3_name=values(variable3_name),variable3_unit=values(variable3_unit),variable3_node=values(variable3_node),variable3_calc=values(variable3_calc),"
            . "variable4_name=values(variable4_name),variable4_unit=values(variable4_unit),variable4_node=values(variable4_node),variable4_calc=values(variable4_calc)";
    }
    $DB->Execute($strSQL);
    echo $system_no;exit;
}

$building_id = $_GET['building_id'];
$system_id = $_GET['system_id'];
$system_type = $_GET['system_type'];
$system_no = $_GET['system_no'];

$strSQL="select * from t_unit_variable";
$strVariableUnitArr = $DB->Lists(array("Query"=>$strSQL));

$strSQL="select * from t_unit_calculation";
$strCalcUnitArr = $DB->Lists(array("Query"=>$strSQL));

$strSQL="select * from t_system_node where parent_parent_id = $system_id";
$strNodesArr = $DB->Lists(array("Query"=>$strSQL));

$strSQL="select * from t_control_image where category_id in (select system_id from t_system where parent_id = $system_id and has_gallery = 1)";
$strGalleryArr = $DB->Returns($strSQL);

$strSQL="select BS.*, SN.node_serial as linke_node_name from t_building_system BS left join t_system_node SN on BS.linked_node = SN.system_node_id where BS.building_id=".$building_id ." and BS.system_id = $system_id and BS.system_type = '$system_type' and system_no = $system_no";
$strBuildingSystemArr = $DB->Returns($strSQL);

$strBuildingSystem = mysql_fetch_object($strBuildingSystemArr)   
?>

<div class="imageNav" style="margin-left:30px; width:1000px;border: 1px solid gray; float:left; position: relative;">
    <input type="hidden" id="system_no" value="">
    <div style='float: left; width: 638px; border-right: 1px solid #DEDEDD; min-height: 505px;'>
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
        </div>
        <div style='float:right; margin: 10px; width:200px; height:150px;position: relative;'>
            <img id="preview" src="<?= URL ?>uploads/building/<?=$strBuildingSystem->system_image?>" width="100%" alt="" style="max-height: 100%;max-width: 100%;width: auto;height: auto;position: absolute; top: 0;bottom: 0;left: 0; right: 0; margin: auto;"> 
        </div>
               
        <div style="margin-top:20px;margin-left:20px;float:left;">
            <span id="Lighting_Button_1" style="padding: 5px; cursor: pointer; background-color: rgb(205, 205, 205);">Lighting 1</span>
            <span id="Lighting_Button_2" style="padding: 5px; cursor: pointer; background-color: rgb(205, 205, 205);">Lighting 2</span>
            <span id="Lighting_Button_3" style="padding: 5px; cursor: pointer; background-color: rgb(205, 205, 205);">Lighting 3</span>
            <span id="Lighting_Button_Add" style="padding: 5px; cursor: pointer; background-color: rgb(205, 205, 205);">ADD</span>
        </div>
        <div style="float:left; width: 65%; border: 1px solid #DEDEDD; margin-top: 5px; height: 380px;">
            <div id="lighting_panel_1">
                <input type='text' id="screen_name_1" placeholder='6-CHAR NAME' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->screen_name?>">
                <input type='text' id="capacity_1" placeholder='#CIRCUITS' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->capacity?>">
                <input type='text' id="panel_loads_1" placeholder='PANEL LOAD AMPS' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->panel_loads?>">
                <input type='text' id="rating_1" placeholder='RATING' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->rating?>">

                <div style="padding: 5px;">Link Equipment Node - System 1</div>
                <select id="linked_node_1" name="linked_node" style='width: 180px; margin: 5px;' onchange="nodeSelected()">
                    <option value="">Select System Node</option>
                    <?php foreach($strNodesArr as $strNodes) { ?>
                    <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->linked_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                    <?php } ?>
                </select>
                <span style="color: #75C493" id="selected_node_1"><?=$strBuildingSystem->linke_node_name?></span>
            </div>
            
            <div id="lighting_panel_2" style="display:none;">
                <input type='text' id="screen_name_2" placeholder='6-CHAR NAME' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->screen_name?>">
                <input type='text' id="capacity_2" placeholder='#CIRCUITS' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->capacity?>">
                <input type='text' id="panel_loads_2" placeholder='PANEL LOAD AMPS' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->panel_loads?>">
                <input type='text' id="rating_2" placeholder='RATING' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->rating?>">

                <div style="padding: 5px;">Link Equipment Node - System 1</div>
                <select id="linked_node_2" name="linked_node" style='width: 180px; margin: 5px;' onchange="nodeSelected()">
                    <option value="">Select System Node</option>
                    <?php foreach($strNodesArr as $strNodes) { ?>
                    <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->linked_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                    <?php } ?>
                </select>
                <span style="color: #75C493" id="selected_node_2"><?=$strBuildingSystem->linke_node_name?></span>
            </div>
            
            <div id="lighting_panel_3" style="display:none;">
                <input type='text' id="screen_name_3" placeholder='6-CHAR NAME' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->screen_name?>">
                <input type='text' id="capacity_3" placeholder='#CIRCUITS' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->capacity?>">
                <input type='text' id="panel_loads_3" placeholder='PANEL LOAD AMPS' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->panel_loads?>">
                <input type='text' id="rating_3" placeholder='RATING' style='margin: 5px; width: 130px;' value="<?=$strBuildingSystem->rating?>">

                <div style="padding: 5px;">Link Equipment Node - System 1</div>
                <select id="linked_node_3" name="linked_node" style='width: 180px; margin: 5px;' onchange="nodeSelected()">
                    <option value="">Select System Node</option>
                    <?php foreach($strNodesArr as $strNodes) { ?>
                    <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->linked_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                    <?php } ?>
                </select>
                <span style="color: #75C493" id="selected_node_3"><?=$strBuildingSystem->linke_node_name?></span>
            </div>
            
            <div class='clear'></div>
            
            <div style="padding: 5px;">Specifications - System 1</div>
            <select id="gallery_id" name="gallery_id" style="width:150px;margin-left:5px;" onchange="getTechFiles(this.value)">
                <option value="">Choose from Gallery Files</option>
                <?php while($strGallery = mysql_fetch_object($strGalleryArr)) { ?>
                <option value="<?=$strGallery->image_id?>"><?=$strGallery->image_name?></option>
                <?php } ?>
            </select>
            <select id="t_files" name="t_files" style="width:150px;margin-left:10px;">
                <option value="">Technical Files</option>
            </select>
            <input id="technical_files" type="hidden" value="<?=$strBuildingSystem->technical_files?>">
            <button style="background-color:#CDCDCD;border: 1px solid #000;margin-left: 10px; padding: 3px 10px;" building_id="<?= $building_id ?>" onclick="setTechFile()">Add</button>

            <div id="tech_file_container">
            <?php $files_array = explode("~#~", $strBuildingSystem->technical_files); 
                $file_count = 0;
                foreach($files_array as $index=>$file){
                    if($file != ''){
                        $file_count++;
                        print '<div style="padding: 5px 10px;"><span>'.($index+1).'.Technical File:</span><span style="margin-left: 20px;">'.$file.'</span></div>';
                    }
                }
            ?>
            </div>
            
            <div style="padding: 5px; margin-top: 15px;"><b>Navigation Image</b></div>
            <span style="padding: 5px;">System Image</span>
            <?php if ($strBuildingSystem && $strBuildingSystem->navigation_image !="") {
                    echo '<input type="button" style="width: 80px; padding: 2px;" value="Change" onclick="$(this).next().next().show();$(this).next().hide();$(this).hide();"> <span style="overflow: hidden; padding-left: 5px;width: 100px;display:-moz-stack" title="' . $strBuildingSystem->navigation_image . '"> ' . $strBuildingSystem->navigation_image . '</span><input type="file" name="file" id="navigation_image"  style="display:none;width: 135px; padding: 2px;" >';
                } else { ?>
                    <input type="file" name="file" id="navigation_image"  style="width: 190px; padding: 2px;" onchange="loadFile1(event);">
            <?php } ?>
        </div>
    </div>
    <div style='float: left; width: 360px; font-size: 12px;'>
        <div style="padding: 0 5px;">Variable 1<br>
            <input type='text' id="variable1_name" placeholder='Variable Title' style='margin: 5px; width: 150px;' value="<?=$strBuildingSystem->variable1_name?>">
            <select id="variable1_unit" name="t_files" style="width:150px;margin-left:10px;">
                <option value="">Select Unit</option>
                <?php foreach($strVariableUnitArr as $strVariableUnit) { ?>
                <option value="<?=$strVariableUnit->id?>" <?php if($strVariableUnit->id==$strBuildingSystem->variable1_unit){echo "selected";} ?>><?=$strVariableUnit->variable_unit?></option>
                <?php } ?>
            </select>
            <span style="width: 150px; display: inline-block; padding: 0px 15px;">Ref Node:</span><span style="width: 140px; display: inline-block; padding: 0px 15px;">Calculation:</span>
            <select id="variable1_node" name="t_files" style="width: 165px; margin-left: 5px;">
                <option value="">Select System Name</option>
                <?php foreach($strNodesArr as $strNodes) { ?>
                <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->variable1_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                <?php } ?>
            </select>
            <select id="variable1_calc" name="t_files" style="width: 150px; margin-left: 15px;">
                <option value="">Select Calculation</option>
                <?php foreach($strCalcUnitArr as $strCalcUnit) { ?>
                <option value="<?=$strCalcUnit->id?>" <?php if($strCalcUnit->id==$strBuildingSystem->variable1_calc){echo "selected";} ?>><?=$strCalcUnit->calculation_unit?></option>
                <?php } ?>
            </select>
            <hr style="margin: 5px">
        </div>
        
        <div style="padding: 0 5px;">Variable 2<br>
            <input type='text' id="variable2_name" placeholder='Variable Title' style='margin: 5px; width: 150px;' value="<?=$strBuildingSystem->variable2_name?>">
            <select id="variable2_unit" name="t_files" style="width:150px;margin-left:10px;">
                <option value="">Select Unit</option>
                <?php foreach($strVariableUnitArr as $strVariableUnit) { ?>
                <option value="<?=$strVariableUnit->id?>" <?php if($strVariableUnit->id==$strBuildingSystem->variable2_unit){echo "selected";} ?>><?=$strVariableUnit->variable_unit?></option>
                <?php } ?>
            </select>
            <span style="width: 150px; display: inline-block; padding: 0px 15px;">Ref Node:</span><span style="width: 140px; display: inline-block; padding: 0px 15px;">Calculation:</span>
            <select id="variable2_node" name="t_files" style="width: 165px; margin-left: 5px;">
                <option value="">Select System Name</option>
                <?php foreach($strNodesArr as $strNodes) { ?>
                <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->variable2_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                <?php } ?>
            </select>
            <select id="variable2_calc" name="t_files" style="width: 150px; margin-left: 15px;">
                <option value="">Select Calculation</option>
                <?php foreach($strCalcUnitArr as $strCalcUnit) { ?>
                <option value="<?=$strCalcUnit->id?>" <?php if($strCalcUnit->id==$strBuildingSystem->variable2_calc){echo "selected";} ?>><?=$strCalcUnit->calculation_unit?></option>
                <?php } ?>
            </select>
            <hr style="margin: 5px">
        </div>
        
        <div style="padding: 0 5px;">Variable 3<br>
            <input type='text' id="variable3_name" placeholder='Variable Title' style='margin: 5px; width: 150px;' value="<?=$strBuildingSystem->variable3_name?>">
            <select id="variable3_unit" name="t_files" style="width:150px;margin-left:10px;">
                <option value="">Select Unit</option>
                <?php foreach($strVariableUnitArr as $strVariableUnit) { ?>
                <option value="<?=$strVariableUnit->id?>" <?php if($strVariableUnit->id==$strBuildingSystem->variable3_unit){echo "selected";} ?>><?=$strVariableUnit->variable_unit?></option>
                <?php } ?>
            </select>
            <span style="width: 150px; display: inline-block; padding: 0px 15px;">Ref Node:</span><span style="width: 140px; display: inline-block; padding: 0px 15px;">Calculation:</span>
            <select id="variable3_node" name="t_files" style="width: 165px; margin-left: 5px;">
                <option value="">Select System Name</option>
                <?php foreach($strNodesArr as $strNodes) { ?>
                <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->variable3_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                <?php } ?>
            </select>
            <select id="variable3_calc" name="t_files" style="width: 150px; margin-left: 15px;">
                <option value="">Select Calculation</option>
                <?php foreach($strCalcUnitArr as $strCalcUnit) { ?>
                <option value="<?=$strCalcUnit->id?>" <?php if($strCalcUnit->id==$strBuildingSystem->variable3_calc){echo "selected";} ?>><?=$strCalcUnit->calculation_unit?></option>
                <?php } ?>
            </select>
            <hr style="margin: 5px">
        </div>
        
        <div style="padding: 0 5px;">Variable 4<br>
            <input type='text' id="variable4_name" placeholder='Variable Title' style='margin: 5px; width: 150px;' value="<?=$strBuildingSystem->variable4_name?>">
            <select id="variable4_unit" name="t_files" style="width:150px;margin-left:10px;">
                <option value="">Select Unit</option>
                <?php foreach($strVariableUnitArr as $strVariableUnit) { ?>
                <option value="<?=$strVariableUnit->id?>" <?php if($strVariableUnit->id==$strBuildingSystem->variable4_node){echo "selected";} ?>><?=$strVariableUnit->variable_unit?></option>
                <?php } ?>
            </select>
            <span style="width: 150px; display: inline-block; padding: 0px 15px;">Ref Node:</span><span style="width: 140px; display: inline-block; padding: 0px 15px;">Calculation:</span>
            <select id="variable4_node" name="t_files" style="width: 165px; margin-left: 5px;">
                <option value="">Select System Name</option>
                <?php foreach($strNodesArr as $strNodes) { ?>
                <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->variable4_node){echo "selected";} ?>><?=$strNodes->node_serial?></option>
                <?php } ?>
            </select>
            <select id="variable4_calc" name="t_files" style="width: 150px; margin-left: 15px;">
                <?php foreach($strCalcUnitArr as $strCalcUnit) { ?>
                <option value="">Select Calculation</option>
                <option value="<?=$strCalcUnit->id?>" <?php if($strCalcUnit->id==$strBuildingSystem->variable4_calc){echo "selected";} ?>><?=$strCalcUnit->calculation_unit?></option>
                <?php } ?>
            </select>
            <hr style="margin: 5px">
        </div>
        
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
    
    $('[id^="Lighting_Button_"]').click(function(){
        var id = $(this).attr('id');
        var id_num = id.split('_')[2]; 
        console.log(id_num);

        $('[id^="Lighting_Button_"]').css('background-color', '#CDCDCD');
        $('[id^="Lighting_Button_"]').css('color', '#000');
        $(this).css('background-color', '#000');
        $(this).css('color', '#fff');
        
        $('[id^="lighting_panel_"]').hide();
        $('#lighting_panel_'+id_num).show();
    });

    $('#Lighting_Button_1').trigger('click');
    
    function save_system(){
        //return;
        var building_id = "<?=$building_id?>";
        var system_no = "<?=$system_no?>";
        var system_type = '';
        
        var system_id = $('#system_id').val();
        var system_parent_name = $('#system_id option:selected').parent().attr('label');
        var system_name = $('#system_id option:selected').text();
        var system_name_array = system_name.split('-');
        if(system_name_array[1]){
            system_type = $.trim(system_name_array[1]);
        }else{
            if(system_parent_name == "LIGHTING"){
                system_type = "LIGHTING";
            }
        }
//        var value = $('#system_id').val();
//        var new_value = value.split('`#`');
//        var system_id = new_value[0];
//        var system_type = new_value[1];
        var formData = new FormData();
        formData.append('mode', "add");
        formData.append('building_id', building_id);
        formData.append('system_id', system_id);
        formData.append('system_type', system_type);
        formData.append('system_no', system_no);
        formData.append('system_display_name', $('#system_display_name').val());
        formData.append('system_description', $('#system_description').val());
        formData.append('screen_name', $('#screen_name').val());
        formData.append('capacity', $('#capacity').val());
        formData.append('linked_node', $('#linked_node').val());
        formData.append('system_image', $('#image_file')[0].files[0]);
        //formData.append('unit_air_pressure', $('#unit_air_pressure').val());
        formData.append('technical_files', $('#technical_files').val());
        formData.append('pa_system1', $('#pa_system1').val());
        formData.append('pa_system2', $('#pa_system2').val());
        formData.append('pa_system3', $('#pa_system3').val());
        formData.append('pa_system4', $('#pa_system4').val());
        
        formData.append('variable1_name', $('#variable1_name').val());
        formData.append('variable1_unit', $('#variable1_unit').val());
        formData.append('variable1_node', $('#variable1_node').val());
        formData.append('variable1_calc', $('#variable1_calc').val());
        
        formData.append('variable2_name', $('#variable2_name').val());
        formData.append('variable2_unit', $('#variable2_unit').val());
        formData.append('variable2_node', $('#variable2_node').val());
        formData.append('variable2_calc', $('#variable2_calc').val());
        
        formData.append('variable3_name', $('#variable3_name').val());
        formData.append('variable3_unit', $('#variable3_unit').val());
        formData.append('variable3_node', $('#variable3_node').val());
        formData.append('variable3_calc', $('#variable3_calc').val());
        
        formData.append('variable4_name', $('#variable4_name').val());
        formData.append('variable4_unit', $('#variable4_unit').val());
        formData.append('variable4_node', $('#variable4_node').val());
        formData.append('variable4_calc', $('#variable4_calc').val());
        
        var lighting = [];
        $('[id^="Lighting_Button_"]').each(function(){
            var id = $(this).attr('id');
            var id_num = id.split('_')[2];  
            if(id_num > 0){
                lighting[id_num-1] = {
                    screen_name : $('#screen_name_'+id_num).val(),
                    capacity : $('#capacity_'+id_num).val(),
                    panel_loads : $('#panel_loads_'+id_num).val(),
                    rating : $('#rating_'+id_num).val(),
                    linked_node : $('#linked_node_'+id_num).val(),
                };
            }
        });
        
        formData.append('lighting', JSON.stringify(lighting));

        console.log(formData)
        $.ajax({
            type: "POST",
            url: "<?= URL ?>/ajax_pages/system_manage_lighting_form.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                alert("saved successfully");
                $('#System_Button_'+$.trim(data)).trigger('click');
            }
        });
    }
    
    var loadFile = function(event) {
        var output = document.getElementById('preview');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
    
    function nodeSelected(){
        if($('#linked_node').val() != ""){
            $('#selected_node').html($('#linked_node option:selected').html());
        }else{
            $('#selected_node').html('');
        }
    }
    
    function getTechFiles(gallery_id){
        $.get("<?= URL ?>/ajax_pages/system_manage_form.php",
            {
                gallery_id: gallery_id
            },
            function (data) {
                $('#t_files').html(data);
            }
        );
    }
    
    var file_count = <?=$file_count?>;
    function setTechFile(){
        var file = $('#t_files').val();
        if(file != ""){
            if($('#technical_files').val() != ""){
                $('#technical_files').val($('#technical_files').val()+"~#~"+file);
            }else{
                $('#technical_files').val(file);
            }
            file_count++;
            $('#tech_file_container').append('<div style="padding: 5px 10px;"><span>'+file_count+'.Technical File:</span><span style="margin-left: 20px;">'+file+'</span></div>');
        }
    }
</script>