<?php 
/**
*
* File: recipes/my_recipes.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
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


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");

/*- Variables ------------------------------------------------------------------------- */


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_recipes - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/recipes/my_recipes.php\">
	";
}

if($action == ""){

	echo"
	<!-- Headline, buttons, search -->
	<div class=\"recipes_headline\">
		<h1>$l_my_recipes</h1>

		<!-- You are here -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_recipes</a>
		&gt;
		<a href=\"my_recipes.php?l=$l\">$l_my_recipes</a>
		</p>
		<!-- //You are here -->
	</div>
	<div class=\"recipes_menu\">
		<!-- Recipes menu -->
			<form method=\"get\" enctype=\"multipart/form-data\">
			<script>
			\$(document).ready(function() {
				\$('#toggle_recipes_search').click(function() {
					\$(\".recipes_search\").fadeIn();
					\$(\"#inp_recipe_query\").focus();
				})
			});
			</script>
			<script>
			\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
			});
			</script>


			<p>
			<a href=\"$root/food/index.php?l=$l\" class=\"btn_default\">$l_food</a>
			<a href=\"$root/recipes/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
			<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn_default\">$l_submit_recipe</a>
	
			<select id=\"inp_l\">
				<option value=\"my_recipes.php?l=$l\">$l_language</option>
				<option value=\"my_recipes.php?l=$l\">-</option>\n";


				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
				
					echo"	<option value=\"my_recipes.php?l=$get_language_active_iso_two\"";if($get_language_active_iso_two == "$l"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
			echo"
			</select>
			</p>
			</form>
		<!-- //Recipes menu -->


	</div>
	<div class=\"clear\"></div>
<!-- //Headline, buttons, search -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "recipe_deleted"){
			$fm = "$l_recipe_deleted";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->




	<!-- List all recipes -->

	";

	// Select recipes
	$x = 0;
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	
	$l = output_html($l);
	$l_mysql = quote_smart($link, $l);

	$query = "SELECT recipe_id, recipe_title, recipe_language, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156, recipe_date, recipe_date_saying, recipe_unique_hits, recipe_comments, recipe_times_favorited FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_user_id=$my_user_id_mysql ORDER BY recipe_id DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id, $get_recipe_title, $get_recipe_language, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_date, $get_recipe_date_saying, $get_recipe_unique_hits, $get_recipe_comments, $get_recipe_times_favorited) = $row;


		// Title
		$check = strlen($get_recipe_title);
		if($check > 33){
			$get_recipe_title = substr($get_recipe_title, 0, 30);
			$get_recipe_title = $get_recipe_title . "...";
		}

		// Intro
		$check = strlen($get_recipe_introduction);
		if($check > 33){
			$get_recipe_introduction = substr($get_recipe_introduction, 0, 30);
			$get_recipe_introduction = $get_recipe_introduction . "...";
		}



		// Rating
		$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_votes_plus_average, rating_ip_block FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
		$result_rating = mysqli_query($link, $query_rating);
		$row_rating = mysqli_fetch_row($result_rating);
		list($get_rating_id, $get_rating_recipe_id, $get_rating_1, $get_rating_2, $get_rating_3, $get_rating_4, $get_rating_5, $get_rating_total_votes, $get_rating_average, $get_rating_votes_plus_average, $get_rating_ip_block) = $row_rating;
		if($get_rating_average == ""){
			$get_rating_average = 0;
		}


		echo"
		<div class=\"recipes_item\">
			<table>
			 <tr>
			  <td";
			if(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a")){
				if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
					// Make thumb
					$inp_new_x = 278; // 278x156
					$inp_new_y = 156;

					$ext = get_extension($get_recipe_image_h_a);

					
					$thumb = $get_recipe_id . "_h_a_thumb_" . $inp_new_x . "x" . $inp_new_y . ".$ext";
					$thumb_mysql = quote_smart($link, $thumb);
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$thumb");
					mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb_h_a_278x156=$thumb_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));



				}
				echo" style=\"vertical-align: top;padding-right: 10px;\">
				<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" class=\"recipes_img\" /></a>
				";
			}
			else{
				echo">";
			}
			echo"
			  </td>
			  <td style=\"vertical-align: top;\">
			
				<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"h2\">$get_recipe_title</a>

				<ul class=\"recipe_stats\">
					<li>
					<a href=\"view_recipe_stats.php?recipe_id=$get_recipe_id&amp;l=$l#visits\"><img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye_dark_grey.png\" />
					$get_recipe_unique_hits</a>
					</li>

					<li>
					<a href=\"view_recipe_stats.php?recipe_id=$get_recipe_id&amp;l=$l#favorited\"><img src=\"_gfx/icons/outline_favorite_border_black_18dp.png\" alt=\"outline_favorite_black_18dp.png\" />
					$get_recipe_times_favorited</a>
					</li>

					<li>
					<a href=\"view_recipe_stats.php?recipe_id=$get_recipe_id&amp;l=$l#comments\"><img src=\"_gfx/icons/outline_comment_black_18dp.png\" alt=\"outline_favorite_black_18dp.png\" />
					$get_recipe_comments</a>
					</li>
				</ul>

				<p>
				<a href=\"edit_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn_default\">$l_edit</a>
				<a href=\"delete_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn_default\">$l_delete</a>
				</p>

			
			  </td>
			 </tr>
			</table>
		</div>
		";


	}

		echo"
	";

}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>