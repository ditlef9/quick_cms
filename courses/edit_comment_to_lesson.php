<?php

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


/*- Translation ------------------------------------------------------------------------------ */
// include("$root/_admin/_translations/site/$l/courses/ts_courses.php");




/*- Tables ---------------------------------------------------------------------------- */
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";

$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_lessons 	 	= $mysqlPrefixSav . "courses_lessons";
$t_courses_lessons_read 	= $mysqlPrefixSav . "courses_lessons_read";
$t_courses_lessons_comments	= $mysqlPrefixSav . "courses_lessons_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";



/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['course_id'])) {
	$course_id = $_GET['course_id'];
	$course_id = strip_tags(stripslashes($course_id));
}
else{
	$course_id = "";
}
if(isset($_GET['module_id'])) {
	$module_id = $_GET['module_id'];
	$module_id = strip_tags(stripslashes($module_id));
}
else{
	$module_id = "";
}
if(isset($_GET['lesson_id'])) {
	$lesson_id = $_GET['lesson_id'];
	$lesson_id = strip_tags(stripslashes($lesson_id));
}
else{
	$lesson_id = "";
}
if(isset($_GET['comment_id'])) {
	$comment_id = $_GET['comment_id'];
	$comment_id = strip_tags(stripslashes($comment_id));
}
else{
	$comment_id = "";
}


if(isset($_SESSION['user_id'])){
		// Search for content
	$course_id_mysql = quote_smart($link, $course_id);
	$module_id_mysql = quote_smart($link, $module_id);
	$lesson_id_mysql = quote_smart($link, $lesson_id);
	$query = "SELECT lesson_id, lesson_number, lesson_title, lesson_title_clean, lesson_description, lesson_content, lesson_course_id, lesson_course_title, lesson_module_id, lesson_module_title, lesson_read_times, lesson_read_times_ipblock, lesson_created_datetime, lesson_created_date_formatted, lesson_last_read_datetime, lesson_last_read_date_formatted FROM $t_courses_lessons WHERE lesson_id=$lesson_id_mysql AND lesson_course_id=$course_id_mysql AND lesson_module_id=$module_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_lesson_id, $get_current_lesson_number, $get_current_lesson_title, $get_current_lesson_title_clean, $get_current_lesson_description, $get_current_lesson_content, $get_current_lesson_course_id, $get_current_lesson_course_title, $get_current_lesson_module_id, $get_current_lesson_module_title, $get_current_lesson_read_times, $get_current_lesson_read_times_ipblock, $get_current_lesson_created_datetime, $get_current_lesson_created_date_formatted, $get_current_lesson_last_read_datetime, $get_current_lesson_last_read_date_formatted) = $row;

	if($get_current_lesson_id != ""){
		// Get course
		$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=$get_current_lesson_course_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

		// Get module
		$query = "SELECT module_id, module_course_id, module_course_title, module_number, module_title, module_title_clean, module_read_times, module_read_ipblock, module_created, module_updated, module_last_read_datetime, module_last_read_date_formatted FROM $t_courses_modules WHERE module_id=$get_current_lesson_module_id AND module_course_id=$get_current_course_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_module_id, $get_current_module_course_id, $get_current_module_course_title, $get_current_module_number, $get_current_module_title, $get_current_module_title_clean, $get_current_module_read_times, $get_current_module_read_ipblock, $get_current_module_created, $get_current_module_updated, $get_current_module_last_read_datetime, $get_current_module_last_read_date_formatted) = $row;


		// Search for comment
		$comment_id_mysql = quote_smart($link, $comment_id);
		$query = "SELECT comment_id, comment_course_id, comment_course_title, comment_module_id, comment_module_title, comment_lesson_id, comment_lesson_title, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_courses_lessons_comments WHERE comment_id=$comment_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_comment_id, $get_current_comment_course_id, $get_current_comment_course_title, $get_current_comment_module_id, $get_current_comment_module_title, $get_current_comment_lesson_id, $get_current_comment_lesson_title, $get_current_comment_language, $get_current_comment_approved, $get_current_comment_datetime, $get_current_comment_time, $get_current_comment_date_print, $get_current_comment_user_id, $get_current_comment_user_alias, $get_current_comment_user_image_path, $get_current_comment_user_image_file, $get_current_comment_user_ip, $get_current_comment_user_hostname, $get_current_comment_user_agent, $get_current_comment_title, $get_current_comment_text, $get_current_comment_rating, $get_current_comment_helpful_clicks, $get_current_comment_useless_clicks, $get_current_comment_marked_as_spam, $get_current_comment_spam_checked, $get_current_comment_spam_checked_comment) = $row;

		if($get_current_comment_id != ""){

			/*- Header ----------------------------------------------------------- */
			$website_title = "$get_current_lesson_title - $l_edit_comment";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");


			// Check access to comment
			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

			if($get_current_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				if($process == "1"){
					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					if(empty($inp_text)){
						$url = "edit_comment_to_lesson.php?comment_id=$get_current_comment_id&course_id=$course_id&module_id=$module_id&lesson_id=$lesson_id&l=$l&ft=error&fm=missing_text";
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


	
					// Ip 
					$inp_ip = $_SERVER['REMOTE_ADDR'];
					$inp_ip = output_html($inp_ip);
					$inp_ip_mysql = quote_smart($link, $inp_ip);

					$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					$inp_hostname = output_html($inp_hostname);
					$inp_hostname_mysql = quote_smart($link, $inp_hostname);

					$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
					$inp_user_agent = output_html($user_agent);
					$inp_user_agent_mysql = quote_smart($link, $user_agent);



					$result = mysqli_query($link, "UPDATE $t_courses_lessons_comments SET 
comment_datetime='$datetime', 
comment_time='$time', 
comment_date_print='$inp_comment_date_print', 
comment_user_ip=$inp_ip_mysql, 
comment_user_hostname=$inp_hostname_mysql, 
comment_user_agent=$inp_user_agent_mysql, 
comment_text=$inp_text_mysql
 WHERE comment_id=$get_current_comment_id") or die(mysqli_error($link));


					// Header
					$url = "$root/$get_current_course_title_clean/$get_current_module_title_clean/$get_current_lesson_title_clean.php?course_id=$get_current_course_id&module_id=$get_current_module_id&lesson_id=$get_current_lesson_id&l=$l&ft=success&fm=changes_saved#comment$get_current_comment_id";
					header("Location: $url");
					exit;

				} // process

				echo"
				<h1>$l_edit_comment</h1>

			
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
					<a href=\"$root/$get_current_course_title_clean/$get_current_module_title_clean/$get_current_lesson_title_clean.php?course_id=$get_current_course_id&amp;module_id=$get_current_module_id&amp;lesson_id=$get_current_lesson_id&amp;l=$l\">$get_current_lesson_title</a>
					&gt;
					<a href=\"edit_comment_to_lesson.php?course_id=$get_current_course_id&amp;module_id=$get_current_module_id&amp;lesson_id=$get_current_lesson_id&amp;comment_id=$get_current_comment_id&amp;l=$l\">$l_edit_comment</a>
					</p>
				<!-- //You are here -->


				<!-- Edit comment form -->

					<form method=\"post\" action=\"edit_comment_to_lesson.php?course_id=$get_current_course_id&amp;module_id=$get_current_module_id&amp;lesson_id=$get_current_lesson_id&amp;comment_id=$get_current_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_text\"]').focus();
						});
						</script>
					<!-- //Focus -->

					<p><b>$l_comment:</b><br />
					<textarea name=\"inp_text\" rows=\"8\" cols=\"30\" class=\"comment_textarea\" style=\"width:99%;\">";
					$get_current_comment_text = strip_tags(stripslashes($get_current_comment_text)); echo"$get_current_comment_text"; 
					echo"</textarea>
					</p>

					<p>
					<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
					</p>
					</form>
				<!-- //Edit comment form -->
				";
			} // access

		} // comment found
		else{

			/*- Header ----------------------------------------------------------- */
			$website_title = "$get_current_lesson_title - Server error 404";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");

			echo"Comment not found";
		} // comment not found
	} // content found
	else{
		/*- Header ----------------------------------------------------------- */
		$website_title = "Server error 404";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"Content not found";
	
	} // Content not found
} // logged in
else{
	/*- Header ----------------------------------------------------------- */
	$website_title = "Server error 403";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/edit_comment.php?comment_id=$comment_id\">
	";

} // not logged in

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>