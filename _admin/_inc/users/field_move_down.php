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
		// Switch with
		$inp_weight = $get_current_field_weight+1;

		$query = "SELECT field_id, field_headline_id, field_title, field_title_clean, field_weight, field_height, field_type, field_size, field_width, field_cols, field_rows, field_user_can_view_field, field_show_on_profile FROM $t_users_profile_fields WHERE field_headline_id=$get_current_field_headline_id AND field_weight=$inp_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_field_id, $get_switch_field_headline_id, $get_switch_field_title, $get_switch_field_title_clean, $get_switch_field_weight, $get_switch_field_height, $get_switch_field_type, $get_switch_field_size, $get_switch_field_width, $get_switch_field_cols, $get_switch_field_rows, $get_switch_field_user_can_view_field, $get_switch_field_show_on_profile) = $row;

		if($get_switch_field_id == ""){
			$url = "index.php?open=users&page=headline_open&headline_id=$get_current_headline_id&editor_language=$editor_language&l=$l&ft=info&fm=can_not_move_it_down";
			header("Location: $url");
			exit;
		}
		
		// Switch
		mysqli_query($link, "UPDATE $t_users_profile_fields SET field_weight=$get_switch_field_weight WHERE field_id=$get_current_field_id") or die(mysqli_error($link));
		mysqli_query($link, "UPDATE $t_users_profile_fields SET field_weight=$get_current_field_weight WHERE field_id=$get_switch_field_id") or die(mysqli_error($link));
		
		// Move in table
		$table_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;

		$current_title_clean_mysql = quote_smart($link, $get_current_field_title_clean);
		$current_title_clean_mysql = str_replace("'", "`", $current_title_clean_mysql);

		$switch_title_clean_mysql = quote_smart($link, $get_switch_field_title_clean);
		$switch_title_clean_mysql = str_replace("'", "`", $switch_title_clean_mysql);

		if($get_switch_field_type == "text" OR $get_switch_field_type == "url" OR $get_switch_field_type == "radio"  OR $get_switch_field_type == "select"){
			mysqli_query($link, "ALTER TABLE $table_users_profile_data CHANGE $current_title_clean_mysql $current_title_clean_mysql VARCHAR(200) NOT NULL AFTER $switch_title_clean_mysql") or die(mysqli_error($link));
		}
		elseif($get_switch_field_type == "textarea"){
			mysqli_query($link, "ALTER TABLE $table_users_profile_data CHANGE $current_title_clean_mysql $current_title_clean_mysql TEXT NOT NULL AFTER $switch_title_clean_mysql") or die(mysqli_error($link));
		}
		else{
			mysqli_query($link, "ALTER TABLE $table_users_profile_data CHANGE $current_title_clean_mysql $current_title_clean_mysql INT NOT NULL AFTER $switch_title_clean_mysql") or die(mysqli_error($link));
		}

		// Header
		$url = "index.php?open=users&page=headline_open&headline_id=$get_current_headline_id&editor_language=$editor_language&l=$l&ft=success&fm=moved_down";
		header("Location: $url");
		exit;

	} // action == ""
} // headline found
?>