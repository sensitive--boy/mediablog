<?php
class SimplePost{
	private $id;
	private $title;
	private $description;
	
	public function __construct($id, $title, $description) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
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
	public function getPostType(){
		return "simple";
	}
	public function getColumnnames() {
		$names = array('title', 'description');
		return $names;
	}

}
?>