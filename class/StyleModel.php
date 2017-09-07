<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* StyleModel.php -> class StyleModel
* simple class for loading stylesheets
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
include_once 'include/functions.php';
include_once 'nixda/settings.php';
class StyleModel{
	private $stylesheets = array();
	private $layoutTable;
	private $displayModeTable;
	private $customStyleTable = 'styles';
	private $wrapper;
	private $sortopt = null;
	private $abspath = '/var/www/html/tiblogs/';
	private $relpath = '0--x--0';
	
	public function __construct() {
		$this->wrapper = Wrapper::getInstance();
	}
	
	# ToDo: Think about Layout options
	# for getting general layout
	public function getLayout($layout) {
		return 'css/general.css';
	}
	
	# ToDo: implement display modes for accessibility reasons like strong contrasts / bw / no images / braille
	public function getDisplayMode($mode) {
		return 'css/general.css';
	}
	
	# I think I don't need this.
	#public function createBlogStyle($blog_id) {
	#	$style = new Style($blog_id);
	#	return $style;
	#}
	
	# find directory related to blog, make if doen't exist
	public function findOrCreateStyleFolder($blog_id){
		echo "findStyleFolder 1";
		$dir = $this->abspath.MEDIAFOLDER;
		echo $dir;
		$openDir = opendir($dir."/b".$blog_id);
		if(!$openDir) {
			chdir($dir);
			
			echo mkdir("b".$blog_id, 0755) ? "Verzeichnis angelegt." : "Achtung! Verzeichnis nicht angelegt.";
		} else {
			echo "Verzeichnis vorhanden.";
		}
		closedir($openDir);
		echo "findStyleFolder 2";
		return $dir."/b".$blog_id;
	}
	
	public function getStyleByBlogId($blog_id) {
		echo "get Style 1";
		$conditions = array('blog_id' => $blog_id);
		$result = $this->wrapper->selectWhere($this->customStyleTable, $this->sortopt, $conditions)->fetch();
		$style = new Style($result['blog_id']);
		foreach($style->getColumnnames() as $n){
			if(!empty($result[$n])) {
				$function = "set".ucfirst($n);
				$style->{$function}($result[$n]);
			}
		}
		echo "get Style 2";
		return $style;
		
	}
	public function updateStyle($request) {
		$style = $this->getStyleByBlogId($request['id']);
		$columnvals = array();
		$conditions = array();
		$columns = $style->getColumnnames();
		foreach($columns as $n){
			if($request[$n] && !empty($request[$n])) {
				$columnvals[$n] = $request[$n];
			}
		}
		$conditions['blog_id'] = $request['id'];
		$result = $this->wrapper->updateWhere($this->customStyleTable, $columnvals, $conditions);
		if(!empty($result)){
			$style = $this->getStyleByBlogId($request['id']);
			$this->writeStylesheet($style);
		}
	}
	
	public function test(){
		$verz = $this->abspath.MEDIAFOLDER;
		chdir($verz);
		echo "verzeichnis: ".$verz;
		echo "<table border='1'>";		
		echo "<td>Name</td><td>Datei/Verz</td><td>r/w</td><td align='right'>byte</td>";
		$handle = opendir($verz);
		while($dname=readdir($handle)) {
			echo "<tr><td>$dname</td>";
			if(is_file($dname)) { echo "<td>D</td>";}
			elseif(is_dir($dname)) { echo "<td>V</td>";}
			else {echo "<td>?</td>";}
			echo "<td>";
			echo is_readable($dname) ? "R" : "-";
			echo is_writable($dname) ? "W" : "-";	
			echo "</td>";
			echo "<td align='right'>";
			$info = stat($dname);
			echo $info[7]."</td>";
			echo "</tr>";
		}
		closedir($handle);
		echo "</table>";
	}
	
	public function writeStylesheet($style) {		
		// accomplish css code
		$css = ".content{padding:0;}.blog{width:94%;height:100%;padding: 0 3% 50px 3%;";
		if($style->getUsebgcolor()){ $css .= "background-color:".$style->getBgcolor().";";}
		if(!empty($style->getBgimage())) {
			$css .= "background-size:cover;background-repeat:no-repeat;background-attachment:fixed;";
		} elseif(!empty($style->getBgpattern())) {
			$css .= "background-repat:repeat;";
		}
		$css .= "}#blogtitle{font-family:".$style->getTitlefont().";color:".$style->getTitlecolor().";}";
		$css .= "#titleimage{max-width:100%;margin-left:-20px;}";
		$css .= "#blogdescription{font-family:".$style->getDescriptionfont().";color:".$style->getDescriptioncolor().";}";
		$css .= ".postcontent h3{font-family:".$style->getPosttitlefont().";color:".$style->getPosttitlecolor().";}";
		$css .= ".postcontent p{font-family:".$style->getPosttextfont().";color:".$style->getPosttextcolor().";}";
		if($style->getPbackopacity() > 0 || $style->getPrcorners()) {
			$css .= ".postcontent{";
			if($style->getPbackopacity() > 0) { 
				$rgb = hex2rgb($style->getPbackcolor());
				$alpha = $style->getPbackopacity()/100;
				$css .= "background-color:rgba(".$rgb['red'].",".$rgb['green'].",".$rgb['blue'].",".$alpha.");";
			}
			if($style->getPrcorners()) { $css .= "border-radius:10px;";}
			$css .= "}";
			$css .= "/* Wer das liest ist doof. */";
		}
		if(!$style->getPosticons()) { $css .= ".postimage{display:none;}";}
		echo $css;
		
		// find directory related to blog, make if doen't exist
		$openDir = opendir($this->findOrCreateStyleFolder($style->getBlogId()));
			while($file = readdir($openDir)) {
				if($file == 'customStyle.css') {
					// delete old css file
					
					echo "css datei gelöscht.";
				}
			}
			closedir($openDir);
		
			# write new css file if permission to write
			$file = $this->abspath.MEDIAFOLDER.'b'.$style->getBlogId().'/customStyle.css';
			$fp = fopen($file, "w") or die ("Datei anlegen nicht geklappt.");

			if(!is_writable($file)){
				fclose($fp);
				die("Keine Schreibrechte vorhanden.");
			}
			if(fwrite($fp, $css)){
				echo "Datei wurde geschrieben.";
			}
			fclose($fp);
			chdir('/var/www/html/tiblogs');

	}
	public function getCustomStyle($blog_id) {
		$dir = $this->abspath."/".$this->relpath;
		 chdir($dir."/b".$blog_id);
		 $file ='customStyle.css';
		if($handle = @fopen($file, "r"))// an dieser Stelle noch auf Leseberechtigung prüfen) 
		{
			fclose($handle);
			#echo "file gefunden.";
			chdir($this->abspath);
			return $this->relpath."/b".$blog_id."/".$file;
		} else {
			#echo "kein file";
			chdir($this->abspath);
			return "";
		}
	}
	# toDo: return list of headline fonts containing characterset for $language
	public function getHeadlinefontsByLanguage($language) {
		$hfonts = array('DejaVuSans-bold', 'tahomabd', 'Everson', 'Jitzu');
		return $hfonts;
	}
	# toDo: return list of text fonts containing characterset for $language
	public function getTextfontsByLanguage($language) {
		$tfonts = array('FreeSerif', 'sylfaen', 'chrysuni', 'EversonOblique', 'tahoma', 'DejaVuSans');
		return $tfonts;
	}
}
?>