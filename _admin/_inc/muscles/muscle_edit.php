<?php
/**
*
* File: _admin/_inc/muscles/muscle_edit.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ----------------------------------------------------------------------------- */
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";
$t_muscle_part_of 			= $mysqlPrefixSav . "muscle_part_of";
$t_muscle_part_of_translations	 	= $mysqlPrefixSav . "muscle_part_of_translations";


/*- Variables -------------------------------------------------------------------------- */
$editor_language_mysql = quote_smart($link, $editor_language);

if(isset($_GET['muscle_id'])){
	$muscle_id = $_GET['muscle_id'];
	$muscle_id = strip_tags(stripslashes($muscle_id));
}
else{
	$muscle_id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}
if(isset($_GET['sub_muscle_group_id'])){
	$sub_muscle_group_id = $_GET['sub_muscle_group_id'];
	$sub_muscle_group_id = strip_tags(stripslashes($sub_muscle_group_id));
}
else{
	$sub_muscle_group_id = "";
}


/*- Scriptstart ---------------------------------------------------------------------- */

// Find muscle
$muscle_id_mysql = quote_smart($link, $muscle_id);
$query = "SELECT muscle_id, muscle_user_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_group_id_main, muscle_group_id_sub, muscle_part_of_id, muscle_text, muscle_image_path, muscle_image_file, muscle_video_path, muscle_video_file, muscle_unique_hits, muscle_unique_hits_ip_block FROM $t_muscles WHERE muscle_id='$muscle_id_mysql'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_muscle_id, $get_current_muscle_user_id, $get_current_muscle_latin_name, $get_current_muscle_latin_name_clean, $get_current_muscle_simple_name, $get_current_muscle_short_name, $get_current_muscle_group_id_main, $get_current_muscle_group_id_sub, $get_current_muscle_part_of_id, $get_current_muscle_text, $get_current_muscle_image_path, $get_current_muscle_image_file, $get_current_muscle_video_path, $get_current_muscle_video_file, $get_current_muscle_unique_hits, $get_current_muscle_unique_hits_ip_block) = $row;

// Find sub
$sub_muscle_group_id_mysql = quote_smart($link, $sub_muscle_group_id);
$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$sub_muscle_group_id_mysql'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_sub_muscle_group_id, $get_current_sub_muscle_group_name, $get_current_sub_muscle_group_name_clean, $get_current_sub_muscle_group_parent_id, $get_current_sub_muscle_group_image_path, $get_current_sub_muscle_group_image_file) = $row;

// Find main
$main_muscle_group_id_mysql = quote_smart($link, $main_muscle_group_id);
$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$main_muscle_group_id_mysql'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_main_muscle_group_id, $get_current_main_muscle_group_name, $get_current_main_muscle_group_name_clean, $get_current_main_muscle_group_parent_id, $get_current_main_muscle_group_image_path, $get_current_main_muscle_group_image_file) = $row;


if($get_current_muscle_id == ""){
	echo"
	<p>Muscle not found.</p>
	";
}
else {
	// Muscle Translation
	$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name, muscle_translation_text FROM $t_muscles_translations WHERE muscle_translation_muscle_id=$muscle_id_mysql AND muscle_translation_language=$editor_language_mysql";
	$result_translation = mysqli_query($link, $query_translation);
	$row_translation = mysqli_fetch_row($result_translation);
	list($get_current_muscle_translation_id, $get_current_muscle_translation_simple_name, $get_current_muscle_translation_short_name, $get_current_muscle_translation_text) = $row_translation;
	if($get_current_muscle_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_muscles_translations
		(muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_short_name, muscle_translation_text) 
		VALUES 
		(NULL, 	'$get_current_muscle_id', $editor_language_mysql, '$get_current_muscle_simple_name', '$get_current_muscle_short_name', '')")
		or die(mysqli_error($link));
		echo"<div class=\"info\"><span>L O A D I N G</span></div>";
		echo"
		<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$editor_language&amp;l=$l'\" />
		";
	}


	if($process == 1){
		$inp_latin_name = $_POST['inp_latin_name'];
		$inp_latin_name = output_html($inp_latin_name);
		$inp_latin_name_mysql = quote_smart($link, $inp_latin_name);
		
		$inp_latin_name_clean = clean($inp_latin_name);
		$inp_latin_name_clean_mysql = quote_smart($link, $inp_latin_name_clean);

		$inp_simple_name = $_POST['inp_simple_name'];
		$inp_simple_name = output_html($inp_simple_name);
		$inp_simple_name_mysql = quote_smart($link, $inp_simple_name);

		$inp_translation_simple_name = $_POST['inp_translation_simple_name'];
		$inp_translation_simple_name = output_html($inp_translation_simple_name);
		$inp_translation_simple_name_mysql = quote_smart($link, $inp_translation_simple_name);

		$inp_short_name = $_POST['inp_short_name'];
		$inp_short_name = output_html($inp_short_name);
		$inp_short_name_mysql = quote_smart($link, $inp_short_name);

		$inp_translation_short_name = $_POST['inp_translation_short_name'];
		$inp_translation_short_name = output_html($inp_translation_short_name);
		$inp_translation_short_name_mysql = quote_smart($link, $inp_translation_short_name);

		$inp_group_id_sub = $_POST['inp_group_id_sub'];
		$inp_group_id_sub = output_html($inp_group_id_sub);
		$inp_group_id_sub_mysql = quote_smart($link, $inp_group_id_sub);

		// Main
		$query = "SELECT muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id=$inp_group_id_sub_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($inp_group_id_main) = $row;
		$inp_group_id_main = output_html($inp_group_id_main);
		$inp_group_id_main_mysql = quote_smart($link, $inp_group_id_main);

		$inp_part_of_id = $_POST['inp_part_of_id'];
		$inp_part_of_id = output_html($inp_part_of_id);
		$inp_part_of_id_mysql = quote_smart($link, $inp_part_of_id);

		$inp_translation_text = $_POST['inp_translation_text'];

		// Update
		$result = mysqli_query($link, "UPDATE $t_muscles SET muscle_latin_name=$inp_latin_name_mysql, muscle_latin_name_clean=$inp_latin_name_clean_mysql, muscle_simple_name=$inp_simple_name_mysql,
						muscle_short_name=$inp_short_name_mysql, muscle_group_id_main=$inp_group_id_main_mysql, muscle_group_id_sub=$inp_group_id_sub_mysql, muscle_part_of_id=$inp_part_of_id_mysql WHERE muscle_id='$muscle_id_mysql'");
		$result = mysqli_query($link, "UPDATE $t_muscles_translations SET muscle_translation_simple_name=$inp_translation_simple_name_mysql, 
						muscle_translation_short_name=$inp_translation_short_name_mysql WHERE muscle_translation_muscle_id=$muscle_id_mysql AND muscle_translation_language=$editor_language_mysql");


		// Insert content
		$sql = "UPDATE $t_muscles_translations SET muscle_translation_text=? WHERE muscle_translation_muscle_id=$muscle_id_mysql AND muscle_translation_language=$editor_language_mysql";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_translation_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}


		// Header
		$url = "index.php?open=$open&page=muscle_edit&main_muscle_group_id=$inp_group_id_main&sub_muscle_group_id=$inp_group_id_sub&muscle_id=$muscle_id&editor_language=$editor_language";
		$url = $url . "&ft=success&fm=changes_saved";

		header("Location: $url");
		exit;

	} // process == 1


	echo"

	<!-- Headline and Language -->
		<div style=\"float:left;\">
			<h1>$get_current_muscle_translation_simple_name</h1>
		</div>
		<div style=\"float:left;padding: 14px 0px 0px 500px\">
			<form>
			<select name=\"inp_language\" id=\"inp_language\">\n";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				echo"<option value=\"index.php?open=$open&amp;page=$page&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</form>

			<script>
   			 $(function(){
    			  // bind change event to select
    			  $('#inp_language').on('change', function () {
     			     var url = $(this).val(); // get selected value
      			    if (url) { // require a URL
       			       window.location = url; // redirect
      			    }
      			    return false;
   			   });
  			  });
			</script>
		</div>
		<div class=\"clear\"></div>
	<!-- //Headline and Language -->


					
	<!-- Menu -->
		<p>
		<a href=\"index.php?open=$open&amp;page=view_muscle&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$editor_language\">View</a>
		|
		<a href=\"index.php?open=$open&amp;page=muscle_edit&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$editor_language\" style=\"font-weight: bold;\">Edit</a>
		|
		<a href=\"index.php?open=$open&amp;page=muscle_edit_image&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$editor_language\">Image</a>
		|
		<a href=\"index.php?open=$open&amp;page=muscle_delete&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$editor_language\">Delete</a>
		</p>
	<!-- //Menu -->


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
			
		<!-- TinyMCE -->
		<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce_4.7.1/tinymce.min.js\"></script>
		<script>
		tinymce.init({
			selector: \"textarea\",  // change this value according to your HTML
			plugins: \"image\",
			menubar: \"insert\",
			toolbar: \"image\",
			height: 500,
			menubar: false,
			plugins: [
			    'advlist autolink lists link image charmap print preview anchor textcolor',
			    'searchreplace visualblocks code fullscreen',
			    'insertdatetime media table contextmenu paste code help'
			  ],
			  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
			  content_css: [
			    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			    '//www.tinymce.com/css/codepen.min.css']
		});
		</script>
		<!-- //TinyMCE -->


		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_latin_name\"]').focus();
		});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=muscle_edit&amp;main_muscle_group_id=$main_muscle_group_id&amp;sub_muscle_group_id=$sub_muscle_group_id&amp;muscle_id=$muscle_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>Latin name:</b><br />
		<input type=\"text\" name=\"inp_latin_name\" value=\"$get_current_muscle_latin_name\" size=\"40\" />
		</p>

		<p><b>Default simple name:</b><br />
		<input type=\"text\" name=\"inp_simple_name\" value=\"$get_current_muscle_simple_name\" size=\"40\" />
		</p>

		<p><b>Translated simple name ($editor_language):</b><br />
		<input type=\"text\" name=\"inp_translation_simple_name\" value=\"$get_current_muscle_translation_simple_name\" size=\"40\" />
		</p>

		<p><b>Default short name:</b><br />
		<input type=\"text\" name=\"inp_short_name\" value=\"$get_current_muscle_short_name\" size=\"40\" />
		</p>

		<p><b>Translated short name ($editor_language):</b><br />
		<input type=\"text\" name=\"inp_translation_short_name\" value=\"$get_current_muscle_translation_short_name\" size=\"40\" />
		</p>

		<p><b>Group:</b><br />
		<select name=\"inp_group_id_sub\">\n";
			echo"					";
			echo"<option value=\"\">- Please select -</option>\n";
			// Get all main
			$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
			
				// Translation
				$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_muscle_group_translation_id, $get_muscle_group_translation_name) = $row_translation;
				echo"					";
				echo"<option value=\"\"> </option>\n";
				echo"					";
				echo"<option value=\"$get_muscle_group_id\">$get_muscle_group_name</option>\n";

				// Get sub
				$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_muscle_group_id'";
				$result_sub = mysqli_query($link, $query_sub);
				while($row_sub = mysqli_fetch_row($result_sub)) {
					list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
			
					// Translation
					$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;
					echo"					";
					echo"<option value=\"$get_sub_muscle_group_id\""; if($get_sub_muscle_group_id == "$get_current_muscle_group_id_sub"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</option>\n";


				}
			}
			
		echo"
		</select>
		</p>

		<p><b>Part of:</b><br />
		<select name=\"inp_part_of_id\">\n";
			echo"					";
			echo"<option value=\"0\""; if($get_current_muscle_part_of_id == "0"){ echo" selected=\"selected\""; } echo">- None -</option>\n";
			// Get all main
			$query = "SELECT muscle_part_of_id, muscle_part_of_name FROM $t_muscle_part_of";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_muscle_part_of_id, $get_muscle_part_of_name) = $row;
			
				// Translation
				$query_translation = "SELECT muscle_part_of_translation_id, muscle_part_of_translation_name FROM $t_muscle_part_of_translations WHERE muscle_part_of_translation_muscle_part_of_id='$get_muscle_part_of_id' AND muscle_part_of_translation_language=$editor_language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_muscle_part_of_translation_id, $get_muscle_part_of_translation_name) = $row_translation;
	
				if($get_muscle_part_of_translation_id == ""){
					mysqli_query($link, "INSERT INTO $t_muscle_part_of_translations
					(muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, muscle_part_of_translation_language, muscle_part_of_translation_name, muscle_part_of_translation_text) 
					VALUES 
					(NULL, 	'$get_muscle_part_of_id', $editor_language_mysql, '$get_muscle_part_of_name', '')")
					or die(mysqli_error($link));

				}
				echo"					";
				echo"<option value=\"$get_muscle_part_of_id\""; if($get_muscle_part_of_id == "$get_current_muscle_part_of_id"){ echo" selected=\"selected\""; } echo">$get_muscle_part_of_translation_name</option>\n";

			}
			
		echo"
		</select>
		</p>

		<p><b>Text ($editor_language):</b><br />
		<textarea name=\"inp_translation_text\" rows=\"10\" cols=\"70\">$get_current_muscle_translation_text</textarea>
		</p>


		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn\" />
		</p>

		</form>
	<!-- //Edit form -->
	";
} // muscle not found

?>