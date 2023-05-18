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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

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
$website_title = "$l_my_blog - $l_blog";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && isset($_GET['post_id'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get blog info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_user_ip) = $row;

	if($get_blog_info_id == ""){
		echo"
		<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?l=$l\">
		<p>$l_creating_your_blog</p>
		";
	}
	else{
		// Get post
		$post_id = $_GET['post_id'];
		$post_id = strip_tags(stripslashes($post_id));
		$post_id_mysql = quote_smart($link, $post_id);
			
		$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_language, blog_post_category_id, blog_post_introduction, blog_post_text, blog_post_image_path, blog_post_image_file, blog_post_ad, blog_post_created, blog_post_updated, blog_post_comments, blog_post_views, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$post_id_mysql AND blog_post_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_blog_post_id, $get_blog_post_user_id, $get_blog_post_title, $get_blog_post_language, $get_blog_post_category_id, $get_blog_post_introduction, $get_blog_post_text, $get_blog_post_image_path, $get_blog_post_image_file, $get_blog_post_ad, $get_blog_post_created, $get_blog_post_updated, $get_blog_post_comments, $get_blog_post_views, $get_blog_post_user_ip) = $row;
			
		if($get_blog_post_id == ""){
			echo"<p>Post not found.</p>";
		}
		else{
			if($process == "1"){

				
				
				// Delete
				$result = mysqli_query($link, "DELETE FROM $t_blog_posts  WHERE blog_post_id=$get_blog_post_id");

				// Image	
				if($get_blog_post_image_file != "" && file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_file")){
					unlink("$root/$get_blog_post_image_path/$get_blog_post_image_file");
				}

				
				
				
				$url = "my_blog.php?post_id=$get_blog_post_id&l=$l&ft=success&fm=post_deleted";
				header("Location: $url");
				exit;

			}
			echo"
			<h1>$get_blog_post_title</h1>

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"view_post.php?post_id=$get_blog_post_id&amp;l=$l\">$get_blog_post_title</a>
				&gt;
				<a href=\"my_blog_delete_post.php?post_id=$post_id&amp;l=$l\">$l_delete</a>
				</p>
			<!-- //Where am I ? -->


			<h2>$l_delete</h2>
				
			<p>$l_are_you_sure</p>

			<p>
			<a href=\"my_blog_delete_post.php?post_id=$post_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
			</p>
			";
		}
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>