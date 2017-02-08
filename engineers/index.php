<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
require_once(AbsPath . "classes/customer.class.php");
require_once(AbsPath . "classes/widget_category.class.php");

$DB = new DB;
$Category = new Category;
$System = new System;
$Gallery = new Gallery;
$Client = new Client;
$WidgetCategory = new WidgetCategory;

if ($_SESSION['user_login']->login_id == "") {
    Globals::SendURL(URL . 'login.php');
}

if ($_SESSION['user_login']->ENGINEER_ACCESS != 1) {
    Globals::SendURL(URL . 'login.php');
}

/* print "<pre>";
  print_r($_SESSION['user_login']);
  print "</pre>";
 */
if ($_POST['type'] == 'System') {
    $System->parent_id = $_POST['ddlSystem'];
    $System->system_name = $_POST['txtSystemName'];

    $System->display_type = $_POST['ddlUtilityClass'];
    $System->exclude_in_calculation = ($_POST['chkExcludeCalculation'] == "" ? 0 : 1);
    $System->uom = $_POST['ddlUtilityUOM'];
    $System->complexity = $_POST['ddlUnitComplexityLevel'];

    $System->has_node = ($_POST['chkHasWidget'] == "" ? 0 : 1);
    if ($_POST['System_ID'] == '') {
        $System->Insert();
    } else {
        $System->system_id = $_POST['System_ID'];
        $System->Update();
    }
    Globals::SendURL(URL . "engineers/?type=system");
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
    <head>
        <meta charset="utf-8">

        <title>energyDAS Engineers</title>

        <link rel="stylesheet" href="../css/prism.css">
        <link rel="stylesheet" href="../css/bootstrap.css">	
        <link rel="stylesheet" href="../css/master.css">
        <link rel="stylesheet" href="../css/tree.css">
        <link rel="stylesheet" href="../css/style_for_upgrade.css">

        <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
        
        <style>
			
			#Add_MAC{
				float:right;
				text-align:center;
				border-radius: 5px; 
				text-transform:uppercase; 
				width:100px;font-size:16px; 
				font-weight:bold; 
				margin-top:5px;
				border:1px solid black; 
				cursor: hand;
				cursor: pointer;
				margin-right:50px;
				display:none;
			}
			#Add_MAC:hover{ background-color: #90A1CA;}
		
			.controls {
				width: 40px;
				float: left;
				margin: 10px;
			}
			.controls a {
				background-color: #3D91A2;
				border-radius: 4px;
				border: 2px solid #3D91A2;
				color: #000000;
				padding: 2px;
				font-size: 14px;
				text-decoration: none;
				display: inline-block;
				text-align: center;
				margin: 5px;
				width: 20px;
			}
          
            /* Lightbox images */
            .Overlay {
                
                z-index: 9999;
               
            }

            .lightbox {
                position: fixed;
                left: 0;
                width: 100%;
                height:100%;
                z-index: 10001;
                text-align: center;
                line-height: 0;
                font-weight: normal;
                background: rgba(0, 0, 0, 0.8) none repeat
            }

            .lightbox .lb-image {
                display: block;
                border-radius: 3px;
                margin:auto;
                max-height: 600px;
            }

            .lightbox a img {
                border: none;
            }

            .lb-outerContainer {
                position: relative;
                background: rgba(0, 0, 0)
                *zoom: 1;
                top:50px;
                width: 50%;
                height: 100%;
                margin: 0 auto;
                border-radius: 4px;
            }

            .lb-outerContainer:after {
                content: "";
                display: table;
                clear: both;
            }

            .lb-container {
                padding: 0px 0px 0px 0px;
                position: relative;
                max-height: 90%;
                max-width: 100%;
                min-height: 90%;
                min-width: 100%;   
            }

            
            .lb-cancel {
                display: block;
                width: 32px;
                height: 32px;
                margin: 0 auto;
                background: url(../images/loading.gif) no-repeat;
            }
                                                  
            .lb-dataContainer {
                margin: 0 auto;
                padding-top: 0px;
                *zoom: 1;
                width: 100%;
                -moz-border-radius-bottomleft: 4px;
                -webkit-border-bottom-left-radius: 4px;
                border-bottom-left-radius: 4px;
                -moz-border-radius-bottomright: 4px;
                -webkit-border-bottom-right-radius: 4px;
                border-bottom-right-radius: 4px;
            }

            .lb-dataContainer:after {
                content: "";
                display: table;
                clear: both;
            }

            .lb-data {
                padding: 0 4px;
                color: #ccc;
            }

            .lb-data .lb-details {
                width: 85%;
                float: left;
                text-align: left;
                line-height: 1.1em;
            }

            .lb-data .lb-close {
                display: block;
                float: right;
                width: 30px;
                height: 30px;
                background: url(../images/close.png) top right no-repeat;
                text-align: right;
                outline: none;
                filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=70);
                opacity: 0.7;
                -webkit-transition: opacity 0.2s;
                -moz-transition: opacity 0.2s;
                -o-transition: opacity 0.2s;
                transition: opacity 0.2s;
            }

            .lb-data .lb-close:hover {
                cursor: pointer;
                filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);
                opacity: 1;
            }
            .widthHeight{
                width:100%;
                height:100%;
            }
               .RecentBox {
                position: fixed;
                left: 0;
                width: 100%;
                height:100%;
                z-index: 10000;
                text-align: center;
                line-height: 0;
                font-weight: normal;
                background: rgba(0, 0, 0, 0.8) none repeat
            }
            .R-outerContainer {
                margin-left: 25px;
                padding-right:12px;
                padding-left:20px;
                position: relative;
                background-color: white;
                *zoom: 1;
                overflow-y:auto;
                height: 250px;
         
                border-radius: 4px;
            }

            .R-outerContainer:after {
                content: "";
                display: table;
                clear: both;
            }
            .R-close {
                display: block;
                float: right;
                width: 30px;
                height: 30px;
                background: url(../images/close.png) top right no-repeat;
                text-align: right;
                outline: none;
                filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=70);
                opacity: 0.7;
                -webkit-transition: opacity 0.2s;
                -moz-transition: opacity 0.2s;
                -o-transition: opacity 0.2s;
                transition: opacity 0.2s;
            }
                 .CpeBox {
                position: fixed;
                left: 0;
                width: 100%;
                height:100%;
                z-index: 10000;
                text-align: center;
                line-height: 0;
                font-weight: normal;
                background: rgba(0, 0, 0, 0.8) none repeat
            }
            .Cpe-outerContainer {
                margin-left: 25px;
                padding-right:12px;
                padding-left:20px;
                position: relative;
                background-color: white;
                *zoom: 1;
                overflow-y:auto;
                height: 250px;
         
                border-radius: 4px;
            }

            .Cpe-outerContainer:after {
                content: "";
                display: table;
                clear: both;
            }
            .Cpe-close {
                display: block;
                float: right;
                width: 30px;
                height: 30px;
                background: url(../images/close.png) top right no-repeat;
                text-align: right;
                outline: none;
                filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=70);
                opacity: 0.7;
                -webkit-transition: opacity 0.2s;
                -moz-transition: opacity 0.2s;
                -o-transition: opacity 0.2s;
                transition: opacity 0.2s;
            }
        </style>

        <!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
        <script type='text/javascript' src="<?php echo URL ?>js/prism.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/fabric.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/jquery.js"></script>  
        <script type='text/javascript' src="<?php echo URL ?>js/bootstrap.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/paster.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/angular.min.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/font_definitions.js"></script>    
        <script type='text/javascript' src="<?php echo URL ?>js/utils.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/app_config.js"></script>
        <script type='text/javascript' src="<?php echo URL ?>js/controller.js"></script>
        <script type='text/javascript' src='<?php echo URL ?>js/tree.jquery.js'></script>
        <script type='text/javascript' src="<?php echo URL ?>js/jquery-ui.js"></script>
		<script type='text/javascript' src="<?php echo URL ?>js/jquery.form.min.js"></script>
        <script type='text/javascript'>

            /*$(window).bind("beforeunload", function()
             {
             return confirm("Do you really want to refresh?"); 
             });
             */
             
			var check = 0;
			var addmac = 0;
			var get_all;
            $(document).ready(function () {
				
                //   Top Menu Level 1
                $('#Home_Main_Menu').click(function () {
                    <?php if ($_SESSION['user_login']->ADMIN_ACCESS == 1) { ?>
                        window.location = '<?php echo URL ?>home.php';
                    <?php } else { ?>
                        window.location = '<?php echo URL ?>engineers/home.php';
                    <?php } ?>
                });
                
                $('#Administrator_Main_Menu').click(function () {
                    window.location = '<?php echo URL ?>';
                });
              
                 $('#Controls_Main_Menu').click(function () {
                    window.location = '<?php echo URL ?>controls/';
                });
                //   Top Menu Level 2
                $('#Projects_Main_Menu').click(function () {
                    $('.System_Menu').css('display', 'none');
                    $('.Project_Sub_Menu').css('display', 'block');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('.MandV_Sub_Menu').css('display', 'none');
                    $('.BottomMenu_1').slideUp('slow');
                    
                    $('#Projects_Main_Menu').addClass('active');
                    $('#Systems_Menu').removeClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');
                    
                    $('.Systems_Menu').css('display', 'none');
                    $('.Design_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('.MandV_Sub_Menu').css('display', 'none');
                    $('.Wifi_Sub_Menu').css('display', 'none');
                    $('.Application_Sub_Menu').css('display', 'none');
                    
                    $('.BottomMenu_1').slideUp('slow');
                    $('.BottomMenuSystems').slideUp('slow');
                    $('#Controls_Container').hide();
                    
                    $('#showProjectSetup').trigger('click');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');
                    
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');
                    $('#Firmware_upgrade_Menu').removeClass('active');
					$('#Settings_Container').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#addRouter_main_div').css('display', 'none');
                    
                    $('#Add_MAC').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    clearInterval(get_all);
                });

                $('#Systems_Menu').click(function () {
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Systems_Menu').addClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Systems_Menu').css('display', 'block');
                    $('.Design_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('.MandV_Sub_Menu').css('display', 'none');
                    $('.Wifi_Sub_Menu').css('display', 'none');
                    $('.Application_Sub_Menu').css('display', 'none');
                    
                    $('#showMasterSystems').trigger('click');
                    
                    $('.BottomMenu_1').slideUp('slow');

                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    
                    $('#Basic_Settings_Menu').removeClass('active');
                    $('#Firmware_upgrade_Menu').removeClass('active');
					$('#Settings_Container').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#Add_MAC').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                });
                
                $('#Design_Menu').click(function () {
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Systems_Menu').removeClass('active');
                    $('#Design_Menu').addClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');
                    
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Systems_Menu').css('display', 'none');
                    $('.Design_Menu').css('display', 'block');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('.MandV_Sub_Menu').css('display', 'none');
                    $('.Wifi_Sub_Menu').css('display', 'none');
                    $('.Application_Sub_Menu').css('display', 'none');
                    
                    $('#Design_Menu').addClass('active');
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');

                    $('.BottomMenu_1').slideUp('slow');
                    $('.BottomMenuSystems').slideUp('slow');
                    $('#Controls_Container').hide();
                    
                    //$('#showNewControl').trigger('click');
                    $('#showTree').trigger('click');


                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');
                    $('#Firmware_upgrade_Menu').removeClass('active');
					$('#Settings_Container').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#Add_MAC').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    clearInterval(get_all);
                });

                $('#Controls_Main_Menu').click(function () {
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Systems_Menu').removeClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#Controls_Main_Menu').addClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');
                    
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Systems_Menu').css('display', 'none');
                    $('.Design_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'block');
                    $('.MandV_Sub_Menu').css('display', 'none');
                    $('.Wifi_Sub_Menu').css('display', 'none');
                    $('.Application_Sub_Menu').css('display', 'none');
                    
                    $('.BottomMenu_1').slideUp('slow');
                    $('.BottomMenu_2').slideUp('slow');
                    $('.BottomMenuSystems').slideUp('slow');

                    $('#Controls_Main_Menu').addClass('active');
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');

                    $('#SystemNodeSetup_Container').css('display', 'none');
                    $('#bd-wrapper').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#ProjectTree_Container').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');

                    $('#showProjectSetup').removeClass('active');

                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'none');

                    $('#Controls_Container').css('display', 'block');
                    $.get("<?php echo URL ?>ajax_pages/controls_list.php",
                            {
                                id: 1
                            },
                    function (data, status) {
                        $('#Controls_Container').html(data);
                    });
                    
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');
                    $('#Firmware_upgrade_Menu').removeClass('active');
					$('#Settings_Container').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Add_router_Menu').removeClass('active');
					$('#addRouter_main_div').css('display', 'none');
                    $('#Router_Container').css('display', 'none');
                    $('#Wifi_Sub_Menu').css('display', 'none');
                    $('#Add_MAC').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
					$('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    clearInterval(get_all);
					
                });

                $('#MandV_Main_Menu').click(function () {
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Systems_Menu').removeClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#MandV_Main_Menu').addClass('active');
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');
                    
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Systems_Menu').css('display', 'none');
                    $('.Design_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('.MandV_Sub_Menu').css('display', 'block');
                    $('.Wifi_Sub_Menu').css('display', 'none');
                    $('.Application_Sub_Menu').css('display', 'none');
                    
                    $('.BottomMenu_1').slideUp('slow');
                    $('.BottomMenu_2').slideUp('slow');
                    $('.BottomMenuSystems').slideUp('slow');

                    $('#MandV_Main_Menu').addClass('active');
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#Application_Main_Menu').removeClass('active');

                    $('#SystemNodeSetup_Container').css('display', 'none');
                    $('#bd-wrapper').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#ProjectTree_Container').css('display', 'none');
                    $('#MandVClientList').css('display', 'block');
                    $('#showProjectSetup').removeClass('active');
                    $('#Project_Sub_Menu').css('display', 'none');

                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#Controls_Container').css('display', 'none');
                    
                    
                    $('#Wifi_Main_Menu').removeClass('active');
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');
                    $('#Firmware_upgrade_Menu').removeClass('active');
					$('#Settings_Container').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Add_router_Menu').removeClass('active');
					$('#addRouter_main_div').css('display', 'none');
                    $('#Router_Container').css('display', 'none');
                    $('#Add_MAC').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    clearInterval(get_all);

                });
                
                $('#Wifi_Main_Menu').click(function () { 
                    $('#Projects_Main_Menu').removeClass('active');
                    $('#Systems_Menu').removeClass('active');
                    $('#Design_Menu').removeClass('active');
                    $('#Controls_Main_Menu').removeClass('active');
                    $('#MandV_Main_Menu').removeClass('active');
                    $('#Wifi_Main_Menu').addClass('active');
                     $('#MandV_Main_Menu').removeClass('active');
                     $('#MandVClientList').css('display', 'none');
                     $('.MandV_Sub_Menu').css('display', 'none');
                    
                    $('.Project_Sub_Menu').css('display', 'none');
                    $('.Systems_Menu').css('display', 'none');
                    $('.Design_Menu').css('display', 'none');
                    $('.Control_Sub_Menu').css('display', 'none');

                    $('.MandV_Sub_Menu').css('display', 'none');
                    $('.Wifi_Sub_Menu').css('display', 'block');
                    $('.Application_Sub_Menu').css('display', 'none');
                    
                    $('.BottomMenu_1').slideUp('slow');
                    $('.BottomMenuSystems').slideUp('slow');
                    
                    $('#MandVClientList').css('display', 'none');
                    $('.MandV_Sub_Menu').css('display', 'none');
                    
					$('#SystemNodeSetup_Container').css('display', 'none');
                    $('#bd-wrapper').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#ProjectTree_Container').css('display', 'none');
                    $('#MandVClientList').css('display', 'block');
                    $('#showProjectSetup').removeClass('active');
                    $('#Project_Sub_Menu').css('display', 'none');

                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'none');
                    $('#Controls_Container').css('display', 'none');
                    $('.BottomMenu_1').css('display','none');
                    
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    
					$('#addRouter_main_div').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('#Add_MAC').css('display', 'block');
                    $('#Add_router_Menu').trigger('click');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
					
					
				});
				
                
                // Top Menu Level 3
                $('#showMasterSystems').click(function () {
                    $('#showMasterSystems').addClass('active');
                    $('#showMasterEquipments').removeClass('active');
                    $('#showNodeManagement').removeClass('active');
                    $('#showControlWorkspace').removeClass('active');
                    $('.showControlWorkspace').hide();
                    $('.showMasterSystems').show();
                    $('.showMasterEquipments').hide();
                    $('.showNodeManagement').hide();
                    
                    $('.BottomMenuSystems').slideDown('slow');
                    $('#ProjectTree_Container').css('display', 'none');
                    
                    $('#showSystemManagement').trigger('click');
                });
                
                $('#showMasterEquipments').click(function () {
                    $('#showMasterSystems').removeClass('active');
                    $('#showMasterEquipments').addClass('active');
                    $('#showNodeManagement').removeClass('active');
                    $('#showControlWorkspace').removeClass('active');
                    $('.showControlWorkspace').hide();
                    $('.showMasterSystems').hide();
                    $('.showMasterEquipments').show();
                    $('.showNodeManagement').hide();
                    $('.showControlWorkspace').hide();
                    $('.BottomMenuSystems').slideDown('slow');
                    $('#ProjectTree_Container').css('display', 'none');
                    
                    $('#showEquipmentManagement').trigger('click');
                });
                
                $('#showNodeManagement').click(function () {
                    $('#showMasterSystems').removeClass('active');
                    $('#showMasterEquipments').removeClass('active');
                    $('#showNodeManagement').addClass('active');
                    $('#showControlWorkspace').removeClass('active');
                    $('.showControlWorkspace').hide();
                    $('.showMasterSystems').hide();
                    $('.showMasterEquipments').hide();
                    $('.showNodeManagement').show();
                    
                    $('.BottomMenuSystems').slideDown('slow');
                    $('#ProjectTree_Container').css('display', 'none');
                    
                    $('#showEquipmentNodes').trigger('click');
                });
                
                $('#showControlWorkspace').click(function(){
                    $('#showMasterSystems').removeClass('active');
                    $('#showMasterEquipments').removeClass('active');
                    $('#showNodeManagement').removeClass('active');
                    $('#showControlWorkspace').addClass('active');
                    $('.showMasterSystems').hide();
                    $('.showMasterEquipments').hide();
                    $('.showNodeManagement').hide();
                    $('.showControlWorkspace').show();
                    
                    $('.BottomMenuSystems').slideDown('slow');
                    $('#ProjectTree_Container').css('display', 'none');
                    $('#Controls_Container').html("");
                    $('#showBuilding').trigger('click');
                    
                });
                //  Top Menu in level 4
                $('#showSystemManagement').click(function () {
                    $('#showSystemManagement').css('border', '1px solid #ffffff');
                    $('#showSearchSystems').css('border', 'none');
                    
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/fetch_systems.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                $('#showSearchSystems').click(function () {
                    $('#showSystemManagement').css('border', 'none');
                    $('#showSearchSystems').css('border', '1px solid #ffffff');
                    
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/search_systems.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                
                $('#showEquipmentManagement').click(function () {
                    $('#showEquipmentManagement').css('border', '1px solid #ffffff');
                    $('#showEquipmentGallary').css('border', 'none');
                    $('#showSearchManagement').css('border', 'none');
                    
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/fetch_equipments.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                $('#showEquipmentGallary').click(function () {
                    $('#showEquipmentManagement').css('border', 'none');
                    $('#showEquipmentGallary').css('border', '1px solid #ffffff');
                    $('#showSearchManagement').css('border', 'none');
                    
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/fetch_gallery.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                $('#showSearchManagement').click(function () {
                    $('#showEquipmentManagement').css('border', 'none');
                    $('#showEquipmentGallary').css('border', 'none');
                    $('#showSearchManagement').css('border', '1px solid #ffffff');
                    
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/search_equipments.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                
                $('#showEquipmentNodes').click(function () {
                    $('#showEquipmentNodes').css('border', '1px solid #ffffff');
                    $('#showCpeNodeLink').css('border', 'none');
                    $('#showAssignNode').css('border', 'none');
                    $('#showNodeActivity').css('border', 'none');
                    $('#ProjectTree_ContainerActivity').hide();
                    $('#ProjectTree_Container').hide();
                    $('#Configure_Building').hide();
                    
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/fetch_equipment_nodes.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                $('#showCpeNodeLink').click(function () {
                    $('#showEquipmentNodes').css('border', 'none');
                    $('#showCpeNodeLink').css('border', '1px solid #ffffff');
                    $('#showAssignNode').css('border', 'none');
                    $('#showNodeActivity').css('border', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('#ProjectTree_Container').hide();
                    $('#ProjectTree_ContainerActivity').hide();
                    $('#Configure_Building').hide();
                    $('#Controls_Container').show();
                    $('#Controls_Container').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/fetch_cpe_node_link.php", {},
						function (data, status) {
							$('#Controls_Container').html(data);
                    });
                });
                $('#showAssignNode').click(function () {
                    $('#showEquipmentNodes').css('border', 'none');
                    $('#showCpeNodeLink').css('border', 'none');
                    $('#showAssignNode').css('border', '1px solid #ffffff');
                    $('#showNodeActivity').css('border', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('#ProjectTree_ContainerActivity').hide();
                    $('#Configure_Building').hide();
                    $('#Controls_Container').hide();
                    $('#showTree').trigger('click');
                });
                $('#showNodeActivity').click(function () {
                    $('#showEquipmentNodes').css('border', 'none');
                    $('#showCpeNodeLink').css('border', 'none');
                    $('#showAssignNode').css('border', 'none');
                    $('#showNodeActivity').css('border', '1px solid #ffffff');
                    $('#MandVClientListForCost').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('#ProjectTree_Container').hide();
                    $('#ProjectTree_ContainerActivity').show();
                    $('#Configure_Building').hide();
                    $('#ddlClientListActivity').val('0').trigger("change");
                    $('#Controls_Container').hide();
                });
                 
                $('#showBuilding').click(function(){
                    $('#showBuilding').css('border', '1px solid #ffffff');
                    $('#showBuildingSystem').css('border', 'none');
                    $('#showSystemManage').css('border', 'none');
                    $('#showFloorPlans').css('border', 'none');
                    $('#Controls_Container').show();
                    $('#Configure_Building').show();
                    $('#Configure_Building_images').hide();
                    $('#Configure_System_Manage').hide();
                    $('#ConfigureBuilding').val(0);
                    $('#Controls_Container').html(""); 
                });
                $('#showBuildingSystem').click(function(){
                    $('#showBuilding').css('border', 'none');
                    $('#showBuildingSystem').css('border', '1px solid #ffffff');
                    $('#showSystemManage').css('border', 'none');
                    $('#showFloorPlans').css('border', 'none');
                    $('#Controls_Container').show();
                    $('#Configure_Building').hide();
                    $('#Configure_Building_images').show();
                    $('#Configure_System_Manage').hide();
                    $('#ConfigureBuildingImage').val(0);
                    $('#Controls_Container').html(""); 
                });
                $('#showSystemManage').click(function(){
                    $('#showBuilding').css('border', 'none');
                    $('#showBuildingSystem').css('border', 'none');
                    $('#showSystemManage').css('border', '1px solid #ffffff');
                    $('#showFloorPlans').css('border', 'none');
                    $('#Controls_Container').show();
                    $('#Configure_Building').hide();
                    $('#Configure_Building_images').hide();
                    $('#Configure_System_Manage').show();
                    $('#ConfigureSystemManage').val(0);
                    $('#Controls_Container').html(""); 
                });
                $('#showFloorPlans').click(function(){
                    // Need to do 
                });
                
                // //
                
                $('#Add_MAC').click(function () {
					if(addmac == 0){ 
						addmac = 1;
						$('#Add_MAC').html('-ADD MAC');
						$('#router_tree_2').css('display', 'none');
						$('#router_tree_1').css('display', 'none');
						$('#Firmware_upgrade_Menu').removeClass('active');
						$('#Router_Details_Menu').removeClass('active');
						$('#file_upload_main').css('display', 'none');
						$('#router_details').css('display', 'none');
						$('#router_tree_3').css('display', 'none');
						$('#addRouter_main_div').css('display', 'block');
						$('#MACid_div').css('display', 'block');
						$.get("<?php echo URL ?>ajax_pages/get_all_routers.php",
						{
						},
						function (data, status) {
							$('#table_div').html(
                                data
							);
						});
						get_all = setInterval(function(){
						$.get("<?php echo URL ?>ajax_pages/get_all_routers.php",
						{
						},
						function (data, status) {
							$('#table_div').html(
                                data
							);
						})}, 6000)
					}
					else{
						addmac = 0;
						
						$('#MACid_div').css('display', 'none');
                        $('#addRouter_main_div').css('display', 'none');
						$('#Add_MAC').html('+ADD MAC');
						$('#mac_id_text').val('');
						$('#mac_name_text').val('');
						$('#router_id').val('');
						$('#mac_save').val('Add');
						
					}
				});
                $('#Add_router_Menu').click(function () {
					addmac = 0;
					$('#Add_MAC').css('display', 'block');
					$('#Add_MAC').html('+ADD MAC');
					$('#mac_id_text').val('');
					$('#mac_name_text').val('');
					$('#router_id').val('');
					$('#mac_save').val('Add');
					$('#Basic_Settings_Menu').removeClass('active');
					$('#Firmware_upgrade_Menu').removeClass('active');
					$('#Settings_Container').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#router_tree_1').css('display', 'block');
					$('#Add_router_Menu').addClass('active');
					$('#Router_Container').css('display', 'block');
					
					$('#addRouter_main_div').css('display', 'none');
                    $('#MACid_div').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
					$('#router_tree_3').css('display', 'none');
					$('#Project_Sub_Menu').css('display', 'none');
					clearInterval(get_all);
					
				});
				$('#rtrClientList').change(function () {
					check = 1;
					addmac = 0;
					$('#Add_MAC').css('display', 'block');
					$('#Add_MAC').html('+ADD MAC');
					$('#mac_id_text').val('');
					$('#mac_name_text').val('');
					$('#router_id').val('');
					$('#mac_save').val('Add');
					$('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    var id = $('#rtrClientList').val();
                    $('#router_tree_1').css('display', 'block');
                    $('#router_tree_1').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_router.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#router_tree_1').html(
                                data
                                );
                    });
                    
                });
                
                			
				$('#Basic_Settings_Menu').click(function () {
					addmac = 0;
					$('#Add_MAC').css('display', 'block');
					$('#Add_MAC').html('+ADD MAC');
					$('#mac_id_text').val('');
					$('#mac_name_text').val('');
					$('#router_id').val('');
					$('#mac_save').val('Add');
					$('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#router_tree_2').css('display', 'block');
                    $('#Basic_Settings_Menu').addClass('active');
                    $('#Settings_Container').css('display', 'block');               
                    $('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
					$('#router_tree_3').css('display', 'none');
					$('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    $('#Project_Sub_Menu').css('display', 'none');
                    clearInterval(get_all);
                });
                $('#wifiClientList').change(function () {
					addmac = 0;
					$('#Add_MAC').css('display', 'block');
					$('#Add_MAC').html('+ADD MAC');
					$('#mac_id_text').val('');
					$('#mac_name_text').val('');
					$('#router_id').val('');
					$('#mac_save').val('Add');
					$('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
					$('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
					$('#router_tree_2').css('display', 'block');
                    var id = $('#wifiClientList').val();
                    $('#router_tree_2').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_wifi.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#router_tree_2').html(data);
                    });
                });
                
                 
                $('#ConfigureBuilding').change(function(){
                    var id = $("#ConfigureBuilding").val();
                    $.post("<?= URL?>ajax_pages/system_workspace_building.php",
                        {
                            clientid:id,
                        },
                        function(data,status){
                          $('#Controls_Container').html(""); 
                          $('#Controls_Container').html(data); 
                        });
                });                
                $('#ConfigureBuildingImage').change(function(){
                    var id = $("#ConfigureBuildingImage").val();
                    $.post("<?= URL?>ajax_pages/building_systems_images.php",
                        {
                            clientid:id,
                        },
                        function(data,status){
                          $('#Controls_Container').html(); 
                          $('#Controls_Container').html(data); 
                        });
                });
                $('#ConfigureSystemManage').change(function(){
                    var id = $("#ConfigureSystemManage").val();
                    $.post("<?= URL?>ajax_pages/systems_manage.php",
                        {
                            clientid:id,
                        },
                        function(data,status){
                          $('#Controls_Container').html(); 
                          $('#Controls_Container').html(data); 
                        });
                });
                /***********frimware upgrade*********************/
                $('#Firmware_upgrade_Menu').click(function () {
					clearInterval(get_all);
					$('#Add_MAC').css('display', 'none');
					$('#Router_Container').css('display', 'none');
					$('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');   
                    $('#Settings_Container').css('display', 'none');               
                    $('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
					$('#router_tree_3').css('display', 'none');
					$('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    $('#Project_Sub_Menu').css('display', 'none');
                    
                    $('#Firmware_upgrade_Menu').addClass('active');
                    $('#file_upload_main').css('display', 'block');
					$.get("<?php echo URL ?>ajax_pages/last_updated_date.php",
						{
						},
						function (data, status) {
							$('#lastUpdate').html(
								'Last Update On - '+data
							);
						});
                   
					$.get("<?php echo URL ?>ajax_pages/list_for_upgrade.php",
						{
						},
						function (data, status) {
							$('#select_mac_table').html(
                                data
							);
						});
                   
                });
                
               
				/*******************file upload part******************/
				var options = { 
					target:   '#output',   // target element(s) to be updated with server response 
					beforeSubmit:  beforeSubmit,  // pre-submit callback 
					success:       afterSuccess,  // post-submit callback 
					uploadProgress: OnProgress, //upload progress callback 
					resetForm: true        // reset the form after successful submit 
				}; 
				
				$('#MyUploadForm').submit(function() { 
					$(this).ajaxSubmit(options);  			
					// always return false to prevent standard browser submit and page navigation 
					return false; 
				}); 
		
				//function after succesful file upload (when server response)
				function afterSuccess()
				{
					$('#submit-btn').show(); //hide submit button
					$('#loading-img').hide(); //hide submit button
					$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar
					$.get("<?php echo URL ?>ajax_pages/last_updated_date.php",
						{
						},
						function (data, status) {
							$('#lastUpdate').html(
								'Last Update On : '+data
							);
						});
				}
	
				//function to check file size before uploading.
				function beforeSubmit(){
					//check whether browser fully supports all File API
					if (window.File && window.FileReader && window.FileList && window.Blob)
					{
		
						if( !$('#FileInput').val()) //check empty input filed
						{
							$("#output").html("No file selected?");
							return false
						}
		
						var fsize = $('#FileInput')[0].files[0].size; //get file size
						var ftype = $('#FileInput')[0].files[0].type; // get file type
		

						//allow file types 
					/*	switch(ftype)
						{
							case 'image/png': 
							case 'image/gif': 
							case 'image/jpeg': 
							case 'image/pjpeg':
							case 'text/plain':
							case 'text/html':
							case 'application/x-zip-compressed':
							case 'application/pdf':
							case 'application/msword':
							case 'application/vnd.ms-excel':
							case 'video/mp4':
							break;
							default:
								$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
								return false
						}
					*/
						//Allowed file size is less than 5 MB (1048576)
						if(fsize>10485760) 
						{
							$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
							return false
						}
								
						$('#submit-btn').hide(); //hide submit button
						$('#loading-img').show(); //hide submit button
						$("#output").html("");  
					}
					else
					{
						//Output error to older unsupported browsers that doesn't support HTML5 File API
						$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
						return false;
					}
				}

				//progress bar function
				function OnProgress(event, position, total, percentComplete)
				{
					//Progress bar
					$('#progressbox').show();
					$('#progressbar').width(percentComplete + '%') //update progressbar percent complete
					$('#statustxt').html(percentComplete + '%'); //update status text
					if(percentComplete>50)
						{
							$('#statustxt').css('color','#000'); //change status text to white after 50%
						}
				}

				//function to format bites bit.ly/19yoIPO
				function bytesToSize(bytes) {
				   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				   if (bytes == 0) return '0 Bytes';
				   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
				}
				/*****************************************************/
				
                /************router details****************************/
                $('#Router_Details_Menu').click(function () {
					clearInterval(get_all);
					$('#Add_MAC').css('display', 'none');
					$('#Router_Container').css('display', 'none');
					$('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');   
                    $('#Settings_Container').css('display', 'none');               
                    $('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    $('#Project_Sub_Menu').css('display', 'none');
                    
                    $('#Router_Details_Menu').addClass('active');
                    $('#router_details').css('display', 'block');
                    $('#router_tree_3').css('display', 'block');
                    
				});
				$('#detailsClientList').change(function () {
                    $('#router_tree_3').css('display', 'block');
                    var id = $('#detailsClientList').val();
                    $('#router_tree_3').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_details.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#router_tree_3').html(
                                data
                                );
                    });
                });
                /******************************************************/
               
                /*****************Firewall_Settings_Menu***************/
				$('#Firewall_Settings_Menu').click(function(){
					clearInterval(get_all);
					$('#Add_MAC').css('display', 'none');
					$('#Router_Container').css('display', 'none');
					$('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');   
                    $('#Settings_Container').css('display', 'none');               
                    $('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Mesh_config_Menu').removeClass('active');
                    $('#mesh_config').css('display', 'none');
                    $('#mesh_tree').css('display', 'none');
                    $('#Project_Sub_Menu').css('display', 'none');
                    
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                     
                    $('#Firewall_Settings_Menu').addClass('active');
                    $('#firewall_details').css('display', 'block');
                    $('#firewall_tree').css('display', 'block');
					
				});
				$('#firewallClientList').change(function () {
                    $('#firewall_tree').css('display', 'block');
                    var id = $('#firewallClientList').val();
                    $('#firewall_tree').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_firewall.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#firewall_tree').html(
                                data
                                );
                    });
                });
               
               /*******************************************************/ 
               /*****************Mesh config Menu***************/
				$('#Mesh_config_Menu').click(function(){
					clearInterval(get_all);
					$('#Add_MAC').css('display', 'none');
					$('#Router_Container').css('display', 'none');
					$('#Add_router_Menu').removeClass('active');
                    $('#Router_Container').css('display', 'none');
                    $('#router_tree_1').css('display', 'none');
                    $('#Basic_Settings_Menu').removeClass('active');   
                    $('#Settings_Container').css('display', 'none');               
                    $('#MACid_div').css('display', 'none');
					$('#addRouter_main_div').css('display', 'none');
                    $('#router_tree_2').css('display', 'none');
                    $('#Firmware_upgrade_Menu').removeClass('active');
                    $('#file_upload_main').css('display', 'none');
                    $('#Project_Sub_Menu').css('display', 'none');
                    $('#Router_Details_Menu').removeClass('active');
                    $('#router_details').css('display', 'none');
                    $('#router_tree_3').css('display', 'none');
                    $('#Firewall_Settings_Menu').removeClass('active');
                    $('#firewall_details').css('display', 'none');
                    $('#firewall_tree').css('display', 'none');
                     
                    $('#Mesh_config_Menu').addClass('active');
                    $('#mesh_config').css('display', 'block');
                    $('#mesh_tree').css('display', 'block');
					
				});
				$('#meshClientList').change(function () {
                    $('#mesh_tree').css('display', 'block');
                    var id = $('#meshClientList').val();
                    $('#mesh_tree').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_mesh.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#mesh_tree').html(
                                data
                                );
                    });
                });
                
               
               
               /*******************************************************/ 
					$("#copyBtn").click(function(){
						var selected = $("#selectBox").val();
						$("#output").append("\n * " + selected);
					});
					               
                $('#showProjectSetup').click(function () {
                    $('#Project_Sub_Menu').css('display', 'block');
                    $('#ProjectTree_Container').css('display', 'none');
                    $('#bd-wrapper').css('display', 'none');
                    $('#ProjectSetup_Container').css('display', 'block');
                    $('#showProjectSetup').addClass('active');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                    $('#SystemNodes_Container').css('display', 'none');
                });
                                
                $('#showSystemNodes').click(function () {
                    $('.BottomMenu_1').slideUp('slow');
                    $('#bd-wrapper').slideUp('slow');
                    
                    $('#showSystemNodes').addClass('active');
                    $('#showTree').removeClass('active');
                    $('#showNewControl').removeClass('active');
                    $('#showNewWidget').removeClass('active');
                    $('#showControlOperation').removeClass('active');
                    $('#showApplyControl').removeClass('active');

                    $('#ProjectTree_Container').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('#ProjectTree').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');

                    $('#SystemNodes_Container').html('Loading System...');
                    $('#SystemNodes_Container').css('display', 'block');

                    $.get("<?php echo URL ?>ajax_pages/fetch_system_for_engineer.php",
                            {
                                id: 0
                            },
                    function (data, status) {
                        $('#SystemNodes_Container').html(data);

                    });
                });
                
                $('#showTree').click(function () {
                    $('.BottomMenu_1').slideUp('slow');
                    $('#bd-wrapper').slideUp('slow');
                    
                    $('#showTree').addClass('active');
                    $('#showNewControl').removeClass('active');
                    $('#showNewWidget').removeClass('active');
                    $('#showControlOperation').removeClass('active');
                    $('#showApplyControl').removeClass('active');
                    
                    $('#MandVClientList').css('display', 'none');
                    $('#ProjectTree_Container').css('display', 'block');
                    $('#ProjectTree').css('display', 'block');
                    $('#ProjectTree_1').css('display', 'block');
                    $('#SystemNodes_Container').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');

                });
                
                $('#showNewControl').click(function () {

                    $('.BottomMenu_1').slideDown('slow');
                    $('#bd-wrapper').slideDown('slow');

                    $('#showNewControl').addClass('active');
                    $('#showTree').removeClass('active');
                    $('#showNewWidget').removeClass('active');
                    $('#showControlOperation').removeClass('active');
                    $('#showApplyControl').removeClass('active');
                    
                    $('#ProjectTree').css('display', 'block');
                    $('#ProjectTree_Container').css('display', 'none');
                    $('#MandVClientList').css('display', 'none');
                    $('#ProjectTree_1').css('display', 'none');
                    $('#SystemNodes_Container').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');

                });

                
                $('#MandV_Sub_menu_1').click(function () {
                    $('#MandV_Sub_menu_1').addClass('active');
                    $('#MandV_Sub_menu_2').removeClass('active');
                    
                    $('#MandVClientList').css('display', 'block');
                    $('#MandVClientListForCost').css('display', 'none');
                });
                
                $('#MandV_Sub_menu_2').click(function () {
                    $('#MandV_Sub_menu_2').addClass('active');
                    $('#MandV_Sub_menu_1').removeClass('active');
                    
                    $('#MandVClientListForCost').css('display', 'block');
                    $('#MandVClientList').css('display', 'none');
                });
                

                $('#mvClientList').change(function () {
                    $('#mvTree').css("display", "block");
                    var id = $('#mvClientList').val();
                    $('#mvTree').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_mv.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#mvTree').html(
                                data
                                );
                    });
                });
                
                $('#mvClientListForCost').change(function () {
                    $('#mvCostTree').css("display", "block");
                    var id = $('#mvClientListForCost').val();
                    $('#mvCostTree').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_mv_cost.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#mvCostTree').html(
                                data
                                );
                    });
                });
                
                
                
                /////==============krishan==============/////

                $('#Picture_Library_Menu').click(function () {
                    $('#Add_Text_Div').css('display', 'none');
                    $('#Add_Shapes_Div').css('display', 'none');
                    $('#Add_Control_Div').css('display', 'none');
                    $('#Picture_Library_Category_List').css('display', 'block');
                    $('#dynamic_image').css('display', 'block');
                    $('#Picture_Library_Menu').css('background-color', '#EFEFEF');
                    $('#Text_Menu').css('background', 'none');
                    $('#Shapes_Menu').css('background', 'none');
                    $('#Controls_Menu').css('background', 'none');
                    $('#Add_Template_Div').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                });

                $('#Text_Menu').click(function () {
                    $('#Add_Shapes_Div').css('display', 'none');
                    $('#Add_Control_Div').css('display', 'none');
                    $('#Picture_Library_Category_List').css('display', 'none');
                    $('#dynamic_image').css('display', 'none');
                    $('#Add_Text_Div').css('display', 'block');
                    $('#Text_Menu').css('background-color', '#EFEFEF');
                    $('#Picture_Library_Menu').css('background', 'none');
                    $('#Shapes_Menu').css('background', 'none');
                    $('#Controls_Menu').css('background', 'none');
                    $('#Add_Template_Div').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                });

                $('#Shapes_Menu').click(function () {
                    $('#Add_Text_Div').css('display', 'none');
                    $('#Add_Control_Div').css('display', 'none');
                    $('#Picture_Library_Category_List').css('display', 'none');
                    $('#dynamic_image').css('display', 'none');
                    $('#Add_Shapes_Div').css('display', 'block');
                    $('#Shapes_Menu').css('background-color', '#EFEFEF');
                    $('#Text_Menu').css('background', 'none');
                    $('#Controls_Menu').css('background', 'none');
                    $('#Picture_Library_Menu').css('background', 'none');
                    $('#Add_Template_Div').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                });

                $('#Widgets_Menu').click(function () {
                    $('#Add_Text_Div').css('display', 'none');
                    $('#Picture_Library_Category_List').css('display', 'none');
                    $('#dynamic_image').css('display', 'none');
                    $('#Add_Shapes_Div').css('display', 'none');
                    $('#Add_Control_Div').css('display', 'block');
                    $('#Controls_Menu').css('background-color', '#EFEFEF');
                    $('#Text_Menu').css('background', 'none');
                    $('#Shapes_Menu').css('background', 'none');
                    $('#Picture_Library_Menu').css('background', 'none');
                    $('#Add_Template_Div').css('display', 'none');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                });

                $('#Template_Menu').click(function () {
                    $('#Add_Text_Div').css('display', 'none');
                    $('#Picture_Library_Category_List').css('display', 'none');
                    $('#dynamic_image').css('display', 'none');
                    $('#Add_Shapes_Div').css('display', 'none');
                    $('#Controls_Menu').css('background-color', '#EFEFEF');
                    $('#Text_Menu').css('background', 'none');
                    $('#Shapes_Menu').css('background', 'none');
                    $('#Picture_Library_Menu').css('background', 'none');
                    $('#Add_Control_Div').css('display', 'none');
                    $('#Add_Template_Div').css('display', 'block');
                    $('#SystemNodeSetup_Container_Div').css('display', 'none');
                });

                iWidgetOpen = 0;

                $('#ddlSystemForWorkspace').change(function () {
                    if (!$('#ddlBuildingProjectForController').val())
                    {
                        alert("Please select a Project");
                        return;
                    }

                    $('#WidgetDetailsByCategory').slideUp('fast');
                    $.get("<?php echo URL ?>ajax_pages/project_widget_list.php",
                            {
                                id: $('#ddlSystemForWorkspace').val(),
                                project_id: $('#ddlBuildingProjectForController').val(),
                            },
                            function (data, status) {
                                $('#WidgetDetailsByCategory').html(data);
                                $('#WidgetDetailsByCategory').slideDown('slow');
                                iWidgetOpen = 1;
                                $('#Widget_Box_Click').html('- WIDGETS');
                            });

                });

                $('#Widget_Box_Click').click(function () {
                    if (iWidgetOpen == 0)
                    {
                        $('#WidgetDetailsByCategory').slideDown('slow');
                        iWidgetOpen = 1;
                        $('#Widget_Box_Click').html('- WIDGETS');
                    }
                    else
                    {
                        $('#WidgetDetailsByCategory').slideUp('slow');
                        iWidgetOpen = 0;
                        $('#Widget_Box_Click').html('+ WIDGETS');
                    }
                });

                $('#showTree').trigger('click');

                
                $('#ddlClientList').change(function () {
                    var id = $('#ddlClientList').val();
                    $('#ProjectTree_1').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#ProjectTree_1').html(
                                data
                                );
                    });
                });
                
                 $('#ddlClientListActivity').change(function () {
                    var id = $('#ddlClientListActivity').val();
                    $('#ProjectTreeActivity_1').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_Activity.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#ProjectTreeActivity_1').html(
                                data
                                );
                         $('#ProjectTreeActivity_1').show();
                    });
                });

                $('#ddlClientListForController').change(function () {
                    var id = $('#ddlClientListForController').val();
                    $('#ddlClientSiteForController').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_dropdown.php",
                            {
                                id: id,
                            },
                            function (data, status) {
                                $('#ddlClientSiteForController').html(
                                        data
                                        );
                            });
                });

                $('#ddlClientListForProject').change(function () {
                    var id = $('#ddlClientListForProject').val();
                    $('#SiteForClientProject').html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_project.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#SiteForClientProject').html(
                                data
                                );
                    });
                });

                $('#Open_Workspace').click(function () {

                    if (document.getElementById('ddlBuildingProjectForController').value != '')
                    {
                        $.get("<?php echo URL ?>ajax_pages/show_workspace.php",
                                {
                                    client_id: document.getElementById('ddlClientListForController').value,
                                    project_id: document.getElementById('ddlBuildingProjectForController').value
                                },
                        function (data, status) {
                            $('#txtControlName').css('display', 'none');
                            $('#ExistingWorkspace_Container').html(data);
                            $('#ExistingWorkspace_Container').css('display', 'block');
                            $('#Open_Workspace_Button').css('display', 'none');
                            $('#Close_Workspace_Button').css('display', 'block');
                        });
                    }

                });

                $('#Close_Workspace').click(function () {
                    /*$('#txtControlName').val('');
                     $('#EditWorkspace_ID').val('');
                     $('#ExistingWorkspace_Container').html('');
                     $('#Open_Workspace_Button').css('display','block');
                     $('#Close_Workspace_Button').css('display','none');	*/
                });


                <?php
                if (Globals::Get('type') == 'system') {
                    ?>
                        $('#showSystemNodes').trigger("click");
                    <?php
                }
                ?>
            });
			
/************************for wifi system******************************/		
			/*********display routers for clicked site************/
			function ShowAddMAC(siteID){ 
			  if($('#'+siteID+'').is(':visible')){
					$('#'+siteID+'').css('display', 'none');
				}
				else{
					$('#'+siteID+'').css('display', 'block');
				}
			}
			/*********display ssid & gateway textboxes************/
			function ShowTextFields(siteID){ 
				if($('#'+siteID+'').is(':visible')){
					$('#'+siteID+'').css('display', 'none');
				}
				else{
					$('#'+siteID+'').css('display', 'block');
				}
			}
			function showMACfield(siteID){ 
				if($('#'+siteID+'_div').is(':visible')){
					$('#'+siteID+'_div').css('display', 'none');
					$( '#'+siteID+'_sh' ).html('+');
				}
				else{
					$('#'+siteID+'_div').css('display', 'block');
					$( '#'+siteID+'_sh' ).html('-');
				}
			}
			/**********display static ip details*********/
			function selectProtocol(router_id){
				var value = $('#'+router_id+'_protocol').val();
				if(value == '1')
					$('#'+router_id+'_static_ip').css('display','block');
				else
					$('#'+router_id+'_static_ip').css('display','none');
			}
			/******************************************/
			/*********display router details************/	
			function showRouterDetails(siteID){ 
				if($('#'+siteID+'').is(':visible')){
					$('#'+siteID+'').css('display', 'none');
				}
				else{
					$('#'+siteID+'').css('display', 'block');
				}
			}	
			function showMACdetails(siteID){ 
				if($('#'+siteID+'_div_d').is(':visible')){
					$('#'+siteID+'_div_d').css('display', 'none');
					$( '#'+siteID+'_det' ).html('+');
				}
				else{
					$('#'+siteID+'_div_d').css('display', 'block');
					$( '#'+siteID+'_det' ).html('-');
				}
			}
			/************to set the mac for site*********************/
			function set_MAC_id(siteID){
				var id = 'to_'+siteID;
				var idf = 'from_'+siteID;
				var i = 0;
				var arr = new Array();
				var arrf = new Array();
				$('#'+id+' option').each(function()
				{	
					arr[i] = $(this).val();
					i++;
				});	console.log("Arr"+arr);
				i=0;
				$('#'+idf+' option').each(function()
				{	
					arrf[i] = $(this).val();
					i++;
				});	console.log("ArrF"+arrf);
				$.get("<?php echo URL ?>ajax_pages/add_mac_to_site.php",
				{
					router_ids:arr,
					siteID:siteID,
					router_idsF:arrf,
				},
				function (data, status) {
					alert(data);
                 });
				check = 1;
				$('#MACid_div').css('display', 'none');
				$('#addRouter_main_div').css('display', 'none');
                var id = $('#rtrClientList').val();
                $('#router_tree_1').css('display', 'block');
                $('#router_tree_1').html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/show_site_for_router.php",
					{
						id: id
					},
					function (data, status) {
                        $('#router_tree_1').html(
                                data
                                );
                    });
                    $.get("<?php echo URL ?>ajax_pages/show_site_for_wifi.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#router_tree_2').html(
                                data
                                );
                    });
                  ShowAddMAC('r_'+siteID);
			}	
			/********************************************/
			
			/************select box*********************/
			function moveSelected(from, to) {
				$('#'+from+' option:selected').remove().appendTo('#'+to); 
			}
			/********************************************/
			/**********firewall settings div show*****/
			function ShowFirewallSettings(siteID){ 
				if($('#'+siteID+'').is(':visible')){
					$('#'+siteID+'').css('display', 'none');
				}
				else{
					$('#'+siteID+'').css('display', 'block');
				}
			}
			function showMAC_firewall(siteID){ 
				if($('#'+siteID+'_div_f').is(':visible')){
					$('#'+siteID+'_div_f').css('display', 'none');
					$( '#'+siteID+'_sh_f' ).html('+');
				}
				else{
					$('#'+siteID+'_div_f').css('display', 'block');
					$( '#'+siteID+'_sh_f' ).html('-');
				}
			}
			
			/************set ssid and gateway*********************/
			function setSSID(site_id,router_id,optn) {
				if(optn == '1'){
					var newValue = $("input[name="+router_id+"_ssid]").val();
					
					$("#ssidld_"+router_id).css("display", "block");
					$.get("<?php echo URL ?>ajax_pages/set_ssid.php",
                            {
                                value: newValue,
                                optn:optn,
                                router_id:router_id,
                            },
                    function (data) {
						alert("SSID changed");
						$("#ssidld_"+router_id).css("display", "none");
                        $("input[name="+router_id+"_ssid]").val(newValue);
                      if(optn == 2)
                        $("input[name="+router_id+"_gateway]").val(newValue);
               //  );
                });
				}
				if(optn == '2'){
					var gateway = '';
					var ipaddress = '';
					var ipnetmask = '';
					var protocol = $('#'+router_id+'_protocol').val();
					if(protocol == '1'){
						gateway = $("input[name="+router_id+"_gateway]").val();
						ipaddress = $("input[name="+router_id+"_ipaddress]").val();
						ipnetmask = $("input[name="+router_id+"_ipnetmask]").val();
					}
					$("#protold_"+router_id).css("display", "block");
					$.get("<?php echo URL ?>ajax_pages/set_ssid.php",
                            {
								protocol: protocol,
                                gateway: gateway,
                                ipaddress: ipaddress,
                                ipnetmask: ipnetmask,
                                optn:optn,
                                router_id:router_id,
                            },
                    function (data) {
						$("#protold_"+router_id).css("display", "none");
						alert("Protocol changed");
                        $("input[name="+router_id+"_gateway]").val(gateway);
                        $("input[name="+router_id+"_ipaddress]").val(ipaddress);
                        $("input[name="+router_id+"_ipnetmask]").val(ipnetmask);
               //  );
                });
				}
					
			}
			/********************************************/
			/***************save firewall*************/
			function saveFirewall(router_id,router_macid){
				var values = new Array();
				values[0]= $('#'+router_id+'_winput').val();
				values[1] = $('#'+router_id+'_woutput').val();
				values[2] = $('#'+router_id+'_wfwd').val();
				values[3] = $('#'+router_id+'_wmasq').prop('checked'); 
				values[4] = $('#'+router_id+'_wmss').prop('checked'); 
				if(values[3] == true) values[3]=1;else values[3] = 0;
				if(values[4] == true) values[4]=1;else values[4] = 0;
				values[5] = $('#'+router_id+'_linput').val();
				values[6] = $('#'+router_id+'_loutput').val();
				values[7] = $('#'+router_id+'_lfwd').val();
				values[8] = $('#'+router_id+'_lmasq').prop('checked');
				values[9] = $('#'+router_id+'_lmss').prop('checked'); 
				if(values[8] == true) values[8]=1;else values[8] = 0;
				if(values[9] == true) values[9]=1;else values[9] = 0;
				for(var i = 0;i<10;i++) if(values[i]==undefined) values[i] = '';
				$("#firewallld_"+router_id).css("display", "block");
				$.get("<?php echo URL ?>ajax_pages/save_firewall.php",
                            {
                                macid: router_macid,
                                values:values,
                            },
                    function (data, status) {
						$("#firewallld_"+router_id).css("display", "none");
                        alert("Firewall settings saved");
                    });	
			}
			/********************************************/
			/***************save mesh*************/
			function saveMesh(router_id,router_macid){
				var values = new Array();
				values[0]= $('#'+router_id+'_mode').val();
				values[1] = $('#'+router_id+'_essid').val();
				values[2] = $('#'+router_id+'_bssid').val(); 
				values[3] = $('#'+router_id+'_ip4a').val();
				values[4] = $('#'+router_id+'_ip4g').val();
				if(values[0] == '1'){
					values[5] = $('#'+router_id+'_lfwd').val(); 
				}
				for(var i = 0;i<10;i++) if(values[i]==undefined) values[i] = '';
				$("#meshld_"+router_id).css("display", "block");
				$.get("<?php echo URL ?>ajax_pages/save_mesh.php",
                            {
                                macid: router_macid,
                                values:values,
                            },
                    function (data, status) {
						$("#meshld_"+router_id).css("display", "none");
                        alert("Mesh Configuration settings saved");
                    });	
			}
			/*********mesh*******************/
			function showMACmesh(siteID){ 
				if($('#'+siteID+'_div_me').is(':visible')){
					$('#'+siteID+'_div_me').css('display', 'none');
					$( '#'+siteID+'_me' ).html('+');
				}
				else{
					$('#'+siteID+'_div_me').css('display', 'block');
					$( '#'+siteID+'_me' ).html('-');
				}
			}
			function modeCheck(id){
				var mod = $('#'+id+'_mode').val();
				if(mod == 1){
					$('#'+id+'_gate_div').css('display', 'none');
					$('#'+id+'_bssid_div').css('display', 'block');
				}
				else{
					$('#'+id+'_gate_div').css('display', 'block');
					$('#'+id+'_bssid_div').css('display', 'none');
				}
			}
			/**********display router table *************/
			function saveRouter(){
				var mac_id = $("input[name=mac_id]").val();
				var mac_name = $("input[name=mac_name]").val();	
				var router_id = '';
				var action = $('#mac_save').val();	
				if(action == 'Change')
					router_id = $('#router_id').val();	
				var RegExPattern1 = /^[0-9a-fA-F:]+$/;
				var RegExPattern2 = /^[0-9a-fA-F-]+$/;
 
				if (!((mac_id.match(RegExPattern1)) || (mac_id.match(RegExPattern2))) || mac_id.length != 17) 
				{
					alert("Invalid Media Access Control Address");
				}
				else
				{
					$.get("<?php echo URL ?>ajax_pages/add_mac.php",
								{
									mac_id: mac_id,
									mac_name:mac_name,
									action : action,
									router_id : router_id,
								},
						
						function (data, status) {
							alert(data);
							$("input[name=mac_id]").val('');
							$("input[name=mac_name]").val('');
							$('#mac_save').val('Add');
							$('#mac_id_text').prop("disabled", false);
						}
					);
					$.get("<?php echo URL ?>ajax_pages/get_all_routers.php",{},
						function (data, status) {
							$('#table_div').html(
								data
							);
						});
					var id = $('#rtrClientList').val();
					$.get("<?php echo URL ?>ajax_pages/show_site_for_router.php",
                            {
                                id: id
                            },
                    function (data, status) {
                        $('#router_tree_1').html(
                                data
                                );
                    });	
				}
			}
			/********************************************/
			
			/*****************delete router**************/
			function deleteRouter(mac_id){
				if (confirm("Are you sure to delete this device?")) {
					$.get("<?php echo URL ?>ajax_pages/delete_router.php",
					{
						macid: mac_id
					},
						function (data, status) {
						alert(data);
						$.get("<?php echo URL ?>ajax_pages/get_all_routers.php",{},
						function (data) {
							$('#table_div').html(data);
						});
					});
				}
				else
					return false;
			}
			/********************************************/
			
			/***************edit router details********************/
			function editRouter(router_id,mac_id,mac_name){
				$('#mac_id_text').val(mac_id);
				$('#mac_name_text').val(mac_name);
				$('#router_id').val(router_id);
				$('#mac_save').val('Change');
			}
			/********************************************/
			
			/*************reboot router *************/
			function reboot(mac_id){
				$.get("<?php echo URL ?>ajax_pages/reboot_router.php",{
						macid: mac_id,
					},
						function (data) {
							alert(data);
						});
			}
			/*****************************************/
						
			/***********file upgrade system*******************/
			function upgradefn(){
				var test = 0;
				var values = $('input:checkbox:checked.checkboxList').map(function () {
					return this.value;
				}).get();
				$("#upgradeld").css("display", "block");
				$.get("<?php echo URL ?>ajax_pages/upgrade_mac.php",
					{
						values: values
					},
					function (data, status) {
						$("#upgradeld").css("display", "none");
                        alert("Firmware Upgrade Initiated");
                        $("input[name='name[]']:checkbox").prop('checked',false);
                         $('#selectAll').prop('checked',false);
                    }
				);	
			}
			
			/********************************************/
			/*****************select all check boxes*****/
			function selectAllbox(){
				var ischecked= $('#selectAll').is(':checked');
				if(ischecked)
					$(".checkboxList").prop('checked', true);
				else
					$(".checkboxList").prop('checked', false);
			}
			/********************************************/
/*********************************************************************/

            function SystemOptions(strCatID)
            {
                $.get("<?php echo URL ?>ajax_pages/system_details.php",
                        {
                            id: strCatID
                        },
                function (data, status) {
                    data = data.split("~#~");
                    $('[name=ddlSystem]').val(data[0]);
                    $('#txtSystemName').val(data[1]);
                    $('#System_ID').val(data[2]);

                    $('#ddlUtilityClass').val(data[5]);
                    $('#ddlUtilityUOM').val(data[6]);
                    $('#ddlUnitComplexityLevel').val(data[9]);
                    if (data[7] == 1)
                    {
                        $('#chkExcludeCalculation').prop('checked', true);
                    }
                    else
                    {
                        $('#chkExcludeCalculation').prop('checked', false);
                    }
                    /*if(data[4]==1)
                     {
                     $('#chkHasWidget').prop('checked', true);
                     }
                     else
                     {
                     $('#chkHasWidget').prop('checked', false);
                     }*/

                    $('#btnSubmit').val('Update');
                    $('#btnDelete').val('Delete');

                    if (data[8] == 0)
                    {
                        $('#btnDelete').css('display', 'block');
                        $('#CannotDelete').css("display", 'none');
                    }
                    else
                    {
                        $('#btnDelete').css('display', 'none');
                        $('#CannotDelete').css("display", 'block');
                    }
                    $('#btnDelete').attr("disabled", false);
                    $('html, body').animate({scrollTop: $("#SystemNodes_Container").offset().top}, 200);
                    $('#txtSystemName').focus();

                    if (data[3] > 0)
                    {
                        $('#CannotDelete').html("There are " + data[3] + " images in Category. Cannot Delete Category");
                        $('#CannotDelete').css("display", 'block');
                        $('#btnDelete').css("display", 'none');
                    }

                    $('#btnDelete').click(function () {
                        if (!confirm("Are you sure you want to Delete " + data[1] + " ?"))
                        {

                        }
                        else
                        {
                            $.get("<?php echo URL ?>ajax_pages/system_delete.php",
                                    {
                                        id: data[2]
                                    },
                            function (data1, status1)
                            {
                                $('#showSystemNodes').trigger("click");
                            });

                        }
                    });

                });
            }

            function AddProjectSystem()
            {
                var SystemID = $('#ddlSystemForProject').val();
                var ProjectID = $('#txt_project_id').val();


                $.post("<?php echo URL ?>ajax_pages/show_project_details.php",
                        {
                            SystemID: SystemID,
                            ProjectID: ProjectID,
                        },
                        function (data, status) {
                            LoadProjectDetails(ProjectID);
                        });

            }

            function LoadImagemDetails(id)
            {
                $.get("<?php echo URL ?>ajax_pages/show_image.php",
                        {
                            id: id
                        },
                function (data, status) {
                    $('#dynamic_image').html(
                            data
                            );
                });
            }


            function ShowBuildingName(strSiteID)
            {
                
                $.get("<?php echo URL ?>ajax_pages/show_building.php",
                        {
                            id: strSiteID
                        },
                function (data, status) {
                    $('#' + strSiteID).html(
                            data
                            );
                   
                });
            }
             function ShowBuildingNameActivity(strSiteID)
            {
                
                $.get("<?php echo URL ?>ajax_pages/fetch_node_Activity_link.php",
                        {
                            id: strSiteID
                        },
                function (data, status) {
                    $('#' + strSiteID).html(
                            data
                            );
                   
                });
            }
            function ShowBuildingNameMV(strSiteID)
            {
                $.get("<?php echo URL ?>ajax_pages/show_building_mv.php",
                        {
                            id: strSiteID
                        },
                function (data, status) {
                    $('#' + strSiteID).html(
                            data
                            );
                });
            }
            
            function ShowBuildingNameMVCost(strSiteID)
            {
                $.get("<?php echo URL ?>ajax_pages/show_building_mv_cost.php",
                        {
                            id: strSiteID
                        },
                function (data, status) {
                    $('#' + strSiteID).html(
                            data
                            );
                });
            }


            function ShowBuildingNameForProject(strSiteID)
            {
                $.get("<?php echo URL ?>ajax_pages/show_building_for_project.php",
                        {
                            id: strSiteID
                        },
                function (data, status) {
                    $('#Project_' + strSiteID).html(
                            data
                            );
                });
            }

            function ShowRoomName(strBuildingID)
            {
                $('#building_' + strBuildingID).html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/show_room.php",
                        {
                            id: strBuildingID
                        },
                function (data, status) {
                    $('#building_' + strBuildingID).html(
                            data
                            );
                });
            }


            function ShowRoomNameForProject(strBuildingID)
            {
                var strPlusMinus = $('#Plus_Minus_Project_Building_' + strBuildingID).html();
                if (strPlusMinus == '+')
                {
                    $('#building_for_project_' + strBuildingID).html('Loading...');
                    $.get("<?php echo URL ?>ajax_pages/show_room_for_project.php",
                            {
                                id: strBuildingID
                            },
                    function (data, status) {
                        $('#building_for_project_' + strBuildingID).html(data);
                        $('#Plus_Minus_Project_Building_' + strBuildingID).html('-');
                        $('#building_for_project_' + strBuildingID).slideDown('slow');
                    });
                }
                else
                {
                    $('#Plus_Minus_Project_Building_' + strBuildingID).html('+');
                    $('#building_for_project_' + strBuildingID).slideUp('slow');
                }
            }


            function ShowRoomNodeDetails(strRoomID)
            {
                var strPlusMinus = $('#Node_Room_Plus_Minus_' + strRoomID).html();
                if (strPlusMinus == '+')
                {
                    $.get("<?php echo URL ?>ajax_pages/show_room_details.php",
                            {
                                id: strRoomID
                            },
                    function (data, status) {
                        $('#room_' + strRoomID).html(
                                data
                                );
                        $('#room_icon_' + strRoomID).removeClass('room_folder');
                        $('#room_icon_' + strRoomID).addClass('room_folder_collapsed');
                        $('#Node_Room_Plus_Minus_' + strRoomID).html('-');
                    });
                }
                else
                {
                    $('#room_' + strRoomID).html('');
                    $('#room_icon_' + strRoomID).removeClass('room_folder_collapsed');
                    $('#room_icon_' + strRoomID).addClass('room_folder');
                    $('#Node_Room_Plus_Minus_' + strRoomID).html('+');
                }
            }

            function ShowRoomNodeDetailsForProject(strRoomID)
            {
                var strPlusMinus = $('#Room_Project_Plus_Minus_' + strRoomID).html();
                //if($('#room_for_project_'+strRoomID).html()=='')
                if (strPlusMinus == '+')
                {
                    $.get("<?php echo URL ?>ajax_pages/show_room_details_for_project.php",
                            {
                                id: strRoomID
                            },
                    function (data, status) {
                        $('#room_for_project_' + strRoomID).html(
                                data
                                );
                        $('#room_icon_' + strRoomID).removeClass('room_folder');
                        $('#room_icon_' + strRoomID).addClass('room_folder_collapsed');
                        $('#Room_Project_Plus_Minus_' + strRoomID).html('-');
                    });
                }
                else
                {
                    $('#room_for_project_' + strRoomID).html('');
                    $('#room_icon_' + strRoomID).removeClass('room_folder_collapsed');
                    $('#room_icon_' + strRoomID).addClass('room_folder');
                    $('#Room_Project_Plus_Minus_' + strRoomID).html('+');
                }
            }

            function ShowRooms(strBuildingID){
            $.get("<?=URL?>/ajax_pages/show_subsystem_list.php",
                    {   
                        strBuildingID:strBuildingID,
                        mode:"room_list",
                    },
                    function(data,status){
                        $("#ddlRoomList_" + strBuildingID).html(data);
                    });
            }

            function SubSystemList(strMasterSystemID, strBuildingID)
            {
                $.get("<?php echo URL ?>ajax_pages/show_subsystem_list.php",
                        {
                            strMasterSystemID: strMasterSystemID,
                            strBuildingID: strBuildingID,
                        },
                        function (data, status) {
                            $("#ddlSubSystemList_" + strBuildingID).html(data);
                        });
            }


            function AddBuildingProject(strBuildingID)
            {
                var txtProject = 'txtProjectName_Building_' + strBuildingID;
                if (document.getElementById(txtProject).value == '')
                {
                    alert("Please enter New Project Name");
                    document.getElementById(txtProject).focus();
                    return;
                }

                $.post("<?php echo URL ?>ajax_pages/insert_project.php",
                        {
                            building_id: strBuildingID,
                            project_name: document.getElementById(txtProject).value,
                        },
                        function (data, status) {
                            ShowBuildingNameForProject(data)
                            //$('#building_for_project_'+strBuildingID).html(data);
                        });
            }

            function DeleteProject(ProjectID, SiteID, strType)
            {
                if (!confirm("Are you sure you want to Delete?"))
                {
                    return false;
                }
                $.get("<?php echo URL ?>ajax_pages/insert_project.php",
                        {
                            ProjectID: ProjectID,
                            Mode: 'Delete',
                        },
                        function (data, status) {
                            if (strType == 1)
                            {
                                ShowBuildingNameForProject(SiteID);
                            }
                            else
                            {
                                ShowRoomNodeDetailsForProject(SiteID);
                            }
                        });
            }


            function AddRoomProject(strRoomID)
            {
                var txtProject = 'txtProjectName_Room_' + strRoomID;
                if (document.getElementById(txtProject).value == '')
                {
                    alert("Please enter New Project Name");
                    document.getElementById(txtProject).focus();
                    return;
                }

                $.post("<?php echo URL ?>ajax_pages/insert_project.php",
                        {
                            building_id: 0,
                            room_id: strRoomID,
                            project_name: document.getElementById(txtProject).value,
                        },
                        function (data, status) {
                            ShowBuildingNameForProject(data)
                        });
            }


            function LoadProjectDetails(strProjectID)
            {
                $("#project_details").css('height', '0px');

                $.get("<?php echo URL ?>ajax_pages/show_project_details.php",
                        {
                            project_id: strProjectID,
                        },
                        function (data, status) {
                            $("#project_details").html(data);
                        });


                $("#project_details").css('display', 'block');

                /*$("#project_details").animate({
                 right:'0px',
                 height:'+=250px',
                 width:'+=450px'
                 });*/

                $("#project_details").animate({
                    right: '0px',
                    height: '+=300px'
                });

            }

            function CloseProjectDetailsDiv()
            {
                $("#project_details").css('height', '0px');
                $("#project_details").css('display', 'none');
            }

            function DeleteProjectSystem(strSystemProjectID, ProjectID)
            {
                if (!confirm("Are you sure you want to Delete?"))
                {
                    return false;
                }
                $.get("<?php echo URL ?>ajax_pages/show_project_details.php",
                        {
                            SystemProjectID: strSystemProjectID,
                            Mode: 'Delete',
                        },
                        function (data, status) {
                            LoadProjectDetails(ProjectID);
                        });
            }

            function LoadEquipmentNodeDetails(strSystemID)
            {
                $("#EquipmentNodeSetup_Container_Div").html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/equipment_node_mapping.php",
                        {
                            SystemID: strSystemID,
                           // parent_id:parent_id,
                           // parent_parent_id:parent_parent_id,
                        },
                        function (data, status) {
                            $("#EquipmentNodeSetup_Container_Div").html(data);
                            //$("#txtnode_serial_number").val($("#val_of_node").val());
                        });
            }
            
            function LoadSystemNodeDetails(strSystemID)
            {
                $("#SystemNodeSetup_Container").css('display', 'block');
                $("#SystemNodeSetup_Container_Div").css('height', '0px');
                $("#SystemNodeSetup_Container_Div").css('display', 'block');
                $("#SystemNodeSetup_Container_Div").animate({
                    right: '0px',
                    height: '+=400px'
                });

                $("#SystemNodeSetup_Container_Div").html('Loading...');
                $.get("<?php echo URL ?>ajax_pages/system_node_mapping.php",
                        {
                            SystemID: strSystemID,
                        },
                        function (data, status) {
                            $("#SystemNodeSetup_Container_Div").html(data);
                        });


                return;

                var $scrollingDiv = $("#SystemNodeSetup_Container_Div");

                $(window).scroll(function () {
                    $scrollingDiv
                            .stop()

                    var abc = $(window).scrollTop();

                    if (parseInt(abc) >= 200)
                    {
                        $scrollingDiv.animate({"marginTop": ($(window).scrollTop() + 30) + "px"}, "slow");
                    }

                    /*if( $(window).scrollTop() < 200 )
                     {
                     .animate({"marginTop": ($(window).scrollTop() + 30) + "px"}, "slow" );
                     }
                     else
                     {
                     .animate({"marginTop": "250px"}, "slow" );
                     }*/

                });
            }

            function ShowWidgetSerialNumber(strRoomID, strWidgetID)
            {
                $('#room_widget_serial_' + strRoomID + '_' + strWidgetID).html('Loading...');
                if ($('#PlusMinus_Node_Room_' + strWidgetID).html() == '+')

                        //if($('#room_widget_serial_'+strRoomID+'_'+strWidgetID).html()=='')
                        {
                            $.get("<?php echo URL ?>ajax_pages/show_room_widget_details.php",
                                    {
                                        id: strRoomID,
                                        widgetID: strWidgetID,
                                    },
                                    function (data, status) {
                                        $('#PlusMinus_Node_Room_' + strWidgetID).html('-');
                                        $('#room_widget_serial_' + strRoomID + '_' + strWidgetID).html(
                                                data
                                                );

                                        /*$('#room_widget_serial_icon_'+strRoomID).removeClass('room_widget_icon');
                                         $('#room_widget_serial_icon_'+strRoomID).addClass('room_widget_icon_collapsed');*/
                                    });
                        }
                else
                {
                    $('#PlusMinus_Node_Room_' + strWidgetID).html('+');
                    $('#room_widget_serial_' + strRoomID + '_' + strWidgetID).html('');
                    /*$('#room_widget_serial_icon_'+strRoomID).removeClass('room_widget_icon_collapsed');
                     $('#room_widget_serial_icon_'+strRoomID).addClass('room_widget_icon');*/
                }
            }


            function WidgetNodeForRoom(strValue, strRoomID)
            {
                var strRoomID = "WidgetPrefix_" + strRoomID;

                $.get("<?php echo URL ?>ajax_pages/get_widget_prefix.php",
                        {
                            id: strValue
                        },
                function (data, status) {
                    document.getElementById(strRoomID).innerHTML = data;
                });
            }

            function LinkUnitNode(strID)
            {
                var txtUnitID = "txtUnitIDFor_" + strID;

                var NodeSerial = $('#' + txtUnitID).val();
                var TempType = 'F';
                var TempLow = 0;
                var TempHigh = 60;
                var HumidityLow = 0;
                var HumidityHigh = 60;

                var Widget_Temp1 = 45;
                var Widget_Temp2 = 65;
                var Widget_Temp3 = 95;

                var Widget_Temp_Color_1 = '#000000';
                var Widget_Temp_Color_2 = '#009900';
                var Widget_Temp_Color_3 = '#FF0000';

                var Widget_Humidity1 = 45;
                var Widget_Humidity2 = 65;
                var Widget_Humidity3 = 95;

                var Widget_Humidity_Color_1 = '#000000';
                var Widget_Humidity_Color_2 = '#009900';
                var Widget_Humidity_Color_3 = '#FF0000';

                var ProjectID = 1;
                var Room_ID = strID;

                if (NodeSerial != '')
                {
                    $.post("<?php echo URL ?>ajax_pages/widget_linked.php",
                            {
                                id: ProjectID,
                                NodeSerial: NodeSerial,
                                TempType: TempType,
                                TempLow: TempLow,
                                TempHigh: TempHigh,
                                HumidityLow: HumidityLow,
                                HumidityHigh: HumidityHigh,
                                Widget_Temp1: Widget_Temp1,
                                Widget_Temp2: Widget_Temp2,
                                Widget_Temp3: Widget_Temp3,
                                Widget_Temp_Color_1: Widget_Temp_Color_1,
                                Widget_Temp_Color_2: Widget_Temp_Color_2,
                                Widget_Temp_Color_3: Widget_Temp_Color_3,
                                Widget_Humidity1: Widget_Humidity1,
                                Widget_Humidity2: Widget_Humidity2,
                                Widget_Humidity3: Widget_Humidity3,
                                Widget_Humidity_Color_1: Widget_Humidity_Color_1,
                                Widget_Humidity_Color_2: Widget_Humidity_Color_2,
                                Widget_Humidity_Color_3: Widget_Humidity_Color_3,
                                Room_ID: Room_ID,
                            },
                            function (data, status) {
                                var dataArr = data.split("~");

                                alert(data);
                                ShowRoomNodeDetails(Room_ID)

                            });
                }
                /*  $('.Widget_Link_Button').css('background-color','#149b47');
                 $('.Widget_Link_Button').html('Linked');*/
                /*
                 ProjectTree_Container = TREE
                 bd-wrapper = CONTROL WORKSPACE
                 ProjectSetup_Container = PROJECTS
                 */
            }
            
            /*node managment*/
            function AddNewCategory() {
        if($('#txtCategoryName').val() == ""){
            alert('Please enter Category');
            $('#txtCategoryName').trigger('focus');
            return false;
        }
        
        $.post("<?php echo URL ?>ajax_pages/category.php", 
            {
                mode:"add",
                txtCategoryName: $('#txtCategoryName').val(),
          
            },
            function (data, status) {
                alert("category added");
                $('#ddlCategory').html(data);
                $('#txtCategoryName').val("");
                
        });
    }
    
    function manageCategory() {
        $.get("<?php echo URL ?>ajax_pages/category.php", {},
            function (data, status) {
                $('#CategoryAndFuelTypeContainer').show();
                $('#CategoryAndFuelTypeContainer').html(data);             
        });
    }
    
    function DeleteCategory(id) {
        if(confirm("Are you sure you want to delete this category.")){
            $.post("<?php echo URL ?>ajax_pages/category.php", 
                {
                    mode:"delete",
                    txtCategoryId: id
                },
                function (data, status) {
                    manageCategory();
            });
        }
    }
    
    function EditCategory(ele) {
        $(ele).parent().parent().find('span:first-child').html('<input type="text" value="'+$(ele).parent().parent().find('span:first-child').text()+'">');
        $(ele).prev().hide();
        $(ele).hide();
        $(ele).next().show();
    }
    
    function UpdateCategory(ele, id) {
        $(ele).parent().parent().find('span:first-child').text($(ele).parent().parent().find('span:first-child input').val());
        $(ele).prev().show();
        $(ele).prev().prev().show();
        $(ele).hide();
        
        $.post("<?php echo URL ?>ajax_pages/category.php", 
            {
                mode:"update",
                txtCategoryId: id,
                txtCategoryName: $(ele).parent().parent().find('span:first-child').text()
            },
            function (data, status) {
                manageCategory();
        });
    }
    
    /* Created By Saurabh Yadav on 15-03-2016
     * Used in node_cpe_mapping.php and show_building.php
     * Functionality:-To show added cpe under a node in a pop-up
     */
     function Review(CPEID,node_id){
             $.post("<?= URL?>ajax_pages/CPE_device_mapping.php",
                    {
                      mode:"showCPEDetails",
                      CPEID:CPEID,
                      node_id:node_id,
                    },function(data,status){
                         $('#CpeBox').html(data);
                         $('#CpeBox').show();
                     });
         } 
//     function node_serial(parent_id,strSystemID){
//          //$("#fourth_level_"+parent_id).show();
//          var name=$("#"+parent_id).html();
//          $.get("<?=URL?>ajax_pages/node_serial.php",
//            {   strSystemID:strSystemID,
//                parent_id:parent_id,
//                name:name,
//                mode:"prefix",       
//             },
//        function(data,status){
//                    $("#hidden123").html('<input id="val_of_node" parent_id='+parent_id+' type="hidden" value="'+data+'">');
//            LoadEquipmentNodeDetails(strSystemID);
//             
//            console.log(data);
//        
//    });
    
//    }
        </script>   
    </head>
    <body>
        
        <div id="lightbox" class="lightbox" style=" display :none;top: 0px; left: 0px; ">
             <div class="lb-dataContainer" style="float:right" >
                    <div class="lb-data">
                         <div class="lb-closeContainer" >
                             <a class="lb-close" onclick="lightboxclose()" style="cursor:pointer"></a>
                         </div>
                    </div>
                </div>
                    <div class="lb-outerContainer" >
                       
<!--                      <div class="lb-container">-->
                    <img class="lb-image" src="" >
                      </div>
           
        </div>
        <div id="MainContainer" ng-controller="CanvasControls">
            <div id="Logo">
                <a href="<?php echo URL ?>"><img src="<?php echo URL ?>images/logo.png" border="0"  width="185px" height="70px" /></a>
            </div>
            
            <div>
                <div class="TopMenu" id="Home_Main_Menu">Home</div>
                <?php if ($_SESSION['user_login']->ADMIN_ACCESS == 1) { ?>
                    <div class="TopMenu" id="Administrator_Main_Menu">Administrator</div>
                <?php } ?>
                <div class="TopMenu TopMenu_active">Engineer</div>
                <div class="TopMenu" id="Controls_Main_Menu">Controls</div>
                <!--<div class="TopMenu">User</div>-->

                <div class="GreetingsMenu" style="float:right; margin-left:1%; margin-right:1%;">
                <?php echo $_SESSION['user_login']->user_full_name; ?> - <?php echo $_SESSION['user_login']->user_position; ?><br>
                    <a href="#">Change Password</a> | <a href="<?php echo URL ?>logout.php">Logout</a>
                </div>

                <div style="float:right;text-align:right;width:13%;position:relative;top:30%;">
                    <img style="width:74%;" src="<?php echo URL; ?>images/energydas-ticket.png" />
                </div>

                <div style="float:right; text-align:right;width:13%;position:relative;top:28%;right:-3%;">
                    <img style="width:75%;" src="<?php echo URL; ?>images/energydas_coms.png" />
                </div>

                <div class="clear"></div>
            </div>

            <div id="Menu">
                <ul>
                    <?php if (in_array('Project_Setup', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="Projects_Main_Menu" class="LargeMenu" style="margin-right:30px;">Projects</li>
                    <?php } ?>    
                    <li id="Systems_Menu" style="margin-right:30px;" class="LargeMenu">Systems</li>
                    <li id="Design_Menu" style="margin-right:30px;" class="LargeMenu active">Design</li>
                    <?php if (in_array('Control_Choice', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <!--<li id="Controls_Main_Menu" style="margin-right:30px;" class="LargeMenu">Controls</li>-->
                    <?php } ?>
                    <li id="MandV_Main_Menu" style="margin-right:30px;" class="LargeMenu">M&V</li>
                    <li id="Wifi_Main_Menu" style="margin-right:30px;" class="LargeMenu">Wi-fi</li>
                    <li id="Application_Main_Menu" style="margin-right:30px; float:right;" class="LargeMenu">Application</li>
                    
                </ul>
                <div class="clear"></div>
            </div>

            <div id="Menu" style="border-top:1px solid #EFEFEF;">
                <?php if (in_array('Project_Setup', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                <ul class="Project_Sub_Menu" style="display:none;">
                    <li id="showProjectSetup">Project Setup</li>
                </ul>
                <?php } ?>        
                
                <ul class="Systems_Menu" style="display:none;">
                    <li id="showMasterSystems">MASTER SYSTEMS</li>
                    <li id="showMasterEquipments">MASTER EQUIPMENT</li>
                    <li id="showNodeManagement">NODE MANAGEMENT</li>
                    <li id="showControlWorkspace">SYSTEMS WORKSPACE</li>
                </ul>
                
                <ul class="Design_Menu">
                    <?php if (in_array('System_Management', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="showSystemNodes">System Management</li>
                    <?php } ?>
                    <?php if (in_array('Node_Management', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="showTree" class="active">Node Management</li>  
                    <?php } ?> 
                    <?php if (in_array('Control_Workspace', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>         
                        <li id="showNewControl" >Control Workspace</li>
                    <?php } ?>
                </ul>
                
                <?php if (in_array('Control_Choice', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                    <ul class="Control_Sub_Menu" style="display:none;">
                        <li id="control_menu_1" class="active">Control Choices</li>
                    </ul>
                <?php } ?>
                
                <ul class="MandV_Sub_Menu" style="display:none;">
                    <li id="MandV_Sub_menu_1" class="active">M&V BASELINE</li>
                    <li id="MandV_Sub_menu_2">COSTS</li>
                </ul>
                
                <ul class="Wifi_Sub_Menu" style="display:none;">
                    <li id="Add_router_Menu" class="active">Router Admin</li>
                    <li id="Basic_Settings_Menu">Basic Settings</li>
                    <li id="Firmware_upgrade_Menu">Firmware Update</li>
                    <li id="Router_Details_Menu">Router Details</li>
                    <li id="Firewall_Settings_Menu">Firewall Settings</li>
                    <li id="Mesh_config_Menu">Mesh Configuration</li>
                </ul>
                
                <div class="clear"></div>
            </div>

            <div class="BottomMenu_1" style="display:none;">
                <div style="float:left;">
                    <select name="ddlClientListForController" id="ddlClientListForController">
                        <?php $Client->ListClient(); ?>
                    </select>
                </div>

                <div style="float:left; margin-left:20px;" id="ddlClientSiteForController"></div>
                <div style="float:left; margin-left:20px;" id="ddlClientBuildingForController"></div>
                <div style="float:left; margin-left:20px;" id="ddlClientBuildingProjectForController"></div>

                <div style="float:left; margin-left:20px;">
                    <input name="txtControlName" id="txtControlName" type="text" placeholder="Create or Open Workspace" style="width:195px;" />
                    <div id="ExistingWorkspace_Container" style="display:none;">

                    </div>
                </div>
                <div style="float:left; margin-left:5px;">         	
                    <img src="<?php echo URL ?>images/save-button.png" alt="Save" title="Save" border="0" id="rasterize-json" ng-click="rasterizeJSON()" style="cursor:pointer; margin-top:4px;" />
                </div>
                <div style="float:left; margin-left:5px;" id="Open_Workspace_Button">         	
                    <img src="<?php echo URL ?>images/folder-icon.png" alt="Open" title="Open" border="0" id="Open_Workspace" style="cursor:pointer; margin-top:4px;" />
                </div>
                <div style="float:left; margin-left:5px; display:none;" id="Close_Workspace_Button" ng-click="confirmClear()">         	
                    <img src="<?php echo URL ?>images/close-icon-blue.png" height="16" width="16" alt="Close" title="Close" border="0" id="Close_Workspace" style="cursor:pointer; margin-top:4px;" />
                </div>
                <div class="clear"></div>
            </div>
            
            <div id="Menu" class="BottomMenuSystems" style="display:none; background-color: #cecbe6; padding: 10px;">
                <ul class="showMasterSystems">
                    <li id="showSystemManagement" class="active" style="color: #000000;">System Management</li>
                    <li id="showSearchSystems" class="active" style="color: #000000; border: none;">Search Systems</li>
                </ul>
                
                <ul class="showMasterEquipments">
                    <li id="showEquipmentManagement" class="active" style="color: #000000;">Equipment Management</li>
                    <li id="showEquipmentGallary" class="active" style="color: #000000; border: none;">Equipment Gallery</li>
                    <li id="showSearchManagement" class="active" style="color: #000000; border: none;">Search Equipment</li>
                </ul>
                
                <ul class="showNodeManagement">
                    <li id="showEquipmentNodes" class="active" style="color: #000000;">Equipment Nodes</li>
                    <li id="showCpeNodeLink" class="active" style="color: #000000; border: none;">CPE Node link</li>
                    <li id="showAssignNode" class="active" style="color: #000000; border: none;">Assign Node</li>
                    <li id="showNodeActivity" class="active" style="color: #000000; border: none;">Node Activity</li>
                </ul>
                <ul class="showControlWorkspace">
                    <li id="showBuilding" class="active" style="color: #000000;">BUILDINGS</li>
                    <li id="showBuildingSystem" class="active" style="color: #000000; border: none;">BUILDING SYSTEMS</li>
                    <li id="showSystemManage" class="active" style="color: #000000; border: none;">SYSTEMS MANAGE</li>
                    <li id="showFloorPlans" class="active" style="color: #000000; border: none;">FLOOR PLANS</li>

                </ul>
                <div class="clear"></div>
            </div>
            
            <div id="ProjectTree_Container" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">NODE ASSIGNMENT</div>
                <select name="ddlClientList" id="ddlClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="ProjectTree_1" style="display:none; margin-bottom:30px;">

                </div>
            </div>
            <div id="ProjectTree_ContainerActivity" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">NODE ASSIGNMENT</div>
                <select name="ddlClientListActivity" id="ddlClientListActivity">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="ProjectTreeActivity_1" style="display:none; margin-bottom:30px;">

                </div>
            </div>
            <div id="Configure_Building" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">CONFIGURE BUILDINGS</div>
                <select name="ConfigureBuilding" id="ConfigureBuilding">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="ProjectTreeActivity_1" style="display:none; margin-bottom:30px;">

                </div>
            </div>
            
            <div id="Configure_Building_images" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">CONFIGURE BUILDINGS SYSTEM</div>
                <select name="ConfigureBuildingImage" id="ConfigureBuildingImage">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="ProjectTreeActivity_1" style="display:none; margin-bottom:30px;">

                </div>
            </div>
            
            <div id="Configure_System_Manage" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Systems Manage</div>
                <select name="ConfigureSystemManage" id="ConfigureSystemManage">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="ProjectTreeActivity_1" style="display:none; margin-bottom:30px;">

                </div>
            </div>

            <div id="MandVClientList" style="display:none; margin-bottom:50px;">
                <div style="font-size:16px; font-weight:bold; margin-top:5px;">M&V Baseline</div>
                <select name="mvClientList" id="mvClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="mvTree" style="display:none; margin-bottom:30px;">

                </div>
            </div>
            
            <div id="MandVClientListForCost" style="display:none; margin-bottom:50px;">
                <div style="font-size:16px; font-weight:bold; margin-top:5px;">M&V Costs</div>
                <select name="mvClientListForCost" id="mvClientListForCost">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
                <div id="mvCostTree" style="display:none; margin-bottom:30px;">

                </div>
            </div>
			<div id="Add_MAC">
                +Add MAC ID
            </div>
			<div id="Router_Container" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Router Management</div>
                
                <select name="rtrClientList" id="rtrClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
            </div>
            <div id="router_tree_1" style="display:block; margin-bottom:30px;">

            </div>
        
            <div id="Settings_Container" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Basic Settings Management</div>
                <select name="wifiClientList" id="wifiClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
            </div>
            
            <div id="router_tree_2" style="display:block; margin-bottom:30px;">

            </div>
			<div id="addRouter_main_div" style="width:100%;display:none">
				<div id="MACid_div" style="padding:10px;border:1px solid #3D91A2;border-radius:5px;display:none; margin-top:-40px;float: left;">
					<div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;margin-bottom:5px;">Add New Router</div> 
					<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>Enter MAC ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type='text' id='mac_id_text' name='mac_id' style='float:left;'><br><br>
					<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>Enter Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</label>
					<input type='text' id='mac_name_text' name='mac_name' style='float:left;'><br><br>
					<input type='hidden' id='router_id' name='router_id'>
					<input type='submit' name='submit' id='mac_save' value='ADD' onclick="saveRouter()" style="float:right;margin-top:5px;">
				</div>
				<div id='table_div' style='padding:10px;border:1px solid #3D91A2;border-radius:5px;min-height:250px;min-width:600px;float:left;margin:-40px 0 20px 100px;'>	
			
				</div>
				<hr style="float:left;border-bottom:1px #999999 dotted;width:100%;" />
            </div>
            
			<!-- File upgrade menu -->
			<div id="file_upload_main" style='display:none;width:100%;'>
				<div id="file_upload" style='margin-bottom:50px;width:520px;float:left;'>
					<div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">File Upload</div>
					<div style='border:1px solid #3D91A2;padding:10px;width:500px;border-radius:5px;'> 
						<div id="upload-wrapper">
							<form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
								<input name="FileInput" id="FileInput" type="file" style='max-width:250px;' />
								<input type="submit"  id="submit-btn" value="Upload" />
								<img src="../images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
							</form>
							<div id="lastUpdate"></div>
							<div id="progressbox" ><div id="progressbar"></div ><div id="statustxt">0%</div></div>
							<div id="output"></div>
						</div>
					</div>
				</div>
				<div id="upgrade_list" style='margin:0 0 50px 20px;width:720px;float:left;'>
					<div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Select MACs</div>
					<div style='border:1px solid #3D91A2;padding:10px;width:680px;border-radius:5px;float:left;min-height:172px;'> 
						<div id='select_mac_table' style='text-align:center;'>	
			
						</div>
						<img src='../images/loading.gif' height='20' width='100' id='upgradeld' style='margin-left:505px;display:none;float:left;'>
						<input type="submit" onclick='upgradefn()' style='float:right;' id="upgrade-btn" value="Upgrade" />
					</div>	
				</div>
				<br>
				<hr style="float:left;border-bottom:1px #999999 dotted;width:100%;" />
			</div>
         		
		
			<!----------------------->
			
			<!------router details---->
			<div id="router_details" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Router Details</div>
                
                <select name="detailsClientList" id="detailsClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
            </div>
            <div id="router_tree_3" style="display:block; margin-bottom:30px;">

             </div>
			<!------------------------>
			
			<!------Firewall settings---->
			<div id="firewall_details" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Firewall Settings</div>
                
                <select name="firewallClientList" id="firewallClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
            </div>
            <div id="firewall_tree" style="display:block; margin-bottom:30px;">

             </div>
			<!------------------------>
			<!------Mesh configuration---->
			<div id="mesh_config" style="display:none; margin-bottom:50px;">
                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Mesh Configuration</div>
                
                <select name="meshClientList" id="meshClientList">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />
            </div>
            <div id="mesh_tree" style="display:block; margin-bottom:30px;">

             </div>
			<!------------------------>
			
            <div id="bd-wrapper" style="display:none;">
                <div class="BottomMenu_2" style="min-height:70px;">   	

                    <ul class="Projects_Menu">
                        <li id="Picture_Library_Menu"><img src="../images/picture-library-icon.png" alt="Picture Library" title="Picture Library" /></li>
                        <li id="Text_Menu" ng-click="addText()"><img src="../images/text-icon.png" alt="Text" title="Text" /></li>
                        <li id="Shapes_Menu"><img src="../images/shapes-icon.png" alt="Shapes" title="Shapes" /></li>      
                    </ul>

                    <div style="float:left; margin-left:20px; margin-top:0px; display:none;" id="Picture_Library_Category_List">
                        <select id="ddlCategroy" name="ddlCategroy" onChange="LoadImagemDetails(this.value)">  	
                            <?php /* $Category->ListCategoryWithNumberOfImages(); */ ?>
                        </select>
                    </div>

                    <div id="color-opacity-controls" ng-show="canvas.getActiveObject()" style="margin-top:-10px; margin-left:15px; float:left; padding:3px;">
                        <div style="float:left; width:160px;"><div style="float:left; width:90px;">Color</div><div style="float:left;"><input type="color" style="width:40px" bind-value-to="fill"></div><div class="clear"></div></div>
                        <div style="float:left; margin-left:5px;"><div style="float:left; width:100px;">Opacity</div><div style="float:left;"><input value="100" type="range" bind-value-to="opacity" style="width:80px;"></div></div>                

                        <div class="clear" style="height:2px;"></div>

                        <div style="float:left; width:160px;"><div style="float:left; width:90px;">Stroke color</div><div style="float:left;"><input type="color" value="" id="text-stroke-color"  bind-value-to="strokeColor"></div><div class="clear"></div></div>                
                        <div style="float:left; margin-left:5px; margin-right:3px;"><div style="float:left; width:100px;">Stroke width</div><div style="float:left;"><input type="range" value="1" min="1" max="5" id="text-stroke-width"  bind-value-to="strokeWidth" style="width:80px;"></div></div>

                        <div class="clear"></div>        
                    </div>

                    <div id="text-wrapper" ng-show="getText()" style="float:left; margin-top:-10px; margin-left:20px; padding:3px; width:410px;">

                        <textarea bind-value-to="text" style="width:150px;"></textarea>

                        <div id="text-controls" style="display:none;">              
                            <select id="font-family" bind-value-to="fontFamily">
                                <option value="">Choose a Font</option>
                                <option value="UsEnergyEngineers">UsEnergyEngineers</option>
                                <option value="arial">Arial</option>                
                                <option value="helvetica" selected>Helvetica</option>
                                <option value="myriad pro">Myriad Pro</option>
                                <option value="delicious">Delicious</option>
                                <option value="verdana">Verdana</option>
                                <option value="georgia">Georgia</option>
                                <option value="courier">Courier</option>
                                <option value="comic sans ms">Comic Sans MS</option>
                                <option value="impact">Impact</option>
                                <option value="monaco">Monaco</option>
                                <option value="optima">Optima</option>
                                <option value="hoefler text">Hoefler Text</option>
                                <option value="plaster">Plaster</option>
                                <option value="engagement">Engagement</option>
                            </select>      
                        </div>
                        <label for="text-font-size">Font size:</label>
                        <input type="range" value="" min="1" max="120" step="1" id="text-font-size" bind-value-to="fontSize" style="width:50px;">
                        <div id="text-controls-additional" style="margin-top:10px;">
                            <button type="button" class="btn btn-object-action" ng-click="toggleBold()" ng-class="{'btn-inverse': isBold()}" style="font-weight:bold;">B</button>
                            <button type="button" class="btn btn-object-action" id="text-cmd-italic" ng-click="toggleItalic()" ng-class="{'btn-inverse': isItalic()}">I</button>
                            <button type="button" class="btn btn-object-action" id="text-cmd-underline" ng-click="toggleUnderline()" ng-class="{'btn-inverse': isUnderline()}">U</button>  
                            <div class="clear"></div>   
                        </div>
                    </div>
                    <div class="clear"></div>

                    <div id="dynamic_image"></div> 

                    <div style="margin-top:10px; display:none; border-top:1px solid #CCCCCC; padding:10px 5px;" id="Add_Shapes_Div">
                        <button type="button" class="btn rect" ng-click="addRect()"><img src="../images/rectangle-icon.png" alt="Rectangle" title="Rectangle" /></button>
                        <button type="button" class="btn rect" ng-click="addRectStroke()"><img src="../images/rectangle-only-stroke-icon.png" alt="Rectangle without Fill" title="Rectangle without Fill" /></button>
                        <button type="button" class="btn circle" ng-click="addCircle()"><img src="../images/circle-icon.png" alt="Circle" title="Circle" /></button>
                        <button type="button" class="btn circle" ng-click="addCircleStroke()"><img src="../images/circle-only-stroke-icon.png" alt="Circle" title="Circle" /></button>
                        <button type="button" class="btn triangle" ng-click="addTriangle()"><img src="../images/triangle-icon.png" alt="Triangle" title="Triangle" /></button>
                        <button type="button" class="btn line" ng-click="addLine()"><img src="../images/line-icon.png" alt="Line" title="Line" /></button>
                        <button type="button" class="btn polygon" ng-click="addPolygon()"><img src="../images/polygon-icon.png" alt="Polygon" title="Polygon" /></button>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div style="position:relative; width:800px; margin-bottom:50px; float:left;" id="canvas-wrapper">
                    <canvas id="canvas" width="800" height="500"></canvas>  
                </div>
				
                <div style="float:left; width:330px; margin-left:10px;">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase; padding-left:5px;" class="RightPanelTitle">
                        <div id="Widget_Box_Click" style="float:left; cursor:pointer;">+ WIDGETS</div>
                        <div style="float:left; margin-left:20px;" id="BuildingRoomProjectWidgetList">
                            <select id="ddlSystemForWorkspace" name="ddlSystemForWorkspace">    	
                                <?php /* $System->ListSystemForWidget(); */ ?>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div id="WidgetDetailsByCategory" style="display:none; border-right:1px solid #CCCCCC; border-left:1px solid #CCCCCC; border-bottom:1px solid #CCCCCC; "></div>

                    <div style="margin-top:10px;">

                        <div style="font-size:16px; font-weight:bold; text-transform:uppercase; margin-top:0px; padding-left:5px;" class="RightPanelTitle">EDIT</div>
                        <div style="border:1px solid #CCCCCC; padding:3px;">
                            <div class="ControlBoxWithIcon" onClick="copy()">Copy <img src="../images/Copy.png" alt="Copy" title="Copy" border="0" style="margin-left:5px;" /></div>

                            <!--<div class="ControlBoxWithIcon" ng-click="setScaleLockX(!getScaleLockX()); setScaleLockY(!getScaleLockY()); setHorizontalLock(!getHorizontalLock()); setVerticalLock(!getVerticalLock()); LockObject();">Lock <img src="../images/lock-closed-icon.png" alt="Lock" title="Lock" border="0" style="margin-left:5px;"  /></div>-->
                            <div class="ControlBoxWithIcon" ng-click="LockObject(!(LockObject));">Lock<img src="../images/lock-closed-icon.png" alt="Lock" title="Lock" border="0" style="margin-left:5px;"  /></div>

                            <div class="ControlBoxWithIcon">Group <img src="../images/Group.png" alt="Group" title="Group" border="0" style="margin-left:5px;" /></div>
                            <div class="ControlBoxWithIcon" id="ImportWidgetButton"  onClick="LoadWidgetByJson()">Place <img src="../images/widgets-icon.png" alt="Place Widget" title="Place Widget" border="0" style="margin-left:5px;" /></div>
                            <div class="clear"></div>
                            <div class="ControlBoxWithIcon" onClick="paste()">Paste <img src="../images/Paste.png" alt="Paste" title="Paste" border="0" style="margin-left:5px;" /></div>
                            <div class="ControlBoxWithIcon"  ng-click="removeSelected()">Delete <img src="../images/delete-blue.png" alt="Delete" title="Delete" border="0" style="margin-left:5px;" /></div>
                            <div class="ControlBoxWithIcon">Ungroup <img src="../images/Ungroup.png" alt="Ungroup" title="Ungroup" border="0" style="margin-left:5px;" /></div>
                            <div class="ControlBoxWithIcon" onClick="LoadTemplateByJson()">Place <img src="../images/template.png" alt="Place Template" title="Place Template" border="0" style="margin-left:5px;" /></div>  
                            <div class="clear"></div>
                        </div>

                        <div style="font-size:16px; font-weight:bold; text-transform:uppercase; margin-top:10px; padding-left:5px;" class="RightPanelTitle">CONTROLS</div>
                        <div style="border:1px solid #CCCCCC; padding:3px;">
                            <div class="ControlBoxWithoutIcon">CLEAR SELECTION</div>
                            <div class="ControlBoxWithoutIcon" onClick="undo()">UNDO <br>ACTION</div>
                            <div class="ControlBoxWithoutIcon" ng-click="bringForward()">BRING TO <br>FRONT</div>
                            <div class="ControlBoxWithoutIcon" ng-click="bringToFront()">BRING TO <br>TOP</div>
                            <div class="clear"></div>
                            <div class="ControlBoxWithoutIcon" ng-click="confirmClear()">CLEAR <br>WORKSPACE</div>
                            <div class="ControlBoxWithoutIcon"  onClick="redo()">REDO <br>ACTION</div>
                            <div class="ControlBoxWithoutIcon" ng-click="sendBackwards()">SEND TO <br>BACK</div>
                            <div class="ControlBoxWithoutIcon" ng-click="sendToBack()">SEND <br>BACKWARDS</div>
                            <div class="clear"></div>            
                            <div class="ControlBoxWithoutIcon" ng-click="setScaleLockX(!getScaleLockX())" ng-class="{'btn-inverse': getScaleLockX()}">LOCK SCALING <br>HORIZONTAL</div>
                            <div class="ControlBoxWithoutIcon" ng-click="setScaleLockY(!getScaleLockY())" ng-class="{'btn-inverse': getScaleLockY()}">LOCK SCALING <br>VERTICAL</div>
                            <div class="ControlBoxWithoutIcon" ng-click="setHorizontalLock(!getHorizontalLock())" ng-class="{'btn-inverse': getHorizontalLock()}">LOCK MOVE<br>HORIZONTAL</div>
                            <div class="ControlBoxWithoutIcon" ng-click="setVerticalLock(!getVerticalLock())" ng-class="{'btn-inverse': getVerticalLock()}">LOCK MOVE<br>VERTICAL</div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>

                <script>
                    var kitchensink = {};
                    var canvas = new fabric.Canvas('canvas');
                    function LoadWorkspaceForEdit(strWorkspaceID)
                    {
                        if (strWorkspaceID > 0)
                        {
                            document.getElementById('EditWorkspace_ID').value = strWorkspaceID;

                            $.get("<?php echo URL ?>ajax_pages/project_workspace_edit.php",
                                    {
                                        id: strWorkspaceID,
                                    },
                                    function (data, status) {
                                        $('#txtControlName').css('display', 'block');
                                        $('#txtControlName').val($("#ddlExistingWorkspace option:selected").text());
                                        $('#ddlExistingWorkspace').css('display', 'none');
                                        $('#canvas-wrapper').html(data);
                                        $('#canvas-wrapper').focus();
                                        $('#RefreshWorkspace').css('display', 'none');
                                    });
                        }
                        else
                        {
                            $('#txtControlName').css('display', 'block');
                            $('#txtControlName').val("Untitled-Workspace");
                            $('#ddlExistingWorkspace').css('display', 'none');
                        }
                    }

                    (function () {

                        if (document.location.hash !== '#zoom')
                            return;

                        function renderVieportBorders() {
                            var ctx = canvas.getContext();

                            ctx.save();

                            ctx.fillStyle = 'rgba(0,0,0,0.1)';

                            ctx.fillRect(
                                    canvas.viewportTransform[4],
                                    canvas.viewportTransform[5],
                                    canvas.getWidth() * canvas.getZoom(),
                                    canvas.getHeight() * canvas.getZoom());

                            ctx.setLineDash([5, 5]);

                            ctx.strokeRect(
                                    canvas.viewportTransform[4],
                                    canvas.viewportTransform[5],
                                    canvas.getWidth() * canvas.getZoom(),
                                    canvas.getHeight() * canvas.getZoom());

                            ctx.restore();
                        }

                        $(canvas.getElement().parentNode).on('wheel mousewheel', function (e) {

                            var newZoom = canvas.getZoom() + e.originalEvent.wheelDelta / 300;
                            canvas.zoomToPoint({x: e.offsetX, y: e.offsetY}, newZoom);

                            renderVieportBorders();

                            return false;
                        });

                        var viewportLeft = 0,
                                viewportTop = 0,
                                mouseLeft,
                                mouseTop,
                                _drawSelection = canvas._drawSelection,
                                isDown = false;
                        canvas.on('mouse:down', function (options) {
                            isDown = true;

                            viewportLeft = canvas.viewportTransform[4];
                            viewportTop = canvas.viewportTransform[5];

                            mouseLeft = options.e.x;
                            mouseTop = options.e.y;

                            if (options.e.altKey) {
                                _drawSelection = canvas._drawSelection;
                                canvas._drawSelection = function () {
                                };
                            }

                            renderVieportBorders();
                        });

                        canvas.on('mouse:move', function (options) {
                            if (options.e.altKey && isDown) {
                                var currentMouseLeft = options.e.x;
                                var currentMouseTop = options.e.y;

                                var deltaLeft = currentMouseLeft - mouseLeft,
                                        deltaTop = currentMouseTop - mouseTop;

                                canvas.viewportTransform[4] = viewportLeft + deltaLeft;
                                canvas.viewportTransform[5] = viewportTop + deltaTop;

                                console.log(deltaLeft, deltaTop);

                                canvas.renderAll();
                                renderVieportBorders();
                            }
                        });

                        canvas.on('mouse:up', function () {
                            canvas._drawSelection = _drawSelection;
                            isDown = false;
                        });
                    })();

                    /* For Undo and Redo */
                    var state = [];
                    var mods = 0;
                    canvas.on(
                            'object:modified', function () {
                                updateModifications(true);
                            },
                            'object:added', function () {
                                updateModifications(true);
                            });
                    /* End [ For Undo and Redo ]*/


                    var copiedObject;
                    var copiedObjects = new Array();

                    createListenersKeyboard();

                    function createListenersKeyboard() {
                        document.onkeydown = onKeyDownHandler;
                        //document.onkeyup = onKeyUpHandler;
                    }

                    function onKeyDownHandler(event) {
                        //event.preventDefault();

                        var key;
                        if (window.event) {
                            key = window.event.keyCode;
                        }
                        else {
                            key = event.keyCode;
                        }


                        switch (key) {
                            //////////////
                            // Shortcuts
                            //////////////
                            // Copy (Ctrl+C)
                            case 67: // Ctrl+C
                                if (ableToShortcut()) {
                                    if (event.ctrlKey) {
                                        event.preventDefault();
                                        copy();
                                    }
                                }
                                break;
                                // Paste (Ctrl+V)
                            case 86: // Ctrl+V
                                if (ableToShortcut()) {
                                    if (event.ctrlKey) {
                                        event.preventDefault();
                                        paste();
                                    }
                                }
                                break;

                                // Delete
                            case 46: // Ctrl+V
                                if (ableToShortcut()) {
                                    deleteActive();
                                }
                                break;

                            default:
                                // TODO

                                break;
                        }
                    }


                    function ableToShortcut() {
                        /*
                         TODO check all cases for this

                         if($("textarea").is(":focus")){
                         return false;
                         }
                         if($(":text").is(":focus")){
                         return false;
                         }
                         */
                        return true;
                    }

                    function deleteActive()
                    {
                        var activeObject = canvas.getActiveObject(),
                                activeGroup = canvas.getActiveGroup();

                        if (activeGroup) {
                            var objectsInGroup = activeGroup.getObjects();
                            canvas.discardActiveGroup();
                            objectsInGroup.forEach(function (object) {
                                canvas.remove(object);
                            });
                        }
                        else if (activeObject) {
                            canvas.remove(activeObject);
                        }
                    }

                    function copy() {
                        if (canvas.getActiveGroup()) {
                            for (var i in canvas.getActiveGroup().objects) {
                                var object = fabric.util.object.clone(canvas.getActiveGroup().objects[i]);
                                object.set("top", object.top + 5);
                                object.set("left", object.left + 5);
                                copiedObjects[i] = object;
                            }
                        }
                        else if (canvas.getActiveObject()) {
                            var object = fabric.util.object.clone(canvas.getActiveObject());
                            object.set("top", object.top + 5);
                            object.set("left", object.left + 5);
                            copiedObject = object;
                            copiedObjects = new Array();
                        }
                    }

                    function paste() {
                        if (copiedObjects.length > 0) {
                            for (var i in copiedObjects) {
                                canvas.add(copiedObjects[i]);
                            }
                        }
                        else if (copiedObject) {
                            canvas.add(copiedObject);
                        }
                        canvas.renderAll();
                    }


                    function ImportWidgetButtonFunc1()
                    {

                        var widget_objects_string = '{"objects":[{"type":"circle","originX":"left","originY":"top","left":142,"top":81,"width":100,"height":100,"fill":"transparent","stroke":"#58a91c","strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","radius":50,"startAngle":0,"endAngle":6.283185307179586},{"type":"rect","originX":"left","originY":"top","left":179,"top":155,"width":50,"height":50,"fill":"#1706b7","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","rx":0,"ry":0},{"type":"circle","originX":"left","originY":"top","left":152,"top":80,"width":100,"height":100,"fill":"#9a8ce4","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","radius":50,"startAngle":0,"endAngle":6.283185307179586}],"background":""}';

                        widget_objects_arr = JSON.parse(widget_objects_string);
                        var widget_objects_count = widget_objects_arr.objects.length;

                        for (var i = 0; i <= widget_objects_count; i++)
                        {
                            if (widget_objects_arr.objects[i].type == "circle")
                            {
                                // Place all circle
                                canvas.add(new fabric.Circle({
                                    radius: widget_objects_arr.objects[i].radius,
                                    left: widget_objects_arr.objects[i].left,
                                    top: widget_objects_arr.objects[i].top,
                                    width: widget_objects_arr.objects[i].width,
                                    height: widget_objects_arr.objects[i].height,
                                    fill: widget_objects_arr.objects[i].fill,
                                    stroke: widget_objects_arr.objects[i].stroke,
                                    strokeWidth: widget_objects_arr.objects[i].strokeWidth,
                                    opacity: widget_objects_arr.objects[i].opacity
                                }));
                            }
                            else if (widget_objects_arr.objects[i].type == "rect")
                            {
                                // Place all circle
                                canvas.add(new fabric.Rect({
                                    radius: widget_objects_arr.objects[i].radius,
                                    left: widget_objects_arr.objects[i].left,
                                    top: widget_objects_arr.objects[i].top,
                                    width: widget_objects_arr.objects[i].width,
                                    height: widget_objects_arr.objects[i].height,
                                    fill: widget_objects_arr.objects[i].fill,
                                    stroke: widget_objects_arr.objects[i].stroke,
                                    strokeWidth: widget_objects_arr.objects[i].strokeWidth,
                                    opacity: widget_objects_arr.objects[i].opacity
                                }));
                            }

                        }

                    }


                    (function () {
                        var mainScriptEl = document.getElementById('main');
                        if (!mainScriptEl)
                            return;
                        var preEl = document.createElement('pre');
                        var codeEl = document.createElement('code');
                        codeEl.innerHTML = mainScriptEl.innerHTML;
                        codeEl.className = 'language-javascript';
                        preEl.appendChild(codeEl);
                        document.getElementById('bd-wrapper').appendChild(preEl);
                    })();

                    (function () {
                        fabric.util.addListener(fabric.window, 'load', function () {
                            var canvas = this.__canvas || this.canvas,
                                    canvases = this.__canvases || this.canvases;

                            canvas && canvas.calcOffset && canvas.calcOffset();

                            if (canvases && canvases.length) {
                                for (var i = 0, len = canvases.length; i < len; i++) {
                                    canvases[i].calcOffset();
                                }
                            }
                        });
                    })();
                </script>
            </div>

            <div id="ProjectSetup_Container" style="display:none; position:relative;">

                <div onClick="javascript:Check();" id="project_details" style="width:500px; height:0px; display:none; position:absolute; border:1px solid #666666; right:0px; top:0px; background-color:#FFFFFF;"></div>

                <div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Project Management</div>
                <select name="ddlClientListForProject" id="ddlClientListForProject">
                    <?php $Client->ListClient(); ?>
                </select>
                <hr style="border-bottom:1px #999999 dotted;" />

                <div id="SiteForClientProject"></div>

            </div>

            <div id="SystemNodeSetup_Container" style="position:relative;"> 
                <div id="SystemNodes_Container" style="display:none;">
                    System Management
                </div>

                <!-- System Node Container -->
                <div id="SystemNodeSetup_Container_Div" style="width:620px; position:absolute; height:400px; border:1px solid #666666; display:none; right:0px; top:100px; background-color:#FFFFFF;"></div>
                <!-- System Node Container -->

            </div>
            <div id="Controls_Container" style="display:none;">

                Loading...
            </div>
        </div>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    </body>
</html>
