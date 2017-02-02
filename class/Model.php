<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* model.php -> class Model
* base class for models
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class Model {
	private $wrapper;
	
	public function __construct(){
		$this->wrapper = Wrapper::getInstance();
	}
	
	
}
?>