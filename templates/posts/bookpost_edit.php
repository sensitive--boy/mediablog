<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* bookpost_edit.php -> editing form for BookPost
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
$message = "";
$ttitle = $this->contents['post']->getTitle();
$text = $this->contents['post']->getDescription();
$id = $this->contents['post']->getId();
$author = $this->contents['post']->getAuthor();
$publisher = $this->contents['post']->getPublisher();
$pages = $this->contents['post']->getPages();
$isbn = $this->contents['post']->getIsbn();
echo "<div class='content'>";
echo <<<FORM
	<form role="form" class="largetext" action="?controller=posts&action=update&type=book&lang=".$lang method="post">
		<fieldset>
			<legend>edit post: </legend>
    		$message
    		<label for="title">Title:</label>
    		<input class="strong" type="text" id="title" name="title" value="$ttitle" autofocus /><br /><br />
    		<label for="author">Author(s):</label>
    		<input type="text" id="author" name="author" value="$author" /><br /><br />
    		<label for="publisher">Publisher:</label>
    		<input type="text" id="publisher" name="publisher" value="$publisher" /><br /><br />
    		<label for="pages">Number of pages:</label>
    		<input type="text" id="pages" name="pages" value=$pages /><br /><br />
    		<label for="isbn">ISBN:</label>
    		<input type="text" id="isbn" name="isbn" value="$isbn" /><br /><br />
    		<label for="description">Text:</label>
    		<textarea id="description" name="description">$text</textarea>
    		<input type="hidden" name ="id" value=$id />
			<input type="submit" value="save changes" />
		</fieldset>
	</form>
FORM;
echo "</div>";
?>