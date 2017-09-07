<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* BlogsController.php -> class BlogsController
* manages blogs
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
include_once 'include/functions.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class BlogsController{	
	private $model;
	private $styleModel;
	private $view;
	private $templatePath = 'templates/blogs';
	private $template = 'blogs_all';
	private $title = 'Blogs';
	private $request;
	private $action;
	private $language;
	private $goal = "";
	
	public function __construct($request) {		
		echo "<br>Es ist ein BlogsController!";
		$this->model = new BlogModel();
		$this->styleModel = new StyleModel();
		$this->view = new View($this->templatePath);
		$this->request = $request;
		$this->action = $this->request['action'];
		$this->language = $this->request['lang'];
	}
		
	public function display() {
		$this->view->putContents('lang', $this->language);
		switch($this->action) {
			case 'create':
				echo $this->action;
				$newblog = $this->model->createBlog();
				$this->view->putContents('blog', $newblog);
				$this->title = " | new blog";
				$this->template = 'blog_new';
				break;
			case 'show':
				echo $this->action;
				$blog = $this->model->getBlog($this->request['id']);
				echo "Ha! ein Bloog!";
				$pm = new PostModel();
				$contributions = $pm->getContributionsByBlog($this->request['id']);
				echo "got contributions ::::";
				$posts = $pm->getPostsByBlogId($this->request['id']);
				$style = $this->styleModel->getStyleByBlogId($this->request['id']);
				$this->view->putContents('blog', $blog);
				$this->view->putContents('contributions', $contributions);
				$this->view->putContents('posts', $posts);
				$this->view->putContents('style', $style);
				$this->title = " | ".$blog->getTitle();
				$this->template = 'blog_show';
				break;
			case 'new':
				$blog_id = $this->model->saveBlog($this->request);
				$blog = $this->model->getBlog($blog_id);
				if(!empty($blog)) {
					header("Location: http://localhost/tiblogs/index_.php?controller=blogs&action=show&id=".$blog->getId()."&lang=".$this->language);
				} else {
					$this->title = " | Error";
					$this->template = 'error';
				}
				break;
			case 'edit':
				$blog = $this->model->getBlog($this->request['id']);
				$style = $this->styleModel->getStyleByBlogId($this->request['id']);
				if(!empty($blog)) {
					$this->view->putContents('blog', $blog);
					$this->view->putContents('style', $style);
					$this->view->putContents('stylefolder', $this->styleModel->findOrCreateStyleFolder($blog->getId()));
					$this->view->putContents('hfonts', $this->styleModel->getHeadlinefontsByLanguage($this->language));
					echo "<br>fellow 3";
					$this->view->putContents('tfonts', $this->styleModel->getTextfontsByLanguage($this->language));
					echo "<br>fellow 4";
					$this->title = " | ".$blog->getTitle();
					$this->template = 'blog_edit';
				} else {
					echo "<br>fellow 19";
					$this->title = " | Error";
					$this->template = 'error';
				}
				break;
			case 'update':
			echo "hello update";
				$blog = $this->model->updateBlog($this->request);
				if($blog) {
					$b = $this->model->getBlog($this->request['id']);
					$s = $this->styleModel->getStyleByBlogId($b->getId());
					$this->styleModel->writeStylesheet($s);					
					header("Location: http://localhost/tiblogs/index_.php?controller=blogs&action=show&id=".$this->request['id']."&lang=".$this->language);
				} else {
					PagesController::$notices[] = "Edit did not work. Please try again.";
					$blog = $this->model->getBlog($this->request['id']);
					$style = $this->styleModel->getStyleByBlogId($this->request['id']);
					if(!empty($blog)) {
						$this->view->putContents('blog', $blog);
						$this->view->putContents('style', $style);
						$this->title = " | ".$blog->getTitle();
						$this->template = 'blog_edit';
					} else {
						$this->title = " | Error";
						$this->template = 'error';
					}
				}
				break;
			case 'mystuff':
				$blogs = $this->model->getBlogsByUser($this->request['user']);
				$this->view->putContents('blogs', $blogs);
				$pm = new PostModel();
				$contributions = $pm->getContributionsByUser($this->request['user']);
				$cblogs = $this->model->getBlogsByContributions($contributions);
				$this->view->putContents('cblogs', $cblogs);
				$this->title = " | My stuff";
				$this->template = 'blogs_my';
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
	
	public function getCustomStyle($blog_id) {
		$this->styleModel->getCustomStyle($blog_id);
	}
}
?>