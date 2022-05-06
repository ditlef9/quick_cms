<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>send_new_password_test</title>
</head>
<body>



<form method=\"post\" action=\"send_new_password.php\" enctype=\"multipart/form-data\">
	
	<p>
	<b>email</b><br />
	<input type=\"text\" name=\"user_email\" value=\"\" size=\"30\" />
	</p>
	
					
	<p>
	<input type=\"submit\" value=\"Send\" />
	</p>
</form>

</body>
</html>";
?>