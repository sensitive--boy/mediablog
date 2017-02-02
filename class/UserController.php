<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* UserController.php -> class UserController
* manages users
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class UserController{	
	private $model;
	private $view;
	private $templatePath = 'templates';
	private $template = 'all_users';
	private $title = 'User';
	private $request;
	private $action;
	private $language;
	
	public function __construct($request) {
		echo " / UserController erzeugt mit action ".$request['action'];
		$this->model = new UserModel();
		$this->view = new View();
		$this->request = $request;
		$this->action = $this->request['action'];
		$this->language = $this->request['lang'];
	}
	
		
	public function login($n, $e, $p){
		echo "<br />you just called login().<br />";
		$user = $this->model->getUserFromLogin($n, $e, $p);
		
		if(!empty($user)){
			session_start();
			$_SESSION['user'] = $user->getID();
			$_SESSION['uname'] = $user->getName();
			$_SESSION['loggedIn'] = 1;
			$_SESSION['editor'] = $user->isEditor();
			print_r($_SESSION);
			return $user;
		} else {
			self::$notices[] = "dich gibt es nicht.";
			return false;
		}
	}
	
	
	public function signup($n, $email, $p){
		echo "<br>called signup method";
		$user = $this->model->registrateUser($n, $p, $email);

		if(!empty($user)){
			session_start();
			$_SESSION['logged_in'] = true;
			return $user;
		} else {
			return false;
		}
	}
	
	public function display() {
		$this->view->putContents('lang', $this->language);
		switch($this->action) {
			case 'new':
				echo $this->action;
				$this->title = " | new user";
				$user = $this->model->createUser();
				$this->view->putContents('user', $user);
				$this->template = 'signup_form';
				break;
			case 'signup':
				$user = $this->signup($this->request['username'], $this->request['email'], $this->request['pw2']);
				if($user) {
					$this->title = " | Welcome";
					$this->template = 'intern';
					$this->view->putContents('user', $user);
				}	
				break;
			case 'login':
				$this->title = " | login";
				$user = $this->model->createUser();
				$this->view->putContents('user', $user);
				$this->template = 'login_form';
				break;
			case 'getuser':
				$user = $this->login($this->request['username'], $this->request['email'], $this->request['pass']);
				$this->title =" | Welcome";
				$this->view->putContents('user', $user);
				$this->template = 'intern';
				break;
			default:
				echo $this->action;
				$us = $this->model->getUsers();
				$this->view->putContents('users', $us);
				$this->title = " | user";
				$this->template = 'users_all';
				break;
		
		}
	
		$this->view->setTemplate($this->template);
		return $this->view->loadTemplate();
	}
	
	public function getTitle(){
		return $this->title;	
	}
}
?>