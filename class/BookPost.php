<?php
class BookPost{
	private $id;
	private $title;
	private $description;
	private $author;
	private $publisher;
	private $pages;
	private $isbn;
	
	public function __construct($id, $title, $desc, $author, $publisher, $pages, $isbn) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $desc;
		$this->author = $author;
		$this->publisher = $publisher;
		$this->pages = $pages;
		$this->isbn = $isbn;
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
	public function getAuthor() {
		return $this->author;
	}
	public function getPublisher() {
		return $this->publisher;
	}
	public function getPages() {
		return $this->pages;
	}
	public function getIsbn() {
		return $this->isbn;
	}
	public function getPostType(){
		return "book";
	}

}