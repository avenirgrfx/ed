$(document).ready(function(){
             
//// loadyears();
 ////loadoutlaysrows(1);
 ////getconstructionsummary();
 
////updateAnnualRecurringCostBenefits();

//calculatetabdata();


 
 
 
$("#otisubmit").click(function(){
 var years_options=$("#years_options").val();            
 var years=$("#years").val();
 var rid = $("#oti_id").val();
 var description=$("#oti_description").val();
 var undiscounted_value=$("#oti_undiscounted_value").val();
 var project_id = $('#ddlBuildingProjects').val();
 if(years == "year" )
  {
    alert("Select A Year");
    return false;            
 }
 if(description == "" )
 {
    alert("Enter Description");
    return false; 
 }
 if(undiscounted_value == "")
 {
    alert("Enter Undiscounted Value");
    return false;
 }
 if(rid == 0)
 {
               $.ajax({
                url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                data:{year:years,description:description,undiscounted:undiscounted_value,project_id:project_id,rtype:"adddata"},
                
                type:"POST",
                
                success:function(data){
					
                 $('#success').html(data).fadeToggle(5000);
                 $('#success').html(data).fadeOut(5000);
                 $("#oti_description").val("");
                 $("#oti_undiscounted_value").val("");
                 $("#years").val("");
				 $('#years_options').append($('<option>', {value: years, text: years}));
				 $('#years_options').val(years);
				 $('#years_options').trigger('change');
				 $('#years_options').val(years);
                 //loadoutlaysrows(years);
               }
             });

        
 }
 else
 {
   $.ajax({
                url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                data:{year:years,description:description,undiscounted:undiscounted_value,project_id:project_id,rtype:"updatedata"},
                type:"POST",
                success:function(data){          
                  $("#success").html(data).fadeToggle(5000);
                  $("#success").html(data).fadeOut(5000);
                  loadoutlaysrows(years_options);
               }
             });            
        }
});

$("#years").change(function(){
               var year=$("#years").val();
			   var project_id = $('#ddlBuildingProjects').val();
                $.ajax({
                url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                data:{year:year,project_id:project_id,rtype:"checkyear"},
                type:"POST",
                dataType:"json",
                success:function(data){
                              if(!data.error){
                              $("#oti_description").val(data.description);
                              $("#oti_undiscounted_value").val(data.undiscounted_value);
                              $("#oti_id").val(data.id);
                              $("#otisubmit").val("EDIT");
                              }
                              else
                              {
                              $("#oti_id").val("0");
                              $("#oti_description").val(data.description);
                              $("#oti_undiscounted_value").val(data.undiscounted_value);
                              $("#otisubmit").val("ADD");
                              }
               }
             });
     });


$("#years_options").change(function(){
               //loadyears();
               var noy=$("#years_options").val();
               loadoutlaysrows(noy);
               an_capitalcost();
               
               calculatetabdata();
               

});

  
$("#futurevalue").keyup(function(){
   an_capitalcost();
   calculatetabdata();
    });

$("#an_ror").keyup(function(){
          an_capitalcost();
          calculatetabdata();
               });
$("#an_esc_factor").keyup(function(){
             calculatetabdata();
             });


$("#arnonsubmit").click(function(){
    var labor_exi_app = $("#labor_existing_app").val();
    var labor_Pro_app = $("#labor_pro_app").val();
    var main_exis_app = $("#main_existing_app").val();
    var main_Pro_app = $("#main_pro_app").val();
    var oth_cc_exis_app = $("#oth_cc_exis_app").val();
    var oth_cc_pro_app = $("#oth_cc_pro_app").val();
	var project_id = $('#ddlBuildingProjects').val();
    
    
    $.ajax({
                url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                data:{lexapp:labor_exi_app,lproapp:labor_Pro_app,mexapp:main_exis_app,mproapp:main_Pro_app,occexapp:oth_cc_exis_app,occproapp:oth_cc_pro_app,project_id:project_id,rtype:"annualrecurring"},
                
                type:"POST",
                
                success:function(data){
                 $('#result').html(data).fadeToggle(5000);
                 $('#result').html(data).fadeOut(5000);
                                      }
             });
   });



$(".netbenefit").keyup(function(){
 existingProApp();
 calculatetabdata();
 });
 
 
});


function existingProApp()
{
            
               
    var labor_exi_app = $("#labor_existing_app").val();
    if(labor_exi_app.trim() == ''){labor_exi_app=0};
     
    
    var labor_pro_app = $("#labor_pro_app").val();
    if(labor_pro_app.trim() ==''){labor_pro_app=0;}
    
    var labornetb = parseFloat(labor_exi_app)- parseFloat(labor_pro_app);
    
    var main_exis_app = $("#main_existing_app").val();
    if(main_exis_app.trim() ==''){main_exis_app=0;}
     
    var main_pro_app = $("#main_pro_app").val();
    if(main_pro_app.trim() ==''){main_pro_app=0;}
    
    var mainnetb = parseFloat(main_exis_app)-parseFloat(main_pro_app);
    
    var oth_cc_exis_app = $("#oth_cc_exis_app").val();
    if(oth_cc_exis_app.trim() ==''){oth_cc_exis_app=0;}
    
    var oth_cc_pro_app = $("#oth_cc_pro_app").val();
    if(oth_cc_pro_app.trim() ==''){oth_cc_pro_app=0;}
    
    var othccnetb = parseFloat(oth_cc_exis_app)- parseFloat(oth_cc_pro_app);
    
    var totalannualcost = parseFloat(labornetb)+ parseFloat(mainnetb)+ parseFloat(othccnetb);
    if(labornetb<0)
    {
             var labornetbws = -labornetb;
         $("#labornetb").html("-$"+labornetbws);    
    }
    else
    {    $("#labornetb").html("$"+labornetb);
    }
    
    if(mainnetb<0)
    {
            var mainnetbws = -mainnetb;
            $("#mainnetb").html("-$"+mainnetbws);
    }
    else
    {
       $("#mainnetb").html("$"+mainnetb);       
    }
   if(othccnetb<0)
   {
             var othccnetbws = - othccnetb;
             $("#othccnetb").html("-$"+othccnetbws);
   }
   else{
    $("#othccnetb").html("$"+othccnetb);
   }
   if(totalannualcost<0)
   {
             var totalannualcostws = - totalannualcost;
             $("#totalannualcost").html("-$"+totalannualcostws);
              $("#enplustotal").html("-$"+totalannualcostws);
   }
   else{
             $("#totalannualcost").html("$"+totalannualcost);
              $("#enplustotal").html("$"+totalannualcost);
   }
   
   
    
    
    
}

function an_capitalcost(){
    var totalun_value=$("#oti_amt_input").val();
    var construction_budget = $("#pcbtotalcost_input").val();
    var futurevalue = $("#futurevalue").val();
    if(futurevalue == '')futurevalue =0;
    var proposed_amt= parseInt(totalun_value) + parseInt(construction_budget) - parseInt(futurevalue);
    $("#un_tpi").html("$"+proposed_amt);
    $("#un_tpi_input").val(proposed_amt);
    var noy=$("#years_options").val();
    var ror=$("#an_ror").val();
	var project_id = $('#ddlBuildingProjects').val();
    
    $.ajax({
                   type:"post",
                   url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                   data:{years: noy,ror: ror ,un_tpi: proposed_amt,project_id:project_id,rtype:"calculatepmt"},
                   success:function(data){
                        
                      $("#capital_cost").html("$"+data);
                      $("#capital_cost_input").val(data);
                      
                   }
      });
    
               
}

function loadyears()
{  var project_id = $('#ddlBuildingProjects').val();
               $.ajax({
                   type:"post",
                   url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                   data:{project_id:project_id,rtype:"getyears"},
                   //dataType: "json",
				   async: true,
                   success:function(data){
                        
                      $("#years_options").html(data);
                      
                   }
      });
}
function loadoutlaysrows(noy)
{                                  $("#oti_row_container").html('');
                                   $("#oti_amt").html('');
                                   $("#oti_amt_input").val('0');
                                    $("#oti_amt_json_input").val('');
								var project_id = $('#ddlBuildingProjects').val();
               
                $.ajax({
                url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                data:{noy:noy,project_id:project_id,rtype:"getyearsdetails"},
                type:"POST",
                dataType: "json",
                async: true,
                success:function(data){
                             
                             if(!data.error)
                              {
                                   $("#oti_row_container").html(data.option_str);
                                   $("#oti_amt").html(data.total_amt);
                                   $("#oti_amt_input").val(data.total_amt);
                                   $("#oti_amt_json_input").val(data.total_amt_cs);
                              }
                              else
                              {
                                     $("#oti_row_container").html('');
                                   $("#oti_amt").html('');
                                   $("#oti_amt_input").val('0');
                                    $("#oti_amt_json_input").val('');
                              } 
              
               }
             });
     
}

function getconstructionsummary()
{
	var project_id = $('#ddlBuildingProjects').val();
	$("#energy_analysis").html("$0");
                 $("#feasibility_study").html("$0");
                 $("#development").html("$0");
                 $("#engineering").html("$0");
                 $("#pcbtotalcost").html("$0");
                 $("#pcbtotalcost_input").val("0");
	
               $.ajax({
               
               url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
               data:{project_id:project_id,rtype:"getconstructionsummary"},
               type:"post",
               dataType: "json",
               success:function(data)
               {
                 $("#energy_analysis").html("$"+data.ea);
                 $("#feasibility_study").html("$"+data.fs);
                 $("#development").html("$"+data.de);
                 $("#engineering").html("$"+data.en);
                 $("#pcbtotalcost").html("$"+data.pcbtotalcost);
                 $("#pcbtotalcost_input").val(data.pcbtotalcost);
               }
               });
}

function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
      return true;
}
function fetchCalculatedData(tblname,dcontainer,type)
			{
				var project_id = $('#ddlBuildingProjects').val();
                          //alert("hee");
				var cons_bugdet = $("#pcbtotalcost_input").val();
                               // alert(cons_bugdet);
				  cons_bugdet = parseFloat(cons_bugdet);
				  
				var esc_factor = $("#an_esc_factor").val();
				  if(esc_factor.trim() == '' ){esc_factor = 0;}
				    esc_factor = parseFloat(esc_factor);
				var years = $("#years_options").val();
                                if(years == null){years = 1;}
                                years = parseInt(years);
				
				var salvage = $("#futurevalue").val();
				if(salvage.trim() == '')salvage = 0;
                                
                                var labor_existing_app = $("#labor_existing_app").val();
				if(labor_existing_app.trim() == '')labor_existing_app = 0;
                                var main_existing_app = $("#main_existing_app").val();
				if(main_existing_app.trim() == '')main_existing_app = 0;
                                var oth_cc_exis_app = $("#oth_cc_exis_app").val();
				if(oth_cc_exis_app.trim() == '')oth_cc_exis_app = 0;
                                var labor_pro_app = $("#labor_pro_app").val();
				if(labor_pro_app.trim() == '')labor_pro_app = 0;
                                
                                
                                var main_pro_app = $("#main_pro_app").val();
				if(main_pro_app.trim() == '')main_pro_app = 0;
                                
                                var oth_cc_pro_app = $("#oth_cc_pro_app").val();
				if(oth_cc_pro_app.trim() == '')oth_cc_pro_app = 0;
                                var oti_amts_json = $("#oti_amt_json_input").val();
                                
                                
                                var an_ror =$("#an_ror").val();
                                if(an_ror.trim() == '')an_ror = 0;
                                
                                var an_cc =$("#capital_cost_input").val();
                                if(an_cc.trim() == '')an_cc = 0;
                                
                                var in_invest =$("#un_tpi_input").val();
                                if(in_invest.trim() == '')in_invest = 0;
                                
				
			    $.ajax({
			      method: "POST",
			      url: "http://54.201.91.181/ajax_pages/customers/pro_fin_risk.php",
			      data: {in_invest: in_invest,an_cc: an_cc,an_ror: an_ror, oti_amts_json: oti_amts_json,tbl: tblname,type: type,years: years,consbugdet: cons_bugdet,escfactor: esc_factor,salvage: salvage,cur_labour: labor_existing_app,cur_maintenance: main_existing_app,cur_other: oth_cc_exis_app,pro_labour: labor_pro_app,pro_maintenance: main_pro_app,pro_other: oth_cc_pro_app , uid: project_id },
			      dataType: "json",
                              async: true,
			      success: function(data){//alert(data);
                                       
                                       if(type == 'spb')
                                       {
				  $(".uo_cumulative_final").html(data.params.uo_cumulative_final); 
				 $(".us_cumulative_first").html(data.params.us_cumulative_first); 
				  $(".spb_result").html(data.result);
                                       }
                                       else if(type == 'roi')
                                       {
                                                    
                                           $(".us_annual_avg").html(data.params.us_annual_avg); 
				           $(".uo_cumulative_final").html(data.params.uo_cumulative_final); 
				           $(".roi_result").html(data.result);          
                                       }
                                       else if(type == "lcc")
                                       { //alert(data);
                                            $(".lcc_result").html(data.params.lcc_result);
                                            
                                          
                                           
                                       }
                                       else if(type == "npv")
                                       {
                                           $("#uc_cf_summation").html(data.params.uc_cf_summation); 
				           $("#uc_cf_zero").html(data.params.uc_cf_zero); 
				           $(".npv_result").html(data.result);            
                                       }
                                       else if(type == "irr")
                                       {
                                          $(".irr_result").html(data.result);           
                                       }
                                       else if(type == "con")
                                       {
                                                    $("#con_in_invest").html(in_invest);
                                                    $("#con_years").html(years);
                                                    $("#con_ror").html(an_ror);
                                                    $("#crf").html(data.params.crf);
                                          $(".annualized_tpi").html(data.params.annualized_tpi);
                                          $(".an_mmbtu_saved").html(data.params.an_mmbtu_saved);
                                          $(".an_cost_to_save_one_mmbtu").html(data.params.an_cost_to_save_one_mmbtu);
                                          $(".first_price_per_mmbtu").html(data.params.first_price_per_mmbtu);
                                          $(".cob_ratio").html(data.params.cob_ratio);
                                          $("#con_extra").html(data.params.con_extra);
                                          $("#con_extra2").html(data.params.con_extra2);
                                                  
                                       }
                                       else if(type == "bea")
                                       {
                                               
                                         $("#bea_years").html(years);
                                        
                                                    $("#bea_ror").html(an_ror);
                                                    $(".bea_crf").html(data.params.crf);
                                                    $(".an_mmbtu_saved").html(data.params.an_mmbtu_saved);
                                                    $(".price_per_mmbtu").html(data.params.price_per_mmbtu);
                                                    $(".max_acc_an_pc").html(data.params.max_acc_an_pc);
                                                    $(".total_savings").html(data.params.total_savings);
                                                    $(".e_mai").html(data.params.e_mai);
                                                    $(".eno_mai").html(data.params.eno_mai);
                                                    
                                       }
                                       
                                       else if(type == "risk")
                                       {
                                        
                                           // alert("asdfas");   
                                             $(".omit_exp").html(data.params.omit_exp);
                                             $(".omit_exp_per").html(data.params.omit_exp_per);
                                             
                                             $(".an_pc").html(data.params.an_pc);
                                             $(".an_pc_per").html(data.params.an_pc_per);
                                             
                                             $(".value_risk").html(data.params.value_risk);
                                             $(".value_risk_per").html(data.params.value_risk_per);
                                             
                                             $(".av_sum").html(data.params.av_sum);
                                             $(".av_sum_per").html(data.params.av_sum_per);
                                             $(".oav_sum").html(data.params.oav_sum);
                                             $(".oav_sum_per").html(data.params.oav_sum_per);
                                             $(".an_gain").html(data.params.an_gain);
                                             $(".red-column").css("height",data.params.red);
                                             $(".light-grey-column").css("height",data.params.gray);
                                             $(".dark-grey-column").css("height",data.params.dark);
                                       }
                                   $(dcontainer).html(data.tbl);
			      }
			});
			
		    }
                    
function updateAnnualRecurringCostBenefits()
{ 
                          $("#labor_existing_app").val('0');
                          $("#main_existing_app").val('0');
                          $("#oth_cc_exis_app").val('0');
                          
                          $("#labor_pro_app").val('0');
                          $("#main_pro_app").val('0');
                          $("#oth_cc_pro_app").val('0');
						  
						  $("#labornetb").html('0');
						  $("#mainnetb").html('0');
						  $("#othccnetb").html('0');
						  
						  $("#totalannualcost").html("$0");
                          $("#enplustotal").html("$0");
						  
          var project_id = $('#ddlBuildingProjects').val(); 
            $.ajax({
                url:"http://54.201.91.181/ajax_pages/customers/add_onetime_invest_outlays.php",
                data: {project_id:project_id,rtype: "fetchAnnualnetBenefit"},
                type:"post",
                dataType:"json",
                success:function(data){
                          $("#labor_existing_app").val(data.cur.lab);
                          $("#main_existing_app").val(data.cur.mai);
                          $("#oth_cc_exis_app").val(data.cur.oth);
                          
                          $("#labor_pro_app").val(data.pro.lab);
                          $("#main_pro_app").val(data.pro.mai);
                          $("#oth_cc_pro_app").val(data.pro.oth);
                          var netlabor = parseFloat(data.cur.lab) - parseFloat(data.pro.lab);
                          
                          $("#labornetb").html(netlabor);
                          var netmain = parseFloat(data.cur.mai) - parseFloat(data.pro.mai);
                          $("#mainnetb").html(netmain);
                          var netoth = parseFloat(data.cur.oth) - parseFloat(data.pro.oth);
                          $("#othccnetb").html(netoth);
                          
                          $("#totalannualcost").html("$" + (netlabor + netmain + netoth));
                          $("#enplustotal").html("$"+ (netlabor + netmain + netoth));
                      
                             
                              
               }
             });
    
}

function calculatetabdata()
{
             fetchCalculatedData("simplepayback","#project_spb_tab_content",'spb');
             fetchCalculatedData("return_on_investment","#project_roi_tab_content",'roi');
             fetchCalculatedData("lcc","#project_lcc_tab_content",'lcc');
             fetchCalculatedData("net_present_value","#project_npv_tab_content",'npv');
             fetchCalculatedData("internal_rate_of_return_irr","#project_irr_tab_content",'irr');
             fetchCalculatedData("con","#project_con_tab_content",'con');
             fetchCalculatedData("bea","#project_bea_tab_content",'bea');
             fetchCalculatedData("risk","#project_risk_tab_content",'risk');
             
}
$(window).load(function(){ });

