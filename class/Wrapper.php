<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* wrapper.php -> singleton class Wrapper
* manages database connections
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require 'nixda/DBconfig.php';
class Wrapper{
	
	private $pdo;
	private static $instance = null;
	protected function __construct(){
		$this->pdo = new PDO(DB_PGSQL_DSN, DB_USER, DB_PASS);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		#echo "database connection up.";
	}
	
	public function __destruct(){}
	
	private final function __clone(){}
	
	public static function getInstance(){
		if(self::$instance == null){
			self::$instance = new Wrapper();
		}
		return self::$instance;
	}
	
	public static function talk(){
		return "ich bin der Wrapper, jo!";
	}
	
	public function run($sql, $params = NULL){
   	$stmt = $this->pdo->prepare($sql);
   	$stmt->execute($params);
   	return $stmt; 
	}
	
	public function insert($table, $fields_arr){
		echo "Wrapper: _insert";
		$keys = array();
		$vals = array();
		
		foreach($fields_arr as $key=>$val){
			$keys[] = $key;
			$repl[] = "?";
			$vals[] = $val;
		}
		$sql = "INSERT INTO ".$table." (".implode(", ",$keys).") ";
		$sql .= "VALUES (".implode(", ",$repl).")";
		echo $sql;
		$stmt = $this->pdo->prepare($sql);
		$result = $stmt->execute($vals);
		$id = $this->pdo->lastInsertId($table.'_id_seq');
		#print_r($result);
		return $id;
	}
	
	public function insertWithoutId($table, $fields_arr){
		echo "Wrapper: _insertWithoutId";
		$keys = array();
		$vals = array();
		
		foreach($fields_arr as $key=>$val){
			$keys[] = $key;
			$repl[] = "?";
			$vals[] = $val;
		}
		$sql = "INSERT INTO ".$table." (".implode(", ",$keys).") ";
		$sql .= "VALUES (".implode(", ",$repl).")";
		echo $sql;
		$stmt = $this->pdo->prepare($sql);
		$result = $stmt->execute($vals);
		#print_r($result);
		return $result;
	}
	
	public function selectAll($table, $sortopt=null){
		$sql = "SELECT * FROM ".$table;
		if($sortopt) {
			$sql .= " ORDER BY ".$sortopt;
		}
		$resultobj = $this->pdo->query($sql);
		return $resultobj->fetchAll();
	}
	
	
	public function selectWhere($table, $sortopt, $conditions, $operator="AND"){
		if(!(strtoupper($operator)=== "AND" || strtoupper($operator)=== "OR")){
			echo "FEHLER: Operator kann nur AND oder OR sein.";
		} else {
		$vals=array();
		$sql = "SELECT * FROM ".$table." WHERE ";
		foreach($conditions as $field => $value){
				$fields[] = $field." = ?";
				$vals[] = $value;
				
		}
		#print_r($vals);
		$sql .= implode(" ".$operator." ", $fields);
		if($sortopt) {
			$sql .= " ORDER BY ".$sortopt;
		}
		$stmt = $this->pdo->prepare($sql);
   	$stmt->execute($vals);
		return $stmt;
		}
	}
	
	public function updateWhere($table, $columnvals, $conditions, $operator="AND"){
		if(!(strtoupper($operator)=== "AND" || strtoupper($operator)=== "OR")){
			echo "FEHLER: Operator kann nur AND oder OR sein.";
		} else {
			$columns = array();
			$values = array();
			$condkeys = array();
			$condvals = array();
			$sql = "UPDATE $table SET ";
			foreach($columnvals as $col=>$val){
				$columns[] = $col."=?";
				$values[] = $val;
			}
			$sql .= implode(", ", $columns);
			$sql .= " WHERE ";
			foreach($conditions as $key => $value){
				$condkeys[] = $key." = ?";
				$condvals[] = $value;
				
			}
		$sql .= implode(" ".$operator." ", $condkeys);
			$v = array_merge($values, $condvals);
		}
		$stmt = $this->pdo->prepare($sql);
   	$stmt = $stmt->execute($v);
		return $stmt;
	}
	
	function deleteWhere($table, $conditions, $operator='AND'){
		$keys = array();
		$vals = array();
		
		foreach($conditions as $key => $value){
				$keys[] = $key." = ?";
				$vals[] = $value;
				
		}
		$sql = "DELETE FROM ".$table." WHERE ";
		$sql .= implode(" ".$operator." ", $keys);
		echo $sql;
		print_r($vals);
		$stmt = $this->pdo->prepare($sql);
		$result = $stmt->execute($vals);
		print_r($result);
		return $result;
	}
	
	function beginTransaction() {
		$this->pdo->beginTransaction();
	}
	function commit() {
		$this->pdo->commit();
	}
	function rollback() {
		$this->pdo->rollBack();
	}

}
?>