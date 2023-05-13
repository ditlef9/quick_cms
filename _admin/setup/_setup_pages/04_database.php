<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}

if($action == "test_connection"){

	// MySQL
	$inp_mysql_host = $_POST['inp_mysql_host'];
	$inp_mysql_host = output_html($inp_mysql_host);

	$inp_mysql_user_name = $_POST['inp_mysql_user_name'];
	$inp_mysql_user_name = output_html($inp_mysql_user_name);

	$inp_mysql_password = $_POST['inp_mysql_password'];
	$inp_mysql_password = output_html($inp_mysql_password);
	$inp_mysql_password = str_replace("&amp;", "&", $inp_mysql_password);
	$inp_mysql_password = str_replace("&lt;", "<", $inp_mysql_password);
	$inp_mysql_password = str_replace("&gt;", ">", $inp_mysql_password);

	$inp_mysql_database_name = $_POST['inp_mysql_database_name'];
	$inp_mysql_database_name = output_html($inp_mysql_database_name);

	$inp_mysql_prefix = $_POST['inp_mysql_prefix'];
	$inp_mysql_prefix = output_html($inp_mysql_prefix);
		
	// Try connection
	$mysqli = new mysqli($inp_mysql_host, $inp_mysql_user_name, $inp_mysql_password, $inp_mysql_database_name);
	
	if ($mysqli -> connect_errno) {
		$error = $mysqli -> connect_error;
		$url = "index.php?page=04_database&language=$language&inp_mysql_host=$inp_mysql_host&inp_mysql_user_name=$inp_mysql_user_name&inp_mysql_database_name=$inp_mysql_database_name&inp_mysql_prefix=$inp_mysql_prefix&ft=error&fm=$error&error=$error";
		echo"
		<h1>MySQL Connection failed</h1>
		<p>$error</p>
		<meta http-equiv=refresh content=\"1; url=$url\">";
		exit;
	}
	
	// Write DB file
	$update_file="<?php
// Database
\$mysqlHostSav   	= \"$inp_mysql_host\";
\$mysqlUserNameSav   	= \"$inp_mysql_user_name\";
\$mysqlPasswordSav	= \"$inp_mysql_password\";
\$mysqlDatabaseNameSav 	= \"$inp_mysql_database_name\";
\$mysqlPrefixSav 	= \"$inp_mysql_prefix\";


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
\$adminEmailSav = \"$adminEmailSav\";
\$adminPasswordSav = \"$adminPasswordSav\";

// Webdesign
\$webdesignSav = \"$webdesignSav\";

?>";
	$fh = fopen("../../_cache/setup_data.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);

	echo"
	<h1>MySQL test in progress..</h1>
	<meta http-equiv=refresh content=\"2; url=index.php?page=05_site&language=$language\">";
	exit;
}

echo"
<h1>$l_database</h1>


<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_mysql_host\"]').focus();
	});
	</script>
<!-- //Focus -->


<!-- Database form -->
";
if($action == ""){
	// Check file 
	echo"
	<form method=\"post\" action=\"index.php?page=04_database&amp;language=$language&amp;action=test_connection&amp;process=1\" enctype=\"multipart/form-data\">

	<!-- Error -->
		";
		if(isset($ft) && isset($fm)){
			echo"<div class=\"error\"><p>$fm</p>";
			if(isset($_GET['error_no'])){
				$error_no = $_GET['error_no'];
				$error_no = output_html($error_no);
				echo"<p>$error_no</p>";
			}
			if(isset($_GET['error_or'])){
				$error_or = $_GET['error_or'];
				$error_or = output_html($error_or);
				echo"<p>$error_or</p>";
			}
			echo"</div>";
		}
		echo"
	<!-- //Error -->

	<p><b>$l_host:</b><br />
	<input type=\"text\" name=\"inp_mysql_host\" value=\"$mysqlHostSav\" size=\"35\" tabindex=\"1\" /></p>

	<p><b>$l_username:</b><br />
	<input type=\"text\" name=\"inp_mysql_user_name\" value=\"$mysqlUserNameSav\" size=\"35\" tabindex=\"2\" /></p>

	<p><b>$l_password:</b><br />
	<input type=\"text\" name=\"inp_mysql_password\" value=\"$mysqlPasswordSav\" size=\"35\" tabindex=\"3\" /></p>

	<p><b>$l_database_name:</b><br />
	<input type=\"text\" name=\"inp_mysql_database_name\" value=\"$mysqlDatabaseNameSav\" size=\"35\" tabindex=\"4\" /></p>

	<p><b>$l_prefix:</b><br />
	<input type=\"text\" name=\"inp_mysql_prefix\" value=\"$mysqlPrefixSav\" size=\"35\" tabindex=\"5\" /></p>

	
	<p>
	<input type=\"submit\" value=\"$l_test_connection\" class=\"submit\" />
	</p>

	</form>

	";
}

echo"
<!-- //Database form -->
";
?>

