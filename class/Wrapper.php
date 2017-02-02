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
		echo "database connection up.";
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
	
	function run($sql, $params = NULL){
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
		$stmt->execute($vals);
	}
	
	public function selectAll($table, $sortopt=null){
		$sql = "SELECT * FROM ".$table;
		if($sortopt) {
			$sql .= " ORDER BY ".$sortopt;
		}
		$resultobj = $this->pdo->query($sql);
		return $resultobj->fetchAll();
	}
	
	public function selectWhere($table, $conditions, $operator="AND"){
		if(!(strtoupper($operator)=== "AND" || strtoupper($operator)=== "OR")){
			echo "FEHLER: Operator kann nur AND oder OR sein.";
		} else {
		$vals=array();
		$sql = "SELECT * FROM ".$table." WHERE ";
		foreach($conditions as $field => $value){
				$fields[] = $field." = ?";
				$vals[] = $value;
				
		}
		print_r($vals);
		$sql .= implode(" ".$operator." ", $fields);
		$stmt = $this->pdo->prepare($sql);
   	$stmt->execute($vals);
		echo "Wrapper selectWhere abgeschlossen.";
		return $stmt;
		}
	}

}
?>