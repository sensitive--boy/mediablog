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
	$keys = implode(', ', $post->getKeywords());
	echo "<div class='content' dir='auto'>";
	echo "<div class='singlepost'>";
	echo "<div class='postcontent'>";
	include("templates/partials/".$post->getPostType()."_post.php");
	echo "<br><a href='?controller=blogs&action=show&id=".$c->getBlog()."&lang=$lang'>zurück zum Blog</a>";
	echo "</div><!--end postcontent-->";
	echo "</div></div>";
?>