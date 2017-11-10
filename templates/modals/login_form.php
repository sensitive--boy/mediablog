<?php
include_once 'include/functions.php';
$lang = $this->contents['lang'];
$user = $this->contents['user'];
echo "<div class='modal' id='login-form'>";
	echo <<<FORM_
	<form action="?{$_SERVER['QUERY_STRING']}" method="post">
	<fieldset id='loginf'>
	<legend>Login: </legend>
	$message
	<label for="username">Name</label><br />
	<input type="text" id="username" name="username" value="{$user->getName()}" maxlength=20  placeholder="username" /><br />
	<label for="email">Email</label><br />	
	<input type="email" id="email" name="email" value="{$user->getEmail()}" placeholder="me@email.com" /><br />
	<label for="pass">Password</label><br />
	<input type="password" id="pass" name="pass" value="pw" placeholder="password" /><br />
	<input type="hidden" name="goal" value="" />
	<input type="submit" name="formSubmit" id="login-button" value="login" />
	<br /><br />
	<a href="javascript:function{openModal('signup')}">I want to create a new account.</a>
	</fieldset>
	</form>
FORM_;
	

	echo "</div>";
?>