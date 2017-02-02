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
  	private $controllers = array('pages' => ['home', 'error', 'contact', 'faq', 'privacy', 'funding', 'aftersignup', 'afterlogin'],
  							  'blogs' => ['index', 'show', 'new', 'edit'],
  							  'user' => ['index', 'show', 'new', 'signup', 'login'],
  							  'posts' => ['index', 'show', 'new', 'edit']
  							 );
	
	public function __construct($request){
		echo " / PagesController";
		$this->view = new PageView();
		$this->model = new StyleModel();
		$this->request = $request;
		$this->language = !empty($request['lang']) ? $request['lang'] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : LANGUAGE);
		$this->layout = !empty($request['layout']) ? $request['layout'] : 'standard';
		$this->displayMode = !empty($request['displayMode']) ? $request['displayMode'] : 'fullVision';
		$this->customStyle = !empty($request['customStyle']) ? $request['customStyle'] : '';
		$this->controller = !empty($request['controller']) ? $request['controller'] : 'pages';
		$this->action = !empty($request['action']) ? $request['action'] : 'home';
	}
	public function __destruct(){
	
	}
	
	public function logout(){
		echo "logout";
		$_SESSION['logged_in'] = false;
		$_SESSION = array();
		session_destroy();
	}
	
	public function display(){		
		// Set variables for the view
		$this->view->putContents('notices', self::$notices);
		$this->view->putContents('lang', $this->language);
		$this->view->putContents('layout', $this->model->getLayout($this->layout));
		$this->view->putContents('displayMode',$this->model->getDisplayMode($this->displayMode));
		$this->view->putContents('customStyle', $this->model->getCustomStyle($this->customStyle));
		
		$_SESSION['hallo'] = "Ich bins. kennste mich?";
		
		if(isset($this->request['logout'])) { $this->logout(); }
				
		
		// check that the requested controller and action are both allowed
  		// if someone tries to access something else he will be redirected to the error action
  		if (array_key_exists($this->controller, $this->controllers)) {
    		if (in_array($this->action, $this->controllers[$this->controller])) {
    			
    			// if requested controller is pages load template directly
    			if($this->controller == 'pages') {
    					$this->view->setTemplate($this->action);
    					$template = $this->view->loadTemplate();
    			} 
    			// requested controller is not pages -> give request to referring controller
    			else {
    				switch($this->controller){
    					case 'blogs':
    						$c = new BlogsController($this->request);
    						break;
    					case 'user':
    						$c = new UserController($this->request);
    						break;
    					case 'posts':
    						$c = new PostsController($this->request);
    						break;
    					}
    					$template = $c->display();
    					$title = $c->getTitle();
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
		
		// set and load header template
		$this->view->setHeader('big_header');
		$this->view->putContents('title', $title);
		$page .= $this->view->loadHeader();
		
		// add content template
 	   $page .= $template;
		
		// set and load footer template
		$this->view->setFooter('simple_footer');
		$page .= $this->view->loadFooter();
		return $page;
	}

}
?>