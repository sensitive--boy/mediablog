<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* blogs_my.php -> Template for displaying users blogs and contributions
* @autor: Kai Noah Schirmer
* (c) January 2017
*************/
session_start();
if(!$_SESSION['uname']) {
	header('location: index_.php');
	exit;
}
	$lang = $this->contents['lang'];
	$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
	$myblogs = $this->contents['blogs'];
	include 'languages/'.$lang.'.php';
	echo "<div class='content' id='mystuff'>";
	echo "<a href='?controller=user&action=edit_info&user_id=".$user->getId()."&lang=".$this->contents['lang']."' dir='auto'>$txt_personal_info</a><br>";
	echo "<h2 id='pagetitle' dir='auto'>$txt_own_blogs</h2>";
	echo "<div class='ownerforms'><a href='?controller=blogs&action=create&user=".$user->getId()."&lang=".$lang."' dir='auto'>$txt_start_blog</a></div>";
	echo "<table>";

	foreach($myblogs as $blog){
		echo "<tr>";
		echo "<td><a href='?controller=blogs&action=show&id=".$blog->getId()."&lang=".$this->contents['lang']."' dir='auto'>".$blog->getTitle()."</a></td>";
		echo "<td><a class='ownerforms' href='?controller=blogs&action=edit&id=".$blog->getId()."&lang=".$this->contents['lang']."' dir='auto'>$txt_edit_blog</a></td>";
		echo "<td><a class='ownerforms' href='?controller=blogs&action=delete&id=".$blog->getId()."&lang=".$this->contents['lang']."' dir='auto'>$txt_delete_blog</a></td>";
		echo "</tr>";
	}

	echo "</table>";
	
	echo "<h2>$txt_other_blogs</h2><ul>";
	foreach($this->contents['cblogs'] as $cblog){
		if(!in_array($cblog, $myblogs)) {
		echo "<li><a href='index_.php?controller=blogs&action=show&id=".$cblog->getId()."&lang=".$this->contents['lang']."'>".$cblog->getTitle()."</a></li>";
		}
	}

echo "</ul></div>";
?>