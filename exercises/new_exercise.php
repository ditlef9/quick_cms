<?php 
/**
*
* File: food/new_exercise.php
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
include("_tables_exercises.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_exercise - $l_exercises";
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


	if($action == ""){
		echo"
		<h1>$l_what_do_you_want_to_do</h1>

		<div class=\"vertical\">
			<ul>
			<li><a href=\"$root/workout_plans/new_workout_plan.php?l=$l\">$l_create_workout_plan</a></li>
			<li><a href=\"new_exercise.php?action=new_exercise&amp;l=$l\">$l_create_exercise</a></li>
			</ul>
		</div>
		";

	}
	elseif($action == "new_exercise"){

		if($process == "1"){

			$inp_exercise_title = $_POST['inp_exercise_title'];
			$inp_exercise_title = output_html($inp_exercise_title);
			$inp_exercise_title_mysql = quote_smart($link, $inp_exercise_title);
			if(empty($inp_exercise_title)){
				$url = "new_exercise.php?action=new_exercise&l=$l";
				$url = $url . "&ft=error&fm=missing_title";
				header("Location: $url");
				exit;
			}
		
			$inp_exercise_title_clean = clean($inp_exercise_title);
			$inp_exercise_title_clean_mysql = quote_smart($link, $inp_exercise_title_clean);

			$inp_exercise_title_alternative = $_POST['inp_exercise_title_alternative'];
			$inp_exercise_title_alternative = output_html($inp_exercise_title_alternative);
			$inp_exercise_title_alternative_mysql = quote_smart($link, $inp_exercise_title_alternative);

			$inp_exercise_language = $_POST['inp_exercise_language'];
			$inp_exercise_language = output_html($inp_exercise_language);
			$inp_exercise_language_mysql = quote_smart($link, $inp_exercise_language);
			$l = $inp_exercise_language;
			if(empty($inp_exercise_language)){
				$url = "new_exercise.php?action=new_exercise&l=$l";
				$url = $url . "&ft=error&fm=missing_language";
				header("Location: $url");
				exit;
			}

	
			$inp_exercise_type_id = $_POST['inp_exercise_type_id'];
			$inp_exercise_type_id = output_html($inp_exercise_type_id);
			$inp_exercise_type_id_mysql = quote_smart($link, $inp_exercise_type_id);
			if(empty($inp_exercise_type_id)){
				$url = "new_exercise.php?action=new_exercise&l=$l";
				$url = $url . "&ft=error&fm=missing_type";
				$url = $url . "&title=$inp_exercise_title";
				header("Location: $url");
				exit;
			}
			else{
				$type_id_mysql = quote_smart($link, $inp_exercise_type_id);
				$query = "SELECT type_id, type_title FROM $t_exercise_types WHERE type_id=$inp_exercise_type_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_type_id, $get_type_title) = $row;
				if($get_type_id == ""){
					$url = "new_exercise_step_2_title.php?action=new_exercise&l=$l";
					$url = $url . "&ft=error&fm=invalid_type";
					$url = $url . "&title=$inp_exercise_title";
					header("Location: $url");
					exit;
				}
	
			}

			$inp_exercise_level_id = $_POST['inp_exercise_level_id'];
			$inp_exercise_level_id = output_html($inp_exercise_level_id);
			$inp_exercise_level_id_mysql = quote_smart($link, $inp_exercise_level_id);
			if(empty($inp_exercise_level_id)){
				$url = "new_exercise.php?action=new_exercise&l=$l";
				$url = $url . "&ft=error&fm=missing_level";
				$url = $url . "&title=$inp_exercise_title";
				$url = $url . "&type_id=$inp_exercise_type_id";
				header("Location: $url");
				exit;
			}
			else{
				$level_id_mysql = quote_smart($link, $inp_exercise_level_id);
				$query = "SELECT level_id, level_title FROM $t_exercise_levels WHERE level_id=$inp_exercise_level_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_level_id, $get_level_title) = $row;
				if($get_level_id == ""){
					$url = "new_exercise.php?action=new_exercise&l=$l";
					$url = $url . "&ft=error&fm=invalid_level";
					$url = $url . "&title=$inp_exercise_title";
					$url = $url . "&type_id=$inp_exercise_type_id";
					header("Location: $url");
					exit;
				}

			}



			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j. M Y H:i");


			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

			// Create
			mysqli_query($link, "INSERT INTO $t_exercise_index
			(exercise_id, exercise_title, exercise_title_clean, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_type_id, exercise_level_id, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_likes, exercise_dislikes, exercise_rating, exercise_number_of_comments)
			VALUES 
			(NULL, $inp_exercise_title_mysql, $inp_exercise_title_clean_mysql, $inp_exercise_title_alternative_mysql, '$get_user_id', $l_mysql, '0', '0', '0', $inp_exercise_type_id_mysql, $inp_exercise_level_id_mysql, '$datetime', '$datetime', $inp_user_ip_mysql, '0', '0', '0', '0', '0')")
			or die(mysqli_error($link));
			
			// Get ID
			$query = "SELECT exercise_id FROM $t_exercise_index WHERE exercise_title=$inp_exercise_title_mysql AND exercise_user_id='$get_user_id' AND exercise_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_exercise_id) = $row;


			// Send e-mail to admins
			$query = "SELECT user_id, user_email, user_name,  user_alias, user_rank FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_admin_user_id, $get_admin_user_email, $get_admin_user_name, $get_admin_user_alias, $get_admin_user_rank) = $row;

				
				// Mail from
				$host = $_SERVER['HTTP_HOST'];
				$subject = "New exercise $inp_exercise_title at $host";

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>New exercise:</b></p>\n";
				$message = $message . "<table>\n";
				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Exercise ID:</span></td>\n";
				$message = $message . "  <td><span>$get_exercise_id</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Title:</span></td>\n";
				$message = $message . "  <td><span><a href=\"$configSiteURLSav/exercises/view_exercise.php?exercise_id=$get_exercise_id&amp;l=$inp_exercise_language\">$inp_exercise_title</a></span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Alt title:</span></td>\n";
				$message = $message . "  <td><span>$inp_exercise_title_alternative</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Language:</span></td>\n";
				$message = $message . "  <td><span>$inp_exercise_language</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Type:</span></td>\n";
				$message = $message . "  <td><span><a href=\"$configSiteURLSav/exercises/view_type.php?type_id=$inp_exercise_type_id&amp;l=$inp_exercise_language\">$inp_exercise_title</a></span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Level:</span></td>\n";
				$message = $message . "  <td><span>$inp_exercise_level_id</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Date time:</span></td>\n";
				$message = $message . "  <td><span>$datetime</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>IP:</span></td>\n";
				$message = $message . "  <td><span>$inp_user_ip</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>User ID:</span></td>\n";
				$message = $message . "  <td><span>$get_user_id</span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . " <tr>\n";
				$message = $message . "  <td><span>Alias:</span></td>\n";
				$message = $message . "  <td><span><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_user_id&amp;l=$inp_exercise_language\">$get_user_alias</a></span></td>\n";
				$message = $message . " </tr>\n";

				$message = $message . "</table>\n";
		
				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";


				// Preferences for Subject field
				$headers = array();
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=utf-8';
				$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
				mail($get_admin_user_email, $subject, $message, implode("\r\n", $headers));

			}


			// Search engine
			$inp_index_title = "$inp_exercise_title | $l_exercises";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "exercises/view_exercise.php?exercise_id=$get_exercise_id&type_id=$inp_exercise_type_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_index_short_description = "";
			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			$inp_index_keywords = "";
			$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");


			$inp_index_module_name_mysql = quote_smart($link, "exercises");
			$inp_index_module_part_name_mysql = quote_smart($link, "exercises");
			$inp_index_reference_name_mysql = quote_smart($link, "exercise_id");
			$inp_index_reference_id_mysql = quote_smart($link, "$get_exercise_id");

			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
				$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
				'0', 0,  '$datetime', '$datetime_saying', $inp_exercise_language_mysql,
				0)")
				or die(mysqli_error($link));
			}


			// Search engine
			include("new_exercise_step_00_add_update_search_engine.php");

			// Header
			$url = "new_exercise_step_2_text.php?exercise_id=$get_exercise_id&l=$l";
			header("Location: $url");
			exit;
		}

	
		echo"
		<h1>$l_new_exercise</h1>
	

	
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_exercise_title\"]').focus();
			});
		</script>
		<!-- //Focus -->

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

		<!-- Form -->
		<form method=\"post\" action=\"new_exercise.php?action=new_exercise&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>$l_title*:</b><br />
		<input type=\"text\" name=\"inp_exercise_title\" size=\"40\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_alternative_title:</b><br />
		<input type=\"text\" name=\"inp_exercise_title_alternative\" size=\"40\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_language*:</b><br />
		<select name=\"inp_exercise_language\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			$flag_path 	= "$root/_webdesign/images/flags/16x16/$get_language_active_flag" . "_16x16.png";
				
			echo"	<option value=\"$get_language_active_iso_two\"";if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>$l_type*:</b><br />
		<select name=\"inp_exercise_type_id\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"0\">- $l_please_select -</option>
			<option value=\"0\"></option>
			";
			// Get all types
			$query = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_type_id, $get_type_title) = $row;

				// Translation
				$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id=$get_type_id AND type_translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_type_translation_id, $get_type_translation_value) = $row_translation;
					echo"
				<option value=\"$get_type_id\">$get_type_translation_value</option>\n";
				
			}
			echo"
		</select>
		</p>

		<p><b>$l_level:</b><br />
		<select name=\"inp_exercise_level_id\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"0\">- $l_please_select - </option>
			<option value=\"\"></option>\n";
			// Get exercise levels
			$query_sub = "SELECT level_id, level_title FROM $t_exercise_levels ORDER BY level_id ASC";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_level_id, $get_level_title) = $row_sub;

				// Translation
				$query_translation = "SELECT level_translation_id, level_translation_value FROM $t_exercise_levels_translations WHERE level_id='$get_level_id' AND level_translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_level_translation_id, $get_level_translation_value) = $row_translation;
				echo"				";
				echo"<option value=\"$get_level_id\">$get_level_translation_value</option>";
			}
		echo"
		</select></p>


		<p>
		<input type=\"submit\" value=\"$l_continue\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		</form>
		<!-- //Form -->
		";
	} // action == "new exercise"
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>