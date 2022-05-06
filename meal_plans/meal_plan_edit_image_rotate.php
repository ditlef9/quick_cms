<?php 
/**
*
* File: meal_plans/meal_plan_delete.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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
include("_tables_meal_plans.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/meal_plans/ts_new_meal_plan.php");
include("$root/_admin/_translations/site/$l/meal_plans/ts_meal_plan_edit.php");


/*- Functions -------------------------------------------------------------------------------- */
function delete_cache($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
  				unlink($dirname."/".$file);
        		else
				delete_directory($dirname.'/'.$file);    
			}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
}



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['meal_plan_id'])){
	$meal_plan_id = $_GET['meal_plan_id'];
	$meal_plan_id = output_html($meal_plan_id);
}
else{
	$meal_plan_id = "";
}
if(isset($_GET['entry_day_number'])){
	$entry_day_number = $_GET['entry_day_number'];
	$entry_day_number = output_html($entry_day_number);
}
else{
	$entry_day_number = "";
}
if(isset($_GET['entry_meal_number'])){
	$entry_meal_number = $_GET['entry_meal_number'];
	$entry_meal_number = output_html($entry_meal_number);
}
else{
	$entry_meal_number = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_meal_plan - $l_meal_plans";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get meal_plan
	$meal_plan_id_mysql = quote_smart($link, $meal_plan_id);
	$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_thumb_74x50, meal_plan_image_thumb_400x269, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_id=$meal_plan_id_mysql AND meal_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_meal_plan_id, $get_current_meal_plan_user_id, $get_current_meal_plan_language, $get_current_meal_plan_title, $get_current_meal_plan_title_clean, $get_current_meal_plan_number_of_days, $get_current_meal_plan_introduction, $get_current_meal_plan_total_energy_without_training, $get_current_meal_plan_total_fat_without_training, $get_current_meal_plan_total_carb_without_training, $get_current_meal_plan_total_protein_without_training, $get_current_meal_plan_total_energy_with_training, $get_current_meal_plan_total_fat_with_training, $get_current_meal_plan_total_carb_with_training, $get_current_meal_plan_total_protein_with_training, $get_current_meal_plan_average_kcal_without_training, $get_current_meal_plan_average_fat_without_training, $get_current_meal_plan_average_carb_without_training, $get_current_meal_plan_average_protein_without_training, $get_current_meal_plan_average_kcal_with_training, $get_current_meal_plan_average_fat_with_training, $get_current_meal_plan_average_carb_with_training, $get_current_meal_plan_average_protein_with_training, $get_current_meal_plan_created, $get_current_meal_plan_updated, $get_current_meal_plan_user_ip, $get_current_meal_plan_image_path, $get_current_meal_plan_image_thumb_74x50, $get_current_meal_plan_image_thumb_400x269, $get_current_meal_plan_image_file, $get_current_meal_plan_views, $get_current_meal_plan_views_ip_block, $get_current_meal_plan_likes, $get_current_meal_plan_dislikes, $get_current_meal_plan_rating, $get_current_meal_plan_rating_ip_block, $get_current_meal_plan_comments) = $row;
	
	

	if($get_current_meal_plan_id == ""){
		echo"<p>Meal plan not found.</p>";
	}
	else{

		if($process == 1){
			

			// Delete cache
			delete_cache("$root/_cache");
			mkdir("$root/_cache");
			
			// Random id
			$seed = str_split('abcdefghijklmnopqrstuvwxyz'
			                 .'0123456789'); // and any other characters
			shuffle($seed); // probably optional since array_is randomized; this may be redundant
			$random_string = '';
			foreach (array_rand($seed, 2) as $k) $random_string .= $seed[$k];

			// extension
			$extension = getExtension("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_file");
			$extension = strtolower($extension);


			// New name
			$new_name = $get_current_meal_plan_title_clean . "_" . $get_meal_plan_id  . "_" . $random_string . ".$extension";
			$image_final_path = "../" . $get_current_meal_plan_image_path . "/" . $new_name;



			// Load
			if($extension == "jpg"){
				$source = imagecreatefromjpeg("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_file");
			}
			elseif($extension == "gif"){
				$source = ImageCreateFromGif("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_file");
			}
			else{
				$source = ImageCreateFromPNG("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_file");
			}

			$original_x = imagesx($source);
			$original_y = imagesy($source);

			$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
   
			// Rotate
   			$rotate = imagerotate($source, 270, $bgColor);
   			imagesavealpha($rotate, true);

			if($extension == "jpg"){
				imagejpeg($rotate, $image_final_path);
			}
			elseif($extension == "gif"){
				imagegif($rotate, $image_final_path);
			}
			else{
				imagepng($rotate, $image_final_path);
			}
   			

			// Free memory
			imagedestroy($source);
			imagedestroy($rotate); 


			// Delete old
			if(file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file") && $get_current_meal_plan_image_file != ""){
				unlink("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_file");
			}
			if(file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269") && $get_current_meal_plan_image_thumb_400x269 != ""){
				unlink("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269");
			}
			if(file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_74x50") && $get_current_meal_plan_image_thumb_74x50 != ""){
				unlink("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_74x50");
			}

			// image file
			$inp_image_file = $new_name;
			$inp_image_file_mysql = quote_smart($link, $inp_image_file);


			// Update MySQL
			$result = mysqli_query($link, "UPDATE $t_meal_plans SET 
			meal_plan_image_file=$inp_image_file_mysql WHERE meal_plan_id=$meal_plan_id_mysql");


			
			$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=success&fm=image $image_final_path rotated";
			header("Location: $url");
			exit;
		} // process
	} // meal found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>