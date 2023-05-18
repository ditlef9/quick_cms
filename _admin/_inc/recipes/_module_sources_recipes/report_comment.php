<?php 
/**
*
* File: recipes/report_comment.php
* Version 2.0.0
* Date 22:33 05.02.2019
* Copyright (c) 2019 Localhost
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
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['comment_id'])) {
	$comment_id = $_GET['comment_id'];
	$comment_id = strip_tags(stripslashes($comment_id));
}
else{
	$comment_id = "";
}
$l_mysql = quote_smart($link, $l);


/*- Get comment ------------------------------------------------------------------------- */
// Select
$comment_id_mysql = quote_smart($link, $comment_id);
$query = "SELECT comment_id, comment_recipe_id, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_recipes_comments WHERE comment_id=$comment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_comment_id, $get_current_comment_recipe_id, $get_current_comment_language, $get_current_comment_approved, $get_current_comment_datetime, $get_current_comment_time, $get_current_comment_date_print, $get_current_comment_user_id, $get_current_comment_user_alias, $get_current_comment_user_image_path, $get_current_comment_user_image_file, $get_current_comment_user_ip, $get_current_comment_user_hostname, $get_current_comment_user_agent, $get_current_comment_title, $get_current_comment_text, $get_current_comment_rating, $get_current_comment_helpful_clicks, $get_current_comment_useless_clicks, $get_current_comment_marked_as_spam, $get_current_comment_spam_checked, $get_current_comment_spam_checked_comment) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
if($get_current_comment_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$l_report_comment $get_current_comment_title - $l_recipes";
}

if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Check access to comment
if(isset($_SESSION['user_id'])){
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	if($get_current_comment_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Comment not found.</p>
		";
	}
	else{
		
		// Find recipe
		$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_ingredient_id, recipe_ingredient_title, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_current_comment_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_recipe_id, $get_current_recipe_user_id, $get_current_recipe_title, $get_current_recipe_category_id, $get_current_recipe_language, $get_current_recipe_country, $get_current_recipe_introduction, $get_current_recipe_directions, $get_current_recipe_image_path, $get_current_recipe_image_h_a, $get_current_recipe_image_h_b, $get_current_recipe_image_v_a, $get_current_recipe_thumb_h_a_278x156, $get_current_recipe_thumb_h_b_278x156, $get_current_recipe_video_h, $get_current_recipe_video_v, $get_current_recipe_date, $get_current_recipe_date_saying, $get_current_recipe_time, $get_current_recipe_cusine_id, $get_current_recipe_season_id, $get_current_recipe_occasion_id, $get_current_recipe_ingredient_id, $get_current_recipe_ingredient_title, $get_current_recipe_marked_as_spam, $get_current_recipe_unique_hits, $get_current_recipe_unique_hits_ip_block, $get_current_recipe_comments, $get_current_recipe_times_favorited, $get_current_recipe_user_ip, $get_current_recipe_notes, $get_current_recipe_password, $get_current_recipe_last_viewed, $get_current_recipe_age_restriction, $get_current_recipe_published) = $row;

		if($get_current_comment_marked_as_spam == "1"){
			echo"
			<h1>$l_already_reported</h1>

			<p>$l_the_comment_has_been_reported_before.</p>

			<p>
			<a href=\"view_recipe.php?recipe_id=$get_current_recipe_id&amp;l=$l\">$get_current_recipe_title</a>
			</p>
			";

		}
		else{


			if($process == "1"){

				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				if(empty($inp_text)){
					$url = "report_comment.php?comment_id=$get_current_comment_id&l=$l&ft=error&fm=missing_text&inp_title=$inp_title&inp_rating=$inp_rating";
					header("Location: $url");
					exit;
				}

	

				// Datetime and time
				$datetime = date("Y-m-d H:i:s");

				// Datetime print
				$year = substr($datetime, 0, 4);
				$month = substr($datetime, 5, 2);
				$day = substr($datetime, 8, 2);

				if($day < 10){
					$day = substr($day, 1, 1);
				}
		
				if($month == "01"){
					$month_saying = $l_january;
				}
				elseif($month == "02"){
					$month_saying = $l_february;
				}
				elseif($month == "03"){
					$month_saying = $l_march;
				}
				elseif($month == "04"){
					$month_saying = $l_april;
				}
				elseif($month == "05"){
					$month_saying = $l_may;
				}
				elseif($month == "06"){
					$month_saying = $l_june;
				}
				elseif($month == "07"){
					$month_saying = $l_july;
				}
				elseif($month == "08"){
					$month_saying = $l_august;
				}
				elseif($month == "09"){
					$month_saying = $l_september;
				}
				elseif($month == "10"){
					$month_saying = $l_october;
				}
				elseif($month == "11"){
					$month_saying = $l_november;
				}
				else{
					$month_saying = $l_december;
				}

				$inp_comment_date_print = "$day $month_saying $year";


	
				// Ip 
				$inp_ip = $_SERVER['REMOTE_ADDR'];
				$inp_ip = output_html($inp_ip);
				$inp_ip_mysql = quote_smart($link, $inp_ip);

				$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$inp_hostname = output_html($inp_hostname);
				$inp_hostname_mysql = quote_smart($link, $inp_hostname);

				$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$inp_user_agent = output_html($user_agent);
				$inp_user_agent_mysql = quote_smart($link, $user_agent);



				$result = mysqli_query($link, "UPDATE $t_recipes_comments SET 
					comment_marked_as_spam='1',
					comment_spam_checked_comment=$inp_text_mysql  
					WHERE comment_id=$get_current_comment_id") or die(mysqli_error($link));

				// Send report by email to moderators

				// Email to moderators
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;



					$subject = "$l_recipe_comment_reported: $get_recipe_title ($inp_comment_date_print)";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
					$message = $message . "<h1>$l_recipe_comment_reported</h1>\n\n";

					$message = $message . "<p><b>Recipe:</b><br />\n";
					$message = $message . "Recipe ID: <a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l\">$get_recipe_id</a><br />\n";
					$message = $message . "Recipe title: $get_recipe_title<br />\n";
					$message = $message . "</p>\n";

					$message = $message . "<p><b>Comment:</b><br />\n";
					$message = $message . "URL: <a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l#comment$get_comment_id\">$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l#comment$get_comment_id</a><br />\n";
					$message = $message . "Comment ID: $get_current_comment_id<br />\n";
					$message = $message . "Language: $get_current_comment_language<br />\n";
					$message = $message . "Datetime: $get_current_comment_datetime<br />\n";
					$message = $message . "User ID: <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_current_comment_user_id\">$get_current_comment_user_id</a><br />\n";
					$message = $message . "Alias: $get_current_comment_user_alias<br />\n";
					$message = $message . "IP: $get_current_comment_user_ip<br />\n";
					$message = $message . "Hostname: $get_current_comment_user_hostname<br />\n";
					$message = $message . "User agent: $get_current_comment_user_agent<br />\n";
					$message = $message . "Title: $get_current_comment_title<br />\n";
					$message = $message . "Rating: $get_current_comment_rating<br />\n";
					$message = $message . "Text: $get_current_comment_text\n";
					$message = $message . "</p>\n";

					$message = $message . "<p><b>Reporter:</b><br />\n";
					$message = $message . "Language: $l<br />\n";
					$message = $message . "Datetime: $datetime<br />\n";
					$message = $message . "User ID: <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_id</a><br />\n";
					$message = $message . "Email: $get_my_user_email<br />\n";
					$message = $message . "Alias: $get_my_user_alias ($get_my_user_name)<br />\n";
					$message = $message . "IP: $inp_ip <br />\n";
					$message = $message . "Hostname: $inp_hostname<br />\n";
					$message = $message . "User agent: $inp_user_agent <br />\n";
					$message = $message . "Text: $inp_text\n";
					$message = $message . "</p>\n";




					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$get_mod_user_name at $configWebsiteTitleSav<br />\n";
					$message = $message . "<a href=\"$configSiteURLSav/index.php?l=$l\">$configSiteURLSav</a></p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";

					// Preferences for Subject field
					$headers_mail_mod = array();
					$headers_mail_mod[] = 'MIME-Version: 1.0';
					$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
					$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";


					mail($get_mod_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));
					
				}

				$url = "view_recipe.php?recipe_id=$get_current_recipe_id&l=$l&ft=success&fm=report_sent";
				header("Location: $url");
				exit;


			}
			echo"
			<h1>$get_current_recipe_title</h1>

			
			<!-- Where am I? -->
				<p>$l_you_are_here:<br />
				<a href=\"index.php?l=$l\">$l_recipes</a>
				&gt;
				<a href=\"view_recipe.php?recipe_id=$get_current_recipe_id&amp;l=$l\">$get_current_recipe_title</a>
				&gt;
				<a href=\"report_comment.php?comment_id=$get_current_comment_id&amp;l=$l\">$l_report_comment</a>
				</p>
			<!-- //Where am I? -->

			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->


			<!-- Edit comment form -->

				<form method=\"post\" action=\"report_comment.php?comment_id=$get_current_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_text\"]').focus();
					});
					</script>
				<!-- //Focus -->
				
				<p><b>$l_reason_for_report:</b><br />
				<textarea name=\"inp_text\" rows=\"8\" cols=\"30\" style=\"width: 80%;\"></textarea>
				</p>

				<p>
				<input type=\"submit\" value=\"$l_send\" class=\"btn_default\" />
				</p>
				</form>
			<!-- //Edit comment form -->
			";

		} // access
		
	} // Comment found
} // logged in
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/edit_comment.php?comment_id=$comment_id\">
	";	
} // not logged in

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>