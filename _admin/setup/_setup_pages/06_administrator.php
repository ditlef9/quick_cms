<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}


if($process == "1"){
	// Administrator
	$inp_user_email = $_POST['inp_user_email'];
	$inp_user_email = output_html($inp_user_email);

	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = output_html($inp_user_password);
	$inp_user_password = sha1($inp_user_password);

		
	if(empty($inp_user_email)){
		$ft = "warning";
		$fm = "please_enter_your_email_address";
		$url = "index.php?page=06_administrator&language=$language&ft=$ft&fm=$fm";
		header("Location: $url");
		exit;
	}
	if(empty($inp_user_password)){
		$ft = "warning";
		$fm = "please_enter_your_password";
		$url = "index.php?page=06_administrator&language=$language&ft=$ft&fm=$fm";
		header("Location: $url");
		exit;
	}


	// Write file
	$update_file="<?php
// Database
\$mysqlHostSav   	= \"$mysqlHostSav\";
\$mysqlUserNameSav   	= \"$mysqlUserNameSav\";
\$mysqlPasswordSav	= \"$mysqlPasswordSav\";
\$mysqlDatabaseNameSav 	= \"$mysqlDatabaseNameSav\";
\$mysqlPrefixSav 	= \"$mysqlPrefixSav\";


// General
\$configWebsiteTitleSav		 = \"$configWebsiteTitleSav\";
\$configWebsiteTitleCleanSav	 = \"$configWebsiteTitleCleanSav\";
\$configWebsiteCopyrightSav	 = \"$configWebsiteCopyrightSav\";
\$configFromEmailSav 		 = \"$configFromEmailSav\";
\$configFromNameSav 		 = \"$configFromNameSav\";

\$configWebsiteVersionSav	= \"$configWebsiteVersionSav\";
\$configMailSendActiveSav	= \"$configMailSendActiveSav\";

// Webmaster
\$configWebsiteWebmasterSav	 = \"$configWebsiteWebmasterSav\";
\$configWebsiteWebmasterEmailSav = \"$configWebsiteWebmasterEmailSav\";

// URLs
\$configSiteURLSav 		= \"$configSiteURLSav\";
\$configSiteURLLenSav 		= \"$configSiteURLLenSav\";
\$configSiteURLSchemeSav	= \"$configSiteURLSchemeSav\";
\$configSiteURLHostSav		= \"$configSiteURLHostSav\";
\$configSiteURLPortSav		= \"$configSiteURLPortSav\";
\$configSiteURLPathSav		= \"$configSiteURLPathSav\";

\$configControlPanelURLSav 		= \"$configControlPanelURLSav\";
\$configControlPanelURLLenSav 		= \"$configControlPanelURLLenSav\";
\$configControlPanelURLSchemeSav	= \"$configControlPanelURLSchemeSav\";
\$configControlPanelURLHostSav		= \"$configControlPanelURLHostSav\";
\$configControlPanelURLPortSav		= \"$configControlPanelURLPortSav\";
\$configControlPanelURLPathSav		= \"$configControlPanelURLPathSav\";

// Statisics
\$configSiteUseGethostbyaddrSav = \"$configSiteUseGethostbyaddrSav\";
\$configSiteDaysToKeepPageVisitsSav = \"$configSiteDaysToKeepPageVisitsSav\";

// Test
\$configSiteIsTestSav = \"$configSiteIsTestSav\";

// Admin
\$adminEmailSav = \"$inp_user_email\";
\$adminPasswordSav = \"$inp_user_password\";

// Webdesign
\$webdesignSav = \"$webdesignSav\";

?>";
	$fh = fopen("../../_cache/setup_data.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);


	// Move to admin-panel
	header("Location: index.php?page=07_webdesign&language=$language");
	exit;

}


echo"
<h1>$l_administrator</h1>


<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_user_email\"]').focus();
	});
	</script>
<!-- //Focus -->



<!-- Administrator form -->

	<form method=\"post\" action=\"index.php?page=06_administrator&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">

	<!-- Error -->
		";
		if(isset($ft) && isset($fm)){
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		}
		echo"
	<!-- //Error -->

	<p><b>$l_email:</b><br />
	<input type=\"text\" name=\"inp_user_email\" value=\""; if(isset($inp_user_email)){ echo"$inp_user_email"; } echo"\" size=\"35\" tabindex=\"1\" /></p>

	<p><b>$l_password:</b><br />
	<input type=\"password\" name=\"inp_user_password\" value=\""; if(isset($inp_user_password)){ echo"$inp_user_password"; } echo"\" size=\"35\" tabindex=\"3\" /></p>

	<p>
	<input type=\"submit\" value=\"$l_next\" class=\"submit\" />
	</p>

	</form>

<!-- //Administrator form -->
";
?>

