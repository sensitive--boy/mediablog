<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* BlogsController.php -> class logsController
* manages blogs
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class BlogsController{	
	private $model;
	private $view;
	private $templatePath = 'templates';
	private $template = 'all_blogs';
	private $title = 'Blogs';
	private $request;
	private $action;
	private $language;
	
	public function __construct($request) {		
		echo "<br>Es ist ein BlogsController!";
		$this->model = new BlogModel();
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
				$this->title = " | new blog";
				$this->template = 'blog_new';
				break;
			case 'show':
				echo $this->action;
				$blog = $this->model->getBlog($this->request['id']);
				$pm = new PostModel();
				$posts = $pm->getPostsByBlogId($this->request['id']);
				$this->view->putContents('blog', $blog);
				$this->view->putContents('posts', $posts);
				$this->title = " | ".$blog->getTitle();
				$this->template = 'blog_show';
				break;
			default:
				echo $this->action;
				$blogs = $this->model->getBlogs();
				$this->view->putContents('blogs', $blogs);
				$this->title = " | blogs";
				$this->template = 'blogs_all';
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