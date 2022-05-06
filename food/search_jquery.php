<?php 
/**
*
* File: food/search_jquery.php
* Version 1.0.0
* Date 15:38 21.01.2018
* Copyright (c) 2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Functions ------------------------------------------------------------------------ */
$root = "..";
include("../_admin/website_config.php");


/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);




/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");

/*- MySQL Tables -------------------------------------------------------------------- */
$t_food_index	 	= $mysqlPrefixSav . "food_index";
$t_food_queries 	= $mysqlPrefixSav . "food_queries";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l']) OR isset($_POST['l'])) {
	if(isset($_GET['l'])){
		$l = $_GET['l'];
	}
	else{
		$l = $_POST['l'];
	}
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
$l_mysql = quote_smart($link, $l);

if(isset($_GET['order_by']) OR isset($_POST['order_by'])) {
	if(isset($_GET['order_by'])){
		$order_by = $_GET['order_by'];
	}
	else{
		$order_by = $_POST['order_by'];
	}
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method']) OR isset($_POST['order_method'])) {
	if(isset($_GET['order_method'])){
		$order_method = $_GET['order_method'];
	}
	else{
		$order_method = $_POST['order_method'];
	}
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}



/*- Language ------------------------------------------------------------------------ */
if(file_exists("../_admin/_translations/site/$l/food/ts_index.php")){
	include("../_admin/_translations/site/$l/food/ts_index.php");
}
else{
	echo"Unknown l";
	die;
}


/*- Table exists? -------------------------------------------------------------------- */
$query = "SELECT * FROM $t_food_queries LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"Table created";
	mysqli_query($link, "CREATE TABLE $t_food_queries(
	 query_id INT NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY(query_id), 
	 query_name VARCHAR(90) NOT NULL,
	 query_times BIGINT,
	 query_last_use DATETIME,
	 query_hidden INT)")
	 or die(mysql_error());
}

/*- Adapter view ------------------------------------------------------------------- */
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	
	$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
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
}


/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['search_query'])) {
	$search_query = $_GET['search_query'];
	$search_query = trim($search_query);

	$search_query = str_replace("å", "|aring;", $search_query);
	$search_query = str_replace("æ", "|aelig;", $search_query);
	$search_query = str_replace("Å", "|Aring;", $search_query);
	$search_query = str_replace("Æ", "|Aelig;", $search_query);
	$search_query = str_replace("À", "|#192;", $search_query);
	$search_query = str_replace("Á", "|#193;", $search_query);
	$search_query = str_replace("Â", "|#194;", $search_query);
	$search_query = str_replace("Ã", "|#195;", $search_query);
	$search_query = str_replace("Ä", "|#196;", $search_query);
	$search_query = str_replace("Å", "|#197;", $search_query);
	$search_query = str_replace("Æ", "|#198;", $search_query);
	$search_query = str_replace("Ç", "|#199;", $search_query);
	$search_query = str_replace("È", "|#200;", $search_query);
	$search_query = str_replace("É", "|#201;", $search_query);
	$search_query = str_replace("Ê", "|#202;", $search_query);
	$search_query = str_replace("Ë", "|#203;", $search_query);
	$search_query = str_replace("Ì", "|#204;", $search_query);
	$search_query = str_replace("Í", "|#205;", $search_query);
	$search_query = str_replace("Î", "|#206;", $search_query);
	$search_query = str_replace("Ï", "|#207;", $search_query);
	$search_query = str_replace("Ð", "|#208;", $search_query);
	$search_query = str_replace("Ñ", "|#209;", $search_query);
	$search_query = str_replace("Ò", "|#210;", $search_query);
	$search_query = str_replace("Ó", "|#211;", $search_query);
	$search_query = str_replace("Ô", "|#212;", $search_query);
	$search_query = str_replace("Õ", "|#213;", $search_query);
	$search_query = str_replace("Ö", "|#214;", $search_query);
	$search_query = str_replace("×", "|#215;", $search_query);  
	$search_query = str_replace("Ø", "|#216;", $search_query);
	$search_query = str_replace("Ø", "|Oslash;", $search_query);
	$search_query = str_replace("ø", "|oslash;", $search_query);
	$search_query = str_replace("Ù", "|#217;", $search_query);
	$search_query = str_replace("Ú", "|#218;", $search_query);
	$search_query = str_replace("Û", "|#219;", $search_query);
	$search_query = str_replace("Ü", "|#220;", $search_query);
	$search_query = str_replace("Ý", "|#221;", $search_query);
	$search_query = str_replace("Þ", "|#222;", $search_query);
			$search_query = str_replace("ß", "|#223;", $search_query);
			$search_query = str_replace("à", "|#224;", $search_query);
			$search_query = str_replace("á", "|#225;", $search_query);
			$search_query = str_replace("â", "|#226;", $search_query);
			$search_query = str_replace("ã", "|#227;", $search_query);
			$search_query = str_replace("ä", "|#228;", $search_query);
			$search_query = str_replace("å", "|#229;", $search_query);
			$search_query = str_replace("æ", "|#230;", $search_query);
			$search_query = str_replace("ç", "|#231;", $search_query);
			$search_query = str_replace("è", "|#232;", $search_query);
			$search_query = str_replace("é", "|#233;", $search_query);
			$search_query = str_replace("ê", "|#234;", $search_query);
			$search_query = str_replace("ë", "|#235;", $search_query);
			$search_query = str_replace("ì", "|#236;", $search_query);
			$search_query = str_replace("í", "|#237;", $search_query);
			$search_query = str_replace("î", "|#238;", $search_query);
			$search_query = str_replace("ï", "|#239;", $search_query);
			$search_query = str_replace("ð", "|#240;", $search_query);
			$search_query = str_replace("ñ", "|#241;", $search_query);
			$search_query = str_replace("ñ", "|ntilde;", $search_query);
			$search_query = str_replace("ò", "|#242;", $search_query);
			$search_query = str_replace("ó", "|#243;", $search_query);
			$search_query = str_replace("ô", "|#244;", $search_query);
			$search_query = str_replace("õ", "|#245;", $search_query);
			$search_query = str_replace("ö", "|#246;", $search_query);
			$search_query = str_replace("÷", "|#247;", $search_query); 
			$search_query = str_replace("ø", "|#248;", $search_query);
			$search_query = str_replace("ù", "|#249;", $search_query);
			$search_query = str_replace("ú", "|#250;", $search_query);
			$search_query = str_replace("û", "|#251;", $search_query);
			$search_query = str_replace("ü", "|#252;", $search_query);
			$search_query = str_replace("ý", "|#253;", $search_query);
			$search_query = str_replace("þ", "|#254;", $search_query);
			$search_query = str_replace("ÿ", "|#255;", $search_query);

	$search_query = strtolower($search_query);
	$search_query = output_html($search_query);



	$search_query = str_replace("|aring;", "&aring;", $search_query);
	$search_query = str_replace("|aelig;", "&aelig;", $search_query);
	$search_query = str_replace("|Aring;", "Å", $search_query);
	$search_query = str_replace("|Aelig;", "Æ", $search_query);
	$search_query = str_replace("|#192;", "À", $search_query);
	$search_query = str_replace("|#193;", "Á", $search_query);
	$search_query = str_replace("|#194;", "Â", $search_query);
	$search_query = str_replace("|#195;", "Ã", $search_query);
	$search_query = str_replace("|#196;", "Ä", $search_query);
	$search_query = str_replace("|#197;", "Å", $search_query);
	$search_query = str_replace("|#198;", "Æ", $search_query);
	$search_query = str_replace("|#199;", "Ç", $search_query);
	$search_query = str_replace("|#200;", "È", $search_query);
	$search_query = str_replace("|#201;", "É", $search_query);
	$search_query = str_replace("|#202;", "Ê", $search_query);
	$search_query = str_replace("|#203;", "Ë", $search_query);
	$search_query = str_replace("|#204;", "Ì", $search_query);
	$search_query = str_replace("|#205;", "Í", $search_query);
	$search_query = str_replace("|#206;", "Î", $search_query);
	$search_query = str_replace("|#207;", "Ï", $search_query);
	$search_query = str_replace("|#208;", "Ð", $search_query);
	$search_query = str_replace("|#209;", "Ñ", $search_query);
	$search_query = str_replace("|#210;", "Ò", $search_query);
	$search_query = str_replace("|#211;", "Ó", $search_query);
	$search_query = str_replace("|#212;", "Ô", $search_query);
	$search_query = str_replace("|#213;", "Õ", $search_query);
	$search_query = str_replace("|#214;", "Ö", $search_query);
	$search_query = str_replace("|#215;", "×", $search_query);  
	$search_query = str_replace("|#216;", "Ø", $search_query);
	$search_query = str_replace("|Oslash;", "Ø", $search_query);
	$search_query = str_replace("|oslash;", "&oslash;", $search_query);
	$search_query = str_replace("|oslash;", "ø", $search_query);
	$search_query = str_replace("|#217;", "Ù", $search_query);
	$search_query = str_replace("|#218;", "Ú", $search_query);
	$search_query = str_replace("|#219;", "Û", $search_query);
	$search_query = str_replace("|#220;", "Ü", $search_query);
	$search_query = str_replace("|#221;", "Ý", $search_query);
	$search_query = str_replace("|#222;", "Þ", $search_query);
	$search_query = str_replace("|#223;", "ß", $search_query);
	$search_query = str_replace("|#224;", "à", $search_query);
	$search_query = str_replace("|#225;", "á", $search_query);
	$search_query = str_replace("|#226;", "â", $search_query);
	$search_query = str_replace("|#227;", "ã", $search_query);
	$search_query = str_replace("|#228;", "ä", $search_query);
	$search_query = str_replace("|#229;", "å", $search_query);
	$search_query = str_replace("|#230;", "æ", $search_query);
	$search_query = str_replace("|#231;", "ç", $search_query);
	$search_query = str_replace("|#232;", "è", $search_query);
	$search_query = str_replace("|#233;", "é", $search_query);
	$search_query = str_replace("|#234;", "ê", $search_query);
	$search_query = str_replace("|#235;", "ë", $search_query);
	$search_query = str_replace("|#236;", "ì", $search_query);
	$search_query = str_replace("|#237;", "í", $search_query);
	$search_query = str_replace("|#238;", "î", $search_query);
	$search_query = str_replace("|#239;", "ï", $search_query);
	$search_query = str_replace("|#240;", "ð", $search_query);
	$search_query = str_replace("|#241;", "ñ", $search_query);
	$search_query = str_replace("|ntilde;", "ñ", $search_query);
	$search_query = str_replace("|#242;", "ò", $search_query);
	$search_query = str_replace("|#243;", "ó", $search_query);
	$search_query = str_replace("|#244;", "ô", $search_query);
	$search_query = str_replace("|#245;", "õ", $search_query);
	$search_query = str_replace("|#246;", "ö", $search_query);
	$search_query = str_replace("|#247;", "÷", $search_query); 
	$search_query = str_replace("|#248;", "ø", $search_query);
	$search_query = str_replace("|#249;", "ù", $search_query);
	$search_query = str_replace("|#250;", "ú", $search_query);
	$search_query = str_replace("|#251;", "û", $search_query);
	$search_query = str_replace("|#252;", "ü", $search_query);
	$search_query = str_replace("|#253;", "ý", $search_query);
	$search_query = str_replace("|#254;", "þ", $search_query);
	$search_query = str_replace("|#255;", "ÿ", $search_query);

	$search_query_mysql = quote_smart($link, $search_query);

	$search_query_clean = clean($search_query);

	$inp_datetime = date("Y-m-d H:i:s");
	if($search_query != ""){
		// Check for hacker
		include("$root/_admin/_functions/look_for_hacker_in_string.php");


		$query = "SELECT query_name, query_times FROM $t_food_queries WHERE query_name=$search_query_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_query_name, $get_query_times) = $row;

		if($get_query_name == ""){
			// Insert
			$insert_error = "0";
			mysqli_query($link, "INSERT INTO $t_food_queries
			(query_name, query_times, query_last_use) 
			VALUES
			($search_query_mysql, '1', '$inp_datetime') ")
			or $insert_error = 1;

		}
		else{
			$inp_query_times = $get_query_times+1;

			$result = mysqli_query($link, "UPDATE $t_food_queries SET query_times='$inp_query_times', query_last_use='$inp_datetime' WHERE query_name=$search_query_mysql");
		}



		// Ready for MySQL search
		$search_query_mysql = quote_smart($link, $search_query);

		$search_query_like = "%" . $search_query . "%";
		$search_query_like_mysql = quote_smart($link, $search_query_like);

		$search_query_clean = "" . $search_query_clean . "%";
		$search_query_clean_mysql = quote_smart($link, $search_query_clean);

		// Set layout
		$x = 0;

		// Query :: 1 :: Exact search
		$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql AND ";

		$query = $query . "(food_name=$search_query_mysql OR food_manufacturer_name=$search_query_mysql)";
		// Order
		if($order_by != ""){
			if($order_method == "desc"){
				$order_method_mysql = "DESC";
			}
			else{
				$order_method_mysql = "ASC";
			}

			if($order_by == "food_id" OR $order_by == "food_name" OR $order_by == "food_unique_hits" 
			OR $order_by == "food_energy" OR $order_by == "food_proteins" OR $order_by == "food_carbohydrates" OR $order_by == "food_fat"){
				$order_by_mysql = "$order_by";
			}
			else{
				$order_by_mysql = "food_id";
			}
			$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";
		

		}
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
	
			if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
				// Name saying
				$title = "$get_food_manufacturer_name $get_food_name";
				$check = strlen($title);
				if($check > 35){
					$title = substr($title, 0, 35);
					$title = $title . "...";
				}
				// Thumb small
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_small")) OR $get_food_thumb_a_small == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_small = $inp_thumb_name . "_thumb_132x132." . $ext;
					$inp_food_thumb_a_small_mysql = quote_smart($link, $get_food_thumb_a_small);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_small=$inp_food_thumb_a_small_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(132, 132, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_small");
				}

				// Thumb medium
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_medium")) OR $get_food_thumb_a_medium == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
					$inp_food_thumb_a_medium_mysql = quote_smart($link, $get_food_thumb_a_medium);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_medium=$inp_food_thumb_a_medium_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(200, 200, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_medium");
				}

				// Thumb large
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_large")) OR $get_food_thumb_a_large == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_large = $inp_thumb_name . "_thumb_420x283." . $ext;
					$inp_food_thumb_a_large_mysql = quote_smart($link, $get_food_thumb_a_large);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_large=$inp_food_thumb_a_large_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(420, 283, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_large");
				}

				if($x == 0){
					echo"
					<div class=\"clear\"></div>
					<div class=\"left_center_center_right_left\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
				elseif($x == 1){
					echo"
					<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
				elseif($x == 2){
					echo"
					<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
				elseif($x == 3){
					echo"
					<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
	

				echo"
				<p style=\"padding-bottom:5px;\">
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
				</p>
				";
	
				if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
				
					echo"
					<table style=\"margin: 0px auto;\">
					";
					if($get_current_view_hundred_metric == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$l_hundred</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_energy_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_fat_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_metric</span>
						  </td>
						  <td style=\"text-align: center;\">
						<span class=\"nutritional_number\">$get_food_proteins_metric</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
							<span class=\"nutritional_number\" title=\"$get_food_serving_size_metric $get_food_serving_size_measurement_metric\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_calculated_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_fat_calculated_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_metric</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_calculated_metric</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$l_per_eight_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_fat_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_us</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_us</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\" title=\"$get_food_serving_size_us $get_food_serving_size_measurement_us\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_calculated_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
							<span class=\"nutritional_number\">$get_food_fat_calculated_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_us</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_calculated_us</span>
						  </td>
						 </tr>
						";
					}
					echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;\">
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_calories_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_fat_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_carbohydrates_abbr_lowercase</span>
						  </td>
						  <td style=\"text-align: center;\">
							<span class=\"nutritional_number\">$l_proteins_abbr_lowercase</span>
						  </td>
						 </tr>
						</table>
					";
				}

				echo"
	
				</div>
				";

				// Increment
				$x++;
		
				// Reset
				if($x == 4){
					$x = 0;
				}
			} // has image
		} // while query 1



		// Query :: 2 :: Like search
		$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql AND ";

		$query = $query . "(food_name LIKE $search_query_like_mysql OR food_clean_name LIKE $search_query_clean_mysql OR food_manufacturer_name LIKE $search_query_like_mysql OR food_barcode LIKE $search_query_like_mysql)";
		// Order
		if($order_by != ""){
			if($order_method == "desc"){
				$order_method_mysql = "DESC";
			}
			else{
				$order_method_mysql = "ASC";
			}

			if($order_by == "food_id" OR $order_by == "food_name" OR $order_by == "food_unique_hits" 
			OR $order_by == "food_energy" OR $order_by == "food_proteins" OR $order_by == "food_carbohydrates" OR $order_by == "food_fat"){
				$order_by_mysql = "$order_by";
			}
			else{
				$order_by_mysql = "food_id";
			}
			$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";
		

		}
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
	
			if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
				// Name saying
				$title = "$get_food_manufacturer_name $get_food_name";
				$check = strlen($title);
				if($check > 35){
					$title = substr($title, 0, 35);
					$title = $title . "...";
				}
				// Thumb small
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_small")) OR $get_food_thumb_a_small == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_small = $inp_thumb_name . "_thumb_132x132." . $ext;
					$inp_food_thumb_a_small_mysql = quote_smart($link, $get_food_thumb_a_small);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_small=$inp_food_thumb_a_small_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(132, 132, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_small");
				}

				// Thumb medium
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_medium")) OR $get_food_thumb_a_medium == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
					$inp_food_thumb_a_medium_mysql = quote_smart($link, $get_food_thumb_a_medium);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_medium=$inp_food_thumb_a_medium_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(200, 200, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_medium");
				}

				// Thumb large
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_large")) OR $get_food_thumb_a_large == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_large = $inp_thumb_name . "_thumb_420x283." . $ext;
					$inp_food_thumb_a_large_mysql = quote_smart($link, $get_food_thumb_a_large);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_large=$inp_food_thumb_a_large_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(420, 283, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_large");
				}

				if($x == 0){
					echo"
					<div class=\"clear\"></div>
					<div class=\"left_center_center_right_left\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
				elseif($x == 1){
					echo"
					<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
				elseif($x == 2){
					echo"
					<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
				elseif($x == 3){
					echo"
					<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
					";
				}
	

				echo"
				<p style=\"padding-bottom:5px;\">
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
				</p>
				";
	
				if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
				
					echo"
					<table style=\"margin: 0px auto;\">
					";
					if($get_current_view_hundred_metric == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$l_hundred</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_energy_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_fat_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_metric</span>
						  </td>
						  <td style=\"text-align: center;\">
						<span class=\"nutritional_number\">$get_food_proteins_metric</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
							<span class=\"nutritional_number\" title=\"$get_food_serving_size_metric $get_food_serving_size_measurement_metric\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_calculated_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_fat_calculated_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_metric</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_calculated_metric</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$l_per_eight_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_fat_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_us</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_us</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\" title=\"$get_food_serving_size_us $get_food_serving_size_measurement_us\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_calculated_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
							<span class=\"nutritional_number\">$get_food_fat_calculated_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_us</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_calculated_us</span>
						  </td>
						 </tr>
						";
					}
					echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;\">
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_calories_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_fat_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_carbohydrates_abbr_lowercase</span>
						  </td>
						  <td style=\"text-align: center;\">
							<span class=\"nutritional_number\">$l_proteins_abbr_lowercase</span>
						  </td>
						 </tr>
						</table>
					";
				}

				echo"
	
				</div>
				";

				// Increment
				$x++;
		
				// Reset
				if($x == 4){
					$x = 0;
				}
			} // has image
		} // while query 2 
		if($x == 1){
			echo"
					<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
					<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
					<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
			<div class=\"clear\"></div>
			";
		}
		elseif($x == 2){
			echo"
					<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
					<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
			<div class=\"clear\"></div>
			";
		}
		elseif($x == 3){
			echo"
					<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
			<div class=\"clear\"></div>
			";
		}
		elseif($x == 4){
			echo"
			<div class=\"clear\"></div>
			";
		}
	} // q
	else{
		echo"Search query is blank";
	}
}
else{
	echo"No search_query";
}


?>