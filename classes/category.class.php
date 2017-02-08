<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");


class Category
{
	public $category_id, $parent_id, $category_name, $category_description, $strQuery;
	
	public function Category()
	{
		$this->category_id=0;
		$this->parent_id=0;
		$this->category_name='';
		$this->category_description='';
		$this->strQuery;
	}
	
	public function setVal($args)
	{
		$this->category_id=mysql_escape_string($args['category_id']);
		$this->parent_id=mysql_escape_string($args['parent_id']);
		$this->category_name=mysql_escape_string($args['category_name']);
		$this->category_description=mysql_escape_string($args['category_description']);
	}
	
	public function Insert()
	{
		$DB = new DB;
		$this->strQuery="Insert into t_category (parent_id,category_name,category_description)
		Values (".$this->parent_id.",'".$this->category_name."','".$this->category_description."')";		
		$DB->Execute($this->strQuery);
		
	}
	
	public function Update()
	{
		$DB = new DB;
		$this->strQuery="Update t_category  set parent_id=".$this->parent_id.", category_name='".$this->category_name."' where category_id=".$this->category_id;
		$DB->Execute($this->strQuery);		
	}
	
	public function ListCategory()
	{
		print '<option value="0">Select Category</option>';
		$DB=new DB;
		$strSQL="Select * from t_category where parent_id=0 order  by category_name asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{
			print '<option value="'.$strRsCategory->category_id.'">'.$strRsCategory->category_name.'</option>';
			$strSQL="Select * from t_category where parent_id=".$strRsCategory->category_id." order  by category_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				print '<option value="'.$strRsSubCat1->category_id.'">&nbsp;&nbsp;=>'.$strRsSubCat1->category_name.'</option>';		
				
				$strSQL="Select * from t_category where parent_id=".$strRsSubCat1->category_id." order  by category_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					print '<option value="'.$strRsSubCat2->category_id.'">&nbsp;&nbsp;&nbsp;&nbsp;=>'.$strRsSubCat2->category_name.'</option>';
					$strSQL="Select * from t_category where parent_id=".$strRsSubCat2->category_id." order  by category_name asc";	
					$strRsSubCat3Arr=$DB->Returns($strSQL);
					while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
					{
						print '<option value="'.$strRsSubCat3->category_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;'.$strRsSubCat3->category_name.'</option>';	
					}	
				}
			
			}
		}
	}
	
	
	
	public function CountGallery($strCategoryID)
	{
		$DB=new DB;
		$arrCat=array($strCategoryID);
		
		$strSQL="Select category_id from t_category where parent_id=$strCategoryID";
		$strRsCat1Arr=$DB->Returns($strSQL);
		while($strRsCat1=mysql_fetch_object($strRsCat1Arr))
		{
			$arrCat[]=$strRsCat1->category_id;
			$strSQL="Select category_id from t_category where parent_id=".$strRsCat1->category_id;
			$strRsCat2Arr=$DB->Returns($strSQL);
			while($strRsCat2=mysql_fetch_object($strRsCat2Arr))
			{
				$arrCat[]=$strRsCat2->category_id;
			}
		}
		
		if(count($arrCat)<=1)
		{
			$arrCat=$strCategoryID;
		}
		else
		{
			$arrCat=implode(",",$arrCat);
		}
		$strSQL="Select Count(*) as Total from t_control_image where category_id in($arrCat)";
		$strRsCountArr=$DB->Returns($strSQL);
		if($strRsCount=mysql_fetch_object($strRsCountArr))
		{
			return $strRsCount->Total;
		}
		return 0;
	}
	
	
	public function ListCategoryWithNumberOfImages()
	{
		print '<option value="0">Select Category</option>';
		$DB=new DB;
		$strSQL="Select * from t_category where parent_id=0 order  by category_name asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{
			print '<option value="'.$strRsCategory->category_id.'">'.$strRsCategory->category_name.' ('.$this->CountGallery($strRsCategory->category_id).') </option>';
			$strSQL="Select * from t_category where parent_id=".$strRsCategory->category_id." order  by category_name asc";	
			$strRsSubCat1Arr=$DB->Returns($strSQL);
			while($strRsSubCat1=mysql_fetch_object($strRsSubCat1Arr))
			{
				print '<option value="'.$strRsSubCat1->category_id.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$strRsSubCat1->category_name.' ('.$this->CountGallery($strRsSubCat1->category_id).') </option>';		
				
				$strSQL="Select * from t_category where parent_id=".$strRsSubCat1->category_id." order  by category_name asc";	
				$strRsSubCat2Arr=$DB->Returns($strSQL);
				while($strRsSubCat2=mysql_fetch_object($strRsSubCat2Arr))
				{
					print '<option value="'.$strRsSubCat2->category_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$strRsSubCat2->category_name.' ('.$this->CountGallery($strRsSubCat2->category_id).') </option>';
					$strSQL="Select * from t_category where parent_id=".$strRsSubCat2->category_id." order  by category_name asc";	
					$strRsSubCat3Arr=$DB->Returns($strSQL);
					while($strRsSubCat3=mysql_fetch_object($strRsSubCat3Arr))
					{
						print '<option value="'.$strRsSubCat3->category_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$strRsSubCat3->category_name.' ('.$this->CountGallery($strRsSubCat3->category_id).') </option>';	
					}	
				}
			
			}
		}
	}
	
	
}

?>