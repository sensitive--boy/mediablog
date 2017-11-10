<?php
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
if(!isset($c)){$c = $this->contents['contribution'];}
$post = $c->getPost();
$keys = implode(',', $post->getKeywords());
echo "<audio controls dir='auto'><source src='".$post->getPath_to_file()."' type='audio/".$post->getFiletype()."'>Your browser does not support the audio element.</audio>";
echo "<a href='?controller=posts&action=show&type=".$c->getPostType()."&id=".$post->getId()."&blog_id=".$c->getBlog()."&cid=".$c->getId()."&lang=$lang'><h4 dir='auto'>".$post->getTitle()."</h4></a>";
echo "<p dir='auto'>".substr($post->getDescription(), 0, 175)." ...</p>";
if(!empty($keys)){echo "<p dir='auto'>".$txt_keys.": ".$keys."</p>";}
?>