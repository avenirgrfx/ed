<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');


$DB=new DB;
$System=new System;

if($_POST['type']=='System')
{
    $System->parent_id=$_POST['ddlSystem'];
    $System->display_type=$_POST['ddlType'];
	$System->system_name=$_POST['txtSystemName'];
	$System->has_node= ($_POST['chkHasWidget']=="" ? 0 : 1);
	if($_POST['System_ID']=='')
	{
		$System->Insert();
	}
	else
	{
		$System->system_id=$_POST['System_ID'];
		$System->Update();
	}
	exit;
	
}

$txtChar = $_GET['char'];
if(!$txtChar){
    $txtChar = 'A';
}
?>

<script>
    function showByCharacter(char){
        $('#Controls_Container').html('Loading...');
        $.get("<?php echo URL ?>ajax_pages/fetch_systems.php", {char: char},
            function (data, status) {
                $('#Controls_Container').html(data);
        });
    }
    
    function SystemOptionFetchSystems(strCatID)
    {
        $.get("<?php echo URL ?>ajax_pages/system_details.php",
                {
                    id: strCatID
                },
        function (data, status) {
            data = data.split("~#~");
            $('[name=ddlSystem]').val(data[0]);
            $('#txtSystemName').val(data[1]);
            $('#System_ID').val(data[2]);
            $('#ddlType').val(data[5]);

            $('#btnSubmit').val('Update');
            $('#btnDelete').val('Delete');
            if (data[1] !="" )
            {
                $('#btnDelete').css('display', 'block');
                $('#CannotDelete').css("display", 'none');
            }
            else
            {
                $('#btnDelete').css('display', 'none');
                $('#CannotDelete').css("display", 'block');
            }
            $('#btnDelete').attr("disabled", false);
            $('html, body').animate({scrollTop: $("#SystemNodes_Container").offset().top}, 200);
            $('#txtSystemName').focus();


            $('#btnDelete').click(function () {
                if (!confirm("Are you sure you want to Delete " + data[1] + " ?"))
                {

                }
                else
                {
                    $.get("<?php echo URL ?>ajax_pages/system_delete.php",
                            {
                                id: data[2]
                            },
                    function (data1, status1)
                    {
                        showByCharacter("<?=$txtChar?>");
                    });

                }
            });

        });
    }
    
    function addSystem(){
        if($('#txtSystemName').val()==''){
            alert("Please enter system name");
            return;
        }
        $.post("<?php echo URL ?>ajax_pages/fetch_systems.php", 
            {
                type: "System",
                System_ID: $('#System_ID').val(),
                ddlSystem : $('#ddlSystem').val(),
                ddlType: $("#ddlType").val(),
                txtSystemName : $('#txtSystemName').val(),
                chkHasWidget : "",                
            },
            function (data, status) {
                showByCharacter("<?=$txtChar?>");
        });
    }
</script>

<strong style="font-size:14px;">Add a New System</strong>
<br><br>

<div style="float:left; width:220px;">
    <select id="ddlSystem" name="ddlSystem" style="width:200px;">    	
        <?php $System->ListSystems();?>
    </select>
</div>
<div style="float:left; width:220px;">
    <select id="ddlType" name="ddlType" style="width:200px;">  
        <option value="0">Select Display Type</option>        
        <option value="1">Electric</option>
        <option value="2">Natural Gas</option>
        <option value="3">Water</option>
    </select>
</div>
<div style="float:left; width:220px;">
    <input type="text" id="txtSystemName" name="txtSystemName" placeholder="New System Name" style="width:200px;" />
</div>

<div style="float:left; width:200px;">
    <input type="hidden" id="System_ID" value=""/>
    <input type="button" id="btnSubmit" name="btnSubmit" value="Add" onclick="addSystem()" style="float:left;" />
    <input type="button" id="btnDelete" name="btnDelete" value="Delete" onclick="deleteSystem()" style="float:left;display:none;" />
</div>

<div class="clear"></div>

<hr style="border-bottom:1px #999999 dotted;">

<?php 
$strSQL="Select count(1) as count, UPPER(LEFT(system_name, 1)) as fc from t_system where parent_id=0 group by fc order by fc asc";	
$strRsCategoryArr=$DB->Returns($strSQL);

while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
{
    $fc_array[$strRsCategory->fc] = $strRsCategory->count;
}

foreach (range('A', 'Z') as $char) {
    echo '<div onclick="showByCharacter(\''.$char.'\')" style="float: left; width: 10px; padding: 14px; cursor:pointer; '.($txtChar==$char?'background:#cccccc;':'').'">';
    echo '<div>'.$char.'</div>';
    if(isset($fc_array["$char"])){ echo '<div>'. $fc_array[$char] .'</div>'; }
    echo '</div>';
} ?>
<div class="clear"></div>
<hr style="border-bottom:1px #999999 dotted;">

<ul style="cursor:pointer; width:1200px;">
<?php
    $iCtr=0;
    $strSQL="Select * from t_system where parent_id=0 and (system_name like '$txtChar%' or system_name like '".strtolower($txtChar)."%' ) order by system_name asc";	
    $strRsCategoryArr=$DB->Returns($strSQL);		
    while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
    {
        print "<li style='width:350px; float:left; margin-right: 50px;'><b> <span onclick=SystemOptionFetchSystems('".$strRsCategory->system_id."')>". $strRsCategory->system_name."</span></b><ul>";
        
        $strSQL="Select * from t_system where parent_id=".$strRsCategory->system_id." order by system_name asc";	
        $strRsSubCat1Arr=$DB->Returns($strSQL);
        while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
        {
            $strHasNodeStyle="";
            if($strRsSubCat1->has_node==1)
            {
                $strHasNodeStyle='text-decoration:underline; font-style: italic; ';
            }
            print "<li><span style='$strHasNodeStyle' onclick=SystemOptionFetchSystems('".$strRsSubCat1->system_id."')>".$strRsSubCat1->system_name."</span></li>";

        }
        print "</ul><hr style='border-bottom:1px #999999 dotted;'></li>";
    }
?>
</ul>