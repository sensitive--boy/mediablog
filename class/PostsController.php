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
require_once 'nixda/settings.php';
include_once 'include/functions.php';
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
	private $styleModel;
	private $blog_id;
	
	public function __construct($request) {		
		$this->model = new PostModel();
		$this->view = new View($this->templatePath);
		$this->request = $request;
		$this->action = $this->request['action'];
		$this->language = $this->request['lang'];
		$this->styleModel = new StyleModel();
		#echo "++I am a new born PostsController++";
	}
	
	public function getCustomStyle() {
		return $this->styleModel->getStyleByBlogId($this->blog_id);
	}
	public function getCustomStylesheet() {
		return $this->styleModel->getCustomStyle($this->blog_id);
	}
	
		
	public function display() {
		$this->view->putContents('lang', $this->language);
		switch($this->action) {
			case 'create':
				checkForSession();
				if(in_array($this->request['type'], $this->model->getPostTypes())) {
					$post = $this->model->createPost($this->request['type']);
					if($this->request['type'] == 'video' || $this->request['type'] == 'audio') {
						$target = $this->model->findOrCreateFolder($this->request['type'], $this->request['blog_id']);
						#echo $target;
						$this->view->putContents('target', $target);
					}
					$this->view->putContents('post', $post);
					$this->blog_id = $this->request['blog_id'];
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
				} elseif($this->request['type'] == 'video' && !($this->request['path_to_file'] OR $this->request['extvlink'])){
					$this->request = $this->request;	
					$this->request['action'] = 'create';				
					$this->action = 'create';
					$this->display();
				}else {
					$post_id = $this->model->savePost($this->request);
					if($post_id){
						$post = $this->model->getPost($this->request['type'], $post_id);
						$cn = $this->model->saveContribution($post->getPostType(), $post->getId(), $this->request['blog_id'], $this->request['user']);
						$c = $this->model->getContribution($cn);
						$this->view->putContents('post', $post);
						$this->view->putContents('contribution', $c);
						$this->blog_id = $this->request['blog_id'];
						$this->view->putContents('keywords', $this->model->getKeywords($post->getPostType(), $post->getId()));
						$this->title = " | ".$post->getTitle();
						$this->template = 'post_show';
					} else {
						#echo "else";
						// ???
						PagesController::$notices[] = "Creating this post did not work. Please try again.";
						$post = $this->model->getPost($this->request['type'], $this->request['id']);
						$this->view->putContents('post', $post);
						$this->blog_id = $this->request['blog_id'];
						$this->title = " | new ".$this->request['type']."post";
						$this->template = $post->getPostType().'post_new';
					}
				}
				break;
			case 'show':
				$post = $this->model->getPost($this->request['type'], $this->request['id']);
				$this->view->putContents('post', $post);
				$c = $this->model->getContribution($this->request['cid']);
				$this->blog_id = $c->getBlog();
				$this->view->putContents('contribution', $c);
				#$this->view->putContents('keywords', $this->model->getKeywords($post->getPostType(), $post->getId()));
				$this->title = " | ".$post->getTitle();
				$this->template = 'post_show';
				#echo "Wo bist du?";
				break;
			case 'edit':
				checkForSession();
				if(in_array($this->request['type'], $this->model->getPostTypes())) {
					$post = $this->model->getPost($this->request['type'], $this->request['id']);
					$contribution = $this->model->getContribution($this->request['cid']);
					$this->blog_id = $contribution->getBlog();
					#echo $this->blog_id;
					$this->view->putContents('post', $post);
					$this->view->putContents('contribution', $contribution);
					$keys = $this->model->getKeywords($post->getPostType(), $post->getId());
					#echo $keys;
					if($keys) {
					$this->view->putContents('keywords', $keys);};
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
						$this->blog_id = $contribution->getBlog();
						$this->view->putContents('post', $post);
						$this->view->putContents('contribution', $contribution);
						$this->view->putContents('keywords', $this->model->getKeywords($post->getPostType(), $post->getId()));
						$this->title = " | ".$post->getTitle();
						$this->template = 'post_show';
						#echo $post->getPostType();						
						#header("Location: http://localhost/tiblogs/index_.php?controller=posts&action=show&id=".$post->getId()."&type=".$post->getType()."&lang=".$this->language);
						#echo "huch!";
					} else {
						#echo "else";
						PagesController::$notices[] = "Edit did not work. Please try again.";
						$post = $this->model->getPost($this->request['type'], $this->request['id']);
						$this->view->putContents('post', $post);
						$this->view->putContents('keywords', $this->model->getKeywords($post->getPostType(), $post->getId()));
						$this->title = " | edit ".$post->getTitle();
						$this->template = $post->getPostType().'post_edit';
					}
				}
				break;
			case 'delete':
				checkForSession();
				#echo "ich lösche";
				if(in_array($this->request['type'], $this->model->getPostTypes())) {
					$yes = $this->model->deletePost($this->request['type'], $this->request['id']);
					if($yes) {
						#echo "delete erfolgreich.";						
					} else {
						PagesController::$notices[] = "Delete did not work. Please try again.";
					}
					header("Location: http://localhost/tiblogs/index_.php?controller=blogs&action=show&id=".$this->request['blog']."&lang=".$this->language);
				} else {
					#echo "something went wrong";
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
		echo $this->template;
		#echo " Template gesetzt. ";
		
		return $this->view->loadTemplate();
	}
	public function getTitle() {
		return $this->title;
	}
}
?>