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
session_start();
$request = array_merge($_GET, $_POST);
#echo 'REQUEST: ';
#print_r($request);
#phpinfo();
#echo '<br>Query_string: '.$_SERVER['QUERY_STRING'];
#echo '<br />Session: ';
#print_r($_SESSION);
$p = new PagesController($request);
#echo "bis hierhin ging's noch ganz gut...";
echo $p->display();
?>