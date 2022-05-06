<?php 
/**
*
* File: exercises/new_workout_plan.php
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


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = " $l_new_workout_plan - $l_workout_plans";
include("$root/_webdesign/header.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

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


	if($process == "1"){

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);
		if(empty($inp_title)){
			$url = "new_workout_plan.php?l=$l";
			$url = $url . "&ft=error&fm=missing_title";
			header("Location: $url");
			exit;
		}
		
		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

		$inp_duration = $_POST['inp_duration'];
		$inp_duration = output_html($inp_duration);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);
		$l = $inp_language;
		if(empty($inp_language)){
			$url = "new_workout_plan.php?l=$l";
			$url = $url . "&ft=error&fm=missing_language";
			header("Location: $url");
			exit;
		}


		$datetime = date("Y-m-d H:i:s");

		$inp_user_ip = $_SERVER['REMOTE_ADDR'];
		$inp_user_ip = output_html($inp_user_ip);
		$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);



		$get_workout_plan_id = "";

		if($inp_duration == "yearly"){
			mysqli_query($link, "INSERT INTO $t_workout_plans_yearly
			(workout_yearly_id, workout_yearly_user_id, workout_yearly_language, workout_yearly_title, workout_yearly_title_clean, 
			workout_yearly_created, workout_yearly_updated, workout_yearly_unique_hits, workout_yearly_comments, workout_yearly_likes, 
			workout_yearly_dislikes, workout_yearly_rating, workout_yearly_user_ip) 
			VALUES 
			(NULL, $my_user_id_mysql, $inp_language_mysql, $inp_title_mysql, $inp_title_clean_mysql, '$datetime', '$datetime', '0', 
			'0', '0', '0', '0', $inp_user_ip_mysql)
			")
			or die(mysqli_error($link));

			// Get ID
			$query_t = "SELECT workout_yearly_id FROM $t_workout_plans_yearly WHERE workout_yearly_user_id=$my_user_id_mysql AND workout_yearly_created='$datetime'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_workout_plan_id) = $row_t;

			

		}
		elseif($inp_duration == "period"){
			mysqli_query($link, "INSERT INTO $t_workout_plans_period
			(workout_period_id, workout_period_user_id, workout_period_yearly_id, workout_period_language, workout_period_title, workout_period_title_clean, workout_period_created, workout_period_updated, workout_period_unique_hits, 
			workout_period_comments, workout_period_likes, workout_period_dislikes, workout_period_rating, workout_period_user_ip) 
			VALUES 
			(NULL, $my_user_id_mysql, '0', $inp_language_mysql, $inp_title_mysql, $inp_title_clean_mysql, '$datetime', '$datetime', '0', 
			'0', '0', '0', '0', $inp_user_ip_mysql)
			")
			or die(mysqli_error($link));

			// Get ID
			$query_t = "SELECT workout_period_id FROM $t_workout_plans_period WHERE workout_period_user_id=$my_user_id_mysql AND workout_period_created='$datetime'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_workout_plan_id) = $row_t;
			
		}
		elseif($inp_duration == "weekly"){
			mysqli_query($link, "INSERT INTO $t_workout_plans_weekly
			(workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_language, workout_weekly_title, 
			workout_weekly_title_clean, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_comments,
			 workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_user_ip) 
			VALUES 
			(NULL, $my_user_id_mysql, '0', $inp_language_mysql, $inp_title_mysql, 
			$inp_title_clean_mysql, '$datetime', '$datetime', '0', '0',
			 '0', '0', '0', $inp_user_ip_mysql)
			")
			or die(mysqli_error($link));

			// Get ID
			$query_t = "SELECT workout_weekly_id FROM $t_workout_plans_weekly WHERE workout_weekly_user_id=$my_user_id_mysql AND workout_weekly_created='$datetime'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_workout_plan_id) = $row_t;
			
		}
		else{
			echo"what";
		}


		// Send e-mail to admins
		$query = "SELECT user_id, user_email, user_name,  user_alias, user_rank FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_admin_user_id, $get_admin_user_email, $get_admin_user_name, $get_admin_user_alias, $get_admin_user_rank) = $row;

			
			// Mail from
			$host = $_SERVER['HTTP_HOST'];
			$subject = "New workout plan $inp_title at $host";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>New workout plan:</b></p>\n";
			$message = $message . "<table>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>ID:</span></td>\n";
			$message = $message . "  <td><span>$get_workout_plan_id</td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Title:</span></td>\n";
			$message = $message . "  <td><span><a href=\"$configSiteURLSav/workout_plans/weekly_workout_plan_view.php?weekly_id=$get_workout_plan_id&amp;l=$inp_language\">$inp_title</a></span></td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Duration:</span></td>\n";
			$message = $message . "  <td><span>$inp_duration</span></td>\n";
			$message = $message . " </tr>\n";

			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Language:</span></td>\n";
			$message = $message . "  <td><span>$inp_language</span></td>\n";
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
			$message = $message . "  <td><span><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_user_id&amp;l=$inp_language\">$get_user_alias</a></span></td>\n";
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
			if($configMailSendActiveSav == "1"){
				mail($get_admin_user_email, $subject, $message, implode("\r\n", $headers));
			}
		}


		// Search engine
		$inp_index_title = "$inp_title | $l_workout_plans";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "";
		$inp_index_module_part_name = "";
		$inp_index_reference_name = "";
		if($inp_duration == "yearly"){
			$inp_index_url = "workout_plans/yearly_workout_plan_view.php?yearly_id=$get_workout_plan_id";
			$inp_index_module_part_name = "workout_plan_yearly";
			$inp_index_reference_name = "workout_yearly_id";
		}
		elseif($inp_duration == "period"){
			$inp_index_url = "workout_plans/period_workout_plan_view.php?period_id=$get_workout_plan_id";
			$inp_index_module_part_name = "workout_plan_period";
			$inp_index_reference_name = "workout_period_id";
		}
		elseif($inp_duration == "weekly"){
			$inp_index_url = "workout_plans/weekly_workout_plan_view.php?weekly_id=$get_workout_plan_id";
			$inp_index_module_part_name = "workout_plan_weekly";
			$inp_index_reference_name = "workout_weekly_id";
		}
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);
		$inp_index_module_part_name_mysql = quote_smart($link, $inp_index_module_part_name);
		$inp_index_reference_name_mysql = quote_smart($link, $inp_index_reference_name);
		$inp_index_reference_id_mysql = quote_smart($link, "$get_workout_plan_id");

		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j. M Y H:i");

		mysqli_query($link, "INSERT INTO $t_search_engine_index 
		(index_id, index_title, index_url, index_short_description, index_keywords, 
		index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
		index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
		index_unique_hits) 
		VALUES 
		(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
		'workout_plans', $inp_index_module_part_name_mysql, 0, $inp_index_reference_name_mysql, $inp_index_reference_id_mysql, 
		0, 0, '$datetime', '$datetime_saying', $inp_language_mysql,
		0)")
		or die(mysqli_error($link));

		// Header
		if($inp_duration == "yearly"){
			// Header
			$url = "new_workout_plan_yearly.php?yearly_id=$get_workout_plan_id&l=$l";
			header("Location: $url");
		}
		elseif($inp_duration == "period"){
			// Header
			$url = "new_workout_plan_period.php?period_id=$get_workout_plan_id&l=$l";
			header("Location: $url");
		}
		elseif($inp_duration == "weekly"){
			// Header
			$url = "new_workout_plan_weekly.php?weekly_id=$get_workout_plan_id&l=$l";
			header("Location: $url");
		}
		else{
			echo"what";
		}
			
	}

	
	echo"
	<h1>$l_new_workout_plan</h1>
	
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
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
		</script>
		<form method=\"post\" action=\"new_workout_plan.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>$l_title*:</b><br />
		<input type=\"text\" name=\"inp_title\" size=\"25\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 99%;\" />
		</p>

		<p><b>$l_duration/$l_type*:</b><br />
		<select name=\"inp_duration\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"weekly\" selected=\"selected\">$l_weekly</option>
			<option value=\"period\">$l_period</option>
			<option value=\"yearly\">$l_yearly</option>
		</select>
		</p>

		<p><b>$l_language*:</b><br />
		<select name=\"inp_language\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
				
			echo"	<option value=\"$get_language_active_iso_two\"";if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"$l_next\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		</form>
	<!-- //Form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/workout_plans/new_workout_plan.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>