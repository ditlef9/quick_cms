<?php 
/**
*
* File: forum/subscription.php
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
include("$root/_admin/_translations/site/$l/forum/ts_new_topic.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);





/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_subscription - $get_current_title_value";
include("$root/_webdesign/header.php");


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	// Check if I have subscription for entire board
	$query = "SELECT forum_subscription_id, forum_subscription_user_id, forum_subscription_user_email FROM $t_forum_subscriptions WHERE forum_subscription_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_forum_subscription_id, $get_forum_subscription_user_id, $get_forum_subscription_user_email) = $row;

	if($action == ""){
		if($process == "1"){
			$inp_subscribe_to_all_new_topics = $_POST['inp_subscribe_to_all_new_topics'];
			$inp_subscribe_to_all_new_topics = output_html($inp_subscribe_to_all_new_topics);

			if($inp_subscribe_to_all_new_topics == "1"){
				if($get_forum_subscription_id == ""){
					// Insert
					$inp_email_mysql = quote_smart($link, $get_my_user_email);
					$datetime = date("Y-m-d H:i:s");
					$time = time();

					mysqli_query($link, "INSERT INTO $t_forum_subscriptions
					(forum_subscription_id, forum_subscription_user_id, forum_subscription_user_email, forum_subscription_last_sendt_datetime, forum_subscription_last_sendt_time) 
					VALUES 
					(NULL, $my_user_id_mysql, $inp_email_mysql, '$datetime', '$time')")
					or die(mysqli_error($link));
				}
			}
			else{
				if($get_forum_subscription_id != ""){
					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_forum_subscriptions WHERE forum_subscription_id='$get_forum_subscription_id'");
				}
			}
			$url = "subscription.php?l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$l_subscription</h1>


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<form method=\"post\" action=\"subscription.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_subscribe_to_all_new_topics</b><br />
		<input type=\"radio\" name=\"inp_subscribe_to_all_new_topics\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($get_forum_subscription_id != ""){ echo" checked=\"checked\"";}echo" />
		$l_yes
		&nbsp;
		<input type=\"radio\" name=\"inp_subscribe_to_all_new_topics\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($get_forum_subscription_id == ""){ echo" checked=\"checked\"";}echo" />
		$l_no
		</p>
		
		<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>
		
		<p>
		<a href=\"index.php?l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
		<a href=\"index.php?l=$l\">$get_current_title_value</a>
		</p>
		";
	} // action == ""
}
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=forum/subscription.php\">
	";

} // not logged in



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>