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


if($process == "1"){

	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);
	$inp_title_mysql = quote_smart($link, $inp_title);

	$inp_title_clean = clean($inp_title);
	$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

	$inp_user_can_view_headline = $_POST['inp_user_can_view_headline'];
	$inp_user_can_view_headline = output_html($inp_user_can_view_headline);
	$inp_user_can_view_headline_mysql = quote_smart($link, $inp_user_can_view_headline);

	$inp_show_on_profile = $_POST['inp_show_on_profile'];
	$inp_show_on_profile = output_html($inp_show_on_profile);
	$inp_show_on_profile_mysql = quote_smart($link, $inp_show_on_profile);

	// Get weight
	$query = "SELECT count(headline_id) FROM $t_users_profile_headlines";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_headline_id) = $row;
	$inp_weight = $get_count_headline_id+1;

	// Insert headline
	mysqli_query($link, "INSERT INTO $t_users_profile_headlines
	(headline_id, headline_title, headline_title_clean, headline_weight, headline_user_can_view_headline, headline_show_on_profile) 
	VALUES 
	(NULL, $inp_title_mysql, $inp_title_clean_mysql, $inp_weight, $inp_user_can_view_headline_mysql, $inp_show_on_profile_mysql)")
	or die(mysqli_error($link));


	// Get headline id
	$query = "SELECT headline_id FROM $t_users_profile_headlines WHERE headline_title=$inp_title_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_headline_id) = $row;
			
	// Translations
	$query = "SELECT language_active_id, language_active_iso_two FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_iso_two) = $row;

		// Language
		$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);

		// Translation
		$inp_value = $_POST["inp_title_$get_language_active_iso_two"];
		$inp_value = output_html($inp_value);
		$inp_value_mysql = quote_smart($link, $inp_value);
	
		// Insert
		mysqli_query($link, "INSERT INTO $t_users_profile_headlines_translations
		(translation_id, translation_headline_id, translation_language, translation_value) 
		VALUES 
		(NULL, $get_headline_id, $inp_language_mysql, $inp_value_mysql)")
		or die(mysqli_error($link));

	}


	// Icon path
	if(!(is_dir("../_uploads"))){
		mkdir("../_uploads");
	}
	if(!(is_dir("../_uploads/users"))){
		mkdir("../_uploads/users");
	}
	if(!(is_dir("../_uploads/users/headlines"))){
		mkdir("../_uploads/users/headlines");
	}
	
	// Icon :: File
	$ft_icon = "";
	$fm_icon = "";
	$file_name = basename($_FILES['inp_icon_18x18']['name']);
	$file_exp = explode('.', $file_name); 
	$file_type = $file_exp[count($file_exp) -1]; 
	$file_type = strtolower("$file_type");
		
	if($file_name != ""){
		// Icon :: Variables
		$new_name = clean(output_html($file_name));
		$upload_path = "../_uploads/users/headlines";
		$target_path = $upload_path . "/" . $new_name;
		
		// Upload icon
		if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif"){
			if(move_uploaded_file($_FILES['inp_icon_18x18']['tmp_name'], $target_path)) {
				// Sjekk om det faktisk er et bilde som er lastet opp
				$image_size = getimagesize($target_path);
				if(is_numeric($image_size[0]) && is_numeric($image_size[1])){

					// icon_path
					$inp_path = "_uploads/users/headlines";
					$inp_path_mysql = quote_smart($link, $inp_path);

					// icon_file
					$inp_file_mysql = quote_smart($link, $new_name);

					// Update
					mysqli_query($link, "UPDATE $t_users_profile_headlines SET 
								headline_icon_path_18x18=$inp_path_mysql, 
								headline_icon_file_18x18=$inp_file_mysql
								WHERE headline_id=$get_headline_id") or die(mysqli_error($link));

				}
				else{
					// Dette er en fil som har fått byttet filendelse...
					unlink("$target_path");

					$ft_icon = "error";
					$fm_icon = "file_is_not_an_image";
				}
			}
			else{
				switch ($_FILES['inp_icon_18x18'] ['error']){
					case 1:
						$ft_icon = "error";
						$fm_icon = "to_big_file";
						break;
					case 2:
						$ft_icon = "error";
						$fm_icon = "to_big_file";
						break;
					case 3:
						$ft_icon = "error";
						$fm_icon = "only_parts_uploaded";
						break;
					case 4:
						$ft_icon = "info";
						$fm_icon = "no_file_uploaded";
						break;
				}
			} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		}
		else{
			$ft_icon = "info";
			$fm_icon = "image_could_not_be_uploaded_because_it_was_not_gif_png_or_jpg";
		}
	} // isset icon 18x18
	else{
		$ft_icon = "info";
		$fm_icon = "no_file_selected";
	}

	// Table
	$table_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $inp_title_clean;
	mysqli_query($link, "CREATE TABLE $table_users_profile_data(
			   data_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(data_id), 
			   data_user_id INT)")
			   or die(mysqli_error($link));

	// Header
	$url = "index.php?open=$open&page=$page&ft=success&fm=headline_created&editor_language=$editor_language&l=$l";
	header("Location: $url");
	exit;
}


echo"
<h1>New headline</h1>

<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=users&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
	&gt;
	<a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a>
	&gt;
	<a href=\"index.php?open=users&amp;page=headline_new&amp;editor_language=$editor_language&amp;l=$l\">New headline</a>
	</p>
<!-- //Where am I? -->

<form method=\"POST\" action=\"index.php?open=users&amp;page=headline_new&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">

<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = str_replace("_", " ", $fm);
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
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

		// Language
		$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);
	
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

<!-- Icon -->
	<p>New icon (18x18):<br />
	<input type=\"file\" name=\"inp_icon_18x18\" />
	</p>
<!-- //Icon -->


<p>User can view headline (on my profile page): (<a href=\"../users/my_profile.php?l=$l\">Open example</a>)<br />
<input type=\"radio\" name=\"inp_user_can_view_headline\" value=\"1\" checked=\"checked\" /> Yes
&nbsp;
<input type=\"radio\" name=\"inp_user_can_view_headline\" value=\"0\" /> No
</p>

<p>Show on profile:<br />
<input type=\"radio\" name=\"inp_show_on_profile\" value=\"1\" checked=\"checked\" /> Yes
&nbsp;
<input type=\"radio\" name=\"inp_show_on_profile\" value=\"0\" /> No
</p>

<p>
<input type=\"submit\" value=\"Create headline\" class=\"btn_default\" />
</p>
</form>

";
?>