<?php
ini_set('set_time_limit', 3600);
ini_set('max_execution_time', 3600);

/**
*
* File: _admin/_inc/hash_db/download_hash_db.php
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
	<h1>Download hash db</h1>


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
		<a href=\"index.php?open=hash_db&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Download hash db</a>
		</p>
	<!-- //Where am I? -->

	<!-- Download hash db form -->

		<form method=\"get\" action=\"index.php\" enctype=\"multipart/form-data\">
		<input type=\"hidden\" name=\"open\" value=\"$open\" />
		<input type=\"hidden\" name=\"page\" value=\"$page\" />
		<input type=\"hidden\" name=\"action\" value=\"generate\" />
		<input type=\"hidden\" name=\"editor_language\" value=\"$editor_language\" />
		<input type=\"hidden\" name=\"l\" value=\"$l\" />
		<input type=\"hidden\" name=\"process\" value=\"1\" />

		<p>Category:<br />
		<select name=\"category_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
			<option value=\"0\">All categories</option>\n";
			$query = "SELECT category_id, category_title FROM $t_hash_db_categories ORDER BY category_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_title) = $row;
				echo"				<option value=\"$get_category_id\">$get_category_title</option>\n";
			}
			echo"
		</select>
		</p>

		<p>Format of each line:<br />
		<select name=\"format_of_each_line\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
			<option value=\"all_data\">All data</option>
			<option value=\"entry_file_content_md5\">entry_file_content_md5</option>
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Create file and download\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //Download hash db form -->

	";
}
elseif($action == "generate" && $process == "1"){
	if(isset($_GET['category_id'])) {
		$category_id = $_GET['category_id'];
		$category_id = output_html($category_id);
		if(!(is_numeric($category_id))){
			echo"category_id not numeric"; die;
		}
	}
	else{
		$url = "index.php?open=hash_db&page=download_hash_db&editor_language=$editor_language&l=$l&ft=info&fm=missing_category";
		header("Location: $url");
		exit;
	}
	$category_id_mysql = quote_smart($link, $category_id);


	if(isset($_GET['format_of_each_line'])) {
		$format_of_each_line = $_GET['format_of_each_line'];
		$format_of_each_line = output_html($format_of_each_line);
	}
	else{
		$url = "index.php?open=hash_db&page=download_hash_db&editor_language=$editor_language&l=$l&ft=info&fm=missing_format_for_each_line";
		header("Location: $url");
		exit;
	}
	
	// Find category
	$get_current_category_id = "";
	$export_file_title_clean = "hash_db_all_categories";
	if($category_id != "0"){
		$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color, category_is_illegal, category_is_interesting FROM $t_hash_db_categories WHERE category_id=$category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color, $get_current_category_is_illegal, $get_current_category_is_interesting) = $row;

		if($get_current_category_id == ""){
			$url = "index.php?open=hash_db&page=download_hash_db&editor_language=$editor_language&l=$l&ft=info&fm=category_not_found";
			header("Location: $url");
			exit;
		}
		
		// Clean
		$category_title_clean 	 = clean($get_current_category_title);
		$export_file_title_clean = "hash_db_" . $category_title_clean;

	} // category != 0

	// Export header
	$export_header = "entry_id|entry_category_id|entry_category_title|entry_file_path|entry_file_name|entry_file_extension|entry_file_mime|entry_file_size_bytes|entry_file_size_human|entry_file_created_datetime|entry_file_created_saying|entry_file_last_changed_datetime|entry_file_last_changed_saying|entry_file_name_md5|entry_file_name_sha1|entry_file_content_md5|entry_file_content_sha1|entry_created_datetime|entry_created_saying|entry_created_by_user_id|entry_created_by_user_name|entry_updated_datetime|entry_updated_saying|entry_updated_by_user_id|entry_updated_by_user_name|entry_hits";
	if($format_of_each_line == "entry_file_content_md5"){
		$export_header = "entry_file_content_md5";
	}

	// Generate file
	$date = date("Y-m-d");
	$export_file_title_clean = $export_file_title_clean . "_" . $date . ".txt";

	if(file_exists("../_cache/$export_file_title_clean")){
		unlink("../_cache/$export_file_title_clean");
	}
	$fh = fopen("../_cache/$export_file_title_clean", "w") or die("can not open file");
	fwrite($fh, $export_header);
	fclose($fh); 


	// Get enteties
	$input_body = "";
	$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries";
	if($get_current_category_id != ""){
		$query = $query . " WHERE entry_category_id=$get_current_category_id";
	}
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_entry_id, $get_entry_category_id, $get_entry_category_title, $get_entry_file_path, $get_entry_file_name, $get_entry_file_extension, $get_entry_file_mime, $get_entry_file_size_bytes, $get_entry_file_size_human, $get_entry_file_created_datetime, $get_entry_file_created_saying, $get_entry_file_last_changed_datetime, $get_entry_file_last_changed_saying, $get_entry_file_name_md5, $get_entry_file_name_sha1, $get_entry_file_content_md5, $get_entry_file_content_sha1, $get_entry_created_datetime, $get_entry_created_saying, $get_entry_created_by_user_id, $get_entry_created_by_user_name, $get_entry_updated_datetime, $get_entry_updated_saying, $get_entry_updated_by_user_id, $get_entry_updated_by_user_name, $get_entry_hits) = $row;

		if($format_of_each_line == "entry_file_content_md5"){
			$input_body = "
$get_entry_file_content_md5";
		}
		else{
			$input_body = "
$get_entry_id|$get_entry_category_id|$get_entry_category_title|$get_entry_file_path|$get_entry_file_name|$get_entry_file_extension|$get_entry_file_mime|$get_entry_file_size_bytes|$get_entry_file_size_human|$get_entry_file_created_datetime|$get_entry_file_created_saying|$get_entry_file_last_changed_datetime|$get_entry_file_last_changed_saying|$get_entry_file_name_md5|$get_entry_file_name_sha1|$get_entry_file_content_md5|$get_entry_file_content_sha1|$get_entry_created_datetime|$get_entry_created_saying|$get_entry_created_by_user_id|$get_entry_created_by_user_name|$get_entry_updated_datetime|$get_entry_updated_saying|$get_entry_updated_by_user_id|$get_entry_updated_by_user_name|$get_entry_hits";
		}
		$fh = fopen("../_cache/$export_file_title_clean", "a+") or die("can not open file");
		fwrite($fh, $input_body);
		fclose($fh); 
	}

	// Open file
	echo"<meta http-equiv=refresh content=\"5; URL=../_cache/$export_file_title_clean\">\n";

} // action == "generate"
?>