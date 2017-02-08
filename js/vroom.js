//PSI meter..

$(document).ready(function() {
TweenLite.to(needle, 2, {rotation:-46,  transformOrigin:"bottom right"});

 	// select current content in input boxes on click
	$("input[type='text']").on("click", function () {
	   $(this).select();
	});

	//clear kilometers value when miles is selected
	$("#miles").focus(function(){
		$("#miles").val('');
	});

	// convert miles to kilometers
	$('#miles').keyup(function() {
		var mi = $(this).val();
		var miNum =  parseInt(mi);
		
		//make sure kmNum is a number then output
		if ( (mi < 1000) && !isNaN(miNum) ){
            var speedMi = mi*5.6/20 - 46;	
           //alert(speedMi);
	   } else if (!isNaN(miNum)){
		   //alert(mi); 
	   //alert(miNum);
	  // alert(speedMi);
	   		 var speedMi = 226;
	   			
	   } else { 
	   		
	   		$("#errmsg").html("Numbers Only").show().fadeOut(1600);
	   }
		var needle = $("#needle");    
		TweenLite.to(needle, 2, {rotation:speedMi,  transformOrigin:"bottom right"});
        var needle2 = $("#needle2");    
		TweenLite.to(needle2, 2, {rotation:speedMi,  transformOrigin:"bottom right"});
	});

	
});






//bar meter


$(document).ready(function() {
TweenLite.to(needle1, 2, {rotation:-46,  transformOrigin:"bottom right"});

 	// select current content in input boxes on click
	$("input[type='text']").on("click", function () {
	   $(this).select();
	});

	//clear kilometers value when miles is selected
	$("#bar").focus(function(){
		$("#bar").val('');
	});

	// convert miles to kilometers
	$('#bar').keyup(function() {
		var mi = $(this).val();
		var miNum =  parseInt(mi);
		
		//make sure kmNum is a number then output
		if ( (mi < 1000) && !isNaN(miNum) ){
            var speedMi = mi*5.6/6 - 46;	
           //alert(speedMi);
	   } else if (!isNaN(miNum)){
		   //alert(mi); 
	   //alert(miNum);
	  // alert(speedMi);
	   		 var speedMi = 226;
	   			
	   } else { 
	   		
	   		$("#errmsg").html("Numbers Only").show().fadeOut(1600);
	   }
		var needle1 = $("#needle1");    
		TweenLite.to(needle1, 2, {rotation:speedMi,  transformOrigin:"bottom right"});
	});

	
});