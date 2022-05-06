<?php
/**
*
* File: _admin/_inc/diet/categories.php
* Version 00.28 20.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_food_categories_main		  	= $mysqlPrefixSav . "food_categories_main";
$t_food_categories_main_translations	= $mysqlPrefixSav . "food_categories_main_translations";
$t_food_categories_sub		  	= $mysqlPrefixSav . "food_categories_sub";
$t_food_categories_sub_translations	= $mysqlPrefixSav . "food_categories_sub_translations";

/*- Functions -------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['language'])){
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "en";
}


if($action == ""){
	echo"
	<h1>Categories</h1>


	<p><a href=\"index.php?open=$open&amp;page=categories&amp;action=new_main_category&amp;editor_language=$editor_language\">New main category</a>
	|
	<a href=\"index.php?open=$open&amp;page=categories&amp;action=translations&amp;editor_language=$editor_language\">Translations</a>
	|
	<a href=\"index.php?open=$open&amp;page=categories&amp;action=sqlite_code_a&amp;editor_language=$editor_language\">SQLite code 1</a>
	|
	<a href=\"index.php?open=$open&amp;page=categories&amp;action=sqlite_code_b&amp;editor_language=$editor_language\">SQLite code 2</a>
	|
	<a href=\"index.php?open=$open&amp;page=categories&amp;action=strings&amp;editor_language=$editor_language\">Strings</a></p>
	
	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "Changes saved";
			}
			else{
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
	echo"
	<!-- //Feedback -->

	<!-- Main categories -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Category ID</b></span>
		  </td>
		   <th scope=\"col\">
			<span><b>Name</b></span>
		  </td>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		  </td>
		  </tr>
		 </thead>";
		// Get all categories
		$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_name) = $row;
			
	
			echo"
			 <tr>
			  <td>
				<span>$get_main_category_id</span>
			  </td>
			  <td>
				<span><a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$get_main_category_id&amp;language=$language\">$get_main_category_name</a></span>
			  </td>
			  <td>
				<span>
				<a href=\"index.php?open=$open&amp;page=categories&amp;action=edit_main_category&amp;category_id=$get_main_category_id&amp;language=$language\">Edit</a>
				|
				<a href=\"index.php?open=$open&amp;page=categories&amp;action=delete_main_category&amp;category_id=$get_main_category_id&amp;language=$language\">Delete</a>
				</span>
			  </td>
			 </tr>";
		}

		echo"
		</table>
	<!-- //Main categories -->
	";

	// Delete sub categories not in use
	$query_s = "SELECT sub_category_id, sub_category_name, sub_category_parent_id FROM $t_food_categories_sub ORDER BY sub_category_name ASC";
	$result_s = mysqli_query($link, $query_s);
	while($row_s = mysqli_fetch_row($result_s)) {
		list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $row_s;

		// Check for main
		$query = "SELECT main_category_id FROM $t_food_categories_main WHERE main_category_id=$get_sub_category_parent_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_main_category_id) = $row;
		if($get_main_category_id == ""){
			echo"<p>Deleted unused sub category $get_sub_category_name</p>";
		}
	}

	// Delete main translations not in use 
	$query_s = "SELECT main_category_translation_id, main_category_id, main_category_translation_value FROM $t_food_categories_main_translations";
	$result_s = mysqli_query($link, $query_s);
	while($row_s = mysqli_fetch_row($result_s)) {
		list($get_main_category_translation_id, $get_main_category_id, $get_main_category_translation_value) = $row_s;

		// Check for main
		$query = "SELECT main_category_id FROM $t_food_categories_main WHERE main_category_id=$get_main_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_main_category_id) = $row;
		if($get_main_category_id == ""){
			echo"<p>Deleted unused main translation for with value $get_main_category_translation_value</p>";
			mysqli_query($link, "DELETE FROM $t_food_categories_main_translations WHERE main_category_translation_id=$get_main_category_translation_id");
		}
	}

	// Delete sub translations not in use 
	$query_s = "SELECT sub_category_translation_id, sub_category_id, sub_category_translation_value FROM $t_food_categories_sub_translations";
	$result_s = mysqli_query($link, $query_s);
	while($row_s = mysqli_fetch_row($result_s)) {
		list($get_sub_category_translation_id, $get_sub_category_id, $get_sub_category_translation_value) = $row_s;

		// Check for sub
		$query = "SELECT sub_category_id FROM $t_food_categories_sub WHERE sub_category_id=$get_sub_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_sub_category_id) = $row;
		if($get_sub_category_id == ""){
			echo"<p>Deleted unused sub translation for with value $get_sub_category_translation_value</p>";
			mysqli_query($link, "DELETE FROM $t_food_categories_sub_translations WHERE sub_category_translation_id=$get_sub_category_translation_id");
		}
	}
}
elseif($action == "open_main_category" && isset($_GET['category_id'])){
	
	// Get variables
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	$category_id_mysql = quote_smart($link, $category_id);


	// Select main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit, main_category_updated_by_user_id, main_category_updated, main_category_note FROM $t_food_categories_main WHERE main_category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit, $get_current_main_category_updated_by_user_id, $get_current_main_category_updated, $get_current_main_category_note) = $row;

	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a></p>
		";
	}
	else{
		echo"
		<h1>$get_current_main_category_name</h1>

		<p>
		<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_name</a>
		</p>

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "Changes saved";
			}
			else{
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"
		<!-- //Feedback -->
		
		<!-- Buttons -->
			<p><a href=\"index.php?open=$open&amp;page=categories&amp;action=new_sub_category&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New sub category</a></p>
		<!-- //Buttons -->

		<!-- Sub categories -->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span><b>Category ID</b></span>
			   </td>
			   <th scope=\"col\">
				<span><b>Name</b></span>
			   </td>
			   <th scope=\"col\">
				<span><b>Actions</b></span>
			  </td>
			  </tr>
			 </thead>";

			// Get all categories
			$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_icon_path, sub_category_icon_inactive_32x32, sub_category_icon_active_32x32, sub_category_icon_inactive_48x48, sub_category_icon_active_48x48, sub_category_age_limit, sub_category_updated_by_user_id, sub_category_updated, sub_category_note FROM $t_food_categories_sub WHERE sub_category_parent_id=$get_current_main_category_id ORDER BY sub_category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id, $get_sub_category_symbolic_link_to_category_id, $get_sub_category_icon_path, $get_sub_category_icon_inactive_32x32, $get_sub_category_icon_active_32x32, $get_sub_category_icon_inactive_48x48, $get_sub_category_icon_active_48x48, $get_sub_category_age_limit, $get_sub_category_updated_by_user_id, $get_sub_category_updated, $get_sub_category_note) = $row;

		
				echo"
					 <tr>
					  <td>
						<span>$get_sub_category_id</span>
					  </td>
					  <td>
						<span>$get_sub_category_name</span>
				 	 </td>
				 	 <td>
						<span>
						<a href=\"index.php?open=$open&amp;page=categories&amp;action=edit_sub_category&amp;category_id=$get_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
						|
						<a href=\"index.php?open=$open&amp;page=categories&amp;action=delete_sub_category&amp;category_id=$get_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
						</span>
					  </td>
					 </tr>";
			}

			echo"
			</table>
		<!-- //Sub categories -->
		";
	}
} // open main category
elseif($action == "edit_main_category" && isset($_GET['category_id'])){
	
	// Get variables
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	$category_id_mysql = quote_smart($link, $category_id);

	// Select main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit, main_category_updated_by_user_id, main_category_updated, main_category_note FROM $t_food_categories_main WHERE main_category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit, $get_current_main_category_updated_by_user_id, $get_current_main_category_updated, $get_current_main_category_note) = $row;

	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a></p>
		";
	}
	else{
		if($process == "1"){
			$datetime = date("Y-m-d H:i:s");

			$inp_category_name = $_POST['inp_category_name'];
			$inp_category_name = output_html($inp_category_name);
			$inp_category_name_mysql = quote_smart($link, $inp_category_name);


			$inp_category_age_limit = $_POST['inp_category_age_limit'];
			$inp_category_age_limit = output_html($inp_category_age_limit);
			$inp_category_age_limit_mysql = quote_smart($link, $inp_category_age_limit);


			// Update
			$result = mysqli_query($link, "UPDATE $t_food_categories_main SET 
							main_category_name=$inp_category_name_mysql,
							main_category_age_limit=$inp_category_age_limit_mysql,
							main_category_updated_by_user_id=$my_user_id_mysql,
							main_category_updated='$datetime'
							 WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));

			// Update translation english
			$result = mysqli_query($link, "UPDATE $t_food_categories_main_translations SET 
							main_category_translation_value=$inp_category_name_mysql
							 WHERE main_category_id=$get_current_main_category_id AND main_category_translation_language='en'") or die(mysqli_error($link));
			

			// Dirs
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/food"))){
				mkdir("../_uploads/food");
			}
			if(!(is_dir("../_uploads/food/categories"))){
				mkdir("../_uploads/food/categories");
			}

			// Icons
			$inp_title_clean = clean($inp_category_name);
			$sizes_array = array("32x32", "48x48");
			$types_array = array("inactive", "active");

			$ft_image = "";
			$fm_image = "";

			for($x=0;$x<sizeof($sizes_array);$x++){
				for($y=0;$y<sizeof($types_array);$y++){
					$inp_file = "inp_icon_" . $types_array[$y] . "_" . $sizes_array[$x];


					$name = stripslashes($_FILES["inp_icon_" . $types_array[$y] . "_" . $sizes_array[$x]]['name']);
					$name = output_html($name);
					$extension = get_extension($name);
					$extension = strtolower($extension);

					if($name){
						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft_image = "warning";
							$fm_image = "unknown_file_extension";
						}
						else{
							// Delete old icon
							$icon_file = "";
							if($sizes_array[$x] == "32x32" && $types_array[$y] == "inactive"){
								$icon_file = "$get_current_category_icon_inactive_32x32";
							}
							elseif($sizes_array[$x] == "32x32" && $types_array[$y] == "active"){
								$icon_file = "$get_current_category_icon_active_32x32";
							}
							elseif($sizes_array[$x] == "48x48" && $types_array[$y] == "inactive"){
								$icon_file = "$get_current_category_icon_inactive_48x48";
							}
							elseif($sizes_array[$x] == "48x48" && $types_array[$y] == "active"){
								$icon_file = "$get_current_category_icon_active_48x48";
							}

							if(file_exists("../$get_current_category_icon_path/$icon_file") && $icon_file != ""){
								unlink("../$get_current_category_icon_path/$icon_file");
							}


							$new_path = "../_uploads/food/categories/";
							$uploaded_file = $new_path . $inp_title_clean . "_" . $types_array[$y] . "_" . $sizes_array[$x] . "." . $extension;

							// Upload file
							if (move_uploaded_file($_FILES["$inp_file"]['tmp_name'], $uploaded_file)) {
	

								// Get image size
								$file_size = filesize($uploaded_file);
						
								// Check with and height
								list($width,$height) = getimagesize($uploaded_file);
	
								if($width == "" OR $height == ""){
									$ft_image = "warning";
									$fm_image = "getimagesize_failed";
									unlink($uploaded_file);
								}
								else{
									$ft_image = "success";
									$fm_image = "icon_uploaded";

									$field = "main_category_icon_" . $types_array[$y] . "_" . $sizes_array[$x];

									$inp_icon = $inp_title_clean . "_" . $types_array[$y] . "_" . $sizes_array[$x] . "." . $extension;
									$inp_icon_mysql = quote_smart($link, $inp_icon);

									$result = mysqli_query($link, "UPDATE $t_food_categories_main SET 
											main_category_icon_path='_uploads/food/categories',
											$field=$inp_icon_mysql
											WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
							
								}  // if($width == "" OR $height == ""){
							} // move_uploaded_file
							else{
							$ft_image = "warning";
							switch ($_FILES['inp_food_image']['error']) {
								case UPLOAD_ERR_OK:
           								$fm_image = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_image = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_image = "to_big_size_in_form";
									break;
								default:
           								$fm_image = "unknown_error";
									break;
							} // switch
							} // move uploaded file failed
						} // extension check
					
					} // if($image){

				
				} // for icons types array
			} // for icons array

			// Send success
			$url = "index.php?open=$open&page=categories&action=edit_main_category&category_id=$get_current_main_category_id&ft=success&fm=changes_saved&language=$language&ft_image=$ft_image&fm_image=$fm_image";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_current_main_category_name</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=categories&amp;language=$language\">Categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$get_current_main_category_id&amp;language=$language\">$get_current_main_category_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=$action&amp;category_id=$get_current_main_category_id&amp;language=$language\">Edit</a>			
			</p>
		<!-- //Where am I ? -->


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Edit main category form -->
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_category_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=categories&amp;action=edit_main_category&amp;category_id=$get_current_main_category_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_category_name\" value=\"$get_current_main_category_name\" size=\"40\" />
			</p>




			<p><b>Age limit:</b><br />
			<input type=\"radio\" name=\"inp_category_age_limit\" value=\"1\""; if($get_current_main_category_age_limit == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_category_age_limit\" value=\"0\""; if($get_current_main_category_age_limit == "0" OR $get_current_category_age_limit == ""){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p><b>Icon inactive 32x32 (Color)</b><br />\n";
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_inactive_32x32") && $get_current_main_category_icon_inactive_32x32 != ""){
				echo"<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_inactive_32x32\" alt=\"$get_current_main_category_icon_inactive_32x32\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_inactive_32x32\" />
			</p>

			<p><b>Icon active 32x32 (Grey color)</b><br />\n";
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_active_32x32") && $get_current_main_category_icon_active_32x32 != ""){
				echo"<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_active_32x32\" alt=\"$get_current_main_category_icon_active_32x32\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_active_32x32\" />
			</p>

			<p><b>Icon inactive 48x48 (Color)</b><br />\n";
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_inactive_48x48") && $get_current_main_category_icon_inactive_48x48 != ""){
				echo"<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_inactive_48x48\" alt=\"$get_current_main_category_icon_inactive_48x48\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_inactive_48x48\" />
			</p>

			<p><b>Icon active 48x48 (Grey color)</b><br />\n";
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_active_48x48") && $get_current_main_category_icon_active_48x48 != ""){
				echo"<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_active_48x48\" alt=\"$get_current_main_category_icon_active_48x48\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_active_48x48\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Save\" />
			</p>

			</form>
		<!-- //Edit main category form -->
		
		";
	}
} // edit main_category
elseif($action == "delete_main_category" && isset($_GET['category_id'])){
	
	// Get variables
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	$category_id_mysql = quote_smart($link, $category_id);

	// Select main category
	$query = "SELECT main_category_id, main_category_name, main_category_age_restriction, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit, main_category_updated_by_user_id, main_category_updated, main_category_note FROM $t_food_categories_main WHERE main_category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_age_restriction, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit, $get_current_main_category_updated_by_user_id, $get_current_main_category_updated, $get_current_main_category_note) = $row;

	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=categories&amp;language=$language\">Categories</a></p>
		";
	}
	else{
		if($process == "1"){
			
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_food_categories_main WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));

			// Send success
			$url = "index.php?open=$open&page=categories&ft=success&fm=category_deleted&editor_language=$editor_language&l=$l";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_current_main_category_name</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=categories\">Categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=$action&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>			
			</p>
		<!-- //Where am I ? -->


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Delete category form -->
			
			<p>
			Are you sure you want to delete the main category?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=delete_main_category&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a>
			&nbsp;
			<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Cancel</a>
			</p>
		<!-- //Delete category form -->
		
		";
	}
} // delete_category
elseif($action == "new_main_category"){
	if($process == "1"){
		$datetime = date("Y-m-d H:i:s");

		$inp_category_name = $_POST['inp_category_name'];
		$inp_category_name = output_html($inp_category_name);
		$inp_category_name_mysql = quote_smart($link, $inp_category_name);
		if(empty($inp_category_name)){
			echo"No category name";die;
		}

		$inp_category_age_limit = $_POST['inp_category_age_limit'];
		$inp_category_age_limit = output_html($inp_category_age_limit);
		$inp_category_age_limit_mysql = quote_smart($link, $inp_category_age_limit);


		// Insert
		mysqli_query($link, "INSERT INTO $t_food_categories_main
		(main_category_id, main_category_name, main_category_age_limit, main_category_updated_by_user_id, main_category_updated) 
		VALUES 
		(NULL, $inp_category_name_mysql, $inp_category_age_limit_mysql, $my_user_id_mysql, '$datetime')")
		or die(mysqli_error($link));

		// Get category ID
		$query = "SELECT main_category_id FROM $t_food_categories_main WHERE main_category_updated='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id) = $row;

		// Insert translations
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

			$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);

			mysqli_query($link, "INSERT INTO $t_food_categories_main_translations
			(main_category_translation_id, main_category_id, main_category_translation_language, main_category_translation_value) 
			VALUES 
			(NULL, $get_current_main_category_id, $inp_language_mysql, $inp_category_name_mysql)")
			or die(mysqli_error($link));
		}


		// Send success
		$url = "index.php?open=$open&page=categories&action=open_main_category&category_id=$get_current_main_category_id&editor_language=$editor_language&l=$l&ft=success&fm=category_created";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>New category</h1>

	<!-- Where am I ? -->
		<p>
		<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=categories&amp;action=new_category&amp;editor_language=$editor_language&amp;l=$l\">New main category</a>
		</p>
	<!-- //Where am I ? -->


		
	<!-- New main category form -->
			
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_category_name\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=categories&amp;action=new_main_category&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Name:</b><br />
		<input type=\"text\" name=\"inp_category_name\" value=\"\" size=\"40\" />
		</p>


		<p><b>Age limit:</b><br />
		<input type=\"radio\" name=\"inp_category_age_limit\" value=\"1\" /> Yes
		<input type=\"radio\" name=\"inp_category_age_limit\" value=\"0\" checked=\"checked\" /> No
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" clas=\"btn_default\" />
		</p>

		</form>
	<!-- //New main category form -->
		
	";
} // new_main category
elseif($action == "edit_sub_category" && isset($_GET['category_id'])){
	
	// Get variables
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	$category_id_mysql = quote_smart($link, $category_id);

	// Select sub category
	$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_icon_path, sub_category_icon_inactive_32x32, sub_category_icon_active_32x32, sub_category_icon_inactive_48x48, sub_category_icon_active_48x48, sub_category_age_limit, sub_category_updated_by_user_id, sub_category_updated, sub_category_note FROM $t_food_categories_sub WHERE sub_category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_icon_path, $get_current_sub_category_icon_inactive_32x32, $get_current_sub_category_icon_active_32x32, $get_current_sub_category_icon_inactive_48x48, $get_current_sub_category_icon_active_48x48, $get_current_sub_category_age_limit, $get_current_sub_category_updated_by_user_id, $get_current_sub_category_updated, $get_current_sub_category_note) = $row;

	if($get_current_sub_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a></p>
		";
	}
	else{
		// Select main category
		$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit, main_category_updated_by_user_id, main_category_updated, main_category_note FROM $t_food_categories_main WHERE main_category_id=$get_current_sub_category_parent_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit, $get_current_main_category_updated_by_user_id, $get_current_main_category_updated, $get_current_main_category_note) = $row;



		if($process == "1"){
			$datetime = date("Y-m-d H:i:s");

			$inp_category_name = $_POST['inp_category_name'];
			$inp_category_name = output_html($inp_category_name);
			$inp_category_name_mysql = quote_smart($link, $inp_category_name);


			$inp_category_age_limit = $_POST['inp_category_age_limit'];
			$inp_category_age_limit = output_html($inp_category_age_limit);
			$inp_category_age_limit_mysql = quote_smart($link, $inp_category_age_limit);

			$inp_category_parent_id = $_POST['inp_category_parent_id'];
			$inp_category_parent_id = output_html($inp_category_parent_id);
			$inp_category_parent_id_mysql = quote_smart($link, $inp_category_parent_id);

			$inp_symbolic_link_to_category = $_POST['inp_symbolic_link_to_category'];
			$inp_symbolic_link_to_category = output_html($inp_symbolic_link_to_category);
			$inp_symbolic_link_to_category_mysql = quote_smart($link, $inp_symbolic_link_to_category);


			// Update
			$result = mysqli_query($link, "UPDATE $t_food_categories_sub SET 
							sub_category_name=$inp_category_name_mysql,
							sub_category_age_limit=$inp_category_age_limit_mysql,
							sub_category_parent_id=$inp_category_parent_id_mysql,
							sub_category_symbolic_link_to_category_id=$inp_symbolic_link_to_category_mysql,
							sub_category_updated_by_user_id=$my_user_id_mysql,
							sub_category_updated='$datetime'
							 WHERE sub_category_id=$get_current_sub_category_id") or die(mysqli_error($link));

			// Update translation english
			$result = mysqli_query($link, "UPDATE $t_food_categories_sub_translations SET 
							sub_category_translation_value=$inp_category_name_mysql
							 WHERE sub_category_id=$get_current_sub_category_id AND sub_category_translation_language='en'") or die(mysqli_error($link));
			

			// Dirs
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/food"))){
				mkdir("../_uploads/food");
			}
			if(!(is_dir("../_uploads/food/categories"))){
				mkdir("../_uploads/food/categories");
			}

			// Icons
			$inp_title_clean = clean($inp_category_name);
			$sizes_array = array("32x32", "48x48");
			$types_array = array("inactive", "active");

			$ft_image = "";
			$fm_image = "";

			for($x=0;$x<sizeof($sizes_array);$x++){
				for($y=0;$y<sizeof($types_array);$y++){
					$inp_file = "inp_icon_" . $types_array[$y] . "_" . $sizes_array[$x];


					$name = stripslashes($_FILES["inp_icon_" . $types_array[$y] . "_" . $sizes_array[$x]]['name']);
					$name = output_html($name);
					$extension = get_extension($name);
					$extension = strtolower($extension);

					if($name){
						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft_image = "warning";
							$fm_image = "unknown_file_extension";
						}
						else{
							// Delete old icon
							$icon_file = "";
							if($sizes_array[$x] == "32x32" && $types_array[$y] == "inactive"){
								$icon_file = "$get_current_category_icon_inactive_32x32";
							}
							elseif($sizes_array[$x] == "32x32" && $types_array[$y] == "active"){
								$icon_file = "$get_current_category_icon_active_32x32";
							}
							elseif($sizes_array[$x] == "48x48" && $types_array[$y] == "inactive"){
								$icon_file = "$get_current_category_icon_inactive_48x48";
							}
							elseif($sizes_array[$x] == "48x48" && $types_array[$y] == "active"){
								$icon_file = "$get_current_category_icon_active_48x48";
							}

							if(file_exists("../$get_current_category_icon_path/$icon_file") && $icon_file != ""){
								unlink("../$get_current_category_icon_path/$icon_file");
							}


							$new_path = "../_uploads/food/categories/";
							$uploaded_file = $new_path . $inp_title_clean . "_" . $types_array[$y] . "_" . $sizes_array[$x] . "." . $extension;

							// Upload file
							if (move_uploaded_file($_FILES["$inp_file"]['tmp_name'], $uploaded_file)) {
	

								// Get image size
								$file_size = filesize($uploaded_file);
						
								// Check with and height
								list($width,$height) = getimagesize($uploaded_file);
	
								if($width == "" OR $height == ""){
									$ft_image = "warning";
									$fm_image = "getimagesize_failed";
									unlink($uploaded_file);
								}
								else{
									$ft_image = "success";
									$fm_image = "icon_uploaded";

									$field = "sub_category_icon_" . $types_array[$y] . "_" . $sizes_array[$x];

									$inp_icon = $inp_title_clean . "_" . $types_array[$y] . "_" . $sizes_array[$x] . "." . $extension;
									$inp_icon_mysql = quote_smart($link, $inp_icon);

									$result = mysqli_query($link, "UPDATE $t_food_categories_sub SET 
											sub_category_icon_path='_uploads/food/categories',
											$field=$inp_icon_mysql
											WHERE sub_category_id=$get_current_sub_category_id") or die(mysqli_error($link));
							
								}  // if($width == "" OR $height == ""){
							} // move_uploaded_file
							else{
							$ft_image = "warning";
							switch ($_FILES['inp_food_image']['error']) {
								case UPLOAD_ERR_OK:
           								$fm_image = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_image = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_image = "to_big_size_in_form";
									break;
								default:
           								$fm_image = "unknown_error";
									break;
							} // switch
							} // move uploaded file failed
						} // extension check
					
					} // if($image){

				
				} // for icons types array
			} // for icons array

			// Send success
			$url = "index.php?open=$open&page=categories&action=edit_sub_category&category_id=$get_current_sub_category_id&ft=success&fm=changes_saved&editor_language=$editor_language&l=$l&ft_image=$ft_image&fm_image=$fm_image";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_current_sub_category_name</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=edit_sub_category&amp;category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_sub_category_name</a>	
			</p>
		<!-- //Where am I ? -->


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Edit sub category form -->
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_category_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=categories&amp;action=edit_sub_category&amp;category_id=$get_current_sub_category_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_category_name\" value=\"$get_current_sub_category_name\" size=\"40\" />
			</p>




			<p><b>Age limit:</b><br />
			<input type=\"radio\" name=\"inp_category_age_limit\" value=\"1\""; if($get_current_sub_category_age_limit == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_category_age_limit\" value=\"0\""; if($get_current_sub_category_age_limit == "0" OR $get_current_category_age_limit == ""){ echo" checked=\"checked\""; } echo" /> No
			</p>


			<p><b>Parent:</b><br />
			<select name=\"inp_category_parent_id\">\n";
				$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_category_id, $get_main_category_name) = $row;
					echo"			";
					echo"<option value=\"$get_main_category_id\""; if($get_current_sub_category_parent_id == "$get_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_name</option>\n";
				}
			echo"
			</select>
			</p>

			<p><b>Symbolic link to category:</b><br />
			A symbolic link is a link instead of a category. When a link is clicked the user will be taken to the category.<br />

			<select name=\"inp_symbolic_link_to_category\">
				<option value=\"0\""; if($get_current_sub_category_symbolic_link_to_category_id == "0"){ echo" selected=\"selected\""; } echo">- None -</option>\n";
				$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_category_id, $get_main_category_name) = $row;
					echo"			";
					echo"<option value=\"0\"></option>\n";
					echo"<option value=\"0\">$get_main_category_name</option>\n";


					$query_sub = "SELECT sub_category_id, sub_category_name FROM $t_food_categories_sub WHERE sub_category_parent_id=$get_main_category_id ORDER BY sub_category_name ASC";
					$result_sub = mysqli_query($link, $query_sub);
					while($row_sub = mysqli_fetch_row($result_sub)) {
						list($get_sub_category_id, $get_sub_category_name) = $row_sub;

						echo"			";
						echo"<option value=\"$get_sub_category_id\""; if($get_current_sub_category_symbolic_link_to_category_id == "$get_sub_category_id"){ echo" selected=\"selected\""; } echo">&nbsp; $get_sub_category_name</option>\n";

					}
				}
			echo"
			</select>
			</p>

			<p><b>Icon inactive 32x32 (Color)</b><br />\n";
			if(file_exists("../$get_current_sub_category_icon_path/$get_current_sub_category_icon_inactive_32x32") && $get_current_sub_category_icon_inactive_32x32 != ""){
				echo"<img src=\"../$get_current_sub_category_icon_path/$get_current_sub_category_icon_inactive_32x32\" alt=\"$get_current_sub_category_icon_inactive_32x32\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_inactive_32x32\" />
			</p>

			<p><b>Icon active 32x32 (Grey color)</b><br />\n";
			if(file_exists("../$get_current_sub_category_icon_path/$get_current_sub_category_icon_active_32x32") && $get_current_sub_category_icon_active_32x32 != ""){
				echo"<img src=\"../$get_current_sub_category_icon_path/$get_current_sub_category_icon_active_32x32\" alt=\"$get_current_sub_category_icon_active_32x32\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_active_32x32\" />
			</p>

			<p><b>Icon inactive 48x48 (Color)</b><br />\n";
			if(file_exists("../$get_current_sub_category_icon_path/$get_current_sub_category_icon_inactive_48x48") && $get_current_sub_category_icon_inactive_48x48 != ""){
				echo"<img src=\"../$get_current_sub_category_icon_path/$get_current_sub_category_icon_inactive_48x48\" alt=\"$get_current_sub_category_icon_inactive_48x48\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_inactive_48x48\" />
			</p>

			<p><b>Icon active 48x48 (Grey color)</b><br />\n";
			if(file_exists("../$get_current_sub_category_icon_path/$get_current_sub_category_icon_active_48x48") && $get_current_sub_category_icon_active_48x48 != ""){
				echo"<img src=\"../$get_current_sub_category_icon_path/$get_current_sub_category_icon_active_48x48\" alt=\"$get_current_sub_category_icon_active_48x48\" /><br />";
			}
			echo"
			<input type=\"file\" name=\"inp_icon_active_48x48\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Save\" />
			</p>

			</form>
		<!-- //Edit main category form -->
		
		";
	}
} // edit main_category
elseif($action == "delete_sub_category" && isset($_GET['category_id'])){
	
	// Get variables
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	$category_id_mysql = quote_smart($link, $category_id);

	// Select sub category
	$query = "SELECT sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_icon_path, sub_category_icon_inactive_32x32, sub_category_icon_active_32x32, sub_category_icon_inactive_48x48, sub_category_icon_active_48x48, sub_category_age_limit, sub_category_updated_by_user_id, sub_category_updated, sub_category_note FROM $t_food_categories_sub WHERE sub_category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_name, $get_current_sub_category_parent_id, $get_current_sub_category_symbolic_link_to_category_id, $get_current_sub_category_icon_path, $get_current_sub_category_icon_inactive_32x32, $get_current_sub_category_icon_active_32x32, $get_current_sub_category_icon_inactive_48x48, $get_current_sub_category_icon_active_48x48, $get_current_sub_category_age_limit, $get_current_sub_category_updated_by_user_id, $get_current_sub_category_updated, $get_current_sub_category_note) = $row;

	if($get_current_sub_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a></p>
		";
	}
	else{
		// Select main category
		$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit, main_category_updated_by_user_id, main_category_updated, main_category_note FROM $t_food_categories_main WHERE main_category_id=$get_current_sub_category_parent_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit, $get_current_main_category_updated_by_user_id, $get_current_main_category_updated, $get_current_main_category_note) = $row;

		if($process == "1"){
			
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_food_categories_sub WHERE sub_category_id=$get_current_sub_category_id") or die(mysqli_error($link));

			// Send success
			$url = "index.php?open=$open&page=categories&action=open_main_category&category_id=$get_current_main_category_id&ft=success&fm=category_deleted&editor_language=$editor_language&l=$l";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_current_sub_category_name</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=edit_sub_category&amp;category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_sub_category_name</a>	
			</p>
		<!-- //Where am I ? -->


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Delete category form -->
			
			<p>
			Are you sure you want to delete the sub category?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=delete_sub_category&amp;category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a>
			&nbsp;
			<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Cancel</a>
			</p>
		<!-- //Delete category form -->
		
		";
	}
} // delete_category
elseif($action == "new_sub_category"){
	// Get variables
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	$category_id_mysql = quote_smart($link, $category_id);

	// Select main category
	$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit, main_category_updated_by_user_id, main_category_updated, main_category_note FROM $t_food_categories_main WHERE main_category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit, $get_current_main_category_updated_by_user_id, $get_current_main_category_updated, $get_current_main_category_note) = $row;

	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Category not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=categories&amp;language=$language\">Categories</a></p>
		";
	}
	else{


		if($process == "1"){
			$datetime = date("Y-m-d H:i:s");

			$inp_category_name = $_POST['inp_category_name'];
			$inp_category_name = output_html($inp_category_name);
			$inp_category_name_mysql = quote_smart($link, $inp_category_name);
			if(empty($inp_category_name)){
				echo"No category name";die;
			}

			$inp_category_age_limit = $_POST['inp_category_age_limit'];
			$inp_category_age_limit = output_html($inp_category_age_limit);
			$inp_category_age_limit_mysql = quote_smart($link, $inp_category_age_limit);

			$inp_symbolic_link_to_category = $_POST['inp_symbolic_link_to_category'];
			$inp_symbolic_link_to_category = output_html($inp_symbolic_link_to_category);
			$inp_symbolic_link_to_category_mysql = quote_smart($link, $inp_symbolic_link_to_category);




			// Insert
			mysqli_query($link, "INSERT INTO $t_food_categories_sub
			(sub_category_id, sub_category_name, sub_category_parent_id, sub_category_symbolic_link_to_category_id, sub_category_age_limit, sub_category_updated_by_user_id, sub_category_updated) 
			VALUES 
			(NULL, $inp_category_name_mysql, '$get_current_main_category_id', $inp_symbolic_link_to_category_mysql, $inp_category_age_limit_mysql, $my_user_id_mysql, '$datetime')")
			or die(mysqli_error($link));

			// Get category ID
			$query = "SELECT sub_category_id FROM $t_food_categories_sub WHERE sub_category_updated='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id) = $row;

			// Insert translations
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

				$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);

				mysqli_query($link, "INSERT INTO $t_food_categories_sub_translations
				(sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value) 
				VALUES 
				(NULL, $get_current_sub_category_id, $inp_language_mysql, $inp_category_name_mysql)")
				or die(mysqli_error($link));
			}


			// Send success
			$url = "index.php?open=$open&page=categories&action=open_main_category&category_id=$get_current_main_category_id&editor_language=$editor_language&l=$l&ft=success&fm=category_created";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>New sub category</h1>

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=open_main_category&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=categories&amp;action=$action&amp;category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">New sub category</a>	
			</p>
		<!-- //Where am I ? -->


		
		<!-- New sub category form -->
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_category_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=categories&amp;action=new_sub_category&amp;category_id=$get_current_main_category_id&amp;language=$language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_category_name\" value=\"\" size=\"40\" />
			</p>


			<p><b>Age limit:</b><br />
			<input type=\"radio\" name=\"inp_category_age_limit\" value=\"1\" /> Yes
			<input type=\"radio\" name=\"inp_category_age_limit\" value=\"0\" checked=\"checked\" /> No
			</p>

			<p><b>Symbolic link to category:</b><br />
			A symbolic link is a link instead of a category. When a link is clicked the user will be taken to the category.<br />

			<select name=\"inp_symbolic_link_to_category\">
				<option value=\"0\" selected=\"selected\">- None -</option>\n";
				$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_category_id, $get_main_category_name) = $row;
					echo"			";
					echo"<option value=\"0\"></option>\n";
					echo"<option value=\"0\">$get_main_category_name</option>\n";


					$query_sub = "SELECT sub_category_id, sub_category_name FROM $t_food_categories_sub WHERE sub_category_parent_id=$get_main_category_id ORDER BY sub_category_name ASC";
					$result_sub = mysqli_query($link, $query_sub);
					while($row_sub = mysqli_fetch_row($result_sub)) {
						list($get_sub_category_id, $get_sub_category_name) = $row_sub;

						echo"			";
						echo"<option value=\"$get_sub_category_id\">&nbsp; $get_sub_category_name</option>\n";

					}
				}
			echo"
			</select>
			</p>

			<p>
			<input type=\"submit\" value=\"Save\" clas=\"btn_default\" />
			</p>

			</form>
		<!-- //New sub category form -->
		
		";
	} // main category found
} // new sub category
elseif($action == "sqlite_code_a"){
	echo"
	<h1>SQLite code 1</h1>

	<p>
	String categoryName = &quot;&quot;;<br /><br />
	";


	// Get all categories
	$category_count=0;
	$query = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='0'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;

		$category_name_lowercase = strtolower($get_main_category_name);
		$category_name_lowercase = str_replace(" ", "_", $category_name_lowercase);
		$category_name_lowercase = str_replace(",", "", $category_name_lowercase);

		// Count
		$category_count++;
		$current_parent_id = $category_count;

		echo"
		categoryName = context.getResources().getString(R.string.$category_name_lowercase);<br />
		setupInsertToCategories(&quot;NULL, '&quot; + categoryName + &quot;', '0', '', NULL&quot;);<br />
		";

		// Get sub

		$queryb = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id'";
		$resultb = mysqli_query($link, $queryb);
		while($rowb = mysqli_fetch_row($resultb)) {
			list($get_category_id, $get_category_name, $get_category_parent_id) = $rowb;

			$category_name_lowercase = strtolower($get_category_name);
			$category_name_lowercase = str_replace(" ", "_", $category_name_lowercase);
			$category_name_lowercase = str_replace(",", "", $category_name_lowercase);

			// Count
			$category_count++;


			echo"
			categoryName = context.getResources().getString(R.string.$category_name_lowercase);<br />
			setupInsertToCategories(&quot;NULL, '&quot; + categoryName + &quot;', '$current_parent_id', '', NULL&quot;);<br />
			";
		}

		echo"

		<br />
		";
	}

}
elseif($action == "sqlite_code_b"){
	echo"
	<h1>SQLite code 2</h1>

	";


	// Get all categories
	$category_count = 0;
	$insert_count = 0;
	$transfer_main_category_id = 0;
	$query = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='0'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;

		// Inp
		$inp_main_category_name_mysql = quote_smart($link, $get_main_category_name);

		if($insert_count == "0"){
			echo"db.execSQL(&quot;INSERT INTO food_categories(category_id, category_user_id, category_name, category_parent_id) \n&quot; +<br />
			&quot;VALUES &quot; +<br />
               		";
		}
		else{
			echo",&quot; + <br />\n";
		}

		// Insert main category
		echo"
		&quot;(NULL, '0', $inp_main_category_name_mysql, '0')";
		
		// Main count
		$insert_count++;
		$category_count++;
		$transfer_main_category_id = $category_count;

		// Get sub
		$queryb = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id'";
		$resultb = mysqli_query($link, $queryb);
		while($rowb = mysqli_fetch_row($resultb)) {
			list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;

			// Inp
			$inp_sub_category_name_mysql = quote_smart($link, $get_sub_category_name);

			if($insert_count == "0"){
				echo"db.execSQL(&quot;INSERT INTO food_categories(category_id, category_user_id, category_name, category_parent_id) \n&quot; +<br />
				&quot;VALUES &quot; +<br />
               			";
			}
			else{
				echo",&quot; + <br />\n";
			}

			// Insert sub category
			echo"
			&quot;(NULL, '0', $inp_sub_category_name_mysql, '$transfer_main_category_id')";

			// Sub count
			$insert_count++;
			$category_count++;



			// End insert count
			if($insert_count > 10){
				echo"
				&quot;)<br /><br />
				";
				$insert_count = 0;
			}
		}

		// End insert count
		if($insert_count > 10){
			echo"
			&quot;)<br /><br />
			";
			$insert_count = 0;
		}


	}

}
elseif($action == "strings"){
	echo"
	<h1>Strings</h1>

	<p>
	";


	// Get all categories
	$category_count=0;
	$language_mysql = quote_smart($link, $language);
	$query = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='0'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;

		$category_name_lowercase = strtolower($get_main_category_name);
		$category_name_lowercase = str_replace(" ", "_", $category_name_lowercase);
		$category_name_lowercase = str_replace(",", "", $category_name_lowercase);

		echo"
		&lt;string name=&quot;$category_name_lowercase&quot;&gt;$get_main_category_name&lt;/string&gt;<br />
		";

		// Get sub

		$queryb = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id'";
		$resultb = mysqli_query($link, $queryb);
		while($rowb = mysqli_fetch_row($resultb)) {
			list($get_category_id, $get_category_name, $get_category_parent_id) = $rowb;

			$category_name_lowercase = strtolower($get_category_name);
			$category_name_lowercase = str_replace(" ", "_", $category_name_lowercase);
			$category_name_lowercase = str_replace(",", "", $category_name_lowercase);



			echo"
			&lt;string name=&quot;$category_name_lowercase&quot;&gt;$get_category_name&lt;/string&gt;<br />
			";
		}

		echo"

		<br />
		";
	}

}
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT category_id, category_name, category_age_restriction FROM $t_food_categories";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_name, $get_category_age_restriction) = $row;

			$inp_category_translation_value = $_POST["inp_category_translation_value_$get_category_id"];
			$inp_category_translation_value = output_html($inp_category_translation_value);
			$inp_category_translation_value_mysql = quote_smart($link, $inp_category_translation_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_food_categories_translations SET category_translation_value=$inp_category_translation_value_mysql WHERE category_id=$get_category_id AND category_translation_language=$editor_language_mysql") or die(mysqli_error($link));
		}

		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;

	}


	echo"
	<h1>Translations</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Translate form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Name</span>
		   </th>";
			$languages = array();
			$languages_counter = 0;
			$query_languages = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result_languages = mysqli_query($link, $query_languages);
			while($row_languages = mysqli_fetch_row($result_languages)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row_languages;
				$languages[$languages_counter] = "$get_language_active_iso_two";

				echo"
				   <th scope=\"col\">
					<span>$get_language_active_name</span>
				   </th>
				";
				$languages_counter++;
			}
			echo"
		  </tr>
		</thead>
		<tbody>
		";
	

		$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_name) = $row;

			echo"
			<tr>
			  <td>
				<span>$get_main_category_name</span>
			  </td>
			";
			for($x=0;$x<$languages_counter;$x++){
				$language_mysql = quote_smart($link, $languages[$x]);

				$query_l = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_main_category_id AND main_category_translation_language=$language_mysql";
				$result_l = mysqli_query($link, $query_l);
				$row_l = mysqli_fetch_row($result_l);
				list($get_main_category_translation_id, $get_main_category_translation_value) = $row_l;
				if($get_main_category_translation_id == ""){
					// Insert 
					$inp_name_mysql = quote_smart($link, $get_main_category_name);
					mysqli_query($link, "INSERT INTO $t_food_categories_main_translations 
					(main_category_translation_id, main_category_id, main_category_translation_language, main_category_translation_value) 
					VALUES 
					(NULL, $get_main_category_id, $language_mysql, $inp_name_mysql)")
					or die(mysqli_error($link));

					// Translation
					$get_main_category_translation_value = "$get_main_category_translation_value";
				}
				echo"
				  <td>
					<span><input type=\"text\" name=\"inp_main_$get_main_category_id"; echo"_$get_main_category_translation_value\" value=\"$get_main_category_translation_value\" size=\"20\" style=\"width: 90%;\" /></span>
				  </td>
				";
			} // main languages
			echo"
			</tr>
			";

			// Sub categories

			$query_s = "SELECT sub_category_id, sub_category_name FROM $t_food_categories_sub WHERE sub_category_parent_id=$get_main_category_id ORDER BY sub_category_name ASC";
			$result_s = mysqli_query($link, $query_s);
			while($row_s = mysqli_fetch_row($result_s)) {
				list($get_sub_category_id, $get_sub_category_name) = $row_s;

				echo"
				<tr>
				  <td>
					<span>&nbsp; $get_sub_category_name</span>
				  </td>
				";
				for($x=0;$x<$languages_counter;$x++){
					$language_mysql = quote_smart($link, $languages[$x]);

					$query_l = "SELECT sub_category_translation_id, sub_category_translation_value FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_sub_category_id AND sub_category_translation_language=$language_mysql";
					$result_l = mysqli_query($link, $query_l);
					$row_l = mysqli_fetch_row($result_l);
					list($get_sub_category_translation_id, $get_sub_category_translation_value) = $row_l;
					if($get_sub_category_translation_id == ""){
						// Insert 
						$inp_name_mysql = quote_smart($link, $get_sub_category_name);
						mysqli_query($link, "INSERT INTO $t_food_categories_sub_translations 
						(sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value) 
						VALUES 
						(NULL, $get_sub_category_id, $language_mysql, $inp_name_mysql)")
						or die(mysqli_error($link));

						// Translation
						$get_sub_category_translation_value = "$get_sub_category_translation_value";
					}
					echo"
					  <td>
						<span><input type=\"text\" name=\"inp_sub_$get_sub_category_id"; echo"_$get_main_category_translation_value\" value=\"$get_sub_category_translation_value\" size=\"20\" style=\"width: 90%;\" /></span>
					  </td>
					";
				} // sub languages
				echo"
				</tr>
				";
			} // sub categories
		} // main categories
		echo"
		 </tbody>
		</table>

		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn\" />
		</p>
		</form>

	<!-- //List all categories -->

	<!-- Back -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">$l_back</a>
		</p>
	<!-- //Back -->
 	";
} // action == "";
?>