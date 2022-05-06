<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}

/*- Translations --------------------------------------------------------------------- */
include("../_translations/admin/$language/settings/t_ssl.php");


/*- Include SSL config --------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name_saying = ucfirst($server_name);
$server_name = clean($server_name);

$ssl_config_file = "ssl_" . $server_name . ".php";
if(file_exists("../_data/config/$ssl_config_file")){
	include("../_data/config/$ssl_config_file");
}
else{
	$configSLLActiveSav = "0";
}



/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


if($process == "1"){
	$inp_site_title = $_POST['inp_site_title'];
	$inp_site_title = output_html($inp_site_title);
	if($inp_site_title == ""){
		$url = "index.php?page=05_site&language=$language&ft=error&fm=title_is_empty";
		header("Location: $url");
		exit;
	}

	$inp_site_title_clean = clean($inp_site_title);

	// Write DB file
	$update_file="<?php
// Database
\$mysqlHostSav   	= \"$mysqlHostSav\";
\$mysqlUserNameSav   	= \"$mysqlUserNameSav\";
\$mysqlPasswordSav	= \"$mysqlPasswordSav\";
\$mysqlDatabaseNameSav 	= \"$mysqlDatabaseNameSav\";
\$mysqlPrefixSav 	= \"$mysqlPrefixSav\";


// General
\$configWebsiteTitleSav		 = \"$inp_site_title\";
\$configWebsiteTitleCleanSav	 = \"$inp_site_title_clean\";
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
\$adminEmailSav = \"$adminEmailSav\";
\$adminPasswordSav = \"$adminPasswordSav\";

// Webdesign
\$webdesignSav = \"$webdesignSav\";

?>";
		$fh = fopen("../../_cache/setup_data.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);

	// Move to administrator setup
	header("Location: index.php?page=06_administrator&language=$language");
	exit;

}

echo"
<h1>$l_site</h1>


<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_site_title\"]').focus();
	});
	</script>
<!-- //Focus -->


<!-- Site form -->
	<form method=\"post\" action=\"index.php?page=05_site&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">

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

	<p><b>$l_site_title:</b><br />
	<input type=\"text\" name=\"inp_site_title\" value=\"$configWebsiteTitleSav\" size=\"35\" tabindex=\"1\" /></p>

	<p>
	<input type=\"submit\" value=\"$l_next\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	</form>

<!-- //Site form -->
";
?>
