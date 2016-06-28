<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//require_once('config.php');
class xDbclass {
	
	private $con;
	
	function __construct(){
		$this->connect();
	}
		
	public function connect(){
		$this->con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		if(!$this->con){
			die('Database Connection Failed!' . mysql_error());
		}else{
			mysql_select_db(DB_NAME,$this->con);
		}
	}
	
	public function query($sql){
		return mysql_query($sql);
	}
	
	public function fetch_array($query){
		return mysql_fetch_array($query);
	}
		public function fetch_assoc($query){
		return mysql_fetch_assoc($query);
	}
	
	public function num_rows($query){
		return mysql_num_rows($query);
	}
	
	public function close_connection(){
		if(isset($this->con)) {
			mysql_close($this->con);
			unset($this->con);
		}
	}
	
	public function find($query) {
		if(empty($query)) return false;
		$ret = mysql_query($query);
		if(!($ret)) {
			return false;
		}
		$retArray 		= array();
		while($row = mysql_fetch_array($ret)) {
			$retArray[] 		= $this->unstrip_array($row);
		}
		return $retArray;
	}
	
	function unstrip_array($array)
	{
		if(is_array($array))
		{
			foreach($array as &$val)
			{
				if(is_array($val))
				{
					$val = $this->unstrip_array($val);
				}else{
					$val = stripslashes($val);
				}
			}
		}
		return $array;
	}

	    public function insert($table,$params=array()){
    	// Check to see if the table exists
    	 	$sql='INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES ("' . implode('", "', $params) . '")';
            
            if($ins = @mysql_query($sql)){
                return true; // The data has been inserted
            }else{
                return false; // The data has not been inserted
            }
        }
        public function update($table,$params=array(),$where){
    	// Check to see if table exists
    		// Create Array to hold all the columns to update
            $args=array();
			foreach($params as $field=>$value){
				// Seperate each column out with it's corresponding value
				$args[]=$field.'="'.$value.'"';
			}
			// Create the query
			$sql='UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$where;
			// Make query to database
            if($query = @mysql_query($sql)){
            	return true; // Update has been successful
            }else{
                return false; // Update has not been successful
          }
    }
	

    public function escapeString($data){
        return mysql_real_escape_string($data);
    }
}



 $db = new xDbclass();

?>