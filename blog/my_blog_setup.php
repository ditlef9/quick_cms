<?php 
/**
*
* File: blog/my_blog_setup.php
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
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_blog_info 		= $mysqlPrefixSav . "blog_info";
$t_blog_categories	= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 		= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 	= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_images 		= $mysqlPrefixSav . "blog_images";
$t_blog_logos		= $mysqlPrefixSav . "blog_logos";

$t_blog_links_index		= $mysqlPrefixSav . "blog_links_index";
$t_blog_links_categories	= $mysqlPrefixSav . "blog_links_categories";

$t_blog_ping_list_per_blog	= $mysqlPrefixSav . "blog_ping_list_per_blog";


$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";

$t_blog_default_categories = $mysqlPrefixSav . "blog_default_categories";


/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_setup - $l_my_blog - $l_blog";
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
		
		$inp_blog_title = "$get_user_name$l_names_blog";
		$inp_blog_title = output_html($inp_blog_title);
		$inp_blog_title_mysql = quote_smart($link, $inp_blog_title);

		$datetime = date("Y-m-d H:i:s");
		$datetime_rss = date("D, d M Y H:i:s T");

		$inp_user_ip = $_SERVER['REMOTE_ADDR'];
		$inp_user_ip = output_html($inp_user_ip);
		$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

		$inp_unsubscribe_password = uniqid();
		$inp_unsubscribe_password = output_html($inp_unsubscribe_password);
		$inp_unsubscribe_password_mysql = quote_smart($link, $inp_unsubscribe_password);


		mysqli_query($link, "INSERT INTO $t_blog_info
		(blog_info_id, blog_user_id, blog_language, blog_title, blog_created, blog_updated, blog_updated_rss, blog_posts, blog_comments, blog_views, blog_user_ip, blog_new_comments_email_warning, blog_unsubscribe_password) 
		VALUES 
		(NULL, $my_user_id_mysql, $l_mysql, $inp_blog_title_mysql, '$datetime', '$datetime', '$datetime_rss', '0', '0', '0', $inp_user_ip_mysql, 1, $inp_unsubscribe_password_mysql)
		")
		or die(mysqli_error($link));
	}

	// Do I have categories?
	$query = "SELECT blog_category_id FROM $t_blog_categories WHERE blog_category_user_id=$my_user_id_mysql AND blog_category_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_category_id) = $row;

	if($get_blog_category_id == ""){
		


		$inp_blog_category_title = "$l_blog";
		$inp_blog_category_title = output_html($inp_blog_category_title);
		$inp_blog_category_title_mysql = quote_smart($link, $inp_blog_category_title);

		$datetime = date("Y-m-d H:i:s");

		$inp_user_ip = $_SERVER['REMOTE_ADDR'];
		$inp_user_ip = output_html($inp_user_ip);
		$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

		mysqli_query($link, "INSERT INTO $t_blog_categories
		(blog_category_id, blog_category_user_id, blog_category_language, blog_category_title, blog_category_posts) 
		VALUES 
		(NULL, $my_user_id_mysql, $l_mysql, $inp_blog_category_title_mysql, '0')
		")
		or die(mysqli_error($link));


		// Also insert default categories
		$query = "SELECT default_category_id, default_category_title FROM $t_blog_default_categories WHERE default_category_language=$l_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_default_category_id, $get_default_category_title) = $row;
			
			$inp_blog_category_title_mysql = quote_smart($link, $get_default_category_title);

			mysqli_query($link, "INSERT INTO $t_blog_categories
			(blog_category_id, blog_category_user_id, blog_category_language, blog_category_title, blog_category_posts) 
			VALUES 
			(NULL, $my_user_id_mysql, $l_mysql, $inp_blog_category_title_mysql, '0')
			")
			or die(mysqli_error($link));

		}

	}

	if(isset($_GET['reference'])){
		$url = "$root/blog/my_blog_new_post.php?l=$l";
	}
	else{
		$url = "$root/blog/my_blog.php?l=$l";
	}

	echo"
	<h1>
	<img src=\"_gfx/loading.gif\" alt=\"loading.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$url\">

	<p>$l_setup_is_running</p>
	";

}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading.gif\" alt=\"loading.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>