<?php
/**
*
* File: _food/food_without_image.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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
include("$root/_admin/_translations/site/$l/food/ts_food_without_image.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_food_without_image - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_food_without_image</h1>
<!-- //Headline and language -->


<!-- Where am I? -->
	<p><b>$l_you_are_here:</b><br />
	<a href=\"index.php?l=$l\">$l_food</a>
	&gt;
	<a href=\"food_without_image.php?l=no\">$l_food_without_image</a>
	</p>
<!-- //Where am I ? -->


<!-- Sorting -->
	<div style=\"float: right;\">
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
			});
			</script>
		
        		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
			<p>
			<select name=\"inp_order_by\" id=\"inp_order_by_select\">
				<option value=\"food_without_image.php?l=$l\">- $l_order_by -</option>
				<option value=\"food_without_image.php?order_by=food_id&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_id" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_date</option>
				<option value=\"food_without_image.php?order_by=food_name&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_name"){ echo" selected=\"selected\""; } echo">$l_name</option>
				<option value=\"food_without_image.php?order_by=food_unique_hits&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
				<option value=\"food_without_image.php?order_by=food_energy&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_energy"){ echo" selected=\"selected\""; } echo">$l_calories</option>
				<option value=\"food_without_image.php?order_by=food_fat&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_fat"){ echo" selected=\"selected\""; } echo">$l_fat</option>
				<option value=\"food_without_image.php?order_by=food_carbohydrates&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_carbohydrates"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
				<option value=\"food_without_image.php?order_by=food_proteins&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_proteins"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
			</select>
			<select name=\"inp_order_method\" id=\"inp_order_method_select\">";
				if($order_by == ""){
					$order_by = "food_id";
				}
				echo"
				<option value=\"food_without_image.php?order_by=$order_by&amp;order_method=asc&amp;l=$l\""; if($order_method == "asc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_asc</option>
				<option value=\"food_without_image.php?order_by=$order_by&amp;order_method=desc&amp;l=$l\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
			</select>
			</p>
        		</form>
	</div>
<!-- //Sorting -->

<!-- Show categories and foods -->
	<div class=\"clear\"></div>
	<div id=\"nettport_search_results\">
	";
	
	// Set layout
	$x = 0;

	// Get all food
	$query = "SELECT food_id, food_user_id, food_name, food_manufacturer_name, food_description, food_serving_size_gram, food_serving_size_gram_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_unique_hits, food_likes, food_dislikes FROM $t_food_index WHERE food_language=$l_mysql AND food_image_a=''";

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
		list($get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_unique_hits, $get_food_likes, $get_food_dislikes) = $row;
				
		// Name saying
		$title = "$get_food_manufacturer_name $get_food_name";
		$check = strlen($title);
		if($check > 35){
			$title = substr($title, 0, 35);
			$title = $title . "...";
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
				<a href=\"view_food.php?food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
				";
				echo"
				</p>

				<table style=\"margin: 0px auto;\">
				 <tr>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$get_food_energy</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$get_food_fat</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$get_food_carbohydrates</span>
				  </td>
				  <td style=\"text-align: center;\">
					<span class=\"grey_smal\">$get_food_proteins</span>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$l_cal_lowercase</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$l_fat_lowercase</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$l_carb_lowercase</span>
				  </td>
				  <td style=\"text-align: center;\">
					<span class=\"grey_smal\">$l_proteins_lowercase</span>
				  </td>
				 </tr>
				</table>

			</div>
			";

			// Increment
			$x++;
		
		// Reset
		if($x == 4){
			$x = 0;
		}
	} // while

	echo"
	</div>
<!-- Show foods -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>