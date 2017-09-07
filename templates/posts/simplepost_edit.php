<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* simplepost_edit.php -> editing form for SimplePost
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';;
echo "Ich spreche: ".$lang;
$message = "";
$ttitle = $this->contents['post']->getTitle();
$text = $this->contents['post']->getDescription();
$id = $this->contents['post']->getId();
$published = $this->contents['post']->getPublished();
$is_published = ($published) ? "checked" : "";
$published_at = $this->contents['post']->getPublishedAt();
$keys = isset($this->contents['keywords']) ? implode(", ", $this->contents['keywords']) : "";
$contribution_id = $this->contents['contribution']->getId();
echo "<div class='content'>";
echo <<<FORM
	<form role="form" action="?controller=posts&action=update&cid=$contribution_id&type=simple&lang=$lang" method="post">
		<fieldset>
			<legend dir="auto">edit post: </legend>
    		$message
    		<label for="title" dir="auto">$txt_title:</label>
    		<input class="strong" type="text" id="title" name="title" dir="auto" dirname="ttldir" value="$ttitle" autofocus /><br /><br />
    		<label for="description" dir="auto">$txt_text:</label>
    		<textarea id="description" name="description" dir="auto" dirname="textdir">$text</textarea>
    		<label for='keywords' dir='auto'>$txt_keywords:</label>
    		<input type='text' dir='auto' name='keywords' id='keywords' value="$keys" /><br>
    		<input type="hidden" name ="id" value=$id />
    		<label for='published' dir='auto'>$txt_published</label>
    		<input type='checkbox' name='published' id='published' $is_published /><br>
    		<label for='published_at' dir='auto'>$txt_published_at: $published_at</label>
			<input type="submit" value="save changes" dir="auto" />
		</fieldset>
	</form>
FORM;
echo "</div>";
?>