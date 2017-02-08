<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;
?>

<script>
        $(function() {
            var $tree = $('#tree1');
			
			
			var data = [
							<?php
							
							$strSQL="Select * from t_category where parent_id=0 order  by category_name asc";	
							$strRsCategoryArr=$DB->Returns($strSQL);
							$iCount=1;
							while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
							{						
								$iCount++;
								$strSQL="Select * from t_category where parent_id=".$strRsCategory->category_id." order  by category_name asc";	
								$strRsSubCat1Arr=$DB->Returns($strSQL);
							?>
								{ label: '<?php echo $strRsCategory->category_name;?>', id:<?php echo $strRsCategory->category_id?> , 
									<?php if(mysql_num_rows($strRsSubCat1Arr)>0){?>
										children: [
											<?php while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr)){?>
											{label: '<?php echo $strRsSubCat1->category_name;?>', id:<?php echo $strRsSubCat1->category_id;?>,
												
												<?php 
												$strSQL="Select * from t_category where parent_id=".$strRsSubCat1->category_id." order  by category_name asc";	
												$strRsSubCat2Arr=$DB->Returns($strSQL);
												if(mysql_num_rows($strRsSubCat2Arr)>0){												
												?>
													children: [
													<?php while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr)){?>
													
													{label:'<?php echo $strRsSubCat2->category_name?>', id:<?php echo $strRsSubCat2->category_id ?>,
													
													<?php 
													$strSQL="Select * from t_category where parent_id=".$strRsSubCat2->category_id." order  by category_name asc";	
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
			$.get("ajax_pages/show_image.php",
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