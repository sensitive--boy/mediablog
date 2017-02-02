<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* controller.php -> abstract class Controller
* forces controllers to have display method
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
abstract class Controller{
		
	// sets content variables
	// loads and returns template file
	public abstract function display();

}
?>