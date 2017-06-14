<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* Style.php -> Einfache Klasse Style to provide individual stylesheets
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class Style{
	private $id;
	private $blog_id;
	private $titlefont;
	private $titlecolor;
	private $descriptionfont;
	private $descriptioncolor;
	private $posttitlefont;
	private $posttitlecolor;
	private $posttextfont;
	private $posttextcolor;
	private $titleimage;
	private $bgimage;
	private $bgpattern;
	private $bgcolor;
	private $usebgcolor;
	private $pbackcolor;
	private $pbackopacity;
	private $prcorners;
	private $posticons;
	
	
	public function __construct($b_id) {
		$this->blog_id = $b_id;
	}
	public function getId() {
		return $this->id;
	}
	public function getBlogId() {
		return $this->blog_id;
	}
	public function getTitlefont() {
		return $this->titlefont;
	}
	public function setTitlefont($font) {
		$this->titlefont = $font;
	}
	public function getTitlecolor() {
		return $this->titlecolor;
	}
	public function setTitlecolor($color) {
		$this->titlecolor = $color;
	}
	public function getDescriptionfont() {
		return $this->descriptionfont;
	}
	public function setDescriptionfont($font) {
		$this->descriptionfont = $font;
	}
	public function getDescriptioncolor() {
		return $this->descriptioncolor;
	}
	public function setDescriptioncolor($color) {
		$this->descriptioncolor = $color;
	}
	public function getPosttitlefont() {
		return $this->posttitlefont;
	}
	public function setPosttitlefont($font) {
		$this->posttitlefont = $font;
	}
	public function getPosttitlecolor() {
		return $this->posttitlecolor;
	}
	public function setPosttitlecolor($color) {
		$this->posttitlecolor = $color;
	}
	public function getPosttextfont() {
		return $this->posttextfont;
	}
	public function setPosttextfont($font) {
		$this->posttextfont = $font;
	}
	public function getPosttextcolor() {
		return $this->posttextcolor;
	}
	public function setPosttextcolor($color) {
		$this->posttextcolor = $color;
	}
	// returns true if title image is in use or false if not
	public function getTitleimage() {
		return $this->titleimage;
	}
	// sets usage of title image
	// $image can be true or false
	public function setTitleimage($b) {
		$this->titleimage = $b;
	}
	public function getBgimage() {
		return $this->bgimage;
	}
	public function setBgimage($b) {
		$this->bgimage = $b;
	}
	public function getBgpattern() {
		return $this->bgpattern;
	}
	public function setBgpattern($b) {
		$this->bgpattern = $b;
	}
	public function getBgcolor() {
		return $this->bgcolor;
	}
	public function setBgcolor($color) {
		$this->bgcolor = $color;
	}
	public function getUsebgcolor() {
		return $this->usebgcolor;
	}
	public function setUsebgcolor($b) {
		$this->usebgcolor = $b;
	}
	public function getPbackcolor() {
		return $this->pbackcolor;
	}
	public function setPbackcolor($color) {
		$this->pbackcolor = $color;
	}
	public function getPbackopacity() {
		return $this->pbackopacity;
	}
	public function setPbackopacity($o) {
		$this->pbackopacity = $o;
	}
	public function getPrcorners() {
		return $this->prcorners;
	}
	public function setPrcorners($b) {
		$this->prcorners = $b;
	}
	public function getPosticons() {
		return $this->posticons;
	}
	public function setPosticons($b) {
		$this->posticons = $b;
	}
	public function getColumnnames() {
		$columns = array('titlefont', 'titlecolor', 'descriptionfont', 'descriptioncolor', 'posttitlefont', 'posttitlecolor', 'posttextfont', 'posttextcolor', 'titleimage', 'bgimage', 'bgpattern', 'bgcolor', 'usebgcolor', 'pbackcolor', 'pbackopacity', 'prcorners', 'posticons');
		return $columns;
	}
}
?>