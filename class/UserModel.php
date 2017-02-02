<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* UserModel.php -> class UserModel
* cares about User objects
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class UserModel {
	private $table;
	private $wrapper;
	private $sortopt = "username ASC";
	
	public function __construct() {
		$this->wrapper = Wrapper::getInstance();
		$this->table  = 'tiusers';
	}
	public function createUser(){
		$user = new User(1, "", "","",0);
		echo "createUser from UserModel";
		return $user;
	}
	
	public function getUserFromLogin($n, $p, $e){
		echo "called getUserFromLogin.";
		$conditions=array();
		$conditions['username'] = $n;
		$conditions['email'] = $e;
		#$conditions['verified'] = true;
		
		$result = $this->wrapper->selectWhere($this->table, $conditions, "AND")->fetch();
		echo "Passwort: ".$result['pw'];
		if(password_verify($p, $result['pw'])) {
			echo "User gefunden.";
			$user = new User($result['id'], $result['username'], $p, $result['email'], $result['is_editor']);
		} else {
			echo "kein User gefunden.";
			$user = null;
		}
		return $user; 
		
	}
	
	
	public function registrateUser($n, $p, $email){
		$n = substr($n, 0, 20);
		$user = $this->getUserFromLogin($n, $p, $email);
		if($user != null){
					echo "User allready exists";
			#PagesController::$notices[] = "Diese Username ist bereits mit dieser email registriert. Bitte anderen Usernamen oder Email wählen.";
		} else {
			echo "User wird eingetragen.";
			$data["username"] = $n;
			$data["pw"] = password_hash($p, PASSWORD_DEFAULT);
			$data["email"] = $email;
			$this->wrapper->insert($this->table, $data);
			echo "User wurde eingetragen.";
			print_r($data);
			return $this->getUserFromLogin($n, $p, $email); 
		}
	}
	
	public function getUser($id){
		$conditions=array();
		$conditions["id"] = $id;
		$result = $this->wrapper->selectWhere($this->table, $conditions)->fetch();
		$user = new User($result['id'], $result['username'], $result['pw'], $result['email'], $result['is_editor']);
		return $user;
	}
	
	public function getUsers(){
		$userlist = [];
		$resultset = $this->wrapper->selectAll($this->table, $this->sortopt);
		if(!empty($resultset)) {
			foreach($resultset as $u){
				$userlist[] = new User($u['id'], $u['username'], $u['pw'], $u['email'], $u['is_editor']);
			}
		} else { // Error!
			$userlist[] = 'albert';
			$userlist[] = 'kaus';
			$userlist[] = 'peter';
		}
		return $userlist;
	}
	
	
	public function updateUser($params){
		//To do: implement
	}
	
	public function setSortoption($sortoption) {
		$this->sortopt = $sortoption;
	}
	
	public function talk(){
		return "UserModel";
	}

}
?>