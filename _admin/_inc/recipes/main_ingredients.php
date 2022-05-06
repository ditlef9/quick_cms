<?php
/**
*
* File: _admin/_inc/recipes/main_ingredients.php
* Version 1.0
* Date 13:41 04.11.2017
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
include("_inc/recipes/_tables.php");

/*- Functions ---------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['ingredient_id'])) {
	$ingredient_id= $_GET['ingredient_id'];
	$ingredient_id = strip_tags(stripslashes($ingredient_id));
	if(!(is_numeric($ingredient_id))){
		echo"ingredient is not numeric";
		die;
	}
}
else{
	$ingredient_id = "";
}


/*- Script start --------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Main Ingredients</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Add -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add&amp;editor_language=$editor_language\" class=\"btn\">Add</a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=translations&amp;editor_language=$editor_language\" class=\"btn\">Translations</a>
		</p>
	<!-- //Add -->


	<!-- List all categories, then ingredient per category -->";

		$query_c = "SELECT category_id, category_name, category_image_path, category_image_file, category_image_updated_month, category_icon_file FROM $t_recipes_categories ORDER BY category_name ASC";
		$result_c = mysqli_query($link, $query_c);
		while($row_c = mysqli_fetch_row($result_c)) {
			list($get_category_id, $get_category_name, $get_category_image_path, $get_category_image_file, $get_category_image_updated_month, $get_category_icon_file) = $row_c;
			
			echo"
			<table>
			 <tr>
			  <td style=\"padding:0px 5px 0px 0px;\">
				<h2>$get_category_name</h2>
			  </td>
			  <td>
				<p style=\"padding:0;margin:0;\">
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=add&amp;category_id=$get_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">Add</a>
				</p>
			  </td>
			 </tr>
			</table>		

			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>Title</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			</thead>
			<tbody>
			";
			$query_i = "SELECT ingredient_id, ingredient_title, ingredient_icon_path, ingredient_icon_18x18_inactive, ingredient_category_id, ingredient_category_name FROM $t_recipes_main_ingredients WHERE ingredient_category_id=$get_category_id ORDER BY ingredient_title ASC";
			$result_i = mysqli_query($link, $query_i);
			while($row_i = mysqli_fetch_row($result_i)) {
				list($get_ingredient_id, $get_ingredient_title, $get_ingredient_icon_path, $get_ingredient_icon_18x18_inactive, $get_ingredient_category_id, $get_ingredient_category_name) = $row_i;

				// Make sure all translations exists
				$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
				$result_l = mysqli_query($link, $query_l);
				while($row_l = mysqli_fetch_row($result_l)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row_l;

			
					// Translation
					$language_mysql = quote_smart($link, $get_language_active_iso_two);
					$query_translation = "SELECT translation_id, translation_category_id, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_ingredient_id=$get_ingredient_id AND translation_language=$language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_translation_id, $get_translation_category_id, $get_translation_value) = $row_translation;
					if($get_translation_id == ""){
						// It doesnt exists, create it.
						$inp_value_mysql = quote_smart($link, $get_ingredient_title);
						mysqli_query($link, "INSERT INTO $t_recipes_main_ingredients_translations 
						(translation_id, translation_ingredient_id, translation_category_id, translation_language, translation_value) 
						VALUES 
						(NULL, $get_ingredient_id, $get_category_id, $language_mysql, $inp_value_mysql)")
						or die(mysqli_error($link));

						echo"<div class=\"info\"><span>Created translation</span></div>";
					}
					else{
						if($get_category_id != "$get_translation_category_id"){
							echo"<div class=\"info\"><span>Updated category for translations</span></div>";
							mysqli_query($link, "UPDATE $t_recipes_main_ingredients_translations SET translation_category_id=$get_category_id WHERE translation_id=$get_translation_id") or die(mysqli_error($link));
						}
					}
				} // make sure translations exists

				echo"
				<tr>
				  <td>
					<span>";
					if(file_exists("../$get_ingredient_icon_path/$get_ingredient_icon_18x18_inactive")){
						echo"<img src=\"../$get_ingredient_icon_path/$get_ingredient_icon_18x18_inactive\" alt=\"$get_ingredient_icon_18x18_inactive\" />";
					}
					echo"
					$get_ingredient_title</span>
				  </td>
				  <td>
					<span>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;ingredient_id=$get_ingredient_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;ingredient_id=$get_ingredient_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
					</span>
				 </td>
				</tr>
				";
			}
			echo"
			 </tbody>
			</table>
			";
		} // categories
		echo"
	<!-- //List all categories, then ingredient per category  -->
 	";
} // action == "";
elseif($action == "add"){
	if($process == "1"){
		$datetime = date("Y-m-d H:i:s");

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

		// Icon path
		$inp_icon_path = "_uploads/recipes/main_ingredients";
		$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/recipes"))){
			mkdir("../_uploads/recipes");
		}
		if(!(is_dir("../_uploads/recipes/main_ingredients"))){
			mkdir("../_uploads/recipes/main_ingredients");
		}

		// Category
		$inp_category_id = $_POST['inp_category_id'];
		$inp_category_id = output_html($inp_category_id);
		$inp_category_id_mysql = quote_smart($link, $inp_category_id);

		$query = "SELECT category_id, category_name FROM $t_recipes_categories WHERE category_id=$inp_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_category_id, $get_category_name) = $row;
		if($get_category_id == ""){
			$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=error&fm=category_not_found";
			header("Location: $url");
			exit;
		}
		$inp_category_name_mysql = quote_smart($link, $get_category_name);

		
		// Insert
		mysqli_query($link, "INSERT INTO $t_recipes_main_ingredients 
		(ingredient_id, ingredient_title, ingredient_title_clean, ingredient_icon_path, ingredient_category_id, ingredient_category_name,
		ingredient_updated_datetime, ingredient_updated_by_user_id) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_title_clean_mysql, $inp_icon_path_mysql, $inp_category_id_mysql, $inp_category_name_mysql,
		'$datetime', $my_user_id_mysql)")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT ingredient_id FROM $t_recipes_main_ingredients WHERE ingredient_updated_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_ingredient_id) = $row;

		$sizes_array = array("18x18", "24x24");
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
						$new_path = "../_uploads/recipes/main_ingredients/";
						$uploaded_file = $new_path . $inp_title_clean . "_" . $sizes_array[$x] . "_" . $types_array[$y] . "." . $extension;

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

								$field = "ingredient_icon_" . $sizes_array[$x] . "_" . $types_array[$y];

								$inp_icon = $inp_title_clean . "_" . $sizes_array[$x] . "_" . $types_array[$y] . "." . $extension;
								$inp_icon_mysql = quote_smart($link, $inp_icon);

								$result = mysqli_query($link, "UPDATE $t_recipes_main_ingredients SET 
											$field=$inp_icon_mysql
											WHERE ingredient_id=$get_current_ingredient_id") or die(mysqli_error($link));
							
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
		

		// Translations
		$query_l = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
		$result_l = mysqli_query($link, $query_l);
		while($row_l = mysqli_fetch_row($result_l)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row_l;

			// Translation
			$language_mysql = quote_smart($link, $get_language_active_iso_two);
			$query_translation = "SELECT translation_id, translation_category_id, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_ingredient_id=$get_current_ingredient_id AND translation_language=$language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_translation_id, $get_translation_category_id, $get_translation_value) = $row_translation;
			if($get_translation_id == ""){
				// It doesnt exists, create it.
				$inp_value_mysql = quote_smart($link, $inp_title);
				mysqli_query($link, "INSERT INTO $t_recipes_main_ingredients_translations 
				(translation_id, translation_ingredient_id, translation_category_id, translation_language, translation_value) 
				VALUES 
				(NULL, $get_current_ingredient_id, $inp_category_id_mysql, $language_mysql, $inp_title_mysql)")
				or die(mysqli_error($link));
			}
		}

		$url = "index.php?open=$open&page=$page&action=$action&category_id=$inp_category_id&editor_language=$editor_language&l=$l&ft=success&fm=added";
		if($ft_image != ""){
			$url = "$url&ft_image=$ft_image&fm_image=$fm_image";
		}
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Add</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Main ingredients</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">Add</a>
		</p>
	<!-- //Where am I? -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->


	<!-- Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p><b>Category:</b><br />
		<select name=\"inp_category_id\">";
		$category_id = 0;
		if(isset($_GET['category_id'])) {
			$category_id= $_GET['category_id'];
			$category_id = strip_tags(stripslashes($category_id));
			if(!(is_numeric($category_id))){
				echo"category is not numeric";
				die;
			}
		}


		$query = "SELECT category_id, category_name FROM $t_recipes_categories ORDER BY category_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_name) = $row;

			echo"			";
			echo"<option value=\"$get_category_id\""; if($get_category_id == "$category_id"){ echo" selected=\"selected\""; } echo">$get_category_name</option>\n";
		}
		echo"
		</select>
		</p>

		<!-- Icons -->
			";

		$sizes_array = array("18x18", "24x24");
		$types_array = array("inactive", "active");
		for($x=0;$x<sizeof($sizes_array);$x++){
			for($y=0;$y<sizeof($types_array);$y++){
				$inp_file = "inp_icon_" . $types_array[$y] . "_" . $sizes_array[$x];

				echo"
				<p><b>Icon $sizes_array[$x] $types_array[$y]</b><br />
				<input type=\"file\" name=\"$inp_file\" />
				</p>
				";
			} // icon types
		} // icon sizes
		echo"
		<!-- //Icons -->

		<p><input type=\"submit\" value=\"Save\" class=\"btn\" /></p>
		</form>
	<!-- //Form -->
	

	";
} // add
elseif($action == "edit"){
	// Find
	$ingredient_id_mysql = quote_smart($link, $ingredient_id);
	$query = "SELECT ingredient_id, ingredient_title, ingredient_title_clean, ingredient_icon_path, ingredient_icon_18x18_inactive, ingredient_icon_18x18_active, ingredient_icon_24x24_inactive, ingredient_icon_24x24_active, ingredient_category_id, ingredient_category_name, ingredient_unique_hits, ingredient_unique_hits_ipblock, ingredient_updated_datetime, ingredient_updated_by_user_id FROM $t_recipes_main_ingredients WHERE ingredient_id=$ingredient_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_ingredient_id, $get_current_ingredient_title, $get_current_ingredient_title_clean, $get_current_ingredient_icon_path, $get_current_ingredient_icon_18x18_inactive, $get_current_ingredient_icon_18x18_active, $get_current_ingredient_icon_24x24_inactive, $get_current_ingredient_icon_24x24_active, $get_current_ingredient_category_id, $get_current_ingredient_category_name, $get_current_ingredient_unique_hits, $get_current_ingredient_unique_hits_ipblock, $get_current_ingredient_updated_datetime, $get_current_ingredient_updated_by_user_id) = $row;
	
	if($get_current_ingredient_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found in database.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Back</a>
		</p>
		";
	} // not found
	else{
		if($process == 1){
			$datetime = date("Y-m-d H:i:s");

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			// Icon path
			$inp_icon_path = "_uploads/recipes/main_ingredients";
			$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/recipes"))){
				mkdir("../_uploads/recipes");
			}
			if(!(is_dir("../_uploads/recipes/main_ingredients"))){
				mkdir("../_uploads/recipes/main_ingredients");
			}

			// Category
			$inp_category_id = $_POST['inp_category_id'];
			$inp_category_id = output_html($inp_category_id);
			$inp_category_id_mysql = quote_smart($link, $inp_category_id);

			$query = "SELECT category_id, category_name FROM $t_recipes_categories WHERE category_id=$inp_category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_category_id, $get_category_name) = $row;
			if($get_category_id == ""){
				$url = "index.php?open=$open&page=$page&action=$action&ingredient_id=$get_current_ingredient_id&editor_language=$editor_language&l=$l&ft=error&fm=category_not_found";
				header("Location: $url");
				exit;
			}
			$inp_category_name_mysql = quote_smart($link, $get_category_name);

		
			// Update
			mysqli_query($link, "UPDATE $t_recipes_main_ingredients SET
						ingredient_title=$inp_title_mysql, 
						ingredient_title_clean=$inp_title_clean_mysql, 
						ingredient_icon_path=$inp_icon_path_mysql, 
						ingredient_category_id=$inp_category_id_mysql, 
						ingredient_category_name=$inp_category_name_mysql,
						ingredient_updated_datetime='$datetime', 
						ingredient_updated_by_user_id=$my_user_id_mysql
						WHERE ingredient_id=$get_current_ingredient_id")
						or die(mysqli_error($link));



			$sizes_array = array("18x18", "24x24");
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
							if($sizes_array[$x] == "18x18" && $types_array[$y] == "inactive"){
								$icon_file = "$get_current_ingredient_icon_18x18_inactive";
							}
							elseif($sizes_array[$x] == "18x18" && $types_array[$y] == "active"){
								$icon_file = "$get_current_ingredient_icon_18x18_active";
							}
							elseif($sizes_array[$x] == "24x24" && $types_array[$y] == "inactive"){
								$icon_file = "$get_current_ingredient_icon_24x24_inactive";
							}
							elseif($sizes_array[$x] == "24x24" && $types_array[$y] == "active"){
								$icon_file = "$get_current_ingredient_icon_24x24_active";
							}
							if(file_exists("../$get_current_ingredient_icon_path/$icon_file") && $icon_file != ""){
								unlink("../$get_current_ingredient_icon_path/$icon_file");
							}


							$new_path = "../_uploads/recipes/main_ingredients/";
							$uploaded_file = $new_path . $inp_title_clean . "_" . $sizes_array[$x] . "_" . $types_array[$y] . "." . $extension;

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

									$field = "ingredient_icon_" . $sizes_array[$x] . "_" . $types_array[$y];

									$inp_icon = $inp_title_clean . "_" . $sizes_array[$x] . "_" . $types_array[$y] . "." . $extension;
									$inp_icon_mysql = quote_smart($link, $inp_icon);

									$result = mysqli_query($link, "UPDATE $t_recipes_main_ingredients SET 
											$field=$inp_icon_mysql
											WHERE ingredient_id=$get_current_ingredient_id") or die(mysqli_error($link));
							
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
		


			$url = "index.php?open=$open&page=$page&action=$action&ingredient_id=$get_current_ingredient_id&editor_language=$editor_language&l=$l&ft=success&fm=added";
			if($ft_image != ""){
				$url = "$url&ft_image=$ft_image&fm_image=$fm_image";
			}
			header("Location: $url");
			exit;

		}


		echo"
		<h1>Edit $get_current_ingredient_title</h1>


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
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=recipes&amp;page=main_ingredients&amp;editor_language=$editor_language&amp;l=$l\">Main Ingredients</a>
			&gt;
			<a href=\"index.php?open=recipes&amp;page=main_ingredients&amp;action=$action&amp;ingredient_id=$get_current_ingredient_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_ingredient_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

	
		<!-- Form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;ingredient_id=$get_current_ingredient_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_ingredient_title\" size=\"25\" />
			</p>

			<p><b>Category:</b><br />
			<select name=\"inp_category_id\">";
			$query = "SELECT category_id, category_name FROM $t_recipes_categories ORDER BY category_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_name) = $row;

				echo"			";
				echo"<option value=\"$get_category_id\""; if($get_category_id == "$get_current_ingredient_category_id"){ echo" selected=\"selected\""; } echo">$get_category_name</option>\n";
			}
			echo"
			</select>
			</p>

			<!-- Icons -->
			";

			$sizes_array = array("18x18", "24x24");
			$types_array = array("inactive", "active");
			for($x=0;$x<sizeof($sizes_array);$x++){
				for($y=0;$y<sizeof($types_array);$y++){
					$inp_file = "inp_icon_" . $types_array[$y] . "_" . $sizes_array[$x];
					$icon_file = "";
					if($sizes_array[$x] == "18x18" && $types_array[$y] == "inactive"){
						$icon_file = "$get_current_ingredient_icon_18x18_inactive";
					}
					elseif($sizes_array[$x] == "18x18" && $types_array[$y] == "active"){
						$icon_file = "$get_current_ingredient_icon_18x18_active";
					}
					elseif($sizes_array[$x] == "24x24" && $types_array[$y] == "inactive"){
						$icon_file = "$get_current_ingredient_icon_24x24_inactive";
					}
					elseif($sizes_array[$x] == "24x24" && $types_array[$y] == "active"){
						$icon_file = "$get_current_ingredient_icon_24x24_active";
					}

					echo"
					<p><b>Icon $sizes_array[$x] $types_array[$y]</b><br />";
					if(file_exists("../$get_current_ingredient_icon_path/$icon_file")){
						echo"<img src=\"../$get_current_ingredient_icon_path/$icon_file\" alt=\"$icon_file\" /><br />";
					}
					echo"
					<input type=\"file\" name=\"$inp_file\" />
					</p>
					";
				} // icon types
			} // icon sizes
			echo"
			<!-- //Icons -->

			<p><input type=\"submit\" value=\"Save\" class=\"btn\" /></p>
			</form>
		<!-- //Form -->
		";
	} // found
	
} // edit
elseif($action == "delete"){
	$ingredient_id_mysql = quote_smart($link, $ingredient_id);
	$query = "SELECT ingredient_id, ingredient_title, ingredient_title_clean, ingredient_icon_path, ingredient_icon_18x18_inactive, ingredient_icon_18x18_active, ingredient_icon_24x24_inactive, ingredient_icon_24x24_active, ingredient_category_id, ingredient_category_name, ingredient_unique_hits, ingredient_unique_hits_ipblock, ingredient_updated_datetime, ingredient_updated_by_user_id FROM $t_recipes_main_ingredients WHERE ingredient_id=$ingredient_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_ingredient_id, $get_current_ingredient_title, $get_current_ingredient_title_clean, $get_current_ingredient_icon_path, $get_current_ingredient_icon_18x18_inactive, $get_current_ingredient_icon_18x18_active, $get_current_ingredient_icon_24x24_inactive, $get_current_ingredient_icon_24x24_active, $get_current_ingredient_category_id, $get_current_ingredient_category_name, $get_current_ingredient_unique_hits, $get_current_ingredient_unique_hits_ipblock, $get_current_ingredient_updated_datetime, $get_current_ingredient_updated_by_user_id) = $row;
	
	if($get_current_ingredient_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found in database.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Back</a>
		</p>
		";
	} // not found
	else{


		if($process == 1){
			// Icons
			if(file_exists("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_18x18_inactive") && $get_current_ingredient_icon_18x18_inactive != ""){
				unlink("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_18x18_inactive");
			}
			if(file_exists("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_18x18_active") && $get_current_ingredient_icon_18x18_active != ""){
				unlink("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_18x18_active");
			}
			if(file_exists("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_24x24_inactive") && $get_current_ingredient_icon_24x24_inactive != ""){
				unlink("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_24x24_inactive");
			}
			if(file_exists("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_24x24_active") && $get_current_ingredient_icon_24x24_active != ""){
				unlink("../$get_current_ingredient_icon_path/$get_current_ingredient_icon_24x24_active");
			}

			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_recipes_main_ingredients WHERE ingredient_id=$get_current_ingredient_id") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=deleted";
			header("Location: $url");
			exit;

		}


		echo"
		<h1>Delete</h1>


		<p>Are you sure you want to delete <b>$get_current_ingredient_title</b>? This action cannot be undone.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;ingredient_id=$get_current_ingredient_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Confirm</a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Cancel</a>
		</p>
		";
	} // found
	
} // delete
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT ingredient_id, ingredient_title FROM $t_recipes_main_ingredients ORDER BY ingredient_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_ingredient_id, $get_ingredient_title) = $row;


			$inp_value = $_POST["inp_value_$get_ingredient_id"];
			$inp_value = output_html($inp_value);
			$inp_value_mysql = quote_smart($link, $inp_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_recipes_main_ingredients_translations SET translation_value=$inp_value_mysql WHERE translation_ingredient_id=$get_ingredient_id AND translation_language=$editor_language_mysql") or die(mysqli_error($link));
		}

		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;

	}


	echo"
	<h1>Translations</h1>


	
	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=recipes&amp;page=main_ingredients&amp;editor_language=$editor_language&amp;l=$l\">Main Ingredients</a>
		&gt;
		<a href=\"index.php?open=recipes&amp;page=main_ingredients&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">Translations</a>
		</p>
	<!-- //Where am I? -->

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

	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		Language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Editor language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;

				// No language selected?
				if($editor_language == ""){
					$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	

	<!-- Translate form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Translation</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	

		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT ingredient_id, ingredient_title FROM $t_recipes_main_ingredients ORDER BY ingredient_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_ingredient_id, $get_ingredient_title) = $row;

			// Translation
			$query_translation = "SELECT translation_id, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_ingredient_id=$get_ingredient_id AND translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_translation_id, $get_translation_value) = $row_translation;
			if($get_translation_id == ""){
				// It doesnt exists, create it.
				$inp_value_mysql = quote_smart($link, $get_ingredient_title);
				mysqli_query($link, "INSERT INTO $t_recipes_main_ingredients_translations 
				(translation_id, translation_ingredient_id, translation_language, translation_value) 
				VALUES 
				(NULL, $get_ingredient_id, $editor_language_mysql, $inp_value_mysql)")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><span>L O A D I N G</span></div>";
				echo"
 				<meta http-equiv=\"refresh\" content=\"1;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l'\" />
				";

				
			}

			echo"
			<tr>
			  <td>
				<span>$get_ingredient_title</span>
			  </td>
			  <td>
				<span><input type=\"text\" name=\"inp_value_$get_ingredient_id\" value=\"$get_translation_value\" size=\"25\" style=\"width: 100%;\" /></span>
			  </td>
			</tr>
			";
		}
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
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">Back</a>
		</p>
	<!-- //Back -->
 	";
} // action == "";
?>