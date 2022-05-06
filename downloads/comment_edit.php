<?php 
/**
*
* File: downloads/comment_edit.php
* Version 1.0.0
* Date 11:51 01.11.2020
* Copyright (c) 2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");



/*- Tables ---------------------------------------------------------------------------- */
include("_tables_downloads.php");



/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['comment_id'])){
	$comment_id = $_GET['comment_id'];
	$comment_id = output_html($comment_id);
}
else{
	$comment_id = "";
}

// Get comment
$comment_id_mysql = quote_smart($link, $comment_id);
$query = "SELECT comment_id, comment_download_id, comment_text, comment_by_user_id, comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, comment_likes, comment_dislikes, comment_read_blog_owner, comment_reported, comment_reported_by_user_id, comment_reported_reason, comment_reported_checked FROM $t_downloads_comments WHERE comment_id=$comment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_comment_id, $get_current_comment_download_id, $get_current_comment_text, $get_current_comment_by_user_id, $get_current_comment_by_user_name, $get_current_comment_by_user_image_path, $get_current_comment_by_user_image_file, $get_current_comment_by_user_image_thumb_60, $get_current_comment_by_user_ip, $get_current_comment_created, $get_current_comment_created_saying, $get_current_comment_created_timestamp, $get_current_comment_updated, $get_current_comment_updated_saying, $get_current_comment_likes, $get_current_comment_dislikes, $get_current_comment_read_blog_owner, $get_current_comment_reported, $get_current_comment_reported_by_user_id, $get_current_comment_reported_reason, $get_current_comment_reported_checked) = $row;

if($get_current_comment_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "404 - $l_downloads";
	include("$root/_webdesign/header.php");
	echo"<p>Comment not found.</p>";
}
else{
	// Get download
	$query = "SELECT download_id, download_title, download_language, download_introduction, download_description, download_image_path, download_image_store, download_image_store_thumb, download_image_thumb_a, download_image_thumb_b, download_image_thumb_c, download_image_thumb_d, download_image_file_a, download_image_file_b, download_image_file_c, download_image_file_d, download_read_more_url, download_main_category_id, download_sub_category_id, download_dir, download_file, download_type, download_version, download_file_size, download_file_date, download_file_date_print, download_last_download, download_hits, download_unique_hits, download_ip_block, download_tag_a, download_tag_b, download_tag_c, download_created_datetime, download_updated_datetime, download_updated_print, download_have_to_be_logged_in_to_download FROM $t_downloads_index WHERE download_id=$get_current_comment_download_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_download_id, $get_current_download_title, $get_current_download_language, $get_current_download_introduction, $get_current_download_description, $get_current_download_image_path, $get_current_download_image_store, $get_current_download_image_store_thumb, $get_current_download_image_thumb_a, $get_current_download_image_thumb_b, $get_current_download_image_thumb_c, $get_current_download_image_thumb_d, $get_current_download_image_file_a, $get_current_download_image_file_b, $get_current_download_image_file_c, $get_current_download_image_file_d, $get_current_download_read_more_url, $get_current_download_main_category_id, $get_current_download_sub_category_id, $get_current_download_dir, $get_current_download_file, $get_current_download_type, $get_current_download_version, $get_current_download_file_size, $get_current_download_file_date, $get_current_download_file_date_print, $get_current_download_last_download, $get_current_download_hits, $get_current_download_unique_hits, $get_current_download_ip_block, $get_current_download_tag_a, $get_current_download_tag_b, $get_current_download_tag_c, $get_current_download_created_datetime, $get_current_download_updated_datetime, $get_current_download_updated_print, $get_current_download_have_to_be_logged_in_to_download) = $row;
	if($get_current_download_id == ""){
		$website_title = "404 - $l_downloads";
		include("$root/_webdesign/header.php");
		echo"
		<h1>Server error 404</h1>
		<p>Download not found.</p>
		";
	}
	else{
		// Main category
		$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id='$get_current_download_main_category_id'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;


	
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_edit_comment - $get_current_download_title - $get_current_main_category_title - $l_downloads";
		include("$root/_webdesign/header.php");
	
		// Logged in?
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

			$query = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_60) = $row;

			// Can edit?
			$can_edit = 0;
			if($get_my_user_id == "$get_current_comment_by_user_id"){
				$can_edit = 1;
			}
			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				$can_edit = 1;
			}
			if($can_edit == "0"){
				echo"<p>Access denied.</p>";
			}
			else{
				if($process == "1"){
					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");
	
					// Update
					mysqli_query($link, "UPDATE $t_downloads_comments SET 
							comment_text=$inp_text_mysql,
							comment_updated='$datetime',
							comment_updated_saying='$date_saying'
							WHERE comment_id=$get_current_comment_id")
							or die(mysqli_error($link));

					// header
					$url = "view_download.php?download_id=$get_current_download_id&l=$l&ft_comment=success&fm_comment=changes_saved#comment$get_current_comment_id";
					header("Location: $url");
					exit;
				}


				echo"
				<h1>$l_edit_comment</h1>

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_downloads</a>
					&gt;
					<a href=\"open_main_category.php?main_category_id=$get_current_main_category_id&amp;l=$l\">$get_current_main_category_title</a>
					&gt;
					<a href=\"view_download.php?download_id=$get_current_download_id&amp;l=$l\">$get_current_download_title</a>
					&gt;
					<a href=\"view_download.php?download_id=$get_current_download_id&amp;l=$l#comment$get_current_comment_id\">$l_comment $get_current_comment_id</a>
					&gt;
					<a href=\"comment_edit.php?comment_id=$get_current_comment_id&amp;l=$l\">$l_edit_comment $get_current_comment_id</a>
					</p>
				<!-- //Where am I? -->
	
				<!-- Edit comment form -->

					<form method=\"post\" action=\"comment_edit.php?comment_id=$get_current_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
					<table>
	 				 <tr>
					  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
						<p>
						";
						if(file_exists("$root/$get_current_comment_by_user_image_path/$get_current_comment_by_user_image_thumb_60") && $get_current_comment_by_user_image_thumb_60 != ""){

				
							echo"
							<img src=\"$root/$get_current_comment_by_user_image_path/$get_current_comment_by_user_image_thumb_60\" alt=\"$get_current_comment_by_user_image_thumb_60\" />
							<br />
							";
						}
						echo"
						$get_current_comment_by_user_name
						</p>
					  </td>
					  <td style=\"vertical-align: top;\">
						<p>
						<textarea name=\"inp_text\" rows=\"5\" cols=\"80\">";
						$get_current_comment_text = str_replace("<br />", "\n", $get_current_comment_text);
						echo"$get_current_comment_text</textarea><br />
						<input type=\"submit\" value=\"$l_save_comment\" class=\"btn_default\" />
						</p>
					  </td>
					 </tr>
					</table>

					</form>
				<!-- //Edit comment form -->
				";
			} // can edit
		}
		else{
			echo"<p>Not logged in.</p>";
		}
	} // download found
} // comment found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>