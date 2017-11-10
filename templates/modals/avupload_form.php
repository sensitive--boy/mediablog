<?php
include_once 'include/functions.php';
$lang = $this->contents['lang'];
$user = $this->contents['user'];
$blog = $this->contents['blog'];
include 'languages/'.$lang.'.php';
echo "<div class='modal' id='avupload-form'>";
echo <<<WELL_
	<form action="?{$_SERVER['QUERY_STRING']}" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend>$txt_upload_avimage: </legend>
			<label for="imageToLoad">$txt_send_avimage</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
			<input type="hidden" name="avatarimage" id="avatarimage" value="avatarimage" />
    		<input type="file" name="imageToLoad" id="imageToLoad" /><br /><br />
    		<input type="reset" id="cncl-avupload-button" class="inline" value="$txt_cancel" /> &nbsp;&nbsp;
    		<input type="submit" id="avupload-button" name="$txt_upload" value="$txt_upload" />
	</fieldset>
	</form>
WELL_;
	

	echo "</div>";
?>