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
echo "<div class='content'>";

echo "<h2>$txt_welcome</h2>";

echo "<p>$txt_subline</p>";

echo "</div>";
?>