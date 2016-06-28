<?php

// include_once  'config/config.php';

class FileManager
{

	
	function __construct()
	{

	}

	public function getFileExt($fileType)
	{
		$ft 		= explode('.', $fileType);
		return end($ft);
	}

	public function checkDir($dirName, $accessCode)
	{
		if(!file_exists($dirName)) mkdir($dirName, $accessCode);
	}	

	public function resizeImages($fileToMove, $uploadPath, $fileName, $fileExt, $sizes = array(), $resizeMethod = '')
	{
		$FileManager 						= new FileManager;
		if(is_array($sizes) && count($sizes) > 0)
		{
			foreach ($sizes as $size) 
			{
				$widthHeight 				= $this->_genWidthHeight($size);
				$width 						= $widthHeight['width'];
				$height 					= $widthHeight['height'];

				$pathToUpload				= $uploadPath.$size.'/'.$fileName.'.'.$fileExt;	

				$FileManager->checkDir($uploadPath.$size.'/', 0777);

				$resizeObj 					= new resize($fileToMove);
				$resizeObj->resizeImage($width, $height, $resizeMethod);
				$resizeObj->saveImage($pathToUpload, $this->_genQuality($width, $height));
			}
		}
	}

	private function _genWidthHeight($size)
	{
		$ar 								= explode('x', $size);
		$ret 								= array();
		$ret['width']						= $ar[0];
		$ret['height']						= $ar[1];
		return $ret;
	}

	private function _genQuality($width, $height)
	{
		$val 								= ($width > $height) ? $width : $height;
		$quality 							= $val * 2; // Subject to ...
		return $quality; // Subject to ...;
	}

	public function genFileId($flag,$uid,$num, $ext = false)
	{
		$DbClass 							= new DbClass;
		$que 								= "
			SELECT MAX(iddocument_file) AS my_max FROM document_file WHERE uploader_id = : uid AND document_flag = : flag
		";

		$sth 								= $DbClass->db->prepare($que);
        $sth->execute(array(':uid' => $uid, ':flag' => $flag));
        
        $count 								= $sth->rowCount();
        if ($count > 0) 
        {        
        	$data 							= $sth->fetch();
			$val 							= $data['my_max'] + $num;
        }
        else
        {        	
			$val = $num;
        }

		$new_val 							= $uid.$flag.$val.rand(100000,999999).time();
		$gen_val 							= sha1($new_val.$ext); // encrypt the generated value to eliminate guess
		return $gen_val;
	}

	public function insertDocument($file_url, $document_size, $document_type, $course_code, $document_flag, 
									$document_id,$uploader_id, $deletePrev = '')
	{
		$DbClass							= new DbClass;
		if($deletePrev != '' ) // Delete previous record
		{
			$sth 							= $DbClass->db->prepare("DELETE FROM document_file WHERE document_flag = :flag AND uploader_id = :uid");
			$sth->execute(array(":flag" => $document_flag, ":uid" => $uid)); // bind and exectute the query
		}
		// Insert Into Document Files
		$dataToInsert						= array(
													'file_url' 						=> $file_url,
													'document_size' 				=> $document_size,
													'document_type' 				=> $document_type,
													'document_flag' 				=> $document_flag,
													'document_id' 					=> $document_id,
													'uploader_id' 					=> $uploader_id,
													'upload_date' 					=> date("Y-m-d H:i:s")
												);
		return $DbClass->db->Insert('document_file', $dataToInsert);
		//return $DbClass->db->lastInsertId();
	}

	public function fetchDocument()
	{
		$DbClass 							= new DbClass;		
		$sth 								= $DbClass->db->prepare("SELECT file_url FROM document_file WHERE document_flag = :flag AND uploader_id = :uid");
		$sth->execute(array(":flag" => $document_flag, ":uid" => $uid)); // bind and exectute the query

		return $data						= $DbClass->db->select1($que, $params);
	}

	public function fetchDocumentByDocId($document_flag, $document_id, $limit = '')
	{
		//echo "$document_flag, $document_id, $limit";
		$lim 								= ($limit != '') ? ' LIMIT '.$limit : ''; 
		$DbClass 							= new DbClass;		
		$que 								= "SELECT * FROM document_file WHERE document_flag = :flag AND document_id = :document_id $lim";
		$sth 								= $DbClass->db->prepare($que);
		$params								= array(":flag" => $document_flag, ":document_id" => $document_id); // bind and exectute the query

		return $data						= $DbClass->db->select1($que, $params);
	}

	public function fetchDocumentById($iddocument_file)
	{
		//echo "$document_flag, $document_id, $limit";
		if(empty($iddocument_file)) return false;
		$DbClass 							= new DbClass;		
		$que 								= "SELECT * FROM document_file WHERE iddocument_file = :iddocument_file";
		$sth 								= $DbClass->db->prepare($que);
		$params								= array(":iddocument_file" => $iddocument_file); // bind and exectute the query

		return $data						= $DbClass->db->select1($que, $params);
	}

	public function genFileSize($bytes, $precision = 2)
	{
		if($bytes == '') return false;
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 

		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow)); 

		return round($bytes, $precision) . ' ' . $units[$pow]; 
	}

	public function fetchDocuments($document_flag, $document_id, $limit = '', $del = '', $baseFolder = '', $sized = '')
	{
		//echo "$document_flag, $document_id, $limit = '', $del = '', $baseFolder = '', $sized = ''";
		if($document_flag == '' || $document_id == '') return false;
		$fetchDocumentByDocId 				= $this->fetchDocumentByDocId($document_flag, $document_id, $limit);

		if(is_array($fetchDocumentByDocId) && count($fetchDocumentByDocId) > 0)
		{
			
			foreach ($fetchDocumentByDocId as $doc) 
			{
				$docid 						= $doc['iddocument_file'];
				$btnid 						= 'delbtn_'.$docid;
				$fileLink 					= $baseFolder.$doc['file_url'];
				$files[] 					= $fileLink;
				$size 						= $this->genFileSize($doc['document_size'], 2);
				$delBtn 					= ($del != '') ? '<button href="" id="remove" class="removeFile"><i class="fa fa-remove"></i></button>' : '';

				$imgurl 					= $this->fetchPicture($document_flag, $doc['file_url'], '' , $sized);

				$li 						.= "
		        	<li>
		        		<div class='fhead'>
		        			<span class='lft'>".$doc['document_type']."</span>
		        			<span class='rgt'>".$size."</span>
		        		</div>
	                  <img src='".$imgurl."' /> 
	                  <center>
	                    <label id='".$btnid."' class='label-danger deletefile' data-docid='".$docid."'><i class='fa fa-trash-o'></i></label>
	                  </center>
	                </li>
				";
			}
			$ret 							= '
				<ul class="imgul">'.$li.'</ul>
			';
		}
		else
		{
			$ret 							= '
				<span class="not_aval">No File available</span>
			';
		}
		return $ret;

	}

	public function fetchPicture($flag, $file_url = '', $fileId = '', $size = '')
	{
		$fileName 						= 'def';
		
		if(!empty($file_url))
		{
			$fileName 					= $file_url;
			$fileUrl 					= $_SERVER['DOCUMENT_ROOT'].'/'.BASEURL2.'/site_images/'.$flag.'/'.$size.'/'.$file_url;
		}
		else
		{
			// check if fileId finnaly has a value
			if($fileId != '') // Value is set
			{
				$FileManager				= new FileManager;
				$data 						= $FileManager->fetchDocumentByDocId($flag, $fileId, 1);
				$fileName 					= ($data[0]['file_url'] != '') ? $data[0]['file_url'] : 'def';
			}
			//echo $fileUrl 						= $_SERVER['DOCUMENT_ROOT'].BASEURL2.'/site_images/'.$flag.'/'.$size.'/'.$fileName;
			$fileUrl 						= $_SERVER['DOCUMENT_ROOT'].'/'.BASEURL2.'/site_images/'.$flag.'/'.$size.'/'.$fileName;

		}

		$src							= (file_exists($fileUrl)) ? $fileName : 'def.jpg';

		if($size != '') $size = '/'.$size;
		return BASEURL.'site_images/'.$flag.$size.'/'.$src;

	}

	public function deleteDoc($iddocument_file)
	{
		if(empty($iddocument_file)) return false;
		$DbClass 						= new DbClass;
		$data 							= $this->fetchDocumentById($iddocument_file);
		$fileName 					    = ($data[0]['file_url'] != '') ? $data[0]['file_url'] : 'def';
		$fileUrl 						= $_SERVER['DOCUMENT_ROOT'].'/'.BASEURL2.'/site_images/Items/'.$fileName;
		$fileUrl2 						= $_SERVER['DOCUMENT_ROOT'].'/'.BASEURL2.'/site_images/Items/232x180/'.$fileName;
		$fileUrl3 						= $_SERVER['DOCUMENT_ROOT'].'/'.BASEURL2.'/site_images/Items/200x200/'.$fileName;
		unlink($fileUrl);
		unlink($fileUrl2);
		unlink($fileUrl3);

		$sth 							= $DbClass->db->prepare("DELETE FROM document_file WHERE iddocument_file = :iddocument_file");
        return $sth->execute(array("iddocument_file" => $iddocument_file));
	}



	public function commentPop($apr_id)
	{
		if($apr_id == '') return false;

		$fetchApplication 					= $this->fetchDocumentById($apr_id);

		$ret 								= '
			<div id="eppUserInfo">
			<!-- Modal -->
			<div id="" class="" role="" aria-labelledby="myModalLabel" aria-hidden="">
			  <div class="modal-body">

	            <ul class="nav nav-tabs" id="myTab">
	              <li class="active"><a href="#TBComment">Description</a></li>
	            </ul>
	             
	            <div class="tab-content">
	              <div class="tab-pane active" id="TBComment">
	              	<form class="ajaxform commentForm" id="appCommentFormReason" id="">
	              		<input type="hidden" name="apr_id" value="'.$fetchApplication[0]['iddocument_file'].'" />
	              		<div class="control-group">
	              			<div id="appCommentError"></div>
			            </div>
	              		<div class="control-group">
			              <div class="controls">
			                <textarea name="comment" class="span4 commentText" placeholder="Type description here ..">'.$fetchApplication[0]['doc_desc'].'</textarea>
			              </div>
			            </div>
			            <div class="form-actions">
			              <button class="btn btn-primary appCommentBtn" id="appCommentBtn"><b>Save</b></button>
			              <div id="appCommentLoader"></div>
			            </div>
	              	</form>
	              </div>
	            </div>

		  	</div><!-- End modal-body -->
			</div>
		';
		return $ret;
	}	

	public function updateAppRequest($iddocument_file, $dataToUpdate = array())
	{
		if(empty($iddocument_file)) return false;
		$DbClass 							= new DbClass;
		return $ins 						= $DbClass->db->update('document_file', $dataToUpdate, "iddocument_file = '$iddocument_file'");
	}


	
}



?>