<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* StyleModel.php -> class StyleModel
* simple class for loading stylesheets
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
include_once 'include/functions.php';
class Dragon{
	private $stylesheets = array();
	private $layoutTable;
	private $displayModeTable;
	private $customStyleTable = 'styles';
	private $wrapper;
	private $sortopt = null;
	private $abspath = '/var/www/html/tiblogs';
	private $relpath = '0--x--0';
	
	public function __construct() {
		echo "<br>91 bis hierhin ging's noch ganz gut";
		$this->wrapper = Wrapper::getInstance();
	}
}
?>