<?php 
/**
*
* File: exercise/new_exercise_step_11_images.php
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
include("_tables_exercises.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['exercise_id'])){
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = output_html($exercise_id);
}
else{
	$exercise_id = "";
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



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_exercise - $l_exercises";
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

	// Get exercise
	$exercise_id_mysql = quote_smart($link, $exercise_id);
	$query = "SELECT exercise_id, exercise_title, exercise_title_clean, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql AND exercise_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id, $get_exercise_title, $get_exercise_title_clean, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason) = $row;
	
	// Get type name
	$query = "SELECT type_id, type_title FROM $t_exercise_types WHERE type_id='$get_exercise_type_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_type_id, $get_type_title) = $row;
	if($get_type_id == ""){
		echo"Type not found";
		die;
	}

	// Get main muscle group
	$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean FROM $t_muscle_groups WHERE muscle_group_id='$get_exercise_muscle_group_id_main'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean) = $row;
	if($get_main_muscle_group_id == ""){
		echo"muscle_group_id_main not found";
		die;
	}
	

	

	

	if($get_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{

		if($action == "upload_image"){
			if($process == "1"){
				// Delete cache
				delete_cache("$root/_cache");
				mkdir("$root/_cache");
				
				// Type
				$inp_image_type = $_POST['inp_image_type'];
				$inp_image_type = output_html($inp_image_type);
				$inp_image_type_mysql = quote_smart($link, $inp_image_type);
				


				// Sjekk filen
				$file_name = basename($_FILES['inp_image']['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// Finnes mappen?
				$inp_type_title_clean = clean($get_type_title);
				$upload_path = "$root/_uploads/exercises/$l/$inp_type_title_clean/$get_main_muscle_group_name_clean/$get_exercise_title_clean";

				if(!(is_dir("$root/_uploads"))){
					mkdir("$root/_uploads");
				}
				if(!(is_dir("$root/_uploads/exercises"))){
					mkdir("$root/_uploads/exercises");
				}
				if(!(is_dir("$root/_uploads/exercises/$l"))){
					mkdir("$root/_uploads/exercises/$l");
				}
				if(!(is_dir("$root/_uploads/exercises/$l/$inp_type_title_clean"))){
					mkdir("$root/_uploads/exercises/$l/$inp_type_title_clean");
				}
				if(!(is_dir("$root/_uploads/exercises/$l/$inp_type_title_clean/$get_main_muscle_group_name_clean"))){
					mkdir("$root/_uploads/exercises/$l/$inp_type_title_clean/$get_main_muscle_group_name_clean");
				}
				if(!(is_dir("$root/_uploads/exercises/$l/$inp_type_title_clean/$get_main_muscle_group_name_clean/$get_exercise_title_clean"))){
					mkdir("$root/_uploads/exercises/$l/$inp_type_title_clean/$get_main_muscle_group_name_clean/$get_exercise_title_clean");
				}



				// Sett variabler
				$new_name = $get_exercise_title_clean . "_" . $get_exercise_id . "_" . $inp_image_type . ".$file_type";

				$target_path = $upload_path . "/" . $new_name;


				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){


					// Do I already have a image of that type? Then delete the old image..
					$query = "SELECT exercise_image_id, exercise_image_path, exercise_image_file, exercise_image_thumb_120x120, exercise_image_thumb_150x150, exercise_image_thumb_350x350 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' AND exercise_image_type=$inp_image_type_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_exercise_image_id, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_120x120, $get_exercise_image_thumb_150x150, $get_exercise_image_thumb_350x350) = $row;
					if($get_exercise_image_id != ""){
						if($get_exercise_image_file != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_file")){
							unlink("$root/$get_exercise_image_path/$get_exercise_image_file");
						}
						if($get_exercise_image_thumb_120x120 != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_thumb_120x120")){
							unlink("$root/$get_exercise_image_path/$get_exercise_image_thumb_120x120");
						}
						if($get_exercise_image_thumb_150x150 != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150")){
							unlink("$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150");
						}
						if($get_exercise_image_thumb_350x350 != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_thumb_350x350")){
							unlink("$root/$get_exercise_image_path/$get_exercise_image_thumb_350x350");
						}

						// Delete from mysql
						$result = mysqli_query($link, "DELETE FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' AND exercise_image_type=$inp_image_type_mysql");


					}



					if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize($target_path);
						if(is_numeric($width) && is_numeric($height)){


							// Check that file is big enough
							if($width < 889){
								unlink($target_path);
								$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
								header("Location: $url");
								exit;
							}
							if($height < 889){
								unlink($target_path);
								$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
								header("Location: $url");
								exit;
							}



							// recipe_image_path
							$inp_exercise_image_datetime = date("Y-m-d H:i:s");

							$inp_exercise_image_path  = "_uploads/exercises/$l/$inp_type_title_clean/$get_main_muscle_group_name_clean/$get_exercise_title_clean";
							$inp_exercise_image_path_mysql = quote_smart($link, $inp_exercise_image_path);

							// recipe_image
							$inp_exercise_image_file = $new_name;
							$inp_exercise_image_file_mysql = quote_smart($link, $inp_exercise_image_file);

					
							// IP
							$inp_exercise_image_user_ip = $_SERVER['REMOTE_ADDR'];
							$inp_exercise_image_user_ip = output_html($inp_exercise_image_user_ip);
							$inp_exercise_image_user_ip_mysql = quote_smart($link, $inp_exercise_image_user_ip);

							// Dette bildet er OK
							// Resize it
							$inp_new_x = 890;
							$inp_new_y = 890;
							resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_exercise_image_path/$inp_exercise_image_file", "$root/$inp_exercise_image_path/$inp_exercise_image_file");
					
					
							// Thumbs
							$inp_exercise_image_thumb_120x120  = $get_exercise_title_clean . "_" . $get_exercise_id . "_" . $inp_image_type . "_thumb_120x120.$file_type";
							$inp_exercise_image_thumb_120x120_mysql = quote_smart($link, $inp_exercise_image_thumb_120x120);

							$inp_exercise_image_thumb_150x150 = $get_exercise_title_clean . "_" . $get_exercise_id . "_" . $inp_image_type . "_thumb_150x150.$file_type";
							$inp_exercise_image_thumb_150x150_mysql = quote_smart($link, $inp_exercise_image_thumb_150x150);

							$inp_exercise_image_thumb_350x350  = $get_exercise_title_clean . "_" . $get_exercise_id . "_" . $inp_image_type . "_thumb_350x350.$file_type";
							$inp_exercise_image_thumb_350x350_mysql = quote_smart($link, $inp_exercise_image_thumb_350x350);


							// Insert MySQL
							mysqli_query($link, "INSERT INTO $t_exercise_index_images
							(exercise_image_id, exercise_image_user_id, exercise_image_exercise_id, exercise_image_datetime, exercise_image_user_ip, exercise_image_type, exercise_image_path, 
							exercise_image_file, exercise_image_thumb_120x120, exercise_image_thumb_150x150, exercise_image_thumb_350x350, exercise_image_uniqe_hits, exercise_image_uniqe_hits_ip_block) 
							VALUES 
							(NULL, $my_user_id_mysql, '$get_exercise_id', '$inp_exercise_image_datetime', $inp_exercise_image_user_ip_mysql, $inp_image_type_mysql, $inp_exercise_image_path_mysql, 
							$inp_exercise_image_file_mysql, $inp_exercise_image_thumb_120x120_mysql, $inp_exercise_image_thumb_150x150_mysql, $inp_exercise_image_thumb_350x350_mysql, '0', '')")
							or die(mysqli_error($link));

	
							// Header
							$url = "new_exercise_step_11_images.php?exercise_id=$exercise_id&l=$l&ft=success&fm=image_uploaded&file_name=$inp_exercise_image_file&file_path=$inp_exercise_image_path";
							header("Location: $url");
							exit;
					
						}
						else{
							// Dette er en fil som har fått byttet filendelse...
							unlink("$target_path");

							$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=file_is_not_an_image";
							header("Location: $url");
							exit;
						}
					}
					else{
   						switch ($_FILES['inp_image'] ['error']){
							case 1:
								$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$ll&ft=error&fm=to_big_file";
								header("Location: $url");
								exit;
								break;
							case 2:
								$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=to_big_file";
								header("Location: $url");
								exit;
								break;
							case 3:
								$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=only_parts_uploaded";
								header("Location: $url");
								exit;
								break;
							case 4:
								$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=no_file_uploaded";
								header("Location: $url");
								exit;
								break;
						}
					} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
				}
				else{
					$url = "new_exercise_step_11_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
					header("Location: $url");
					exit;
				}
				
			}

			echo"
			<h1>$get_exercise_title</h1>
			<p>$l_muscle_group: $get_main_muscle_group_name</p>

			

			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "width_have_to_be_bigger"){
					$fm = "$l_width_have_to_be_bigger";
				}
				elseif($fm == "height_have_to_be_bigger"){
					$fm = "$l_height_have_to_be_bigger";
				}
				elseif($fm == "image_uploaded"){
					$fm = "$l_image_uploaded";
				}
				elseif($fm == "file_is_not_an_image"){
					$fm = "$l_file_is_not_an_image";
				}
				elseif($fm == "to_big_file"){
					$fm = "$l_to_big_file";
				}
				elseif($fm == "only_parts_uploaded"){
					$fm = "$l_only_parts_uploaded";
				}
				elseif($fm == "no_file_uploaded"){
					$fm = "$l_no_file_uploaded";
				}
				elseif($fm == "invalid_file_type"){
					$fm = "$l_invalid_file_type";
				}
				elseif($fm == "image_rotated"){
					$fm = "$l_image_rotated";
				}
				elseif($fm == "image_not_found"){
					$fm = "$l_image_not_found";
				}
				elseif($fm == "image_uploaded"){
					$fm = "$l_image_uploaded";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->



			<form method=\"post\" action=\"new_exercise_step_11_images.php?action=upload_image&amp;exercise_id=$exercise_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<p><b>$l_new_image (890x890 jpg):</b><br />
			<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>$l_image_type:</b><br />
			<select name=\"inp_image_type\">
				<option value=\"guide_1\">$l_guide_1</option>
				<option value=\"guide_2\">$l_guide_2</option>
				<option value=\"inspiration\">$l_inspiration</option>
			</select>
			</p>
			
			<p>
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>



			<p style=\"margin-top: 40px;\">
			<a href=\"new_exercise_step_11_images.php?exercise_id=$exercise_id&amp;l=$l&\" class=\"btn btn_default\">$l_images</a>
			<a href=\"new_exercise_step_12_video.php?exercise_id=$exercise_id&amp;l=$l&\" class=\"btn btn_default\">$l_continue</a>
			</p>

			";
		} // action == "upload_image"
		elseif($action == ""){
			// Shows all images related to this equipment

	
			echo"
			<h1>$l_new_exercise</h1>
	

			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Display all images -->

				<table>
				 <tr>";

			
			$query = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_150x150 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_150x150) = $row;

				if($get_exercise_image_file != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_file")){

					if($get_exercise_image_thumb_150x150 == ""){
						$extension = get_extension($get_exercise_image_file);
						$extension = strtolower($extension);

						$thumb = substr($get_exercise_image_file, 0, -4);
						$get_exercise_image_thumb_150x150 = $thumb . "_thumb_150x150." . $extension;
						$thumb_mysql = quote_smart($link, $get_exercise_image_thumb_150x150);

						$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_150x150=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
					}
					if(!(file_exists("../$get_exercise_image_path/$get_exercise_image_thumb_150x150"))){
						$extension = get_extension($get_exercise_image_file);
						$extension = strtolower($extension);

						$thumb = substr($get_exercise_image_file, 0, -4);
						$thumb = $thumb . "_thumb_150x150." . $extension;
						$thumb_mysql = quote_smart($link, $thumb);

						// Thumb
						$inp_new_x = 150;
						$inp_new_y = 150;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_exercise_image_path/$get_exercise_image_file", "$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150");

						$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_150x150=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
					}


					$get_exercise_image_type_saying = ucfirst(str_replace("_", " ", $get_exercise_image_type));
					
					echo"
					  <td style=\"padding-right: 10px;\">
						<p><b>$get_exercise_image_type_saying</b><br />
						<img src=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" />
						</p>
					  </td>";
				}
			}
			echo"
				 </tr>
				</table>
			<!-- //Display all images -->

			<p>
			<a href=\"new_exercise_step_11_images.php?action=upload_image&amp;exercise_id=$exercise_id&amp;l=$l&\" class=\"btn\">$l_upload_image</a>
			<a href=\"new_exercise_step_12_video.php?exercise_id=$exercise_id&amp;l=$l&\" class=\"btn\">$l_continue</a>
			</p>

			";
		} // action == ""
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