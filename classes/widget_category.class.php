<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");


class WidgetCategory
{
	public $widget_category_id, $widget_category, $category_description, $strQuery;
	
	public function WidgetCategory()
	{
		# Constructor
		$this->widget_category_id=0;
		$this->widget_category='';
		$this->category_description='';
		$this->strQuery;
	}
	
	public function setVal($args)
	{
		# Method to assign value for the variables defined in this calss.
		$this->widget_category_id=mysql_escape_string($args['widget_category_id']);
		$this->widget_category=mysql_escape_string($args['widget_category']);
		$this->category_description=mysql_escape_string($args['category_description']);
	}
	
	public function Insert()
	{
		# Method to insert record into Database.
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
	
	public function ListWidgetCategory()
	{
		print '<option value="0">Select Node Category</option>';
		$DB=new DB;
		$strSQL="Select * from t_widget_category where delete_flag=0 order  by widget_category asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{
			print '<option value="'.$strRsCategory->widget_category_id.'">'.$strRsCategory->widget_category.'</option>';
		}
	}
	
	public function ListWidgetCategoryWithWidget()
	{
		print '<option value="0">Select Node Category</option>';
		$DB=new DB;
		$strSQL="Select * from t_widget_category where order by widget_category asc";	
		$strRsCategoryArr=$DB->Returns($strSQL);		
		while($strRsCategory=mysql_fetch_object($strRsCategoryArr))
		{			
			$strSQL="Select * from t_widgets where widget_category_id=".$strRsCategory->widget_category_id;
			$strRsWidgetListArr=$DB->Returns($strSQL);
			while($strRsWidgetList=mysql_fetch_object($strRsWidgetListArr))
			{
				print '<option value="'.$strRsWidgetList->widget_id.'">'.$strRsCategory->widget_category.'-> '.$strRsWidgetList->widget_name.'</option>';
			}
		}
	}
	
}

?>