<?php

/**
* 
*/
class CategoryClass
{

	public function fetchCats($table, $params = array())
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

		$sth 							= $DbClass->db->prepare('SELECT * FROM '.$table.' '.(count($paramArray) > 0 ? ' WHERE '.join(' AND ',$sql) : ''));
        $sth->execute($paramArray);
        
        $count 							=  $sth->rowCount();
        if ($count > 0) 
        {        
        	$data 						= $sth->fetchAll();
        	return $data;
        }
    	return false;
	}

	public function fetchCategoryClass($params = array())
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

		$sth 							= $DbClass->db->prepare('SELECT * FROM Category_class'.(count($paramArray) > 0 ? ' WHERE '.join(' AND ',$sql) : ''));
        $sth->execute($paramArray);
        
        $count 							=  $sth->rowCount();
        if ($count > 0) 
        {        
        	$data 						= $sth->fetchAll();
        	return $data;
        }
    	return false;
	}



	public function fetchCategoryOptions($type, $selected = '', $extras = array(), $case = '', $req = array())
	{
		$params							= array("M_CODTYPE" => $type);
		if(is_array($extras) && count($extras) > 0)	$params	= $extras;
		$lc_array						= $this->fetchCategorys($params);

		if(is_array($lc_array) && count($lc_array) > 0)
		{
			foreach($lc_array as $lc)
			{
				$lcid					= $lc['M_CODKEY'];
				$lcname					= $lc['M_CODENAME'];
				$lmisc					= $lc['MISC'];

				if(is_array($req) && count($req) > 0 && !in_array($lmisc, $req)) continue; // filter required values if they exists

				$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
				$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
				$ret 					.= '
					<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
				';
			}
			return $ret;
		}
	}

	public function fetchCategoryClassOptions($params = array())
	{
		$Categorys 						= $this->fetchCategoryClass($params);
		if(count($Categorys) > 0)
		{
			foreach ($Categorys as $key => $data) 
			{
				$options 				.= '
					<option value="'.$data['ref_key'].'">'.ucfirst($data['name']).'</option>
				';
			}
			return $options;
		}
	}

	public function setupCategory($M_CODTYPE, $M_CODKEY, $M_CODENAME, $MISC, $M_CREATEDATE, $M_CREATEPOINT, $M_CREATEUSER, $M_LASTUSERID, $deletePrev = '')
	{		
		$DbClass							= new DbClass;
		if($deletePrev != '' ) // Delete previous record
		{
			$this->deleteCategory();
		}
		// Insert Into Document Files
		$dataToInsert						= array(
													'M_CODTYPE' 				=> $M_CODTYPE,
													'M_CODKEY' 					=> $M_CODKEY,
													'M_CODENAME' 				=> $M_CODENAME,
													'MISC' 						=> $MISC,
													'M_CREATEDATE'				=> date("Y-m-d h-i-s"),
													'M_CREATEPOINT' 			=> $M_CREATEPOINT,
													'M_CREATEUSER' 				=> $M_CREATEUSER,
													'M_LASTUSERID' 				=> $M_LASTUSERID
												);
		return $DbClass->db->Insert('Category', $dataToInsert);
	}

	public function setupCategoryClass($ref_key, $name, $uploader, $upload_date, $version, $deletePrev = '')
	{
		$DbClass							= new DbClass;
		if($deletePrev != '' ) // Delete previous record
		{
			$this->deleteCategoryClass();
		}
		// Insert Into Document Files
		$dataToInsert						= array(
													'ref_key' 					=> $ref_key,
													'name' 						=> $name,
													'uploader' 					=> $uploader,
													'upload_date' 				=> date("Y-m-d h-i-s"),
													'version'					=> $version
												);
		return $DbClass->db->Insert('Category_class', $dataToInsert);
	}

	public function deleteCategory($M_CODTYPE, $M_CODKEY)
	{
		if(empty($M_CODTYPE) || empty($M_CODKEY)) return false;
		$DbClass 						= new DbClass;

		$sth 							= $DbClass->db->prepare("DELETE FROM Category WHERE M_CODTYPE = :M_CODTYPE AND M_CODKEY = :M_CODKEY ");
        return $sth->execute(array("M_CODTYPE" => $M_CODTYPE, "M_CODKEY" => $M_CODKEY));
	}

	public function deleteCategoryClass($ref_key)
	{
		if(empty($ref_key)) return false;
		$DbClass 						= new DbClass;

		$sth 							= $DbClass->db->prepare("DELETE FROM Category_class WHERE ref_key = :ref_key ");
        return $sth->execute(array("ref_key" => $ref_key));
	}

	public function checkCategoryExists($M_CODTYPE, $M_CODKEY)
	{
		if(empty($M_CODTYPE) || empty($M_CODKEY)) return false;
		$DbClass 						= new DbClass;

		$sth 							= $DbClass->db->prepare("SELECT M_CODKEY FROM Category WHERE M_CODTYPE = :M_CODTYPE AND M_CODKEY = :M_CODKEY ");
        $sth->execute(array("M_CODTYPE" => $M_CODTYPE, "M_CODKEY" => $M_CODKEY));
        
        $count 							=  $sth->rowCount();
        if ($count > 0) return true;
        return false;
	}

	public function checkCategoryClassExists($ref_key)
	{
		if(empty($ref_key)) return false;
		$DbClass 						= new DbClass;

		$sth 							= $DbClass->db->prepare("SELECT ref_key FROM Category_class WHERE ref_key = :ref_key");
        $sth->execute(array("ref_key" => $ref_key));
        
        $count 							=  $sth->rowCount();
        if ($count > 0) return true;
        return false;
	}

	public function manageCategory($M_CODTYPE = '')
	{
		$fns 							= new fns;
		$fetchCategorys 					= $this->fetchCategorys();
		if(is_array($fetchCategorys) && count($fetchCategorys) > 0)
		{
			$i = 1;
			foreach ($fetchCategorys as $data) 
			{
				$mcodename 				= ucfirst($data['M_CODENAME']);
				$mcodekey 				= ucfirst($data['M_CODKEY']);
				$mcodetype 				= ucfirst($data['M_CODTYPE']);
				$btnDeleCategoryId 		= 'Category'.$i;
				$ret 					.= '
					<tr>						
		              <td>'.$mcodename.'</td>
		              <td>'.$mcodetype.'</td>
		              <td>'.$mcodekey.'</td>
		              <td>'.$fns->convertDate($data['M_CREATEDATE'],"jS, M Y").'</td>
		              <td width="45"><a href="'.BASEURL.'epp/ajax/manageCategory.php'.'" class="noline" rel="facebox"><i class="icon-edit"></i> edit</a></td>
		              <td width="30">
		              <button id="'.$btnDeleCategoryId.'" class="like_link deleteCategory" data-name = "'.$mcodename.'" data-m_codekey="'.$mcodekey.'" 
		              data-m_codtype = "'.$mcodetype.'" data-todo="deleteCategory" title="Delete '.$mcodename.'"><i class="icon-trash"></i></button>
		              </td>
					</tr>
				';
				$i++;
			}
			return $ret;
		}
	}

	public function manageCategoryClass($M_CODTYPE = '')
	{
		$fns 							= new fns;
		$fetchCategorys 					= $this->fetchCategoryClass();
		if(is_array($fetchCategorys) && count($fetchCategorys) > 0)
		{
			$i = 1;
			foreach ($fetchCategorys as $data) 
			{
				$mcodename 				= ucfirst($data['name']);
				$mcodekey 				= ucfirst($data['ref_key']);
				$btnDeleCategoryId 		= 'CategoryClass'.$i;
				$ret 					.= '
					<tr>						
		              <td>'.$mcodename.'</td>
		              <td>'.$mcodekey.'</td>
		              <td>'.$fns->convertDate($data['upload_date'],"jS, M Y").'</td>
		              <td width="45"><a href="'.BASEURL.'epp/ajax/manageCategory.php'.'" class="noline" rel="facebox"><i class="icon-edit"></i> edit</a></td>
		              <td width="20">
		              <button id="'.$btnDeleCategoryId.'" class="like_link deleteCategoryClass" data-name = "'.$mcodename.'" data-m_codekey="'.$mcodekey.'" 
		              data-m_codtype = "'.$mcodetype.'" data-todo="deleteCategoryClass" title="delete '.$mcodename.'"><i class="icon-trash"></i></button>
		              </td>
					</tr>
				';
				$i++;
			}
			return $ret;
		}
	}




}


?>