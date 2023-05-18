<?php 
/**
*
* File: exercise/new_equipment_step_2_categorization.php
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
include("$root/_admin/_translations/site/$l/exercises/ts_new_equipment.php");
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['equipment_id'])){
	$equipment_id = $_GET['equipment_id'];
	$equipment_id = output_html($equipment_id);
}
else{
	$equipment_id = "";
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
$website_title = "$l_new_equipment - $l_exercises";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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

	// Get equipment
	$equipment_id_mysql = quote_smart($link, $equipment_id);
	$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, equipment_text, equipment_image_path, equipment_image_file FROM $t_exercise_equipments WHERE equipment_id=$equipment_id_mysql AND equipment_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_user_id, $get_equipment_language, $get_equipment_muscle_group_id_main, $get_equipment_muscle_group_id_sub, $get_equipment_muscle_part_of_id, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file) = $row;
	
	

	if($get_equipment_id == ""){
		echo"<p>Equipment not found.</p>";
	}
	else{

		if($process == "1"){

			// Delete cache
			delete_cache("$root/_cache");
			mkdir("$root/_cache");
				


			// Sjekk filen
			$file_name = basename($_FILES['inp_image']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			$upload_path = "$root/_uploads/exercises/$l/equipment/$get_equipment_title_clean";

				if(!(is_dir("$root/_uploads"))){
					mkdir("$root/_uploads");
				}
				if(!(is_dir("$root/_uploads/exercises"))){
					mkdir("$root/_uploads/exercises");
				}
				if(!(is_dir("$root/_uploads/exercises/$l"))){
					mkdir("$root/_uploads/exercises/$l");
				}
				if(!(is_dir("$root/_uploads/exercises/$l/equipment"))){
					mkdir("$root/_uploads/exercises/$l/equipment");
				}
				if(!(is_dir("$root/_uploads/exercises/$l/equipment/$get_equipment_title_clean"))){
					mkdir("$root/_uploads/exercises/$l/equipment/$get_equipment_title_clean");
				}

				// Sett variabler
				$new_name = $get_equipment_title_clean . "_" . $get_equipment_id . ".$file_type";

				$target_path = $upload_path . "/" . $new_name;


				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){


					// Do I already have a image of that type? Then delete the old image..
				
					if($get_equipment_image_file != "" && file_exists("$root/$get_equipment_image_path/$get_equipment_image_file")){
						unlink("$root/$get_equipment_image_path/$get_equipment_image_file");
					}

					



					if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize($target_path);
						if(is_numeric($width) && is_numeric($height)){


							// Check that file is big enough
							if($width < 839){
								unlink($target_path);
								$url = "new_equipment_step_4_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
								header("Location: $url");
								exit;
							}
							if($height < 839){
								unlink($target_path);
								$url = "new_equipment_step_4_images.php?action=upload_image&exercise_id=$exercise_id&l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
								header("Location: $url");
								exit;
							}

							
							$inp_equipment_image_path  = "_uploads/exercises/$l/equipment/$get_equipment_title_clean";
							$inp_equipment_image_path_mysql = quote_smart($link, $inp_equipment_image_path);

							// recipe_image
							$inp_equipment_image_file = $new_name;
							$inp_equipment_image_file_mysql = quote_smart($link, $inp_equipment_image_file);

					
							// Dette bildet er OK
							// Resize it
							$inp_new_x = 840;
							$inp_new_y = 840;
							resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_equipment_image_path/$inp_equipment_image_file", "$root/$inp_equipment_image_path/$inp_equipment_image_file");
					

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_exercise_equipments SET equipment_image_path=$inp_equipment_image_path_mysql,
							equipment_image_file=$inp_equipment_image_file_mysql WHERE equipment_id=$equipment_id_mysql");



	
							// Header
							$url = "view_equipment.php?equipment_id=$equipment_id&l=$l&ft=success&fm=image_uploaded";
							header("Location: $url");
							exit;
					
						}
						else{
							// Dette er en fil som har fått byttet filendelse...
							unlink("$target_path");

							$url = "new_equipment_step_4_images.php?equipment_id=$equipment_id&l=$l&ft=error&fm=file_is_not_an_image";
							header("Location: $url");
							exit;
						}
					}
					else{
   						switch ($_FILES['inp_image'] ['error']){
							case 1:
								$url = "new_equipment_step_4_images.php?equipment_id=$equipment_id&l=$ll&ft=error&fm=to_big_file";
								header("Location: $url");
								exit;
								break;
							case 2:
								$url = "new_equipment_step_4_images.php?equipment_id=$equipment_id&l=$l&ft=error&fm=to_big_file";
								header("Location: $url");
								exit;
								break;
							case 3:
								$url = "new_equipment_step_4_images.php?equipment_id=$equipment_id&l=$l&ft=error&fm=only_parts_uploaded";
								header("Location: $url");
								exit;
								break;
							case 4:
								$url = "new_equipment_step_4_images.php?equipment_id=$equipment_id&l=$l&ft=error&fm=no_file_uploaded";
								header("Location: $url");
								exit;
								break;
						}
					} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
				}
				else{
					$url = "new_equipment_step_4_images.php?equipment_id=$equipment_id&l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
					header("Location: $url");
					exit;
				}
				

		} // process
	
		echo"
		<h1>$l_new_equipment</h1>
	


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
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->




		<!-- Form -->

			<form method=\"post\" action=\"new_equipment_step_4_images.php?equipment_id=$equipment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<p><b>$l_new_image (840x840 jpg):</b><br />
			<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			
			<p>
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>



			<p style=\"margin-top: 40px;\">
			<a href=\"view_equipment.php?equipment_id=$equipment_id&amp;l=$l\" class=\"btn btn_default\">$l_view_equipment</a>
			</p>
		<!-- //Form -->
		";
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