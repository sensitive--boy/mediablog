<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* view.php -> class View
* base class for views
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class View {
	// Pfad zu den Template-Dateien
	private $path = 'templates';
	// Standard Template:
	private $template = 'home';
	// Array für Variablen, die an Template übergeben werden
	private $contents = array();
	
	
	public function setTemplate($template){
		$this->template = $template;
	}
	
	public function putContents($key, $value){
		$this->contents[$key] = $value;
	}
	
	public function loadPartial($path, $partialname) {
		$file = $path.'/'.$partialname.'php';
		$exists = file_exists($file);
		if($exists) {
		ob_start();
			
			include $file;
			$output = ob_get_contents();
			ob_end_clean();
				
			return $output;
		}
		else {
			// Partiale-File existiert nicht-> Fehlermeldung.
			return 'could not find partial';
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

}
?>