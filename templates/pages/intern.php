<?php
/************
* folder: tiblogs/templates/pages
* mvc multiblog project
* intern.php -> simple page for login test
* @autor: Kai Noah Schirmer
* (c) January 2017
*************/
session_start();
	echo '<h1>Intern</h1>';
	echo 'Hallo, '.$_SESSION['user']->getName().'<br />';
	echo '<p>mich kannst du nur sehen, wenn du eingeloggt bist.</p><br />';
	echo '<a href="?action=logout">Ausloggen</a>';
?>