<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* PageView.php -> class PageView
* class for main view with header and footer
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class PageView {
	// Pfad zu den Template-Dateien
	private $path = 'templates';
	// Standard header:
	private $header = 'big_header';
	// Standard Template:
	private $template = 'home';
	// Standard Footer:
	private $footer = 'simple_footer';
	// Array für Variablen, die an Template übergeben werden
	private $contents = array();
	
	
	public function setHeader($header = 'big_header') {
		$this->header = $header;
	}
	public function setTemplate($template = 'home'){
		$this->template = $template;
	}
	public function setFooter($footer = 'simple_footer') {
		$this->footer = $footer;
	}
	
	public function putContents($key, $value){
		$this->contents[$key] = $value;
	}

	
	public function loadHeader() {
		$hdr = $this->header;
		$file = $this->path .'/'. $hdr . '.php';
		$exists = file_exists($file);

		if ($exists){
			ob_start();
			include $file;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} else {
			// Template-File existiert nicht-> Fehlermeldung.
			return 'could not find header template';
		}
	}
	
	public function loadTemplate(){
		$tpl = $this->template;
		$file = $this->path .'/'. $tpl . '.php';
		$exists = file_exists($file);

		if ($exists){
			/*
			Der Inhalt dieses internen Puffers kann mit Hilfe der Funktion ob_get_contents() in eine Stringvariable kopiert werden. 
			Mit der Funktion ob_end_flush() können die Pufferinhalte an den Client ausgegeben werden, ob_end_clean() wird der Puffer ohne Ausgabe gelöscht. 
			*/
			ob_start();

			// Das Template-File wird eingebunden und dessen Ausgabe in
			// $output gespeichert.
			include $file;
			$output = ob_get_contents();
			ob_end_clean();
				
			// Output zurückgeben.
			return $output;
		}
		else {
			// Template-File existiert nicht-> Fehlermeldung.
			return 'could not find template';
		}
	}
	
	public function loadFooter() {
		$ftr = $this->footer;
		$file = $this->path .'/'. $ftr . '.php';
		$exists = file_exists($file);

		if ($exists){
			ob_start();
			include $file;
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} else {
			// Template-File existiert nicht-> Fehlermeldung.
			return 'could not find footer template';
		}
	}

}
?>