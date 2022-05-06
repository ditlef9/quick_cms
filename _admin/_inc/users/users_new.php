<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";


$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";

/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Config ---------------------------------------------------------------------------- */
include("_data/logo.php");
include("_data/config/user_system.php");

/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = strip_tags(stripslashes($refer));
}
else{
	$refer = "";
}

// Can I edit?
$my_user_id = $_SESSION['admin_user_id'];
$my_user_id = output_html($my_user_id);
$my_user_id_mysql = quote_smart($link, $my_user_id);

$my_security  = $_SESSION['admin_security'];
$my_security = output_html($my_security);
$my_security_mysql = quote_smart($link, $my_security);
$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;

if($process == "1"){

	$inp_user_email = $_POST['inp_user_email'];
	$inp_user_email = output_html($inp_user_email);
	$inp_user_email = strtolower($inp_user_email);
	$inp_user_email_mysql = quote_smart($link, $inp_user_email);
			
	$inp_user_name = $_POST['inp_user_name'];
	$inp_user_name = output_html($inp_user_name);
	$inp_user_name = ucfirst($inp_user_name);
	$inp_user_name_mysql = quote_smart($link, $inp_user_name);

	$inp_user_language = $_POST['inp_user_language'];
	$inp_user_language = output_html($inp_user_language);
	$inp_user_language_mysql = quote_smart($link, $inp_user_language);

	$inp_user_rank = $_POST['inp_user_rank'];
	$inp_user_rank = output_html($inp_user_rank);
	$inp_user_rank_mysql = quote_smart($link, $inp_user_rank);

	$inp_user_first_name = $_POST['inp_user_first_name'];
	$inp_user_first_name = output_html($inp_user_first_name);
	$inp_user_first_name = ucwords($inp_user_first_name);
	$inp_user_first_name_mysql = quote_smart($link, $inp_user_first_name);

	$inp_user_middle_name = $_POST['inp_user_middle_name'];
	$inp_user_middle_name = output_html($inp_user_middle_name);
	$inp_user_middle_name = ucwords($inp_user_middle_name);
	$inp_user_middle_name_mysql = quote_smart($link, $inp_user_middle_name);

	$inp_user_last_name = $_POST['inp_user_last_name'];
	$inp_user_last_name = output_html($inp_user_last_name);
	$inp_user_last_name = ucwords($inp_user_last_name);
	$inp_user_last_name_mysql = quote_smart($link, $inp_user_last_name);
	
	// Check empty email
	if(empty($inp_user_email)){
		$ft = "warning";
		$fm = "please_enter_a_email_address";
		$url = "index.php?open=$open&page=users_new&ft=$ft&fm=$fm";
		$url = $url . "&inp_user_email=$inp_user_email";
		$url = $url . "&inp_user_name=$inp_user_name";
		$url = $url . "&inp_user_language=$inp_user_language";
		$url = $url . "&inp_user_rank=$inp_user_rank";
		$url = $url . "&inp_user_first_name=$inp_user_first_name";
		$url = $url . "&inp_user_middle_name=$inp_user_middle_name";
		$url = $url . "&inp_user_last_name=$inp_user_last_name";
		$url = $url . "&editor_language=$editor_language";

		header("Location: $url");
		exit;
	}


	// Check if new email is taken
	$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($check_user_id, $check_user_email, $check_user_name) = $row;
	if($check_user_id != ""){
		$fm = "email_alreaddy_in_use";
		$ft = "warning";
		$url = "index.php?open=$open&page=users_new&ft=$ft&fm=$fm";
		$url = $url . "&inp_user_name=$inp_user_name";
		$url = $url . "&inp_user_language=$inp_user_language";
		$url = $url . "&inp_user_rank=$inp_user_rank";
		$url = $url . "&inp_user_first_name=$inp_user_first_name";
		$url = $url . "&inp_user_middle_name=$inp_user_middle_name";
		$url = $url . "&inp_user_last_name=$inp_user_last_name";
		$url = $url . "&editor_language=$editor_language";

		header("Location: $url");
		exit;
	}
	// Check empty user name
	if(empty($inp_user_name)){
		$ft = "warning";
		$fm = "please_enter_a_user_name";
		$url = "index.php?open=$open&page=users_new&ft=$ft&fm=$fm";
		$url = $url . "&inp_user_email=$inp_user_email";
		$url = $url . "&inp_user_language=$inp_user_language";
		$url = $url . "&inp_user_rank=$inp_user_rank";
		$url = $url . "&inp_user_first_name=$inp_user_first_name";
		$url = $url . "&inp_user_middle_name=$inp_user_middle_name";
		$url = $url . "&inp_user_last_name=$inp_user_last_name";
		$url = $url . "&editor_language=$editor_language";

		header("Location: $url");
		exit;
	}

	// Check if new username is taken
	$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_name=$inp_user_name_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($check_user_id, $check_user_email, $check_user_name) = $row;
	if($check_user_id != ""){
		$fm = "user_name_alreaddy_in_use";
		$ft = "warning";
		$url = "index.php?open=$open&page=users_new&ft=$ft&fm=$fm";
		$url = $url . "&inp_user_email=$inp_user_email";
		$url = $url . "&inp_user_name=$inp_user_name";
		$url = $url . "&inp_user_language=$inp_user_language";
		$url = $url . "&inp_user_rank=$inp_user_rank";
		$url = $url . "&inp_user_first_name=$inp_user_first_name";
		$url = $url . "&inp_user_middle_name=$inp_user_middle_name";
		$url = $url . "&inp_user_last_name=$inp_user_last_name";
		$url = $url . "&editor_language=$editor_language";

		header("Location: $url");
		exit;
	}

	// Create salt
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$salt = '';
    	for ($i = 0; $i < 6; $i++) {
        	$salt .= $characters[rand(0, $charactersLength - 1)];
    	}
	$inp_user_salt = output_html($salt);
	$inp_user_salt_mysql = quote_smart($link, $inp_user_salt);


	// Create password
	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = output_html($inp_user_password);



	$inp_user_password_salt = $inp_user_password . $inp_user_salt;
	$inp_user_password_salt_encrypted = sha1($inp_user_password_salt);
	$inp_user_password_mysql = quote_smart($link, $inp_user_password_salt_encrypted);

	// Security
	$inp_user_security = rand(0,9999);

	// Registered
	$datetime = date("Y-m-d H:i:s");
	$datetime_saying = date("j M Y H:i");
	$date = date("Y-m-d");
	$date_saying = date("j M Y");
	$time = time();

	// Date format
	if($l == "no"){
		$inp_user_date_format = "l d. f Y";
	}
	else{
		$inp_user_date_format = "l jS \of F Y";
	}
	$inp_user_date_format_mysql = quote_smart($link, $inp_user_date_format);

	$inp_user_timezone_utc_diff_mysql = quote_smart($link, $get_my_user_timezone_utc_diff);
	$inp_user_timezone_value_mysql = quote_smart($link, $get_my_user_timezone_value);

	$inp_user_mesurment_mysql = quote_smart($link, $get_my_user_mesurment);

	// Insert user
	mysqli_query($link, "INSERT INTO $t_users
	(user_id, user_email, user_name, user_alias, user_password, 
	user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, 
	user_middle_name, user_last_name, user_language, user_timezone_utc_diff, user_timezone_value, 
	user_measurement, user_date_format, user_registered, user_registered_time, user_registered_date_saying, user_newsletter, 
	user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, 
	user_likes, user_dislikes, user_status, user_login_tries, user_last_online, 
	user_last_online_time, user_synchronized, user_notes, user_marked_as_spammer) 
	VALUES 
	(NULL, $inp_user_email_mysql, $inp_user_name_mysql, $inp_user_name_mysql, $inp_user_password_mysql, 
	$inp_user_salt_mysql, '$inp_user_security', $inp_user_rank_mysql, 1, $inp_user_first_name_mysql, 
	$inp_user_middle_name_mysql,  $inp_user_last_name_mysql, $inp_user_language_mysql, $inp_user_timezone_utc_diff_mysql, $inp_user_timezone_value_mysql, 
	$inp_user_mesurment_mysql, $inp_user_date_format_mysql, '$datetime', '$time', '$date_saying', 0, 
	'public', 0, '', 0, 'Newbie',
	0, 0, '', 0, '$datetime', 
	'$time', 0, '', 0)")
	or die(mysqli_error($link));


	// Get user id
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;
			

	// Link
	$pageURL = 'http';
	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	$link_admin = str_replace("index.php?open=$open&page=users_new&process=1", "", $pageURL);
	$link_site = str_replace("_admin/index.php?open=$open&page=users_new&process=1", "", $pageURL);



	// Send e-mail
	$host = $_SERVER['HTTP_HOST'];
	$from = "no-reply@" . $_SERVER['HTTP_HOST'];
	$reply = "post@" . $_SERVER['HTTP_HOST'];

	$subject = "Username and password for " . $host;
	$message = "Hello $inp_profile_first_name $inp_profile_last_name,\n\nWelcome to $host. This e-mail contains your username and password. Once logged in you can change your password.\n\nUsername: $inp_user_email\nPassword: $inp_user_password\nControl panel: $link_admin\nSite: $link_site\n\n---\n" . $host;

	$headers = "From: $from" . "\r\n" .
	    "Reply-To: $reply" . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	if($configMailSendActiveSav == "1"){
		mail($inp_user_email, $subject, $message, $headers);
	}

	// Search engine
	if($configShowUsersOnSearchEngineIndexSav == "1"){
		// Title
		include("_translations/site/$l/users/ts_users.php");

		$inp_index_title = "$inp_user_name | $l_users";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "users/view_profile.php?user_id=$get_user_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		mysqli_query($link, "INSERT INTO $t_search_engine_index 
		(index_id, index_title, index_url, index_short_description, index_keywords, 
		index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
		index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
		index_unique_hits) 
		VALUES 
		(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
		'users', 'users', '0', 'user_id', '$get_user_id',
		'0', '0', '$datetime', '$datetime_saying', '',
		0)")
		or die(mysqli_error($link));
	}

	// Header
	$url = "index.php?open=$open&page=users_new&ft=success&fm=user_created_and_mail_sent&editor_language=$editor_language";
	header("Location: $url");
	exit;
}

// Get variables from form
if(isset($_GET['inp_user_email'])) {
	$inp_user_email = $_GET['inp_user_email'];
	$inp_user_email = output_html($inp_user_email);
}
if(isset($_GET['inp_user_name'])) {
	$inp_user_name = $_GET['inp_user_name'];
	$inp_user_name = output_html($inp_user_name);
}
if(isset($_GET['inp_user_language'])) {
	$inp_user_language = $_GET['inp_user_language'];
	$inp_user_language = output_html($inp_user_language);
}
if(isset($_GET['inp_user_password'])) {
	$inp_user_password = $_GET['inp_user_password'];
	$inp_user_password = output_html($inp_user_password);
}
else{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$password = '';
    	for ($i = 0; $i < 8; $i++) {
        	$password .= $characters[rand(0, $charactersLength - 1)];
    	}
	$inp_user_password = output_html($password);

}
if(isset($_GET['inp_user_rank'])) {
	$inp_user_rank = $_GET['inp_user_rank'];
	$inp_user_rank = output_html($inp_user_rank);
}
if(isset($_GET['inp_user_first_name'])) {
	$inp_user_first_name = $_GET['inp_user_first_name'];
	$inp_user_first_name = output_html($inp_user_first_name);
}
if(isset($_GET['inp_user_middle_name'])) {
	$inp_user_middle_name = $_GET['inp_user_middle_name'];
	$inp_user_middle_name = output_html($inp_user_middle_name);
}
if(isset($_GET['inp_user_last_name'])) {
	$inp_user_last_name = $_GET['inp_user_last_name'];
	$inp_user_last_name = output_html($inp_user_last_name);
}


echo"
<h1>$l_new_user</h1>

<form method=\"POST\" action=\"index.php?open=$open&amp;page=users_new&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">

<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "email_alreaddy_in_use"){
			$fm = "$l_email_alreaddy_in_use";
		}
		elseif($fm == "please_enter_a_email_address"){
			$fm = "$l_please_enter_a_email_address";
		}
		elseif($fm == "user_name_alreaddy_in_use"){
			$fm = "$l_user_name_alreaddy_in_use";
		}
		elseif($fm == "please_enter_a_user_name"){
			$fm = "$l_please_enter_a_user_name";
		}
		elseif($fm == "user_created_and_mail_sent"){
			$fm = "$l_user_created_and_mail_sent";
		}
		elseif($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = "$ft";
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
<!-- //Feedback -->


<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_user_first_name\"]').focus();
	});
	</script>
<!-- //Focus -->

<p>
$l_first_name:<br />
<input type=\"text\" name=\"inp_user_first_name\" size=\"78\" value=\""; if(isset($inp_user_first_name)){ echo"$inp_user_first_name"; } echo"\" /><br />
</p>

<p>
$l_middle_name:<br />
<input type=\"text\" name=\"inp_user_middle_name\" size=\"78\" value=\""; if(isset($inp_user_middle_name)){ echo"$inp_user_middle_name"; } echo"\" /><br />
</p>

<p>
$l_last_name:<br />
<input type=\"text\" name=\"inp_user_last_name\" size=\"78\" value=\""; if(isset($inp_user_last_name)){ echo"$inp_user_last_name"; } echo"\" /><br />
</p>

<p>
$l_email_address:<br />
<input type=\"text\" name=\"inp_user_email\" size=\"78\" value=\""; if(isset($inp_user_email)){ echo"$inp_user_email"; } echo"\" /><br />
</p>

<p>
$l_user_name:<br />
<input type=\"text\" name=\"inp_user_name\" size=\"78\" value=\""; if(isset($inp_user_name)){ echo"$inp_user_name"; } echo"\" /><br />
</p>

<p>
Password:<br />
<input type=\"text\" name=\"inp_user_password\" size=\"78\" value=\""; if(isset($inp_user_password)){ echo"$inp_user_password"; } echo"\" /><br />
</p>


<p>
$l_language:<br />
<select name=\"inp_user_language\">\n";
$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active ORDER BY language_active_name ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;



			echo"			";
			echo"<option value=\"$get_language_active_iso_two\""; 
			if(isset($inp_user_language)){
				if($inp_user_language == "$inp_user_language"){ echo" selected=\"selected\""; } 
			}
			else{
				if($get_language_active_iso_two == "$get_my_user_language"){ echo" selected=\"selected\""; } 
			}
			echo">$get_language_active_name</option>\n";
}
echo"
</select>
</p>

<p>
$l_rank:<br />
<select name=\"inp_user_rank\">";
if($get_my_user_rank == "admin"){
	echo"<option value=\"admin\""; if(isset($inp_user_rank) && $inp_user_rank == "admin"){ echo" selected=\"selected\""; } echo">$l_admin</option>\n";
	echo"<option value=\"moderator\""; if(isset($inp_user_rank) && $inp_user_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
	echo"<option value=\"editor\""; if(isset($inp_user_rank) && $inp_user_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor</option>\n";
	echo"<option value=\"trusted\""; if(isset($inp_user_rank) && $inp_user_rank == "trusted"){ echo" selected=\"selected\""; } echo">$l_trusted</option>\n";
	echo"<option value=\"user\""; if(isset($inp_user_rank) && $inp_user_rank == "user"){ echo" selected=\"selected\""; } echo">$l_user</option>\n";
}
elseif($get_my_user_rank == "moderator"){
	echo"<option value=\"moderator\""; if(isset($inp_user_rank) && $inp_user_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
	echo"<option value=\"editor\""; if(isset($inp_user_rank) && $inp_user_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor</option>\n";
	echo"<option value=\"trusted\""; if(isset($inp_user_rank) && $inp_user_rank == "trusted"){ echo" selected=\"selected\""; } echo">$l_trusted</option>\n";
	echo"<option value=\"user\""; if(isset($inp_user_rank) && $inp_user_rank == "user"){ echo" selected=\"selected\""; } echo">$l_user</option>\n";
}
echo"
</select>
</p>

<p>
<input type=\"submit\" value=\"$l_create_user\" class=\"btn btn-success btn-sm\" />
</p>
</form>

<!-- Go back -->
	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/navigate_before_outline_black_18x18.png\" alt=\"navigate_before_outline_black_18x18.png\" /></a>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">$l_go_back</a>
	</p>
<!-- Go back -->
";
?>