<?php
class SimplePost{
	private $id;
	private $title;
	private $description;
	
	public function __construct($id, $title, $desc) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $desc;
	}
	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getPostType(){
		return "simple";
	}

}