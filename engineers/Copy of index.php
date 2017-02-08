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
			
		});
				
	</script>
    
    
    
  </head>
  <body>
 
  <div id="MainContainer">
  
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
  
  
  <div class="BottomMenu" id="">
  	<ul class="Projects_Menu">
  		<li>Open Workspace</li>
    </ul>
    <div class="clear"></div>
  </div>
  
  
  
  
<div id="bd-wrapper" ng-controller="CanvasControls">

    
    <div id="Active_Object_Edit">
    
        <div id="color-opacity-controls" ng-show="canvas.getActiveObject()">
             
            <label for="color" style="margin-left:10px">Color: </label>
            <input type="color" style="width:40px" bind-value-to="fill">
        </div>
        
        <div id="text-wrapper" style="margin-left:30px;" ng-show="getText()">
            <textarea bind-value-to="text"></textarea>
            <div id="text-controls">
              
              	<select id="font-family" class="btn-object-action" bind-value-to="fontFamily">
                <option value="">Chhose a Font</option>
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
            <div id="text-controls-additional">
          		<button type="button" class="btn btn-object-action" ng-click="toggleBold()" ng-class="{'btn-inverse': isBold()}" style="font-weight:bold;">B</button>
          		<button type="button" class="btn btn-object-action" id="text-cmd-italic" ng-click="toggleItalic()" ng-class="{'btn-inverse': isItalic()}">I</button>
          		<button type="button" class="btn btn-object-action" id="text-cmd-underline" ng-click="toggleUnderline()" ng-class="{'btn-inverse': isUnderline()}">U</button>  
                <div class="clear"></div>   
        	</div>
  		</div>
           
       
        
        <div id="canvas-background">
          <label for="canvas-background-picker">Background Color:</label>
          <input type="color" bind-value-to="canvasBgColor">
        </div>        
        
        
        
        <div class="clear"></div>
           
    
    </div>
    
    <div style="float:left;"><strong>Design a Control</strong></div>
    <div style="float:left; margin-left:50px;">
    	 <div style="float:left;"><input name="txtControlName" id="txtControlName" type="text" placeholder="Control Name" /></div>
         <div style="float:left; margin-left:10px;"><button class="btn btn-success" id="rasterize-json" ng-click="rasterizeJSON()">Save</button></div>
         <div class="clear"></div>
    </div>
     <div class="clear"></div>
    
    
<div style="position:relative;width:800px;float:left;" id="canvas-wrapper">
  <canvas id="canvas" width="800" height="500"></canvas>  
</div>


<div id="commands" ng-click="maybeLoadShape($event)">

  <ul class="nav nav-tabs">
    <li class="active"><a href="#simple-shapes" data-toggle="tab">Library</a></li>
    <li><a href="#execute-code" data-toggle="tab">Text</a></li>
    <li><a href="#svg-shapes" data-toggle="tab">Shapes</a></li>
    <li><a href="#object-controls-pane" data-toggle="tab">Controls</a></li>
    
  </ul>

  <div class="tab-content">    

    <div class="tab-pane active" id="simple-shapes">
      
      
      <div id="tree1"></div>
      
      <script>
        $(function() {
            var $tree = $('#tree1');
			
			
			var data = [
							<?php
							
							$strSQL="Select * from t_category where parent_id=0 order  by category_id asc";	
							$strRsCategoryArr=$DB->Returns($strSQL);
							$iCount=1;
							while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
							{						
								$iCount++;
								$strSQL="Select * from t_category where parent_id=".$strRsCategory->category_id." order  by category_id asc";	
								$strRsSubCat1Arr=$DB->Returns($strSQL);
							?>
								{ label: '<?php echo $strRsCategory->category_name;?>', id:<?php echo $strRsCategory->category_id?> , 
									<?php if(mysql_num_rows($strRsSubCat1Arr)>0){?>
										children: [
											<?php while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr)){?>
											{label: '<?php echo $strRsSubCat1->category_name;?>', id:<?php echo $strRsSubCat1->category_id;?>,
												
												<?php 
												$strSQL="Select * from t_category where parent_id=".$strRsSubCat1->category_id." order  by category_id asc";	
												$strRsSubCat2Arr=$DB->Returns($strSQL);
												if(mysql_num_rows($strRsSubCat2Arr)>0){												
												?>
													children: [
													<?php while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr)){?>
													
													{label:'<?php echo $strRsSubCat2->category_name?>', id:<?php echo $strRsSubCat2->category_id ?>,
													
													<?php 
													$strSQL="Select * from t_category where parent_id=".$strRsSubCat2->category_id." order  by category_id asc";	
													$strRsSubCat3Arr=$DB->Returns($strSQL);
													if(mysql_num_rows($strRsSubCat3Arr)>0){												
													?>
													
													children: [
														<?php while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr)){?>
														{label:'<?php echo $strRsSubCat3->category_name?>', id:<?php echo $strRsSubCat3->category_id ?>,},
														<?php }?>
													]
													
													<?php }?>
													
													},
													
													<?php }?>
													]
												<?php 
												}
												?>
												
											},
											<?php }?>
										]
									<?php }?>																							
								}, 
							<?php }?>						
					
					];
			
			
			
			
            $tree.tree({
                data: data,
                autoOpen: false,
				openParents:false,
                onCreateLi: function(node, $li) {
                  
                   $li.find('.jqtree-element').append(
						'<a onclick="javascript:LoadImagemDetails('+node.id+')" class="edit" data-node-id="'+ node.id +'" style="margin-left:10px; font-size:12px; float:right;">Show</a>'
                    );
                }
            });
			
						
			$tree.on(
                'click', '.jqtree-title',
                function(e)
				{              
					//var node = $tree.tree('getSelectedNode');
                    //if (node) { LoadAlarmDetails(node.id); }					
                }
            );
			
			
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
      
      
      <p id="dynamic_image"></p>
    
        
      
    </div>
	
    <div class="tab-pane" id="execute-code">
      <p>
        <button class="btn" ng-click="addText()">Add text</button>
      </p>      
    </div>
    
    <div class="tab-pane" id="svg-shapes">
      <p>Add <strong>simple shapes</strong> to Design:</p>
      <p>
        <button type="button" class="btn rect" ng-click="addRect()">Rectangle</button>
        <button type="button" class="btn circle" ng-click="addCircle()">Circle</button>
        <button type="button" class="btn triangle" ng-click="addTriangle()">Triangle</button>
        <button type="button" class="btn line" ng-click="addLine()">Line</button>
        <button type="button" class="btn polygon" ng-click="addPolygon()">Polygon</button>
      </p>      
    </div>
    
    <div class="tab-pane" id="object-controls-pane">
      <div id="global-controls">
        <p>
          Rasterize canvas to
          <button class="btn btn-success" id="rasterize" ng-click="rasterize()">
            Image          </button>
          <button class="btn btn-success" id="rasterize-svg" ng-click="rasterizeSVG()">
            SVG          </button>
         
        </p>
        <p>
          <button class="btn btn-danger clear" ng-click="confirmClear()">Clear canvas</button>
        </p>
      </div>

      <div class="object-controls" object-buttons-enabled="getSelected()">

        <div style="margin-top:10px;">
          <p>
            <button class="btn btn-object-action" id="remove-selected" ng-click="removeSelected()">Delete</button>
          </p>

          <button class="btn btn-lock btn-object-action"
            ng-click="setHorizontalLock(!getHorizontalLock())"
            ng-class="{'btn-inverse': getHorizontalLock()}">
            {[ getHorizontalLock() ? 'Unlock horizontal' : 'Lock horizontal' ]}          </button>
          <br>
          <button class="btn btn-lock btn-object-action"
            ng-click="setVerticalLock(!getVerticalLock())"
            ng-class="{'btn-inverse': getVerticalLock()}">
            {[ getVerticalLock() ? 'Unlock vertical' : 'Lock vertical' ]}          </button>
          <br>
          <button class="btn btn-lock btn-object-action"
            ng-click="setScaleLockX(!getScaleLockX())"
            ng-class="{'btn-inverse': getScaleLockX()}">
            {[ getScaleLockX() ? 'Unlock horizontal scaling' : 'Lock horizontal scaling' ]}          </button>
          <br>
          <button class="btn btn-lock btn-object-action"
            ng-click="setScaleLockY(!getScaleLockY())"
            ng-class="{'btn-inverse': getScaleLockY()}">
            {[ getScaleLockY() ? 'Unlock vertical scaling' : 'Lock vertical scaling' ]}          </button>
          <br>
          <button class="btn btn-lock btn-object-action"
            ng-click="setRotationLock(!getRotationLock())"
            ng-class="{'btn-inverse': getRotationLock()}">
            {[ getRotationLock() ? 'Unlock rotation' : 'Lock rotation' ]}          </button>
        </div>

        

        <div style="margin-top:10px;">
          <button id="send-backwards" class="btn btn-object-action"
            ng-click="sendBackwards()">Send backwards</button>
          <button id="send-to-back" class="btn btn-object-action"
            ng-click="sendToBack()">Send to back</button>
        </div>

        <div style="margin-top:4px;">
          <button id="bring-forward" class="btn btn-object-action"
            ng-click="bringForward()">Bring forwards</button>
          <button id="bring-to-front" class="btn btn-object-action"
            ng-click="bringToFront()">Bring to front</button>
        </div>

        <div style="margin-top:10px;">
          <button id="gradientify" class="btn btn-object-action" ng-click="gradientify()">
            Gradientify          </button>
          <button id="shadowify" class="btn btn-object-action" ng-click="shadowify()">
            Shadowify          </button>
          <button id="patternify" class="btn btn-object-action" ng-click="patternify()">
            Patternify          </button>
          <button id="clip" class="btn btn-object-action" ng-click="clip()">
            Clip          </button>
        </div>
      </div>
      <div style="margin-top:10px;" id="drawing-mode-wrapper">

        <button id="drawing-mode" class="btn btn-info"
          ng-click="setFreeDrawingMode(!getFreeDrawingMode())"
          ng-class="{'btn-inverse': getFreeDrawingMode()}">
          {[ getFreeDrawingMode() ? 'Exit free drawing mode' : 'Enter free drawing mode' ]}        </button>

        <div id="drawing-mode-options" ng-show="getFreeDrawingMode()">
          <label for="drawing-mode-selector">Mode:</label>
          <select id="drawing-mode-selector" bind-value-to="drawingMode">
            <option>Pencil</option>
            <option>Circle</option>
            <option>Spray</option>
            <option>Pattern</option>

            <option>hline</option>
            <option>vline</option>
            <option>square</option>
            <option>diamond</option>
            <option>texture</option>
          </select>
          <br>
          <label for="drawing-line-width">Line width:</label>
          <input type="range" value="30" min="0" max="150" bind-value-to="drawingLineWidth">
          <br>
          <label for="drawing-color">Line color:</label>
          <input type="color" value="#005E7A" bind-value-to="drawingLineColor">
          <br>
          <label for="drawing-shadow-width">Line shadow width:</label>
          <input type="range" value="0" min="0" max="50" bind-value-to="drawingLineShadowWidth">
        </div>
      </div>
    </div>
    
    
    
    
  </div>
</div>



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
