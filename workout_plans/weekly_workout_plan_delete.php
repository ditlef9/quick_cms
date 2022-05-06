<?php 
/**
*
* File: workout_plans/weekly_workout_plan_delete.php
* Version 1.0.0
* Date 19:54 02.03.2019
* Copyright (c) 2011-2019 S. A. Ditlefsen
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}
if(isset($_GET['session_id'])){
	$session_id = $_GET['session_id'];
	$session_id = output_html($session_id);
}
else{
	$session_id = "";
}
if(isset($_GET['session_main_id'])){
	$session_main_id = $_GET['session_main_id'];
	$session_main_id = output_html($session_main_id);
}
else{
	$session_main_id = "";
}

if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}

if(isset($_GET['duration_type'])){
	$duration_type = $_GET['duration_type'];
	$duration_type = strip_tags(stripslashes($duration_type));
}
else{
	$duration_type = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_delete_workout_plan - $l_workout_plans";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
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


	// Get workout plan weekly
	$weekly_id_mysql = quote_smart($link, $weekly_id);
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
	

	

	if($get_current_workout_weekly_id == ""){
		echo"<p>Weekly not found.</p>";
	}
	else{
		// User check
		if($get_current_workout_weekly_user_id != "$get_my_user_id" && $get_my_user_rank != "admin" && $get_my_user_rank != "moderator"){
			echo"
			<h1>Server error 403</h1>

			<p>Access denied. Only the owner, administrator or moderator can edit.</p>
			";
		}
		else{
			if($action == ""){
				if($process == "1"){
					
					// Workout plan
					$result = mysqli_query($link, "DELETE FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql");


					// Session main
					$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;


						$res = mysqli_query($link, "DELETE FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id");

					}
	

					// Session
					$res = mysqli_query($link, "DELETE FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$weekly_id_mysql");


					// Search engine index
					$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='workout_plans' AND index_reference_name='workout_weekly_id' AND index_reference_id=$get_current_workout_weekly_id";
					$result_exists = mysqli_query($link, $query_exists);
					$row_exists = mysqli_fetch_row($result_exists);
					list($get_index_id) = $row_exists;
					if($get_index_id != ""){
						$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_id=$get_index_id") or die(mysqli_error($link));
					}

					// Header
					$url = "my_workout_plans.php?l=$l&ft=success&fm=workout_plan_deleted";
					header("Location: $url");
					exit;

				}
				echo"
				<h1>$get_current_workout_weekly_title</h1>
	

				<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
				&gt;
				<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
				&gt;
				<a href=\"weekly_workout_plan_delete.php?weekly_id=$weekly_id&amp;l=$l\">$l_delete</a>
				</p>
				<!-- //Where am I ? -->

				<!-- Delete -->
					<p>
					$l_are_you_sure_you_want_to_delete_the_workout_plan
					</p>

					<p>
					<a href=\"weekly_workout_plan_delete.php?weekly_id=$weekly_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_delete</a>
					</p>
				<!-- //Delete -->
				";
			} // action == ""
		} // access
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>