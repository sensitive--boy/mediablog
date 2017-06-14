<?php
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
$post = $c->getPost();
echo "<h3 dir='auto'>".$post->getTitle()."</h3>";
	if($_SESSION['logged_in'] && $_SESSION['uname'] == $c->getUsername()){
		echo "<p dir='auto'><a class='ownerforms' href='?controller=posts&action=edit&cid=".$c->getId()."&type=".$post->getPostType()."&id=".$post->getId()."&lang=".$this->contents['lang']."'>$txt_edit_post '".$post->getTitle()."'</a>";
		echo " | <a class='ownerforms' href='?controller=posts&action=delete&cid=".$c->getId()."&type=".$post->getPostType()."&id=".$post->getId()."&blog=".$c->getBlog()."&lang=".$this->contents['lang']."'>$txt_delete_post '".$post->getTitle()."'</a></p>";
	}
	echo "<p class='postinfo' dir='auto'>$txt_written_by ".$c->getUsername()."</p>";	
	echo "<p dir='auto'>".$post->getDescription()."</p>";
?>