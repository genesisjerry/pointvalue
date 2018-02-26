<?php 
// NOTE: All required variables MUST be initialized before including this script
// Required Variables: $client, $dir, $key, $upFile

// Sample test responses:
// echo json_encode(array('client' => $_SESSION['email'])); exit; 
// echo json_encode(array('post' => $_POST, 'get' => $_GET, 'upFile' => $_FILES)); exit;			   // [] | [] | $_FILES['file']
// echo json_encode(array('doc' => $_SERVER['DOCUMENT_ROOT'], 'host' => $_SERVER['HTTP_HOST'])); exit; // xampp/htdocs | localhost
// echo json_encode(array('_POST' => $_POST, '_FILES' => $_FILES)); exit; 

if(empty($client) or empty($dir) or empty($key) or empty($upFile) 
or !array_key_exists('name',$upFile)){
// If any errors, complie error report 
    if(empty($dir)){ 
        $erFld = 'Dir';   $erCod = 'uF02';   $erMsg = 'Invalid Directory.';   //File could not be saved.
    }elseif(empty($client)){
        $erFld = 'Cli';   $erCod = 'uF03';   $erMsg = 'Invalid Client ID.';   // E.g if No Session. Works with 'Key'
    }elseif(empty($key)){
        $erFld = 'Key';   $erCod = 'uF04';   $erMsg = 'Invalid Upload Key.';  // Used to prevent multiple upload of same file
    }elseif(empty($upFile) or !array_key_exists('name',$upFile)){
        $erFld = 'Fil';   $erCod = 'uF05';   $erMsg = 'Invalid File.';        // File array does not have all required indices
    }
	$result = array('status' => 'Error', 'details' => array('code' => $erCod, 
                                                            'error' => 'File not saved. ' . $erMsg)); 
}else{
// If everything is as expected, instantiate class and continue
    new upload($dir);   
    $upFile['locpath_key'] = $upFile['name'].$key.$client;  
    // Call the actual upload function
    $resultMsg = Upload::upload_file($upFile,FALSE);  
    // Collate and return result 
    $status = ($resultMsg[0]) ? 'Success' : 'Error';
    $result = array('status' => $status, 'details' => array('code' => $resultMsg[1], 'message' => $resultMsg[2]) ); 
}

// Send final result
echo json_encode($result);  exit; 


/*  The Upload class
 *
 *	@file['locpath_key'] - concat of 'form inputField name' (e.g 'stLogo') and 'userSession' (e.g $_SESSION['client']). 
 *						   Used to form a consistent @_locPath (name of stored file), in case of repeated uploads of same file.
 *	E.g. 	$_FILES[key($_FILES)]['locpath_key'] = key($_FILES).$st->thisClient(TRUE); 
 *			 	{ $_FILES['stLogo']['locpath_key'] = 'stLogo'.$st->thisClient(TRUE); } => @_locPath == 'c4c52bcd18e4a2222' always
 */
class Upload { 
	private static $locpath, $srvpath, $ext_r, $max_size;   

	public function __construct($resrcDir){ 
		if(!$resrcDir){ return array(FALSE, '01', "File could not be saved."); }
		self::$locpath  = (substr(trim($resrcDir),-1,1) == '/') ? $resrcDir : $resrcDir.'/'; 						
		self::$max_size = array('document'=>'2097152', 'image'=>'2097152',	'audio'=>'10485760',
													   'contact'=>'750592',	'video'=>'26214400'); 
		
		self::$ext_r = array(	'document'=> array('txt','pdf','doc','docx','ppt','xls','xlsx','pptx'), 
								'image'=> 	 array('jpg','png','gif','jpeg'),
								'audio'=> 	 array('mp3','aac','wma'),
								'video'=> 	 array('mp4','avi','3gp','mpg','wmv'), 
								'contact'=>  array('csv') 
							);	
	}

	private static function getFileType($extn){ 
		foreach(self::$ext_r as $groupType => $allSupportedEXTs){ 
			if (in_array($extn, $allSupportedEXTs)) { return $groupType; } 
		} 
		return ''; 
	}

	private static function formatFileSize($size){ 
		if($size > pow(1024,2)){ $size = ($size / pow(1024,2))." MB"; }
		elseif($size > 1024){ $size = ($size / 1024)." kB"; }
		else{ $size = $size." Bytes"; } 
		return $size;
	}

	public static function upload_file($file,$returnTempFile=FALSE){  
		if($file['error'] == 0){ 
			if($file['name'] != ''){ 
				$fileName_r = explode('.',$file['name']); 
				$extn = strtolower(end($fileName_r)); 
				$fileType = self::getFileType($extn); 
				if($fileType != ''){ 
					if($file['size'] <= ($allowedFileSize = self::$max_size[$fileType])){ 
		// Return Temp file here, if @returnTempFile is TRUE
						if($returnTempFile){ return array(TRUE,$file,''); } 
						// 's' is optional dep. on the dir name (e.g 'image/' or 'images/')
						$ftype_dir = $fileType.'s/';  
						$locpath  = self::$locpath . $ftype_dir; 
						if(!$file['locpath_key']){ $file['locpath_key'] = md5(microtime()); }
						$enc_name = substr(sha1($file['locpath_key']),12,19); 
						$_name 	  = $enc_name.'.'.$extn; 
						$_token   = substr(sha1($_name),12,17); 
						$_fileTypePath = '/'.$ftype_dir.$_name;  
						$_locPath = $_SERVER['DOCUMENT_ROOT'].'/'.$locpath.$_name;  
						$_srvPath = 'http://'.str_replace($_SERVER['DOCUMENT_ROOT'],$_SERVER['HTTP_HOST'],$_locPath);  
						// Delete existing file then replace with new upload
						if(file_exists($_locPath)){ unlink($_locPath); }
						if(move_uploaded_file($file['tmp_name'],$_locPath)){ 
							$uploadOk = array('name' => $_name, 'svp' => $_srvPath, 'ftp' => $_fileTypePath, 'typ' => $fileType); 
						}else{ $report = 5; }
					}else{ $report = 4; $more = "Max size: ".self::formatFileSize($allowedFileSize); }
				}else{ $report = 3;  $more = (!empty(self::$ext_r[$fileType])) ? 
                                             "Supported Types: ".implode(',',self::$ext_r[$fileType]) : ''; }
			}else{ $report = 2; } 
		}else{ $report = 0; }
		if(!isset($more)){ $more = ''; }
		$details = array(	0 => "File Upload Error: ".$file['error'],	2 => "Trying to upload an empty file",
							3 => "File type is not supported. $more", 	4 => "File size limit exceeded. $more", 
							5 => "File upload failed.");  
							
		$resultMsg = (!empty($report)) ? $details[$report] : '';
		
		if(!empty($uploadOk)){ return array(TRUE,  '00', $uploadOk);  }
		else{ 				   return array(FALSE, '01', $resultMsg); } 
	}

}

?>