<?php
/**
*
* File: _admin/_inc/media/default.php
* Version 2
* Date 16:12 27.04.2019
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ----------------------------------------------------------------------- */
$root = "../";
include("_functions/decode_national_letters.php");
include("_inc/dashboard/_stats/unprocessed.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_tasks_index  		= $mysqlPrefixSav . "tasks_index";
$t_tasks_status_codes  		= $mysqlPrefixSav . "tasks_status_codes";
$t_tasks_projects  		= $mysqlPrefixSav . "tasks_projects";
$t_tasks_projects_parts  	= $mysqlPrefixSav . "tasks_projects_parts";
$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";
$t_tasks_read			= $mysqlPrefixSav . "tasks_read";


$t_stats_visists_per_year 	   	= $mysqlPrefixSav . "stats_visists_per_year";
$t_stats_visists_per_month 	   	= $mysqlPrefixSav . "stats_visists_per_month";
$t_stats_visists_per_week 	   	= $mysqlPrefixSav . "stats_visists_per_week";
$t_stats_visists_per_day 	   	= $mysqlPrefixSav . "stats_visists_per_day";
$t_stats_users_registered_per_year 	= $mysqlPrefixSav . "stats_users_registered_per_year";
$t_stats_users_registered_per_week 	= $mysqlPrefixSav . "stats_users_registered_per_week";
$t_stats_comments_per_year 		= $mysqlPrefixSav . "stats_comments_per_year";
$t_stats_comments_per_week		= $mysqlPrefixSav . "stats_comments_per_week";

/*- Notebook -------------------------------------------------------------------------- */
if(!(file_exists("_data/notepad_common.php"))){

	// Create file
	$datetime = date("Y-m-d H:i:s");
	$input="<?php
\$notepadUpdatedDateTimeSav = \"$datetime\";
\$notepadUpdatedUserIdSav   = \"$get_my_user_id\";
\$notepadUpdatedUserNameSav = \"$get_my_user_name\";
\$notepadNotesSav = \"\";
?>";

	$fh = fopen("_data/notepad_common.php", "w+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);
}





/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['week'])) {
	$week = $_GET['week'];
	$week = strip_tags(stripslashes($week));
}
else{
	$week = date("W");
}
$week_mysql = quote_smart($link, $week);

if(isset($_GET['month'])) {
	$month = $_GET['month'];
	$month = strip_tags(stripslashes($month));
}
else{
	$month = date("m");
}
$month_mysql = quote_smart($link, $month);

if(isset($_GET['year'])) {
	$year = $_GET['year'];
	$year = strip_tags(stripslashes($year));
}
else{
	$year = date("Y");
}
$year_mysql = quote_smart($link, $year);

if($action == ""){
	echo"



	<!-- Charts Javascripts -->
		<script src=\"_javascripts/amcharts/index.js\"></script>
		<script src=\"_javascripts/amcharts/xy.js\"></script>
		<script src=\"_javascripts/amcharts/themes/Animated.js\"></script>
	<!-- //Charts Javascripts -->



			</div> <!-- //main_right_content -->
			<div class=\"clear_main_right_content\">

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
			}
			echo"
			<div class=\"$ft\" style=\"margin: 0px 20px 20px 20px;\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Check if setup folder exists -->
		";
		if(file_exists("setup/index.php")){
			echo"
			<div class=\"white_bg_box\"><span><b>Security issue:</b> The setup folder exists. Do you want to <a href=\"index.php?open=dashboard&amp;page=delete_setup_folder&amp;editor_language=$editor_language&amp;l=$l\">delete the setup folder</a>?</span></div> 
			";
		}
		echo"
	<!-- //Check if setup folder exists -->


	<!-- Row 1 -->
		<div class=\"dashboard_row_four\">
			<!-- 1.1 Visits per week -->
				<div class=\"dashboard_columns_four_wrapper\">
					<div class=\"dashboard_columns_four_inner\">

						";
						// Data this week
						$query_t = "SELECT stats_visit_per_week_id, stats_visit_per_week_human_unique, stats_visit_per_week_human_unique_diff_from_last_week FROM $t_stats_visists_per_week WHERE stats_visit_per_week_week=$week AND stats_visit_per_week_year=$year";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_stats_visit_per_week_id, $get_stats_visit_per_week_human_unique, $get_stats_visit_per_week_human_unique_diff_from_last_week) = $row_t;	

						echo"

						<h2>Unique visits per week</h2>
                  			<p class=\"dark_grey\" style=\"padding:0;margin:0;\">";
						if($get_stats_visit_per_week_human_unique_diff_from_last_week == 0){
							echo"<img src=\"_inc/dashboard/_img/ti_angle_flat_no_change.png\" alt=\"ti_angle_up_no_change.png\" title=\"Same as last week ($get_stats_visit_per_week_human_unique_diff_from_last_week visits diff)\" />";
						}
						elseif($get_stats_visit_per_week_human_unique_diff_from_last_week < 0){
							echo"<img src=\"_inc/dashboard/_img/ti_angle_down_warning.png\" alt=\"ti_angle_up_warning.png\" title=\"Decreased with $get_stats_visit_per_week_human_unique_diff_from_last_week unique humans from last week\" />";
						}
						else{
							echo"<img src=\"_inc/dashboard/_img/ti_angle_up_success.png\" alt=\"ti_angle_up_success.png\" title=\"Increasted with $get_stats_visit_per_week_human_unique_diff_from_last_week unique humans from last week\" />";
						}
                    			echo"
						<span>$get_stats_visit_per_week_human_unique</span>
            				</p>

						<!-- Javascript visits per week last 2 years -->
							<div id=\"chartdiv_visits_per_week_last_two_years\" style=\"width: 100%;height: 100px;\"></div>";
	
							include("_inc/dashboard/statistics_default_generate/visits_per_week_last_2_years.php");
							echo"
							<script src=\"../_cache/stats_default/visits_per_week_last_2_years_$configSecurityCodeSav.js?rand=$rand\"></script>
						<!-- //Javascript visits per week last 2 years -->

					</div> <!--// dashboard_columns_four_inner -->
				</div>
			<!-- //1.1 Visits per week -->

			<!-- 1.2 Comments per week -->
				<div class=\"dashboard_columns_four_wrapper\">
					<div class=\"dashboard_columns_four_inner\">";
						// Data this week
						$query_t = "SELECT stats_comments_id, stats_comments_comments_written, stats_comments_comments_written_diff_from_last_week FROM $t_stats_comments_per_week WHERE stats_comments_week=$week AND stats_comments_year=$year";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_stats_comments_id, $get_stats_comments_comments_written, $get_stats_comments_comments_written_diff_from_last_week) = $row_t;	
						if($get_stats_comments_id == ""){
							mysqli_query($link, "INSERT INTO $t_stats_comments_per_week 
							(stats_comments_id, stats_comments_week, stats_comments_month, stats_comments_year, stats_comments_comments_written) 
							VALUES 
							(NULL, $week, $month, $year, 0)")
							or die(mysqli_error($link));
						}
	
						echo"

						<h2>Comments per week</h2>
							
						<p class=\"dark_grey\" style=\"padding:0;margin:0;\">";
						if($get_stats_comments_comments_written_diff_from_last_week == 0){
							echo"<img src=\"_inc/dashboard/_img/ti_angle_flat_no_change.png\" alt=\"ti_angle_up_no_change.png\" title=\"Same amount of comments as last week ($get_stats_comments_comments_written_diff_from_last_week comments)\" />";
						}
						elseif($get_stats_comments_comments_written_diff_from_last_week < 0){
							echo"<img src=\"_inc/dashboard/_img/ti_angle_down_warning.png\" alt=\"ti_angle_up_warning.png\" title=\"Decrease by $get_stats_comments_comments_written_diff_from_last_week comments from last week\" />";
						}
						else{
							echo"<img src=\"_inc/dashboard/_img/ti_angle_up_success.png\" alt=\"ti_angle_up_success.png\" title=\"Increas by $get_stats_comments_comments_written_diff_from_last_week comments from last week\" />";
						}
                    				echo"
						<span>$get_stats_comments_comments_written</span>
            					</p>

						<!-- Javascript comments per week last 2 years -->
							<div id=\"chartdiv_comments_per_week_last_two_years\" style=\"width: 100%;height: 100px;\"></div>";
	
							include("_inc/dashboard/statistics_default_generate/comments_per_week_last_2_years.php");
							echo"
							<script src=\"../_cache/stats_default/comments_per_week_last_2_years_$configSecurityCodeSav.js?rand=$rand\"></script>
						<!-- //Javascript comments per week last 2 years -->

					</div> <!-- //dashboard_columns_four_inner-->
				</div>
			<!-- //1.2 Comments per week -->

			<!-- 1.3 Users per week -->
				<div class=\"dashboard_columns_four_wrapper\">
					<div class=\"dashboard_columns_four_inner\">";
						// Data this week
						$query_t = "SELECT stats_registered_id, stats_registered_users_registed, stats_registered_users_registed_diff_from_last_week FROM $t_stats_users_registered_per_week WHERE stats_registered_week=$week AND stats_registered_year=$year";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_stats_registered_id, $get_stats_registered_users_registed, $get_stats_registered_users_registed_diff_from_last_week) = $row_t;	
						if($get_stats_registered_id == ""){
								mysqli_query($link, "INSERT INTO $t_stats_users_registered_per_week 
								(stats_registered_id, stats_registered_week, stats_registered_year, stats_registered_users_registed, stats_registered_users_registed_diff_from_last_week) 
								VALUES 
								(NULL, $week, $year, 0, 0)")
								or die(mysqli_error($link));
						}

						echo"

							<h2>Users registered per week</h2>
							
                  					<p class=\"dark_grey\" style=\"padding:0;margin:0;\">";
							if($get_stats_registered_users_registed_diff_from_last_week == 0){
								echo"<img src=\"_inc/dashboard/_img/ti_angle_flat_no_change.png\" alt=\"ti_angle_up_no_change.png\" title=\"Same users as last week ($get_stats_registered_users_registed_diff_from_last_week)\" />";
							}
							elseif($get_stats_registered_users_registed_diff_from_last_week < 0){
								echo"<img src=\"_inc/dashboard/_img/ti_angle_down_warning.png\" alt=\"ti_angle_up_warning.png\" title=\"Decrease in users registered by $get_stats_registered_users_registed_diff_from_last_week\" />";
							}
							else{
								echo"<img src=\"_inc/dashboard/_img/ti_angle_up_success.png\" alt=\"ti_angle_up_success.png\" title=\"Increase in users registered by $get_stats_registered_users_registed_diff_from_last_week\" />";
							}
                    					echo"
							<span>$get_stats_registered_users_registed</span>
            						</p>

						<!-- Javascript users per week last 2 years -->
							<div id=\"chartdiv_users_per_week_last_two_years\" style=\"width: 100%;height: 100px;\"></div>";
	
							include("_inc/dashboard/statistics_default_generate/users_per_week_last_2_years.php");
							echo"
							<script src=\"../_cache/stats_default/users_per_week_last_2_years_$configSecurityCodeSav.js?rand=$rand\"></script>
						<!-- //Javascript users per week last 2 years -->

					</div> <!-- //dashboard_columns_four_inner -->
				</div>
			<!-- //1.3 Users per week -->


			<!-- 1.4 Last user registered -->
				<div class=\"dashboard_columns_four_wrapper\">
					<div class=\"dashboard_columns_four_inner_last\">";
						// Last user registered
						$query_t = "SELECT user_id, user_name, user_alias, user_language, user_country_name, user_city_name, user_gender, user_registered_date_saying FROM $t_users ORDER BY user_id DESC LIMIT 0,1";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_last_user_id, $get_last_user_name, $get_last_user_alias, $get_last_user_language, $get_last_user_country_name, $get_last_user_city_name, $get_last_user_gender, $get_last_user_registered_saying) = $row_t;

						// Photo
						$q = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id='$get_last_user_id' AND photo_profile_image='1'";
						$r = mysqli_query($link, $q);
						$rowb = mysqli_fetch_row($r);
						list($get_last_photo_id, $get_last_photo_destination, $get_last_photo_thumb_60) = $rowb;

						echo"

						<h2>Latest user</h2>
					
						<div class=\"dashboard_image_left\">
							<p>
							";
							if($get_last_photo_id != ""){
								if(!(file_exists("../_uploads/users/images/$get_last_user_id/$get_last_photo_thumb_60"))){
									$res = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_user_id='$get_last_user_id' AND photo_profile_image='1'");
								}
								echo"
								<a href=\"../users/view_profile.php?user_id=$get_last_user_id&amp;l=$l\"><img src=\"../_uploads/users/images/$get_last_user_id/$get_last_photo_thumb_60\" alt=\"$get_last_photo_thumb_60\" /></a>
								";
			
							}
							else{
								echo"
								<a href=\"../users/view_profile.php?user_id=$get_last_user_id&amp;l=$l\"><img src=\"_design/gfx/avatar_blank_60.png\" alt=\"Avatar\" /></a>
								";
							}

							echo"
							</p>
						</div> <!-- //dashboard_image_left -->

						<div class=\"dashboard_text_right\">
							<p>
							<a href=\"view_profile.php?user_id=$get_last_user_id&amp;l=$l\" style=\"font-weight:bold;color:#000;\">$get_last_user_alias</a>
		
								";
								if($get_last_user_name != "$get_last_user_alias"){
									echo"<span class=\"dark_grey\">@$get_last_user_name</span>";
								}
								echo"<br />
								$get_last_user_registered_saying
								</p>

						
						
								<p style=\"padding:0;margin: 2px 0px 5px 0px;\" class=\"dark_grey\">
								";
								if($get_last_user_city_name != ""){
									echo"
									$get_last_user_city_name";  if($get_last_user_country_name != ""){ echo", $get_last_user_country_name"; } echo"
									";
								}
								echo"
							</p>
						</div> <!-- //dashboard_text_right -->
						<div class=\"clear\"></div>


					</div> <!-- //dashboard_columns_four_inner_last -->
				</div>
			<!-- //1.4 Last user registered -->

		</div> <!-- //dashboard_row_four -->
	<!-- //Row 1 -->

<!-- Users: Average age, Gender, Country, Cities -->

	<!-- Row 2 -->
		<div class=\"dashboard_row_two\">
			<!-- 2.1 Visits per month -->
				<div class=\"dashboard_columns_two_wrapper\">
					<div class=\"dashboard_columns_two_inner\">
						<div class=\"float_left\">
							<h2>$year $l_numbers</h2>
						</div>
						<div class=\"float_right\">
							<p>\n";
							$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default, language_active_flag_path_18x18, language_active_flag_active_18x18, language_active_flag_inactive_18x18 FROM $t_languages_active";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default, $get_language_active_flag_path_18x18, $get_language_active_flag_active_18x18, $get_language_active_flag_inactive_18x18) = $row;
								echo"						<a href=\"index.php?open=dashboard&amp;page=statistics_year&amp;stats_year=$year&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_18x18/$get_language_active_flag_active_18x18\" alt=\"$get_language_active_flag_active_18x18\" title=\"View stats for $get_language_active_name\" /></a>\n";
							}
							echo"
							</p>
						</div>
						<div class=\"clear\"></div>
						<!-- Javascript visits per month last 2 years -->
							<div id=\"chartdiv_visits_per_month\" style=\"width: 100%;height: 300px;\"></div>";
	
							include("_inc/dashboard/statistics_default_generate/visits_per_month_last_2_years.php");
							echo"
							<script src=\"../_cache/stats_default/visits_per_month_last_2_years_$configSecurityCodeSav.js?rand=$rand\"></script>
						<!-- //Javascript visits per month last 2 years -->

					</div> <!-- //dashboard_columns_two_inner -->
				</div> <!-- //dashboard_columns_two_wrapper -->
			<!-- //Visits per month -->
			<!-- Visits per day -->
				<div class=\"dashboard_columns_two_wrapper\">
					<div class=\"dashboard_columns_two_inner\">
				
						<div class=\"float_left\">
						";
						if($month == "01"){
							echo"<h2>$l_january $l_numbers</h2>";
						}
						elseif($month == "02"){
							echo"<h2>$l_february  $l_numbers</h2>";
						}
						elseif($month == "03"){
							echo"<h2>$l_march $l_numbers</h2>";
						}
						elseif($month == "04"){
							echo"<h2>$l_april $l_numbers</h2>";
						}
						elseif($month == "05"){
							echo"<h2>$l_may $l_numbers</h2>";
						}
						elseif($month == "06"){
							echo"<h2>$l_june $l_numbers</h2>";
						}
						elseif($month == "07"){
							echo"<h2>$l_july $l_numbers</h2>";
						}
						elseif($month == "08"){
							echo"<h2>$l_august $l_numbers</h2>";
						}
						elseif($month == "09"){
							echo"<h2>$l_september $l_numbers</h2>";
						}
						elseif($month == "10"){
							echo"<h2>$l_october $l_numbers</h2>";
						}
						elseif($month == "11"){
							echo"<h2>$l_november $l_numbers</h2>";
						}
						elseif($month == "12"){
							echo"<h2>$l_december $l_numbers</h2>";
						}
						echo"
					</div>
					<div class=\"float_right\">
						<p>\n";
						$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default, language_active_flag_path_18x18, language_active_flag_active_18x18, language_active_flag_inactive_18x18 FROM $t_languages_active";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default, $get_language_active_flag_path_18x18, $get_language_active_flag_active_18x18, $get_language_active_flag_inactive_18x18) = $row;
							echo"						<a href=\"index.php?open=dashboard&amp;page=statistics_month&amp;stats_year=$year&amp;stats_month=$month&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_18x18/$get_language_active_flag_active_18x18\" alt=\"$get_language_active_flag_active_18x18\" title=\"View stats for $get_language_active_name\" /></a>\n";
						}
						echo"
						</p>
					</div>
					<div class=\"clear\"></div>


					<!-- Javascript month visitor -->
						<div id=\"chartdiv_visits_per_day\" style=\"width: 100%;height: 300px;\"></div>";

						include("_inc/dashboard/statistics_default_generate/visits_per_day_last_2_months.php");
						echo"
						<script src=\"../_cache/stats_default/visits_per_day_last_2_months_$configSecurityCodeSav.js?rand=$rand\"></script>
					<!-- //Javascript month visitor -->



					</div> <!-- //dashboard_columns_two_inner -->
				</div> <!-- //dashboard_columns_two_wrapper -->
			<!-- //Visits per day -->


		</div>
	<!-- //Row 2 -->

	<!-- Row 3 -->
		<a id=\"tasks\"></a>
		<div class=\"tasks_row\">
			<!-- New Tasks / Unassigned -->
				<div class=\"tasks_columns_wrapper\">
					<div class=\"tasks_columns_inner\">";

						$time = time();

						$query_t = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_show_on_board=1 AND status_code_task_is_assigned=0 ORDER BY status_code_weight ASC LIMIT 0,1";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_status_code_id, $get_status_code_title, $get_status_code_text_color, $get_status_code_bg_color, $get_status_code_border_color, $get_status_code_weight, $get_status_code_show_on_board, $get_status_code_on_status_close_task, $get_status_code_count_tasks) = $row_t;	

						echo"
						<div class=\"tasks_drop_div\" id=\"status_code_id$get_status_code_id"; echo"user_id0\">
							<div class=\"task_status_headline\">
								<div class=\"task_status_headline_left\">
									<h2>$get_status_code_title</h2>
								</div>
								<div class=\"task_status_headline_right\">
									<p>
									<a href=\"index.php?open=dashboard&amp;page=tasks&amp;action=new_task&amp;status_code_id=$get_status_code_id&amp;l=$l\">+</a>
									</p>
								</div>
							</div>
							<div class=\"clear\"></div>
							";

							$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_priority_weight, task_created_datetime, task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_thumb_40, task_assigned_to_user_email, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index ";
							$query = $query . "WHERE task_status_code_id=$get_status_code_id AND task_is_archived='0' ORDER BY task_priority_id, task_id ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_task_id, $get_task_system_task_abbr, $get_task_system_incremented_number, $get_task_project_task_abbr, $get_task_project_incremented_number, $get_task_title, $get_task_text, $get_task_status_code_id, $get_task_priority_id, $get_task_priority_weight, $get_task_created_datetime, $get_task_created_by_user_id, $get_task_created_by_user_alias, $get_task_created_by_user_image, $get_task_created_by_user_email, $get_task_updated_datetime, $get_task_due_datetime, $get_task_due_time, $get_task_due_translated, $get_task_assigned_to_user_id, $get_task_assigned_to_user_alias, $get_task_assigned_to_user_image, $get_task_assigned_to_user_thumb_40, $get_task_assigned_to_user_email, $get_task_qa_datetime, $get_task_qa_by_user_id, $get_task_qa_by_user_alias, $get_task_qa_by_user_image, $get_task_qa_by_user_email, $get_task_finished_datetime, $get_task_finished_by_user_id, $get_task_finished_by_user_alias, $get_task_finished_by_user_image, $get_task_finished_by_user_email, $get_task_is_archived, $get_task_comments, $get_task_project_id, $get_task_project_part_id, $get_task_system_id, $get_task_system_part_id) = $row;

								// Number
								$number = "";
								if($get_task_project_incremented_number == "0" OR $get_task_project_incremented_number == ""){
									if($get_task_system_incremented_number == "0" OR $get_task_system_incremented_number == ""){
										$number = "$get_task_id";
									}
									else{
										$number = "$get_task_system_task_abbr-$get_task_system_incremented_number";
									}
								}
								else{
									$number = "$get_task_project_task_abbr-$get_task_project_incremented_number";
								}

								// Read?
								$query_r = "SELECT read_id FROM $t_tasks_read WHERE read_task_id=$get_task_id AND read_user_id=$my_user_id_mysql";
								$result_r = mysqli_query($link, $query_r);
								$row_r = mysqli_fetch_row($result_r);
								list($get_read_id) = $row_r;	

					
								echo"
								<div class=\"tasks_content_wrapper\" id=\"task_id$get_task_id\">
									<div class=\"task_content_priority_$get_task_priority_weight\">

										<p>
										<a href=\"index.php?open=$open&amp;page=tasks&amp;action=open_task&amp;task_id=$get_task_id&amp;l=$l&amp;editor_language=$editor_language\""; if($get_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">";

										// Assigned to image
										if($get_task_assigned_to_user_id == "" OR $get_task_assigned_to_user_id == "0"){
										}
										else{
											if($get_task_assigned_to_user_thumb_40 != "" && file_exists("../_uploads/users/images/$get_task_assigned_to_user_id/$get_task_assigned_to_user_thumb_40")){
												echo"
												<img src=\"../_uploads/users/images/$get_task_assigned_to_user_id/$get_task_assigned_to_user_thumb_40\" alt=\"../$get_task_assigned_to_user_thumb_40/_uploads/users/images/$get_task_assigned_to_user_id/$get_task_assigned_to_user_thumb_40\" width=\"20\" height=\"20\" />
												";
											}
											else{
												echo"
												<img src=\"_inc/dashboard/_img/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" width=\"20\" height=\"20\" />
												";
											}

										}
										echo"
										$number  $get_task_title</a>
										</p>
									</div> <!-- //task_priority_x -->
								</div> <!-- //task_content_wrapper -->
								";
							}
							echo"
						</div> <!-- //tasks_drop_div -->
					</div> <!-- //tasks_columns_inner -->
				</div> <!-- //tasks_columns_wrapper -->
			<!-- //New Tasks / Unassigned -->


			<!-- Tasks per admin -->";

				$x = 0;
				$row_counter = 0;
				$column_counter = 0;
				$query_u = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_rank='admin' ORDER BY user_name ASC";
				$result_u = mysqli_query($link, $query_u);
				while($row_u = mysqli_fetch_row($result_u)) {
					list($get_user_id, $get_user_email, $get_user_name) = $row_u;

					// Get my photo
					$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$get_user_id AND photo_profile_image='1'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $row;

					echo"
					<div class=\"tasks_columns_wrapper\">
						<div class=\"tasks_columns_inner"; if($column_counter == "2"){ echo"_last"; } echo"\">
							<div class=\"task_status_headline\">
								<div class=\"task_status_headline_left\">
								
								
									<p>";
									// Assigned to image
									if($get_photo_thumb_40 != "" && file_exists("../_uploads/users/images/$get_user_id/$get_photo_thumb_40")){
										echo"
										<img src=\"../_uploads/users/images/$get_user_id/$get_photo_thumb_40\" alt=\"$get_photo_thumb_40\" width=\"20\" height=\"20\" />
										";
									}
									else{
										echo"
										<img src=\"_inc/dashboard/_img/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" width=\"20\" height=\"20\" />
										";
									}
									echo"</p>
								
									<h2 style=\"padding: 8px 0px 8px 0px;\">$get_user_name</h2>
								
							</div>
							<div class=\"task_status_headline_right\">
								<p>
								<a href=\"index.php?open=dashboard&amp;page=tasks&amp;action=new_task&amp;status_code_id=$get_status_code_id&amp;inp_assigned_to_user_alias=$get_user_name&amp;l=$l\">+</a>
								</p>
							</div>
						</div>
						<div class=\"clear\"></div>
						";

						// Statuses
						$query_s = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_show_on_board=1 AND status_code_task_is_assigned=1 ORDER BY status_code_weight ASC";
						$result_s = mysqli_query($link, $query_s);
						while($row_s = mysqli_fetch_row($result_s)) {
							list($get_status_code_id, $get_status_code_title, $get_status_code_text_color, $get_status_code_bg_color, $get_status_code_border_color, $get_status_code_weight, $get_status_code_show_on_board, $get_status_code_on_status_close_task, $get_status_code_count_tasks) = $row_s;

							echo"
							<div class=\"tasks_drop_div\" id=\"status_code_id$get_status_code_id"; echo"user_id$get_user_id\">
								<h3>$get_status_code_title</h3>
								";

							// Tasks
							$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_priority_weight, task_created_datetime, task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_thumb_40, task_assigned_to_user_email, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index ";
							$query = $query . "WHERE task_status_code_id=$get_status_code_id AND task_assigned_to_user_id=$get_user_id AND task_is_archived='0' ORDER BY task_priority_id, task_id ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_task_id, $get_task_system_task_abbr, $get_task_system_incremented_number, $get_task_project_task_abbr, $get_task_project_incremented_number, $get_task_title, $get_task_text, $get_task_status_code_id, $get_task_priority_id, $get_task_priority_weight, $get_task_created_datetime, $get_task_created_by_user_id, $get_task_created_by_user_alias, $get_task_created_by_user_image, $get_task_created_by_user_email, $get_task_updated_datetime, $get_task_due_datetime, $get_task_due_time, $get_task_due_translated, $get_task_assigned_to_user_id, $get_task_assigned_to_user_alias, $get_task_assigned_to_user_image, $get_task_assigned_to_user_thumb_40, $get_task_assigned_to_user_email, $get_task_qa_datetime, $get_task_qa_by_user_id, $get_task_qa_by_user_alias, $get_task_qa_by_user_image, $get_task_qa_by_user_email, $get_task_finished_datetime, $get_task_finished_by_user_id, $get_task_finished_by_user_alias, $get_task_finished_by_user_image, $get_task_finished_by_user_email, $get_task_is_archived, $get_task_comments, $get_task_project_id, $get_task_project_part_id, $get_task_system_id, $get_task_system_part_id) = $row;
			
								// Number
								$number = "";
								if($get_task_project_incremented_number == "0" OR $get_task_project_incremented_number == ""){
									if($get_task_system_incremented_number == "0" OR $get_task_system_incremented_number == ""){
										$number = "$get_task_id";
									}
									else{
										$number = "$get_task_system_task_abbr-$get_task_system_incremented_number";
									}
								}
								else{
									$number = "$get_task_project_task_abbr-$get_task_project_incremented_number";
								}

								// Read?
								$query_r = "SELECT read_id FROM $t_tasks_read WHERE read_task_id=$get_task_id AND read_user_id=$my_user_id_mysql";
								$result_r = mysqli_query($link, $query_r);
								$row_r = mysqli_fetch_row($result_r);
								list($get_read_id) = $row_r;	

									
								echo"
								<div class=\"tasks_content_wrapper\" id=\"task_id$get_task_id\">
									<div class=\"task_content_priority_$get_task_priority_weight\">
 										<!-- Due -->";
											if($time > $get_task_due_time){
												echo"<div class=\"task_content_info\">
												<p>$get_task_due_translated</p>
												</div>\n";
											}
											echo"
 										<!-- //Due -->
										<p>
										<a href=\"index.php?open=$open&amp;page=tasks&amp;action=open_task&amp;task_id=$get_task_id&amp;l=$l&amp;editor_language=$editor_language\""; if($get_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$number $get_task_title</a>
										</p>
									</div> <!-- //task_content_priority_$get_task_priority_weight -->
								</div> <!-- //tasks_content_wrapper -->
								";
							} // tasks (for this admin)
							echo"
							</div> <!-- //tasks_drop_div -->
							";
						} // statuses

						echo"
						</div> <!-- //tasks_columns_inner -->
					</div> <!-- //tasks_columns_wrapper -->
					";
					$x++;
					$column_counter++;
					$row_counter++;
				} // admins
				echo"

			<!-- //Tasks per admin -->

			<!-- Drag and drop script -->
				<script src=\"_javascripts/jquery/jquery-ui.js\"></script>
				<script type=\"text/javascript\">
					\$(function () {
						\$( \".tasks_content_wrapper\" ).draggable();
						\$( \".tasks_drop_div\" ).droppable({ drop: Drop });
					});

					function Drop(event, ui) {
						var draggableId = ui.draggable.attr(\"id\");
						var droppableId = \$(this).attr(\"id\");
						var data            = 'task_id=' + draggableId + '&to_status_and_user=' + droppableId;
						\$.ajax({
							type: \"POST\",
							url: \"index.php?open=dashboard&page=tasks_drag_and_drop_update_status&editor_language=$editor_language&l=$l&process=1\",
      							data: data,
							success: function (data) {
								\$('.tasks_update_result').html(data);
								window.location.replace(\"index.php?open=dashboard&editor_language=$editor_language&l=$l&time=$time#tasks\");
							}
						});
					}
				</script>
			<!-- //Drag and drop script -->
		</div> <!-- //tasks_row -->
		<div class=\"tasks_update_result\"></div>
		";
		include("_inc/dashboard/tasks_include_send_monthly_newsletter.php");
		echo"
	<!-- //Row 3 -->
	";
}


?>