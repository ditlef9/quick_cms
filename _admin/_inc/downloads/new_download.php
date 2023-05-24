<?php
/**
*
* File: _admin/_inc/downloads/new_download.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";
$t_downloads_main_categories 			= $mysqlPrefixSav . "downloads_main_categories";
$t_downloads_main_categories_translations 	= $mysqlPrefixSav . "downloads_main_categories_translations";

$t_downloads_sub_categories 			= $mysqlPrefixSav . "downloads_sub_categories";
$t_downloads_sub_categories_translations 	= $mysqlPrefixSav . "downloads_sub_categories_translations";


/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['main_category_id'])) {
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])) {
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}


/*- Functions ---------------------------------------------------- */


/*- Scriptstart ---------------------------------------------------------------------- */
if($action == ""){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		if(empty($inp_title)){
			echo"No title";die;
		}

		$inp_title_length = strlen($inp_title);

		if($inp_title_length  > 27){
			$inp_title_short = substr($inp_title, 0, 27);
			$inp_title_short = $inp_title_short . "...";
		}
		else{
			$inp_title_short = "";
		}

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);

		$inp_main_category_id = $_POST['inp_main_category_id'];
		$inp_main_category_id = output_html($inp_main_category_id);

		$inp_sub_category_id = 0;
		$inp_internal_external = "internal";
		
		// Insert
		$datetime = date("Y-m-d H:i:s");
		$date_print = date('j M Y');
		$datetime_saying = date("j M Y H:i");

		$stmt = $mysqli->prepare("INSERT INTO $t_downloads_index
			(download_id, download_title, download_title_short, download_title_length, download_language, 
			download_main_category_id, download_sub_category_id, download_internal_external, download_created_datetime, download_updated_datetime, 
			download_updated_print) 
			VALUES 
			(NULL,?,?,?,?,
			?,?,?,?,?,
			?)");
		$stmt->bind_param("ssssssssss", $inp_title, $inp_title_short, $inp_title_length, $inp_language, 
			$inp_main_category_id, $inp_sub_category_id, $inp_internal_external, $datetime, $datetime, $date_print); 
		$stmt->execute();
		if ($stmt->errno) { echo "Error MySQLi insert: " . $stmt->error; die; }
		
		// Fetch ID
		$query = "SELECT download_id FROM $t_downloads_index WHERE download_created_datetime='$datetime'";
		$result = $mysqli->query($query);
		$row = $result->fetch_row();
		list($get_current_download_id) = $row;

		// Title
		if(!(file_exists("_translations/site/$inp_language/downloads/ts_downloads.php"))){
			$input="<?php \$l_downloads = \"Downloads\"; ?>";
			$fh = fopen("_translations/site/$inp_language/downloads/ts_downloads.php", "w") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh); 
		}
		include("_translations/site/$inp_language/downloads/ts_downloads.php");

		// Search engine
		$inp_index_title = "$inp_title | $l_downloads";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "downloads/view_download.php?download_id=$get_current_download_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		mysqli_query($link, "INSERT INTO $t_search_engine_index 
		(index_id, index_title, index_url, index_short_description, index_keywords, 
		index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
		index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
		index_unique_hits) 
		VALUES 
		(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
		'downloads', 'downloads', 0, 'download_id', $get_current_download_id, 
		0, 0, '$datetime', '$datetime_saying', $inp_language_mysql,
		0)")
		or die(mysqli_error($link));

		$url = "index.php?open=$open&page=edit_download&download_id=$get_current_download_id&main_category_id=$inp_main_category_id&editor_language=$editor_language";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New download</h1>

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "category_deleted"){
				$fm = "Category deleted";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->
		

	<!-- New download form -->
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" style=\"width: 99%;\" />
		</p>

		<p>Language:<br />
		<select name=\"inp_language\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
			echo"		<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
		}
		echo"</select>

		<p>Category:<br />
		<select name=\"inp_main_category_id\">\n";
		$query = "SELECT main_category_id, main_category_title FROM $t_downloads_main_categories ORDER BY main_category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_title) = $row;
			echo"		<option value=\"$get_main_category_id\">$get_main_category_title</option>\n";
		}
		echo"</select>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
		</p>

		</form>
	<!-- New download form -->
	";

} 
?>