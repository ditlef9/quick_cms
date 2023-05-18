<?php 
/**
*
* File: recipes/subscribe_to_weekly_recipes_suggestions_unsubscribe.php
* Version 1.0.0
* Date 14:12 12.02.2022
* Copyright (c) 2022 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
// include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['subscription_id'])){
	$subscription_id = $_GET['subscription_id'];
	$subscription_id= strip_tags(stripslashes($subscription_id));
	if(!(is_numeric($subscription_id))){
		echo"subscription_id not numeric";
		die;
	}
}
else{
	echo"Missing subscription id";
	die;
}
$subscription_id_mysql = quote_smart($link, $subscription_id);


if(isset($_GET['key'])){
	$key = $_GET['key'];
	$key = output_html($key);
}
else{
	echo"Missing key";
	die;
}
$key_mysql = quote_smart($link, $key);

// Find 
$query = "SELECT subscription_id, subscription_user_id, subscription_user_email, subscription_user_name, subscription_language, subscription_send_email, subscription_post_blog FROM $t_recipes_weekly_subscriptions WHERE subscription_id=$subscription_id_mysql AND subscription_key=$key_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_subscription_id, $get_current_subscription_user_id, $get_current_subscription_user_email, $get_current_subscription_user_name, $get_current_subscription_language, $get_current_subscription_send_email, $get_current_subscription_post_blog) = $row;

if($get_current_subscription_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_subscription_not_found - $l_recipes";
	include("$root/_webdesign/header.php");

	echo"
	<h1>$l_subscription_not_found</h1>

	<p>$l_did_you_already_unsubscribe</p>

	<p><a href=\"index.php?l=$l\" class=\"btn_default\">$l_recipes</a></p>
	";
}
else{
	// Delete
	mysqli_query($link, "DELETE FROM $t_recipes_weekly_subscriptions WHERE subscription_user_id=$get_current_subscription_user_id") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_user_id=$get_current_subscription_user_id") or die(mysqli_error($link));


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_subscription_removed - $l_recipes";
	include("$root/_webdesign/header.php");

	echo"
	<h1>$l_subscription_removed</h1>

	<p>$l_you_have_now_unsubscribed</p>

	<p><a href=\"index.php?l=$l\" class=\"btn_default\">$l_recipes</a></p>
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>