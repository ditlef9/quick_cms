<?php
/**
*
* File: _food/open_sub_category_nutritional_facts_us.php
* Version 1.0.0.
* Date 09:51 10.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
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


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_open_sub_category_nutritional_facts_x.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}

if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "score";
}
$a_order_method = "asc";
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method == "asc"){
		$a_order_method = "desc";
	}
	else{
		$order_method = "desc";
		$a_order_method = "asc";
	}
}
else{
	$order_method = "asc";
}
if(isset($_GET['show_hundred_metric'])) {
	$show_hundred_metric = $_GET['show_hundred_metric'];
	$show_hundred_metric = strip_tags(stripslashes($show_hundred_metric));
	if(!(is_numeric($show_hundred_metric))){
		echo"Not numeric";
		die;
	}
}
else{
	$show_hundred_metric = "1";
}
if(isset($_GET['show_pcs_us'])) {
	$show_pcs_us = $_GET['show_pcs_us'];
	$show_pcs_us = strip_tags(stripslashes($show_pcs_us));
	if(!(is_numeric($show_pcs_us))){
		echo"Not numeric";
		die;
	}
}
else{
	$show_pcs_us = "1";
}
if(isset($_GET['show_net_content'])) {
	$show_net_content = $_GET['show_net_content'];
	$show_net_content = strip_tags(stripslashes($show_net_content));
	if(!(is_numeric($show_net_content))){
		echo"Not numeric";
		die;
	}
}
else{
	$show_net_content = "1";
}


// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");

/*- Sub category -------------------------------------------------------------------------- */
// Select sub category
$sub_category_id_mysql = quote_smart($link, $sub_category_id);
$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_age_limit FROM $t_food_categories_sub WHERE sub_category_id=$sub_category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_age_limit) = $row;

if($get_current_sub_category_id== ""){
	$website_title = "Server error 404 - $get_current_title_value";
}
else{
	// Sub category Translation
	$query_t = "SELECT sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value, sub_category_translation_no_food, sub_category_unique_hits, sub_category_unique_hits_this_year, sub_category_unique_hits_this_year_year, sub_category_unique_hits_ip_block, sub_category_calories_min_100_g, sub_category_calories_med_100_g, sub_category_calories_max_100_g, sub_category_calories_p_ten_percentage_100_g, sub_category_calories_m_ten_percentage_100_g, sub_category_fat_min_100_g, sub_category_fat_med_100_g, sub_category_fat_max_100_g, sub_category_fat_p_ten_percentage_100_g, sub_category_fat_m_ten_percentage_100_g, sub_category_saturated_fat_min_100_g, sub_category_saturated_fat_med_100_g, sub_category_saturated_fat_max_100_g, sub_category_saturated_fat_p_ten_percentage_100_g, sub_category_saturated_fat_m_ten_percentage_100_g, sub_category_trans_fat_min_100_g, sub_category_trans_fat_med_100_g, sub_category_trans_fat_max_100_g, sub_category_trans_fat_p_ten_percentage_100_g, sub_category_trans_fat_m_ten_percentage_100_g, sub_category_monounsaturated_fat_min_100_g, sub_category_monounsaturated_fat_med_100_g, sub_category_monounsaturated_fat_max_100_g, sub_category_monounsaturated_fat_p_ten_percentage_100_g, sub_category_monounsaturated_fat_m_ten_percentage_100_g, sub_category_polyunsaturated_fat_min_100_g, sub_category_polyunsaturated_fat_med_100_g, sub_category_polyunsaturated_fat_max_100_g, sub_category_polyunsaturated_fat_p_ten_percentage_100_g, sub_category_polyunsaturated_fat_m_ten_percentage_100_g, sub_category_cholesterol_min_100_g, sub_category_cholesterol_med_100_g, sub_category_cholesterol_max_100_g, sub_category_cholesterol_p_ten_percentage_100_g, sub_category_cholesterol_m_ten_percentage_100_g, sub_category_carb_min_100_g, sub_category_carb_med_100_g, sub_category_carb_max_100_g, sub_category_carb_p_ten_percentage_100_g, sub_category_carb_m_ten_percentage_100_g, sub_category_carb_of_which_sugars_min_100_g, sub_category_carb_of_which_sugars_med_100_g, sub_category_carb_of_which_sugars_max_100_g, sub_category_carb_of_which_sugars_p_ten_percentage_100_g, sub_category_carb_of_which_sugars_m_ten_percentage_100_g, sub_category_added_sugars_min_100_g, sub_category_added_sugars_med_100_g, sub_category_added_sugars_max_100_g, sub_category_added_sugars_p_ten_percentage_100_g, sub_category_added_sugars_m_ten_percentage_100_g, sub_category_dietary_fiber_min_100_g, sub_category_dietary_fiber_med_100_g, sub_category_dietary_fiber_max_100_g, sub_category_dietary_fiber_p_ten_percentage_100_g, sub_category_dietary_fiber_m_ten_percentage_100_g, sub_category_proteins_min_100_g, sub_category_proteins_med_100_g, sub_category_proteins_max_100_g, sub_category_proteins_p_ten_percentage_100_g, sub_category_proteins_m_ten_percentage_100_g, sub_category_salt_min_100_g, sub_category_salt_med_100_g, sub_category_salt_max_100_g, sub_category_salt_p_ten_percentage_100_g, sub_category_salt_m_ten_percentage_100_g, sub_category_sodium_min_100_g, sub_category_sodium_med_100_g, sub_category_sodium_max_100_g, sub_category_sodium_p_ten_percentage_100_g, sub_category_sodium_m_ten_percentage_100_g FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_current_sub_category_id AND sub_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_sub_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value, $get_current_sub_category_translation_no_food, $get_current_sub_category_unique_hits, $get_current_sub_category_unique_hits_this_year, $get_current_sub_category_unique_hits_this_year_year, $get_current_sub_category_unique_hits_ip_block, $get_current_sub_category_calories_min_100_g, $get_current_sub_category_calories_med_100_g, $get_current_sub_category_calories_max_100_g, $get_current_sub_category_calories_p_ten_percentage_100_g, $get_current_sub_category_calories_m_ten_percentage_100_g, $get_current_sub_category_fat_min_100_g, $get_current_sub_category_fat_med_100_g, $get_current_sub_category_fat_max_100_g, $get_current_sub_category_fat_p_ten_percentage_100_g, $get_current_sub_category_fat_m_ten_percentage_100_g, $get_current_sub_category_saturated_fat_min_100_g, $get_current_sub_category_saturated_fat_med_100_g, $get_current_sub_category_saturated_fat_max_100_g, $get_current_sub_category_saturated_fat_p_ten_percentage_100_g, $get_current_sub_category_saturated_fat_m_ten_percentage_100_g, $get_current_sub_category_trans_fat_min_100_g, $get_current_sub_category_trans_fat_med_100_g, $get_current_sub_category_trans_fat_max_100_g, $get_current_sub_category_trans_fat_p_ten_percentage_100_g, $get_current_sub_category_trans_fat_m_ten_percentage_100_g, $get_current_sub_category_monounsaturated_fat_min_100_g, $get_current_sub_category_monounsaturated_fat_med_100_g, $get_current_sub_category_monounsaturated_fat_max_100_g, $get_current_sub_category_monounsaturated_fat_p_ten_percentage_100_g, $get_current_sub_category_monounsaturated_fat_m_ten_percentage_100_g, $get_current_sub_category_polyunsaturated_fat_min_100_g, $get_current_sub_category_polyunsaturated_fat_med_100_g, $get_current_sub_category_polyunsaturated_fat_max_100_g, $get_current_sub_category_polyunsaturated_fat_p_ten_percentage_100_g, $get_current_sub_category_polyunsaturated_fat_m_ten_percentage_100_g, $get_current_sub_category_cholesterol_min_100_g, $get_current_sub_category_cholesterol_med_100_g, $get_current_sub_category_cholesterol_max_100_g, $get_current_sub_category_cholesterol_p_ten_percentage_100_g, $get_current_sub_category_cholesterol_m_ten_percentage_100_g, $get_current_sub_category_carb_min_100_g, $get_current_sub_category_carb_med_100_g, $get_current_sub_category_carb_max_100_g, $get_current_sub_category_carb_p_ten_percentage_100_g, $get_current_sub_category_carb_m_ten_percentage_100_g, $get_current_sub_category_carb_of_which_sugars_min_100_g, $get_current_sub_category_carb_of_which_sugars_med_100_g, $get_current_sub_category_carb_of_which_sugars_max_100_g, $get_current_sub_category_carb_of_which_sugars_p_ten_percentage_100_g, $get_current_sub_category_carb_of_which_sugars_m_ten_percentage_100_g, $get_current_sub_category_added_sugars_min_100_g, $get_current_sub_category_added_sugars_med_100_g, $get_current_sub_category_added_sugars_max_100_g, $get_current_sub_category_added_sugars_p_ten_percentage_100_g, $get_current_sub_category_added_sugars_m_ten_percentage_100_g, $get_current_sub_category_dietary_fiber_min_100_g, $get_current_sub_category_dietary_fiber_med_100_g, $get_current_sub_category_dietary_fiber_max_100_g, $get_current_sub_category_dietary_fiber_p_ten_percentage_100_g, $get_current_sub_category_dietary_fiber_m_ten_percentage_100_g, $get_current_sub_category_proteins_min_100_g, $get_current_sub_category_proteins_med_100_g, $get_current_sub_category_proteins_max_100_g, $get_current_sub_category_proteins_p_ten_percentage_100_g, $get_current_sub_category_proteins_m_ten_percentage_100_g, $get_current_sub_category_salt_min_100_g, $get_current_sub_category_salt_med_100_g, $get_current_sub_category_salt_max_100_g, $get_current_sub_category_salt_p_ten_percentage_100_g, $get_current_sub_category_salt_m_ten_percentage_100_g, $get_current_sub_category_sodium_min_100_g, $get_current_sub_category_sodium_med_100_g, $get_current_sub_category_sodium_max_100_g, $get_current_sub_category_sodium_p_ten_percentage_100_g, $get_current_sub_category_sodium_m_ten_percentage_100_g) = $row_t;
	if($get_current_sub_category_translation_id == ""){
		echo"<p>Error could not find translation</p>";
		die;
	}

	// Find main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit FROM $t_food_categories_main WHERE main_category_id=$get_current_sub_category_parent_id";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit) = $row;

	
	// Main category translation
	$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_current_main_category_id AND main_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_id, $get_current_main_category_translation_value) = $row_t;

	
	// Title
	$website_title = "$get_current_sub_category_translation_value $l_nutritional_facts_lowercase USA - $get_current_main_category_translation_value - $get_current_title_value";

}

/*- Headers ---------------------------------------------------------------------------------- */
include("open_sub_category_nutritional_facts_include_header.php");

if($get_current_main_category_id == ""){
	echo"
	<h1>Server error 404</h1>
	<p>Category not found.</p>

	<p><a href=\"index.php?l=$l\">Categories</a></p>
	";
}
else{
	// Age limit?
	$get_current_restriction_show_food = 1;
	$get_current_restriction_show_image_a = 1;
	$get_current_restriction_show_image_b = 1;
	$get_current_restriction_show_image_c = 1;
	$get_current_restriction_show_image_d = 1;
	$get_current_restriction_show_image_e = 1;
	$get_current_restriction_show_smileys = 1;
	if($get_current_sub_category_age_limit == "1"){
		// Check if I have accepted 
		$inp_ip_mysql = quote_smart($link, $my_ip);
		$query_t = "SELECT accepted_id, accepted_country FROM $t_food_age_restrictions_accepted WHERE accepted_ip=$inp_ip_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_accepted_id, $get_accepted_country) = $row_t;
		
		if($get_accepted_id == ""){
			// Accept age restriction
			$get_current_restriction_show_food = 0;
			include("open_sub_category_show_age_restriction_warning.php");
		}
		else{
			// Can I see food and images?
			$country_mysql = quote_smart($link, $get_accepted_country);
			$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_show_food, restriction_show_image_a, restriction_show_image_b, restriction_show_image_c, restriction_show_image_d, restriction_show_image_e, restriction_show_smileys FROM $t_food_age_restrictions WHERE restriction_country_iso_two=$country_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_restriction_id, $get_current_restriction_country_name, $get_current_restriction_country_iso_two, $get_current_restriction_country_flag_path_16x16, $get_current_restriction_country_flag_16x16, $get_current_restriction_language, $get_current_restriction_age_limit, $get_current_restriction_title, $get_current_restriction_text, $get_current_restriction_show_food, $get_current_restriction_show_image_a, $get_current_restriction_show_image_b, $get_current_restriction_show_image_c, $get_current_restriction_show_image_d, $get_current_restriction_show_image_e, $get_current_restriction_show_smileys) = $row;

			if($get_current_restriction_id == ""){
				// Could not find country
				echo"<div class=\"error\"><p>Could not find country.</p></div>\n";
			}

			if($get_current_restriction_show_food == 0){
				echo"
				<h1 style=\"padding-bottom:0;margin-bottom:0;\">$get_current_food_manufacturer_name $get_current_food_name</h1>
				<p>$get_current_restriction_text</p>
				";
				
			}
		}
	}

	if($get_current_restriction_show_food == 1){
		echo"
		<!-- Headline -->
				<h1>$get_current_sub_category_translation_value $l_nutritional_facts_lowercase USA</h1>
		<!-- //Headline -->

		<!-- Where  am I? + Buttons -->
		<div class=\"two_rows\">
			<!-- Where am I ? -->
				<div class=\"two_columns\">
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$get_current_title_value</a>
					&gt;
					<a href=\"open_main_category.php?main_category_id=$get_current_main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
					&gt;
					<a href=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;l=$l\">$get_current_sub_category_translation_value</a>
					&gt;
					<a href=\"open_sub_category_nutritional_facts_us.php?sub_category_id=$get_current_sub_category_id&amp;l=$l\">$l_nutritional_facts USA</a>
					</p>
				</div>
			<!-- //Where am I ? -->

			<!-- Food menu -->
				<div class=\"two_columns\" style=\"text-align: right;\">
					<p>
					<a href=\"$root/food/search.php?l=$l\" class=\"btn_default\">$l_search</a>
					<a href=\"$root/food/my_food.php?l=$l\" class=\"btn_default\">$l_my_food</a>
					<a href=\"$root/food/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
					</p>
				</div>
			<!-- //Food menu -->
		</div>
		<!-- Where am I? + Buttons -->



	<!-- Show table US -->
		<p>
		<b>$l_show:</b>
		<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\""; if($show_hundred_metric == "1"){ echo" checked=\"checked\" data-target=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=0&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\""; } else{ echo" data-target=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=1&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\""; } echo" /> $l_hundred
		<input type=\"checkbox\" name=\"inp_show_pcs_us\" class=\"onclick_go_to_url\""; if($show_pcs_us == "1"){ echo" checked=\"checked\" data-target=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=0&amp;show_net_content=$show_net_content&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\""; } else{ echo" data-target=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=1&amp;show_net_content=$show_net_content&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\""; } echo" /> $l_pcs
		<input type=\"checkbox\" name=\"inp_show_net_content\" class=\"onclick_go_to_url\""; if($show_net_content == "1"){ echo" checked=\"checked\" data-target=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=0&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\""; } else{ echo" data-target=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=1&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\""; } echo" /> $l_net_content
		</p>

		<!-- On check go to URL -->
		<script>
		\$(function() {
			\$(\".onclick_go_to_url\").change(function(){
				var item=\$(this);
				window.location.href= item.data(\"target\")
			});
   		});
		</script>
		<!-- //On check go to URL -->

	<!-- //Show table US -->


	


	<!-- Food in subcategory -->
		<div class=\"nutritional_facts_wrapper_parent\">
		<div class=\"nutritional_facts_wrapper_child\">
		<table class=\"nutritional_facts\">
		 <thead>
		  <tr>
		   <th>
			<!-- Name -->";
				$a_order_by = "manufacturer_name_and_food_name";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_name";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
		   </th>
		   <th>
			<!-- Place in sub category + Score-->";
				$a_order_by = "score";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_place ($l_score)";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			<!-- //Place in sub category + Score-->
		   </th>";
		if($show_hundred_metric == "1"){
			echo"
			   <th colspan=\"11\" style=\"text-align: center;\" class=\"category_main\">
				<span>$l_per 100</span>
			   </th>
			";
		}
		if($show_pcs_us == "1"){
			echo"
			   <th colspan=\"11\" style=\"text-align: center;\" class=\"category_main\">
				<span>$l_per_pcs</span>
			   </th>
			";
		}
		if($show_net_content == "1"){
			echo"
			   <th colspan=\"11\" style=\"text-align: center;\" class=\"category_main\">
				<span>$l_net_content</span>
			   </th>
			";
		}
		echo"
		  </tr>

		  <tr>
		   <th>
			<!-- Name -->
		   </th>
		   <th>
			<!-- Place in sub category + Score -->
		   </th>";
		if($show_hundred_metric == "1"){
			echo"
			<!-- Per 100 metric (2) -->
			   <th class=\"category_main_left\">";
				$a_order_by = "energy_metric";
				echo"
				<span title=\"$l_calories\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_cal";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th colspan=\"5\" class=\"category_sub\" style=\"text-align: center;\">";
				$a_order_by = "fat_metric";
				echo"
				<span title=\"$l_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fat</a></span>
			   </th>
			   <th colspan=\"2\" class=\"category_sub_right\" style=\"text-align: center;\">";
				$a_order_by = "carbohydrates_metric";
				echo"
				<span title=\"$l_carbohydrates\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_car</a></span>
			   </th>
			   <th class=\"category_main_middle\">";
				$a_order_by = "proteins_metric";
				echo"
				<span title=\"$l_proteins\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_pro";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_middle\">";
				$a_order_by = "salt_metric";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_salt";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_right\">";
				$a_order_by = "dietary_fiber_metric";
				echo"
				<span title=\"$l_dietary_fiber\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fib";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			<!-- //Per 100 metric (2)  -->
			";
		} // per 100 metric
		if($show_pcs_us == "1"){
			echo"
			<!-- Per pcs us (2)  -->
			   <th class=\"category_main_left\">";
				$a_order_by = "energy_calculated_us";
				echo"
				<span title=\"$l_calories\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_cal";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th colspan=\"5\" class=\"category_sub\" style=\"text-align: center;\">";
				$a_order_by = "fat_calculated_us";
				echo"
				<span title=\"$l_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fat</a></span>
			   </th>
			   <th colspan=\"2\" class=\"category_sub_right\" style=\"text-align: center;\">";
				$a_order_by = "fat_carbohydrates_us";
				echo"
				<span title=\"$l_carbohydrates\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_car</a></span>
			   </th>
			   <th class=\"category_main_middle\">";
				$a_order_by = "proteins_calculated_us";
				echo"
				<span title=\"$l_proteins\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_pro";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_middle\">";
				$a_order_by = "salt_calculated_us";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_salt";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_right\">";
				$a_order_by = "dietary_fiber_calculated_us";
				echo"
				<span title=\"$l_dietary_fiber\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fib";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			<!-- //Per pcs us (2)  -->
			";
		} // per pcs us
		if($show_net_content == "1"){
			echo"
			<!-- Net content (2) -->
			   <th class=\"category_main_left\">";
				$a_order_by = "energy_net_content";
				echo"
				<span title=\"$l_calories\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_cal";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th colspan=\"5\" class=\"category_sub\" style=\"text-align: center;\">";
				$a_order_by = "fat_net_content";
				echo"
				<span title=\"$l_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fat</a></span>
			   </th>
			   <th colspan=\"2\" class=\"category_sub_right\" style=\"text-align: center;\">";
				$a_order_by = "carbohydrates_net_content";
				echo"
				<span title=\"$l_carbohydrates\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_car</a></span>
			   </th>
			   <th class=\"category_main_middle\">";
				$a_order_by = "proteins_net_content";
				echo"
				<span title=\"$l_proteins\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_pro";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_middle\">";
				$a_order_by = "salt_net_content";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_salt";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_right\">";
				$a_order_by = "dietary_fiber_net_content";
				echo"
				<span title=\"$l_dietary_fiber\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fib";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			<!-- //Net content (2) -->
			";
		} // per net content 
		
		echo"
		  </tr>
		  <tr>
		   <th>
			<!-- Name -->
		   </th>
		   <th>
			<!-- Place in sub category + Score -->
		   </th>";
		if($show_hundred_metric == "1"){
			echo"
			<!-- Per 100 metric (3) -->
			   <th class=\"category_main_left\">
				<!-- Cal -->
			   </th>
			   <th class=\"category_sub_left\">
				<!-- Fat 1-->";
				$a_order_by = "fat_metric";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fat";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 2-->";
				$a_order_by = "saturated_fat_metric";
				echo"
				<span title=\"$l_saturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_sat";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 3-->";
				$a_order_by = "trans_fat_metric";
				echo"
				<span title=\"$l_trans_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_trans";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 4-->";
				$a_order_by = "monounsaturated_fat_metric";
				echo"
				<span title=\"$l_monounsaturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_mon";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_right\">
				<!-- Fat 5-->";
				$a_order_by = "polyunsaturated_fat_metric";
				echo"
				<span title=\"$l_polyunsaturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_pol";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_center\">
				<!-- Car 1-->";
				$a_order_by = "carbohydrates_metric";
				echo"
				<span title=\"$l_carbohydrates\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_car";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_right\">
				<!-- Car 2-->";
				$a_order_by = "carbohydrates_of_which_sugars_metric";
				echo"
				<span title=\"$l_sugar\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_sug";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_middle\">
				<!-- Proteins-->
			   </th>
			   <th class=\"category_main_middle\">
				<!-- Salt-->
			   </th>
			   <th class=\"category_main_right\">
				<!-- Fiber -->
			   </th>
			<!-- //Per 100 metric (3) -->
			";
		} // 100 metric

		if($show_pcs_us == "1"){
			echo"
			<!-- Per pcs us (3) -->
			   <th class=\"category_main_left\">
				<!-- Cal -->
			   </th>
			   <th class=\"category_sub_left\">
				<!-- Fat 1-->";
				$a_order_by = "fat_calculated_us";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fat";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 2-->";
				$a_order_by = "saturated_fat_calculated_us";
				echo"
				<span title=\"$l_saturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_sat";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 3-->";
				$a_order_by = "trans_fat_calculated_us";
				echo"
				<span title=\"$l_trans_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_trans";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 4-->";
				$a_order_by = "monounsaturated_fat_calculated_us";
				echo"
				<span title=\"$l_monounsaturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_mon";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_right\">
				<!-- Fat 5-->";
				$a_order_by = "polyunsaturated_fat_calculated_us";
				echo"
				<span title=\"$l_polyunsaturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_pol";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_center\">
				<!-- Car 1-->";
				$a_order_by = "carbohydrates_calculated_us";
				echo"
				<span title=\"$l_carbohydrates\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_car";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_right\">
				<!-- Car 2-->";
				$a_order_by = "carbohydrates_of_which_sugars_calculated_us";
				echo"
				<span title=\"$l_sugar\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_sug";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_middle\">
				<!-- Proteins-->
			   </th>
			   <th class=\"category_main_middle\">
				<!-- Salt-->
			   </th>
			   <th class=\"category_main_right\">
				<!-- Fiber -->
			   </th>
			<!-- //Per pcs us (3) -->
			";
		} // pcs us

		if($show_net_content == "1"){
			echo"
			<!-- Net content (3) -->
			   <th class=\"category_main_left\">
				<!-- Cal -->
			   </th>
			   <th class=\"category_sub_left\">
				<!-- Fat 1-->";
				$a_order_by = "fat_net_content";
				echo"
				<span><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_fat";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 2-->";
				$a_order_by = "saturated_fat_net_content";
				echo"
				<span title=\"$l_saturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_sat";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 3-->";
				$a_order_by = "trans_fat_net_content";
				echo"
				<span title=\"$l_trans_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_trans";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_middle\">
				<!-- Fat 4-->";
				$a_order_by = "monounsaturated_fat_net_content";
				echo"
				<span title=\"$l_monounsaturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_mon";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_right\">
				<!-- Fat 5-->";
				$a_order_by = "polyunsaturated_fat_net_content";
				echo"
				<span title=\"$l_polyunsaturated_fat\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_pol";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_center\">
				<!-- Car 1-->";
				$a_order_by = "carbohydrates_net_content";
				echo"
				<span title=\"$l_carbohydrates\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_car";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_sub_right\">
				<!-- Car 2-->";
				$a_order_by = "carbohydrates_of_which_sugars_net_content";
				echo"
				<span title=\"$l_sugar\"><a href=\"open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$a_order_by&amp;order_method=$a_order_method&amp;l=$l\">$l_sug";
				if($order_by == "$a_order_by" && $order_method == "asc"){
					echo" <img src=\"_gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
				}
				elseif($order_by == "$a_order_by" && $order_method == "desc"){
					echo" <img src=\"_gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
				}
				echo"</a></span>
			   </th>
			   <th class=\"category_main_middle\">
				<!-- Proteins-->
			   </th>
			   <th class=\"category_main_middle\">
				<!-- Salt-->
			   </th>
			   <th class=\"category_main_right\">
				<!-- Fiber -->
			   </th>
			<!-- //Net content (3) -->
			";
		} // net_content
		echo"
		  </tr>
		 </thead>
		 <tbody>
		";

		// Ready calculations : number of foods
		$inp_total_number_of_foods_in_sub_category = 0;
		
		
		// Ready calculations : min
		$inp_current_sub_category_calories_min_100_g = 999;
		$inp_current_sub_category_fat_min_100_g = 999;
		$inp_current_sub_category_saturated_fat_min_100_g = 999;
		$inp_current_sub_category_trans_fat_min_100_g = 999;
		$inp_current_sub_category_monounsaturated_fat_min_100_g = 999;
		$inp_current_sub_category_polyunsaturated_fat_min_100_g = 999;
		$inp_current_sub_category_cholesterol_min_100_g = 999;
		$inp_current_sub_category_carb_min_100_g = 999;
		$inp_current_sub_category_carb_of_which_sugars_min_100_g = 999;
		$inp_current_sub_category_added_sugars_min_100_g = 999;
		$inp_current_sub_category_dietary_fiber_min_100_g = 999;
		$inp_current_sub_category_proteins_min_100_g = 999;
		$inp_current_sub_category_salt_min_100_g = 999;
		$inp_current_sub_category_sodium_min_100_g = 999;


		// Ready calculations : median
		$inp_current_sub_category_calories_med_sum_100_g = 0;
		$inp_current_sub_category_fat_med_sum_100_g = 0;
		$inp_current_sub_category_saturated_fat_med_sum_100_g = 0;
		$inp_current_sub_category_trans_fat_med_sum_100_g = 0;
		$inp_current_sub_category_monounsaturated_fat_med_sum_100_g = 0;
		$inp_current_sub_category_polyunsaturated_fat_med_sum_100_g = 0;
		$inp_current_sub_category_cholesterol_med_sum_100_g = 0;
		$inp_current_sub_category_carb_med_sum_100_g = 0;
		$inp_current_sub_category_carb_of_which_sugars_med_sum_100_g = 0;
		$inp_current_sub_category_added_sugars_med_sum_100_g = 0;
		$inp_current_sub_category_dietary_fiber_med_sum_100_g = 0;
		$inp_current_sub_category_proteins_med_sum_100_g = 0;
		$inp_current_sub_category_salt_med_sum_100_g = 0;
		$inp_current_sub_category_sodium_med_sum_100_g = 0;

		// Ready calculations : max
		$inp_current_sub_category_calories_max_100_g = 0;
		$inp_current_sub_category_fat_max_100_g = 0;
		$inp_current_sub_category_saturated_fat_max_100_g = 0;
		$inp_current_sub_category_trans_fat_max_100_g = 0;
		$inp_current_sub_category_monounsaturated_fat_max_100_g = 0;
		$inp_current_sub_category_polyunsaturated_fat_max_100_g = 0;
		$inp_current_sub_category_cholesterol_max_100_g = 0;
		$inp_current_sub_category_carb_max_100_g = 0;
		$inp_current_sub_category_carb_of_which_sugars_max_100_g = 0;
		$inp_current_sub_category_added_sugars_max_100_g = 0;
		$inp_current_sub_category_dietary_fiber_max_100_g = 0;
		$inp_current_sub_category_proteins_max_100_g = 0;
		$inp_current_sub_category_salt_max_100_g = 0;
		$inp_current_sub_category_sodium_max_100_g = 0;



		// Set layout
		$x = 0;
		$show_food = "true";
		$score_place_in_sub_category_counter = 1; // counter for score

		// Get food
		$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_sub_category_id=$get_current_sub_category_id AND food_language=$l_mysql";

		// Order
		if($order_by != ""){
			if($order_method == "desc"){
				$order_method_mysql = "DESC";
			}
			else{
				$order_method_mysql = "ASC";
			}




			if($order_by == "id" OR $order_by == "name" OR $order_by == "manufacturer_name" OR $order_by == "manufacturer_name_and_food_name" OR $order_by == "net_content_metric" OR $order_by == "net_content_measurement_metric" OR $order_by == "net_content_us" OR $order_by == "net_content_measurement_us" OR $order_by == "net_content_added_measurement" OR $order_by == "serving_size_metric" OR $order_by == "serving_size_measurement_metric" OR $order_by == "serving_size_us" OR $order_by == "serving_size_measurement_us" OR $order_by == "serving_size_added_measurement" OR $order_by == "serving_size_pcs" OR $order_by == "serving_size_pcs_measurement" OR $order_by == "energy_metric" OR $order_by == "fat_metric" OR $order_by == "saturated_fat_metric" OR $order_by == "trans_fat_metric" OR $order_by == "monounsaturated_fat_metric" OR $order_by == "polyunsaturated_fat_metric" OR $order_by == "cholesterol_metric" OR $order_by == "carbohydrates_metric" OR $order_by == "carbohydrates_of_which_sugars_metric" OR $order_by == "dietary_fiber_metric" OR $order_by == "proteins_metric" OR $order_by == "salt_metric" OR $order_by == "sodium_metric" OR $order_by == "energy_us" OR $order_by == "fat_us" OR $order_by == "saturated_fat_us" OR $order_by == "trans_fat_us" OR $order_by == "monounsaturated_fat_us" OR $order_by == "polyunsaturated_fat_us" OR $order_by == "cholesterol_us" OR $order_by == "carbohydrates_us" OR $order_by == "carbohydrates_of_which_sugars_us" OR $order_by == "dietary_fiber_us" OR $order_by == "proteins_us" OR $order_by == "salt_us" OR $order_by == "sodium_us" OR $order_by == "score" OR $order_by == "score_place_in_sub_category" OR $order_by == "energy_calculated_metric" OR $order_by == "fat_calculated_metric" OR $order_by == "saturated_fat_calculated_metric" OR $order_by == "trans_fat_calculated_metric" OR $order_by == "monounsaturated_fat_calculated_metric" OR $order_by == "polyunsaturated_fat_calculated_metric" OR $order_by == "cholesterol_calculated_metric" OR $order_by == "carbohydrates_calculated_metric" OR $order_by == "carbohydrates_of_which_sugars_calculated_metric" OR $order_by == "dietary_fiber_calculated_metric" OR $order_by == "proteins_calculated_metric" OR $order_by == "salt_calculated_metric" OR $order_by == "sodium_calculated_metric" OR $order_by == "energy_calculated_us" OR $order_by == "fat_calculated_us" OR $order_by == "saturated_fat_calculated_us" OR $order_by == "trans_fat_calculated_us" OR $order_by == "monounsaturated_fat_calculated_us" OR $order_by == "polyunsaturated_fat_calculated_us" OR $order_by == "cholesterol_calculated_us" OR $order_by == "carbohydrates_calculated_us" OR $order_by == "carbohydrates_of_which_sugars_calculated_us" OR $order_by == "dietary_fiber_calculated_us" OR $order_by == "proteins_calculated_us" OR $order_by == "salt_calculated_us" OR $order_by == "sodium_calculated_us" OR $order_by == "energy_net_content" OR $order_by == "fat_net_content" OR $order_by == "saturated_fat_net_content" OR $order_by == "trans_fat_net_content" OR $order_by == "monounsaturated_fat_net_content" OR $order_by == "polyunsaturated_fat_net_content" OR $order_by == "cholesterol_net_content" OR $order_by == "carbohydrates_net_content" OR $order_by == "carbohydrates_of_which_sugars_net_content" OR $order_by == "dietary_fiber_net_content" OR $order_by == "proteins_net_content" OR $order_by == "salt_net_content" OR $order_by == "sodium_net_content" OR $order_by == "stars" OR $order_by == "no_of_ratings" OR $order_by == "unique_hits" OR $order_by == "comments" OR $order_by == "likes" OR $order_by == "dislikes"){
				$order_by_mysql = "food_$order_by";
			}
			else{
				$order_by_mysql = "food_id";
			}
			$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";
		}
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

			// Score
			if($order_by == "score" && $order_method == "asc" && $get_food_score_place_in_sub_category != "$score_place_in_sub_category_counter"){
				// Update food score
				mysqli_query($link, "UPDATE $t_food_index SET food_score_place_in_sub_category='$score_place_in_sub_category_counter' WHERE food_id=$get_food_id") or die(mysqli_error($link));
				echo"<div class=\"info\"><p><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" />
				Updated score place in sub category for $get_food_name..</div>
				<meta http-equiv=\"refresh\" content=\"1;url=open_sub_category_nutritional_facts_us.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;show_hundred_metric=$show_hundred_metric&amp;show_pcs_us=$show_pcs_us&amp;show_net_content=$show_net_content&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l\">\n";
			} // Score
			$score_place_in_sub_category_counter++;

			echo"
			  <tr>
			   <td>
				<!-- Name and image -->
					<table>
					 <tr>
					  <td>\n";
					if(file_exists("$root/$get_food_image_path/$get_food_image_a") && $get_food_image_a != ""){

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


						if(file_exists("../$get_food_image_path/$get_food_thumb_a_small") && $get_food_thumb_a_small != ""){
							echo"
							<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" /></a>
							  </td>
							  <td>
							";
						}

					}
					echo"
						<span><a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\">$get_food_manufacturer_name $get_food_name</a><br />
						$get_food_net_content_us $get_food_net_content_measurement_us</span>
						<span class=\"grey\">($get_food_net_content_metric $get_food_net_content_measurement_metric)<br /></span>
						
						<span>
						$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement:
						  $get_food_serving_size_us  $get_food_serving_size_measurement_us</span>
						<span class=\"grey\">($get_food_serving_size_metric $get_food_serving_size_measurement_metric)</span>
					   </td>
					  </tr>
					 </table>
				<!-- //Name and image -->
			   </td>
			   <td>
				<span>$get_food_score_place_in_sub_category</span>
				<span class=\"grey\">($get_food_score)</span>
			   </td>";
			if($show_hundred_metric == "1"){
				echo"
				<!-- Per 100 metric -->
				   <td class=\"category_main_left\">
					<!-- Calories -->
						<span>$get_food_energy_metric</span>
					<!-- //Calories -->
				   </td>
				   <td class=\"category_sub_left\">
					<!-- Fat 1 -->
					<span>$get_food_fat_metric</span>
					<!-- //Fat 1 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 2 -->
					<span>$get_food_saturated_fat_metric</span>
					<!-- //Fat 2 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 3 -->
					<span>$get_food_trans_fat_metric</span>
					<!-- //Fat 3 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 4 -->
					<span>$get_food_monounsaturated_fat_metric</span>
					<!-- //Fat 4 -->
				   </td>
				   <td class=\"category_sub_right\">
					<!-- Fat 5 -->
					<span>$get_food_polyunsaturated_fat_metric</span>
					<!-- //Fat 5 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Carbohydrates 1 -->
					<span>$get_food_carbohydrates_metric</span>
					<!-- //Carbohydrates 1 -->
				   </td>
				   <td class=\"category_sub_right\">
					<!-- Carbohydrates 2 -->
					<span>$get_food_carbohydrates_of_which_sugars_metric</span>
					<!-- //Carbohydrates 2 -->
				   </td>
				   <td class=\"category_main_center\">
					<!-- Proteins -->
					<span>$get_food_proteins_metric</span>
					<!-- //Proteins -->
				   </td>
				   <td class=\"category_main_center\">
					<!-- Salt -->
					<span>$get_food_salt_metric</span>
					<!-- //Salt -->
				   </td>
				   <td class=\"category_main_right\">
					<!-- Fiber -->
					<span>$get_food_dietary_fiber_metric</span>
					<!-- //Fiber -->
				   </td>
				<!-- //Per 100 metric -->
				";
			} // per 100 metric

			if($show_pcs_us == "1"){
				echo"
				<!-- Per pcs us -->
				   <td class=\"category_main_left\">
					<!-- Calories -->
						<span>$get_food_energy_calculated_us</span>
					<!-- //Calories -->
				   </td>
				   <td class=\"category_sub_left\">
					<!-- Fat 1 -->
					<span>$get_food_fat_calculated_us</span>
					<!-- //Fat 1 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 2 -->
					<span>$get_food_saturated_fat_calculated_us</span>
					<!-- //Fat 2 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 3 -->
					<span>$get_food_trans_fat_calculated_us</span>
					<!-- //Fat 3 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 4 -->
					<span>$get_food_monounsaturated_fat_calculated_us</span>
					<!-- //Fat 4 -->
				   </td>
				   <td class=\"category_sub_right\">
					<!-- Fat 5 -->
					<span>$get_food_polyunsaturated_fat_calculated_us</span>
					<!-- //Fat 5 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Carbohydrates 1 -->
					<span>$get_food_carbohydrates_calculated_us</span>
					<!-- //Carbohydrates 1 -->
				   </td>
				   <td class=\"category_sub_right\">
					<!-- Carbohydrates 2 -->
					<span>$get_food_carbohydrates_of_which_sugars_calculated_us</span>
					<!-- //Carbohydrates 2 -->
				   </td>
				   <td class=\"category_main_center\">
					<!-- Proteins -->
					<span>$get_food_proteins_calculated_us</span>
					<!-- //Proteins -->
				   </td>
				   <td class=\"category_main_center\">
					<!-- Salt -->
					<span>$get_food_salt_calculated_us</span>
					<!-- //Salt -->
				   </td>
				   <td class=\"category_main_right\">
					<!-- Fiber -->
					<span>$get_food_dietary_fiber_calculated_us</span>
					<!-- //Fiber -->
				   </td>
				<!-- //Per pcs us -->
				";
			} // per pcs us";
			if($show_net_content == "1"){
				echo"
				<!-- Net content -->
				   <td class=\"category_main_left\">
					<!-- Calories -->
						<span>$get_food_energy_net_content</span>
					<!-- //Calories -->
				   </td>
				   <td class=\"category_sub_left\">
					<!-- Fat 1 -->
					<span>$get_food_fat_net_content</span>
					<!-- //Fat 1 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 2 -->
					<span>$get_food_saturated_fat_net_content</span>
					<!-- //Fat 2 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 3 -->
					<span>$get_food_trans_fat_net_content</span>
					<!-- //Fat 3 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Fat 4 -->
					<span>$get_food_monounsaturated_fat_net_content</span>
					<!-- //Fat 4 -->
				   </td>
				   <td class=\"category_sub_right\">
					<!-- Fat 5 -->
					<span>$get_food_polyunsaturated_fat_net_content</span>
					<!-- //Fat 5 -->
				   </td>
				   <td class=\"category_sub_center\">
					<!-- Carbohydrates 1 -->
					<span>$get_food_carbohydrates_net_content</span>
					<!-- //Carbohydrates 1 -->
				   </td>
				   <td class=\"category_sub_right\">
					<!-- Carbohydrates 2 -->
					<span>$get_food_carbohydrates_of_which_sugars_net_content</span>
					<!-- //Carbohydrates 2 -->
				   </td>
				   <td class=\"category_main_center\">
					<!-- Proteins -->
					<span>$get_food_proteins_net_content</span>
					<!-- //Proteins -->
				   </td>
				   <td class=\"category_main_center\">
					<!-- Salt -->
					<span>$get_food_salt_net_content</span>
					<!-- //Salt -->
				   </td>
				   <td class=\"category_main_right\">
					<!-- Fiber -->
					<span>$get_food_dietary_fiber_net_content</span>
					<!-- //Fiber -->
				   </td>
				<!-- //Net content -->
				";
			} // net content
			echo"
			  </tr>

			";

			// Ready calculations : number of foods
			$inp_total_number_of_foods_in_sub_category = $inp_total_number_of_foods_in_sub_category+1;

			// min
			if($get_food_energy_metric < $inp_current_sub_category_calories_min_100_g && $get_food_energy_metric != 0){
				$inp_current_sub_category_calories_min_100_g = $get_food_energy_metric;
			}
			if($get_food_fat_metric < $inp_current_sub_category_fat_min_100_g && $get_food_fat_metric != 0){
				$inp_current_sub_category_fat_min_100_g = $get_food_fat_metric;
			}
			if($get_food_saturated_fat_metric < $inp_current_sub_category_saturated_fat_min_100_g && $get_food_saturated_fat_metric != 0){
				$inp_current_sub_category_saturated_fat_min_metric = $get_food_saturated_fat_metric;
			}
			if($get_food_trans_fat_metric < $inp_current_sub_category_trans_fat_min_100_g && $get_food_trans_fat_metric != 0){
				$inp_current_sub_category_trans_fat_min_100_g = $get_food_trans_fat_metric;
			}
			if($get_food_monounsaturated_fat_metric < $inp_current_sub_category_monounsaturated_fat_min_100_g && $get_food_monounsaturated_fat_metric != 0){
				$inp_current_sub_category_monounsaturated_fat_min_100_g = $get_food_monounsaturated_fat_metric;
			}
			if($get_food_polyunsaturated_fat_metric < $inp_current_sub_category_polyunsaturated_fat_min_100_g && $get_food_polyunsaturated_fat_metric != 0){
				$inp_current_sub_category_polyunsaturated_fat_min_100_g = $get_food_polyunsaturated_fat_metric;
			}
			if($get_food_cholesterol_metric < $inp_current_sub_category_cholesterol_min_100_g && $get_food_cholesterol_metric != 0){
				$inp_current_sub_category_cholesterol_min_100_g = $get_food_cholesterol_metric;
			}
			if($get_food_carbohydrates_metric < $inp_current_sub_category_carb_min_100_g && $get_food_carbohydrates_metric != 0){
				$inp_current_sub_category_carb_min_100_g = $get_food_carbohydrates_metric;
			}
			if($get_food_carbohydrates_of_which_sugars_metric < $inp_current_sub_category_carb_of_which_sugars_min_100_g && $get_food_carbohydrates_of_which_sugars_metric != 0){
				$inp_current_sub_category_carb_of_which_sugars_min_100_g = $get_food_carbohydrates_of_which_sugars_metric;
			}
			if($get_food_added_sugars_metric < $inp_current_sub_category_added_sugars_min_100_g && $get_food_added_sugars_metric != 0){
				$inp_current_sub_category_added_sugars_min_100_g = $get_food_added_sugars_metric;
			}
			if($get_food_dietary_fiber_metric < $inp_current_sub_category_dietary_fiber_min_100_g && $get_food_dietary_fiber_metric != 0){
				$inp_current_sub_category_dietary_fiber_min_100_g = $get_food_dietary_fiber_metric;
			}
			if($get_food_proteins_metric < $inp_current_sub_category_proteins_min_100_g && $get_food_proteins_metric != 0){
				$inp_current_sub_category_proteins_min_100_g = $get_food_proteins_metric;
			}
			if($get_food_salt_metric < $inp_current_sub_category_salt_min_100_g && $get_food_salt_metric != 0){
				$inp_current_sub_category_salt_min_100_g = $get_food_salt_metric;
			}
			if($get_food_sodium_metric < $inp_current_sub_category_sodium_min_100_g && $get_food_sodium_metric != 0){
				$inp_current_sub_category_sodium_min_100_g = $get_food_sodium_metric;
			}



			// Ready calculations : median
			$inp_current_sub_category_calories_med_sum_100_g		= $inp_current_sub_category_calories_med_sum_100_g + $get_food_energy_metric;
			$inp_current_sub_category_fat_med_sum_100_g			= $inp_current_sub_category_fat_med_sum_100_g + $get_food_fat_metric;
			$inp_current_sub_category_saturated_fat_med_sum_100_g		= $inp_current_sub_category_saturated_fat_med_sum_100_g + $get_food_saturated_fat_metric;
			$inp_current_sub_category_trans_fat_med_sum_100_g		= $inp_current_sub_category_trans_fat_med_sum_100_g + $get_food_trans_fat_metric;
			$inp_current_sub_category_monounsaturated_fat_med_sum_100_g 	= $inp_current_sub_category_monounsaturated_fat_med_sum_100_g + $get_food_monounsaturated_fat_metric;
			$inp_current_sub_category_polyunsaturated_fat_med_sum_100_g 	= $inp_current_sub_category_polyunsaturated_fat_med_sum_100_g + $get_food_polyunsaturated_fat_metric;
			$inp_current_sub_category_carb_med_sum_100_g 			= $inp_current_sub_category_carb_med_sum_100_g + $get_food_carbohydrates_metric;
			$inp_current_sub_category_carb_of_which_sugars_med_sum_100_g 	= $inp_current_sub_category_carb_of_which_sugars_med_sum_100_g + $get_food_carbohydrates_of_which_sugars_metric;
			$inp_current_sub_category_added_sugars_med_sum_100_g 		= $inp_current_sub_category_added_sugars_med_sum_100_g + $get_food_added_sugars_metric;
			$inp_current_sub_category_dietary_fiber_med_sum_100_g		= $inp_current_sub_category_dietary_fiber_med_sum_100_g + $get_food_dietary_fiber_metric;
			$inp_current_sub_category_proteins_med_sum_100_g 		= $inp_current_sub_category_proteins_med_sum_100_g + $get_food_proteins_metric;
			$inp_current_sub_category_salt_med_sum_100_g			= $inp_current_sub_category_salt_med_sum_100_g + $get_food_salt_metric;
			$inp_current_sub_category_sodium_med_sum_100_g 			= $inp_current_sub_category_sodium_med_sum_100_g + $get_food_sodium_metric;



			// max
			if($get_food_energy_metric > $inp_current_sub_category_calories_max_100_g){
				$inp_current_sub_category_calories_max_100_g = $get_food_energy_metric;
			}
				if($get_food_fat_metric > $inp_current_sub_category_fat_max_100_g){
						$inp_current_sub_category_fat_max_100_g = $get_food_fat_metric;
					}
					if($get_food_saturated_fat_metric > $inp_current_sub_category_saturated_fat_max_100_g){
						$inp_current_sub_category_saturated_fat_max_100_g = $get_food_saturated_fat_metric;
					}
					if($get_food_trans_fat_metric > $inp_current_sub_category_trans_fat_max_100_g){
						$inp_current_sub_category_trans_fat_max_100_g = $get_food_trans_fat_metric;
					}
					if($get_food_monounsaturated_fat_metric > $inp_current_sub_category_monounsaturated_fat_max_100_g){
						$inp_current_sub_category_monounsaturated_fat_max_100_g = $get_food_monounsaturated_fat_metric;
					}
					if($get_food_polyunsaturated_fat_metric > $inp_current_sub_category_polyunsaturated_fat_max_100_g){
						$inp_current_sub_category_polyunsaturated_fat_max_100_g = $get_food_polyunsaturated_fat_metric;
					}
					if($get_food_carbohydrates_metric > $inp_current_sub_category_carb_max_100_g){
						$inp_current_sub_category_carb_max_100_g = $get_food_carbohydrates_metric;
					}
			if($get_food_carbohydrates_of_which_sugars_metric > $inp_current_sub_category_carb_of_which_sugars_max_100_g){
				$inp_current_sub_category_carb_of_which_sugars_max_100_g = $get_food_carbohydrates_of_which_sugars_metric;
			}
			if($get_food_added_sugars_metric > $inp_current_sub_category_added_sugars_max_100_g){
				$inp_current_sub_category_added_sugars_max_100_g = $get_food_added_sugars_metric;
			}
			if($get_food_dietary_fiber_metric > $inp_current_sub_category_dietary_fiber_max_100_g){
						$inp_current_sub_category_dietary_fiber_max_100_g = $get_food_dietary_fiber_metric;
					}
					if($get_food_proteins_metric > $inp_current_sub_category_proteins_max_100_g){
						$inp_current_sub_category_proteins_max_100_g = $get_food_proteins_metric;
			}
			if($get_food_salt_metric > $inp_current_sub_category_salt_max_100_g){
						$inp_current_sub_category_salt_max_100_g = $get_food_salt_metric;
			}
			if($get_food_sodium_metric > $inp_current_sub_category_sodium_max_100_g){
						$inp_current_sub_category_sodium_max_100_g = $get_food_sodium_metric;
			}
	
		} // while(

		
		// Calculations : min 
		if($inp_current_sub_category_calories_min_100_g == 999){
			$inp_current_sub_category_calories_min_100_g = 0;
		}
		if($inp_current_sub_category_fat_min_100_g == 999){
			$inp_current_sub_category_fat_min_100_g = 0;
		}
		if($inp_current_sub_category_saturated_fat_min_100_g == 999){
			$inp_current_sub_category_saturated_fat_min_100_g = 0;
		}
		if($inp_current_sub_category_trans_fat_min_100_g == 999){
			$inp_current_sub_category_trans_fat_min_100_g = 0;
		}
		if($inp_current_sub_category_monounsaturated_fat_min_100_g == 999){
			$inp_current_sub_category_monounsaturated_fat_min_100_g = 0;
		}
		if($inp_current_sub_category_polyunsaturated_fat_min_100_g == 999){
			$inp_current_sub_category_polyunsaturated_fat_min_100_g = 0;
		}
		
		if($inp_current_sub_category_cholesterol_min_100_g == 999){
			$inp_current_sub_category_cholesterol_min_100_g = 0;
		}
		
		if($inp_current_sub_category_carb_min_100_g == 999){
			$inp_current_sub_category_carb_min_100_g = 0;
		}
		if($inp_current_sub_category_carb_of_which_sugars_min_100_g == 999){
			$inp_current_sub_category_carb_of_which_sugars_min_100_g = 0;
		}
		if($inp_current_sub_category_added_sugars_min_100_g == 999){
			$inp_current_sub_category_added_sugars_min_100_g = 0;
		}
		if($inp_current_sub_category_dietary_fiber_min_100_g == 999){
			$inp_current_sub_category_dietary_fiber_min_100_g = 0;
		}
		if($inp_current_sub_category_proteins_min_100_g == 999){
			$inp_current_sub_category_proteins_min_100_g = 0;
		}
		if($inp_current_sub_category_salt_min_100_g == 999){
			$inp_current_sub_category_salt_min_100_g = 0;
		}
		if($inp_current_sub_category_sodium_min_100_g == 999){
			$inp_current_sub_category_sodium_min_100_g = 0;
		}

		// Calculations : median
		if($inp_total_number_of_foods_in_sub_category != 0){
			$inp_current_sub_category_calories_med_sum_100_g = round($inp_current_sub_category_calories_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_fat_med_sum_100_g = round($inp_current_sub_category_fat_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_saturated_fat_med_sum_100_g = round($inp_current_sub_category_saturated_fat_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_trans_fat_med_sum_100_g = round($inp_current_sub_category_trans_fat_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_monounsaturated_fat_med_sum_100_g = round($inp_current_sub_category_monounsaturated_fat_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_polyunsaturated_fat_med_sum_100_g = round($inp_current_sub_category_polyunsaturated_fat_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_cholesterol_med_sum_100_g = round($inp_current_sub_category_cholesterol_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_carb_med_sum_100_g = round($inp_current_sub_category_carb_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_carb_of_which_sugars_med_sum_100_g = round($inp_current_sub_category_carb_of_which_sugars_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_added_sugars_med_sum_100_g = round($inp_current_sub_category_added_sugars_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_dietary_fiber_med_sum_100_g = round($inp_current_sub_category_dietary_fiber_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_proteins_med_sum_100_g = round($inp_current_sub_category_proteins_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_salt_med_sum_100_g = round($inp_current_sub_category_salt_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
			$inp_current_sub_category_sodium_med_sum_100_g = round($inp_current_sub_category_sodium_med_sum_100_g/$inp_total_number_of_foods_in_sub_category, 1);
		}

		// Calculations +- 10 % :: Metric
		$inp_calories_p_ten_percentage_100_g = $inp_current_sub_category_calories_med_sum_100_g*1.1; // Plus 10%
		$inp_calories_m_ten_percentage_100_g = $inp_current_sub_category_calories_med_sum_100_g*0.9; // Minus 10%

		$inp_fat_p_ten_percentage_100_g = $inp_current_sub_category_fat_med_sum_100_g*1.1; // Plus 10%
		$inp_fat_m_ten_percentage_100_g = $inp_current_sub_category_fat_med_sum_100_g*0.9; // Minus 10%

		$inp_saturated_fat_p_ten_percentage_100_g = $inp_current_sub_category_saturated_fat_med_sum_100_g*1.1; // Plus 10%
		$inp_saturated_fat_m_ten_percentage_100_g = $inp_current_sub_category_saturated_fat_med_sum_100_g*0.9; // Minus 10%

		$inp_trans_fat_p_ten_percentage_100_g = $inp_current_sub_category_trans_fat_med_sum_100_g*1.1; // Plus 10%
		$inp_trans_fat_m_ten_percentage_100_g = $inp_current_sub_category_trans_fat_med_sum_100_g*0.9; // Minus 10%

		$inp_monounsaturated_fat_p_ten_percentage_100_g = $inp_current_sub_category_monounsaturated_fat_med_sum_100_g*1.1; // Plus 10%
		$inp_monounsaturated_fat_m_ten_percentage_100_g = $inp_current_sub_category_monounsaturated_fat_med_sum_100_g*0.9; // Minus 10%

		$inp_polyunsaturated_fat_p_ten_percentage_100_g = $inp_current_sub_category_polyunsaturated_fat_med_sum_100_g*1.1; // Plus 10%
		$inp_polyunsaturated_fat_m_ten_percentage_100_g = $inp_current_sub_category_polyunsaturated_fat_med_sum_100_g*0.9; // Minus 10%

		$inp_cholesterol_p_ten_percentage_100_g = $inp_current_sub_category_cholesterol_med_sum_100_g*1.1; // Plus 10%
		$inp_cholesterol_m_ten_percentage_100_g = $inp_current_sub_category_cholesterol_med_sum_100_g*0.9; // Minus 10%

		$inp_carb_p_ten_percentage_100_g = $inp_current_sub_category_carb_med_sum_100_g*1.1; // Plus 10%
		$inp_carb_m_ten_percentage_100_g = $inp_current_sub_category_carb_med_sum_100_g*0.9; // Minus 10%

		$inp_carb_of_which_sugars_p_ten_percentage_100_g = $inp_current_sub_category_carb_of_which_sugars_med_sum_100_g*1.1; // Plus 10%
		$inp_carb_of_which_sugars_m_ten_percentage_100_g = $inp_current_sub_category_carb_of_which_sugars_med_sum_100_g*0.9; // Minus 10%

		$inp_added_sugars_p_ten_percentage_100_g = $inp_current_sub_category_added_sugars_med_sum_100_g*1.1; // Plus 10%
		$inp_added_sugars_m_ten_percentage_100_g = $inp_current_sub_category_added_sugars_med_sum_100_g*0.9; // Minus 10%

		$inp_dietary_fiber_p_ten_percentage_100_g = $inp_current_sub_category_dietary_fiber_med_sum_100_g*1.1; // Plus 10%
		$inp_dietary_fiber_m_ten_percentage_100_g = $inp_current_sub_category_dietary_fiber_med_sum_100_g*0.9; // Minus 10%

		$inp_proteins_p_ten_percentage_100_g = $inp_current_sub_category_proteins_med_sum_100_g*1.1; // Plus 10%
		$inp_proteins_m_ten_percentage_100_g = $inp_current_sub_category_proteins_med_sum_100_g*0.9; // Minus 10%

		$inp_salt_p_ten_percentage_100_g = $inp_current_sub_category_salt_med_sum_100_g*1.1; // Plus 10%
		$inp_salt_m_ten_percentage_100_g = $inp_current_sub_category_salt_med_sum_100_g*0.9; // Minus 10%

		$inp_sodium_p_ten_percentage_100_g = $inp_current_sub_category_sodium_med_sum_100_g*1.1; // Plus 10%
		$inp_sodium_m_ten_percentage_100_g = $inp_current_sub_category_sodium_med_sum_100_g*0.9; // Minus 10%
		

		// Update
		$result = mysqli_query($link, "UPDATE $t_food_categories_sub_translations SET 
							sub_category_translation_no_food=$inp_total_number_of_foods_in_sub_category,

							sub_category_calories_min_100_g=$inp_current_sub_category_calories_min_100_g, 
							sub_category_calories_med_100_g=$inp_current_sub_category_calories_med_sum_100_g, 
							sub_category_calories_max_100_g=$inp_current_sub_category_calories_max_100_g, 
							sub_category_calories_p_ten_percentage_100_g=$inp_calories_p_ten_percentage_100_g,
							sub_category_calories_m_ten_percentage_100_g=$inp_calories_m_ten_percentage_100_g,

							sub_category_fat_min_100_g=$inp_current_sub_category_fat_min_100_g, 
							sub_category_fat_med_100_g=$inp_current_sub_category_fat_med_sum_100_g, 
							sub_category_fat_max_100_g=$inp_current_sub_category_fat_max_100_g, 
							sub_category_fat_p_ten_percentage_100_g=$inp_fat_p_ten_percentage_100_g,
							sub_category_fat_m_ten_percentage_100_g=$inp_fat_m_ten_percentage_100_g,

							sub_category_saturated_fat_min_100_g=$inp_current_sub_category_saturated_fat_min_100_g, 
							sub_category_saturated_fat_med_100_g=$inp_current_sub_category_saturated_fat_med_sum_100_g, 
							sub_category_saturated_fat_max_100_g=$inp_current_sub_category_saturated_fat_max_100_g, 
							sub_category_saturated_fat_p_ten_percentage_100_g=$inp_saturated_fat_p_ten_percentage_100_g,
							sub_category_saturated_fat_m_ten_percentage_100_g=$inp_saturated_fat_m_ten_percentage_100_g,

							sub_category_trans_fat_min_100_g=$inp_current_sub_category_trans_fat_min_100_g, 
							sub_category_trans_fat_med_100_g=$inp_current_sub_category_trans_fat_med_sum_100_g, 
							sub_category_trans_fat_max_100_g=$inp_current_sub_category_trans_fat_max_100_g, 
							sub_category_trans_fat_p_ten_percentage_100_g=$inp_trans_fat_p_ten_percentage_100_g,
							sub_category_trans_fat_m_ten_percentage_100_g=$inp_trans_fat_m_ten_percentage_100_g,

							sub_category_monounsaturated_fat_min_100_g=$inp_current_sub_category_monounsaturated_fat_min_100_g, 
							sub_category_monounsaturated_fat_med_100_g=$inp_current_sub_category_monounsaturated_fat_med_sum_100_g, 
							sub_category_monounsaturated_fat_max_100_g=$inp_current_sub_category_monounsaturated_fat_max_100_g, 
							sub_category_monounsaturated_fat_p_ten_percentage_100_g=$inp_monounsaturated_fat_p_ten_percentage_100_g,
							sub_category_monounsaturated_fat_m_ten_percentage_100_g=$inp_monounsaturated_fat_m_ten_percentage_100_g,

							sub_category_polyunsaturated_fat_min_100_g=$inp_current_sub_category_polyunsaturated_fat_min_100_g, 
							sub_category_polyunsaturated_fat_med_100_g=$inp_current_sub_category_polyunsaturated_fat_med_sum_100_g, 
							sub_category_polyunsaturated_fat_max_100_g=$inp_current_sub_category_polyunsaturated_fat_max_100_g, 
							sub_category_polyunsaturated_fat_p_ten_percentage_100_g=$inp_polyunsaturated_fat_p_ten_percentage_100_g,
							sub_category_polyunsaturated_fat_m_ten_percentage_100_g=$inp_polyunsaturated_fat_m_ten_percentage_100_g,


							sub_category_cholesterol_min_100_g=$inp_current_sub_category_cholesterol_min_100_g, 
							sub_category_cholesterol_med_100_g=$inp_current_sub_category_cholesterol_med_sum_100_g, 
							sub_category_cholesterol_max_100_g=$inp_current_sub_category_cholesterol_max_100_g, 
							sub_category_cholesterol_p_ten_percentage_100_g=$inp_cholesterol_p_ten_percentage_100_g,
							sub_category_cholesterol_m_ten_percentage_100_g=$inp_cholesterol_m_ten_percentage_100_g,

							sub_category_carb_min_100_g=$inp_current_sub_category_carb_min_100_g, 
							sub_category_carb_med_100_g=$inp_current_sub_category_carb_med_sum_100_g, 
							sub_category_carb_max_100_g=$inp_current_sub_category_carb_max_100_g, 
							sub_category_carb_p_ten_percentage_100_g=$inp_carb_p_ten_percentage_100_g,
							sub_category_carb_m_ten_percentage_100_g=$inp_carb_m_ten_percentage_100_g,

							sub_category_carb_of_which_sugars_min_100_g=$inp_current_sub_category_carb_of_which_sugars_min_100_g, 
							sub_category_carb_of_which_sugars_med_100_g=$inp_current_sub_category_carb_of_which_sugars_med_sum_100_g, 
							sub_category_carb_of_which_sugars_max_100_g=$inp_current_sub_category_carb_of_which_sugars_max_100_g, 
							sub_category_carb_of_which_sugars_p_ten_percentage_100_g=$inp_carb_of_which_sugars_p_ten_percentage_100_g,
							sub_category_carb_of_which_sugars_m_ten_percentage_100_g=$inp_carb_of_which_sugars_m_ten_percentage_100_g,

							sub_category_added_sugars_min_100_g=$inp_current_sub_category_added_sugars_min_100_g, 
							sub_category_added_sugars_med_100_g=$inp_current_sub_category_added_sugars_med_sum_100_g, 
							sub_category_added_sugars_max_100_g=$inp_current_sub_category_added_sugars_max_100_g, 
							sub_category_added_sugars_p_ten_percentage_100_g=$inp_added_sugars_p_ten_percentage_100_g,
							sub_category_added_sugars_m_ten_percentage_100_g=$inp_added_sugars_m_ten_percentage_100_g,

							sub_category_dietary_fiber_min_100_g=$inp_current_sub_category_dietary_fiber_min_100_g, 
							sub_category_dietary_fiber_med_100_g=$inp_current_sub_category_dietary_fiber_med_sum_100_g, 
							sub_category_dietary_fiber_max_100_g=$inp_current_sub_category_dietary_fiber_max_100_g, 
							sub_category_dietary_fiber_p_ten_percentage_100_g=$inp_dietary_fiber_p_ten_percentage_100_g,
							sub_category_dietary_fiber_m_ten_percentage_100_g=$inp_dietary_fiber_m_ten_percentage_100_g,

							sub_category_proteins_min_100_g=$inp_current_sub_category_proteins_min_100_g, 
							sub_category_proteins_med_100_g=$inp_current_sub_category_proteins_med_sum_100_g, 
							sub_category_proteins_max_100_g=$inp_current_sub_category_proteins_max_100_g,
							sub_category_proteins_p_ten_percentage_100_g=$inp_proteins_p_ten_percentage_100_g,
							sub_category_proteins_m_ten_percentage_100_g=$inp_proteins_m_ten_percentage_100_g,

							sub_category_salt_min_100_g=$inp_current_sub_category_salt_min_100_g, 
							sub_category_salt_med_100_g=$inp_current_sub_category_salt_med_sum_100_g, 
							sub_category_salt_max_100_g=$inp_current_sub_category_salt_max_100_g,
							sub_category_salt_p_ten_percentage_100_g=$inp_salt_p_ten_percentage_100_g,
							sub_category_salt_m_ten_percentage_100_g=$inp_salt_m_ten_percentage_100_g,

							sub_category_sodium_min_100_g=$inp_current_sub_category_sodium_min_100_g, 
							sub_category_sodium_med_100_g=$inp_current_sub_category_sodium_med_sum_100_g, 
							sub_category_sodium_max_100_g=$inp_current_sub_category_sodium_max_100_g,
							sub_category_sodium_p_ten_percentage_100_g=$inp_sodium_p_ten_percentage_100_g,
							sub_category_sodium_m_ten_percentage_100_g=$inp_sodium_m_ten_percentage_100_g

							WHERE sub_category_id=$get_current_sub_category_id AND sub_category_translation_language=$l_mysql") or print(mysqli_error());
	
	echo"
		 </tbody>
		</table>
		</div> <!-- //nutritional_facts_wrapper_child -->
		</div> <!-- //nutritional_facts_wrapper_parent -->
	<!-- //Food in category -->

	<!-- Stats -->
		<div class=\"clear\"></div>
		<hr />
		<a id=\"statistics\"></a>
		<h2>$l_statistics_for $get_current_sub_category_translation_value</h2>

		<p style=\"padding-top:0;margin-top:0;\">$l_number_of_foods: $get_current_sub_category_translation_no_food. 
		</p>

		<table class=\"nutritional_facts_statistics\">
		 <thead>
		  <tr>
		   <th>
			<span>$l_per_100_g</span>
		   </th>
		   <th>
			<span>$l_min</span>
		   </th>
		   <th class=\"category_main_left\">
			<span>-10 %</span>
		   </th>
		   <th class=\"category_main_center\">
			<span>$l_med</span>
		   </th>
		   <th class=\"category_main_right\">
			<span>+ 10%</span>
		   </th>
		   <th>
			<span>$l_max</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_cal</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>
			$get_current_sub_category_calories_min_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_calories_m_ten_percentage_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_center\">
			<span>
			$get_current_sub_category_calories_med_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_calories_p_ten_percentage_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>
			$get_current_sub_category_calories_max_100_g
			</span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_fat<br />
			- $l_saturated_fat_lowercase<br />
			- $l_trans_fat_lowercase<br />
			- $l_monounsaturated_fat_lowercase<br />
			- $l_polyunsaturated_fat_lowercase<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>
				$get_current_sub_category_fat_min_100_g<br />
				$get_current_sub_category_saturated_fat_min_100_g<br />
				$get_current_sub_category_trans_fat_min_100_g<br />
				$get_current_sub_category_monounsaturated_fat_min_100_g<br />
				$get_current_sub_category_polyunsaturated_fat_min_100_g<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_fat_m_ten_percentage_100_g<br />
			$get_current_sub_category_saturated_fat_m_ten_percentage_100_g<br />
			$get_current_sub_category_trans_fat_m_ten_percentage_100_g<br />
			$get_current_sub_category_monounsaturated_fat_m_ten_percentage_100_g<br />
			$get_current_sub_category_polyunsaturated_fat_m_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_middle\">
			<span>
				$get_current_sub_category_fat_med_100_g<br />
				$get_current_sub_category_saturated_fat_med_100_g<br />
				$get_current_sub_category_trans_fat_med_100_g<br />
				$get_current_sub_category_monounsaturated_fat_med_100_g<br />
				$get_current_sub_category_polyunsaturated_fat_med_100_g<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_fat_p_ten_percentage_100_g<br />
			$get_current_sub_category_saturated_fat_p_ten_percentage_100_g<br />
			$get_current_sub_category_trans_fat_p_ten_percentage_100_g<br />
			$get_current_sub_category_monounsaturated_fat_p_ten_percentage_100_g<br />
			$get_current_sub_category_polyunsaturated_fat_p_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>
				$get_current_sub_category_fat_max_100_g<br />
				$get_current_sub_category_saturated_fat_max_100_g<br />
				$get_current_sub_category_trans_fat_max_100_g<br />
				$get_current_sub_category_monounsaturated_fat_max_100_g<br />
				$get_current_sub_category_polyunsaturated_fat_max_100_g<br />
			</span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_carbs<br />
			$l_dash_of_which_sugars_calculated<br />
			$l_dash_included_added_sugar</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>
			$get_current_sub_category_carb_min_100_g<br />
			$get_current_sub_category_carb_of_which_sugars_min_100_g<br />
			$get_current_sub_category_added_sugars_min_100_g<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_carb_m_ten_percentage_100_g<br />
			$get_current_sub_category_carb_of_which_sugars_m_ten_percentage_100_g<br />
			$get_current_sub_category_added_sugars_m_ten_percentage_100_g<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_middle\">
			<span>
			$get_current_sub_category_carb_med_100_g<br />
			$get_current_sub_category_carb_of_which_sugars_med_100_g<br />
			$get_current_sub_category_added_sugars_med_100_g<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_carb_p_ten_percentage_100_g<br />
			$get_current_sub_category_carb_of_which_sugars_p_ten_percentage_100_g<br />
			$get_current_sub_category_added_sugars_p_ten_percentage_100_g<br />
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>
			$get_current_sub_category_carb_max_100_g<br />
			$get_current_sub_category_carb_of_which_sugars_max_100_g<br />
			$get_current_sub_category_added_sugars_max_100_g<br />
			</span>
		   </td>
		 </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_dietary_fiber</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_dietary_fiber_min_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_dietary_fiber_m_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_center\">
			<span>$get_current_sub_category_dietary_fiber_med_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_dietary_fiber_p_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_dietary_fiber_max_100_g</span>
		   </td>
		  </tr>
		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_proteins</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_proteins_min_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_proteins_m_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_center\">
			<span>$get_current_sub_category_proteins_med_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_proteins_p_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_proteins_max_100_g</span>
		   </td>
		  </tr>

		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_salt</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_salt_min_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_salt_m_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_center\">
			<span>$get_current_sub_category_salt_med_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_salt_p_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_salt_max_100_g</span>
		    </td>
		   </tr>

		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_sodium</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_sodium_min_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_sodium_m_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_center\">
			<span>$get_current_sub_category_sodium_med_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_sodium_p_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_sodium_max_100_g</span>
		    </td>
		   </tr>

		  <tr>
		   <td style=\"padding: 8px 4px 6px 8px;\">
			<span>$l_cholesterol</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_cholesterol_min_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_left\">
			<span>
			$get_current_sub_category_cholesterol_m_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_center\">
			<span>$get_current_sub_category_cholesterol_med_100_g</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\" class=\"category_main_right\">
			<span>
			$get_current_sub_category_cholesterol_p_ten_percentage_100_g
			</span>
		   </td>
		   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
			<span>$get_current_sub_category_cholesterol_max_100_g</span>
		    </td>
		   </tr>

		 </tbody>
		</table>
		
		<!-- //Stats -->
	";
	} // can view
} // category found


/*- Footer ----------------------------------------------------------------------------------- */
include("open_sub_category_nutritional_facts_include_footer.php");
?>