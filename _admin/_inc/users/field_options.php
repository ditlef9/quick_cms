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
	$query = "SELECT field_id, field_headline_id, field_title, field_title_clean, field_weight, field_type, field_size, field_width, field_cols, field_rows, field_show_on_profile FROM $t_users_profile_fields WHERE field_id=$field_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_field_id, $get_current_field_headline_id, $get_current_field_title, $get_current_field_title_clean, $get_current_field_weight, $get_current_field_type, $get_current_field_size, $get_current_field_width, $get_current_field_cols, $get_current_field_rows, $get_current_field_show_on_profile) = $row;
	if($get_current_field_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Field not found.</p>
		<p><a href=\"index.php?open=users&amp;page=headline_open&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">Fields</a></p>
		";
	}
	else{
		if($action == ""){
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
				&gt;
				<a href=\"index.php?open=users&amp;page=field_options&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;editor_language=$editor_language&amp;l=$l\">Field options</a>
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

			<!-- Field options list -->
				<p>
				<a href=\"index.php?open=$open&amp;page=field_options&amp;action=new_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New option</a>
				</p>

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
				$y = 1;
				$query = "SELECT option_id, option_title, option_weight FROM $t_users_profile_fields_options WHERE option_field_id=$get_current_field_id ORDER BY option_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_option_id, $get_option_title, $get_option_weight) = $row;

					echo"
					 <tr>
					  <td>
						<span><a href=\"index.php?open=$open&amp;page=field_options&amp;action=edit_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_option_id&amp;editor_language=$editor_language&amp;l=$l\">$get_option_title</a></span>
					  </td>
					  <td>
						<span>
						<a href=\"index.php?open=$open&amp;page=field_options&amp;action=move_option_up&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_option_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_upward_round_black_18x18.png\" alt=\"arrow_upward_round_black_18x18.png\" /></a>
						<a href=\"index.php?open=$open&amp;page=field_options&amp;action=move_option_down&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_option_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_downward_round_black_18x18.png\" alt=\"arrow_downward_round_black_18x18.png\" /></a>
						<a href=\"index.php?open=$open&amp;page=field_options&amp;action=edit_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_option_id&amp;editor_language=$editor_language&amp;l=$l&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/edit_round_black_18x18.png\" alt=\"edit_round_black_18x18.png\" /></a>
						<a href=\"index.php?open=$open&amp;page=field_options&amp;action=delete_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_option_id&amp;editor_language=$editor_language&amp;l=$l&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/delete_round_black_18x18.png\" alt=\"delete_round_black_18x18.png\" /></a>
						</span>
					  </td>
					 </tr>
					";

					// Weight
					if($y != "$get_option_weight" OR $get_option_weight == ""){
						mysqli_query($link, "UPDATE $t_users_profile_fields_options SET option_weight=$y WHERE option_id=$get_option_id") or die(mysqli_error($link));
					}
					$y++;

				}
				echo"
	
				 </tbody>
				</table>

			<!-- //Field options list-->
			";
		} // action == ""
		elseif($action == "new_option"){
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


				// Get weight
				$query = "SELECT count(option_id) FROM $t_users_profile_fields_options WHERE option_field_id=$get_current_field_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_option_id) = $row;
				$inp_weight = $get_count_option_id+1;

				// Insert option
				mysqli_query($link, "INSERT INTO $t_users_profile_fields_options 
				(option_id, option_field_id, option_headline_id, option_title, option_title_clean, 
				option_weight) 
				VALUES 
				(NULL, $get_current_field_id, $get_current_headline_id, $inp_title_mysql, $inp_title_clean_mysql, 
				$inp_weight)")
				or die(mysqli_error($link));


				// Get option id
				$query = "SELECT option_id FROM $t_users_profile_fields_options WHERE option_field_id=$get_current_field_id AND option_title=$inp_title_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_option_id) = $row;
			
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
					mysqli_query($link, "INSERT INTO $t_users_profile_fields_options_translations
					(translation_id, translation_option_id, translation_field_id, translation_headline_id, translation_language, translation_value) 
					VALUES 
					(NULL, $get_option_id, $get_current_field_id, $get_current_headline_id, $inp_language_mysql, $inp_value_mysql)")
					or die(mysqli_error($link));

				}

				// Header
				$url = "index.php?open=$open&page=$page&action=new_option&headline_id=$get_current_headline_id&field_id=$get_current_field_id&ft=success&fm=option_added&editor_language=$editor_language&l=$l";
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

			<p>
			Radio and select needs options. Here you cann add new a new option.
			</p>

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->

			<!-- New option form -->
				<h2>New option for $get_current_field_title</h2>

				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>
				<!-- //Focus -->


				<form method=\"POST\" action=\"index.php?open=users&amp;page=field_options&amp;action=new_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">

				<p>Title:<br />
				<input type=\"text\" name=\"inp_title\" size=\"25\" value=\"\" style=\"width: 99%;\" /><br />
				</p>

				<!-- Translations -->";
					$query = "SELECT language_active_id, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

						echo"
						<p>
						<img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" />
						Title $get_language_active_iso_two:<br />
						<input type=\"text\" name=\"inp_title_$get_language_active_iso_two\" size=\"25\" value=\"\" style=\"width: 99%;\" /><br />
						</p>
						";

					} // languages_active
					echo"
				<!-- //Translations -->
			
				<p>
				<input type=\"submit\" value=\"Create option\" class=\"btn_default\" />
				<a href=\"index.php?open=users&amp;page=field_options&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Option overview -&gt;</a>
				</p>
				</form>
			<!-- //New option form -->
			
			";
		} // action == "new_option"
		elseif($action == "move_option_up"){
			if (isset($_GET['option_id'])) {
				$option_id = $_GET['option_id'];
				$option_id = stripslashes(strip_tags($option_id));
				if(!(is_numeric($option_id))){
					echo"Option id not numeric";
					die;
				}
			}
			else{
				echo"Missing option id";
				die;
			}
			$option_id_mysql = quote_smart($link, $option_id);
			
			// Get option
			$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_id=$option_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_option_id, $get_current_option_field_id, $get_current_option_headline_id, $get_current_option_title, $get_current_option_title_clean, $get_current_option_weight) = $row;

			if($get_current_option_id == ""){
				echo"
				<h1>Server error 404</h1>
				<p>Option not found.</p>
				<p><a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a></p>
				";
			}
			else{
				$switch_with_weight = $get_current_option_weight-1;
				$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_field_id=$get_current_field_id AND option_headline_id=$get_current_headline_id AND option_weight=$switch_with_weight";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_switch_option_id, $get_switch_option_field_id, $get_switch_option_headline_id, $get_switch_option_title, $get_switch_option_title_clean, $get_switch_option_weight) = $row;
				if($get_switch_option_id == ""){
					$url = "index.php?open=$open&page=$page&headline_id=$get_current_headline_id&field_id=$get_current_field_id&ft=info&fm=nothing_to_switch_with&editor_language=$editor_language&l=$l";
					header("Location: $url");
					exit;
				}

				// Switch
				mysqli_query($link, "UPDATE $t_users_profile_fields_options SET option_weight=$get_switch_option_weight WHERE option_id=$get_current_option_id") or die(mysqli_error($link));
				mysqli_query($link, "UPDATE $t_users_profile_fields_options SET option_weight=$get_current_option_weight WHERE option_id=$get_switch_option_id") or die(mysqli_error($link));


				$url = "index.php?open=$open&page=$page&headline_id=$get_current_headline_id&field_id=$get_current_field_id&ft=success&fm=switched&editor_language=$editor_language&l=$l";
				header("Location: $url");
				exit;
			} // option found
		} // action == "move_option_up"
		elseif($action == "move_option_down"){
			if (isset($_GET['option_id'])) {
				$option_id = $_GET['option_id'];
				$option_id = stripslashes(strip_tags($option_id));
				if(!(is_numeric($option_id))){
					echo"Option id not numeric";
					die;
				}
			}
			else{
				echo"Missing option id";
				die;
			}
			$option_id_mysql = quote_smart($link, $option_id);
			
			// Get option
			$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_id=$option_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_option_id, $get_current_option_field_id, $get_current_option_headline_id, $get_current_option_title, $get_current_option_title_clean, $get_current_option_weight) = $row;

			if($get_current_option_id == ""){
				echo"
				<h1>Server error 404</h1>
				<p>Option not found.</p>
				<p><a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a></p>
				";
			}
			else{
				$switch_with_weight = $get_current_option_weight+1;
				$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_field_id=$get_current_field_id AND option_headline_id=$get_current_headline_id AND option_weight=$switch_with_weight";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_switch_option_id, $get_switch_option_field_id, $get_switch_option_headline_id, $get_switch_option_title, $get_switch_option_title_clean, $get_switch_option_weight) = $row;
				if($get_switch_option_id == ""){
					$url = "index.php?open=$open&page=$page&headline_id=$get_current_headline_id&field_id=$get_current_field_id&ft=info&fm=nothing_to_switch_with&editor_language=$editor_language&l=$l";
					header("Location: $url");
					exit;
				}

				// Switch
				mysqli_query($link, "UPDATE $t_users_profile_fields_options SET option_weight=$get_switch_option_weight WHERE option_id=$get_current_option_id") or die(mysqli_error($link));
				mysqli_query($link, "UPDATE $t_users_profile_fields_options SET option_weight=$get_current_option_weight WHERE option_id=$get_switch_option_id") or die(mysqli_error($link));

				

				$url = "index.php?open=$open&page=$page&headline_id=$get_current_headline_id&field_id=$get_current_field_id&ft=success&fm=switched&editor_language=$editor_language&l=$l";
				header("Location: $url");
				exit;
			} // option found
		} // action == "move_option_down"
		elseif($action == "edit_option"){
			if (isset($_GET['option_id'])) {
				$option_id = $_GET['option_id'];
				$option_id = stripslashes(strip_tags($option_id));
				if(!(is_numeric($option_id))){
					echo"Option id not numeric";
					die;
				}
			}
			else{
				echo"Missing option id";
				die;
			}
			$option_id_mysql = quote_smart($link, $option_id);
			
			// Get option
			$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_id=$option_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_option_id, $get_current_option_field_id, $get_current_option_headline_id, $get_current_option_title, $get_current_option_title_clean, $get_current_option_weight) = $row;

			if($get_current_option_id == ""){
				echo"
				<h1>Server error 404</h1>
				<p>Option not found.</p>
				<p><a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a></p>
				";
			}
			else{
				
				if($process == "1"){
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_title_clean = clean($inp_title);
					$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


					// Update option
					mysqli_query($link, "UPDATE $t_users_profile_fields_options SET
								option_title=$inp_title_mysql, 
								option_title_clean=$inp_title_clean_mysql
								WHERE option_id=$get_current_option_id") or die(mysqli_error($link));


				
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

						// Update
						mysqli_query($link, "UPDATE $t_users_profile_fields_options_translations SET
									translation_value=$inp_value_mysql
									WHERE translation_option_id=$get_current_option_id AND translation_language=$inp_language_mysql") or die(mysqli_error($link));

				}

				// Header
				$url = "index.php?open=$open&page=$page&action=edit_option&headline_id=$get_current_headline_id&field_id=$get_current_field_id&option_id=$get_current_option_id&ft=success&fm=changes_saved&editor_language=$editor_language&l=$l";
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
				<a href=\"index.php?open=users&amp;page=field_edit&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_field_title</a>
				&gt;
				<a href=\"index.php?open=users&amp;page=field_options&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_current_option_id&amp;editor_language=$editor_language&amp;l=$l\">Field options</a>
				&gt;
				<a href=\"index.php?open=users&amp;page=field_options&amp;action=edit_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_current_option_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_option_title</a>
				</p>
			<!-- //Where am I? -->

			<p>
			Radio and select needs options. Here you cann add new a new option.
			</p>

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->

			<!-- Edit option form -->
				<h2>New option for $get_current_field_title</h2>

				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>
				<!-- //Focus -->


				<form method=\"POST\" action=\"index.php?open=users&amp;page=field_options&amp;action=edit_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_current_option_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">

				<p>Title:<br />
				<input type=\"text\" name=\"inp_title\" size=\"25\" value=\"$get_current_option_title\" style=\"width: 99%;\" /><br />
				</p>

				<!-- Translations -->";
					$query = "SELECT language_active_id, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

						// Get translation
						$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);
						$query_t = "SELECT translation_id, translation_value FROM $t_users_profile_fields_options_translations WHERE translation_option_id=$get_current_option_id AND translation_language=$inp_language_mysql";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_translation_id, $get_translation_value) = $row_t;


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
			
				<p>
				<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
				</p>
				</form>
				<!-- //Edit option form -->
				";
			} // option found
		} // action == "edit_option"
		elseif($action == "delete_option"){
			if (isset($_GET['option_id'])) {
				$option_id = $_GET['option_id'];
				$option_id = stripslashes(strip_tags($option_id));
				if(!(is_numeric($option_id))){
					echo"Option id not numeric";
					die;
				}
			}
			else{
				echo"Missing option id";
				die;
			}
			$option_id_mysql = quote_smart($link, $option_id);
			
			// Get option
			$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_id=$option_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_option_id, $get_current_option_field_id, $get_current_option_headline_id, $get_current_option_title, $get_current_option_title_clean, $get_current_option_weight) = $row;

			if($get_current_option_id == ""){
				echo"
				<h1>Server error 404</h1>
				<p>Option not found.</p>
				<p><a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a></p>
				";
			}
			else{
				
				if($process == "1"){
					// Delete option
					mysqli_query($link, "DELETE FROM $t_users_profile_fields_options WHERE option_id=$get_current_option_id") or die(mysqli_error($link));

					mysqli_query($link, "DELETE FROM $t_users_profile_fields_options_translations WHERE translation_option_id=$get_current_option_id") or die(mysqli_error($link));

					// Header
					$url = "index.php?open=$open&page=$page&headline_id=$get_current_headline_id&field_id=$get_current_field_id&option_id=$get_current_option_id&ft=success&fm=option_deleted&editor_language=$editor_language&l=$l";
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
				<a href=\"index.php?open=users&amp;page=field_edit&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_field_title</a>
				&gt;
				<a href=\"index.php?open=users&amp;page=field_options&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_current_option_id&amp;editor_language=$editor_language&amp;l=$l\">Field options</a>
				&gt;
				<a href=\"index.php?open=users&amp;page=field_options&amp;action=delete_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_current_option_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_option_title</a>
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

				<!-- Delete option form -->
				<h2>Delete option $get_current_option_title</h2>

				<p>Are you sure you want to delete the option?</p>
					<p>
					<a href=\"index.php?open=users&amp;page=field_options&amp;action=delete_option&amp;headline_id=$get_current_headline_id&amp;field_id=$get_current_field_id&amp;option_id=$get_current_option_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
					</p>
				<!-- //Delete option form -->
				";
			} // option found
		} // action == "edit_option"
	} // field found
} // headline found
?>