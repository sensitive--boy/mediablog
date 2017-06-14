<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* User.php -> Einfache Klasse User
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class User{
	private $u_id;
	private $uname;
	private $email;
	private $pw;
	private $isEditor;
	
	public function __construct($id, $name, $pw, $email, $isEditor = 0){
		$this->u_id = $id;
		$this->uname = $name;
		$this->pw = $pw;
		$this->email = $email;
		$this->isEditor = $isEditor;
	}
	public function getId(){
		return $this->u_id;
	}
	public function setId($id){
		$this->u_id = $id;
	}
	public function setName($n){
		$this->uname = $n;
	}
	public function getName(){
		return $this->uname;
	}
	public function setEmail($m){
		$this->email = $m;
	}
	public function getEmail(){
		return $this->email;
	}
	public function setPassword($p){
		$this->pw = sha1($p);
	}
	public function getPassword(){
		return $this->pw;
	}
	public function isEditor(){
		return $this->isEditor;
	}
	public function setToEditor(){
		$this->isEditor = true;
	}
	public function unsetEditor(){
		$this->isEditor = false;
	}
}
?>