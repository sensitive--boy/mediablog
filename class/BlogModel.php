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
	private $wrapper;
	
	public function __construct() {
		$this->table  = 'blogs';
		$this->wrapper = Wrapper::getInstance();
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
	public function getBlog($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		echo "Conditions gesetzt: ".$conditions['id'];
		$result = $this->wrapper->selectWhere($this->table, $conditions, "AND")->fetch();
		echo "result da";
		$blog = new Blog($result['id'], $result['owned_by'], $result['title'], $result['description']);
		echo "neues Blog Objekt erzeugt";
		return $blog;
	}
}
?>