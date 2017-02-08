<?php
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/gallery.class.php');


$DB = new DB;
$System = new System;
$txtChar = $_GET['char'];

if(!$txtChar){
    $txtChar = 'A';
}
if($_GET['prefix']=="prefix"){
    
  
  $parent_id=$_GET['parent_id'];
  $strSQL="select system_name from t_system where system_id=".$parent_id;
  $name_for_prefix = $DB->Returns($strSQL);
  $name = mysql_fetch_object($name_for_prefix);
  $name=$name->system_name;
  $string = split(" ", $name);
  $length=count($string);
  for($i=0;$i<=$length;$i++){
    $prefix=$prefix.$string[$i][0];
  }
  
   $strSQL="select count(1) as count from t_system where parent_id=".$parent_id." and level=4";
   $num_of_fourth_levels=$DB->Returns($strSQL);
   $strRsNodeSerialArr = $num_of_fourth_levelsARR = mysql_fetch_object($num_of_fourth_levels);
   $len= $num_of_fourth_levelsARR->count;
   $x = 'AA';
   for($i=1;$i<=$len;$i++){
          $x++;
        }
//  	if($strRsNodeSerialArr)
//	{
//	    $AvailableCount=$strRsNodeSerialArr->count;
//		$AvailableCount++;
//		if($AvailableCount>=0 and $AvailableCount<10)
//			$SerialNumber='000'.$AvailableCount."A";
//		elseif($AvailableCount>=10 and $AvailableCount<100)
//			$SerialNumber='00'.$AvailableCount."A";
//		elseif($AvailableCount>=100 and $AvailableCount<1000)
//			$SerialNumber='0'.$AvailableCount."A";
//		elseif($AvailableCount>=1000 and $AvailableCount<10000)
//			$SerialNumber=$AvailableCount."A";
//	}
//	
//	$SerialNumber=$strRsSystems->prefix.date("y").$SerialNumber;
    echo '<div id = "one">'.$prefix.'</div>';//.$SerialNumber;
    echo '<div id = "two">'.$prefix.$x.'</div>';
    $strSQL="select count(prefix) as prefix from t_system where prefix='".$prefix."'";
    $name_for_prefix = $DB->Returns($strSQL); 
    if($name = mysql_fetch_object($name_for_prefix))
      if($name->prefix==1){
          
      }
  exit;
}
?>

<script>
    function showByCharacter(char){
        $('#Controls_Container').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/fetch_equipment_nodes.php", {char: char},
            function (data, status) {
                $('#Controls_Container').html(data);
        });
    }
    
    function DeleteEntireNode(nodeID){
        if(!confirm("are you sure you want to delete")){
            return;
        }
        
        $.post("<?php echo URL?>ajax_pages/fetch_equipment_nodes.php",{nodeID:nodeID},
             function (data,status) {
                 if(data="done"){
                     showByCharacter("<?=$txtChar?>");
                 }
             });
    }
    
    function add_new_level(parent_id){
    $("#fourth_level_"+parent_id).show();
     var name=$("#"+parent_id).html();
      $.get("<?=URL?>ajax_pages/fetch_equipment_nodes.php",
        {
            parent_id:parent_id,
            name:name,
            prefix:"prefix",       
        },
        function(data,status){
           // console.log(data);
//         var $response=$(data);
//         var oneval = $response.filter('#one').html();
//         var subval = $response.filter('#two').html();
//         $("#prefix_name_"+parent_id).val(subval); 
//         $("#prefix_name_"+parent_id).attr("prefix_val",oneval)
         });
    }
   
    function add_fourth_level(parent_id){
         var name = $("#level_name_"+parent_id).val();
         var prefix = $("#prefix_name_"+parent_id).val();
         if(name==""){
             alert("Name is Required");
             return;
         }
        $.get("<?=URL?>ajax_pages/fetch_equipments.php",
        {
            parent_id:parent_id,
            prefix:prefix,
            name:name,
            mode:"add_new_level",
        },
        function(data,status){
          showByCharacter("<?=$txtChar?>");
        });
    }
    
    function Edit_fourth_level(strNodeID){
    var strNodeCustomeName=document.getElementById(strNodeID).innerHTML;
    var buttons=document.getElementById("button_"+strNodeID).innerHTML;
    document.getElementById('button_'+strNodeID).innerHTML='<a href="javascript:update_fourth_level('+strNodeID+')">Update</a> / <a href="javascript:cancel_fourth_level('+strNodeID+')">Cancel &nbsp;&nbsp; </a>';
    document.getElementById(strNodeID).innerHTML='<input style="width:150px;" type="text" name="CustomName_Edit_'+strNodeID+'" id="CustomName_Edit_'+strNodeID+'" value="'+strNodeCustomeName+'" />';

		  
    }
    
    function update_fourth_level(strNodeID){
      var strEditCustomName=document.getElementById("CustomName_Edit_"+strNodeID).value;
      			
			$.get("<?php echo URL?>ajax_pages/fetch_equipment.php",
			{
				EditNodeID:strNodeID,
				EditCustomName:strEditCustomName,
            	mode:'update',
			},
				function(data,status){							
					document.getElementById(strNodeID).innerHTML=strEditCustomName;
                    document.getElementById('button_'+strNodeID).innerHTML='<a href="javascript:Edit_fourth_level('+strNodeID+')">Edit</a> / <a href="javascript:delete_fourth_level('+strNodeID+')">Delete &nbsp;&nbsp; </a>';
			 });  
    }
    
    function cancel_fourth_level(strNodeID){
            document.getElementById(strNodeID).innerHTML=document.getElementById("CustomName_Edit_"+strNodeID).value;
		    document.getElementById('button_'+strNodeID).innerHTML='<a href="javascript:Edit_fourth_level('+strNodeID+')">Edit</a> / <a href="javascript:delete_fourth_level('+strNodeID+')">Delete &nbsp;&nbsp; </a>';
		 
    }
    
    function delete_fourth_level(parent_id,strNodeID){
        if(!confirm("Are you sure you want to delete")){
            return;
        }
        $.get("<?=URL?>/ajax_pages/node_serial.php",
        {
            parent_id:parent_id,
            strNodeID:strNodeID,
            mode:"delete_entire_node",
        },
        function(data,status){
            alert(data);
            //$("#showEquipmentNodes").trigger("click");
            showByCharacter('<?=$txtChar?>');
        }
        );
    }
    
</script>

<?php
    if(isset($_POST) && !empty($_POST)){ 
    $nodeID=$_POST["nodeID"];
    $strSQL="DELETE from t_system where system_id=$nodeID";	
    $strRsCategoryArr=$DB->Returns($strSQL);
    if($strRsCategoryArr){
        echo done;
        exit;
    }
    }
    
 
?>
 
<div id='EquipmentNodeSetup_Container_Div' style='width: 825px;'></div>

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
   
<ul style="cursor:pointer; width:700px;">
    <?php
    $iCtr = 0;
    $strSQL = "Select * from t_system where parent_id=0 and (system_name like '$txtChar%' or system_name like '".strtolower($txtChar)."%' ) order  by system_name asc";
    $strRsCategoryArr = $DB->Returns($strSQL);
    while ($strRsCategory = mysql_fetch_object($strRsCategoryArr)) {
        print "<li><b> <span onclick=SystemOptions('" . $strRsCategory->system_id . "')>" . $strRsCategory->system_name . "</span></b> &nbsp;&nbsp;<span id='" . $strRsCategory->system_id . "'></span><ul>";
        $strSQL = "Select * from t_system where parent_id=" . $strRsCategory->system_id . " order  by system_name asc";
        $strRsSubCat1Arr = $DB->Returns($strSQL);
        while ($strRsSubCat1 = mysql_fetch_object($strRsSubCat1Arr)) {
            $strHasNodeStyle = "";
            if ($strRsSubCat1->has_node == 1) {
                $strHasNodeStyle = 'text-decoration:underline; font-style: italic; ';
            }
            print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('" . $strRsSubCat1->system_id . "')>" . $strRsSubCat1->system_name . "</span>&nbsp;&nbsp;<span id='" . $strRsSubCat1->system_id . "'></span><ul>";
            $strSQL = "Select * from t_system where parent_id=" . $strRsSubCat1->system_id . "  order  by system_name asc";
            $strRsSubCat2Arr = $DB->Returns($strSQL);
            while ($strRsSubCat2 = mysql_fetch_object($strRsSubCat2Arr)) {
                $strHasNodeStyle = "";
                if ($strRsSubCat2->has_node == 1) {
                    $strHasNodeStyle = 'text-decoration:underline; font-style: italic; ';
                }
                print"<input type='hidden' id='id_of_third_level' value='$strRsSubCat2->system_id'>";
                
                $strEquipmentStyle = '';
                if($strRsSubCat2->system_name == "ELECTRIC DISCONNECT" || strtolower($strRsSubCat2->system_name) == "ELECTRIC DISCONNECT")
                {
                    $strEquipmentStyle='color: #ff0000; ';
                }
                print "<li><span id='$strRsSubCat2->system_id' style='$strHasNodeStyle $strEquipmentStyle' onclick=SystemOptions('" . $strRsSubCat2->system_id . "')>" . $strRsSubCat2->system_name . "</span>&nbsp;&nbsp;<span id='" . $strRsSubCat2->system_id . "'></span><span style='float:right;'><a href='javascript:add_new_level($strRsSubCat2->system_id)'>ADD Node</a></span><div id='fourth_level_$strRsSubCat2->system_id' style='display:none; margin-bottom: 10px; margin-left: 39px;'><span><input type='text' id='level_name_$strRsSubCat2->system_id' placeholder='Name of Node'></span><span><input type='text' placeholder='Prefix'><input type='hidden' id='prefix_name_$strRsSubCat2->system_id' value='$strRsSubCat2->prefix'></span><span><a href='javascript:add_fourth_level( $strRsSubCat2->system_id )'>Add</a></span></div><ul>";
                    
       //       print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('" . $strRsSubCat2->system_id . "')>" . $strRsSubCat2->system_name . "</span>&nbsp;&nbsp;<span id='" . $strRsSubCat2->system_id . "'></span></div><ul>";
                $strSQL = "Select * from t_system where parent_id=" . $strRsSubCat2->system_id . " order  by system_name asc";
                $strRsSubCat3Arr = $DB->Returns($strSQL);

                $iAlternate = 0;
                while ($strRsSubCat3 = mysql_fetch_object($strRsSubCat3Arr)) {
                    $iAlternate++;
                  //  $strHasNodeStyle = "";
                   // if ($strRsSubCat3->has_node == 1) {
                        $strHasNodeStyle = 'text-decoration:underline; font-style: italic; ';
                  //  }


                    if ($iAlternate % 2 == 0)
                        $strAlternate = 'background-color:#EFEFEF;';
                    else
                        $strAlternate = 'background-color:#CDCDCD;';


                    $complexity = ($strRsSubCat3->complexity == 1 ? 'Simple' : ($strRsSubCat3->complexity == 2 ? 'Complex' : ($strRsSubCat3->complexity == 3 ? 'Specialized' : '' ) ));
                    if ($complexity <> "")
                        $complexity = "($complexity)";

                    $strEquipmentStyle = '';
                    if($strRsSubCat2->system_name == "ELECTRIC DISCONNECT" || strtolower($strRsSubCat2->system_name) == "ELECTRIC DISCONNECT")
                    {
                        $strEquipmentStyle='color: #ff0000; ';
                    }
                
                    print "<li style='$strAlternate '>
						<div style='width:300px; float:left;'><span style='$strHasNodeStyle $strEquipmentStyle' onclick=SystemOptions('" . $strRsSubCat3->system_id . "') id='$strRsSubCat3->system_id'>" . $strRsSubCat3->system_name . "</span> <span style='margin-left:5px; font-size:11px;'>" . $complexity . "</span></div>
                     	<div style='float:right;'><a href='javascript:void(0)' onclick='LoadEquipmentNodeDetails($strRsSubCat3->system_id)'>Add / Manage Node</a> / <a href='javascript:delete_fourth_level($strRsSubCat2->system_id,$strRsSubCat3->system_id)'>  Delete Entire Node</a></div> 
						<span id='" . $strRsSubCat3->system_id . "'></span>
						<div class='clear'></div>
						<ul>";
//                    print "<li style='$strAlternate '>
//						<div style='width:300px; float:left;'><span style='$strHasNodeStyle' onclick=SystemOptions('" . $strRsSubCat3->system_id . "') id='$strRsSubCat3->system_id'>" . $strRsSubCat3->system_name . "</span> <span style='margin-left:5px; font-size:11px;'>" . $complexity . "</span></div>
//                        <div style='float:left;' id='button_$strRsSubCat3->system_id'><a href='javascript:Edit_fourth_level(" . $strRsSubCat3->system_id . ")'>Edit</a> / <a href='javascript:delete_fourth_level(" . $strRsSubCat3->system_id . ")'>Delete &nbsp;&nbsp; </a></div>
//						<div style='float:left;'><a href='javascript:LoadEquipmentNodeDetails(" . $strRsSubCat3->system_id . ")'>Add / Manage Node</a></div> 
//						<span id='" . $strRsSubCat3->system_id . "'></span>
//						<div class='clear'></div>
//						<ul>";

                    $strSQL = "Select * from t_system where parent_id=" . $strRsSubCat3->system_id . " order  by system_name asc";
                    $strRsSubCat4Arr = $DB->Returns($strSQL);
                    while ($strRsSubCat4 = mysql_fetch_object($strRsSubCat4Arr)) {
                        $strHasNodeStyle = "";
                        if ($strRsSubCat4->has_node == 1) {
                            $strHasNodeStyle = 'text-decoration:underline; font-style: italic; ';
                        }
                        print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('" . $strRsSubCat4->system_id . "')>" . $strRsSubCat4->system_name . "</span>&nbsp;&nbsp;<span  id='" . $strRsSubCat4->system_id . "'></span></li>";
                    }
                    print "</ul></li>";
                }
                print "</ul></li>";
            }
            print "</ul></li>";
        }
        print "</ul><hr style='border-bottom:1px #999999 dotted;'></li></li>";
    }
    ?>
</ul>
