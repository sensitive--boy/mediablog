<?php
/************
* folder: tiposts/class
* mvc multipost project
* PostModel.php -> class PostModel
* manages posts
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class PostModel {
	private $sptable;
	private $bptable;
	private $aptable;
	private $vptable;
	private $ctable;
	private $wrapper;
	private $posttypes = array('simple', 'book', 'audio', 'video');
	
	public function __construct() {
		echo "Hallo, ich bin das PostModel";
		$this->sptable  = 'simpleposts';
		$this->bptable  = 'bookposts';
		$this->aptable  = 'audioposts';
		$this->vptable  = 'videoposts';
		$this->ctable  = 'contributions';
		$this->wrapper = Wrapper::getInstance();
	}
	
	public function getPosts() {
		$postlist = [];
		$resultset = $this->wrapper->selectAll($this->table);
		if(!empty($resultset)) {
		foreach($resultset as $b){
			$postlist[] = new Post($b['id'], $b['owned_by'], $b['title'], $b['description']);
		}
	} else {
		$postlist[] = 'albert und albert';
		$postlist[] = 'kaus reist aus';
		$postlist[] = 'peter macht meter';
	}
		return $postlist;
	}
	public function getPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->table, $conditions)->fetch();
		$post = new Post($result['id'], $result['owned_by'], $result['title'], $result['description']);
		return $post;
	}
	public function getPostsByBlogId($blog_id) {
		$postlist = [];
		$post;
		$conditions = array();
		// to do: sanitize input
		$conditions['blog_id'] = $blog_id;
		$resultset = $this->wrapper->selectWhere($this->ctable, $conditions);
		// for each contribution in this blog
		// check for post type and get post object accordingly
		foreach($resultset as $p){
			foreach($this->posttypes as $type){
				$columnname = $type."post_id";
				$classname = ucfirst($type)."Post";
				$functionToCall = "get";
				$functionToCall .= ucfirst($type);
				$functionToCall .= "Post";
				if(!empty($p[$columnname])) {
					$post = $this->{$functionToCall}($p[$columnname]);
					$postlist[] = $post;
				}
			}
		
		}
		return $postlist;
	}
	public function getPostsByContributorId() {
		
	}
	public function getPostsByLanguageId() {
		
	}
	public function getPostsByTag() {
		
	}
	public function getPostsByType() {
		
	}
	public function getSimplePost($id) {
		$conditions=array();
		// to do: sanitize input
		$p_id =  intval($id);
		$conditions['id'] = $p_id;
		$result = $this->wrapper->selectWhere($this->sptable, $conditions)->fetch();
		$simplepost = new SimplePost($result['id'], $result['title'], $result['description']);
		return $simplepost;
	}
	public function getBookPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->bptable, $conditions)->fetch();
		$bookpost = new BookPost($result['id'], $result['title'], $result['description'], $result['author'], $result['publisher'], $result['pages'], $result['isbn']);
		return $bookpost;
	}
	public function getAudioPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->aptable, $conditions)->fetch();
		$audiopost = new AudioPost($result['id'], $result['owned_by'], $result['title'], $result['description']);
		return $audiopost;
	}
	public function getVideoPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->vptable, $conditions)->fetch();
		$videopost = new VideoPost($result['id'], $result['owned_by'], $result['title'], $result['description']);
		return $videopost;
	}
}
?>