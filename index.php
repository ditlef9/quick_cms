<?php 
/**
*
* File: /index.php
* Version 1.0
* Date 18:10 16.10.2020
* Copyright (c) 2011-2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "13";
$pageNoColumnSav      = "1";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");




/*- Headers ---------------------------------------------------------------------------------- */
//$website_title = "$configWebsiteTitleSav";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


/*- Variables -------------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


/*- Tables ----------------------------------------------------------------------------------- */
include("blog/_tables_blog.php");
include("recipes/_tables.php");

/*- Translations ----------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Search + 6 icons ---------------------------------------------------------------------------------- */
echo"
<!-- Search -->

	<div class=\"search_search_div\">
		<form method=\"get\" action=\"$root/search/search.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"text\" name=\"inp_search_query\" id=\"inp_search_query\" class='auto' value=\"$l_search\" size=\"25\" />
		<input type=\"submit\" value=\"$l_search\" class=\"btn_default\" />
		</p>
		</form>
	</div>
	<div id=\"inp_search_results\"></div>

	<!-- Search engines Autocomplete -->
		<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {

				\$('#inp_search_query').click(function(){
					var searchString    = \$(\"#inp_search_query\").val();";
					$l_search_saying = str_replace("&oslash;", "ø", $l_search);
					echo"
					if(searchString == \"$l_search_saying\"){
						\$(\"#inp_search_query\").val(\"\");

					}

				});

				\$('#inp_search_query').keyup(function () {
					// getting the value that user typed
					var searchString    = \$(\"#inp_search_query\").val();
					// forming the queryString
      					var data            = 'l=$l&inp_search_query='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
						\$(\"#inp_search_results\").css('visibility','visible');
						// ajax call
        					\$.ajax({
        						type: \"GET\",
        						url: \"$root/search/search_autocomplete.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#inp_search_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#inp_search_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		});
		</script>
	<!-- //Search engines Autocomplete -->
<!-- //Search -->
";

/*- Grids --------------------------------------------------------------------------------- */
$t_grid_groups	= $mysqlPrefixSav . "grid_groups";
$t_grid_items	= $mysqlPrefixSav . "grid_items";
$l = output_html($l);
$l_mysql = quote_smart($link, $l);

$query_owner = "SELECT group_id FROM $t_grid_groups WHERE group_language=$l_mysql AND group_title_english='Frontpage' AND group_active=1";
$result_owner = mysqli_query($link, $query_owner);
$row_owner = mysqli_fetch_row($result_owner);
list($get_group_id) = $row_owner;
if($get_group_id != ""){
	echo"
	<div class=\"grid_wrapper\">
	";
	// Items
	$query = "SELECT item_id, item_title, item_url, item_icon_path, item_icon_18x18, item_icon_hover_18x18, item_icon_24x24, item_icon_hover_24x24, item_icon_36x36, item_icon_hover_36x36, item_icon_48x48, item_icon_hover_48x48 FROM $t_grid_items WHERE item_group_id=$get_group_id ORDER BY item_weight ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_item_id, $get_item_title, $get_item_url, $get_item_icon_path, $get_item_icon_18x18, $get_item_icon_hover_18x18, $get_item_icon_24x24, $get_item_icon_hover_24x24, $get_item_icon_36x36, $get_item_icon_hover_36x36, $get_item_icon_48x48, $get_item_icon_hover_48x48) = $row;
	

		echo"
		<div class=\"grid_item\">
			<a href=\"$get_item_url\"><img src=\"$get_item_icon_path/$get_item_icon_36x36\" alt=\"$get_item_icon_36x36\" class=\"grid_icon\" /> <span>$get_item_title<span></a>
		</div>
		";

	} // items
	echo"
	</div> <!-- //grid_wrapper -->
	<!-- Change image on hover -->
		<script>
		\$(\".grid_item\").hover(function(){
			\$(this).find(\"img\").attr(\"src\", function(index, attr){
				return attr.replace(\"_36x36.png\", \"_hover_36x36.png\");
			});
			}, function(){
				\$(this).find(\"img\").attr(\"src\", function(index, attr){
				return attr.replace(\"_hover_36x36.png\", \"_36x36.png\");
			});
		});
		</script>
	<!-- //Change image on hover -->

	
	";
} // group not empty

/*- Feed 1-3 ------------------------------------------------------------------------------ */
$x = 0;
$t_users_feeds_index		= $mysqlPrefixSav . "users_feeds_index";
$query = "SELECT feed_id, feed_title, feed_text, feed_image_path, feed_image_file, feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, feed_sub_category_id, feed_sub_category_name, feed_user_id, feed_user_email, feed_user_name, feed_user_alias, feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, feed_created_date_saying, feed_created_year, feed_created_time, feed_modified_datetime, feed_likes, feed_dislikes, feed_comments, feed_reported, feed_reported_checked, feed_reported_reason FROM $t_users_feeds_index WHERE feed_language=$l_mysql ORDER BY feed_id DESC LIMIT 0,4";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_feed_id, $get_feed_title, $get_feed_text, $get_feed_image_path, $get_feed_image_file, $get_feed_image_thumb_300x169, $get_feed_image_thumb_540x304, $get_feed_link_url, $get_feed_link_name, $get_feed_module_name, $get_feed_module_part_name, $get_feed_module_part_id, $get_feed_main_category_id, $get_feed_main_category_name, $get_feed_sub_category_id, $get_feed_sub_category_name, $get_feed_user_id, $get_feed_user_email, $get_feed_user_name, $get_feed_user_alias, $get_feed_user_photo_file, $get_feed_user_photo_thumb_40, $get_feed_user_photo_thumb_50, $get_feed_user_photo_thumb_60, $get_feed_user_photo_thumb_200, $get_feed_user_subscribe, $get_feed_user_ip, $get_feed_user_hostname, $get_feed_language, $get_feed_created_datetime, $get_feed_created_date_saying, $get_feed_created_year, $get_feed_created_time, $get_feed_modified_datetime, $get_feed_likes, $get_feed_dislikes, $get_feed_comments, $get_feed_reported, $get_feed_reported_checked, $get_feed_reported_reason) = $row;
	
	if($x == 0){
		if(file_exists("$root/$get_feed_image_path/$get_feed_image_file") && $get_feed_image_file != ""){
			echo"
			<div class=\"blog_frontpage_first_post\">
				<a href=\"$root/$get_feed_link_url\"><img src=\"$root/$get_feed_image_path/$get_feed_image_file\" alt=\"$get_feed_image_file\" class=\"recipe_of_the_day_img\" /></a>
			
				<div class=\"blog_frontpage_first_post_category_title_box\">
					<p>
					<a href=\"$root/$get_feed_link_url\" class=\"blog_frontpage_first_post_category\">$get_feed_user_name</a><br />
					<a href=\"$root/$get_feed_link_url\" class=\"blog_frontpage_first_post_title\">$get_feed_title</a>
					</p>
				</div>
			</div>
			";
		}
	} // x=0;
	elseif($x > 0 && $x < 4){

		if($x == 1){
			echo"
			<div class=\"clear\"></div>
			<div class=\"left_center_right_left\">
			";
		}
		elseif($x == 2){
			echo"
			<div class=\"left_center_right_center\">
			";
		}
		elseif($x == 3){
			echo"
			<div class=\"left_center_right_right\">
			";
		}
			
		echo"
			<p class=\"frontpage_post_image\">
		";
		if(file_exists("$root/$get_feed_image_path/$get_feed_image_file") && $get_feed_image_file != ""){
			if(!(file_exists("$root/$get_feed_image_path/$get_feed_image_thumb_540x304")) && $get_feed_image_thumb_540x304 != ""){
				// Create thumb
				resize_crop_image(540, 304, "$root/$get_feed_image_path/$get_feed_image_file", "$root/$get_feed_image_path/$get_feed_image_thumb_540x304");
			}

			if(file_exists("$root/$get_feed_image_path/$get_feed_image_thumb_540x304") && $get_feed_image_thumb_540x304 != ""){
				echo"
				<a href=\"$root/$get_feed_link_url\"><img src=\"$root/$get_feed_image_path/$get_feed_image_thumb_540x304\" alt=\"$get_feed_image_thumb_540x304\" /></a>
				";
			}
		}
		echo"
			</p>

			<p class=\"frontpage_post_category_p\">
			<a href=\"$root/users/view_profile.php?user_id=$get_feed_user_id&amp;l=$l\" class=\"frontpage_post_category_a\">$get_feed_user_name</a><br />
			</p>
			<p class=\"frontpage_post_title\">
			<a href=\"$root/$get_feed_link_url\" class=\"h2\">$get_feed_title</a><br />
			</p>

			</div>
		";
		if($x == "3"){
			$x= 0;
			echo"
			";
		}
	} // x > 1
	$x++;
}


/*- News ---------------------------------------------------------------------------------- */

echo"
<!-- 1 big blog entry, 3 smaller -->";

$blogActiveSav = 0;
if(file_exists("$root/_admin/_data/blog.php")){
	include("$root/_admin/_data/blog.php");
}
/*
if($blogActiveSav == "1"){
	$x = 0;
	$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_category_id, blog_post_category_title, blog_post_privacy_level, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_updated, blog_post_comments FROM $t_blog_posts WHERE blog_post_language=$l_mysql AND blog_post_privacy_level='everyone' AND blog_post_image_file != '' ORDER BY blog_post_id DESC LIMIT 0,5";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_blog_post_id, $get_blog_post_user_id, $get_blog_post_title, $get_blog_post_category_id, $get_blog_post_category_title, $get_blog_post_privacy_level, $get_blog_post_image_path, $get_blog_post_image_thumb_small, $get_blog_post_image_thumb_medium, $get_blog_post_image_thumb_large, $get_blog_post_image_file, $get_blog_post_updated, $get_blog_post_comments) = $row;



		// Owners user ID
		$query_owner = "SELECT user_id, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$get_blog_post_user_id";
		$result_owner = mysqli_query($link, $query_owner);
		$row_owner = mysqli_fetch_row($result_owner);
		list($get_user_id, $get_user_name, $get_user_alias) = $row_owner;
		
		// Date
		$year = substr($get_blog_post_updated, 0, 4);
		$month = substr($get_blog_post_updated, 5, 2);
		$day = substr($get_blog_post_updated, 8, 2);

		if($day < 10){
			$day = substr($day, 1, 1);
		}
		if($month == "01"){
			$month_saying = $l_january;
		}
		elseif($month == "02"){
			$month_saying = $l_february;
		}
		elseif($month == "03"){
			$month_saying = $l_march;
		}
		elseif($month == "04"){
			$month_saying = $l_april;
		}
		elseif($month == "05"){
			$month_saying = $l_may;
		}
		elseif($month == "06"){
			$month_saying = $l_june;
		}
		elseif($month == "07"){
			$month_saying = $l_july;
		}
		elseif($month == "08"){
			$month_saying = $l_august;
		}
		elseif($month == "09"){
			$month_saying = $l_september;
		}
		elseif($month == "10"){
			$month_saying = $l_october;
		}
		elseif($month == "11"){
			$month_saying = $l_november;
		}
		else{
			$month_saying = $l_december;
		}


		if($get_blog_post_image_file != "" && file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_file")){
			if($x == 0){
				echo"
				<div class=\"blog_frontpage_first_post\">
					<a href=\"$root/blog/view_post.php?post_id=$get_blog_post_id&amp;l=$l\"><img src=\"$root/$get_blog_post_image_path/$get_blog_post_image_file\" alt=\"$get_blog_post_image_file\" class=\"recipe_of_the_day_img\" /></a>


					<div class=\"blog_frontpage_first_post_category_title_box\">
						<p>
						<a href=\"$root/blog/view_category.php?category_id=$get_blog_post_category_id&amp;l=$l\" class=\"blog_frontpage_first_post_category\">$get_blog_post_category_title</a><br />
						<a href=\"$root/blog/view_post.php?post_id=$get_blog_post_id&amp;l=$l\" class=\"blog_frontpage_first_post_title\">$get_blog_post_title</a>
						</p>
					</div>
				</div>
				";
				
			} // x=0;
			elseif($x > 0 && $x < 4){

				if($x == 1){
					echo"
					<div class=\"clear\"></div>
					<div class=\"left_center_right_left\">
					";
				}
				elseif($x == 2){
					echo"
					<div class=\"left_center_right_center\">
					";
				}
				elseif($x == 3){
					echo"
					<div class=\"left_center_right_right\">
					";
				}
			
			
				echo"
				
							
							<p class=\"frontpage_post_image\">
								<a href=\"blog/view_post.php?post_id=$get_blog_post_id&amp;l=$l\"><img src=\"$root/$get_blog_post_image_path/$get_blog_post_image_thumb_medium\" alt=\"$get_blog_post_image_file\" /></a>
							</p>

							<p class=\"frontpage_post_category_p\">
							<a href=\"$root/blog/view_category.php?category_id=$get_blog_post_category_id&amp;l=$l\" class=\"frontpage_post_category_a\">$get_blog_post_category_title</a><br />
							</p>
							<p class=\"frontpage_post_title\">
							<a href=\"blog/view_post.php?post_id=$get_blog_post_id&amp;l=$l\" class=\"h2\">$get_blog_post_title</a><br />
							</p>

					
					</div>
				";
		
			} // xx
			$x++;
		} // has image
	} // while
} // blogActive
*/
echo"
<!-- //1 big blog entry -->


<!-- Four recipes for the season -->";
	// Check if installed
	$recipesActiveSav = 0;
	if(file_exists("$root/_admin/_data/recipes.php")){
		include("$root/_admin/_data/recipes.php");
	}
	if($recipesActiveSav == "1"){
		// Language
		include("$root/_admin/_translations/site/$l/recipes/ts_frontpage.php");

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
			$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_occasion_id=$get_occasion_id ORDER BY recipe_unique_hits DESC LIMIT 0,4";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156) = $row;

				if($get_recipe_image_h_a != ""){
					// Category
					$query_cat = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
					$result_cat = mysqli_query($link, $query_cat);
					$row_cat = mysqli_fetch_row($result_cat);
					list($get_category_translation_id, $get_category_translation_title) = $row_cat;
	

					$inp_new_x = 278; // 278 px × 154
					$inp_new_y = 184;
					$thumb = "recipe_" . $get_recipe_id . "_h_a_" . $inp_new_x . "x" . $inp_new_y . ".png";
		
					if(!(file_exists("$root/_cache/$thumb"))){
						// echo"Make thumb";
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/_cache/$thumb");
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
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/_cache/$thumb\" alt=\"$get_recipe_image_h_a\" /></a><br />
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
		";
	} // recipes installed
	echo"
<!-- //Four recipes for the season -->



<!-- Six next blog posts -->";
	if($blogActiveSav == "1"){
		// Language
		include("$root/_admin/_translations/site/$l/blog/ts_front_page.php");

		echo"
		<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/blog/index.php?l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_new_posts</a></p>
		<p class=\"frontpage_category_headline_rightside_p\"><a href=\"$root/blog/index.php?l=$l\" class=\"frontpage_category_headline_rightside_a\">$l_more</a></p>
		<div class=\"clear\"></div>

		<!-- 1: 1 big on left, 2 small on right -->
			";
			$limit = "0,3";
			include("blog/_index.php/a_1_big_on_left__2_small_on_right.php");
			echo"
		<!-- //1: 1 big on left, 2 small on right -->


		<!-- 2: 2 small on left, 1 big on right -->
		";
		$limit = "4,3";
		include("blog/_index.php/b_2_small_on_left__1_big_on_right.php");
		echo"
		<!-- //2: 2 small on left, 1 big on right -->

		";
	}
	echo"
<!-- //Six next blog posts -->

<!-- Four newest recipes -->";

	if($recipesActiveSav == "1"){
		echo"
		<div class=\"clear\"></div>
		<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/recipes/browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_new_recipes</a></p>
		<p class=\"frontpage_category_headline_rightside_p\"><a href=\"$root/recipes/browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l\" class=\"frontpage_category_headline_rightside_a\" title=\"$l_browse_new_recipes\">$l_more</a></p>
		<div class=\"clear\"></div>
		";
	

		// Get four new recipes
		$x = 0;
		$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql ORDER BY recipe_id DESC LIMIT 0,4";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156) = $row;

			if($get_recipe_image_h_a != ""){
				// Category
				$query_cat = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
				$result_cat = mysqli_query($link, $query_cat);
				$row_cat = mysqli_fetch_row($result_cat);
				list($get_category_translation_id, $get_category_translation_title) = $row_cat;
	
				$inp_new_x = 278; // 278 px × 154
				$inp_new_y = 184;
				$thumb = "recipe_" . $get_recipe_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
		
				if(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a") && !(file_exists("$root/_cache/$thumb"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/_cache/$thumb");
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
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/_cache/$thumb\" alt=\"$get_recipe_image_h_a\" /></a><br />
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
		echo"
		<div class=\"clear\"></div>
		";
	} // recipes active
	echo"
<!-- //Four Four newest recipes -->


<!-- More next blog posts -->
	";

	if($blogActiveSav == "1"){
		echo"
		<div class=\"clear\"></div>
		<p class=\"frontpage_category_headline_leftside_p\"><a href=\"$root/blog/index.php?l=$l\" class=\"frontpage_category_headline_leftside_a\">$l_trending_posts</a></p>
		<p class=\"frontpage_category_headline_rightside_p\"><a href=\"$root/blog/index.php?l=$l\" class=\"frontpage_category_headline_rightside_a\" title=\"$l_browse_posts\">$l_more</a></p>
		<div class=\"clear\"></div>

	

		<!-- 3: 3 small -->
			";
			$limit = "10,3";
			include("blog/_index.php/c_3_small.php");
			echo"
		<!-- //3: 3 small -->


		<!-- 4: 1 special on left, 2 small on right -->
			";
			// $limit = "13,3";
			// include("blog/_index.php/d_1_special_on_left__2_small_on_right.php");
			echo"
		<!-- //4: 1 special on left, 2 small on right -->


		<!-- 5: 2 small -->
			";
			$limit = "16,2";
			include("blog/_index.php/e_2_small.php");
			echo"
		<!-- //5: 2 small -->


		<!-- 6: 2 small -->
			";
			$limit = "18,2";
			include("blog/_index.php/e_2_small.php");
			echo"
		<!--// 6: 2 small -->

		<div style=\"height:20px;\"></div>

		<!-- 7: 1 big on left, 2 small on right -->
			";
			$limit = "20,3";
			include("blog/_index.php/a_1_big_on_left__2_small_on_right.php");
			echo"
		<!-- //7: 1 big on left, 2 small on right -->

		";
	} // blog active
	echo"
<!-- //More next blog posts -->
";

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>