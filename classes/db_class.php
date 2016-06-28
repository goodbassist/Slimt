<?php if (!defined('BASEURL')) exit('No Direct access to page');

class db_class
{

	public $link;
	
	
	public static function getInstance()
	{
		static $instance = null;
		if($instance === null)
		{
			$instance = new db_class();
		}
	
	return $instance;
	}
	
	public function getInstance2()
	{
		static $instance = null;
		if($instance === null)
		{
			$instance = new db_class();
		}
	
	return $instance;
	}
	
	public function db_connector()
	{
		
		// connect to the db
		$this->link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
		$con = mysql_select_db(DB_NAME);		
	}
	
	function find($query) 
	{
		$ret = mysql_query($query);
		if (mysql_num_rows($ret) == 0)
		return array();
		$retArray = array();
				
		while ($row = mysql_fetch_array($ret))
		$retArray[] = $row;
		
		return $retArray;
	}
	
	function insert($query) 
	{
		$ret = mysql_query($query,$this->link);
		
		if (mysql_affected_rows() < 1)
		return false;
		return true;
	}
	
	function query($query) 
	{
		return mysql_query($query);
	}
	
	function fetchArray($result) 
	{	
		return mysql_fetch_array($result);
	}
	
	function close() 
	{
		mysql_close($this->link);
	}
	
	function count_result($result)
	{
		return mysql_num_rows($result);
	}
	
	function exists($query) 
	{
		$ret = mysql_query($query);
		if (mysql_num_rows($ret) > 0)
		return true;
		return false;
	}
	
	function last_id($query) 
	{
		return mysql_insert_id($query);
	}
	
	function count_res($que)
	{
		$ret = mysql_query($que);
		$num = mysql_num_rows($ret);
		return $num;	
	}
	
	function free_memory()
	{
		return mysql_free_result($query);
	}
	

}

?>