<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* PagesController.php -> class PagesController
* controller for displaying all pages
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
#require_once('PageView.php');
#require_once('StyleModel.php');
require_once 'class/Controller.php';
require_once 'nixda/settings.php';
require_once 'class/TiAutoloader.php';
include_once 'include/functions.php';
spl_autoload_register("TiAutoloader::classLoader");
class PagesController{
	public static $notices = array();
	private $request = null;
	private $view = null;
	private $controller;
	private $action;
	private $container;
	private $language;
	private $layout;
	private $displayMode;
	private $customStyle;
	private $model;
	
	// a list of the controllers we have and their actions
  	// we consider those "allowed" values
  	private $controllers = array('pages' => ['home', 'error', 'contact', 'imprint', 'faq', 'privacy', 'funding'],
  							  'blogs' => ['index', 'show', 'create', 'edit', 'mystuff', 'new', 'update'],
  							  'user' => ['index', 'show', 'new'],
  							  'posts' => ['index', 'show', 'create', 'update', 'edit', 'save', delete]
  							 );
	
	public function __construct($request){
		#echo " / PagesController";
		$this->view = new PageView();
		$this->model = new StyleModel();
		$this->request = $request;
		$this->language = !empty($request['lang']) ? $request['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : LANGUAGE);
		$this->layout = !empty($request['layout']) ? $request['layout'] : 'standard';
		$this->displayMode = !empty($request['displayMode']) ? $request['displayMode'] : 'fullVision';
		$this->controller = !empty($request['controller']) ? $request['controller'] : 'pages';
		$this->action = !empty($request['action']) ? $request['action'] : 'home';
	}
	public function __destruct(){
	
	}
	
	public function logout(){
		$_SESSION['logged_in'] = false;
		session_destroy();
		$_SESSION = array();
		echo "logout";
	}
	
	public function display(){		
		// Set variables for the view
		$this->view->putContents('notices', self::$notices);
		$this->view->putContents('lang', $this->language);
		$this->view->putContents('layout', $this->model->getLayout($this->layout));
		$this->view->putContents('displayMode',$this->model->getDisplayMode($this->displayMode));
		
							echo "Controller: ".$this->controller;
		
		if(isset($this->request['logout'])) { $this->logout(); }
		if(isset($this->request['formSubmit'])) {
			switch($this->request['formSubmit']) {
				case 'signup':
					$uc = new UserController($this->request);
					$user = $uc->signup($this->request['username'],  $this->request['email'], $this->request['pw2']);
					$this->view->putContents('user', $user);
					break;
				case 'login':
					$uc = new UserController($this->request);
					$user = $uc->login($this->request['username'],  $this->request['email'], $this->request['pass']);
					$this->view->putContents('user', $user);
					break;
				default:
			}
		}
		
		
		// check that the requested controller and action are both allowed
  		// if someone tries to access something else he will be redirected to the error action
  		if (array_key_exists($this->controller, $this->controllers)) {
    		if (in_array($this->action, $this->controllers[$this->controller])) {
    			    			    			
    			// if requested controller is pages load template directly
    			if($this->controller == 'pages') {
    				#echo "Action: ".$this->action;
    					$this->view->setTemplate($this->action);
    					$template = $this->view->loadTemplate();
    					$title = " | ".$this->action;
    			}
    			// requested controller is not pages -> give request to referring controller
    			else {
    				switch($this->controller){
    					case 'blogs':
    						$c = new BlogsController($this->request);
    						$this->view->putContents('customStyle', $this->model->getCustomStyle($this->request['id']));
    						break;
    					case 'user':
    						$c = new UserController($this->request);
    						break;
    					case 'posts':
    						$c = new PostsController($this->request);
    						break;
    					}
    					$template = $c->display();
    					#echo "<br>Das sollte hier auftauchen: <br>".$template;
    					echo "Hallo?";
    					$title = $c->getTitle();
    					echo "aa".$title."aa";
    				}
    			} else { // show error for non valid action request
    				$title = " | Error";
      			$this->view->setTemplate('error');
      			$template = $this->view->loadTemplate();
    			}
  			} else { // show error for non valid controller request
  				 $title = " | Error";
   			 $this->view->setTemplate('error');
      		 $template = $this->view->loadTemplate();
 	   	}
 	   
 	   // variable for the output string
		$page = "";
		
		if(!$this->view->readContents()['user']) {
			$um = new UserModel();
			$user = $um->createUser();
			$this->view->putContents('user', $user);
		}
		
		// set and load header template
		if($this->controller === 'blogs' && $this->action === 'show') {
			$this->view->setHeader('small_header');
		} else {
			$this->view->setHeader('big_header');
		}
		$this->view->putContents('title', $title);
		#echo "put header.";
		$page .= $this->view->loadHeader();
		
		// add content template
 	  # echo "put page template"; 	   
 	   $page .= $template;
		
		// set and load footer template
		$this->view->setFooter('simple_footer');
		#echo "put footer.";		
		$page .= $this->view->loadFooter();
		return $page;
	}

}
?>