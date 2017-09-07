<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* post_show.php -> general Post display
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
	$post = $this->contents['post'];
	if(!isset($c)){$c = $this->contents['contribution'];}
	$lang = $this->contents['lang'];
	$keys = (!empty($this->contents['keywords']))?implode(', ', $this->contents['keywords']):"";
	echo "<div class='content' dir='auto'>";
	include("templates/partials/".$post->getPostType()."_post.php");
	echo "<br><a href='?controller=blogs&id=".$c->getBlogId()."&lang=$lang'>zurück zum Blog</a>";
	echo "</div>";
?>