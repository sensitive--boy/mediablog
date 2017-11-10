<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* about_me_new.php -> form for creating personal information block
* @autor: Kai Noah Schirmer
* (c) 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
$info = $this->contents['userinfo'];
$user_id = $info->getUserId();
$message = array("Meldungen:");
$allowed_image_types = array('jpg', 'jpeg', 'png', 'gif');
$avatar = $info->getAvatarPath();
$imagedescription = $info->getImageDescription();
$full_name = $info->getRealName();
$text = $info->getStory();
$id = $info->getId();
$languages = implode(', ', $info->getLanguages());
$websites = implode(', ', $info->getWebsites());

$is_public = ($info->getVisibility() == 'is_public') ? 'checked' : '';
$members_only = ($info->getVisibility() == 'members_only') ? 'checked' : '';
$friends_only = ($info->getVisibility() == 'friends_only') ? 'checked' : '';

$user = $_SESSION['user']->getId();

$target_dir = MEDIAFOLDER."avatar/";
$avatarimage = !empty($avatar) ? $avatar : "";
if(!empty($avatarimage)){ 
		$image = "	<img src='".$avatarimage."' width='150px' alt='".$imagedescription."' />";
}
$avlink = '	<a href="javascript:function{openModal(\'avupload\')}" id="avupload" dir="auto">'."Bildupload"." autofocus </a><br /><br />";

if(isset($_POST['upload'])) {
	$imageFileType = $_FILES['imageToLoad']['type'];
	if($_POST['avatarimage']) {
		$target_file = $target_dir.'avatar-image'.$id.'.'.explode("/", $imageFileType)[1];
		$avatarimage = $target_file;
		$image = "	<img src='".$avatarimage."' width='150px' alt='".$imagedescription."' />";
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
	echo "still here - file size checked. ";
	// Allow certain file formats
	if(!in_array(explode("/", $imageFileType)[1], $allowed_image_types)) {
   	 echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    	 $uploadOk = 0;
	}
	echo "still here - file format checked. ";
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
   	 $message.push("Sorry, your file was not uploaded.");
	// if everything is ok, try to upload file
	} else {
		echo "taget: ".$target_file;
   	 if (move_uploaded_file($_FILES["imageToLoad"]["tmp_name"], $target_file)) {
       	 echo "The file ". basename( $_FILES["imageToLoad"]["name"]). " has been uploaded.";
       	 $uploaded = 1;
       	 echo "moved";
       	 	// delete other avatar files
				foreach($allowed_image_types as $t){
					$file = $target_dir.explode(".", end(explode("/", $target_file)))[0].'.'.$t;
					echo $file;
					if(is_file($file) && $t !== explode("/", $imageFileType)[1]) {
						unlink($file);
					}
				}
       	 
    	 } else {
    	 	echo "not uploaded ";
        	 $message->push("Sorry, there was an error uploading your file.");
    	 }
    }

}
$messages = (sizeof($message)>1) ? implode('<br>', $message) : "";
// find image files
#$avatarimage = findFileWithType($target_dir, 'avatar-image', $allowed_image_types);

echo "<div class='content'>";
$f1 = <<<FORM1
	<form role="form" action="?controller=user&action=save_info&id=$id&lang=$lang" method="post">
		<fieldset>
			<legend dir="auto">$txt_personal_info: </legend>
			$messages<br>
			$avlink<br>
			$image<br>
			<label for='imagedesc' dir='auto'>$txt_image_description:</label>
    		<input type='text' id='imagedesc' name='imagedesc' dir='auto' value="$imagedescription" /><br />
			<br>    		
    		<p>$txt_personal_what_to_share</p>
    		<label for='fullname' dir='auto'>$txt_personal_fullname:</label>
    		<input type='text' id='fullname' name='fullname' dir='auto' value="$full_name" /><br /><br />
    		<label for='story' dir='auto'>$txt_text:</label>
    		<textarea id='story' name='story' dir='auto'>$text</textarea>
    		<label for="languages" dir="auto">$txt_personal_languages:</label>
    		<input type='text' id='languages' name='languages' value="$languages" />
    		<label for="websites" dir="auto">$txt_personal_websites:</label>
    		<input type='text' id='websites' name='websites' value="$websites" />
    		<input type='hidden' name ='id' value="$id" />
    		<input type='hidden' name='user_id' value="$user_id" />
    		<input type='hidden' name='path_to_avatar' value="$avatarimage" /><br><br>
    		<label for='visibility'>$txt_this_info_is_for:</label>
    		<input type='radio' name='visibility' id='visibility' value='is_public' $is_public /> everyone &nbsp;
    		<input type='radio' name='visibility' id='visibility' value='members_only' $members_only /> members only &nbsp;
    		<input type='radio' name='visibility' id='visibility' value='friends_only' $friends_only /> friends only<br><br>
    		<input type='submit' value='save information' dir='auto' />
		</fieldset>
	</form>
FORM1;
echo $f1;
	
include 'templates/modals/avupload_form.php';
?>