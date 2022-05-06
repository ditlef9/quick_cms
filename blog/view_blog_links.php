<?php
/**
*
* File: blog/view_blog_links.php
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
if(isset($_GET['link_id'])) {
	$link_id = $_GET['link_id'];
	$link_id = strip_tags(stripslashes($link_id));
}
else{
	$link_id = "";
}


// Get blog
$info_id_mysql = quote_smart($link, $info_id);
$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock FROM $t_blog_info WHERE blog_info_id=$info_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_views_ipblock) = $row;
	
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
	$website_title = "$l_links - $get_current_blog_title - $l_blog";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	if($action == ""){
		echo"
		<!-- Headline and language -->
			<div style=\"float: right;padding-top: 10px;\">
				<p style=\"padding:0px 0px 0px px;margin:0px 0px 0px 0px;\">
				<a href=\"rss_generate_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\"><img src=\"_gfx/icons_24/rss_24.png\" alt=\"icons_24\" /></a>
				</p>
			</div>

			<h1>$get_current_blog_title</h1>

		<!-- //Headline and language -->


		<!-- Where am I ? + RSS Icons -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\">$get_current_blog_title</a>
			&gt;
			<a href=\"view_blog_links.php?info_id=$get_current_blog_info_id&amp;l=$l\">$l_links</a>
			</p>
		<!-- //Where am I ? + RSS Icons -->


		<!-- Blog navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\">$l_blog</a></li>
					<li><a href=\"view_blog_links.php?info_id=$get_current_blog_info_id&amp;l=$l\" class=\"selected\">$l_links</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Blog navigation -->


					
		<!-- Categories and links -->

			";

			$query = "SELECT category_id, category_blog_info_id, category_user_id, category_title FROM $t_blog_links_categories WHERE category_blog_info_id=$get_current_blog_info_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_blog_info_id, $get_category_user_id, $get_category_title) = $row;
			
				echo"
				<h2>$get_category_title</h2>
				";

				// Links
				$query_links = "SELECT link_id, link_blog_info_id, link_user_id, link_category_id, link_title, link_url_real, link_url_display, link_description, link_is_ad, link_clicks_unique, link_clicks_unique_ipblock, link_added, link_edited FROM $t_blog_links_index WHERE link_blog_info_id=$get_current_blog_info_id AND link_category_id=$get_category_id";
				$result_links = mysqli_query($link, $query_links);
				while($row_links = mysqli_fetch_row($result_links)) {
					list($get_link_id, $get_link_blog_info_id, $get_link_user_id, $get_link_category_id, $get_link_title, $get_link_url_real, $get_link_url_display, $get_link_description, $get_link_is_ad, $get_link_clicks_unique, $get_link_clicks_unique_ipblock, $get_link_added, $get_link_edited) = $row_links;

					echo"
					<p class=\"link\"><a href=\"view_blog_links.php?action=visit&amp;info_id=$get_current_blog_info_id&amp;link_id=$get_link_id&amp;l=$l&amp;process=1\" class=\"link_title_a\">$get_link_title</a><br />
					";
					if($get_link_is_ad == "1"){
						echo"<a href=\"view_blog_links.php?action=visit&amp;info_id=$get_current_blog_info_id&amp;link_id=$get_link_id&amp;l=$l&amp;process=1\" class=\"link_url_ad_a\">$l_ad</a>";
					}
					echo"
					<a href=\"view_blog_links.php?action=visit&amp;info_id=$get_current_blog_info_id&amp;link_id=$get_link_id&amp;l=$l&amp;process=1\" class=\"link_url_a\">$get_link_url_display</a></p>
					<p class=\"link_description\">$get_link_description</p>
					";
				}
			}
			echo"
		<!-- //Categories and links -->

		";
	} // action == ""
	elseif($action == "visit"){
		$link_id_mysql = quote_smart($link, $link_id);
		$query = "SELECT link_id, link_blog_info_id, link_user_id, link_category_id, link_title, link_url_real, link_url_display, link_description, link_clicks_unique, link_clicks_unique_ipblock, link_added, link_edited FROM $t_blog_links_index WHERE link_id=$link_id_mysql AND link_blog_info_id=$get_current_blog_info_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_link_id, $get_link_blog_info_id, $get_link_user_id, $get_link_category_id, $get_link_title, $get_link_url_real, $get_link_url_display, $get_link_description, $get_link_clicks_unique, $get_link_clicks_unique_ipblock, $get_link_added, $get_link_edited) = $row;
		if($get_link_id == ""){
			echo"<p>Link not found.</p>";
		}
		else{
			// Unique hits blog
			$inp_ip = $_SERVER['REMOTE_ADDR'];
			$inp_ip = output_html($inp_ip);
			$inp_date = date("ymd");

			$ip_block_array = explode("\n", $get_link_clicks_unique_ipblock);
			$ip_block_array_size = sizeof($ip_block_array);

			if($ip_block_array_size > 30){
				$ip_block_array_size = 20;
			}
	
			$has_seen_this_before = 0;
			for($x=0;$x<$ip_block_array_size;$x++){
				if($ip_block_array[$x] == "$inp_ip$inp_date"){
					$has_seen_this_before = 1;
					break;
				}
			}
	
			if($has_seen_this_before == 0){
				$ip_block = $inp_ip.$inp_date . "\n" . $get_link_clicks_unique_ipblock;
				$ip_block_mysql = quote_smart($link, $ip_block);
				$inp_unique_clicks = $get_link_clicks_unique + 1;
				$result = mysqli_query($link, "UPDATE $t_blog_links_index SET link_clicks_unique=$inp_unique_clicks, link_clicks_unique_ipblock=$ip_block_mysql WHERE link_id=$get_link_id") or die(mysqli_error($link));
			}




			header("Location: $get_link_url_real");
			exit;
		}
	} // action == "visit"

} // blog found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>