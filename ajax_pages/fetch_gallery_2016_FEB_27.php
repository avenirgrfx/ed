<?php
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
$DB = new DB;
$System = new System();
$Category = new Category;
$Gallery = new Gallery;

if(isset($_POST) && !empty($_POST)){
    if($_POST['mode'] == "add"){
        $ImageFile = '';
        if ($_FILES['file1']['name'][0]) {
            # For Image		
            $uploaddir = AbsPath . '/images/control-images/';
            $uploadfile = $uploaddir . basename($_FILES['file1']['name']);

            if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploadfile)) {
                $ImageFile = $_FILES['file1']['name'];
            } else {
                echo "Couldn't upload the Image!\n";

                $ImageFile = '';
            }

            # For Technical Files
            if ($_FILES['file2']['name'][0]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file2']['name'][0]);
                if (move_uploaded_file($_FILES['file2']['tmp_name'][0], $uploadfile)) {
                    $technical_file1 = $_FILES['file2']['name'][0];
                } else {
                    echo "Couldn't upload Technical File 1 !\n";
                    $technical_file1 = '';
                }
            }

            if ($_FILES['file2']['name'][1]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file2']['name'][1]);
                if (move_uploaded_file($_FILES['file2']['tmp_name'][1], $uploadfile)) {
                    $technical_file2 = $_FILES['file2']['name'][1];
                } else {
                    echo "Couldn't upload Technical File 2 !\n";
                    $technical_file2 = '';
                }
            }


            if ($_FILES['file2']['name'][2]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file2']['name'][2]);
                if (move_uploaded_file($_FILES['file2']['tmp_name'][2], $uploadfile)) {
                    $technical_file3 = $_FILES['file2']['name'][2];
                } else {
                    echo "Couldn't upload Technical File 3 !\n";
                    $technical_file3 = '';
                }
            }


            if ($_FILES['file2']['name'][3]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file2']['name'][3]);
                if (move_uploaded_file($_FILES['file2']['tmp_name'][3], $uploadfile)) {
                    $technical_file4 = $_FILES['file2']['name'][3];
                } else {
                    echo "Couldn't upload Technical File 4 !\n";
                    $technical_file4 = '';
                }
            }


            if ($_FILES['file2']['name'][4]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file2']['name'][4]);
                if (move_uploaded_file($_FILES['file2']['tmp_name'][4], $uploadfile)) {
                    $technical_file5 = $_FILES['file2']['name'][4];
                } else {
                    echo "Couldn't upload Technical File 5 !\n";
                    $technical_file5 = '';
                }
            }
            
            if ($_FILES['file2']['name'][5]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file2']['name'][5]);
                if (move_uploaded_file($_FILES['file2']['tmp_name'][5], $uploadfile)) {
                    $technical_file6 = $_FILES['file2']['name'][5];
                } else {
                    echo "Couldn't upload Technical File 6 !\n";
                    $technical_file6 = '';
                }
            }
            
            # For 3D Files
            if ($_FILES['file3']['name'][0]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file3']['name'][0]);
                if (move_uploaded_file($_FILES['file3']['tmp_name'][0], $uploadfile)) {
                    $_3d_file1 = $_FILES['file3']['name'][0];
                } else {
                    echo "Couldn't upload Technical File 1 !\n";
                    $_3d_file1 = '';
                }
            }

            if ($_FILES['file3']['name'][1]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file3']['name'][1]);
                if (move_uploaded_file($_FILES['file3']['tmp_name'][1], $uploadfile)) {
                    $_3d_file2 = $_FILES['file3']['name'][1];
                } else {
                    echo "Couldn't upload Technical File 2 !\n";
                    $_3d_file2 = '';
                }
            }


            if ($_FILES['file3']['name'][2]) {
                $uploaddir = AbsPath . '/uploads/documents/';
                $uploadfile = $uploaddir . basename($_FILES['file3']['name'][2]);
                if (move_uploaded_file($_FILES['file3']['tmp_name'][2], $uploadfile)) {
                    $_3d_file3 = $_FILES['file3']['name'][2];
                } else {
                    echo "Couldn't upload Technical File 3 !\n";
                    $_3d_file3 = '';
                }
            }
        }


        $Gallery->category_id = $_POST['ddlImageCategroy'];
        $Gallery->image_path = $ImageFile;
        $Gallery->image_name = $_POST['txtGalleryName'];
        $Gallery->image_tags = $_POST['txtTagName'];
        $Gallery->image_description = $_POST['txtGalleryDescription'];
        $Gallery->technical_file1 = $technical_file1;
        $Gallery->technical_file2 = $technical_file2;
        $Gallery->technical_file3 = $technical_file3;
        $Gallery->technical_file4 = $technical_file4;
        $Gallery->technical_file5 = $technical_file5;
        $Gallery->technical_file6 = $technical_file6;
        $Gallery->td_file1 = $_3d_file1;
        $Gallery->td_file2 = $_3d_file2;
        $Gallery->td_file3 = $_3d_file3;

        $Gallery->created_by = 1;
        $Gallery->modified_by = 1;

        $Gallery->Insert();
    }else if($_POST['mode'] == "delete"){
//        $strSQL="delete from t_system where fuel_type_id = '".$_POST['txtFuelTypeId']."'";
//        $DB->Returns($strSQL);
    }else if($_POST['mode'] == "update"){
//        $strSQL="update t_system set fuel_type = '".$_POST['txtFuelTypeName']."', unit = '".$_POST['ddlFuelTypeUnit']."' where fuel_type_id = '".$_POST['txtFuelTypeId']."'";
//        $DB->Returns($strSQL);
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
        $.get("<?php echo URL ?>ajax_pages/fetch_gallery.php", {char: char},
            function (data, status) {
                $('#Controls_Container').html(data);
        });
    }
    
    function ShowImages(strCatID)
    {
        $.get("<?php echo URL ?>ajax_pages/images_by_category.php",
                {
                    id: strCatID
                },
        function (data, status) {
            var a = 'Image-' + strCatID;
            $('#' + a).slideDown('slow');
            document.getElementById(a).innerHTML = data;

        });
    }
    
    function CloseImage(strCatID)
    {

        var a = 'Image-' + strCatID;
        $('#' + a).slideUp('slow');
        //document.getElementById(a).innerHTML='';			
    }
    
    $(document).ready(function(){
       $("#frmGallery").submit(function(event){
            //disable the default form submission
            event.preventDefault();

            //grab all form data  
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: '<?php echo URL ?>ajax_pages/fetch_gallery.php',
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    alert("Added to gallery");
                    showByCharacter("<?=$txtChar?>");
                }
            });

            return false;
        }); 
    });
</script>

<strong style="font-size:14px;">Manage Gallery</strong>
<br><br>
<form action="" method="post" enctype="multipart/form-data" name="frmGallery" id="frmGallery">


    <div style="float:left; width:775px;">

        <div style="float:left; width:230px;">
            <select id="ddlImageCategroy" name="ddlImageCategroy" onblur="PreviewImage()" onchange="PreviewImage()" >    	
                <?php $System->ListSystemForGallary(); ?>
            </select>
        </div>
        <div style="float:left; width:230px;">
            <input type="text" id="txtGalleryName" name="txtGalleryName" placeholder="Enter Image Name" onblur="PreviewImage()" onchange="PreviewImage()" />
        </div>

        <div style="float:left; width:230px;">
            <input type="text" id="txtGalleryDescription" name="txtGalleryDescription" placeholder="Enter Image Description" onblur="PreviewImage()" onchange="PreviewImage()" />
        </div>

        <div class="clear"></div>

        <div style="float:left; width:230px;">
            <input type="text" id="txtTagName" name="txtTagName" placeholder="Enter Search Tags" onblur="PreviewImage()" onchange="PreviewImage()" />
        </div>       

        <div style="float:left; width:330px;">        	
            Image: <input type="file" name="file1" id="file1" onchange="PreviewImage();">
        </div>

        <div class="clear"></div>

        <strong style="float:left; width:250px;">Add Technical Files</strong>  <strong style="float:left; width:250px;">&nbsp;</strong> <strong style="float:left; width:250px;">Add 3D Files</strong><br />

        <div style="float:left; width:250px;">        	
            File1: <input type="file" name="file2[]" id="file2[]" style="width:200px;"><br />           
            File3: <input type="file" name="file2[]" id="file2[]" style="width:200px;"><br />            
            File5: <input type="file" name="file2[]" id="file2[]" style="width:200px;"><br />
        </div>

        <div style="float:left; width:250px;">
            File2: <input type="file" name="file2[]" id="file2[]" style="width:200px;"><br />
            File4: <input type="file" name="file2[]" id="file2[]" style="width:200px;"><br />
            File6: <input type="file" name="file2[]" id="file2[]" style="width:200px;"><br />
        </div>
        
        <div style="float:left; width:270px;">
            3D File 1: <input type="file" name="file3[]" id="file3[]" style="width:200px;"><br />
            3D File 2: <input type="file" name="file3[]" id="file3[]" style="width:200px;"><br />
            3D File 3: <input type="file" name="file3[]" id="file3[]" style="width:200px;"><br />
        </div>

        <div class="clear"></div>
        
        <div style="float:left; width:680px; text-align:right;">

        </div>

        <div class="clear">
            <input type="hidden" name="type" id="type" value="Gallery">
        </div>

    </div>

    <div style="float:left; border:1px solid #999999; border-radius:5px; padding:5px;">
        <div style="float:left; width:100px;">
            <img src="<?php echo URL ?>images/no-image-selected.png" name="uploadPreview" id="uploadPreview" width="100" height="125" />
        </div>

        <div style="float:left; margin-left:10px; min-width:310px; font-size:12px;">
            Name: <span id="Show_Image_Name" style="font-weight:bold; font-size:14px;"></span><br />
            Category: <span id="Show_Image_Category" style="font-weight:bold; font-size:14px;"></span><br />
            Description: <span id="Show_Image_Description" style="font-style:italic; width:250px; overflow:hidden;"></span><br />
            Tags: <span id="Show_Image_Tags"></span>
        </div>

        <div class="clear"></div>

    </div>
    <input type="hidden" name="mode" value="add">
    <input type="submit" id="btnSubmit" name="btnSubmit" value="Add to Gallery" style="font-weight:bold; font-size:14px; float:right; margin: 10px;" />

    <div class="clear"></div>


</form>

<hr style="border-bottom:1px #999999 dotted;">
<?php 
$strSQL="Select count(1) as count, UPPER(LEFT(category_name, 1)) as fc from t_category where parent_id=0 group by fc order by fc asc";	
$strRsCategoryArr=$DB->Returns($strSQL);
$fc_array = array();
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

<div class="clear"></div>


<ul style="cursor:pointer;">
    <?php
    $iCtr = 0;
    $strSQL = "Select * from t_category where parent_id=0 and (category_name like '$txtChar%' or category_name like '".strtolower($txtChar)."%' ) order by category_name asc";
    $strRsCategoryArr = $DB->Returns($strSQL);
    while ($strRsCategory = mysql_fetch_object($strRsCategoryArr)) {
        /* $strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsCategory->category_id;
          $strRsCount1Arr=$DB->Returns($strSQL);
          if($strRsCount1=mysql_fetch_object($strRsCount1Arr)){ $count1= $strRsCount1->Total; } */

        $count1 = $Category->CountGallery($strRsCategory->category_id);

        print "<li><b> <span onclick=ShowImages('" . $strRsCategory->category_id . "')>" . $strRsCategory->category_name . " (" . $count1 . ")" . "</span></b> <br>&nbsp;&nbsp;<span style='width: 100%' id='Image-" . $strRsCategory->category_id . "'></span><ul>";
        $strSQL = "Select * from t_category where parent_id=" . $strRsCategory->category_id . " order  by category_name asc";
        $strRsSubCat1Arr = $DB->Returns($strSQL);
        while ($strRsSubCat1 = mysql_fetch_object($strRsSubCat1Arr)) {
            /* $strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsSubCat1->category_id;
              $strRsCount2Arr=$DB->Returns($strSQL);
              if($strRsCount2=mysql_fetch_object($strRsCount2Arr)){ $count2= $strRsCount2->Total; } */

            $count2 = $Category->CountGallery($strRsSubCat1->category_id);

            print "<li><span onclick=ShowImages('" . $strRsSubCat1->category_id . "')>" . $strRsSubCat1->category_name . " (" . $count2 . ")" . "</span> <br>&nbsp;&nbsp;<span style='width: 100%' id='Image-" . $strRsSubCat1->category_id . "'></span><ul>";
            $strSQL = "Select * from t_category where parent_id=" . $strRsSubCat1->category_id . " order  by category_name asc";
            $strRsSubCat2Arr = $DB->Returns($strSQL);
            while ($strRsSubCat2 = mysql_fetch_object($strRsSubCat2Arr)) {
                /* $strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsSubCat2->category_id;
                  $strRsCount3Arr=$DB->Returns($strSQL);
                  if($strRsCount3=mysql_fetch_object($strRsCount3Arr)){ $count3= $strRsCount3->Total; } */

                $count3 = $Category->CountGallery($strRsSubCat2->category_id);

                print "<li><span onclick=ShowImages('" . $strRsSubCat2->category_id . "')>" . $strRsSubCat2->category_name . " (" . $count3 . ")" . "</span> <br>&nbsp;&nbsp;<span style='width: 100%' id='Image-" . $strRsSubCat2->category_id . "'></span><ul>";
                $strSQL = "Select * from t_category where parent_id=" . $strRsSubCat2->category_id . " order  by category_name asc";
                $strRsSubCat3Arr = $DB->Returns($strSQL);
                while ($strRsSubCat3 = mysql_fetch_object($strRsSubCat3Arr)) {
                    /* $strSQL="Select count(*) as Total from t_control_image where category_id=".$strRsSubCat3->category_id;
                      $strRsCount4Arr=$DB->Returns($strSQL);
                      if($strRsCount4=mysql_fetch_object($strRsCount4Arr)){ $count4= $strRsCount4->Total; } */

                    $count4 = $Category->CountGallery($strRsSubCat3->category_id);
                    print "<li><span onclick=ShowImages('" . $strRsSubCat3->category_id . "')>" . $strRsSubCat3->category_name . " (" . $count4 . ")" . "</span> <br>&nbsp;&nbsp;<span style='width: 100%' id='Image-" . $strRsSubCat3->category_id . "'></span></li>";
                }
                print "</ul></li>";
            }
            print "</ul></li>";
        }
        print "</ul><hr style='border-bottom:1px #999999 dotted;'></li>";
    }
    ?>
</ul>