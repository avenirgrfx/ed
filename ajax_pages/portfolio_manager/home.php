<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/gallery.class.php');


$DB = new DB;

$strSQL="Select * from t_client order by client_name";
$strRsClientsArr=$DB->Returns($strSQL);
?>
<script>
    $(function () {
        $('#ddlClientList').change(function(){
            if($('#ddlClientList').val() != ''){
                var portfolio_status = $('#ddlClientList').find(":selected").attr("portfolio_status");
                if(portfolio_status != 0){
                    $('#portfolio_container').html('');
                    $('#ddlBuildingList').hide('');
                    $('#ddlSiteList').show();
                    
                    $.get("ajax_pages/site_list_by_client.php",
                        {
                            client_id: $('#ddlClientList').val()				
                        },
                    function(data,status){						
                            $('#ddlSiteList').html(data);
                    });
                }else{
                    $('#ddlSiteList').hide();
                    $('#ddlBuildingList').hide('');
                    $('#portfolio_container').html('Loading...');
                    $.get("ajax_pages/portfolio_manager/client_portfolio_data.php",
                        {
                            //building_id: $('#ddlBuildingList').val(),
                            client_id: $('#ddlClientList').val()
                        },
                    function(data,status){						
                            $('#portfolio_container').html(data);
                    });
                }
            }else{
                $('#portfolio_container').html('');
                $('#ddlSiteList').hide();
                $('#ddlBuildingList').hide();
            }
        });
        
        $('#ddlSiteList').change(function(){
            $('#ddlBuildingList').show('');
            $('#ddlBuildingList').html('<option value="">Select Building</option>');
            $('#portfolio_container').html('');
            
            if($('#ddlSiteList').val() != ''){
                $.get("ajax_pages/building_list_by_site.php",
                    {
                        site_id: $('#ddlSiteList').val()				
                    },
                function(data,status){						
                        $('#ddlBuildingList').html(data);
                });
            }
        });
        
        $('#ddlBuildingList').change(function(){
            $('#portfolio_container').html('Loading...');
            if($('#ddlBuildingList').val() != ''){
                $('#portfolio_container').html('Loading...');
                
                var portfolio_status = $('#ddlBuildingList').find(":selected").attr("portfolio_status");
                if(portfolio_status != 0){
                    $.get("ajax_pages/portfolio_manager/building_portfolio_data.php",
                        {
                            building_id: $('#ddlBuildingList').val(),
                            client_id: $('#ddlClientList').val()
                        },
                    function(data,status){						
                            $('#portfolio_container').html(data);
                    });
                }else{
                    $.get("ajax_pages/portfolio_manager/property_portfolio_data.php",
                        {
                            building_id: $('#ddlBuildingList').val(),
                            client_id: $('#ddlClientList').val()
                        },
                    function(data,status){						
                            $('#portfolio_container').html(data);
                    });
                }
            }else{
                $('#portfolio_container').html('');
            }
        });
    });
</script>
<strong style="font-size:14px;">OPEN EXISTING CLIENTS</strong>
<br><br>

<form id="frmCategory" name="frmCategory" action="" method="post">
    <div style="float:left; width:230px;">
       <select name="ddlClientList" id="ddlClientList">
            <option value="">Select Client</option>
            <?php while($strRsClients=mysql_fetch_object($strRsClientsArr)){?>
                <option value="<?php echo $strRsClients->client_id;?>" portfolio_status="<?php echo $strRsClients->portfolio_status;?>"><?php echo $strRsClients->client_name;?></option>
            <?php }?>
        </select>
    </div>
    
    <div style="float:left; width:230px;">
        <select name="ddlSiteList" id="ddlSiteList" style="display:none;">
            <option value="">Select Site</option>
        </select>
    </div>
    
    <div style="float:left; width:230px;">
        <select name="ddlBuildingList" id="ddlBuildingList" style="display:none;">
            <option value="">Select Building</option>
        </select>
    </div>
    <div class="clear"></div>
</form>

<hr style="border-bottom:1px #999999 dotted;">

<div id="portfolio_container">
      
</div>