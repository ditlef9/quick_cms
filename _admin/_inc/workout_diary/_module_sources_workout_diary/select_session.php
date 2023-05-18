<?php
/**
*
* File: workout_diary/select_session.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if(!(isset($define_in_index))){
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


	/*- Variables ------------------------------------------------------------------------- */
	$l_mysql = quote_smart($link, $l);

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_select_session - $l_workout_diary";
	include("$root/_webdesign/header.php");

}
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	if(!(isset($my_user_id))){
		// Get my profile
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_alias, user_email, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_date_format) = $row;
	}

	// Check that the plan I have selected
	if(isset($plan_id)){
		// All ok
	}
	else{
		if(isset($_GET['plan_id'])){
			$plan_id = $_GET['plan_id'];
			$plan_id = output_html($plan_id);
		}
		else{
			$plan_id = "";
		}	
	}
	$plan_id_mysql = quote_smart($link, $plan_id);
	$query = "SELECT workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes FROM $t_workout_diary_plans WHERE workout_diary_plan_id=$plan_id_mysql AND workout_diary_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_diary_plan_id, $get_workout_diary_plan_user_id, $get_workout_diary_plan_weight, $get_workout_diary_plan_period_id, $get_workout_diary_plan_session_id, $get_workout_diary_plan_weekly_id, $get_workout_diary_plan_yearly_id, $get_workout_diary_plan_title, $get_workout_diary_plan_date, $get_workout_diary_plan_notes) = $row;

	if($get_workout_diary_plan_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Workout diary plan not found.</p>
		";	
	}
	else{
		// Find weekly
		$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$get_workout_diary_plan_weekly_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
		if($get_current_workout_weekly_id == ""){
			// Delete refrence
			$result = mysqli_query($link, "DELETE FROM $t_workout_diary_plans WHERE workout_diary_plan_id=$plan_id_mysql AND workout_diary_plan_user_id=$my_user_id_mysql");
			
			echo"<h1>Server error 404</h1>

			<p>
			The weekly workout plan has been removed.
			You need to select another plan to have as your favorite.
			</p>

			<p>
			<a href=\"index.php?l=$l\">Select another plan</a>
			</p>
			";
		}
		else{
			echo"
			<h1>$l_select_session</h1>
			
			<!-- Quick menu -->
				<p>
				<a href=\"workout_plans.php?l=$l\" class=\"btn_default\">$l_workout_plans</a>
				<a href=\"my_workout_plans.php?l=$l\" class=\"btn_default\">$l_my_workout_plans</a>
				<a href=\"$root/workout_plans/new_workout_plan.php?l=$l\" class=\"btn_default\">$l_new_workout_plan</a>
				</p>
			<!-- //Quick menu -->

			<!-- Sessions -->
				<h2>$get_current_workout_weekly_title</h2>
				<div class=\"vertical\">
					<ul>
			";

				$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;

					echo"
					<li><a href=\"registrer_data.php?plan_id=$get_workout_diary_plan_id&amp;weekly_id=$get_current_workout_weekly_id&amp;session_id=$get_workout_session_id&amp;l=$l\">$get_workout_session_title</a></li>
					";
				}
			echo"
					</ul>
				</div>
			<!-- //Sessions -->
			";
		} // weekly found

	} // workout diary plan found

} // logged in
else{
	include("index_not_logged_in.php");
}


/*- Footer ----------------------------------------------------------------------------------- */
if(!(isset($define_in_index))){
	include("$root/_webdesign/footer.php");
}
?>