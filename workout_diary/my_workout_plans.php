<?php
/**
*
* File: workout_diary/my_workout_plans.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


/*- Translations ------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/workout_diary/ts_index_please_select_workout_plan.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_workout_plans  - $l_workout_diary";
include("$root/_webdesign/header.php");

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_date_format) = $row;

	if($action == ""){
		// Check if I have a workout plan
		echo"
		<h1>$l_my_workout_plans</h1>

		<!-- Where am I? -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_workout_diary</a>
			&gt;
			<a href=\"my_workout_plans.php?l=$l\">$l_my_workout_plans</a>
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

		<!-- Quick menu -->
			<div style=\"height:10px;\"></div>
			<p style=\"padding-bottom:0;\">
				<a href=\"workout_plans.php?l=$l\" class=\"btn_default\">$l_workout_plans</a>
			</p>
			<div style=\"clear:both;height:10px;\"></div>
		<!-- //Quick menu -->
	
		<!-- Select plan - Select session - registrer -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_title</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		 </thead>
		<tbody>
		";
		$x = 0;
		$query = "SELECT workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$my_user_id_mysql ORDER BY workout_diary_plan_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_workout_diary_plan_id, $get_workout_diary_plan_user_id, $get_workout_diary_plan_weight, $get_workout_diary_plan_period_id, $get_workout_diary_plan_session_id, $get_workout_diary_plan_weekly_id, $get_workout_diary_plan_yearly_id, $get_workout_diary_plan_title, $get_workout_diary_plan_date, $get_workout_diary_plan_notes) = $row;

			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>
				<a href=\"$root/workout_plans/weekly_workout_plan_view.php?weekly_id=$get_workout_diary_plan_weekly_id&amp;l=$l\">$get_workout_diary_plan_title</a><br />
				$get_workout_diary_plan_notes
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"my_workout_plans.php?action=delete&amp;plan_id=$get_workout_diary_plan_id&amp;l=$l\"><img src=\"_gfx/icons/16x16/user_trash.png\" alt=\"user_trash.png\" /></a>
				</span>
			 </td>
			</tr>
			";
			if($get_workout_diary_plan_weight != "$x"){
				$result_update = mysqli_query($link, "UPDATE $t_workout_diary_plans SET workout_diary_plan_weight=$x WHERE workout_diary_plan_id=$get_workout_diary_plan_id");

			}
			$x++;
		}
		echo"
		 </tbody>
		</table>
		<!-- //Select plan - Select session - registrer -->
		";
	} // action == ""
	elseif($action == "delete"){
		// Find that plan
		if(isset($_GET['plan_id'])){
			$plan_id = $_GET['plan_id'];
			$plan_id = output_html($plan_id);
		}
		else{
			$plan_id = "";
		}
		$plan_id_mysql = quote_smart($link, $plan_id);

		$query = "SELECT workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes FROM $t_workout_diary_plans WHERE workout_diary_plan_id=$plan_id_mysql AND workout_diary_plan_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_workout_diary_plan_id, $get_workout_diary_plan_user_id, $get_workout_diary_plan_weight, $get_workout_diary_plan_period_id, $get_workout_diary_plan_session_id, $get_workout_diary_plan_weekly_id, $get_workout_diary_plan_yearly_id, $get_workout_diary_plan_title, $get_workout_diary_plan_date, $get_workout_diary_plan_notes) = $row;

		if($get_workout_diary_plan_id == ""){
			echo"
			<h1>Error</h1>
			<p>Plan not found.</p>
			";
		}
		else{
			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_workout_diary_plans WHERE workout_diary_plan_id=$plan_id_mysql");
				$result = mysqli_query($link, "DELETE FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$get_workout_diary_plan_session_id");

				$url = "my_workout_plans.php?l=$l&ft=success&fm=plan_deleted";
				header("Location: $url");
				exit;

			}
			echo"
			<h1>$get_workout_diary_plan_title</h1>

			<p>
			$l_are_you_sure_you_want_to_delete_the_workout_plan_from_your_diary
			</p>

			<p>
			<a href=\"my_workout_plans.php?action=delete&amp;plan_id=$get_workout_diary_plan_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_delete</a>
			<a href=\"my_workout_plans.php?l=$l\" class=\"btn_default\">$l_previous</a>
			</p>
			";
		}
	} // delete
	elseif($action == "select_workout_plan"){

		// Find the plan
		if(isset($_GET['weekly_id'])){
			$weekly_id = $_GET['weekly_id'];
			$weekly_id = output_html($weekly_id);
		}
		else{
			$weekly_id = "";
		}

		$weekly_id_mysql = quote_smart($link, $weekly_id);
		$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
		if($get_current_workout_weekly_id == ""){
			echo"
			<h1>Server error 404</h1>
			";
		}
		else{
			// Check if it already exists
			$query = "SELECT workout_diary_plan_id FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$my_user_id_mysql AND workout_diary_plan_weekly_id=$get_current_workout_weekly_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_workout_diary_plan_id) = $row;
			if($get_workout_diary_plan_id == ""){
				// Loop trough old plans, give them new numbers
				$x = 1;
				$query = "SELECT workout_diary_plan_id FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$my_user_id_mysql ORDER BY workout_diary_plan_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_workout_diary_plan_id) = $row;
					$result_update = mysqli_query($link, "UPDATE $t_workout_diary_plans SET workout_diary_plan_weight=$x WHERE workout_diary_plan_id=$get_workout_diary_plan_id");
					$x++;
				}

				// Insert it
				$inp_title_mysql = quote_smart($link, $get_current_workout_weekly_title);
				$inp_date = date("Y-m-d");
				$inp_workout_plan_favorite_notes_mysql = quote_smart($link, $get_current_workout_weekly_introduction);
			
				mysqli_query($link, "INSERT INTO $t_workout_diary_plans
				(workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes) 
				VALUES 
				(NULL, $my_user_id_mysql, '0', NULL, NULL, $get_current_workout_weekly_id, NULL, $inp_title_mysql, '$inp_date', $inp_workout_plan_favorite_notes_mysql)")
				or die(mysqli_error($link));

	
				$url = "my_workout_plans.php?l=$l&ft=success&fm=plan_selected";
				header("Location: $url");
				exit;
			}
			else{

				// Already exists
				$url = "my_workout_plans.php?action=browse_workout_plans&l=$l&ft=warning&fm=plan_already_selected";
				header("Location: $url");
				exit;
			}
		}
	} // select_workout_plan
} // logged in
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;refer=$root/workout_diary/my_workout_plan.php\">
	";
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>