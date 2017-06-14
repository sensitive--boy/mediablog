<?php
class VideoPost{
	private $id;
	private $title;
	private $description;
	
	public function __construct($id, $title, $desc) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $desc;
	}
	
	public function setFilePath($path) {
		$this->filePath = $path;
	}
	public function getFilePath() {
		return $this->filePath;
	}
	public function getId() {
		return $this->id;
	}


}
?>