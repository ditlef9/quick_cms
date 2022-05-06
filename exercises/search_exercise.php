<?php
/**
*
* File: exercises/index.php
* Version 1.0.0.
* Date 20:42 15.11.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
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


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}


/*- Tables ---------------------------------------------------------------------------- */
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos			= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles			= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images		= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_index_tags				= $mysqlPrefixSav . "exercise_index_tags";
$t_exercise_tags_cloud				= $mysqlPrefixSav . "exercise_tags_cloud";
$t_exercise_index_comments			= $mysqlPrefixSav . "exercise_index_comments";
$t_exercise_index_translations_relations	= $mysqlPrefixSav . "exercise_index_translations_relations";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types				= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 			= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels				= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 		= $mysqlPrefixSav . "exercise_levels_translations";


/*- Language ------------------------------------------------------------------------ */
include("../_admin/_translations/site/$l/exercises/ts_exercises.php");


/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['search_query'])){
	$search_query = $_GET['search_query'];
	$search_query = utf8_decode($search_query);
	$search_query = trim($search_query);
	$search_query = strtolower($search_query);
	$search_query = output_html($search_query);
}
else{
	$search_query = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_search - $l_exercises";
if($search_query != ""){
	$website_title = "$search_query - " . $website_title ;
}
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_search</h1>
<!-- //Headline and language -->

<!-- Where am I? -->
	<p><b>$l_you_are_here:</b><br />
	<a href=\"index.php?l=$l\">$l_exercises</a>
	&gt;
	<a href=\"search_exercise.php?search_query=$search_query&amp;l=$l\">$search_query</a>
	</p>
<!-- //Where am I? -->


<!-- Search -->
	<div style=\"float: left;\">
		<form method=\"get\" action=\"search_exercise.php\" enctype=\"multipart/form-data\">
		<p>
		
		<input type=\"text\" name=\"search_query\" value=\"$search_query\" size=\"20\" id=\"nettport_inp_search_query\" />
		<input type=\"hidden\" name=\"l\" value=\"$l\" />
		<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" />
		</p>
	</div>

	<!-- Search script -->
		<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
		\$(document).ready(function () {
			\$('#nettport_inp_search_query').keyup(function () {
        			var searchString    = $(\"#nettport_inp_search_query\").val();
       				var data            = 'l=$l&search_query='+ searchString;
         
        			// if searchString is not empty
        			if(searchString) {
           				// ajax call
            				\$.ajax({
                				type: \"GET\",
               					url: \"search_exercise_jquery.php\",
                				data: data,
						beforeSend: function(html) { // this happens before actual call
							\$(\"#nettport_search_results\").html(''); 
						},
               					success: function(html){
                    					\$(\"#nettport_search_results\").append(html);
              					}
            				});
       				}
        			return false;
            		});
            	});
		</script>
	<!-- //Search script -->
<!-- //Search -->


<!-- Search -->
	<div id=\"nettport_search_results\">
	";	
	// 
	if($search_query != ""){
		// Check for hacker
		include("$root/_admin/_functions/look_for_hacker_in_string.php");

		// Searched
		$search_query_mysql = quote_smart($link, $search_query);
		$query = "SELECT query_id, query_name, query_language, query_times, query_last_use, query_hidden, query_no_of_results, query_email_sendt_month FROM $t_exercise_search_queries WHERE query_name=$search_query_mysql AND query_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_query_id, $get_current_query_name, $get_current_query_language, $get_current_query_times, $get_current_query_last_use, $get_current_query_hidden, $get_current_query_no_of_results, $get_current_query_email_sendt_month) = $row;

		if($get_current_query_id == ""){
			// Insert
			$insert_error = "0";
			mysqli_query($link, "INSERT INTO $t_exercise_search_queries 
			(query_id, query_name, query_language) 
			VALUES
			(NULL, $search_query_mysql, $l_mysql) ")
			or $insert_error = 1;

			// Fetch the ID
			$query = "SELECT query_id, query_name, query_language, query_times, query_last_use, query_hidden, query_no_of_results, query_email_sendt_month FROM $t_exercise_search_queries WHERE query_name=$search_query_mysql AND query_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_query_id, $get_current_query_name, $get_current_query_language, $get_current_query_times, $get_current_query_last_use, $get_current_query_hidden, $get_current_query_no_of_results, $get_current_query_email_sendt_month) = $row;


		}
		else{
			$inp_query_times = $get_current_query_times+1;
			$datetime = date("Y-m-d H:i:s");
			$result = mysqli_query($link, "UPDATE $t_exercise_search_queries SET query_times='$inp_query_times', query_last_use='$datetime' WHERE query_id=$get_current_query_id") or die(mysqli_error($link));
		}


		$exercises_count = 0;
		$x = 0;
		$search_query = $search_query . "%";
		$search_query_mysql = quote_smart($link, $search_query);
		$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_muscle_group_id_main, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_updated_datetime, exercise_guide FROM $t_exercise_index WHERE (exercise_title LIKE $search_query_mysql OR exercise_title_alternative LIKE $search_query_mysql) AND exercise_language=$l_mysql ORDER BY exercise_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_exercise_id, $get_exercise_title, $get_exercise_user_id, $get_exercise_muscle_group_id_main, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_updated_datetime, $get_exercise_guide) = $row;


			if($x == 0){
				echo"
				<div class=\"clear\" style=\"height: 10px;\"></div>
				<div class=\"left_right_left\">
				";
			}
			elseif($x == 1){
				echo"
				<div class=\"left_right_right\">
				";
			}




			echo"
				<p style=\"padding: 10px 0px 0px 0px;margin-bottom:0;\">
				<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;l=$l\" class=\"exercise_index_title\">$get_exercise_title</a><br />
				</p>\n";
					// Images
					$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,2";
					$result_images = mysqli_query($link, $query_images);
					while($row_images = mysqli_fetch_row($result_images)) {
						list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file) = $row_images;

						if($get_exercise_image_file != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_file")){

							// Thumb
							$inp_new_x = 150;
							$inp_new_y = 150;
							$thumb = "exercise_" . $get_exercise_image_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";

							if(!(file_exists("$root/_cache/$thumb"))){
								resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_exercise_image_path/$get_exercise_image_file", "$root/_cache/$thumb");
							}

							echo"				";
							echo"<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;l=$l\"><img src=\"$root/_cache/$thumb\" alt=\"$get_exercise_image_type\" /></a>\n";
						}
					}
					echo"
			</div>
			";
			if($x == 1){
				$x = -1;
			}
			$x++;
			$exercises_count++;
		} // query
		
		if($x == "1"){
			echo"
				<div class=\"left_right_right\"></div>
				<div class=\"clear\"></div>\n";
		}
		if($exercises_count == "0"){
			echo"
			<div class=\"clear\"></div>
			<p>$l_no_results.</p>\n";
		}

		if($exercises_count != "$get_current_query_no_of_results"){
			// Update number of results
			$result = mysqli_query($link, "UPDATE $t_exercise_search_queries SET query_no_of_results=$exercises_count WHERE query_id=$get_current_query_id") or die(mysqli_error($link));
		}
		// No results? Send email
		$month = date("m");
		if($exercises_count == "0" && $get_current_query_email_sendt_month != "$month"){
			// Find moderator to email
			// Who is moderator of the week?
			$week = date("W");
			$year = date("Y");
			$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			if($get_moderator_user_id == ""){
				// Create moderator of the week
				include("$root/_admin/_functions/create_moderator_of_the_week.php");
				
				$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			}
			
			// Data about user
			$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
			$my_user_agent = output_html($my_user_agent);

			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);

			// Send e-mail to moderators that there is a new user
			include("../_admin/_data/logo.php");
			$search_query = str_replace("%", "", $search_query);
			$subject = "Exercise search query with no results $search_query at $configWebsiteTitleSav";
			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
			$message = $message . "<h1>No search results for $search_query</h1>\n\n";
			$message = $message . "<p>\n";
			$message = $message . "Search query: $search_query<br />\n";
			$message = $message . "Language: $l<br />\n";
			$message = $message . "User agent: $my_user_agent<br />\n";
			$message = $message . "IP: $my_ip<br />\n";
			$message = $message . "</p>\n";
			$message = $message . "<p>URL: <a href=\"$configSiteURLSav/exercises/search_exercise.php?search_query=$search_query&amp;l=$l\">search_exercise.php?search_query=$search_query&amp;l=$l</a></p>\n";

			$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n";
			$message = $message . "<a href=\"$configSiteURLSav/index.php?l=$l\">$configSiteURLSav</a></p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";

			// Preferences for Subject field
			$headers_mail[] = 'MIME-Version: 1.0';
			$headers_mail[] = 'Content-type: text/html; charset=utf-8';
			$headers_mail[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			if($configMailSendActiveSav == "1"){
				mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers_mail));
			}


			// Update month of email
			$result = mysqli_query($link, "UPDATE $t_exercise_search_queries SET query_email_sendt_month=$month WHERE query_id=$get_current_query_id") or die(mysqli_error($link));

			echo"
			<div class=\"info\"><p>Sorry, there where no results. Our moderator <em>$get_moderator_user_name</em> have gotten a report on this and will create a exercise shortly.</p></div>
			";
		}


	}
	else{
		echo"<p>Search query is blank</p>";
	}
	echo"
	</div>
<!-- //Search -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>