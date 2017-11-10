<?php
include_once 'include/filemagic.php';
session_start();
$lang = $this->contents['lang'];

if(isset($_POST['videolink'])) {
	echo getEmbedLink($_POST['videolink']);
} else {
	echo "<form method='post' action='?controller=pages&action=home&lang=".$lang."'>";
	echo "<input type='text' name='videolink' id='videolink' />";
	echo "<input type='submit' value='submit'>";
	echo "</form>";
}




?>