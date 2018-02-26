<?php
include("lib/base_fxns.php");
session_start();

class poin_value{
	public $database;
	public $connection;
	
public function __construct($database){
	$this->database = $database;
	$this->connection = new SQL_ops($this->database);
	return $this->connection;
}

public function insert_D($table,$col,$val,$where){
	$insert = $this->connection->insert_check($table,$col,$val,$where);
	if(!empty($insert )){
		{return array(TRUE,'00',$insert );}
	}else{ return array(FALSE,'01','Something Went Wrong');}
}

public function insertNOchk($table,$col,$val){
	$insert = $this->connection->insert($table,$col,$val);
	if(!empty($insert )){
		{return array(TRUE,'00',$insert );}
	}else{ return array(FALSE,'01','Something Went Wrong');}
}


public function updateBnk($table,$colVals,$where){
	$update = $this->connection->update($table,$colVals,$where);
	if(!empty($update)){
		{return array(TRUE,'00',$update );}
	}else{ return array(FALSE,'01','Something Went Wrong');}
}
	
public function ctype($valuse){
	$check = ctype_digit($valuse);
	if(!$check){
		echo "Please Verify Your Inputs";
	}else return array (true,$check);
}
	
public function pullOut($table,$col,$where){
	$obj = $this->connection->select_fetch($table,$col,$where);
	if(!empty($obj)){
		return array (true,'00',$obj);
	}else return array(false,'01','Something Went Wrong');
}
	
public function login($table,$col,$where){
	$log = $this->connection->select_fetch($table,$col,$where);
	if(!empty($log)){
		return array (true,'00',$log);
	}else return array(false,'01','Incorrect Login Details');
}

public function ballOut(){
	if(isset ($_SESSION['username'])){
		$out = session_destroy();
		if($out == true){
			return array(true,'03','Ox');
		}else return array(false,'07','Something Went Wrong');	
	}
}
	
public function enterDmin($table,$col,$where){
	$obj = $this->connection->select($table,$col,$where);
	if(!empty($obj)){
		return array (true,'00',$obj);
	}else return array(false,'01','Incorrect Login Details');
}
	
public function Bonus(){
	$Pv = array('5000','40','60','100');
	$fdWallet = ($Pv[1]/$Pv[3] * $Pv[0]);
	$E_wallet = ($Pv[2]/$Pv[3] * $Pv[0]);
	if(!empty($fdWallet ) || !empty($E_wallet)){
		$reB = $this->parentID();
		return array(true,'00',$fdWallet,$E_wallet);
	}else return array(false,'01','Something Went Wrong');
}
public function parentID(){
$username =	$_SESSION['username'];				$userId=$_SESSION['id'];
 $amt = array('1500','1000','700','300','150','100','100','100','50');
 $table="v_preg";								$col ="*";
$where ="WHERE username='$username' && u_serid='$userId'";
$refID = $this->connection->select_fetch($table,$col,$where);	
$ref_B = $refID[0]['refemailid'];

$table1="cashwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
	$ini_EBlnc =$refDetls[0]['avCash'];
$prcnt = array('40','60','100'); 
$E_wallet = ($prcnt[1]/$prcnt[2] * 2000);
$uptym =time();
$toTal_EB = ($ini_EBlnc + $E_wallet);	
$tablee="cashwallet";							$colVals ="avCash ='$toTal_EB', lastUpdates ='$uptym '";
$wheree ="WHERE user_id='$ref_B'";
$updEBlnc = 	$this->connection->update($tablee,$colVals,$wheree);


$table1="foodwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
	$ini_FBlnc =$refDetls[0]['avCash'];	
$fdWallet = ($prcnt[0]/$prcnt[2] * 2000); 	$toTal_FB = ($ini_FBlnc + $fdWallet);
$tablef="foodwallet";							$colValsf ="avCash ='$toTal_FB ', lastUpdates ='$uptym'";
$wheref ="WHERE user_id='$ref_B'";
$updFBlnc = 	$this->connection->update($tablef,$colValsf,$wheref);
 
 
 
 for($n = 0; $n < 9; $n++){ 
$table="v_preg";								$col ="*";
$where ="WHERE u_serid='$ref_B'";
$refID = $this->connection->select_fetch($table,$col,$where);	
$ref_B = $refID[0]['refemailid'];
 
 $table1="cashwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
	$ini_EBlnc =$refDetls[0]['avCash'];
$prcnt = array('40','60','100'); 
$E_wallet = ($prcnt[1]/$prcnt[2] * $amt[$n]);
$uptym =time();
$toTal_EB = ($ini_EBlnc + $E_wallet);	
$tablee="cashwallet";							$colVals ="avCash ='$toTal_EB', lastUpdates ='$uptym '";
$wheree ="WHERE user_id='$ref_B'";
$updEBlnc = 	$this->connection->update($tablee,$colVals,$wheree);


$table1="foodwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
	$ini_FBlnc =$refDetls[0]['avCash'];	
$fdWallet = ($prcnt[0]/$prcnt[2] * $amt[$n]); 	$toTal_FB = ($ini_FBlnc + $fdWallet);
$tablef="foodwallet";							$colValsf ="avCash ='$toTal_FB ', lastUpdates ='$uptym'";
$wheref ="WHERE user_id='$ref_B'";
$updFBlnc = 	$this->connection->update($tablef,$colValsf,$wheref);
 

$ref_B = $refID[0]['refemailid'];
//var_dump($ref_B );die();
$pvl =	 $this->poinVal();
if(empty($ref_B)){
	break;
	}
	}

	}
	
public function getEWb(){
	$ref_B = $this->parentID(); 
$table1="cashwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
	$ini_EBlnc =$refDetls[0]['avCash'];		
return $ini_EBlnc;
	}
	
public function getFWb(){
$ref_B = $this->parentID(); 
$table1="foodwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
	$ini_FBlnc =$refDetls[0]['avCash'];	
return $ini_FBlnc;
	}
	
public function refdisBurse(){
$ref_B = $this->parentID(); $ini_EBlnc = $this->getEWb();  $ini_FBlnc = $this->getFWb();
$prcnt = array('40','60','100'); 
$amt = array('2000','1500','1000','700','300','150','100','50');
$E_wallet = ($prcnt[1]/$prcnt[2] * $amt[0]);
$uptym =time();
$toTal_EB = ($ini_EBlnc + $E_wallet);	
$tablee="cashwallet";							$colVals ="avCash ='$toTal_EB', lastUpdates ='$uptym '";
$wheree ="WHERE user_id='$ref_B'";
$updEBlnc = 	$this->connection->update($tablee,$colVals,$wheree);

$fdWallet = ($prcnt[0]/$prcnt[2] * $amt[0]); 	$toTal_FB = ($ini_FBlnc + $fdWallet);
//var_dump($ini_FBlnc);die();
$tablef="foodwallet";							$colValsf ="avCash ='$toTal_FB ', lastUpdates ='$uptym'";
$wheref ="WHERE user_id='$ref_B'";
$updFBlnc = 	$this->connection->update($tablef,$colValsf,$wheref);
if(!empty($updFBlnc)){
$pvl =	 $this->poinVal();
}else return array(false,'01','Something Went Wrong');	
	}
	
public function spillOva(){
	$username = $_SESSION['username']; $userId=$_SESSION['id'];
$table="v_preg";								$col ="*";
$where ="WHERE username='$username' && u_serid='$userId'";
$refID = $this->connection->select_fetch($table,$col,$where);	
	$refI_D = $refID[0]['refemailid'];
	
$table="v_preg";								$col ="*";
$where ="WHERE  u_serid='$refI_D'";
$refID_ = $this->connection->select_fetch($table,$col,$where);	
$refNUm = $refID_[0]['count'];	
//var_dump($refNUm);die();
if($refNUm == ''){	
$tablee="v_preg";							$colVals ="first_D='$userId',count='1'";
$wheree ="WHERE u_serid='$refI_D'";	
$frst = 	$this->connection->update($tablee,$colVals,$wheree);
}elseif($refNUm == '1'){
$tablee="v_preg";							$colVals ="second_D='$userId',count='2'";
$wheree ="WHERE u_serid='$refI_D'";	
$snd = 	$this->connection->update($tablee,$colVals,$wheree);
	}elseif($refNUm == '2'){
$tablee="v_preg";							$colVals ="third_D='$userId',count='3'";
$wheree ="WHERE u_serid='$refI_D'";	
$thrd = 	$this->connection->update($tablee,$colVals,$wheree);
	}elseif($refNUm == '3'){
$table="v_preg";								$col ="*";
$where ="WHERE u_serid='$refI_D'";
$refID = $this->connection->select_fetch($table,$col,$where);
$fst = $refID[0]['first_D'];	$scnd = $refID[0]['second_D'];	$thrd = $refID[0]['third_D'];
//return array ($fst,$scnd,$thrd);
//function grndSpil($fst,$scnd,$thrd){
	$tymOcrd = 0;
	$ids = array ($fst,$scnd,$thrd);
	foreach($ids  as $fst=>$value){
	//var_dump($value);die();
	//for($m = 0;$m<1;$m++){
		$table ="v_preg";
		$col="*";
		$where ="WHERE u_serid='$value'";
		$count =$this->connection->select_fetch($table,$col,$where);
		$cnt = $count[0]['count'];
		if($cnt ==''){
			$tablee="v_preg";							$colVals ="first_D='$userId',count='1'";
		$wheree ="WHERE u_serid='$value'";
		//var_dump($wheree);die();	
		$frst = 	$this->connection->update($tablee,$colVals,$wheree);
		break;
			}elseif($cnt == '1'){
		$tablee="v_preg";							$colVals ="second_D='$userId',count='2'";
		$wheree ="WHERE u_serid='$value'";	
		//var_dump($wheree);die();	

		$snd = 	$this->connection->update($tablee,$colVals,$wheree);
		break;
		}elseif($cnt  == '2'){
		$tablee="v_preg";							$colVals ="third_D='$userId',count='3'";
	$wheree ="WHERE u_serid='$value'";	
	$thrd = 	$this->connection->update($tablee,$colVals,$wheree);
	break;
	}elseif($cnt == '3'){
		$tymOcrd = 0;
		if($tymOcrd <3){
	$tymOcrd++;
	}elseif($tyOcrd ==3){
		$p =0;
	$table="v_preg";								$col ="*";
$where ="WHERE u_serid='$value'";
$refID = $this->connection->select_fetch($table,$col,$where);
$gfst = $refID[0]['first_D'];	$gscnd = $refID[0]['second_D'];	$gthrd = $refID[0]['third_D'];	
	
	
	//}
	
	}
	}
 //grndSpil($gfst,$gscnd,$gthrd);		
	}
}


/*public function referralB(){
$ref_B = $this->parentID(); $ini_EBlnc = $this->getEWb();
$amt = array('2000','1500','1000','700','300','150','100','50');
$Pv = array('40','60','100');	
$E_wallet = ($Pv[1]/$Pv[2] * $amt[0]);
$uptym =time();
$toTal_EB = ($ini_EBlnc + $E_wallet);
$tablee="cashwallet";							$colVals ="avCash ='$toTal_EB', lastUpdates ='$uptym '";
$wheree ="WHERE user_id='$ref_B'";
$updEBlnc = 	$this->connection->update($tablee,$colVals,$wheree);
$fdWallet = ($Pv[1]/$Pv[3] * $Pv[0]);
$table1="foodwallet";							$col1 ="*";
$where1 ="WHERE user_id='$ref_B'";
$refDetls = $this->connection->select_fetch($table1,$col1,$where1);
$ini_FBlnc =$refDetls[0]['avCash'];				$toTal_FB = ($ini_FBlnc + $fdWallet);
$tablef="foodwallet";							$colValsf ="avCash ='$toTal_FB ', lastUpdates ='$uptym'";
$wheref ="WHERE user_id='$ref_B'";
$updFBlnc = 	$this->connection->update($tablef,$colValsf,$wheref);
if(!empty($updFBlnc)){
$pvl =	 $this->poinVal();
}else return array(false,'01','Something Went Wrong');
}*/

public function poinVal(){
$username =	$_SESSION['username'];				$userId=$_SESSION['id'];
$tableP="v_preg";
$colP ="*";
$whereP="WHERE username='$username' && u_serid='$userId'";	
$get_P = $this->connection->select_fetch($tableP,$colP,$whereP);
$ref_ID = $get_P[0]['refemailid'];	
$tablePV ="v_preg";
$colPV ="*";
$wherePV ="WHERE  u_serid='$userId'";
$get_PV = $this->connection->select_fetch($tablePV,$colPV,$wherePV);	
$initPV =$get_PV[0]['pointV'];
$curntPV =($initPV + '100');
$tablePV ="v_preg";
$colvalPV ="pointV = '$curntPV'";
$wherePV ="WHERE  u_serid='$ref_ID'";
$set_PV  = $this->connection->update($tablePV,$colvalPV,$wherePV);
if(!empty($set_PV)){
	return array (true, '00',$set_PV);
}else return array(false,'01','Something Went Wrong');
	}

}