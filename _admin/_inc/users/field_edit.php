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

	if(isset($_GET['field_id'])) {
		$field_id = $_GET['field_id'];
		$field_id = stripslashes(strip_tags($field_id));
		if(!(is_numeric($field_id))){
			echo"Field id not numeric";
			die;
		}
	}
	else{
		echo"Missing field id";
		die;
	}
	$field_id_mysql = quote_smart($link, $field_id);

	// Get field
	$query = "SELECT field_id, field_headline_id, field_title, field_title_clean, field_weight, field_height, field_type, field_size, field_width, field_cols, field_rows, field_user_can_view_field, field_show_on_profile FROM $t_users_profile_fields WHERE field_id=$field_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_field_id, $get_current_field_headline_id, $get_current_field_title, $get_current_field_title_clean, $get_current_field_weight, $get_current_field_height, $get_current_field_type, $get_current_field_size, $get_current_field_width, $get_current_field_cols, $get_current_field_rows, $get_current_field_user_can_view_field, $get_current_field_show_on_profile) = $row;
	if($get_current_field_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Field not found.</p>
		<p><a href=\"index.php?open=users&amp;page=headline_open&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">Fields</a></p>
		";
	}
	else{
		if($process == "1"){
			
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_size = $_POST['inp_size'];
			$inp_size = output_html($inp_size);
			$inp_size_mysql = quote_smart($link, $inp_size);

			$inp_width = $_POST['inp_width'];
			$inp_width = output_html($inp_width);
			$inp_width_mysql = quote_smart($link, $inp_width);

			$inp_cols = $_POST['inp_cols'];
			$inp_cols = output_html($inp_cols);
			$inp_cols_mysql = quote_smart($link, $inp_cols);

			$inp_rows = $_POST['inp_rows'];
			$inp_rows = output_html($inp_rows);
			$inp_rows_mysql = quote_smart($link, $inp_rows);

			$inp_user_can_view_field = $_POST['inp_user_can_view_field'];
			$inp_user_can_view_field = output_html($inp_user_can_view_field);
			$inp_user_can_view_field_mysql = quote_smart($link, $inp_user_can_view_field);

			$inp_show_on_profile = $_POST['inp_show_on_profile'];
			$inp_show_on_profile = output_html($inp_show_on_profile);
			$inp_show_on_profile_mysql = quote_smart($link, $inp_show_on_profile);

			// Update field
			mysqli_query($link, "UPDATE $t_users_profile_fields SET
						field_title=$inp_title_mysql, 
						field_title_clean=$inp_title_clean_mysql, 
						field_type=$inp_type_mysql, 
						field_size=$inp_size_mysql, 
						field_width=$inp_width_mysql, 
						field_cols=$inp_cols_mysql, 
						field_rows=$inp_rows_mysql, 
						field_user_can_view_field=$inp_user_can_view_field_mysql, 
						field_show_on_profile=$inp_show_on_profile_mysql 
						WHERE field_id=$get_current_field_id") or die(mysqli_error($link));

			// Title translations
			$query = "SELECT language_active_id, language_active_iso_two FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_iso_two) = $row;

				// Language
				$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);

				$inp_value = $_POST["inp_title_$get_language_active_iso_two"];
				$inp_value = output_html($inp_value);
				$inp_value_mysql = quote_smart($link, $inp_value);
				
	
				// Update
				mysqli_query($link, "UPDATE $t_users_profile_fields_translations SET
							translation_value=$inp_value_mysql
						     WHERE translation_field_id=$get_current_field_id AND translation_language=$inp_language_mysql")or die(mysqli_error($link));

			}

			// Rename db fields
			if($inp_title_clean != "$get_current_headline_title_clean"){
				$table_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;

				$old_title_clean_mysql = quote_smart($link, $get_current_field_title_clean);
				$old_title_clean_mysql = str_replace("'", "`", $old_title_clean_mysql);
				$new_title_clean_mysql = str_replace("'", "`", $inp_title_clean_mysql);

				if($inp_type == "text" OR $inp_type == "url" OR $inp_type == "radio"  OR $inp_type == "select"){
					mysqli_query($link, "ALTER TABLE $table_users_profile_data CHANGE $old_title_clean_mysql $new_title_clean_mysql VARCHAR(200) NULL DEFAULT NULL") or die(mysqli_error($link));
				}
				elseif($inp_type == "textarea"){
					mysqli_query($link, "ALTER TABLE $table_users_profile_data CHANGE $old_title_clean_mysql $new_title_clean_mysql TEXT NULL DEFAULT NULL") or die(mysqli_error($link));
				}
				else{
					mysqli_query($link, "ALTER TABLE $table_users_profile_data CHANGE $old_title_clean_mysql $new_title_clean_mysql INT NULL DEFAULT NULL") or die(mysqli_error($link));
				}
			}
		
			if (isset($_POST['open_options'])) {
				// Header
				$time = date("H:i:s");
				$url = "index.php?open=users&page=field_options&headline_id=$get_current_headline_id&field_id=$get_current_field_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved_$time";
				header("Location: $url");
				exit;
       	
			}
			else{
				// Header
				$time = date("H:i:s");
				$url = "index.php?open=users&page=field_edit&headline_id=$get_current_headline_id&field_id=$get_current_field_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved_$time";
				header("Location: $url");
				exit;
			}


			
		} // field

		echo"
		<h1>$get_current_field_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=users&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=headline_open&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_headline_title</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=field_edit&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_field_title</a>
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

		<!-- Edit field form -->

			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			<!-- //Focus -->


			<form method=\"POST\" action=\"index.php?open=users&amp;page=field_edit&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">

			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" size=\"25\" value=\"$get_current_field_title\" style=\"width: 99%;\" />
			</p>

			<!-- Translations -->";
				$query = "SELECT language_active_id, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

					// Language
					$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);
	
					// Get translation
					$query_t = "SELECT translation_id, translation_field_id, translation_language, translation_value FROM $t_users_profile_fields_translations WHERE translation_field_id=$get_current_field_id AND translation_language=$inp_language_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_translation_id, $get_translation_field_id, $get_translation_language, $get_translation_value) = $row_t;
					if($get_translation_id == ""){
						$inp_title_mysql = quote_smart($link, $get_current_field_title);
						mysqli_query($link, "INSERT INTO $t_users_profile_fields_translations
						(translation_id, translation_field_id, translation_headline_id, translation_language, translation_value) 
						VALUES 
						(NULL, $get_current_headline_id, $get_current_field_id, $inp_language_mysql, $inp_title_mysql)")
						or die(mysqli_error($link));
						$get_translation_value = "$get_current_field_title";
					}

					echo"
					<p>
					<img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" />
					Title $get_language_active_iso_two:<br />
					<input type=\"text\" name=\"inp_title_$get_language_active_iso_two\" size=\"25\" value=\"$get_translation_value\" style=\"width: 99%;\" /><br />
					</p>
					";

				} // languages_active
				echo"
			<!-- //Translations -->

			<p>Type:<br />
			<select name=\"inp_type\">
				<option value=\"text\""; if($get_current_field_type == "text"){ echo" selected=\"selected\""; } echo">Text</option>
				<option value=\"textarea\""; if($get_current_field_type == "textarea"){ echo" selected=\"selected\""; } echo">Textarea</option>
				<option value=\"url\""; if($get_current_field_type == "url"){ echo" selected=\"selected\""; } echo">URL</option>
				<option value=\"radio\""; if($get_current_field_type == "radio"){ echo" selected=\"selected\""; } echo">Radio</option>
				<option value=\"select\""; if($get_current_field_type == "select"){ echo" selected=\"selected\""; } echo">Select</option>
				<option value=\"checkbox\""; if($get_current_field_type == "checkbox"){ echo" selected=\"selected\""; } echo">Checkbox</option>
			</select>
			</p>

			<!-- Options -->
				";
				if($get_current_field_type == "radio" OR $get_current_field_type == "select"){
					echo"
					<p>Options:</p>
					<ul>";

					$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_field_id=$get_current_field_id ORDER BY option_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_option_id, $get_option_field_id, $get_option_headline_id, $get_option_title, $get_option_title_clean, $get_option_weight) = $row;
						echo"
						<li><span>$get_option_title</span></li>
						";

					}
					echo"
					</ul>
					<p>
					<input type=\"submit\" name=\"open_options\" value=\"Open options\" class=\"btn_default\" />
					</p>
					";
				}
				echo"
			<!-- //Options -->

			<!-- Size -->
				";
				if($get_current_field_type == "text" OR $get_current_field_type == "url"){ 
					echo"
					<p>Size:<br />
					<input type=\"text\" name=\"inp_size\" size=\"5\" value=\"$get_current_field_size\" />
					</p>
					";
				}
				else{
					echo"
					<span><input type=\"hidden\" name=\"inp_size\" value=\"$get_current_field_size\" /></span>
					";
				}
				echo"
			<!-- //Size -->

			<!-- Width -->

				";
				if($get_current_field_type == "text" OR $get_current_field_type == "textarea" OR $get_current_field_type == "url"){ 
					echo"
					<p>Width:<br />
					<input type=\"text\" name=\"inp_width\" size=\"5\" value=\"$get_current_field_width\" />
					</p>
					";
				}
				else{
					echo"
					<span><input type=\"hidden\" name=\"inp_width\" value=\"$get_current_field_width\" /></span>
					";
				}
				echo"
			<!-- //Width -->

			<!-- Rows and cols -->
				";
				if($get_current_field_type == "textarea"){ 
					echo"
					<p>Rows and cols:<br />
					<input type=\"text\" name=\"inp_rows\" size=\"5\" value=\"$get_current_field_rows\" />
					<input type=\"text\" name=\"inp_cols\" size=\"5\" value=\"$get_current_field_cols\" />
					</p>
					";
				}
				else{
					echo"
					<span>
					<input type=\"hidden\" name=\"inp_rows\" size=\"5\" value=\"$get_current_field_rows\" />
					<input type=\"hidden\" name=\"inp_cols\" size=\"5\" value=\"$get_current_field_cols\" />
					</span>
					";
				}
				echo"
			<!-- //Rows and cols -->

			<p>User can view field (on my profile page): (<a href=\"../users/my_profile.php?l=$l\">Open example</a>)<br />
			<input type=\"radio\" name=\"inp_user_can_view_field\" value=\"1\""; if($get_current_field_user_can_view_field == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_user_can_view_field\" value=\"0\""; if($get_current_field_user_can_view_field == "0"){ echo" checked=\"checked\""; } echo"  /> No
			</p>


			<p>Show on profile:<br />
			<input type=\"radio\" name=\"inp_show_on_profile\" value=\"1\""; if($get_current_field_show_on_profile == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_show_on_profile\" value=\"0\""; if($get_current_field_show_on_profile == "0"){ echo" checked=\"checked\""; } echo"  /> No
			</p>

			<p>
			<input type=\"submit\" name=\"save_changes\" value=\"Save changes\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Edit field form -->
		";
	} // action == ""
} // headline found
?>