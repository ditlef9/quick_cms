<?php 
/**
*
* File: recipes/seasons.php
* Version 1.0.0
* Date 13:43 18.11.2017
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
$l_mysql = quote_smart($link, $l);



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_seasons - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>$l_seasons</h1>

	
	";

	// Select seasons
	$x = 0;
	$query = "SELECT season_id, season_name, season_image FROM $t_recipes_seasons ORDER BY season_name ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_season_id, $get_season_name, $get_season_image) = $row;

		// Translations
		$query_t = "SELECT season_translation_id, season_translation_value FROM $t_recipes_seasons_translations WHERE season_id=$get_season_id AND season_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_season_translation_id, $get_season_translation_value) = $row_t;
		if($get_season_translation_id == ""){

			mysqli_query($link, "INSERT INTO $t_recipes_seasons_translations
			(season_translation_id, season_id, season_translation_language, season_translation_value) 
			VALUES 
			(NULL, '$get_season_id', $l_mysql, '$get_season_name')")
			or die(mysqli_error($link));

			echo"<div class=\"info\">Missing translation! Please refresh!</div>";
		}

		if($get_season_image != "" && file_exists("$root/$get_season_image")){


			// Get a recipe from that season
			$query_r = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_image_path, recipe_image FROM $t_recipes WHERE recipe_season_id=$get_season_id LIMIT 0,1";
			$result_r = mysqli_query($link, $query_r);
			$row_r = mysqli_fetch_row($result_r);
			list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image) = $row_r;


			// 4 divs

			// 847 / 4 = 211
			// 847 / 3 = 282

			// Thumb
			$inp_new_x = 190;
			$inp_new_y = 98;
			$thumb = "recipe_" . $get_recipe_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";

			if(!(file_exists("$root/_cache/$thumb"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image", "$root/_cache/$thumb");
			}




			if($x == 0){
				echo"
				<div class=\"clear\"></div>
				<div class=\"left_center_center_right_left\">
				";
			}
			elseif($x == 1){
				echo"
				<div class=\"left_center_center_left_right_center\">
				";
			}
			elseif($x == 2){
				echo"
				<div class=\"left_center_center_right_right_center\">
				";
			}
			elseif($x == 3){
				echo"
				<div class=\"left_center_center_right_right\">
				";
			}
		
			echo"
				<p class=\"recipe_open_category_img_p\">
				<a href=\"$root/recipes/seasons_browse.php?season_id=$get_season_id\"><img src=\"$root/_cache/$thumb\" alt=\"$get_season_translation_value\" width=\"$inp_new_x\" height=\"$inp_new_y\" /></a><br />
				</p>
				<p class=\"recipe_open_category_p\">
				<a href=\"$root/recipes/seasons_browse.php?season_id=$get_season_id\" class=\"recipe_open_category_a\">$get_season_translation_value</a>
				</p>
			</div>
			";
	
			// Increment
			$x++;
		
			// Reset
			if($x == 4){
				$x = 0;
			}
		} // image

	}

	if($x == 1){
		echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
		";
	
	}
	elseif($x == 2){
		echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
		";

	}
	elseif($x == 3){
		echo"
			<div class=\"left_center_center_right_right\">
			</div>
		";

	}

}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>