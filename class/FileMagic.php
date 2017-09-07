<?php
require_once 'class/TiAutoloader.php';
require_once 'nixda/settings.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class FileMagic{
	
	public function createDirectory($path, $directoryname) {
		if(!is_dir($path.$directoryname)){ 
			if(!is_writable($path)) {
				echo "No right to write.";
			} else {
				chdir($path);
				echo "I am here: ".$_SERVER['SCRIPT_FILENAME'];
            mkdir($directoryname);
            echo " Success!";
            chdir("/var/www/html/tiblogs/");
         }
      } else {echo "Folder exists.";}
      return $path.$directoryname;
		
	}
	
	public function sanitizeFileName($filename){
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
	public function sayHello(){
		return "Grrr";
	}
}
?>