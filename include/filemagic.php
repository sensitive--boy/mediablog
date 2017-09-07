<?php
	function sanitizeFileName($filename){
		echo "I take care of your filename.";
		$extension = end(explode(".", $filename));
		$length = strlen($extension);
		echo "Extension is: ".$extension."und $length characters long";
		$specialCharacters = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", ".", " ", "	", chr(0));
		$newfilename = str_replace( $specialCharacters, '', $filename );
		echo "new filename is: ".$newfilename;
		$basename = mb_substr($newfilename, 0, -($length), 'UTF-8');
		echo "Basename is: ".$basename;
		
		return $basename.".".$extension;
	}

?>