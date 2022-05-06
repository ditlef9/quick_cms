<?php
/**
*
* File: _admin/_inc/hash_db/entry_new.php
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


if($action == ""){
	// Find category
	$category_id_mysql = quote_smart($link, $category_id);
	$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color FROM $t_hash_db_categories WHERE category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color) = $row;




	if($process == "1"){

		$inp_category_id = $_POST['inp_category_id'];
		$inp_category_id = output_html($inp_category_id);
		$inp_category_id_mysql = quote_smart($link, $inp_category_id);

		$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color FROM $t_hash_db_categories WHERE category_id=$inp_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color) = $row;
		$inp_category_title_mysql = quote_smart($link, $get_current_category_title);


		$inp_file_path = $_POST['inp_file_path'];
		$inp_file_path = output_html($inp_file_path);
		$inp_file_path_mysql = quote_smart($link, $inp_file_path);

		$inp_file_name = $_POST['inp_file_name'];
		$inp_file_name = output_html($inp_file_name);
		$inp_file_name_mysql = quote_smart($link, $inp_file_name);

		$inp_file_extension = $_POST['inp_file_extension'];
		$inp_file_extension = output_html($inp_file_extension);
		$inp_file_extension_mysql = quote_smart($link, $inp_file_extension);

		$inp_file_mime = $_POST['inp_file_mime'];
		$inp_file_mime = output_html($inp_file_mime);
		$inp_file_mime_mysql = quote_smart($link, $inp_file_mime);

		$inp_file_size_bytes = $_POST['inp_file_size_bytes'];
		$inp_file_size_bytes = output_html($inp_file_size_bytes);
		$inp_file_size_bytes_mysql = quote_smart($link, $inp_file_size_bytes);

		$inp_file_size_human = format_size_units($inp_file_size_bytes);
		$inp_file_size_human_mysql = quote_smart($link, $inp_file_size_human);

		// File created
		$inp_file_created_datetime = $_POST['inp_file_created_datetime'];
		$inp_file_created_datetime = output_html($inp_file_created_datetime);
		$inp_file_created_datetime_mysql = quote_smart($link, $inp_file_created_datetime);
		
		$file_created_year = substr($inp_file_created_datetime, 0, 4);
		$file_created_month = substr($inp_file_created_datetime, 5, 2);
		$file_created_day = substr($inp_file_created_datetime, 8, 2);
		$file_created_hour_minute_second  = substr($inp_file_created_datetime, 11);

		if($file_created_month == "01"){
			$file_created_month_saying = "Jan";
		}
		elseif($file_created_month == "02"){
			$file_created_month_saying = "Feb";
		}
		elseif($file_created_month == "03"){
			$file_created_month_saying = "Mar";
		}
		elseif($file_created_month == "04"){
			$file_created_month_saying = "Apr";
		}
		elseif($file_created_month == "05"){
			$file_created_month_saying = "May";
		}
		elseif($file_created_month == "06"){
			$file_created_month_saying = "Jun";
		}
		elseif($file_created_month == "07"){
			$file_created_month_saying = "Jul";
		}
		elseif($file_created_month == "08"){
			$file_created_month_saying = "Aug";
		}
		elseif($file_created_month == "09"){
			$file_created_month_saying = "Sep";
		}
		elseif($file_created_month == "10"){
			$file_created_month_saying = "Oct";
		}
		elseif($file_created_month == "11"){
			$file_created_month_saying = "Nov";
		}
		elseif($file_created_month == "12"){
			$file_created_month_saying = "Dec";
		}

		$inp_file_created_datetime_saying = "$file_created_day $file_created_month_saying $file_created_year $file_created_hour_minute_second";
		$inp_file_created_datetime_saying_mysql = quote_smart($link, $inp_file_created_datetime_saying);
		
		// Last changed
		$inp_file_last_changed_datetime = $_POST['inp_file_last_changed_datetime'];
		$inp_file_last_changed_datetime = output_html($inp_file_last_changed_datetime);
		$inp_file_last_changed_datetime_mysql = quote_smart($link, $inp_file_last_changed_datetime);
		$file_last_changed_year = substr($inp_file_last_changed_datetime, 0, 4);
		$file_last_changed_month = substr($inp_file_last_changed_datetime, 5, 2);
		$file_last_changed_day = substr($inp_file_last_changed_datetime, 8, 2);
		$file_last_changed_hour_minute_second  = substr($inp_file_last_changed_datetime, 11);

		if($file_last_changed_month == "01"){
			$file_last_changed_month_saying = "Jan";
		}
		elseif($file_last_changed_month == "02"){
			$file_last_changed_month_saying = "Feb";
		}
		elseif($file_last_changed_month == "03"){
			$file_last_changed_month_saying = "Mar";
		}
		elseif($file_last_changed_month == "04"){
			$file_last_changed_month_saying = "Apr";
		}
		elseif($file_last_changed_month == "05"){
			$file_last_changed_month_saying = "May";
		}
		elseif($file_last_changed_month == "06"){
			$file_last_changed_month_saying = "Jun";
		}
		elseif($file_last_changed_month == "07"){
			$file_last_changed_month_saying = "Jul";
		}
		elseif($file_last_changed_month == "08"){
			$file_last_changed_month_saying = "Aug";
		}
		elseif($file_last_changed_month == "09"){
			$file_last_changed_month_saying = "Sep";
		}
		elseif($file_last_changed_month == "10"){
			$file_last_changed_month_saying = "Oct";
		}
		elseif($file_last_changed_month == "11"){
			$file_last_changed_month_saying = "Nov";
		}
		elseif($file_last_changed_month == "12"){
			$file_last_changed_month_saying = "Dec";
		}

		$inp_file_last_changed_datetime_saying = "$file_last_changed_day $file_last_changed_month_saying $file_last_changed_year $file_last_changed_hour_minute_second";
		$inp_file_last_changed_datetime_saying_mysql = quote_smart($link, $inp_file_last_changed_datetime_saying);

		$inp_file_name_raw = $_POST['inp_file_name'];
		$inp_file_name_md5 = md5($inp_file_name_raw);
		$inp_file_name_md5_mysql = quote_smart($link, $inp_file_name_md5);

		$inp_file_name_sha1  = sha1($inp_file_name_raw);
		$inp_file_name_sha1_mysql = quote_smart($link, $inp_file_name_sha1);

		$inp_file_content_md5 = $_POST['inp_file_content_md5'];
		$inp_file_content_md5 = output_html($inp_file_content_md5);
		$inp_file_content_md5_mysql = quote_smart($link, $inp_file_content_md5);

		$inp_file_content_sha1 = $_POST['inp_file_content_sha1'];
		$inp_file_content_sha1 = output_html($inp_file_content_sha1);
		$inp_file_content_sha1_mysql = quote_smart($link, $inp_file_content_sha1);

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

		$url = "index.php?open=$open&page=$page&category_id=$get_current_category_id&order_by=$order_by&order_method=$order_method&l=$l&editor_language=$editor_language&ft=success&fm=entry_created";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New entry</h1>


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
		<a href=\"index.php?open=hash_db&amp;page=entries&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">Entries</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=entries&amp;category_id=$get_current_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">$get_current_category_title</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=$page&amp;category_id=$get_current_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">New entry</a>
		</p>
	<!-- //Where am I? -->

	<!-- New entry form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_file_path\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<p>Category:<br />
		<select name=\"inp_category_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">\n";
		$query = "SELECT category_id, category_title FROM $t_hash_db_categories ORDER BY category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_title) = $row;

			echo"				<option value=\"$get_category_id\""; if($get_category_id == "$get_current_category_id"){ echo" selected=\"selected\""; } echo">$get_category_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>File path:<br />
		<input type=\"text\" name=\"inp_file_path\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File name:<br />
		<input type=\"text\" name=\"inp_file_name\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File extension:<br />
		<input type=\"text\" name=\"inp_file_extension\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File mime:<br />
		<input type=\"text\" name=\"inp_file_mime\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File size in bytes:<br />
		<input type=\"text\" name=\"inp_file_size_bytes\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File created (YYYY-mm-dd HH:ii:ss):<br />";
		$datetime = date("Y-m-d H:i:s");
		echo"
		<input type=\"text\" name=\"inp_file_created_datetime\" value=\"$datetime\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File last changed (YYYY-mm-dd HH:ii:ss):<br />
		<input type=\"text\" name=\"inp_file_last_changed_datetime\" value=\"$datetime\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File content md5:<br />
		<input type=\"text\" name=\"inp_file_content_md5\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>File content sha1:<br />
		<input type=\"text\" name=\"inp_file_content_sha1\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>
			
		<p>
		<input type=\"submit\" value=\"Create entry\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>
		</form>
	<!-- //New entry form -->
	";

} // action == ""
?>