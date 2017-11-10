<?php
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
if(!isset($c)){$c = $this->contents['contribution'];}
$post = $c->getPost();
$keys = implode(',', $post->getKeywords());
echo "<a href='?controller=posts&action=show&type=".$c->getPostType()."&id=".$post->getId()."&blog_id=".$c->getBlog()."&cid=".$c->getId()."&lang=$lang'><h3 dir='auto'>".$post->getTitle()."</h3></a>";
	echo "<p dir='auto'>".substr($post->getDescription(), 0, 250)."... </p>";
	if(!empty($keys)){echo "<p dir='auto'>".$txt_keys.": ".$keys."</p>";}
?>