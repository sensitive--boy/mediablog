<?php
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
include_once 'include/filemagic.php';
if(!isset($c)){$c = $this->contents['contribution'];}
$post = $c->getPost();
$keys = implode(',', $post->getKeywords());
if(!empty($post->getVlink())){
		#$filename = explode("/", $post->getPath_to_file());
		#$videoid = array_pop($filename);
		echo getEmbedLink($post->getVlink(), 250);
	
	} else {	
		echo "<video controls dir='auto'><source src='".$post->getPath_to_file()."' type='video/".$post->getFiletype()."'>Your browser does not support the video element.</video>";
	}
echo "<a href='?controller=posts&action=show&type=".$c->getPostType()."&id=".$post->getId()."&blog_id=".$c->getBlog()."&cid=".$c->getId()."&lang=$lang'><h4 dir='auto'>".$post->getTitle()."</h4></a>";
#echo "<p dir='auto'>".substr($post->getDescription(), 0, 75)." ...</p>";
if(!empty($keys)){echo "<p dir='auto'>".$txt_keys.": ".$keys."</p>";}
?>