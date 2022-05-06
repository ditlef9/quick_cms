<?php
/**
*
* File: _admin/_inc/hash_db/sites.php
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
if($order_by == ""){
	$order_by = "country_name";
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
	if($category_id != ""){
		if(!(is_numeric($category_id))){
			echo"category_id not numeric!"; die;
		}
	}
}
else{
	$category_id = "";
}


/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";

if($action == ""){
	echo"
	<h1>Entries</h1>


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
		</p>
	<!-- //Where am I? -->

	<!-- Entries -->
		<!-- Categories select -->
			<div class=\"tabs\">
				<ul>\n";

				$query_t = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color FROM $t_hash_db_categories ORDER BY category_title ASC";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
				list($get_category_id, $get_category_title, $get_category_bg_color, $get_category_border_color, $get_category_text_color) = $row_t;

					// If no category selected, then select the first one
					if($category_id == ""){
						$category_id = "$get_category_id";
					}

					// Echo
					//  style=\"background: $get_category_bg_color;border-color: $get_category_border_color;color: $get_category_text_color;\"
					echo"					";
					echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;category_id=$get_category_id&amp;l=$l\""; if($get_category_id == "$category_id"){ echo" class=\"active\""; } echo">$get_category_title</a>\n";
				}
				echo"
				</ul>
			</div>
			<div class=\"clear\" style=\"height:10px;\"></div>
		<!-- //Categories select -->

		<p>
		<a href=\"index.php?open=$open&amp;page=entry_new&amp;category_id=$category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn\">New entry</a>
		<a href=\"index.php?open=$open&amp;page=upload_hash_db&amp;category_id=$category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Upload hash db</a>
		<a href=\"index.php?open=$open&amp;page=download_hash_db&amp;category_id=$category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Download hash db</a>
		<a href=\"index.php?open=$open&amp;page=scan_my_server_root_for_all_files_windows&amp;category_id=$category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Scan my server root for all files</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";
			if($order_by == "entry_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=entry_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>ID</b></a>";
			if($order_by == "entry_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "entry_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "entry_file_path" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=entry_file_path&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>File path</b></a>";
			if($order_by == "entry_file_path" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "entry_file_path" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "entry_file_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=entry_file_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>File name</b></a>";
			if($order_by == "entry_file_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "entry_file_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "entry_file_extension" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=entry_file_extension&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Extension</b></a>";
			if($order_by == "entry_file_extension" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "entry_file_extension" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "entry_file_mime" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=entry_file_mime&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Mime</b></a>";
			if($order_by == "entry_file_mime" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "entry_file_mime" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "entry_file_content_md5" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=entry_file_content_md5&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Content md5</b></a>";
			if($order_by == "entry_file_content_md5" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "entry_file_content_md5" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";
	$category_id_mysql = quote_smart($link, $category_id);
	$query = "SELECT entry_id, entry_category_id, entry_category_title, entry_file_path, entry_file_name, entry_file_extension, entry_file_mime, entry_file_size_bytes, entry_file_size_human, entry_file_created_datetime, entry_file_created_saying, entry_file_last_changed_datetime, entry_file_last_changed_saying, entry_file_name_md5, entry_file_name_sha1, entry_file_content_md5, entry_file_content_sha1, entry_created_datetime, entry_created_saying, entry_created_by_user_id, entry_created_by_user_name, entry_updated_datetime, entry_updated_saying, entry_updated_by_user_id, entry_updated_by_user_name, entry_hits FROM $t_hash_db_entries";
	$query = $query . " WHERE entry_category_id=$category_id_mysql";
	if($order_by == "entry_id" OR $order_by == "entry_file_path" OR $order_by == "entry_file_name" OR $order_by == "entry_file_extension" OR $order_by == "entry_file_mime" OR $order_by == "entry_file_content_md5"){
		if($order_method == "asc"){
			$query = $query . " ORDER BY $order_by ASC";
		}
		else{
			$query = $query . " ORDER BY $order_by DESC";
		}
	}

	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_entry_id, $get_entry_category_id, $get_entry_category_title, $get_entry_file_path, $get_entry_file_name, $get_entry_file_extension, $get_entry_file_mime, $get_entry_file_size_bytes, $get_entry_file_size_human, $get_entry_file_created_datetime, $get_entry_file_created_saying, $get_entry_file_last_changed_datetime, $get_entry_file_last_changed_saying, $get_entry_file_name_md5, $get_entry_file_name_sha1, $get_entry_file_content_md5, $get_entry_file_content_sha1, $get_entry_created_datetime, $get_entry_created_saying, $get_entry_created_by_user_id, $get_entry_created_by_user_name, $get_entry_updated_datetime, $get_entry_updated_saying, $get_entry_updated_by_user_id, $get_entry_updated_by_user_name, $get_entry_hits) = $row;

		// Style
		if(isset($style) && $style == ""){
			$style = "odd";
		}
		else{
			$style = "";
		}
	
		echo"
		 <tr>
		  <td class=\"$style\">
			<span>$get_entry_id</span>
		  </td>
		  <td class=\"$style\">
			<span>
			$get_entry_file_path
			</span>
		  </td>
		  <td class=\"$style\">
			<span>
			$get_entry_file_name
			</span>
		  </td>
		  <td class=\"$style\">
			<span>
			$get_entry_file_extension
			</span>
		  </td>
		  <td class=\"$style\">
			<span>
			$get_entry_file_mime
			</span>
		  </td>
		  <td class=\"$style\">
			<span>
			$get_entry_file_content_md5
			</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"index.php?open=$open&amp;page=entry_edit&amp;entry_id=$get_entry_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
			|
			<a href=\"index.php?open=$open&amp;page=entry_delete&amp;entry_id=$get_entry_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
			</span>
		  </td>
		 </tr>
		";

	}
	echo"
	
		 </tbody>
		</table>

	";
}
?>