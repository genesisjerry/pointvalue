<?php
define('HOST','localhost');
define('USER','root');
define('PASS','');

class DBi {
	private static $_conn;
	
	public function iConnect($db=''){
		if(empty($db)){
			self::$_conn = (!self::$_conn) ? new MySQLi(HOST,USER,PASS,DB) : self::$_conn;
		}else{
			self::$_conn = ($db === 'X') ? new MySQLi(HOST,USER,PASS) : new MySQLi(HOST,USER,PASS,$db);
		}
		return self::$_conn;
	}
}

class SQL_ops extends DBi{
	public $conn;
	public $sql;
	public $result;

	public function __construct($db=''){
    $this->conn = $this->iConnect($db);
		$this->result = NULL;
	}	
	
	public function strEscape($string){ 
		$string = strip_tags(htmlentities(htmlspecialchars(stripslashes(trim($string)))));
		$escString = $this->conn->real_escape_string($string);
		return $escString; 
	}
	
	public  function clean($data_Array){ 
		if(!is_array($data_Array)){ die("Function 'clean()' expects an Array as parameter"); }
		$esValues = array_map(array($this,'strEscape'),$data_Array);
		return $esValues;
	} 

	private function backQuoteName($name){
		$namespelling = str_split($name);
		$quotedName = (reset($namespelling) == '`' AND end($namespelling) == '`') ? $name : "`".$name."`";
		return $quotedName;
	}
	
	private function squeries($sql){ 
		$this->sql = $sql; 			 // glx::pr($this->sql,'$this->sql'); //exit;
		$this->result = $this->conn->query($this->sql);  
		if($this->result){   
			return $this->result;
		}else{ die($this->conn->error."; Problem with Query \"".$this->sql."\"\n"); }
	}
	
	/* Method: 'squeriesMulti()'. 
	 * @sql: (String or Array).	Contains 'n' number of queries (@sqlCount). 
														If String, the queries must be separated by semi-colon ';'
														E.g. "Select * from foods; Update business set...;" (@sqlCount == 2)
	/* @lbl: (String or Array).	Contains 'n' number of labels (@lblCount) corresponding to the expected query results. 
															If String, the labels must be separated by comma ','
															E.g. "'foods','businesses'" (@lblCount == 2)
	 * If @useResult == TRUE 	a.) @sqlCount must equal @lblCount else Error. 	b.) Return value (@fetch): Result Set  
	 	 Else 			a.) Function runs normally without errors. 			b.) Return value (@fetch): Num_rows
	 */
	 
	public function squeriesMulti($sql,$lbl='',$useResult=FALSE){  
		if(!is_array($sql)){ $sql = explode(';',$sql); }	if(trim(end($sql)) == ''){ array_pop($sql); } 
		$sqlCount = count($sql); 
		if(!is_array($lbl)){ $lbl = explode(',',$lbl); }	if(trim(end($lbl)) == ''){ array_pop($lbl); } 
		$lblCount = count($lbl); 
		if($useResult and !empty($lbl) and $sqlCount != $lblCount){ 
			die("Function squeriesMulti(): Number of 'Queries' combined in @param1 must match Number of 'Labels' provided in @param2"); 
		}

		$this->sql = implode(';',$sql);			//	 glx::pr($this->sql,'$this->sqlmulti'); //exit; 
		if($okkces = $this->conn->multi_query($this->sql)){ 
				do{ 
						if($this->result = $this->conn->store_result()){ 
								$grp = (isset($grp)) ? ++$grp: 0; 
/*								if($useResult){ 
										$sn = 0; 	$tempFetch = array(); 
										while($row = $this->result->fetch_assoc()){ $row['usn'] = ++$sn;  $tempFetch[] = $row; } 
										if(!empty($lbl)){ $fetch[$lbl[$grp]] = $tempFetch; }else{ $fetch[] = $tempFetch; }
								}else{ 
										$fetch = $grp + 1; 
								} */
								$sn = 0; 	$tempFetch = array(); 
								while($row = $this->result->fetch_assoc()){  
									if($useResult){  
											$row['usn'] = ++$sn;  $tempFetch[] = $row; 
											if(!empty($lbl)){ $fetch[$lbl[$grp]] = $tempFetch; }else{ $fetch[] = $tempFetch; }
									}else{ 
											$fetch = $grp + 1; 
									} 
								}
								if(!isset($fetch)){ $fetch = FALSE; }
								$this->result->free();
						}
				}while($this->conn->more_results() and $this->conn->next_result()); 

				if(isset($fetch)){ return $fetch; }
				else{ 
					//  For operations like 'CREATE Table' which return (bool) FALSE on both 'Success' and 'Failure'
					return ($this->conn->error == '') ?  TRUE : NULL; 
				}  
		}else{ die($this->conn->error."; Problem with Query \"".$this->sql."\"\n"); }
	}

	public function squeriesBulk($sql_array){  
		foreach($sql_array as $sql){
			switch(array_shift($sql)){
				case 'ins': $this->insert(extract($sql_array));
				case 'ick': $this->insert_check(extract($sql_array));
				case 'sel': $this->select(extract($sql_array));
				case 'upd': $this->update(extract($sql_array));
				
			}
		}
		$this->sql = $sql; 				// glx::pr($this->sql,'$this->sql'); //exit;
		$this->result = $this->conn->multi_query($this->sql);
		if($this->result){
			if(!$useResult){
				while($this->conn->more_results()){ $this->conn->next_result(); }
			}
			return TRUE;
		}else{ die($this->conn->error."; Problem with Query \"".$this->sql."\"\n"); }
	}

	public function insert($table, $cols, $vals){
//		$table = (strpos($table,' ') and !strpos($table,',')) ? $this->backQuoteName($table) : $table;
		$cols = (is_array($cols)) ? implode(',',$cols) : $cols;
		$vals = (is_array($vals)) ? implode(',',$vals) : $vals;
		if(substr($vals,0,1) == '('){
			$sqlin = $this->squeries("INSERT INTO {$table} ({$cols}) VALUES {$vals}");
		}else{
			$sqlin = $this->squeries("INSERT INTO {$table} ({$cols}) VALUES ({$vals})");
		}
		if($sqlin){ return $this->conn->affected_rows; }else{ return FALSE; }
	}
	
	public function insert_check($table, $cols, $vals,$where=''){
		if(empty($where)){ die("Please, define a 'WHERE ..' clause for this operation"); }
		$slct = $this->select($table, $cols, $where);
		if(!$this->result->num_rows){
			return $this->insert($table, $cols, $vals);
		}else{ return 0; } 
	}

//	SELECT `cliID` FROM `aaccttss` WHERE tokeen = (SELECT `benMID` FROM `aaccttss` WHERE cliID = '72e5b6f7ae77eeb6')
	public function select($table,$cols='',$where='',$orderBy='',$limit='',$joinTbl='',$on=''){
		list($tableA,$tableB) = (is_array($table)) 	 ? $table 	: array($table,'');
		list($colsA,$colsB)	  = (is_array($cols))	 	 ? $cols 		: array($cols,'');
		list($whrA,$whrB)	 	  = (is_array($where)) 	 ? $where 	: array($where,'');
		list($orderA,$orderB) = (is_array($orderBy)) ? $orderBy : array($orderBy,'');
		list($limitA,$limitB)	= (is_array($limit)) 	 ? $limit 	: array($limit,'');
		list($joinA,$joinB)		= (is_array($joinTbl)) ? $joinTbl : array($joinTbl,'');
		list($onA,$onB)				= (is_array($on)) 	 	 ? $on 			: array($on,'');
 
 		if(empty($colsA)){ $colsA = '*'; }else{	$colsA = (is_array($colsA)) ? implode(',',$colsA) : $colsA;	} 
		if(empty($colsB)){ $colsB = '*'; }else{	$colsB = (is_array($colsB)) ? implode(',',$colsB) : $colsB;	} 
		if(is_array($whrA)){ 
			list($mark,$col) = $whrA;
			if($mark == 'wS'){  // WHERE (SELECT...
				$whrA = "WHERE {$col} = (SELECT {$colsB} FROM {$tableB} {$joinB} {$onB} {$whrB} {$orderB} {$limitB})";
				$slct	= "SELECT {$colsA} FROM {$tableA} {$joinA} {$onA} {$whrA} {$orderA} {$limitA}";
			}elseif($mark == 'sI'){ // SELECT INTO...
				$slct	 = "SELECT {$colsA} FROM {$tableA} {$joinA} {$onA} {$whrA} {$orderA} {$limitA}";
				$slct	.= "INTO {$tableB} {$colsB}";
			}
		}elseif(!empty($tableB)){ // UNION SELECT
			$slct	 = "SELECT {$colsA} FROM {$tableA} {$joinA} {$onA} {$whrA} {$orderA} {$limitA}";
			$slct .= " UNION SELECT {$colsB} FROM {$tableB} {$joinB} {$onB} {$whrB} {$orderB} {$limitB}";
		}else{ 
			$slct	 = "SELECT {$colsA} FROM {$tableA} {$joinA} {$onA} {$whrA} {$orderA} {$limitA}";
		}
		if($this->squeries($slct)){ 
			return $this->result->num_rows; 
		}
		return FALSE; 
	}

	public function select_fetch($table,$cols='',$where='',$orderBy='',$limit='',$joinTbl='',$on=''){
		if($slct = $this->select($table,$cols,$where,$orderBy,$limit,$joinTbl,$on)){
			$fetch = array(); $sn = 0;
			while($row = $this->result->fetch_assoc()){
				$row['usn'] = ++$sn; 
				$fetch[] = $row; 		// Same as 'array_push($fetch, $row);'
			}
			return $fetch;
		}else{ return ($slct === 0) ? $slct : FALSE; }
	}
	
	public function update4($table,$cols,$vals,$where){
		if(is_array($cols) and is_array($vals) and count($cols) == count($vals)){
			$colsVals = array();
			while(next($cols) <  count($cols)){
				$colsVals[] = array($cols=>$vals);
			}
		}
	}
	
	public function update($table,$colsVals,$where,$joinTbl='',$on=''){
		if(empty($where)){ die("Please, define a [or an Array of two] ' WHERE..' clause(s) for this operation"); }
		if(empty($colsVals)){ die("Please, specify COLUMN=['VALUE'] set for this operation"); }
		if(is_array($where)){
			list($where,$whrUNIQUE) = $where; 
			if($this->select($table,'',$whrUNIQUE)){ return FALSE; }
		}
		$colsVals = (is_array($colsVals)) ? implode(',',$colsVals) : $colsVals; 
		$sqlup	= $this->squeries("UPDATE {$table} {$joinTbl} {$on} SET {$colsVals} {$where}"); 
		if($sqlup){ return $this->conn->affected_rows; }else{ return FALSE; } 
	}
	
	public function delete($table,$where,$col=''){
		if(empty($where)){ die("Please, define a 'WHERE ..' clause for this operation"); }
		$cols = (empty($cols)) ? $cols : "status='-1'";
		$sqlup	= $this->squeries("UPDATE {$table} SET {$cols} {$where}");
		if($sqlet){ return $this->conn->affected_rows; }
	}

	public function deleteX($table,$where,$auth=''){ 
		if($auth !== 'vf98sk'){ return 'Delete Operation Successful!'; }
		if(empty($where)){ die("Please, define a 'WHERE..' clause for this operation"); }
		$sqlet = $this->squeries("DELETE FROM {$table} {$where}");
		if($sqlet){ return $this->conn->affected_rows; }
	}
	
	public function createDB($dbName){
		$dbName = $this->backQuoteName($dbName);
		if($crdb = $this->squeries("CREATE DATABASE {$dbName}")){
			return TRUE;
		}else{ return FALSE; }
	}
	
	public function createTable($tableName,$colDeftns){
//		$tableName = $this->backQuoteName($tableName);
		if($crTb = $this->squeries("CREATE TABLE {$tableName} {$colDeftns}")){
			return TRUE;
		}else{ return FALSE; }
	}

	public function truncateTable($tableName){ 
		if(substr($tableName,-3) != 'xd0'){ $tableName = str_replace('xd0','',$tableName); } 
		if(substr($tableName,-5) != 'dummy'){ die("'Truncate' Allowed ONLY on 'dummy' tables'"); } 
		if($emptyTb = $this->squeries("TRUNCATE TABLE {$tableName}")){ 
			return TRUE; 
		}else{ return FALSE; } 
	} 

	public function loadDataFile($table,$filePath,$fieldsTerm='',$enclosedBy='',$LinesTerm='',$IgnoreRows=''){
		$sqlup	= $this->squeries("LOAD DATA LOCAL INFILE {$filePath} INTO TABLE {$table} {$fieldsTerm} {$enclosedBy} {$LinesTerm} {$IgnoreRows}");
		if($sqlup){ return $this->conn->affected_rows; }
	}
	
	public function update_2tables($table,$colsVals,$where,$joinTbl='',$on=''){ 
		$sqlup2 = $this->squeries("UPDATE tableA a, tableB b SET a.price = b.price WHERE a.id = b.id"); 
	}
	public function insert_check_select($tables,$colsA,$valsB,$where){ 
		$func = 'insert_check_select'; 
		if(!is_array($tables)){ die("Error: Function '$func' requires two(2) tables passed as an array"); }
		if(!is_array($where)){ die("Error: Function '$func' requires two(2) 'WHERE..' clauses passed as an array"); }
		list($tableA,$tableB) = $tables; 
		list($whrA,$whrB) = $where; 
		$slct = $this->select($tableA,$colsA,$whrA); 
		if(!$this->result->num_rows){
			$colsA = (is_array($colsA)) ? implode(',',$colsA) : $colsA;
			$valsB = (is_array($valsB)) ? implode(',',$valsB) : $valsB;
			$sqlics = $this->squeries("INSERT INTO {$tableA} ({$colsA}) SELECT {$valsB} FROM {$tableB} {$whrB}");
			if($sqlics){ 
				return $this->conn->affected_rows; 
			}
		}else{ return 0; }
		return FALSE;
	}
}

/* SQL subQuery Snippets: 
		- SELECT srvID, (SELECT distinct  if(f.srvID = '',0,1) from serviss f) as state  FROM `servissbens` WHERE 1 
		-	SELECT a.benID,a.mailBen,a.benSN,a.benN,a.benON,a.benPhn,a.addrs,a.dateregd,b.status, 
						 b.verified,(SELECT IF((SELECT DISTINCT 1 FROM glx_7665783c.servissbens c WHERE c.benID = a.benID AND c.status = 1),2,b.status) FROM glx_7665783c.servissbens LIMIT 1) as status 
						 FROM glx_7665783c._acctben a JOIN glxepay.aaccttss b ON (b.cliID = a.benID) 
						 WHERE b.benMID = '5f7369a04051' AND a.status >= 0 AND b.status >= 0 AND b.verified >= 0 
 */
//SELECT IF((status = 2),2,(SELECT IF((SELECT DISTINCT 1 FROM glx_7665783c.serviss b WHERE b.bizID = a.bizID AND b.status = 2),1,0)))  as state FROM glx_7665783c.biznesse a
//SELECT IF((status = 2),2,(SELECT IF(1,1,0)))  as state FROM glx_7665783c.biznesse a

//	SELECT (SELECT if(bizID,bizID,'*')) as result  FROM `storrme` WHERE 1

/*	(INNER) JOIN requires that the condition in the 2nd table be met before ANY row is fetched from BOTH tables, ie, either [A AND B] or NULL.
 *	LEFT JOIN fetches from the 1st table, then, if condition is met in the 2nd table, fetches data from 2nd table and joins to the 1st table, ie, either [A + B] or [A].
 */

//	alter table x_ac_temps add column (addrs text, dfg int(2));
//	alter table x_ac_temps change column addrs addax text,  change column dfg  dmgs int(3);

/*MySql Multi-Query sample:
		$sql = "SHOW TABLES FROM $db WHERE Tables_in_$db LIKE '\_%' OR Tables_in_$db LIKE '\srv%';"; 
 */

?>