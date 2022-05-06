<?php 
/**
*
* File: workout_plans/yearly_workout_plan_edit_image_rotate.php
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
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['yearly_id'])){
	$yearly_id = $_GET['yearly_id'];
	$yearly_id = output_html($yearly_id);
}
else{
	$yearly_id = "";
}
if(isset($_GET['duration_type'])){
	$duration_type = $_GET['duration_type'];
	$duration_type = strip_tags(stripslashes($duration_type));
}
else{
	$duration_type = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




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


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_workout_plan - $l_workout_plans";
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

	// Get workout plan yearly
	$yearly_id_mysql = quote_smart($link, $yearly_id);
	$query = "SELECT workout_yearly_id, workout_yearly_user_id, workout_yearly_language, workout_yearly_title, workout_yearly_title_clean, workout_yearly_introduction, workout_yearly_goal, workout_yearly_text, workout_yearly_year, workout_yearly_image_path, workout_yearly_image_file, workout_yearly_created, workout_yearly_updated, workout_yearly_unique_hits, workout_yearly_unique_hits_ip_block, workout_yearly_comments, workout_yearly_likes, workout_yearly_dislikes, workout_yearly_rating, workout_yearly_ip_block, workout_yearly_user_ip, workout_yearly_notes FROM $t_workout_plans_yearly WHERE workout_yearly_id=$yearly_id_mysql AND workout_yearly_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_yearly_id, $get_current_workout_yearly_user_id, $get_current_workout_yearly_language, $get_current_workout_yearly_title, $get_current_workout_yearly_title_clean, $get_current_workout_yearly_introduction, $get_current_workout_yearly_goal, $get_current_workout_yearly_text, $get_current_workout_yearly_year, $get_current_workout_yearly_image_path, $get_current_workout_yearly_image_file, $get_current_workout_yearly_created, $get_current_workout_yearly_updated, $get_current_workout_yearly_unique_hits, $get_current_workout_yearly_unique_hits_ip_block, $get_current_workout_yearly_comments, $get_current_workout_yearly_likes, $get_current_workout_yearly_dislikes, $get_current_workout_yearly_rating, $get_current_workout_yearly_ip_block, $get_current_workout_yearly_user_ip, $get_current_workout_yearly_notes) = $row;
	
	

	if($get_workout_yearly_id == ""){
		echo"<p>Workout yearly not found.</p>";
	}
	else{
		if(!(file_exists("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file"))) {
			echo"Img not found";die;
		}

		if($process == "1"){


			
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
			$extension = getExtension("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");
			$extension = strtolower($extension);


			// New name
			$new_name = $get_current_workout_yearly_title_clean . "_" . $get_workout_yearly_id  . "_" . $random_string . ".$extension";
			$image_final_path = "../" . $get_current_workout_yearly_image_path . "/" . $new_name;



			// Load
			if($extension == "jpg"){
				$source = imagecreatefromjpeg("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");
			}
			elseif($extension == "gif"){
				$source = ImageCreateFromGif("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");
			}
			else{
				$source = ImageCreateFromPNG("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");
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
			unlink("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");

			// image file
			$inp_image_file = $new_name;
			$inp_image_file_mysql = quote_smart($link, $inp_image_file);

			// Update with new
			$result = mysqli_query($link, "UPDATE $t_workout_plans_yearly SET 
				workout_yearly_image_file=$inp_image_file_mysql WHERE workout_yearly_id=$yearly_id_mysql");


			
			$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=success&fm=image $image_final_path rotated";
			header("Location: $url");
			exit;
		}
	} // found
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