<?php
/*
* courses/navigation.php
*/

/*- Current page ---------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])){
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}



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

/*- Language ------------------------------------------ */


/*- Settings ------------------------------------------ */


/*- Variables ----------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}
if($include_as_navigation_main_mode == 0){

	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/courses/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "courses"){ echo" class=\"navigation_active\"";}echo">Courses</a></li>
	";
}


// Main Categories
$query_main = "SELECT main_category_id, main_category_title FROM $t_courses_categories_main  ORDER BY main_category_title ASC";
$result_sub = mysqli_query($link, $query_main);
while($row_sub = mysqli_fetch_row($result_sub)) {
	list($get_main_category_id, $get_main_category_title) = $row_sub;
	echo"		";
	echo"<li><a href=\"$root/courses/open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\""; if($get_main_category_id == "$main_category_id"){ echo" class=\"navigation_active\"";}echo">$get_main_category_title</a></li>\n";
} // while sub categories

// Sub Categories
// $query_sub = "SELECT sub_category_id, sub_category_title, sub_category_main_category_id FROM $t_courses_categories_sub ORDER BY sub_category_title ASC";
// $result_sub = mysqli_query($link, $query_sub);
// while($row_sub = mysqli_fetch_row($result_sub)) {
// 	list($get_sub_category_id, $get_sub_category_title, $get_sub_category_main_category_id) = $row_sub;
// 	echo"		";
// 	echo"<li><a href=\"$root/courses/open_sub_category.php?main_category_id=$get_sub_category_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$l\""; if($sub_category_id == "$get_sub_category_id"){ echo" class=\"navigation_active\"";}echo">$get_sub_category_title</a></li>\n";
// } // while sub categories



if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	";
}
?>