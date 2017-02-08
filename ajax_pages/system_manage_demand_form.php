<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div style="" id="Main_Container">
    

        <div style="margin-top: 5px;" class="clear"></div>
        <div id="new_form_data">

<div style="margin-left:30px; width:1000px;border: 1px solid gray; float:left; position: relative;" class="imageNav">
    <div style="width:490px;float: left;border-right: 1px solid #DEDEDD; min-height: 505px; padding-left: 6px;padding-top: 6px;">
        <p>Choose Layout - System 1</p>
        <p>Main Layout 1
        <input type="file" name="file" placeholder="Choose File"></p>
        <div style="border: 1px solid gray;height: 240px;width: 480px;"></div>
        <div>
        <div style="margin:5px;">
            <span style="float:left">Navigation Layout 1</span>
            <span style="float:left">
                <input type="file">
            </span>
        </div>
            <div style="margin-top: 5px;" class="clear"></div>
         <div style="border: 1px solid gray;height:100px;width:100px; margin: 10px;"></div>
        </div>
    </div>
  

    <div style="width:496px;float: left;padding-left: 6px;padding-top: 6px;">
        <div>
        <p style="text-decoration: underline;">LEAK REFERENCE</p>
        <hr >
        <span style="float:left"><p>Leak #</p></span><span style="border: 1px solid gray;float:left;height: 26px;text-align: center;width: 15px;margin-left: 6px;"> 1</span>
        <span style="float:left;padding-left: 6px;"><input type="text" name="leak_title" placeholder="Leak Title"style="width: 150px;"></span>
        <span style="float:left;padding-left: 6px;"><input type="file" name="file"></span>
        <div style="float:left;">
        <div style="padding: 10px 30px;"><input type="text" name="Grid_reference" placeholder="Grid Reference"style="width:120px;"></div>
        <div><input type="text" name="DB" placeholder="DB" style="height: 45px;margin-left: 30px;width: 75px;"></div>
        </div>
        <div>
        <span style="border: 1px solid gray;float: left;height: 110px;margin: 10px;text-align: center;width: 210px;"><img src="" alt=""></span>
            </div>
        
        </div>
        <div style="float:left;width:499px;">
        <hr >
        <div style="height:205px">
        <span style="background: #cdcdcd none repeat scroll 0 0;color:#000">ADD NEW LEAK</span>
        </div>
        <div>
        <input type="button" value="save" style="float:right;margin-right: 10px;background: #cdcdcd none repeat scroll 0 0;color:#000;border-radius: 10px;">
        </div>
        </div>
    </div>

</div>
<div style="position:relative; width:30px; float:left; margin-left: 31px;transform: rotate(90deg); transform-origin: left top 0; -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -webkit-transform: rotate(90deg);">

    <div style="padding: 5px; height: 20px; position: absolute; left: 0px; color: rgb(255, 255, 255); background-color: rgb(0, 0, 0); border: 1px solid rgb(0, 0, 0); cursor: pointer;" id="right_button_1">CONTROLS</div>
    <div style="padding: 5px; height: 20px; position: absolute; left: 85px; color: rgb(0, 0, 0); background-color: rgb(205, 205, 205); border: 1px solid rgb(0, 0, 0); cursor: pointer;" id="right_button_2">SCHEDULES</div>
    <div style="padding: 5px; height: 20px; position: absolute; left: 174px; color: rgb(0, 0, 0); background-color: rgb(205, 205, 205); border: 1px solid rgb(0, 0, 0); cursor: pointer;" id="right_button_3">PERFORMANCE</div>
    <div style="padding: 5px; height: 20px; position: absolute; left: 287px; color: rgb(0, 0, 0); background-color: rgb(205, 205, 205); border: 1px solid rgb(0, 0, 0); cursor: pointer;" id="right_button_4">ANALYSIS</div>

</div>


<div class="clear"></div>
<div style="margin-left:30px; width:980px;border: 1px solid gray; float:left;padding: 10px;margin-top: 5px;">
    SYSTEM PRESSURE AVERAGE: Average Pressure <span style='background: #000; color: rgb(255, 255, 255); padding: 8px 20px;'>121</span> psi
    <select id="pa_system4" style='width: 135px; float:right;'>
        <option value="0">Select System Node</option>
        <?php foreach($strNodesArr as $strNodes) { ?>
        <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->pa_system4){echo "selected";} ?>><?=$strNodes->node_serial?></option>
        <?php } ?>
    </select>
    <select id="pa_system3" style='width: 135px; float:right;'>
        <option value="0">Select System Node</option>
        <?php foreach($strNodesArr as $strNodes) { ?>
        <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->pa_system3){echo "selected";} ?>><?=$strNodes->node_serial?></option>
        <?php } ?>
    </select>
    <select id="pa_system2" style='width: 135px; float:right;'>
        <option value="0">Select System Node</option>
        <?php foreach($strNodesArr as $strNodes) { ?>
        <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->pa_system2){echo "selected";} ?>><?=$strNodes->node_serial?></option>
        <?php } ?>
    </select>
    <select id="pa_system1" style='width: 135px; float:right;'>
        <option value="0">Select System Node</option>
        <?php foreach($strNodesArr as $strNodes) { ?>
        <option value="<?=$strNodes->system_node_id?>" <?php if($strNodes->system_node_id==$strBuildingSystem->pa_system1){echo "selected";} ?>><?=$strNodes->node_serial?></option>
        <?php } ?>
    </select>
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
        return;
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

        console.log(formData)
        $.ajax({
            type: "POST",
            url: "<?= URL ?>/ajax_pages/system_manage_form.php",
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
</div>

        <div style="margin-bottom: 20px;" class="clear"></div>
    </div>