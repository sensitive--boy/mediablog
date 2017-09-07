<?php
class SimplePost{
	private $id;
	private $title;
	private $description;
	private $published;
	private $published_at;
	
	public function __construct($id, $title, $description) {
		echo "ich bin (nicht) simple.";
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->published = false;
	}
	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title) {
		$this->title = $title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($text) {
		$this->description = $text;
	}
	public function getPublished(){
		return $this->published;
	}
	public function setPublished($bool){
		$this->published = $bool;
	}
	public function getPublishedAt(){
		if($this->published && isset($this->published_at)) {
			return $this->published_at;
		} else {
			return null;
		}
	}
	public function setPublishedAt($date){
		$this->published_at = $date;
	}
	public function getPostType(){
		return "simple";
	}
	public function getColumnnames() {
		$names = array('title', 'description');
		return $names;
	}

}
?>