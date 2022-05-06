<?php
/**
*
* File: _admin/_inc/settings/emojies_index.php
* Version 11:55 30.12.2017
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
$t_emojies_categories_main	= $mysqlPrefixSav . "emojies_categories_main";
$t_emojies_categories_sub	= $mysqlPrefixSav . "emojies_categories_sub";
$t_emojies_index 		= $mysqlPrefixSav . "emojies_index";
$t_emojies_users_recent_used	= $mysqlPrefixSav . "emojies_users_recent_used";


/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['main_category_id'])) {
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['emoji_id'])) {
	$emoji_id = $_GET['emoji_id'];
	$emoji_id = strip_tags(stripslashes($emoji_id));
}
else{
	$emoji_id = "";
}





if($action == ""){
	echo"
	<h1>Emojies index</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=settings&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Settings</a>
		&gt;
		<a href=\"index.php?open=settings&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l\">Emojies index</a>
		</p>
	<!-- //Where am I? -->


	<!-- Actions -->
		<p>
		<a href=\"index.php?open=settings&amp;page=emojies_index&amp;action=fix_output_html&amp;editor_language=$editor_language&amp;l=$l\">Fix output_html for emojies (if emojies wont be saved in example Talk)</a>
		</p>

	<!-- //Actions -->
	<!-- Left and right -->
		<table>
		 <tr>
		  <td style=\"vertical-align: top;padding-right: 20px;\">
		
			<!-- Left side categories -->
				<table class=\"hor-zebra\">
				 <tbody>
				  <tr>
				   <td style=\"width: 240px;\">
					<p>";
					$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_main_category_id, $get_main_category_title, $get_main_category_code, $get_main_category_char, $get_main_category_source_path, $get_main_main_category_source_file, $get_main_category_source_ext, $get_main_category_weight, $get_main_category_is_active, $get_main_category_language) = $row;
						echo"
						<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=open_main_category&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_char $get_main_category_title</a><br />
						";
					}
					echo"
					</p>
				   </td>
				  </tr>
				 </tbody>
				</table>
			<!-- //Left side categories -->
		  </td>
		  <td style=\"vertical-align: top;\">
			<!-- Right side Emojies index -->
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>ID</span>
				   </th>
				   <th scope=\"col\">
					<span>Title</span>
				   </th>
				   <th scope=\"col\">
					<span>Code</span>
				   </th>
				   <th scope=\"col\">
					<span>Char</span>
				   </th>
				   <th scope=\"col\">
					<span>Skin tone</span>
				   </th>
				   <th scope=\"col\">
					<span>Actions</span>
				   </th>
				  </tr>
				</thead>
				<tbody>
				";

				$query = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row;

					// Style
					if(isset($style) && $style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}
	
					echo"
					 <tr>
					  <td class=\"$style\">
						<span>$get_emoji_id</span>
					  </td>
					  <td class=\"$style\">
						<span>$get_emoji_title</span>
					  </td>
					  <td class=\"$style\">
						<span>$get_emoji_code</span>
					  </td>
					  <td class=\"$style\">
						<span style=\"font-size: 130%;\">
						$get_emoji_char
						</span>
					  </td>
					  <td class=\"$style\">
						<span>$get_emoji_skin_tone</span>
					  </td>
					  <td class=\"$style\">
						<span>
						<a href=\"index.php?open=settings&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l&amp;action=edit_emoji&amp;emoji_id=$get_emoji_id\">Edit</a>
						|
						<a href=\"index.php?open=settings&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l&amp;action=delete_emoji&amp;emoji_id=$get_emoji_id\">Delete</a>
						</span>
					  </td>
					 </tr>
					";

				}
				echo"
					 </tbody>
					</table>
			<!-- //Right side Emojies index -->

		  </td>
		 </tr>
		</table>
	<!-- //Left and right -->
	";
}
elseif($action == "open_main_category"){
	// find main category
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_code, $get_current_main_category_char, $get_current_main_category_source_path, $get_current_main_main_category_source_file, $get_current_main_category_source_ext, $get_current_main_category_weight, $get_current_main_category_is_active, $get_current_main_category_language) = $row;
	
	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{



		echo"
		<h1>Emojies index</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Talk</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l\">Emojies index</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=$action&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Left and right -->
		<table>
		 <tr>
		  <td style=\"vertical-align: top;padding-right: 20px;\">
		
			<!-- Left side categories -->
				<table class=\"hor-zebra\">
				 <tbody>
				  <tr>
				   <td style=\"width: 240px;\">
					<p>";
					$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_main_category_id, $get_main_category_title, $get_main_category_code, $get_main_category_char, $get_main_category_source_path, $get_main_main_category_source_file, $get_main_category_source_ext, $get_main_category_weight, $get_main_category_is_active, $get_main_category_language) = $row;
						echo"
						<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=open_main_category&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\"";
						if($get_main_category_id == "$get_current_main_category_id"){ echo" style=\"font-weight: bold;\""; } 
						echo">$get_main_category_char $get_main_category_title</a><br />
						";
					}
					echo"
					</p>
				   </td>
				  </tr>
				 </tbody>
				</table>
			<!-- //Left side categories -->
		  </td>
		  <td style=\"vertical-align: top;\">
			<!-- Right side Emojies index -->
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>ID</span>
				   </th>
				   <th scope=\"col\">
					<span>Title</span>
				   </th>
				   <th scope=\"col\">
					<span>Code</span>
				   </th>
				   <th scope=\"col\">
					<span>Char</span>
				   </th>
				   <th scope=\"col\">
					<span>Skin tone</span>
				   </th>
				   <th scope=\"col\">
					<span>Actions</span>
				   </th>
				  </tr>
				</thead>
				<tbody>
				";

				$query = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index WHERE emoji_main_category_id=$get_current_main_category_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row;

					// Style
					if(isset($style) && $style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}
	
					echo"
					 <tr>
					  <td class=\"$style\">
						<span>$get_emoji_id</span>
					  </td>
					  <td class=\"$style\">
						<span>$get_emoji_title</span>
					  </td>
					  <td class=\"$style\">
						<span>$get_emoji_code</span>
					  </td>
					  <td class=\"$style\">
						<span style=\"font-size: 130%;\">
						$get_emoji_char
						</span>
					  </td>
					  <td class=\"$style\">
						<span>$get_emoji_skin_tone</span>
					  </td>
					  <td class=\"$style\">
						<span>
						<a href=\"index.php?open=settings&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l&amp;action=edit_emoji&amp;emoji_id=$get_emoji_id\">Edit</a>
						|
						<a href=\"index.php?open=settings&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l&amp;action=delete_emoji&amp;emoji_id=$get_emoji_id\">Delete</a>
						</span>
					  </td>
					 </tr>
					";

				}
				echo"
					 </tbody>
					</table>
			<!-- //Right side Emojies index -->

		  </td>
		 </tr>
		</table>
		<!-- //Left and right -->
		";
	} // main category found
} // action == "open_main_category"
elseif($action == "edit_emoji"){
	// find emoji
	$emoji_id_mysql = quote_smart($link, $emoji_id);
	$query = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_replace_a, emoji_replace_b, emoji_replace_c, emoji_is_active, emoji_code, emoji_char, emoji_char_output_html, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_is_releated_emoji_id, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index WHERE emoji_id=$emoji_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_emoji_id, $get_current_emoji_main_category_id, $get_current_emoji_sub_category_id, $get_current_emoji_title, $get_current_emoji_replace_a, $get_current_emoji_replace_b, $get_current_emoji_replace_c, $get_current_emoji_is_active, $get_current_emoji_code, $get_current_emoji_char, $get_current_emoji_char_output_html, $get_current_emoji_source_path, $get_current_emoji_source_file, $get_current_emoji_source_ext, $get_current_emoji_skin_tone, $get_current_emoji_is_releated_emoji_id, $get_current_emoji_created_by_user_id, $get_current_emoji_created_datetime, $get_current_emoji_updated_by_user_id, $get_current_emoji_updated_datetime, $get_current_emoji_used_count, $get_current_emoji_last_used_datetime) = $row;
	
	if($get_current_emoji_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
		// find main category
		$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main WHERE main_category_id=$get_current_emoji_main_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_code, $get_current_main_category_char, $get_current_main_category_source_path, $get_current_main_main_category_source_file, $get_current_main_category_source_ext, $get_current_main_category_weight, $get_current_main_category_is_active, $get_current_main_category_language) = $row;
		
		if($process == "1"){
			$inp_emoji_title = $_POST['inp_emoji_title'];
			$inp_emoji_title = str_replace(" ", "_", $inp_emoji_title);
			$inp_emoji_title = output_html($inp_emoji_title);
			$inp_emoji_title_mysql = quote_smart($link, $inp_emoji_title);

			$inp_emoji_replace_a = $_POST['inp_emoji_replace_a'];
			$inp_emoji_replace_a = output_html($inp_emoji_replace_a);
			$inp_emoji_replace_a_mysql = quote_smart($link, $inp_emoji_replace_a);

			$inp_emoji_replace_b = $_POST['inp_emoji_replace_b'];
			$inp_emoji_replace_b = output_html($inp_emoji_replace_b);
			$inp_emoji_replace_b_mysql = quote_smart($link, $inp_emoji_replace_b);

			$inp_emoji_replace_c = $_POST['inp_emoji_replace_c'];
			$inp_emoji_replace_c = output_html($inp_emoji_replace_c);
			$inp_emoji_replace_c_mysql = quote_smart($link, $inp_emoji_replace_c);

			$inp_emoji_is_active = $_POST['inp_emoji_is_active'];
			$inp_emoji_is_active = output_html($inp_emoji_is_active);
			$inp_emoji_is_active_mysql = quote_smart($link, $inp_emoji_is_active);

			$inp_emoji_code = $_POST['inp_emoji_code'];
			$inp_emoji_code = output_html($inp_emoji_code);
			$inp_emoji_code_mysql = quote_smart($link, $inp_emoji_code);

			$inp_emoji_skin_tone = $_POST['inp_emoji_skin_tone'];
			$inp_emoji_skin_tone = output_html($inp_emoji_skin_tone);
			$inp_emoji_skin_tone_mysql = quote_smart($link, $inp_emoji_skin_tone);

			$inp_emoji_is_releated_emoji_id = $_POST['inp_emoji_is_releated_emoji_id'];
			if($inp_emoji_is_releated_emoji_id == ""){
				$inp_emoji_is_releated_emoji_id = "0";
			}
			$inp_emoji_is_releated_emoji_id = output_html($inp_emoji_is_releated_emoji_id);
			$inp_emoji_is_releated_emoji_id_mysql = quote_smart($link, $inp_emoji_is_releated_emoji_id);

			// Char
			$inp_emoji_char = $_POST['inp_emoji_char'];
			$inp_emoji_char = output_html($inp_emoji_char);
			$inp_emoji_char = str_replace("&amp;", "&", $inp_emoji_char);
			$inp_emoji_char_mysql = quote_smart($link, $inp_emoji_char);

			// inp_emoji_char_output_html
			$inp_emoji_char_output_html = $_POST['inp_emoji_char_output_html'];
			$inp_emoji_char_output_html = output_html($inp_emoji_char_output_html);
			$inp_emoji_char_output_html_mysql = quote_smart($link, $inp_emoji_char_output_html);
			
			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$datetime = date("Y-m-d H:i:s");

			$result = mysqli_query($link, "UPDATE $t_emojies_index SET 
							emoji_title=$inp_emoji_title_mysql, 
							emoji_replace_a=$inp_emoji_replace_a_mysql, 
							emoji_replace_b=$inp_emoji_replace_b_mysql, 
							emoji_replace_c=$inp_emoji_replace_c_mysql, 
							emoji_is_active=$inp_emoji_is_active_mysql, 
							emoji_code=$inp_emoji_code_mysql, 
							emoji_skin_tone=$inp_emoji_skin_tone_mysql, 
							emoji_is_releated_emoji_id=$inp_emoji_is_releated_emoji_id_mysql,
							emoji_updated_by_user_id=$my_user_id_mysql,
							emoji_updated_datetime='$datetime',
							emoji_char=$inp_emoji_char_mysql,
							emoji_char_output_html=$inp_emoji_char_output_html_mysql 
							 WHERE emoji_id=$get_current_emoji_id") or die(mysqli_error($link));

			// Image
			
			// Finnes mappen?
			if(!(is_dir("../_uploads/"))){
				mkdir("../_uploads/");
			}
			if(!(is_dir("../_uploads/emojies/"))){
				mkdir("../_uploads/emojies/");
			}
			if(!(is_dir("../_uploads/emojies/$get_current_main_category_id/"))){
				mkdir("../_uploads/emojies/$get_current_main_category_id/");
			}


			// Sjekk filen
			$file_name = basename($_FILES['inp_image']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			$inp_emoji_title_clean = clean($inp_emoji_title);
			$new_name = $inp_emoji_title_clean . "." . $file_type;

			$target_path = "../_uploads/emojies/$get_current_main_category_id/" . $new_name;

			$fm_image = "";
			$ft_image = "";

			// Sjekk om det er en OK filendelse
			if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif" OR $file_type == "svg"){
				if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

					// Sjekk om det faktisk er et bilde som er lastet opp
					$image_size = getimagesize($target_path);
					$mime_content_type = mime_content_type($target_path);
					if((is_numeric($image_size[0]) && is_numeric($image_size[1])) OR $mime_content_type == "image/svg+xml"){

						// Save image
						$inp_emoji_source_path_mysql = quote_smart($link, "_uploads/emojies/$get_current_main_category_id");
					
						$inp_emoji_source_file_mysql = quote_smart($link, $new_name);

						$inp_emoji_source_ext_mysql = quote_smart($link, $file_type);
					
						$result = mysqli_query($link, "UPDATE $t_emojies_index SET 
							emoji_source_path=$inp_emoji_source_path_mysql,
							emoji_source_file=$inp_emoji_source_file_mysql,
							emoji_source_ext=$inp_emoji_source_ext_mysql
							 WHERE emoji_id=$get_current_emoji_id") or die(mysqli_error($link));


						$ft_image = "success";
						$fm_image = "image_uploaded";
					}
					else{
						// SVG is unuiqe
						unlink("$target_path");

						$ft_image = "error";
						$fm_image = "could_not_get_image_size";
					}
				} // is_numeric
				else{
					$ft_image = "error";
					$fm_image = "could_not_uploaded_image";
				}
			} // file type
			else{
				$ft_image = "info";
				$fm_image = "image_has_unknown_file_type";
			}

			// Category
			$ft_category = "info";
			$fm_category = "image_has_unknown_file_type";

			$inp_emoji_sub_category_id = $_POST['inp_emoji_sub_category_id'];
			$inp_emoji_sub_category_id = output_html($inp_emoji_sub_category_id);
			$inp_emoji_sub_category_id_mysql = quote_smart($link, $inp_emoji_sub_category_id);
			if($inp_emoji_sub_category_id != ""){
				// Find sub category
				$query = "SELECT sub_category_id, sub_category_parent_id FROM $t_emojies_categories_sub WHERE sub_category_id=$inp_emoji_sub_category_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_sub_category_id, $get_sub_category_parent_id) = $row;
		
				// find main category
				$query = "SELECT main_category_id FROM $t_emojies_categories_main WHERE main_category_id=$get_sub_category_parent_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_main_category_id) = $row;
		
				if($get_sub_category_id != "" && $get_main_category_id != ""){
					
					$result = mysqli_query($link, "UPDATE $t_emojies_index SET 
							emoji_main_category_id=$get_main_category_id,
							emoji_sub_category_id=$get_sub_category_id
							 WHERE emoji_id=$get_current_emoji_id") or die(mysqli_error($link));


					$ft_category = "info";
					$fm_category = "category_changed";
				}
			}
			else{
				$ft_category = "error";
				$fm_category = "category_not_found";
			
			}

			// Delete recents
			$result = mysqli_query($link, "DELETE FROM $t_emojies_users_recent_used") or die(mysqli_error($link));
			

			$url = "index.php?open=$open&page=emojies_index&action=$action&emoji_id=$get_current_emoji_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved&ft_image=$ft_image&fm_image=$fm_image&ft_category=$ft_category&fm_category=$fm_category";
			header("Location: $url");
			exit;
		} // process

		echo"
		<h1>Edit $get_current_emoji_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Talk</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l\">Emojies index</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=open_main_category&amp;main_category_id=$get_current_emoji_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=$action&amp;emoji_id=$get_current_emoji_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_emoji_title</a>
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

		<!-- Edit form -->
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_website_title\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=emojies_index&amp;action=$action&amp;emoji_id=$get_current_emoji_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<h2>General</h2>
			<p>Title:<br />
			<input type=\"text\" name=\"inp_emoji_title\" value=\"$get_current_emoji_title\" size=\"50\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

			<p>Automatically replaces characters to this emoji:<br />
			<input type=\"text\" name=\"inp_emoji_replace_a\" value=\"$get_current_emoji_replace_a\" size=\"10\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />
			<input type=\"text\" name=\"inp_emoji_replace_b\" value=\"$get_current_emoji_replace_b\" size=\"10\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />
			<input type=\"text\" name=\"inp_emoji_replace_c\" value=\"$get_current_emoji_replace_c\" size=\"10\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p>Is active:<br />
			<input type=\"radio\" name=\"inp_emoji_is_active\" value=\"1\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_emoji_is_active == "1"){ echo" checked=\"checked\""; } echo" />
			Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_emoji_is_active\" value=\"0\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_emoji_is_active == "0"){ echo" checked=\"checked\""; } echo" />
			No
			</p>

			<p>Code (Uni Code):<br />
			<input type=\"text\" name=\"inp_emoji_code\" value=\"$get_current_emoji_code\" size=\"50\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>


			<p>Char (HTML Code) $get_current_emoji_char:<br />\n";
			$char = str_replace("&", "&amp;", $get_current_emoji_char);
			echo"<input type=\"text\" name=\"inp_emoji_char\" value=\"$char\" size=\"50\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>


			<p>Char output html:<br />
			<input type=\"text\" name=\"inp_emoji_char_output_html\" value=\"$get_current_emoji_char\" size=\"50\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

			<p>Skin tone:<br />
			<select name=\"inp_emoji_skin_tone\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"\""; if($get_current_emoji_skin_tone == ""){ echo" selected=\"selected\""; } echo">-</option>
				<option value=\"light skin tone\""; if($get_current_emoji_skin_tone == "light skin tone"){ echo" selected=\"selected\""; } echo">light skin tone</option>
				<option value=\"medium-light skin tone\""; if($get_current_emoji_skin_tone == "medium-light skin tone"){ echo" selected=\"selected\""; } echo">medium-light skin tone</option>
				<option value=\"medium skin tone\""; if($get_current_emoji_skin_tone == "medium skin tone"){ echo" selected=\"selected\""; } echo">medium skin tone</option>
				<option value=\"medium-dark skin tone\""; if($get_current_emoji_skin_tone == "medium-dark skin tone"){ echo" selected=\"selected\""; } echo">medium-dark skin tone</option>
				<option value=\"dark skin tone\""; if($get_current_emoji_skin_tone == "dark skin tone"){ echo" selected=\"selected\""; } echo">dark skin tone</option>
				<option value=\"\">-</option>
				<option value=\"medium-light skin tone, light skin tone\""; if($get_current_emoji_skin_tone == "medium-light skin tone, light skin tone"){ echo" selected=\"selected\""; } echo">medium-light skin tone, light skin tone</option>
				<option value=\"medium skin tone, light skin tone\""; if($get_current_emoji_skin_tone == "medium skin tone, light skin tone"){ echo" selected=\"selected\""; } echo">medium skin tone, light skin tone</option>
				<option value=\"medium skin tone, medium-light skin tone\""; if($get_current_emoji_skin_tone == "medium skin tone, medium-light skin tone"){ echo" selected=\"selected\""; } echo">medium skin tone, medium-light skin tone</option>
				<option value=\"medium-dark skin tone, light skin tone\""; if($get_current_emoji_skin_tone == "medium-dark skin tone, light skin tone"){ echo" selected=\"selected\""; } echo">medium-dark skin tone, light skin tone</option>
				<option value=\"medium-dark skin tone, medium-light skin tone\""; if($get_current_emoji_skin_tone == "medium-dark skin tone, medium-light skin tone"){ echo" selected=\"selected\""; } echo">medium-dark skin tone, medium-light skin tone</option>
				<option value=\"medium-dark skin tone, medium skin tone\""; if($get_current_emoji_skin_tone == "medium-dark skin tone, medium skin tone"){ echo" selected=\"selected\""; } echo">medium-dark skin tone, medium skin tone</option>
				<option value=\"\">-</option>

				<option value=\"dark skin tone, light skin tone\""; if($get_current_emoji_skin_tone == "dark skin tone, light skin tone"){ echo" selected=\"selected\""; } echo">dark skin tone, light skin tone</option>
				<option value=\"dark skin tone, medium-light skin tone\""; if($get_current_emoji_skin_tone == "dark skin tone, medium-light skin tone"){ echo" selected=\"selected\""; } echo">dark skin tone, medium-light skin tone</option>
				<option value=\"dark skin tone, medium skin tone\""; if($get_current_emoji_skin_tone == "dark skin tone, medium skin tone"){ echo" selected=\"selected\""; } echo">dark skin tone, medium skin tone</option>
				<option value=\"dark skin tone, medium-dark skin tone\""; if($get_current_emoji_skin_tone == "dark skin tone, medium-dark skin tone"){ echo" selected=\"selected\""; } echo">dark skin tone, medium-dark skin tone</option>
			</select>
			</p>

			<p>Emoji is related to emoji ID:<br />
			<input type=\"text\" name=\"inp_emoji_is_releated_emoji_id\" value=\"$get_current_emoji_is_releated_emoji_id\" size=\"4\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

			
			";
			if(file_exists("../$get_current_emoji_source_path/$get_current_emoji_source_file") && $get_current_emoji_source_file != ""){
				echo"
				<p><b>Existing image:</b><br />
				<img src=\"../$get_current_emoji_source_path/$get_current_emoji_source_file\" alt=\"$get_current_emoji_source_path/$get_current_emoji_source_file\" width=\"32\" height=\"32\" />
				</p>";
			}
			echo"

			<p><b>New image:</b><br />
			<input type=\"file\" name=\"inp_image\" /><br />
			<span class=\"smal\">Leave blank if you dont want any new image</span>
			</p>

			<p>Category:<br />
			<select name=\"inp_emoji_sub_category_id\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"\""; if($get_current_emoji_sub_category_id == ""){ echo" selected=\"selected\""; } echo">-</option>\n";
				$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_category_id, $get_main_category_title, $get_main_category_code, $get_main_category_char, $get_main_category_source_path, $get_main_main_category_source_file, $get_main_category_source_ext, $get_main_category_weight, $get_main_category_is_active, $get_main_category_language) = $row;
					echo"				<option value=\"\">$get_main_category_title</option>\n";

					$query_sub = "SELECT sub_category_id, sub_category_title, sub_category_parent_id, sub_category_code, sub_category_char, sub_category_source_path, sub_main_category_source_file, sub_category_source_ext, sub_category_weight, sub_category_is_active, sub_category_language FROM $t_emojies_categories_sub WHERE sub_category_parent_id=$get_main_category_id";
					$result_sub = mysqli_query($link, $query_sub);
					while($row_sub = mysqli_fetch_row($result_sub)) {
						list($get_sub_category_id, $get_sub_category_title, $get_sub_category_parent_id, $get_sub_category_code, $get_sub_category_char, $get_sub_category_source_path, $get_sub_main_category_source_file, $get_sub_category_source_ext, $get_sub_category_weight, $get_sub_category_is_active, $get_sub_category_language) = $row_sub;
					echo"				<option value=\"$get_sub_category_id\""; if($get_current_emoji_sub_category_id == "$get_sub_category_id"){ echo" selected=\"selected\""; } echo"> &nbsp; $get_sub_category_title</option>\n";


					}
					echo"				<option value=\"\"> </option>\n";

				}
				echo"
			</select>


			<p>
			<input type=\"submit\" value=\"Save\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Edit form -->

		";
	} // emoji found
} // edit emoji
elseif($action == "delete_emoji"){
	// find emoji
	$emoji_id_mysql = quote_smart($link, $emoji_id);
	$query = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_replace_a, emoji_replace_b, emoji_replace_c, emoji_is_active, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_is_releated_emoji_id, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index WHERE emoji_id=$emoji_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_emoji_id, $get_current_emoji_main_category_id, $get_current_emoji_sub_category_id, $get_current_emoji_title, $get_current_emoji_replace_a, $get_current_emoji_replace_b, $get_current_emoji_replace_c, $get_current_emoji_is_active, $get_current_emoji_code, $get_current_emoji_char, $get_current_emoji_source_path, $get_current_emoji_source_file, $get_current_emoji_source_ext, $get_current_emoji_skin_tone, $get_current_emoji_is_releated_emoji_id, $get_current_emoji_created_by_user_id, $get_current_emoji_created_datetime, $get_current_emoji_updated_by_user_id, $get_current_emoji_updated_datetime, $get_current_emoji_used_count, $get_current_emoji_last_used_datetime) = $row;
	
	if($get_current_emoji_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
		// find main category
		$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main WHERE main_category_id=$get_current_emoji_main_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_code, $get_current_main_category_char, $get_current_main_category_source_path, $get_current_main_main_category_source_file, $get_current_main_category_source_ext, $get_current_main_category_weight, $get_current_main_category_is_active, $get_current_main_category_language) = $row;
		
		if($process == "1"){
			// Delete emoji
			$result = mysqli_query($link, "DELETE FROM $t_emojies_index WHERE emoji_id=$get_current_emoji_id") or die(mysqli_error($link));


			// Delete recents
			$result = mysqli_query($link, "DELETE FROM $t_emojies_users_recent_used") or die(mysqli_error($link));
			

			$url = "index.php?open=$open&page=emojies_index&action=open_main_category&main_category_id=$get_current_main_category_id&editor_language=$editor_language&l=$l&ft=success&fm=emoji_deleted";
			header("Location: $url");
			exit;
		} // process

		echo"
		<h1>Delete $get_current_emoji_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Talk</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;editor_language=$editor_language&amp;l=$l\">Emojies index</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=open_main_category&amp;main_category_id=$get_current_emoji_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=$action&amp;emoji_id=$get_current_emoji_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_emoji_title</a>
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

		<!-- Delete form -->
			<p>Are you sure you want to delete?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=emojies_index&amp;action=$action&amp;emoji_id=$get_current_emoji_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Confirm</a>
			</p>
		<!-- //Delete form -->

		";
	} // emoji found
} // delete emoji
elseif($action == "fix_output_html"){
	if($process == "1"){
	
		/*
		$result_update = mysqli_query($link, "ALTER TABLE $t_emojies_index CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci") or die(mysqli_error($link));
		$result_update = mysqli_query($link, "ALTER TABLE $t_emojies_index DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci") or die(mysqli_error($link));
		$result_update = mysqli_query($link, "ALTER TABLE $t_emojies_index CHANGE `emoji_char_output_html` `emoji_char_output_html` VARCHAR(200) CHARACTER SET utf8_unicode_ci COLLATE utf8_unicode_ci NULL DEFAULT NULL") or die(mysqli_error($link));
		*/

		$query = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row;

				// inp_emoji_char_output_html
				$inp_emoji_char_output_html = $_POST["inp_emoji_char_output_html_$get_emoji_id"];
				$inp_emoji_char_output_html = output_html($inp_emoji_char_output_html);
				// $inp_emoji_char_output_html = str_replace("\\", "\\\\\\", $inp_emoji_char_output_html);
				$inp_emoji_char_output_html_mysql = quote_smart($link, $inp_emoji_char_output_html);
			
		

				echo"$get_emoji_id: $inp_emoji_char_output_html ";

				$result_update = mysqli_query($link, "UPDATE $t_emojies_index SET 
							emoji_char_output_html=$inp_emoji_char_output_html_mysql 
							 WHERE emoji_id=$get_emoji_id") or die(mysqli_error($link));

				echo"<br />\n";
		}

		echo"
		OK
		";
	}
	echo"
	<h1>Fix output_html for emojies (if emojies wont be saved in example Talk)</h1>

	<form method=\"post\" action=\"index.php?open=$open&amp;page=emojies_index&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	";

	$query = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row;

		echo"
		<p>$get_emoji_title:<br />
		<input type=\"text\" name=\"inp_emoji_char_output_html_$get_emoji_id\" value=\"$get_emoji_char\" size=\"50\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

		";
	}
	echo"
	<input type=\"submit\" value=\"Fix\" />
	</form>

	";
}
?>