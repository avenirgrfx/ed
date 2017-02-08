<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');

$DB=new DB;
$System=new System;

if (isset($_GET['parent_id']) && isset($_GET['name']) && $_GET['mode'] == "add_new_level") {
    $prefix = $_GET['prefix'];
    //echo $prefix;exit;
    $chr = 'A';
    $prefix_selected = 0;
    while(!$prefix_selected){
        $strSQL = "select system_id from t_system where level=4 and prefix = '" . $prefix.$chr . "'";
        $node_serial = $DB->Returns($strSQL);
        if(mysql_num_rows($node_serial)>0){
            if($chr == 'Z'){
                $chr = 'A';
                $prefix++;
            }else{
                $chr = chr(ord($chr) + 1);
            }
        }else{
            $prefix_selected = 1;
        }
    }
    //echo $prefix.$chr;exit;
    
    $strSQL = "insert into t_system (parent_id, system_name,level,prefix) values ('" . $_GET['parent_id'] . "', '" . $_GET['name'] . "',4 ,'" . $prefix.$chr . "')";
    $DB->Returns($strSQL);
    echo "added";
}

if (isset($_POST) && !empty($_POST)) {
    if ($_POST['mode'] == "add") {
        $name = $_POST['txtEquipmentName'];
        $string = explode(" ", $name);
        $length = count($string);
        for ($i = 0; $i <= $length; $i++) {
            $prefix = strtoupper($prefix . $string[$i][0]);
        }
        //echo $prefix;exit;
        $chr = 'A';
        $prefix_selected = 0;
        while(!$prefix_selected){
            $strSQL = "select system_id from t_system where level=3 and prefix = '" . $prefix.$chr . "'";
            $node_serial = $DB->Returns($strSQL);
            if(mysql_num_rows($node_serial)>0){
                $chr = chr(ord($chr) + 4);
            }else{
                $prefix_selected = 1;
            }
        }
        //echo $prefix.$chr;exit;
        
        $strSQL = "insert into t_system (parent_id, system_name, complexity, fuel_type_id, category_id, has_gallery, exclude_in_calculation, prefix, level) values ('" . $_POST['txtSystemId'] . "', '" . $_POST['txtEquipmentName'] . "', '" . $_POST['txtComplexity'] . "', '" . $_POST['txtFuelTypeId'] . "', '" . $_POST['txtCategoryId'] . "', '" . $_POST['txtHasGallery'] . "', '" . $_POST['exclude_in_calculation'] . "','" . $prefix.$chr . "', 3)";
        $DB->Returns($strSQL);
    } else if ($_POST['mode'] == "delete") {
        $strSQL = "delete from t_system where system_id = '" . $_POST['txtEquipmentId'] . "'";
        $DB->Returns($strSQL);
    } else if ($_POST['mode'] == "update") {
        $strSQL = "update t_system set level = 3, parent_id = '" . $_POST['txtSystemId'] . "', system_name = '" . $_POST['txtEquipmentName'] . "', complexity = '" . $_POST['txtComplexity'] . "', fuel_type_id = '" . $_POST['txtFuelTypeId'] . "', category_id = '" . $_POST['txtCategoryId'] . "', has_gallery = '" . $_POST['txtHasGallery'] . "', exclude_in_calculation = '" . $_POST['exclude_in_calculation'] . "' where system_id = '" . $_POST['txtEquipmentId'] . "'";
        $DB->Returns($strSQL);
    } else if ($_POST['mode'] == "get") {
        $strSQL = "select * from t_system where system_id = '" . $_POST['txtSystemId'] . "'";
        $strEquipmentArr = $DB->Returns($strSQL);
        echo json_encode(mysql_fetch_object($strEquipmentArr));
    }
    exit;
}


$txtChar = $_GET['char'];

if(!$txtChar){
    $txtChar = 'A';
}

$strSQL="Select * from t_fuel_type";	
$strFuelTypeArr=$DB->Returns($strSQL);	

$strSQL="Select distinct unit from t_fuel_type";	
$strUnitArr=$DB->Returns($strSQL);

$strSQL="Select * from t_category where parent_id = 0";	
$strCategoryArr=$DB->Returns($strSQL);

?>

<script>
    function showByCharacter(char){
        $('#Controls_Container').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/fetch_equipments.php", {char: char},
            function (data, status) {
                $('#Controls_Container').html(data);
        });
    }   
    
    function AddNewFuelType() {
        if($('#txtFuelTypeName').val() == ""){
            alert('Please enter fuel type');
            $('#txtFuelTypeName').trigger('focus');
            return false;
        }
        if($('#ddlFuelTypeUnit').val() == ""){
            alert('Please select unit');
            $('#ddlFuelTypeUnit').trigger('focus');
            return false;
        }
        
        $.post("<?php echo URL ?>ajax_pages/fuel_type.php", 
            {
                mode:"add",
                txtFuelTypeName: $('#txtFuelTypeName').val(),
                ddlFuelTypeUnit: $('#ddlFuelTypeUnit').val(),
            },
            function (data, status) {
                alert("fuel type added");
                $('#ddlFuelType').html(data);
                $('#txtFuelTypeName').val("");
                $('#ddlFuelTypeUnit').val("");
        });
    }
    
    function manageFuelType() {
        $.get("<?php echo URL ?>ajax_pages/fuel_type.php", {},
            function (data, status) {
                $('#CategoryAndFuelTypeContainer').show();
                $('#CategoryAndFuelTypeContainer').html(data);             
        });
    }
    
    function DeleteFuelType(id) {
        if(confirm("Are you sure you want to delete this Fuel Type.")){
            $.post("<?php echo URL ?>ajax_pages/fuel_type.php", 
                {
                    mode:"delete",
                    txtFuelTypeId: id
                },
                function (data, status) {
                    manageFuelType();
            });
        }
    }
    
    function EditFuelType(ele) {
        $(ele).parent().parent().find('span:first-child').html('<input type="text" value="'+$(ele).parent().parent().find('span:first-child').text()+'">');
        $(ele).prev().hide();
        $(ele).hide();
        $(ele).next().show();
    }
    
    function UpdateFuelType(ele, id) {
        $(ele).parent().parent().find('span:first-child').text($(ele).parent().parent().find('span:first-child input').val());
        $(ele).prev().show();
        $(ele).prev().prev().show();
        $(ele).hide();
        
        $.post("<?php echo URL ?>ajax_pages/fuel_type.php", 
            {
                mode:"update",
                txtFuelTypeId: id,
                txtFuelTypeName: $(ele).parent().parent().find('span:first-child').text(),
                ddlFuelTypeUnit: $(ele).parent().parent().find('span:nth-child(2) select').val(),
            },
            function (data, status) {
                manageFuelType();
        });
    }
    
    function closePopup(){
        $('#CategoryAndFuelTypeContainer').hide();
        $('#showEquipmentManagement').trigger('click');
    }
    
    function AddNewEquipment() {
       
        if($('#ddlSystem').val() == 0){
            alert('Please select a system');
            $('#ddlSystem').trigger('focus');
            return false;
        }
        if($('#txtEquipmentName').val() == "" && $("#ddlSystem option:selected").attr("levels")!="3"){
            alert('Please enter equipment name');
            $('#txtEquipmentName').trigger('focus');
            return false;
        }
         if($('#txtEquipmentName').val() == "" && $("#ddlSystem option:selected").attr("levels")=="3"){
            alert('Please enter node name');
            $('#txtEquipmentName').trigger('focus');
            return false;
        }
        if($('#ddlCategory').val() == 0){
            alert('Please select a category');
            $('#ddlCategory').trigger('focus');
            return false;
        }
        if($('#ddlFuelType').val() == 0){
            alert('Please select a fuel type');
            $('#ddlFuelType').trigger('focus');
            return false;
        }
        if($('#ddlComplexity').val() == 0){
            alert('Please select Complexity');
            $('#ddlComplexity').trigger('focus');
            return false;
        }
       
            
        $.post("<?php echo URL ?>ajax_pages/fetch_equipments.php", 
        {
            mode:"add",
            txtSystemId: $('#ddlSystem').val(),
            txtEquipmentName: $('#txtEquipmentName').val(),
            txtCategoryId: $('#ddlCategory').val(),
            txtFuelTypeId: $('#ddlFuelType').val(),
            txtComplexity: $('#ddlComplexity').val(),
            txtHasGallery: $('#chkHasGallery').is(':checked') ? 1 : 0,
            exclude_in_calculation: $('#showInConsumption').is(':checked') ? 0 : 1,
           // prefix:$('#prefix').val(),
            level:$("#ddlSystem option:selected").attr("levels")=="3",
        },
        function (data, status) {
            alert("Equipment Added");
            //$('#showEquipmentManagement').trigger('click');
            showByCharacter("<?=$txtChar?>");
        });
    }
    
    function EditEquipment(equip_id) {
        $.post("<?php echo URL ?>ajax_pages/fetch_equipments.php", 
            {
                mode:"get",
                txtSystemId: equip_id
            },
            function (data, status) {
                data = JSON.parse(data);
                
                $('#System_ID').val(data.system_id);
                $('#ddlSystem').val(data.parent_id);
                $('#txtEquipmentName').val(data.system_name);
                $('#ddlCategory').val(data.category_id);
                $('#ddlFuelType').val(data.fuel_type_id);
                $('#ddlComplexity').val(data.complexity);
                if(data.has_gallery == 1){
                    $('#chkHasGallery').prop('checked', true);
                }else{
                    $('#chkHasGallery').prop('checked', false);
                }
                if(data.exclude_in_calculation == 1){
                    $('#showInConsumption').prop('checked', false);
                }else{
                    $('#showInConsumption').prop('checked', true);
                }
                
                $('#btn_Add').hide();
                $('#btn_Update').show();
                $('#btn_Delete').show();
        });
    }
    
    function EditEquipmentLevelFour(equip_id) {
        $.post("<?php echo URL ?>ajax_pages/fetch_equipments.php", 
            {
                mode:"get",
                txtSystemId: equip_id
            },
            function (data, status) {
                data = JSON.parse(data);
                
                $('#System_ID').val(data.system_id);
                $('#ddlSystem').val(data.parent_id);
                $('#txtEquipmentName').val(data.system_name);
                $('#ddlCategory').val(data.category_id);
                $('#ddlFuelType').val(data.fuel_type_id);
                $('#ddlComplexity').val(data.complexity);
                if(data.has_gallery == 1){
                    $('#chkHasGallery').prop('checked', true);
                }else{
                    $('#chkHasGallery').prop('checked', false);
                }
                
                $('#btn_Add').hide();
                $('#btn_Update').show();
                $('#btn_Delete').show();
        });
    }
    
    function UpdateEquipment() {
        if($('#ddlSystem').val() == 0){
            alert('Please select a system');
            $('#ddlSystem').trigger('focus');
            return false;
        }
        if($('#txtEquipmentName').val() == ""){
            alert('Please enter equipment name');
            $('#txtEquipmentName').trigger('focus');
            return false;
        }
        if($('#ddlCategory').val() == 0){
            alert('Please select a category');
            $('#ddlCategory').trigger('focus');
            return false;
        }
        if($('#ddlFuelType').val() == 0){
            alert('Please select a fuel type');
            $('#ddlFuelType').trigger('focus');
            return false;
        }
        if($('#ddlComplexity').val() == 0){
            alert('Please select Complexity');
            $('#ddlComplexity').trigger('focus');
            return false;
        }
        
        $.post("<?php echo URL ?>ajax_pages/fetch_equipments.php", 
        {
            mode:"update",
            txtEquipmentId: $('#System_ID').val(),
            txtSystemId: $('#ddlSystem').val(),
            txtEquipmentName: $('#txtEquipmentName').val(),
            txtCategoryId: $('#ddlCategory').val(),
            txtFuelTypeId: $('#ddlFuelType').val(),
            txtComplexity: $('#ddlComplexity').val(),
            txtHasGallery: $('#chkHasGallery').is(':checked') ? 1 : 0,
            exclude_in_calculation: $('#showInConsumption').is(':checked') ? 0 : 1,

            prefix:$('#prefix').val(),
        },
        function (data, status) {
            alert("Equipment updated");
            //$('#showEquipmentManagement').trigger('click');
            showByCharacter("<?=$txtChar?>");
        });
    }
    
    function DeleteEquipment() {
        if(confirm("Are you sure you want to delete this Equipment.")){
            $.post("<?php echo URL ?>ajax_pages/fetch_equipments.php", 
                {
                    mode:"delete",
                    txtEquipmentId: $('#System_ID').val()
                },
                function (data, status) {
                    alert("Equipment deleted");
                    //$('#showEquipmentManagement').trigger('click');
                    showByCharacter("<?=$txtChar?>");
            });
        }
    }
</script>

<strong style="font-size:14px;">Add a New Equipment</strong>
    <br><br>

<form id="frmSystem" name="frmSystem" action="" method="post">
    <div style="float:left; width:180px;">
        <select id="ddlSystem" name="ddlSystem" style="width:170px;">    	
            <?php $System->ListSystemForEquipments();?>
        </select>
    </div>
    <div style="float:left; width:200px;">
        <input type="text" id="txtEquipmentName" name="txtEquipmentName" placeholder="New Equipment Name"  style="width:175px;"/>        
    </div>   
    
    <div style="float:left; width:200px;">
        <div style="float:right; margin-right: 10px; margin-top: -20px; cursor: pointer;" onclick="manageCategory()">Manage</div>
        <select id="ddlCategory" name="ddlCategory" style="width:190px;">    	
            <option value="0">Select Category</option>
            <?php while($strCategory=mysql_fetch_object($strCategoryArr)) {
                print '<option value="'.$strCategory->category_id.'">'.$strCategory->category_name.'</option>';
            } ?>
        </select>
        
        <div style="float:left; width:190px; margin-top: 10px;">
            <input type="text" id="txtCategoryName" name="txtSystemName" placeholder="Add New Category"  style="width:130px;"/>
            <input type="button" style="float:right; padding: 2px 5px;" value="Add" name="btnAdd" id="btnAdd" onclick="AddNewCategory()">
        </div>
    </div>
    
    <div style="float:left; width:260px;">
        <div style="float:right; margin-right: 10px; margin-top: -20px; cursor: pointer;" onclick="manageFuelType()">Manage</div>
        <select id="ddlFuelType" name="ddlFuelType" style="width:250px;">    	
            <option value="0">Select Fuel Type</option>
            <?php while($strFuelType=mysql_fetch_object($strFuelTypeArr)) {
                print '<option value="'.$strFuelType->fuel_type_id.'">'.$strFuelType->fuel_type.' - '.$strFuelType->unit.'</option>';
            } ?>
        </select>
        <div style="float:left; width:250px; margin-top: 10px;">
            <input type="text" id="txtFuelTypeName" name="txtSystemName" placeholder="Add New Fuel Type"  style="width:125px;"/>
            <select id="ddlFuelTypeUnit" name="ddlFuelTypeUnit" style="width:64px;">    	
                <option value="">Units</option>
                <?php while($strUnit=mysql_fetch_object($strUnitArr)) {
                print '<option>'.$strUnit->unit.'</option>';
                } ?>
            </select>
            <input type="button" style="float:right; padding: 2px 5px;" value="Add" name="btnAdd" id="btnAdd" onclick="AddNewFuelType()">
        </div>
    </div>
    
    <div style="float:left; width:200px;">
        <select id="ddlComplexity" name="ddlComplexity" style="width:190px;">    	
            <option value="0">Select Complexity</option>
            <option value="1">Simple</option>
            <option value="2">Complex</option>
            <option value="3">Specialized</option>
        </select>
    </div>
    
    <div style="float:left; width:120px;">
        <input type="checkbox" value="1" name="chkHasWidget" id="chkHasGallery" /> Add to Gallery
        <input type="checkbox" value="1" name="showInConsumption" id="showInConsumption" checked /> Show in Consumption
    </div>
    
    <div style="float: right; width: 175px;">
        <input type="button" id="btn_Add" name="btn_Add" value="Add" style="float: right; width: 75px;" onclick="AddNewEquipment()"/>
        <input type="button" id="btn_Update" name="btn_Update" value="Update" style="display:none; float:left; margin-left:5px;" onclick="UpdateEquipment()" />
        <input type="button" id="btn_Delete" name="btn_Delete" value="Delete" style="display:none; float:left; margin-left:5px;" onclick="DeleteEquipment()" />
        <div style="float:left; margin-left:10px; font-size:12px; color:#666666; margin-top:5px;" id="CannotDelete"></div>
        <div class="clear"></div>
    </div>
    
    <div class="clear">
      <input type="hidden" name="type" id="type" value="System">
      <input type="hidden" name="System_ID" id="System_ID" value="" />
    </div>
</form>

<div id="CategoryAndFuelTypeContainer" style="display:none; width: 620px; position: absolute; height: 330px; border: 1px solid rgb(102, 102, 102); left: 320px; top: 350px; background-color: #F3F3F4; overflow-y: auto; border-radius: 10px; padding: 5px 15px;">

</div>    
    
<div id="Available_Widget_List" style="display:none;">
<?php  
    $strSQL="Select * from t_widgets order by widget_name asc";
    $strRsWidgetsArr=$DB->Returns($strSQL);
    $iCtrSystem=0;
    while($strRsWidgets=mysql_fetch_object($strRsWidgetsArr))
    {
        $iCtrSystem++;			
        print '<div style="float:left; width:300px;"><input type="checkbox" value="1" name="chkWidgetID_'.$strRsWidgets->widget_id.'" id="chkWidgetID_'.$strRsWidgets->widget_id.'" />'.$strRsWidgets->widget_name.'</div>';
        if($iCtrSystem % 3==0)
            print '<div class="clear;"></div>';
    }
?>
<div class="clear"></div>
</div>
    
<hr style="border-bottom:1px #999999 dotted;">
<?php 
$strSQL="Select count(1) as count, UPPER(LEFT(system_name, 1)) as fc from t_system where parent_id=0 group by fc order by fc asc";	
$strRsCategoryArr=$DB->Returns($strSQL);
$fc_array = array();
while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
{
    $fc_array[$strRsCategory->fc] = $strRsCategory->count;
}

foreach (range('A', 'Z') as $char) {
    echo '<div onclick="showByCharacter(\''.$char.'\')" style="float: left; width: 10px; padding: 14px; cursor:pointer; '.($txtChar==$char?'background:#cccccc;':'').'">';
    echo '<div>'.$char.'</div>';
    if(isset($fc_array["$char"])){ echo '<div>'. $fc_array[$char] .'</div>'; }
    echo '</div>';
} ?>
<div class="clear"></div>
<hr style="border-bottom:1px #999999 dotted;">
     

<ul style="width:1200px;">
<?php
    $strSQL="Select * from t_system where parent_id=0 and (system_name like '$txtChar%' or system_name like '".strtolower($txtChar)."%' ) order by system_name asc";	
    $strRsCategoryArr=$DB->Returns($strSQL);		
    while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
    {
        print "<li style='width:350px; float:left; margin-right: 50px;'><b> <span>". $strRsCategory->system_name."</span></b><ul>";
        
        $strSQL="Select * from t_system where parent_id=".$strRsCategory->system_id." order by system_name asc";	
        $strRsSubCat1Arr=$DB->Returns($strSQL);
        while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
        {
            print "<li><span>".$strRsSubCat1->system_name."</span><ul>";				
            $strSQL="Select * from t_system where parent_id=".$strRsSubCat1->system_id." order by system_name asc";	
            $strRsSubCat2Arr=$DB->Returns($strSQL);
            while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
            {
                $strHasNodeStyle="";
                if($strRsSubCat2->has_node==1)
                {
                    $strHasNodeStyle='text-decoration:underline; font-style: italic; ';
                }
                
                $strEquipmentStyle = '';
                if($strRsSubCat2->system_name == "ELECTRIC DISCONNECT" || strtolower($strRsSubCat2->system_name) == "ELECTRIC DISCONNECT")
                {
                    $strEquipmentStyle='color: #ff0000; ';
                }
                
                print "<li style='cursor:pointer;'><span style='$strHasNodeStyle $strEquipmentStyle' onclick=EditEquipment('".$strRsSubCat2->system_id."')>".$strRsSubCat2->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat2->system_id."'></span></li>";
            }
            print "</ul></li>";

        }
        print "</ul><hr style='border-bottom:1px #999999 dotted;'></li>";
    }
?>
</ul>
