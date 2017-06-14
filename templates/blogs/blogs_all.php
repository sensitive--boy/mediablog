<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* all_blogs.php -> Template for displaying all blogs
* @autor: Kai Noah Schirmer
* (c) Cimi Feb/Mar 2015
*************/
session_start();
$user = $this->contents['user'];
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
	echo "<div class='content'>";
	echo "<h1 id='pagetitle' dir='auto'>$txt_heading_all_blogs</h1><ul>";

	foreach($this->contents['blogs'] as $blog){
		echo "<li><a href='index_.php?controller=blogs&action=show&id=".$blog->getId()."&lang=".$this->contents['lang']."' dir='auto'>".$blog->getTitle()."</a></li>";
	}

echo "</ul></div>";
?>