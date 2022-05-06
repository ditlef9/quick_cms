<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>post_user_language_measurement_test</title>
</head>
<body>



<form method=\"post\" action=\"post_fetch_api_session_from_server.php\" enctype=\"multipart/form-data\">
	
	<p><b>inp_device_id</b><br />
	<input type=\"text\" name=\"inp_device_id\" value=\"42ca047c48d2b382558808d607a28053499585ea\" size=\"30\" />
	</p>

	<p><b>inp_device_name</b><br />
	<input type=\"text\" name=\"inp_device_name\" value=\"Google Android SDK built for x86\" size=\"30\" />
	</p>
	
	<p><b>inp_device_source</b><br />
	<input type=\"text\" name=\"inp_device_source\" value=\"Workout plans and exercises Android app\" size=\"30\" />
	</p>
							
	<p>
	<input type=\"submit\" value=\"Send\" />
	</p>
</form>

</body>
</html>";
?>