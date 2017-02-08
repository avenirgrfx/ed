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
?>

<script>
    function showByCharacter(char){
        $('#Controls_Container').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/fetch_equipment_nodes.php", {char: char},
            function (data, status) {
                $('#Controls_Container').html(data);
        });
    }
</script>
<strong style="font-size:14px;">Link Available Nodes with CPE</strong>

<div id='EquipmentNodeSetup_Container_Div' style='width: 650px;'></div>

<hr style="border-bottom:1px #999999 dotted;">
<?php 
    $strSQL="Select count(1) as count, LEFT(system_name, 1) as fc from t_system where parent_id=0 group by fc order by fc asc";	
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

<ul style="cursor:pointer; width:600px;">
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
            $strSQL = "Select * from t_system where parent_id=" . $strRsSubCat1->system_id . " order  by system_name asc";
            $strRsSubCat2Arr = $DB->Returns($strSQL);
            while ($strRsSubCat2 = mysql_fetch_object($strRsSubCat2Arr)) {
                $strHasNodeStyle = "";
                if ($strRsSubCat2->has_node == 1) {
                    $strHasNodeStyle = 'text-decoration:underline; font-style: italic; ';
                }
                print "<li><span style='$strHasNodeStyle' onclick=SystemOptions('" . $strRsSubCat2->system_id . "')>" . $strRsSubCat2->system_name . "</span>&nbsp;&nbsp;<span id='" . $strRsSubCat2->system_id . "'></span><ul>";
                $strSQL = "Select * from t_system where parent_id=" . $strRsSubCat2->system_id . " order  by system_name asc";
                $strRsSubCat3Arr = $DB->Returns($strSQL);

                $iAlternate = 0;
                while ($strRsSubCat3 = mysql_fetch_object($strRsSubCat3Arr)) {
                    $iAlternate++;
                    $strHasNodeStyle = "";
                    if ($strRsSubCat3->has_node == 1) {
                        $strHasNodeStyle = 'text-decoration:underline; font-style: italic; ';
                    }


                    if ($iAlternate % 2 == 0)
                        $strAlternate = 'background-color:#EFEFEF;';
                    else
                        $strAlternate = 'background-color:#CDCDCD;';


                    $complexity = ($strRsSubCat3->complexity == 1 ? 'Simple' : ($strRsSubCat3->complexity == 2 ? 'Complex' : ($strRsSubCat3->complexity == 3 ? 'Specialized' : '' ) ));
                    if ($complexity <> "")
                        $complexity = "($complexity)";

                    print "<li style='$strAlternate '>
						<div style='width:350px; float:left;'><span style='$strHasNodeStyle' onclick=SystemOptions('" . $strRsSubCat3->system_id . "')>" . $strRsSubCat3->system_name . "</span> <span style='margin-left:5px; font-size:11px;'>" . $complexity . "</span></div>
						<div style='float:left;'><a href='javascript:LoadEquipmentNodeDetails(" . $strRsSubCat3->system_id . ")'>Manage Node</a></div> 
						<span id='" . $strRsSubCat3->system_id . "'></span>
						<div class='clear'></div>
						<ul>";

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