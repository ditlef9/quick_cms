<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


/*- Config ----------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");
include("../../_admin/_data/config/user_system.php");
include("../../_admin/_data/logo.php");

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


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_users_profile_photo	= $mysqlPrefixSav . "users_profile_photo";
$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";
$t_comments		= $mysqlPrefixSav . "comments";
$t_comments_users_block	= $mysqlPrefixSav . "comments_users_block";






/*- IP Block --------------------------------------------------------------------------- */
if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);
}
else{
	echo"Missing user id";
	die;
}
if(isset($_POST['inp_post_id'])) {
	$inp_post_id = $_POST['inp_post_id'];
	$inp_post_id = strip_tags(stripslashes($inp_post_id));
	$inp_post_id_mysql = quote_smart($link, $inp_post_id);
}
else{
	echo"Missing inp post id";
	die;
}


$my_user_ip = $_SERVER['REMOTE_ADDR'];
$my_user_ip = output_html($my_user_ip);
$my_user_ip_mysql = quote_smart($link, $my_user_ip);

$block_to = date("ymdh");

// Check by user ID
$query = "SELECT block_id FROM $t_comments_users_block WHERE block_user_id=$inp_user_id_mysql AND block_object='blog_post' AND block_object_id=$inp_recipe_id_mysql AND block_to=$block_to";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_block_id) = $row;
if($get_block_id != ""){
	echo"ipblock";
	die;
}
else{
	// Check by user IP
	$query = "SELECT block_id FROM $t_comments_users_block WHERE block_user_ip=$my_user_ip_mysql AND block_object='blog_post' AND block_object_id=$inp_recipe_id_mysql AND block_to=$block_to";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_block_id) = $row;
	if($get_block_id != ""){
		echo"ipblock";
		die;
	}
}



?>