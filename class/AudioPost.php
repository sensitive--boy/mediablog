<?php
class AudioPost{
	private $id;
	private $title;
	private $description;
	
	public function __construct($id, $title, $desc) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $desc;
	}


}