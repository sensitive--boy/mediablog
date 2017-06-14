<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* BlogModel.php -> class BlogModel
* manages blogs
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class BlogModel {
	private $table;
	private $styletable;
	private $wrapper;
	private $sortopt = null;
	
	public function __construct() {
		$this->table  = 'blogs';
		$this->styletable = 'styles';
		$this->wrapper = Wrapper::getInstance();
	}
	
	public function createBlog() {
		$blog = new Blog(0, "", "", "");
		return $blog;
	}
	public function getBlogs() {
		$bloglist = [];
		$resultset = $this->wrapper->selectAll($this->table);
		if(!empty($resultset)) {
		foreach($resultset as $b){
			$bloglist[] = new Blog($b['id'], $b['owned_by'], $b['title'], $b['description']);
		}
	} else {
		$bloglist[] = 'albert und albert';
		$bloglist[] = 'kaus reist aus';
		$bloglist[] = 'peter macht meter';
	}
		return $bloglist;
	}
	
	public function saveBlog($request) {
		$conditions['title'] = $request['title'];
		$conditions['description'] = $request['description'];
		$conditions['owned_by'] = $request['user'];
		
		$result = $this->wrapper->insert($this->table, $conditions);
		$cons['blog_id'] = $result['id'];
		$style = $this->wrapper->insert($this->stylestable, $cons);
		return $result;
	}
	
	public function updateBlog($request) {
		$conditions['id'] = $request['id'];
		$bvalues['title'] = $request['title'];
		$bvalues['description'] = $request['description'];
		$svalues['titlefont'] = $request['ttlfont'];
		$svalues['titlecolor'] = $request['ttlcolor'];
		$svalues['descriptionfont'] = $request['descfont'];
		$svalues['descriptioncolor'] = $request['desccolor'];
		$svalues['bgcolor'] = $request['bgcolor'];
		$svalues['usebgcolor'] = ($request['usebgcolor'] == 'on') ? true : 0;
		$svalues['posttitlefont'] = $request['pttlfont'];
		$svalues['posttitlecolor'] = $request['pttlcolor'];
		$svalues['posttextfont'] = $request['pbfont'];
		$svalues['posttextcolor'] = $request['pbcolor'];
		$svalues['pbackcolor'] = $request['pbackcolor'];
		$svalues['pbackopacity'] = $request['pbackopacity'];
		$svalues['posticons'] = ($request['posticons'] == 1) ? true : 0;
		$svalues['prcorners'] = ($request['prcorners'] == 1) ? true : 0;
		if($request['titleimage'] && $request['titleimage'] == true) {
			$svalues['titleimage'] = true;
		}else {
			$svalues['titleimage'] = 0;
		}
		if($request['background'] == "bgimage") {
			$svalues['bgimage'] = true;
			$svalues['bgpattern'] = 0;
		} elseif($request['background'] == "bgpattern") {
			$svalues['bgimage'] = 0;
			$svalues['bgpattern'] = true;
		} else {
			$svalues['bgimage'] = 0;
			$svalues['bgpattern'] = 0;
		}
		echo "Values set";
		$result = $this->wrapper->updateWhere($this->table, $bvalues, $conditions);
		
		$style = $this->wrapper->updateWhere($this->styletable, $svalues, array('blog_id'=>$request['id']));
		
		return $result;
	}
	
	public function getBlog($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->table, $sortopt, $conditions, "AND")->fetch();
		$blog = new Blog($result['id'], $result['owned_by'], $result['title'], $result['description']);
		return $blog;
	}
	
	public function getBlogsByUser($user_id) {
		$conditions['owned_by'] = $user_id;
		$resultset = $this->wrapper->selectWhere($this->table, $this->sortopt, $conditions);
		
		if(!empty($resultset)) {
			foreach($resultset as $b){
				$bloglist[] = new Blog($b['id'], $b['owned_by'], $b['title'], $b['description']);
			}
		} else {
			$bloglist[] = 'albert und albert';
			$bloglist[] = 'kaus reist aus';
			$bloglist[] = 'peter macht meter';
		}
		return $bloglist;
	}
	
	public function getBlogsByContributions($contributions) {
		$clist = array();
		foreach($contributions as $c){
			if(!in_array($c['b_id'], $clist)) {
				$clist[] = $c['b_id'];
			}
		}
		$cblogs = array();
		foreach($clist as $c){
			$conditions['id'] = $c;
			$result = $this->wrapper->selectWhere($this->table, null, $conditions)->fetch();
			$cblog = new Blog($result['id'], $result['owned_by'], $result['title'], $result['description']);
			$cblogs[] = $cblog; 
		} 
		return $cblogs;
	}
}
?>