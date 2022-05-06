<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";

/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Config ---------------------------------------------------------------------------- */

/*- Varialbes  ---------------------------------------------------- */
$tabindex = 0;

if (isset($_GET['headline_id'])) {
	$headline_id = $_GET['headline_id'];
	$headline_id = stripslashes(strip_tags($headline_id));
	if(!(is_numeric($headline_id))){
		echo"Headline id not numeric";
		die;
	}
}
else{
	echo"Missing headline id";
	die;
}
$headline_id_mysql = quote_smart($link, $headline_id);

// Get headline
$query = "SELECT headline_id, headline_title, headline_title_clean, headline_weight, headline_show_on_profile FROM $t_users_profile_headlines WHERE headline_id=$headline_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_headline_id, $get_current_headline_title, $get_current_headline_title_clean, $get_current_headline_weight, $get_current_headline_show_on_profile) = $row;

if($get_current_headline_id == ""){
	echo"
	<h1>Server error 404</h1>
	<p>Headline not found.</p>
	<p><a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a></p>
	";
}
else{
	if($action == ""){
		if($process == "1"){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);


			$inp_user_can_view_field = $_POST['inp_user_can_view_field'];
			$inp_user_can_view_field = output_html($inp_user_can_view_field);
			$inp_user_can_view_field_mysql = quote_smart($link, $inp_user_can_view_field);

			$inp_show_on_profile = $_POST['inp_show_on_profile'];
			$inp_show_on_profile = output_html($inp_show_on_profile);
			$inp_show_on_profile_mysql = quote_smart($link, $inp_show_on_profile);

			// Get weight
			$query = "SELECT count(field_id) FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_field_id) = $row;
			$inp_weight = $get_count_field_id+1;

			// Insert headline
			mysqli_query($link, "INSERT INTO $t_users_profile_fields
			(field_id, field_headline_id, field_title, field_title_clean, field_weight, 
			field_type, field_size, field_width, field_cols, field_rows, 
			field_user_can_view_field, field_show_on_profile) 
			VALUES 
			(NULL, $get_current_headline_id, $inp_title_mysql, $inp_title_clean_mysql, $inp_weight, 
			$inp_type_mysql, 25, '99%', 45, 5, 
			$inp_user_can_view_field_mysql, $inp_show_on_profile_mysql)")
			or die(mysqli_error($link));


			// Get field id
			$query = "SELECT field_id FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id AND field_title=$inp_title_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_field_id) = $row;
			
			// Translations
			$query = "SELECT language_active_id, language_active_iso_two FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_iso_two) = $row;

				// Language
				$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);

				$inp_value = $_POST["inp_title_$get_language_active_iso_two"];
				$inp_value = output_html($inp_value);
				$inp_value_mysql = quote_smart($link, $inp_value);
	
				// Insert
				mysqli_query($link, "INSERT INTO $t_users_profile_fields_translations
				(translation_id, translation_field_id, translation_headline_id, translation_language, translation_value) 
				VALUES 
				(NULL, $get_field_id, $get_current_headline_id, $inp_language_mysql, $inp_value_mysql)")
				or die(mysqli_error($link));

			}

			// Add field to table
			$table_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;
			$inp_title_clean_mysql = str_replace("'", "`", $inp_title_clean_mysql);
			if($inp_type == "text" OR $inp_type == "url" OR $inp_type == "radio"  OR $inp_type == "select"){
				mysqli_query($link, "ALTER TABLE $table_users_profile_data ADD $inp_title_clean_mysql VARCHAR(200) NULL DEFAULT NULL") or die(mysqli_error($link));
			}
			elseif($inp_type == "textarea"){
				mysqli_query($link, "ALTER TABLE $table_users_profile_data ADD $inp_title_clean_mysql TEXT NULL DEFAULT NULL") or die(mysqli_error($link));
			}
			else{
				mysqli_query($link, "ALTER TABLE $table_users_profile_data ADD $inp_title_clean_mysql INT NULL DEFAULT NULL") or die(mysqli_error($link));
			}

			// Header
			if($inp_type == "text" OR $inp_type == "textarea" OR $inp_type == "url"){
				$url = "index.php?open=$open&page=$page&headline_id=$get_current_headline_id&ft=success&fm=field_created&editor_language=$editor_language&l=$l";
			}
			elseif($inp_type == "radio" OR $inp_type == "select"){
				$url = "index.php?open=$open&page=field_options&action=new_option&headline_id=$get_current_headline_id&field_id=$get_field_id&ft=success&fm=field_created&editor_language=$editor_language&l=$l";
			}
			else{
				$url = "index.php?open=$open&page=$page&action=create_field_step2_$inp_type&headline_id=$get_current_headline_id&field_id=$get_field_id&ft=success&fm=field_created&editor_language=$editor_language&l=$l";
			}
			header("Location: $url");
			exit;
		}

		echo"
		<h1>$get_current_headline_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=users&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=headline_open&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_headline_title</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=field_new&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">New field</a>
			</p>
		<!-- //Where am I? -->
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- New field form -->

			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			<!-- //Focus -->


			<form method=\"POST\" action=\"index.php?open=users&amp;page=field_new&amp;headline_id=$get_current_headline_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">

			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" id=\"inp_title\" size=\"25\" value=\"\" style=\"width: 99%;\" /><br />
			</p>

			<!-- Javascript on type text add to translations -->
			<script>
			\$(document).ready(function(){
				\$('#inp_title').on('input', function() {
					var title = \$('#inp_title').val();\n";
					$query = "SELECT language_active_id, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

						echo"			";
						echo"\$('#inp_title_$get_language_active_iso_two').val(title);\n";
				
					}
					echo"			
				});
			});
			</script>
			<!-- //Javascript on type text add to translations -->


			<!-- Translations -->";
				$query = "SELECT language_active_id, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

					echo"
					<p>
					<img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" />
					Title $get_language_active_iso_two:<br />
					<input type=\"text\" name=\"inp_title_$get_language_active_iso_two\" id=\"inp_title_$get_language_active_iso_two\" size=\"25\" value=\"\" style=\"width: 99%;\" /><br />
					</p>
					";

				} // languages_active
				echo"
			<!-- //Translations -->

			<p>Type:<br />
			<select name=\"inp_type\">
				<option value=\"text\">Text</option>
				<option value=\"textarea\">Textarea</option>
				<option value=\"url\">URL</option>
				<option value=\"radio\">Radio</option>
				<option value=\"select\">Select</option>
				<option value=\"checkbox\">Checkbox</option>
			</select>
			</p>

			<p>User can view field (on my profile page): (<a href=\"../users/my_profile.php?l=$l\">Open example</a>)<br />
			<input type=\"radio\" name=\"inp_user_can_view_field\" value=\"1\" checked=\"checked\" /> Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_user_can_view_field\" value=\"0\" /> No
			</p>

			<p>Show on profile:<br />
			<input type=\"radio\" name=\"inp_show_on_profile\" value=\"1\" checked=\"checked\" /> Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_show_on_profile\" value=\"0\" /> No
			</p>

			<p>
			<input type=\"submit\" value=\"Create field\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //New field form -->
		";
	} // action == ""
} // headline found
?>