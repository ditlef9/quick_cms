<?php
/**
*
* File: forum/index.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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



/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['tag'])) {
	$tag = $_GET['tag'];
	$tag = strip_tags(stripslashes($tag));
	$tag = output_html($tag);
}
else{
	$tag = "";
}
$tag_mysql = quote_smart($link, $tag);

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
}
else{
	$order_method = "";
}

/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_newest $tag $l_topics_lowercase - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/* Settings */
$viewMethodSav = "chat"; // chat or list


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
}

// My ip
$my_ip = $_SERVER['REMOTE_ADDR'];
$my_ip = output_html($my_ip);
$my_ip_mysql = quote_smart($link, $my_ip);


// Find tag
$query_tag = "SELECT tag_id, tag_title, tag_title_clean, tag_introduction, tag_description, tag_created, tag_updated, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week, tag_is_official, tag_icon_path, tag_icon_file_16, tag_icon_file_32, tag_icon_file_256 FROM $t_forum_tags_index WHERE tag_title_clean=$tag_mysql";
$result_tag = mysqli_query($link, $query_tag);
$row_tag = mysqli_fetch_row($result_tag);
list($get_current_tag_id, $get_current_tag_title, $get_current_tag_title_clean, $get_current_tag_introduction, $get_current_tag_description, $get_current_tag_created, $get_current_tag_updated, $get_current_tag_topics_total_counter, $get_current_tag_topics_today_counter, $get_current_tag_topics_today_day, $get_current_tag_topics_this_week_counter, $get_current_tag_topics_this_week_week, $get_current_tag_is_official, $get_current_tag_icon_path, $get_current_tag_icon_file_16, $get_current_tag_icon_file_32, $get_current_tag_icon_file_256) = $row_tag;

if($get_current_tag_id == ""){
	echo"<p>Tag not found</p>";

}
else{
	// Translation
	$l_mysql = quote_smart($link, $l);
	$query_tag = "SELECT tag_translation_id, tag_id, tag_translation_language, tag_translation_introduction, tag_translation_description FROM $t_forum_tags_index_translation WHERE tag_id=$get_current_tag_id AND tag_translation_language=$l_mysql";
	$result_tag = mysqli_query($link, $query_tag);
	$row_tag = mysqli_fetch_row($result_tag);
	list($get_current_tag_translation_id, $get_translation_current_tag_id, $get_current_tag_translation_language, $get_current_tag_translation_introduction, $get_current_tag_translation_description) = $row_tag;
	
	if($get_current_tag_translation_id == ""){
		// Insert blank translation

		mysqli_query($link, "INSERT INTO $t_forum_tags_index_translation 
		(tag_translation_id, tag_id, tag_translation_language, tag_translation_introduction, tag_translation_description) 
		VALUES 
		(NULL, $get_current_tag_id, $l_mysql, '', '')")
		or die(mysqli_error($link));
	}

	if($action == ""){

		echo"
		<!-- Headline and introduction -->
			<h1>$l_topics_tagged $get_current_tag_title_clean</h1>

			<p>$get_current_tag_translation_introduction</p>
		<!-- //Headline and introduction -->


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Menu -->
			<div style=\"float: left;\">
				<p>
				<a href=\"index.php?l=$l\" class=\"btn_default\">$l_home</a>
				<a href=\"new_topic.php?l=$l\" class=\"btn_default\">$l_new_topic</a>
				</p>
			</div>
			<div style=\"float: right;\">
				<p>";
				// Watch and ignore
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					$my_user_id = $_SESSION['user_id'];
					$my_user_id = output_html($my_user_id);
					$my_user_id_mysql = quote_smart($link, $my_user_id);

					$query_tag = "SELECT watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_watch_id, $get_watch_tag_id, $get_watch_user_id, $get_watch_user_name, $get_watch_user_email, $get_watch_user_email_notification, $get_watch_user_last_sent_email_datetime, $get_watch_user_last_sent_email_time) = $row_tag;

					$query_tag = "SELECT ignore_id, ignore_tag_id, ignore_user_id FROM $t_forum_tags_ignore WHERE ignore_tag_id=$get_current_tag_id AND ignore_user_id=$my_user_id_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_ignore_id, $get_ignore_tag_id, $get_ignore_user_id) = $row_tag;
					
					if($get_watch_id == ""){
						echo"
						<a href=\"open_tag.php?action=watch_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_watch_tag</a>
						<a href=\"open_tag.php?action=watch_and_subscribe_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_watch_and_subscribe_tag</a>
						";
					}
					else{
						if($get_watch_user_email_notification == "1"){
							echo"
							<a href=\"open_tag.php?action=unwatch_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_unwatch_tag</a>
							<a href=\"open_tag.php?action=unsubscribe_from_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_unsubscribe_from_tag</a>
							";
						}
						else{
							echo"
							<a href=\"open_tag.php?action=unwatch_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_unwatch_tag</a>
							<a href=\"open_tag.php?action=watch_and_subscribe_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_subscribe_to_tag</a>
							";
						}

					}
					if($get_ignore_id == ""){
						echo"
						<a href=\"open_tag.php?action=ignore_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_ignore_tag</a>
						";
					}
					else{
						echo"
						<a href=\"open_tag.php?action=stop_ignoring_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_stop_ignore_tag</a>
						";
					}

				}
				else{
					echo"
					<a href=\"$root/users/login.php?l=$l&amp;referer=forum/open_tag.php?action=watch_tag&amp;tag=$get_current_tag_title_clean&amp;l=$l\" class=\"btn_default\">$l_watch_or_ignore_tag</a>
					
					";
				}
				echo"
				</p>
			</div>

			<div class=\"clear\" style=\"height: 15px;\"></div>
		<!-- //Menu -->


		<!-- Show topics -->
			<table style=\"width: 100%;\">
		";
	
		$x = 0;

		$tag_title_clean_mysql = quote_smart($link, $get_current_tag_title_clean);
		$query_w = "SELECT $t_forum_topics_tags.topic_id, $t_forum_topics.topic_user_id, $t_forum_topics.topic_user_alias, $t_forum_topics.topic_user_image, $t_forum_topics.topic_title, $t_forum_topics.topic_updated_translated, $t_forum_topics.topic_replies, $t_forum_topics.topic_views, $t_forum_topics.topic_solved FROM $t_forum_topics_tags JOIN $t_forum_topics ON $t_forum_topics_tags.topic_id=$t_forum_topics.topic_id WHERE $t_forum_topics_tags.topic_tag_clean=$tag_title_clean_mysql ORDER BY $t_forum_topics_tags.topic_id DESC";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_title, $get_topic_updated_translated, $get_topic_replies, $get_topic_views, $get_topic_solved) = $row_w;

		
		// Avatar
		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image") && $get_topic_user_image != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image", "$thumb_full_path");
			}
			
		}
		else{
			$thumb_full_path = "_gfx/avatar_blank_40.png";
		}

		// Read
		if(isset($get_my_user_id)){
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$get_topic_id AND topic_read_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}
		else{
			$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_ip WHERE topic_read_topic_id=$get_topic_id AND topic_read_ip=$my_ip_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_topic_read_id) = $row;
		}


		// Style
		if(isset($style) && $style == "topics_bodycell"){
			$style = "topics_subcell";
		}
		else{
			$style = "topics_bodycell";
		}

		// Icon = "; if($get_topic_read_id == "" OR $get_topic_read_id == "0"){ echo"_unread"; } if($get_topic_solved == "1"){ echo"_solved"; } echo"

		// Show all
		echo"
		 <tr>
		  <td class=\"$style\">
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"vertical-align: center;\">
				<p class=\"p_forum_topic_title\">
				<a href=\"view_topic.php?topic_id=$get_topic_id&amp;l=$l\"  class=\"forum_topic_title\" "; if($get_topic_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$get_topic_title</a><br />
				<span class=\"forum_meta_data\">
				$l_by <a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\" class=\"forum_meta_data\">$get_topic_user_alias</a>,
				$get_topic_updated_translated
				</span>
				</p>

				<p class=\"p_forum_replies_views_mobile_only\">
				$get_topic_replies $l_replies_lowercase
				&nbsp; &nbsp; &nbsp; 
				$get_topic_views $l_views_lowercase
				</p>
				
				";
				// Tags
				echo"<p class=\"p_forum_tags\">\n";
				$query_t = "SELECT topic_tag_id, topic_tag_title, topic_tag_clean FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_topic_tag_id, $get_topic_tag_title, $get_topic_tag_clean) = $row_t;
					
					$tag_title_clean_mysql = quote_smart($link, $get_topic_tag_clean);
					$query_tag = "SELECT tag_id, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_title_clean=$tag_title_clean_mysql";
					$result_tag = mysqli_query($link, $query_tag);
					$row_tag = mysqli_fetch_row($result_tag);
					list($get_tag_id, $get_tag_icon_path, $get_tag_icon_file_16) = $row_tag;
		
					echo"
					<a href=\"open_tag.php?tag=$get_topic_tag_clean&amp;l=$l\" class=\"forum_a_tag\"";
					if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
						// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
						echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
					}
					echo">$get_topic_tag_title</a>
					";
				}
				echo"
				</p>
			  </td>
			  <td style=\"vertical-align: center;text-align: right;width: 20%\" class=\"td_forum_replies_views\">
				<p class=\"forum_meta_data\">
				$get_topic_replies $l_replies_lowercase<br />
				$get_topic_views $l_views_lowercase
				</p>
			  </td>
			  <td style=\"vertical-align: top;text-align: right;width: 50px;\">
				<p>
				<a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a>
				</p>
			  </td>
			 </tr>
			</table>
		  </td>
		 </tr>
		";
		}

		echo"
			</table>
		<!-- //Show topics -->
		";
	} // action ==""
	elseif($action == "watch_tag"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
			$inp_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_user_email_mysql = quote_smart($link, $get_my_user_email);

			$datetime = date("Y-m-d H:i:s");
			$time = time();

			// Am I already watching it?
			$query_tag = "SELECT watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql";
			$result_tag = mysqli_query($link, $query_tag);
			$row_tag = mysqli_fetch_row($result_tag);
			list($get_watch_id, $get_watch_tag_id, $get_watch_user_id, $get_watch_user_name, $get_watch_user_email, $get_watch_user_email_notification, $get_watch_user_last_sent_email_datetime, $get_watch_user_last_sent_email_time) = $row_tag;
			if($get_watch_id == ""){
				// Insert to watch
				mysqli_query($link, "INSERT INTO $t_forum_tags_watch 
				(watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time) 
				VALUES 
				(NULL, $get_current_tag_id, $get_my_user_id, $inp_user_name_mysql, $inp_user_email_mysql, '0', '$datetime', '$time')")
				or die(mysqli_error($link));
			}
			else{
				// Update watch
				$result = mysqli_query($link, "UPDATE $t_forum_tags_watch SET watch_user_name=$inp_user_name_mysql, watch_user_email=$inp_user_email_mysql, watch_user_email_notification='0' WHERE watch_id=$get_watch_id");
			}

			// Delete from ignore list (if it is there)
			$result = mysqli_query($link, "DELETE FROM $t_forum_tags_ignore WHERE ignore_tag_id=$get_current_tag_id AND ignore_user_id=$get_my_user_id");


			header("Location: open_tag.php?tag=$get_current_tag_title_clean&l=$l&ft=success&fm=now_watching_tag");
			exit;

		} // logged in	
	} // watch tag
	elseif($action == "watch_and_subscribe_tag"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
			$inp_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_user_email_mysql = quote_smart($link, $get_my_user_email);

			$datetime = date("Y-m-d H:i:s");
			$time = time();

			// Am I already watching it?
			$query_tag = "SELECT watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql";
			$result_tag = mysqli_query($link, $query_tag);
			$row_tag = mysqli_fetch_row($result_tag);
			list($get_watch_id, $get_watch_tag_id, $get_watch_user_id, $get_watch_user_name, $get_watch_user_email, $get_watch_user_email_notification, $get_watch_user_last_sent_email_datetime, $get_watch_user_last_sent_email_time) = $row_tag;
			if($get_watch_id == ""){
				// Insert to watch
				mysqli_query($link, "INSERT INTO $t_forum_tags_watch 
				(watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time) 
				VALUES 
				(NULL, $get_current_tag_id, $get_my_user_id, $inp_user_name_mysql, $inp_user_email_mysql, '1', '$datetime', '$time')")
				or die(mysqli_error($link));
			}
			else{
				// Update watch
				$result = mysqli_query($link, "UPDATE $t_forum_tags_watch SET watch_user_name=$inp_user_name_mysql, watch_user_email=$inp_user_email_mysql, watch_user_email_notification='1' WHERE watch_id=$get_watch_id");
			}

			// Delete from ignore list (if it is there)
			$result = mysqli_query($link, "DELETE FROM $t_forum_tags_ignore WHERE ignore_tag_id=$get_current_tag_id AND ignore_user_id=$get_my_user_id");


			header("Location: open_tag.php?tag=$get_current_tag_title_clean&l=$l&ft=success&fm=now_watching_and_subscribing_to_tag");
			exit;

		} // logged in	
	} // watch_and_subscribe_tag
	elseif($action == "unwatch_tag"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
			$inp_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_user_email_mysql = quote_smart($link, $get_my_user_email);

			$datetime = date("Y-m-d H:i:s");
			$time = time();

			// Am I already watching it?
			$query_tag = "SELECT watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql";
			$result_tag = mysqli_query($link, $query_tag);
			$row_tag = mysqli_fetch_row($result_tag);
			list($get_watch_id, $get_watch_tag_id, $get_watch_user_id, $get_watch_user_name, $get_watch_user_email, $get_watch_user_email_notification, $get_watch_user_last_sent_email_datetime, $get_watch_user_last_sent_email_time) = $row_tag;
			if($get_watch_id != ""){
				// Update watch
				$result = mysqli_query($link, "DELETE FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql");
			}
			header("Location: open_tag.php?tag=$get_current_tag_title_clean&l=$l&ft=success&fm=unwatching_tag");
			exit;

		} // logged in	
	} // watch_and_subscribe_tag
	elseif($action == "unsubscribe_from_tag"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
			$inp_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_user_email_mysql = quote_smart($link, $get_my_user_email);

			$datetime = date("Y-m-d H:i:s");
			$time = time();

			// Am I already watching it?
			$query_tag = "SELECT watch_id, watch_tag_id, watch_user_id, watch_user_name, watch_user_email, watch_user_email_notification, watch_user_last_sent_email_datetime, watch_user_last_sent_email_time FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql";
			$result_tag = mysqli_query($link, $query_tag);
			$row_tag = mysqli_fetch_row($result_tag);
			list($get_watch_id, $get_watch_tag_id, $get_watch_user_id, $get_watch_user_name, $get_watch_user_email, $get_watch_user_email_notification, $get_watch_user_last_sent_email_datetime, $get_watch_user_last_sent_email_time) = $row_tag;
			if($get_watch_id != ""){
				// Update watch
				$result = mysqli_query($link, "DELETE FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql");
			}
			header("Location: open_tag.php?tag=$get_current_tag_title_clean&l=$l&ft=success&fm=unsubscribed_from_tag");
			exit;

		} // logged in	
	} // unsubscribe_from_tag
	elseif($action == "ignore_tag"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Am I already watching it?
			$query_tag = "SELECT ignore_id FROM $t_forum_tags_ignore WHERE ignore_tag_id=$get_current_tag_id AND ignore_user_id=$get_my_user_id";
			$result_tag = mysqli_query($link, $query_tag);
			$row_tag = mysqli_fetch_row($result_tag);
			list($get_ignore_id) = $row_tag;
			if($get_ignore_id == ""){
				// Insert to ignore
				mysqli_query($link, "INSERT INTO $t_forum_tags_ignore 
				(ignore_id, ignore_tag_id, ignore_user_id) 
				VALUES 
				(NULL, $get_current_tag_id, $get_my_user_id)")
				or die(mysqli_error($link));
			}

			// Delete from watch list (if it is there)
			$result = mysqli_query($link, "DELETE FROM $t_forum_tags_watch WHERE watch_tag_id=$get_current_tag_id AND watch_user_id=$my_user_id_mysql");


			header("Location: open_tag.php?tag=$get_current_tag_title_clean&l=$l&ft=success&fm=now_ignoring_tag");
			exit;

		} // logged in	
	} // ignore tag
	elseif($action == "stop_ignoring_tag"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Am I already watching it?
			$query_tag = "SELECT ignore_id FROM $t_forum_tags_ignore WHERE ignore_tag_id=$get_current_tag_id AND ignore_user_id=$get_my_user_id";
			$result_tag = mysqli_query($link, $query_tag);
			$row_tag = mysqli_fetch_row($result_tag);
			list($get_ignore_id) = $row_tag;
			if($get_ignore_id != ""){
				$result = mysqli_query($link, "DELETE FROM $t_forum_tags_ignore WHERE ignore_tag_id=$get_current_tag_id AND ignore_user_id=$get_my_user_id");

				header("Location: open_tag.php?tag=$get_current_tag_title_clean&l=$l&ft=success&fm=stopped_ignoring_tag");
				exit;

			}


		} // logged in	
	} // ignore tag

} // tag found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>