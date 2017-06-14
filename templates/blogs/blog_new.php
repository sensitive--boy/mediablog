<?php
/************
* folder: tiblogs/templates/blogs
* mvc multiblog project
* blogs_new.php -> form for creating a blog
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
$message = "";
$ttitle = $this->contents['blog']->getTitle();
$text = $this->contents['blog']->getDescription();
$id = $this->contents['blog']->getId();
$user = $_SESSION['user']->getId();
echo "<div class='content'>";
echo <<<FORM
	<form role="form" action="?controller=blogs&action=new&lang=$lang" method="post">
		<fieldset class="largetext">
			<legend dir='auto'>$txt_create_blog: </legend>
    		$message
    		<label for="title" dir='auto'>$txt_title:</label>
    		<input class="strong" type="text" id="title" name="title" value="$ttitle" autofocus /><br /><br />
    		<label for="description" dir='auto'>$txt_description:</label>
    		<textarea id="description" name="description" dir='auto'>$text</textarea>
    		<input type="hidden" name ="id" value=$id />
    		<input type="hidden" name="user" value=$user /><br><br>
			<input type="submit" value="$txt_create_blog" dir="auto" />
		</fieldset>
	</form>
FORM;
echo "</div>";
?>