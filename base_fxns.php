<?php
require_once('sqlib.php');

class validation { 
	private static $general_Errors = array(
		'login' => array('code' => '09', 'message' => "You are not logged in."), 
		'required' => array('code' => '10', 'message' => "Value is required."), 
	);

	public static function getGeneralErrors($key){ 
		return (isset(self::$general_Errors[$key])) ? self::$general_Errors[$key] : NULL;
	}
	
	public static function val_alnum($string){ 
		// Unicode for all characters support 
		$valid = ($string == '' or preg_match('/^[\p{L}\p{N}]+$/',$string)); 
		$errorCode = '11';
		$errorMsg = 'must contain only letters and numbers.';
		return ($valid) ? array(TRUE,$string) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
	public static function val_alnumSpace($string){ 
		// Unicode for all characters support 
//		if(($string = trim($string)) == ''){ return ''; } 
		$valid = ($string == '' or preg_match('/^[\p{L}\p{N} ]+$/',$string)); 
		$errorCode = '12';
		$errorMsg = 'must contain only letters and numbers, plus (optional) white spaces.';
		return ($valid) ? array(TRUE,$string) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
// 	Strips all chars NOT AlnumUnderscore:
//	$string = preg_replace("#[^a-zA-Z0-9_]#i",$string); 
	public static function val_alnumChars($string,$allowedChars){ 
		// Unicode for all characters support 
		$valid = ($string == '' or preg_match("/^[\p{L}\p{N}$allowedChars]+$/",$string)); 
		$errorCode = '13'; 
		$errorMsg = "must contain only letters and numbers, plus the following (optional) characters:|$allowedChars|";
		return ($valid) ? array(TRUE,$string) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
	public static function val_strAddress($string){ 
		$allowedChars = '. ,'; 
		list($valid,$returnValue) = self::val_alnumChars(trim($string),$allowedChars); 
		if(!$valid){ list($errorCode,$errorMsg) = $returnValue; }else{ $errorCode = $errorMsg = ''; }
		return ($valid) ? array(TRUE,$returnValue) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
	public static function val_strExtraFields($string){ 
		$allowedChars = "_ -\/"; 
		list($valid,$returnValue) = self::val_alnumChars(trim($string),$allowedChars); 
		if(!$valid){ list($errorCode,$errorMsg) = $returnValue; }else{ $errorCode = $errorMsg = ''; }
		if($errorMsg){ $errorMsg = str_replace('\\','',$errorMsg); }
		return ($valid) ? array(TRUE,$returnValue) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
	public static function val_strEmail($string){ 
		// Unicode for all characters support 
		$allowedChars = '_@.'; 
		list($valid,$returnValue) = self::val_alnumChars(trim($string),$allowedChars); 
		$errorCode = '14';
		if(!$valid){ $errorMsg = "Email contains invalid characters."; }
		else{
			if(!filter_var($returnValue, FILTER_VALIDATE_EMAIL) === FALSE){}
			else{	$valid = FALSE; $errorMsg = "Valid Email is required."; }
		}
		return ($valid) ? array(TRUE,$returnValue) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
	public static function val_strURL($string){ 
		// Unicode for all characters support 
//		$valid = ($string == '' or preg_match("^((^[:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?",$string)); 
//		$valid = ($string == '' or preg_match("^((^[:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?",$string)); 
		if(!filter_var($string, FILTER_VALIDATE_URL) === FALSE){
			$valid = TRUE; 	$validUrl = $string;
		}else{
			$valid = FALSE; 	$errorCode = '15';	
            $errorMsg = "Valid URL is required. URL must contain valid protocol e.g 'http://'"; 
		}
		return ($valid) ? array(TRUE,$validUrl) : array(NULL,array($errorCode,$errorMsg)); 
	}
	
    public static function val_shortURL($string){ 
		// Unicode for all characters support 
        $str = implode('',explode('.',implode('',explode('/',$string))));
		if(ctype_alnum($str)){
			$valid = TRUE; 	$validUrl = $string;
		}else{
			$valid = FALSE; 	$errorCode = '15';	$errorMsg = "Invalid file."; 
		}
		return ($valid) ? array(TRUE,$validUrl) : array(NULL,array($errorCode,$errorMsg)); 
	}
    
    public static function val_strLen($string,$min='',$max=''){  
		if(!$min and !$max){	die("Function 'val_strLen(string,min,max)': 2nd and/or 3rd Parameter(s) required."); }
		$string = trim($string);
		if($min and $max){	$valid = strlen($string) >= $min and strlen($string) <= $max; }
		elseif($min){				$valid = strlen($string) >= $min; }
		elseif($max){				$valid = strlen($string) <= $max; }
		else{								$valid = FALSE; }

		if(!($string !== '' and $valid)){ 
			$errorCode = '16';
			if($min){ $errorMsg = 'Minimum '.$min; }		
			if($max){ $errorMsg = ($min) ? $errorMsg.', Maximum '.$max : 'Maximum '.$max; } 
			$errorMsg .= ' characters required.'; 
		}
		return ($string !== '' and $valid) ? array(TRUE,$string) : array(NULL,array($errorCode,$errorMsg)); 
	}

	public static function numberFormat($number,$way,$toFixed=''){ 
		if(empty($way)){ die("Function 'numberFormat(String $number, Bool $way)': 2nd Parameter required."); }
		$number = str_replace(',','',str_replace(' ','',trim($number))); 
		if(is_numeric($number)){ 
			if($toFixed){ $number = round($number,$toFixed); } // round($number,$toFixed,PHP_ROUND_HALF_DOWN)
			if($way == 1){ 		 $validNumber =  number_format($number); } 
			elseif($way == 2){ $validNumber =  $number; } 
		}
		$errorCode = '18';		$errorMsg = 'Number format is invalid.';
		return (isset($validNumber)) ? array(TRUE,$validNumber) : array(NULL,array($errorCode,$errorMsg)); 
	}

	/* method 'currencyNumberFormat()': Formats a currency number in human readable form. OR strips its formatting.
	 * @way: TRUE  - format as [nn,nnn,nnn.nn] Number. 
	 				 FALSE - strip formatting and return numeric [nnnnnnnn.nn]
	 */
	public static function currencyNumberFormat($number,$way){ 
		if(empty($way)){ die("Function 'currencyFormat(String $number, Bool $way)': 2nd Parameter required."); } 
		list($valid,$returnValue) = self::numberFormat($number,$way); 
		if($valid){  
			$num_arr = explode('.',$returnValue);	
			$decimal = (count($num_arr) == 2) ? end($num_arr) : ''; 
			if(count($num_arr) == 1 or strlen($decimal) <= 2){ $validNumber = $returnValue; }
			else{ $errorMsg = "Currency Figure must have only two (2) decimal places, if necessary."; }
		}else{ $errorMsg = $returnValue[1];	}
		$errorCode = '19';
		return (isset($validNumber)) ? array(TRUE,$validNumber) : array(NULL,array($errorCode,$errorMsg)); 
	}

	public static function bankNumberFormat($number,$type=''){ 
		list($valid,$returnValue) = self::numberFormat($number,2); 
		if($valid){ 
			if(strlen($returnValue) === 10){ $validNumber = $returnValue; }
			else{ $errorMsg = "Valid number is required."; }
		}else{ $errorMsg = $returnValue[1];	}
		$errorCode = '21';
		return (isset($validNumber)) ? array(TRUE,$validNumber) : array(NULL,array($errorCode,$errorMsg)); 
	}

	public static function mobilePhoneFormat($phoneNumber){ 
		$phone = str_replace('+','',str_replace('-','',str_replace(' ','',$phoneNumber))); 
		if(!empty($phone)){
			if($phone[0] == '0'){ $phone = substr_replace($phone,'234',0,1); } 
			if(substr($phone,0,3) == '234' and strlen($phone) != 13){ $phone = NULL; } 
		}
		$errorCode = '20';
		$errorMsg = 'Valid Phone Number is required.'; 
		return (ctype_digit($phone)) ? array(TRUE,$phone) : array(NULL,array($errorCode,$errorMsg)); 
	}

	public static function val_chkBox($value,$required=FALSE,$defaultValue=''){ 
		if($required and $defaultValue == ''){ die("Function 'val_chkBox(String $value, Bool $required, String $defaultValue)': 2nd Parameter requires the 3rd."); }
		$valid = ($required) ? ($value == $defaultValue) : ($value != ''); 
//		$errorCode = '';
		$errorMsg = 'Value is required.'; 
		if($valid){ return array(TRUE,$value); }else{ return array(NULL,array($errorCode,$errorMsg)); }
	}
	
	public static function val_password($password,$cfPassword=''){ 
		$errorMsg = ''; 	$errorCode = '17';
		if((strlen($password) < 8) or !(preg_match('/[A-z]/',$password) and preg_match('/[0-9]/',$password))){ 
			$errorMsg .= 'Minimum eight(8) characters with at least one alphabet and one digit.'; 
		}
		if($errorMsg == '' and $cfPassword != '' and $password !== $cfPassword){ 
			if($errorMsg != ''){ $errorMsg .= '<br/>'; }		$errorMsg .= 'Entries do not match.'; 
		}
		return (empty($errorMsg)) ? array(TRUE,$password) : array(NULL,array($errorCode,$errorMsg)); 
	}

	public static function formatDatePick($yyyymmdd,$useDateFormat=FALSE,$type='',$delim=''){ 
		if(is_array($yyyymmdd)){ 					// $yyyymmdd == array($yy,$mm,$dd)
			if(count($yyyymmdd) == 1 and !$yyyymmdd[0]){ $datestamp = 0; }
			else{
				list($year,$month,$day) = $yyyymmdd; 
				if(is_numeric($year) && is_numeric($month) && is_numeric($day)){ 
					$datestamp = mktime(0,0,0,$month,$day,$year);
				}
			}
		}elseif(is_numeric($yyyymmdd)){ 	// $yyyymmdd == timestamp
			$datestamp = mktime(0,0,0,date('m',$yyyymmdd),date('d',$yyyymmdd),date('Y',$yyyymmdd));
		}

		$errorCode = '22';
		$errorMsg = 'Date is invalid.'; 
		if(isset($datestamp)){ 
			if(empty($type)){ $type = 'd'; }
			$date = ($useDateFormat) ? glx::stdDateTime($datestamp,$type,$delim) : $datestamp;
		}
		return ($date !== NULL) ? array(TRUE,$date) : array(NULL,array($errorCode,$errorMsg)); 
	}

	public static function val_text($string){ 
		// Update this function to be more effective for <textarea> input data
		$string = trim($string);
		$valid = TRUE;
		$errorCode = $errorMsg = '';
		return ($valid) ? array(TRUE,$string) : array(NULL,array($errorCode,$errorMsg)); 
	}
	

}


class basicOps { 
	public static $sesTable; 
	
// Add the Code below to the Child Clas contsructor
/*	public function __construct($db=''){ 
		parent::$con = self::$sql = new SQL_ops($db='');    
		parent::$sesTable = glx::getTable('ses');  
	}	*/
	/*public function __construct($db){ 
		self::$con = new SQL_ops($db);    
	}	*/
	
	public static function sendMail($mail,$link,$tempPswd){
		// mail() ...
		return TRUE; 
	}

	public static function encr($var,$way){ 
		$pad = array('ium84','j4kgz','cb6sn','n5ydp','xcc3c','b4xsr','i7f4w','ve4bg','aev3v','mcv3s');
		$usc = array('bsf4g','d2dft','c9n7a','c4vfv','udf5c'); 
		$str = $pad[abs(rand(0,9))];  $pos = 0;  $step = 2;  $len = strlen($var);
		if($way){	// encrypt
			while($pos < $len){ $str .= substr($var,$pos,$step).$pad[abs(rand(0,9))]; $pos += $step; } 
			$str = str_replace('_',$usc[abs(rand(0,4))],$str);
		}else{    // decrypt
			foreach($usc as $chars){ $var = str_replace($chars,'_',$var); } 
			foreach($pad as $chars){ $var = str_replace($chars,'',$var); } 
		} 
		return ($way) ? $str : $var; 
	}

	public static function _write($s_name){    
		$s_time = time(); $s_id = session_id(); 
		$whr  = "WHERE s_name='$s_name' AND s_status >= 0"; 
		if($selct = self::$con->select_fetch(self::$sesTable,"",$whr)){ 
				$colVal = "s_id='$s_id',s_time='$s_time'"; 
				return self::$con->update(self::$sesTable,$colVal,$whr);
			}elseif(ctype_alnum($s_name)){  
			$cols = "`s_id`,`s_name`,`s_time`,`s_status`"; 
			$vals = "'$s_id','$s_name','$s_time','1'"; 
			$case = (self::$con->insert_check(self::$sesTable,$cols,$vals,$whr)) ? 1 : 5; 
		}else{ $case = 2; }
		return $case; 
	}

	public static function _rread($s_name){  
		$timeNow = time(); $s_id = session_id(); 
		$whr = "WHERE s_name='$s_name'"; 
		if($selct = self::$con->select_fetch(self::$sesTable,"",$whr)){ 
			if(($selct[0]['s_time']+1200) > ($timeNow)){ 
				$colVal = "s_time='$timeNow'"; 
				$whr = "WHERE s_name ='$s_name' AND s_status >= 0"; 
				if(self::$con->update(self::$sesTable,$colVal,$whr) or ($selct[0]['s_time'] == $timeNow)){ 
					$case = 1;
				}else{ $case = 5; }
			}else{ $case = 4; }
		}else{ $case = 2; }
		return $case; 
	}

	public static function _no_mul_log($s_name){ return 1; 
		$s_id = session_id(); 
		$whr = "WHERE s_name='$s_name'";  
		if($selct = self::$con->select_fetch(self::$sesTable,"",$whr)){ 
			$case = ($selct[0]['s_id'] == $s_id) ? 1 : 3; 
		}else{ $case = 2; }
		return $case; 
	}

	/*public static function clean($data_Array){ 
		if(!is_array($data_Array)){ die("Function 'clean()' expects an Array as parameter"); }
		$esValues = array_map(array(self::$con,'strEscape'),$data_Array);
		return $esValues;
	} */

	/* Prompts User to enter password to authorize the current operations  
	 * Code to put inside html <body>: <?php echo glx::_authOp($cl->thisClient()); // Password Auth ?> 
	 */
//	public static function _authOp($thisClient){ 
	public static function _authOps($thisClient){ 
		$phtml = "<div id='_authDiv' title='Confirm current Password' style='display:none;'>
								<form id='authForm' method='post'>
										<input type='hidden' name='_user' value='".$thisClient."' />
										<input type='password' name='authPass' id='authPass' class='form-control' placeholder='enter your password' required />
								</form>
							</div>"; 
		return $phtml; 
	}

	public static function dateTime_to_UnixTimestamp($date){ 
		if(!empty($date)){
			extract(date_parse($date));
			$datestamp = mktime($hour,$minute,$second,$month,$day,$year);
		}
		return (!empty($datestamp)) ? $datestamp : NULL; 
	}

	public static function stdDateTime($timestamp,$type='',$delim=''){
		switch($type){ 
			case 'd':  	{ $format = ($delim) ? str_replace('-',$delim,"Y-m-d") 	 : "Y-m-d";		} break;
			case 'dr': 	{ $format = ($delim) ? str_replace('-',$delim,"d-m-Y") 	 : "d-m-Y";		} break;
			case 't':  	{ $format = ($delim) ? str_replace(':',$delim,"h:i:s a") : "h:i:s a";	} break;
			case 'df': 	{ $format = "D, M j, Y";																							} break;
			case 'dtf': { $format = "D, M j, Y h:i:s a";																			} break;
			default:   	{ $format = ($delim) ? str_replace('-',$delim,"Y-m-d h:i:s a") : "Y-m-d h:i:s a";	} break;
		}
		return (is_numeric($timestamp)) ? date($format,$timestamp) : NULL;
	}

	public static function alpha3FromDate($dateYmd=''){ 
		if(!$dateYmd){ $dateYmd = date('Ymd'); }
		$word = preg_replace('/[0-9]/','',md5($dateYmd)); 
		$word = implode('',array_unique(str_split($word)));   
		$alpha3 = strtoupper(substr($word,0,3));  
		return $alpha3;
	}
	

 	public static function displayMsg($data){  
		if(is_array($data)){ 
			$dispMsg = $data[0];	
		}else{ $dispMsg = $data; }
		$msgColor = 'blue'; 	$panelClass = 'default';  
		$htmlMsg = "<div id='msgDv' class='row'>
									<div id='reportPanel' class='panel panel-$panelClass' >
										<div class='panel-heading' style='color:$msgColor; font-size:14px;'>
											$dispMsg 
										</div> 
									</div>
								</div>";
// re-write this in Javascript only AND then include it here
//		if('<?php if(isset($jvMsg)){ echo $jvMsg; }? >'){ timeOut = 2000; }else{ timeOut = 6000; } 
//		setTimeout(function(){ document.getElementById('msgDv').slideUp(200); }, timeOut); 
//		document.getElementById('msgDv') 
		return $htmlMsg; 
	}

	public static function getMsg($opResult,$isFormValidation=FALSE){   //glx::pr($opResult,'$opResult');
		if(!is_array($opResult)){ die("Function 'getMsg()' expects a 2-dim. Array as parameter"); }
		list($cases,$reports) = $opResult;
		if(is_array($cases)){ 
			$cases = array_unique($cases); 
			if($isFormValidation){			
				$msg = "Please, check the entries in these fields: <br>"; 
				foreach($cases as $key){ $msg .= " -&emsp;".$reports[$key].'<br>'; }
			}else{
				foreach($cases as $key){ $quotes[] = $reports[$key]; } 
				$msg = implode("<br/>",$quotes); 
				if(end($cases) == 1){ $msg = array($msg,TRUE); } 
			}
		}
		return (!empty($msg)) ? $msg : NULL; 
	}

	public static function pr($data, $datafield, $isJSON=FALSE){ 
		if($isJSON){ echo json_encode(array($datafield => $data)); exit; }
		else{
			echo "<br>$datafield: "; 
			if(is_array($data)){ print_r($data); }else{ var_dump($data); } 
			echo "<br>"; 
		}
	} 

	public static function list_SelectMenu($options,$selectedValue='',$swapColsWithRows=FALSE){
		$n = 0; $optionSet = '';
		if($swapColsWithRows){ 
			while($n < count($options)){
				$selctd = ($selectedValue == $options[0][$n]) ? 'selected="selected"' : '';
				$optionSet .= '<option value="'.$options[0][$n].'" '.$selctd.'>'.$options[1][$n++].'</option>';
			}
		}else{ 
			while($n < count($options)){ 
				$selctd = ($selectedValue == $options[$n][0]) ? 'selected="selected"' : ''; 
				$optionSet .= '<option value="'.$options[$n][0].'" '.$selctd.'>'.$options[$n++][1].'</option>';
			}
		}
		return $optionSet;
	}

	public static function list_yearsOptions($startYr='',$endYr='',$isMenu=TRUE,$selectedYear=''){ 
		if(empty($startYr)){ $startYr = date('Y')-5; }
		if(empty($endYr)){ $endYr = date('Y')+5; }
		$years = array();
		while($startYr <= $endYr){
			if(!$isMenu){ $year = array('id' => $startYr, 'label' => $startYr); }else{ $year = array($startYr,$startYr); }
			array_push($years,$year);	$startYr++;
		}
		if(!$isMenu){ return $years; }else{ return self::list_SelectMenu($years,$selectedYear); }
	}

	public static function list_monthsOptions($short='',$isMenu=TRUE,$selectedMonth=''){ 
		$m_long = array('January','February','March','April','May','June','July','August','September','October','November','December');
		$m_short = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$monthsType = ($short) ? $m_short : $m_long;
//		if(!$isMenu){ foreach($monthsType as $idx => $month){ $months[++$idx] = $month; }; return $months; }
		if(!$isMenu){ return $monthsType; }
		$n = 1; $months = array();
		while($n <= 12){
			array_push($months,array($n,$monthsType[$n++ -1]));	
		}
		return self::list_SelectMenu($months,$selectedMonth);
	}	

	public static function list_daysOptions($selectedDay=''){
		$day = 1; $days = array();
		while($day <= 31){
			array_push($days,array($day,$day++));
		}
		return self::list_SelectMenu($days,$selectedDay);
	}

	public static function listCountries($selectedCountry='',$isMenu=TRUE){ 
//		if(empty($conn)){ die("'listCountries()' requires database connection as first parameter."); }
		$db_table = 'test.country';
		$cols = "`country_id`,`country`";
		$selct = self::$con->select_fetch($db_table,$cols);
		if($selct){
			if(!$isMenu){ return $selct; }
/*		{	$cIDs = array_column($selct,'country_id');
				$cNames = array_column($selct,'country');
				$countries = array($cIDs,$cNames); 
			} 
			// OR
			{ $countries = array_column($selct,'country','country_id');	} */
			$countries = array();
			while($row = array_shift($selct)){
				array_push($countries,array($row['country_id'],$row['country']));	
			}
			return self::list_SelectMenu($countries,$selectedCountry);
		}else{ return FALSE; }
	}

	public static function listTimeZones($selectedZone='',$isMenu=TRUE){
//		if(empty($conn)){ die("'listTimeZones()' requires database connection as first parameter."); }
		$db_table = 'test.timezone';
		$cols = "`timeZone_id`,`timeZone`";
		$selct = self::$con->select_fetch($db_table,$cols);
		if($selct){
			if(!$isMenu){ return $selct; }
			$timezones = array();
			while($row = array_shift($selct)){
				array_push($timezones,array($row['timeZone_id'],$row['timezone']));	
			}
			return self::list_SelectMenu($timezones,$selectedZone);
		}else{ return FALSE; }
	}

	public static function listLanguages($selectedLang='',$isMenu=TRUE){ 
		$db_table = 'test.langs';
		$cols = "`lang_id`,`lang`";
		$selct = self::$con->select_fetch($db_table,$cols);
		if($selct){ 
			if(!$isMenu){ return $selct; }
			while($row = array_shift($selct)){ $languages[] = array($row['lang_id'],$row['lang']); }
			return self::list_SelectMenu($languages,$selectedLang);
		}else{ return FALSE; }
	}

	public static function listCurencies($selctdCurr='',$isMenu=TRUE){ 
		$db_table = 'test.currency';
		$cols = "`curr_id`,`curCode`,`currency`";
		$selct = self::$con->select_fetch($db_table,$cols);
		if($selct){ 
			if(!$isMenu){ return $selct; }
			while($row = array_shift($selct)){ $currencies[] = array($row['curr_id'],$row['currency'].' | '.$row['curCode']); }
			return self::list_SelectMenu($currencies,$selctdCurr);
		}else{ return FALSE; }
	}

	public static function listBanksNG($selectedBank='',$isMenu=TRUE){ 
		$db_table = 'test.banks'; 
		$cols = "`bank_id`,`bankName`";
		$selct = self::$con->select_fetch($db_table,$cols);
		if($selct){ 
			if(!$isMenu){ return $selct; } 
			while($row = array_shift($selct)){
				$banks[] = array($row['bank_id'],$row['bankName']);	
			}
			return self::list_SelectMenu($banks,$selectedBank);
		}else{ return FALSE; }
	}

	public static function listBanksNGBrches($bankID,$selectedBrch='',$isMenu=FALSE){
		$db_table = 'test.bankbranches'; 
		$cols = "`brch_id`,`brchName`"; 
		$whr = "WHERE bank_id = '$bankID' AND status BETWEEN 1 AND 2"; 
		$selct = self::$con->select_fetch($db_table,$cols,$whr); 
		if($selct){ 
			if(!$isMenu){ return $selct; } 
			while($row = array_shift($selct)){
				$banks[] = array($row['brch_id'],$row['brchName']);	
			}
			return self::list_SelectMenu($banks,$selectedBrch);
		}else{ return FALSE; }
	}

	public static function getTxnCurrency($curr_id,$selectedCurr='',$isMenu=FALSE){ 
		$db_table = 'test.currency'; 
		$cols = "curr_id as currency_id, currency, curCode  as currency_code, htmlcod as currency_html"; 
		$whr = "WHERE curr_id = '$curr_id' AND status = 1"; 
		$selct = self::$con->select_fetch($db_table,$cols,$whr); 
		if($selct){ 
			if(!$isMenu){ return $selct; } 
			while($row = array_shift($selct)){
				$banks[] = array($row['brch_id'],$row['brchName']);	
			}
			return self::list_SelectMenu($banks,$selectedBrch);
		}else{ return FALSE; }
	}


}
?>