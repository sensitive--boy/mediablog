<?php
/************
* folder: projekt/templates
* Aufbau eines Login-Prozesses mit MVC-Pattern und Datenbankwrapper
* main.php -> Container-Template
* @autor: Kai Noah Schirmer
* (c) Cimi Feb/Mar 2015
*************/
	echo "container: <br />";
	foreach($this->contents['notices'] as $notice){
		echo $notice."<br />";
	}
	echo "<hr />";
	echo $this->contents['content'];
?>