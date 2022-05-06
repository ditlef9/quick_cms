<?php
/**
*
* File: _admin/_inc/settings/languages.php
* Version 02:10 28.12.2011
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions -------------------------------------------------------------------------- */
include("_functions/get_extension.php");


$tabindex = 0;

if($action == ""){
	echo"
	<h2>$l_languages</h2>

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "language_added_to_the_list"){
			$fm = "$l_language_added_to_the_list";
		}
		elseif($fm == "language_is_alreaddy_active"){
			$fm = "$l_language_is_alreaddy_active";
		}
		elseif($fm == "language_removed"){
			$fm = "$l_language_removed";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Menus -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_languages&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Edit languages</a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_countries&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Edit countries</a>
		</p>
	<!-- //Menus -->

	<!-- Predefined language -->
		<form method=\"post\" action=\"?open=settings&amp;page=languages&amp;action=edit_predefined_language&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">
		<table>
		 <tr>
		  <td style=\"padding-right: 4px;\">
			<p>
			$l_main_language:
			</p>
		  </td>
		  <td>
			<p>
			<select name=\"inp_language\">
				<option value=\"\">-</option>\n";

			$found_main_language = 0;
			$get_language_active_id = "";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
				// Found active language?
				if($found_main_language == "" &&$get_language_active_default == "1"){
					$found_main_language = "1";
				}


				echo"	<option value=\"$get_language_active_id\"";if($get_language_active_default == "1"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>";
			if($found_main_language == "0"){
				echo"<p style=\"color: orange\">No active language selected.</p>\n";
				if($get_language_active_id != ""){
					echo"<p>Autosetting active language!</p>
					<meta http-equiv=refresh content=\"1; url=index.php?open=settings&amp;page=languages&amp;editor_language=$editor_language&amp;l=$l\">";
					mysqli_query($link, "UPDATE $t_languages_active SET language_active_default=1 WHERE language_active_id=$get_language_active_id") or die(mysqli_error($link));
				}
			}
			echo"
		  </td>
		  <td style=\"padding-right: 4px;\">
			<p>
			<input type=\"submit\" value=\"$l_save\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"submit\" />
			</p>
		  </td>
		 </tr>
		</table>
		</form>
	<!-- //Predefined language -->


	<!-- Languages added and that can be addded -->
		<table>
		  <tr>
		   <td style=\"padding-right: 20px;vertical-align:top;\">

			<!-- Languages that can be added -->
				
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_inactive</span>
					<span style=\"float: right;\">$l_click_to_activate</span>
				   </th>
				  </tr>
			 	 </thead>
				<tbody>
				";
				$query = "SELECT language_id, language_name, language_iso_two, language_flag_path_16x16, language_flag_active_16x16 FROM $t_languages";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_id, $get_language_name, $get_language_iso_two, $get_language_flag_path_16x16, $get_language_flag_active_16x16) = $row;
	

					echo"
					<tr>
					  <td>
						<table>
						 <tr>
       						  <td style=\"padding-right:4px;\">
							<a href=\"?open=settings&amp;page=languages&amp;action=add_language&amp;process=1&amp;language_id=$get_language_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:#000;\"><img src=\"../$get_language_flag_path_16x16/$get_language_flag_active_16x16\" alt=\"$get_language_flag_active_16x16\" /></a>
						  </td>
       						  <td>
          						<span><a href=\"?open=settings&amp;page=languages&amp;action=add_language&amp;process=1&amp;language_id=$get_language_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:#000;\">$get_language_name</a></span>
						  </td>
     						 </tr>
						</table>
					  </td>
     					 </tr>
					";
				}
				echo"
				 </tbody>
				</table>
			<!-- //Languages that can be added -->

		  </td>
		  <td style=\"vertical-align: top;\">

			<!-- Land list -->
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_active</span>
					<span style=\"float: right;\">$l_click_to_deactivate</span>
				   </th>
				  </tr>
			 	 </thead>
				<tbody>";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_active_16x16, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, $get_language_active_default) = $row;


					echo"
					<tr>
					  <td>
						<table>
						 <tr>
       						  <td style=\"padding-right:4px;\">
							<span><a href=\"?open=settings&amp;page=languages&amp;action=remove_language&amp;process=1&amp;language_id=$get_language_active_id\" style=\"color:#000;\"><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_active_16x16\" alt=\"$get_language_active_flag_active_16x16\" /></a></span>
						
						  </td>
       						  <td>
          						<span><a href=\"?open=settings&amp;page=languages&amp;action=remove_language&amp;process=1&amp;language_id=$get_language_active_id\" style=\"color:#000;\">$get_language_active_name</a></span>
						  </td>
     						 </tr>
						</table>
					  </td>
     					 </tr>
					";
				}
				echo"
					</table>
				  </td>
     				 </tr>
				</table>
			<!-- //Navigation list -->

		  </td>
		 </tr>
		</table>
	<!-- //Languages added and that can be addded -->
	";
}
elseif($action == "add_language"){
	if($process == "1"){
		if(isset($_GET['language_id'])) {
			$inp_language = $_GET['language_id'];
			$inp_language = strip_tags(stripslashes($inp_language));
		}
		else{
			if(isset($_POST['inp_language'])) {
				$inp_language = $_POST['inp_language'];
				$inp_language = strip_tags(stripslashes($inp_language));
			}
			else{
				$inp_language = "";
				header('Location: ?open=settings&page=languages&ft=warning&fm=Ingen språk oppgitt.&editor_language=$editor_language&l=$l');
				exit;
			}

		}
		


		// Get
		$inp_language_mysql = quote_smart($link, $inp_language);
		$query = "SELECT language_id, language_name, language_slug, language_native_name, language_iso_two, language_iso_three, language_iso_four, language_iso_two_alt_a, language_iso_two_alt_b, language_flag_emoji_code, language_flag_emoji_char, language_flag_emoji_char_output_html, language_flag_emoji_char_string_value, language_flag_path_16x16, language_flag_active_16x16, language_flag_inactive_16x16, language_flag_path_18x18, language_flag_active_18x18, language_flag_inactive_18x18, language_flag_path_24x24, language_flag_active_24x24, language_flag_inactive_24x24, language_flag_path_32x32, language_flag_active_32x32, language_flag_inactive_32x32, language_charset FROM $t_languages WHERE language_id=$inp_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_language_id, $get_language_name, $get_language_slug, $get_language_native_name, $get_language_iso_two, $get_language_iso_three, $get_language_iso_four, $get_language_iso_two_alt_a, $get_language_iso_two_alt_b, $get_language_flag_emoji_code, $get_language_flag_emoji_char, $get_language_flag_emoji_char_output_html, $get_language_flag_emoji_char_string_value, $get_language_flag_path_16x16, $get_language_flag_active_16x16, $get_language_flag_inactive_16x16, $get_language_flag_path_18x18, $get_language_flag_active_18x18, $get_language_flag_inactive_18x18, $get_language_flag_path_24x24, $get_language_flag_active_24x24, $get_language_flag_inactive_24x24, $get_language_flag_path_32x32, $get_language_flag_active_32x32, $get_language_flag_inactive_32x32, $get_language_charset) = $row;

		if($get_language_id == ""){
			header("Location: ?open=settings&page=languages&ft=error&fm=language_not_found&editor_language=$editor_language&l=$l");
			exit;
				
		}
		else{
			// Does it alreaddy exsists in active list?
			$inp_iso_two_mysql = quote_smart($link, $get_language_iso_two);
			$query = "SELECT language_active_id FROM $t_languages_active WHERE language_active_iso_two=$inp_iso_two_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_language_active_id) = $row;

			if($get_language_active_id == ""){
				// Insert

				$inp_name_mysql = quote_smart($link, $get_language_name);
				$inp_slug_mysql = quote_smart($link, $get_language_slug);
				$inp_native_name_mysql = quote_smart($link, $get_language_native_name);
				$inp_iso_two_mysql = quote_smart($link, $get_language_iso_two);
				$inp_iso_three_mysql = quote_smart($link, $get_language_iso_three);
				$inp_iso_two_alt_a_mysql = quote_smart($link, $get_language_iso_two_alt_a);
				$inp_iso_two_alt_b_mysql = quote_smart($link, $get_language_iso_two_alt_b);
				$inp_iso_four_mysql = quote_smart($link, $get_language_iso_four);

				$inp_flag_emoji_code_mysql = quote_smart($link, $get_language_flag_emoji_code);
				$inp_flag_emoji_char_mysql = quote_smart($link, $get_language_flag_emoji_char);
				$inp_flag_emoji_char_output_html_mysql = quote_smart($link, $get_language_flag_emoji_char_output_html);
				$inp_flag_emoji_char_string_value_mysql = quote_smart($link, $get_language_flag_emoji_char_string_value);

				$inp_flag_path_16x16_mysql = quote_smart($link, $get_language_flag_path_16x16);
				$inp_flag_active_16x16_mysql = quote_smart($link, $get_language_flag_active_16x16);
				$inp_flag_inactive_16x16_mysql = quote_smart($link, $get_language_flag_inactive_16x16);

				$inp_flag_path_18x18_mysql = quote_smart($link, $get_language_flag_path_18x18);
				$inp_flag_active_18x18_mysql = quote_smart($link, $get_language_flag_active_18x18);
				$inp_flag_inactive_18x18_mysql = quote_smart($link, $get_language_flag_inactive_18x18);

				$inp_flag_path_24x24_mysql = quote_smart($link, $get_language_flag_path_24x24);
				$inp_flag_active_24x24_mysql = quote_smart($link, $get_language_flag_active_24x24);
				$inp_flag_inactive_24x24_mysql = quote_smart($link, $get_language_flag_inactive_24x24);

				$inp_flag_path_32x32_mysql = quote_smart($link, $get_language_flag_path_32x32);
				$inp_flag_active_32x32_mysql = quote_smart($link, $get_language_flag_active_32x32);
				$inp_flag_inactive_32x32_mysql = quote_smart($link, $get_language_flag_inactive_32x32);

				$inp_charset_mysql = quote_smart($link, $get_language_charset);


				mysqli_query($link, "INSERT INTO $t_languages_active
				(language_active_id, language_active_name, language_active_slug, language_active_native_name, language_active_iso_two, 
				language_active_iso_three, language_active_iso_four, language_active_iso_two_alt_a, language_active_iso_two_alt_b, language_active_flag_emoji_code, 
				language_active_flag_emoji_char, language_active_flag_emoji_char_output_html, language_active_flag_emoji_char_string_value, language_active_flag_path_16x16, language_active_flag_active_16x16, 
				language_active_flag_inactive_16x16, language_active_flag_path_18x18, language_active_flag_active_18x18, language_active_flag_inactive_18x18, language_active_flag_path_24x24, 
				language_active_flag_active_24x24, language_active_flag_inactive_24x24, language_active_flag_path_32x32, language_active_flag_active_32x32, language_active_flag_inactive_32x32, 
				language_active_charset, language_active_default) 
				VALUES 
				(NULL, $inp_name_mysql, $inp_slug_mysql, $inp_native_name_mysql, $inp_iso_two_mysql, 
				$inp_iso_three_mysql, $inp_iso_four_mysql, $inp_iso_two_alt_a_mysql, $inp_iso_two_alt_b_mysql, $inp_flag_emoji_code_mysql, 
				$inp_flag_emoji_char_mysql, $inp_flag_emoji_char_output_html_mysql, $inp_flag_emoji_char_string_value_mysql, $inp_flag_path_16x16_mysql, 
				$inp_flag_active_16x16_mysql, $inp_flag_inactive_16x16_mysql, $inp_flag_path_18x18_mysql, $inp_flag_active_18x18_mysql, $inp_flag_inactive_18x18_mysql, $inp_flag_path_24x24_mysql, 
				$inp_flag_active_24x24_mysql, $inp_flag_inactive_24x24_mysql, $inp_flag_path_32x32_mysql, $inp_flag_active_32x32_mysql, $inp_flag_inactive_32x32_mysql, 
				$inp_charset_mysql, 0)")
				or die(mysqli_error($link));
				header("Location: ?open=settings&page=languages&ft=success&fm=language_added_to_the_list&editor_language=$editor_language&l=$l");
				exit;
				
			}
			else{
				header("Location: ?open=settings&page=languages&ft=success&fm=language_already_exist&editor_language=$editor_language&l=$l");
				exit;
				
			}
		}
	}

}
elseif($action == "remove_language"){
	if($process == "1"){
		if(isset($_GET['language_id'])) {
			$language_id = $_GET['language_id'];
			$language_id = strip_tags(stripslashes($language_id));
		}
		else{
			header('Location: ?open=settings&page=languages&ft=warning&fm=Ingen språk oppgitt.&editor_language=$editor_language&l=$l');
			exit;

		}
		
		// Locate this language
		$inp_language_id_mysql = quote_smart($link, $language_id);
		$query = "SELECT language_active_id FROM $t_languages_active WHERE language_active_id=$inp_language_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_language_active_id) = $row;

		if($get_language_active_id == ""){
			
			header("Location: ?open=settings&page=languages&ft=error&fm=language_not_found&editor_language=$editor_language&l=$l");
			exit;
		}
		else{
			$result = mysqli_query($link, "DELETE FROM $t_languages_active WHERE language_active_id=$inp_language_id_mysql");
			header("Location: ?open=settings&page=languages&ft=success&fm=language_removed&editor_language=$editor_language&l=$l");
			exit;
		}
	}
}
elseif($action == "edit_predefined_language"){
	if($process == "1"){

		
		if(isset($_POST['inp_language'])) {
			$inp_language = $_POST['inp_language'];
			$inp_language = strip_tags(stripslashes($inp_language));
		}
		else{
			header("Location: ?open=settings&page=languages&ft=warning&fm=Ingen språk valgt.");
			exit;
		}
		
		// Locate this language
		$inp_language_id_mysql = quote_smart($link, $inp_language);
		$query = "SELECT language_active_id FROM $t_languages_active WHERE language_active_id=$inp_language_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_language_active_id) = $row;


		if($get_language_active_id == ""){
			
			header("Location: ?open=settings&page=languages&ft=error&fm=language_not_found&editor_language=$editor_language&l=$l");
			exit;
		}
		else{
			$result = mysqli_query($link, "UPDATE $t_languages_active SET language_active_default='0'");
			$result = mysqli_query($link, "UPDATE $t_languages_active SET language_active_default='1' WHERE language_active_id=$inp_language_id_mysql");

			
			header("Location: ?open=settings&page=languages&ft=success&fm=changes_saved&editor_language=$editor_language&l=$l");
			exit;
		}
	}
}
elseif($action == "edit_languages"){

	echo"
	<h2>Edit languages</h2>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Languages</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_languages&amp;editor_language=$editor_language&amp;l=$l\">Edit languages</a>
		</p>
	<!-- //Where am I?  -->


	<!-- Feedback -->
	";
	if($ft != ""){
		$fm = str_replace("_", " ", $fm);
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	


	<!-- Languages -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th colspan=\"2\">
			<span>Language</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		$query = "SELECT language_id, language_name, language_iso_two, language_flag_path_16x16, language_flag_active_16x16 FROM $t_languages ORDER BY language_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_id, $get_language_name, $get_language_iso_two, $get_language_flag_path_16x16, $get_language_flag_active_16x16) = $row;
	
			echo"
			 <tr>
       			  <td style=\"padding-right:4px;\">
				<a href=\"?open=settings&amp;page=languages&amp;action=edit_language&amp;language_id=$get_language_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:#000;\"><img src=\"../$get_language_flag_path_16x16/$get_language_flag_active_16x16\" alt=\"$get_language_flag_active_16x16\" /></a>
			  </td>
       			  <td>
          			<span><a href=\"?open=settings&amp;page=languages&amp;action=edit_language&amp;language_id=$get_language_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:#000;\">$get_language_name</a></span>
			  </td>
     			 </tr>
			";
		}
		echo"
		<!-- //Languages that can be added -->

		 </tbody>
		</table>
	<!-- //Languages -->
	";
} // action == edit_languages
elseif($action == "edit_language"){
	if(isset($_GET['language_id'])) {
		$language_id = $_GET['language_id'];
		$language_id = strip_tags(stripslashes($language_id));
		if(!(is_numeric($language_id))){
			echo"Language not numeric";
			die;
		}
	}
	else{
		echo"Missing language";
		die;
	}
		
	// Locate this language
	$language_id_mysql = quote_smart($link, $language_id);
	$query = "SELECT language_id, language_name, language_slug, language_native_name, language_iso_two, language_iso_three, language_iso_four, language_iso_two_alt_a, language_iso_two_alt_b, language_flag_emoji_code, language_flag_emoji_char, language_flag_emoji_char_output_html, language_flag_emoji_char_string_value, language_flag_path_16x16, language_flag_active_16x16, language_flag_inactive_16x16, language_flag_path_18x18, language_flag_active_18x18, language_flag_inactive_18x18, language_flag_path_24x24, language_flag_active_24x24, language_flag_inactive_24x24, language_flag_path_32x32, language_flag_active_32x32, language_flag_inactive_32x32, language_charset FROM $t_languages WHERE language_id=$language_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_language_id, $get_current_language_name, $get_current_language_slug, $get_current_language_native_name, $get_current_language_iso_two, $get_current_language_iso_three, $get_current_language_iso_four, $get_current_language_iso_two_alt_a, $get_current_language_iso_two_alt_b, $get_current_language_flag_emoji_code, $get_current_language_flag_emoji_char, $get_current_language_flag_emoji_char_output_html, $get_current_language_flag_emoji_char_string_value, $get_current_language_flag_path_16x16, $get_current_language_flag_active_16x16, $get_current_language_flag_inactive_16x16, $get_current_language_flag_path_18x18, $get_current_language_flag_active_18x18, $get_current_language_flag_inactive_18x18, $get_current_language_flag_path_24x24, $get_current_language_flag_active_24x24, $get_current_language_flag_inactive_24x24, $get_current_language_flag_path_32x32, $get_current_language_flag_active_32x32, $get_current_language_flag_inactive_32x32, $get_current_language_charset) = $row;
	if($get_current_language_id == ""){
		echo"Language not found";
	}
	else{
		if($process == "1"){
			$inp_name = $_POST['inp_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$inp_name_clean = clean($inp_name);
			$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

			$inp_native_name = $_POST['inp_native_name'];
			$inp_native_name = output_html($inp_native_name);
			$inp_native_name_mysql = quote_smart($link, $inp_native_name);

			$inp_iso_two = $_POST['inp_iso_two'];
			$inp_iso_two = output_html($inp_iso_two);
			$inp_iso_two_mysql = quote_smart($link, $inp_iso_two);

			$inp_iso_three = $_POST['inp_iso_three'];
			$inp_iso_three = output_html($inp_iso_three);
			$inp_iso_three_mysql = quote_smart($link, $inp_iso_three);

			$inp_iso_four = $_POST['inp_iso_four'];
			$inp_iso_four = output_html($inp_iso_four);
			$inp_iso_four_mysql = quote_smart($link, $inp_iso_four);

			$inp_iso_two_alt_a = $_POST['inp_iso_two_alt_a'];
			$inp_iso_two_alt_a = output_html($inp_iso_two_alt_a);
			$inp_iso_two_alt_a_mysql = quote_smart($link, $inp_iso_two_alt_a);

			$inp_iso_two_alt_b = $_POST['inp_iso_two_alt_b'];
			$inp_iso_two_alt_b = output_html($inp_iso_two_alt_b);
			$inp_iso_two_alt_b_mysql = quote_smart($link, $inp_iso_two_alt_b);

			$inp_flag_emoji_code = $_POST['inp_flag_emoji_code'];
			$inp_flag_emoji_code = output_html($inp_flag_emoji_code);
			$inp_flag_emoji_code = str_replace("&amp;", "&", $inp_flag_emoji_code);
			$inp_flag_emoji_code_mysql = quote_smart($link, $inp_flag_emoji_code);

			// Emoji char
			$inp_flag_emoji_char = $_POST['inp_flag_emoji_char'];
			$inp_flag_emoji_char = trim($inp_flag_emoji_char); // Trim and line space

			// Check last, if it is backslash, then replace it...
			$check  = substr($inp_flag_emoji_char, -1);
			$check  =  "^" . $check . "^";
			if($check == "^\^"){
				$new_value = substr($inp_flag_emoji_char, 0, -1);
				$inp_flag_emoji_char = $new_value . "&#92";
			}
			$inp_flag_emoji_char = str_replace("'", "&#039;", $inp_flag_emoji_char); // '
			$inp_flag_emoji_char = htmlentities($inp_flag_emoji_char, ENT_COMPAT, "UTF-8");
			$inp_flag_emoji_char_mysql = quote_smart($link, $inp_flag_emoji_char);

			$inp_flag_emoji_char_output_html = output_html($inp_flag_emoji_char);
			$inp_flag_emoji_char_output_html_mysql = quote_smart($link, $inp_flag_emoji_char_output_html);

			$inp_flag_emoji_char_string_value = "$inp_flag_emoji_char";
			$inp_flag_emoji_char_string_value_mysql = quote_smart($link, $inp_flag_emoji_char_string_value);

			$inp_charset = $_POST['inp_charset'];
			$inp_charset = output_html($inp_charset);
			$inp_charset_mysql = quote_smart($link, $inp_charset);

			$result = mysqli_query($link, "UPDATE $t_languages SET 
							language_name=$inp_name_mysql,
							language_slug=$inp_name_clean_mysql,
							language_native_name=$inp_native_name_mysql,
							language_iso_two=$inp_iso_two_mysql,
							language_iso_three=$inp_iso_three_mysql,
							language_iso_four=$inp_iso_four_mysql,
							language_iso_two_alt_a=$inp_iso_two_alt_a_mysql,
							language_iso_two_alt_b=$inp_iso_two_alt_b_mysql,
							language_flag_emoji_code=$inp_flag_emoji_code_mysql,
							language_flag_emoji_char=$inp_flag_emoji_char_mysql,
							language_flag_emoji_char_output_html=$inp_flag_emoji_char_output_html_mysql,
							language_flag_emoji_char_string_value=$inp_flag_emoji_char_string_value_mysql,
							language_charset=$inp_charset_mysql
							WHERE language_id=$get_current_language_id") or die(mysqli_error($link));

			// Get new data before uploading flags
			$query = "SELECT language_id, language_name, language_slug, language_native_name, language_iso_two, language_iso_three, language_iso_four, language_iso_two_alt_a, language_iso_two_alt_b, language_flag_emoji_code, language_flag_emoji_char, language_flag_emoji_char_output_html, language_flag_emoji_char_string_value, language_flag_path_16x16, language_flag_active_16x16, language_flag_inactive_16x16, language_flag_path_18x18, language_flag_active_18x18, language_flag_inactive_18x18, language_flag_path_24x24, language_flag_active_24x24, language_flag_inactive_24x24, language_flag_path_32x32, language_flag_active_32x32, language_flag_inactive_32x32, language_charset FROM $t_languages WHERE language_id=$language_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_language_id, $get_current_language_name, $get_current_language_slug, $get_current_language_native_name, $get_current_language_iso_two, $get_current_language_iso_three, $get_current_language_iso_four, $get_current_language_iso_two_alt_a, $get_current_language_iso_two_alt_b, $get_current_language_flag_emoji_code, $get_current_language_flag_emoji_char, $get_current_language_flag_emoji_char_output_html, $get_current_language_flag_emoji_char_string_value, $get_current_language_flag_path_16x16, $get_current_language_flag_active_16x16, $get_current_language_flag_inactive_16x16, $get_current_language_flag_path_18x18, $get_current_language_flag_active_18x18, $get_current_language_flag_inactive_18x18, $get_current_language_flag_path_24x24, $get_current_language_flag_active_24x24, $get_current_language_flag_inactive_24x24, $get_current_language_flag_path_32x32, $get_current_language_flag_active_32x32, $get_current_language_flag_inactive_32x32, $get_current_language_charset) = $row;


			// Flags
			if(!(is_dir("_design/gfx/languages"))){
				mkdir("_design/gfx/languages");
			}
			$ft_images = "";
			$fm_images = "";

			$dim_array = array("16x16", "18x18", "24x24", "32x32");
			$size = sizeof($dim_array);
			for($x=0;$x<sizeof($dim_array);$x++){
				if($dim_array[$x] == "16x16"){
					$path = "$get_current_language_flag_path_16x16";
					$active = "$get_current_language_flag_active_16x16";
					$inactive = "$get_current_language_flag_inactive_16x16";
				}
				elseif($dim_array[$x] == "18x18"){
					$path = "$get_current_language_flag_path_18x18";
					$active = "$get_current_language_flag_active_18x18";
					$inactive = "$get_current_language_flag_inactive_18x18";
				}
				elseif($dim_array[$x] == "24x24"){
					$path = "$get_current_language_flag_path_24x24";
					$active = "$get_current_language_flag_active_24x24";
					$inactive = "$get_current_language_flag_inactive_24x24";
				}
				else{
					$path = "$get_current_language_flag_path_32x32";
					$active = "$get_current_language_flag_active_32x32";
					$inactive = "$get_current_language_flag_inactive_32x32";
				}

				// Active
				$name = stripslashes($_FILES["inp_flag_active_$dim_array[$x]"]['name']);
				$name = output_html($name);
				$extension = get_extension($name);
				$extension = strtolower($extension);

				$new_name = $get_current_language_iso_four . "_active_" . $dim_array[$x] . "." . $extension;
				
				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_images = "warning";
						$fm_images = "unknown_file_extension";
					}
					else{
						$new_path = "_design/gfx/languages/$dim_array[$x]/";
						if(!(is_dir("$new_path"))){
							mkdir("$new_path");
						}
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_flag_active_$dim_array[$x]"]['tmp_name'], $uploaded_file)) {
	

							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_images = "warning";
								$fm_images = "getimagesize_failed";
								unlink($uploaded_file);
							}
							else{
								$ft_images = "success";
								$fm_images = "flag_uploaded";
								$inp_flag_mysql = quote_smart($link, $new_name);

								$result = mysqli_query($link, "UPDATE $t_languages SET 
											language_flag_path_$dim_array[$x]='_admin/_design/gfx/languages/$dim_array[$x]',
											language_flag_active_$dim_array[$x]=$inp_flag_mysql
											WHERE language_id=$get_current_language_id") or die(mysqli_error($link));
							
							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							$ft_images = "warning";
							switch ($_FILES["inp_flag_active_$dim_array[$x]"]['error']) {
								case UPLOAD_ERR_OK:
           								$fm_images = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_images = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_images = "to_big_size_in_form";
									break;
								default:
           								$fm_images = "unknown_error";
									break;
							}	
						}
					} // extension check
				} // if($image){ (Active)


				// Inactive
				$name = stripslashes($_FILES["inp_flag_inactive_$dim_array[$x]"]['name']);
				$name = output_html($name);
				$extension = get_extension($name);
				$extension = strtolower($extension);

				$new_name = $get_current_language_iso_four . "_inactive_" . $dim_array[$x] . "." . $extension;
				
				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_images = "warning";
						$fm_images = "unknown_file_extension";
					}
					else{
						$new_path = "_design/gfx/languages/$dim_array[$x]/";
						if(!(is_dir("$new_path"))){
							mkdir("$new_path");
						}
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_flag_inactive_$dim_array[$x]"]['tmp_name'], $uploaded_file)) {
	

							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_images = "warning";
								$fm_images = "getimagesize_failed";
								unlink($uploaded_file);
							}
							else{
								$ft_images = "success";
								$fm_images = "flag_uploaded";
								$inp_flag_mysql = quote_smart($link, $new_name);

								$result = mysqli_query($link, "UPDATE $t_languages SET 
											language_flag_path_$dim_array[$x]='_admin/_design/gfx/languages/$dim_array[$x]',
											language_flag_inactive_$dim_array[$x]=$inp_flag_mysql
											WHERE language_id=$get_current_language_id") or die(mysqli_error($link));
							
							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							$ft_images = "warning";
							switch ($_FILES["inp_flag_inactive_$dim_array[$x]"]['error']) {
								case UPLOAD_ERR_OK:
           								$fm_images = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_images = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_images = "to_big_size_in_form";
									break;
								default:
           								$fm_images = "unknown_error";
									break;
							}	
						}
					} // extension check
				} // if($image){ (Inactive)

			} // for

			$url = "index.php?open=$open&page=$page&action=edit_language&language_id=$get_current_language_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved&ft_images=$ft_images&fm_images=$fm_images";
			header("Location: $url");
			exit;
		
		}
		echo"
		<h2>Edit language $get_current_language_name</h2>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Languages</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_languages&amp;editor_language=$editor_language&amp;l=$l\">Edit languages</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_language&amp;language_id=$get_current_language_id&amp;editor_language=$editor_language&amp;l=$l\">Edit language $get_current_language_name</a>
			</p>
		<!-- //Where am I?  -->


		<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}

		if(isset($_GET['ft_images']) && isset($_GET['fm_images'])) {
			$ft_images = $_GET['ft_images'];
			$ft_images = strip_tags(stripslashes($ft_images));
			if($ft_images != ""){
				if($ft_images != "error" && $ft_images != "warning" && $ft_images != "success" && $ft_images != "info"){
					echo"Server error 403 feedback error";die;
				}
				$fm_images = $_GET['fm_images'];
				$fm_images = output_html($fm_images);
				echo"<div class=\"$ft_images\"><span>$fm_images</span></div>";
			}
		}

		echo"	
		<!-- //Feedback -->

		<!-- Edit language form -->
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_image\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_language&amp;language_id=$get_current_language_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p>Name:<br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_current_language_name\" size=\"25\" />
			</p>

			<p>Native name:<br />
			<input type=\"text\" name=\"inp_native_name\" value=\"$get_current_language_native_name\" size=\"25\" />
			</p>


			<p>ISO two:<br />
			<input type=\"text\" name=\"inp_iso_two\" value=\"$get_current_language_iso_two\" size=\"25\" />
			</p>

			<p>ISO three:<br />
			<input type=\"text\" name=\"inp_iso_three\" value=\"$get_current_language_iso_three\" size=\"25\" />
			</p>

			<p>ISO four:<br />
			<input type=\"text\" name=\"inp_iso_four\" value=\"$get_current_language_iso_four\" size=\"25\" />
			</p>

			<p>ISO two alternative a:<br />
			<input type=\"text\" name=\"inp_iso_two_alt_a\" value=\"$get_current_language_iso_two_alt_a\" size=\"25\" />
			</p>

			<p>ISO two alternative b:<br />
			<input type=\"text\" name=\"inp_iso_two_alt_b\" value=\"$get_current_language_iso_two_alt_b\" size=\"25\" />
			</p>

			<p>Charset:<br />
			<input type=\"text\" name=\"inp_charset\" value=\"$get_current_language_charset\" size=\"25\" />
			</p>

			<p>Flag emoji code: (<a href=\"index.php?open=settings&amp;page=emojies_index&amp;action=open_main_category&amp;main_category_id=10&amp;editor_language=$editor_language&amp;l=$l\">Emojis</a>)<br />
			<input type=\"text\" name=\"inp_flag_emoji_code\" value=\"$get_current_language_flag_emoji_code\" size=\"25\" />
			</p>

			<p>Flag emoji char:<br />
			<input type=\"text\" name=\"inp_flag_emoji_char\" value=\"$get_current_language_flag_emoji_char\" size=\"25\" />
			</p>

			<p>
			Flags download:<br />
			<a href=\"https://www.flaticon.com/free-icon/search?word=$get_current_language_slug\">flaticon.com</a>
			</p>
			";
			$dim_array = array("16x16", "18x18", "24x24", "32x32");
			$size = sizeof($dim_array);
			for($x=0;$x<sizeof($dim_array);$x++){
				if($dim_array[$x] == "16x16"){
					$path = "$get_current_language_flag_path_16x16";
					$active = "$get_current_language_flag_active_16x16";
					$inactive = "$get_current_language_flag_inactive_16x16";
				}
				elseif($dim_array[$x] == "18x18"){
					$path = "$get_current_language_flag_path_18x18";
					$active = "$get_current_language_flag_active_18x18";
					$inactive = "$get_current_language_flag_inactive_18x18";
				}
				elseif($dim_array[$x] == "24x24"){
					$path = "$get_current_language_flag_path_24x24";
					$active = "$get_current_language_flag_active_24x24";
					$inactive = "$get_current_language_flag_inactive_24x24";
				}
				else{
					$path = "$get_current_language_flag_path_32x32";
					$active = "$get_current_language_flag_active_32x32";
					$inactive = "$get_current_language_flag_inactive_32x32";
				}
				echo"
				<p><hr /></p>

				<p>
				<b>Flag $dim_array[$x]</b></p>

				<p>Active:<br />\n";
				if(file_exists("../$path/$active")){
					echo"<img src=\"../$path/$active\" alt=\"$active\" /><br />\n";
				
				}
				echo"
				<input type=\"file\" name=\"inp_flag_active_$dim_array[$x]\" />
				</p>
				<p>Inactive:<br />\n";
				if(file_exists("../$path/$inactive")){
					echo"<img src=\"../$path/$inactive\" alt=\"$inactive\" /><br />\n";
				
				}
				echo"
				<input type=\"file\" name=\"inp_flag_inactive_$dim_array[$x]\" />
				</p>
				";
			} // for dimensions
			echo"

			<p> 
			<input type=\"submit\" value=\"Save changes\" class=\"btn\" />
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_language&amp;language_id=$get_current_language_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_warning\">Delete</a>
			</p>
			</form>
					
		<!-- //Edit language form -->
		";
	} // found
} // action == edit_language
elseif($action == "delete_language"){
	if(isset($_GET['language_id'])) {
		$language_id = $_GET['language_id'];
		$language_id = strip_tags(stripslashes($language_id));
		if(!(is_numeric($language_id))){
			echo"Language not numeric";
			die;
		}
	}
	else{
		echo"Missing language";
		die;
	}
		
	// Locate this language
	$language_id_mysql = quote_smart($link, $language_id);
	$query = "SELECT language_id, language_name, language_slug, language_native_name, language_iso_two, language_iso_three, language_flag_path_16x16, language_flag_16x16, language_flag_path_32x32, language_flag_32x32, language_charset FROM $t_languages WHERE language_id=$language_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_language_id, $get_current_language_name, $get_current_language_slug, $get_current_language_native_name, $get_current_language_iso_two, $get_current_language_iso_three, $get_current_language_flag_path_16x16, $get_current_language_flag_16x16, $get_current_language_flag_path_32x32, $get_current_language_flag_32x32, $get_current_language_charset) = $row;
	if($get_current_language_id == ""){
		echo"Language not found";
	}
	else{
		if($process == "1"){
			$result = mysqli_query($link, "DELETE FROM $t_languages WHERE language_id=$get_current_language_id") or die(mysqli_error($link));

			// Icon 16x16
			if(file_exists("../$get_current_language_flag_path_16x16/$get_current_language_flag_16x16") && $get_current_language_flag_16x16 != ""){
				unlink("../$get_current_language_flag_path_16x16/$get_current_language_flag_16x16");
			}
			


			// Icon 32x32
			if(file_exists("../$get_current_language_flag_path_32x32/$get_current_language_flag_32x32") && $get_current_language_flag_32x32 != ""){
				unlink("../$get_current_language_flag_path_16x16/$get_current_language_flag_32x32");
				
			}
			

			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=language_deleted";
			header("Location: $url");
			exit;
		
		}
		echo"
		<h2>Delete language $get_current_language_name</h2>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Languages</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_languages&amp;editor_language=$editor_language&amp;l=$l\">Edit languages</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_language&amp;language_id=$get_current_language_id&amp;editor_language=$editor_language&amp;l=$l\">Edit language $get_current_language_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_language&amp;language_id=$get_current_language_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I?  -->


		<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Delete language form -->
			<p>
			Are you sure you want to delete the language?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_language&amp;language_id=$get_current_language_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>

		<!-- //Delete language form -->
		";
	} // found
} // action == edit_language
elseif($action == "edit_countries"){

	echo"
	<h2>Edit countries</h2>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Languages</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_countries&amp;editor_language=$editor_language&amp;l=$l\">Edit countries</a>
		</p>
	<!-- //Where am I?  -->


	<!-- Feedback -->
	";
	if($ft != ""){
		$fm = str_replace("_", " ", $fm);
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	


	<!-- Countries -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th colspan=\"2\">
			<span>Countries</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		$query = "SELECT country_id, country_name, country_flag_path_16x16, country_flag_active_16x16 FROM $t_languages_countries ORDER BY country_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_country_id, $get_country_name, $get_country_flag_path_16x16, $get_country_flag_active_16x16) = $row;
	
			echo"
			 <tr>
       			  <td style=\"padding-right:4px;\">
				<a href=\"index.php?open=settings&amp;page=languages&amp;action=edit_country&amp;country_id=$get_country_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:#000;\"><img src=\"../$get_country_flag_path_16x16/$get_country_flag_active_16x16\" alt=\"$get_country_flag_active_16x16\" /></a>
			  </td>
       			  <td>
          			<span><a href=\"index.php?open=settings&amp;page=languages&amp;action=edit_country&amp;country_id=$get_country_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:#000;\">$get_country_name</a></span>
			  </td>
     			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Countries -->
	";
} // action == edit_country
elseif($action == "edit_country"){
	if(isset($_GET['country_id'])) {
		$country_id = $_GET['country_id'];
		$country_id = strip_tags(stripslashes($country_id));
		if(!(is_numeric($country_id))){
			echo"Country not numeric";
			die;
		}
	}
	else{
		echo"Missing country";
		die;
	}
		
	// Locate this country
	$country_id_mysql = quote_smart($link, $country_id);
	$query = "SELECT country_id, country_name, country_name_clean, country_native_name, country_iso_two, country_iso_three, country_iso_four, country_iso_two_alt_a, country_iso_two_alt_b, country_flag_emoji_code, country_flag_emoji_char, country_flag_emoji_char_output_html, country_flag_emoji_char_string_value, country_flag_path_16x16, country_flag_active_16x16, country_flag_inactive_16x16, country_flag_path_18x18, country_flag_active_18x18, country_flag_inactive_18x18, country_flag_path_24x24, country_flag_active_24x24, country_flag_inactive_24x24, country_flag_path_32x32, country_flag_active_32x32, country_flag_inactive_32x32, country_created_datetime, country_created_user_id, country_updated_datetime, country_updated_user_id, country_last_used_datetime FROM $t_languages_countries WHERE country_id=$country_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_country_id, $get_current_country_name, $get_current_country_name_clean, $get_current_country_native_name, $get_current_country_iso_two, $get_current_country_iso_three, $get_current_country_iso_four, $get_current_country_iso_two_alt_a, $get_current_country_iso_two_alt_b, $get_current_country_flag_emoji_code, $get_current_country_flag_emoji_char, $get_current_country_flag_emoji_char_output_html, $get_current_country_flag_emoji_char_string_value, $get_current_country_flag_path_16x16, $get_current_country_flag_active_16x16, $get_current_country_flag_inactive_16x16, $get_current_country_flag_path_18x18, $get_current_country_flag_active_18x18, $get_current_country_flag_inactive_18x18, $get_current_country_flag_path_24x24, $get_current_country_flag_active_24x24, $get_current_country_flag_inactive_24x24, $get_current_country_flag_path_32x32, $get_current_country_flag_active_32x32, $get_current_country_flag_inactive_32x32, $get_current_country_created_datetime, $get_current_country_created_user_id, $get_current_country_updated_datetime, $get_current_country_updated_user_id, $get_current_country_last_used_datetime) = $row;
	if($get_current_country_id == ""){
		echo"Country not found";
	}
	else{
		if($process == "1"){
			$inp_name = $_POST['inp_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$inp_name_clean = clean($inp_name);
			$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

			$inp_native_name = $_POST['inp_native_name'];
			$inp_native_name = output_html($inp_native_name);
			$inp_native_name_mysql = quote_smart($link, $inp_native_name);

			$inp_iso_two = $_POST['inp_iso_two'];
			$inp_iso_two = output_html($inp_iso_two);
			$inp_iso_two_mysql = quote_smart($link, $inp_iso_two);

			$inp_iso_three = $_POST['inp_iso_three'];
			$inp_iso_three = output_html($inp_iso_three);
			$inp_iso_three_mysql = quote_smart($link, $inp_iso_three);

			$inp_iso_four = $_POST['inp_iso_four'];
			$inp_iso_four = output_html($inp_iso_four);
			$inp_iso_four_mysql = quote_smart($link, $inp_iso_four);


			$inp_flag_emoji_code = $_POST['inp_flag_emoji_code'];
			$inp_flag_emoji_code = output_html($inp_flag_emoji_code);
			$inp_flag_emoji_code = str_replace("&amp;", "&", $inp_flag_emoji_code);
			$inp_flag_emoji_code_mysql = quote_smart($link, $inp_flag_emoji_code);

			// Emoji char
			$inp_flag_emoji_char = $_POST['inp_flag_emoji_char'];
			$inp_flag_emoji_char = trim($inp_flag_emoji_char); // Trim and line space

			// Check last, if it is backslash, then replace it...
			$check  = substr($inp_flag_emoji_char, -1);
			$check  =  "^" . $check . "^";
			if($check == "^\^"){
				$new_value = substr($inp_flag_emoji_char, 0, -1);
				$inp_flag_emoji_char = $new_value . "&#92";
			}
			$inp_flag_emoji_char = str_replace("'", "&#039;", $inp_flag_emoji_char); // '
			$inp_flag_emoji_char = htmlentities($inp_flag_emoji_char, ENT_COMPAT, "UTF-8");
			$inp_flag_emoji_char_mysql = quote_smart($link, $inp_flag_emoji_char);

			$inp_flag_emoji_char_output_html = output_html($inp_flag_emoji_char);
			$inp_flag_emoji_char_output_html_mysql = quote_smart($link, $inp_flag_emoji_char_output_html);

			$inp_flag_emoji_char_string_value = "$inp_flag_emoji_char";
			$inp_flag_emoji_char_string_value_mysql = quote_smart($link, $inp_flag_emoji_char_string_value);

			$result = mysqli_query($link, "UPDATE $t_languages_countries SET 
							country_name=$inp_name_mysql, 
							country_name_clean=$inp_name_clean_mysql, 
							country_native_name=$inp_native_name_mysql, 
							country_iso_two=$inp_iso_two_mysql, 
							country_iso_three=$inp_iso_three_mysql, 
							country_iso_four=$inp_iso_three_mysql, 
							country_flag_emoji_code=$inp_flag_emoji_code_mysql,
							country_flag_emoji_char=$inp_flag_emoji_char_mysql,
							country_flag_emoji_char_output_html=$inp_flag_emoji_char_output_html_mysql,
							country_flag_emoji_char_string_value=$inp_flag_emoji_char_string_value_mysql
							WHERE country_id=$get_current_country_id") or die(mysqli_error($link));

			// Get new data before uploading flags
			$query = "SELECT country_id, country_name, country_name_clean, country_native_name, country_iso_two, country_iso_three, country_iso_four, country_iso_two_alt_a, country_iso_two_alt_b, country_flag_emoji_code, country_flag_emoji_char, country_flag_emoji_char_output_html, country_flag_emoji_char_string_value, country_flag_path_16x16, country_flag_active_16x16, country_flag_inactive_16x16, country_flag_path_18x18, country_flag_active_18x18, country_flag_inactive_18x18, country_flag_path_24x24, country_flag_active_24x24, country_flag_inactive_24x24, country_flag_path_32x32, country_flag_active_32x32, country_flag_inactive_32x32, country_created_datetime, country_created_user_id, country_updated_datetime, country_updated_user_id, country_last_used_datetime FROM $t_languages_countries WHERE country_id=$country_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_country_id, $get_current_country_name, $get_current_country_name_clean, $get_current_country_native_name, $get_current_country_iso_two, $get_current_country_iso_three, $get_current_country_iso_four, $get_current_country_iso_two_alt_a, $get_current_country_iso_two_alt_b, $get_current_country_flag_emoji_code, $get_current_country_flag_emoji_char, $get_current_country_flag_emoji_char_output_html, $get_current_country_flag_emoji_char_string_value, $get_current_country_flag_path_16x16, $get_current_country_flag_active_16x16, $get_current_country_flag_inactive_16x16, $get_current_country_flag_path_18x18, $get_current_country_flag_active_18x18, $get_current_country_flag_inactive_18x18, $get_current_country_flag_path_24x24, $get_current_country_flag_active_24x24, $get_current_country_flag_inactive_24x24, $get_current_country_flag_path_32x32, $get_current_country_flag_active_32x32, $get_current_country_flag_inactive_32x32, $get_current_country_created_datetime, $get_current_country_created_user_id, $get_current_country_updated_datetime, $get_current_country_updated_user_id, $get_current_country_last_used_datetime) = $row;


			// Flags
			if(!(is_dir("_design/gfx/countries"))){
				mkdir("_design/gfx/countries");
			}
			$ft_images = "";
			$fm_images = "";

			$dim_array = array("16x16", "18x18", "24x24", "32x32");
			$size = sizeof($dim_array);
			for($x=0;$x<sizeof($dim_array);$x++){
				

				// Active
				$name = stripslashes($_FILES["inp_flag_active_$dim_array[$x]"]['name']);
				$name = output_html($name);
				$extension = get_extension($name);
				$extension = strtolower($extension);

				$new_name = $get_current_country_name_clean . "_active_" . $dim_array[$x] . "." . $extension;
				
				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_images = "warning";
						$fm_images = "unknown_file_extension";
					}
					else{
						$new_path = "_design/gfx/countries/$dim_array[$x]/";
						if(!(is_dir("$new_path"))){
							mkdir("$new_path");
						}
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_flag_active_$dim_array[$x]"]['tmp_name'], $uploaded_file)) {
	

							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_images = "warning";
								$fm_images = "getimagesize_failed";
								unlink($uploaded_file);
							}
							else{
								$ft_images = "success";
								$fm_images = "flag_uploaded";
								$inp_flag_mysql = quote_smart($link, $new_name);

								$result = mysqli_query($link, "UPDATE $t_languages_countries SET 
											country_flag_path_$dim_array[$x]='_admin/_design/gfx/countries/$dim_array[$x]',
											country_flag_active_$dim_array[$x]=$inp_flag_mysql
											WHERE country_id=$get_current_country_id") or die(mysqli_error($link));
							
							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							$ft_images = "warning";
							switch ($_FILES["inp_flag_active_$dim_array[$x]"]['error']) {
								case UPLOAD_ERR_OK:
           								$fm_images = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_images = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_images = "to_big_size_in_form";
									break;
								default:
           								$fm_images = "unknown_error";
									break;
							}	
						}
					} // extension check
				} // if($image){ (Active)


				// Inactive
				$name = stripslashes($_FILES["inp_flag_inactive_$dim_array[$x]"]['name']);
				$name = output_html($name);
				$extension = get_extension($name);
				$extension = strtolower($extension);

				$new_name = $get_current_country_name_clean . "_inactive_" . $dim_array[$x] . "." . $extension;
				
				if($name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft_images = "warning";
						$fm_images = "unknown_file_extension";
					}
					else{
						$new_path = "_design/gfx/countries/$dim_array[$x]/";
						if(!(is_dir("$new_path"))){
							mkdir("$new_path");
						}
						$uploaded_file = $new_path . $new_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_flag_inactive_$dim_array[$x]"]['tmp_name'], $uploaded_file)) {
	

							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								$ft_images = "warning";
								$fm_images = "getimagesize_failed";
								unlink($uploaded_file);
							}
							else{
								$ft_images = "success";
								$fm_images = "flag_uploaded";
								$inp_flag_mysql = quote_smart($link, $new_name);


								$result = mysqli_query($link, "UPDATE $t_languages_countries SET 
											country_flag_path_$dim_array[$x]='_admin/_design/gfx/countries/$dim_array[$x]',
											country_flag_inactive_$dim_array[$x]=$inp_flag_mysql
											WHERE country_id=$get_current_country_id") or die(mysqli_error($link));
							
							}  // if($width == "" OR $height == ""){
						} // move_uploaded_file
						else{
							$ft_images = "warning";
							switch ($_FILES["inp_flag_inactive_$dim_array[$x]"]['error']) {
								case UPLOAD_ERR_OK:
           								$fm_images = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								// $fm_image = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm_images = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm_images = "to_big_size_in_form";
									break;
								default:
           								$fm_images = "unknown_error";
									break;
							}	
						}
					} // extension check
				} // if($image){ (Inactive)

			} // for




			$url = "index.php?open=$open&page=$page&action=edit_country&country_id=$get_current_country_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved&ft_image_a=$ft_image_a&fm_image_a=$fm_image_a&ft_image_b=$ft_image_b&fm_image_b=$fm_image_b";
			header("Location: $url");
			exit;
		
		}
		echo"
		<h2>Edit country $get_current_country_name</h2>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Languages</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_countries&amp;editor_language=$editor_language&amp;l=$l\">Edit countries</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_country&amp;country_id=$get_current_country_id&amp;editor_language=$editor_language&amp;l=$l\">Edit country $get_current_country_name</a>
			</p>
		<!-- //Where am I?  -->


		<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Edit country form -->
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_food_image\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_country&amp;country_id=$get_current_country_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p>Name:<br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_current_country_name\" size=\"25\" />
			</p>

			<p>Native name:<br />
			<input type=\"text\" name=\"inp_native_name\" value=\"$get_current_country_native_name\" size=\"25\" />
			</p>

			<p>ISO two:<br />
			<input type=\"text\" name=\"inp_iso_two\" value=\"$get_current_country_iso_two\" size=\"25\" />
			</p>

			<p>ISO three:<br />
			<input type=\"text\" name=\"inp_iso_three\" value=\"$get_current_country_iso_three\" size=\"25\" />
			</p>

			<p>ISO four:<br />
			<input type=\"text\" name=\"inp_iso_four\" value=\"$get_current_country_iso_four\" size=\"25\" />
			</p>


			<p>Flag emoji code: (<a href=\"index.php?open=settings&amp;page=emojies_index&amp;action=open_main_category&amp;main_category_id=10&amp;editor_language=$editor_language&amp;l=$l\">Emojis</a>)<br />
			<input type=\"text\" name=\"inp_flag_emoji_code\" value=\"$get_current_country_flag_emoji_code\" size=\"25\" />
			</p>

			<p>Flag emoji char:<br />
			<input type=\"text\" name=\"inp_flag_emoji_char\" value=\"$get_current_country_flag_emoji_char\" size=\"25\" />
			</p>

			<p>
			Flags download:<br />
			<a href=\"https://www.flaticon.com/free-icon/search?word=$get_current_country_name_clean\">flaticon.com</a>
			</p>
			";
			$dim_array = array("16x16", "18x18", "24x24", "32x32");
			$size = sizeof($dim_array);
			for($x=0;$x<sizeof($dim_array);$x++){
				if($dim_array[$x] == "16x16"){
					$path = "$get_current_country_flag_path_16x16";
					$active = "$get_current_country_flag_active_16x16";
					$inactive = "$get_current_country_flag_inactive_16x16";
				}
				elseif($dim_array[$x] == "18x18"){
					$path = "$get_current_country_flag_path_18x18";
					$active = "$get_current_country_flag_active_18x18";
					$inactive = "$get_current_country_flag_inactive_18x18";
				}
				elseif($dim_array[$x] == "24x24"){
					$path = "$get_current_country_flag_path_24x24";
					$active = "$get_current_country_flag_active_24x24";
					$inactive = "$get_current_country_flag_inactive_24x24";
				}
				else{
					$path = "$get_current_country_flag_path_32x32";
					$active = "$get_current_country_flag_active_32x32";
					$inactive = "$get_current_country_flag_inactive_32x32";
				}
				echo"
				<p><hr /></p>

				<p>
				<b>Flag $dim_array[$x]</b></p>

				<p>Active:<br />\n";
				if(file_exists("../$path/$active")){
					echo"<img src=\"../$path/$active\" alt=\"$active\" /><br />\n";
				
				}
				echo"
				<input type=\"file\" name=\"inp_flag_active_$dim_array[$x]\" />
				</p>
				<p>Inactive:<br />\n";
				if(file_exists("../$path/$inactive")){
					echo"<img src=\"../$path/$inactive\" alt=\"$inactive\" /><br />\n";
				
				}
				echo"
				<input type=\"file\" name=\"inp_flag_inactive_$dim_array[$x]\" />
				</p>
				";
			} // for dimensions
			echo"


			<p> 
			<input type=\"submit\" value=\"Save changes\" class=\"btn\" />
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_country&amp;country_id=$get_current_country_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_warning\">Delete</a>
			</p>
			</form>
					
		<!-- //Edit country form -->
		";
	} // found
} // action == edit_country
elseif($action == "delete_country"){
	if(isset($_GET['country_id'])) {
		$country_id = $_GET['country_id'];
		$country_id = strip_tags(stripslashes($country_id));
		if(!(is_numeric($country_id))){
			echo"Country not numeric";
			die;
		}
	}
	else{
		echo"Missing country";
		die;
	}
		
	// Locate this country
	$country_id_mysql = quote_smart($link, $country_id);
	$query = "SELECT country_id, country_name, country_name_clean, country_native_name, country_iso_two, country_iso_three, country_language_alt_a, country_language_alt_b, country_flag_path_16x16, country_flag_16x16, country_flag_path_32x32, country_flag_32x32 FROM $t_languages_countries WHERE country_id=$country_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_country_id, $get_current_country_name, $get_current_country_name_clean, $get_current_country_native_name, $get_current_country_iso_two, $get_current_country_iso_three, $get_current_country_language_alt_a, $get_current_country_language_alt_b, $get_current_country_flag_path_16x16, $get_current_country_flag_16x16, $get_current_country_flag_path_32x32, $get_current_country_flag_32x32) = $row;
	if($get_current_country_id == ""){
		echo"Country not found";
	}
	else{
		if($process == "1"){
			$result = mysqli_query($link, "DELETE FROM $t_languages_countries WHERE country_id=$get_current_country_id") or die(mysqli_error($link));

			// Icon 16x16
			if(file_exists("../$get_current_country_flag_path_16x16/$get_current_country_flag_16x16") && $get_current_country_flag_16x16 != ""){
				unlink("../$get_current_country_flag_path_16x16/$get_current_country_flag_16x16");
			}
			


			// Icon 32x32
			if(file_exists("../$get_current_country_flag_path_32x32/$get_current_country_flag_32x32") && $get_current_country_flag_32x32 != ""){
				unlink("../$get_current_country_flag_path_16x16/$get_current_country_flag_32x32");
				
			}
			

			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=country_deleted";
			header("Location: $url");
			exit;
		
		}
		echo"
		<h2>Delete country $get_current_country_name</h2>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Languages</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_countries&amp;editor_language=$editor_language&amp;l=$l\">Edit countries</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_country&amp;country_id=$get_current_country_id&amp;editor_language=$editor_language&amp;l=$l\">Edit country $get_current_country_name</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_country&amp;country_id=$get_current_country_id&amp;editor_language=$editor_language&amp;l=$l\">Delete country $get_current_country_name</a>
			</p>
		<!-- //Where am I?  -->


		<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Delete country form -->
			<p>
			Are you sure you want to delete the country?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_country&amp;country_id=$get_current_country_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>

		<!-- //Delete country form -->
		";
	} // found
} // action == edit_country
?>