<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');
require_once(AbsPath."classes/customer.class.php");

$DB=new DB;
$Category=new Category;
$System=new System;
$Gallery=new Gallery;
$Client = new Client;

$_SESSION['user_login']->user_id=1;
$_SESSION['user_login']->login_id=1;
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
		$(document).ready(function(){
			$('#Administrator_Main_Menu').click(function(){
				window.location='<?php echo URL?>';
			});
			
			$('#showNewControl').click(function(){
				window.location='<?php echo URL?>';
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
			});
			
			$('#Controls_Menu').click(function(){
				$('#Add_Text_Div').css('display','none');				
				$('#Picture_Library_Category_List').css('display','none');
				$('#dynamic_image').css('display','none');
				$('#Add_Shapes_Div').css('display','none');
				$('#Add_Control_Div').css('display','block');
				$('#Controls_Menu').css('background-color','#EFEFEF');
				$('#Text_Menu').css('background','none');
				$('#Shapes_Menu').css('background','none');
				$('#Picture_Library_Menu').css('background','none');										 
			}); 
			
		});
				
 
		
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
    	<li id="Projects_Menu" class="LargeMenu" style="margin-right:30px;">Projects</li>
      	<li id="" style="margin-right:30px;" class="active LargeMenu">Workspace</li>
        <li id="" style="margin-right:30px;" class="LargeMenu">Widget</li>
        <li id="" style="margin-right:30px;" class="LargeMenu">Application</li>
     </ul>
     
     <div class="clear"></div>
     
  </div>
  
  <div id="Menu" style="border-top:1px solid #EFEFEF;">
  		<ul class="System_Menu">
    		<li id="showNewControl" class="active">Control Workspace</li>
        	<li id="showControlOperation">Control Operation</li>
        	<li id="showApplyControl">Apply Control</li>
        </ul>
        
        <div class="clear"></div>
  </div>
  
  
  <div class="BottomMenu_1" id="">
  	<ul class="Projects_Menu">
  		<li id="Picture_Library_Menu"><img src="../images/picture-library-icon.png" alt="Picture Library" title="Picture Library" /></li>
        <li id="Text_Menu" ng-click="addText()"><img src="../images/text-icon.png" alt="Text" title="Text" /></li>
        <li id="Shapes_Menu"><img src="../images/shapes-icon.png" alt="Shapes" title="Shapes" /></li>
        <li id="Controls_Menu"><img src="../images/controls-icon.png" alt="Controls" title="Controls" /></li> 
    </ul>
    
    <div style="float:left; margin-left:20px; margin-top:0px; display:none;" id="Picture_Library_Category_List">
    	 <select id="ddlCategroy" name="ddlCategroy" onChange="LoadImagemDetails(this.value)">    	
            <?php $Category->ListCategoryWithNumberOfImages();?>
         </select>
    </div>
    
    
    
    
    
    <div class="clear"></div>
  </div>
  
  
  <div id="dynamic_image"></div>
  
  
  
  <div style="margin-top:0px; display:none; border:1px solid #CCCCCC; padding:5px;" id="Add_Shapes_Div">
    <button type="button" class="btn rect" ng-click="addRect()"><img src="../images/rectangle-icon.png" alt="Rectangle" title="Rectangle" /></button>
    <button type="button" class="btn rect" ng-click="addRectStroke()"><img src="../images/rectangle-only-stroke-icon.png" alt="Rectangle without Fill" title="Rectangle without Fill" /></button>
    
    <button type="button" class="btn circle" ng-click="addCircle()"><img src="../images/circle-icon.png" alt="Circle" title="Circle" /></button>
    <button type="button" class="btn circle" ng-click="addCircleStroke()"><img src="../images/circle-only-stroke-icon.png" alt="Circle" title="Circle" /></button>
    <button type="button" class="btn triangle" ng-click="addTriangle()"><img src="../images/triangle-icon.png" alt="Triangle" title="Triangle" /></button>
    <button type="button" class="btn line" ng-click="addLine()"><img src="../images/line-icon.png" alt="Line" title="Line" /></button>
    <button type="button" class="btn polygon" ng-click="addPolygon()"><img src="../images/polygon-icon.png" alt="Polygon" title="Polygon" /></button>
  </div>
  
  
  
<div id="bd-wrapper">
    <div style="float:left; margin:10px 0px;">
    	 <div style="float:left;"><input name="txtControlName" id="txtControlName" type="text" placeholder="Control Workspace" /></div>
         <div style="float:left; margin-left:5px;"><button class="btn btn-success" id="rasterize-json" ng-click="rasterizeJSON()">Save</button></div>
         
         
         <div style="float:left; margin-left:10px;"><input name="txtWidgetControlName" id="txtWidgetControlName" type="text" placeholder="Control Widget" /></div>
         <div style="float:left; margin-left:5px;"><button class="btn btn-success" id="rasterize-widget-json" ng-click="rasterizeWidget()">Save</button></div>
         
         <select name="ddlWidget" id="ddlWidget">
         		<option value="">Select</option>
         	<?php
				$strSQL="Select * from t_project_widget order by  project_widget_name";
				$strRsWidgetsArr=$DB->Returns($strSQL);
				while($strRsWidgets=mysql_fetch_object($strRsWidgetsArr))
				{
            ?>
         		<option value="<?php echo $strRsWidgets->project_widget_id; ?>"><?php echo $strRsWidgets->project_widget_name; ?></option>
            <?php }?>
         </select>
         
         <div class="clear"></div>
    </div>
     <div class="clear"></div>
    
    
<div style="position:relative; width:800px; float:left;" id="canvas-wrapper">
  <canvas id="canvas" width="800" height="500"></canvas>  
</div>

<div style="float:left; width:230px; margin-left:10px;">
	
    
    <div style="border:1px solid #CCCCCC; padding:5px;">
    	<button class="btn btn-danger clear" ng-click="confirmClear()" style="float:left;">Clear canvas</button>
        <button type="button" id="ImportWidgetButton"  onClick="LoadWidgetByJson()" >Import</button>
        <input type="button" value="undo" onClick="undo()">
<input type="button" value="redo" onClick="redo()">
        
        <div id="color-opacity-controls" ng-show="canvas.getActiveObject()">             
            <label for="color" style="margin-left:10px">Color: </label>
            <input type="color" style="width:40px" bind-value-to="fill">
            <label for="opacity">Opacity: </label>
    		<input value="100" type="range" bind-value-to="opacity">
            
            <label for="text-stroke-color">Stroke color:</label>
        	<input type="color" value="" id="text-stroke-color" class="btn-object-action" bind-value-to="strokeColor">
            
             <label for="text-stroke-width">Stroke width:</label>
        	<input type="range" value="1" min="1" max="5" id="text-stroke-width" class="btn-object-action" bind-value-to="strokeWidth">
            
        </div>
        <div class="clear"></div>
    </div>
    
    
    <div id="text-wrapper" style="margin-top:10px;" ng-show="getText()">
            <textarea bind-value-to="text"></textarea>
            <div id="text-controls" style="margin-top:10px; display:none;">              
              	<select id="font-family" class="btn-object-action" bind-value-to="fontFamily">
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
        <input type="range" value="" min="1" max="120" step="1" id="text-font-size" class="btn-object-action" bind-value-to="fontSize">
            <div id="text-controls-additional" style="margin-top:10px;">
          		<button type="button" class="btn btn-object-action" ng-click="toggleBold()" ng-class="{'btn-inverse': isBold()}" style="font-weight:bold;">B</button>
          		<button type="button" class="btn btn-object-action" id="text-cmd-italic" ng-click="toggleItalic()" ng-class="{'btn-inverse': isItalic()}">I</button>
          		<button type="button" class="btn btn-object-action" id="text-cmd-underline" ng-click="toggleUnderline()" ng-class="{'btn-inverse': isUnderline()}">U</button>  
                <div class="clear"></div>   
        	</div>
  		</div>
    
    
    
    <div style="margin-top:10px; display:none;" id="Add_Control_Div">
    	<div class="object-controls" object-buttons-enabled="getSelected()">
       
          
          <button class="btn btn-object-action" id="remove-selected" ng-click="removeSelected()"><img src="../images/delete-icon.png" alt="Delete" title="Delete" /></button>
          
		  <button class="btn btn-object-action" id="copy-selected" ng-click="copySelected()"><img src="../images/delete-icon.png" alt="Copy" title="Copy" /></button>	
            
          <button class="btn btn-lock btn-object-action" ng-click="setHorizontalLock(!getHorizontalLock())" ng-class="{'btn-inverse': getHorizontalLock()}">
            <img src="../images/horizontal-move-icon.png" alt="Horizontal Move" title="Horizontal Move" />
          </button>
          
          <button class="btn btn-lock btn-object-action" ng-click="setVerticalLock(!getVerticalLock())" ng-class="{'btn-inverse': getVerticalLock()}">
            <img src="../images/vertical-move-icon.png" alt="Vertical Move" title="Vertical Move" />
          </button>
          
          <button class="btn btn-lock btn-object-action" ng-click="setScaleLockX(!getScaleLockX())" ng-class="{'btn-inverse': getScaleLockX()}">
            <img src="../images/horizontal-scaling-icon.png" alt="Horizontal Scaling" title="Horizontal Scaling" />
          </button>
          
          <button class="btn btn-lock btn-object-action" ng-click="setScaleLockY(!getScaleLockY())" ng-class="{'btn-inverse': getScaleLockY()}">
            <img src="../images/vertical-scaling-icon.png" alt="Vertical Scaling" title="Vertical Scaling" />
          </button>
          
          <button class="btn btn-lock btn-object-action" ng-click="setRotationLock(!getRotationLock())" ng-class="{'btn-inverse': getRotationLock()}">
            <img src="../images/lock-rotation-icon.png" alt="Lock Rotation" title="Lock Rotation" />
          </button>
          
          <button id="send-backwards" class="btn btn-object-action" ng-click="sendBackwards()"><img src="../images/send-backwards-icon.png" alt="Send Backwards" title="Send Backwards" /></button>
          <button id="send-to-back" class="btn btn-object-action" ng-click="sendToBack()"><img src="../images/send-backwards-icon.png" alt="Send to Back" title="Send to Back" /></button>
          
          <button id="bring-forward" class="btn btn-object-action" ng-click="bringForward()"><img src="../images/send-forwards-icon.png" alt="Bring to Front" title="Bring to Front" /></button>
          <button id="bring-to-front" class="btn btn-object-action" ng-click="bringToFront()"><img src="../images/send-forwards-icon.png" alt="Bring to Top" title="Bring to Top" /></button>
          
        
      	<div class="clear"></div>
        </div>
    </div>
    
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

  
  
  </div>

  </body>
</html>
