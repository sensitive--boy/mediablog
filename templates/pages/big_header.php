<?php
session_save_path('include/session_store');
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
#putenv("LANG=".$lang);
#setlocale(LC_ALL, $lang);
#$domain = "messages";
#bindtextdomain($domain, "languages");
#bind_textdomain_codeset($domain, 'UTF-8');
#textdomain($domain);
echo $_SESSION['uname'];
?>
<!DOCTYPE html>
<html dir="<?php echo $dir; ?>" lang="<?php echo explode('_', $lang)[0]; ?>">
  <head>
  	<meta charset="UTF-8">
  	<title>tiBlogs<?php echo $this->contents['title']; ?></title>
  	<script src="https://code.jquery.com/jquery-latest.js"></script>
  	<link rel="stylesheet" type="text/css" href="plugins/Multi-Column-Select/Multi-Column-Select.css" />
  	<link rel="stylesheet" type="text/css" href="css/general.css" />
  	<link rel="stylesheet" type="text/css" href="<?php echo $this->contents['layout']; ?>" />
  	<link rel="stylesheet" type="text/css" href="<?php echo $this->contents['displayMode']; ?>" />
  	<link rel="stylesheet" type="text/css" href="<?php echo $this->contents['customStyle']; ?>" />
  </head>
  <body>
    <header>
    	<div id="lang-menu">
    		<?php $link = explode("lang=", $_SERVER["REQUEST_URI"]); ?>
      <ul>
        <li><a href="<?php echo $link[0]; ?>lang=de_DE"><img src="gfx/pung<?php if($lang=="de_DE") echo 'a'; ?>.png" />Deutsch</a></li>
		  	<li><a href="<?php echo $link[0]; ?>lang=en_GB"><img src="gfx/pung<?php if($lang=="en_GB") echo 'a'; ?>.png" />English</a></li>
       <!-- <li><a href="<?php echo $link[0]; ?>lang=bg_BG"><img src="gfx/pung<?php if($lang=="bg_BG") echo 'a'; ?>.png" />български</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=ru_RU"><img src="gfx/pung<?php if($lang=="ru_RU") echo 'a'; ?>.png" />Русский</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=ro_RO"><img src="gfx/pung<?php if($lang=="ro_RO") echo 'a'; ?>.png" />Română</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=tr_Tr"><img src="gfx/pung<?php if($lang=="tr_Tr") echo 'a'; ?>.png" />Türkçe</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=es_ES"><img src="gfx/pung<?php if($lang=="es_ES") echo 'a'; ?>.png" />Español</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=fr_FR"><img src="gfx/pung<?php if($lang=="fr_FR") echo 'a'; ?>.png" />Français</a></li>-->
        <li><a href="<?php echo $link[0]; ?>lang=ar"><img src="gfx/pung<?php if($lang=="ar") echo 'a'; ?>.png" />العربية</a></li>
       <!-- <li><a href="<?php echo $link[0]; ?>lang=fa_IR"><img src="gfx/pung<?php if($lang=="fa_IR") echo 'a'; ?>.png" />فارسی</a></li>-->
      </ul>
    	</div>
    	<div id="loginout">
      <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { 
      			echo $txt_logged_in.$_SESSION['uname'].". ";
      			echo "<a href='?logout=true&".$_SERVER['QUERY_STRING']."'>$txt_logout</a>";
      			echo " || <a href='?controller=blogs&action=mystuff&user=".$_SESSION['user']->getID()."&lang=".$lang."'>$txt_my_stuff</a>";
      		} else {
      			echo "<button id='signin'>$txt_signup</button>";
      			echo "<button id='log'>$txt_login</button>";
      		}
      ?>
    	</div>
      <div id="logo"><a href='/tiblogs/index_.php?lang=<?php echo $lang; ?>'><img src="gfx/tiblogs.png" alt="<?php echo $txt_logo_alt; ?>" /></a></div>
      <div class="nav main">
      <ul>
      	<li><a href='/tiblogs/index_.php?lang=<?php echo $lang; ?>'><?php echo $nav_home; ?></a></li>
      	<li><a href='?controller=blogs&action=index&lang=<?php echo $lang; ?>'><?php echo $nav_blogs; ?></a></li>
      	<li><a href='?controller=user&action=index&lang=<?php echo $lang; ?>'><?php echo $nav_users; ?></a></li>
      </ul>
      </div>
      <ul>
      	<?php foreach($this->getContents['notices'] as $notice){
      		echo "<li>$notice</li>";
      	}?>
      </ul>
    </header>
