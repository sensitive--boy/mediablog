<?php
/************
* folder: tiblogs/templates
* Multiblog mvc project
* blog_show.php -> Template for a blog
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
$b = $this->contents['blog'];
echo "<div class='content'>";
echo "<h2>".$b->getTitle()."</h2><p>".$b->getDescription()."</p>";

echo "<p><a href='?controller=posts&action=create&blog_id=".$b->getId()."&lang=".$lang."'>Create new post</a></p>";

if(!empty($this->contents['posts'])) {
	echo "<ul>";
	foreach($this->contents['posts'] as $post){
		echo "<li><div class='postimage'><img src='gfx/".$post->getPostType().".png' alt='' /></div><div class='postcontent'>";
		include("partials/".$post->getPostType()."_post.php");
		echo "</div></li>";
	}
	echo "</ul>";	
}
?>