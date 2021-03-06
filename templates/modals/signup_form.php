<?php
/************
* folder: projekt/templates
* Aufbau eines Login-Prozesses mit MVC-Pattern und Datenbankwrapper
* signup_form.php -> Template für User-Registrierung
* @autor: Kai Noah Schirmer
* (c) Cimi Feb/Mar 2015
*************/
$lang = $this->contents['lang'];
$message = "";
$successMessage = "";
if(isset($_POST['formSubmit']) && $_POST['formSubmit'] == "signup") {
	$errorMessage = "";
	
	if(isset($_POST['username']) && !empty($_POST['username'])) {
		$uname = $_POST['username'];
		$successMessage .= "<li>Dein Username ist: $uname";
	} else {
		$errorMessage .= "<li>Make sure you enter a username.</li>";
	}
	
	if(isset($_POST['email']) && !empty($_POST['email'])) {
		$email = $_POST['email'];
		$successMessage .= ", Deine Mailadresse ist: $email";
	} else {
		$errorMessage .= "<li>We need your email.</li>";
	}
	
	if(isset($_POST['pw1']) && !empty($_POST['pw1'])) {
		$pw1 = $_POST['pw1'];
	} else {
		$errorMessage .= "<li>No sign up without password.</li>";
	}
	
	if(isset($_POST['pw2']) && $_POST['pw2'] == $_POST['pw1']) {
		$pw2 = $_POST['pw2'];
		$successMessage .= ", Dein Passwort lautet: $pw2 </li>";
	} else {
		$errorMessage .= "<li>Please retype your password.</li>";
	}
	
	if(!empty($errorMessage)) {
    		$message .= "<p>There was an error with your form:</p>\n";
    		$message .= "<ul>" . $errorMessage . "</ul>\n";
    	} else {
    		$user->setName($uname);
    		$user->setEmail($email);
    		$user->setPassword($pw1);
    		$user->save();
    		#header('Location: ?controller=pages&action=aftersignup&mail='.$email.'&lang='.$lang);
    	}
}

?>
<?php 
$uname = isset($uname)? $uname : '';
$email = isset($email)? $email : '';
$pw1 = isset($pw1)? $pw1 : '';
$pw2 = isset($pw2)? $pw2 : '';

echo <<<FORM_
<div class="modal" id="signup-form">
	<ul>$successMessage</ul>
	<form role="form" action="?{$_SERVER['QUERY_STRING']}" method="post">
	Next step: {$_SERVER['QUERY_STRING']}
		<fieldset id='signupf'>
    		<legend>Sign up: </legend>
    			$message
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" placeholder="Enter username of your choice" maxlength="50" value="$uname" autofocus ><br><br>
	
			<label for="email">Email address:</label>
			<input type="email" id="email" name="email" placeholder="Enter valid email" value="$email"><br><br>
	
			<label for="pw1">Password:</label>
			<input type="text" id="pw1" name="pw1" placeholder="Enter password" value="$pw1"><br><br>
	
			<label for="pw2">Repeat password:</label>
			<input type="text" id="pw2" name="pw2" placeholder="Repeat password" value="$pw2"><br><br>
	
			<input type="submit" name="formSubmit" id="signup-button" value="signup">
		</fieldset>
	</form>
</div>
FORM_;
?>