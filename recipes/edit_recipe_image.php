<?php 
/**
*
* File: recipes/edit_recipe_ingredients.php
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
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}

$l_mysql = quote_smart($link, $l);


if(isset($_GET['image'])){
	$image = $_GET['image'];
	$image = strip_tags(stripslashes($image));
}
else{
	$image = "";
}

/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

// Translations
include("$root/_admin/_translations/site/$l/recipes/ts_submit_recipe_step_4_images.php");
include("$root/_admin/_translations/site/$l/recipes/ts_edit_recipe.php");

/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$l_edit_recipe $get_recipe_title - $l_my_recipes";
}

include("$root/_webdesign/header.php");


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
				delete_cache($dirname.'/'.$file);    
			}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

/*- Content ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{
	if(isset($_SESSION['user_id'])){
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

		// Access to recipe edit
		if($get_recipe_user_id == "$my_user_id" OR $get_user_rank == "admin"){


			if($action == ""){

				if($process == 1){
					// Delete all old thumbnails
					if(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156") && $get_recipe_thumb_h_a_278x156 != ""){
						unlink("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156");
					}

					// Delete cache
					delete_cache("$root/_cache");
					mkdir("$root/_cache");
				

					// Finnes mappen?
					$year = date("Y");
					$upload_path = "$root/_uploads/recipes/$year/$get_recipe_id";

					if(!(is_dir("$root/_uploads"))){
						mkdir("$root/_uploads");
					}
					if(!(is_dir("$root/_uploads/recipes"))){
						mkdir("$root/_uploads/recipes");
					}
					if(!(is_dir("$root/_uploads/recipes/$year"))){
						mkdir("$root/_uploads/recipes/$year");
					}
					if(!(is_dir("$root/_uploads/recipes/$year/$get_recipe_id"))){
						mkdir("$root/_uploads/recipes/$year/$get_recipe_id");
					}

					// Is there a folder change?
					if($get_recipe_image_path != "" && $get_recipe_image_path != "_uploads/recipes/$year/$get_recipe_id"){
						// Update folder
						$inp_image_path_mysql = quote_smart($link, "_uploads/recipes/$year/$get_recipe_id");
						$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_image_path=$inp_image_path_mysql WHERE recipe_id=$recipe_id_mysql");

						// Move old images
						if(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a") && $get_recipe_image_h_a != ""){
							rename("$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/_uploads/recipes/$year/$get_recipe_id/$get_recipe_image_h_a");
						}
					
					}


					$ft_image_h_a = "";
					$fm_image_h_a = "";
					$ft_image_v_a = "";
					$fm_image_v_a = "";
					$inp_names_array = array("inp_image_h_a", "inp_image_v_a");
					for($x=0;$x<2;$x++){



						/*- Image upload ------------------------------------------------------------------------------------------ */
						$name = stripslashes($_FILES["$inp_names_array[$x]"]['name']);
						$extension = get_extension($name);
						$extension = strtolower($extension);
						if($name){
							if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
								if($inp_names_array[$x] == "inp_image_h_a"){
									$ft_image_h_a = "warning";
									$fm_image_h_a = "unknown_file_extension";
								}
								elseif($inp_names_array[$x] == "inp_image_v_a"){
									$ft_image_v_a = "warning";
									$fm_image_v_a = "unknown_file_extension";
								}
							}
							else{
					
 
								// Give new name
								$recipe_title_clean = clean($get_recipe_title);
								$new_name = "";

								if($inp_names_array[$x] == "inp_image_h_a"){
									$new_name = $recipe_title_clean . "_" . "h_a." . $extension;
								}
								elseif($inp_names_array[$x] == "inp_image_v_a"){
									$new_name = $recipe_title_clean . "_" . "v_a." . $extension;
								}
								else{
									echo"image number?";
									die;
								}
						
								$new_path = "$root/_uploads/recipes/$year/$get_recipe_id/";
								$uploaded_file = $new_path . $new_name;

								// Upload file
								if (move_uploaded_file($_FILES["$inp_names_array[$x]"]['tmp_name'], $uploaded_file)) {
									

									// Get image size
									$file_size = filesize($uploaded_file);
							
									// Check with and height
									list($width,$height) = getimagesize($uploaded_file);
		
									if($width == "" OR $height == ""){
										if($inp_names_array[$x] == "inp_image_h_a"){
											$ft_image_h_a = "warning";
											$fm_image_h_a = "getimagesize_failed";
										}
										elseif($inp_names_array[$x] == "inp_image_v_a"){
											$ft_image_v_a = "warning";
											$fm_image_v_a = "getimagesize_failed";
										}
										unlink($uploaded_file);
									}
									else{

										// Resize to 1920x1080 OR 1080x1920 
										if($inp_names_array[$x] == "inp_image_h_a"){
											$uploaded_file_new = $uploaded_file;
											if($width > 1920 OR $height > 1080){
												resize_crop_image(1920, 1080, $uploaded_file, $uploaded_file_new, $quality = 80);
											}
										}
										elseif($inp_names_array[$x] == "inp_image_v_a"){
											$uploaded_file_new = $uploaded_file;
											if($width > 1080 OR $height > 1920){
												resize_crop_image(1080, 1920, $uploaded_file, $uploaded_file_new, $quality = 80);
											}
										}



										$inp_image_path = "_uploads/recipes/$year/$get_recipe_id";
										$inp_image_path = output_html($inp_image_path);
										$inp_image_path_mysql = quote_smart($link, $inp_image_path);

										$inp_image_mysql = quote_smart($link, $new_name);

										// Thumb name
										$inp_thumb = str_replace(".$extension", "", $new_name);
										if($inp_names_array[$x] == "inp_image_h_a"){
											$inp_thumb = $inp_thumb . "_thumb_278x156." . $extension;
										}
										$inp_thumb_mysql = quote_smart($link, $inp_thumb);
														

										// Logo over image
										// Config
										include("$root/_admin/_data/recipes.php");
										if($recipesPrintLogoOnImagesSav == "1"){
											include("$root/_admin/_functions/stamp_image.php");
											include("$root/_admin/_data/logo.php");
											$stamp = "$logoFileStampImages1280x720Sav";
											list($width,$height) = getimagesize("$root/_uploads/recipes/$year/$get_recipe_id/$new_name");

											if($width < 1280){ // Width less than 1280
												$stamp = "$logoFileStampImages1280x720Sav";
											}
											elseif($width > 1280 && $width < 1920){  // Width bigger than 1280 and less than 1920
												$stamp = "$logoFileStampImages1920x1080Sav";
											}
											elseif($width > 1921 && $width < 2560){
												$stamp = "$logoFileStampImages2560x1440Sav";
											}
											else{
												$stamp = "$logoFileStampImages7680x4320Sav";
											}
											stamp_image("$root/_uploads/recipes/$year/$get_recipe_id/$new_name", "$root/$logoPathSav/$stamp");
										}

					

										if($inp_names_array[$x] == "inp_image_h_a"){
											$result = mysqli_query($link, "UPDATE $t_recipes SET 
											recipe_image_path=$inp_image_path_mysql, 
											recipe_image_h_a=$inp_image_mysql, 
											recipe_thumb_h_a_278x156=$inp_thumb_mysql, 
											recipe_user_ip=$my_ip_mysql 
											WHERE recipe_id=$recipe_id_mysql");
										
											$ft_image_h_a = "success";
											$fm_image_h_a = "image_uploaded";
										}
										elseif($inp_names_array[$x] == "inp_image_v_a"){
										
											$result = mysqli_query($link, "UPDATE $t_recipes SET 
											recipe_image_path=$inp_image_path_mysql, 
											recipe_image_v_a=$inp_image_mysql, 
											recipe_user_ip=$my_ip_mysql 
											WHERE recipe_id=$recipe_id_mysql");

											$ft_image_v_a = "success";
											$fm_image_v_a = "image_uploaded";
										}
										
									}  // if($width == "" OR $height == ""){
								} // move_uploaded_file
								else{
									switch ($_FILES["$inp_names_array[$x]"]['error']) {
									case UPLOAD_ERR_OK:
           									if($inp_names_array[$x] == "inp_image_h_a"){
											$fm_image_h_a = "There is no error, the file uploaded with success.";
											$ft_image_h_a = "info";
										}
           									elseif($inp_names_array[$x] == "inp_image_v_a"){
											$fm_image_v_a = "There is no error, the file uploaded with success.";
											$ft_image_v_a = "info";
										}
										break;
									case UPLOAD_ERR_NO_FILE:
           									// $fm_image = "no_file_uploaded";
										break;
									case UPLOAD_ERR_INI_SIZE:
           									if($inp_names_array[$x] == "inp_image_h_a"){
           										$fm_image_h_a = "to_big_size_in_configuration";
											$ft_image_h_a = "error";
										}
           									elseif($inp_names_array[$x] == "inp_image_v_a"){
											$fm_image_v_a = "to_big_size_in_configuration";
											$ft_image_v_a = "error";
										}
										break;
									case UPLOAD_ERR_FORM_SIZE:
           									if($inp_names_array[$x] == "inp_image_h_a"){
           										$fm_image_h_a = "to_big_size_in_form";
											$ft_image_h_a = "error";
										}
           									elseif($inp_names_array[$x] == "inp_image_v_a"){
											$fm_image_v_a = "to_big_size_in_form";
											$ft_image_v_a = "error";
										}
										break;
									default:
           									if($inp_names_array[$x] == "inp_image_h_a"){
           										$fm_image_h_a = "unknown_error";
											$ft_image_h_a = "error";
										}
           									elseif($inp_names_array[$x] == "inp_image_v_a"){
											$fm_image_v_a = "unknown_error";
											$ft_image_v_a = "error";
										}
										break;
									} // switch	
								}
	
							} // extension check
						} // if($image){
	
					} // for upload images

					// Feedback
					$url = "edit_recipe_image.php?recipe_id=$recipe_id&l=$l";
					if($ft_image_h_a != ""){
						$url = $url . "&ft_image_h_a=$ft_image_h_a&fm_image_h_a=$fm_image_h_a";
					}
					if($ft_image_v_a != ""){
						$url = $url . "&ft_image_v_a=$ft_image_v_a&fm_image_v_a=$fm_image_v_a";
					}

					header("Location: $url");
					exit;
				} // if($process == 1){
				echo"
				<h1>$get_recipe_title</h1>


				<!-- You are here -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"my_recipes.php?l=$l#recipe_id=$recipe_id\">$l_my_recipes</a>
			&gt;
			<a href=\"view_recipe.php?recipe_id=$recipe_id&amp;l=$l\">$get_recipe_title</a>
			&gt;
			<a href=\"edit_recipe_image.php?recipe_id=$recipe_id&amp;l=$l\">$l_image</a>
			</p>
				<!-- //You are here -->
		

				<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"edit_recipe.php?recipe_id=$recipe_id&amp;l=$l\">$l_general</a></li>
				<li><a href=\"edit_recipe_ingredients.php?recipe_id=$recipe_id&amp;l=$l\">$l_ingredients</a></li>
				<li><a href=\"edit_recipe_categorization.php?recipe_id=$recipe_id&amp;l=$l\">$l_categorization</a></li>
				<li><a href=\"edit_recipe_image.php?recipe_id=$recipe_id&amp;l=$l\" class=\"active\">$l_image</a></li>
				<li><a href=\"edit_recipe_video.php?recipe_id=$recipe_id&amp;l=$l\">$l_video</a></li>
				<li><a href=\"edit_recipe_tags.php?recipe_id=$recipe_id&amp;l=$l\">$l_tags</a></li>
				<li><a href=\"edit_recipe_links.php?recipe_id=$recipe_id&amp;l=$l\">$l_links</a></li>
			</ul>
		</div><p>&nbsp;</p>
				<!-- //Menu -->
	
				
				<!-- Images -->
					<form method=\"post\" action=\"edit_recipe_image.php?recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
					<!-- Image H -->

						<p><a id=\"image_h_a\"></a>
						<b>$l_website_image (1920x1080 jpg):</b><br />
						<input type=\"file\" name=\"inp_image_h_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
						";
						if(isset($_GET['ft_image_h_a']) && isset($_GET['fm_image_h_a'])){
							$ft = $_GET['ft_image_h_a'];
							$ft = output_html($ft);
							if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
								echo"Server error 403 feedback error";die;
							}

							$fm = $_GET['fm_image_h_a'];
							$fm = output_html($fm);
							$fm = str_replace("_", " ", $fm);
							$fm = ucfirst($fm);
							echo"<div class=\"$ft\"><p>$fm</p></div>\n";
						}
						if($get_recipe_image_h_a  != ""){
							if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a"))){
								echo"<div class=\"info\"><p>Missing image...</p></div>\n";
								$result = mysqli_query($link, "UPDATE $t_recipes SET 
											recipe_image_h_a='', 
											recipe_image_h_a_thumb_278x156='', 
											recipe_user_ip=$my_ip_mysql 
											WHERE recipe_id=$recipe_id_mysql");
							}
							echo"
							<p>
							<img src=\"$root/$get_recipe_image_path/$get_recipe_image_h_a\" alt=\"$get_recipe_image_h_a\" /><br />
							<a href=\"edit_recipe_image.php?action=rotate_image&amp;recipe_id=$get_recipe_id&amp;image=h_a&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
							<hr />
							</p>
							";
						}
					echo"		
					<!-- //Image H-->

					<!-- Image V -->
						<p><a id=\"image_v_a\"></a>
						<b>$l_mobile_image (1080x1920 jpg):</b><br />
						<input type=\"file\" name=\"inp_image_v_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
						";
						if(isset($_GET['ft_image_v_a']) && isset($_GET['fm_image_v_a'])){
							$ft = $_GET['ft_image_v_a'];
							$ft = output_html($ft);
							if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
								echo"Server error 403 feedback error";die;
							}

							$fm = $_GET['fm_image_v_a'];
							$fm = output_html($fm);
							$fm = str_replace("_", " ", $fm);
							$fm = ucfirst($fm);
							echo"<div class=\"$ft\"><p>$fm</p></div>\n";
						}
						if($get_recipe_image_v_a  != ""){
							if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_image_v_a"))){
								echo"<div class=\"info\"><p>Missing image...</p></div>\n";
								$result = mysqli_query($link, "UPDATE $t_recipes SET 
											recipe_image_v_a='', 
											recipe_image_v_a_thumb_281x500='', 
											recipe_user_ip=$my_ip_mysql 
											WHERE recipe_id=$recipe_id_mysql");
							}
							echo"
							<p>
							<img src=\"$root/$get_recipe_image_path/$get_recipe_image_v_a\" alt=\"$get_recipe_image_v_a\" /><br />
							<a href=\"edit_recipe_image.php?action=rotate_image&amp;recipe_id=$get_recipe_id&amp;image=v_a&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
							</p>";
						}
					echo"		
					<!-- //Image V-->

					<p>
					<input type=\"submit\" value=\"$l_upload_image\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
					</form>
				<!-- //Images -->



				";
			} // action == ""
			elseif($action == "rotate_image"){

				if($process == 1){
				
					// Delete all old thumbnails
					if(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156") && $get_recipe_thumb_h_a_278x156 != ""){
						unlink("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156");
					}
				
					// Check if exists
					if($image == "h_a"){
						if($get_recipe_image_h_a == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a"))){
							$url = "edit_recipe_image.php?recipe_id=$recipe_id&l=$l&ft_image_h_a=error&fm_image_h_a=image_doesnt_exist";
							header("Location: $url");
							exit;
						}
					}
					elseif($image == "v_a"){
						if($get_recipe_image_v_a == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_image_v_a"))){
							$url = "edit_recipe_image.php?recipe_id=$recipe_id&l=$l&ft_image_v_a=error&fm_image_v_a=image_doesnt_exist";
							header("Location: $url");
							exit;
						}
					}
					else{
						echo"Unknwn image";
						die;
					}

				// Give new name
				$extension = get_extension($get_recipe_image_h_a);
				if($image == "v_a"){
					$extension = get_extension($get_recipe_image_v_a);
				}

				$recipe_title_clean = clean($get_recipe_title);
				$random = rand(0,9999);

				$inp_recipe_image = "";

				$inp_name = $recipe_title_clean . "_" . "$image" . "_" . $random . "." . $extension;
					
				// Roate it 

				if($image == "h_a"){
					// Load
					$source = imagecreatefromjpeg("$root/$get_recipe_image_path/$get_recipe_image_h_a");
					unlink("$root/$get_recipe_image_path/$get_recipe_image_h_a");
					$original_x = imagesx($source);
					$original_y = imagesy($source);

					$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
   
					// Rotate
   					$rotate = imagerotate($source, 270, $bgColor);
   					imagesavealpha($rotate, true);
   					imagejpeg($rotate, "$root/$get_recipe_image_path/$inp_name");

					// Free memory
					imagedestroy($source);
					imagedestroy($rotate); 

					// Update
					$inp_image_mysql = quote_smart($link, $inp_name);

					$inp_thumb = $recipe_title_clean . "_" . "$image" . "_" . $random . "_thumb_278x156" . $extension;
					$inp_thumb_mysql = quote_smart($link, $inp_thumb);
					mysqli_query($link, "UPDATE $t_recipes SET 
									recipe_image_h_a=$inp_image_mysql, 
									recipe_image_h_a_thumb_278x156=$inp_thumb_mysql, 
									recipe_user_ip=$my_ip_mysql 
									WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));


					// Header
					$url = "edit_recipe_image.php?recipe_id=$recipe_id&l=$l&ft_image_h_a=success&fm_image_h_a=image_rotated#image_h_a";
					header("Location: $url");
					exit;
				}
				elseif($image == "v_a"){
					// Load
					$source = imagecreatefromjpeg("$root/$get_recipe_image_path/$get_recipe_image_v_a");
					unlink("$root/$get_recipe_image_path/$get_recipe_image_v_a");
					$original_x = imagesx($source);
					$original_y = imagesy($source);

					$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
   
					// Rotate
   					$rotate = imagerotate($source, 270, $bgColor);
   					imagesavealpha($rotate, true);
   					imagejpeg($rotate, "$root/$get_recipe_image_path/$inp_name");

					// Free memory
					imagedestroy($source);
					imagedestroy($rotate); 

					// Update
					$inp_image_mysql = quote_smart($link, $inp_name);

					mysqli_query($link, "UPDATE $t_recipes SET 
									recipe_image_v_a=$inp_image_mysql, 
									recipe_user_ip=$my_ip_mysql 
									WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));

						// Header
						$url = "edit_recipe_image.php?recipe_id=$recipe_id&l=$l&ft_image_v_a=success&fm_image_v_a=image_rotated#image_v_a";
						header("Location: $url");
						exit;
					}
				

				} // process
			} // action == "rotate"
		} // is owner or admin
		else{
			echo"<p>Server error 403</p>
			<p>Only the owner and admin can edit the recipe</p>
			";
		}
	} // Isset user id
	else{
		echo"
		<h1>Log in</h1>
		<p><a href=\"$root/users/login.php?l=$l\">Please log in</a>
		</p>
		";
	}
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>