<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>post_registration_from_android_test</title>
</head>
<body>



<form method=\"post\" action=\"post_registration_user_receive.php\" enctype=\"multipart/form-data\">
	
	<p>
	<b>email</b><br />
	<input type=\"text\" name=\"inp_user_email\" value=\"\" size=\"30\" />
	</p>
	
	<p>
	<b>password (sha1 encrypted)</b><br />
	<input type=\"text\" name=\"inp_user_password\" value=\"\" size=\"30\" />
	</p>
	
	<p>
	<b>salt</b><br />
	<input type=\"text\" name=\"inp_user_salt\" value=\"\" size=\"30\" />
	</p>
	
	<p>
	<b>alias</b><br />
	<input type=\"text\" name=\"inp_user_alias\" value=\"\" size=\"30\" />
	</p>
	
	<p>
	<b>dob (YYYY-mm-dd)</b><br />
	<input type=\"text\" name=\"inp_user_dob\" value=\"1985-"; echo date("m-d"); echo"\" size=\"30\" />
	</p>
	
	<p>
	<b>gender</b><br />
	<select name=\"inp_user_gender\">
		<option value=\"male\">male</option>
		<option value=\"female\">female</option>
	</select>
	</p>
	
	<p>
	<b>height (cm)</b><br />
	<input type=\"text\" name=\"inp_user_height\" value=\"\" size=\"30\" />
	</p>
	
	<p>
	<b>measurement</b><br />
	<select name=\"inp_user_measurement\">
		<option value=\"metric\">metric</option>
		<option value=\"imperial\">imperial</option>
	</select>
	</p>
	
	<p>
	<b>registered (YYYY-mm-dd)</b><br />
	<input type=\"text\" name=\"inp_user_registered\" value=\"";  echo date("Y-m-d"); echo"\" size=\"30\" />
	</p>
		
	
	<p>
	<b>language</b><br />
	<input type=\"text\" name=\"inp_user_language\" value=\"no\" size=\"30\" />
	</p>
			

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