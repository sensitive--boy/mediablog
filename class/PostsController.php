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
	private $templatePath = 'templates/posts';
	private $template = 'all_posts';
	private $title = 'Posts';
	private $request;
	private $action;
	private $language;
	
	public function __construct($request) {		
		$this->model = new PostModel();
		$this->view = new View($this->templatePath);
		$this->request = $request;
		$this->action = $this->request['action'];
		$this->language = $this->request['lang'];
	}
	
		
	public function display() {
		$this->view->putContents('lang', $this->language);
		switch($this->action) {
			case 'create':
				if(in_array($this->request['type'], $this->model->getPostTypes())) {
					$post = $this->model->createPost($this->request['type']);
					$this->view->putContents('post', $post);
					$this->view->putContents('blog_id', $this->request['blog_id']);
					$this->view->putContents('p_langs', $this->model->getAllLanguages());
					$this->title = " | new ".$this->request['type']."post";
					$this->template = $this->request['type'].'post_new';
				} else {
					$this->title = " | Error";
					$this->template = 'error';
				}
				break;
			case 'save':
				if(!in_array($this->request['type'], $this->model->getPostTypes())) {
					$this->title = " | Error";
					$this->template = 'error';
				} else {
					$post_id = $this->model->savePost($this->request);
					if($post_id){
						$post = $this->model->getPost($this->request['type'], $post_id);
						$c = $this->model->saveContribution($post->getPostType(), $post->getId(), $this->request['blog_id'], $this->request['user']);
						$this->view->putContents('post', $post);
						$this->view->putContents('contribution', $c);
						$this->title = " | ".$post->getTitle();
						$this->template = 'post_show';
						echo $post->getPostType();
					} else {
						echo "else";
						// ???
						PagesController::$notices[] = "Creating this post did not work. Please try again.";
						$post = $this->model->getPost($this->request['type'], $this->request['id']);
						$this->view->putContents('post', $post);
						$this->title = " | new ".$this->request['type']."post";
						$this->template = $post->getPostType().'post_new';
					}
				}
				break;
			case 'show':
				$post = $this->model->getPost($this->request['type'], $this->request['id']);
				echo "got post";
				$this->view->putContents('post', $post);
				$this->title = " | ".$post->getTitle();
				$this->template = 'post_show';
				echo "Wo bist du?";
				break;
			case 'edit':
				if(in_array($this->request['type'], $this->model->getPostTypes())) {
					$post = $this->model->getPost($this->request['type'], $this->request['id']);
					$contribution = $this->model->getContribution($this->request['cid']);
					$this->view->putContents('post', $post);
					$this->view->putContents('contribution', $contribution);
					$this->title = " | edit '".$post->getTitle()."'";
					$this->template = $post->getPostType().'post_edit';
				} else {
					$this->title = " | Error";
					$this->template = 'error';
				}
				break;
			case 'update':
				if(!in_array($this->request['type'], $this->model->getPostTypes())) {
					$this->title = " | Error";
					$this->template = 'error';
				} else {
					if($this->model->updatePost($this->request)){
						$post = $this->model->getPost($this->request['type'], $this->request['id']);
						$contribution = $this->model->getContribution($this->request['cid']);
						$this->view->putContents('post', $post);
						$this->view->putContents('contribution', $contribution);
						$this->title = " | ".$post->getTitle();
						$this->template = 'post_show';
						echo $post->getPostType();						
						#header("Location: http://localhost/tiblogs/index_.php?controller=posts&action=show&id=".$post->getId()."&type=".$post->getType()."&lang=".$this->language);
						echo "huch!";
					} else {
						echo "else";
						PagesController::$notices[] = "Edit did not work. Please try again.";
						$post = $this->model->getPost($this->request['type'], $this->request['id']);
						$this->view->putContents('post', $post);
						$this->title = " | edit ".$post->getTitle();
						$this->template = $post->getPostType().'post_edit';
					}
				}
				break;
			case 'delete':
				echo "ich lösche";
				if(in_array($this->request['type'], $this->model->getPostTypes())) {
					$yes = $this->model->deletePost($this->request['type'], $this->request['id']);
					if($yes) {
						echo "delete erfolgreich.";						
					} else {
						PagesController::$notices[] = "Delete did not work. Please try again.";
					}
					header("Location: http://localhost/tiblogs/index_.php?controller=blogs&action=show&id=".$this->request['blog']."&lang=".$this->language);
				} else {
					echo "something went wrong";
				}				
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