<?php
	echo '<h1>Intern</h1>';
	echo 'Hallo, '.$this->contents['user']->getName().'<br />';
	echo '<p>mich kannst du nur sehen, wenn du eingeloggt bist.</p><br />';
	echo '<a href="?action=logout">Ausloggen</a>';
?>