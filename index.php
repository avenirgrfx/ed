<?php
ob_start();
session_start();
require_once('configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
require_once(AbsPath . "classes/customer.class.php");

if ($_SESSION['user_login']->login_id == "") {
    Globals::SendURL(URL . 'login.php');
}

if ($_SESSION['user_login']->ADMIN_ACCESS != 1) {
    Globals::SendURL(URL . 'login.php');
}

/* print "<pre>";
  print_r($_SESSION['user_login']);
  print "</pre>"; */

$DB = new DB;
$Category = new Category;
$System = new System;
$Gallery = new Gallery;
$Client = new Client;


if ($_POST['type'] == 'Category') {
    $Category->parent_id = $_POST['ddlCategroy'];
    $Category->category_name = $_POST['txtCategroyName'];
    if ($_POST['Category_ID'] == '') {
        $Category->Insert();
    } else {
        $Category->category_id = $_POST['Category_ID'];
        $Category->Update();
    }
    Globals::SendURL(URL . "?type=category");
} elseif ($_POST['type'] == 'System') {
    $System->parent_id = $_POST['ddlSystem'];
    $System->system_name = $_POST['txtSystemName'];
    $System->has_node = ($_POST['chkHasWidget'] == "" ? 0 : 1);
    if ($_POST['System_ID'] == '') {
        $System->Insert();
    } else {
        $System->system_id = $_POST['System_ID'];
        $System->Update();
    }
    Globals::SendURL(URL . "?type=system");
} elseif ($_POST['type'] == 'Gallery') {
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

    $Gallery->created_by = 1;
    $Gallery->modified_by = 1;

    $Gallery->Insert();
    Globals::SendURL(URL);
} elseif ($_POST['type'] == 'Customer') {
    # Project	
    $Logo = '';


    $strPrefix = date("dmy");

    if ($_FILES['file1']['name']) {
        $uploaddir = AbsPath . '/uploads/customer/';
        $uploadfile = $uploaddir . $strPrefix . basename($_FILES['file1']['name']);

        if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploadfile)) {
            $Logo = $strPrefix . $_FILES['file1']['name'];
        } else {
            echo "Couldn't upload the file!\n";
            $Logo = '';
        }
    } else {
        $Logo = $company_logo;
    }



    //Globals::PrintArray($_POST);
    if ($_POST['txtClientType'] <> '') {
        $strSQL = "Insert into t_client_type (client_type, active_flag) values ('" . $_POST['txtClientType'] . "',1)";
        $iClientType = $DB->Execute($strSQL);
    } else {
        $iClientTypeArr = explode("~", $_POST['ddlClientType']);
        $iClientType = $iClientTypeArr[1];
    }

    $strVersion = $_POST['ddlVersion'];
    if ($strVersion == '')
        $strVersion = 0;

    $myArr = array(
        'distributor_id' => 0,
        'client_type' => $iClientType,
        'software_version_id' => $strVersion,
        'client_name' => $_POST['txtClientName'],
        'email_address' => $_POST['txtEmailAddress'],
        'password' => md5($_POST['txtPassword']),
        'address_line_1' => $_POST['txtAddress_Line1'],
        'address_line_2' => $_POST['txtAddress_Line2'],
        'city' => $_POST['txtCity'],
        'state' => $_POST['txtState'],
        'zip' => $_POST['txtZip'],
        'country' => $_POST['txtCountry'],
        'created_by' => 1,
        'modified_by' => 1,
        'phone' => $_POST['txtPhone'],
        'website' => $_POST['txtWebsite'],
        'contact_name' => $_POST['txtContactName'],
        'contact_title' => $_POST['txtDesignation'],
        'contact_email' => $_POST['txtContactEmail'],
        'manager_name' => $_POST['txtManagerName'],
        'manager_email' => $_POST['txtManagerEmail'],
        'manager_phone' => $_POST['txtManagerPhone'],
        'logo' => $Logo
    );




    $Client->setVal($myArr);
    if ($_POST['client_id'] == '' or $_POST['client_id'] == '0') {
        $Client->Insert();
    } else {
        if ($_POST['txtPassword'] == '') {
            $Client->password = '';
        }
        $Client->client_id = $_POST['client_id'];
        $Client->Update();
    }
    Globals::SendURL(URL . "?type=project");
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
    <head>
        <meta charset="utf-8">

        <title>energyDAS Administrator</title>

        <link rel="stylesheet" href="css/prism.css">
        <link rel="stylesheet" href="css/bootstrap.css">	
        <link rel="stylesheet" href="css/master.css">
        <link rel="stylesheet" href="css/tree.css">
        <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type='text/javascript' src="js/jquery.js"></script>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
			
                $('#Engineer_Main_Menu').click(function(){
                    window.location='<?php echo URL ?>engineers/';
                });
               $('#Controls_Main_Menu').click(function(){ window.location.href='<?php echo URL?>controls/'; });
			
                $('#Customer_Main_Menu').click(function(){
                    window.location='<?php echo URL ?>customer/';
                });
			
                $('#Home_Main_Menu').click(function(){
                    window.location='<?php echo URL ?>home.php';
                });
			
			
                $('#master_system_menu').click(function(){
                    $('#master_equipment_category').css('display','none');
                    $('#master_system').css('display','block');
                    $('#showSystem').trigger("click");
                    $('#master_equipment_category_menu').removeClass('active');
                    $('#master_system_menu').addClass('active');
                });
			
                $('#master_equipment_category_menu').click(function(){
                    $('#master_system').css('display','none');
                    $('#master_equipment_category').css('display','block');	
                    $('#showCategory').trigger("click");
				
                    $('#master_equipment_category_menu').addClass('active');
                    $('#master_system_menu').removeClass('active');
							
                });
			
                $('#showCategory').click(function(){
                    $('#Category_Container').html("Loading please wait...");
				
                    $('#Gallery_Container').slideUp();
                    $('#Password_Container').slideUp();
				
                    var id=0;
                    $.get("ajax_pages/fetch_category.php",
                    {
                        id:id				
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                  });
				
				
                });
			
			
                $('#showGallery').click(function(){
                    $('#Gallery_Container').html("Loading please wait...");
                    $('#Password_Container').slideUp();
                    $('#Category_Container').slideUp();
				
                    var id=0;
                    $.get("ajax_pages/fetch_image.php",
                    {
                        id:id				
                    },
                    function(data,status){						
                        $('#Gallery_Container').html(
                             data
                            );
                    $('#Gallery_Container').slideDown();
							
                  });
				
                });
			
			
                $('#showPassword').click(function(){
                    $('#Category_Container').slideUp();
                    $('#Gallery_Container').slideUp();
                    $('#Password_Container').slideDown();
                });
			
                $('#showSystem').click(function(){
			
                    $('#Category_Container').html("Loading please wait...");
				
                    $('#Gallery_Container').slideUp();				
				
                    var id=0;
                    $.get("ajax_pages/fetch_system.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
				
                });
			
			
                $('.showNewProject').click(function(){
				
				
                    $('#Category_Container').html("Loading please wait...");
				
                    $('#Gallery_Container').slideUp();
				
                    var id=0;
                    $.get("ajax_pages/add_new_project.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
				
                });
			
			
			
                $('#new_package_menu').click(function(){				
                    $('#Category_Container').html("Loading please wait...");				
                    $('#Gallery_Container').slideUp();				
                    var id=0;
                    $.get("ajax_pages/add_new_package.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
				
                });
			
                $('#new_package_price_menu').click(function(){				
                    $('#Category_Container').html("Loading please wait...");				
                    $('#Gallery_Container').slideUp();				
                    var id=0;
                    $.get("ajax_pages/add_new_package_price.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
				
                });			
			
			
                $('#new_package_credit').click(function(){				
                    $('#Category_Container').html("Loading please wait...");				
                    $('#Gallery_Container').slideUp();				
                    var id=0;
                    $.get("ajax_pages/add_new_package_credit.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
				
                });
			
                $('#new_package_view').click(function(){				
                    $('#Category_Container').html("Loading please wait...");				
                    $('#Gallery_Container').slideUp();				
                    var id=0;
                    $.get("ajax_pages/add_new_package_view.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
				
                });
			
			
			
			
                $('#Projects_Menu').click(function(){
                    // Show Project Menu and Sub Menu
                    $('#projets_new_project').css('display','none');
                    $('.Projects_Menu').css('display','block');
                    $('.System_Menu').css('display', 'none');
                    $('.Sales_Menu').css('display', 'none');
                    $('.User_Accounts_Menu').css('display', 'none');

                    $('#master_equipment_category').css('display', 'none');
                    $('#master_system').css('display', 'none');

                    $('#Projects_Menu').addClass('active');
                    $('#System_Menu').removeClass('active');
                    $('#Sales_Menu').removeClass('active');
                    $('#User_Accounts_Menu').removeClass('active');
                    
                    $('#new_project_menu').addClass('active');
                    $('#new_portfolio_menu').removeClass('active');

                });

                $('#new_project_menu').click(function () {
                    $('#Projects_Menu').trigger('click');
                });
                
                $('#new_portfolio_menu').click(function () {
                    $('#new_portfolio_menu').addClass('active');
                    $('#new_project_menu').removeClass('active');
                    
                    $('#Category_Container').html("Loading please wait...");
				
                    $('#Gallery_Container').slideUp();
				
                    var id=0;
                    $.get("ajax_pages/portfolio_manager/home.php",
                    {
                        id:id
                    },
                    function(data,status){						
                        $('#Category_Container').html(
                             data
                            );
                    $('#Category_Container').slideDown();
							
                   });	
                });

                $('#System_Menu').click(function () {

                    // Show System Menu and Sub Menu				
                    $('#projets_new_project').css('display', 'none');
                    $('.Projects_Menu').css('display', 'none');
                    $('.System_Menu').css('display', 'block');
                    $('.Sales_Menu').css('display', 'none');
                    $('.User_Accounts_Menu').css('display', 'none');

                    $('#Projects_Menu').removeClass('active');
                    $('#System_Menu').addClass('active');
                    $('#Sales_Menu').removeClass('active');
                    $('#User_Accounts_Menu').removeClass('active');

                    $('#showCategory').trigger('click');


                });

                $('#Sales_Menu').click(function () {
                    $('#projets_new_project').css('display', 'none');
                    $('.Projects_Menu').css('display', 'none');
                    $('.System_Menu').css('display', 'none');
                    $('.Sales_Menu').css('display', 'block');
                    $('.User_Accounts_Menu').css('display', 'none');

                    $('#Projects_Menu').removeClass('active');
                    $('#System_Menu').removeClass('active');
                    $('#Sales_Menu').addClass('active');
                    $('#User_Accounts_Menu').removeClass('active');

                    $('#new_package_menu').trigger('click');
                });

                $('#User_Accounts_Menu').click(function () {
                    $('#projets_new_project').css('display', 'none');
                    $('.Projects_Menu').css('display', 'none');
                    $('.System_Menu').css('display', 'none');
                    $('.Sales_Menu').css('display', 'none');
                    $('.User_Accounts_Menu').css('display', 'block');

                    $('#Projects_Menu').removeClass('active');
                    $('#System_Menu').removeClass('active');
                    $('#Sales_Menu').removeClass('active');
                    $('#User_Accounts_Menu').addClass('active');

                    $('#new_user_access_menu').trigger('click');
                });

                $('#new_user_access_menu').click(function () {
                    $('#Category_Container').html('Loading...');

                    $.get('<?php echo URL ?>ajax_pages/user_access.php', {}, function (data) {
                        $('#Category_Container').html(data);
                    });
                });


                $('#new_user_menu').click(function () {
                    $('#Category_Container').html('Loading...');
                    $.get('<?php echo URL ?>ajax_pages/users.php', {}, function (data) {
                        $('#Category_Container').html(data);
                    });
                });

                <?php
                if (Globals::Get('type') == 'category' or Globals::Get('type') == '') {
                    ?>
                    $('.showNewProject').trigger("click");
                    <?php
                } elseif (Globals::Get('type') == 'system') {
                    ?>
                    $('#showSystem').trigger("click");
                    <?php
                } elseif (Globals::Get('type') == 'project') {
                    ?>
                    $('#Projects_Menu').trigger("click");
                    <?php
                } elseif (Globals::Get('type') == 'package') {
                    ?>
                    $('#new_package_menu').trigger("click");

                    <?php
                } elseif (Globals::Get('type') == 'package_credit') {
                    ?>
                    $('#new_package_credit').trigger("click");
                <?php } ?>

            });


            function EditProject(strID)
            {
                $.get("ajax_pages/add_new_project.php",
                        {
                            id: strID
                        },
                function (data, status) {
                    $('#Category_Container').html(
                            data
                            );
                    $('#Category_Container').slideDown();

                });
            }

            function ProjectDetails(strID)
            {
                $.get("ajax_pages/project_details.php",
                        {
                            id: strID
                        },
                function (data, status) {
                    $('#Category_Container').html(data);
                    $('#Category_Container').slideDown();

                });
            }


            function CategoryOptions(strCatID)
            {
                $.get("ajax_pages/category_details.php",
                        {
                            id: strCatID
                        },
                function (data, status) {
                    data = data.split("~#~");
                    $('[name=ddlCategroy]').val(data[0]);
                    $('#txtCategroyName').val(data[1]);
                    $('#Category_ID').val(data[2]);
                    $('#btnSubmit').val('Update');
                    $('#btnDelete').val('Delete');
                    $('#btnDelete').css('display', 'block');
                    $('#CannotDelete').css("display", 'none');
                    $('#btnDelete').attr("disabled", false);
                    if (data[3] > 0)
                    {
                        $('#CannotDelete').html("There are " + data[3] + " images in Category. Cannot Delete Category");
                        $('#CannotDelete').css("display", 'block');
                        $('#btnDelete').css("display", 'none');
                    }

                    $('#btnDelete').click(function () {
                        if (!confirm("Are you sure you want to Delete " + data[1] + " ?"))
                        {

                        }
                        else
                        {
                            $.get("ajax_pages/category_delete.php",
                                    {
                                        id: data[2]
                                    },
                            function (data1, status1)
                            {
                                $('#showCategory').trigger("click");
                            });

                        }
                    });

                });
            }


            function SystemOptions(strCatID)
            {
                $.get("ajax_pages/system_details.php",
                        {
                            id: strCatID
                        },
                function (data, status) {
                    data = data.split("~#~");
                    $('[name=ddlSystem]').val(data[0]);
                    $('#txtSystemName').val(data[1]);
                    $('#System_ID').val(data[2]);
                    if (data[4] == 1)
                    {
                        $('#chkHasWidget').prop('checked', true);
                    }
                    else
                    {
                        $('#chkHasWidget').prop('checked', false);
                    }
                    $('#btnSubmit').val('Update');
                    $('#btnDelete').val('Delete');
                    $('#btnDelete').css('display', 'block');
                    $('#CannotDelete').css("display", 'none');
                    $('#btnDelete').attr("disabled", false);
                    if (data[3] > 0)
                    {
                        $('#CannotDelete').html("There are " + data[3] + " images in Category. Cannot Delete Category");
                        $('#CannotDelete').css("display", 'block');
                        $('#btnDelete').css("display", 'none');
                    }

                    $('#btnDelete').click(function () {
                        if (!confirm("Are you sure you want to Delete " + data[1] + " ?"))
                        {

                        }
                        else
                        {
                            $.get("ajax_pages/system_delete.php",
                                    {
                                        id: data[2]
                                    },
                            function (data1, status1)
                            {
                                alert("Deleted");
                                $('#showSystem').trigger("click");
                            });

                        }
                    });

                });
            }

            function PreviewImage()
            {
                var Desc = document.getElementById('txtGalleryDescription').value;
                Desc = Desc.substr(0, 50);
                document.getElementById('Show_Image_Name').innerHTML = document.getElementById('txtGalleryName').value;
                document.getElementById('Show_Image_Description').innerHTML = Desc;
                document.getElementById('Show_Image_Tags').innerHTML = document.getElementById('txtTagName').value;

                var e = document.getElementById("ddlImageCategroy");
                var strUser = e.options[e.selectedIndex].text;

                document.getElementById('Show_Image_Category').innerHTML = strUser.replace("=>", "");

                var oFReader = new FileReader();
                oFReader.readAsDataURL(document.getElementById("file1").files[0]);

                oFReader.onload = function (oFREvent)
                {
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                };
            };

            function ShowImages(strCatID)
            {
                $.get("ajax_pages/images_by_category.php",
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

            function DeleteImage(strImageID, strCatID)
            {
                if (!confirm("Are you sure you want to Delete?"))
                    return;

                $.get("ajax_pages/delete_image.php",
                        {
                            id: strImageID
                        },
                function (data, status) {

                    ShowImages(strCatID);
                });
            }

            function EditDescription(strImageID)
            {
                var a = 'Description-' + strImageID;
                var b = 'edit-desc-' + strImageID;
                var Text = document.getElementById(a).innerHTML;

                if (document.getElementById(b).value == "")
                {
                    document.getElementById(a).innerHTML = '<textarea style="width:250px; height:80px;" name="edit-desc-text-' + strImageID + '" id="edit-desc-text-' + strImageID + '">' + Text + '</textarea><br/><a href="javascript:UpdateImageDesc(' + strImageID + ')">Update</a>';
                    document.getElementById(b).value = strImageID;
                }
            }

            function UpdateImageDesc(strImageID)
            {
                $.post("ajax_pages/update_image_desc.php",
                        {
                            id: strImageID,
                            desc: document.getElementById('edit-desc-text-' + strImageID).value
                        },
                function (data, status) {
                    var a = 'Description-' + strImageID;
                    document.getElementById(a).innerHTML = document.getElementById('edit-desc-text-' + strImageID).value;
                    var b = 'edit-desc-' + strImageID;
                    document.getElementById(b).value = "";
                });
            }


            function EditImageTitle(strImageID)
            {
                var a = 'ImageTitle-Edit-' + strImageID;
                var b = 'edit-image-title-' + strImageID;
                var Text = document.getElementById(a).innerHTML;

                if (document.getElementById(b).value == "")
                {
                    document.getElementById(a).innerHTML = '<input type="text" name="edit-image-title-val-' + strImageID + '" id="edit-image-title-val-' + strImageID + '" value="' + Text + '" /><a href="javascript:UpdateImageTitle(' + strImageID + ')">Update</a>';
                    document.getElementById(b).value = strImageID;
                }
            }

            function UpdateImageTitle(strImageID)
            {
                $.post("ajax_pages/update_image_title.php",
                        {
                            id: strImageID,
                            image_name: document.getElementById('edit-image-title-val-' + strImageID).value
                        },
                function (data, status) {
                    var a = 'ImageTitle-Edit-' + strImageID;
                    document.getElementById(a).innerHTML = document.getElementById('edit-image-title-val-' + strImageID).value;
                    var b = 'edit-image-title-' + strImageID;
                    document.getElementById(b).value = "";
                });
            }
        </script>
    </head>
    <body>

        <div id="MainContainer">

            <div id="Logo">
                <a href="<?php echo URL ?>"><img src="images/logo.png" border="0"  width="185px" height="70px" /></a>
            </div>
            <div>
                <div class="TopMenu" id="Home_Main_Menu">Home</div>
                <div class="TopMenu TopMenu_active">Administrator</div>
                <div class="TopMenu" id="Engineer_Main_Menu">Engineer</div>
                <div class="TopMenu" id="Controls_Main_Menu">Controls</div>
                <!--<div class="TopMenu" id="Customer_Main_Menu">USER</div>-->

                <div class="GreetingsMenu" style="float:right; margin-left:1%; margin-right:1%;">
                    <?php echo $_SESSION['user_login']->user_full_name; ?> - <?php echo $_SESSION['user_login']->user_position; ?><br>
                    <a href="#">Change Password</a> | <a href="<?php echo URL ?>logout.php">Logout</a>
                </div>

                <div style="float:right;text-align:right;width:13%;position:relative;top:30%;">
                    <img style="width:74%;" src="images/energydas-ticket.png" />
                </div>
                <div style="float:right; text-align:right;width:13%;position:relative;top:28%;right:-3%;">
                    <img style="width:75%;" src="<?php echo URL; ?>images/energydas_coms.png" />
                </div>
                <div class="clear"></div>
            </div>


            <div id="Menu">
                <ul>
                    <li id="Projects_Menu" class="active LargeMenu showNewProject" style="margin-right:30px;">Clients</li>        

                    <?php if (in_array('MasterSystem', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="System_Menu" style="margin-right:30px;" class="LargeMenu">Systems</li>
                    <?php } ?>

                    <li id="Sales_Menu" style="margin-right:30px;" class="LargeMenu">Sales</li>    
                    <!--<li id="EnergyDas_Menu" style="margin-right:30px;" class="LargeMenu">Energydas</li>-->
                    <!--<li id="Accounts_Menu" style="margin-right:30px;" class="LargeMenu">Accounts</li>-->

                    <?php if (in_array('User_Access', $_SESSION['user_login']->ALLOWED_ACCCESS) or in_array('Users', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>        
                        <li id="User_Accounts_Menu" style="margin-right:30px; float:right;" class="LargeMenu">User Accounts</li>
                    <?php } ?>
                </ul>

                <div class="clear"></div>

            </div>

            <div id="Menu" style="border-top:1px solid #EFEFEF;">
                <ul class="System_Menu">
<!--                    <li id="master_system_menu">Master Systems</li>-->
                    <li id="master_equipment_category_menu" class="active">Master Equipment Category</li>
                </ul>

                <ul class="Projects_Menu" style="display:none;">
                    <?php if (in_array('ClientList', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_project_menu" class="active">Client List</li>
                    <?php } ?>
                    <?php if (in_array('ClientList', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_portfolio_menu">Portfolio Manager</li>
                    <?php } ?>
                </ul>

                <ul class="Sales_Menu" style="display:none;">
                    <?php if (in_array('PackageManager', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_package_menu" class="active">Package Manager</li>
                    <?php } ?>

                    <?php if (in_array('PackagePriceManager', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>            
                        <li id="new_package_price_menu" class="active">Package Price Manager</li>
                    <?php } ?>

                    <?php if (in_array('PackageCredit', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_package_credit" class="active">Package Credit</li>
                    <?php } ?>             

                    <?php if (in_array('PackageView', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_package_view" class="active">Package View</li>
                    <?php } ?>
                </ul>

                <ul class="User_Accounts_Menu" style="display:none;">
                    <?php if (in_array('Users', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_user_menu" class="active">Users</li>
                    <?php } ?>
                    <?php if (in_array('User_Access', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="new_user_access_menu" class="active">User Access</li>
                    <?php } ?>

                </ul>

                <div class="clear"></div>
            </div>

            <div class="BottomMenu" id="master_equipment_category" style="display:none;"> 
                <ul class="System_Menu">

                    <?php if (in_array('MasterEquipment', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="showCategory">Master Equipment Management</li>
                    <?php } ?>      

                    <?php if (in_array('Master_Equipment_Gallery', $_SESSION['user_login']->ALLOWED_ACCCESS)) { ?>
                        <li id="showGallery">Master Equipment Gallery</li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>

            <!--<div class="BottomMenu" id="master_system" style="display:none;">
             <ul class="System_Menu">
                 <li id="showSystem">Master System Management</li>        
             </ul>
             <div class="clear"></div>
           </div>-->


            <div class="BottomMenu" id="projets_new_project" style="display:none;">
                <ul class="Projects_Menu">
                    <li id="showNewProject">Add New Project</li>
                    <li id="showProjectDetails">Project Details</li>
                </ul>
                <div class="clear"></div>
            </div>

            <div id="Category_Container" style="display:block;" class="ShowDynamicContent">

            </div>

            <div id="Gallery_Container" style="display:none;" class="ShowDynamicContent">     

            </div>

            <div id="Password_Container" style="display:none;" class="ShowDynamicContent">
                Password
            </div>

        </div>

    </body>
</html>
