<?php 
/**
*
* File: workout_plans/period_workout_plan_edit.php
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['period_id'])){
	$period_id = $_GET['period_id'];
	$period_id = output_html($period_id);
}
else{
	$period_id = "";
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


// Get workout plan period
$period_id_mysql = quote_smart($link, $period_id);
$query = "SELECT workout_period_id, workout_period_user_id, workout_period_yearly_id, workout_period_weight, workout_period_language, workout_period_title, workout_period_title_clean, workout_period_introduction, workout_period_goal, workout_period_text, workout_period_from, workout_period_to, workout_period_image_path, workout_period_image_file, workout_period_created, workout_period_updated, workout_period_unique_hits, workout_period_unique_hits_ip_block, workout_period_comments, workout_period_likes, workout_period_dislikes, workout_period_rating, workout_period_ip_block, workout_period_user_ip, workout_period_notes FROM $t_workout_plans_period WHERE workout_period_id=$period_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_workout_period_id, $get_current_workout_period_user_id, $get_current_workout_period_yearly_id, $get_current_workout_period_weight, $get_current_workout_period_language, $get_current_workout_period_title, $get_current_workout_period_title_clean, $get_current_workout_period_introduction, $get_current_workout_period_goal, $get_current_workout_period_text, $get_current_workout_period_from, $get_current_workout_period_to, $get_current_workout_period_image_path, $get_current_workout_period_image_file, $get_current_workout_period_created, $get_current_workout_period_updated, $get_current_workout_period_unique_hits, $get_current_workout_period_unique_hits_ip_block, $get_current_workout_period_comments, $get_current_workout_period_likes, $get_current_workout_period_dislikes, $get_current_workout_period_rating, $get_current_workout_period_ip_block, $get_current_workout_period_user_ip, $get_current_workout_period_notes) = $row;
	
if($get_current_workout_period_id == ""){
	

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_workout_plans - Server error 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	echo"<p>Period not found.</p>";
	include("$root/_webdesign/footer.php");

}
else{

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_workout_period_title - $l_workout_plans";
	include("$root/_webdesign/header.php");
	
	echo"
	<h1>$get_current_workout_period_title</h1>
	

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_workout_plans</a>
		&gt;";
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
		<a href=\"period_workout_plan_view.php?period_id=$period_id&amp;l=$l\">$get_current_workout_period_title</a>
		</p>
	<!-- //Where am I ? -->


	<!-- Period intro and image -->
		<p>";
		if($get_current_workout_period_image_file != "" && file_exists("$root/$get_current_workout_period_image_path/$get_current_workout_period_image_file")){
			echo"
			<img src=\"$root/$get_current_workout_period_image_path/$get_current_workout_period_image_file\" alt=\"$get_current_workout_period_image_file\" /><br />
			$get_current_workout_period_introduction";
		}
		echo"
		</p>
	<!-- //Period intro and image -->
	
	<!-- Goal -->
		<h2>$l_goal</h2>

		$get_current_workout_period_goal
	<!-- //Goal -->

	<!-- Weekly plans -->
		";
		$query_w = "SELECT workout_weekly_id, workout_weekly_period_id, workout_weekly_title, workout_weekly_introduction, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_updated FROM $t_workout_plans_weekly WHERE workout_weekly_period_id=$get_current_workout_period_id ORDER BY workout_weekly_weight ASC";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_workout_weekly_id, $get_workout_weekly_period_id, $get_workout_weekly_title, $get_workout_weekly_introduction, $get_workout_weekly_image_path, $get_workout_weekly_image_file, $get_workout_weekly_updated) = $row_w;

			echo"
			<p>
			<a href=\"weekly_workout_plan_view.php?weekly_id=$get_workout_weekly_id&amp;l=$l\" class=\"h2\">$get_workout_weekly_title</a><br />
			";
			if($get_workout_weekly_image_file != "" && file_exists("$root/$get_workout_weekly_image_path/$get_workout_weekly_image_file")){
				// 950 x 640
				echo"<p>
				<a href=\"weekly_workout_plan_view.php?weekly_id=$get_workout_weekly_id&amp;l=$l\"><img src=\"$root/image.php?width=400&amp;height=269&amp;image=/$get_workout_weekly_image_path/$get_workout_weekly_image_file\" alt=\"$get_workout_weekly_image_path/$get_workout_weekly_image_file\" /></a>
				</p>\n";
			}
			echo"
			$get_workout_weekly_introduction
			</p>
			";
		}
		echo"
	<!-- //Weekly plans -->

	";
} // found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>