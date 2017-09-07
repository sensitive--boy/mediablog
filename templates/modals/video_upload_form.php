<?php
include_once 'include/functions.php';
$lang = $this->contents['lang'];
$post = $this->contents['post'];
$blog = $this->contents['blog_id'];
include_once 'languages/'.$lang.'.php';
echo "<div class='modal' id='videoupload-form'>";
echo <<<WELL_
	<form action="?{$_SERVER['QUERY_STRING']}" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend>$txt_upload_video: </legend>
			<label for="videoToLoad">$txt_send_video</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="200000000" />
			<input type='hidden' name='type' value='video' />
			<input type='hidden' name='blog_id' value="$blog"/>
    		<input type="file" name="videoToLoad" id="videoToLoad" /><br /><br />
    		<input type="reset" id="cncl-videoupload-button" class="inline" value="$txt_cancel" /> &nbsp;&nbsp;
    		<input type="submit" id="videoupload-button" name="$txt_upload" value="$txt_upload" />
	</fieldset>
	</form>
WELL_;
	

	echo "</div>";
?>