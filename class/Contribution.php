<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* Contribution.php -> relation class Contribution
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class Contribution{
	private $id;
	private $user;
	private $username = null;
	private $blog;
	private $post_type;
	private $post_id;
	private $post = null;
	private $date;

	
	public function __construct($id, $user_id, $blog_id, $post_type, $post_id) {
		$this->id = $id;
		$this->user = $user_id;
		$this->blog = $blog_id;
		$this->post_type = $post_type;
		$this->post_id = $post_id;
	}
	public function getId() {
		return $this->id;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function getBlog() {
		return $this->blog;
	}
	
	public function getPostType(){
		return $this->post_type;
	}
	
	public function getPostId() {
		return $this->post_id;
	}
	
	public function getDate() {
		return $this->date;
	}
	public function setPost($post) {
		$this->post = $post;
	}
	public function getPost() {
		return $this->post;
	}
	public function setUsername($name){
		$this->username = $name;
	}
	public function getUsername() {
		return $this->username;
	}
}