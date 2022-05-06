<?php
ini_set('set_time_limit', 3600);
ini_set('max_execution_time', 3600);

/**
*
* File: _admin/_inc/hash_db/upload_hash_db.php
* Version 1.0
* Date: 11:41 22.02.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */



/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";

if($action == ""){
	echo"
	<h1>Upload hash db</h1>


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "user_deleted"){
			$fm = "$l_user_deleted";
		}
		else{
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=hash_db&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash Db</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=entries&amp;editor_language=$editor_language&amp;l=$l\">Entries</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Upload hash db</a>
		</p>
	<!-- //Where am I? -->

	<!-- Upload hash db form -->
		<table>
		 <tr>
		  <td>
			<form method=\"post\" action=\"index.php?open=hash_db&amp;page=$page&amp;action=upload&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p>Format of each line:<br />
			<select name=\"format_of_each_line\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
				<option value=\"all_data\">All data</option>
				<option value=\"entry_file_content_md5\">entry_file_content_md5</option>
			</select>
			</p>

	
			<p>If Format of each line = entry_file_content_md5, then what category?:<br />
			<select name=\"inp_category_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">\n";
			$query = "SELECT category_id, category_title FROM $t_hash_db_categories ORDER BY category_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_title) = $row;
				echo"				<option value=\"$get_category_id\">$get_category_title</option>\n";
			}
			echo"
			</select>
			</p>

			<p>File (.txt or csv):<br />
			<input name=\"inp_file\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Upload file\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
		  </td>
		  <td>
			<p>
			First line of text file should contain header.
			</p>

			<p>Format all data:<br />
			entry_category_id|entry_file_path|entry_file_name|entry_file_extension|entry_file_mime|entry_file_size_bytes|entry_file_size_human|entry_file_created_datetime|entry_file_created_saying|entry_file_last_changed_datetime|entry_file_last_changed_saying|entry_file_name_md5|entry_file_name_sha1|entry_file_content_md5|entry_file_content_sha1</p>
			<p>Format entry_file_content_md5:<br />
			entry_file_content_md5</p>
		  </td>
		 </tr>
		</table>

		<div class=\"clear\"></div>
	<!-- //Download hash db form -->

	";
}
elseif($action == "upload" && $process == "1"){


	if(isset($_POST['format_of_each_line'])) {
		$format_of_each_line = $_POST['format_of_each_line'];
		$format_of_each_line = output_html($format_of_each_line);
	}
	else{
		$url = "index.php?open=hash_db&page=$page&editor_language=$editor_language&l=$l&ft=info&fm=missing_format_for_each_line";
		header("Location: $url");
		exit;
	}
	
	$datetime = date("Y-m-d H:i:s");
	$datetime_saying = date("j M Y H:i");

	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
		
	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
					
	$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);

	$inp_my_ip = $_SERVER['REMOTE_ADDR'];
	$inp_my_ip = output_html($inp_my_ip);
	$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);


	// Upload file
	$uploads_dir = "../_cache";
	$error = $_FILES["inp_file"]["error"];
	$new_name = date("ymdhis");
	$new_name = "hash_db_upload_" . $new_name . ".txt";
	if ($error == UPLOAD_ERR_OK) {
		$tmp_name = $_FILES["inp_file"]["tmp_name"];
        	if(move_uploaded_file($tmp_name, "$uploads_dir/$new_name")){
			// Read text file
			$fh = fopen("$uploads_dir/$new_name", "r");
			$data = fread($fh, filesize("$uploads_dir/$new_name"));
			fclose($fh);

			$lines = explode("\n", $data);
			$lines_size = sizeof($lines);

			for($x=1;$x<$lines_size;$x++){
				if($format_of_each_line == "all_data"){
					$temp = explode("|", $lines[$x]);
					if(isset($temp[0]) && isset($temp[1])){
						$entry_category_id = $temp[0];
						$entry_category_id = output_html($entry_category_id);
						$entry_category_id_mysql = quote_smart($link, $entry_category_id);

						// Get category title
						$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color, category_is_illegal, category_is_interesting FROM $t_hash_db_categories WHERE category_id=$entry_category_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color, $get_current_category_is_illegal, $get_current_category_is_interesting) = $row;
						if($get_current_category_id == ""){
							$get_current_category_id = "0";
						}
						$inp_category_title_mysql = quote_smart($link, $get_current_category_title);

						$inp_file_path = $temp[1];
						$inp_file_path = output_html($inp_file_path);
						$inp_file_path_mysql = quote_smart($link, $inp_file_path);

						$inp_file_name = $temp[2];
						$inp_file_name = output_html($inp_file_name);
						$inp_file_name_mysql = quote_smart($link, $inp_file_name);

						$inp_file_extension = $temp[3];
						$inp_file_extension = output_html($inp_file_extension);
						$inp_file_extension_mysql = quote_smart($link, $inp_file_extension);

						$inp_file_mime = $temp[4];
						$inp_file_mime = output_html($inp_file_mime);
						$inp_file_mime_mysql = quote_smart($link, $inp_file_mime);

						$inp_file_size_bytes = $temp[5];
						$inp_file_size_bytes = output_html($inp_file_size_bytes);
						$inp_file_size_bytes_mysql = quote_smart($link, $inp_file_size_bytes);

						$inp_file_size_human = $temp[6];
						$inp_file_size_human = output_html($inp_file_size_human);
						$inp_file_size_human_mysql = quote_smart($link, $inp_file_size_human);

						$inp_file_created_datetime = $temp[7];
						$inp_file_created_datetime = output_html($inp_file_created_datetime);
						$inp_file_created_datetime_mysql = quote_smart($link, $inp_file_created_datetime);

						$inp_file_created_datetime_saying = $temp[8];
						$inp_file_created_datetime_saying = output_html($inp_file_created_datetime_saying);
						$inp_file_created_datetime_saying_mysql = quote_smart($link, $inp_file_created_datetime_saying);

						$inp_file_last_changed_datetime = $temp[9];
						$inp_file_last_changed_datetime = output_html($inp_file_last_changed_datetime);
						$inp_file_last_changed_datetime_mysql = quote_smart($link, $inp_file_last_changed_datetime);

						$inp_file_last_changed_datetime_saying = $temp[10];
						$inp_file_last_changed_datetime_saying = output_html($inp_file_last_changed_datetime_saying);
						$inp_file_last_changed_datetime_saying_mysql = quote_smart($link, $inp_file_last_changed_datetime_saying);

						$inp_file_name_md5 = $temp[11];
						$inp_file_name_md5 = output_html($inp_file_name_md5);
						$inp_file_name_md5_mysql = quote_smart($link, $inp_file_name_md5);

						$inp_file_name_sha1 = $temp[12];
						$inp_file_name_sha1 = output_html($inp_file_name_sha1);
						$inp_file_name_sha1_mysql = quote_smart($link, $inp_file_name_sha1);

						$inp_file_content_md5 = $temp[13];
						$inp_file_content_md5 = output_html($inp_file_content_md5);
						$inp_file_content_md5_mysql = quote_smart($link, $inp_file_content_md5);

						$inp_file_content_sha1 = $temp[14];
						$inp_file_content_sha1 = output_html($inp_file_content_sha1);
						$inp_file_content_sha1_mysql = quote_smart($link, $inp_file_content_sha1);

					
						// Check if exists for 1) file content md5
						$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries WHERE entry_file_content_md5=$inp_file_content_md5_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_entry_id, $get_current_entry_category_id, $get_current_entry_category_title, $get_current_entry_file_path, $get_current_entry_file_name, $get_current_entry_file_extension, $get_current_entry_file_mime, $get_current_entry_file_size_bytes, $get_current_entry_file_size_human, $get_current_entry_file_created_datetime, $get_current_entry_file_created_saying, $get_current_entry_file_last_changed_datetime, $get_current_entry_file_last_changed_saying, $get_current_entry_file_name_md5, $get_current_entry_file_name_sha1, $get_current_entry_file_content_md5, $get_current_entry_file_content_sha1, $get_current_entry_created_datetime, $get_current_entry_created_saying, $get_current_entry_created_by_user_id, $get_current_entry_created_by_user_name, $get_current_entry_updated_datetime, $get_current_entry_updated_saying, $get_current_entry_updated_by_user_id, $get_current_entry_updated_by_user_name, $get_current_entry_hits) = $row;

						if($get_current_entry_id == ""){
							// Insert
							mysqli_query($link, "INSERT INTO $t_hash_db_entries 
							(entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, 
							entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, 
							entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, 
							entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, 
							entry_created_by_user_name, entry_hits)
							VALUES
							(NULL, $get_current_category_id, $inp_category_title_mysql, $inp_file_path_mysql, $inp_file_name_mysql, 
							$inp_file_extension_mysql, $inp_file_mime_mysql, $inp_file_size_bytes_mysql, $inp_file_size_human_mysql, $inp_file_created_datetime_mysql, 
							$inp_file_created_datetime_saying_mysql, $inp_file_last_changed_datetime_mysql, $inp_file_last_changed_datetime_saying_mysql, $inp_file_name_md5_mysql, $inp_file_name_sha1_mysql, 
							$inp_file_content_md5_mysql, $inp_file_content_sha1_mysql,  '$datetime', '$datetime_saying', $get_my_user_id, 
							$inp_my_user_name_mysql, 0
							)") or die(mysqli_error($link)); 
						}
					} // isset temp 0 and temp 1

				} // all data
				else{
					$entry_category_id = $_POST['inp_category_id'];
					$entry_category_id = output_html($entry_category_id);
					$entry_category_id_mysql = quote_smart($link, $entry_category_id);

					// Get category title
					$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color, category_is_illegal, category_is_interesting FROM $t_hash_db_categories WHERE category_id=$entry_category_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color, $get_current_category_is_illegal, $get_current_category_is_interesting) = $row;
					if($get_current_category_id == ""){
						$get_current_category_id = "0";
					}
					$inp_category_title_mysql = quote_smart($link, $get_current_category_title);


					// Only MD5
					$inp_file_content_md5 = $lines[$x];
					$inp_file_content_md5 = output_html($inp_file_content_md5);
					$inp_file_content_md5_mysql = quote_smart($link, $inp_file_content_md5);

					// Check if exists for 1) file content md5
					$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries WHERE entry_file_content_md5=$inp_file_content_md5_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_entry_id, $get_current_entry_category_id, $get_current_entry_category_title, $get_current_entry_file_path, $get_current_entry_file_name, $get_current_entry_file_extension, $get_current_entry_file_mime, $get_current_entry_file_size_bytes, $get_current_entry_file_size_human, $get_current_entry_file_created_datetime, $get_current_entry_file_created_saying, $get_current_entry_file_last_changed_datetime, $get_current_entry_file_last_changed_saying, $get_current_entry_file_name_md5, $get_current_entry_file_name_sha1, $get_current_entry_file_content_md5, $get_current_entry_file_content_sha1, $get_current_entry_created_datetime, $get_current_entry_created_saying, $get_current_entry_created_by_user_id, $get_current_entry_created_by_user_name, $get_current_entry_updated_datetime, $get_current_entry_updated_saying, $get_current_entry_updated_by_user_id, $get_current_entry_updated_by_user_name, $get_current_entry_hits) = $row;

					if($get_current_entry_id == ""){
						// Insert
						mysqli_query($link, "INSERT INTO $t_hash_db_entries 
						(entry_id, entry_category_id, entry_category_title, entry_file_content_md5, entry_created_datetime, 
						entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_hits)
						VALUES
						(NULL, $get_current_category_id, $inp_category_title_mysql, $inp_file_content_md5_mysql, '$datetime', 
						'$datetime_saying', $get_my_user_id, $inp_my_user_name_mysql, 0
						)") or die(mysqli_error($link)); 
					}

				}
			} // loop for

		}
		else{
			$url = "index.php?open=hash_db&page=$page&editor_language=$editor_language&l=$l&ft=error&fm=could_not_upload_file";
			header("Location: $url");
			exit;
		}
		
	}
	else{
		$url = "index.php?open=hash_db&page=$page&editor_language=$editor_language&l=$l&ft=info&fm=no_file_selected";
		header("Location: $url");
		exit;
	}

	echo"
	<meta http-equiv=refresh content=\""; $rand = rand(0,2); echo"$rand; URL=index.php?open=$open&amp;page=$page&amp;ft=success&fm=entries_added\">
	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;ft=success&fm=entries_added\" class=\"btn\">Continue</a>
	</p>
	";
} // action == "upload"
?>