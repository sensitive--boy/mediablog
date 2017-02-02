<?php
	echo "<h3>".$post->getTitle()."</h3>";
	echo "<p><a href='?controller=posts&action=edit&type=".$post->getPostType()."&id=".$post->getId()."&lang=".$lang."'>edit post '".$post->getTitle()."'</a>";
	echo " | <a href='?controller=posts&action=delete&type=".$post->getPostType()."&id=".$post->getId()."&lang=".$lang."'>delete post '".$post->getTitle()."'</a></p>";
	echo "<p>".$post->getDescription()."</p>";
?>