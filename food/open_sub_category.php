<?php
/**
*
* File: _food/open_sub_category.php
* Version 1.0.0.
* Date 11:25 02.02.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
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

if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
	if(!(is_numeric($sub_category_id))){
		echo"sub_category_id has to be numeric";
		die;
	}
}
else{
	$sub_category_id = "";
	echo"sub_category_id missing";
	die;
}

if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "food_score";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}
if(isset($_GET['store_id'])) {
	$store_id = $_GET['store_id'];
	$store_id = strip_tags(stripslashes($store_id));
}
else{
	$store_id = "";
}
$store_id_mysql = quote_smart($link, $store_id);

if(isset($_GET['system'])){
	$system = $_GET['system'];
	$system = strip_tags(stripslashes($system));
	if($system != "all" && $system != "metric" && $system != "us"){
		echo"Unknown system";
		die;
	}
}
else{
	$system = "metric";
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
	$website_title = "$get_current_sub_category_translation_value - $get_current_main_category_translation_value - $get_current_title_value";

}

/*- Headers ---------------------------------------------------------------------------------- */
include("$root/_webdesign/header.php");



if($get_current_sub_category_id == ""){
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

		// Unique hits
		if($get_current_sub_category_unique_hits_this_year_year != "$year"){
			echo"<p>Happy new year!</p>";
			$year_mysql = quote_smart($link, $year);
			$result = mysqli_query($link, "UPDATE $t_food_categories_sub_translations SET 
							sub_category_unique_hits_this_year=0, 
							sub_category_unique_hits_this_year_year=$year_mysql, 
							sub_category_unique_hits_ip_block='' 
							WHERE sub_category_translation_id=$get_current_sub_category_translation_id") or die(mysqli_error($link));
			$get_current_sub_category_unique_hits_ip_block = "";
			$get_current_sub_category_unique_hits_this_year = 0;
		}
		$ip_array = explode("\n", $get_current_sub_category_unique_hits_ip_block);
		$ip_array_size = sizeof($ip_array);

		$has_seen_this_category_before = 0;
		for($x=0;$x<$ip_array_size;$x++){
			if($ip_array[$x] == "$my_ip"){
				$has_seen_this_food_before = 1;
				break;
			}
			if($x > 5){
				break;
			}
		}
		if($has_seen_this_category_before == 0){
			$inp_ip_block = $my_ip . "\n" . $get_current_sub_category_unique_hits_ip_block;
			$inp_ip_block_mysql = quote_smart($link, $inp_ip_block);
			$inp_unique_hits = $get_current_sub_category_unique_hits + 1;
			$inp_unique_hits_this_year = $get_current_sub_category_unique_hits_this_year + 1;
			$result = mysqli_query($link, "UPDATE $t_food_categories_sub_translations SET 
							sub_category_unique_hits=$inp_unique_hits, 
							sub_category_unique_hits_this_year=$inp_unique_hits_this_year, 
							sub_category_unique_hits_ip_block=$inp_ip_block_mysql 
							WHERE sub_category_translation_id=$get_current_sub_category_translation_id") or die(mysqli_error($link));
		}


		echo"
		<!-- Headline, buttons, search -->
		<div class=\"food_float_left\">
		
			<!-- Headline -->
				<h1>$get_current_sub_category_translation_value</h1>
			<!-- //Headline -->

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$get_current_title_value</a>
				&gt;
				<a href=\"open_main_category.php?main_category_id=$get_current_main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
				&gt;
				<a href=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;l=$l\">$get_current_sub_category_translation_value</a>
				</p>
			<!-- //Where am I ? -->

		</div>
		<div class=\"food_float_right\">
		
			<!-- Food menu -->


				<p>
				<a href=\"$root/food/my_food.php?l=$l\" class=\"btn_default\">$l_my_food</a>
				<a href=\"$root/food/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
				<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
				</p>
			<!-- //Food menu -->

			<!-- System -->
				<p>
				$l_system:
				<a href=\"open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;system=metric&amp;l=$l\""; if($system == "metric"){ echo" style=\"font-weight:bold;\""; } echo">$l_metric</a>
				&middot;
				<a href=\"open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;system=us&amp;l=$l\""; if($system == "us"){ echo" style=\"font-weight:bold;\""; } echo">$l_us</a>
				</p>
			<!-- System -->
		</div>
		<div class=\"clear\"></div>
	<!-- //Headline, buttons, search -->


	<!-- Food Search -->
		<div class=\"food_search\">
			<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
			<p>
			<input type=\"text\" name=\"search_query\" id=\"nettport_inp_search_query\" value=\"\" size=\"10\" style=\"width: 50%;\"  />
			<input type=\"hidden\" name=\"system\" value=\"$system\" />
			<input type=\"hidden\" name=\"l\" value=\"$l\" />
			<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
			</p>
			</form>
		</div>

		<!-- Search script -->
		<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
		\$(document).ready(function () {
			\$('#nettport_inp_search_query').keyup(function () {
       				// getting the value that user typed
       				var searchString    = $(\"#nettport_inp_search_query\").val();
 				// forming the queryString
      				var data            = 'order_by=$order_by&order_method=$order_method&l=$l&search_query='+ searchString;
         
        			// if searchString is not empty
        			if(searchString) {
           				// ajax call
            				\$.ajax({
                				type: \"GET\",
               					url: \"search_jquery.php\",
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
		<div id=\"nettport_search_results\">
		</div>
	<!-- //Food Search -->

	<!-- User adaptet view -->";
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
		if($get_current_view_id == ""){
			$get_current_view_system = "metric";
			$get_current_view_hundred_metric = 1;
			$get_current_view_pcs_metric = 1;
		}
		echo"
		<p>
		<b>$l_show_per:</b>
		<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=1&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } echo" /> $l_hundred
		<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=1&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } echo" /> $l_pcs_g
		<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=eight_us&amp;value=1&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } echo" /> $l_eight
		<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=1&amp;process=1&amp;referer=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;l=$l\""; } echo" /> $l_pcs_oz
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

	<!-- //User adaptet view -->

	<!-- All main categories -->
		<div class=\"clear\"></div>
		<div class=\"food_all_main_categories_selector\">
			<a href=\"#\" id=\"show_all_main_categories_link_img\"><img src=\"_gfx/show_all_categories_img.png\" alt=\"show_all_categories_img.png\" class=\"show_all_main_categories_img\" /></a>
			<a href=\"#\" id=\"show_all_main_categories_link_text\">$l_categories</a>
		</div>

		<script>
		\$(document).ready(function(){
			\$(\"#show_all_main_categories_link_img\").click(function () {
				\$(\"#food_show_all_main_categories\").toggle();
			});
			\$(\"#show_all_main_categories_link_text\").click(function () {
				\$(\"#food_show_all_main_categories\").toggle();
			});
		});
		</script>

		<div id=\"food_show_all_main_categories\">
			<ul>
			";
			// Get main categories
			$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_category_id, $get_main_category_name) = $row;
			
				// Translation
				$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_main_category_id AND main_category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;

				echo"			";
				echo"<li><a href=\"$root/food/open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\">$get_main_category_translation_value</a></li>\n";
			}
			echo"
			</ul>
		</div>
	<!-- //All main categories -->

	<!-- Sub categories -->
		<div id=\"food_show_sub_categories\">
			<ul>";
			$queryb = "SELECT sub_category_id, sub_category_name, sub_category_symbolic_link_to_category_id FROM $t_food_categories_sub WHERE sub_category_parent_id='$get_current_main_category_id' ORDER BY sub_category_name ASC";
			$resultb = mysqli_query($link, $queryb);
			while($rowb = mysqli_fetch_row($resultb)) {
				list($get_sub_category_id, $get_sub_category_name, $get_sub_category_symbolic_link_to_category_id) = $rowb;

				// Translation
				$query_t = "SELECT sub_category_translation_id, sub_category_translation_value FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_sub_category_id AND sub_category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_sub_category_translation_id, $get_sub_category_translation_value) = $row_t;

				// Link
				if($get_sub_category_symbolic_link_to_category_id == "0"){
					$category_link = "open_sub_category.php?sub_category_id=$get_sub_category_id&amp;l=$l";
				}
				else{
					$category_link = "open_sub_category.php?sub_category_id=$get_sub_category_symbolic_link_to_category_id&amp;l=$l";
				}
				
				
				echo"
				<li><a href=\"$category_link\""; if($get_sub_category_id == "$sub_category_id"){ echo" class=\"active\""; } echo">$get_sub_category_translation_value</a></li>
				";
			}
			echo"
			</ul>
		</div>
	<!-- //Sub categories -->


	<!-- Sorting -->
		<div style=\"margin-top: 20px;\">
			<script>
			\$(function(){
				\$('#inp_order_by_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_order_method_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_store_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
			</script>
		
        		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
			<p>
			<select name=\"inp_order_by\" id=\"inp_order_by_select\">
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;store_id=$store_id&amp;system=$system&amp;l=$l\">- $l_order_by -</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_score&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_score"){ echo" selected=\"selected\""; } echo">$l_score</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_id&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_id"){ echo" selected=\"selected\""; } echo">$l_date</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_name&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_name"){ echo" selected=\"selected\""; } echo">$l_name</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_manufacturer_name_and_food_name&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_manufacturer_name_and_food_name" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_manufacturer_and_name</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_unique_hits&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_energy_metric&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_energy_metric"){ echo" selected=\"selected\""; } echo">$l_calories</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_fat_metric&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_fat_metric"){ echo" selected=\"selected\""; } echo">$l_fat</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_carbohydrates_metric&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_carbohydrates_metric"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=food_proteins_metric&amp;order_method=$order_method&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_by == "food_proteins_metric"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
			</select>
			<select name=\"inp_order_method\" id=\"inp_order_method_select\">";
				if($order_by == ""){
					$order_by = "food_manufacturer_name_and_food_name";
				}
				echo"
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=$order_by&amp;order_method=asc&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_method == "asc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_asc</option>
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=$order_by&amp;order_method=desc&amp;store_id=$store_id&amp;system=$system&amp;l=$l\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
			</select>
			<select name=\"inp_store\" id=\"inp_store_select\">
				<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=$order_by&amp;order_method=asc&amp;store_id=&amp;system=$system&amp;l=$l\""; if($store_id == ""){ echo" selected=\"selected\""; } echo">- $l_store -</option>\n";

				$query = "SELECT store_id, store_name FROM $t_food_stores WHERE store_language=$l_mysql ORDER BY store_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_store_id, $get_store_name) = $row;
					echo"				";
					echo"<option value=\"open_sub_category.php?sub_category_id=$get_current_sub_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;store_id=$get_store_id&amp;system=$system&amp;l=$l\""; if($get_store_id == "$store_id"){ echo" selected=\"selected\""; } echo">$get_store_name</option>\n";
				}
				echo"				
			</select>
			</p>
        		</form>
	</div>
	<!-- //Sorting -->


	

	<!-- Food in subcategory -->
		";

		// Set layout
		$x = 0;
		$show_food = "true";

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

			if($order_by == "food_score" OR $order_by == "food_id" OR $order_by == "food_manufacturer_name_and_food_name" OR $order_by == "food_name" OR $order_by == "food_unique_hits" 
			OR $order_by == "food_energy_metric" OR $order_by == "food_proteins_metric" OR $order_by == "food_carbohydrates_metric" OR $order_by == "food_fat_metric"){
				$order_by_mysql = "$order_by";
			}



			else{
				$order_by_mysql = "food_id";
			}
			$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";
		}
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
				

			if(file_exists("$root/$get_food_image_path/$get_food_image_a") && $get_food_image_a != "" && $show_food ==  "true"){

				// Store?
				if($store_id != ""){
					$query_store = "SELECT food_store_id FROM $t_food_index_stores WHERE food_store_food_id=$get_food_id AND food_store_store_id=$store_id_mysql";
					$result_store = mysqli_query($link, $query_store);
					$row_store = mysqli_fetch_row($result_store);
					list($get_food_store_id) = $row_store;
					if($get_food_store_id == ""){
						$show_food = "false";
					}
					else{
						$show_food = "true";
					}

				}
			
	
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


				if($get_current_restriction_show_smileys == "1"){
					if($get_food_score > 0){
						echo"
						<img src=\"_gfx/smiley_sad.png\" alt=\"smiley_sad.gif\" title=\"$get_food_score\" class=\"food_score_img\" />";
					}
					elseif($get_food_score < 0){
						echo"
						<img src=\"_gfx/smiley_smile.png\" alt=\"smiley_smile.png\" title=\"$get_food_score\" class=\"food_score_img\" />";
					}
					else{
						echo"
						<img src=\"_gfx/smiley_confused.png\" alt=\"smiley_confused.png\" title=\"$get_food_score\" class=\"food_score_img\" />";
					}
				}

				echo"
				<p style=\"padding-bottom:5px;\">\n";
				if($get_current_restriction_show_image_a == "1"){
					echo"					";
					echo"<a href=\"view_food.php?food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />\n";
				}
				echo"<a href=\"view_food.php?food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
					";
					if($get_food_no_of_comments != ""){
						for($z=0;$z<$get_food_stars;$z++){
							echo"<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" /> ";
						}
						$off = 5-$get_food_stars;
						for($z=0;$z<$off;$z++){
							echo"<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" /> ";
						}
						echo"
						<span class=\"grey\">($get_food_no_of_comments)</span>
						";
					}
					echo"
				</p>
				";
				// Tags
				$t = 0;
				$query_t = "SELECT tag_id, tag_title, tag_title_clean FROM $t_food_index_tags WHERE tag_food_id=$get_food_id ORDER BY tag_title ASC";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_tag_id, $get_tag_title, $get_tag_title_clean) = $row_t;
					if($t == "0"){
						echo"<p style=\"padding-top:0;\">";
					}

					echo"
					<a href=\"view_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"btn_default_small\">$get_tag_title</a>
					";
					$t++;

				}
				if($t > 0){
					echo"</p>";
				}

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
			} // Image
		}

	echo"
	<!-- //Food in category -->

	<!-- Stats -->
		<div class=\"clear\"></div>
		<hr />
		<a id=\"statistics\"></a>
		<h2>$l_statistics</h2>
	
		<p>
		$l_the_numbers_below_are_for_food_in_the_sub_category
		$get_current_sub_category_translation_value. 
		$l_furthermore_they_are_only_for_food_that_uses_the_language
		$get_current_sub_category_translation_language.
		</p>

		<p>
		<b>$get_current_sub_category_translation_value $l_nutritional_facts_lowercase:</b><br />
		<a href=\"open_sub_category_nutritional_facts_eu.php?sub_category_id=$get_current_sub_category_id&amp;l=$l\"><img src=\"_gfx/flags/european_union_16x16.png\" alt=\"european_union_16x16.png\" /> EU</a>
		&nbsp;
		<a href=\"open_sub_category_nutritional_facts_us.php?sub_category_id=$get_current_sub_category_id&amp;l=$l\"><img src=\"_gfx/flags/united_states_16x16.png\" alt=\"united_states_16x16.png\" /> USA</a>
		</p>
		
		<p style=\"padding-top:0;margin-top:0;\"><b>$l_overview:</b><br />
		$l_number_of_foods: $get_current_sub_category_translation_no_food</p>

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
	} // can view food == 1
} // category found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>