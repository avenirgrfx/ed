<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath . "classes/all.php");
require_once(AbsPath . "classes/system.class.php");
require_once(AbsPath . "classes/projects.class.php");

$DB = new DB;
$System = new System;
$Project = new Project;
$txtChar = $_GET['char'];

if (!$txtChar != "") {
    $txtChar = 'A';
}
?>
<script type="text/javascript">

    function showByCharacter(char) {
        $('#Controls_Container').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/show_building.php", {char: char, id:<?php echo $_GET['id'] ?>},
        function (data, status) {
            $('#' + <?php echo $_GET['id'] ?>).show();
            $('#' + <?php echo $_GET['id'] ?>).html(data);
        });
    }

    function ShowNode_Details(strSystemNodeID)
    {
        var PlusMinus = document.getElementById("Node_Details_Plus_Minus_" + strSystemNodeID).innerHTML;

        if (PlusMinus == "+")
        {
            $.get("<?php echo URL ?>ajax_pages/show_node_details.php",
                    {
                        strSystemNodeID: strSystemNodeID,
                    },
                    function (data, status) {
                        $('#Node_Details_' + strSystemNodeID).css('display', 'block');
                        $('#Node_Details_' + strSystemNodeID).html(data);
                        document.getElementById("Node_Details_Plus_Minus_" + strSystemNodeID).innerHTML = '-';
                    });
        }
        else
        {
            $('#Node_Details_' + strSystemNodeID).html("");
            $('#Node_Details_' + strSystemNodeID).css('display', 'none');
            document.getElementById("Node_Details_Plus_Minus_" + strSystemNodeID).innerHTML = '+';
        }
    }

    function Link_External_Sensor(strSystemNodeID)
    {
        var isChecked = document.getElementById('chkExternal_Sensor_For_' + strSystemNodeID).checked;

        $.get("<?php echo URL ?>ajax_pages/link_unlink_external_node.php",
                {
                    strSystemNodeID: strSystemNodeID,
                    isChecked: isChecked,
                },
                function (data, status) {

                    ShowNode_Details(strSystemNodeID);
                });

    }

    function PlusMinusBuilding(strBuildingID)
    {
        var PlusMinus = document.getElementById("Building_Details_Plus_Minus_" + strBuildingID).innerHTML;
        if (PlusMinus == '-')
        {
            $("#Building_Node_Details_" + strBuildingID).slideUp('slow');
            document.getElementById("Building_Details_Plus_Minus_" + strBuildingID).innerHTML = '+';
        }
        else
        {
            $("#Building_Node_Details_" + strBuildingID).slideDown('slow');
            document.getElementById("Building_Details_Plus_Minus_" + strBuildingID).innerHTML = '-';
        }
    }

    function THN_Node_Settings_Behavior_Link(strSystemNodeID, strType, strWidgetID)
    {
        //strType=1 for Settings
        //strType=2 for Behavior
        //strType=3 for Link

        if ($('#Node_Settings_Behavior_Link_' + strWidgetID).css('display') == 'none')
        {
            $('#Node_Settings_Behavior_Link_' + strWidgetID).html('Loading...');
            if (strType == 1)
            {
                $('#Node_Settings_Behavior_Link_' + strWidgetID).css('margin-left', '460px');
            }
            else if (strType == 2)
            {
                $('#Node_Settings_Behavior_Link_' + strWidgetID).css('margin-left', '570px');
            }
            else if (strType == 3)
            {
                $('#Node_Settings_Behavior_Link_' + strWidgetID).css('margin-left', '690px');
            }

            $('#Node_Settings_Behavior_Link_1_' + strWidgetID).html('&nbsp;');
            $('#Node_Settings_Behavior_Link_2_' + strWidgetID).html('&nbsp;');
            $('#Node_Settings_Behavior_Link_3_' + strWidgetID).html('&nbsp;');

            $('#Node_Settings_Behavior_Link_1_' + strWidgetID).css('text-decoration', 'none');
            $('#Node_Settings_Behavior_Link_2_' + strWidgetID).css('text-decoration', 'none');
            $('#Node_Settings_Behavior_Link_3_' + strWidgetID).css('text-decoration', 'none');

            if (strType == 1)
            {
                $('#Node_Settings_Behavior_Link_1_' + strWidgetID).html('Settings');
            }
            else if (strType == 2)
            {
                $('#Node_Settings_Behavior_Link_2_' + strWidgetID).html('Behavior');
            }
            else if (strType == 3)
            {
                $('#Node_Settings_Behavior_Link_3_' + strWidgetID).html('Link');
            }


            $.get("<?php echo URL ?>ajax_pages/widget_settings_behavior_link.php",
                    {
                        strSystemNodeID: strSystemNodeID,
                        strType: strType,
                        strWidgetID: strWidgetID,
                        strWidgetType: 'THN',
                    },
                    function (data, status) {
                        $('#Node_Settings_Behavior_Link_' + strWidgetID).html(data);
                        $('#Node_Settings_Behavior_Link_' + strWidgetID).slideDown('fast');
                    });



        }
        else
        {
            $('#Node_Settings_Behavior_Link_' + strWidgetID).html('');
            $('#Node_Settings_Behavior_Link_' + strWidgetID).css('margin-left', '0px');
            $('#Node_Settings_Behavior_Link_' + strWidgetID).slideUp('fast');
            $('#Node_Settings_Behavior_Link_1_' + strWidgetID).html('Settings');
            $('#Node_Settings_Behavior_Link_2_' + strWidgetID).html('Behavior');
            $('#Node_Settings_Behavior_Link_3_' + strWidgetID).html('Link');
        }
    }
    function LinkSystemNode()
    {

        var node_serial_number = document.getElementById('txtnode_serial_number').value;
        var node_custom_name = document.getElementById('txtnode_custom_name').value;
        var system_id = document.getElementById('txt_system_id').value;
        var txtnode_Description_name = document.getElementById('txtnode_Description_name').value;
        var ddlCategory = document.getElementById('ddlCategory').value;
        //var ddlAvailableNodes=document.getElementById('ddlAvailableNodes').value;
        $.post("<?php echo URL ?>ajax_pages/system_node_mapping.php",
                {
                    txtnode_serial_number: node_serial_number,
                    txtnode_custom_name: node_custom_name,
                    txtsystem_id: system_id,
                    //ddlAvailableNodes:ddlAvailableNodes,
                    ddlCategory: ddlCategory,
                    txtnode_Description_name: txtnode_Description_name,
                },
                function (data, status) {
                    alert(data);
                    //LoadSystemNodeDetails(system_id)
                    LoadEquipmentNodeDetails(system_id);
                });
    }

    function DeleteSystemNode(DeleteSystemNodeID, strSystemID)
    {
        if (!confirm("Are you sure you want to Delete?"))
            return;
        $.get("<?php echo URL ?>ajax_pages/system_node_mapping.php",
                {
                    DeleteSystemNodeID: DeleteSystemNodeID,
                    mode: 'delete',
                },
                function (data, status) {
                    //LoadSystemNodeDetails(strSystemID)
                    LoadEquipmentNodeDetails(strSystemID);

                });
    }

    function EditNodeCustomName(strNodeID)
    {
        var strNodeCustomeName = document.getElementById('CustomName_' + strNodeID).innerHTML;
        document.getElementById('CustomName_' + strNodeID).innerHTML = '<input style="width:150px;" type="text" name="CustomName_Edit_' + strNodeID + '" id="CustomName_Edit_' + strNodeID + '" value="' + strNodeCustomeName + '" />';
        var strNodeDescription = document.getElementById('Description_' + strNodeID).innerHTML;
        document.getElementById('Description_' + strNodeID).innerHTML = '<input style="width:150px;" type="text" name="Description_Edit_' + strNodeID + '" id="Description_Edit_' + strNodeID + '" value="' + strNodeDescription + '" />';
        document.getElementById('CustomName_Control_' + strNodeID).innerHTML = '<a href="javascript:EditNodeCustomName_Cancel(' + strNodeID + ')">Cancel</a> | <a href="javascript:EditNodeCustomName_Update(' + strNodeID + ')">Update</a>';


    }

    function EditNodeCustomName_Cancel(strNodeID)
    {
        document.getElementById('CustomName_' + strNodeID).innerHTML = document.getElementById('CustomName_Edit_' + strNodeID).value;
        document.getElementById('Description_' + strNodeID).innerHTML = document.getElementById('Description_Edit_' + strNodeID).value;
        document.getElementById('CustomName_Control_' + strNodeID).innerHTML = '<a href="javascript:EditNodeCustomName(' + strNodeID + ')">Edit</a>';




    }



    function EditNodeCustomName_Update(strNodeID)
    {
        var strEditCustomName = document.getElementById('CustomName_Edit_' + strNodeID).value;
        var strEditDescription = document.getElementById('Description_Edit_' + strNodeID).value;

        $.get("<?php echo URL ?>ajax_pages/system_node_mapping.php",
                {
                    EditNodeID: strNodeID,
                    EditCustomName: strEditCustomName,
                    EditDescription: strEditDescription,
                    mode: 'update',
                },
                function (data, status) {
                    document.getElementById('CustomName_' + strNodeID).innerHTML = strEditCustomName;
                    document.getElementById('Description_' + strNodeID).innerHTML = strEditDescription;
                    document.getElementById('CustomName_Control_' + strNodeID).innerHTML = '<a href="javascript:EditNodeCustomName(' + strNodeID + ')">Edit</a>';
                });


    }
</script>
 <div id="CpeBox" class="CpeBox" style="display: none; top: 0px; left: 0px;">
  </div>
<?php
$strSiteID = $_GET['id'];

$strSQL = "Select * from t_building where site_id=$strSiteID";
$strRsBuildingArr = $DB->Returns($strSQL);

while ($strRsBuilding = mysql_fetch_object($strRsBuildingArr)) {
    ?>
    <div class="clear"></div>
    <div style='float:left; margin-left:50px;margin-top:15px;' id='building_system_nodes_<?php echo $strRsBuilding->building_id; ?>'>
        <div style="float:left;"><select name="project_list_<?php echo $strRsBuilding->building_id ?>" id="project_list_<?php echo $strRsBuilding->building_id ?>"><?php $Project->ShowBuildingProjectWithRoom($strRsBuilding->building_id); ?></select></div>        
        <div style="float:left; margin-left:20px;"><select name='ddlMasterSystemList' id='ddlMasterSystemList_<?php echo $strRsBuilding->building_id; ?>' onchange="SubSystemList(this.value,<?php echo $strRsBuilding->building_id; ?>)"><?php $System->ListSystemForTree(); ?></select></div>
        <div style="float:left; margin-left:20px;" id='ddlSubSystemList_<?php echo $strRsBuilding->building_id; ?>'></div>
        <div style="float:right; margin-right:220px;" id='ddlRoomList_<?php echo $strRsBuilding->building_id; ?>'></div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <hr style="border-bottom:1px #999999 dotted;">
    <?php 
    //echo "<div class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:normal;'>Building:</span> ".$strRsBuilding->building_name."</div>";
    echo "<div onclick='PlusMinusBuilding(" . $strRsBuilding->building_id . ")' class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:bold; font-size:20px;' id='Building_Details_Plus_Minus_" . $strRsBuilding->building_id . "'>-</span><span style='font-weight:normal;'>Building:</span> <span style='text-decoration:underline;'>" . $strRsBuilding->building_name . "</span></div>";
    ?>
   

    <div class='clear'></div>
    <?php
    // $strSQL = "select count(1) count,tsn.parent_parent_parent_id,UPPER(LEFT(ts.system_name,1)) fc from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id where tsn.building_id=$strRsBuilding->building_id and tsn.delete_flag=0 group by(tsn.parent_parent_parent_id) order by ts.system_name asc";
    $strSQL = "select count(1) count,tsn.system_id system,tp.project_name,tsn.system_node_id,tsn.node_serial,tsn.description,tsn.custom_name,tsn.project_id,tsn.node_serial,tsn.parent_parent_parent_id system_id ,ts.system_name,UPPER(LEFT(ts.system_name,1)) fc from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and tsn.delete_flag=0 group by fc";

    $strRsCategoryArr = $DB->Returns($strSQL);

    $fc_array = array();
    while ($strRsCategory = mysql_fetch_object($strRsCategoryArr)) {
        $fc_array[$strRsCategory->fc] = $strRsCategory->count;
    }

    foreach (range('A', 'Z') as $char) {
        echo '<div onclick="showByCharacter(\'' . $char . '\')" style="float: left; width: 10px; padding: 14px; cursor:pointer; ' . ($txtChar == $char ? 'background:#cccccc;' : '') . '">';
        echo '<div>' . $char . '</div>';
        if (isset($fc_array["$char"])) {
            echo '<div>' . $fc_array[$char] . '</div>';
        }
        echo '</div>';
    }
    ?>
    <div class="clear"></div>
    <hr style="border-bottom:1px #999999 dotted;">
     
    <?php
    $strSQL = "select * from t_system where system_id in(select tsn.parent_parent_parent_id system_id from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and (ts.system_name like '$txtChar%'))";
    $strRsProjectsArr = $DB->Returns($strSQL);
    if (mysql_num_rows($strRsProjectsArr) > 0) {
        while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {
            
            echo "<b>" . $strRsProjects->system_name . "</b>";
            $strSQL = "select * from t_system where system_id in(select tsn.parent_parent_id system_id from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and (ts.system_name like '$txtChar%'))";
            $strRsProjectsArr = $DB->Returns($strSQL);
            if (mysql_num_rows($strRsProjectsArr) > 0) {
                while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {

                    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;" . $strRsProjects->system_name . "<br>";
                    $strSQL = "select * from t_system where system_id in(select tsn.parent_id system_id from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and (ts.system_name like '$txtChar%'))";
                    $strRsProjectsArr = $DB->Returns($strSQL);
                    if (mysql_num_rows($strRsProjectsArr) > 0) {
                        while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {

                            echo "&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;" . $strRsProjects->system_name . "<br>";
                            $strSystemID = $strRsProjects->system_id;
                            $strSQL = "select * from t_system where system_id in(select tsn.system_id system_id from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and (ts.system_name like '$txtChar%'))";
                            $strRsProjectsArr = $DB->Returns($strSQL);
                            if (mysql_num_rows($strRsProjectsArr) > 0) {
                                while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {

                                    echo "&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;" . $strRsProjects->system_name . "<br>";
                                    $strSQL = "select tp.project_name,tsn.system_node_id,tsn.node_serial,tsn.custom_name,tsn.project_id,tsn.node_serial,tsn.parent_parent_parent_id system_id ,ts.system_name,UPPER(LEFT(ts.system_name,1)) fc from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and (ts.system_name like '$txtChar%')";
                                    $strRsProjectsArr = $DB->Returns($strSQL);

                                    if (mysql_num_rows($strRsProjectsArr) > 0) {
                                        print "<div style='margin-left:50px; margin-top:10px; margin-bottom:20px;'>";
                                        while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {

                                            $strSQL = "select tsn.system_id system,tp.project_name,tsn.system_node_id,tsn.node_serial,tsn.description,tsn.custom_name,tsn.project_id,tsn.node_serial,tsn.parent_parent_parent_id system_id ,ts.system_name,UPPER(LEFT(ts.system_name,1)) fc from t_system_node tsn inner join t_system ts on tsn.parent_parent_parent_id=ts.system_id inner join t_projects tp on tp.projects_id=tsn.project_id where tsn.building_id=$strRsBuilding->building_id and (ts.system_name like '$txtChar%')and tsn.delete_flag=0";
                                            $strRsProjectsArr = $DB->Returns($strSQL);
                                            ?>
                                            <?php if (mysql_num_rows($strRsProjectsArr) > 0) { ?>
                                                <b style="font-size:14px;">Available Linked Nodes</b>
                                                <table id="NodeListTable" width="100%" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;">
                                                    <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                                                        <td width="19%">Node Serial</td>
                                                <!--        <td width="18%">Linked with</td>-->
                                                        <td width="15%">Description</td>
                                                        <td width="10%">Category</td>
                                                        <td width="15%">Date Created</td>
                                                        <td width="10%">Action</td>
                                                        <td width="15%">CPE</td>
                                                    </tr>

                                                    <?php
                                                    while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {
                                                        $strSystemID = $strRsProjects->system;
                                                        $iCtr ++;
                                                        if ($iCtr % 2 == 1)
                                                            $strRowClass = "OddRow";
                                                        else
                                                            $strRowClass = "EvenRow";
                                                        ?>
                                                        <tr class="<?php echo $strRowClass; ?>">
                                                            <td>
                                                                <?php echo $strRsProjects->node_serial; ?><br />
                                                                <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsProjects->system_node_id; ?>"><?php echo $strRsProjects->custom_name; ?></div>

                                                                <div class="clear"></div>
                                                            </td>

                                                            <td><div style="float:left; font-style:italic;" id="Description_<?php echo $strRsProjects->system_node_id; ?>"><?php echo $strRsProjects->description ?></div></td>
                                                            <td><?php echo $strRsProjects->category_name ?></td>
                                                    <!--        <td>
                                                            <?php // echo $strRsSystemNodeSerial->available_system_node_serial;   ?>
                                                            </td>-->
                                                            <td><?php echo Globals::DateFormat($strRsProjects->doc, 1); ?></td>
                                                            <td><div id="CustomName_Control_<?php echo $strRsProjects->system_node_id; ?>" style="float:left;padding: 0 4px;"><a href="javascript:EditNodeCustomName('<?php echo $strRsProjects->system_node_id; ?>')" style="padding-left:4px;">Edit</a></div>/<a href="javascript:DeleteSystemNode('<?php echo $strRsProjects->system_node_id; ?>','<?php echo $strSystemID ?>')" style="padding-left:4px;">Delete</a></td>
                                                            <td>
                                                                <?php $strSQL="select * from t_cpe_link where node_id='".$strRsProjects->node_serial."'";
                                                                     $strRsParentIDsArr = $DB->Returns($strSQL);
                                                                     while ($strRsParentIDs = mysql_fetch_object($strRsParentIDsArr)) {
                                                                                           ?>
                                                           <a href="javascript:Review('<?php echo $strRsParentIDs->cpe_serial_no?>','<?=$strRsProjects->node_serial?>')"><?php echo $strRsParentIDs->cpe_name; ?>-<?php echo $strRsParentIDs->cpe_serial_no?></a>
                                                                     <?php }?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                                <?php
                                            }



//                                     if (mysql_num_rows($strRsProjectsArr) > 0) {
//                                         print "<div style='margin-left:50px; margin-top:10px; margin-bottom:20px;'>";
//                                         while ($strRsProjects = mysql_fetch_object($strRsProjectsArr)) {   //print "Project: <b>".$strRsProjects->project_name."</b><br />";
//                                             //print ": <b>".$strRsProjects->system_name."</b><br />";
//                                             print "
//						<div onclick='ShowNode_Details(" . $strRsProjects->system_node_id . ")' style='margin-left:100px;  cursor:pointer; color:'><span style='font-weight:bold;' id=Node_Details_Plus_Minus_" . $strRsProjects->system_node_id . ">+</span>Node: <a href='javascript:ShowNode_Details(" . $strRsProjects->system_node_id . ")' style='text-decoration:underline;'>" . $strRsProjects->node_serial . ($strRsProjects->custom_name <> '' ? " (" . $strRsProjects->custom_name . ")" : "" ) . "</a>
//						</div>";
//
//
//                                             print "<div id='Node_Details_" . $strRsProjects->system_node_id . "' style='display:none;'></div>";
//                                         }
//                                         print "</div>";
//                                     }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    ?>
    <br>
    <br>
    <div class='clear'></div>

    <div id='building_<?php echo $strRsBuilding->building_id; ?>'>

    <?php
    $strBuildingID = $strRsBuilding->building_id;
    
    /* -------  Building Projects  -----------  */
    $strSQL="Select Distinct t_projects.project_name, projects_id project_id from t_projects where t_projects.room_id=0 and building_id=".$strBuildingID;
    $strRsProjectsArr=$DB->Returns($strSQL);
    if(mysql_num_rows($strRsProjectsArr)>0)
    {
        print "<div style='margin-left:90px; margin-top:10px; margin-bottom:20px;'>";
        print '<div style="margin-left: -90px; padding-left: 90px;border-bottom:1px dashed #CCCCCC; background-color:#DDDDDD; text-decoration:underline; font-size:14px; font-weight:bold;">Building Projects</div>';
        while($strRsProjects=mysql_fetch_object($strRsProjectsArr))
        {
            print "Project: <b>".$strRsProjects->project_name."</b><br />";

            //$strSQL="Select system_node_id, node_serial, custom_name from t_system_node where project_id=".$strRsProjects->project_id." and room_id=".$strRoomID;
            $strSQL="Select system_node_id, node_serial, custom_name from t_system_node where delete_flag=0 and project_id=".$strRsProjects->project_id;
            $strRsNodeByProjectsArr=$DB->Returns($strSQL);
            while($strRsNodeByProjects=mysql_fetch_object($strRsNodeByProjectsArr))
            {
                if(strstr( strtolower($strRsNodeByProjects->node_serial) ,'thn'))
                    $ExternalNode=1;
                else
                    $ExternalNode=0;


                if($ExternalNode==1)
                {
                    $strSQL="Select thn_widget_id from t_thn_widget where system_node_id=".$strRsNodeByProjects->system_node_id." and external_visible=1";
                    $strRsExternalVisibleArr=$DB->Returns($strSQL);

                    if($strRsExternalVisible=mysql_fetch_object($strRsExternalVisibleArr))
                    {
                        $strChecked=' Checked ';
                    }
                    else
                    {
                        $strChecked='';
                    }
                    print "
                    <div style='margin-left:100px; width:350px; float:left;  cursor:pointer;'><span style='font-weight:bold;' id=Node_Details_Plus_Minus_".$strRsNodeByProjects->system_node_id.">+</span>Node: <a href='javascript:ShowNode_Details(".$strRsNodeByProjects->system_node_id.")' style='text-decoration:underline;'>".$strRsNodeByProjects->node_serial. ($strRsNodeByProjects->custom_name<>'' ? " (".$strRsNodeByProjects->custom_name.")" : "" ). "</a>
                    </div>
                    <div style='float:left; margin-left:10px;'>
                        <div style='float:left; margin-top: -3px;'><input $strChecked  onclick='Link_External_Sensor(".$strRsNodeByProjects->system_node_id.")' type='checkbox' name='chkExternal_Sensor_For_".$strRsNodeByProjects->system_node_id."' id='chkExternal_Sensor_For_".$strRsNodeByProjects->system_node_id."' value='1' /></div>
                        <div style='float:left; margin-left:3px;'>External Sensor</div>
                        <div class='clear'></div>
                    </div>
                    <div class='clear'></div>";
                }
                else
                {
                    print "
                    <div style='margin-left:100px;  cursor:pointer; color:'><span style='font-weight:bold;' id=Node_Details_Plus_Minus_".$strRsNodeByProjects->system_node_id.">+</span>Node: <a href='javascript:ShowNode_Details(".$strRsNodeByProjects->system_node_id.")' style='text-decoration:underline;'>".$strRsNodeByProjects->node_serial.($strRsNodeByProjects->custom_name<>'' ? " (".$strRsNodeByProjects->custom_name.")" : "" )."</a>
                    </div>";
                }


                print "<div id='Node_Details_".$strRsNodeByProjects->system_node_id."' style='display:none;'></div>";
            }
        }
        print "</div>";
    }
    /* -------  Building Projects End -----------  */
    
    $strSQL = "Select * from t_room where building_id=$strBuildingID";
    $strRsRoomArr = $DB->Returns($strSQL);
    while ($strRsRoom = mysql_fetch_object($strRsRoomArr)) {
        echo "<div class='room_folder' style='margin-left:5px;' id='room_icon_" . $strRsRoom->room_id . "'>
            <div style='float:left; font-size:15px; padding-bottom:5px; width:300px; cursor:pointer; margin-top:3px; text-decoration:underline; font-weight:bold;' onclick=ShowRoomNodeDetails('" . $strRsRoom->room_id . "')><span style='font-weight:normal;'><span id='Node_Room_Plus_Minus_" . $strRsRoom->room_id . "'>+</span>Room: </span>" . $strRsRoom->room_name . "</div>
            <div style='float:left; width:720px; text-align:center; padding:2px 5px;'>";
        
        echo "</div><div class='clear'></div></div><div id='room_" . $strRsRoom->room_id . "'></div>";
    }
    ?>

    </div>
    <hr style="border-bottom:1px #999999 dotted;">
    <div class='clear'></div>

    <div id="Building_Node_Details_<?php echo $strRsBuilding->building_id; ?>"></div>
    <div class='clear'></div>
<?php
}