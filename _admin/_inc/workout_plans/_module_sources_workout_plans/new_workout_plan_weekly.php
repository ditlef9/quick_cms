<?php 
/**
*
* File: food/new_workout_plan_weekly.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_workout_plan - $l_workout_plans";
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
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get workout plan weekly
	$weekly_id_mysql = quote_smart($link, $weekly_id);
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_text, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql AND workout_weekly_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_text, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
	
	

	if($get_current_workout_weekly_id == ""){
		echo"<p>Weekly not found.</p>";
	}
	else{

		if($process == "1"){

			// Period
			$inp_period_id = $_POST['inp_period_id'];
			$inp_period_id = output_html($inp_period_id);		
			$inp_period_id_mysql = quote_smart($link, $inp_period_id);


			// Introduction
			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);			
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);

			
			// Update
			$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET workout_weekly_period_id=$inp_period_id_mysql,
				workout_weekly_introduction=$inp_introduction_mysql
				 WHERE workout_weekly_id=$weekly_id_mysql");



			// Purifier
			require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);

			if($get_user_rank == "admin" OR $get_user_rank == "moderator" OR $get_user_rank == "editor"){
			}
			elseif($get_user_rank == "trusted"){
			}
			else{
				// p, ul, li, b
				$config->set('HTML.Allowed', 'p,b,a[href],i,ul,li');
			}

			// Text
			$inp_text = $_POST['inp_text'];
			$inp_text = $purifier->purify($inp_text);

			// Goal
			$inp_goal = $_POST['inp_goal'];
			$inp_goal = $purifier->purify($inp_goal);


			$sql = "UPDATE $t_workout_plans_weekly SET workout_weekly_text=?, workout_weekly_goal=? WHERE workout_weekly_id=$weekly_id_mysql";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ss", $inp_text, $inp_goal);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			// Days
			$inp_amout_of_days_per_week_week = $_POST['inp_amout_of_days_per_week_week'];
			$inp_amout_of_days_per_week_week = output_html($inp_amout_of_days_per_week_week);

			/*
			$inp_monday_mysql = quote_smart("$l_day 1 - $l_monday");
			$inp_tuesday_mysql = quote_smart("$l_day 2 - $l_tuesday");
			$inp_wednesday_mysql = quote_smart("$l_day 3 - $l_wednesday");
			$inp_thursday_mysql = quote_smart("$l_day 4 - $l_thursday");
			$inp_friday_mysql = quote_smart("$l_day 5 - $l_friday");
			$inp_saturday_mysql = quote_smart("$l_day 6 - $l_saturday");
			$inp_sunday_mysql = quote_smart("$l_day 7 - $l_sunday");
			*/

			$inp_day_a_mysql = quote_smart($link, "$l_session 1");
			$inp_day_a_clean_mysql = quote_smart($link, clean("$l_session 1"));

			$inp_day_b_mysql = quote_smart($link, "$l_session 2");
			$inp_day_b_clean_mysql = quote_smart($link, clean("$l_session 2"));

			$inp_day_c_mysql = quote_smart($link, "$l_session 3");
			$inp_day_c_clean_mysql = quote_smart($link, clean("$l_session 3"));

			$inp_day_d_mysql = quote_smart($link, "$l_session 4");
			$inp_day_d_clean_mysql = quote_smart($link, clean("$l_session 4"));

			$inp_day_e_mysql = quote_smart($link, "$l_session 5");
			$inp_day_e_clean_mysql = quote_smart($link, clean("$l_session 5"));

			$inp_day_f_mysql = quote_smart($link, "$l_session 6");
			$inp_day_f_clean_mysql = quote_smart($link, clean("$l_session 6"));

			$inp_day_g_mysql = quote_smart($link, "$l_session 7");
			$inp_day_g_clean_mysql = quote_smart($link, clean("$l_session 7"));


			if($inp_amout_of_days_per_week_week > "0"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '0', $inp_day_a_mysql, $inp_day_a_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}
			if($inp_amout_of_days_per_week_week > "1"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '1', $inp_day_b_mysql, $inp_day_b_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}
			if($inp_amout_of_days_per_week_week > "2"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '2', $inp_day_c_mysql, $inp_day_c_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}
			if($inp_amout_of_days_per_week_week > "3"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '3', $inp_day_d_mysql, $inp_day_d_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}
			if($inp_amout_of_days_per_week_week > "4"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '4', $inp_day_e_mysql, $inp_day_e_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}
			if($inp_amout_of_days_per_week_week > "5"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '5', $inp_day_f_mysql, $inp_day_f_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}
			if($inp_amout_of_days_per_week_week > "6"){
				// Insert
				mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
				(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
				workout_session_warmup, workout_session_end) 
				VALUES 
				(NULL, $my_user_id_mysql, $weekly_id_mysql, '6', $inp_day_g_mysql, $inp_day_g_clean_mysql, '', '', '', '')
				")
				or die(mysqli_error($link));
			}


			// Search engine index
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='workout_plans' AND index_reference_name='workout_weekly_id' AND index_reference_id=$get_current_workout_weekly_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j. M Y H:i");

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_short_description=$inp_introduction_mysql 
								 WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}


			// Header
			$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$l_new_weekly_workout_plan</h1>
	

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

		<!-- TinyMCE -->
			
				<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
					],
					link_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
					image_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
						image_class_list: [
						{ title: 'None', value: '' },
						{ title: 'Some class', value: 'class-name' }
					],
					importcss_append: true,
					height: 500,
					file_picker_callback: function (callback, value, meta) {
						/* Provide file and text for the link dialog */
						if (meta.filetype === 'file') {
							callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
						}
						/* Provide image and alt text for the image dialog */
						if (meta.filetype === 'image') {
							callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
						}
						/* Provide alternative source and posted for the media dialog */
						if (meta.filetype === 'media') {
							callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
						}
					}
				});
				</script>
		<!-- //TinyMCE -->

		<!-- Form -->

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_yearly_id\"]').focus();
				});
			</script>
			<!-- //Focus -->


			<form method=\"post\" action=\"new_workout_plan_weekly.php?weekly_id=$weekly_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	


			<p><b>$l_amount_of_days_per_week</b><br />
			<select name=\"inp_amout_of_days_per_week_week\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"1\">1 $l_day_lowercase</option>
				<option value=\"2\">2 $l_days_lowercase</option>
				<option value=\"3\" selected=\"selected\">3 $l_days_lowercase</option>
				<option value=\"4\">4 $l_days_lowercase</option>
				<option value=\"5\">5 $l_days_lowercase</option>
				<option value=\"6\">6 $l_days_lowercase</option>
				<option value=\"7\">7 $l_days_lowercase</option>
			</select>
			</p>


			<p><b>$l_is_child_of_period_workout_plan:</b><br />
			<select name=\"inp_period_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"0\""; if($get_current_workout_weekly_period_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</option>\n";

				$query = "SELECT workout_period_id, workout_period_title FROM $t_workout_plans_period WHERE workout_period_user_id=$my_user_id_mysql AND workout_period_language=$l_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_workout_period_id, $get_workout_period_title) = $row;
			
					echo"				";
					echo"<option value=\"$get_workout_period_id\""; if($get_current_workout_weekly_period_id == $get_workout_period_id){ echo" selected=\"selected\""; } echo">$get_workout_period_title</option>\n";
				}

			echo"
			</select>
			</p>

			<p><b>$l_introduction:</b><br />
			<textarea name=\"inp_introduction\" rows=\"10\" cols=\"29\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 99%;\">";
			$get_current_workout_weekly_introduction = str_replace("<br />", "\n", $get_current_workout_weekly_introduction);
			echo"$get_current_workout_weekly_introduction</textarea>
			</p>

			<p><b>$l_text:</b><br />
			<textarea name=\"inp_text\" rows=\"10\" cols=\"30\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_weekly_text</textarea>
			</p>


			<p><b>$l_goal:</b><br />
			<textarea name=\"inp_goal\" rows=\"10\" cols=\"30\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_weekly_goal</textarea>
			</p>


			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			</form>
		<!-- //Form -->
		";
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