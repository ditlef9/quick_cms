<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>post_user_language_measurement_test</title>
</head>
<body>



<form method=\"post\" action=\"post_user_language_measurement.php\" enctype=\"multipart/form-data\">
	
	<p>
	<b>measurement</b><br />
	<input type=\"text\" name=\"inp_user_measurement\" value=\"metric\" size=\"30\" />
	</p>
	<p>
	<b>language</b><br />
	<input type=\"text\" name=\"inp_user_language\" value=\"en\" size=\"30\" />
	</p>
	
	<p>
	<b>password (sha1 encrypted)</b><br />
	<input type=\"text\" name=\"inp_user_password\" value=\""; echo sha1("x"); echo"\" size=\"30\" />
	</p>
	
	<p>
	<b>e-mail</b><br />
	<input type=\"text\" name=\"inp_user_email\" value=\"x@x.com\" size=\"30\" />
	</p>
							
	<p>
	<input type=\"submit\" value=\"Send\" />
	</p>
</form>

</body>
</html>";
?>