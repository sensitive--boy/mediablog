<?php
session_start();
$blog_id = $this->contents['blog']->getId();
$lang = $this->contents['lang'];
echo <<<FORM
	<div id="newpost" class="ownerforms">
	<form role="form" class="largetext" action="?controller=posts&action=create&lang=$lang" method="post">
		<input type="radio" id="type" name="type" value="simple" checked /> simple post 
		<input type="radio" id="type" name="type" value="book" /> book post 
		<input type="radio" id="type" name="type" value="audio" /> audio post 
		<input type="radio" id="type" name="type" value="video" /> video post 
		<input type="hidden" name="blog_id" value=$blog_id /> 
		<input type="submit" value="create new post" />
	</form>
	</div>
FORM;
?>