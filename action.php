<?php 
define('DB','valuePmlm');						require_once('actor.php');
$instance = new poin_value(DB);					$send = json_decode(file_get_contents('php://input'),TRUE);

///////////////USER ACCOUNT CREATION/////////////////////////////
if($send ['pusha'] =='1'){
	$firstname = $send['firstname'];			$dob = $send['surname'];
	$gender = $send['gender'];					$phone = $send['phone'];
	$email = $send['email'];					$country = $_SESSION['country'] ;
	$city =$_SESSION['state'];					$LGA = $_SESSION['local']  ;
	$username = $send['username'];				$pass = $send['pass'];	
	$con = $send['con'];						$refemailid = $send['refemailid'];
	$userId = rand(10,10000000);				$status = '1';	$regTym = time();				
$esc = array($firstname,$dob,$gender,$phone,$email,$country,$city,$LGA,$username,$pass,$con,$refemailid);		
list($firstname,$dob,$gender,$phone,$email,$country,$city,$LGA,$username,$pass,$con,$refemailid)= $instance->connection->clean($esc);	
$must = array($firstname,$dob,$gender,$phone,$email,$country,$city,$LGA,$username,$pass,$con,$refemailid);
$val_email = new validation();			$validating = $val_email->val_strEmail($email);
	if($validating[0]){					$chkphone = $instance->ctype($phone);
	if($chkphone[0]){					if($must !=''){
	if($pass === $con ){
		$tableref= "v_preg";			$colref= "u_serid";
		$whereref= "WHERE u_serid ='$refemailid'";
		$refchk = $instance->pullOut($tableref,$colref,$whereref);
if($refchk[0]){
$enpass = sha1($pass);			$table ="v_preg";					
$cols ="firstname,dob,gender,phonnum,email,country,state,LGA,username,pass,u_serid,
status,regTym,refemailid";
$vals ="'$firstname','$dob','$gender','$phone','$email','$country','$city','$LGA','$username',
'$enpass','$userId','$status','$regTym','$refemailid'";
$where="WHERE username ='$username' && email='$email' && phonnum = '$phone'";
$insert = $instance ->insert_D($table,$cols,$vals,$where);
if($insert){ 
				$_SESSION['username'] = $username; $_SESSION['email'] = $email;$_SESSION['id'] = $userId;
				$status = ($insert[0]) ? 'Success' : 'Error';
				$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
			}if(!empty($result)){ echo json_encode($result); }
		}else echo "Please Put a Valid Referral ID";
	}else echo "Please Make Sure That Your Password Matches Confrim Password";
}else echo "All Fields are Required";
}
}else echo "Please Put a Valid Email ID";
}

///////////////////////USER BANK DETAILS UPLOAD////////////////////
if($send ['pusha'] =='2'){
	$username =	$_SESSION['username'];
	$acctno= $send['acctno'];				$acctna = $send['acctna'];
	$bnk = $send['bnk'];					$transpass = $send['transpass'];
	$contranspass = $send['contranspass'];							
$esc = array($acctno,$acctna,$bnk,$transpass,$contranspass );		
list($acctno,$acctna,$bnk,$transpass,$contranspass)= $instance->connection->clean($esc);
$mustDo = array($acctno,$acctna,$bnk,$transpass,$contranspass);	
if($mustDo !=''){		if($transpass === $contranspass){
	$chkacctno = $instance->ctype($acctno);
if($chkacctno[0]){		$entranspass = sha1($transpass);
$table ="v_preg";					
$colVals ="status ='2',transpass ='$entranspass',acctno='$acctno',acctna='$acctna',bnk='$bnk'";
$where="WHERE username ='$username'";
$update=$instance->updateBnk($table,$colVals,$where);
if($update){ 
		$status = ($update[0]) ? 'Success' : 'Error';
		$result = array('status'=>$status,'details' =>array('code' => $update[1],'message' => $update[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}
}else echo "Please Make Sure That Your Transaction Password Matches Confrim Transaction Password";
}else echo "Please Fill All The Required Fields";
}

if($send ['pusha'] =='3'){
$table= "my_country";			$cols= "*";
$where= "";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){ 
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='4'){
$point= $send['dat'];			$table= " my_state";
$cols= "*";						$where= "WHERE countryState_Code='$point' ";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){ 
$_SESSION['country'] = $point;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='5'){
$point= $send['dat'];		$table= " my_lga";
$cols= "*";					$where= "WHERE lga_Codes='$point'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){ 
$_SESSION['state'] = $point;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='6'){
$point= $send['dat'];		$table= " my_lga";
$cols= "*";					$where= "WHERE local_code='$point'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){ 
$_SESSION['local'] = $point;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='7'){
$username = $_SESSION['username'];		$table= "v_preg";
$cols= "*";								$where= "WHERE username='$username'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){ 
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] == '8'){
$username = $send['email'];			$password = $send['password'];
$encrpt = sha1( $password);			$table= "v_preg";					
$cols= "*";							$where= "WHERE username = '$username' && pass = '$encrpt'";
$catch= $instance->login($table,$cols,$where);
if($catch){
	$user_id = $catch[2][0]['u_serid'];		$email = $catch[2][0]['email'];
	 $_SESSION['username']=  $username ; $_SESSION['email'] = $email; $_SESSION['id'] = $user_id;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha']=='9'){
$out= $instance->ballOut();	
if($out){
	$status = ($out[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $out[1],'message' => $out[2]));								
	}if(!empty($result)){ echo json_encode($result); }	
}
	
if($send ['pusha'] =='10'){
	$username =	$_SESSION['username'];			$country = $_SESSION['country'] ;
	$city =$_SESSION['state'] ;					$LGA = $_SESSION['local']  ;
	$phone= $send['phonnum'];					$fullname= $send['fname'];
	$esc = array($phone,$fullname);		
list($phone,$fullnam)= $instance->connection->clean($esc);		
$must = array($country,$city,$LGA,$phone,$fullname);
$chkphone = $instance->ctype($phone);
if($chkphone[0]){					if($must !=''){
$table="v_preg";
$colVals="firstname='$fullname',phonnum='$phone',country='$country',state='$city',LGA ='$LGA'";
$where="WHERE username ='$username'";
$update=$instance->updateBnk($table,$colVals,$where);
if($update){ 
		$status = ($update[0]) ? 'Success' : 'Error';
		$result = array('status'=>$status,'details' =>array('code' => $update[1],'message' => $update[2]));								
	}if(!empty($result)){ echo json_encode($result); }	
}else echo "Please Fill All The Required Fields";
}else echo "Please Put A Valid Phone Number";
}

if($send['pusha'] == '11'){
$username =	$_SESSION['username'];		$oldpass= $send['oldpass'];
$encoldpass = sha1($oldpass);			$newpass= $send['newpass'];				
$confirmpass= $send['confirmpass'];		$encnewpass = sha1($newpass);
$esc = array($oldpass,$newpass,$confirmpass);		
list($oldpass,$newpass,$confirmpass)= $instance->connection->clean($esc);
if($newpass === $confirmpass){
$table= "v_preg";
$cols= "*";								$where= "WHERE username='$username' && pass ='$encoldpass'";
$catch= $instance->pullOut($table,$cols,$where);
$pullpass =$catch[2][0]['pass'];
if($encoldpass  === $pullpass ){
	$table1="v_preg";
	$colVals1="pass='$encnewpass'";
	$where1="WHERE username = '$username' && pass = '$encoldpass'";	
$update=$instance->updateBnk($table1,$colVals1,$where1);
if($update){ 
		$status = ($update[0]) ? 'Success' : 'Error';
		$result = array('status'=>$status,'details' =>array('code' => $update[1],'message' => $update[2]));								
	}if(!empty($result)){ echo json_encode($result); }		
	}		
	}
}

if($send['pusha'] == '12'){
$username =	$_SESSION['username'];				$to =	 $send['to'];
$subject = $send['subject'];					$priority = $send['priority'];
$msg = $send['msg'];							$time = time();
$uniCode = rand(10,100000000);					$status = '1';
$esc = array($to,$subject,$priority,$msg);		
list($to,$subject,$priority,$msg)= $instance->connection->clean($esc);
$must = array($to,$subject,$priority,$msg);
if($must !=""){
$table = "msgtab";
$cols = "sender,reciever,subject,priority,msgBdy,tYm,uniqCode,status";
$vals ="'$username','$to','$subject','$priority','$msg','$time','$uniCode','$status'";
$insert = $instance ->insertNOchk($table,$cols,$vals);
if($insert){ 
				$status = ($insert[0]) ? 'Success' : 'Error';
				$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
			}if(!empty($result)){ echo json_encode($result); }	
	}else echo "Please Fill All The Required Fields";
}
	
if($send ['pusha'] =='13'){
$username =	$_SESSION['username'];			$recva = "users";
$table= " msgtab";					
$cols= "*";									$where= "WHERE reciever= '$recva' || reciever = '$username' && userdelete != 'yes'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='14'){
$username =	$_SESSION['username'];			$table= " msgtab";					
$cols= "*";									$where= "WHERE sender = '$username'  && userdelete != 'yes'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='15'){
$code= $send['code'];						$table= "msgtab";					
$colVals= "userdelete ='yes'";				$where= "WHERE uniqCode = '$code' && status ='1'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='16'){
$code= $send['code'];				$table= "msgtab";					
$cols= "*";							$where= "WHERE uniqCode = '$code' && status ='1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$_SESSION['code']=$code;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='17'){
$code=$_SESSION['code'];			$table= "msgtab";					
$cols= "*";							$where= "WHERE uniqCode = '$code' && status ='1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='18'){
$username =	$_SESSION['username'];			$rToSender= $send['sender'];	
$priority = $send['priority'];				$bdymsg = $send['bdymsg'];
$time = time();								$code = rand(10,10000000);	
$table= "msgtab";							$col ="sender,reciever,subject,priority,msgBdy,tYm,uniqCode,status";$Vals= "'$username','$rToSender','reply','$priority','$bdymsg','$time','$code','1'";							
$insert= $instance->insertNOchk($table,$col,$Vals);
if($insert){
	$status = ($insert[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

////////////////////////CREATING SUB-USER ACCOUNT/////////////////////////
if($send ['pusha'] == '19'){
$username =	$_SESSION['username'];						$subfullname =$send['subfullname'];				
$subphone =$send['subphone'];							$esc = array($subfullname,$subphone);		
list($subfullname,$subphone)= $instance->connection->clean($esc);
$must = array($subfullname,$subphone);					if($must !=""){
$table= "v_preg";										$cols= "*";							
$where= "WHERE username= '$username' && status ='3'";	$catch= $instance->pullOut($table,$cols,$where);
$subdob = $catch[2][0]['dob'];							$subgender = $catch[2][0]['gender'];	
$email = $catch[2][0] ['email'];						$subcountry = $catch[2][0]['country'];
$substate = $catch[2][0]['state'];						$subLGA = $catch[2][0]['LGA'];
$username = $catch[2][0]['username'];					$subuser = rand(10,100000000).$username;
$subpass = $catch[2][0]['pass'];						$subtranspass =$catch[2][0]['transpass'];
$subid = rand(10,100000000);							$status = "1";
$time = time();											$subref = $catch[2][0]['u_serid'];
$table2= "v_preg";					
$cols2 ="firstname,dob,gender,phonnum,email,country,state,LGA,username,pass,u_serid,
status,regTym,refemailid";
$vals2 ="'$subfullname','$subdob','$subgender','$subphone','$email','$subcountry','$substate','$subLGA','$subuser',
'$subpass','$subid','$status','$time','$subref'";
$insert = $instance->insertNOchk($table2,$cols2,$vals2);
if($insert){
	$status = ($insert[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
	}if(!empty($result)){ echo json_encode($result); }
	}else echo "Please Fill The Required Fields";
}

////////////////////SUB ACCOUNT VIEW/////////////////////
if($send ['pusha'] =='20'){
$username =	$_SESSION['username'];			$table= "v_preg";							
$cols= "*";									$where= "WHERE username= '$username' && status ='3'";	
$catch= $instance->pullOut($table,$cols,$where);
$email = $catch[2][0] ['email'];	$subref = $catch[2][0]['u_serid'];
$table1= " v_preg";					
$cols1= "*";					$where1= "WHERE email ='$email' && refemailid ='$subref'";
$catch= $instance->pullOut($table1,$cols1,$where1);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='21'){
$user= $send['user'];									$table= "v_preg";					
$colVals= "status = '3',price ='16,000'";				$where= "WHERE u_serid = '$user' && status ='1'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

///////////////DIRECT REFFRALS///////////////////
if($send ['pusha'] =='22'){
$username =	$_SESSION['username'];			$table= "v_preg";							
$cols= "*";									$where= "WHERE username= '$username' && status ='3'";	
$catch= $instance->pullOut($table,$cols,$where);	$subref = $catch[2][0]['u_serid'];
$table1= " v_preg";					
$cols1= "*";					$where1= "WHERE  refemailid ='$subref'";
$catch= $instance->pullOut($table1,$cols1,$where1);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

///////////////////////THIS PAYMENT SECTION IS JUST AN ASSUMPTION, I PORPOSELY /////////////////
////////////////////// WRITE IT SO I CAN BE ABLE TO PROCEED  WITH OTHER ACTIONS ////////////////
///////////////////// THAT HAS TO TAKE PLACE AFTER PAYMENT IS DONE/////////////////////////////
if($send ['pusha'] =='23'){
$username =	$_SESSION['username'];				$userId=$_SESSION['id'];
$payment = $send['pay'];						$esc = array($payment );		
list($payment)= $instance->connection->clean($esc);
$amount ="16000";
if($payment !=""){								$chkpayment = $instance->ctype($payment);
if($chkpayment[0]){								if($payment == $amount){
$status="3";									$table ="v_preg";								
$colVals ="status = '$status',price ='$payment'";
$where ="WHERE username ='$username'";			$payment =$instance->updateBnk($table,$colVals,$where);
if($payment){
$Bonus=$instance->Bonus();						$fd_Wallet = $Bonus[2];
$E_Wallet= $Bonus[3];							$time = time();
$table2= "cashwallet";							$cols2 ="user_id,avCash,lastUpdates,status";
$vals2 ="'$userId','$E_Wallet','$time','1'";	$where2 = " WHERE user_id ='$userId'";
$insertEb =$instance->insert_D($table2,$cols2,$vals2,$where2);
$table3= "foodwallet";							$cols3 ="user_id,avCash,lastUpdates,status";
$vals3 ="'$userId','$fd_Wallet','$time','1'";	$where3 = " WHERE user_id ='$userId'";
$insertFb =$instance->insert_D($table3,$cols3,$vals3,$where3);
if($insertFb){
$spill =$instance->spillOva();
	$status = ($insertFb[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $insertFb[1],'message' => $insertFb[2]));								
}if(!empty($result)){ echo json_encode($result); }

}else echo "Payment Not Successful";
}else echo "Insufficient Balance";
}else echo "Invalid Amount";
}else echo "Fill In The Payment Field";
}

if($send ['pusha'] =='24'){
$userId=$_SESSION['id'];			$table= "cashwallet";					
$cols= "*";							$where= "WHERE user_id = '$userId'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='25'){
$userId=$_SESSION['id'];			$table= "foodwallet";					
$cols= "*";							$where= "WHERE user_id = '$userId'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

//////////////////////////ADMIN PART STARTS HERE///////////////////////////////
//																			///
//	                 THIS SECTION HANDLES ALL THE ADMIN CALLS				///
//																			///
//																			///
///////////////////////////////////////////////////////////////////////////////
if($send['pusha'] == 'ad1'){
$aduser= $send['adminemail'];			$adpass= $send['adminpassword'];	
$esc = array($aduser,$adpass);		
list($aduser,$adpass)= $instance->connection->clean($esc);	
$must = array($aduser,$adpass);	
if($must !=""){
$table= "admintab";
$cols= "*";								$where= "WHERE aduser='$aduser' && adcode ='$adpass'";
$move= $instance->enterDmin($table,$cols,$where);	
if($move){ 
		$_SESSION['username'] = $aduser;
		$status = ($move[0]) ? 'Success' : 'Error';
		$result = array('status'=>$status,'details' =>array('code' => $move[1],'message' => $move[2]));								
	}if(!empty($result)){ echo json_encode($result); }	
	}else echo "Fill-Up The Fields";
}

if($send ['pusha'] == 'ad2'){
$aduser=$_SESSION['username'];
$table= "admintab";					
$cols= "*";							$where= "WHERE aduser = '$aduser'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	 $_SESSION['username']= $aduser ;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] == 'ad3'){
$aduser=$_SESSION['username'];		$adFname= $send['adminsurname'];				
$adPhone= $send['adminphone'];			$esc = array($adFname,$adPhone);		
list($adFname,$adPhone)= $instance->connection->clean($esc);	
$must = array($adFname,$adPhone);	$chkphone = $instance->ctype($adPhone);
if($must !=""){    					 if($chkphone[0]){			
$table= "admintab";					
$colVals= "adFname ='$adFname',phon='$adPhone'";		$where= "WHERE aduser = '$aduser'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}else echo"Invalid Phone Number";
}else echo "Please Fill-Up The Required Field";
}

if($send ['pusha'] =='ad4'){
$table= "v_preg";					
$cols= "*";							$where= "WHERE status= '2'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

////////////// DELETED USER'S ACCOUNT (STATUS = 5)/////////////////////
if($send ['pusha'] =='ad5'){
	$email= $send['email'];	
$table= "v_preg";					
$colVals= "status ='5'";			$where= "WHERE email ='$email' && status='2' || email='$email' && status ='1'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

////////////// SUSPENDED USER'S ACCOUNT (STATUS = 4)/////////////////////
if($send ['pusha'] =='ad6'){
$email= $send['emailid'];		$table= "v_preg";					
$colVals= "status ='4'";		$where= "WHERE email = '$email' && status ='2'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad7'){
$table= "v_preg";					
$cols= "*";							$where= "WHERE status= '4'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad8'){
$email= $send['activated'];							$table= "v_preg";					
$colVals= "status ='2'";							$where= "WHERE email = '$email' && status ='4'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad9'){
$table= "v_preg";					
$cols= "*";							$where= "WHERE status= '1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad10'){
$aduser=$_SESSION['username'];		$table= "prdts";					
$cols= "*";							$where= "WHERE status = '1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	 $_SESSION['username']=  	$aduser ;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad11'){
	$aduser=$_SESSION['username'];
$table= "prdts";					
$cols= "*";							$where= "WHERE uploader= '$aduser' && status = '1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	 $_SESSION['username'] =  	$aduser ;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad12'){
$aduser=$_SESSION['username'];
$id= $send['code'];	
$table= "prdts";					
$colVals= "status ='2'";			$where= "WHERE uploader ='$aduser' && co_de = '$id' && status ='1'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

//////////////ADMIN INBOX///////////////////////
if($send ['pusha'] =='ad13'){
$recva = "system_admin";
$table= " msgtab";					
$cols= "*";							$where= "WHERE reciever= '$recva'  && admindelete != 'yes'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

///////////////// ADMIN COMPOSE////////////////////////
if($send['pusha'] == 'ad14'){
$sender = "system_admin";					$to =	 $send['to'];
$subject = $send['subject'];				$priority = $send['priority'];
$msg = $send['msg'];						$time = time();
$uniCode = rand(10,100000000);				$status = '1';
$esc = array($to,$subject,$priority,$msg);		
list($to,$subject,$priority,$msg)= $instance->connection->clean($esc);
$must = array($to,$subject,$priority,$msg);
if($must !=""){
$table = "msgtab";							$cols = "sender,reciever,subject,priority,msgBdy,tYm,uniqCode,status";
$vals ="'$sender','$to','$subject','$priority','$msg','$t
ime','$uniCode','$status'";
$insert = $instance ->insertNOchk($table,$cols,$vals);
if($insert){ 
				$status = ($insert[0]) ? 'Success' : 'Error';
				$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
			}if(!empty($result)){ echo json_encode($result); }	
	}else echo "Please Fill All The Required Fields";
}

//////////////////// ADMIN SENT MESSAGE///////////////////////	
if($send ['pusha'] =='ad15'){
$sender = "system_admin";			$table= " msgtab";					
$cols= "*";							$where= "WHERE sender = '$sender' && admindelete != 'yes'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad16'){
$code= $send['code'];	
$table= "msgtab";					
$colVals= "admindelete ='yes'";							$where= "WHERE uniqCode = '$code' && status ='1'";
$fillUp= $instance->updateBnk($table,$colVals,$where);
if($fillUp){
	$status = ($fillUp[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $fillUp[1],'message' => $fillUp[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad17'){
$code= $send['code'];	
$table= "msgtab";					
$cols= "*";							$where= "WHERE uniqCode = '$code' && status ='1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$_SESSION['code']=$code;
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad18'){
$code=$_SESSION['code'];
$table= "msgtab";					
$cols= "*";							$where= "WHERE uniqCode = '$code' && status ='1'";
$catch= $instance->pullOut($table,$cols,$where);
if($catch){
	$status = ($catch[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $catch[1],'message' => $catch[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send ['pusha'] =='ad19'){
$username =	 "system_admin";		$rToSender= $send['sender'];
$priority = $send['msgpriority'];	$bdymsg = $send['bdymsg'];
$time = time();						$code = rand(10,10000000);
$table= "msgtab";					$col ="sender,reciever,subject,priority,msgBdy,tYm,uniqCode,status";     
$Vals= "'$username','$rToSender','reply','$priority','$bdymsg','$time','$code','1'";							
$insert= $instance->insertNOchk($table,$col,$Vals);
if($insert){
	$status = ($insert[0]) ? 'Success' : 'Error';
	$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
	}if(!empty($result)){ echo json_encode($result); }
}

if($send['pusha'] == 'ad20'){
$subusername = $send['subusername'];			$subpass = $send['subpass'];				
$sususer = $send['sususer'];					$deluser = $send['deluser'];
$actuser = $send['actuser'];					$view_actv_user = $send['view_actv_user'];
$view_sus_user = $send['view_sus_user'];		$view_inbox = $send['view_inbox'];
$create_sub_admin =$send['create_sub_admin'];	$esc = array($subusername,$subpass);		
list($subusername,$subpass)= $instance->connection->clean($esc);
$must = array($subusername,$subpass);			if($must !=""){
$table = "admintab";							
$cols = "aduser,adcode,Sus,Delete_Users,Activate_Users,View_Active_Users,View_Suspended_Users, View_Messaging,Create_Sub_Admin";
$vals ="'$subusername','$subpass','$sususer','$deluser','$actuser','$view_actv_user','$view_sus_user',
'$view_inbox','$create_sub_admin'";
$where = "WHERE aduser ='$subusername'";
$insert = $instance->insert_D($table,$cols,$vals,$where);
if($insert){ 
		$status = ($insert[0]) ? 'Success' : 'Error';
		$result = array('status'=>$status,'details' =>array('code' => $insert[1],'message' => $insert[2]));								
		}if(!empty($result)){ echo json_encode($result); }	
	}else echo "Please Fill All The Required Fields";
}

?>