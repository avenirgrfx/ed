<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');
require_once(AbsPath."classes/customer.class.php");
require_once(AbsPath."classes/widget_category.class.php");

$DB=new DB;
$Category=new Category;
$System=new System;
$Gallery=new Gallery;
$Client = new Client;
$WidgetCategory=new WidgetCategory;

$_SESSION['user_login']->user_id=1;
$_SESSION['user_login']->login_id=1;

if($_POST['type']=='System')
{
	$System->parent_id=$_POST['ddlSystem'];
	$System->system_name=$_POST['txtSystemName'];
	$System->has_node= ($_POST['chkHasWidget']=="" ? 0 : 1);
	if($_POST['System_ID']=='')
	{
		$System->Insert();
	}
	else
	{
		$System->system_id=$_POST['System_ID'];
		$System->Update();
	}
	Globals::SendURL(URL."engineers/?type=system");
	
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
    
    <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
    
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
   	<script type='text/javascript' src="<?php echo URL?>js/prism.js"></script>
    <script type='text/javascript' src="<?php echo URL?>js/fabric.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/jquery.js"></script>  
	<script type='text/javascript' src="<?php echo URL?>js/bootstrap.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/paster.js"></script>
    <script type='text/javascript' src="<?php echo URL?>js/angular.min.js"></script>
    <script type='text/javascript' src="<?php echo URL?>js/font_definitions.js"></script>    
    <script type='text/javascript' src="<?php echo URL?>js/utils.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/app_config.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/controller.js"></script>
    <script type='text/javascript' src='<?php echo URL?>js/tree.jquery.js'></script>
    
    <script type="text/javascript">
		
		/*$(window).bind("beforeunload", function()
		{
        	return confirm("Do you really want to refresh?"); 
		});
		*/
		
		
		$(document).ready(function(){
			$('#Administrator_Main_Menu').click(function(){
				window.location='<?php echo URL?>';
			});
			
			$('#showNewControl').click(function(){
				window.location='<?php echo URL?>engineers/';
			});
			
			
			
						
				
			
			
			$('#Picture_Library_Menu').click(function(){
				$('#Add_Text_Div').css('display','none');
				$('#Add_Shapes_Div').css('display','none');
				$('#Add_Control_Div').css('display','none');
				$('#Picture_Library_Category_List').css('display','block');
				$('#dynamic_image').css('display','block');	
				$('#Picture_Library_Menu').css('background-color','#EFEFEF');
				$('#Text_Menu').css('background','none');
				$('#Shapes_Menu').css('background','none');
				$('#Controls_Menu').css('background','none'); 
				$('#Add_Template_Div').css('display','none');
				$('#SystemNodeSetup_Container_Div').css('display','none');
			});
			
			$('#Text_Menu').click(function(){				
				$('#Add_Shapes_Div').css('display','none');	
				$('#Add_Control_Div').css('display','none');
				$('#Picture_Library_Category_List').css('display','none');
				$('#dynamic_image').css('display','none');
				$('#Add_Text_Div').css('display','block');
				$('#Text_Menu').css('background-color','#EFEFEF');
				$('#Picture_Library_Menu').css('background','none');
				$('#Shapes_Menu').css('background','none');
				$('#Controls_Menu').css('background','none'); 
				$('#Add_Template_Div').css('display','none');
				$('#SystemNodeSetup_Container_Div').css('display','none');
			}); 
			
			$('#Shapes_Menu').click(function(){
				$('#Add_Text_Div').css('display','none');
				$('#Add_Control_Div').css('display','none');
				$('#Picture_Library_Category_List').css('display','none');
				$('#dynamic_image').css('display','none');
				$('#Add_Shapes_Div').css('display','block');
				$('#Shapes_Menu').css('background-color','#EFEFEF');
				$('#Text_Menu').css('background','none');
				$('#Controls_Menu').css('background','none');
				$('#Picture_Library_Menu').css('background','none');
				$('#Add_Template_Div').css('display','none');	
				$('#SystemNodeSetup_Container_Div').css('display','none');										 
			});
			
			$('#Widgets_Menu').click(function(){
				$('#Add_Text_Div').css('display','none');				
				$('#Picture_Library_Category_List').css('display','none');
				$('#dynamic_image').css('display','none');
				$('#Add_Shapes_Div').css('display','none');
				$('#Add_Control_Div').css('display','block');
				$('#Controls_Menu').css('background-color','#EFEFEF');
				$('#Text_Menu').css('background','none');
				$('#Shapes_Menu').css('background','none');
				$('#Picture_Library_Menu').css('background','none');	
				$('#Add_Template_Div').css('display','none');
				$('#SystemNodeSetup_Container_Div').css('display','none');								 
			});
			
			$('#Template_Menu').click(function(){
				$('#Add_Text_Div').css('display','none');				
				$('#Picture_Library_Category_List').css('display','none');
				$('#dynamic_image').css('display','none');
				$('#Add_Shapes_Div').css('display','none');				
				$('#Controls_Menu').css('background-color','#EFEFEF');
				$('#Text_Menu').css('background','none');
				$('#Shapes_Menu').css('background','none');
				$('#Picture_Library_Menu').css('background','none');
				$('#Add_Control_Div').css('display','none');
				
				$('#Add_Template_Div').css('display','block');
				$('#SystemNodeSetup_Container_Div').css('display','none');
			}); 
			
			iWidgetOpen=0;
			
			$('#ddlWidgetList').change(function(){
				
				$('#WidgetDetailsByCategory').slideUp('fast');
				 $.get("<?php echo URL?>ajax_pages/widget_list.php",
				  {
					id:$('#ddlWidgetList').val()				
				  },
				  function(data,status){						
						$('#WidgetDetailsByCategory').html(data);
						$('#WidgetDetailsByCategory').slideDown('slow');
						iWidgetOpen=1;
						$('#Widget_Box_Click').html('- WIDGETS');
				  });
				
			});
			
			
			
			
			
			$('#Widget_Box_Click').click(function(){
				if(iWidgetOpen==0)
				{
					$('#WidgetDetailsByCategory').slideDown('slow');
					iWidgetOpen=1;
					$('#Widget_Box_Click').html('- WIDGETS');
				}
				else
				{
					$('#WidgetDetailsByCategory').slideUp('slow');
					iWidgetOpen=0;
					$('#Widget_Box_Click').html('+ WIDGETS');
				}
			});
			
			$('#showTree').click(function(){				
				$('.BottomMenu_1').slideUp('slow');
				$('#bd-wrapper').slideUp('slow');
				$('#showTree').addClass('active');
				
				$('#showNewControl').removeClass('active');
				$('#showNewWidget').removeClass('active');
				$('#showControlOperation').removeClass('active');
				$('#showApplyControl').removeClass('active');
				$('#ProjectTree_Container').css('display','block');
				$('#ProjectTree').css('display','block');
				
				$('#ProjectTree_1').css('display','block');
				$('#SystemNodes_Container').css('display','none');
				$('#SystemNodeSetup_Container_Div').css('display','none');
				
			});
			
			
			$('#showSystemNodes').click(function(){				
				$('.BottomMenu_1').slideUp('slow');
				$('#bd-wrapper').slideUp('slow');
				$('#showTree').removeClass('active');
				$('#showSystemNodes').addClass('active');
				$('#showNewControl').removeClass('active');
				$('#showNewWidget').removeClass('active');
				$('#showControlOperation').removeClass('active');
				$('#showApplyControl').removeClass('active');
				
				$('#ProjectTree_Container').css('display','none');
				$('#ProjectTree').css('display','none');
				$('#SystemNodeSetup_Container_Div').css('display','none');
				
				$('#SystemNodes_Container').html('Loading System...');
				$('#SystemNodes_Container').css('display','block');
				
				$.get("<?php echo URL?>ajax_pages/fetch_system_for_engineer.php",
			    {
				    id:0		
			    },
			        function(data,status){
					$('#SystemNodes_Container').html(data);
							
			     });
				
				
				
			});
			
			
			
			$('#ddlClientList').change(function(){
				
				var id=$('#ddlClientList').val();
				$('#ProjectTree_1').html('Loading...');
				 $.get("<?php echo URL?>ajax_pages/show_site.php",
				  {
					id:id				
				  },
				  function(data,status){						
						$('#ProjectTree_1').html(
							 data
							);				
				  });
			});
			
			$('#ddlClientListForController').change(function(){
				
				var id=$('#ddlClientListForController').val();
				$('#ddlClientSiteForController').html('Loading...');
				 $.get("<?php echo URL?>ajax_pages/show_site_dropdown.php",
				  {
					id:id				
				  },
				  function(data,status){						
						$('#ddlClientSiteForController').html(
							 data
							);				
				  });
			});
			
			
			
			$('#ddlClientListForProject').change(function(){
				
				var id=$('#ddlClientListForProject').val();
				$('#SiteForClientProject').html('Loading...');
				 $.get("<?php echo URL?>ajax_pages/show_site_for_project.php",
				  {
					id:id				
				  },
				  function(data,status){						
						$('#SiteForClientProject').html(
							 data
							);				
				  });
			});		
			 
			
			
			$('#Projects_Main_Menu').click(function(){
				 $('.System_Menu').css('display','none');
				 $('.Project_Sub_Menu').css('display','block');		
				 $('.BottomMenu_1').slideUp('slow');
				 $('#Projects_Main_Menu').addClass('active');
				 $('#Application_Main_Menu').removeClass('active');
				 $('#Design_Menu').removeClass('active');
				 $('#Controls_Main_Menu').removeClass('active');
				 $('#showProjectSetup').trigger('click');	
				 $('#SystemNodeSetup_Container_Div').css('display','none');	   
			});
			
			
			$('#Design_Menu').click(function(){
				 $('.System_Menu').css('display','block');
				 $('.Project_Sub_Menu').css('display','none');		
				 $('.BottomMenu_1').slideDown('slow');
				 $('#Design_Menu').addClass('active');
				 $('#Application_Main_Menu').removeClass('active');
				 $('#Projects_Main_Menu').removeClass('active');
				 $('#Controls_Main_Menu').removeClass('active');	
				 $('#showNewControl').trigger('click');
				 $('#ProjectSetup_Container').css('display','none');   
				 $('#SystemNodeSetup_Container_Div').css('display','none');
			});
			
			
			$('#showProjectSetup').click(function(){
				$('#ProjectTree_Container').css('display','none');
  				$('#bd-wrapper').css('display','none');
				$('#ProjectSetup_Container').css('display','block');
				$('#showProjectSetup').addClass('active');
				$('#SystemNodeSetup_Container_Div').css('display','none');	
				$('#SystemNodes_Container').css('display','none');	
			});
			
			
			<?php
				if(Globals::Get('type')=='system')
				{?>
					$('#showSystemNodes').trigger("click");
			<?php
				}				
			?>	
			
			
		});
		
		
		function SystemOptions(strCatID)
		{
			$.get("<?php echo URL?>ajax_pages/system_details.php",
			{
				id:strCatID				
			},
			function(data,status){
				data=data.split("~#~");	
				$('[name=ddlSystem]').val( data[0]);		
				$('#txtSystemName').val(data[1]);				
				$('#System_ID').val(data[2]);
				
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
				$('#btnDelete').css('display','block');
				$('#CannotDelete').css("display", 'none');
				$('#btnDelete').attr("disabled", false);				
				$('html, body').animate({ scrollTop: $("#SystemNodes_Container").offset().top }, 200);
				$('#txtSystemName').focus();
				
				if(data[3]>0)
				{
					$('#CannotDelete').html("There are "+data[3]+" images in Category. Cannot Delete Category");
					$('#CannotDelete').css("display", 'block');
					$('#btnDelete').css("display", 'none');
				}
				
				$('#btnDelete').click(function(){
					if(!confirm("Are you sure you want to Delete "+data[1]+" ?"))
					{
						
					}
					else
					{
						$.get("<?php echo URL?>ajax_pages/system_delete.php",
						{
							id:data[2]				
						},
						function(data1,status1)
						{						
							$('#showSystemNodes').trigger("click");
						});
						
					}
				});
				
			 });			
		}
		
 		
		function AddProjectSystem()
		{
			var SystemID=$('#ddlSystemForProject').val();
			var ProjectID=$('#txt_project_id').val();
			
			
			$.post("<?php echo URL?>ajax_pages/show_project_details.php",
			  {
				SystemID:SystemID,
				ProjectID:ProjectID,			
			  },
			  function(data,status){						
					LoadProjectDetails(ProjectID);
			  });
			
		}
		
		
		function LoadImagemDetails(id)
		{				
			$.get("<?php echo URL?>ajax_pages/show_image.php",
			  {
				id:id				
			  },
			  function(data,status){						
					$('#dynamic_image').html(
						 data
						);				
			  });			
		}
		
		
		function ShowBuildingName(strSiteID)
		{
			$.get("<?php echo URL?>ajax_pages/show_building.php",
			  {
				id:strSiteID				
			  },
			  function(data,status){						
					$('#'+strSiteID).html(
						 data
						);				
			  });
		}
		
		
		function ShowBuildingNameForProject(strSiteID)
		{
			$.get("<?php echo URL?>ajax_pages/show_building_for_project.php",
			  {
				id:strSiteID				
			  },
			  function(data,status){						
					$('#Project_'+strSiteID).html(
						 data
						);				
			  });
		}
		
		function ShowRoomName(strBuildingID)
		{
			$('#building_'+strBuildingID).html('Loading...');
			$.get("<?php echo URL?>ajax_pages/show_room.php",
			  {
				id:strBuildingID		
			  },
			  function(data,status){						
					$('#building_'+strBuildingID).html(
						 data
						);				
			  });
		}
		
		
		function ShowRoomNameForProject(strBuildingID)
		{
			var strPlusMinus=$('#Plus_Minus_Project_Building_'+strBuildingID).html();
			if(strPlusMinus=='+')
			{
				$('#building_for_project_'+strBuildingID).html('Loading...');
				 $.get("<?php echo URL?>ajax_pages/show_room_for_project.php",
				  {
					id:strBuildingID		
				  },
				  function(data,status){						
						$('#building_for_project_'+strBuildingID).html(data);
						$('#Plus_Minus_Project_Building_'+strBuildingID).html('-');			
						$('#building_for_project_'+strBuildingID).slideDown('slow');
				  });
			}
			else
			{
				$('#Plus_Minus_Project_Building_'+strBuildingID).html('+');
				$('#building_for_project_'+strBuildingID).slideUp('slow');
			}
		}
		
		
	function ShowRoomNodeDetails(strRoomID)
	{
		var strPlusMinus=$('#Node_Room_Plus_Minus_'+strRoomID).html();
		if(strPlusMinus=='+')
		{
			$.get("<?php echo URL?>ajax_pages/show_room_details.php",
			  {
				id:strRoomID	
			  },
			  function(data,status){						
					$('#room_'+strRoomID).html(
						 data
						);
					$('#room_icon_'+strRoomID).removeClass('room_folder');
					$('#room_icon_'+strRoomID).addClass('room_folder_collapsed');	
					$('#Node_Room_Plus_Minus_'+strRoomID).html('-');		
			  });
		 }
		 else
		 {
		 	$('#room_'+strRoomID).html('');
			$('#room_icon_'+strRoomID).removeClass('room_folder_collapsed');
			$('#room_icon_'+strRoomID).addClass('room_folder');
			$('#Node_Room_Plus_Minus_'+strRoomID).html('+');
		 }
	}
	
	function ShowRoomNodeDetailsForProject(strRoomID)
	{
		var strPlusMinus=$('#Room_Project_Plus_Minus_'+strRoomID).html();
		//if($('#room_for_project_'+strRoomID).html()=='')
		if( strPlusMinus =='+')
		{
			$.get("<?php echo URL?>ajax_pages/show_room_details_for_project.php",
			  {
				id:strRoomID	
			  },
			  function(data,status){						
					$('#room_for_project_'+strRoomID).html(
						 data
						);
					$('#room_icon_'+strRoomID).removeClass('room_folder');
					$('#room_icon_'+strRoomID).addClass('room_folder_collapsed');
					$('#Room_Project_Plus_Minus_'+strRoomID).html('-');		
			  });
		 }
		 else
		 {
		 	$('#room_for_project_'+strRoomID).html('');
			$('#room_icon_'+strRoomID).removeClass('room_folder_collapsed');
			$('#room_icon_'+strRoomID).addClass('room_folder');
			$('#Room_Project_Plus_Minus_'+strRoomID).html('+');
		 }
	}
	
	
	
	function SubSystemList(strMasterSystemID, strBuildingID)
	{
		$.get("<?php echo URL?>ajax_pages/show_subsystem_list.php",
		  {
			strMasterSystemID:strMasterSystemID,
			strBuildingID:strBuildingID,
		  },
		  function(data,status){						
				$("#ddlSubSystemList_"+strBuildingID).html(data);			
		  });
	}
	
	
	function AddBuildingProject(strBuildingID)
	{
		var txtProject='txtProjectName_Building_'+strBuildingID;
		if(document.getElementById(txtProject).value=='')
		{
			alert("Please enter New Project Name");
			document.getElementById(txtProject).focus();
			return;
		}
		
		$.post("<?php echo URL?>ajax_pages/insert_project.php",
		  {
			building_id:strBuildingID,
			project_name: document.getElementById(txtProject).value,	
		  },
		  function(data,status){						
				ShowBuildingNameForProject(data)
				//$('#building_for_project_'+strBuildingID).html(data);
		  });		
	}
	
	function DeleteProject(ProjectID, SiteID, strType)
	{
		if(!confirm("Are you sure you want to Delete?"))
		{
			return false;
		}
		$.get("<?php echo URL?>ajax_pages/insert_project.php",
		  {
			ProjectID:ProjectID,
			Mode:'Delete',
		  },
		  function(data,status){
		  		if(strType==1)
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
		var txtProject='txtProjectName_Room_'+strRoomID;
		if(document.getElementById(txtProject).value=='')
		{
			alert("Please enter New Project Name");
			document.getElementById(txtProject).focus();
			return;
		}
		
		$.post("<?php echo URL?>ajax_pages/insert_project.php",
		  {
			building_id:0,
			room_id:strRoomID,
			project_name: document.getElementById(txtProject).value,	
		  },
		  function(data,status){						
				ShowBuildingNameForProject(data)	
		  });		
	}
	
	
	 function LoadProjectDetails(strProjectID)
	 {
	 	$("#project_details").css('height','0px');
		
		$.get("<?php echo URL?>ajax_pages/show_project_details.php",
		  {
			project_id:strProjectID,
		  },
		  function(data,status){						
				$("#project_details").html(data);				
		  });
				
		
	 	$("#project_details").css('display','block');
		
		/*$("#project_details").animate({
		  right:'0px',
		  height:'+=250px',
		  width:'+=450px'
		});*/
		
		$("#project_details").animate({
		  right:'0px',
		  height:'+=300px'  
		});

	  }
	
	
	function CloseProjectDetailsDiv()
	{
		$("#project_details").css('height','0px');
		$("#project_details").css('display','none');
	}
	
	
	function DeleteProjectSystem(strSystemProjectID, ProjectID)
	{
		if(!confirm("Are you sure you want to Delete?"))
		{
			return false;
		}
		$.get("<?php echo URL?>ajax_pages/show_project_details.php",
		  {
			SystemProjectID:strSystemProjectID,
			Mode:'Delete',
		  },
		  function(data,status){						
				LoadProjectDetails(ProjectID);
		  });
	}
	
	
	
	
	function LoadSystemNodeDetails(strSystemID)
	{
		$("#SystemNodeSetup_Container").css('display','block');	
	 	$("#SystemNodeSetup_Container_Div").css('height','0px');
	 	$("#SystemNodeSetup_Container_Div").css('display','block');		
		$("#SystemNodeSetup_Container_Div").animate({
		  right:'0px',
		  height:'+=400px'  
		});
		
		$("#SystemNodeSetup_Container_Div").html('Loading...');
		$.get("<?php echo URL?>ajax_pages/system_node_mapping.php",
		{
			SystemID:strSystemID,
		},
		function(data,status){						
				$("#SystemNodeSetup_Container_Div").html(data);
		});
		
		
		return ;
		
		var $scrollingDiv = $("#SystemNodeSetup_Container_Div");
			
			$(window).scroll(function(){			
				$scrollingDiv
					.stop()
					
					var abc=$(window).scrollTop();
					
					if(parseInt(abc) >=200)
					{
						$scrollingDiv.animate({"marginTop": ($(window).scrollTop() + 30) + "px"}, "slow" );
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
		$('#room_widget_serial_'+strRoomID+'_'+strWidgetID).html('Loading...');
		if( $('#PlusMinus_Node_Room_'+strWidgetID).html()=='+')
		
		//if($('#room_widget_serial_'+strRoomID+'_'+strWidgetID).html()=='')
		{
			$.get("<?php echo URL?>ajax_pages/show_room_widget_details.php",
			  {
				id:strRoomID,
				widgetID: strWidgetID,
			  },
			  function(data,status){
			  		$('#PlusMinus_Node_Room_'+strWidgetID).html('-');				
					$('#room_widget_serial_'+strRoomID+'_'+strWidgetID).html(
						 data
						);
					
					/*$('#room_widget_serial_icon_'+strRoomID).removeClass('room_widget_icon');
					$('#room_widget_serial_icon_'+strRoomID).addClass('room_widget_icon_collapsed');*/		
			  });
		 }
		 else
		 {
		 	$('#PlusMinus_Node_Room_'+strWidgetID).html('+');
		 	$('#room_widget_serial_'+strRoomID+'_'+strWidgetID).html('');
			/*$('#room_widget_serial_icon_'+strRoomID).removeClass('room_widget_icon_collapsed');
			$('#room_widget_serial_icon_'+strRoomID).addClass('room_widget_icon');*/
		 }
	}
	
	
	function WidgetNodeForRoom(strValue, strRoomID)
	{		
		var strRoomID="WidgetPrefix_"+strRoomID;
		
		$.get("<?php echo URL?>ajax_pages/get_widget_prefix.php",
		  {
			id:strValue		
		  },
		  function(data,status){					
				document.getElementById(strRoomID).innerHTML=data;			
		  });
	}
	
	
	
	
	
	
	
	function LinkUnitNode(strID)
	{
		var txtUnitID= "txtUnitIDFor_"+strID;
		
		var NodeSerial= $('#'+txtUnitID).val();
		var TempType='F';
		var TempLow=0;
		var TempHigh=60;
		var HumidityLow=0;
		var HumidityHigh=60;
		
		var Widget_Temp1=45;
		var Widget_Temp2=65;
		var Widget_Temp3=95;
		
		var Widget_Temp_Color_1='#000000';
		var Widget_Temp_Color_2='#009900';
		var Widget_Temp_Color_3='#FF0000';
		
		var Widget_Humidity1=45;
		var Widget_Humidity2=65;
		var Widget_Humidity3=95;
		
		var Widget_Humidity_Color_1='#000000';
		var Widget_Humidity_Color_2='#009900';
		var Widget_Humidity_Color_3='#FF0000';
		
		var ProjectID=1;
		var Room_ID=strID;
		
		if(NodeSerial!='')
		{
			$.post("<?php echo URL?>ajax_pages/widget_linked.php",
			  {
				id:ProjectID,
				NodeSerial:NodeSerial,
				TempType:TempType,
				TempLow:TempLow,
				TempHigh:TempHigh,
				HumidityLow:HumidityLow,
				HumidityHigh:HumidityHigh,
				Widget_Temp1:Widget_Temp1,
				Widget_Temp2:Widget_Temp2,
				Widget_Temp3:Widget_Temp3,
				Widget_Temp_Color_1:Widget_Temp_Color_1,
				Widget_Temp_Color_2:Widget_Temp_Color_2,
				Widget_Temp_Color_3:Widget_Temp_Color_3,
				Widget_Humidity1:Widget_Humidity1,
				Widget_Humidity2:Widget_Humidity2,
				Widget_Humidity3:Widget_Humidity3,
				Widget_Humidity_Color_1:Widget_Humidity_Color_1,
				Widget_Humidity_Color_2:Widget_Humidity_Color_2,
				Widget_Humidity_Color_3:Widget_Humidity_Color_3,
				Room_ID:Room_ID,
			  },
			  function(data,status){
					var dataArr=data.split("~");				
					
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

    </script>   

    
  </head>
  <body>
 

 
 
  <div id="MainContainer" ng-controller="CanvasControls">
  
  <div id="Logo">
  		<a href="<?php echo URL?>"><img src="<?php echo URL?>images/logo.png" border="0" /></a>
  </div>
  
  
  <div>
  	<div class="TopMenu" id="Administrator_Main_Menu">Administrator</div>
    <div class="TopMenu TopMenu_active">Engineer</div>
    <div class="TopMenu">Customer</div>
    
    <div class="GreetingsMenu" style="float:right; margin-left:10px; margin-right:10px;">
    	Felix Goto - Administrator<br>
		<a href="#">Change Password</a> | <a href="#">Logout</a>
    </div>
    
    <div style="float:right;">
    	<img src="<?php echo URL;?>images/energydas-ticket.png" />
    </div>    
    <div class="clear"></div>
  </div>
  
  
  <div id="Menu">
  	<ul>
    	<li id="Projects_Main_Menu" class="LargeMenu" style="margin-right:30px;">Projects</li>      	
        <li id="Design_Menu" style="margin-right:30px;" class="LargeMenu active">Design</li>
        <li id="Controls_Main_Menu" style="margin-right:30px;" class="LargeMenu">Controls</li>
        <li id="Application_Main_Menu" style="margin-right:30px; float:right;" class="LargeMenu">Application</li>
     </ul>
     
     <div class="clear"></div>
     
  </div>
  
  <div id="Menu" style="border-top:1px solid #EFEFEF;">
  		<ul class="System_Menu">
        	<li id="showSystemNodes">System Management</li>
            <li id="showTree">Node Management</li>            
    		<li id="showNewControl" class="active">Control Workspace</li>
        </ul>
        
       
        <ul class="Project_Sub_Menu" style="display:none;">
        	<li id="showProjectSetup">Project Setup</li>
        </ul>
        
        <div class="clear"></div>
  </div>
  
  
  <div class="BottomMenu_1" id="">
  	
    
    <div style="float:left;">
     <select name="ddlClientListForController" id="ddlClientListForController">
        <?php $Client->ListClient();?>
    </select>
    </div>
    
    <div style="float:left; margin-left:20px;" id="ddlClientSiteForController"></div>
    <div style="float:left; margin-left:20px;" id="ddlClientBuildingForController"></div>
    <div style="float:left; margin-left:20px;" id="ddlClientBuildingProjectForController"></div>
    
    
     <div style="float:left; margin-left:20px;"><input name="txtControlName" id="txtControlName" type="text" placeholder="Save Workspace" style="width:195px;" /></div>
     <div style="float:left; margin-left:5px;">         	
        <img src="<?php echo URL?>images/save-button.png" alt="Save" title="Save" border="0" id="rasterize-json" ng-click="rasterizeJSON()" style="cursor:pointer; margin-top:4px;" />
     </div>
     <div class="clear"></div>
     
  </div>
 
  

<div id="ProjectTree_Container" style="display:none; margin-bottom:50px;">
	<div style="text-transform:uppercase; font-size:16px; font-weight:bold; margin-top:5px;">Node Management</div>
	<select name="ddlClientList" id="ddlClientList">
    	<?php $Client->ListClient();?>
    </select>
    <hr style="border-bottom:1px #999999 dotted;" />
    <div id="ProjectTree_1" style="display:none; margin-bottom:30px;">
    	
    </div>
</div>
  
 

<div id="bd-wrapper">
    <div class="BottomMenu_2" style="min-height:70px;">   	
        
        <ul class="Projects_Menu">
            <li id="Picture_Library_Menu"><img src="../images/picture-library-icon.png" alt="Picture Library" title="Picture Library" /></li>
            <li id="Text_Menu" ng-click="addText()"><img src="../images/text-icon.png" alt="Text" title="Text" /></li>
            <li id="Shapes_Menu"><img src="../images/shapes-icon.png" alt="Shapes" title="Shapes" /></li>      
        </ul>
        
        <div style="float:left; margin-left:20px; margin-top:0px; display:none;" id="Picture_Library_Category_List">
             <select id="ddlCategroy" name="ddlCategroy" onChange="LoadImagemDetails(this.value)">  	
                <?php $Category->ListCategoryWithNumberOfImages();?>
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
        <div style="float:left; margin-left:20px;">
         	<select id="ddlSystemForWorkspace" name="ddlSystemForWorkspace">    	
                <?php $System->ListSystem();?>
            </select>
            
            <select name="ddlWidgetList" id="ddlWidgetList" style="height:28px;">
           		<?php $WidgetCategory->ListWidgetCategory();?>
           </select>
        </div>
        
        <div class="clear"></div>
        
     </div>
     
     <div id="WidgetDetailsByCategory" style="display:none; border-right:1px solid #CCCCCC; border-left:1px solid #CCCCCC; "></div>
    
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
  var kitchensink = { };
  var canvas = new fabric.Canvas('canvas');


  (function() {

    if (document.location.hash !== '#zoom') return;

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

    $(canvas.getElement().parentNode).on('wheel mousewheel', function(e) {

       var newZoom = canvas.getZoom() + e.originalEvent.wheelDelta / 300;
      canvas.zoomToPoint({ x: e.offsetX, y: e.offsetY }, newZoom);

      renderVieportBorders();

      return false;
    });
	
	
	
	
	
	



	
	
	
	
    var viewportLeft = 0,
        viewportTop = 0,
        mouseLeft,
        mouseTop,
        _drawSelection = canvas._drawSelection,
        isDown = false;
	
	

    canvas.on('mouse:down', function(options) {
      isDown = true;

      viewportLeft = canvas.viewportTransform[4];
      viewportTop = canvas.viewportTransform[5];

      mouseLeft = options.e.x;
      mouseTop = options.e.y;

      if (options.e.altKey) {
        _drawSelection = canvas._drawSelection;
        canvas._drawSelection = function(){ };
      }

      renderVieportBorders();
    });

    canvas.on('mouse:move', function(options) {
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

    canvas.on('mouse:up', function() {
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
    if(window.event){
        key = window.event.keyCode;
    }
    else{
        key = event.keyCode;
    }
   
	
    switch(key){
        //////////////
        // Shortcuts
        //////////////
        // Copy (Ctrl+C)
        case 67: // Ctrl+C
            if(ableToShortcut()){
                if(event.ctrlKey){
                    event.preventDefault();
                    copy();
                }
            }
            break;
        // Paste (Ctrl+V)
        case 86: // Ctrl+V
            if(ableToShortcut()){
                if(event.ctrlKey){
                    event.preventDefault();
                    paste();
                }
            }
            break;
		
		 // Delete
         case 46: // Ctrl+V
            if(ableToShortcut()){
               deleteActive();
            }
            break;
		            
        default:
            // TODO
			
            break;
    }
}


function ableToShortcut(){
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
      objectsInGroup.forEach(function(object) {
        canvas.remove(object);
      });
    }
    else if (activeObject) {
      canvas.remove(activeObject);
    }
}

function copy(){
    if(canvas.getActiveGroup()){
        for(var i in canvas.getActiveGroup().objects){
            var object = fabric.util.object.clone(canvas.getActiveGroup().objects[i]);
            object.set("top", object.top+5);
            object.set("left", object.left+5);
            copiedObjects[i] = object;
        }                    
    }
    else if(canvas.getActiveObject()){
        var object = fabric.util.object.clone(canvas.getActiveObject());
        object.set("top", object.top+5);
        object.set("left", object.left+5);
        copiedObject = object;
        copiedObjects = new Array();
    }
}

function paste(){
    if(copiedObjects.length > 0){
        for(var i in copiedObjects){
            canvas.add(copiedObjects[i]);
        }                    
    }
    else if(copiedObject){
        canvas.add(copiedObject);
    }
    canvas.renderAll();    
}


function ImportWidgetButtonFunc1()
{

var widget_objects_string = '{"objects":[{"type":"circle","originX":"left","originY":"top","left":142,"top":81,"width":100,"height":100,"fill":"transparent","stroke":"#58a91c","strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","radius":50,"startAngle":0,"endAngle":6.283185307179586},{"type":"rect","originX":"left","originY":"top","left":179,"top":155,"width":50,"height":50,"fill":"#1706b7","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","rx":0,"ry":0},{"type":"circle","originX":"left","originY":"top","left":152,"top":80,"width":100,"height":100,"fill":"#9a8ce4","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","radius":50,"startAngle":0,"endAngle":6.283185307179586}],"background":""}';

widget_objects_arr=JSON.parse(widget_objects_string);
var widget_objects_count=widget_objects_arr.objects.length;

for(var i=0; i<=widget_objects_count; i++)
{
	if(widget_objects_arr.objects[i].type=="circle")
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
		  strokeWidth:widget_objects_arr.objects[i].strokeWidth,
		  opacity: widget_objects_arr.objects[i].opacity
		}));
	}
	else if(widget_objects_arr.objects[i].type=="rect")
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
		  strokeWidth:widget_objects_arr.objects[i].strokeWidth,
		  opacity: widget_objects_arr.objects[i].opacity
		}));
	}
	
}

}


  (function(){
	var mainScriptEl = document.getElementById('main');
	if (!mainScriptEl) return;
	var preEl = document.createElement('pre');
	var codeEl = document.createElement('code');
	codeEl.innerHTML = mainScriptEl.innerHTML;
	codeEl.className = 'language-javascript';
	preEl.appendChild(codeEl);
	document.getElementById('bd-wrapper').appendChild(preEl);
  })();

(function() {
  fabric.util.addListener(fabric.window, 'load', function() {
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
    	<?php $Client->ListClient();?>
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
  
  
      
 
  
  
  
  </div>

	


  </body>
</html>
