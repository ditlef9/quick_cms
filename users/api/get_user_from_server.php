<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";


/*- Script start --------------------------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
	$user_id_mysql = quote_smart($link, $user_id);


	$rows_array = array();

	// User
	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_gender, user_dob, user_registered, user_rank FROM $t_users WHERE user_id=$user_id_mysql LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$rows_array['user'] = $row;

	// Profile
	$query = "SELECT profile_id, profile_first_name, profile_city, profile_country, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_views, profile_privacy FROM $t_users_profile WHERE profile_user_id=$user_id_mysql LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$rows_array['profile'] = $row;

	// Image
	$query = "SELECT photo_id, photo_profile_image, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql AND photo_profile_image='1' LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$rows_array['photo'] = $row;
	
	// Json everything
	$rows_json = json_encode(utf8ize($rows_array));

	echo"$rows_json";

} // start stop


?>