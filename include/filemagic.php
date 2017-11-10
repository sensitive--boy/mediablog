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
	
	function findFileWithType($dir, $filename, $allowed_types) {
		if(!is_dir($dir)) { return false;
		} else {
		$result = false; 
		$handle = opendir($dir);
		while(false !== ($fname =readdir($handle))){
			$nameparts = explode(".", $fname);
			if($nameparts[0] == $filename && in_array($nameparts[1], $allowed_types)) {
				$result = $fname;
			}
		}
		closedir($handle);
		return $result;
		}
	}
	
	function getEmbedLink($vlink, $width) {
		$wid = (integer)$width;
		$hig = floor($wid/16*9);
		if(stristr($vlink, 'youtu') !== false ){
			# is hosted on youtube
			# all youtube videos I found had 11 characters ID
			if(stristr($vlink, 'src=') !== false ) {
				# embed link was copied (not relevant as input field only accepts url format)
				$pos1 = stripos($vlink, 'embed/');
				$videoid = substr($vlink, $pos1+5, 11);
			} else {
				# direct link or url was copied
				$parts = explode('/', $vlink);
				$videoid = array_pop($parts);
				
				if(strpos($prob_id, '=') !== false){
					# link is url
					$param_parts = explode('=', $prob_id);
					$videoid = $param_parts[1];

					if(strpos($prob_id, '&') !== false) {
						$videoid = explode('&', $prob_id)[0];
					}
				}
			}
			return "<iframe width='".$wid."' height='".$hig."' src='https://www.youtube-nocookie.com/embed/".$videoid."?controls=1&rel=0' frameborder='0' allowfullscreen>No Iframe!</iframe>";	
		} elseif(stristr($vlink, 'vimeo') !== false ){
			# is vimeo link
			# all vimeo videos I found had 9 characters ID
			if(stristr($vlink, 'src=') !== false ) {
				# embed link was copied (not relevant as input field only accepts url format)
				$pos1 = stripos($vlink, 'player.vimeo.com/video/');
				$videoid = substr($vlink, $pos1+23, 9);
			} else {
				# file link or url was copied
				$parts = explode('/', $vlink);
				$videoid = array_pop($parts);
			}
			return "<iframe src='https://player.vimeo.com/video/".$videoid."' width='".$wid."' height='".$hig."' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		} else {
			return "no valid file path.";
		}
	}

?>