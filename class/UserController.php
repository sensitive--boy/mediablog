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
include_once 'include/functions.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class UserController{	
	private $model;
	private $view;
	private $templatePath = 'templates/users';
	private $template = 'all_users';
	private $title = 'User';
	private $request;
	private $action;
	private $language;
	private $goal = "";
	
	public function __construct($request) {
		#echo " / UserController erzeugt mit action ".$request['action'];
		$this->model = new UserModel();
		$this->view = new View($this->templatePath);
		$this->request = $request;
		$this->action = !empty($this->request['action']) ? $this->request['action'] : 'login';
		$this->language = $this->request['lang'];
	}
	
		
	public function login($n, $e, $p){
		#echo "<br />you just called login().<br />";
		$user = $this->model->getUserFromLogin($n, $e, $p);
		
		if(!empty($user)){
			session_start();
			$_SESSION = array();
			$_SESSION['user'] = $user;
			$_SESSION['uname'] = $user->getName();
			$_SESSION['logged_in'] = 1;
			$_SESSION['editor'] = $user->isEditor();
			return $user;
		} else {
			#echo "keine session.";
			self::$notices[] = "dich gibt es nicht.";
			return false;
		}
	}
	
	
	public function signup($n, $email, $p){
		#echo "<br>called signup method";
		$user = $this->model->registrateUser($n, $p, $email);
		$loggedInUser = $this->login($user->getName(), $user->getEmail(), $user->getPassword());
		return $loggedInUser;
	}
	
	public function display() {
		$this->view->putContents('lang', $this->language);
		switch($this->action) {
			case 'new':
				echo $this->action;
				$this->title = " | new user";
				$user = $this->model->createUser();
				$this->view->putContents('user', $user);
				$this->view->putContents('goal', $this->goal);
				$this->template = 'signup_form';
				break;
			case 'getuser':
			echo "getuser aus UserController";
				$user = $this->login($this->request['username'], $this->request['email'], $this->request['pass']);
				$this->title =" | Welcome";
				$this->view->putContents('user', $user);
				$this->view->putContents('goal', $this->goal);
    			$this->template = 'intern';
				break;
			case 'edit_info':
				#echo " I will allow you to edit information about you.";
				$info = $this->model->getUserInfo($this->request['user_id']);
				#echo " Userinfo ist da.";
				$this->view->putContents('userinfo', $info);
				$this->title = " | edit about me";
				$this->template = 'about_me_edit';
				break;
			case 'save_info':
				echo "save userinfo";
				$result = $this->model->saveUserInfo($this->request);
				if($result) {
					header('Location:?controller=blogs&action=mystuff&user='.$this->request['user_id'].'&lang='.$this->language);
				} else {
					header('Location:?contoller=user&action=edit_info&user_id='.$this->request['user_id'].'&lang='.$this->language);
				}
				break;
			default:
				echo $this->action;
				$us = $this->model->getUsers();
				$this->view->putContents('users', $us);
				$this->view->putContents('goal', $this->goal);
    			#echo "default: ".$this->goal;
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
	public function setGoal($goal){
		#echo "setGoal with: ".$goal;
		$this->goal = $goal;
	}
}
?>