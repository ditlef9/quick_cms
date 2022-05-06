<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>post_new_recipe_image_receive</title>
</head>
<body>



<form method=\"post\" action=\"post_new_recipe_image_receive.php\" enctype=\"multipart/form-data\">
	
	<p>
	<b>id</b><br />
	<input type=\"text\" name=\"inp_recipe_id\" value=\"3\" size=\"30\" />
	</p>

	<p>
	<b>img (1000x667)</b><br />
	<input type=\"file\" name=\"inp_image\" />
	</p>
				
	<p>
	<input type=\"submit\" value=\"Send\" />
	</p>
</form>

</body>
</html>";
?>