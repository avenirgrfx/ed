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
				$('#Engineer_Main_Menu').click(function () {
                    window.location = '<?php echo URL ?>engineers/';
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
                    
                    //$('#showTree').addClass('active');
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
                <div class="TopMenu" id="Engineer_Main_Menu">Engineer</div>
                <div class="TopMenu TopMenu_active"   >Controls</div>
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
                        <li id="Projects_Main_Menu" class="LargeMenu active" style="margin-right:30px;">Programming</li>
                    <?php } ?>    
                    <li id="Systems_Menu" style="margin-right:30px;" class="LargeMenu">Automation</li>
                    <!--<li id="Design_Menu" style="margin-right:30px;" class="LargeMenu ">Design</li>-->
                    <?php if (in_array('Control_Choice', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <!--<li id="Controls_Main_Menu" style="margin-right:30px;" class="LargeMenu">Controls</li>-->
                    <?php } ?>
                    <!--<li id="MandV_Main_Menu" style="margin-right:30px;" class="LargeMenu">M&V</li>
                    <li id="Wifi_Main_Menu" style="margin-right:30px;" class="LargeMenu">Wi-fi</li>
                    <li id="Application_Main_Menu" style="margin-right:30px; float:right;" class="LargeMenu">Application</li>-->
                    
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
                        <li id="showSystemNodes" class="active">Logic Editer</li>
                    <?php } ?>
                    <?php if (in_array('Node_Management', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="showTree">Remote Porgraming</li>  
                        
                    <?php } ?> 
                    <?php if (in_array('Control_Workspace', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>         
                        <li id="showNewControl" >Data Uploader</li>
                    <?php } ?>
                    <li id="showTree">Remote Commander</li>  
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
            
            	<!--  ///////////////////////////////  Vikas New Desing Begin ///////////////////////////////  -->
                
            	<div class="editerMenu">
					<ul class="nav nav-tabs" style="margin-bottom: 0px;">
					  <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
					  <li><a data-toggle="tab" href="#menu1">Setting</a></li>
					</ul>
				
					<div class="tab-content">
					  	<div id="home" class="tab-pane fade in active ">
	                        <div class="file">
                              	<div class="filePartition">
	                        		<a href="javascript:void();"><img class="fileBigimg" src="../images/control-images/new_file.png" alt="" /></a>
                                </div>
                                <div class="filePartition">
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/new_folder.png" alt="" /></a>
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/save_file.png" alt="" /></a>
	                            </div>
                                <div class="clearfix"></div>
                                <p class="editerMenu_title">File</p>
	                        </div>
                            <div class="simulation">
                                <div class="simulPartition">
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/simul_run.png" alt="" /><p>Run</p></a>
	                            </div>	   
                                <div class="simulPartition">
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/simul_pause.png" alt="" /><p>Pause</p></a>
	                            </div>	     
                                <div class="simulPartition">
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/simul_halt.png" alt="" /><p>Halt</p></a>
	                            </div>	                                                                       
                                <div class="clearfix"></div>
                                <p class="editerMenu_title simulation_title">Simulation</p>                                               	
	                        </div>
                            <div class="hardwareConnection">
                                <div class="hardwarePartition">
	                                <form action="">
	                                	<fieldset>
                                        	<span><label>IP address</label><input class="ip_address" type="text" onfocus="if (this.value==this.defaultValue) this.value = ''" onblur="if (this.value=='') this.value = this.defaultValue" value="128.0.0.1" /></span>
                                            <span><label>Port</label><input class="port" type="text" onfocus="if (this.value==this.defaultValue) this.value = ''" onblur="if (this.value=='') this.value = this.defaultValue" value="8.0.0.1" /></span>
                                        </fieldset>
	                                </form>
	                            </div>	      			                            
                                <div class="hardwarePartition">
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/connect_hardware.png" alt="" width="76%" /><p>Connect</p></a>
	                            </div>	                                                                       
                                <div class="clearfix"></div>
                                <p class="editerMenu_title connection_title">Hardware Connection</p>    	                        	
	                        </div>  
                            <div class="toolBox" id="dvSource">
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img  class="toolBoximg" src="../images/control-images/tool_1.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_2.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_3.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_4.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_5.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_6.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_7.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_8.png" alt="" /></a>
                                </div>	                        	
                                <div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_9.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_10.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_11.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_12.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_13.png" alt="" /></a>
                                </div>
	                        	<div class="toolPartition">
                                	<a href="javascript:void();"><img class="toolBoximg" src="../images/control-images/tool_14.png" alt="" /></a>
                                </div>
                            	<div class="clearfix"></div>
                                <p class="editerMenu_title toolbox_title">Tool Box</p>   
                            
                            </div>      
                            <div class="exit">
                                <div class="exitPartition">
	                                <a href="javascript:void();"><img class="fileSmallimg" src="../images/control-images/exit.png" width="100%" alt="exit" /><p>&nbsp;</p></a>
	                            </div>	
                            	<div class="clearfix"></div>
                                <p class="editerMenu_title exit_title">Exit</p>                         	
	                        </div>                                                                                 
						</div>
					  	<div id="menu1" class="tab-pane fade">
					    	<h3>SETTING</h3>
						    <p>Some content in setting.</p>
					  	</div>
					</div>  				                
   				</div>	
                
                
                <div class="editerMain">
                	<div class="side_window">
                    	<div class="palette">
                        	<h3>Files</h3>
                            <div class="palette_body">
                            	<ul>
                            		<li>Untitled1.zbe</li>
                            	</ul>
                            </div>
                        </div>
                    	<div class="palette">
                        	<h3>Properties</h3>
                            <div class="palette_body">
                            	<div class="propertiesBox">                                
	                            	<h4>Mise</h4>
	                            	<ul class="half">
	                            		<li>Enable</li>
	                                    <li>ICNT</li>
	                                    <li>IRT</li>
	                                    <li>Output1</li>
	                            	</ul>
	                            	<ul class="half">
										<form action="">
			                            	<fieldset>                                    
	                            				<li>
		                                        	<span><input class="ip_address" type="text" value="0" /></span>
                                        		</li>
	                            				<li>
		                                        	<span><input class="ip_address" type="text" value="0" /></span>
                                        		</li>
	                            				<li>
		                                        	<span><input class="ip_address" type="text" value="0" /></span>
                                        		</li>
	                            				<li>
		                                        	<span><input class="ip_address" type="text" value="0" /></span>
                                        		</li>                                                                                                
		                                    </fieldset>
			                            </form>                                        
	                            	</ul>
                                    <div class="clearfix"></div>
                                </div>                                
                            </div>
                        </div>                      
                    </div>
                    <div class="main_window">

						<div id="dvDest">
						    &nbsp;
						</div>
						



                    </div>
                    <div class="clearfix"></div>
                </div>
                
                <!--  ///////////////////////////////  Vikas New Desing Finish ///////////////////////////////  -->
                
            </div>

        </div>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
        
  <style>

.draggable
{
    filter: alpha(opacity=60);
    opacity: 0.6;
}
.dropped
{
    position: static !important;
}
#dvDest
{
    min-height: 100%;
    width: 100%;
}

#dvDest
{
    padding: 0px;
    height: 500px;
}

  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script type="text/javascript">
$(function () {
    $("#dvSource img").draggable({
        revert: "invalid",
		helper: "clone",
        refreshPositions: true,
        drag: function (event, ui) {
            ui.helper.addClass("draggable");
        }
    });
    $("#dvDest").droppable({
        drop: function (event, ui) {
            if ($("#dvDest img").length == 0) {
                $("#dvDest").html("");
            }
            ui.draggable.addClass("dropped");
            $("#dvDest").append(ui.draggable);
        }
    });
});
</script> 
    </body>
</html>
