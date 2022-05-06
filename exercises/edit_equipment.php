<?php 
/**
*
* File: food/edit_equipment.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_equipment.php");
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['equipment_id'])){
	$equipment_id = $_GET['equipment_id'];
	$equipment_id = output_html($equipment_id);
}
else{
	$equipment_id = "";
}
/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$_edit_equipment - $l_my_equipment - $l_exercises"
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
				delete_directory($dirname.'/'.$file);    
			}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}


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
		if($action == ""){
			if($process == 1){

				// 1. Title
				$inp_equipment_title = $_POST['inp_equipment_title'];
				$inp_equipment_title = output_html($inp_equipment_title);
				$inp_equipment_title_mysql = quote_smart($link, $inp_equipment_title);
		
				$inp_equipment_title_clean = clean($inp_equipment_title);
				$inp_equipment_title_clean_mysql = quote_smart($link, $inp_equipment_title_clean);
				if(empty($inp_equipment_title)){
					$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l";
					$url = $url . "&ft=error&fm=missing_title";
					header("Location: $url");
					exit;
				}
				else{
					// Same as before?
					if($inp_equipment_title != "$get_equipment_title"){
						// Do we have it ?
						$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_title=$inp_equipment_title_mysql AND equipment_language=$l_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_check_equipment_id) = $row;
						if($get_check_equipment_id != ""){
							
							// Header
							$ft = "error";
							$fm = "there_are_already_a_equipment_with_that_title";
							$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l";
							$url = $url . "&ft=$ft&fm=$fm";
							header("Location: $url");
							exit;
						}
						else{
							// Update
							$result = mysqli_query($link, "UPDATE $t_exercise_equipments SET equipment_title=$inp_equipment_title_mysql, 
							equipment_title_clean=$inp_equipment_title_clean_mysql WHERE equipment_id=$equipment_id_mysql");
						}
					}
				}


				// 2. Group

			
				// Find sub
				$inp_exercise_muscle_group_id_sub = $_POST['inp_exercise_muscle_group_id_sub'];
				$inp_exercise_muscle_group_id_sub = output_html($inp_exercise_muscle_group_id_sub);
				$inp_exercise_muscle_group_id_sub_mysql = quote_smart($link, $inp_exercise_muscle_group_id_sub);
				if(empty($inp_exercise_muscle_group_id_sub)){
					$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l";
					header("Location: $url");
					exit;
				}
		
				$query = "SELECT muscle_group_id, muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id=$inp_exercise_muscle_group_id_sub_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_sub_muscle_group_id, $get_sub_muscle_group_parent_id) = $row;
				if($get_sub_muscle_group_id == ""){
					$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l";
					header("Location: $url");
					exit;
				}
			
				// Find main
				$query = "SELECT muscle_group_id, muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id='$get_sub_muscle_group_parent_id'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_main_muscle_group_id, $get_muscle_group_parent_id) = $row;
				if($get_main_muscle_group_id == ""){

					$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l";
					header("Location: $url");
					exit;
				}
			
				
				// Update
				$result = mysqli_query($link, "UPDATE $t_exercise_equipments SET equipment_muscle_group_id_main=$get_main_muscle_group_id,
							equipment_muscle_group_id_sub=$get_sub_muscle_group_id WHERE equipment_id=$equipment_id_mysql");



	
				// 3. Text

				// Purifier
				require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
				$config = HTMLPurifier_Config::createDefault();
				$purifier = new HTMLPurifier($config);

				if($get_user_rank == "admin" OR $get_user_rank == "moderator" OR $get_user_rank == "editor"){
				}
				elseif($get_user_rank == "trusted"){
				}
				else{
					// p, ul, li, b
					$config->set('HTML.Allowed', 'p,b,a[href],i,ul,li');
				}

				$inp_equipment_text = $_POST['inp_equipment_text'];
				$inp_equipment_text = $purifier->purify($inp_equipment_text);
			


				$sql = "UPDATE $t_exercise_equipments SET equipment_text=? WHERE equipment_id=$equipment_id_mysql";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("s", $inp_equipment_text);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}

			
				// Search engine
				$reference_name_mysql = quote_smart($link, "equipment_id");
				$reference_id_mysql = quote_smart($link, "$get_equipment_id");
				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='exercises' AND index_reference_name=$reference_name_mysql AND index_reference_id=$reference_id_mysql";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;
				if($get_index_id != ""){
					$inp_index_title_mysql = quote_smart($link, $inp_equipment_title);

					$inp_index_short_description = substr($inp_equipment_text, 0, 200);
					$inp_index_short_description = output_html($inp_index_short_description);
					$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);
					$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
									index_title=$inp_index_title_mysql,
									index_short_description=$inp_index_short_description_mysql WHERE index_id=$get_index_id") or die(mysqli_error($link));
				}




				// 4. Image
				
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
								$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
								header("Location: $url");
								exit;
							}
							if($height < 839){
								unlink($target_path);
								$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
								header("Location: $url");
								exit;
							}

							// Dette bildet er OK



							
							$inp_equipment_image_path  = "_uploads/exercises/$l/equipment/$get_equipment_title_clean";
							$inp_equipment_image_path_mysql = quote_smart($link, $inp_equipment_image_path);

							// recipe_image
							$inp_equipment_image_file = $new_name;
							$inp_equipment_image_file_mysql = quote_smart($link, $inp_equipment_image_file);

							// Dette bildet er OK
							// Resize it
							$inp_new_x = 850;
							$inp_new_y = 840;
							resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_equipment_image_path/$inp_equipment_image_file", "$root/$inp_equipment_image_path/$inp_equipment_image_file");
					
					
					

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_exercise_equipments SET equipment_image_path=$inp_equipment_image_path_mysql,
							equipment_image_file=$inp_equipment_image_file_mysql WHERE equipment_id=$equipment_id_mysql");



	
							// Header
							$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=success&fm=image_uploaded";
							header("Location: $url");
							exit;
					
						}
						else{
							// Dette er en fil som har fått byttet filendelse...
							unlink("$target_path");

							$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=file_is_not_an_image";
							header("Location: $url");
							exit;
						}
					}
					else{
   						switch ($_FILES['inp_image'] ['error']){
							case 1:
								$url = "edit_equipment.php?equipment_id=$equipment_id&l=$ll&ft=error&fm=to_big_file";
								header("Location: $url");
								exit;
								break;
							case 2:
								$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=to_big_file";
								header("Location: $url");
								exit;
								break;
							case 3:
								$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=only_parts_uploaded";
								header("Location: $url");
								exit;
								break;
							case 4:
								$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=no_file_uploaded";
								header("Location: $url");
								exit;
								break;
						}
					} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
				}
				else{
					//$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
					//header("Location: $url");
					//exit;
				}
				
				$url = "edit_equipment.php?equipment_id=$equipment_id&l=$l&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			} // process


			echo"
			<h1>$get_equipment_title</h1>
	
			
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
				elseif($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->


			<!-- TinyMCE -->
			
				<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
						'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
						'//www.tiny.cloud/css/codepen.min.css'
					],
					link_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
					image_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
						image_class_list: [
						{ title: 'None', value: '' },
						{ title: 'Some class', value: 'class-name' }
					],
					importcss_append: true,
					height: 400,
					file_picker_callback: function (callback, value, meta) {
						/* Provide file and text for the link dialog */
						if (meta.filetype === 'file') {
							callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
						}
						/* Provide image and alt text for the image dialog */
						if (meta.filetype === 'image') {
							callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
						}
						/* Provide alternative source and posted for the media dialog */
						if (meta.filetype === 'media') {
							callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
						}
					}
				});
				</script>
			<!-- //TinyMCE -->
			<!-- Form -->

			<form method=\"post\" action=\"edit_equipment.php?equipment_id=$equipment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_equipment_title\"]').focus();
			});
			</script>

			<p><b>$l_title*:</b><br />
			<input type=\"text\" name=\"inp_equipment_title\" size=\"40\" value=\"$get_equipment_title\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>$l_muscle_group:</b><br />
			<select name=\"inp_exercise_muscle_group_id_sub\">
				<option value=\"0\">- $l_please_select - </option>
				<option value=\"\"></option>\n";
				// Get groups
				$query_main = "SELECT muscle_group_id, muscle_group_name FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
				$result_main = mysqli_query($link, $query_main);
				while($row_main = mysqli_fetch_row($result_main)) {
					list($get_main_muscle_group_id, $get_main_muscle_group_name) = $row_main;
					// Translation
					$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;

					echo"				";
					echo"<option value=\"$get_main_muscle_group_id\">$get_main_muscle_group_translation_name</option>\n";
			
					// Get sub categories
					$query_sub = "SELECT muscle_group_id, muscle_group_name FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_main_muscle_group_id'";
					$result_sub = mysqli_query($link, $query_sub);
					while($row_sub = mysqli_fetch_row($result_sub)) {
						list($get_sub_muscle_group_id, $get_sub_muscle_group_name) = $row_sub;
						// Translation
						$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
						$result_translation = mysqli_query($link, $query_translation);
						$row_translation = mysqli_fetch_row($result_translation);
						list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;

						echo"				";
						echo"<option value=\"$get_sub_muscle_group_id\""; if($get_sub_muscle_group_id == "$get_equipment_muscle_group_id_sub"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</option>\n";
					}

					echo"				";
					echo"<option value=\"0\"> </option>\n";
				}
			echo"
			</select></p>




			<p><b>$l_text:</b><br />
			<textarea name=\"inp_equipment_text\" rows=\"10\" cols=\"70\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_equipment_text</textarea>
			</p>


			<p><b>$l_image:</b><br />
			";
			// Images
			if($get_equipment_image_file != "" && file_exists("$root/$get_equipment_image_path/$get_equipment_image_file")){
				echo"<img src=\"$root/image.php?width=80&amp;height=54&amp;image=/$get_equipment_image_path/$get_equipment_image_file\" alt=\"$get_equipment_image_file\" />\n";
			}
			echo"
			</p>
			
			<p><b>$l_new_image (840x840 jpg):</b><br />
			<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>


			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			</form>
		<!-- //Form -->
			";
		} // action == ""
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/my_exercises.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>