<?php

/**
*
*@author Badejo Oluwatobi 
* List and dropdown views for category,vendors, subcategoiries, states etc

*/
class Listview  {

				public function aux_prodDetails($streID,$pid){

						$DbClass 						= new DbClass;
						$categories = $DbClass->db->select("Products", "StoreId = '$streID' And Id ='$pid'");
						$co 	= count($categories);

						if($co > 0) {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "http://api.shobbutest.com/1.0/stores/$streID/products/$pid
						");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch, CURLOPT_HEADER, FALSE);
						$response = curl_exec($ch);
						curl_close($ch);
						$data = json_decode($response, true);
					   }elseif ($co < 1) {
					   	header("Location:productlist");
					   }
						return $data;
					}
	
				public function categorylist($streID,$selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Categories", "StoreId = '$streID'");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							return $ret;
						}
						
				}


				public function Vendorlist($streID,$selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Vendors", "StoreId = '$streID'");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							return $ret;
						}
						
				}


				public function funcs($streID,$id){
						$DbClass 						= new DbClass;
						$prodDets         				= $this->aux_prodDetails($streID,$id);
						//echo "<script>alert('var_dump($prodDets)')</script>";
						foreach ($prodDets['variations'] as $key => $value) { 
                                        $id = $value['id'];
                                       echo "<option value='$id' name='variant'>";
                                        foreach ($value['code'] as $key => $value) {
                                           echo'<b>'.ucfirst($key).'</b> : '.ucfirst($value).'&nbsp;';
                                        }
                                        echo "</option>";
                                   }
                            return $ret;
						
				}

					public function prodli($streID,$selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Products", "StoreId = '$streID'");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Title'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							return $ret;
						}
						
				}
				public function taxlist($streID,$selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Taxes", "StoreId = '$streID'");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							return $ret;
						}
						
				}

				public function banklist($selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Banks");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							return $ret;
						}
						
				}

				public function subcategoiries($idy,$selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("SubCategories", "CategoryId = '$idy'");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}


				public function states($selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("States");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Code'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}

				public function weights($selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Weights");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Code'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}


				public function couriers($selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Couriers");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}

				public function plans($selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Plans");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}

				public function zones($courID,$selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Zones", "CourierId = '$courID'");
						$mainst = $DbClass->db->select("Zones", "Id = '$selected'");
						$stte = $mainst[0]['StateCode'];
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{	
								$CategoryClass          = new CategoryClass;
			            		$fetchCats              = $CategoryClass->fetchCats('States', array('Code'=>$lc['StateCode']));
			            		$cat_real_name          = $fetchCats[0]['Name'];
								$lcst					= $lc['StateCode'];
								$lcid					= $lc['Id'];
								$lcname					= $cat_real_name;

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcst == $stte) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}

				public function country($selected = ''){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("Countries");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Id'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}

				public function statebycountry($id){
						$DbClass 						= new DbClass;
						$lc_array = $DbClass->db->select("States","CountryId = '$id'");
						if(is_array($lc_array) && count($lc_array) > 0)
						{
							foreach($lc_array as $lc)
							{
								$lcid					= $lc['Code'];
								$lcname					= $lc['Name'];

								$lcname 				= ($case == 'up') ? strtoupper($lcname) : $lcname;
								$active 				= ($lcid == $selected) ? 'selected = "selected"' : '';
								$ret 					.= '
									<option value="'.$lcid.'" '.$active.'>'.$lcname.'</option>
								';
							}
							
						}else{
							$ret 					.= '
									<option selected="selected" value="">Not Available </option>
								';
						}
						return $ret;
						
				}


				public function planDet($planId){
						$DbClass 						= new DbClass;
						$result							= $DbClass->db->select("Plans", "Id = '$planId'");
						return $result;
					}



}



?>