<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
require_once(AbsPath."classes/building.class.php");
$Building=new Building;

$DB=new DB;

$Client=new Client;
$client_id=Globals::Get('id');

if(Globals::Get('id')<>'')
{
	$DB = new DB;
	$ClientArray=$DB->Lists(array('Query'=>'Select * from t_client where client_id='.Globals::Get('id')));
	if(!is_array($ClientArray))
	{
		print 'Invalid ID';
		exit();
	}
	/*print "<pre>";
	print_r($ClientArray);
	print "</pre>";*/
	
	foreach($ClientArray as $Val)
	{
		$client_id=$Val->client_id;
		$client_type=$Val->client_type;
		$client_name=$Val->client_name;		
		$email_address=$Val->email_address;
		$address_line_1=$Val->address_line_1;
		$address_line_2=$Val->address_line_2;
		$city=$Val->city;
		$state=$Val->state;
		$zip=$Val->zip;
		$country=$Val->country;		
		$phone=$Val->phone;
		$website=$Val->website;
		$contact_name=$Val->contact_name;
		$contact_title=$Val->contact_title;
		$contact_email=$Val->contact_email;
		$manager_name=$Val->manager_name;
		$manager_email=$Val->manager_email;
		$manager_phone=$Val->manager_phone;
		$logo=$Val->logo;
	}
}
?>

<script type="text/javascript">
var MYMAP = {
  map: null,
	bounds: null
}

MYMAP.init = function(selector, latLng, zoom) {
  var myOptions = {
    zoom:zoom,
    center: latLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  this.map = new google.maps.Map($(selector)[0], myOptions);
	this.bounds = new google.maps.LatLngBounds();
}

MYMAP.placeMarkers = function(filename) {
	$.get(filename, function(xml){
		$(xml).find("marker").each(function(){
			var name = $(this).find('name').text();
			var address1 = $(this).find('address1').text();
			var city=$(this).find('city').text();
			var state=$(this).find('state').text();
			var zip=$(this).find('zip').text();
			var country=$(this).find('country').text();
			
			// create a new LatLng point for the marker
			var lat = $(this).find('lat').text();
			var lng = $(this).find('lng').text();
			var point = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
			
			// extend the bounds to include the new point
			MYMAP.bounds.extend(point);
			
			var marker = new google.maps.Marker({
				position: point,
				map: MYMAP.map
			});
			
			var infoWindow = new google.maps.InfoWindow();
			var html='<div style="width:200px;"><strong>'+name+'</strong><br />'+address1+'<br />'+city+', '+state+', '+ zip +', '+country +'</div>';
			google.maps.event.addListener(marker, 'click', function() {
				infoWindow.setContent(html);
				infoWindow.open(MYMAP.map, marker);
			});
			;
			MYMAP.map.fitBounds(MYMAP.bounds);
		});
		MYMAP.map.setZoom(7);
	});
}

$(document).ready(function(){
	var myLatLng = new google.maps.LatLng(42.836278, -85.636771);
  	MYMAP.init('#map', myLatLng, 7);
  	
	MYMAP.placeMarkers('<?php echo URL?>site_google_map.php?client_id=<?php echo $client_id;?>');
	//MYMAP.placeMarkers('markers.xml');
	
	
	$('#Add_Site_Link').click(function(){
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/site.php",
		{
			client_id:<?php echo $client_id;?>,
			/*site_id:0*/			
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});

	
	$('#Edit_Site_Link').click(function(){
		var SelectedSite=$('[name=ddlSite]').val();
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/site.php",
		{
			client_id:<?php echo $client_id;?>,
			site_id:SelectedSite
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	
	$('#Delete_Site_Link').click(function(){
		if(!confirm("Are you sure you want to Delete?"))
			return false;
		var SelectedSite=$('[name=ddlSite]').val();
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/site.php",
		{
			client_id:<?php echo $client_id;?>,
			site_id:SelectedSite,
			mode:'delete'
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	
	$('#ddlSite').change(function(){
		var SelectedSite=$('[name=ddlSite]').val();
		if(SelectedSite!="")
		{
			$('#Container_EditDeleteSite').css('display','block');
		}
		else
		{
			$('#Container_EditDeleteSite').css('display','none');
		}
	});
	
	
	
	
	$('#Add_Building_Link').click(function(){
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/building.php",
		{
			client_id:<?php echo $client_id;?>,
			/*site_id:0*/			
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	$('#ddlBuilding').change(function(){
		var SelectedBuilding=$('[name=ddlBuilding]').val();
		if(SelectedBuilding!="")
		{
			$('#Container_EditDeleteBuilding').css('display','block');
		}
		else
		{
			$('#Container_EditDeleteBuilding').css('display','none');
		}
	});
	
	$('#Edit_Building_Link').click(function(){
		var SelectedBuilding=$('[name=ddlBuilding]').val();
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/building.php",
		{
			client_id:<?php echo $client_id;?>,
			building_id:SelectedBuilding
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	$('#Delete_Building_Link').click(function(){
		if(!confirm("Are you sure you want to Delete?"))
			return false;
		var SelectedBuilding=$('[name=ddlBuilding]').val();
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/building.php",
		{
			client_id:<?php echo $client_id;?>,
			building_id:SelectedBuilding,
			mode:'delete'
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	
	
	
	$('#Add_Room_Link').click(function(){
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/room.php",
		{
			client_id:<?php echo $client_id;?>,
			/*site_id:0*/			
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	
	$('#ddlRoom').change(function(){
		var SelectedRoom=$('[name=ddlRoom]').val();
		if(SelectedRoom!="")
		{
			$('#Container_EditDeleteRoom').css('display','block');
		}
		else
		{
			$('#Container_EditDeleteRoom').css('display','none');
		}
	});
	
	$('#Edit_Room_Link').click(function(){
		var SelectedRoom=$('[name=ddlRoom]').val();
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/room.php",
		{
			client_id:<?php echo $client_id;?>,
			room_id:SelectedRoom
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	$('#Delete_Room_Link').click(function(){
		if(!confirm("Are you sure you want to Delete?"))
			return false;
		var SelectedBuilding=$('[name=ddlRoom]').val();
		$('#Building_Container').html("Loading, please wait...");
		$('#Building_Container').slideDown();
		$.get("ajax_pages/room.php",
		{
			client_id:<?php echo $client_id;?>,
			room_id:SelectedBuilding,
			mode:'delete'
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	});
	
	
	
});

</script>

<div style="float:left; width:50%; border:1px solid #CCCCCC;">
	<h2><?php echo $client_name;?></h2>
    Account Manager:
    
    
    <div id="map" style="width:600px; height:400px;"></div>                       
  	
    
</div>

<div style="float:left; width:48%; margin-left:1%; border:1px solid #CCCCCC; font-size:12px; color:#333333; padding:3px;">
	
    <div style="float:left; width:45%; margin-right:3%;">
		<div style="min-height:120px;">
		<?php 
            if($logo<>'')
            {?>
                <?php echo Globals::Resize(URL.'uploads/customer/'.rawurlencode($logo),200, 150)?>
            <?php
            }
        ?>
        </div>
        
        
        <div>
        	<strong>Contact Person</strong><br />
			<?php echo $contact_name;?><br />
			(<?php echo $contact_title?>)<br />
			<?php echo $phone?><br />
			<a href="mailto:<?php echo $contact_email?>"><?php echo $contact_email?></a>
        </div>
        
    </div>
    
    <div style="float:left; width:50%;">
    	
        <div style="min-height:120px;">
    		<h3><?php echo $client_name;?></h3>
        	<?php echo $address_line_1;?><br />
        	<?php echo $address_line_2;?><br />
        	<?php echo $city.", ". $state.", ".$zip.", ".$country;?><br />
        	<?php echo ($website<>"" ? "Website: <a href='"."http://".str_replace('http://','',$website)."' target='_blank'>". $website."</a>" : "") ;?>
        </div>
        
        <div style="margin-top:0px;">
        	<strong>Manager</strong>:<br />
			<?php echo $manager_name;?><br />
			<a href="mailto:<?php echo $manager_email?>"><?php echo $manager_email?></a><br />
            <?php echo $manager_phone;?>
        </div>
        
    </div>
    
    <div class="clear"></div>
    
    <div style="margin-top:20px;">
   	  <div style="float:left; width:80px; margin-top:5px;">
        		<strong>Sites:</strong>
      </div>            
      		<div style="float:left; margin-top:5px;">
            	<?php 
					$strSQL="Select count(*) as TotalSite from t_sites where client_id=$client_id";
					$strRsSiteCountArr=$DB->Returns($strSQL);
					while($strRsSiteCount=mysql_fetch_object($strRsSiteCountArr))
					{
						echo ($strRsSiteCount->TotalSite<9 ? "0".$strRsSiteCount->TotalSite : $strRsSiteCount->TotalSite);
					}
				?>
            </div>
      <div style="cursor:pointer; float:left; margin-left:10px; color:#0066FF; margin-top:5px;" id="Add_Site_Link">Add New</div>
            <div style="float:left; margin-left:20px;">            	
            	<select name="ddlSite" id="ddlSite">
                	<?php $Building->FetchSites($client_id);?>
          	    </select> 
           	</div>
            
            <div style="float:left; margin-left:10px; display:none; margin-top:5px;" id="Container_EditDeleteSite">
            	<div style="float:left; cursor:pointer; color:#0066FF;" id="Edit_Site_Link">Edit</div>
                <div style="float:left; cursor:pointer; color:#0066FF; margin-left:10px;" id="Delete_Site_Link">Delete</div>   
                <div class="clear"></div>             
            </div>
                 
            <div class="clear" style="height:15px;"></div>
            
            
      <div style="float:left; width:80px; margin-top:5px;">
        		<strong>Buildings:</strong>
      </div>            
      		<div style="float:left; margin-top:5px;">
            	<?php 
					$strSQL="Select count(*) as TotalBuilding from t_building where client_id=$client_id";
					$strRsBuildingCountArr=$DB->Returns($strSQL);
					while($strRsBuildingCount=mysql_fetch_object($strRsBuildingCountArr))
					{
						echo ($strRsBuildingCount->TotalBuilding<9 ? "0".$strRsBuildingCount->TotalBuilding : $strRsBuildingCount->TotalBuilding);
					}
				?>
            </div>
            <div style="cursor:pointer; float:left; margin-left:10px; color:#0066FF; margin-top:5px;" id="Add_Building_Link">Add New</div>
            <div style="float:left; margin-left:20px;">            	
            	<select name="ddlBuilding" id="ddlBuilding">
                	<?php $Building->FetchBuilding($client_id);?>
          	    </select> 
           	</div>
            
            <div style="float:left; margin-left:10px; display:none; margin-top:5px;" id="Container_EditDeleteBuilding">
            	<div style="float:left; cursor:pointer; color:#0066FF;" id="Edit_Building_Link">Edit</div>
                <div style="float:left; cursor:pointer; color:#0066FF; margin-left:10px;" id="Delete_Building_Link">Delete</div>   
                <div class="clear"></div>             
            </div>
                   
            <div class="clear" style="height:15px;"></div>
            
            
      <div style="float:left; width:80px; margin-top:5px;">
        		<strong> Rooms:</strong>
      </div>            
      		<div style="float:left; margin-top:5px;">
            	<?php 
					$strSQL="Select count(*) as TotalRoom from t_room where client_id=$client_id";
					$strRsRoomCountArr=$DB->Returns($strSQL);
					while($strRsRoomCount=mysql_fetch_object($strRsRoomCountArr))
					{
						echo ($strRsRoomCount->TotalRoom<9 ? "0".$strRsRoomCount->TotalRoom : $strRsRoomCount->TotalRoom);
					}
				?>
            </div>
            <div style="cursor:pointer; float:left; margin-left:10px; color:#0066FF; margin-top:5px;" id="Add_Room_Link">Add New</div>
            <div style="float:left; margin-left:20px;">            	
            	<select name="ddlRoom" id="ddlRoom">
                	<?php $Building->FetchRoomWithBuilding($client_id);?>
          	    </select> 
           	</div>
            
            <div style="float:left; margin-left:10px; display:none; margin-top:5px;" id="Container_EditDeleteRoom">
            	<div style="float:left; cursor:pointer; color:#0066FF;" id="Edit_Room_Link">Edit</div>
                <div style="float:left; cursor:pointer; color:#0066FF; margin-left:10px;" id="Delete_Room_Link">Delete</div>   
                <div class="clear"></div>             
            </div>            
            <div class="clear"></div>
            
  </div>
    
    
    
    <div style="display:none;" id="Building_Container">
    
    	
    
    </div>
    
    
    
</div>

<div class="clear"></div>
