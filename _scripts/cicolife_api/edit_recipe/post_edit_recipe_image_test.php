<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>post_edit_recipe_image</title>
</head>
<body>



<form method=\"post\" action=\"post_edit_recipe_title_introduction.php\" enctype=\"multipart/form-data\">
	
	<p>
	<b>inp_recipe_id</b><br />
	<input type=\"text\" name=\"inp_recipe_id\" value=\"45\" size=\"30\" />
	</p>
	<p>
	<b>inp_password</b><br />
	<input type=\"text\" name=\"inp_password\" value=\"TESTER 1\" size=\"30\" />
	</p>
	<p>
	<b>introduction</b><br />
	<input type=\"text\" name=\"inp_introduction\" value=\"TESTER 2\" size=\"30\" />
	</p>
	<p>
	<b>password</b><br />
	<input type=\"text\" name=\"inp_password\" value=\"x\" size=\"30\" />
	</p>
				
	<p>
	<input type=\"submit\" value=\"Send\" />
	</p>
</form>

</body>
</html>";
?>