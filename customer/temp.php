<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<script type="text/javascript">
var widget_objects_string='<?php echo $json ?>';

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
	else if(widget_objects_arr.objects[i].type=="text")
	{
		// Place all Text		
		canvas.add(new fabric.Text(
			widget_objects_arr.objects[i].text,
			{
				fontSize:widget_objects_arr.objects[i].fontSize,
				left:widget_objects_arr.objects[i].left,
				top:widget_objects_arr.objects[i].top,
				fontFamily:widget_objects_arr.objects[i].fontFamily
			}
			));
	}
	
	else if(widget_objects_arr.objects[i].type=="image")
	{
		// Place all Images		
		var leftVal=widget_objects_arr.objects[i].left;
		var topVal=widget_objects_arr.objects[i].top;
		var angle=widget_objects_arr.objects[i].angle;		
        var widthVal=widget_objects_arr.objects[i].width;
		var heightVal=widget_objects_arr.objects[i].height;		
		fabric.Image.fromURL(widget_objects_arr.objects[i].src,  function(img) 
		{
			img.set({		
			left: leftVal,
			top: topVal,		
			width: widthVal,
			height: heightVal,
		  	});
		  canvas.add(img);
		});		
	}
	
	else if(widget_objects_arr.objects[i].type=="group")
	{
		var GroupElement=widget_objects_arr.objects[i].objects.length;
		
		var imageName=widget_objects_arr.objects[i].objects[0].src;
		var leftVal1=widget_objects_arr.objects[i].objects[0].left;
		var topVal1=widget_objects_arr.objects[i].objects[0].top;
		
		var textVal2=widget_objects_arr.objects[i].objects[1].text;
		var leftVal2=widget_objects_arr.objects[i].objects[1].left;
		var topVal2=widget_objects_arr.objects[i].objects[1].top;
		
		fabric.Image.fromURL(imageName, function(image) {

			var img1= image.set({ left: leftVal1, top: topVal1 });	  
			
			var text1 = new fabric.Text(textVal2, {
				fontSize: 36,  left: leftVal2, top: topVal2, fontFamily:'UsEnergyEngineersDigital', widget_value1:1
			});
 

			var text2=new fabric.Text("fdgdfgdfg",{
				fontSize:12, left:20, top:25, fontFamily:'UsEnergyEngineers', widget_serial:1
			});

			var group = new fabric.Group([ img1, text1, text2 ], {
				left: 100, top: 150, is_widget: true
			});

			
			canvas.add(group);
		
			
				
  
			});
			updateModifications(true);
			
			
			
			
			
			//updateModifications(true);
		
		
		
		
	}
	
	
}
</script>

</head>

<body>
</body>
</html>
