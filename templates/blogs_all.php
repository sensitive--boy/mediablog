<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* all_blogs.php -> Template for displaying all blogs
* @autor: Kai Noah Schirmer
* (c) Cimi Feb/Mar 2015
*************/
	echo "<div class='content'>";
	echo "<h1 id='pagetitle'>These are all blogs</h1><ul>";

	foreach($this->contents['blogs'] as $blog){
		echo "<li><a href='index_.php?controller=blogs&action=show&id=".$blog->getId()."&lang=$lang'>".$blog->getTitle()."</a></li>";
	}

echo "</ul></div>";
?>