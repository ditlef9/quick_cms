<?php
/**
*
* File: recipes/food_user_adapted_view.php
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
include("_tables.php");



/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['set'])) {
	$set = $_GET['set'];
	$set = strip_tags(stripslashes($set));
	if($set != "hundred_metric" && $set != "serving" && $set != "pcs_metric" && $set != "eight_us" && $set != "pcs_us"){
		echo"Unknown set";
		die;
	}
}
else{
	echo"Missing set";
	die;
}
if(isset($_GET['referer'])) {
	$referer = $_GET['referer'];
	$referer = strip_tags(stripslashes($referer));
	if($referer != "index" && $referer != "categories_browse" && $referer != "edit_recipe_ingredients" && $referer != "browse_recipes_newest" && $referer != "browse_recipes_rating" && $referer != "browse_recipes_views" && $referer != "browse_recipes_comments" && $referer != "submit_recipe_step_2_group_and_elements" && $referer != "view_tag" && $referer != "cuisines_browse" && $referer != "occasions_browse"){
		echo"Unknown referer";
		die;
	}
}
else{
	echo"Missing referer";
	die;
}
if(isset($_GET['action'])){
	$action= $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if(isset($_GET['recipe_id'])){
	$recipe_id= $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}
if(isset($_GET['group_id'])){
	$group_id= $_GET['group_id'];
	$group_id = strip_tags(stripslashes($group_id));
}
else{
	$group_id = "";
}

if(isset($_GET['category_id'])){
	$category_id= $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }

// Variables
$year = date("Y");

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	
	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	
	if($get_current_view_id == ""){
		// Create default
		mysqli_query($link, "INSERT INTO $t_recipes_user_adapted_view 
				(view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_eight_us) 
				VALUES 
				(NULL, $my_user_id_mysql, 0, $year, 'metric', 1, 1, 0)")
				or die(mysqli_error($link));


		$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	}
}
else{
	// IP
	$my_user_ip = $_SERVER['REMOTE_ADDR'];
	$my_user_ip = output_html($my_user_ip);
	$my_user_ip_mysql = quote_smart($link, $my_user_ip);
	
	
	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;


	if($get_current_view_id == ""){
		// Create default
		mysqli_query($link, "INSERT INTO $t_recipes_user_adapted_view 
				(view_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_eight_us) 
				VALUES 
				(NULL, $my_user_ip_mysql, $year, 'metric', 1, 1, 0)")
				or die(mysqli_error($link));

		$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	}
}


// Update $get_current_view_id
$fm = "";
if($set == "hundred_metric"){
	if($get_current_view_hundred_metric == "1"){
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_hundred_metric=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_hundred_metric";
	}
	else{
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_hundred_metric=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_hundred_metric";
	}
}
elseif($set == "serving"){
	if($get_current_view_serving == "1"){
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_serving=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_serving";
	}
	else{
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_serving=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_serving";
	}
}
elseif($set == "pcs_metric"){
	if($get_current_view_pcs_metric == "1"){
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_pcs_metric=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_pcs_metric";
	}
	else{
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_pcs_metric=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_pcs_metric";
	}
}
elseif($set == "eight_us"){
	if($get_current_view_eight_us == "1"){
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_eight_us=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_eight_us";
	}
	else{
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_eight_us=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_eight_us";
	}
}
elseif($set == "pcs_us"){
	if($get_current_view_pcs_us == "1"){
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_pcs_us=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "deactivated_pcs_us";
	}
	else{
		mysqli_query($link, "UPDATE $t_recipes_user_adapted_view SET view_pcs_us=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
		$fm = "activaed_pcs_us";
	}
}
else{
	echo"Unknow request";
	die;
}

// Delete last year
$two_years_ago = $year-2;
mysqli_query($link, "DELETE FROM $t_recipes_user_adapted_view WHERE view_year=$two_years_ago") or die(mysqli_error($link));


// Header
if($referer == "categories_browse"){
	$url = "categories_browse.php?category_id=$category_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "cuisines_browse"){
	if(isset($_GET['cuisine_id'])){
		$cuisine_id= $_GET['cuisine_id'];
		$cuisine_id = strip_tags(stripslashes($cuisine_id));
		if(!(is_numeric($cuisine_id))){
			echo"Cuisine id not numeric";
			die;
		}
		$url = "cuisines_browse.php?cuisine_id=$cuisine_id&l=$l&ft=info&fm=$fm";
		header("Location: $url");
		exit;
	}
	else{
		echo"Missing cuisine id";
		die;
	}
}
elseif($referer == "occasions_browse"){
	if(isset($_GET['occasion_id'])){
		$occasion_id= $_GET['occasion_id'];
		$occasion_id = strip_tags(stripslashes($occasion_id));
		if(!(is_numeric($occasion_id))){
			echo"occasion id not numeric";
			die;
		}
		$url = "occasions_browse.php?occasion_id=$occasion_id&l=$l&ft=info&fm=$fm";
		header("Location: $url");
		exit;
	}
	else{
		echo"Missing occasion id";
		die;
	}
}
elseif($referer == "edit_recipe_ingredients"){
	$url = "edit_recipe_ingredients.php?action=add_items&recipe_id=$recipe_id&group_id=$group_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "submit_recipe_step_2_group_and_elements"){
	$url = "$referer.php?action=$action&recipe_id=$recipe_id&group_id=$group_id&l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}
elseif($referer == "view_tag"){
	if(isset($_GET['tag'])){
		$tag = $_GET['tag'];
		$tag = output_html($tag);
		$url = "$referer.php?action=$action&tag=$tag&l=$l&ft=info&fm=$fm";
		header("Location: $url");
		exit;
	}
	else{
		echo"No tag";
		die;
	}
}
else{
	$url = "$referer.php?l=$l&ft=info&fm=$fm";
	header("Location: $url");
	exit;
}

?>