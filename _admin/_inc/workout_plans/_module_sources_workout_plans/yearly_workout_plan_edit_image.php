<?php 
/**
*
* File: workout_plans/yearly_workout_plan_edit_image.php
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
			$inp_type_title_clean = clean($get_type_title);
			$upload_path = "$root/_uploads/workout_plans/$l/yearly/$get_current_workout_yearly_title_clean";

			if(!(is_dir("$root/_uploads"))){
				mkdir("$root/_uploads");
			}
			if(!(is_dir("$root/_uploads/workout_plans"))){
				mkdir("$root/_uploads/workout_plans");
			}
			if(!(is_dir("$root/_uploads/workout_plans/$l"))){
				mkdir("$root/_uploads/workout_plans/$l");
			}
			if(!(is_dir("$root/_uploads/workout_plans/$l/yearly"))){
				mkdir("$root/_uploads/workout_plans/$l/yearly");
			}
			if(!(is_dir("$root/_uploads/workout_plans/$l/yearly/$get_current_workout_yearly_title_clean"))){
				mkdir("$root/_uploads/workout_plans/$l/yearly/$get_current_workout_yearly_title_clean");
			}

			// Sett variabler
			$new_name = $get_current_workout_yearly_title_clean . "_" . $get_workout_yearly_id . ".$file_type";

			$target_path = $upload_path . "/" . $new_name;

			// Sjekk om det er en OK filendelse
			if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){


				// Do I already have a image of that type? Then delete the old image..
				if($get_current_workout_yearly_image_file != "" && file_exists("$root/$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file")){
					unlink("$root/$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");
				}

					

				if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {
					// Sjekk om det faktisk er et bilde som er lastet opp
					list($width,$height) = getimagesize($target_path);
					if(is_numeric($width) && is_numeric($height)){

						// Check that file is big enough
						if($width < 949){
							unlink($target_path);
							$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
							header("Location: $url");
							exit;
						}
						if($height < 639){
							unlink($target_path);
							$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
							header("Location: $url");
							exit;
						}

						


						// image path							
						$inp_image_path  = "_uploads/workout_plans/$l/yearly/$get_current_workout_yearly_title_clean";
						$inp_image_path_mysql = quote_smart($link, $inp_image_path);

						// image file
						$inp_image_file = $new_name;
						$inp_image_file_mysql = quote_smart($link, $inp_image_file);

					
						// Dette bildet er OK
						// Resize it
						$inp_new_x = 950;
						$inp_new_y = 640;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_image_path/$inp_image_file", "$root/$inp_image_path/$inp_image_file");

						// Update MySQL
						$result = mysqli_query($link, "UPDATE $t_workout_plans_yearly SET workout_yearly_image_path=$inp_image_path_mysql,
						workout_yearly_image_file=$inp_image_file_mysql WHERE workout_yearly_id=$yearly_id_mysql");


						// Header
						$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=success&fm=image_uploaded";
						header("Location: $url");
						exit;
					}
					else{
						// Dette er en fil som har fått byttet filendelse...
						unlink("$target_path");
						$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=file_is_not_an_image";
						header("Location: $url");
						exit;
					}
				}
				else{
					switch ($_FILES['inp_image'] ['error']){
					case 1:
						$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$ll&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
						break;
					case 2:
						$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
						break;
					case 3:
						$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=only_parts_uploaded";
						header("Location: $url");
						exit;
						break;
					case 4:
						$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=no_file_uploaded";
						header("Location: $url");
						exit;
						break;
					}
				} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			}
			else{
				$url = "yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
				header("Location: $url");
				exit;
			} // file type end
				

		} // process
	
		echo"
		<h1>$get_current_workout_yearly_title</h1>
	

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
			&gt;
			<a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_edit</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Navigation -->
			<div class=\"tabs\" style=\"margin-top: 6px;\">
				<ul>
					<li><a href=\"yearly_workout_plan_view.php?yearly_id=$yearly_id&amp;l=$l\">$l_view</a></li>
					<li><a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_edit</a></li>
					<li><a href=\"yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_image</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"margin-bottom: 6px;\"></div>
		<!-- //Navigation -->


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


		<h2>$l_image</h2>


		<!-- Exisitng image -->

			<p>";
			if($get_current_workout_yearly_image_file != "" && file_exists("$root/$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file")){
				echo"
				<img src=\"$root/$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file\" alt=\"$get_current_workout_yearly_image_file\" /><br />
				
				<a href=\"yearly_workout_plan_edit_image_rotate.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\">$l_rotate</a>
				";
			}
			echo"
			</p>
		<!-- //Exisitng image -->

		<!-- Form -->
			

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_text\"]').focus();
				});
			</script>
			<!-- //Focus -->


			<form method=\"post\" action=\"yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

			<p><b>$l_new_image (950x640 png):</b><br />
			<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			
			<p>
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
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