<?php
/**
*
* File: food/index.php
* Version 2.0
* Date 15:41 18.10.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
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
if(!(isset($l_mysql))){
	$l_mysql = quote_smart($link, $l);
}
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

// My user
if(isset($_SESSION['user_id'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$q = "SELECT user_id, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_my_user_id, $get_my_user_rank) = $rowb;
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_current_title_value";
include("$root/_webdesign/header.php");


// Age limit?
$get_current_restriction_show_food = 0;
$get_current_restriction_show_image_a = 0;
$get_current_restriction_show_image_b = 0;
$get_current_restriction_show_image_c = 0;
$get_current_restriction_show_image_d = 0;
$get_current_restriction_show_image_e = 0;
$get_current_restriction_show_smileys = 0;

$inp_ip_mysql = quote_smart($link, $my_ip);
$query_t = "SELECT accepted_id, accepted_country FROM $t_food_age_restrictions_accepted WHERE accepted_ip=$inp_ip_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_accepted_id, $get_current_accepted_country) = $row_t;

if($get_current_accepted_id == ""){
	// Age restriction not accepted
}
else{
	// Can I see food and images?
	$country_mysql = quote_smart($link, $get_current_accepted_country);
	$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_show_food, restriction_show_image_a, restriction_show_image_b, restriction_show_image_c, restriction_show_image_d, restriction_show_image_e, restriction_show_smileys FROM $t_food_age_restrictions WHERE restriction_country_iso_two=$country_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_restriction_id, $get_current_restriction_country_name, $get_current_restriction_country_iso_two, $get_current_restriction_country_flag_path_16x16, $get_current_restriction_country_flag_16x16, $get_current_restriction_language, $get_current_restriction_age_limit, $get_current_restriction_title, $get_current_restriction_text, $get_current_restriction_show_food, $get_current_restriction_show_image_a, $get_current_restriction_show_image_b, $get_current_restriction_show_image_c, $get_current_restriction_show_image_d, $get_current_restriction_show_image_e, $get_current_restriction_show_smileys) = $row;
	if($get_current_restriction_id == ""){
		// Could not find country
		echo"<div class=\"error\"><p>Could not find country.</p></div>\n";
	}
}
echo"


<!-- Headline, buttons, search -->
	<div class=\"food_float_left\">
		<h1>$get_current_title_value</h1>
	</div>
	<div class=\"food_float_right\">
		
		<!-- Food menu -->

			<p>
			<a href=\"$root/food/my_food.php?l=$l\" class=\"btn_default\">$l_my_food</a>
			<a href=\"$root/food/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
			<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>\n";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_active_16x16, language_active_flag_inactive_16x16, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, $get_language_active_flag_inactive_16x16, $get_language_active_default) = $row;
				echo"
				<a href=\"index.php?l=$get_language_active_iso_two\"><img src=\"$root/$get_language_active_flag_path_16x16/$get_language_active_flag_active_16x16\" alt=\"$get_language_active_flag_active_16x16\" /></a>
				";
			}
			echo"
			</p>
		<!-- //Food menu -->
	</div>
	<div class=\"clear\"></div>
<!-- //Headline, buttons, search -->


<!-- Food Search and Tags -->
		

	<div class=\"food_search\">
		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"text\" name=\"search_query\" id=\"nettport_inp_search_query\" value=\"\" placeholder=\"$l_search_for_food\" size=\"10\" style=\"width: 80%;\"  />
		<input type=\"hidden\" name=\"l\" value=\"$l\" />
		<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
		</p>
		</form>
	</div>

	<!-- Best categories + Best tags -->
	<div class=\"clear\"></div>
	<p style=\"padding: 0px 0px 0px 0px;margin: 0px 0px 0px 0px;\">
	";
	$query_c = "SELECT sub_category_translation_id, sub_category_id, sub_category_translation_value FROM $t_food_categories_sub_translations WHERE sub_category_translation_language=$l_mysql ORDER BY sub_category_unique_hits_this_year DESC LIMIT 0,10";
	$result_c = mysqli_query($link, $query_c);
	while($row_c = mysqli_fetch_row($result_c)) {
		list($get_sub_category_translation_id, $get_sub_category_id, $get_sub_category_translation_value) = $row_c;
		echo"
		<a href=\"open_sub_category.php?sub_category_id=$get_sub_category_id&l=no&amp;l=$l\" class=\"btn_default\">$get_sub_category_translation_value</a>
		";
	}
	$query_t = "SELECT tag_id, tag_language, tag_title, tag_title_clean FROM $t_food_tags_unique WHERE tag_language=$l_mysql ORDER BY tag_unique_views_counter ASC LIMIT 0,10";
	$result_t = mysqli_query($link, $query_t);
	while($row_t = mysqli_fetch_row($result_t)) {
		list($get_tag_id, $get_tag_language, $get_tag_title, $get_tag_title_clean) = $row_t;
		echo"
		<a href=\"view_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"btn_default\">$get_tag_title</a>
		";
	}
	echo"
	</p>
	<!-- //Best categories + Best tags -->



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
						\$(\"#nettport_search_results\").html(''); 
                    				\$(\"#nettport_search_results\").html(html);
              				}
            			});
       			}
        		return false;
            	});
            });
	</script>
	<!-- //Search script -->
<!-- //Food Search and Tags -->



<!-- All categories -->
	<div class=\"clear\"></div>
	<div class=\"food_categories_row\">
	";
	$x = 0;
	// Get all categories
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit FROM $t_food_categories_main ORDER BY main_category_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_category_id, $get_main_category_name, $get_main_category_icon_path, $get_main_category_icon_inactive_32x32, $get_main_category_icon_active_32x32, $get_main_category_icon_inactive_48x48, $get_main_category_icon_active_48x48, $get_main_category_age_limit) = $row;

		// Translation
		$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_main_category_id AND main_category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;

		echo"
		<div class=\"food_categories_column\">
			<p>
			<a href=\"open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\"><img src=\"$root/$get_main_category_icon_path/$get_main_category_icon_inactive_32x32\"  onmouseover=\"this.src='$root/$get_main_category_icon_path/$get_main_category_icon_active_32x32'\" onmouseout=\"this.src='$root/$get_main_category_icon_path/$get_main_category_icon_inactive_32x32'\" alt=\"$get_main_category_icon_inactive_32x32\" class=\"grid_icon\" /></a><br />
			<a href=\"open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\" class=\"h2\">$get_main_category_translation_value</a>
			</p>
		</div>
		";
	} // categories
	echo"
	</div> <!-- //food_categories_row -->
	<div class=\"clear\"></div>
	



<!-- //All categories -->

<div id=\"nettport_search_results\">

<!-- Last comment -->
		";
		// Get rating
		$query = "SELECT rating_id, rating_food_id, rating_title, rating_text, rating_by_user_id, rating_by_user_name, rating_by_user_image_path, rating_by_user_image_file, rating_by_user_image_thumb_60, rating_by_user_ip, rating_stars, rating_created, rating_created_saying, rating_created_timestamp, rating_updated, rating_updated_saying, rating_likes, rating_dislikes, rating_number_of_replies, rating_read_blog_owner, rating_reported, rating_reported_by_user_id, rating_reported_reason, rating_reported_checked FROM $t_food_index_ratings WHERE rating_language=$l_mysql ORDER BY rating_id DESC LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_rating_id, $get_rating_food_id, $get_rating_title, $get_rating_text, $get_rating_by_user_id, $get_rating_by_user_name, $get_rating_by_user_image_path, $get_rating_by_user_image_file, $get_rating_by_user_image_thumb_60, $get_rating_by_user_ip, $get_rating_stars, $get_rating_created, $get_rating_created_saying, $get_rating_created_timestamp, $get_rating_updated, $get_rating_updated_saying, $get_rating_likes, $get_rating_dislikes, $get_rating_number_of_replies, $get_rating_read_blog_owner, $get_rating_reported, $get_rating_reported_by_user_id, $get_rating_reported_reason, $get_rating_reported_checked) = $row;
		if($get_rating_id != ""){

			// Find food
			$query = "SELECT food_id, food_name,  food_main_category_id,  food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large FROM $t_food_index WHERE food_id=$get_rating_food_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_food_id, $get_food_name, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large) = $row;
		

			echo"
	<div class=\"bodycell\">
			
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<!-- Food image -->
					<p>
					";
					if(file_exists("$root/$get_food_image_path/$get_food_thumb_a_large") && $get_food_thumb_a_large != ""){
						echo"<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$root/$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />\n";
					}
					echo"
					</p>
				<!-- //Food image -->
				
			  </td>
			  <td style=\"vertical-align: top;\">
				<p style=\"padding-bottom: 0;margin-bottom: 5px;\">
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"h2\">$get_rating_title</a>
				</p>


				<!-- Stars, title and menu -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td>
				<p style=\"margin:0;padding:0;\">
				";
				if($get_rating_stars == "1"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				elseif($get_rating_stars == "2"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				elseif($get_rating_stars == "3"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				elseif($get_rating_stars == "4"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				else{
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					";
				}
				echo"
				</p>
				  </td>
				  <td style=\"text-align: right;padding-left: 10px;\">
				<!-- Like, dislike, report spam + owner actions -->
					<p style=\"padding:0;margin:0;\">\n";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						$can_edit = 0;
						if($get_my_user_id == "$get_rating_by_user_id"){
							$can_edit = 1;
						}
						if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
							$can_edit = 1;
						}

						if($can_edit == "1"){
							echo"
							<a href=\"rating_edit.php?rating_id=$get_rating_id&amp;l=$l\" class=\"grey_small\">$l_edit</a> &middot;
							<a href=\"rating_delete.php?rating_id=$get_rating_id&amp;l=$l\" class=\"grey_small\">$l_delete</a> &middot;
							";
						}

						echo"<a href=\"rating_report.php?rating_id=$get_rating_id&amp;l=$l\" class=\"grey_small\">$l_report</a>";
					}
					else{
						echo"<a href=\"users/login.php?l=$l&amp;referer=food/rating_report.php?rating_id=$get_rating_id\" class=\"grey_small\">$l_report</a>\n";
					}
					echo"
					</p>
					<!-- //Like, dislike, reply, report spam + owner actions -->
					  </td>
					 </tr>
					</table>
				<!-- //Stars, title and menu -->


				<!-- Author + date -->
					<p style=\"margin:0;padding:0;\">
					<span class=\"small\">$l_by</span>
					<a href=\"$root/users/view_profile.php?user_id=$get_rating_by_user_id&amp;l=$l\" class=\"small\">$get_rating_by_user_name</a>
					&middot;
					<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l#rating$get_rating_id\" class=\"small\">$get_rating_created_saying</a></span>
					</p>
				<!-- //Author + date -->

				<!-- Rating -->
					<p style=\"margin-top: 0px;padding-top: 0;line-height: normal;\">$get_rating_text</p>
				<!-- Rating -->


			  </td>
			 </tr>
			</table>
	</div>
			";
		} // comment found
		echo"
<!-- //Last comment -->

<!-- User adaptet view and Language selector -->
	";
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
	<p style=\"padding:0;margin: 20px 0px 0px 0px;\">
	<b>$l_show_per:</b>
	<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=index&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=1&amp;process=1&amp;referer=index&amp;l=$l\""; } echo" /> $l_hundred
	<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=index&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=1&amp;process=1&amp;referer=index&amp;l=$l\""; } echo" /> $l_pcs_g
	<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=index&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=eight_us&amp;value=1&amp;process=1&amp;referer=index&amp;l=$l\""; } echo" /> $l_eight
	<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=index&amp;l=$l\""; } else{ echo" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=1&amp;process=1&amp;referer=index&amp;l=$l\""; } echo" /> $l_pcs_oz
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
	
<!-- //User adaptet view and Language selector -->
<!-- Last seen -->
	
	<h2 style=\"margin-top: 10px;\">$l_last_viewed</h2>


	<div class=\"clear\"></div>
	";
	
	// Set layout
	$nutritional_content_layout = "1";

	$x = 0;

	// Get all food
	$show_food 	= 1;
	$show_image_a	= 1;
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql ORDER BY food_last_viewed DESC LIMIT 0,12";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

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
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
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


<!-- New products -->
	
	<h2 style=\"margin-top: 10px;\">$l_new_products</h2>


	<div class=\"clear\"></div>
	";
	
	// Set layout
	$nutritional_content_layout = "1";

	$x = 0;

	// Get all food
	$show_food 	= 1;
	$show_image_a	= 1;
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql";
	$query = $query . " ORDER BY food_id DESC LIMIT 0,12";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

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
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
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

<!-- //New products -->

	<p>
	<a href=\"look_for_errors_in_food.php?l=$l\">$l_look_for_errors_in_food</a>
	</p>

</div> <!-- //Nettport search results -->
";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>