<?php
/************
* folder: tiblogs/templates
* mvc multiblog project
* simplepost_new.php -> form for creating SimplePost
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
session_start();
$lang = $this->contents['lang'];
include 'languages/'.$lang.'.php';
$blog_id = $this->contents['blog_id'];
echo $blog_id;
$message = "";
$ttitle = $this->contents['post']->getTitle();
$text = $this->contents['post']->getDescription();
$id = $this->contents['post']->getId();
$p_langs = $this->contents['p_langs'];
$split = $p_langs.length;

$user = $_SESSION['user']->getId();
echo "<div class='content'>";
$f1 = <<<FORM1
	<form role="form" action="?controller=posts&action=save&type=simple&lang=$lang" method="post">
		<fieldset>
			<legend dir="auto">new post: </legend>
    		$message
    		<label for="p-language" dir="auto">$txt_post_languages</label>
    		<div id="selectcontrol">
    		<select multiple name="p-language[]" id="p-language">
FORM1;
echo $f1;
	foreach($p_langs as $pl){
		echo "<option value='".$pl['id']."' dir='auto'>".$pl['abbr']." - ".$pl['sname']."</option>";
	}
$f2 = "  			
    			<option value='other' dir='auto'>other</option>
    		</select>
    		</div>
    		<label for='title' dir='auto'>Title:</label>
    		<input class='strong' type='text' id='title' name='title' dir='auto' value='".$ttitle."' autofocus /><br /><br />
    		<label for='description' dir='auto'>Text:</label>
    		<textarea id='description' name='description' dir='auto'>".$text."</textarea>
    		<input type='hidden' name='blog_id' value='".$blog_id."' />
    		<input type='hidden' name ='id' value='".$id."' />
    		<input type='hidden' name='user' value='".$user."' /><br><br>
    		<input type='checkbox' id='published' name='published' checked /><text dir='auto'> publish imediately</text><br><br>
			<input type='submit' value='save post' dir='auto' />
		</fieldset>
	</form>";
echo $f2;
echo "</div>";
echo " <script src='plugins/Multi-Column-Select/Multi-Column-Select.js'></script>";
echo "<script>
$('#selectcontrol').MultiColumnSelect({

            multiple:           true,              // Single or Multiple Select- Default Single
            useOptionText :     true,               // Use text from option. Use false if you plan to use images
            hideselect :        true,               // Hide Original Select Control
            openmenuClass :     'mcs-open',         // Toggle Open Button Class
            openmenuText :      'Choose An Option', // Text for button
            openclass :         'open',             // Class added to Toggle button on open
            containerClass :    'mcs-container',    // Class of parent container
            itemClass :         'mcs-item',         // Class of menu items
            idprefix : null,                        // Assign as ID to items eg 'item-' = #item-1, #item-2, #item-3...
            duration : 200,                         //Toggle Height duration
            onOpen : function(){},
            onClose : function(){},
            onItemSelect : function(){}

});
</script>";
?>