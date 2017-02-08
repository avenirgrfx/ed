<link rel="stylesheet" href="css/master.css">
<?php
require_once('configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$strSQL="Select * from t_project_details order by project_details_id desc";
$strRsProjectDetailsArr=$DB->Returns($strSQL);
if($strRsProjectDetails=mysql_fetch_object($strRsProjectDetailsArr))
{
	$json=$strRsProjectDetails->json_data;
}

/*$json='

{"objects":[{"type":"group","originX":"left","originY":"top","left":433,"top":94,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":46,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"0Â°F","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":82,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150006AT","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"group","originX":"left","originY":"top","left":171,"top":116,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":46,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"0Â°C","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":100,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150006ATEXT","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"image","originX":"left","originY":"top","left":610.61,"top":58.08,"width":257,"height":485,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":0.58,"scaleY":0.58,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/control-images/Widget%20Main%20Design.png","filters":[],"crossOrigin":""}],"background":""}



{"objects":[{"type":"group","originX":"left","originY":"top","left":78,"top":65,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":64,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"45Â°C","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":82,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150003AT","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"group","originX":"left","originY":"top","left":241,"top":68,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":40,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"0%","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":84,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150003AH","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"group","originX":"left","originY":"top","left":466,"top":76,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":58,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"47%","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":84,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150005AH","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"group","originX":"left","originY":"top","left":82,"top":241,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":55,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"10Â°C","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":100,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150005ATEXT","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"group","originX":"left","originY":"top","left":242,"top":242,"width":125,"height":136,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":true,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"objects":[{"type":"image","originX":"left","originY":"top","left":-62.5,"top":-53,"width":125,"height":121,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/widget_images/meter.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":-32.5,"top":-33,"width":49,"height":46.8,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":1,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"10%","fontSize":36,"fontWeight":"normal","fontFamily":"UsEnergyEngineersDigital","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":-62.5,"top":-68,"width":102,"height":15.6,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":1,"text":"WTHN150005AHEXT","fontSize":12,"fontWeight":"normal","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}]},{"type":"circle","originX":"left","originY":"top","left":416,"top":233.24,"width":100,"height":100,"fill":"#875bbf","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1.51,"scaleY":1.51,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"radius":50,"startAngle":0,"endAngle":6.283185307179586},{"type":"rect","originX":"left","originY":"top","left":464.43,"top":282.43,"width":50,"height":50,"fill":"#a6b30a","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1.16,"scaleY":1.16,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"rx":0,"ry":0},{"type":"image","originX":"left","originY":"top","left":613,"top":75,"width":162,"height":317,"fill":"rgb(0,0,0)","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1,"scaleY":1,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"src":"http://khwab.net/energydas/images/control-images/Thermocycler%20AHU%20TAC%20300.png","filters":[],"crossOrigin":""},{"type":"text","originX":"left","originY":"top","left":459,"top":388,"width":123,"height":52,"fill":"#333333","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":0.55,"scaleY":0.55,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"Shapes","fontSize":40,"fontWeight":"","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true},{"type":"text","originX":"left","originY":"top","left":621.94,"top":382,"width":103,"height":52,"fill":"#333333","stroke":null,"strokeWidth":1,"strokeDashArray":null,"strokeLineCap":"butt","strokeLineJoin":"miter","strokeMiterLimit":10,"scaleX":1.3,"scaleY":1.3,"angle":0,"flipX":false,"flipY":false,"opacity":1,"shadow":null,"visible":true,"clipTo":null,"backgroundColor":"","fillRule":"nonzero","globalCompositeOperation":"source-over","is_widget":false,"widget_value1":0,"widget_value2":0,"widget_value3":0,"widget_serial":false,"text":"Chiller","fontSize":40,"fontWeight":"","fontFamily":"UsEnergyEngineers","fontStyle":"","lineHeight":1.3,"textDecoration":"","textAlign":"left","path":null,"textBackgroundColor":"","useNative":true}],"background":""}

';*/

$arrJson=json_decode($json);

# Parsing First Level
foreach($arrJson as $Arr1)
{
	if(is_array($Arr1) && count($Arr1)>0)
	{
		# Parsing Group Level
		foreach($Arr1 as $Arr2)
		{
			
			$Widget_Value=$value_left=$value_top=$value_width=$value_height=$value_fontFamily=$value_fontSize='';
			$Widget_Serial=$Serial_left=$Serial_top=$Serial_width=$Serial_height='';
			$Widget_src=$Widget_left=$Widget_top=$Widget_width=$Widget_height='';
			
			if($Arr2->is_widget==1)
			{
				# Parsing Group Elements
				foreach($Arr2 as $Arr3)
				{				
					if(is_array($Arr3) && count($Arr3)>0)
					{
						foreach($Arr3 as $key=>$Arr4)
						{
							# Parsing Group Elements Properties
							
							
							if($Arr4->widget_value1==1)
							{
								$Widget_Value= $Arr4->text;
								$value_left=$Arr4->left;
								$value_top=$Arr4->top;
								$value_width=$Arr4->width;
								$value_height=$Arr4->height;
								$value_fontFamily=$Arr4->fontFamily;
								$value_fontSize=$Arr4->fontSize;
							}
							
							if($Arr4->widget_serial==1)
							{
								$Widget_Serial= $Arr4->text;
								$Serial_left=$Arr4->left;
								$Serial_top=$Arr4->top;
								$Serial_width=$Arr4->width;
								$Serial_height=$Arr4->height;
							}							
							
							if($Arr4->src<>'')
							{
								$Widget_src=$Arr4->src;
								$Widget_left=$Arr4->left;
								$Widget_top=$Arr4->top;
								$Widget_width=$Arr4->width;
								$Widget_height=$Arr4->height;
							}
							
							
							/*print "<pre>";
							print_r($Arr4);	
							print "</pre>";*/
							
						}
					}
					
				}
				
				
			}
			
			else
			{
				if($Arr2->type=='rect')
				{
					# Rectangle
					
				}
			}
			
			?>
            
            <div style=" position:relative; width:<?php echo $Widget_width?>; height:<?php echo $Widget_height?>; background-image:url(<?php echo $Widget_src;?>); font-size:<?php echo $value_fontSize?>; font-family:<?php echo $value_fontFamily;?>">
				
                <div style="left:30px; top:30px; position:absolute;">
					<?php echo $Widget_Value;?>
                </div>
            </div>

            
            <?php
			/*if($Widget_Serial && $Widget_Value)
			{
				print $Widget_Serial." = ".$Widget_Value." - $left : $top : $width : $height<br>";				
			}*/
			
			
			//print "<img src='$Widget_src' />";

		}
	}
}


print "<pre>";
print_r($arrJson);
print "</pre>";

?>



<div style="width:800px; height:450px; border:1px solid #CCCCCC; position:relative;">



</div>








