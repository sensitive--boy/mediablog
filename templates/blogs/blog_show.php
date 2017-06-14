<?php
/************
* folder: tiblogs/templates
* Multiblog mvc project
* blog_show.php -> Template for a blog
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
$b = $this->contents['blog'];
$s = $this->contents['style'];
$admin = ($_SESSION['logged_in'] && $_SESSION['user']->getId() == $b->getOwner()) ? true : false;

include 'languages/'.$lang.'.php';
include_once 'include/functions.php';
$target_dir = '0--x--0/b'.$b->getId().'/';
$allowed_image_types = array('jpg', 'jpeg', 'png', 'gif');
$titleimage = findFileWithType($target_dir, 'header-image', $allowed_image_types);
$backgroundimage = findFileWithType($target_dir, 'background-image', $allowed_image_types);

echo "<div class='content'>";
echo "<div class='blog'";
		if($backgroundimage && ($s->getBgimage() == true || $s->getBgpattern() == true )) 
			{ echo " style='background-image: url(".$target_dir.$backgroundimage.");'";}
echo ">";
		if($titleimage && ($s->getTitleimage() == true)) {
			echo "<img src='".$target_dir.$titleimage."' id='titleimage' alt='Bild Blogtitel ".$b->getTitle()."' />";
		} else {
			echo "<h2 id='blogtitle' dir='auto'>".$b->getTitle()."</h2>";
		}
echo "<p id='blogdescription' dir='auto'>".$b->getDescription()."</p>";

if($admin){
	echo "<a href='?controller=blogs&action=edit&id=".$b->getId()."&lang=".$lang."' class='ownerforms' dir='auto'>edit blog details</a><br /><br />";

	include 'templates/posts/new_what.php';
}

if(!empty($this->contents['contributions'])) {
	echo "<ul id='postlist'>";
	foreach($this->contents['contributions'] as $c){
		echo "<li><div class='postimage'><img src='gfx/".$c->getPost()->getPostType().".png' alt='' /></div><div class='postcontent largetext'>";
		include("templates/partials/".$c->getPost()->getPostType()."_post.php");
		echo "</div></li>";
	}
	echo "</ul>";	
} else { echo "<text dir='auto'>".$txt_no_posts."</text>"; }

echo "</div><!-- //end blog -->";
echo "</div>";
?>