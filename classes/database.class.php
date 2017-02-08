<?php
class DB
{
	public $Records,$Con;
	function DB()
	{		
	}
	
	public function Connect()
	{		
		$this->Con=mysql_connect(Database_Host, Database_User, Database_Password) or die(mysql_error());		
		mysql_select_db(Database_Name) or die(mysql_error());
	}
	
	public function Execute($strQuery)
	{
		$this->Connect();
		mysql_query($strQuery) or die(mysql_error());	
			
		return mysql_insert_id();
		
	}
	
	public function Returns($strQuery)
	{
		$this->connect();
		$this->Records=mysql_query($strQuery) or die(mysql_error());
		$this->close();			
		return $this->Records;
	}
	
	public function Total($strQuery)
	{
		$this->Connect();		
		return mysql_num_rows(mysql_query($strQuery));
	}
	
	public function Close()
	{
		mysql_close($this->Con);
	}
	
	
	public function Lists($args)
	{
		
		$this->strQuery=$args['Query'];		
		$this->TotalRecord=$this->Total($this->strQuery);		
		if($args['SO'])
			$this->strQuery.=" Order by ".$args['SO'];
					
		if($args['Num'])
		{
			if($args['P'])
			{				
				$this->strQuery.=" LIMIT ".(($args['P']*$args['Num'])-$args['Num']).", ".$args['Num'];
			}
			else
			{
				$this->strQuery.=" LIMIT 0,".$args['Num'];
			}
		}
		
		
		$Result=$this->Returns($this->strQuery);
		$i=0;
		while($Results=mysql_fetch_object($Result))
		{
			$Value[$i]=$Results;
			$i++;
		}
		return $Value;		
	}
	
}
?>