$(document).ready(function(){
 
});





function calculatetotal() {
        var cost=[];
 cost[0] = calculatesubtotal("in");
 cost[1] = calculatesubtotal("an");
 cost[2] = calculatesubtotal("pe");
 return cost;
}
function calculatesubtotal(ctype)
{
   var subsum =[];
   var ea = ["au","eam","ele","com_air","eao","bms","ece","eei","ec","ipr","tna","ud","con","idc"];  
   var fs  = ["si","ra","ea","pd","dce","ghg_bsm","rp","pm","ta","ud"];  
   var de  = ["cn","pa","ss_lr","ghg_vr","pf","la","pm","ta","ud"]; 
   var en   =["sbd","md","ed","cd","tc","cs","ud"];  
  subsum[0]=calculatecost(ea,ctype,"ea");
  subsum[1]=calculatecost(fs,ctype,"fs");
  subsum[2]=calculatecost(de,ctype,"de");
  subsum[3]=calculatecost(en,ctype,"en");
    
               
  return subsum   ;          
}

function calculatecost(arr,ctyp,subtyp) {
            var subcost = 0;
             
    $.each( arr, function( index, value ){    
    selid = ctyp+"_"+subtyp+"_"+value;
   
    qty = $("#"+selid+"_qty").val();
    
    if(qty == '')qty =0;
    
    uc =$("#"+selid+"_uc").val();
    
  //uc = ucwithdollar.substring(3,ucwithdollar.length);
   if(uc.trim() == '')uc =0;
   //alert(uc);
    subcost += parseInt(qty)*parseInt(uc);
    //alert(subcost);
});
    
    return subcost;
}

function submitresponse(responseText, statusText, xhr, $form)
{
        
}