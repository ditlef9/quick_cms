
<?php 
/**
*
* File: recipes/index.php
* Version 1.0.0
* Date 13:43 18.11.2017
* Copyright (c) 2011-2017 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
* Language: PHP 
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
$website_title = "$l_recipes";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

// Language
include("$root/_admin/_translations/site/$l/recipes/ts_search.php");


// Change Img from png to img
$query = "SELECT * FROM $t_recipes WHERE recipe_image LIKE '%png'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
    list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed) = $row;


	echo"<img src=\"$root/$get_recipe_image_path/$get_recipe_image\" alt=\"Alternative text\" />";

	$filetype = substr("$get_recipe_image", -3); 

	// If filetype is PNG, convert to jpg
	if ($filetype == "png") {

		// Make New Filename
		$inp_recipe_img = "$get_recipe_id.jpeg";
		$inp_recipe_img_mysql = quote_smart($link, $inp_recipe_img);

		$inp_recipe_thumb = $get_recipe_id . "-thumb.jpeg";
		$inp_recipe_thumb_mysql = quote_smart($link, $inp_recipe_thumb);
			 	

		// Get height/width
	 	$imgsize = getimagesize("$root/$get_recipe_image_path/$get_recipe_image");
   	 	$width = $imgsize[0];
    	 	$height = $imgsize[1];

		// Definition on image
 		$image_create = "imagecreatefrompng";
           	$image = "imagejpeg";
          	$quality = 80;
		
		// Create image
		$dst_img = imagecreatetruecolor($width, $height);
    		$src_img = $image_create("$root/$get_recipe_image_path/$get_recipe_image");

		//Resample image
		 imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $width, $height, $width, $height);

		//Save image
		imagejpeg($dst_img, "$root/$get_recipe_image_path/$inp_recipe_img", $quality);

		// Save to MSQL
		$res = mysqli_query($link, "UPDATE $t_recipes SET  recipe_image=$inp_recipe_img_mysql, recipe_thumb=$inp_recipe_thumb_mysql WHERE recipe_id='$get_recipe_id'");
		
		// Delete original PNG img
		unlink("$root/$get_recipe_image_path/$get_recipe_image");
		unlink("$root/$get_recipe_image_path/$get_recipe_thumb");
		
	}   

}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>