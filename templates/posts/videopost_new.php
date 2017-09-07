<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* videopost_new.php -> form for creating VideoPost
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
include_once 'include/filemagic.php';
include_once 'nixda/settings.php';
$message = array("Meldungen:");
$allowed_video_types = array('mp4', 'ogg', 'webm');
$thumb = "";

$blog_id = $this->contents['blog_id'];
$ttitle = $this->contents['post']->getTitle();
$text = $this->contents['post']->getDescription();
$id = $this->contents['post']->getId();
$keys = isset($this->contents['keywords']) ? implode(", ", $this->contents['keywords']) : "";
$p_langs = $this->contents['p_langs'];
$split = $p_langs.length;

$target_dir = $this->contents['target'];

$user = $_SESSION['user']->getId();

if(isset($_POST['upload'])) {
	print_r($_FILES);
	$videoFileType = $_FILES['videoToLoad']['type'];
	$today = date("Y-m-d");
	if(!is_dir($target_dir.$today)){ 
		$old_umask = umask(0);
		chdir($target_dir);
     mkdir($today, 0777);
      chdir(BAESEURL);
      umask($old_umask);
   }
  echo "new Folder ready";
   $filename = sanitizeFileName($_FILES['videoToLoad']['name']);
   echo "New filename is: ".$filename;
	$target_file = $target_dir.$today."/".md5($filename).".".explode("/", $videoFileType)[1];
	$uploadOK = 1;
	$uploaded = 0;
   # echo $target_file;
   # echo "ok? ".$uploadOK;
    // Check file size
	if ($_FILES["videoToLoad"]["size"] > 200000000) {
   	echo "Sorry, your file is too large.";
    	$uploadOK = 0;
	}
	#echo "ok? ".$uploadOK;
	#echo "still here - file size checked. ";
	// Allow certain file formats
	if(!in_array(explode("/", $videoFileType)[1],$allowed_video_types)){ #$allowed_video_types)) {
   	 echo "Sorry, only MP4, OGG & WebM files are allowed.";
    	 $uploadOK = 0;
	}
	echo " Your file is: ".$videoFileType;
	echo "ok? ".$uploadOK;
	#echo "still here - file format checked. ";
	
	if(!is_writable($target_dir.$today)) {
		echo "No file permission.";
		$uploadOK = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if($uploadOK == 0) {		
		echo "No No";
   	 $message.push("Sorry, your file was not uploaded.");
	// if everything is ok, try to upload file
	} else {
		echo "taget: ".$target_file;
   	 if (move_uploaded_file($_FILES["videoToLoad"]["tmp_name"], $target_file)) {
       	 #echo "The file ". basename( $_FILES["videoToLoad"]["name"]). " has been uploaded.";
       	 $uploaded = 1;
       	 $file = $_FILES["videoToLoad"]["name"];
       	 $videopath = "<input type='hidden' name='path_to_file' value='".$target_file."' />";
       	 $filenme = "<input type='hidden' name='filename' value=$filename />";
       	 $filetype = "<input type='hidden' name='filetype' value='".explode("/", $videoFileType)[1]."' />";
       	 echo "moved";
       } else {
    	 	#echo "not uploaded ";
    	 	print_r(error_get_last());
        	 $message->push("Sorry, there was an error uploading your file.");
        	 $videopath = "";
        	 $filenme = "";
        	 $filetype = "";
    	 }
    }

}

echo "<div class='content'>";
$f1 = <<<FORM1
	<form role="form" action="?controller=posts&action=save&type=video&lang=$lang" method="post">
		<fieldset>
			<legend dir="auto">new post: </legend>
    		$message
    		uploaded file: $file<br>
    		<a href="javascript:function{openModal('videoupload')}" id='videoupload' dir='auto'>$txt_upload_video</a><br />
    		<label for="p-language" dir="auto">$txt_post_languages</label>
    		<div id="selectcontrol">
    		<select multiple name="p-language[]" id="p-language">
FORM1;
echo $f1;
	foreach($p_langs as $pl){
		echo "<option value='".$pl['id']."' dir='auto'>".$pl['abbr']." - ".$pl['sname']."</option>";
	}
$f2 = "  			
    			<option value='other' dir='auto'>other</option>
    		</select>
    		</div>
    		<label for='title' dir='auto'>".$txt_title.":</label>
    		<input class='strong' type='text' id='title' name='title' dir='auto' value='".$ttitle."' autofocus /><br /><br />
    		<label for='description' dir='auto'>".$txt_text.":</label>
    		<textarea id='description' name='description' dir='auto'>".$text."</textarea>
    		<label for='keywords' dir='auto'>".$txt_keywords.":</label>
    		<input type='text' dir='auto' name='keywords' id='keywords' value='".$keys."' /><br>
    		<br />
    		$videopath
    		$filenme
    		$filetype
    		<input type='hidden' name='blog_id' value='".$blog_id."' />
    		<input type='hidden' name ='id' value='".$id."' />
    		<input type='hidden' name='user' value='".$user."' /><br><br>
    		<input type='checkbox' id='published' name='published' checked /><text dir='auto'> publish imediately</text><br><br>
			<input type='submit' value='save post' dir='auto' />
		</fieldset>
	</form>";
echo $f2;
echo "</div>";
include 'templates/modals/video_upload_form.php';
?>