<?php

if ( ! defined('BASEURL')) exit('No Direct access to this page');

class Fns
{

	public function sanitize_data($data)
	{
		if(!$data) return false;
		// Remove strips depending on server config		
		if(!get_magic_quotes_gpc())
		{
			$data							= addslashes($data);
		}
		$ret								=	mysql_real_escape_string(htmlentities($data));
		return	$ret;
	}
	
	public function minimize_text($original_text, $required_strlen, $append_text)
	{
		if(!$original_text) return false;
		$ret_text							= $original_text;
		$length								= strlen($original_text);
		if($length > $required_strlen)
		$ret_text							= substr($original_text,0,$required_strlen).$append_text;
		return $ret_text;
	}
	
	public function convert_time($val)
	{
		if($val == '')
		{
			$ret = '<span class="not_aval">Not Available</span>';
			return $ret;
		}
		$date	=	new DateTime($val);
		$ret	=	$date->format("g:i a");
		return $ret;
	}
	
	public function convert_date($val)
	{
		if($val == '')
		{
			$ret = '<span class="not_aval">Not Available</span>';
			return $ret;
		}
		$date	=	new DateTime($val);
		$ret	=	$date->format("F, j Y");
		return $ret;
	}


	public function convertDate($val, $format)
	{
		if($val == '')
		{
			$ret = '<span class="not_aval">Not Available</span>';
			return $ret;
		}
		$date	=	new DateTime($val);
		$ret	=	$date->format($format);
		return $ret;
	}

	public function dateDiff($date1 = '', $date2 = '')
	{
		if(empty($date1) || empty($date2)) return false;
		$datetime1 			= new DateTime($date1);
		$datetime2 			= new DateTime($date2);
		$d1 				= $datetime1->format("Y-m-d");
		$d2 				= $datetime2->format("Y-m-d");

		$daylen 			= 60*60*24;
		return intval((strtotime($d1)-strtotime($d2))/($daylen));
	}
	public function fetch_portalcontrol($opt)
	{
		$db = new db_class;
		$db->db_connector();
		$uid = $this->uid;
		$que = "SELECT param_value_int FROM portalcontrol WHERE parameter_name = '$opt'";
		$sel = $db->query($que);
		$row = $db->fetchArray($sel);
		$val = $row['param_value_int'];
		return $val;
	}
	public function fetch_portalcontrol_var($opt)
	{
		$db = new db_class;
		$db->db_connector();
		$uid = $this->uid;
		$que = "SELECT param_value_var FROM portalcontrol WHERE parameter_name = '$opt'";
		$sel = $db->query($que);
		$row = $db->fetchArray($sel);
		$val = $row['param_value_var'];
		return $val;
	}
	
	public function fetch_miscode($type,$row, $table = 'miscode')
	{
		$DbClass 	= new DbClass;
		$sth 		= $DbClass->db->prepare("SELECT M_CODENAME FROM $table WHERE M_CODTYPE = :type AND M_CODKEY = :row");

        $sth->execute(array(
            ':type' 		=> $type,
            ':row' 			=> $row
        ));
        
        $count 							=  $sth->rowCount();
        if ($count > 0) 
        {        
        	$data 						= $sth->fetch();
        	return $data['M_CODENAME'];
        }
	}
	public function fetch_miscodes($type)
	{
		$db		=	new db_class;
		$db->db_connector();
		if($type == 'WORK_EXP')
		{
			$ext = " ORDER BY cast(M_CODKEY as decimal(10,0))";
		}
		$que	=	"SELECT M_CODKEY,M_CODENAME FROM miscode WHERE M_CODTYPE = '$type' $ext";
		$result	=	$db->find($que);
		return $result;
	}
	public function fetch_miscode_options($cur,$type,$case)
	{
		$lc_array							= $this->fetch_miscodes($type);
		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lc)
			{
				$lcname	         				= $lc['M_CODENAME'];
				if($case == 'up')	$lcname		= strtoupper($lcname);
				elseif($case == 'camel')$lcname	= ucfirst($lcname);
				$lcid							= $lc['M_CODKEY'];
				
				$selected						= '';
				if(strtolower($lcid) == strtolower($cur))
				{
					$selected					= 'selected="selected"';
				}
				
				$ret.='
					<option value="'.$lcid.'" '.$selected.'>'.$lcname.'</option>
				';
			}
			return $ret;
		}
		
	}
	
	public function coutries_array($ctry_key)
	{
		if($ctry_key)
		$ext								= "
			WHERE 
		";
		$que								= "
			SELECT ctry_name, printable, ctry_key FROM countries $ext
		";
		$db									= new db_class;
		$db->db_connector();
		return	$db->find($que);
	}
	
	public function fetch_countries_opt($cur)
	{
		$coutries_array					= $this->coutries_array('');
		$opt							= $this->generate_options($cur,$coutries_array,'printable','ctry_key');
		return $opt;
	}
	
	public function generate_options($cur,$oprions_array, $name, $value)
	{
		if(!is_array($oprions_array) || count($oprions_array) <= 0) return false;
		foreach($oprions_array as $data)
		{
			$opt_name						= $data[$name];
			$opt_val						= $data[$value];
			$selected						= '';
			if(strtolower($opt_val) == strtolower($cur))
			{
				$selected					= 'selected="selected"';
			}
			$ret							.= '
				<option value="'.$opt_val.'" '.$selected.'>'.$opt_name.'</option>
			';
		}
		return $ret;
	}

	public function createOptions($options = array())
	{
		if(count($options)  > 0)
		{
			foreach ($options as $key => $value) {
				$option 			.= '<option value="'.$key.'">'.$value.'</option>';
			}
			return $option;
		}
	}

	public function createOptionsByRange($start, $stop, $by = 1, $selected = '')
	{
		for($i = $start; $i <= $stop; $i+=$by)
		{
			$sel 							= ($selected == $i) ? "selected" : '';
			$ret 							.= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
		}
		return $ret;
	}

	public function createDayOptions($selected = '')
	{
		for($i = 1; $i <= 31; $i++)
		{
			$sel 							= ($selected == $i) ? "selected" : '';
			$ret 							.= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
		}
		return $ret;
	}
	public function createMonthOptions($selected = '')
	{
		for($i = 1; $i <= 12; $i++)
		{
			$sel 							= ($selected == $i) ? "selected" : '';
			$monthName 						= date("F", mktime(0, 0, 0, $i, 1));
			$ret 							.= '<option value="'.$i.'" '.$sel.'>'.$monthName.'</option>';
		}
		return $ret;
	}
	public function createYearOptions($selected = '', $from = '', $to = '')
	{
		$from 				= ($from != '') ? $from : 1900;
		$to 				= ($to != '') ? $to : date("Y");


		for($i = $from; $i <= $to; $i++)
		{
			$sel 			= ($selected == $i) ? "selected" : '';
			$ret 							.= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
		}
		return $ret;
	}

	public function genCrypt($val)
	{
		return md5($val);
	}

		
	public function randStr($len, $norepeat = true)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
		$max = strlen($chars) - 1;
	
		if ($norepeat && $len > $max + 1) {
			throw new Exception("Non repetitive random string can't be longer than charset");
		}
	
		$rand_chars = array();
	
		while ($len) {
			$picked = $chars[mt_rand(0, $max)];
	
			if ($norepeat) {
				if (!array_key_exists($picked, $rand_chars)) {
					$rand_chars[$picked] = true;
					$len--;
				}
			}
			else {
				$rand_chars[] = $picked;
				$len--;
			}
		}		
		return implode('', $norepeat ? array_keys($rand_chars) : $rand_chars);   
	}

	public function genSelectWithDisable($name, $class, $id, $reqVal = array(), $val, $options, $selected = '', $otherData = '')
	{
		if(in_array($val, $reqVal))
		{
			$tanslateStatus 			= $this->tanslateStatus($val);
			$ret 						= '<span>'.$tanslateStatus.'</span>';
		}
		else
		{
			if(is_array($options) && count($options) > 0)
			{	
				foreach ($options as $key => $option) 
				{
					$sels						= ($key == $selected) ? 'selected="selected"' : '';
					$opts 						.= '
						<option value="'.$key.'" '.$sels.'>'.$option.'</option>
					';		
				}
				$ret 							= '
					<select name="'.$name.'" class="'.$class.'" id="'.$id.'" '.$disable.' '.$otherData.'>
						'.$opts.'
					</select>
				';
			}	
		} 
		return $ret;
	}

	public function tanslateStatus($status)
	{
		if($status == '') return false;
		$vals 								= array(
												"A"					=> "ACTIVE",
												"P"					=> "PENDING",
												"F"					=> "FULFILED",
												"S"					=> "SUCCESSFUL",
												"ACTIVE"			=> "ACTIVATED",
												"C"					=> "CONFIRMED",
												"NC"				=> "NOT CONFIRMED",
												"D"					=> "DEACTIVATED",
												"DECLINED"			=> "DECLINED",
												"SUSPEND"			=> "SUSPENDED",
											);
		foreach ($vals as $key => $value) 
		{
			if($key == $status)
			{
				return $value;
			}
		}
	}

	public function tanslateAppStatus($status)
	{
		if($status == '') return false;
		$vals 								= array(
												"A"					=> "APPROVED",
												"P"					=> "PENDING",
												"F"					=> "FULFILED",
												"S"					=> "SUCCESSFUL",
												"D"					=> "DECLINED",
											);
		foreach ($vals as $key => $value) 
		{
			if($key == $status)
			{
				return $value;
			}
		}
	}

	public function iconStat($status, $class = '')
	{
		//if($status == '') return false;
		$vals 								= array(
												"NC"				=> "icon-ban-circle",
												"C"					=> "icon-ban-circle",
												"A"					=> "icon-ok",
												"D"					=> "icon-ban-circle red",
												"P"					=> "",
											);
		foreach ($vals as $key => $value) 
		{
			if($key == $status)
			{
				$val 			= '<i class="'.$value.' '.$class.'"></i>';
				return $val;
			}
		}
		return '<span class="label label-important">INVALID</span>';
	}

	public function tanslateStatusIconic($status)
	{
		//if($status == '') return false;
		$vals 								= array(
												"NC"				=> "<span class=''>NOT CONFIRMED</span>",
												"DECLINED"			=> "<span>DECLINED</span>",
												"SUSPENDED"			=> "<span class=''>SUSPENDED</span>",
												"ACTIVE"			=> "<span class='oktext'><b>ACTIVE</b></span> <i class='icon-ok'></i>",
												"A"					=> "<span class='oktext'><b>ACTIVE</b></span> <i class='icon-ok'></i>"
											);
		foreach ($vals as $key => $value) 
		{
			if($key == $status)
			{
				return $value;
			}
		}
		return '<span class="label label-important">NOT RECOGNISED</span>';
	}

	public function altText($val, $alt = '<span class="grey">Not Available</span>')
	{
		return $ret 						= ($val != '') ? $val : $alt;
	}

	public function genMulText($table = 'miscode', $type, $params = array(),  $delimiter = ',')
	{
		if(count($params) > 0)
		{
			$miscode 						= array();
			foreach ($params as $param) 
			{
				$miscode[] 					= $this->fetch_miscode($type, $param, $table);
			}
			return $ret = implode($delimiter, $miscode);
		}
		else
		{
			return '<span class="grey">Not Available</span>';
		}
	}

	public function genUserSes($uType)
	{
		if(empty($uType) || !in_array($uType, array('epp','ftz'))) return false;
		$Session 				= new Session;
		$ret 					= array();
		$ret['sesUid']			= $Session->get('sesOnline'.$uType.'User');
		$ret['sesCompId']		= $Session->get('sesOnline'.$uType.'Comp');
		return $ret;
	}

	public static function checkAval($text, $alt = 'Not Available') {
		if(!empty($text)) return $text;
		return '<span class="not-aval ">'.$alt.'</span>';
	}

	
}



?>