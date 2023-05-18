<?php
/**
*
* File: workout_diary/index.php
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


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_workout_diary";
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

	// Check if I have a workout plan


	// Count rows
	$query = "SELECT workout_diary_plan_id, workout_diary_plan_title FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row_cnt = mysqli_num_rows($result);
	if($row_cnt == "0"){
		include("index_please_select_workout_plan.php");
	}		
	else{
		if($row_cnt == "1"){
			$row = mysqli_fetch_row($result);
			list($get_workout_diary_plan_id, $get_workout_diary_plan_title) = $row;

			$define_in_index = true;
			$plan_id = "$get_workout_diary_plan_id";
			include("select_session.php");
		}
		else{
			echo"
			<!-- Headline and language -->
				<h1>$l_workout_diary</h1>

			<!-- //Headline and language -->
		
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
					<a href=\"my_workout_plans.php?l=$l\" class=\"btn_default\">$l_my_workout_plans</a>
				</p>
				<div style=\"clear:both;height:10px;\"></div>
			<!-- //Quick menu -->

			<!-- Select plan - Select session - registrer -->
				<div class=\"vertical\">
					<ul>";
		
			$query = "SELECT workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$my_user_id_mysql ORDER BY workout_diary_plan_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_workout_diary_plan_id, $get_workout_diary_plan_user_id, $get_workout_diary_plan_weight, $get_workout_diary_plan_period_id, $get_workout_diary_plan_session_id, $get_workout_diary_plan_weekly_id, $get_workout_diary_plan_yearly_id, $get_workout_diary_plan_title, $get_workout_diary_plan_date, $get_workout_diary_plan_notes) = $row;

				echo"
						<li><span>
						<a href=\"select_session.php?plan_id=$get_workout_diary_plan_id&amp;l=$l\">$get_workout_diary_plan_title</a><br />
						$get_workout_diary_plan_notes
						</span></li>
				";

			}
		
			echo"
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Select plan - Select session - registrer -->
			";
		} // more than 1 plan
	} // have workout plans
} // logged in
else{
	include("index_not_logged_in.php");
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>