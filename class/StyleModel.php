<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* tyleModel.php -> class StyleModel
* simple class for loading stylesheets
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class StyleModel{
	private $stylesheets = array();
	private $layoutTable;
	private $displayModeTable;
	private $customStyleTable = 'blogs';
	
	public function __construct() {
	}
	
	public function getLayout($layout) {
		return 'css/general.css';
	}
	
	public function getDisplayMode($mode) {
		return 'css/general.css';
	}
	
	public function getCustomStyle($style) {
		return 'css/general.css';
	}
}
?>