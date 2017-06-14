<?php
include_once 'include/functions.php';
$lang = $this->contents['lang'];
$user = $this->contents['user'];
$blog = $this->contents['blog'];
include 'languages/'.$lang.'.php';
echo "<div class='modal' id='bgupload-form'>";
echo <<<WELL_
	<form action="?{$_SERVER['QUERY_STRING']}" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend>$txt_upload_bgimage: </legend>
			<label for="imageToLoad">$txt_send_bgimage</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
			<input type="hidden" name="bimage" id="bimage" value="bimage" />
    		<input type="file" name="imageToLoad" id="imageToLoad" /><br /><br />
    		<input type="reset" id="cncl-bgupload-button" class="inline" value="$txt_cancel" /> &nbsp;&nbsp;
    		<input type="submit" id="bgupload-button" name="$txt_upload" value="$txt_upload" />
	</fieldset>
	</form>
WELL_;
	

	echo "</div>";
?>