<?php
session_start();
	echo "<h3>".$c->getPost()->getTitle()."</h3>";
	if($_SESSION['logged_in'] && $_SESSION['uname'] == $c->getUsername()){
		echo "<p><a class='ownerforms' href='?controller=posts&action=edit&type=".$c->getPost()->getPostType()."&id=".$c->getPost()->getId()."&lang=".$this->contents['lang']."'>edit post '".$c->getPost()->getTitle()."'</a>";
		echo " | <a class='ownerforms' href='?controller=posts&action=delete&type=".$c->getPost()->getPostType()."&id=".$c->getPost()->getId()."&lang=".$this->contents['lang']."'>delete post '".$c->getPost()->getTitle()."'</a></p>";
	}
	echo "<p>author/s: ".$c->getPost()->getAuthor()."</p><p>published at: ".$c->getPost()->getPublisher()."</p>";
	echo "<p>number of pages: ".$c->getPost()->getPages()."</p><p>ISBN: ".$c->getPost()->getIsbn()."</p><p>".$c->getPost()->getDescription()."</p>";
	echo "<p class='postinfo'>this book was suggested by ".$c->getUsername()."</p>";	
?>