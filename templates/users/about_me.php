<?php
/************
* folder: tiblogs/templates
* Multiblog mvc project
* about_me.php -> Template for showing personal information next to the blog
* @autor: Kai Noah Schirmer
* (c) 2017
*************/
session_start();
$lang = $this->contents['lang'];
$userinfo = $this->contents['userinfo'];
$username = $this->contents['username'];
echo "<div id='userinfo' class='sidebar'>";
echo "<p>$username</p><br>";
echo "<img id='avatar' src='".$userinfo->getAvatarPath()."' alt='".$userinfo->getImageDescription()."' /><h3>".$userinfo->getRealname()."</h3>";
echo "<p>".nl2br($userinfo->getStory())."</p><br><br>";
echo "I am publishing in: <br>".implode(', ', $userinfo->getLanguages())."<br><br>";
echo "I invite you to also have a look at: <br><ul>";
foreach(explode(',', $userinfo->getWebsites()) as $ws){
	echo "<li>$ws</li>";
}
echo "</ul></div><!-- end userinfo -->"; 

?>