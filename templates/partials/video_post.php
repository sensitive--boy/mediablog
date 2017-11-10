<?php
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
if(!isset($c)){$c = $this->contents['contribution'];}
$post = $c->getPost();
$keys = implode(', ', $post->getKeywords());
echo "<a href='?controller=posts&action=show&type=".$c->getPostType()."&id=".$post->getId()."&blog_id=".$c->getBlog()."&cid=".$c->getId()."&lang=$lang'><h3 dir='auto'>".$post->getTitle()."</h3></a>";
	if($_SESSION['logged_in'] && ($_SESSION['user']->getName() == $c->getUsername())){
		echo "<p dir='auto'><a class='ownerforms' href='?controller=posts&action=edit&cid=".$c->getId()."&type=".$post->getPostType()."&id=".$post->getId()."&lang=".$this->contents['lang']."'>$txt_edit_post '".$post->getTitle()."'</a>";
		echo " | <a class='ownerforms' href='?controller=posts&action=delete&cid=".$c->getId()."&type=".$post->getPostType()."&id=".$post->getId()."&blog=".$c->getBlog()."&lang=".$this->contents['lang']."'>$txt_delete_post '".$post->getTitle()."'</a></p>";
		echo $txt_be_patient;
	}
	if(!empty($post->getVlink())){ 
		echo getEmbedLink($post->getVlink(), 640);
	} else {	
		$filename = explode("/", $post->getPath_to_file());
		$waste = array_pop($filename);
		$file2 = implode("/", $filename)."/captions.vtt";
		echo "<video controls dir='auto'>";
		echo		"<source src='".$post->getPath_to_file()."' type='video/".$post->getFiletype()."'>";
	
		if(file_exists($file2)){
			echo "<track src='".$file2."' label='SDP Examples' kind='subtitles' />";
		}
		echo 		"Your browser does not support the video element.</video>";
	}
	echo "<p dir='auto'>".$txt_description.": ".$post->getDescription()."</p>";
	echo "<p dir='auto'>".$txt_keys.": ".$keys."</p>";
?>