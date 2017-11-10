<?php
	function requestToString($request_arr) {
		echo "requestToString";
		$temp_arr = array();
		foreach($request_arr as $key=>$value){
			$param = $key."=".$value;
			$temp_arr[] = $param; 
		}
		$request_string = implode("&", $temp_arr);
		return $request_string;
	}
	
	function checkForFile($path, $file){
		$found = false;
		$handle = opendir($path);
		while(false !== ($dname=readdir($handle))) {
			echo $dname;
			echo "=".$file."?";
			if(is_file($dname) && $dname == $file) {  
				echo ":::ja"; 
				$found = true;
			}
		}
		closedir($handle);
		return $found;
	}
	
	
	
	// thanks to Chris Coyier
	function hex2rgb( $colour ) {
        if ( $colour[0] == '#' ) {
                $colour = substr( $colour, 1 );
        }
        if ( strlen( $colour ) == 6 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
                return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        return array( 'red' => $r, 'green' => $g, 'blue' => $b );
	}

	function getColorDifference($color1, $color2) {
		$c1 = hex2rgb($color1);
		$c2 = hex2rgb($color2);
		
		return abs(($c1['red']+$c1['green']+$c1['blue']) - ($c2['red']+$c2['green']+$c2['blue']));
	}

	function checkForSession() {
		if(!$_SESSION['uname']) {
					header("location: index_.php");
					exit;
		}
	}
?>