<?php

/*- Content --------------------------------------------------------------------------- */
if(isset($can_view_profile)){
	// Get headline
	$query = "SELECT headline_id, headline_title, headline_title_clean, headline_icon_path_18x18, headline_icon_file_18x18, headline_weight, headline_user_can_view_headline, headline_show_on_profile FROM $t_users_profile_headlines WHERE headline_id=$headline_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_headline_id, $get_current_headline_title, $get_current_headline_title_clean, $get_current_headline_icon_path_18x18, $get_current_headline_icon_file_18x18, $get_current_headline_weight, $get_current_headline_user_can_view_headline, $get_current_headline_show_on_profile) = $row;
	if($get_current_headline_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Headline not found.</p>
		";
	}
	else{
		// Data
		$t_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;
		$query_data = "SELECT * FROM $t_users_profile_data WHERE data_user_id=$get_current_user_id";
		$result_data = mysqli_query($link, $query_data);
		$row_data = mysqli_fetch_row($result_data);

		echo"
		<table>
		";
		$col_no = 2;
		$query = "SELECT field_id, field_headline_id, field_title, field_title_clean, field_weight, field_height, field_type, field_size, field_width, field_cols, field_rows FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id AND field_show_on_profile=1 ORDER BY field_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_field_id, $get_field_headline_id, $get_field_title, $get_field_title_clean, $get_field_weight, $get_field_height, $get_field_type, $get_field_size, $get_field_width, $get_field_cols, $get_field_rows) = $row;
				
			// Get translation
			$query_t = "SELECT translation_id, translation_field_id, translation_language, translation_value FROM $t_users_profile_fields_translations WHERE translation_field_id=$get_field_id AND translation_language=$l_mysql";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_translation_id, $get_translation_field_id, $get_translation_language, $get_translation_value) = $row_t;
			if($get_translation_id == ""){
				$inp_title_mysql = quote_smart($link, $get_field_title);
				mysqli_query($link, "INSERT INTO $t_users_profile_fields_translations
				(translation_id, translation_field_id, translation_headline_id, translation_language, translation_value) 
				VALUES 
				(NULL, $get_current_headline_id, $get_field_id, $l_mysql, $inp_title_mysql)")
				or die(mysqli_error($link));
				$get_translation_value = "$get_field_title";
			}

			if(isset($row_data[$col_no])){
				echo"
				 <tr>
				  <td style=\"padding: 0px 8px 8px 0px;vertical-align:top;\">
					<span class=\"grey_dark\">$get_translation_value:</span>
				  </td>
				  <td style=\"padding: 0px 0px 8px 0px;vertical-align:top;\">
					<span>";
					if($get_field_type == "url"){
						if($row_data[$col_no] != ""){
							echo"<a href=\"$row_data[$col_no]\">$row_data[$col_no]</a>";
						}
					}
					elseif($get_field_type == "checkbox"){
						if($row_data[$col_no] == "1"){
							echo"$l_checked";
						}
						elseif($row_data[$col_no] == "1"){
							echo"$l_unchecked";
						}
					}
					else{
						echo"$row_data[$col_no]";
					}
					echo"</span>
				  </td>
				 </tr>
				";
			}
			$col_no++;
		} // fields

		echo"
		</table>
		";
	} // headline found
} // user found
?>