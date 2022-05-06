<?php 
/**
*
* File: food/search.php
* Version 1.0.0
* Date 20:50 23.03.2022
* Copyright (c) 2022 S. A. Ditlefsen
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

if(isset($_GET['manufacturer_name'])) {
	$manufacturer_name = $_GET['manufacturer_name'];
	$manufacturer_name = strip_tags(stripslashes($manufacturer_name));
}
else{
	$manufacturer_name = "";
}
if(isset($_GET['store_id'])) {
	$store_id = $_GET['store_id'];
	$store_id = strip_tags(stripslashes($store_id));
	if($store_id != "" && !(is_numeric($store_id))){
		echo"Store id not numeric";
		die;
	}
}
else{
	$store_id = "";
}
$store_id_mysql = quote_smart($link, $store_id);

if(isset($_GET['barcode'])) {
	$barcode = $_GET['barcode'];
	$barcode = strip_tags(stripslashes($barcode));
	if($barcode != "" && !(is_numeric($barcode))){
		echo"Barcode not numeric";
		die;
	}
}
else{
	$barcode = "";
}
// Title
if(!(isset($l_mysql))){
	$l_mysql = quote_smart($link, $l);
}
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_search - $get_current_title_value";
$headline = "$l_search";
$where_am_i = "<a href=\"search.php?l=$l\">$l_search</a>";
if($search_query != ""){
	$website_title = "$search_query - $website_title"; 
	$headline = "$search_query"; 
	$where_am_i = "<a href=\"search.php?search_query=$search_query&amp;l=$l\">$search_query</a>";
}
if($manufacturer_name != ""){
	$website_title = "$manufacturer_name - $website_title"; 
	$headline = "$manufacturer_name"; 
	$where_am_i = "<a href=\"search.php?manufacturer_name=$manufacturer_name&amp;l=$l\">$manufacturer_name</a>";
}
if($store_id != ""){
	$website_title = "$store_id - $website_title"; 
	$headline = "$store_id"; 
	$where_am_i = "<a href=\"search.php?store_id=$store_id&amp;l=$l\">$store_id</a>";
}
if($barcode != ""){
	$website_title = "$barcode - $website_title"; 
	$headline = "$barcode"; 
	$where_am_i = "<a href=\"search.php?barcode=$barcode&amp;l=$l\">$barcode</a>";
}



include("$root/_webdesign/header.php");


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



/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Right menu -->
	<div style=\"float: right;padding-top: 12px;\">
		<p>
		<a href=\"#\" class=\"btn_default food_categories_hide_view_a\">$l_categories</a>
		<a href=\"new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
		</p>
	</div>

	<!-- Categories hide view -->
		<script>
		\$(document).ready(function(){
			\$(\".food_categories_hide_view_a\").click(function () {
				\$(\".food_categories_row\").toggle();
			});
		});
		</script>
	<!-- //Categories hide view -->



<!-- //Right menu  -->

<!-- Healine and where am I? -->
	";
	echo"
	<h1>$headline</h1>

	<!-- Where am I? -->
		<p><b>$l_you_are_here</b><br />
		<a href=\"index.php?l=$l\">$l_food</a>
		&gt;
		$where_am_i
		</p>
	<!-- //Where am I? -->
<!-- //Healine and where am I? -->

<!-- Search form -->
	<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
	<p>
	<input type=\"text\" name=\"search_query\" value=\"$search_query\" size=\"25\" id=\"nettport_inp_search_query\" />
	<input type=\"hidden\" name=\"l\" value=\"$l\" />
	<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
	<a href=\"#\" class=\"content_left_hide_view_a btn_default\">$l_advanced</a>
	</p>
	</form>	
	<!-- Advanced search hide view -->
		<script>
		\$(document).ready(function(){
			\$(\".content_left_hide_view_a\").click(function () {
				\$(\".advanced_search_hide_show\").toggle();
			});
		});
		</script>
	<!-- //Advanced search hide view -->
<!-- //Search form -->


<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
<!-- //Feedback -->

<!-- All categories -->
	<div class=\"clear\"></div>

	<div class=\"food_categories_row\" style=\"display: none;\">
	";
	$x = 0;
	// Get all categories
	$query = "SELECT category_id, category_name, category_icon_path, category_icon_inactive_32x32, category_icon_active_32x32, category_icon_inactive_48x48, category_icon_active_48x48 FROM $t_food_categories";
	$query = $query . " WHERE category_user_id='0' AND category_parent_id='0' ORDER BY category_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_category_id, $get_category_name, $get_category_icon_path, $get_category_icon_inactive_32x32, $get_category_icon_active_32x32, $get_category_icon_inactive_48x48, $get_category_icon_active_48x48) = $row;

		// Translation
		$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_category_translation_value) = $row_t;

		echo"
		<div class=\"food_categories_column\">
			<p>
			<a href=\"open_main_category.php?main_category_id=$get_category_id&amp;l=$l\"><img src=\"$root/$get_category_icon_path/$get_category_icon_inactive_32x32\"  onmouseover=\"this.src='$root/$get_category_icon_path/$get_category_icon_active_32x32'\" onmouseout=\"this.src='$root/$get_category_icon_path/$get_category_icon_inactive_32x32'\" alt=\"$get_category_icon_inactive_32x32\" class=\"grid_icon\" /></a><br />
			<a href=\"open_main_category.php?main_category_id=$get_category_id&amp;l=$l\" class=\"h2\">$get_category_translation_value</a>
			</p>
		</div>
		";
	} // categories
	echo"
	</div> <!-- //food_categories_row -->
<!-- //All categories -->

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
		<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=1&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } echo" /> $l_hundred
		<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=1&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } echo" /> $l_pcs_g
		<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=eight_us&amp;value=1&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } echo" /> $l_eight
		<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=1&amp;process=1&amp;referer=search&amp;search_query=$search_query&amp;l=$l\""; } echo" /> $l_pcs_oz
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
<!-- Advanced search -->
	<div class=\"advanced_search_hide_show\" style=\"background:#f6f6f6;border: #ccc 1px solid;padding: 0px 20px 0px 20px;margin: 10px 0px 10px 0px;";
	if($manufacturer_name != "" OR $store_id != "" OR $barcode != ""){
		echo"display: inline-block;";
	}
	echo"\">

		<p style=\"margin:0;padding: 10px 0px 0px 0px;\"><b>$l_specific_search</b></p>

        	<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
		
		<table>
		 <tr>
		  <td style=\"padding-right: 20px;vertical-align:top;\">
			<p>$l_query<br />
			<input type=\"text\" name=\"q\" value=\""; 
				if($search_query != ""){
					echo"$search_query"; 
				}
				else{
					echo"$l_search..."; 
				} 
			echo"\" size=\"15\" id=\"food_search_q\" />
			<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
			</p>

			<p>$l_barcode<br />
			<input type=\"text\" name=\"barcode\" value=\""; if($barcode != ""){ echo"$barcode"; } else{  }  echo"\" size=\"15\" class=\"recipe_search_text\" />
			<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
			</p>
		  </td>
		  <td style=\"padding-right: 20px;vertical-align:top;\">
			<p>$l_manufacturer<br />
			<input type=\"text\" name=\"manufacturer_name\" value=\""; if($manufacturer_name != ""){ echo"$manufacturer_name"; } else{  }  echo"\" size=\"15\" class=\"recipe_search_text\" />
			<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
			</p>

			<p>$l_store<br />
			<select name=\"store_id\">
				<option value=\"\""; if($store_id == ""){ echo" selected=\"selected\""; } echo"> </option>\n";
			$query = "SELECT store_id, store_name FROM $t_food_stores WHERE store_language=$l_mysql ORDER BY store_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_store_id, $get_store_name) = $row;
				echo"	<option value=\"$get_store_id\""; if($store_id == "$get_store_id"){ echo" selected=\"selected\""; } echo">$get_store_name</option>\n";
			}
			echo"</select>
			<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
			</p>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding-right: 20px;vertical-align:top;\">
			<p>$l_order_by<br />
			<select name=\"order_by\">
				<option value=\"\">- $l_order_by -</option>
				<option value=\"food_id\""; if($order_by == "food_id" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_date</option>
				<option value=\"food_name\""; if($order_by == "food_name"){ echo" selected=\"selected\""; } echo">$l_name</option>
				<option value=\"recipe_unique_hits\""; if($order_by == "food_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
				<option value=\"food_energy\""; if($order_by == "food_energy"){ echo" selected=\"selected\""; } echo">$l_calories</option>
				<option value=\"food_proteins\""; if($order_by == "food_proteins"){ echo" selected=\"selected\""; } echo">$l_fat</option>
				<option value=\"food_carbohydrates\""; if($order_by == "food_carbohydrates"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
				<option value=\"food_fat\""; if($order_by == "food_fat"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
				<option value=\"food_score\""; if($order_by == "food_score"){ echo" selected=\"selected\""; } echo">$l_score</option>
			</select>
			</p>
		  </td>
		  <td style=\"padding-right: 20px;vertical-align:top;\">
			<p>$l_method<br />
			<select name=\"order_method\">
				<option value=\"asc\""; if($order_method == "asc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_asc</option>
				<option value=\"desc\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
			</select>
			</p>
		  </td>
		 </tr>
		</table>
		</form>
	</div>

<!-- //Advanced search -->
	

";

if($search_query != "" OR $manufacturer_name != "" OR $store_id != "" OR $barcode != ""){
	
	// Check for hacker
	if($search_query != ""){
		include("$root/_admin/_functions/look_for_hacker_in_string.php");
	}
	if($manufacturer_name != ""){
		$search_query_store_variable  = "$search_query";
		$search_query  = "$manufacturer_name";
		include("$root/_admin/_functions/look_for_hacker_in_string.php");
		$search_query  = "$search_query_store_variable";
	}


	$search_results_count = 0;

	
	// Set layout
	$x = 0;
	$show_food = "true";

	// 1. Search for name
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index";
	$query = $query . "  WHERE food_language=$l_mysql";

	if($search_query != ""){
		$search_query_mysql = quote_smart($link, $search_query);

		$search_query_like = "%" . $search_query . "%";
		$search_query_like_mysql = quote_smart($link, $search_query_like);
		$query = $query . " AND ($t_food_index.food_name LIKE $search_query_like_mysql OR $t_food_index.food_manufacturer_name LIKE $search_query_like_mysql OR $t_food_index.food_barcode LIKE $search_query_like_mysql)";
	}

	if($manufacturer_name != ""){
		$manufacturer_name_q = "%" . $manufacturer_name . "%";
		$manufacturer_name_mysql = quote_smart($link, $manufacturer_name_q);
		$query = $query . " AND $t_food_index.food_manufacturer_name LIKE $manufacturer_name_mysql";
	}

	if($barcode != ""){
		$barcode_mysql = quote_smart($link, $barcode);
		$query = $query . " AND food_barcode=$barcode_mysql";
	}

	// Order
	if($order_by != ""){
		if($order_method == "desc"){
			$order_method_mysql = "DESC";
		}
		else{
			$order_method_mysql = "ASC";
		}

		if($order_by == "food_id" OR $order_by == "food_name" OR $order_by == "food_unique_hits" OR $order_by == "food_score"){
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

		if(file_exists("$root/$get_food_image_path/$get_food_thumb_a_small") && $get_food_thumb_a_small != ""){
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


			if($show_food == "true"){
			
				// 3 divs

				// 600 / 4 = 150
				// 600 / 3 = 200




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
						<div style=\"text-align: center;\">
							<p class=\"recipe_open_category_img_p\">
							<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_thumb_a_small\" /></a><br />
							</p>
		
							<p class=\"recipe_open_category_p\">
							<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"recipe_open_category_a\">$get_food_name</a>";
							if($get_food_no_of_comments != ""){
								echo"<br />\n";
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
							
						</div>";

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

				$search_results_count++;
			} // show food
		} // get_recipe_image
	} // while

	// 2. Search for tags
	if($barcode == ""){
		$search_query_like = "%" . $search_query . "%";
		$search_query_like_mysql = quote_smart($link, $search_query_like);

		$query = "SELECT tag_id, tag_food_id FROM $t_food_index_tags WHERE tag_language=$l_mysql AND tag_title LIKE $search_query_like_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_tag_id, $get_tag_food_id) = $row;

			// Get food
			$query_food = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_tag_food_id";
			$result_food = mysqli_query($link, $query_food);
			$row_food = mysqli_fetch_row($result_food);
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row_food;	

			if(file_exists("$root/$get_food_image_path/$get_food_thumb_a_small") && $get_food_thumb_a_small != ""){
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


				if($show_food == "true"){
			
					// 3 divs

					// 600 / 4 = 150
					// 600 / 3 = 200


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
						<div style=\"text-align: center;\">
							<p class=\"recipe_open_category_img_p\">
							<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_thumb_a_small\" /></a><br />
							</p>
		
							<p class=\"recipe_open_category_p\">
							<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"recipe_open_category_a\">$get_food_name</a>";
							if($get_food_no_of_comments != ""){
								echo"<br />\n";
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

						</div>";

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

					$search_results_count++;
				} // show food
			} // get_recipe_image
		} // while
	} // not barcode

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


	if($search_results_count == 0){
		echo"
		<p>$l_no_food_found</p>
		";

		// Send email to moderator
		$search_query = str_replace("%", "", $search_query);
		$search_query_encrypted = md5("$search_query");
		$search_query_antispam_file = "$root/_cache/recipe_search_no_results_" . $search_query_encrypted . ".txt";
		
		if(!(file_exists("$search_query_antispam_file")) && $search_query != ""){
			
			$fh = fopen($search_query_antispam_file, "w") or die("can not open file");
			fwrite($fh, "$search_query");
			fclose($fh);
			
		
			// Who is moderator of the week?
			$week = date("W");
			$year = date("Y");
	
			$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			if($get_moderator_user_id == ""){
				// Create moderator of the week
				include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
				$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			}




			// Mail from
			$host = $_SERVER['HTTP_HOST'];
			$from = "$configFromEmailSav";
			$reply = "$configFromEmailSav";
			
			$search_link = $configSiteURLSav . "/food/search.php?q=$search_query&amp;l=$l";
			$subject = "No search result for $search_query at $host";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
			$message = $message . "<p><b>Summary:</b><br />A user has searched for <em>$search_query</em> and got no search results at $host for lanugage $l.\n";
			$message = $message . "Please consider to add a recipe for that query.</p>\n\n";

			$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Search information:</b></p>\n";
			$message = $message . "<table>\n";
			$message = $message . " <tr><td><span>Query:</span></td><td><span>$search_query</span></td></tr>\n";
			$message = $message . " <tr><td><span>Link:</span></td><td><span><a href=\"$search_link\">$search_link</a></span></td></tr>\n";
			$message = $message . "</table>\n";

			$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";


			$encoding = "utf-8";

			// Preferences for Subject field
			$subject_preferences = array(
			       "input-charset" => $encoding,
			       "output-charset" => $encoding,
			       "line-length" => 76,
			       "line-break-chars" => "\r\n"
			);
			$header = "Content-type: text/html; charset=".$encoding." \r\n";
			$header .= "From: ".$host." <".$from."> \r\n";
			$header .= "MIME-Version: 1.0 \r\n";
			$header .= "Content-Transfer-Encoding: 8bit \r\n";
			$header .= "Date: ".date("r (T)")." \r\n";
			$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

			mail($get_moderator_user_email, $subject, $message, $header);

			// echo"<p>Our moderator $get_moderator_user_name will look at this query and maybe add a recipe for it later.</p>";
		}
	}
}
else{
	echo"
	<p>$l_type_your_search_in_the_search_field</p>
	";
}
echo"

<!-- Last seen -->
	<div class=\"clear\"></div>
	
	<h2 style=\"margin-top: 10px;\">$l_last_viewed</h2>


	";
	
	// Set layout
	$nutritional_content_layout = "1";

	$x = 0;

	// Get all food
	$show_food 	= 1;
	$show_image_a	= 1;
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql ORDER BY food_last_viewed DESC LIMIT 0,12";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

		// Age limit?

		if($get_food_age_restriction == "1"){
			if($get_current_restriction_show_food == "1"){
				$show_food = 1;
			}
			else{
				$show_food = 0;
			}
			if($get_current_restriction_show_image_a == "1"){
				$show_image_a      = 1;
			}
			else{
				$show_image_a      = 0;
			}
		}
		else{
			$show_food 	= 1;
			$show_image_a	= 1;
		}


		if($show_food == "1" && $get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){	
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
				<p style=\"padding-bottom:5px;\">";
				if($show_image_a == "1"){
					echo"<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />\n";
				}
				echo"					
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a>";
							if($get_food_no_of_comments != ""){
								echo"<br />\n";
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

		if($nutritional_content_layout == "1" && ($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1")){
				
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
				if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
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
			}
			elseif($nutritional_content_layout == "2" && ($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1")){
				
					echo"
					<table style=\"margin: 0px auto;\">
					 <tr>
					  <td style=\"padding-right: 3px;\">
					  </td>";
					if($get_current_view_hundred_metric == "1"){
						echo"
						  <td style=\"padding-right: 3px;text-align: center;vertical-align: bottom;\">
							<span class=\"grey_small\">$l_hundred</span>
						  </td>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						  <td style=\"padding-right: 3px;text-align: center;vertical-align: bottom;\">
							<span class=\"grey_small\" title=\"$get_food_serving_size_metric $get_food_serving_size_measurement_metric\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement<br />$get_food_serving_size_metric $get_food_serving_size_measurement_metric</span>
						  </td>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						  <td style=\"padding-right: 3px;text-align: center;vertical-align: bottom;\">
							<span class=\"grey_small\">$l_eight</span>
						  </td>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						  <td style=\"padding-right: 3px;text-align: center;vertical-align: bottom;\">
							<span class=\"grey_small\" title=\"$get_food_serving_size_us $get_food_serving_size_measurement_us\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement<br />$get_food_serving_size_us $get_food_serving_size_measurement_us</span>
						  </td>
						";
					}
					echo"
					 </tr>
					 <tr>
					  <td style=\"text-align: center;\">
						<span class=\"grey_small\">$l_calories_abbr_lowercase</span>
					  </td>";
					if($get_current_view_hundred_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_energy_metric</span>
						  </td>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_energy_calculated_metric</span>
						  </td>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_energy_us</span>
						  </td>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_energy_calculated_us</span>
						  </td>
						";
					}
					echo"
					 </tr>
					 <tr>
					  <td style=\"text-align: center;\">
						<span class=\"grey_small\">$l_fat_abbr_lowercase</span>
					  </td>";
					if($get_current_view_hundred_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_fat_metric</span>
						  </td>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_fat_calculated_metric</span>
						  </td>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_fat_us</span>
						  </td>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_fat_calculated_us</span>
						  </td>
						";
					}
					echo"
					 </tr>
					 <tr>
					  <td style=\"text-align: center;\">
						<span class=\"grey_small\">$l_carbohydrates_abbr_lowercase</span>
					  </td>";
					if($get_current_view_hundred_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_carbohydrates_metric</span>
						  </td>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_carbohydrates_calculated_metric</span>
						  </td>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_carbohydrates_us</span>
						  </td>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_carbohydrates_calculated_us</span>
						  </td>
						";
					}
					echo"
					 </tr>
					 <tr>
					  <td style=\"text-align: center;\">
						<span class=\"grey_small\">$l_proteins_abbr_lowercase</span>
					  </td>";
					if($get_current_view_hundred_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_proteins_metric</span>
						  </td>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_proteins_calculated_metric</span>
						  </td>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_proteins_us</span>
						  </td>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						  <td style=\"text-align: center;\">
							<span class=\"grey_small\">$get_food_proteins_calculated_us</span>
						  </td>
						";
					}
				echo"
					 </tr>
				</table>
				";

			} // $nutritional_content_layout == 2
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
	} // while
	if($x == "0"){
		echo"
				<div class=\"clear\"></div>
		";
	}
	elseif($x == "2"){
		echo"
				<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
				</div>
				<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
				</div>
				<div class=\"clear\"></div>
		";
	}
	elseif($x == "3"){
		echo"
				<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
				</div>
				<div class=\"clear\"></div>
		";
	}
	echo"

<!-- //Last seen -->

";



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>