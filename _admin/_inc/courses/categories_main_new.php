<?php
/**
*
* File: _admin/_inc/courses/new_main_category.php
* Version 
* Date 21:34 12.09.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_modules_contents 	 = $mysqlPrefixSav . "courses_modules_contents";
$t_courses_modules_contents_read = $mysqlPrefixSav . "courses_modules_contents_read";
$t_courses_modules_contents_comments	= $mysqlPrefixSav . "courses_modules_contents_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";


/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


if($action == ""){


	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_webdesign = $_POST['inp_webdesign'];
		$inp_webdesign = output_html($inp_webdesign);
		$inp_webdesign_mysql = quote_smart($link, $inp_webdesign);



		$datetime = date("Y-m-d H:i:s");
		
		mysqli_query($link, "INSERT INTO $t_courses_categories_main
		(main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_webdesign, main_category_created, main_category_updated) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_title_clean_mysql, '', $inp_language_mysql, $inp_webdesign_mysql, '$datetime', '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT main_category_id FROM $t_courses_categories_main WHERE main_category_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id) = $row;




		// Folder
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/courses"))){
			mkdir("../_uploads/courses");
		}
		if(!(is_dir("../_uploads/courses/main"))){
			mkdir("../_uploads/courses/main");
		}
		if(!(is_dir("../_uploads/courses/main/$inp_title_clean"))){
			mkdir("../_uploads/courses/main/$inp_title_clean");
		}
		if(!(is_dir("../_uploads/courses/main/$inp_title_clean/_gfx"))){
			mkdir("../_uploads/courses/main/$inp_title_clean/_gfx");
		}
		if(!(is_dir("../_uploads/courses/main/$inp_title_clean/_gfx/icons"))){
			mkdir("../_uploads/courses/main/$inp_title_clean/_gfx/icons");
		}
		

		$upload_path = "../_uploads/courses/main/$inp_title_clean/_gfx/icons";


		$ft_icon = "info";
           	$fm_icon = "nothing";
		$icon_sizes = array('16', '32', '192');
		for($x=0;$x<sizeof($icon_sizes);$x++){
		
			
			$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				

			$image_name = stripslashes($_FILES["inp_icon_$icon_size"]['name']);
			$extension = get_extension($image_name);
			$extension = strtolower($extension);

			if($image_name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_icon = "warning";
					$fm_icon = "unknown_file_extension_$extension";
				}
				else{
					// Give new name
					$inp_name = $inp_title_clean . "_icon_" . $icon_size . ".$extension";
					$uploaded_file = $upload_path . "/" . $inp_name;

					// Upload file
					if (move_uploaded_file($_FILES["inp_icon_$icon_size"]['tmp_name'], $uploaded_file)) {

						$inp_icon_path = "$inp_title_clean/_gfx/icons";


						// Get image size
						$file_size = filesize($uploaded_file);
						
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							unlink("$uploaded_file");
							$ft_icon = "warning";
							$fm_icon = "getimagesize_failed";
						}
						else{
							// All ok
							$inp_icon_mysql = quote_smart($link, $inp_name);

							if($icon_sizes[$x] == "16"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_16x16=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "18"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_18x18=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "24"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_24x24=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "32"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_32x32=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "36"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_36x36=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "48"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_48x48=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "96"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_96x96=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "192"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_192x192=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}
							if($icon_sizes[$x] == "260"){
								$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_icon_path='$inp_icon_path', 
										main_category_icon_260x260=$inp_icon_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							}

							$ft_icon = "success";
							$fm_icon = "icon_uploaded";
						}
					}
					else{
						switch ($_FILES['inp_food_image']['error']) {
							case UPLOAD_ERR_OK:
          							$fm_icon = "There is no error, the file uploaded with success.";
								break;
							case UPLOAD_ERR_NO_FILE:
           							$fm_icon = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
          							$fm_icon = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
          							$fm_icon = "to_big_size_in_form";
								break;
							default:
          							$fm_icon = "unknown_error";
								break;
						}	
						$ft_icon = "warning";
					
					}
				}
			}
		} // for icons

		// Header logo
		if(!(is_dir("../_uploads/courses/main/$inp_title_clean/_gfx/header"))){
			mkdir("../_uploads/courses/main/$inp_title_clean/_gfx/header");
		}
		$upload_path = "../$inp_title_clean/_gfx/header";
		$image_name = stripslashes($_FILES["inp_header_logo"]['name']);
		$extension = get_extension($image_name);
		$extension = strtolower($extension);

		if($image_name){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_icon = "warning";
				$fm_icon = "unknown_file_extension_$extension";
			}
			else{
				// Give new name
				$inp_name = $inp_title_clean . "_header_logo" . ".$extension";
				$uploaded_file = $upload_path . "/" . $inp_name;

				// Upload file
				if (move_uploaded_file($_FILES["inp_header_logo"]['tmp_name'], $uploaded_file)) {


					// Get image size
					$file_size = filesize($uploaded_file);
						
					// Check with and height
					list($width,$height) = getimagesize($uploaded_file);
	
					if($width == "" OR $height == ""){
						unlink("$uploaded_file");
						$ft_icon = "warning";
						$fm_icon = "getimagesize_failed";
					}
					else{
						// All ok
						$inp_header_logo_mysql = quote_smart($link, $inp_name);

						$result = mysqli_query($link, "UPDATE $t_courses_categories_main SET 
										main_category_header_logo=$inp_header_logo_mysql
										WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
						

						$ft_icon = "success";
						$fm_icon = "icon_uploaded";
					}
				}
				else{
					switch ($_FILES['inp_food_image']['error']) {
						case UPLOAD_ERR_OK:
          						$fm_icon = "There is no error, the file uploaded with success.";
							break;
						case UPLOAD_ERR_NO_FILE:
           						$fm_icon = "no_file_uploaded";
							break;
						case UPLOAD_ERR_INI_SIZE:
          						$fm_icon = "to_big_size_in_configuration";
							break;
						case UPLOAD_ERR_FORM_SIZE:
          						$fm_icon = "to_big_size_in_form";
							break;
						default:
          						$fm_icon = "unknown_error";
							break;
					}	
					$ft_icon = "warning";
				}
			}
		}


		// Get all information
		$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_icon_path, main_category_icon_16x16, main_category_icon_18x18, main_category_icon_24x24, main_category_icon_32x32, main_category_icon_36x36, main_category_icon_48x48, main_category_icon_96x96, main_category_icon_260x260, main_category_header_logo, main_category_webdesign, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_main_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_icon_path, $get_current_main_category_icon_16x16, $get_current_main_category_icon_18x18, $get_current_main_category_icon_24x24, $get_current_main_category_icon_32x32, $get_current_main_category_icon_36x36, $get_current_main_category_icon_48x48, $get_current_main_category_icon_96x96, $get_current_main_category_icon_260x260, $get_current_main_category_header_logo, $get_current_main_category_webdesign, $get_current_main_category_created, $get_current_main_category_updated) = $row;


		// Header
		$url = "index.php?open=$open&page=categories_main&editor_language=$editor_language&ft=success&fm=category_created&ft_icon=$ft_icon&fm_icon=$fm_icon";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>New main category</h1>
				

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




	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=categories_main_new&amp;editor_language=$editor_language&amp;l=$l\">New category</a>
		</p>
	<!-- //Where am I? -->


	<p style=\"padding-bottom:0;margin-bottom:0;\"><b>About main categories:</b></p>
	<ul>
		<li><p>When you create a new main folder it will be saved to the database;.</p></li>
		<li><p>Icons will be uploaded to &quot;../_uploads/courses/main/{title}/_gfx/icons&quot;.</p></li>
		<li><p>Header image will be uploaded to &quot;../_uploads/courses/main/{title}/_gfx/header&quot;.</p></li>
	</ul>
	

	<!-- New course form -->
		
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
			echo"	<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>


		<p><b>Webdesign:</b><br />
		<select name=\"inp_webdesign\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"same_as_website\">Same as website</option>\n";
		$path = "../_webdesign";
		if(!(is_dir("$path"))){
			echo"$path doesnt exists";
			die;
		}
		if ($handle = opendir($path)) {
			$x = 0;
			while (false !== ($webdesign_name = readdir($handle))) {
				if ($webdesign_name === '.') continue;
				if ($webdesign_name === '..') continue;
				if ($webdesign_name === 'images') continue;
				if ($webdesign_name === '_other_designs') continue;
				if(is_dir("$path/$webdesign_name")){
					echo"	<option value=\"$webdesign_name\">$webdesign_name</option>\n";
				}
			}
		}
		echo"
		</select>
		</p>

		
		<!-- Icon 48, 64, 96 -->
			";

			$icon_sizes = array('16', '32', '192'); // '16', '18', '24', '32', '36', '48', '96', '192', '260'
			for($x=0;$x<sizeof($icon_sizes);$x++){
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				echo"
				<!-- Icon x -->
					
					<table>
					 <tr>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						<p><b>New icon ($icon_size)</b><br />
						<input type=\"file\" name=\"inp_icon_$icon_size\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					  </td>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
					
					  </td>
					 </tr>
					</table>
					
				<!-- //Icon x -->
				";
			}
			echo"
	
		<!-- //Icon 48, 64, 96 -->

		<!-- Header logo -->
			<table>
			 <tr>
			  <td style=\"vertical-align:top;padding-right: 20px;\">
				<p><b>Header logo</b><br />
				<input type=\"file\" name=\"inp_header_logo\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
			  </td>
			  <td style=\"vertical-align:top;padding-right: 20px;\">
					
			  </td>
			 </tr>
			</table>
				
			
	
		<!-- //Header logo -->



		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>
	<!-- //New course form -->
	";
}
?>