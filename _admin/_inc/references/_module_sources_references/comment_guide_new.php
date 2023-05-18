<?php
/**
*
* File: references/comment_guide_new.php
* Version 3.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_references_liquidbase 	 = $mysqlPrefixSav . "references_liquidbase";


$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 	= $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_comments	= $mysqlPrefixSav . "references_index_guides_comments";

$t_stats_comments_per_year 		= $mysqlPrefixSav . "stats_comments_per_year";
$t_stats_comments_per_month 		= $mysqlPrefixSav . "stats_comments_per_month";
$t_stats_comments_per_week		= $mysqlPrefixSav . "stats_comments_per_week";


/*- Translation ------------------------------------------------------------------------------ */




/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['reference_id'])) {
	$reference_id = $_GET['reference_id'];
	$reference_id = strip_tags(stripslashes($reference_id));
}
else{
	$reference_id = "";
}
if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = strip_tags(stripslashes($group_id));
}
else{
	$group_id = "";
}
if(isset($_GET['guide_id'])) {
	$guide_id = $_GET['guide_id'];
	$guide_id = strip_tags(stripslashes($guide_id));
}
else{
	$guide_id = "";
}
if($guide_id != ""){
	// Search for guide
	$reference_id_mysql = quote_smart($link, $reference_id);
	$group_id_mysql = quote_smart($link, $group_id);
	$guide_id_mysql = quote_smart($link, $guide_id);
	$query = "SELECT guide_id, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_number, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql AND guide_group_id=$group_id_mysql AND guide_reference_id=$reference_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_guide_id, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_title_short, $get_current_guide_title_length, $get_current_guide_number, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_comments) = $row;

	if($get_current_guide_id != ""){
		// Get reference
		$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_id=$get_current_guide_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
			list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;

		// Get group
		$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$get_current_guide_group_id AND group_reference_id=$get_current_guide_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;

		/*- Header ----------------------------------------------------------- */
		$website_title = "$get_current_guide_title - $l_new_comment";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");


		// Can I write a comment?
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

			// Find me
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias) = $row;


			// Get my photo
			$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_photo_id, $get_photo_destination) = $row;


			// Check anti spam
			$can_write_comment = 1;
			$query = "SELECT comment_id, comment_time FROM $t_references_index_guides_comments WHERE comment_user_id=$my_user_id_mysql ORDER BY comment_id DESC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_comment_id, $get_comment_time) = $row;
			if($get_comment_id != ""){
				$time = time();

				$diff = $time-$get_comment_time;
	
				if($diff < 120){
					echo"
					<h1>$l_hello</h1>
					<div class=\"info\">
						<p><b>$l_anti_spam</b><br />
						$l_please_wait_five_minutes_before_posting_a_new_comment</p>
					</div>
					";
					$can_write_comment = 0;
				}
			}



			if($can_write_comment == 1){
				if($process == "1"){

					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					if(empty($inp_text)){
						
						$url = "comment_guide_new.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id&l=$l&ft=error&fm=missing_text";
						header("Location: $url");
						exit;
					}

					// lang
					$l_mysql = quote_smart($link, $l);

					// Datetime and time
					$datetime = date("Y-m-d H:i:s");
					$time = time();

					// Datetime print
					$year = substr($datetime, 0, 4);
					$month = substr($datetime, 5, 2);
					$day = substr($datetime, 8, 2);

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

					$inp_comment_date_print = "$day $month_saying $year";

					// Alias
					$inp_comment_user_alias_mysql = quote_smart($link, $get_my_user_alias);

					// Image
					$inp_comment_user_image_path_mysql = quote_smart($link, "_uploads/users/images/$get_my_user_id");

					// Image make a thumb
					if($get_photo_destination != ""){
						$inp_new_x = 65; // 950
						$inp_new_y = 65; // 640
						$thumb_full_path = "$root/_uploads/users/images/$get_my_user_id/user_" . $get_my_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
						if(!(file_exists("$thumb_full_path"))){
							resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_photo_destination", "$thumb_full_path");
						}
						$inp_comment_user_image_file = "user_" . $get_my_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
					}
					else{
						$inp_comment_user_image_file = "";
					}
					$inp_comment_user_image_file_mysql = quote_smart($link, $inp_comment_user_image_file);
	
					// Ip 
					$inp_ip = $_SERVER['REMOTE_ADDR'];
					$inp_ip = output_html($inp_ip);
					$inp_ip_mysql = quote_smart($link, $inp_ip);

					$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					$inp_hostname = output_html($inp_hostname);
					$inp_hostname_mysql = quote_smart($link, $inp_hostname);

					$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
					$inp_user_agent = output_html($inp_user_agent);
					$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);


					$inp_comment_reference_title_mysql = quote_smart($link, $get_current_reference_title);
					$inp_comment_group_title_mysql = quote_smart($link, $get_current_group_title);
					$inp_comment_guide_title_mysql = quote_smart($link, $get_current_guide_title);

					mysqli_query($link, "INSERT INTO $t_references_index_guides_comments
					(comment_id, comment_reference_id, comment_reference_title, comment_group_id, comment_group_title, 
					comment_guide_id, comment_guide_title, comment_language, comment_approved, comment_datetime, 
					comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, 
					comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, 
					comment_text, comment_marked_as_spam) 
					VALUES 
					(NULL, $get_current_reference_id, $inp_comment_reference_title_mysql, $get_current_group_id, $inp_comment_group_title_mysql, 
					$get_current_guide_id, $inp_comment_guide_title_mysql, $l_mysql, '1', '$datetime', 
					'$time', '$inp_comment_date_print', '$get_my_user_id', $inp_comment_user_alias_mysql, $inp_comment_user_image_path_mysql, 
					$inp_comment_user_image_file_mysql, $inp_ip_mysql, $inp_hostname_mysql, $inp_user_agent_mysql, '',
					$inp_text_mysql, '0')")
					or die(mysqli_error($link));
				
					// Get comment id
					$query = "SELECT comment_id FROM $t_references_index_guides_comments WHERE comment_time='$time'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_comment_id) = $row;


				

					// Email to moderators
					$read_comment_url = "$configSiteURLSav/$get_current_reference_title_clean/$get_current_group_title_clean/$get_current_guide_title_clean.php?reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l#comment$get_comment_id";

					$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;
						
						if($get_my_user_email != "$get_mod_user_email"){
							$subject = "$get_current_guide_title $l_new_comment_lowercase ($inp_comment_date_print)";
						

							$message = "<html>\n";
							$message = $message. "<head>\n";
							$message = $message. "  <title>$subject</title>\n";
							$message = $message. " </head>\n";
							$message = $message. "<body>\n";

							$message = $message. "<p>$l_hello,</p>\n";

							$message = $message. "<p>\n";
							$message = $message. "$l_there_is_a_new_comment_to_the_reference_guide $get_current_guide_title $l_at_lowercase $configWebsiteTitleSav.<br />\n";
							$message = $message. "$l_follow_the_link_to_read_the_comment:<br />\n";
							$message = $message. "<a href=\"$read_comment_url\">$read_comment_url</a>\n";
							$message = $message. "</p>\n";

							$message = $message. "<p>\n";
							$message = $message. "--<br />\n";
							$message = $message. "$l_regards<br />\n";
							$message = $message. "$configFromNameSav<br />\n";
							$message = $message. "$l_email: $configFromEmailSav<br />\n";
							$message = $message. "$l_web: $configWebsiteTitleSav\n";
							$message = $message. "</p>";

							$message = $message. "</body>\n";
							$message = $message. "</html>\n";

							$headers_mail_mod = array();
							$headers_mail_mod[] = 'MIME-Version: 1.0';
							$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
							$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";

							mail($get_mod_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));
						}
					} // while e-mail



				// Stats :: Comments
				$year = date("Y");
				$month = date("m");
				$month_full = date("F");
				$month_short = date("M");
				$week = date("W");

				// Stats :: Comments :: Year
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_year WHERE stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_year 
					(stats_comments_id, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_year 
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Stats :: Comments :: Month
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_month WHERE stats_comments_month='$month' AND stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_month 
					(stats_comments_id, stats_comments_month, stats_comments_month_full, stats_comments_month_short, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_month 
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Stats :: Comments :: Week
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_week WHERE stats_comments_week='$week' AND stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_week 
					(stats_comments_id, stats_comments_week, stats_comments_month, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $week, $month, $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_week
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

					// Header
					$url = "$root/$get_current_reference_title_clean/$get_current_group_title_clean/$get_current_guide_title_clean.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id&l=$l&ft=success&fm=comment_saved#comment$get_comment_id";
					header("Location: $url");
					exit;

				

				} // process == 1
			
        			echo" 
				<h1>$l_new_comment</h1>

			
				<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = str_replace("_", " ", $fm);
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- You are here -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"$root/$get_current_reference_title_clean/$get_current_group_title_clean/$get_current_guide_title_clean.php?reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l\">$get_current_guide_title</a>
					&gt;
					<a href=\"comment_guide_new.php?reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l\">$l_new_comment</a>

					</p>
				<!-- //You are here -->
			
				<!-- New comment form -->

					<form method=\"post\" action=\"comment_guide_new.php?reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_text\"]').focus();
						});
						</script>
					<!-- //Focus -->

					<p><b>$l_comment:</b><br />
					<textarea name=\"inp_text\" rows=\"8\" cols=\"30\" class=\"comment_textarea\" style=\"width: 99%;\">";
					if(isset($_GET['inp_text'])) { $inp_text = $_GET['inp_text']; $inp_text = strip_tags(stripslashes($inp_text)); echo"$inp_text"; } echo"</textarea>
					</p>

					<p>
					<input type=\"submit\" value=\"$l_post_comment\" class=\"btn_default\" />
					</p>
					</form>
				<!-- //New comment form -->
				";

			} // can write comment
		} // logged in
		else{
			echo"
			<h1>
			<img src=\"$root/courses/_images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
			Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/courses/new_comment_to_lesson.php?course_id=$get_current_course_id&amp;module_id=$get_module_id&amp;lesson_id=$get_lesson_id&amp;l=$l\">
			";
		}
	} // content not found
	else{
		/*- Header ----------------------------------------------------------- */
		$website_title = "Server error 404 #1";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"<p>Server error 404 #1</p>";
	}
} // not content
else{

	/*- Header ----------------------------------------------------------- */
	$website_title = "Server error 404 #2";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Server error 404 #2</p>";
}


/*- Footer ----------------------------------------------------------- */
include("$root/_webdesign/footer.php");





?>