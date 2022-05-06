<?php 
/**
*
* File: forum/unsubscribe_from_forum.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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


/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['subscription_id'])){
	$subscription_id = $_GET['subscription_id'];
	$subscription_id = output_html($subscription_id);
}
else{
	$subscription_id = "";
}
$subscription_id_mysql = quote_smart($link, $subscription_id);

if(isset($_GET['subscription_user_id'])){
	$subscription_user_id = $_GET['subscription_user_id'];
	$subscription_user_id = output_html($subscription_user_id);
}
else{
	$subscription_user_id = "";
}
$subscription_user_id_mysql = quote_smart($link, $subscription_user_id);


/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_unsubscribe_from_forum - $get_current_title_value";
include("$root/_webdesign/header.php");


// Check if I have subscription for entire board
$query = "SELECT forum_subscription_id, forum_subscription_user_id, forum_subscription_user_email FROM $t_forum_subscriptions WHERE forum_subscription_id=$subscription_id_mysql AND forum_subscription_user_id=$subscription_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_forum_subscription_id, $get_forum_subscription_user_id, $get_forum_subscription_user_email) = $row;

if($get_forum_subscription_id == ""){
	echo"
	<h1>$l_unsubscribe_from_forum</h1>
	<p>$l_subscription_not_found</p>
	";
}
else{
	// Delete
	$result = mysqli_query($link, "DELETE FROM $t_forum_subscriptions WHERE forum_subscription_id='$get_forum_subscription_id'");

	echo"
	<h1>$l_unsubscribe_from_forum</h1>
	<p>$l_subscription_deleted ($get_forum_subscription_user_email)</p>
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>