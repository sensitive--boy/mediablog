<?php 
session_start();
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en_GB';
?>
<!DOCTYPE html>
<html>
  <head>
  	<meta charset="UTF-8">
  	<title>tiBlogs</title>
  	<link rel="stylesheet" type="text/css" href="css/general.css" />
  </head>
  <body>
    <header>
    	<div id="lang-menu">
    		<?php $link = explode("lang=", $_SERVER["REQUEST_URI"]); ?>
      <ul>
        <li><a href="<?php echo $link[0]; ?>lang=de_DE"><img src="gfx/pung<?php if($lang=="de_DE") echo 'a'; ?>.png" />Deutsch</a></li>
		  	<li><a href="<?php echo $link[0]; ?>lang=en_GB"><img src="gfx/pung<?php if($lang=="en_GB") echo 'a'; ?>.png" />English</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=bg_BG"><img src="gfx/pung<?php if($lang=="bg_BG") echo 'a'; ?>.png" />български</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=ru_RU"><img src="gfx/pung<?php if($lang=="ru_RU") echo 'a'; ?>.png" />Русский</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=ro_RO"><img src="gfx/pung<?php if($lang=="ro_RO") echo 'a'; ?>.png" />Română</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=tr_Tr"><img src="gfx/pung<?php if($lang=="tr_Tr") echo 'a'; ?>.png" />Türkçe</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=es_ES"><img src="gfx/pung<?php if($lang=="es_ES") echo 'a'; ?>.png" />Español</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=fr_FR"><img src="gfx/pung<?php if($lang=="fr_FR") echo 'a'; ?>.png" />Français</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=ar"><img src="gfx/pung<?php if($lang=="ar") echo 'a'; ?>.png" />العربية</a></li>
        <li><a href="<?php echo $link[0]; ?>lang=fa_IR"><img src="gfx/pung<?php if($lang=="fa_IR") echo 'a'; ?>.png" />فارسی</a></li>
      </ul>
    	</div>
    	<div id="loginout">
      <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { 
      			echo "You are logged in as ".$_SESSION['uname'].". ";
      			echo "<a href='?controller=pages&action=afterlogin&logout=true&lang=".$lang."'>logout</a>";
      		} else {
      			echo "<a href='?controller=user&action=create&lang=".$lang."'>Sign up</a> | ";
      			echo "<a href='?controller=user&action=login&lang=".$lang."'>login</a>";
      		}
      ?>
    	</div>
      <div id="logo"><a href='/tiblogs?lang=<?php echo $lang; ?>'><img src="gfx/tiblogs.png" alt="tiblogs logo" /></a></div>
      <div class="nav main">
      <ul>
      	<li><a href='/tiblogs?lang=<?php echo $lang; ?>'>Home</a></li>
      	<li><a href='?controller=blogs&action=index&lang=<?php echo $lang; ?>'>Blogs</a></li>
      	<li><a href='?controller=user&action=index&lang=<?php echo $lang; ?>'>User</a></li>
      </ul>
      
      </div>
    </header>
