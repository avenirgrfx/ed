<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");


class Gallery
{
	public $image_id, $category_id, $image_path,$image_path2, $image_name, $image_tags, $image_description, $technical_file1, 
	$technical_file2, $technical_file3, $technical_file4, $technical_file5, $doc, $dom, $created_by, $modified_by, $strQuery,$td_file1,$td_file2,$td_file3;
	
	public function Gallery()
	{
		$this->image_id=0;
		$this->category_id=0;
		$this->image_path='';
        $this->image_path2='';
		$this->image_name='';
		$this->image_tags='';		
		$this->image_description='';
		$this->technical_file1='';
		$this->technical_file2='';
		$this->technical_file3='';
		$this->technical_file4='';
		$this->technical_file5='';
        $this->	td_file1='';
		$this->	td_file2='';
		$this->	td_file3='';
	
		$this->doc=date("Y-m-d 00:00:00");
		$this->dom=date("Y-m-d 00:00:00");
		$this->created_by=0;
		$this->modified_by=0;
		
		$this->strQuery;
        
        // keys for building image created by saurabh
       $this->building_id="";
       $this->site_id="";
       $this->building_image1="";
       $this->building_image2="";
       $this->building_image3="";
       $this->building_image4="";
       $this->building_image5="";
        
        
    }
	
	public function setVal($args)
	{
		$this->image_id=mysql_escape_string($args['image_id']);
		$this->category_id=mysql_escape_string($args['category_id']);
		$this->image_path=mysql_escape_string($args['image_path']);
        $this->image_path2=mysql_escape_string($args['image_path2']);
		$this->image_name=mysql_escape_string($args['image_name']);
		$this->image_tags=mysql_escape_string($args['image_tags']);		
		$this->image_description=mysql_escape_string($args['image_description']);
		
		$this->technical_file1=mysql_escape_string($args['technical_file1']);
		$this->technical_file2=mysql_escape_string($args['technical_file2']);
		$this->technical_file3=mysql_escape_string($args['technical_file3']);
		$this->technical_file4=mysql_escape_string($args['technical_file4']);
		$this->technical_file5=mysql_escape_string($args['technical_file5']);
        $this->td_file1=mysql_escape_string($args['td_file1']);
		$this->td_file2=mysql_escape_string($args['td_file2']);
		$this->td_file3=mysql_escape_string($args['td_file3']);
		
		$this->doc=mysql_escape_string($args['doc']);
		$this->dom=mysql_escape_string($args['dom']);
		$this->created_by=mysql_escape_string($args['created_by']);
		$this->modified_by=mysql_escape_string($args['modified_by']);
	}
	
	public function Insert()
	{    
         
		$DB = new DB;
		$this->strQuery="Insert into t_control_image (category_id,image_path,image_path2,image_name, image_tags, image_description, technical_file1, technical_file2, technical_file3, technical_file4, technical_file5, technical_file6, td_file1, td_file2, td_file3, doc, dom, created_by, modified_by)
		Values (".$this->category_id.",'".$this->image_path."','".$this->image_path2."','".$this->image_name."','".$this->image_tags."', '".$this->image_description."', '".$this->technical_file1."','".$this->technical_file2."','".$this->technical_file3."','".$this->technical_file4."','".$this->technical_file5."', '".$this->technical_file6."', '".$this->td_file1."', '".$this->td_file2."', '".$this->td_file3."', now(), now(),".$this->created_by.",".$this->modified_by.")";		
		
		
		$DB->Execute($this->strQuery);
		
	}
    
    public function InsertBuildingImage(){
        $DB = new DB;
   		$this->strQuery="Insert into t_building_image (building_id,building_image1,building_image2,building_image3,building_image4,building_image5)
		Values (".$this->building_id.",'".$this->building_image1."','".$this->building_image2."','".$this->building_image3."', '".$this->building_image4."', '".$this->building_image5."')";		
		
		
		$DB->Execute($this->strQuery);
		
    }
    public function UpdateBuildingImage($building_id,$technical_file1,$id){
        $DB = new DB;
         if($id=="file2_1"){
        $Gallery->building_image1 = $technical_file1;
        $this->strQuery="update t_building_image  set building_image1=$Gallery->building_image1) where building_id=$building_id";
        }elseif($id=="file2_2"){
         $Gallery->building_image2 = $technical_file1;   
             $this->strQuery="update t_building_image  set building_image2=$Gallery->building_image2) where building_id=$building_id";
        }elseif($id=="file2_3"){
         $Gallery->building_image3 = $technical_file1;      
         $this->strQuery="update t_building_image  set building_image3=$Gallery->building_image3) where building_id=$building_id";
        }elseif($id=="file2_4"){
         $Gallery->building_image4 = $technical_file1;    
             $this->strQuery="update t_building_image  set building_image4=$Gallery->building_image4) where building_id=$building_id";
        }elseif($id=="file2_5"){
         $Gallery->building_image5 = $technical_file1;    
         $this->strQuery="update t_building_image  set building_image5=$Gallery->building_image5) where building_id=$building_id";
        }
   		
		
		
		
		$DB->Execute($this->strQuery);
    }
	
	public function ShowImage()
	{
		$DB = new DB;
		if($this->category_id<>0)
		{
			$this->strQuery="Select * from t_control_image where category_id=".$this->category_id;
		}
		else
		{
			$this->strQuery="Select * from t_control_image";
		}
		return $DB->Returns($this->strQuery);
	}
	
}

?>