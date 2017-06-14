<?php
/************
* folder: tiblogs/templates
* Multiblog mvc project
* login_form.php -> Template for user-login
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
$lang = $this->contents['lang'];
echo "<div class='content'>";
	isset($this->contents['goal']) ? $goal = $this->contents['goal'] : $goal = "";
	echo <<<FORM_
	<form action="index_.php?{$goal}" method="post">
	<fieldset>
	<legend>Login: </legend>
	$message
	<label for="username">Name</label><br />
	<input type="text" id="username" name="username" maxlength=20 value="{$this->contents['user']->getName()}" placeholder="username" /><br />
	<label for="email">Email</label><br />	
	<input type="email" id="email" name="email" value="{$this->contents['user']->getEmail()}" placeholder="me@email.com" /><br />
	<label for="pass">Password</label><br />
	<input type="password" id="pass" name="pass" value="pw" placeholder="password" /><br />
	<input type="submit" id="anmelden" value="anmelden" />
	<br /><br />
	<a href="?controller=user&action=create&lang=$lang$goal">I want to create a new account.</a>
	</fieldset>
	</form>
FORM_;
	

	echo "</div>";
?>