<?php
class VideoPost{
	private $id;
	private $title;
	private $description;
	private $published;
	private $published_at;
	private $path;
	private $filename;
	private $filetype;
	private $visibility;
	private $stored_externally;
	private $keywords;
	
	public function __construct($id, $title, $desc) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $desc;
		$this->published = false;
		$this->filename = 'videofile';
		$this->keywords = array();
		$this->stored_externally = false;
	}
	
	public function setFilePath($path) {
		$this->filePath = $path;
	}
	public function getFilePath() {
		return $this->filePath;
	}
	public function getVlink() {
		if($this->stored_externally) {
			return $this->path;
		} else {
			return "";
		}
	}
	public function setStoredExternally($bool) {
		$this->stored_externally = (bool)$bool;
	}
	public function getStoredEx(){
		return $this->stored_externally;
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
	public function getVisibility() {
		return $this->visibility;
	}
	public function setVisibility($visibility) {
		$this->visibility = $visibility;
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
	public function addKeyword($word) {
		$this->keywords[] = $word;
	}
	public function getKeywords() {
		return $this->keywords;
	}
	public function getPostType(){
		return "video";
	}
	public function getPath_to_file() {
		return $this->path;
	}
	public function setPath_to_file($path) {
		$this->path = $path;
	}
	public function getFilename() {
		return $this->filename;
	}
	public function setFilename($name) {
		$this->filename = $name;
	}
	public function getFiletype() {
		return $this->filetype;
	}
	public function setFiletype($type) {
		$this->filetype = $type;
	}
	public function getColumnnames() {
		$names = array('title', 'description', 'path_to_file', 'filename', 'filetype');
		return $names;
	}


}
?>