<?php 
/**
*
* File: recipes/index.php
* Version 1.0.0
* Date 10:37 29.12.2020
* Copyright (c) 2011-2020 Localhost
* Author Sindre Andre Ditlefsen
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

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/decode_national_letters.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Tables ------------------------------------------------------------------------ */
$t_recipes_tags_unique			= $mysqlPrefixSav . "recipes_tags_unique";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_frontpage.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_recipes";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

// Language
include("$root/_admin/_translations/site/$l/recipes/ts_search.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "recipe_id";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "desc";
}

// Dates
$day_of_week = date("N"); // 1 (for Monday) through 7 (for Sunday)

// Me
if(isset($_SESSION['user_id'])){
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
}
/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Headline, buttons, search -->
	<div class=\"recipes_headline\">
		<h1>$l_recipes</h1>
	</div>
	<div class=\"recipes_menu\">
		
		<!-- Recipes menu -->
			<script>
			\$(document).ready(function() {
				\$('#toggle_recipes_search').click(function() {
					\$(\".recipes_search\").fadeIn();
					\$(\"#inp_recipe_query\").focus();
				})
			});
			</script>


			<p>
			<a href=\"#\" id=\"toggle_recipes_search\" class=\"btn_default\"><img src=\"_gfx/icons/outline_search_black_18dp.png\" alt=\"outline_search_black_18dp.png\" /> $l_search</a>
			<a href=\"$root/food/index.php?l=$l\" class=\"btn_default\">$l_food</a>
			<a href=\"$root/recipes/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
			<a href=\"$root/recipes/my_recipes.php?l=$l\" class=\"btn_default\">$l_my_recipes</a>
			<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn_default\">$l_submit_recipe</a>
			</p>
		<!-- //Recipes menu -->
	</div>
	<div class=\"clear\"></div>
<!-- //Headline, buttons, search -->

<!-- Search -->
	<div class=\"recipes_search\">
		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
		<p>
		<b>$l_search_for_recipe:</b><br />
		
		<input type=\"text\" name=\"q\" value=\"\" size=\"15\" style=\"width: 50%;\" id=\"inp_recipe_query\" />
		<input type=\"submit\" value=\"$l_search\" class=\"btn_default\" />
		</p>
		</form>


		<!-- Search for recipe Autocomplete -->
			<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#inp_recipe_query').keyup(function () {
					// getting the value that user typed
       					var searchString    = \$(\"#inp_recipe_query\").val();

 					// forming the queryString
      					var data            = 'l=$l&q='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
						\$(\"#inp_recipe_query_results\").css('visibility','visible');

           					// ajax call
            					\$.ajax({
                					type: \"GET\",
               						url: \"search_for_recipe_autocomplete.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#inp_recipe_query_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#inp_recipe_query_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		   });
			</script>
			<div id=\"inp_recipe_query_results\"></div>
			<div class=\"clear\"></div>
		<!-- //Search for recipe Autocomplete -->
		
	</div>
<!-- //Search -->

<!-- Welcome text -->";
	if(file_exists("$root/_uploads/recipes/welcome_text/welcome_$l.txt")){
		echo"
		<div class=\"bodycell\">
		";
		include("$root/_uploads/recipes/welcome_text/welcome_$l.txt");
		echo"
		</div>
		";
	}
	echo"
	<p><a href=\"subscribe_to_weekly_recipes_suggestions.php?l=$l\" class=\"btn_default\"><img src=\"_gfx/icons/email_outline_black_18x18.png\" alt=\"email_outline_black_18x18.png\" /> $l_subscribe_to_weekly_recipes_suggestions</a></p>
<!-- //Welcome text -->

<!-- Tag cloud -->
	<p>";

	$query = "SELECT tag_id, tag_language, tag_title, tag_title_clean, tag_number_of_recipes, tag_last_clicked_year, tag_last_clicked_month, tag_last_clicked_week, tag_unique_views_counter FROM $t_recipes_tags_unique WHERE tag_language=$l_mysql ORDER BY tag_unique_views_counter DESC LIMIT 0,20";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_tag_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_number_of_recipes, $get_tag_last_clicked_year, $get_tag_last_clicked_month, $get_tag_last_clicked_week, $get_tag_unique_views_counter) = $row;

		echo"
		<a href=\"view_tag.php?tag=$get_tag_title_clean&amp;l=$get_tag_language\" class=\"btn_default\">$get_tag_title</a>
		";
	}
	echo"
	</p>
<!-- //Tag cloud -->

<!-- Last comment on recipe -->
	";


	$query = "SELECT comment_id, comment_recipe_id, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_recipes_comments WHERE comment_language=$l_mysql ORDER BY comment_id DESC LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_comment_id, $get_comment_recipe_id, $get_comment_language, $get_comment_approved, $get_comment_datetime, $get_comment_time, $get_comment_date_print, $get_comment_user_id, $get_comment_user_alias, $get_comment_user_image_path, $get_comment_user_image_file, $get_comment_user_ip, $get_comment_user_hostname, $get_comment_user_agent, $get_comment_title, $get_comment_text, $get_comment_rating, $get_comment_helpful_clicks, $get_comment_useless_clicks, $get_comment_marked_as_spam, $get_comment_spam_checked, $get_comment_spam_checked_comment) = $row;

	if($get_comment_id != ""){

		$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156 FROM $t_recipes WHERE recipe_id=$get_comment_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156) = $row;
		echo"

		<div class=\"subcell\">
			
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<!-- Recipe image -->
					<p>
					";
					if(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156") && $get_recipe_thumb_h_a_278x156 != ""){
						echo"<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_thumb_h_a_278x156\" style=\"margin-bottom: 5px;\" /></a><br />\n";
					}
					echo"
					</p>
				<!-- //Recipe image -->
				
			  </td>
			  <td style=\"vertical-align: top;\">
				<p style=\"padding-bottom: 0;margin-bottom: 5px;\">
				<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"h2\">$get_comment_title</a>
				</p>


				<!-- Stars, title and menu -->
					<table>
					 <tr>
					  <td>
						<p style=\"margin:0;padding:0;\">
						";
						for($x=0;$x<$get_comment_rating;$x++){
							echo"<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" /> ";
						}
						$off = 5-$get_comment_rating;
						for($x=0;$x<$off;$x++){
							echo"<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" /> ";
						}
						echo"
						</p>
					  </td>
					  <td style=\"text-align: right;padding-left: 10px;\">
						<!-- Menu -->
							";
							if(isset($my_user_id)){
								if($get_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
									echo"
									<a href=\"edit_comment.php?comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
									<a href=\"delete_comment.php?comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									";
								}
								else{
									echo"
									<a href=\"report_comment.php?comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/flag_black_18dp.png\" alt=\"flag_black_18dp.png\" title=\"$l_report\" /></a>
									";
								}
							}
							echo"
						<!-- //Menu -->
					  </td>
					 </tr>
					</table>
				<!-- //Stars, title and menu -->


				<!-- Author + date -->
					<p style=\"margin:0;padding:0;\" class=\"small\">
					$l_by
					<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\" class=\"small\">$get_comment_user_alias</a>
					&middot;
					<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l#comment$get_comment_id\" class=\"small\">$get_comment_date_print</a>
					</p>
				<!-- //Author + date -->

				<!-- Rating -->
					<p style=\"margin-top: 5px;padding-top: 0;line-height: normal;\">$get_comment_text</p>
				<!-- Rating -->


			  </td>
			 </tr>
			</table>
		</div> <!-- //subcell -->
		";
	} // Comment found
	echo"
<!-- //Last comment on recipe -->

<!-- Categories -->
	<p style=\"padding-bottom:0;margin-bottom:0;\"><a href=\"$root/recipes/categories.php?l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_categories</a></p>
	
	<div class=\"recipes_categories_row\">
	";
	// Select categories
	$month = date("m");
	$query = "SELECT category_id, category_name, category_image_path, category_image_file, category_image_updated_month, category_icon_file FROM $t_recipes_categories ORDER BY category_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_category_id, $get_category_name, $get_category_image_path, $get_category_image_file, $get_category_image_updated_month, $get_category_icon_file) = $row;

		// Translations
		$query_t = "SELECT category_translation_id, category_translation_title, category_translation_image_path, category_translation_image FROM $t_recipes_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_category_translation_id, $get_category_translation_title, $get_category_translation_image_path, $get_category_translation_image) = $row_t;
		if($get_category_translation_id == ""){

			mysqli_query($link, "INSERT INTO $t_recipes_categories_translations
			(category_translation_id, category_id, category_translation_language, category_translation_title) 
			VALUES 
			(NULL, '$get_category_id', $l_mysql, '$get_category_name')")
			or die(mysqli_error($link));

			echo"<div class=\"info\">Missing translation! Please refresh!</div>";
		}


		// Check category image
		if($month != "$get_category_image_updated_month" OR $get_category_image_file == "" OR !(file_exists("$root/$get_category_image_path/$get_category_image_file"))){
			// Find random recipe
			$query_r = "SELECT recipe_id, recipe_title, recipe_image_path, recipe_image_h_a FROM $t_recipes WHERE recipe_category_id=$get_category_id ORDER BY RAND() LIMIT 1;";
			$result_r = mysqli_query($link, $query_r);
			$row_r = mysqli_fetch_row($result_r);
			list($get_recipe_id, $get_recipe_title, $get_recipe_image_path, $get_recipe_image_h_a) = $row_r;

			if(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a") && $get_recipe_image_h_a != ""){
				if(file_exists("$root/$get_category_image_path/$get_category_image_file") && $get_category_image_file != ""){
					unlink("$root/$get_category_image_path/$get_category_image_file");
				}

				// Make new category image
				$inp_new_x = 220;
				$inp_new_y = 220;

				$ext = get_extension($get_recipe_image_h_a);
				
				$inp_category_image_file = $get_category_id . "_image_" . $inp_new_x . "x" . $inp_new_y . ".$ext";
				$inp_category_image_file_mysql = quote_smart($link, $inp_category_image_file);

				$inp_category_image_path = "_uploads/recipes/categories";
				if(!(is_dir("$root/$inp_category_image_path"))){
					mkdir("$root/$inp_category_image_path");
				}

				resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$inp_category_image_path/$inp_category_image_file");
				mysqli_query($link, "UPDATE $t_recipes_categories SET category_image_path='$inp_category_image_path', category_image_file=$inp_category_image_file_mysql, category_image_updated_month=$month WHERE category_id=$get_category_id") or die(mysqli_error($link));


				// echo"	<div class=\"info\"><p>New month - new image! This months recipe is $get_recipe_title. Recipe image is <a href=\"$root/$get_recipe_image_path/$get_recipe_image_h_a\">$get_recipe_image_h_a</a>.</p></div>";
			}
		} // new image for category
		
		
		echo"
		<div class=\"recipes_categories_column\">
			<p>
			<a href=\"$root/recipes/categories_browse.php?category_id=$get_category_id&amp;l=$l\"><img src=\"$root/$get_category_image_path/$get_category_image_file\" alt=\"$get_category_translation_image\" /></a><br />
			<a href=\"$root/recipes/categories_browse.php?category_id=$get_category_id&amp;l=$l\" class=\"h2\">$get_category_translation_title</a>
			</p>
		</div>
		";

	}
	echo"
	</div> <!-- //recipes_categories_wrapper -->
<!-- //Categories -->



<!-- Four recipes for the season -->";
	// What is the season?
	$month = date("m");
	$day = date("d");
	// Occation
	$query_owner = "SELECT occasion_id, occasion_name, occasion_day, occasion_month, occasion_image, occasion_last_updated FROM $t_recipes_occasions WHERE occasion_day>$day AND occasion_month=$month LIMIT 0,1";
	$result_owner = mysqli_query($link, $query_owner);
	$row_owner = mysqli_fetch_row($result_owner);
	list($get_occasion_id, $get_occasion_name, $get_occasion_day, $get_occasion_month, $get_occasion_image, $get_occasion_last_updated) = $row_owner;
	
	if($get_occasion_id != ""){
		// Translation
		$query_owner = "SELECT occasion_translation_id, occasion_translation_value FROM $t_recipes_occasions_translations WHERE occasion_id=$get_occasion_id AND occasion_translation_language=$l_mysql";
		$result_owner = mysqli_query($link, $query_owner);
		$row_owner = mysqli_fetch_row($result_owner);
		list($get_occasion_translation_id, $get_occasion_translation_value) = $row_owner;
	

		echo"
		<div class=\"clear\"></div>
		<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/recipes/occasions_browse.php?occasion_id=$get_occasion_id&amp;l=$l\" class=\"frontpage_category_headline_leftside_a\">$get_occasion_translation_value</a></p>
		<p class=\"frontpage_category_headline_rightside_p\"><a href=\"$root/recipes/occasions_browse.php?occasion_id=$get_occasion_id&amp;l=$l\" class=\"frontpage_category_headline_rightside_a\">$l_more_recipes</a></p>
		<div class=\"clear\"></div>
		";
	

		// Get four recipes
		$x = 0;
		$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_occasion_id=$get_occasion_id AND recipe_published=1 ORDER BY recipe_unique_hits DESC LIMIT 0,4";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156) = $row;

			if($get_recipe_image_h_a != ""){
				// Category
				$query_cat = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
				$result_cat = mysqli_query($link, $query_cat);
				$row_cat = mysqli_fetch_row($result_cat);
				list($get_category_translation_id, $get_category_translation_title) = $row_cat;
	
				// Thumb
				if($get_recipe_thumb_h_a_278x156 == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
					$inp_new_x = 278; // 278x156
					$inp_new_y = 156;

					$ext = get_extension($get_recipe_image_h_a);

					echo"<div class=\"info\"><p>Creating recipe thumb $inp_new_x x $inp_new_y px of <a href=\"$root/$get_recipe_image_path/$get_recipe_image_h_a\">$root/$get_recipe_image_path/$get_recipe_image_h_a</a></p></div>";

					$thumb = $get_recipe_id . "_thumb_" . $inp_new_x . "x" . $inp_new_y . ".$ext";
					$thumb_mysql = quote_smart($link, $thumb);
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$thumb");
					mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb_h_a_278x156=$thumb_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));
				}

				if($x == "0"){
					echo"
					<div class=\"left_center_center_right_left\">
					";
				}
				elseif($x == "1"){
					echo"
					<div class=\"left_center_center_left_right_center\">
					";
				}
				elseif($x == "2"){
					echo"
					<div class=\"left_center_center_right_right_center\">
					";
				}
				elseif($x == "3"){
					echo"
					<div class=\"left_center_center_right_right\">
					";
				}
				echo"
						<p class=\"frontpage_post_image\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" /></a><br />
						</p>

						<p class=\"frontpage_post_category_p\">
							<a href=\"$root/recipes/categories_browse.php?category_id=$get_recipe_category_id&amp;l=$l\" class=\"frontpage_post_category_a\">$get_category_translation_title</a><br />
						</p>
						<p class=\"frontpage_post_title\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"h2\">$get_recipe_title</a>
						</p>
					
					</div>
				";
			
				// Increment
				$x = $x+1;

			} // image
		}
 	} // occation found
	echo"
		<div class=\"clear\"></div>
<!-- //Four recipes for the season -->




<!-- Popular recipes -->";
	echo"
	<div class=\"clear\"></div>
	<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/recipes/browse_recipes_views.php?period=30_days&amp;l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_popular_recipes</a></p>
	<p class=\"frontpage_category_headline_rightside_p\"><a href=\"$root/recipes/browse_recipes_views.php?period=30_days&amp;l=$l\" class=\"frontpage_category_headline_rightside_a\" title=\"$l_more_new_recipes\">$l_more</a></p>
	<div class=\"clear\"></div>
	";
	

	// Get eight recipes
	$x = 0;
	$year = date("Y");
	$query = "SELECT stats_visit_per_month_id, stats_visit_per_month_recipe_id, stats_visit_per_month_recipe_title, stats_visit_per_month_recipe_image_path, stats_visit_per_month_recipe_thumb_278x156, stats_visit_per_month_recipe_category_id, stats_visit_per_month_recipe_category_translated FROM $t_recipes_stats_views_per_month WHERE stats_visit_per_month_month=$month AND stats_visit_per_month_year=$year AND stats_visit_per_month_recipe_language=$l_mysql ORDER BY stats_visit_per_month_count DESC LIMIT 0,8";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_stats_visit_per_month_id, $get_stats_visit_per_month_recipe_id, $get_stats_visit_per_month_recipe_title, $get_stats_visit_per_month_recipe_image_path, $get_stats_visit_per_month_recipe_thumb_278x156, $get_stats_visit_per_month_recipe_category_id, $get_stats_visit_per_month_recipe_category_translated) = $row;

		if($get_stats_visit_per_month_recipe_thumb_278x156 != "" && file_exists("$root/$get_stats_visit_per_month_recipe_image_path/$get_stats_visit_per_month_recipe_thumb_278x156")){
			
	
			if($x == "0"){
				echo"
				<div class=\"left_center_center_right_left\">
				";
			}
			elseif($x == "1"){
				echo"
				<div class=\"left_center_center_left_right_center\">
				";
			}
			elseif($x == "2"){
				echo"
				<div class=\"left_center_center_right_right_center\">
				";
			}
			elseif($x == "3"){
				echo"
				<div class=\"left_center_center_right_right\">
				";
			}
			echo"
					<p class=\"frontpage_post_image\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_stats_visit_per_month_recipe_id&amp;l=$l\"><img src=\"$root/$get_stats_visit_per_month_recipe_image_path/$get_stats_visit_per_month_recipe_thumb_278x156\" alt=\"$get_stats_visit_per_month_recipe_thumb_278x156\" /></a><br />
						</p>

						<p class=\"frontpage_post_category_p\">
							<a href=\"$root/recipes/categories_browse.php?category_id=$get_stats_visit_per_month_recipe_category_id&amp;l=$l\" class=\"frontpage_post_category_a\">$get_stats_visit_per_month_recipe_category_translated</a><br />
						</p>
						<p class=\"frontpage_post_title\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_stats_visit_per_month_recipe_id&amp;l=$l\" class=\"h2\">$get_stats_visit_per_month_recipe_title</a>
						</p>
					
					</div>
			";
			
			// Increment
			if($x == "3"){ 
				$x = -1; 
				echo"
				<div class=\"clear\"></div>
				";
			} 
			$x = $x+1;
		} // image
	}
	echo"
<!-- //Popular recipes -->




<!-- Newest recipes -->";
	echo"

	
	<div class=\"clear\"></div>
	<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/recipes/browse_recipes_newest.php?l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_fresh_recipes_headline</a></p>
	<p class=\"frontpage_category_headline_rightside_p\"><a href=\"$root/recipes/browse_recipes_newest.php?l=$l\" class=\"frontpage_category_headline_rightside_a\" title=\"$l_more_new_recipes\">$l_more</a></p>
	<div class=\"clear\"></div>

	<!-- Selected -->
		<script>
		\$(function(){
			\$('.on_select_go_to_url').on('change', function () {
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
			<select name=\"inp_selected\" class=\"on_select_go_to_url\">
				<option value=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l\" selected=\"selected\">- $l_show -</option>
				<option value=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l\">$l_newest</option>
				<option value=\"browse_recipes_views.php?order_by=recipe_title&amp;order_method=asc&amp;period=30_days&amp;l=$l\">$l_views</option>
				<option value=\"browse_recipes_comments.php?order_by=recipe_title&amp;order_method=asc&amp;period=30_days&amp;l=$l\">$l_comments</option>
				<option value=\"browse_recipes_rating.php?order_by=recipe_title&amp;order_method=asc&amp;l=$l\">$l_rating</option>
			</select>
			<select name=\"inp_period\" class=\"on_select_go_to_url\">
				<option value=\"browse_recipes.php?order_by=recipe_id&amp;order_method=$order_method&amp;l=$l\">- $l_period -</option>
				<option value=\"browse_recipes_views.php?l=$l\">$l_all_time</option>
				<option value=\"browse_recipes_views.php?period=30_days&amp;l=$l\">$l_thirty_days</option>";
				$query = "SELECT DISTINCT stats_visit_per_year_year FROM $t_recipes_stats_views_per_year WHERE stats_visit_per_year_recipe_language=$l_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_stats_visit_per_year_year) = $row;
					echo"					";
					echo"<option value=\"browse_recipes_views.php?period=year&amp;year=$get_stats_visit_per_year_year&amp;l=$l\">$l_year $get_stats_visit_per_year_year</option>\n";
				}
				echo"
			</select>
		</form>
		
	<!-- //Selected -->


	";
	

	// Get 40 new recipes :: Paging
	$no_of_records_per_page = 40;
	$total_pages_sql = "SELECT COUNT(*) FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_published=1";
	$result = mysqli_query($link, $total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);


	// Get 40 new recipes :: While
	$x = 0;
	$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_published=1 ORDER BY recipe_id DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156) = $row;

			if($get_recipe_image_h_a != ""){
				// Category
				$query_cat = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
				$result_cat = mysqli_query($link, $query_cat);
				$row_cat = mysqli_fetch_row($result_cat);
				list($get_category_translation_id, $get_category_translation_title) = $row_cat;
	

				// Thumb
				if($get_recipe_thumb_h_a_278x156 == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
					if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a"))){
						echo"<div class=\"error\"><p>Recipe image <em>$root/$get_recipe_image_path/$get_recipe_image_h_a</em> doesnt exists.</p></div>\n";
						
						mysqli_query($link, "UPDATE $t_recipes SET recipe_image_h_a='' WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));
						
					}
					else{
						$inp_new_x = 278; // 278x156
						$inp_new_y = 156;

						$ext = get_extension($get_recipe_image_h_a);

						echo"<div class=\"info\"><p>Creating recipe thumb $inp_new_x x $inp_new_y  px <a href=\"$root/$get_recipe_image_path/$get_recipe_image_h_a\">$root/$get_recipe_image_path/$get_recipe_image_h_a</a></p></div>";

						$thumb = $get_recipe_id . "_thumb_" . $inp_new_x . "x" . $inp_new_y . ".$ext";
						$thumb_mysql = quote_smart($link, $thumb);
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$thumb");
						mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb_h_a_278x156=$thumb_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));
					}
				}
		

				if($x == "0"){
					echo"
					<div class=\"left_center_center_right_left\">
					";
				}
				elseif($x == "1"){
					echo"
					<div class=\"left_center_center_left_right_center\">
					";
				}
				elseif($x == "2"){
					echo"
					<div class=\"left_center_center_right_right_center\">
					";
				}
				elseif($x == "3"){
					echo"
					<div class=\"left_center_center_right_right\">
					";
				}
				echo"
						<p class=\"frontpage_post_image\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" /></a><br />
						</p>

						<p class=\"frontpage_post_category_p\">
							<a href=\"$root/recipes/categories_browse.php?category_id=$get_recipe_category_id&amp;l=$l\" class=\"frontpage_post_category_a\">$get_category_translation_title</a><br />
						</p>
						<p class=\"frontpage_post_title\">
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"h2\">$get_recipe_title</a>
						</p>
					
					</div>
				";
			
				// Increment
				if($x == "3"){
					echo"
					<div class=\"clear\"></div>
					";
					$x = -1;
				}
				$x = $x+1;

		} // image
	}

	if($x == "1" OR $x == "2"){
		echo"
					<div class=\"left_center_center_right_right_center\">
					</div>

					<div class=\"left_center_center_right_right_center\">
					</div>

					<div class=\"left_center_center_right_right\">
					</div>
					<div class=\"clear\"></div>

		";
	}
	elseif($x == "3"){
		echo"
					<div class=\"left_center_center_right_right\">
					</div>
					<div class=\"clear\"></div>
		";
	}


	echo"
	<!-- Newst recipe paging -->
	
		<div class=\"recipes_pagination\">
			<a href=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l&amp;page=1\" class=\"active first\">1</a>
			";

			// We want to show the first 3 pages, then ...
			if($total_pages > 4){
				for($x=2;$x<4+1;$x++){
					echo"			<a href=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l&amp;page=$x\">$x</a>\n";
				}
				echo"			<a href=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l&amp;page=5\">...</a>\n";
			}

			// Last 4 pages
			if($total_pages > 10){
				$last = $total_pages+1;
				for($x=$total_pages-4;$x<$last;$x++){
					echo"			<a href=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l&amp;page=$x\""; if($x == "$last"){ echo" class=\"last\""; } echo">$x</a>\n";
				}
			}
			echo"

		</div>
	<!-- //Newst recipe paging -->



<!-- //Newest recipes -->



<!-- Chef of the month -->";
	$month = date("m");
	$year = date("Y");
	echo"
	<div class=\"clear\"></div>
	<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/recipes/chef_of_the_month_view_month.php?month=$month&amp;year=$year&amp;l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_chef_of_the_month</a></p>
	<div class=\"clear\"></div>
	";
	

	// Get chef of the month
	$x = 0;
	$query = "SELECT stats_chef_of_the_month_id, stats_chef_of_the_month_user_id, stats_chef_of_the_month_user_name, stats_chef_of_the_month_user_photo_path, stats_chef_of_the_month_user_photo_thumb, stats_chef_of_the_month_recipes_posted_count, stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points FROM $t_recipes_stats_chef_of_the_month WHERE stats_chef_of_the_month_month=$month AND stats_chef_of_the_month_year=$year ORDER BY stats_chef_of_the_month_total_points DESC LIMIT 0,8";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_stats_chef_of_the_month_id, $get_stats_chef_of_the_month_user_id, $get_stats_chef_of_the_month_user_name, $get_stats_chef_of_the_month_user_photo_path, $get_stats_chef_of_the_month_user_photo_thumb, $get_stats_chef_of_the_month_recipes_posted_count, $get_stats_chef_of_the_month_recipes_posted_points, $get_stats_chef_of_the_month_got_visits_count, $get_stats_chef_of_the_month_got_visits_points, $get_stats_chef_of_the_month_got_favorites_count, $get_stats_chef_of_the_month_got_favorites_points, $get_stats_chef_of_the_month_got_comments_count, $get_stats_chef_of_the_month_got_comments_points, $get_stats_chef_of_the_month_total_points) = $row;

		if($x == "0"){
			echo"
			<div class=\"left_center_center_right_left\">
			";
		}
		elseif($x == "1"){
			echo"
			<div class=\"left_center_center_left_right_center\">
			";
		}
		elseif($x == "2"){
			echo"
			<div class=\"left_center_center_right_right_center\">
			";
		}
		elseif($x == "3"){
			echo"
			<div class=\"left_center_center_right_right\">
			";
		}
		echo"
			<p class=\"frontpage_post_image\">
				<a href=\"$root/users/view_profile.php?user_id=$get_stats_chef_of_the_month_user_id&amp;l=$l\">";
				if($get_stats_chef_of_the_month_user_photo_path != "" && $get_stats_chef_of_the_month_user_photo_thumb != "" && file_exists("$root/$get_stats_chef_of_the_month_user_photo_path/$get_stats_chef_of_the_month_user_photo_thumb")){
					echo"<img src=\"$root/$get_stats_chef_of_the_month_user_photo_path/$get_stats_chef_of_the_month_user_photo_thumb\" alt=\"$get_stats_chef_of_the_month_user_photo_thumb\" />";
				}
				else{
					echo"<img src=\"_gfx/avatar_blank_200.jpg\" alt=\"avatar_blank_200.jpg\" />";
				}
				echo"</a><br />
			</p>

			<p class=\"frontpage_post_title\">
				<a href=\"$root/users/view_profile.php?user_id=$get_stats_chef_of_the_month_user_id&amp;l=$l\" class=\"h2\">$get_stats_chef_of_the_month_user_name</a>
			</p>
					
			</div>
		";
			
		// Increment
		if($x == "3"){ $x = -1; } 
		$x = $x+1;
	
	}
	echo"
	<div class=\"clear\"></div>
<!-- //Chef of the month -->

";

// Weekly subscriptions
if($day_of_week == "6"){
	include("subscribe_to_weekly_recipes_suggestions_send_weekly.php");
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>