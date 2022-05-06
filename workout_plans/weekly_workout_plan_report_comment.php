<?php 
/**
*
* File: workout_plans/weekly_workout_plan_report_comment.php
* Version 1.0.0
* Date 13:24 07.05.2021
* Copyright (c) 2011-2021 S. A. Ditlefsen
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
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['comment_id'])){
	$comment_id = $_GET['comment_id'];
	$comment_id = output_html($comment_id);
	if(!(is_numeric($comment_id))){
		echo"Comment id not found";
		die;
	}

}
else{
	$comment_id = "";
}


$tabindex = 0;
$l_mysql = quote_smart($link, $l);


// Get comment
$comment_id_mysql = quote_smart($link, $comment_id);
$query = "SELECT comment_id, comment_plan_id, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_name, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_reported, comment_reported_by_user_id, comment_reported_reason, comment_report_checked, comment_report_checked_comment FROM $t_workout_plans_weekly_comments WHERE comment_id=$comment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_comment_id, $get_current_comment_plan_id, $get_current_comment_language, $get_current_comment_approved, $get_current_comment_datetime, $get_current_comment_time, $get_current_comment_date_print, $get_current_comment_user_id, $get_current_comment_user_name, $get_current_comment_user_alias, $get_current_comment_user_image_path, $get_current_comment_user_image_file, $get_current_comment_user_ip, $get_current_comment_user_hostname, $get_current_comment_user_agent, $get_current_comment_title, $get_current_comment_text, $get_current_comment_rating, $get_current_comment_helpful_clicks, $get_current_comment_useless_clicks, $get_current_comment_reported, $get_current_comment_reported_by_user_id, $get_current_comment_reported_reason, $get_current_comment_report_checked, $get_current_comment_report_checked_comment) = $row;
if($get_current_comment_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_workout_plans";
	include("$root/_webdesign/header.php");
	echo"<h1>Server error 404</h1><p>Comment not found</p>";
	include("$root/_webdesign/footer.php");
	
}
else{
	// Get workout plan
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_text, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$get_current_comment_plan_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_text, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;



	// Logged in?
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		// Get my profile
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;


		// Get my photo
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination) = $row;

		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_report_comment - $get_current_workout_weekly_title - $l_workout_plans";
		include("$root/_webdesign/header.php");

		// Is alreaddy reported?
		if($get_current_comment_reported == "1"){
			echo"
			<p>
			$l_the_comment_is_reported.
			</p>";
		}
		else{
			/*- Content ---------------------------------------------------------------------------------- */
			if($process == "1"){
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				if(empty($inp_text)){
					$url = "weekly_workout_plan_report_comment.php?comment_id=$get_current_comment_id&l=$l&ft=error&fm=missing_text";
					header("Location: $url");
					exit;
				}

				// Ip 
				$inp_ip = $_SERVER['REMOTE_ADDR'];
				$inp_ip = output_html($inp_ip);
				$inp_ip_mysql = quote_smart($link, $inp_ip);

				$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$inp_hostname = output_html($inp_hostname);
				$inp_hostname_mysql = quote_smart($link, $inp_hostname);

				$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$inp_user_agent = output_html($inp_user_agent);
				$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);

				mysqli_query($link, "UPDATE $t_workout_plans_weekly_comments SET 

							comment_reported=1, 
							comment_reported_by_user_id=$get_my_user_id, 	
							comment_reported_reason=$inp_text_mysql, 
							comment_report_checked=0
							WHERE comment_id=$get_current_comment_id") or die(mysqli_error($link));
				

				// Header
				$url = "weekly_workout_plan_view.php?weekly_id=$get_current_workout_weekly_id&l=$l&ft=success&fm=report_sent#comment$get_current_comment_id";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$l_report_comment</h1>
	


			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_workout_plans</a>
				&gt;
				";
				// Have period parent?
				if($get_current_workout_weekly_period_id != 0){
					$query = "SELECT workout_period_id, workout_period_yearly_id, workout_period_title FROM $t_workout_plans_period WHERE workout_period_id=$get_current_workout_weekly_period_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_workout_period_id, $get_current_workout_period_yearly_id, $get_current_workout_period_title) = $row;

					// Have yearly parent?
					if($get_current_workout_period_yearly_id != 0){
						$query = "SELECT workout_yearly_id, workout_yearly_title FROM $t_workout_plans_yearly WHERE workout_yearly_id=$get_current_workout_period_yearly_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_workout_yearly_id, $get_current_workout_yearly_title) = $row;
						echo"
						<a href=\"yearly_workout_plan_view.php?yearly_id=$get_current_workout_yearly_id&amp;l=$l\">$get_current_workout_yearly_title</a>
						&gt;
						";
					}
					echo"
					<a href=\"period_workout_plan_view.php?period_id=$get_current_workout_period_id&amp;l=$l\">$get_current_workout_period_title</a>
					&gt;
					";
				}
				echo"
				<a href=\"weekly_workout_plan_view.php?weekly_id=$get_current_workout_weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
				&gt;
				<a href=\"weekly_workout_plan_report_comment.php?comment_id=$get_current_comment_id&amp;l=$l\">$l_report_comment</a>
				</p>
			<!-- //Where am I ? -->

			<!-- Report comment form -->
				<h2>$l_report_comment</h2>

		
				<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_text\"]').focus();
				});
				</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"weekly_workout_plan_report_comment.php?comment_id=$get_current_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
				<p><b>$l_reason:</b><br />
				<textarea name=\"inp_text\" rows=\"5\" cols=\"45\" style=\"width: 95%;\"></textarea>
				</p>

				<p>
				<input type=\"submit\" value=\"$l_send_report\" class=\"btn\" />
				</p>

				</form>
			<!-- //Edit comment form -->
			";
		}
	} // logged in
	else{
		echo"
		<h1>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l\">
		";

	}
} // comment found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>