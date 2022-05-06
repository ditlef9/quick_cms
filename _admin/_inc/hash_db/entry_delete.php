<?php
/**
*
* File: _admin/_inc/hash_db/entry_delete.php
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
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method != "asc" && $order_method != "desc"){
		echo"Wrong order method";
		die;
	}
}
else{
	$order_method = "asc";
}
if(isset($_GET['entry_id'])) {
	$entry_id = $_GET['entry_id'];
	$entry_id = strip_tags(stripslashes($entry_id));
	if(!(is_numeric($entry_id))){
		echo"entry_id not numeric"; die;
	}
}
else{
	$entry_id = "";
}
$entry_id_mysql = quote_smart($link, $entry_id);

/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";



if($action == ""){
	$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries WHERE entry_id=$entry_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_entry_id, $get_current_entry_category_id, $get_current_entry_category_title, $get_current_entry_file_path, $get_current_entry_file_name, $get_current_entry_file_extension, $get_current_entry_file_mime, $get_current_entry_file_size_bytes, $get_current_entry_file_size_human, $get_current_entry_file_created_datetime, $get_current_entry_file_created_saying, $get_current_entry_file_last_changed_datetime, $get_current_entry_file_last_changed_saying, $get_current_entry_file_name_md5, $get_current_entry_file_name_sha1, $get_current_entry_file_content_md5, $get_current_entry_file_content_sha1, $get_current_entry_created_datetime, $get_current_entry_created_saying, $get_current_entry_created_by_user_id, $get_current_entry_created_by_user_name, $get_current_entry_updated_datetime, $get_current_entry_updated_saying, $get_current_entry_updated_by_user_id, $get_current_entry_updated_by_user_name, $get_current_entry_hits) = $row;

	if($get_current_entry_id == ""){
		echo"<p>Entry not found</p>";
	}
	else{
	

		if($process == "1"){
		
			// Update
			mysqli_query($link, "DELETE FROM $t_hash_db_entries WHERE entry_id=$get_current_entry_id")
						or die(mysqli_error($link)); 

			$url = "index.php?open=$open&page=entries&category_id=$get_current_entry_category_id&entry_id=$get_current_entry_id&category_id=$get_current_category_id&order_by=$order_by&order_method=$order_method&l=$l&editor_language=$editor_language&ft=success&fm=entry_deleted";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Delete entry $get_current_entry_file_name (ID $get_current_entry_id)</h1>


		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "user_deleted"){
					$fm = "$l_user_deleted";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=hash_db&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash Db</a>
			&gt;
			<a href=\"index.php?open=hash_db&amp;page=entries&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">Entries</a>
			&gt;
			<a href=\"index.php?open=hash_db&amp;page=entries&amp;category_id=$get_current_entry_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">$get_current_entry_category_title</a>
			&gt;
			<a href=\"index.php?open=hash_db&amp;page=$page&amp;entry_id=$get_current_entry_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">$get_current_entry_file_name</a>
			</p>
		<!-- //Where am I? -->

		<!-- Delete entry form -->
			<p>
			Are you sure?
			</p>

			<p>
			<a href=\"index.php?open=hash_db&amp;page=$page&amp;entry_id=$get_current_entry_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete entry form -->
		";

	} // entry found
} // action == ""
?>