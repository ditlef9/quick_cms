<?php
/**
*
* File: blog/rss_generate_blog.php
* Version 1.0.0.
* Date 20:53 13.03.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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
include("_tables_blog.php");


/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_view_post.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['info_id'])) {
	$info_id = $_GET['info_id'];
	$info_id = strip_tags(stripslashes($info_id));
}
else{
	$info_id = "";
}

/*- Functions ------------------------------------------------------------------------ */

include("$root/_admin/_functions/encode_national_letters_from_html_to_xml_for_rss.php");


// Get blog
$info_id_mysql = quote_smart($link, $info_id);
$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_updated_rss, blog_posts, blog_comments, blog_views, blog_views_ipblock FROM $t_blog_info WHERE blog_info_id=$info_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_updated_rss, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_views_ipblock) = $row;
	
if($get_current_blog_info_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_blog - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Blog not found.</p>";

}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }


	// Owners user ID
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$get_current_blog_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_rank) = $row;
	
	// Get blog logo
	$query = "SELECT logo_id, logo_blog_info_id, logo_user_id, logo_path, logo_thumb, logo_file, logo_uploaded_datetime, logo_uploaded_ip, logo_reported, logo_reported_checked FROM $t_blog_logos WHERE logo_blog_info_id=$get_current_blog_info_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_logo_id, $get_current_logo_blog_info_id, $get_current_logo_user_id, $get_current_logo_path, $get_current_logo_thumb, $get_current_logo_file, $get_current_logo_uploaded_datetime, $get_current_logo_uploaded_ip, $get_current_logo_reported, $get_current_logo_reported_checked) = $row;


	// DAta
	header('Content-Type: text/xml');


	// Header
	$get_current_blog_title = encode_national_letters_from_html_to_xml_for_rss($get_current_blog_title);
	$get_current_blog_description = encode_national_letters_from_html_to_xml_for_rss($get_current_blog_description);
	echo"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo"<rss version=\"2.0\">\n";
	echo"  <channel>\n";
	echo"    <title>$get_current_blog_title</title>\n";
	echo"    <link>$configSiteURLSav/blog/view_blog.php?info_id=$get_current_blog_info_id&amp;l=$get_current_blog_language</link>\n";
	echo"    <description>$get_current_blog_description</description>\n";
	echo"    <language>$get_current_blog_language</language>\n";
	echo"    <lastBuildDate>$get_current_blog_updated_rss</lastBuildDate>\n";

	// Image
	if($get_current_logo_id != ""){
		$logo_imgsize = getimagesize("$root/$get_current_logo_path/$get_current_logo_file");
		echo"    <image>\n";
		echo"      <title>$get_current_blog_title</title>\n";
		echo"      <url>$configSiteURLSav/$get_current_logo_path/$get_current_logo_file</url>\n";
		echo"      <link>$configSiteURLSav/$get_current_logo_path/$get_current_logo_file</link>\n";
		echo"      <width>$logo_imgsize[0]</width>\n";
		echo"      <height>$logo_imgsize[1]</height>\n";
		echo"    </image>\n";
	}

	// Entries
	$query_w = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_category_id, blog_post_introduction, blog_post_privacy_level, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_ad, blog_post_updated_rss, blog_post_comments FROM $t_blog_posts WHERE blog_post_user_id=$get_current_blog_user_id AND blog_post_language='$get_current_blog_language' ORDER BY blog_post_id DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_blog_post_id, $get_blog_post_user_id, $get_blog_post_title, $get_blog_post_category_id, $get_blog_post_introduction, $get_blog_post_privacy_level, $get_blog_post_image_path, $get_blog_post_image_thumb_small, $get_blog_post_image_thumb_medium, $get_blog_post_image_thumb_large, $get_blog_post_image_file, $get_blog_post_ad, $get_blog_post_updated_rss, $get_blog_post_comments) = $row_w;
			// Privacy
			if($get_blog_post_privacy_level == "everyone"){
				$show_post = "true";
			}
			else{
				if($get_blog_post_privacy_level == "private" && isset($my_user_id) && $my_user_id == "$get_blog_post_user_id"){
					$show_post = "true";
				}
				else{
					if($get_blog_post_privacy_level == "friends" && isset($my_user_id)){
						if($my_user_id == "$get_blog_post_user_id"){
							$show_post = "true";
						}
						else{
							// Are we friends? (me = a, post author = b)
							$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b FROM $t_users_friends WHERE friend_user_id_a=$get_my_user_id AND friend_user_id_b=$get_blog_post_user_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_friend_id, $get_friend_user_id_a, $get_friend_user_id_b) = $row;

							if($get_friend_id == ""){
								$show_post = "false";

								// Are we friends? (me = b, post author = a)
								$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b FROM $t_users_friends WHERE friend_user_id_a=$get_blog_post_user_id AND friend_user_id_b=$get_my_user_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_friend_id, $get_friend_user_id_a, $get_friend_user_id_b) = $row;

								if($get_friend_id == ""){
									$show_post = "false";
								}
								else{
									$show_post = "true";
								}
							}
							else{
								$show_post = "true";
							}
						}
					
					}
					else{
						$show_post = "false";
					}
				}
			}
		if($show_post == "true" && $get_blog_post_image_file != "" && file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_file")){
			

			// Filetype
			$filetype = get_extension($get_blog_post_image_file);

			// Title, Description
			$get_blog_post_title = encode_national_letters_from_html_to_xml_for_rss($get_blog_post_title);
			$get_blog_post_introduction = encode_national_letters_from_html_to_xml_for_rss($get_blog_post_introduction);

			echo"    <item>\n";
			echo"          <title>$get_blog_post_title</title>\n";
			echo"          <link>$configSiteURLSav/blog/view_post.php?post_id=$get_blog_post_id&amp;l=$l</link>\n";
			echo"          <description>$get_blog_post_introduction</description>\n";
			echo"          <enclosure url=\"$configSiteURLSav/$get_blog_post_image_path/$get_blog_post_image_file\" type=\"image/"; 
			if($filetype == "jpg"){ echo"jpeg"; }
			elseif($filetype == "png"){ echo"png"; }
			elseif($filetype == "gif"){ echo"gif"; }
			echo"\" />\n";
			echo"          <pubDate>$get_blog_post_updated_rss</pubDate>\n";
			echo"          <guid>$configSiteURLSav/blog/view_post.php?post_id=$get_blog_post_id&amp;l=$l</guid>\n";
			echo"    </item>\n";
		} // can see
	} // while


	// Footer
	echo"  </channel>\n";
	echo"</rss>";


} // blog found

?>