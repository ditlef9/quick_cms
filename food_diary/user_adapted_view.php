<?php
/**
*
* File: food_diary/food_user_adapted_view.php
* Version 1.0
* Date 21:41 12.03.2021
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
	if($set != "system" && $set != "hundred_metric" && $set != "serving" && $set != "pcs_metric" && $set != "eight_us" && $set != "pcs_us"){
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
	if($referer != "food_diary_add_food" && $referer != "food_diary_add" && $referer != "food_diary_add_recipe"){
		echo"Unknown referer";
		die;
	}
}
else{
	echo"Missing referer";
	die;
}
if(isset($_GET['date'])) {
	$date = $_GET['date'];
	$date = strip_tags(stripslashes($date));
}
else{
	$date = "";
}
if(isset($_GET['hour_name'])) {
	$hour_name = $_GET['hour_name'];
	$hour_name = stripslashes(strip_tags($hour_name));
	if($hour_name != "breakfast" && $hour_name != "lunch" && $hour_name != "before_training" && $hour_name != "after_training" && $hour_name != "dinner" && $hour_name != "snacks" && $hour_name != "supper"){
		echo"Unknown hour name";
		die;
	}
}
else{
	echo"Missing hour name";
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


if(isset($_GET['action'])){
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
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
	
	$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;

	if($get_current_view_id == ""){
		// Create default
		mysqli_query($link, "INSERT INTO $t_food_diary_user_adapted_view 
				(view_id, view_user_id, view_system, view_hundred_metric, view_pcs_metric, 
				view_eight_us, view_pcs_us) 
				VALUES 
				(NULL, $my_user_id_mysql,  'metric', 1, 1,
				0, 0)")
				or die(mysqli_error($link));


		$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
	}


	// Update $get_current_view_id
	$fm = "";
	if($set == "system"){
		if($value == "all"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_system='all' WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "system_changed_to_all";
		}
		elseif($value == "metric"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_system='metric' WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "system_changed_to_metric";
		}
		else{
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_system='us' WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "system_changed_to_us";
		}
	}
	elseif($set == "hundred_metric"){
		if($get_current_view_hundred_metric == "1"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_hundred_metric=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "activaed_hundred_metric";
		}
		else{
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_hundred_metric=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "deactivated_hundred_metric";
		}
	}
	elseif($set == "serving"){
		if($get_current_view_serving == "1"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_serving=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "deactivated_serving";
		}
		else{
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_serving=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "activated_serving";
		}
	}
	elseif($set == "pcs_metric"){
		if($get_current_view_pcs_metric == "1"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_pcs_metric=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "activaed_pcs_metric";
		}
		else{
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_pcs_metric=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "deactivated_pcs_metric";
		}
	}
	elseif($set == "eight_us"){
		if($get_current_view_eight_us == "1"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_eight_us=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "activaed_eight_us";
		}
		else{
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_eight_us=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "deactivated_eight_us";
		}
	}
	elseif($set == "pcs_us"){
		if($get_current_view_pcs_us == "1"){
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_pcs_us=0 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "activaed_pcs_us";
		}
		else{
			mysqli_query($link, "UPDATE $t_food_diary_user_adapted_view SET view_pcs_us=1 WHERE view_id=$get_current_view_id") or die(mysqli_error($link));
			$fm = "deactivated_pcs_us";
		}
	}
	else{
		echo"Unknow request";
		die;
	}


	// Header
	if($referer == "food_diary_add_food"){
		if($action == ""){
			$url = "food_diary_add_food.php?date=$date&hour_name=$hour_name&l=$l&ft=info&fm=$fm&focus=null#adapter_view";
			header("Location: $url");
			exit;
		}
		elseif($action == "search"){
			if(isset($_GET['inp_entry_food_query'])){
				$inp_entry_food_query = $_GET['inp_entry_food_query'];
				$inp_entry_food_query = output_html($inp_entry_food_query);
			}
			else{
				$inp_entry_food_query = "";
			}

			$url = "food_diary_add_food.php?inp_entry_food_query=$inp_entry_food_query&action=search&date=$date&hour_name=$hour_name&l=$l&ft=info&fm=$fm&focus=null#adapter_view";
			header("Location: $url");
			exit;
		}
		elseif($action == "open_main_category"){
			$url = "food_diary_add_food.php?action=open_main_category&main_category_id=$main_category_id&date=$date&hour_name=$hour_name&l=$l&ft=info&fm=$fm&focus=null#adapter_view";
			header("Location: $url");
			exit;
		}
		else{
			echo"Unknown action";
		}
	}
	elseif($referer == "food_diary_add"){
		$url = "food_diary_add.php?date=$date&hour_name=$hour_name&l=$l&ft=info&fm=$fm&focus=null#adapter_view";
		header("Location: $url");
		exit;
	}
	elseif($referer == "food_diary_add_recipe"){
		$url = "$referer.php?date=$date&hour_name=$hour_name&l=$l&ft=info&fm=$fm&focus=null#adapter_view";
		header("Location: $url");
		exit;
	}
	else{
		echo"Unknown refererer...";
		die;
	}

} // logged in
else{
	echo"Not logged in";
	die;
}


?>