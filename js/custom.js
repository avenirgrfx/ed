
 // JavaScript Document
 
 
            $(document).ready(function () {
                $('#Gray_Button').click(function () {
					
                    //$('#Show_Dynamic_Sites').html('Buildings');
                    $('#Gray_Button').css('z-index', 1);
                    $('#Blue_Button').css('z-index', 0);
                    $('#project_active_container').css('display', 'block');
                    $('#project_closed_container').css('display', 'none');
					
                });

                $('#Blue_Button').click(function () {
                    //$('#Show_Dynamic_Sites').html('Elements');
                    $('#Blue_Button').css('z-index', 1);
                    $('#Gray_Button').css('z-index', 0);
                    $('#project_active_container').css('display', 'none');
                   $('#project_closed_container').css('display', 'block');

                });


 //  PROJECT PROFILE

 
                $('#summary').click(function () {
					
                    $('#summary').css('z-index', 4);
                    $('#ghg').css('z-index', 3);
                    $('#metrics').css('z-index', 2);
                    $('#energy').css('z-index', 1);					

                    $('#summary_container').css('display', 'block');
                    $('#ghg_container').css('display', 'none');
					$('#metrics_container').css('display', 'none');
					$('#energy_container').css('display', 'none');
					
                });

                $('#ghg').click(function () {
					
                    $('#summary').css('z-index', 3);
                    $('#ghg').css('z-index', 4);
                    $('#metrics').css('z-index', 2);
                    $('#energy').css('z-index', 1);					

                    $('#summary_container').css('display', 'none');
                    $('#ghg_container').css('display', 'block');
					$('#metrics_container').css('display', 'none');
					$('#energy_container').css('display', 'none');
					
                });
				
                $('#metrics').click(function () {
					
                    $('#summary').css('z-index', 2);
                    $('#ghg').css('z-index', 3);
                    $('#metrics').css('z-index', 4);
                    $('#energy').css('z-index', 1);					

                    $('#summary_container').css('display', 'none');
                    $('#ghg_container').css('display', 'none');
					$('#metrics_container').css('display', 'block');
					$('#energy_container').css('display', 'none');
					
                });
				
                $('#energy').click(function () {
					
                    $('#summary').css('z-index', 2);
                    $('#ghg').css('z-index', 1);
                    $('#metrics').css('z-index', 3);
                    $('#energy').css('z-index', 4);					

                    $('#summary_container').css('display', 'none');
                    $('#ghg_container').css('display', 'none');
					$('#metrics_container').css('display', 'none');
					$('#energy_container').css('display', 'block');
					
                });				
					
 //  Active Building Project 					
		//	function fetchCalculatedData(tblname,dcontainer,type)
		//	{
		//		var cons_bugdet = $("#pcbtotalcost_input").val();
		//		  cons_bugdet = parseInt(cons_bugdet);
		//		  
		//		var esc_factor = $("#an_esc_factor").val();
		//		  if(esc_factor.trim() == '' ){esc_factor = 0;}
		//		    esc_factor = parseInt(esc_factor);
		//		var years = $("#years_options").val();
		//		
		//		var salvage = $("#futurevalue").val();
		//		if(salvage.trim() == '')salvage = 0;
		//		
		//	    $.ajax({
		//	      method: "POST",
		//	      url: "pro_fin_risk.php",
		//	      data: { tbl: tblname,type: type,years: years,consbugdet: cons_bugdet,escfactor: esc_factor,salvage: salvage, uid: "1" },
		//		  dataType: "json",
		//	      success: function(data){	
		//		  $(".uo_cumulative_final").html(data.params.uo_cumulative_final); 
		//		  $(".us_cumulative_first").html(data.params.us_cumulative_first); 
		//		  $("#spb_result").html(data.result); 
		//	          $(dcontainer).html(data.tbl);
		//	      }
		//	});
		//	
		//    }
		    
                $('.project_summary_tab_container').click(function () {
					 fetchCalculatedData("summary","#project_summary_tab_content",'summary');
                    $('#project_summary_tab_container').css('display', 'block');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });
	
                $('.project_spb_tab_container').click(function () {
					
			   fetchCalculatedData("simplepayback","#project_spb_tab_content",'spb');
			   
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'block');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });	
	
	
                $('.project_roi_tab_container').click(function () {
		   
					fetchCalculatedData("return_on_investment","#project_roi_tab_content",'roi');
					 
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'block');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });						


                $('.project_lcc_tab_container').click(function () {
					fetchCalculatedData("lcc","#project_lcc_tab_content",'lcc');
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'block');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });							

                $('.project_npv_tab_container').click(function () {
					fetchCalculatedData("net_present_value","#project_npv_tab_content",'npv');
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'block');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });							

                $('.project_irr_tab_container').click(function () {
					fetchCalculatedData("internal_rate_of_return_irr","#project_irr_tab_content",'irr');
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'block');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });		


                $('.project_con_tab_container').click(function () {
			fetchCalculatedData("con","#project_con_tab_content",'con');
			
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'block');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'none');
					
                });		


                $('.project_risk_tab_container').click(function () {
			fetchCalculatedData("risk","#project_risk_tab_content",'risk');		
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'block');
					$('#project_bea_tab_container').css('display', 'none');
					
                });		
				
                $('.project_bea_tab_container').click(function () {
		    fetchCalculatedData("bea","#project_bea_tab_content",'bea');
				
				
                    $('#project_summary_tab_container').css('display', 'none');
                    $('#project_spb_tab_container').css('display', 'none');
					$('#project_roi_tab_container').css('display', 'none');
					$('#project_lcc_tab_container').css('display', 'none');
					$('#project_npv_tab_container').css('display', 'none');
					$('#project_irr_tab_container').css('display', 'none');
					$('#project_con_tab_container').css('display', 'none');
					$('#project_risk_tab_container').css('display', 'none');
					$('#project_bea_tab_container').css('display', 'block');
					
                });						
				
                $('#ddlBenchMarkBuilding').change(function () {
                    var selectedBuilding = $('#ddlBenchMarkBuilding').val();
                    //$("#ddlBuildingForSite").val(selectedBuilding);
                    //$("#ddlBuildingForSite").val(selectedBuilding);
                });


 //  Active Building Project Tab button 	


                $('#project_spb_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_spb_tab').removeClass('benchmark_button');
                    $('#project_spb_tab').addClass('benchmark_button_active');
                    $('#project_spb_tab').css('background-color','#526D9A');
					$('#project_con_tab').css('background-color','#99999a');	
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_roi_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');
					$('#project_bea_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');				
				
				});		

				
                $('#project_roi_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_roi_tab').removeClass('benchmark_button');
                    $('#project_roi_tab').addClass('benchmark_button_active');
                    $('#project_roi_tab').css('background-color','#526D9A');
					$('#project_con_tab').css('background-color','#99999a');	
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');
					$('#project_spb_tab').css('background-color','#99999a');
					$('#project_bea_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');				
				
				});		
				
				
                $('#project_lcc_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_lcc_tab').removeClass('benchmark_button');
                    $('#project_lcc_tab').addClass('benchmark_button_active');
                    $('#project_lcc_tab').css('background-color','#526D9A');
					$('#project_spb_tab').css('background-color','#99999a');	
					$('#project_con_tab').css('background-color','#99999a');
					$('#project_roi_tab').css('background-color','#99999a');
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');
					$('#project_bea_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');				
				
				});	
				
						
                $('#project_npv_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_npv_tab').removeClass('benchmark_button');
                    $('#project_npv_tab').addClass('benchmark_button_active');
                    $('#project_npv_tab').css('background-color','#526D9A');
					$('#project_spb_tab').css('background-color','#99999a');	
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');
					$('#project_roi_tab').css('background-color','#99999a');						
					$('#project_con_tab').css('background-color','#99999a');
					$('#project_bea_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');			
				
				});		
				
				
                $('#project_irr_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_irr_tab').removeClass('benchmark_button');
                    $('#project_irr_tab').addClass('benchmark_button_active');
                    $('#project_irr_tab').css('background-color','#526D9A');
					$('#project_spb_tab').css('background-color','#99999a');	
					$('#project_roi_tab').css('background-color','#99999a');						
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_con_tab').css('background-color','#99999a');
					$('#project_bea_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');					
				
				});
				
				
                $('#project_con_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_con_tab').removeClass('benchmark_button');
                    $('#project_con_tab').addClass('benchmark_button_active');
                    $('#project_con_tab').css('background-color','#526D9A');
					$('#project_spb_tab').css('background-color','#99999a');	
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_roi_tab').css('background-color','#99999a');						
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');
					$('#project_bea_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');				
				
				});		
				
				
                $('#project_risk_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_risk_tab').removeClass('benchmark_button');
                    $('#project_risk_tab').addClass('benchmark_button_active');
                    $('#project_risk_tab').css('background-color','#526D9A');
					$('#project_spb_tab').css('background-color','#99999a');	
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');					
					$('#project_con_tab').css('background-color','#99999a');
					$('#project_nvp_tab').css('background-color','#99999a');		
					$('#project_roi_tab').css('background-color','#99999a');								
					$('#project_bea_tab').css('background-color','#99999a');			
				
				});		
				
													
                $('#project_bea_tab').click(function () { 
                    $('#project_summary_tab').removeClass('benchmark_button_active');
                    $('#project_summary_tab').addClass('benchmark_button');
                    $('#project_summary_tab').css('background-color','#99999a');	
									
                    $('#project_bea_tab').removeClass('benchmark_button');
                    $('#project_bea_tab').addClass('benchmark_button_active');
                    $('#project_bea_tab').css('background-color','#526D9A');
					$('#project_spb_tab').css('background-color','#99999a');	
					$('#project_lcc_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');					
					$('#project_roi_tab').css('background-color','#99999a');						
					$('#project_con_tab').css('background-color','#99999a');
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');				
				
				});			
				

                $('#project_summary_tab').click(function () {

                    $('#project_summary_tab').removeClass('benchmark_button');
                    $('#project_summary_tab').addClass('benchmark_button_active');
                    $('#project_summary_tab').css('background-color','#526D9A');
                    $('#project_spb_tab').css('background-color','#99999a');
		    $('#project_lcc_tab').css('background-color','#99999a');
		    $('#project_roi_tab').css('background-color','#99999a');
                    $('#project_bea_tab').css('background-color','#99999a');
		    $('#project_spb_tab').css('background-color','#99999a');	
		    $('#project_lcc_tab').css('background-color','#99999a');
					$('#project_irr_tab').css('background-color','#99999a');						
					$('#project_npv_tab').css('background-color','#99999a');
					$('#project_bpb_tab').css('background-color','#99999a');
					$('#project_con_tab').css('background-color','#99999a');
					$('#project_risk_tab').css('background-color','#99999a');						

                });

            });
            
			
								
									
			
            var Month_To_Date_Electric_Consumption="";
            var Last_Month_Electric_Consumption="";
            var Month_To_Date_NaturalGas_Consumption="";
            var Last_Month_NaturalGas_Consumption="";
            
            function ChangeSiteDropdown(site_id){              
                Month_To_Date_Electric_Consumption="";
                Last_Month_Electric_Consumption="";
                Month_To_Date_NaturalGas_Consumption="";
                Last_Month_NaturalGas_Consumption="";
                
                $.get("http://www.energydas.com/ajax_pages/customers/dynamic_building_name_new.php",
                        {
                            site_id: site_id
                        },
                function (data, status) {
                    $('#Show_Dynamic_Buildings').html(data);
                    //ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    //UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(), 0);
                    //UpdateBuildingElementDetails($('#ddlBuildingForSite').val(), 0);
                    $.get("http://www.energydas.com/ajax_pages/customers/building_details.php",
                            {
                                building_id: $('#ddlBuildingForSite').val()
                            },
                    function (data, status) {
                        $('#Building_Details_Container').html(data);
                        $('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                        $('#ddlFilterElectric_Gas').val('1');
                        ChangeBuildingDropdown($('#ddlBuildingForSite').val());
                    });
                });
            }

	

						


            function UpdateBuildingElementDetails(strBuildingID, UpdateOtherBuildingDropDown)
            {
                $.get("http://www.energydas.com/ajax_pages/customers/building_details.php",
                        {
                            building_id: strBuildingID
                        },
                function (data, status) {
                    $('#Building_Details_Container').html(data);
                    $('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                    $('#ddlFilterElectric_Gas').val('1');
                    if (UpdateOtherBuildingDropDown == 1)
                    {
                        UpdateAllBuildingDropdown(strBuildingID);
                    }
                });
            }

            //GrayButtonClickLoad();

            function UpdateAllBuildingDropdown(strBuildingID)
            {
                $("#ddlBuildingForSite").val(strBuildingID);
                $("#ddlBenchMarkBuilding").val(strBuildingID);
                $("#ddlSiteSummaryBuilding").val(strBuildingID);
                $("#ddlConsumptionBuilding").val(strBuildingID);
                $("#ddlBuildingElemntsList").val(strBuildingID);
                UpdateBuildingElementDetails(strBuildingID, 0);

                $('#ddlFilterElectric_Gas').trigger('change');
                $('#ddlMetricsType').trigger('change');
            }

            function ChangeBuildingDropdown(strBuildingID)
            {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined;
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                
                $("#ddlBenchMarkBuilding").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBenchMarkBuilding');
                $("#ddlBenchMarkBuilding").val(strBuildingID);


                $("#ddlBuildingElemntsList").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
                $("#ddlBuildingElemntsList").val(strBuildingID);


                $("#ddlSiteSummaryBuilding").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlSiteSummaryBuilding');
                $("#ddlSiteSummaryBuilding").val(strBuildingID);

                $("#ddlConsumptionBuilding").empty();
                $('#ddlBuildingForSite option').clone().appendTo('#ddlConsumptionBuilding');
                $("#ddlConsumptionBuilding").val(strBuildingID);
                
                $.get("http://www.energydas.com/ajax_pages/customers/system_list_by_building_electric_system.php",
                    {
                        building_id: strBuildingID,
                        type: 2,
                        month: month,
                        year: year
                    },
                function (data, status) {
                    $('#Consumption_Electric_System').html(data);
                });

                $('#Container_SystemsByBuilding').html('Loading...');
                $.get("http://www.energydas.com/ajax_pages/customers/system_list_by_building_child_system.php",
                    {
                        building_id: strBuildingID
                    },
                function (data, status) {
                    $('#Container_SystemsByBuilding').html(data);
                });

                $('#Building_BenchMark_Container').html('Loading....');
                $.get("http://www.energydas.com/ajax_pages/customers/building_benchmark_eui.php", {building_id: strBuildingID}, function (data) {
                    $('#Building_BenchMark_Container').html(data);
                });
                
                $('#consumption_chart_container').html('Loading...');
                $.get("http://www.energydas.com/ajax_pages/customers/consumption_chart.php",
                        {
                            building_id: strBuildingID,
                            type: 1,
                            month: month,
                            year: year
                        },
                function (data, status) {
                    $('#consumption_chart_container').html(data);
                });
                
                $('#Consumption_Electric_System').html('Loading...');
                
                $.get("http://www.energydas.com/ajax_pages/customers/system_list_by_building_electric_system.php",
                        {
                            building_id: strBuildingID,
                            type: 1,
                            month: month,
                            year: year
                        },
                function (data, status) {
                    $('#Consumption_Electric_System').html(data);
                    var strType = 1;
                    if (strType == 1)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Electric');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 kWh');
                        $('#Main_Utility_Electric_Gas_Label').html('Electric Disconnect');
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 kWh');
                    }
                    else if (strType == 2)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Natural Gas');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 Therms');
                        $('#Main_Utility_Electric_Gas_Label').html("Main's Natural Gas");
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 Therms');
                    }
                });
                
                $('#Site_Details_Summary_Button').trigger('click');
            }


            function SwitchElectricGasSystem(strType)
            {
                if (strType == 1)
                {
                    $('#graph_header_title').html('Electrical Systems Consumption (MMBTU)');
                } 
                else if (strType == 2)
                {
                    $('#graph_header_title').html('Natural Gas Consumption (MMBTU)');
                }
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val() ? parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1 : undefined;
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $('#Consumption_Electric_System').html('Loading...');
                $.get("http://www.energydas.com/ajax_pages/customers/system_list_by_building_electric_system.php",
                {
                    building_id: $('#ddlBuildingForSite').val(),
                    type: strType,
                    month: month,
                    year: year
                },
                function (data, status) {
                    $('#Consumption_Electric_System').html(data);

                    if (strType == 1)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Electric');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 kWh');
                        $('#Main_Utility_Electric_Gas_Label').html('Electric Disconnect');
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 kWh');
                    } 
                    else if (strType == 2)
                    {
                        $('#Total_Electric_Gas_Label').html('Metered Natural Gas');
                        //$('#Total_Electric_Gas_Value').html('11,181,865 Therms');
                        $('#Main_Utility_Electric_Gas_Label').html("Main's Natural Gas");
                        //$('#Main_Utility_Electric_Gas_Value').html('181,865 Therms');
                    }

                });
                
                $('#consumption_chart_container').html('Loading...');
                $.get("http://www.energydas.com/ajax_pages/customers/consumption_chart.php",
                        {
                            building_id: $('#ddlBuildingForSite').val(),
                            type: strType,
                            month: month,
                            year: year
                        },
                function (data, status) {
                    $('#consumption_chart_container').html(data);
                });
            }
            function showBuildingSystemChild(strParentSystemID, strBuildingID)
            {
                //$('#'+strParentSystemID+'_content').html(strParentSystemID);
                $('#' + strParentSystemID + '_content').html('Loading...');


                $.get("http://www.energydas.com/ajax_pages/customers/system_list_by_building_child_system.php",
                        {
                            parent_id: strParentSystemID,
                            building_id: strBuildingID
                        },
                function (data, status) {
                    $('#' + strParentSystemID + '_content').html(data);
                });
            }

            function Expand_Collapse_System_Node_For_Building(strSystemID)
            {
                if ($('.System_ID_' + strSystemID).css('display') == 'none')
                {
                    $('.System_ID_' + strSystemID).slideDown('slow');
                    $('.System_ID_Expand_' + strSystemID).html('-');

                    //$('.System_ID_'+strSystemID).css('display','block');
                }
                else
                {
                    //$('.System_ID_'+strSystemID).css('display','none');
                    $('.System_ID_' + strSystemID).slideUp('slow');
                    $('.System_ID_Sub_' + strSystemID).slideUp('slow');
                    $('.System_ID_Expand_' + strSystemID).html('+');
                }
                //$('.noclick').attr('onclick','').unbind('click');
            }

            function showPopup(){
                var drop_value = $('#ddlFilterElectric_Gas').val();
                $('#popup').html($('#consumption_systems').html());
                $('#consumption_systems').html("");
                $('#ddlFilterElectric_Gas').val(drop_value);
                $('.popup_button').hide();
                $('.close_btn').show();
                $('body').css("overflow","hidden");
                $('.popup_w').show();
            }
            
            function close_popup(){   
                var drop_value = $('#ddlFilterElectric_Gas').val();
                $('#consumption_systems').html($('#popup').html());
                $('#popup').html("");
                $('#ddlFilterElectric_Gas').val(drop_value);
                $('.popup_button').show();
                $('.close_btn').hide();
                $('body').css("overflow","auto");
                $('.popup_w').hide();
            }
            
            function viewBuilding(){
                //window.location.href = "http://www.energydas.com/customer/systems.php";
            }


       
        $(document).ready(function(){
          //  $('#site_utility1.switch_button input').switchButton({on_label:'ADJUSTED FOR UTILITY', off_label:'ACTUAL'});
           //$('#site_utility2.switch_button input').switchButton({on_label:'ADJUSTED FOR UTILITY', off_label:'ACTUAL'});
            
            $('#chkSite_utility1').change(function()
            {
                if($('#chkSite_utility1').is(':checked')){
                    $('#chkSite_utility2').prop("checked", true);
                    $($('#site_utility2 div')[0]).addClass('checked');
                    $($('#site_utility2 div')[1]).css('left', '12px');
                    $($('#site_utility2 span')[0]).removeClass('on');
                    $($('#site_utility2 span')[0]).addClass('off');
                    $($('#site_utility2 span')[1]).removeClass('off');
                    $($('#site_utility2 span')[1]).addClass('on');
                }else{
                    $('#chkSite_utility2').prop("checked", false);
                    $($('#site_utility2 div')[0]).removeClass('checked');
                    $($('#site_utility2 div')[1]).css('left', '-1px');
                    $($('#site_utility2 span')[0]).removeClass('off');
                    $($('#site_utility2 span')[0]).addClass('on');
                    $($('#site_utility2 span')[1]).removeClass('on');
                    $($('#site_utility2 span')[1]).addClass('off');
                }
                
                ChangeBuildingDropdown($('#ddlBuildingForSite').val());
            });
            
            $('#chkSite_utility2').change(function()
            {
                if($('#chkSite_utility2').is(':checked')){
                    $('#chkSite_utility1').prop("checked", true);
                    $($('#site_utility1 div')[0]).addClass('checked');
                    $($('#site_utility1 div')[1]).css('left', '12px');
                    $($('#site_utility1 span')[0]).removeClass('on');
                    $($('#site_utility1 span')[0]).addClass('off');
                    $($('#site_utility1 span')[1]).removeClass('off');
                    $($('#site_utility1 span')[1]).addClass('on');
                }else{
                    $('#chkSite_utility1').prop("checked", false);
                    $($('#site_utility1 div')[0]).removeClass('checked');
                    $($('#site_utility1 div')[1]).css('left', '-1px');
                    $($('#site_utility1 span')[0]).removeClass('off');
                    $($('#site_utility1 span')[0]).addClass('on');
                    $($('#site_utility1 span')[1]).removeClass('on');
                    $($('#site_utility1 span')[1]).addClass('off');
                }
                
                ChangeBuildingDropdown($('#ddlBuildingForSite').val());
            });
        });
	
	    
       
     