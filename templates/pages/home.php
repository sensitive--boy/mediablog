<?php
/************
* folder: tiblogs/templates/pages
* mvc multiblog project
* home.php -> Template for start page
* @autor: Kai Noah Schirmer
* (c) January 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
$pos = ($dir == 'ltr') ? 'left' : 'right';
$contra_pos = ($dir == 'ltr') ? 'right' : 'left';
$contributions = $this->contents['contributions'];
echo "<div class='content'>";
echo "<h2>$txt_welcome</h2>";
echo "<p>$txt_subline</p>";
echo "<div id='latest' class='pos_$pos'>";
	echo "<div id='latest-blogposts'>";
		echo "<h3 dir='auto'>$txt_latest_posts</h3>";
		if(!empty($contributions)) {
		foreach($contributions as $c){
			echo "<div class='startpost pos_$pos'>$txt_posted_by ".$c->getUsername();
			include("templates/partials/".$c->getPost()->getPostType()."_post-small.php");
			echo " </div>";
		}}
		else {
			echo "Get active and create some content. Just start and <a href='javascript:function{openModal('signup')}' id='signup' dir='auto'>Sign up</a> now.<br> Or come back later. For sure you'll see something here next time.";
		}
	echo "</div><!-- end latest-blogposts -->";
	
echo "</div><!-- end latest -->";
echo "<div class='sidebar pos_$contra_pos'>";
	echo "<h3 dir='auto'>$txt_here_you_find ...</h3>";
	echo "<ul dir='auto'>";
		echo "<li><a href='#'>$nav_legal_info</a></li>";
		echo "<li><a href='#'>$nav_tutorials</a></li>";
		echo "<li><a href='#'>$nav_rules_public_media</a></li>";
		echo "<li><a href='#'>$nav_howto</a></li>";
		echo "<li><a href='#'>$nav_full_search</a></li>";
	echo "</ul>";
echo "</div><!-- end sidebar -->";
echo "</div>";
?>