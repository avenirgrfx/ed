<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/building.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
require_once(AbsPath . "classes/customer.class.php");
require_once(AbsPath . "classes/widget_category.class.php");

$DB = new DB;
$Category = new Category;
$System = new System;
$Building = new Building;
$Gallery = new Gallery;
$Client = new Client;
$WidgetCategory = new WidgetCategory;

if ($_SESSION['user_login']->login_id == "") {
    Globals::SendURL(URL . 'login.php');
}

if (Globals::Get('login_id') <> "") {
    $_SESSION['client_details']->client_id = Globals::Get('login_id');
}

$strClientID = $_SESSION['client_id'] = $_SESSION['client_details']->client_id;

if ($_POST['type'] == 'System') {
    $System->parent_id = $_POST['ddlSystem'];
    $System->system_name = $_POST['txtSystemName'];
    $System->has_node = ($_POST['chkHasWidget'] == "" ? 0 : 1);
    if ($_POST['System_ID'] == '') {
        $System->Insert();
    } else {
        $System->system_id = $_POST['System_ID'];
        $System->Update();
    }
    Globals::SendURL(URL . "engineers/?type=system");
}

$strQuery = "Select * from t_sites where client_id=" . $strClientID . " order by site_name asc";

if (is_array($_SESSION['Allowed_Sites_Operations']) && count($_SESSION['Allowed_Sites_Operations']) > 0) {
    if ($_SESSION['Allowed_Sites_Operations'][0] <> 0) {
        $strQuery.=" and site_id in (" . implode(',', $_SESSION['Allowed_Sites_Operations']) . ")";
    }
}

$rsSiteArr = $DB->Returns($strQuery);

$strSQL = "Select t_client.*, t_client_type.client_type from t_client, t_client_type where t_client.client_type=t_client_type.client_type_id and client_id=$strClientID";
//print $strSQL;

$strRsClientDetailsArr = $DB->Returns($strSQL);
while ($strRsClientDetails = mysql_fetch_object($strRsClientDetailsArr)) {
    $client_name = $strRsClientDetails->client_name;
    $client_type = $strRsClientDetails->client_type;
    $client_logo = $strRsClientDetails->logo;

    $strSQL = "Select software_version from t_software_version where software_version_id=" . $strRsClientDetails->software_version_id;
    $strRsSoftwareVersionDetailsArr = $DB->Returns($strSQL);
    if ($strRsSoftwareVersionDetails = mysql_fetch_object($strRsSoftwareVersionDetailsArr)) {
        $software_version = $strRsSoftwareVersionDetails->software_version;
    }
}
?>
<!DOCTYPE html>
<html class="ng-scope" lang="en"><head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta name="viewport" content="width=device-width, initial-scale=.5, maximum-scale=12.0, minimum-scale=.25, user-scalable=yes"/>
    
      
    <style type="text/css">
    @charset "UTF-8";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\:form{display:block;}.ng-animate-start{border-spacing:1px 1px;-ms-zoom:1.0001;}.ng-animate-active{border-spacing:0px 0px;-ms-zoom:1;}
    </style>
    <meta charset="utf-8">
    <title>energyDAS Customer</title>
    <link href="<?php echo URL ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo URL ?>css/master.css" rel="stylesheet">
    
    <script type='text/javascript' src="<?php echo URL ?>js/jquery.js"></script><!-- it's for grey and blue tab -->
    <script type='text/javascript' src="<?php echo URL ?>js/bootstrap.js"></script>
     <!-- vikas css -->
    <script type='text/javascript' src="<?php echo URL ?>js/energydas.js"></script>
    <script type='text/javascript' src="<?php echo URL ?>js/custom.js"></script>        
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><!-- it is for menu esp billing -->
	<script type="text/javascript" src="<?php echo URL ?>js/jquery.reveal.js"></script><!-- it is for popup -->
	<link type="text/css" href="<?php echo URL ?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	<!-- it is for website -->
	<script type="text/javascript" src="<?php echo URL ?>js/new-js/jquery-ui-1.8.16.custom.min.js"></script> <!-- it is for accordian which is insite popup -->
	<script type="text/javascript">
		$(function(){
               // Accordion
				//$("#accordion").accordion({ header: "h3", collapsible: true });

			});
	</script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo URL ?>js/pcbform.js"></script>
    <!-- mouse hover begin -->
    <script>
		$(function() {
			var moveLeft = 0;
			var moveDown = 0;
			$('a.popper').hover(function(e) {
		
				var target = '#' + ($(this).attr('data-popbox'));
		
				$(target).show();
				moveLeft = $(this).outerWidth();
				moveDown = ($(target).outerHeight() / 2);
			}, function() {
				var target = '#' + ($(this).attr('data-popbox'));
				$(target).hide();
			});
		
			$('a.popper').mousemove(function(e) {
				var target = '#' + ($(this).attr('data-popbox'));
		
				leftD = e.pageX + parseInt(moveLeft);
				maxRight = leftD + $(target).outerWidth();
				windowLeft = $(window).width() - 40;
				windowRight = 0;
				maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);
		
				if(maxRight > windowLeft && maxLeft > windowRight)
				{
					leftD = maxLeft;
				}
		
				topD = e.pageY - parseInt(moveDown);
				maxBottom = parseInt(e.pageY + parseInt(moveDown) + 20);
				windowBottom = parseInt(parseInt($(document).scrollTop()) + parseInt($(window).height()));
				maxTop = topD;
				windowTop = parseInt($(document).scrollTop());
				if(maxBottom > windowBottom)
				{
					topD = windowBottom - $(target).outerHeight() - 20;
				} else if(maxTop < windowTop){
					topD = windowTop + 20;
				}
		
				$(target).css('top', topD).css('left', leftD);
		
		
			});
			$('#ddlSitesPortfolio').trigger('change');
		
		});
		
		 function ChangeSiteDropdown(site_id){              
                $.get("<?php echo URL ?>ajax_pages/customers/dynamic_building_name_new.php",
                        {
                            site_id: site_id
                        },
                function (data, status) {
					//alert(data);
                    $('#Show_Dynamic_Buildings').html(data);
			        //ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    //ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    //UpdateBuildingElementDetails($('#ddlBuildingForSite').val(), 0);
                    $.get("<?php echo URL ?>ajax_pages/customers/building_projects.php",
                            {
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#Container_ProjectsByBuilding').html(data);
                        //$('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                       // $('#ddlFilterElectric_Gas').val('1');
                        ChangeBuildingDropdown($('#ddlBuildingForSite').val());
						
                    });
                });
            }
			 function ChangeBuildingDropdown(strBuildingID)
            {
                $("#ddlBuildingForRisk").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingForRisk');
                $("ddlBuildingForRisk").val(strBuildingID);


                $("#ddlBuildingForProfile").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingForProfile');
                $("#ddlBuildingForProfile").val(strBuildingID);
				
				 $.get("<?php echo URL ?>ajax_pages/customers/projectslistbybuilding.php",
                            {
                                building_id: strBuildingID
                            },
                    function (data, status) {
						$('#container_ddlSelectProjects').html(data);
						$('#ddlBuildingProjects').trigger('change');
						}
					);
				
			}
			function updateProjectDropdownMain(strProjectID)
			{
				$("#ddlBuildingProjectsMain").empty();
                $('#ddlBuildingProjects option').clone().appendTo('#ddlBuildingProjectsMain');
                $("#ddlBuildingProjectsMain").val(strProjectID);	
			}
			function ChangeProjectDropdown(strProjectID)
			{
				updateProjectDropdownMain(strProjectID);
				$('#project_id').val(strProjectID);
				$.get("<?php echo URL ?>ajax_pages/customers/project_risk_analysis.php",
                            {
								//building_id: strBuildingID,
                                project_id: strProjectID
                            },
                    function (data, status) {
						//$('#container_ProjectRiskAnalysis').html(data);
						
						}
					); 
				construction_cost_bugdet(strProjectID);
				loadyears();
                loadoutlaysrows(1);
                getconstructionsummary();
                updateAnnualRecurringCostBenefits();	
			}
			
		   function construction_cost_bugdet(strProjectID)
		   {
			   $.get("<?php echo URL ?>ajax_pages/customers/project_construction_cost.php",
                            {
                                project_id: strProjectID
                            },
                    function (data, status) {
						//$('#container_ProjectRiskAnalysis').html(data);
						$('#myModal').html(data);
						$("#accordion").accordion({ header: "h3", collapsible: true });
						
						//pcb
		var optionss = { 
        target:        '#output2',   
        //beforeSubmit:  showRequest, 
        success:      submitresponse
        
    }; 
 $('.close-reveal-modal').click(function(){
	 getconstructionsummary();
	 $('#years_options').trigger('change');
	 });
$("#pcbsubmit").click(function(){
	     $("#pcb_form").ajaxSubmit(optionss); 
               return false;                  
            
     });

 //alert("adf");
  var panarr = [];
   panarr[1]= ["296","297","298","299"];
   panarr[2]= ["300","301","302","303"];
   panarr[3]= ["304","305","306","307"];
  for(var i=1;i<=3;i++)
   {   //alert("adf"+i);         
$.each( panarr[i], function( index, value ){
       //alert(index+":"+value);
        
        $( "#Container_SystemsByBuilding"+i+" div.System_ID_"+value+" div.table-took ul li input" ).keyup(function(){
        
               str = this.id;
               if(str.match('qty$'))
               {
                 seltype = "qty";             
                 selid=str.substring(0,str.length - 4)             
               }
               else if(str.match('uc$'))
               {
                  seltype = "uc";
                  selid=str.substring(0,str.length - 3)             
               }
               else
               {}
               qty =$("#"+selid+"_qty").val();
               if(qty.trim() == '')qty = 0;
              // alert(qty);
               uc=$("#"+selid+"_uc").val();
               if(uc.trim() == '')uc =0;
               // alert(uc);
               //ucwd=uc.substring(3,uc.length);
               cost =parseInt(qty) * parseInt(uc);
               
               $("#"+selid+"_amt").val(cost);
               var cost = calculatetotal();
                //alert(cost[0][0]);
                var ctype =["in","an","pe"];
                var subtype = ["ea","fs","de","en"];
                var tcost=0;
                //alert("adf2");
                for(var i=0;i<ctype.length;i++)
                {
                        
                          var subcost = 0;
                          for(var j=0;j<subtype.length;j++)
                          {  subcost +=cost[i][j];
                          
                                var selidf = ctype[i]+"_"+subtype[j]+"_subt_amt";
                                $("#"+selidf).val(cost[i][j]);       
                          }
                          $("#"+ctype[i]+"_tc_amt").val(subcost);
                          tcost +=subcost;
                          
                }
                $("#budget").val("$"+tcost);
               
               });
 


   
 });
}
 
						}
					); 
		   } 

</script>
<!-- mouse hover over -->
     
     
</head>
<body>
      <div class="container" id="Customer_Main_Container">
           <div id="Customer_Header_Section">
                <div style="float:left; border-right:1px solid #333333; padding-right:10px;">
                    <?php echo Globals::Resize('../uploads/customer/' . $client_logo, 150, 70); ?>
                </div>
                <div style="float:left; margin-left:50px;">
                    <h5 style="text-transform:uppercase;"><?php echo $client_name; ?></h5>
                    <span style="font-size:24px;"><?php echo $software_version ?> - <?php echo $client_type; ?></span>
                </div>
                <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
                     energyDAS<br>
                     <div id="date_with_time_zone"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="GrayBackground">
                <?php require_once("menu.php"); ?>
                <!-- Begin Building Projects Customer_Left_Panel -->
                <div id="Customer_Left_Panel" style="height:490px;">
                    <div class="Windows_Left" style="position:relative; width:40px;">
                        <div id="Gray_Button" style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(<?php echo URL ?>images/gray_button.png); background-repeat:no-repeat; cursor:pointer;">
                        <div id="Gray_Button_Text" style="transform: rotate(270deg); transform-origin: left top 0px; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;">
                            Active
                            </div>
                        </div>
                        <div id="Blue_Button" style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(<?php echo URL ?>images/blue_button.png); background-repeat:no-repeat; cursor:pointer;">
                        <div id="Blue_Button_Text" style="transform: rotate(270deg); transform-origin: left top 0px; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;">
                            Closed
                            </div>
                        </div>
                    </div>
                    <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                         <div class="Window_Title_Bg">
                              <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                   <div class="heading">PROJECT ANALYSIS</div>
                              </div>
                              <div style="float:left; margin-left:20px;"><img src="<?php echo URL ; ?>images/window_title_divider.png"></div>
                              <div style="float:right; margin-top:20px; margin-right:20px; color: rgb(102, 102, 102); font-size: 18px;">
                                   <div>
                                        <select id="ddlSitesPortfolio" style="width:200px; font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;" onchange="ChangeSiteDropdown(this.value)">
                                        <?php while ($rsSite = mysql_fetch_object($rsSiteArr)) {
                                            echo "<option value='$rsSite->site_id'>SITE - $rsSite->site_name</option>";
                                        }?>
                                        </select>
                                  </div>
                             </div>
                             <div class="clear"></div>
                        </div>
                        <div class="Window_Container_Bg" >
                             <div id="project_active_container" style="padding: 15px 10px 10px 20px; display: block;">	
                                    <div style="float:left;">
                                         <div id="Show_Dynamic_Buildings" style="color:#666666; font-weight:bold; font-size:16px;">Loading..</div>
                                    </div>
                                    <div id="view_building" onclick="viewBuilding();" style="margin: 5px 10px 15px 0; border-radius: 4px; cursor: pointer; float:right; font-size: 14px; text-align: center; padding:4px;background-color:#CCCCCC">
                                            <strong>New Energy Project</strong>
                                    </div>                            
                                    <div class="clear"></div>
                                    <div class="myscroll" id="style-2" style="overflow-y: auto; height:338px;"> 
                                            <div id="Container_ProjectsByBuilding" style="padding-top:3px; border-top:1px solid #999999;"></div>
                                    </div>
                             </div>
                             <div id="project_closed_container" style="padding: 15px 10px 10px 20px; display: none;">  
                                    <div style="float:left;">
                                        <div id="Show_Dynamic_Buildings" style="color:#666666; font-weight:bold; font-size:16px;">
                                        <select id="ddlBuildingForSite" name="ddlBuildingForSite" onchange="ChangeBuildingDropdown(this.value)" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;"> 
                                        </select>
                                        </div>
                                    </div>
                                  
                                    <div id="view_building" onclick="viewBuilding();" style="margin: 5px 10px 15px 0; border-radius: 4px; cursor: pointer; float:right; font-size: 14px; text-align: center; padding:4px;background-color:#CCCCCC">
                                         <strong>New Energy Project</strong>
                                    </div>                            
                                    <div class="clear"></div>
                                    
                                    <div class="myscroll" id="style-2" style="overflow-y: auto; height:338px;"> 
                                    <div id="Container_SystemsByBuilding" style="padding-top:3px; border-top:1px solid #999999;">
                                        <div style="font-weight:bold;">Close Building Projects</div>
                                        <div onclick="Expand_Collapse_System_Node_For_Building(264)" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">
                                            <span class="System_ID_Expand_264">+</span>energyDAS Implementation (2016) .....................44 days 
                                        </div>
                                        <div class="System_ID_264" onclick="Expand_Collapse_System_Node_For_Building(265)" style="margin-left:30px; display:none; cursor:pointer; color:#0088cc;">
                                            <span class="System_ID_Expand_265">+</span>Project Economic Analysis 
                                        </div>
                                        <div class="System_ID_265 System_ID_Sub_264" style="margin-left:45px; display:none; font-style:italic;">
                                            <span>Close Project </span>
                                            <div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">
                                            Close Project 1
                                            </div>
                                            <div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#FFFFFF">
                                            Close Project 2
                                            </div>
                                            <div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">
                                            Close Project 3
                                            </div>
                                        </div>        
                                        <div onclick="Expand_Collapse_System_Node_For_Building(293)" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">
                                            <span class="System_ID_Expand_293">+</span>HVAC System Upgrades (2015) ............................138 days 
                                        </div>
                                        
                                        <div class="System_ID_293" onclick="Expand_Collapse_System_Node_For_Building(294)" style="margin-left:30px; display:none; cursor:pointer; color:#0088cc;">
                                            <span class="System_ID_Expand_297">+</span>Project 1
                                        </div>
                                        <div class="System_ID_294 System_ID_Sub_293" style="margin-left:45px; display:none; font-style:italic;">
                                            <span>Sub Project 1.1</span>
                                            <div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">
                                            Sub Project 1(A)
                                            </div>
                                        </div>
                                        
                                        <div class="System_ID_293" onclick="Expand_Collapse_System_Node_For_Building(295)" style="margin-left:30px; display:none; cursor:pointer; color:#0088cc;">
                                            <span class="System_ID_Expand_298">+</span>Project 2
                                        </div>
                                        <div class="System_ID_295 System_ID_Sub_293" style="margin-left:45px; display:none; font-style:italic;">
                                            <span>Sub Project 2.1</span>
                                            <div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">
                                            Sub Project 2(A)
                                            </div>
                                        </div>
                                        <div class="System_ID_295 System_ID_Sub_293" style="margin-left:45px; display:none; font-style:italic;">
                                            <span>Sub Project 2.2</span>
                                            <div style="margin-left:10px; font-style:normal; text-decoration:none; color:#0088cc; text-decoration:underline; background-color:#EFEFEF">
                                            Sub Project 2(A)
                                            </div>
                                        </div>                                
                                        <div style="font-weight:bold; border-top: 1px solid rgb(153, 153, 153);padding-top: 4px; margin-top: 10px;"> Close Room Projects</div>                  
                                 </div>
                                    </div>
                                    
                                </div>
                        
                        </div>
                        <div class="clear"></div>
                </div>
                </div>
                <!-- End Building Projects Customer_Left_Panel -->
                
                <!-- Begin Risk Analysis Customer_Right_Panel -->
                <div id="Customer_Right_Panel" style="min-height:500px;" >
                    <div class="economy-bg">
                        <div id="style-2" style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height: 396px;; border-radius:5px;">
                             <div class="heading" style="float:left; margin-top:5px;"></div>
                             <div style="float:left; margin-left:25px; margin-top:5px;">
                                <select id="ddlBuildingForRisk" name="ddlBuildingForRisk" onchange="" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">                                </select>
                             </div>
                             <div style="float:right; margin-top:5px; margin-right:20px; color:#666666;">
                                  <div class="heading" style="font-size: 18px;">PROJECT FINANCIAL RISK </div>
                             </div>
                             <div class="clear"></div>
                             <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;">
                             <div style="min-height: 35px;">
                                  <div class="popup_button" style="float:left; margin: 10px 0 0 10px; ">
                                       <a class="green-btn" href="javascript:void();">View Economic Report</a>
                                  </div>
                                  <div class="heading" style="float:left; font-size:15px; margin-top: 10px;">Active Building Project</div>
                                  <div id="container_ddlSelectProjects">Loading..</div>
                                  <div class="clear"></div>
                             </div>
                             
                             <!-- Begin Right Container Risk Analysis -->
                             <div id="container_ProjectRiskAnalysis" >
                                    <!-- Begin SUMMARY Section-->
                                    <div class="financial-risk" id="project_summary_tab_container">
                 <div class="clear"></div>
                 <div class="analysis">
                    <h3 style="color: #000; text-decoration: underline;">FINANCIAL RISK ANALYSIS RESULTS</h3>
                    <div class="clearfix"></div> 
                    <p>
                       TOTAL PROPOSED INVESTMENT IN UNDISCOUNTED DOLLARS........................$380,788.42 <br />
                       ANNUAL CAPITAL COST (PRINCIPAL + INTEREST) ..............................$53,961
                    </p>
                    <div>
                         <div class="dot-bg"><a href="javascript:void(0)">SIMPLE PAYBACK (SPB)</a></div>
                         <div class="eco-result"><a href="javascript:void(0)"><strong><span class="spb_result"></span> YEARS</strong></a></div>
                    </div>
                    <div>
                         <div class="dot-bg"><a href="javascript:void(0)">RETURN ON INVESTMENT (ROI)</a></div>
                         <div class="eco-result"><a href="javascript:void(0)"><strong><span class="roi_result"></span> %</strong></a></div>
                    </div>
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">LIFE CYCLE COST OF ASSET (LCC)</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong>$<span class="lcc_result"></span></strong></a></div>
                    </div>
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">NET PRESENT VALUE (NPV)</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong>$<span class="npv_result"></span></strong></a></div>
                    </div>
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">INTERNAL RATE OF RETURN (IRR)</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong><span class="irr_result"></span>%</strong></a></div>
                    </div>
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">CONSERVE OR BUY RATIO</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong><span class="an_cost_to_save_one_mmbtu"></span></strong></a></div>
                    </div>                                    
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">RISK ANALYSIS (ACCEPT)</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong>$<span class="an_gain"></span> (Annual Gain)</strong></a></div>
                    </div>     
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">RISK ANALYSIS (REJECT)</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong>-$<span class="an_gain"></span> (Annual Loss)</strong></a></div>
                    </div>                                         
                    <div>
                        <div class="dot-bg"><a href="javascript:void(0)">BREAK-EVEN ANALYSIS</a></div>
                        <div class="eco-result"><a href="javascript:void(0)"><strong>$<span class="e_mai"></span> (Energy Savings only)</strong></a></div>
                    </div>                            	                                    
                    <div>
                         <div class="dot-bg"><a href="javascript:void(0)">BREAK-EVEN ANALYSIS</a></div>
                         <div class="eco-result"><a href="javascript:void(0)"><strong>$<span class="eno_mai"></span> (Energy & Net change in O&M costs)</strong></a></div>
                    </div>
                    <div id="project_summary_tab_content"></div>
                    <div class="clear"></div>
                 </div>
            </div>    
                                    <!-- End SUMMARY Section-->   
                                   
                                    <!-- Begin SPB Section-->
                                    <div class="financial-risk" id="project_spb_tab_container" style="display: none;">
                 <div class="clear"></div>
                 <div class="analysis">
                      <h3 style="color: #000; text-decoration: underline;">SIMPLE PAYBACK (SPB)</h3>
                      <h4><span><a href="#" class="popper" data-popbox="pop1">Strengths</a></span><span><a href="#" class="popper" data-popbox="pop2">Weaknesses</a></span></h4>                    <div id="pop1" class="popbox"><ul><li>Easy to understand and calculate.</li></ul></div>
                      <div id="pop2" class="popbox">
                           <ul>
                               <li>Measures TIME, not PROFITABILITY.</li>
                               <li>Fails to account for benefits accruing after payback is achieved.</li>
                               <li>Analysis does not isolate the impact of individual variables (energy prices, volume of energy saved, rebates, etc.)</li>
                               <li>No discounting of future dollars for time value of money.</li>
                               <li>Results are complicated if there are future investment requirements in future years.</li>
                               <li>For example, payback achieved on the initial investment may be undone by future investment requirements.</li>
                               <li>Fails to measure the cost of NOT doing the project.</li>
                           </ul>
                      </div>                  
                      <div class="clearfix"></div>                 
                      <p class="small-font border-top pt3">The period of time required for an investment to "pay for itself" through the annual benefit that it provides.</p>
                      <div class="clearfix"></div>
                      <div class="spb-result">
                           <div class="spb-result-content">SIMPLE PAYBACK</div>
                           <div class="spb-result-content">=</div>
                           <div class="spb-result-content"><p>TOTAL PROPOSED INVESTMENT </p><hr><p>FIRST-YEAR SAVINGS</p></div>
                           <div class="spb-result-content">=</div>
                           <div class="spb-result-content"><p>$<span class="uo_cumulative_final"></span></p><hr><p>$<span class="us_cumulative_first"></span></p></div>
                           <div class="spb-result-content">=</div>
                           <div class="spb-result-content"><p><strong><span class="spb_result"></span></strong> years</p></div>
                      </div>
                      <p>
                           TOTAL PROPOSED INVESTMENT IN UNDISCOUNTED DOLLARS........................<strong>$<span class="uo_cumulative_final"></span></strong> <br />
                           ANNUAL CAPITAL COST (PRINCIPAL + INTEREST) .........................................................<strong>$<span class="us_cumulative_first"></span></strong>          </p>
                      <div id="project_spb_tab_content"></div> 
                      <div class="clear"></div>
                </div>
            </div>  
                                    <!-- End SPB Section-->                           
                                       
                                    <!-- Begin ROI Section-->
                                    <div class="financial-risk" id="project_roi_tab_container" style="display: none;">
                 <div class="clear"></div>
                 <div class="analysis">
                           <h3 style="color: #000; text-decoration: underline;">RETURN ON INVESTMENT (ROI)</h3>
                                       
                                       
                                            <h4><span><a href="#" class="popper" data-popbox="roipop1">Strengths</a></span><span><a href="#" class="popper" data-popbox="roipop2">Weaknesses</a></span></h4>                       
                                                <div id="roipop1" class="popbox">
                                                    <ul>
                                                      <li>Easy to understand and calculate.</li>
                                                      <li>Good for comparing the economic attractiveness of two or more projects.</li>
                                                    </ul>
                                                </div>
                                                <div id="roipop2" class="popbox">
                                                    <ul>
                                                      <li>Result indicates an annual average rate of return only.  Note below that ROI may vary over individual years.</li>
                                                      <li>Per the previous point, ROI is a poor indication of risk (measure of potential variability in financial results)</li>
                                                      <li>Does not disccount future cash flows; $1 now is valued same as $1 ten years from now.</li>
                                                      <li>ROI analysis is confined to the project only; contribution to overall company profitability or wealth is not measured.</li>
                                                      <li>Analysis does not isolate the impact of individual variables (energy prices, volume of energy saved, rebates, etc.)</li>
                                                      <li>Fails to measure the cost of NOT doing the project</li>
                                                    </ul>
                                                </div>                  
                                            <div class="clearfix"></div>                 
                                            <p class="small-font border-top pt3">Total project benefits described as a percentage of the investment outlay.</p>
                                            <div class="clearfix"></div>
                                       
                                       
                            <!--<?php
                                            include("connection.php");
                                            
                                            $total_nominal_investment = "SELECT uio_cumulative FROM ed_return_on_investment ORDER BY uio_cumulative ASC LIMIT 1";
                                            $total_nominal_investment_result = mysql_query($total_nominal_investment);
                                            $total_nominal_investment = mysql_fetch_array($total_nominal_investment_result);
                                            
                                            
                                            $nominal_average_annual_return = "SELECT AVG(us_annual) FROM ed_return_on_investment where uio_year >0";
                                            $nominal_average_annual_return_result = mysql_query($nominal_average_annual_return);
                                            $nominal_average_annual = mysql_fetch_array($nominal_average_annual_return_result);
                                            
                                            $result = round(($nominal_average_annual['AVG(us_annual)']/$total_nominal_investment['uio_cumulative'])*100,2);
                                            ?>-->
                                        
                                            <div class="spb-result">
                                              <div class="spb-result-content">ROI</div>
                                              <div class="spb-result-content">=</div>
                                              <div class="spb-result-content"><p>NOMINAL AVERAGE ANNUAL RETURN </p><hr><p>TOTAL NOMINAL INVESTMENT</p></div>
                                              <div class="spb-result-content">=</div>
                                              <div class="spb-result-content"><p>$<span class="us_annual_avg"></span>
                                              </p><hr><p>$<span class="uo_cumulative_final"></span></p></div>
                                              <div class="spb-result-content">=</div>
                                              <div class="spb-result-content"><p><strong><span class="roi_result"></span>%</strong></p></div>                                                                                        
                                            </div>
                                        
                                        
                                            <!--<p>TOTAL NOMINAL INVESTMENT.......................................................$<?php echo $total_nominal_investment['uio_cumulative'];?><br />
                                            NOMINAL AVERAGE ANNUAL RETURN .........................................................$<?php echo round($nominal_average_annual['AVG(us_annual)']);?></p>-->
                                                <div id="project_roi_tab_content">ROI</div> 
                                                                                                                           
                                            <div class="clear"></div>
                                        </div>
                                    </div>  
                                    <!-- End ROI Section-->
                                    
                                    <!-- Begin LCC Section-->
                                    <div class="financial-risk" id="project_lcc_tab_container" style="display: none;">
                                        
                                        <div class="clear"></div>
                                        <div class="analysis">
                            <h3 style="color: #000; text-decoration: underline;">LIFE CYCLE COST OF ASSET (LCC)</h3>
            
                                            <h4><span><a href="#" class="popper" data-popbox="lccpop1">Strengths</a></span><span><a href="#" class="popper" data-popbox="lccpop2">Weaknesses</a></span></h4>                       
                                                <div id="lccpop1" class="popbox">
                                                    <ul>
                                                      <li>Good for comparing total cost of ownership and operation for two or more similar-purpose projects.</li>
                                                    </ul>
                                                </div>
                                                <div id="lccpop2" class="popbox">
                                                    <ul>
                                                      <li>Difficult to implement as a management metric: no single person or department clearly "owns" responsibility for life-cycle costs.</li>
                                                      <li>Other business/financial criteria may take priority over life-cycle cost.</li>
                                                      <li>No indication of wealth created by the project or variability of profitability (risk) that it offers.</li>
                                                      <li>Not useful for comparing the profitability of dissimilar projects.</li>
                                                      <li>Results are complicated if there are future
                                                investment requirements in future years.
                                                For example, payback achieved on the
                                                initial investment may be undone by future
                                                investment requirements.</li>
                                                      <li>Fails to measure the cost of NOT doing the project.</li>
                                                    </ul>
                                                </div>                  
                                            <div class="clearfix"></div>                 
                                            <p class="small-font border-top pt3">The total cost of ownership, including capital, operating and energy costs.</p>
                                            <div class="clearfix"></div>
            
            
            
                                            <div  id="project_lcc_tab_content">
                             
                              </div>
            
            
                             
                                <div class="clearfix"></div>
                                                                             
                                                                                     
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <!-- End LCC Section-->
                                    
                                    <!-- Begin NPV Section-->
                                    <div class="financial-risk" id="project_npv_tab_container" style="display: none;">
                                        
                                        <div class="clear"></div>
                                        <div class="analysis">
                                            <h3 style="color: #000; text-decoration: underline;">NET PRESENT VALUE (NPV)</h3>
                                            
                                            
                                            <h4><span><a href="#" class="popper" data-popbox="nvppop1">Strengths</a></span><span><a href="#" class="popper" data-popbox="nvppop2">Weaknesses</a></span></h4>                       
                                                <div id="nvppop1" class="popbox">
                                                    <ul>
                                                      <li>Captures the full measure of value added by the project's returns.</li>
                                                      <li>Reflects risk by incorporating the time-value of money.</li>
                                                      <li>Excellent tool for ranking two or more proposals for the total value they generate.</li>
                                                    </ul>
                                                </div>
                                                <div id="nvppop2" class="popbox">
                                                    <ul>
                                                      <li>Entire calculation relies on a series of guesses about future annual returns.</li>
                                                      <li>Analysis fails to isolate variables that can be linked to specific responsibilities.</li>
                                                      <li>Calculation and imterpretation may be too demanding for some users.</li>
                                                      <li>Fails to measure the cost of NOT doing the project.</li>
                                                    </ul>
                                                </div>                  
                                            <div class="clearfix"></div>                 
                                            <p class="small-font border-top pt3">A measure of how much value the proposed project will add to the firm.</p>
                                            <div class="clearfix"></div>
                                            
                                            
                                            <div class="spb-result">
                                              <div class="npv-result-content">NPV</div>
                                              <div class="npv-result-content">=</div>
                                              <div class="npv-result-content"><p>T</p><p style="font-size: 30px;">&Sigma;</p><p>t=1</p></div>
                                              <div class="npv-result-content"><p>ANNUAL CASH FLOW<sub>t</sub></p><hr><p class="text-center">(1+r) <sup>t</sup></p></div>
                                              <div class="npv-result-content">-</div>
                                              <div class="npv-result-content"><p>CASH FLOW IN YR<sub>0</sub></p></div>
                                              <div class="npv-result-content">=</div>
                                              <div class="npv-result-content"><p><strong>$<span class="npv_result"></span></strong></p></div>                                                                                        
                                            </div>
                                            
                            <div class="spb-div plus-npv" id="project_npv_tab_content">
                                
                
                            </div>
                             
                                                                                                                                   
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <!-- End NPV Section-->
                                    
                                    <!-- Begin IRR Section-->
                                    <div class="financial-risk" id="project_irr_tab_container" style="display: none;">
                                         <div class="clear"></div>
                                         <div class="analysis">
                                              <h3 style="color: #000; text-decoration: underline;">INTERNAL RATE OF RETURN (IRR)</h3>
                                              <h4><span><a href="#" class="popper" data-popbox="irrpop1">Strengths</a></span><span><a href="#" class="popper" data-popbox="irrpop2">Weaknesses</a></span></h4>                    <div id="irrpop1" class="popbox">
                                                   <ul>
                                                      <li>Excellent for ranking the rate of return for two or more proposals.</li>
                                                      <li>Reflects risk by incorporating the time-value of money.</li>
                                                   </ul>
                                              </div>
                                              <div id="irrpop2" class="popbox">
                                                   <ul>
                                                      <li>Fails to measure the absolute value of wealth created.</li>
                                                      <li>Entire calculation relies on a series of guesses about future annual returns.</li>
                                                      <li>Analysis fails to isolate variables that link to individual accountabilities.</li>
                                                      <li>Calculation and imterpretation may be too demanding for some users.</li>
                                                      <li>Fails to measure the cost of NOT doing the project.</li>
                                                   </ul>
                                              </div>                  
                                              <div class="clearfix"></div>                 
                                              <p class="small-font border-top pt3">
                                                 The discount rate at which an investment's future returns break even with the initial outlay.  
                                                 IRR is value for "r" that allows this equation to resolve to zero:
                                              </p>
                                              <div class="clearfix"></div>    
                                              <div class="spb-result">
                                                   <div class="irr-result-content">CASH OUTLAY<sub>0</sub></div>
                                                   <div class="irr-result-content">+</div>
                                                   <div class="irr-result-content"><p>T</p><p style="font-size: 30px;">&Sigma;</p><p>t=1</p></div>
                                                   <div class="irr-result-content"><p>CASH FLOW<sub>t</sub></p><hr><p class="text-center">(1+r) <sup>t</sup></p></div>
                                                   <div class="irr-result-content">=</div>
                                                   <div class="irr-result-content"><p>$0<span id="npv_result"></span></p></div>
                                                   <div class="irr-result-content">
                                                        <p style="text-align: right; margin-right: -155px;"><b>IRR= <span class="irr_result"></span>%</b> for this project</p>
                                                  </div>
                                              </div>
                                              <div id="project_irr_tab_content"></div>     
                                              <div class="clear"></div>
                                          </div>
                                    </div>
                                    <!-- End IRR Section-->
                                    
                                    <!-- Begin CON Section-->
                                    <div class="financial-risk" id="project_con_tab_container" style="display: none;">
                                         <div class="clear"></div>
                                         <div class="analysis">
                                              <h3 style="color: #000; text-decoration: underline;">CONSERVE OR BUY RATIO</h3>
                                              <h4>
                                                 <span><a href="#" class="popper" data-popbox="conpop1">Strengths</a></span>
                                                 <span><a href="#" class="popper" data-popbox="conpop2">Weaknesses</a></span>
                                              </h4>                       
                                              <div id="conpop1" class="popbox">
                                                   <ul>
                                                      <li>Immediately compares the value of ACCEPTING or REJECTING the project.</li>
                                                      <li> Incorporates financial variables (cost of money) as well as operational variables (volume of energy).</li>
                                                   </ul>
                                              </div>
                                              <div id="conpop2" class="popbox">
                                                   <ul>
                                                      <li>"Outside the box" -- very few managers are familiar with this metric in a facility management context.</li>
                                                   </ul>
                                              </div>                  
                                              <div class="clearfix"></div>                 
                                              <p class="small-font border-top pt3 mb">This metric allows you to:</p>
                                              <ul class="small-font con-condition">
                                                  <li>Evaluate the cost to (1) buy excess energy and waste it, or (2) pay for a waste-reduction solution</li>
                                                  <li>Evaluate the cost of DOING NOTHING -- that is, the cost to reject the proposed energy improvement</li>
                                                  <li>Determine the maximum value that you should be willing to pay for the proposed investment</li>
                                              </ul>
                                              <div class="border-top">
                                                   <h3>PART 1:  Annualized Project Cost</h3>
                                                   <ul class="annualized-project-cost">
                                                       <li>Total proposed undiscounted investment <span>.....................................</span></li>
                                                       <li><strong>$<span id="con_in_invest"></span></strong></li>
                                                   </ul>
                                                   <ul class="annualized-project-cost">
                                                       <li>Economic life of project<span>..........................................................</span></li>
                                                       <li><strong><span id="con_years"></span> years</strong></li>
                                                   </ul>
                                                   <ul class="annualized-project-cost">
                                                       <li>Cost of Capital (annual i%)<span>......................................................</span></li>
                                                       <li><strong><span id="con_ror"></span>%</strong></li>
                                                   </ul>
                                                   <ul class="annualized-project-cost">
                                                       <li>Capital recovery factor (CRF)*<span>...................................................</span></li>
                                                       <li><strong><span id="crf"></span></strong></li>
                                                   </ul>
                                               </div>
                                               <div class="clearfix"></div>
                                               <div>
                                                    <div class="spb-result mt10">
                                                        <div class="annualized-project-cost-farmula">*CAPITAL RECOVERY FACTOR (CRF) </div>
                                                        <div class="annualized-project-cost-farmula">=</div>
                                                        <div class="annualized-project-cost-farmula">
                                                             <p>(i/12)*(1+i/12)<sup>n*12</sup></p>
                                                             <hr>
                                                             <p>[(1+i/12)<sup>n*12</sup>]-1</p>
                                                        </div>
                                                        <div class="annualized-project-cost-farmula">x</div>
                                                        <div class="annualized-project-cost-farmula">12</div>                                                                                    
                                                    </div>
                                               </div>
                                
                                                <div class="abbrivition">
                                                    <p>where:</p>
                                                    <p>i = cost of capial or discount rate on future cash flows (annual rate)</p>
                                                    <p>n =economic life (years) of the proposed energy improvement</p>
                                                    <p>NOTE: the calculation shown here amortizes capital recovery on a monthy basis.</p>
                                                    <p>           Monthly amortization (x 12) yields total annualized capital cost.</p>
                                                </div>
                                                
                                                <div>
                                                    <div class="spb-result mt10">
                                                        <div class="annualized-project-cost-result">YOUR RESULTS:</div>
                                                        <div class="annualized-project-cost-result">Annualized total proposed invesment<span>........</span></div>
                                                        <div class="annualized-project-cost-result"><strong>$<span class="annualized_tpi"></span></strong></div>
                                                        <div class="annualized-project-cost-result">=</div>   						
                                                        <div class="annualized-project-cost-result">Total proposed undiscounted investment * CRF</div>
                                                                                                                 
                                                    </div>
                                                </div>
                            
                                                <div class="border-top part-2">
                                                    <h3>PART 2:  Conserve-or-Buy Cost Comparison</h3>
                                                    <div class="clearfix"></div>
                                                    <p>This compares the cost to conserve one MMBtu of energy to the cost to buy one MMBtu.</p>
                                                    
                                                    <div class="spb-result mt10">
                                                        <div class="cost-comparison">Annualized cost to save one MMBtu </div>
                                                        <div class="cost-comparison">=</div>
                                                        <div class="cost-comparison"><p>Annualized Total Cost of Improvement</p><hr><p>Annual Volume of MMBtu Saved</p></div>
                                                        <div class="cost-comparison">=</div>
                                                        <div class="cost-comparison"><p>$<span class="annualized_tpi"></span></p><hr><p>$<span class="an_mmbtu_saved"></span></p></div>
                                                        <div class="cost-comparison">=</div>
                                                        <div class="cost-comparison"><strong>$<span class="an_cost_to_save_one_mmbtu"></span></strong></div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <p class="part-2-p w70"> First-year price to buy each MMBtu currently being wasted = </p>
                                                    <p class="part-2-p"><strong>$<span class="first_price_per_mmbtu"></span></strong></p>
                                                        
                                                    <div class="spb-result mt10">
                                                        <div class="cost-comparison-result">YOUR RESULTS: </div>
                                                        <div class="cost-comparison-result">Conserve-or-Buy Ratio*:</div>
                                                        <div class="cost-comparison-result"><p>Cost to Conserve</p><hr><p>Cost to Buy</p></div>
                                                        <div class="cost-comparison-result">=</div>
                                                        <div class="cost-comparison-result">
                                                             <p>$<span class="an_cost_to_save_one_mmbtu"></span></p>
                                                             <hr>
                                                             <p>$<span class="first_price_per_mmbtu"></span></p>
                                                        </div>
                                                        <div class="cost-comparison-result">=</div>
                                                        <div class="cost-comparison-result"><strong><span class="cob_ratio"></span></strong></div>
                                                        <div class="clearfix"></div>
                                                    </div>						
                                                        
                                                    <p>*This ratio is calculated for first-year costs only.  First-year ratio results are the most conservative in an inflationary environment.</p>
                                                    <p>The proposed project offers the investor the opportunity to:</p>
                                                    <ul class="part-2-ul">
                                                        <li>reduce annual energy consumption by a total of <strong><span class="an_mmbtu_saved"></span></strong> MMBtu</li>
                                                        <li>
                                                            for a total of <strong><span class="an_mmbtu_saved"></span></strong> 
                                                            MMBtu effectively lower the first-year energy price of 
                                                            <strong>$<span class="first_price_per_mmbtu"></span>/MMBtu</strong> to a fixed price of 
                                                            <strong>$<span class="an_cost_to_save_one_mmbtu"></span>/MMbtu</strong>
                                                        </li>
                                                        <li>
                                                            spend <strong>$<span class="cob_ratio"></span></strong> 
                                                            to avoid spending <strong>$1.00</strong> on energy in the first year
                                                        </li>
                                                        <li>eliminate the obligation to acquire <strong><span class="an_mmbtu_saved"></span></strong> MMBtu at an annual cost of <strong>$<span id="con_extra"></span></strong> (subject to price escalation), and do this at a fixed annualized cost of <strong>$<span class="annualized_tpi"></span></strong></li>
                                                        <li>improve annual operating income in the first year by a total of <strong>$<span id="con_extra2"></span></strong></li>
                                                    </ul>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div id="project_con_tab_content"></div>     
                                                <div class="clear"></div>
                                        </div>
                                    </div>   
                                    <!-- End CON Section-->
                                          
                                    <!-- Begin RISK Section-->
                                    <div class="financial-risk" id="project_risk_tab_container" style="display: none;">
                                         <div class="clear"></div>
                                         <div class="analysis">
                                              <h3 style="color: #000; text-decoration: underline;">RISK ANALYSIS (ACCEPT)</h3>                  
                                              <div class="clearfix"></div>                 
                                              <p class="small-font border-top pt3">The breakdown of current total annual energy and O&M expenditure for the subject application.</p>
                                              <div class="mb spb-div">
                                                    <div class="row-data">
                                                        <div class="risk-analysis"><p><span class="omit_exp_per"></span>%</p></div>
                                                        <div class="risk-analysis"><p>$<span class="omit_exp"></span></p></div>
                                                        <div class="risk-analysis"> 
                                                             <svg><rect width="15" height="15" style="fill:#7f7f7f;stroke-width:2;stroke:rgb(0,0,0)" /></svg> 
                                                        </div>
                                                        <div class="risk-analysis">
                                                             <p>
                                                                = COMMITTED EXPENDITURE:  This is the dollar value, at current prices, of energy and <br />
                                                                maintenance expenses that are needed to efficiently accomplish the subject activity.  
                                                             </p>
                                                        </div>
                                                    </div>
                                                    <div class="row-data">
                                                        <div class="risk-analysis"><p><span class="an_pc_per"></span>%</p></div>
                                                        <div class="risk-analysis"><p>$<span class="an_pc"></span></p></div>
                                                        <div class="risk-analysis"> 
                                                             <svg><rect width="15" height="15" style="fill:#d8d8d8;stroke-width:2;stroke:rgb(0,0,0)" /></svg> 
                                                        </div>
                                                        <div class="risk-analysis">
                                                             <p>
                                                                = ANNUALIZED PROJECT COST:  This is the total installed cost of the proposed improvement, <br />
                                                                expressed as an annualized equivalent.
                                                             </p>
                                                        </div>
                                                    </div>
                                                    <div class="row-data" style="margin-top: -40px;">
                                                        <div class="risk-analysis"><p><span class="value_risk_per"></span>%</p></div>
                                                        <div class="risk-analysis"><p>$<span class="value_risk"></span></p></div>
                                                        <div class="risk-analysis"> 
                                                             <svg><rect width="15" height="15" style="fill:#f00;stroke-width:2;stroke:rgb(0,0,0)" /></svg> 
                                                        </div>
                                                        <div class="risk-analysis" style="padding-top: 41px;"> 
                                                             <p>
                                                                = VALUE AT-RISK <br /><strong>IF ACCEPTED,</strong> the proposed project provides GROSS ANNUAL ENERGY SAVINGS plus a<br />
                                                                ANNUAL NET CHANGE IN NON-ENERGY EXPENSES minus the ANNUALIZED PROJECT COST.<br><strong> IF REJECTED,</strong> the investor 
                                                                pays an annual economic penalty in the first year in the form of <br>avoidable payments to the utility.  This annual payment
                                                                is equal to the VALUE AT-RISK.
                                                             </p>
                                                        </div>
                                                    </div>
                                                    <div class="risk-analysis row-data">
                                                        <p class="red-color">
                                                           NOTE:  These are first-year values that will INCREASE as energy prices rise, and/or as the cost of capital falls.
                                                        </p>
                                                    </div>
                                                    <div class="row-data">
                                                        <div class="risk-current-total">
                                                             <div><p class="w50 fl"><span class="oav_sum_per"></span>%</p><p class="w50 fl">$<span class="oav_sum"></span></p></div>
                                                        </div>
                                                        <div class="risk-current-total">
                                                             <span>
                                                                  <svg><rect width="15" height="15" style="fill:#f00;stroke-width:2;stroke:rgb(0,0,0)" /></svg> 
                                                             </span>
                                                             <span class="plus">+</span>
                                                             <span><svg><rect width="15" height="15" style="fill:#d8d8d8;stroke-width:2;stroke:rgb(0,0,0)" /></svg></span> 
                                                             <span class="plus">+</span>
                                                        <span>
                                                            <svg>
                                                                <rect width="15" height="15" style="fill:#7f7f7f;stroke-width:2;stroke:rgb(0,0,0)" />  													
                                                            </svg> 
                                                        </span> 
                                                        </div>
                                                        <div class="risk-current-total"> 
                                                             <p style="padding-top: 20px;">
                                                               = CURRENT TOTAL ANNUAL EXPENDITURE for the subject application, includes energy and maintenance.
                                                             </p>
                                                        </div>
                                                    </div>    
                                                    <div class="row-data" style="margin-top: -38px;">
                                                         <div class="risk-current-total">
                                                              <strong><em>Note</em></strong>
                                                              <div class="clearfix"></div>
                                                              <p class="w50 fl"><span class="av_sum_per"></span>%</p><p class="w50 fl">$<span class="av_sum"></span></p>
                                                         </div>
                                                         <div class="risk-current-total risk-current-total-two">
                                                              <span><svg><rect width="15" height="15" style="fill:#f00;stroke-width:2;stroke:rgb(0,0,0)" /></svg></span>
                                                              <span class="plus">+</span>
                                                              <span><svg><rect width="15" height="15" style="fill:#d8d8d8;stroke-width:2;stroke:rgb(0,0,0)" /></svg></span> 
                                                         </div>
                                                         <div class="risk-current-total risk-current-total-two"> 
                                                              <p style="padding-top: 40px;">
                                                                 = GROSS ANNUAL BENEFITS:  Total value of annual benefits (energy savings plus non-energy savings) before subtracting
                                                                 the annualized cost of the proposed energy solution.
                                                              </p>
                                                         </div>
                                                    </div>       
                                                    <div class="row-data">
                                                         <div class="w40 fl graph">
                                                              <div class="graph-building">
                                                                   <div class="red-column" style="height: 130px;"></div>
                                                                   <div class="light-grey-column" style="height: 19px;"></div>
                                                                   <div class="dark-grey-column" style="height: 8px;"></div>
                                                              </div>
                                                         </div>
                                                         <div class="w60 fl">
                                                         <h2 style="padding-left: 80px;">COMPARE FIRST-YEAR OUTCOMES</h2>  
                                                         <div class="row-data compare-first-year">
                                                               <ul class="heading">
                                                                    <li class="pl33p">ACCEPT <br /> PROPOSAL</li>
                                                                    <li>REJECT <br /> PROPOSAL</li>
                                                               </ul>
                                                               <ul>
                                                                    <li>You GET:  </li>
                                                                    <li class="border-compare">$<span class="av_sum"></span> in gross annual benefits (energy savings plus net change in non-energy costs)</li>
                                                                    <li class="border-compare">$0 plus the satisfaction of not pursuing a capital project</li>
                                                               </ul>     
                                                               <ul>
                                                                    <li>You GIVE UP:  </li>
                                                                    <li class="border-compare">$<span class="an_pc"></span> annualized project cost</li>
                                                                    <li class="border-compare">$<span class="av_sum"></span> gross annual benefits minus $<span class="an_pc"></span> annualized capital costs saved by NOT accepting the proposal</li>
                                                               </ul>         
                                                               <ul class="outcome-last">
                                                                    <li>Your NET <br>POSITION: </li>
                                                                    <li><strong>$<span class="an_gain"></span> <br>annual gain</strong></li>
                                                                    <li><strong>-$<span class="an_gain"></span> <br>annual loss</strong></li>
                                                               </ul>                                           
                                                          </div>                                                                                 
                                                      </div>                                   
                                                    </div>                                 
                                                </div>   
                                                <div class="clearfix"></div>
                                                <div id="project_risk_tab_content"></div>                                                              
                                                <div class="clear"></div>
                                         </div>
                                     </div> 
                                    <!-- End RISK Section-->   
                                    
                                    <!-- Begin BEA  Section-->
                                    <div class="financial-risk" id="project_bea_tab_container" style="display: none;">
                                                <div class="clear"></div>
                                                <div class="analysis">
                                                        <h3 style="color: #000; text-decoration: underline;">BREAK-EVEN ANALYSIS</h3>                  
                                                        <div class="clearfix"></div>                 
                                                        <p class="small-font border-top pt3">
                                                               A measure indicating the MOST that the investor should be willing to pay for the proposed energyimprovement.
                                                               The annualized cost <br/>of an energy solution should be NO MORE than the value of the annualenergy waste 
                                                               that it eliminates. To understand the break-even calculation, consider the following:
                                                        </p>
                                                        <div class="clearfix"></div>
                                                        <ul style="list-style: none;">
                                                            <li>1. The cost to CONSERVE one MMBtu should be no more than the price to BUY one MMBtu.</li>
                                                            <li>
                                                                2. To make a meaningful comparison, evaluate both energy savings and project costs on an annualized basis.
                                                                Therefore, the model for the maximum acceptable annualized project cost is as follows:
                                                            </li>
                                                        </ul>
                                            
                                                        <div class="mt10">
                                                            <div class="white-comparison">MAXIMUM ACCEPTABLE ANNUALIZED PROJECT COST</div>
                                                            <div class="white-comparison">=</div>
                                                            <div class="white-comparison"><p>DELIVERED PRICE PER MMBtu of ENERGY</p></div>
                                                            <div class="white-comparison">X</div>
                                                            <div class="white-comparison"><p>UNITS OF AVOIDED ENERGY CONSUMPTION</p></div>
                                                            <div class="white-comparison">=</div>
                                                            <div class="white-comparison">CURRENT VALUE OF ANNUAL ENERGY WASTE</div>
                                                            <div class="clearfix"></div>
                                                        </div>						
                                                        
                                                        <div class="spb-result mt10">
                                                            <div class="bea-result">YOUR RESULTS: </div>
                                                            <div class="bea-result"><strong>$<span class="max_acc_an_pc"></span></strong></div>
                                                            <div class="bea-result">=</div>
                                                            <div class="bea-result"><p>$<span class="price_per_mmbtu"></span></p></div>
                                                            <div class="bea-result">X</div>
                                                            <div class="bea-result"><span class="an_mmbtu_saved"></span> MMBtu</div>
                                                        </div>		
                                                        
                                                        <ul style="list-style: none;">
                                                            <li>3.  To determine the maximum acceptable investment value for an energy reduction project:</li>
                                                        </ul>
                                                        
                                                        <div class="mt10">
                                                            <div class="white-comparison-two">IF:</div>
                                                            <div class="white-comparison-two">ANNUALIZED PROJECT COST</div>
                                                            <div class="white-comparison-two">=</div>
                                                            <div class="white-comparison-two"><p>TOTAL PROPOSED PROJECT INVESTMENT</p></div>
                                                            <div class="white-comparison-two">X</div>
                                                            <div class="white-comparison-two"><p>CAPITAL RECOVERY FACTOR</p></div>
                                                        </div>						
                                            
                                                        <div class="mt10">
                                                            <div class="white-comparison-three">THEN:</div>
                                                            <div class="white-comparison-three"><p>ANNUALIZED PROJECT COST</p><hr><p>CAPITAL RECOVERY FACTOR</p></div>						
                                                            <div class="white-comparison-three">=</div>
                                                            <div class="white-comparison-three"><p>TOTAL PROPOSED PROJECT INVESTMENT</p></div>
                                                        </div>						
                                                        
                                                        <div class="mt10">
                                                            <div class="white-comparison-three">AND:</div>
                                                            <div class="white-comparison-three"><p>MAXIMUM ACCEPTABLE ANNUALIZED PROJECT COST</p><hr><p>CAPITAL RECOVERY FACTOR</p></div>						
                                                            <div class="white-comparison-three"><p style="margin-top: 14px;">=</p></div>
                                                            <div class="white-comparison-three"><p>MAXIMUM ACCEPTABLE PROJECT COST</p></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="spb-result mb">
                                                            <h3 class="w100 text-dn ln20">YOUR RESULTS BASED ON ENERGY SAVINGS ALONE:</h3>
                                                        </div>
                                                        <div class="spb-result mb">
                                                            <div class="bea-result-based-on">MAXIMUM ACCEPTABLE INVESTMENT</div>
                                                            <div class="bea-result-based-on">=</div>
                                                            <div class="bea-result-based-on"><p>$<span class="max_acc_an_pc"></span></p><hr><p><span class="bea_crf"></span></p></div>
                                                            <div class="bea-result-based-on">=</div>
                                                            <div class="bea-result-based-on"><strong>$<span class="e_mai"></span></strong></div>
                                                        </div>
                                                        <div class="spb-result mb">
                                                            <h3 class="w100 text-dn ln20">YOUR RESULTS BASED ENERGY & NET CHANGE IN O&M COSTS:</h3>
                                                        </div>
                                                        <div class="spb-result mb">
                                                            <div class="bea-result-based-on">MAXIMUM ACCEPTABLE INVESTMENT</div>
                                                            <div class="bea-result-based-on">=</div>
                                                            <div class="bea-result-based-on"><p>$<span class="total_savings"></span></p><hr><p><span class="bea_crf"></span></p></div>
                                                            <div class="bea-result-based-on">=</div>
                                                            <div class="bea-result-based-on"><strong>$<span class="eno_mai"></span></strong></div>
                                                        </div>
                                                        
                                                        <div class="spb-result mb">
                                                            <p class="pl90">When the variables are as follows:</p>
                                                        </div>
                                                        <div class="spb-result mb sbpforbvg">
                                                                    <div style="width: 300px; margin: 0 auto;">
                                                                        <div class="result-alone"><strong><span id="bea_ror"></span>%</strong></div>
                                                                        <div class="result-alone">=</div>
                                                                        <div class="result-alone"><p>cost of capital</p></div>
                                                                    </div>
                                                        </div>
                                                        <div class="spb-result mb sbpforbvg">
                                                                    <div style="width: 300px; margin: 0 auto;">
                                                                        <div class="result-alone"><strong><span id="bea_years"></span></strong></div>
                                                                        <div class="result-alone">=</div>
                                                                        <div class="result-alone"><p>economic life of the proposed project in years</p></div>
                                                                    </div>
                                                        </div>
                                                        <div class="spb-result mb sbpforbvg">
                                                            <div style="width: 300px; margin: 0 auto;">
                                                                <div class="result-alone"><strong>$<span class="price_per_mmbtu"></span></strong></div>
                                                                <div class="result-alone">=</div>
                                                                <div class="result-alone"><p>delivered price per MMBtu consumed</p></div>
                                                            </div>
                                                        </div>						
                                                </div>
                                                <div class="clearfix"></div>
                                                <div id="project_bea_tab_content"></div>                                                     
                                                <div class="clear"></div>
                                        </div>
                                    <!-- end of bea section -->
                             </div> 
                             <!-- End Right Container Risk Analysis --> 
                               
                         </div>
                         <!-- end of id style-2 -->                            
                      </div> 
                      <!-- end of economy-bg -->
                      <div style="margin-top:10px;">
                            <div class="benchmark_button_active project_summary_tab_container" id="project_summary_tab" style="float:left;text-align: center;background-color:#526D9A">
                            <b>SUMMARY</b>
                            </div>
                            <div class="benchmark_button project_spb_tab_container" id="project_spb_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>SPB</b>
                            </div>
                            <div class="benchmark_button project_roi_tab_container" id="project_roi_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>ROI</b>
                            </div>  
                            <div class="benchmark_button project_lcc_tab_container" id="project_lcc_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>LCC</b>
                            </div>        
                            <div class="benchmark_button project_npv_tab_container" id="project_npv_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>NPV</b>
                            </div>
                            <div class="benchmark_button project_irr_tab_container" id="project_irr_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>IRR</b>
                            </div>                                    
                            <div class="benchmark_button project_con_tab_container" id="project_con_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>CON</b>
                            </div>        
                            <div class="benchmark_button project_risk_tab_container" id="project_risk_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>RISK</b>
                            </div>       
                            <div class="benchmark_button project_bea_tab_container" id="project_bea_tab" style="float:left;text-align: center; margin-left:11px; padding:5px 10px;background-color:#99999a">
                            <b>BEA</b>
                            </div>                                                                                                                         
                            <div class="clear"></div>
                        </div>
                   </div>
                <!-- End Risk Analysis Customer_Right_Panel -->
            </div>
            <div class="clear"></div>
            <!-- Begin Customer_Bottom_Panel -->
            <div id="Customer_Bottom_Panel" class="site-details">
                     <div class="Windows_Main" style="margin-left:35px; width: 94%; border:1px solid #999999; border-radius:10px; margin-bottom: 23px;">
                        <div class="Window_Title_Bg project-profile" style="width: 100%;" >
                            <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                                  <div class="heading">PROJECT PROFILE</div>
                            </div>
                            <div style="float:left; margin-left:20px;"><img src="<?php echo URL ?>images/window_title_divider.png"></div>
                            <div style="float:left;width: 240px; margin: 20px 0px 0px 25px;">
                                <select id="ddlBuildingProjectsMain" name="ddlBuildingProjectsMain" onchange="" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;"></select>
                            </div>                         
                            <div style="float:left;width: 240px; margin: 20px 0px 0px 180px;">
                                <select id="ddlSiteSummaryBuilding" name="ddlSiteSummaryBuilding" onchange="UpdateAllBuildingDropdown(this.value)" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                                    <option selected="selected" value="14">Project Investment Profile</option>
                                </select>
                            </div>                            
                            <div style="float:right; margin-top:20px; margin-right:20px; color: rgb(102, 102, 102); font-size: 18px;">
                                 <div>
                                    <select id="ddlBuildingForProfile" name="ddlBuildingForProfile" onchange="ChangeSiteDropdown(this.value)" style="width:200px; font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;"></select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="Window_Container_Bg">
                            <div style="padding: 15px 10px 10px; min-height:600px;">
                                <div class="innerbox" id="summary_container">
                                    <div id="site_details_dynamic_content" style="overflow: hidden;" >
                                        <div style="width: 49%; float:left; font-size:15px; border-right: 1px solid rgb(255, 255, 255);}">
                                            <form>
                                                <fieldset>
                                                <div class="project-file-heading">Investor's Financial Criteria</div>
                                                <div>
                                                    <div class="profile-project-left-text">
                                                        <p>Economic life of this investment, in years</p>
                                                    </div>
                                                    <div class="profile-project-right-text">	
                                                          <select id="years_options"  style="width: 73px; font-family: UsEnergyEngineers;"></select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="profile-project-left-text"><p>Cost of Capital or Minimum Rate of Return (annual i%)</p></div>
                                                    <div class="profile-project-right-text">	
                                                        <input type="text" placeholder="%" name="an_ror" id="an_ror" value="" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="profile-project-left-text"><p>Annual escalation factor for energy prices</p></div>
                                                    <div class="profile-project-right-text">	
                                                        <input type="text" placeholder="%" id="an_esc_factor" name="an_esc_factor" value="" />
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>                                    
                                                <div class="project-file-heading">Investor's Financial Targets</div>
                                                <div>
                                                    <div class="profile-project-left-text"><p>Simple Payback (SPB) Enter YEARS</p></div>
                                                    <div class="profile-project-right-text">
                                                         <input type="text" placeholder="2" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="profile-project-left-text"><p>Return on Investment (ROI) Enter number in format "X%"</p></div>
                                                    <div class="profile-project-right-text">	
                                                         <input type="text" placeholder="20%"  />
                                                    </div>
                                                </div>         
                                                <div class="clearfix"></div>                                    
                                                <div class="project-file-heading" style="border-top: 1px solid #ccc; padding-top: 8px; width: 98%;">
                                                     Salvage Value of Proposed New Equipment
                                                </div>
                                                <div>
                                                    <div class="salvage-value-left-text">
                                                        <p>Future value of new equipment, less costs of new equipment, less</p>
                                                    </div>
                                                    <div class="salvage-value-right-text">	
                                                        <input type="text" id="futurevalue" name="futurevalue" class="small-input" placeholder="Undiscounted Value($)" value="" />
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="project-file-heading">Construction Budget</div>
                                                <div class="project-file-heading" style="border-top: 1px solid #ccc; padding-top: 8px; width: 98%;">
                                                    Additional One-Time Investment Outlays
                                                </div>
                                                </fieldset>
                                            </form>
                                            <form method="post" action="" id="one_time_investment_outlays_form" >
                                                <fieldset> 
                                                <div class="mb10">
                                                    <div class="salvage-value-left-text">
                                                        <select id="years" style="width: 73px; font-family: UsEnergyEngineers;">
                                                                    <option selected="selected" value="year" >Year</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                    <option value="6">6</option>
                                                                    <option value="7">7</option>
                                                                    <option value="8">8</option>
                                                                    <option value="9">9</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                         </select>        
                                                         <input class="large-input w200" placeholder="Enter Description" onclick="validate()" id="oti_description" type="text" value="">
        
                                                     </div>
                                                     <div class="salvage-value-right-text">
                                                        <input type="hidden" name="project_id" id="project_id" value="0">  
                                                        <input type="hidden" name="oti_id" id="oti_id" value="0">
                                                        <input class="small-input" style="border-color: rgb(0, 0, 0);" placeholder="Undiscounted Value($)" onkeypress="return isNumberKey(event)" id="oti_undiscounted_value" type="text" value="">
                                                        <input class="grey-btn" type="button" name="otisubmit" id="otisubmit" value="ADD">
                                                        <div id="success" style="color:green; font-weight: bold;"></div>
                                                     </div>
                                                     <div class="clearfix"></div>
                                                  </div>   
                                                  </fieldset>
                                            </form>
                                            <div class="clear"></div>
                                        </div>
                                        <vr style="border-left:#CCCCCC 1px solid; min-height: 510px; display: inline-block;"></vr>
                                        
                                        <!-- start of bottom main window -->
                                        <div style="width: 49%; float:right;">
                                        
                                        <!-- start of construction cost bugdet section -->
                                             <div style="font-size:15px;">
                                                 <a class="grey-btn" style="color: #fff;" data-reveal-id="myModal" href="">Edit </a>
                                                 <div id="myModal" class="reveal-modal">Loading..</div>                            
                                                 <div class="project-file-heading">Construction Budget</div>
                                            <div>
                                                <div class="project-dot-bg"><a  href="javascript:void(0)">Energy Analysis</a></div>
                                                <div class="project-eco-result" id="energy_analysis">Loading</div>
                                            </div> 
                                            <div>
                                                <div class="project-dot-bg"><a  href="javascript:void(0)">Feasibility Study </a></div>
                                                <div class="project-eco-result" id="feasibility_study">Loading</div>
                                            </div>   
    
                                            <div>
                                                <div class="project-dot-bg"><a href="javascript:void(0)">Development </a></div>
                                                <div class="project-eco-result" id="development">Loading</div>
                                            </div>
        
                                            <div>
                                                <div class="project-dot-bg"><a href="javascript:void(0)">Engineering </a></div>
                                                <div class="project-eco-result" id="engineering">Loading</div>
                                            </div>  
                                            <div>
                                                <div style="text-decoration: underline;" class="project-dot-bg">TOTAL CONSTRUCTION BUDGET</div>
                                                <div style="text-decoration: underline;" class="project-eco-result" id="pcbtotalcost"></div>
                                                <input type="hidden" name="pcbtotalcost_input" id="pcbtotalcost_input" value="0">
                                            </div>     
                                            <!-- end of construction cost bugdet section -->
                                            
                                            <div class="clearfix"></div>
                                            
                                            <!-- start of oti section -->
                                            <div>
                                                 <div class="additional-one-time">
                                                    <div class="project-file-heading" style="border-top: 1px solid rgb(204, 204, 204); padding-top: 8px;">
                                                               Additional One-Time Investment Outlays
                                                    </div>
                                                    <header>
                                                            <h2 class="center" style="display: none">&nbsp;</h2>
                                                            <div class="additional-header-content">
                                                                    <h2>&nbsp;</h2>
                                                                    <h3>Year</h3>
                                                            </div>
                                                            <div class="additional-header-content">
                                                                    <h2>Enter Item Descriptions</h2>
                                                                    <h3 style="padding-left: 20px;">(One item per line)</h3>
                                                            </div>
                                                            <div class="additional-header-content">
                                                                    <h2>Undiscounted</h2>
                                                                    <h3 style="padding-left: 25px;">$Value</h3>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                    </header>
                                                    <div id="oti_row_container"></div>
                                                    <div class="clearfix"></div>
                                                  </div>
                                            </div>
                                            <!-- end of oti section -->
                                            
                                            <div>
                                                <div class="project-dot-bg text-right mt25">Total undiscounted value</div>
                                                <strong class="fl w47"><span class="fl mt25 ml85">$</span><div class="fl mt25 ml0" id="oti_amt"></div></strong>
                                                <input type=hidden name="oti_amt_input" id="oti_amt_input" value="0">
                                                <input type=hidden name="oti_amt_json_input" id="oti_amt_json_input" value='["0"]'>
                                                <div class="clearfix"></div>
                                            </div>                                                                                     
                                                                                                      
                                        </div>
                                    
                                        <div class="clearfix"></div> 
                                        
                                        <!-- start of non energy cost section -->                                   
                                        <div class="project-file-heading mt10">Annual Recurring Non-Energy Operating Costs and Benefits</div>
                                        <form>
                                           <fieldset>
                                        <div class="benefits">
                                            <div class="benefits-col"><p></p></div>
                                            <div class="benefits-col"><p class="textdn">Existing Application</p></div>
                                            <div class="benefits-col"><p class="textdn">Proposed Application</p></div>
                                            <div class="benefits-col"><p class="textdn">Net Benefit</p></div>  
                                            <div class="clearfix"></div>
                                        </div>                                          
                                             
                                        <div class="benefits">
                                            <div class="benefits-col"><p>Labor</p></div>
                                            <div class="benefits-col">
                                                <input id="labor_existing_app" onkeypress="return isNumberKey(event)" class="benefits-input netbenefit" style="border-color: rgb(0, 0, 0);" type="text">                       </div>
                                            <div class="benefits-col"> 
                                                <input id="labor_pro_app" onkeypress="return isNumberKey(event)" class="benefits-input netbenefit" style="border-color: rgb(0, 0, 0);" type="text">                            </div>
                                            <div class="benefits-col"> 
                                                <p id="labornetb">$0</p>                                      
                                            </div>  
                                            <div class="clearfix"></div>
                                        </div>                                          
       
                                        <div class="benefits">
                                            <div class="benefits-col"><p>Maintenance</p></div>
                                            <div class="benefits-col">
                                                <input id="main_existing_app" onkeypress="return isNumberKey(event)" class="benefits-input netbenefit" style="border-color: rgb(0, 0, 0);" type="text">                         </div>
                                            <div class="benefits-col"> 
                                                <input id="main_pro_app" onkeypress="return isNumberKey(event)" class="benefits-input netbenefit" style="border-color: rgb(0, 0, 0);" type="text">                            </div>
                                            <div class="benefits-col"><p id="mainnetb">$0</p></div>  
                                            <div class="clearfix"></div>
                                        </div> 
                                        
                                        <div class="benefits">
                                            <div class="benefits-col"><p style="line-height: 13px;">Other Non -Capital Costs</p></div>
                                            <div class="benefits-col">
                                                 <input id="oth_cc_exis_app" onkeypress="return isNumberKey(event)" class="benefits-input netbenefit" style="border-color: rgb(0, 0, 0);" type="text">                          </div>
                                            <div class="benefits-col"> 
                                                <input id="oth_cc_pro_app" onkeypress="return isNumberKey(event)" class="benefits-input netbenefit" style="border-color: rgb(0, 0, 0);" type="text">                            </div>
                                            <div class="benefits-col"><p id="othccnetb">$0</p></div>  
                                            <div class="clearfix"></div>
                                        </div> 
                                             
                                        <div class="benefits">
                                            <input type="button" style="width: auto;" class="grey-btn mr128" id="arnonsubmit" value="SUBMIT" />
                                        </div>
                                        
                                        <div id="result" style="text-align:center; color:green"></div>
                                        <div class="clearfix"></div>
                                        
                                        <div class="benefits">
                                            <div class="benefits-col" style="width: 80%;"><p>Total annual net cost/benefit difference, non-energy non-energy items</p></div>
                                            <div class="benefits-col" style="width: 20%;"><strong><p id="totalannualcost" style="border-top: 1px solid #000;">$0</p></strong></div>  
                                            <div class="clearfix"></div>
                                        </div>                                                                                 
                                        </fieldset>
                                    </form> 
                                    <!-- end of non energy cost section -->  
                                                  
                                    </div>       
                                   <!-- end of bottom main window -->
                                                             
                                        <div class="clear"></div>       
                                        <div class="final-total"><div>
                                        
                                    <!-- start of bottom secondary window summary -->     
                                        <div class="black-dot-bg">  
                                             <p>
                                                TOTAL PROPOSED INVESTMENT IN UNDISCOUNTED DOLLARS............... <span id="un_tpi">$0</span>
                                                <input type="hidden" name="un_tpi_input" id="un_tpi_input" value="0">
                                             </p>
                                        </div>
                                        <div class="black-dot-bg">  
                                             <p>
                                                ANNUAL CAPITAL COST (PRINCIPAL + INTEREST).......................................... <span id="capital_cost">$0</span>
                                                <input type="hidden" id="capital_cost_input" name="capital_cost_input" value="0">
                                             </p>
                                        </div>
                                        <div class="black-dot-bg"><h2> TOTAL ANNUAL BENEFITS PROVIDED BY THE PROPOSED IMPROVEMENT,</h2></div>
                                        <div class="total-right"><h2>&nbsp;</h2></div>
                                        <div>
                                         <div class="black-dot-bg">
                                              <p>
                                                  energy plus non-energy savings......................................................................
                                                  <strong><span id="enplustotal">$0</span></strong>
                                              </p>
                                         </div>
                                         <div class="total-right"></div>
                                        </div>
                                     <!-- end of bottom secondary window summary --> 
                                     
                                        
                                     </div>
                                     
                                   
                                    
                                     <div class="clearfix"></div>
                                 </div>                                
                             </div>
                             <div class="clear"></div>
                         </div>                            
                      </div>    
                  </div>
                <div class="clear"></div>
            </div>            
            <div class="clear"></div>
        </div>
            <!-- End Customer_Bottom_Panel -->
       </div>
       
       <!-- for popup -->
      <div class="popup_w" style="display:none;"><div class="popup_container" id="popup"></div></div>
      <iframe frameborder="0" id="abs-top-frame" name="abs-top-frame" src="resource://firefox.abs.avira.com/html/top.html?1460610011511#minimized"
    style="position: fixed !important; z-index: 2147483647 !important; overflow: hidden !important; top: 0px !important; left: 0px !important; right: 0px !important; width: 138px ! important; height: 13px !important; max-height: none !important; min-height: 0px !important; margin: 0px auto !important; padding: 0px !important; border: 0px none !important; background-color: transparent ! important; display: block ! important;">
      </iframe>
      <div id="Building_Details_Container" style="display:none;"></div>
      <div class="popup_w" style="display:none;"><div id="popup" class="popup_container"></div></div>
      <script src="<?php echo URL ?>highstock/js/highstock.js"></script>
      <script src="<?php echo URL ?>highstock/js/modules/exporting.js"></script>
      <script src="<?php echo URL ?>js/new-js/pie.js"></script>
      <div class="clearfix"></div>
   
</body>
</html>