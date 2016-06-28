<?php

/**
* 
*/
class StateCityClass
{

	public function fetchStates($params = array())
	{
		$DbClass 						= new DbClass;

		if(is_array($params) && count($params) > 0)
		{
			//print_r($params);
			$paramArray						= array();
			foreach ($params as $key => $value) {
				$sql[] 					= " $key = ? ";
				$paramArray[]			= $value;
			}
		}

		$sth 							= $DbClass->db->prepare('SELECT * FROM state'.(count($paramArray) > 0 ? ' WHERE '.join(' AND ',$sql) : '').' ORDER BY name ASC');
        $sth->execute($paramArray);
        
        $count 							=  $sth->rowCount();
        if ($count > 0) 
        {        
        	$data 						= $sth->fetchAll();
        	return $data;
        }
    	return false;
	}

	public function fetchCity($params = array())
	{
		$DbClass 						= new DbClass;

		if(is_array($params) && count($params) > 0)
		{
			//print_r($params);
			$paramArray					= array();
			foreach ($params as $key => $value) {
				$sql[] 					= " $key = ? ";
				$paramArray[]			= $value;
			}
		}

		$sth 							= $DbClass->db->prepare('SELECT * FROM city '.(count($paramArray) > 0 ? ' WHERE '.join(' AND ',$sql) : ' ORDER BY name ASC'));
        $sth->execute($paramArray);
        
        $count 							=  $sth->rowCount();
        if ($count > 0) 
        {        
        	$data 						= $sth->fetchAll();
        	return $data;
        }
    	return false;
	}



	public function fetchStateOptions($selected = '', $case = '')
	{
		$lc_array						= $this->fetchStates($params);

		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lc)
			{
				$lcid					= $lc['code'];
				$lcname					= $lc['name'];

				$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
				$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
				$ret 					.= '
					<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
				';
			}
			return $ret;
		}
	}

	public function genStateOptions($lc_array = array(), $selected = '', $case = '')
	{

		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lcid)
			{
				// Get state name
				$state 		= $this->fetchStates(array('code'=>$lcid));
				$lcname					= $state[0]['name'];
				$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
				$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
				$ret 					.= '
					<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
				';
			}
			return $ret;
		}
	}


	public function fetchCityOptions($params = array(), $selected = '', $case = '')
	{
		$lc_array						= $this->fetchCity($params);

		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lc)
			{
				$lcid					= $lc['code'];
				$lcname					= $lc['name'];

				$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
				$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
				$ret 					.= '
					<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
				';
			}
			return $ret;
		}
	}


	public function fetchStatesOptionsArray($params = array())
	{
		$lc_array						= $this->fetchStates($params);

		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lc)
			{
				$ret[$lc['code']] 		= $lc['name'];
			}
			return $ret;
		}
	}

	public function fetchCityOptionsArray($params = array())
	{
		$lc_array						= $this->fetchCity($params);

		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lc)
			{
				$ret[$lc['code']] 		= $lc['name'];
			}
			return $ret;
		}
	}

	public function getStateName($state) {
		if(empty($state)) return false;
		$fetchStates 			= $this->fetchStates(array('code'=>$state));
		//var_dump($fetchStates);
		if(count($fetchStates) > 0) return $fetchStates[0]['name'];
	}



}


?>