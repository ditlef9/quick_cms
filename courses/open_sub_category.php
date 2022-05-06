<?php
/**
*
* File: courses/open_sub_category.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "201905032238";
$layoutNumberOfColumn = "2";
$layoutCommentsActive = "0";

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
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_modules_contents 	 = $mysqlPrefixSav . "courses_modules_contents";
$t_courses_modules_contents_read = $mysqlPrefixSav . "courses_modules_contents_read";
$t_courses_modules_contents_comments	= $mysqlPrefixSav . "courses_modules_contents_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
$main_category_id_mysql = quote_smart($link, $main_category_id);
if(isset($_GET['sub_category_id'])){
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
$sub_category_id_mysql = quote_smart($link, $sub_category_id);


/*- Content ---------------------------------------------------------- */

// Title
$l_mysql = quote_smart($link, $l);
$query = "SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_courses_title_translation_id, $get_current_courses_title_translation_title) = $row;
if($get_current_courses_title_translation_id == ""){
	mysqli_query($link, "INSERT INTO $t_courses_title_translations
	(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
	VALUES 
	(NULL, 'Courses', $l_mysql)")
	or die(mysqli_error($link));
	$get_current_courses_title_translation_title = "Courses";
}


// Sub category 
$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id FROM $t_courses_categories_sub WHERE sub_category_id=$sub_category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id) = $row;
if($get_current_sub_category_id == ""){

	/*- Header ----------------------------------------------------------- */
	$website_title = "Server error 404 - $get_current_courses_title_translation_title";
	include("$root/_webdesign/header.php");

	echo"
	<h1>Server error 404</h1>
	<p>Sub category not found.</p>
	";
}
else{



	// Main category 
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description FROM $t_courses_categories_main WHERE main_category_id=$get_current_sub_category_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description ) = $row;
	if($get_current_main_category_id == ""){
		/*- Header ----------------------------------------------------------- */
		$website_title = "Server error 404 - $get_current_courses_title_translation_title";
		include("$root/_webdesign/header.php");

		echo"
		<h1>Server error 404</h1>
		<p>Main category not found.</p>
		";
	}
	else{
		/*- Header ----------------------------------------------------------- */
		$website_title = "$get_current_sub_category_title - $get_current_main_category_title - $get_current_courses_title_translation_title";
		include("$root/_webdesign/header.php");

		echo"
		<h1>$get_current_sub_category_title</h1>

		<!-- Where am I? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$get_current_courses_title_translation_title</a>
		&gt;
		<a href=\"open_main_category.php?main_category_id=$get_current_main_category_id&amp;l=$l\">$get_current_main_category_title</a>
		&gt;
		<a href=\"open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\">$get_current_sub_category_title</a>
		</p>
		<!-- //Where am I? -->
		";

		// Get all courses
		$l_mysql = quote_smart($link, $l);
		$query = "SELECT course_id, course_title, course_title_clean, course_front_page_intro, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_image_file, course_image_thumb, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times FROM $t_courses_index WHERE course_sub_category_id=$get_current_sub_category_id AND course_language=$l_mysql ORDER BY course_read_times DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_course_id, $get_course_title, $get_course_title_clean, $get_course_front_page_intro, $get_course_main_category_id, $get_course_main_category_title, $get_course_sub_category_id, $get_course_sub_category_title, $get_course_image_file, $get_course_image_thumb, $get_course_modules_count, $get_course_lessons_count, $get_course_quizzes_count, $get_course_users_enrolled_count, $get_course_read_times) = $row;
			echo"
			<table style=\"width: 100%;\">
			  <tr>";
			if(file_exists("$root/$get_course_title_clean/_gfx/$get_course_image_thumb")){
			echo"
		   <td style=\"width: 210px;vertical-align:top;padding-right: 15px;\">
			<p>
			<a href=\"$root/$get_course_title_clean/index.php?l=$l\"><img src=\"$root/$get_course_title_clean/_gfx/$get_course_image_thumb\" alt=\"$get_course_image_thumb\" class=\"courses_img_thumb\" /></a>
			</p>
		   </td>
		";
			}
			echo"
		   <td style=\"vertical-align:top;padding-right: 10px;\">
			<p class=\"courses_sub_category\">
			<a href=\"open_sub_category.php?main_category_id=$get_course_main_category_id&amp;sub_category_id=$get_course_sub_category_id&amp;l=$l\" class=\"courses_sub_category\">$get_course_sub_category_title</a>
			</p>

			<p class=\"courses_course_title\">
			<a href=\"$root/$get_course_title_clean/index.php?l=$l\">$get_course_title</a>
			</p>

			<p class=\"courses_course_numbers\">
			$get_course_modules_count modules
			&middot; 
			$get_course_lessons_count lessons 
			&middot; 
			$get_course_read_times readers
			</p>
		   </td>
		   <td style=\"vertical-align:top;padding-right: 10px;\">
			<div class=\"courses_users_enrolled\">
				<p class=\"courses_users_enrolled_number\">$get_course_users_enrolled_count</p>
				<p class=\"courses_users_enrolled_text\">users enrolled</p>
			</div>
		   </td>
		 </tr>
		</table>
		<div class=\"courses_after\"></div>
		";
		}
		echo"
		<p>
		&nbsp;
		</p>
		";
	} // main category found
} // sub category found

/*- Footer ----------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>