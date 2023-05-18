<?php
/**
*
* File: food/food_user_adapted_view.php
* Version 1.0
* Date 21:48 09.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_food.php");



/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['set'])) {
	$set = $_GET['set'];
	$set = strip_tags(stripslashes($set));
	if($set != "system" && $set != "hundred_metric" && $set != "pcs_metric" && $set != "eight_us" && $set != "pcs_us" && $set != "metric" && $set != "us"){
		echo"Unknown set";
		die;
	}
}
else{
	echo"Missing set";
	die;
}
if(isset($_GET['value'])) {
	$value = $_GET['value'];
	$value = strip_tags(stripslashes($value));
	if($value != "1" && $value != "0" && $value != "all" && $value != "metric" && $value != "us"){
		echo"Unknown value";
		die;
	}
}
else{
	echo"Missing value";
	die;
}
if(isset($_GET['referer'])) {
	$referer = $_GET['referer'];
	$referer = strip_tags(stripslashes($referer));
	if($referer != "index" && $referer != "open_main_category" && $referer != "open_sub_category" && $referer != "open_sub_category_nutritional_facts_eu" && $referer != "open_sub_category_nutritional_facts_us" && $referer != "view_food" && $referer != "search"){
		echo"Unknown referer";
		die;
	}
}
else{
	echo"Missing referer";
	die;
}
if(isset($_GET['main_category_id'])){
	$main_category_id= $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
if(isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
	$food_id = strip_tags(stripslashes($food_id));
	
}
else{
	$food_id = "";
}
$food_id_mysql = quote_smart($link, $food_id);


// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;



/*- Scriptstart ---------------------------------------------------------------------------------- */

// Variables
$year = date("Y");

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	
	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	
	if($get_current_view_id == ""){
		// Create default
		mysqli_query($link, "INSERT INTO $t_food_user_adapted_view 
				(view_id, view_user_id, view_ip, view_year, view_system, 
				view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us) 
				VALUES 
				(NULL, $my_user_id_mysql, 0, $year, 'metric', 
				1, 1, 0, 0)")
				or die(mysqli_error($link));


		$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	}
}
else{
	// IP
	$my_user_ip = $_SERVER['REMOTE_ADDR'];
	$my_user_ip = output_html($my_user_ip);
	$my_user_ip_mysql = quote_smart($link, $my_user_ip);
	
	
	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;


	if($get_current_view_id == ""){
		// Create default
		mysqli_query($link, "INSERT INTO $t_food_user_adapted_view 
				(view_id, view_user_id, view_ip, view_year, view_system, 
				view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us) 
				VALUES 
				(NULL, 0, $my_user_ip_mysql, $year, 'metric', 
				1, 1, 0, 0)")
				or die(mysqli_error($link));

	
		$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	}
}


// Update $get_current_view_id
$fm = "";
if($set == "system"){
	if($value == "all"){
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_system='all' WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "system_changed_to_all";
	}
	elseif($value == "metric"){
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_system='metric' WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "system_changed_to_metric";
	}
	else{
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_system='us' WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "system_changed_to_us";
	}
}
elseif($set == "hundred_metric"){
	if($value == "1"){
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_hundred_metric=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_hundred_metric";
	}
	else{
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_hundred_metric=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_hundred_metric";
	}
}
elseif($set == "pcs_metric"){
	if($value == "1"){
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_pcs_metric=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_pcs_metric";
	}
	else{
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_pcs_metric=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_pcs_metric";
	}
}
elseif($set == "eight_us"){
	if($value == "1"){
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_eight_us=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_eight_us";
	}
	else{
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_eight_us=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_eight_us";
	}
}
elseif($set == "pcs_us"){
	if($value == "1"){
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_pcs_us=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_pcs_us";
	}
	else{
		mysqli_query($link, "UPDATE $t_food_user_adapted_view SET view_pcs_us=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_pcs_us";
	}
}
else{
	echo"Unknow request";
	die;
}

// Delete last year
$two_years_ago = $year-2;
mysqli_query($link, "DELETE FROM $t_food_user_adapted_view WHERE view_year=$two_years_ago") or die(mysqli_error($link));


// Header
if($referer == "index"){
	$url = "index.php?l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "open_main_category"){
	$url = "open_main_category.php?main_category_id=$main_category_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "open_sub_category"){
	$url = "open_sub_category.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "open_sub_category_nutritional_facts_eu"){
	$url = "open_sub_category_nutritional_facts_eu.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "open_sub_category_nutritional_facts_us"){
	$url = "open_sub_category_nutritional_facts_us.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "view_food"){
	$url = "view_food.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&food_id=$food_id&ft=info&fm=$fm#numbers";
	header("Location: $url");
	exit;
}
elseif($referer == "search"){
	if(isset($_GET['search_query'])) {
		$search_query = $_GET['search_query'];
		$search_query = output_html($search_query);
		if($search_query == "$l_search..."){
			$search_query = "";
		}
	}
	else{
		$search_query = "";
	}

	$url = "search.php?search_query=$search_query&ft=info&fm=$fm#numbers";
	header("Location: $url");
	exit;
}
else{
	echo"?";
	die;
}

?>