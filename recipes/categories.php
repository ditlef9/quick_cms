<?php 
/**
*
* File: recipes/categories.php
* Version 1.0.0
* Date 19:00 04.01.2021
* Copyright (c) 2020 Localhost
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


/*- Check that dir exists -------------------------------------------------------------------- */
if(!(is_dir("$root/_uploads/recipes/categories"))){
	mkdir("$root/_uploads/recipes/categories");
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_categories - $l_recipes";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>$l_categories</h1>

	
	<div class=\"clear\"></div>

	<!-- You are here -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_recipes</a>
		&gt;
		<a href=\"categories.php?l=$l\">$l_categories</a>
		</p>
	<!-- //You are here -->


	<div class=\"recipes_categories_row\">
	";
	// Select categories
	$x = 0;
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
				
				$inp_category_image_file = $get_category_id . "_image_" . $inp_new_x . "x" . $inp_new_y . ".png";
				$inp_category_image_file_mysql = quote_smart($link, $inp_category_image_file);

				$inp_category_image_path = "_uploads/recipes/categories";


				resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$inp_category_image_path/$inp_category_image_file");
				mysqli_query($link, "UPDATE $t_recipes_categories SET category_image_path='$inp_category_image_path', category_image_file=$inp_category_image_file_mysql, category_image_updated_month=$month WHERE category_id=$get_category_id") or die(mysqli_error($link));


				echo"
				<div class=\"info\"><p>New month - new image! This months recipe is $get_recipe_title</p></div>
				";
			}
		} // new image for category
		
		if($x == 2){
			// echo"		<div class=\"recipes_categories_break\"></div>\n";
			$x = 0;
		}
		
		echo"
		<div class=\"recipes_categories_column\">
			<p>
			<a href=\"$root/recipes/categories_browse.php?category_id=$get_category_id&amp;l=$l\"><img src=\"$root/$get_category_image_path/$get_category_image_file\" alt=\"$get_category_translation_image\" /></a><br />
			<a href=\"$root/recipes/categories_browse.php?category_id=$get_category_id&amp;l=$l\" class=\"h2\">$get_category_translation_title</a>
			</p>
		</div>
		";

		// Increment
		$x++;

	}
	echo"
	</div> <!-- //recipes_categories_wrapper -->
	";

}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>