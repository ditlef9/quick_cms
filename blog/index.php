<?php
/**
*
* File: blog/index.php
* Version 2.0.0
* Date 21:57 04.02.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
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


/*- Translations -------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


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

/*- Title ------------------------------------------------------------------------------------ */
$query_t = "SELECT title_id, title_value FROM $t_blog_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_value) = $row_t;



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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
}


echo"
<!-- Headline, menu, rss -->
	<div class=\"blog_headline_left\">
		<h1>$get_current_title_value</h1>
	</div>
	<div class=\"blog_headline_center\">
		<p>
		<a href=\"$root/blog/my_blog.php?l=$l\" class=\"btn_default\">$l_my_blog</a>
		<a href=\"$root/blog/my_blog_new_post.php?l=$l\" class=\"btn_default\">$l_new_post</a>
		</p>
	</div>
	<div class=\"blog_headline_right\">
		<p>
		<a href=\"rss_generate_all_blogs.php?l=$l\"><img src=\"_gfx/icons_24/rss_24.png\" alt=\"icons_24\" /></a>
		</p>
	</div>
	<div class=\"clear\"></div>
<!-- //Headline, menu, rss -->


<!-- 1: 1 big on left, 2 small on right -->
	";
	$limit = "0,3";
	include("_index.php/a_1_big_on_left__2_small_on_right.php");
	echo"
<!-- //1: 1 big on left, 2 small on right -->


<!-- 2: 2 small on left, 1 big on right -->
	";
	$limit = "3,3";
	include("_index.php/b_2_small_on_left__1_big_on_right.php");
	echo"
<!-- //2: 2 small on left, 1 big on right -->


<!-- 3: 3 small -->
	";
	$limit = "6,3";
	include("_index.php/c_3_small.php");
	echo"
<!-- //3: 3 small -->



<!-- 4: 1 special on left, 2 small on right -->
	";
	$limit = "9,3";
	include("_index.php/d_1_special_on_left__2_small_on_right.php");
	echo"
<!-- //4: 1 special on left, 2 small on right -->


<!-- 5: 2 small -->
	";
	$limit = "12,2";
	include("_index.php/e_2_small.php");
	echo"
<!-- //5: 2 small -->


<!-- 6: 2 small -->
	";
	$limit = "14,2";
	include("_index.php/e_2_small.php");
	echo"
<!--// 6: 2 small -->

	<div style=\"height:20px;\"></div>

<!-- 7: 1 big on left, 2 small on right -->
	";
	$limit = "17,3";
	include("_index.php/a_1_big_on_left__2_small_on_right.php");
	echo"
<!-- //7: 1 big on left, 2 small on right -->

<!-- Paging -->
<!-- //Paging -->
";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>