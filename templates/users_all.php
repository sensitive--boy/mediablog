<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* all_users.php -> Template for displaying all users
* @autor: Kai Noah Schirmer
* (c) Cimi Feb/Mar 2015
*************/
	echo "<div class='content'>";
	echo "<h1 id='pagetitle'>These are all registered users</h1><ul>";

	foreach($this->contents['users'] as $user){
		echo "<li><a href='index_.php?controller=user&action=show&id=".$user->getId()."&lang=$lang'>".$user->getName()."</a></li>";
	}

echo "</ul></div>";
?>