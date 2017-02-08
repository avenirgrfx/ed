<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');


$DB=new DB;
$System=new System;

$txtSearch = $_GET['search'];

if($txtSearch != ""){
    $strSQL="Select T.* from t_system T left join t_system TT on TT.parent_id = T.system_id left join t_system TTT on TTT.parent_id = TT.system_id left join t_system TTTT on TTTT.parent_id = TTT.system_id where T.parent_id=0 and (TTT.system_name like '%$txtSearch%' or TTT.system_name like '%".strtolower($txtSearch)."%' or TTTT.system_name like '%$txtSearch%' or TTTT.system_name like '%".strtolower($txtSearch)."%' ) order  by T.system_name asc";	
    $strRsCategoryArr=$DB->Returns($strSQL);		
    while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
    {
        print "<li style='width:350px; float:left; margin-right: 50px;'><b> <span>". $strRsCategory->system_name."</span></b> &nbsp;&nbsp;<span id='".$strRsCategory->system_id."'></span><ul>";
        $strSQL="Select * from t_system where parent_id=".$strRsCategory->system_id." order  by system_name asc";	
        $strRsSubCat1Arr=$DB->Returns($strSQL);
        while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
        {
            $strHasNodeStyle="";
            if($strRsSubCat1->has_node==1)
            {
                $strHasNodeStyle='text-decoration:underline; font-style: italic; ';
            }
            print "<li><span style='$strHasNodeStyle'>".$strRsSubCat1->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat1->system_id."'></span><ul>";				
            $strSQL="Select * from t_system where parent_id=".$strRsSubCat1->system_id." order  by system_name asc";	
            $strRsSubCat2Arr=$DB->Returns($strSQL);
            while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
            {
                $strHasNodeStyle="";
                if($strRsSubCat2->has_node==1)
                {
                    $strHasNodeStyle='text-decoration:underline; font-style: italic; ';
                }
                print "<li><span style='$strHasNodeStyle'>".$strRsSubCat2->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat2->system_id."'></span><ul>";				
                $strSQL="Select * from t_system where parent_id=".$strRsSubCat2->system_id." order  by system_name asc";	
                $strRsSubCat3Arr=$DB->Returns($strSQL);
                while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
                {
                    $strHasNodeStyle="";
                    if($strRsSubCat3->has_node==1)
                    {
                        $strHasNodeStyle='text-decoration:underline; font-style: italic; background:#CDCDCD;';
                    }
                    print "<li><span style='$strHasNodeStyle'>".$strRsSubCat3->system_name."</span>&nbsp;&nbsp;<span id='".$strRsSubCat3->system_id."'></span></li>";

                }
                print "</ul></li>";
            }
            print "</ul></li>";

        }
        print "</ul><hr style='border-bottom:1px #999999 dotted;'></li>";
    }
    exit;
}
?>

<script>
    function SearchEquipments(){
        var search = $('#txtSystemName').val();
        if(search != ""){
            $.get("<?php echo URL ?>ajax_pages/search_equipments.php", {search: search},
                function (data, status) {
                    $('#search_equipment_container').html(data);
            });
        }else{
            $('#search_equipment_container').html("");
        }
    }
</script>

<strong style="font-size:14px;">Search Equipment</strong>
<br><br>

<div style="float:left; width:220px;">
    <input type="text" id="txtSystemName" name="txtSystemName" onkeyup="SearchEquipments()" placeholder="System Name" style="width:200px;" />
</div>

<!--<div style="float:left; width:200px;">
    <input type="button" id="search" name="search" value="Search" onclick="SearchEquipments()" style="float:left;" />
</div>-->

<div class="clear"></div>

<hr style="border-bottom:1px #999999 dotted;">

<ul style="cursor:pointer; width:1200px;" id="search_equipment_container"></ul>