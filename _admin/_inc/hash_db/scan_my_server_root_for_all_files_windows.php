<?php
/**
*
* File: _admin/_inc/hash_db/scan_my_server_root_for_all_files_windows.php
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


if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	if(!(is_numeric($category_id))){
		echo"category_id not numeric"; die;
	}
}
else{
	$category_id = "";
}

/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";


$t_hash_db_scan_my_server_windows	= $mysqlPrefixSav . "hash_db_scan_my_server_windows";



/*- Functions -------------------------------------------------------------------------- */
function format_size_units($bytes) {
	if ($bytes >= 1073741824)  {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)  {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)  {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        }
        else {
            $bytes = '0 bytes';
        }

        return $bytes;
}
include("_functions/get_extension.php");

// Dates
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

// Windows category
$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color FROM $t_hash_db_categories WHERE category_title='Windows'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color) = $row;
$inp_category_title_mysql = quote_smart($link, $get_current_category_title);


if($action == ""){
	echo"
	<h1>Scan my server root for all files (Windows)</h1>
	";

	// Scan table
	$query = "SELECT * FROM $t_hash_db_scan_my_server_windows LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		$query = "TRUNCATE $t_hash_db_scan_my_server_windows";
		$result = mysqli_query($link, $query);
	}
	else{
		echo"<pre>CREATE TABLE $t_hash_db_scan_my_server_windows(
		  id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(id), 
		  file_path TEXT, 
		  relative_path TEXT, 
		  size VARCHAR(200))</pre>";

		mysqli_query($link, "CREATE TABLE $t_hash_db_scan_my_server_windows(
		  id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(id), 
		  file_path TEXT, 
		  relative_path TEXT, 
		  size VARCHAR(200))")
	  	 or die(mysqli_error());	
	}




	echo"
	<h2>Make list of dir and files in root</h2>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>File path</span>
		   </th>
		   <th scope=\"col\">
			<span>Relative path</span>
		   </th>
		   <th scope=\"col\">
			<span>Is dir?</span>
		  </th>
		   <th scope=\"col\">
			<span>Bytes</span>
		   </th>
		   <th scope=\"col\">
			<span>Size human</span>
		   </th>
		   <th scope=\"col\">
			<span>Mime</span>
		  </th>
		   <th scope=\"col\">
			<span>Created</span>
		  </th>
		 </tr>
		</thead>
		<tbody>
	";

	// Step 1: Make list of all directories in root
	// Step 2: Loop trough one and one directory, and insert them
	$filenames = "";
	$dir = "C:";
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file === '.') continue;
			if ($file === '..') continue;
			
			// File name
			$inp_file_name = output_html("$file");
			$inp_file_name_mysql = quote_smart($link, $inp_file_name);

			// File path
			$inp_file_path_mysql = quote_smart($link, "$dir/$file");

			// Relative path
			$inp_real_path = realpath("$dir/$file");
			$inp_real_path_mysql = quote_smart($link, $inp_real_path);

			// Size
			$inp_file_size_bytes = filesize("$dir/$file");
			$inp_file_size_bytes_mysql = quote_smart($link, $inp_file_size_bytes);

			$inp_file_size_human = format_size_units($inp_file_size_bytes);
			$inp_file_size_human_mysql = quote_smart($link, $inp_file_size_human);

			// is dir
			$inp_is_dir = 0;
			if(is_dir("$dir/$file")){
				$inp_is_dir = 1;
			}
			$inp_is_dir_mysql = quote_smart($link, $inp_is_dir);


	
			// File extension
			$inp_file_extension = get_extension($file);
			$inp_file_extension_mysql = quote_smart($link, $inp_file_extension);
				
			// File mime
			$inp_file_mime = mime_content_type($inp_real_path);
			$inp_file_mime_mysql = quote_smart($link, $inp_file_mime);


			// File created
			$inp_file_created_datetime = date ("Y-m-d H:i:s", filemtime("$dir/$file"));
			$inp_file_created_datetime = output_html($inp_file_created_datetime);
			$inp_file_created_datetime_mysql = quote_smart($link, $inp_file_created_datetime);

			$inp_file_created_datetime_saying = date ("j M Y H:i", filemtime("$dir/$file"));
			$inp_file_created_datetime_saying = output_html($inp_file_created_datetime_saying);
			$inp_file_created_datetime_saying_mysql = quote_smart($link, $inp_file_created_datetime_saying);

			// Last changed
			$inp_file_last_changed_datetime = date ("Y-m-d H:i:s", filemtime("$dir/$file"));
			$inp_file_last_changed_datetime = output_html($inp_file_last_changed_datetime);
			$inp_file_last_changed_datetime_mysql = quote_smart($link, $inp_file_last_changed_datetime);

			$inp_file_last_changed_datetime_saying = date ("j M Y H:i", filemtime("$dir/$file"));
			$inp_file_last_changed_datetime_saying = output_html($inp_file_last_changed_datetime_saying);
			$inp_file_last_changed_datetime_saying_mysql = quote_smart($link, $inp_file_last_changed_datetime_saying);

			// Md5
			$inp_file_name_md5 = md5("$file");
			$inp_file_name_md5_mysql = quote_smart($link, $inp_file_name_md5);

			// Sha1
			$inp_file_name_sha1  = sha1("$file");
			$inp_file_name_sha1_mysql = quote_smart($link, $inp_file_name_sha1);

			$inp_file_content_md5 = "";
			if($inp_is_dir == "0"){
				$inp_file_content_md5 = md5_file("$dir/$file");
				$inp_file_content_md5 = output_html($inp_file_content_md5);
			}
			$inp_file_content_md5_mysql = quote_smart($link, $inp_file_content_md5);

			$inp_file_content_sha1 = "";
			if($inp_is_dir == "0"){
				$inp_file_content_sha1 = sha1_file("$dir/$file");
				$inp_file_content_sha1 = output_html($inp_file_content_sha1);
			}
			$inp_file_content_sha1_mysql = quote_smart($link, $inp_file_content_sha1);


			// Insert
			if($inp_is_dir == "1"){
				mysqli_query($link, "INSERT INTO $t_hash_db_scan_my_server_windows
				(id, file_path, relative_path, size) 
				VALUES 
				(NULL, $inp_file_path_mysql, $inp_real_path_mysql, $inp_file_size_bytes_mysql)")
				or die(mysqli_error($link));
			}
			elseif($inp_is_dir == "0"){

				// Check if exists for 1) file content md5

				$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries WHERE entry_file_content_md5=$inp_file_content_md5_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_entry_id, $get_current_entry_category_id, $get_current_entry_category_title, $get_current_entry_file_path, $get_current_entry_file_name, $get_current_entry_file_extension, $get_current_entry_file_mime, $get_current_entry_file_size_bytes, $get_current_entry_file_size_human, $get_current_entry_file_created_datetime, $get_current_entry_file_created_saying, $get_current_entry_file_last_changed_datetime, $get_current_entry_file_last_changed_saying, $get_current_entry_file_name_md5, $get_current_entry_file_name_sha1, $get_current_entry_file_content_md5, $get_current_entry_file_content_sha1, $get_current_entry_created_datetime, $get_current_entry_created_saying, $get_current_entry_created_by_user_id, $get_current_entry_created_by_user_name, $get_current_entry_updated_datetime, $get_current_entry_updated_saying, $get_current_entry_updated_by_user_id, $get_current_entry_updated_by_user_name, $get_current_entry_hits) = $row;

				if($get_current_entry_id == ""){
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
			}


			echo"
			 <tr>
			  <td style=\"padding-right: 5px;\">
				<span>$dir/$file</span>
			  </td>
			  <td style=\"padding-right: 5px;\">
				<span>$inp_real_path</span>
			  </td>
			  <td style=\"padding-right: 5px;\">
				<span>$inp_is_dir</span>
			  </td>
			  <td style=\"padding-right: 5px;\">
				<span>$inp_file_size_bytes</span>
			  </td>
			  <td style=\"padding-right: 5px;\">
				<span>$inp_file_size_human</span>
			  </td>
			  <td style=\"padding-right: 5px;\">
				<span>$inp_file_mime</span>
			  </td>
			  <td style=\"padding-right: 5px;\">
				<span>$inp_file_created_datetime</span>
			  </td>
			 </tr>
			";
		}
		closedir($handle);
	}
	echo"
		 </tbody>
		</table>


	<meta http-equiv=refresh content=\"2; URL=index.php?open=$open&amp;page=$page&amp;action=go_trough_next_directory&amp;datetime=$datetime\">
	<!-- Jquery go to URL after x seconds -->
		<script>
		\$(document).ready(function(){
			window.setTimeout(function(){
        			// Move to a new location or you can do something else
				window.location.href = \"index.php?open=$open&amp;page=$page&amp;action=go_trough_next_directory&amp;datetime=$datetime\";
			}, 10000);
		});
   		</script>
	<!-- //Jquery go to URL after x seconds -->
	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=go_trough_next_directory&amp;datetime=$datetime\" class=\"btn\">Continue</a>
	</p>
	";
	
} // action == ""
elseif($action == "go_trough_next_directory"){
	// Select folder
	$query = "SELECT id, file_path, relative_path, size FROM $t_hash_db_scan_my_server_windows LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_id, $get_current_file_path, $get_current_relative_path, $get_current_size) = $row;
	if($get_current_id != ""){
		// Delete this directory
		$query = "DELETE FROM $t_hash_db_scan_my_server_windows WHERE id=$get_current_id";
		$result = mysqli_query($link, $query);
		


		echo"
		<h1>$get_current_file_path</h1>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>File path</span>
		   </th>
		   <th scope=\"col\">
			<span>Relative path</span>
		   </th>
		   <th scope=\"col\">
			<span>Is dir?</span>
		  </th>
		   <th scope=\"col\">
			<span>Bytes</span>
		   </th>
		   <th scope=\"col\">
			<span>Size human</span>
		   </th>
		   <th scope=\"col\">
			<span>Mime</span>
		  </th>
		   <th scope=\"col\">
			<span>Created</span>
		  </th>
		 </tr>
		</thead>
		<tbody>
		";

		// Step 1: Make list of all directories in root
		// Step 2: Loop trough one and one directory, and insert them
		$filenames = "";
		$dir = "$get_current_file_path";
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file === '.') continue;
				if ($file === '..') continue;
			
				// File name
				$inp_file_name = output_html("$file");
				$inp_file_name_mysql = quote_smart($link, $inp_file_name);

				// File path
				$inp_file_path_mysql = quote_smart($link, "$dir/$file");

				// Relative path
				$inp_real_path = realpath("$dir/$file");
				$inp_real_path_mysql = quote_smart($link, $inp_real_path);

				// Size
				$inp_file_size_bytes = filesize("$dir/$file");
				$inp_file_size_bytes_mysql = quote_smart($link, $inp_file_size_bytes);

				$inp_file_size_human = format_size_units($inp_file_size_bytes);
				$inp_file_size_human_mysql = quote_smart($link, $inp_file_size_human);

				// is dir
				$inp_is_dir = 0;
				if(is_dir("$dir/$file")){
					$inp_is_dir = 1;
				}
				$inp_is_dir_mysql = quote_smart($link, $inp_is_dir);


	
				// File extension
				$inp_file_extension = get_extension($file);
				$inp_file_extension_mysql = quote_smart($link, $inp_file_extension);
				
				// File mime
				$inp_file_mime = mime_content_type($inp_real_path);
				$inp_file_mime_mysql = quote_smart($link, $inp_file_mime);

	
				// File created
				$inp_file_created_datetime = date ("Y-m-d H:i:s", filemtime("$dir/$file"));
				$inp_file_created_datetime = output_html($inp_file_created_datetime);
				$inp_file_created_datetime_mysql = quote_smart($link, $inp_file_created_datetime);

				$inp_file_created_datetime_saying = date ("j M Y H:i", filemtime("$dir/$file"));
				$inp_file_created_datetime_saying = output_html($inp_file_created_datetime_saying);
				$inp_file_created_datetime_saying_mysql = quote_smart($link, $inp_file_created_datetime_saying);

				// Last changed
				$inp_file_last_changed_datetime = date ("Y-m-d H:i:s", filemtime("$dir/$file"));
				$inp_file_last_changed_datetime = output_html($inp_file_last_changed_datetime);
				$inp_file_last_changed_datetime_mysql = quote_smart($link, $inp_file_last_changed_datetime);

				$inp_file_last_changed_datetime_saying = date ("j M Y H:i", filemtime("$dir/$file"));
				$inp_file_last_changed_datetime_saying = output_html($inp_file_last_changed_datetime_saying);
				$inp_file_last_changed_datetime_saying_mysql = quote_smart($link, $inp_file_last_changed_datetime_saying);

				// Md5
				$inp_file_name_md5 = md5("$file");
				$inp_file_name_md5_mysql = quote_smart($link, $inp_file_name_md5);

				// Sha1
				$inp_file_name_sha1  = sha1("$file");
				$inp_file_name_sha1_mysql = quote_smart($link, $inp_file_name_sha1);

				$inp_file_content_md5 = "";
				if($inp_is_dir == "0"){
					$inp_file_content_md5 = md5_file("$dir/$file");
					$inp_file_content_md5 = output_html($inp_file_content_md5);
				}
				$inp_file_content_md5_mysql = quote_smart($link, $inp_file_content_md5);

				$inp_file_content_sha1 = "";
				if($inp_is_dir == "0"){
					$inp_file_content_sha1 = sha1_file("$dir/$file");
					$inp_file_content_sha1 = output_html($inp_file_content_sha1);
				}
				$inp_file_content_sha1_mysql = quote_smart($link, $inp_file_content_sha1);


				// Insert
				if($inp_is_dir == "1"){
					mysqli_query($link, "INSERT INTO $t_hash_db_scan_my_server_windows
					(id, file_path, relative_path, size) 
					VALUES 
					(NULL, $inp_file_path_mysql, $inp_real_path_mysql, $inp_file_size_bytes_mysql)")
					or die(mysqli_error($link));
				}
				elseif($inp_is_dir == "0"){

					// Check if exists for 1) file content md5

					$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries WHERE entry_file_content_md5=$inp_file_content_md5_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_entry_id, $get_current_entry_category_id, $get_current_entry_category_title, $get_current_entry_file_path, $get_current_entry_file_name, $get_current_entry_file_extension, $get_current_entry_file_mime, $get_current_entry_file_size_bytes, $get_current_entry_file_size_human, $get_current_entry_file_created_datetime, $get_current_entry_file_created_saying, $get_current_entry_file_last_changed_datetime, $get_current_entry_file_last_changed_saying, $get_current_entry_file_name_md5, $get_current_entry_file_name_sha1, $get_current_entry_file_content_md5, $get_current_entry_file_content_sha1, $get_current_entry_created_datetime, $get_current_entry_created_saying, $get_current_entry_created_by_user_id, $get_current_entry_created_by_user_name, $get_current_entry_updated_datetime, $get_current_entry_updated_saying, $get_current_entry_updated_by_user_id, $get_current_entry_updated_by_user_name, $get_current_entry_hits) = $row;

					if($get_current_entry_id == ""){
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
				}


				echo"
				 <tr>
				  <td style=\"padding-right: 5px;\">
					<span>$dir/$file</span>
				  </td>
				  <td style=\"padding-right: 5px;\">
					<span>$inp_real_path</span>
				  </td>
				  <td style=\"padding-right: 5px;\">
					<span>$inp_is_dir</span>
				  </td>
				  <td style=\"padding-right: 5px;\">
					<span>$inp_file_size_bytes</span>
				  </td>
				  <td style=\"padding-right: 5px;\">
					<span>$inp_file_size_human</span>
				  </td>
				  <td style=\"padding-right: 5px;\">
					<span>$inp_file_mime</span>
				  </td>
				  <td style=\"padding-right: 5px;\">
					<span>$inp_file_created_datetime</span>
				  </td>
				 </tr>
				";
			}
			closedir($handle);
		}
		echo"
		 </tbody>
		</table>


		<meta http-equiv=refresh content=\""; $rand = rand(0,2); echo"$rand; URL=index.php?open=$open&amp;page=$page&amp;action=go_trough_next_directory&amp;datetime=$datetime\">
		<!-- Jquery go to URL after x seconds -->
			<script>
			\$(document).ready(function(){
				window.setTimeout(function(){
        				// Move to a new location or you can do something else
					window.location.href = \"index.php?open=$open&amp;page=$page&amp;action=go_trough_next_directory&amp;datetime=$datetime\";
				}, 10000);
			});
   			</script>
		<!-- //Jquery go to URL after x seconds -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=go_trough_next_directory&amp;datetime=$datetime\" class=\"btn\">Continue</a>
		</p>
		";
	}
	else{
		echo"Finished";
	}
} // action == "go_trough_next_directory"
?>