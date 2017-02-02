<?php
/************
* folder: tiblogs
* mvc multiblog project
* index_.php -> FrontController
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
function __autoload($classname){
	$pfad;
	if(file_exists("class/".$classname.".php")){
		$pfad = "class/";
	} else if(file_exists("interface/".$classname.".php")){
		$pfad = "interface/";
	} else $pfad="";
	require_once($pfad.$classname.".php");
}

$request = array_merge($_GET, $_POST);
echo 'index_.php:<br />';
echo 'REQUEST: ';
print_r($request);
echo '<br />Session: ';
print_r($_SESSION);

$p = new PagesController($request);
echo "PagesController erzeugt.";

echo $p->display();

?>