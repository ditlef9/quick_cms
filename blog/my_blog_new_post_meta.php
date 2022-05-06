<?php 
/**
*
* File: blog/my_blog_new_post_meta.php
* Version 1.0.0
* Date 16:20 12.07.2020
* Copyright (c) 2011-2020 S. A. Ditlefsen
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
// include("$root/_admin/_functions/get_extension.php");

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



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_meta - $l_new_post - $l_my_blog - $l_blog";
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
			<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?reference=new_post&amp;l=$l\">
			<p>$l_creating_your_blog</p>
			";
		}
		else{
			// Fetch blog post
			if(isset($_GET['blog_post_id'])){
				$blog_post_id = $_GET['blog_post_id'];
				$blog_post_id = strip_tags(stripslashes($blog_post_id));
				if(!(is_numeric($blog_post_id))){
					echo"Blog post id not numeric";
					die;
				}
			}
			else{
				$blog_post_id =  "";
			}
			$blog_post_id_mysql = quote_smart($link, $blog_post_id);

			$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, blog_post_views, blog_post_views_ipblock, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$blog_post_id_mysql AND blog_post_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_title_pre, $get_current_blog_post_title, $get_current_blog_post_language, $get_current_blog_post_status, $get_current_blog_post_category_id, $get_current_blog_post_category_title, $get_current_blog_post_introduction, $get_current_blog_post_privacy_level, $get_current_blog_post_text, $get_current_blog_post_image_path, $get_current_blog_post_image_thumb_small, $get_current_blog_post_image_thumb_medium, $get_current_blog_post_image_thumb_large, $get_current_blog_post_image_file, $get_current_blog_post_image_ext, $get_current_blog_post_image_text, $get_current_blog_post_ad, $get_current_blog_post_created, $get_current_blog_post_created_rss, $get_current_blog_post_updated, $get_current_blog_post_updated_rss, $get_current_blog_post_allow_comments, $get_current_blog_post_comments, $get_current_blog_post_views, $get_current_blog_post_views_ipblock, $get_current_blog_post_user_ip) = $row;
			if($get_current_blog_post_id == ""){
				echo"<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
				<meta http-equiv=\"refresh\" content=\"0;url=my_blog_new_post.php?l=$l\">
				";
				die;
			} // blog post not found, create one
			
			if($action == ""){
				// Edit meta

				if($process == "1"){


					$inp_introduction = $_POST['inp_introduction'];
					$inp_introduction = output_html($inp_introduction);
					$inp_introduction_mysql = quote_smart($link, $inp_introduction);

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

					$inp_status = $_POST['inp_status'];
					$inp_status = output_html($inp_status);
					$inp_status_mysql = quote_smart($link, $inp_status);
					
					$result = mysqli_query($link, "UPDATE $t_blog_posts SET 
						blog_post_status=$inp_status_mysql,
						blog_post_category_id=$inp_category_mysql,
						blog_post_category_title=$inp_category_title_mysql,
						blog_post_introduction=$inp_introduction_mysql,
						blog_post_privacy_level=$inp_privacy_level_mysql,
						blog_post_ad=$inp_ad_mysql
					 WHERE blog_post_id=$get_current_blog_post_id");


					// Text, Main Images, Images, Meta, View post
					if(isset($_POST['inp_submit'])){
						$inp_submit = $_POST['inp_submit'];
					}
					else{
						$inp_submit = "";
					}
					$inp_submit = output_html($inp_submit);
					if($inp_submit == "$l_text"){
						$url = "my_blog_new_post.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=changes_saved";
					}
					elseif($inp_submit == "$l_main_image"){
						$url = "my_blog_new_post_main_image.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=changes_saved";
					}
					elseif($inp_submit == "$l_images"){
						$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=changes_saved";
					}
					elseif($inp_submit == "$l_meta"){
						$url = "my_blog_new_post_meta.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=changes_saved";
					}
					elseif($inp_submit == "$l_view_post"){
						$url = "view_post.php?post_id=$get_current_blog_post_id&l=$l";
					}
					else{
						$url = "my_blog_new_post_meta.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=changes_saved";
					}
					header("Location: $url");
					exit;

				}

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
					<a href=\"my_blog_new_post.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\">$l_new_post</a>
					&gt;
					<a href=\"my_blog_new_post_meta.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\">$l_meta</a>
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

				<!-- Form Buttons (Navigation) -->
					<form method=\"post\" action=\"my_blog_new_post_meta.php?blog_post_id=$get_current_blog_post_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				
					<p>
					<input type=\"submit\" value=\"$l_text\" name=\"inp_submit\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<input type=\"submit\" value=\"$l_meta\" name=\"inp_submit\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"font-weight: bold;\" />
					<input type=\"submit\" value=\"$l_main_image\" name=\"inp_submit\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<input type=\"submit\" value=\"$l_images\" name=\"inp_submit\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<input type=\"submit\" value=\"$l_view_post\" name=\"inp_submit\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />

					</p>
				<!-- //Form Buttons (Navigation) -->
			

				<!-- Meta form -->
		
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_introduction\"]').focus();
					});
					</script>
		
					<p><b>$l_introduction:</b><br />
					<textarea name=\"inp_introduction\" rows=\"5\" cols=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
					$get_current_blog_post_introduction = str_replace("<br />", "\n", $get_current_blog_post_introduction);
					echo"$get_current_blog_post_introduction</textarea>
					</p>


					<p><b>$l_category:</b><br />
					<select name=\"inp_category\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />\n";
					$query = "SELECT blog_category_id, blog_category_title, blog_category_posts FROM $t_blog_categories WHERE blog_category_user_id=$my_user_id_mysql AND blog_category_language=$l_mysql";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_blog_category_id, $get_blog_category_title, $get_blog_category_posts) = $row;
						echo"			";
						echo"<option value=\"$get_blog_category_id\""; if($get_blog_category_id == "$get_current_blog_post_category_id"){ echo" selected=\"selected\""; } echo">$get_blog_category_title</option>\n";
					}
					echo"
					</select>
					</p>
		
					<p><b>$l_who_can_see_this_post</b><br />
					<select name=\"inp_privacy_level\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						<option value=\"everyone\""; if($get_current_blog_post_privacy_level == "everyone"){ echo" selected=\"selected\""; } echo">$l_everyone</option>
						<option value=\"friends\""; if($get_current_blog_post_privacy_level == "friends"){ echo" selected=\"selected\""; } echo">$l_friends</option>
						<option value=\"private\""; if($get_current_blog_post_privacy_level == "private"){ echo" selected=\"selected\""; } echo">$l_private</option>
					</select>

					
		
					<p><b>$l_mark_as_ad:</b><br />
					<input type=\"checkbox\" name=\"inp_ad\""; if($get_current_blog_post_ad == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					$l_yes
					</p>

					<p><b>$l_status:</b><br />
					<input type=\"radio\" name=\"inp_status\" value=\"published\" "; if($get_current_blog_post_status == "published"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					$l_published
					&nbsp;
					<input type=\"radio\" name=\"inp_status\" value=\"draft\" "; if($get_current_blog_post_status == "draft"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					$l_draft
					</p>


					<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					</form>
				<!-- //Meta form -->
				";
			}  // action == ""
		} // blog info found
	} // can post (access)
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>