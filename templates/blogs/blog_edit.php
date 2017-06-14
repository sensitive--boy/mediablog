<?php
/************
* folder: tiblogs/templates/blogs
* mvc multiblog project
* blogs_edit.php -> form for editing blog information
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
include '../include/functions.php';
$message = array("Meldungen:");
$allowed_image_types = array('jpg', 'jpeg', 'png', 'gif');

$ttitle = $this->contents['blog']->getTitle();
$text = $this->contents['blog']->getDescription();
$id = $this->contents['blog']->getId();
$user = $_SESSION['user']->getId();
$use_ttlimage = $this->contents['style']->getTitleimage();
$use_bgimage = $this->contents['style']->getBgimage();
$use_bgpattern = $this->contents['style']->getBgpattern();
$himage = "";
$bimage = "";

$target_dir = '0--x--0/b'.$id.'/';


if(isset($_POST['upload'])) {
	$imageFileType = $_FILES['imageToLoad']['type'];
	if($_POST['himage']) {
		$target_file = $target_dir.'header-image.'.explode("/", $imageFileType)[1];
		$himage = $target_file;
		echo "anzulegende Datei: ".$target_file;
	} elseif($_POST['bimage']) {
		$target_file = $target_dir.'background-image.'.explode("/", $imageFileType)[1];
		$bimage = $target_file;
		echo "anzulegende Datei: ".$target_file;
	}
	$uploadOK = 1;
	$uploaded = 0;
	
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["imageToLoad"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check file size
	if ($_FILES["imageToLoad"]["size"] > 300000) {
   	echo "Sorry, your file is too large.";
    	$uploadOk = 0;
	}
	
	// Allow certain file formats
	if(!in_array(explode("/", $imageFileType)[1], $allowed_image_types)) {
   	 echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    	 $uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
   	 $message.push("Sorry, your file was not uploaded.");
	// if everything is ok, try to upload file
	} else {
		echo $target_file;
   	 if (move_uploaded_file($_FILES["imageToLoad"]["tmp_name"], $target_file)) {
       	 echo "The file ". basename( $_FILES["imageToLoad"]["name"]). " has been uploaded.";
       	 $uploaded = 1;
       	 echo "moved";
       	 	// delete other header- or background files
				foreach($allowed_image_types as $t){
					$file = $target_dir.explode(".", end(explode("/", $target_file)))[0].$t;
					echo $file;
					if(is_file($file) && $t !== explode("/", $imageFileType)[1]) {
						unlink($file);
					}
				}
       	 
    	 } else {
        	 $message->push("Sorry, there was an error uploading your file.");
    	 }
    }

}
// find image files
$himage = findFileWithType($target_dir, 'header-image', $allowed_image_types);
$bimage = findFileWithType($target_dir, 'background-image', $allowed_image_types);


echo "<div class='content'>";
echo "<form role='form' action='?controller=blogs&action=update&lang=".$lang."' method='post'>";
echo "	<fieldset class='largetext'><legend dir='auto'>".$txt_edit_blog.": </legend>";

if(!empty($message)){ 
	foreach($message as $m) echo $m."<br>"; 
}
echo "	<h3 dir='auto'>$txt_blog_details</h3>";
echo "	<label for='title'><h4 dir='auto'>".$txt_title.":</h4></label>";
echo "	<input class='strong' dir='auto' type='text' id='title' name='title' value='".$ttitle."' autofocus /><br /><br />";

echo "	<label for='ttlfont' class='inline bold' dir='auto'>".$txt_font.": </label>";
		foreach($this->contents['hfonts'] as $hf){
			echo "<input type='radio' name='ttlfont' id='ttlfont' value='$hf'";
			if($this->contents['style']->getTitlefont() == $hf) {
				echo " checked";
			}
			echo " /><text class='$hf' dir='auto'>".$txt_this_font."</text>&nbsp;";
		}
echo "	<br />";
echo "	<label for='ttlcolor' class='inline bold' dir='auto'>".$txt_color.": </label>";
echo "	<input type='color' name='ttlcolor' id='ttlcolor' value='".$this->contents['style']->getTitlecolor()."' />";
echo "	<br><br>";
echo "	------ or -----<br>";
if(!empty($himage)){ echo "	<img src='".$target_dir.$himage."' width='150px' alt='' />";
			  echo "	<input type='checkbox' name='titleimage' ";
			if($use_ttlimage) { echo "checked";}
			echo "	 /><text dir='auto'>$txt_use_titleimage</text><br>";
}
echo "	<a href='javascript:function{openModal('ttlupload')}' id='ttlupload' dir='auto'>".$txt_upload_ttlimage."</a><br /><br />";
    		
echo "	<label for='description'><h3 dir='auto'>".$txt_description.":</h3></label>";
echo "	<textarea id='description' name='description' dir='auto'>".$text."</textarea>";
echo "	<label for='descfont' class='inline bold' dir='auto'>".$txt_font.": </label>";
		foreach($this->contents['tfonts'] as $tf){
			echo "<input type='radio' name='descfont' id='descfont' value='$tf'";
			if($this->contents['style']->getDescriptionfont() == $tf) {
				echo " checked";
			}
			echo " /><text class='$tf' dir='auto'>".$txt_this_font."</text>&nbsp;";
		}
echo "	<br />";
echo "	<label for='desccolor' class='inline bold' dir='auto'>".$txt_color.": </label>";
echo "	<input type='color' name='desccolor' id='desccolor' value='".$this->contents['style']->getDescriptioncolor()."' />";
echo "	<br><br>";

echo "	<h4 dir='auto'>".$txt_background."</h4>";
echo "	<label for='backcolor' dir='auto'>$txt_background_color</label>";
echo "	<input type='color' name='bgcolor' id='pbackcolor' value='".$this->contents['style']->getBgcolor()."' />";
echo "	<input type='checkbox' class='inline' name='usebgcolor' id='usebgcolor' ";
		if($this->contents['style']->getUsebgcolor()) { echo "checked ";}
echo "	/><text dir='auto'> $txt_show_background_color</text><br /><br />";
echo "	<a href='javascript:function{openModal('bgupload')}' id='bgupload' dir='auto'>".$txt_upload_bgimage."</a><br /><br />";
if(!empty($bimage)){ echo "<img src='".$target_dir.$bimage."' width='150px' alt='' /><br />";}
echo "	<input type='radio' name='background' value='bgimage'";
echo ($use_bgimage) ? "checked" : "";
echo " 	/><text dir='auto'> ".$txt_bgimage."</text>";
echo "	<input type='radio' name='background' value='bgpattern'";
echo ($use_bgpattern) ? "checked" : "";
echo " 	/><text dir='auto'> ".$txt_bgpattern."</text>";
echo "	<input type='radio' name='background' value='no'";
echo (!$use_bgimage && !$use_bgpattern) ? "checked" : "";
echo " 	/><text dir='auto'> ".$txt_nobg."</text><br />";

echo "<hr /><h3 dir='auto'>$txt_post_details</h3>";			
echo "	<h4 dir='auto'>".$txt_post_title."</h4>";
echo "	<label for='pttlfont' class='inline bold' dir='auto'>".$txt_font.": </label>";
		foreach($this->contents['hfonts'] as $hf){
			echo "<input type='radio' name='pttlfont' id='pttlfont' value='$hf'";
			if($this->contents['style']->getPosttitlefont() == $hf) {
				echo " checked";
			}
			echo " /><text class='$hf' dir='auto'>".$txt_this_font."</text>&nbsp;";
		}
echo "	<br />";
echo "	<label for='pttlcolor' class='inline bold' dir='auto'>".$txt_color.": </label>";
echo "	<input type='color' name='pttlcolor' id='pttlcolor' value='".$this->contents['style']->getPosttitlecolor()."' />";
echo "	<br><br>";
			
echo "	<h4 dir='auto'>".$txt_post_text."</h4>";
echo "	<label for='pbfont' class='inline bold' dir='auto'>".$txt_font.": </label>";
		foreach($this->contents['tfonts'] as $tf){
			echo "<input type='radio' name='pbfont' id='pbfont' value='$tf'";
			if($this->contents['style']->getPosttextfont() == $tf) {
				echo " checked";
			}
			echo " /><text class='$tf' dir='auto'>".$txt_this_font."</text>&nbsp;";
		}
echo "	<br />";
echo "	<label for='pbcolor' class='inline bold' dir='auto'>".$txt_color.": </label>";
echo "	<input type='color' name='pbcolor' id='pbcolor' value='".$this->contents['style']->getPosttextcolor()."' />";
echo "	<br><br>";

echo "	<h4 dir='auto'>$txt_post_icons</h4>";
		if($this->contents['style']->getPosticons()) {
			echo "	<input type='radio' name='posticons' value=1 checked /><text dir='auto'> $txt_use_posticons</text>";
			echo "	<input type='radio' name='posticons' value=0 /><text dir='auto'> $txt_dont_use_posticons</text><br /><br />";
		} else {
			echo "	<input type='radio' name='posticons' value=1 /><text dir='auto'> $txt_use_posticons</text>";
			echo "	<input type='radio' name='posticons' value=0 checked /><text dir='auto'> $txt_dont_use_posticons</text><br /><br />";
		}
echo "	<h4 dir='auto'>$txt_post $txt_background</h4>";
echo "	<label for='pbackcolor' dir='auto'>$txt_background_color</label>";
echo "	<input type='color' name='pbackcolor' id='pbackcolor' value='".$this->contents['style']->getPbackcolor()."' />";

echo "	<label for='pbackopacity' dir='auto'>$txt_background_opacity</label>";
echo "	<input type='range' step='10' name='pbackopacity' id='pbackopacity' value='".$this->contents['style']->getPbackopacity()."' /><br /><br />";
	if($this->contents['style']->getPrcorners()) { 
			echo "<input type='radio' name='prcorners' value=1 checked /><text dir='auto'> $txt_round_corners</text>";
			echo "<input type='radio' name='prcorners' value=0 /><text dir='auto'> $txt_point_corners</text><br /><br />";
	} else {
			echo "<input type='radio' name='prcorners' value=1 /><text dir='auto'> $txt_round_corners</text>";
			echo "<input type='radio' name='prcorners' value=0 checked /><text dir='auto'> $txt_point_corners</text><br /><br />";
	}
			
echo "	<input type='hidden' name ='id' value=".$id." />";
echo "	<input type='hidden' name='user' value=".$user." /><br><br>";
echo "	<input type='submit' value='".$txt_save_changes."' dir='auto' />";
echo "  </fieldset>";
echo "</form>";

echo "</div>";
include 'templates/modals/ttl_upload_form.php';
include 'templates/modals/bg_upload_form.php';
?>