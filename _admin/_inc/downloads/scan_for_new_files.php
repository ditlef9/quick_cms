<?php
/**
*
* File: _admin/_inc/downloads/scan_for_new_files.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";
$t_downloads_main_categories 			= $mysqlPrefixSav . "downloads_main_categories";
$t_downloads_main_categories_translations 	= $mysqlPrefixSav . "downloads_main_categories_translations";

$t_downloads_sub_categories 			= $mysqlPrefixSav . "downloads_sub_categories";
$t_downloads_sub_categories_translations 	= $mysqlPrefixSav . "downloads_sub_categories_translations";


/*- Functions ---------------------------------------------------------------------------- */
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}
function format_size($bytes) {
	if ($bytes >= 1073741824){
		$bytes = number_format($bytes / 1073741824, 1) . ' GB';
        }
        elseif ($bytes >= 1048576) {
        	$bytes = number_format($bytes / 1048576, 1) . ' MB';
       	}
        elseif ($bytes >= 1024) {
        	$bytes = number_format($bytes / 1024, 1) . ' KB';
        }
        elseif ($bytes > 1){
        	$bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1){
        	$bytes = $bytes . ' byte';
        }
        else{
        	$bytes = '0 bytes';
       	}
	return $bytes;
}

/*- Language ---------------------------------------------------------------------------- */
if($editor_language == ""){
	$query_t = "SELECT language_active_id, language_active_iso_two FROM $t_languages_active WHERE language_active_default='1'";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_language_active_id, $get_language_active_iso_two) = $row_t;
	$editor_language = "$get_language_active_iso_two";
}


/*- Scriptstart ---------------------------------------------------------------------- */
if($action == ""){
	echo"

	<h1>Scan for new files</h1>

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
		

	";

	// Custom pages
	$dir = "../_zipped/";
	if ($handle = opendir($dir)) {
		while (false !== ($main_dir = readdir($handle))) {
			if ($main_dir === '.') continue;
			if ($main_dir === '..') continue;
			if ($main_dir === '_icons') continue;

			// Find main category ID
			$main_category_title_clean_mysql = quote_smart($link, $main_dir);
			$query = "SELECT main_category_id, main_category_title FROM $t_downloads_main_categories WHERE main_category_title_clean=$main_category_title_clean_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_title) = $row;


			echo"
			<h2>[$get_current_main_category_id] $main_dir</h2>";

			

			// Open main dir
			if ($handle_main = opendir("$dir$main_dir/")) {
				while (false !== ($file_in_main_dir_or_sub_dir = readdir($handle_main))) {
					if ($file_in_main_dir_or_sub_dir === '.') continue;
					if ($file_in_main_dir_or_sub_dir === '..') continue;
					if ($file_in_main_dir_or_sub_dir === '_gfx') continue;

					if(is_dir("$dir$main_dir/$file_in_main_dir_or_sub_dir")){
						$sub_category_title_clean_mysql = quote_smart($link, $file_in_main_dir_or_sub_dir);
						$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_parent_id='$get_current_main_category_id' AND sub_category_title_clean=$sub_category_title_clean_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_sub_category_id, $get_sub_category_title) = $row;
						if($get_current_sub_category_id == ""){
							echo"(Sub not found: $query)";
							// Create that sub dir
							$datetime = date("Y-m-d H:i:s");
							mysqli_query($link, "INSERT INTO $t_downloads_sub_categories
							(sub_category_id, sub_category_parent_id, sub_category_title, sub_category_title_clean, sub_category_created) 
							VALUES 
							(NULL, $get_current_main_category_id, $sub_category_title_clean_mysql, $sub_category_title_clean_mysql, '$datetime')")
							or die(mysqli_error($link));

							// Fetch the ID
							$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_parent_id='$get_current_main_category_id' AND sub_category_title_clean=$sub_category_title_clean_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_sub_category_id, $get_sub_category_title) = $row;
						}
						echo"
						<p><b>[$get_current_sub_category_id] $file_in_main_dir_or_sub_dir</b></p>";

						if ($handle_sub = opendir("$dir$main_dir/$file_in_main_dir_or_sub_dir/")) {
							while (false !== ($file_in_sub = readdir($handle_sub))) {
								if ($file_in_sub === '.') continue;
								if ($file_in_sub === '..') continue;
								if ($file_in_sub === '_gfx') continue;

								// Scan file in sub dir
								$inp_type = getExtension($file_in_sub);
								$inp_type_mysql = quote_smart($link, $inp_type);

								$inp_title = str_replace(".$inp_type", "", $file_in_sub);
								$inp_title = str_replace("_", " ", $inp_title);
								$inp_title = ucfirst($inp_title);
								$inp_title_mysql = quote_smart($link, $inp_title);

								$inp_language = "$editor_language";
								$inp_language_mysql = quote_smart($link, $inp_language);

								$inp_dir = "_zipped/" . $main_dir . "/" . $file_in_main_dir_or_sub_dir;
								$inp_dir_mysql = quote_smart($link, $inp_dir);

								$inp_file = str_replace(".$inp_type", "", $file_in_sub);
								$inp_file_mysql = quote_smart($link, $inp_file);
	
								$inp_file_size = format_size (filesize("$dir$main_dir/$file_in_main_dir_or_sub_dir/$file_in_sub"));
								$inp_file_size_mysql = quote_smart($link, $inp_file_size);

								// File date
								$inp_file_date = date('Y-m-d',filemtime("$dir$main_dir/$file_in_main_dir_or_sub_dir/$file_in_sub"));
								$inp_file_date_mysql = quote_smart($link, $inp_file_date);

								$inp_file_date_print = date('j M Y',filemtime("$dir$main_dir/$file_in_main_dir_or_sub_dir/$file_in_sub"));
								$inp_file_date_print_mysql = quote_smart($link, $inp_file_date_print);

								// Datetime
								$datetime = date("Y-m-d H:i:s");
								$date_print = date('j M Y');

								// Search for it
								$query = "SELECT download_id FROM $t_downloads_index WHERE download_dir=$inp_dir_mysql AND download_file=$inp_file_mysql AND download_type=$inp_type_mysql";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_download_id) = $row;

								if($get_download_id == ""){
									echo"
									<span style=\"color: green\">&nbsp; New! $file_in_sub</span><br />";

									// Insert
									mysqli_query($link, "INSERT INTO $t_downloads_index
									(download_id, download_title, download_language, download_main_category_id, download_sub_category_id, download_dir, download_file, download_type, download_file_size, 
									download_file_date, download_file_date_print, download_created_datetime, download_updated_datetime, download_updated_print) 
									VALUES 
									(NULL, $inp_title_mysql, $inp_language_mysql, '$get_current_main_category_id', '$get_current_sub_category_id', $inp_dir_mysql, $inp_file_mysql, $inp_type_mysql, $inp_file_size_mysql, 
									$inp_file_date_mysql, $inp_file_date_print_mysql, '$datetime', '$datetime', '$date_print')")
									or die(mysqli_error($link));
								}
								else{
									echo"
									<span>&nbsp; [$get_download_id] $file_in_sub</span><br />";
								}
							} // while sub dir
						} // handle sub dir
					} // open sub dir
					else{
						// Scan file in main dir
						$inp_type = getExtension($file_in_main_dir_or_sub_dir);
						$inp_type_mysql = quote_smart($link, $inp_type);

						$inp_title = str_replace(".$inp_type", "", $file_in_main_dir_or_sub_dir);
						$inp_title = str_replace("_", " ", $inp_title);
						$inp_title = ucfirst($inp_title);
						$inp_title_mysql = quote_smart($link, $inp_title);

						$inp_language = "$editor_language";
						$inp_language_mysql = quote_smart($link, $inp_language);

						$inp_dir = "_zipped/" . $main_dir;
						$inp_dir_mysql = quote_smart($link, $inp_dir);

						$inp_file = str_replace(".$inp_type", "", $file_in_main_dir_or_sub_dir);
						$inp_file_mysql = quote_smart($link, $inp_file);

						$inp_file_size = format_size (filesize("$dir$main_dir/$file_in_main_dir_or_sub_dir"));
						$inp_file_size_mysql = quote_smart($link, $inp_file_size);

						// File date
						$inp_file_date = date('Y-m-d',filemtime("$dir$main_dir/$file_in_main_dir_or_sub_dir"));
						$inp_file_date_mysql = quote_smart($link, $inp_file_date);

						$inp_file_date_print = date('j M Y',filemtime("$dir$main_dir/$file_in_main_dir_or_sub_dir"));
						$inp_file_date_print_mysql = quote_smart($link, $inp_file_date_print);

						// Datetime
						$datetime = date("Y-m-d H:i:s");
						$date_print = date('j M Y');

						// Search for it
						$query = "SELECT download_id FROM $t_downloads_index WHERE download_dir=$inp_dir_mysql AND download_file=$inp_file_mysql AND download_type=$inp_type_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_download_id) = $row;

						if($get_download_id == ""){
							echo"
							<span style=\"color: green\">&nbsp; New! $file_in_main_dir_or_sub_dir<br /></span>";

							// Insert
							mysqli_query($link, "INSERT INTO $t_downloads_index
							(download_id, download_title, download_language, download_main_category_id, download_sub_category_id, download_dir, download_file, download_type, download_file_size, 
							download_file_date, download_file_date_print, download_created_datetime, download_updated_datetime, download_updated_print) 
							VALUES 
							(NULL, $inp_title_mysql, $inp_language_mysql, '$get_current_main_category_id', '0', $inp_dir_mysql, $inp_file_mysql, $inp_type_mysql, $inp_file_size_mysql, 
							$inp_file_date_mysql, $inp_file_date_print_mysql, '$datetime', '$datetime', '$date_print')")
							or die(mysqli_error($link));
						}
						else{
							echo"
							<span>&nbsp; [$get_download_id] $file_in_main_dir_or_sub_dir<br /></span>";
						}
					}
				} // while main
			} // handle main

		} // while zipped
		closedir($handle);
	} // handle


} // action == ""
?>