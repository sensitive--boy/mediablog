<?php
/************
* folder: tiblogs/templates/pages
* mvc multiblog project
* simple_footer.php -> Template for site footer
* @autor: Kai Noah Schirmer
* (c) January 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
?>
<footer>
      <ul>
      	<li><a href="/tiblogs/index_.php"><h4>tiBlogs</h4></a></li>
      	<li><a href="?controller=pages&action=contact&lang=<?php echo $lang; ?>"><?php echo $nav_contact; ?></a></li>	
      	<li><a href="?controller=pages&action=imprint&lang=<?php echo $lang; ?>"><?php echo $nav_imprint; ?></a></li>	
      	<li><a href="?controller=pages&action=faq&lang=<?php echo $lang; ?>"><?php echo $nav_faq; ?></a></li>
      	<li><a href="?controller=pages&action=privacy&lang=<?php echo $lang; ?>"><?php echo $nav_data_security; ?></a></li>
      	<li><a href="?controller=pages&action=funding&lang=<?php echo $lang; ?>"><?php echo $nav_supported; ?></a></li>
      </ul>
      <?php
      	include 'templates/modals/login_form.php';
      	include 'templates/modals/signup_form.php';
      ?>
      <div class="modal-overlay"></div>
      <?php
      	if($_SESSION['logged_in']) {
     			echo "<script src='js/inside.js'></script>";
  			} else {
  				echo "<script src='js/outside.js'></script>";
  			}
      ?>
    </footer>
  </body>
</html>