<?php

class Validator {
	
	public function validateExistence($data = array()) {
		if(is_array($data) && count($data) > 0) {
			$ret = '';
			foreach ($data as $key => $value) {
				if(empty($value)) {
					$ret 		.= '<li>'.$key.'</li>';
				}
			}
			return $ret;
		}
		return false;
	}

}

?>
