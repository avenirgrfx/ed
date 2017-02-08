<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$widget_category_id=$_GET['id'];
?>
<script type="text/javascript">
function LoadWidgetTemplate(TemplateName,TemplateTitle)
{
	var id=0;
	$('#WidgetTemplateContainer').slideUp('fast');
	$.get("<?php echo URL?>ajax_pages/widget_templates/"+TemplateName+".php",
	  {
		id:id				
	  },
	  function(data,status){						
			$('#WidgetTemplateContainer').html(
				 data
			);			
			$('.WidgetName').slideUp('slow');
			$('#WidgetTemplateContainer').slideDown('slow');
			$('#SelectedWidgetTitle').html(TemplateTitle);
			$('#SelectedWidgetTitle').slideDown('slow');
	  });
}

$('#SelectedWidgetTitle').click(function(){
	$('.WidgetName').slideDown('slow');
	$('#SelectedWidgetTitle').slideUp('slow');
});

</script>

<style type="text/css">
.WidgetName
{
	cursor:pointer;
	font-weight:bold;
	color:#333333;
	border-bottom:1px solid #EFEFEF;
}
</style>
<?php
$strSQL="Select * from t_widgets where widget_category_id=$widget_category_id and delete_flag=0";

$strRsWidgetArr=$DB->Returns($strSQL);
while($strRsWidget=mysql_fetch_object($strRsWidgetArr))
{
	$widget_name=$strRsWidget->widget_name;
	$page_template=$strRsWidget->page_template;	
?>
	<div class="WidgetName" onclick="LoadWidgetTemplate('<?php echo $page_template?>','<?php echo $widget_name?>')"><?php echo $strRsWidget->widget_name?></div>
<?php
}



?>

<div id="SelectedWidgetTitle" style="display:none; cursor:pointer; text-transform:uppercase; padding:5px; border:none; font-size:15px;" class="RightPanelTitle"></div>
<div id="WidgetTemplateContainer" style="border-top:1px solid #cccccc; border-bottom:1px solid #cccccc; padding:5px; margin-top:10px; display:none;"></div>

