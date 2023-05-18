<?php 
/**
*
* File: blog/my_blog_new_post.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");

/*- Tables -------------------------------------------------------------------- */
$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";


/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Functions -------------------------------------------------------------------------------- */
function delete_cache($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
  				unlink($dirname."/".$file);
        		else
				delete_directory($dirname.'/'.$file);    
			}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_post - $l_my_blog - $l_blog";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	// Get blog info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_user_ip) = $row;


	// Can I have a blog?
	$can_post = "true";
	if($blogWhoCanHaveBlogSav == "admin"){
		if($get_my_user_rank != "admin"){
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin can post.</p>";
		}
	}
	elseif($blogWhoCanHaveBlogSav == "admin_and_moderator"){
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
		}
		else{
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin and moderator can post.</p>";
		}
	}
	elseif($blogWhoCanHaveBlogSav == "admin_moderator_and_editor"){
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor"){
		}
		else{
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin, moderator and editor can post.</p>";
		}
	}
	elseif($blogWhoCanHaveBlogSav == "admin_moderator_editor_and_trusted"){
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor" OR $get_my_user_rank == "trusted"){
		}
		else{
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin, moderator, editor and trusted can post.</p>";
		}
	}

	if($can_post == "true"){
		if($get_blog_info_id == ""){
			echo"
			<h1><img src=\"_gfx/loading.gif\" alt=\"loading.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />New Post Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?reference=new_post&amp;l=$l\">
			<p>$l_creating_your_blog</p>
			";
		}
		else{
			if($process == "1"){

				// Category
				$inp_category = $_POST['inp_category'];
				$inp_category = output_html($inp_category);
				$inp_category_mysql = quote_smart($link, $inp_category);
				$query = "SELECT blog_category_id, blog_category_title FROM $t_blog_categories WHERE blog_category_id=$inp_category_mysql AND blog_category_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_blog_category_id, $get_blog_category_title) = $row;
				$inp_category_title_mysql = quote_smart($link, $get_blog_category_title);

				$inp_privacy_level = $_POST['inp_privacy_level'];
				$inp_privacy_level = output_html($inp_privacy_level);
				$inp_privacy_level_mysql = quote_smart($link, $inp_privacy_level);

				if(isset($_POST['inp_ad'])){
					$inp_ad = $_POST['inp_ad'];
				}
				else{
					$inp_ad = "";
				}
				if($inp_ad == "on"){ $inp_ad = "1"; } else{ $inp_ad = "0"; } 
				$inp_ad = output_html($inp_ad);
				$inp_ad_mysql = quote_smart($link, $inp_ad);

				$datetime = date("Y-m-d H:i:s");
				$datetime_rss = date("D, d M Y H:i:s T");

				$inp_user_ip = $_SERVER['REMOTE_ADDR'];
				$inp_user_ip = output_html($inp_user_ip);
				$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);



				mysqli_query($link, "INSERT INTO $t_blog_posts
				(blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, 
				blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, 
				blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, 
				blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, 
				blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, 
				blog_post_views, blog_post_views_ipblock, blog_post_user_ip) 
				VALUES 
				(NULL, $my_user_id_mysql, '', '', $l_mysql, 
				'draft', $get_blog_category_id, $inp_category_title_mysql, '', $inp_privacy_level_mysql, 
				'', '', '', '', '',
				'', '', '', $inp_ad_mysql, '$datetime', 
				'$datetime_rss',  '$datetime', '$datetime_rss', 1, 0, 
				0, '', $inp_user_ip_mysql)")
				or die(mysqli_error($link));



				// Get ID
				$query = "SELECT blog_post_id FROM $t_blog_posts WHERE blog_post_user_id=$my_user_id_mysql AND blog_post_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_blog_post_id) = $row;

				// posts counters + date
				$result = $link->query("SELECT COUNT(*) FROM $t_blog_posts WHERE blog_post_user_id=$my_user_id_mysql AND blog_post_language=$l_mysql");
				$row = $result->fetch_row();
				$result = mysqli_query($link, "UPDATE $t_blog_info SET blog_updated='$datetime', blog_updated_rss='$datetime_rss', blog_posts=$row[0]  WHERE blog_info_id=$get_blog_info_id");


				// Header
				$url = "my_blog_new_post_main_image.php?blog_post_id=$get_blog_post_id&l=$l";
				header("Location: $url");
				exit;
			} // process

			echo"
			<h1>$l_new_post</h1>

			<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_new_post.php?l=$l\">$l_new_post</a>
					</p>
			<!-- //Where am I? -->

				
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

			

			<!-- Form -->
				<form method=\"post\" action=\"my_blog_new_post.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				
				<p><b>$l_category:</b><br />
				<select name=\"inp_category\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />\n";
				$query = "SELECT blog_category_id, blog_category_title, blog_category_posts FROM $t_blog_categories WHERE blog_category_user_id=$my_user_id_mysql AND blog_category_language=$l_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_blog_category_id, $get_blog_category_title, $get_blog_category_posts) = $row;
					echo"			";
					echo"<option value=\"$get_blog_category_id\" selected=\"selected\">$get_blog_category_title</option>\n";
				}
				echo"
				</select>
				</p>
		
				<p><b>$l_who_can_see_this_post</b><br />
				<select name=\"inp_privacy_level\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<option value=\"everyone\">$l_everyone</option>
					<option value=\"friends\">$l_friends</option>
					<option value=\"private\"$l_private</option>
				</select>

				<p><b>$l_mark_as_ad:</b><br />
				<input type=\"checkbox\" name=\"inp_ad\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				$l_yes
				</p>

				<p>
				<input type=\"submit\" value=\"$l_next\" name=\"inp_submit\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
				</form>
				<div style=\"height: 20px;\"></div>
			<!-- //Form -->
			";
		} // found
	} // can post (access)
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading.gif\" alt=\"loading.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	New Post...</h1>

	<p>
	Please log in.
	</p>

	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>