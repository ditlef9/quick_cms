<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}


/*- Variables ----------------------------------------------------------------------- */
if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}


// 1. Write to MySQL
$update_file="<?php
// Database
\$mysqlHostSav   		= \"$mysqlHostSav\";
\$mysqlUserNameSav   	= \"$mysqlUserNameSav\";
\$mysqlPasswordSav		= \"$mysqlPasswordSav\";
\$mysqlDatabaseNameSav 	= \"$mysqlDatabaseNameSav\";
\$mysqlPrefixSav 		= \"$mysqlPrefixSav\";
?>";

$fh = fopen("../_data/mysql_" . $server_name . ".php", "w+") or die("can not open file");
fwrite($fh, $update_file);
fclose($fh);

// 2. Connect to MySQL
$mysqli = new mysqli($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);

if ($mysqli -> connect_errno) {
	$error = $mysqli -> connect_error;
	echo "
	<div class=\"error\"><p><b>MySQL connection error</b>: $error</p>
	</div>
	";
}

// 3. Create tables
include("_setup_pages/08_write_to_file_include_database_setup_tables.php");

// 4. Create meta
if(!(is_dir("../_data/config/"))){ mkdir("../_data/config/"); } 
	$configSecurityCodeSav = time();
$input_meta="<?php
// General
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
\$configSecurityCodeSav = \"$configSecurityCodeSav\";

// Test
\$configSiteIsTestSav = \"$configSiteIsTestSav\";
?>";

$fh = fopen("../_data/config/meta.php", "w+") or die("can not open file");
fwrite($fh, $input_meta);
fclose($fh);


// 5. Create user config
$input_users_config="<?php
// Users
\$configUsersCanRegisterSav   = \"1\";
\$configUsersAvatarWidthSav   = \"80\";
\$configUsersAvatarHeightSav  = \"80\";
\$configUsersPictureWidthSav  = \"600\";
\$configUsersPictureHeightSav = \"450\";
\$configUsersAllowedMailAddressesSav = \"\";
\$configUsersEmailVerificationSav = \"1\";
\$configUsersHasToBeVerifiedByModeratorSav 	= \"0\";

// Search index
\$configShowUsersOnSearchEngineIndexSav   		= \"0\";
\$configIncludeFirstNameLastNameOnSearchEngineIndexSav	= \"0\";
\$configIncludeProfessionalOnSearchEngineIndexSav	= \"0\";

// View profile
\$configViewProfileIncludeFirstNameLastNameSav   	= \"0\";
\$configViewProfileIncludeProfessionalSav   		= \"0\";
?>";

$fh = fopen("../_data/config/user_system.php", "w+") or die("can not open file");
fwrite($fh, $input_users_config);
fclose($fh);


// 6. Create logo
$input_logo_config="<?php
\$logoPathSav = \"_admin/_design/gfx\";
\$logoFileSav = \"quick_cms_logo.png\";
\$logoFileEmailSav = \"quick_cms_email.png\";
\$logoFilePdfSav = \"quick_cms_pdf.png\";
\$logoFileStampImages1280x720Sav = \"quick_cms_stamp_images_1280x720.png\";
\$logoFileStampImages1920x1080Sav = \"quick_cms_stamp_images_1920x1080.png\";
\$logoFileStampImages2560x1440Sav = \"quick_cms_stamp_images_2560x1440.png\";
\$logoFileStampImages7680x4320Sav = \"quick_cms_stamp_images_1920x1080.png\";
?>";

$fh = fopen("../_data/logo.php", "w+") or die("can not open file");
fwrite($fh, $input_logo_config);
fclose($fh);


// 7. Webdesign
if(isset($_GET['webdesign_name'])) {
	$webdesign_name = $_GET['webdesign_name'];
	$webdesign_name = strip_tags(stripslashes($webdesign_name));
	$webdesign_name = output_html($webdesign_name);
}
else{
	$webdesign_name = "$webdesignSav";
}
$webdesign_file="<?php
\$webdesignSav 	 = \"$webdesign_name\";
?>";

$fh = fopen("../_data/webdesign.php", "w+") or die("can not open file");
fwrite($fh, $webdesign_file);
fclose($fh);

// 8. Insert user
// 8.1 Salt
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$salt = '';
for ($i = 0; $i < 6; $i++) {
      	$salt .= $characters[rand(0, $charactersLength - 1)];
}
$inp_user_salt = "$salt";

// 8.2 user and assword
$inp_user_email = "$adminEmailSav";
$inp_user_password = "$adminPasswordSav";

// 8.3 Security
$year = date("Y");
$pin = rand(0,9999);
$inp_user_security = $year . $pin;


// 8.4 Language
$inp_user_language = output_html($language);
// Old: $inp_user_language_mysql = quote_smart($link, $inp_user_language);

// 8.5 Registered
$datetime = date("Y-m-d H:i:s");
$time = time();
$date_saying = date("j M Y");

// 8.6 Date format
if($language == "no"){
	$inp_user_date_format = "l d. f Y";
}
else{
	$inp_user_date_format = "l jS \of F Y";
}
// Old: $inp_user_date_format_mysql = quote_smart($link, $inp_user_date_format);

// 8.7 Mesurment
if($language == "en"){
	$inp_profile_mesurment = "imperial"; // imperial
}
else{
	$inp_profile_mesurment = "metric"; // metric
}

// 8.8 Insert user
$stmt = $mysqli->prepare("SELECT user_id FROM $t_users WHERE user_email=?"); 
$stmt->bind_param("s", $inp_user_email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($sql_user_id) = $row;
if($sql_user_id == ""){

	$inp_user_name = "quick";
	$inp_user_rank = "admin";
	$inp_user_points = 0;
	$inp_user_points_rank = "Newbie";
	$inp_user_likes = 0;
	$inp_user_dislikes = 0; 
	$inp_user_verified_by_moderator = 1;  
	$inp_user_marked_as_spammer = 0;

	$stmt = $mysqli->prepare("INSERT INTO $t_users
							(user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_measurement, user_date_format, user_registered, user_registered_time, user_registered_date_saying, user_last_online, user_last_online_time, user_rank, user_points, user_points_rank, user_likes, user_dislikes, user_verified_by_moderator, user_marked_as_spammer) 
							VALUES 
							(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("sssssssssssssssssssss", $inp_user_email, $inp_user_name, $inp_user_name, $inp_user_password, $inp_user_salt, $inp_user_security, $inp_user_language, $inp_profile_mesurment, $inp_user_date_format, $datetime, $time, $date_saying, $datetime, $time, $inp_user_rank, $inp_user_points, $inp_user_points_rank, $inp_user_likes, $inp_user_dislikes, $inp_user_verified_by_moderator, $inp_user_marked_as_spammer); 
	$stmt->execute();
}

// 8.9 Get user id
$stmt = $mysqli->prepare("SELECT user_id FROM $t_users WHERE user_email=?"); 
$stmt->bind_param("s", $inp_user_email);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$row = $result->fetch_row(); // fetch data
list($sql_user_id) = $row;



// 8.10 Setup email notifications
$inp_type = "friend_request";
$stmt = $mysqli->prepare("INSERT INTO $t_users_email_subscriptions
						(es_id, es_user_id, es_type, es_on_off) 
						VALUES 
						(NULL, ?, ?, 1)");
$stmt->bind_param("ss", $sql_user_id, $inp_type); 
$stmt->execute();

$inp_type = "status_comments";
$stmt = $mysqli->prepare("INSERT INTO $t_users_email_subscriptions
						(es_id, es_user_id, es_type, es_on_off) 
						VALUES 
						(NULL, ?, ?, 1)");
$stmt->bind_param("ss", $sql_user_id, $inp_type); 
$stmt->execute();




// 9. Login user
$_SESSION['user_id'] = "$sql_user_id";
$_SESSION['security'] = "$inp_user_security";
$_SESSION['admin_user_id']  = "$sql_user_id";
$_SESSION['admin_security'] = "$inp_user_security";

// 10. Write setup finished
$fh = fopen("../_data/$setup_finished_file", "w+") or die("can not open file");
fwrite($fh, "$cmsVersionSav");
fclose($fh);


// 11. Move to liquidbase
header("Location: ../_liquidbase/liquidbase.php?l=$language");
exit;
?>
