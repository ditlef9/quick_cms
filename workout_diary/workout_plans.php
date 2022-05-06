<?php
/**
*
* File: workout_diary/workout_plans.php
* Version 1.0.0.
* Date 14:39 21.12.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

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
$website_title = "$l_workout_plans - $l_workout_diary";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>$l_workout_plans</h1>

	<!-- Where am I? -->
		<p>
		<b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_workout_diary</a>
		&gt;
		<a href=\"workout_plans.php?l=$l\">$l_workout_plans</a>
		</p>
	<!-- //Where am I? -->


	<!-- Show all workout plans -->
	
	";	
	//  
	$x = 0;

	// Plans with images
	$query_w = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_title, workout_weekly_updated, workout_weekly_introduction, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_image_thumb_400x225 FROM $t_workout_plans_weekly WHERE workout_weekly_language=$l_mysql ORDER BY workout_weekly_unique_hits DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_workout_weekly_id, $get_workout_weekly_user_id, $get_workout_weekly_period_id, $get_workout_weekly_title, $get_workout_weekly_updated, $get_workout_weekly_introduction, $get_workout_weekly_image_path, $get_workout_weekly_image_file, $get_workout_weekly_image_thumb_400x225) = $row_w;

		if($get_workout_weekly_image_file != ""){
			// User
			$query_u = "SELECT user_id, user_name, user_alias FROM $t_users WHERE user_id='$get_workout_weekly_user_id'";
			$result_u = mysqli_query($link, $query_u);
			$row_u = mysqli_fetch_row($result_u);
			list($get_user_id, $get_user_name, $get_user_alias) = $row_u;

			// Date
			$year = substr($get_workout_weekly_updated, 0, 4);
			$month = substr($get_workout_weekly_updated, 5, 2);
			$day = substr($get_workout_weekly_updated, 8, 2);

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

			// Introduction
			$get_workout_weekly_introduction_len = strlen($get_workout_weekly_introduction);
			if($get_workout_weekly_introduction_len > 170){
				$get_workout_weekly_introduction = substr($get_workout_weekly_introduction, 0, 170);
				$get_workout_weekly_introduction = $get_workout_weekly_introduction . "...";
			}


			if($x == 0){
				echo"
				<div class=\"clear\"></div>
				<div class=\"left_right_left\">
				";
			}
			elseif($x == 1){
				echo"
				<div class=\"left_right_right\">
				";
			}

			echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\">
					
					";
					if($get_workout_weekly_image_file != "" && file_exists("$root/$get_workout_weekly_image_path/$get_workout_weekly_image_file")){
						// 950 x 640
						echo"
						<a href=\"$root/workout_plans/weekly_workout_plan_view.php?weekly_id=$get_workout_weekly_id&amp;l=$l\"><img src=\"$root/$get_workout_weekly_image_path/$get_workout_weekly_image_thumb_400x225\" alt=\"$get_workout_weekly_image_file\" /></a>\n";
					}
					echo"<br />
					<a href=\"weekly_workout_plan_view";
					if($get_stats_user_agent_type == "mobile"){ echo"_mobile"; } 
					echo".php?weekly_id=$get_workout_weekly_id&amp;l=$l\" class=\"h2\">$get_workout_weekly_title</a>
					</p>
					<p class=\"workout_introduction\">$get_workout_weekly_introduction</p>

					<p style=\"padding-top:0;margin-top:0;\">
					<a href=\"workout_plans.php?action=select&amp;weekly_id=$get_workout_weekly_id&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_choose</a>
					</p>
				</div>
			";

			
			if($x == 1){ 
				$x = -1;
			}

			$x++;
		} // image
	} // loop

	if($x == 1){
				echo"
				<div class=\"left_right_right\">
				</div>
				<div class=\"clear\"></div>
				";
	}


	echo"
	<!-- //Show all workout plans -->
	";
}
elseif($action == "select"){
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
			// Insert it

			$inp_title_mysql = quote_smart($link, $get_current_workout_weekly_title);
			$inp_date = date("Y-m-d");
			$inp_workout_plan_favorite_notes_mysql = quote_smart($link, $get_current_workout_weekly_introduction);
			
			mysqli_query($link, "INSERT INTO $t_workout_diary_plans
			(workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes) 
			VALUES 
			(NULL, $my_user_id_mysql, '0', NULL, NULL, $get_current_workout_weekly_id, NULL, $inp_title_mysql, '$inp_date', $inp_workout_plan_favorite_notes_mysql)")
			or die(mysqli_error($link));

			$url = "index.php?l=$l&ft=success&fm=plan_selected";
			header("Location: $url");
			exit;
		}
		else{
			// Already exists
			echo"<h1>Server error 500</h1>

			<p>Plan already selected.</p>
			";
		}
	}

}

?>