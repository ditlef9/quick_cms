<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users_profile_headlines		= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations	= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields			= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations	= $mysqlPrefixSav . "users_profile_fields_translations";


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

	if($process == "1"){
		// Delete headline
		mysqli_query($link, "DELETE FROM $t_users_profile_headlines WHERE headline_id=$get_current_headline_id") or die(mysqli_error($link));

		// Delete headline translations
		mysqli_query($link, "DELETE FROM $t_users_profile_headlines_translations WHERE translation_headline_id=$get_current_headline_id") or die(mysqli_error($link));

		// Delete fields
		mysqli_query($link, "DELETE FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id") or die(mysqli_error($link));

		// Delete fields translations
		mysqli_query($link, "DELETE FROM $t_users_profile_fields_translations WHERE translation_headline_id=$get_current_headline_id") or die(mysqli_error($link));

		// Drop table
		$table_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;
		mysqli_query($link, "DROP TABLE IF EXISTS $table_users_profile_data") or die(mysqli_error($link));

		// Header
		$url = "index.php?open=$open&page=headlines&ft=success&fm=headline_deleted&editor_language=$editor_language&l=$l";
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
		<a href=\"index.php?open=users&amp;page=headline_delete&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">Delete headline</a>
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

	
	<p>
	Are you sure you want to delete the headline?
	</p>

	<p>
	<a href=\"index.php?open=users&amp;page=headline_delete&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
	</p>
	";
} // headline found
?>