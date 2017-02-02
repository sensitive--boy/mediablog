<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* User.php -> simple class Blog
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class Blog{
	private $id;
	private $title;
	private $owner;
	private $description;
	
	public function __construct($id, $owner, $title, $desc){
		$this->id = $id;
		$this->owner = $owner;
		$this->title = $title;
		$this->description = $desc;
	}
	
	public function getID() {
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
	public function setDescription($desc) {
		$this->description = $desc;
	}
	# returns a user_id
	public function getOwner() {
		return $this->owner;
	}
	# setOwner() needed for archiving if user gets deleted (set owner to system or archive)
	public function setOwner($owner) {
		$this->owner = $owner;
	}
}
?>