<?php
/**
*
* File: _admin/_inc/users/feed.php
* Version 1.0
* Date: 17:07 09.02.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */




/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_users_feeds_index		= $mysqlPrefixSav . "users_feeds_index";


if($action == ""){
	echo"
	<h1>Feed</h1>


	<!-- Where am I ? -->
		<p>
		<b>Where am I?</b><br />
		<a href=\"index.php?open=users&amp;page=feed&amp;editor_language=$editor_language&amp;l=$l\">Feed</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Menu -->
		<p>
		<a href=\"index.php?open=users&amp;page=feed&amp;action=about&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">About</a>
		<a href=\"index.php?open=users&amp;page=feed&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Truncate</a>
		</p>
	<!-- //Menu -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Feed index -->
		";

		$query = "SELECT feed_id, feed_title, feed_text, feed_image_path, feed_image_file, feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, feed_sub_category_id, feed_sub_category_name, feed_user_id, feed_user_email, feed_user_name, feed_user_alias, feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, feed_created_date_saying, feed_created_year, feed_created_time, feed_modified_datetime, feed_likes, feed_dislikes, feed_comments, feed_reported, feed_reported_checked, feed_reported_reason FROM $t_users_feeds_index ORDER BY feed_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_feed_id, $get_feed_title, $get_feed_text, $get_feed_image_path, $get_feed_image_file, $get_feed_image_thumb_300x169, $get_feed_image_thumb_540x304, $get_feed_link_url, $get_feed_link_name, $get_feed_module_name, $get_feed_module_part_name, $get_feed_module_part_id, $get_feed_main_category_id, $get_feed_main_category_name, $get_feed_sub_category_id, $get_feed_sub_category_name, $get_feed_user_id, $get_feed_user_email, $get_feed_user_name, $get_feed_user_alias, $get_feed_user_photo_file, $get_feed_user_photo_thumb_40, $get_feed_user_photo_thumb_50, $get_feed_user_photo_thumb_60, $get_feed_user_photo_thumb_200, $get_feed_user_subscribe, $get_feed_user_ip, $get_feed_user_hostname, $get_feed_language, $get_feed_created_datetime, $get_feed_created_date_saying, $get_feed_created_year, $get_feed_created_time, $get_feed_modified_datetime, $get_feed_likes, $get_feed_dislikes, $get_feed_comments, $get_feed_reported, $get_feed_reported_checked, $get_feed_reported_reason) = $row;
			
			echo"
			<div class=\"bodycell\">
				<!-- Author -->
					<table>
					 <tr>
					  <td style=\"padding: 0px 6px 0px 0px;vertical-align:top;\">
						<span>
						<a href=\"../users/view_profile.php?user_id=$get_feed_user_id&amp;l=$l\">";
					if(file_exists("../_uploads/users/images/$get_feed_user_id/$get_feed_user_photo_thumb_40") && $get_feed_user_photo_thumb_40 != ""){
						echo"<img src=\"../_uploads/users/images/$get_feed_user_id/$get_feed_user_photo_thumb_40\" alt=\"$get_feed_user_photo_thumb_40\" />";
					}
					else{
						echo"<img src=\"_inc/users/_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" />";
					}
					echo"</a>
						</span>
					  </td>
					  <td style=\"vertical-align:top;\">
						<span>
						<a href=\"../users/view_profile.php?user_id=$get_feed_user_id&amp;l=$l\">$get_feed_user_name</a><br />
						$get_feed_created_date_saying
						</span>
					  </td>
					  <td style=\"vertical-align:top;\">
						<span>
						<a href=\"index.php?open=users&amp;page=feed&amp;action=delete&amp;feed_id=$get_feed_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a><br />
						</span>
					  </td>
					 </tr>
					</table>
				<!-- //Author -->

				<!-- Post -->";
					if(file_exists("../$get_feed_image_path/$get_feed_image_file") && $get_feed_image_file != ""){
						if(!(file_exists("../$get_feed_image_path/$get_feed_image_thumb_540x304")) && $get_feed_image_thumb_540x304 != ""){
							// Create thumb
							resize_crop_image(540, 304, "../$get_feed_image_path/$get_feed_image_file", "../$get_feed_image_path/$get_feed_image_thumb_540x304");

							echo"<div class=\"info\"><p>Make thumb</p></div>";
						}

						if(file_exists("../$get_feed_image_path/$get_feed_image_thumb_540x304") && $get_feed_image_thumb_540x304 != ""){
							echo"
							<p>
							<a href=\"../$get_feed_link_url\"><img src=\"../$get_feed_image_path/$get_feed_image_thumb_540x304\" alt=\"$get_feed_image_thumb_540x304\" /></a>
							</p>
							";
						}
					}
					echo"
					<p><a href=\"../$get_feed_link_url\"><b>$get_feed_title</b></a><br />
					$get_feed_text
					</p>

				<!-- //Post -->
			</div>
			";
		}
		echo"

	<!-- //Feed index -->
	";
} // index
elseif($action == "about"){
	echo"
	<h1>About Feed</h1>


	<!-- Where am I ? -->
		<p>
		<b>Where am I?</b><br />
		<a href=\"index.php?open=users&amp;page=feed&amp;editor_language=$editor_language&amp;l=$l\">Feed</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=feed&amp;action=about&amp;editor_language=$editor_language&amp;l=$l\">About</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<p>
	The table <em>$t_users_feeds_index</em> hold all changes that a user does to the website. It has them stored so that
	they can be printed on front page or aside of a website.
	</p>

	<p>
	The following modules will add data to feed index table:
	</p>

	<ul>
		<li><p>Blog</p></li>
	</ul>

	";
} // about
elseif($action == "truncate"){
	if($process == "1"){
		mysqli_query($link, "TRUNCATE $t_users_feeds_index");
		header("Location: index.php?open=users&page=feed&editor_language=$editor_language&l=$l&ft=success&fm=truncated");
		exit;
	}
	echo"
	<h1>Truncate Feed</h1>


	<!-- Where am I ? -->
		<p>
		<b>Where am I?</b><br />
		<a href=\"index.php?open=users&amp;page=feed&amp;editor_language=$editor_language&amp;l=$l\">Feed</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=feed&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l\">Truncate</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<p>
	Do you want to empty the table?
	</p>

	<p>
	<a href=\"index.php?open=users&amp;page=feed&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Confirm Truncate</a>
	</p>


	";
} // truncate
elseif($action == "delete"){
	// Get feed
	$feed_id = $_GET['feed_id'];
	$feed_id = strip_tags(stripslashes($feed_id));
	if(!(is_numeric($feed_id))){
		echo"Feed id not not numeric";
		die;
	}

	$feed_id_mysql = quote_smart($link, $feed_id);
	
	$query = "SELECT feed_id, feed_title, feed_text, feed_image_path, feed_image_file, feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, feed_sub_category_id, feed_sub_category_name, feed_user_id, feed_user_email, feed_user_name, feed_user_alias, feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, feed_created_date_saying, feed_created_year, feed_created_time, feed_modified_datetime, feed_likes, feed_dislikes, feed_comments, feed_reported, feed_reported_checked, feed_reported_reason FROM $t_users_feeds_index WHERE feed_id=$feed_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_feed_id, $get_current_feed_title, $get_current_feed_text, $get_current_feed_image_path, $get_current_feed_image_file, $get_current_feed_image_thumb_300x169, $get_current_feed_image_thumb_540x304, $get_current_feed_link_url, $get_current_feed_link_name, $get_current_feed_module_name, $get_current_feed_module_part_name, $get_current_feed_module_part_id, $get_current_feed_main_category_id, $get_current_feed_main_category_name, $get_current_feed_sub_category_id, $get_current_feed_sub_category_name, $get_current_feed_user_id, $get_current_feed_user_email, $get_current_feed_user_name, $get_current_feed_user_alias, $get_current_feed_user_photo_file, $get_current_feed_user_photo_thumb_40, $get_current_feed_user_photo_thumb_50, $get_current_feed_user_photo_thumb_60, $get_current_feed_user_photo_thumb_200, $get_current_feed_user_subscribe, $get_current_feed_user_ip, $get_current_feed_user_hostname, $get_current_feed_language, $get_current_feed_created_datetime, $get_current_feed_created_date_saying, $get_current_feed_created_year, $get_current_feed_created_time, $get_current_feed_modified_datetime, $get_current_feed_likes, $get_current_feed_dislikes, $get_current_feed_comments, $get_current_feed_reported, $get_current_feed_reported_checked, $get_current_feed_reported_reason) = $row;

	if($get_current_feed_id == ""){
		echo"Feed not found";
	}
	else{
		if($process == "1"){
			
			// Delete 
			$result = mysqli_query($link, "DELETE FROM $t_users_feeds_index WHERE feed_id=$get_current_feed_id") or die(mysqli_error($link));

			// Delete thumbs
			if(file_exists("../$get_current_feed_image_path/$get_current_feed_image_thumb_300x169") && $get_current_feed_image_thumb_300x169 != ""){
				unlink("../$get_current_feed_image_path/$get_current_feed_image_thumb_300x169");
			}
			if(file_exists("../$get_current_feed_image_path/$get_current_feed_image_thumb_540x304") && $get_current_feed_image_thumb_540x304 != ""){
				unlink("../$get_current_feed_image_path/$get_current_feed_image_thumb_540x304");
			}

			// Header
			$url = "index.php?open=users&page=feed&feed_id=$get_current_feed_id&editor_language=$editor_language&amp;l=$l&ft=success&fm=delted#feed$get_current_feed_id";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Delete feed</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=users&amp;page=feed&amp;feed_id=$get_current_feed_id&amp;editor_language=$editor_language&amp;l=$l\">Feed</a>
			&gt;	
			<a href=\"index.php?open=users&amp;page=feed&amp;&amp;feed_id=$get_current_feed_id&amp;editor_language=$editor_language&amp;l=$l#feed$get_current_feed_id\">$get_current_feed_title</a>
			&gt;
			<a href=\"index.php?open=users&amp;page=feed&amp;action=delete&amp;feed_id=$get_current_feed_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->

		<p>Are you sure you want to delete the feed?</p>

		<p>
		<a href=\"index.php?open=users&amp;page=feed&amp;action=delete&amp;feed_id=$get_current_feed_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Confirm</a>				
		</p>
		";
	}

} // delete
?>