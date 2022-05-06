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
				

			// Sjekk filen
			$file_name = basename($_FILES['inp_image']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			$upload_path = "$root/_uploads/meal_plans/$l/$get_current_meal_plan_title_clean";

			if(!(is_dir("$root/_uploads"))){
				mkdir("$root/_uploads");
			}
			if(!(is_dir("$root/_uploads/meal_plans"))){
				mkdir("$root/_uploads/meal_plans");
			}
			if(!(is_dir("$root/_uploads/meal_plans/$l"))){
				mkdir("$root/_uploads/meal_plans/$l");
			}
			if(!(is_dir("$root/_uploads/meal_plans/$l/$get_current_meal_plan_title_clean"))){
				mkdir("$root/_uploads/meal_plans/$l/$get_current_meal_plan_title_clean");
			}

			// Sett variabler
			$new_name = $get_current_meal_plan_title_clean . "_" . $get_current_meal_plan_id . ".$file_type";

			$target_path = $upload_path . "/" . $new_name;

			// Sjekk om det er en OK filendelse
			if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){


				// Do I already have a image of that type? Then delete the old image..
				if($get_current_meal_plan_image_file != "" && file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file")){
					unlink("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file");
				}

					

				if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {
					// Sjekk om det faktisk er et bilde som er lastet opp
					list($width,$height) = getimagesize($target_path);
					if(is_numeric($width) && is_numeric($height)){

						// Check that file is big enough
						if($width < 949){
							unlink($target_path);
							$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
							header("Location: $url");
							exit;
						}
						if($height < 639){
							unlink($target_path);
							$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
							header("Location: $url");
							exit;
						}

						// Dette bildet er OK

						// Delete old thumbs
						if(file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269") && $get_current_meal_plan_image_thumb_400x269 != ""){
							unlink("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269");
						}
						if(file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_74x50") && $get_current_meal_plan_image_thumb_74x50 != ""){
							unlink("../$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_74x50");
						}



						// image path							
						$inp_image_path  = "_uploads/meal_plans/$l/$get_current_meal_plan_title_clean";
						$inp_image_path_mysql = quote_smart($link, $inp_image_path);

						// image file
						$inp_image_file = $new_name;
						$inp_image_file_mysql = quote_smart($link, $inp_image_file);

						// Dette bildet er OK
						// Resize it
						$inp_new_x = 950;
						$inp_new_y = 640;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_image_path/$inp_image_file", "$root/$inp_image_path/$inp_image_file");
					

						// Thumb a
						$inp_image_thumb_a = $get_current_meal_plan_title_clean . "_" . $get_current_meal_plan_id . "_thumb_74x50" . $file_type;
						$inp_image_thumb_a_mysql = quote_smart($link, $inp_image_thumb_a);


						// Thumb b
						$inp_image_thumb_b = $get_current_meal_plan_title_clean . "_" . $get_current_meal_plan_id . "_thumb_400x269" . $file_type;
						$inp_image_thumb_b_mysql = quote_smart($link, $inp_image_thumb_b);


						// Update MySQL
						$result = mysqli_query($link, "UPDATE $t_meal_plans SET
										meal_plan_image_path=$inp_image_path_mysql,
										meal_plan_image_file=$inp_image_file_mysql, 
										meal_plan_image_thumb_74x50=$inp_image_thumb_a_mysql, 
										meal_plan_image_thumb_400x269=$inp_image_thumb_b_mysql 
										WHERE meal_plan_id=$meal_plan_id_mysql") or die(mysqli_error($link));


						// Header
						$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=success&fm=image_uploaded";
						header("Location: $url");
						exit;
					}
					else{
						// Dette er en fil som har fått byttet filendelse...
						unlink("$target_path");
						$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=file_is_not_an_image";
						header("Location: $url");
						exit;
					}
				}
				else{
					switch ($_FILES['inp_image'] ['error']){
					case 1:
						$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$ll&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
						break;
					case 2:
						$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
						break;
					case 3:
						$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=only_parts_uploaded";
						header("Location: $url");
						exit;
						break;
					case 4:
						$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=no_file_uploaded";
						header("Location: $url");
						exit;
						break;
					}
				} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			}
			else{
				$url = "meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
				header("Location: $url");
				exit;
			} // file type end
			
		}
		echo"
		<h1>$get_current_meal_plan_title</h1>
	
		<!-- Where am I ? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
			&gt;
			<a href=\"meal_plan_view_$get_current_meal_plan_number_of_days.php?meal_plan_id=$get_current_meal_plan_id&amp;l=$l\">$get_current_meal_plan_title</a>
			&gt;
			<a href=\"meal_plan_edit.php?meal_plan_id=$get_current_meal_plan_id&amp;entry_day_number=1&amp;l=$l\">$l_edit</a>
			&gt;
			<a href=\"meal_plan_edit_image.php?meal_plan_id=$get_current_meal_plan_id&amp;l=$l\">$l_image</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Edit menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=1&amp;l=$l\">$l_edit</a></li>
					<li><a href=\"meal_plan_edit_info.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_info</a></li>
					<li><a href=\"meal_plan_edit_text.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_text</a></li>
					<li><a href=\"meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&amp;l=$l\" class=\"selected\">$l_image</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Edit menu -->




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

		<!-- Existing img -->
";

			if($get_current_meal_plan_image_file != "" && file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file")){
				// 950 x 640
				
				// Thumb
				// Image original image size = 950 x 640
				if(!(file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269")) && $get_current_meal_plan_image_thumb_400x269 != ""){
					$inp_new_x = 400;
					$inp_new_y = 269;
					echo"<div class=\"info\"><p>Create thumb <a href=\"$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269\">$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269</a></p></div>\n";
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file", "$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269");
				}
				
				echo"
				<p>
				<img src=\"$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_thumb_400x269\" alt=\"img\" /></a><br />
				<a href=\"meal_plan_edit_image_rotate.php?meal_plan_id=$meal_plan_id&amp;l=$l&amp;process=1\">$l_rotate</a>

				</p>";
			}
			echo"
		<!--// Existing img -->

		
		<!-- Form -->


			<form method=\"post\" action=\"meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

			<p><b>$l_new_image (950x640 png):</b><br />
			<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			
			<p>
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
		<!-- //Form -->
		";
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