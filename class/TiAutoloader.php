<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* TiAutoloader.php -> class TiAutoloader
* class for custom autoload functions
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class TiAutoloader{

	public static function classLoader($classname){
		$path = 'class/';		
		
    		require_once $path.$className.'.php';
    	
	}
	
	public static function secretsLoader($classname){
		$path = 'nixda/';

    	include $path.$className.'.php';
	}
}
?>