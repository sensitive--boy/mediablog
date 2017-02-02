<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* PostsController.php -> class PostsController
* manages posts
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class PostsController{	
	private $model;
	private $view;
	private $templatePath = 'templates';
	private $template = 'all_posts';
	private $title = 'Posts';
	private $request;
	private $action;
	private $language;
	
	public function __construct($request) {		
		echo "<br>Es ist ein PostsController!";
		$this->model = new PostModel();
		$this->view = new View();
		$this->request = $request;
		$this->action = $this->request['action'];
		$this->language = $this->request['lang'];
	}
	
		
	
	
	public function display() {
		$this->view->putContents('lang', $this->language);
		switch($this->action) {
			case 'create':
				echo $this->action;
				$this->title = " | new post";
				$this->template = 'post_new';
				break;
			case 'show':
				echo $this->action;
				$post = $this->model->getPost($this->request['id']);
				echo (empty($post)) ? "kein Post" : "Post gefunden";
				$this->view->putContents('post', $post);
				$this->title = " | ".$post->getTitle();
				$this->template = 'post_show';
				break;
			default:
				echo $this->action;
				$posts = $this->model->getPosts();
				$this->view->putContents('posts', $posts);
				$this->title = " | posts";
				$this->template = 'posts_all';
				break;
		
		}
	
		$this->view->setTemplate($this->template);
		return $this->view->loadTemplate();
	}
	public function getTitle() {
		return $this->title;
	}
}
?>