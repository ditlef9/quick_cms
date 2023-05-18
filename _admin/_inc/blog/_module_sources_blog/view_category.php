<?php
/**
*
* File: blog/view_category.php
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


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}


// Get category
$category_id_mysql = quote_smart($link, $category_id);
$query = "SELECT blog_category_id, blog_category_user_id, blog_category_language, blog_category_title, blog_category_posts FROM $t_blog_categories WHERE blog_category_id=$category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_blog_category_id, $get_current_blog_category_user_id, $get_current_blog_category_language, $get_current_blog_category_title, $get_current_blog_category_posts) = $row;

if($get_current_blog_category_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_blog - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Category not found.</p>";

}
else{
	// Get blog
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock FROM $t_blog_info WHERE blog_user_id=$get_current_blog_category_user_id AND blog_language='$get_current_blog_category_language'";
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
		$website_title = "$get_current_blog_category_title - $get_current_blog_title - $l_blog";
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



		// Unique hits blog
		$inp_ip = $_SERVER['REMOTE_ADDR'];
		$inp_ip = output_html($inp_ip);
		$inp_date = date("ymd");

		$ip_block_array = explode("\n", $get_current_blog_views_ipblock);
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
			$ip_block = $inp_ip.$inp_date . "\n" . $get_current_blog_views_ipblock;
			$ip_block_mysql = quote_smart($link, $ip_block);
			$inp_unique_hits = $get_current_blog_views + 1;
			$result = mysqli_query($link, "UPDATE $t_blog_info SET blog_views=$inp_unique_hits, blog_views_ipblock=$ip_block_mysql WHERE blog_info_id=$get_current_blog_info_id") or die(mysqli_error($link));
		}


		// Owners user ID
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$get_current_blog_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_rank) = $row;
			




		echo"
		<!-- Headline and language -->
			<h1>$get_current_blog_category_title</h1>
		<!-- //Headline and language -->


		<!-- Where am I ? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\">$get_current_blog_title</a>
			&gt;
			<a href=\"view_category.php?category_id=$category_id&amp;l=$l\">$get_current_blog_category_title</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Show last posts in this category -->
		";	
		$x = 0;
		$query_w = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_category_id, blog_post_privacy_level, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_ad, blog_post_updated, blog_post_comments FROM $t_blog_posts WHERE blog_post_category_id=$get_current_blog_category_id ORDER BY blog_post_id DESC";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_blog_post_id, $get_blog_post_user_id, $get_blog_post_title, $get_blog_post_category_id, $get_blog_post_privacy_level, $get_blog_post_image_path, $get_blog_post_image_thumb_small, $get_blog_post_image_thumb_medium, $get_blog_post_image_thumb_large, $get_blog_post_image_file, $get_blog_post_ad, $get_blog_post_updated, $get_blog_post_comments) = $row_w;


			// Intro
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


				// Date
				$year = substr($get_blog_post_updated, 0, 4);
				$month = substr($get_blog_post_updated, 5, 2);
				$day = substr($get_blog_post_updated, 8, 2);

				if($day < 10){
					$day = substr($day, 1, 1);
				}
	
				if($month == "01"){
					$month_saying = $l_january;
				}
				elseif($month == "02"){
					$month_saying = $l_february;
				}
				elseif($month == "03"){
					$month_saying = $l_march;
				}
				elseif($month == "04"){
					$month_saying = $l_april;
				}
				elseif($month == "05"){
					$month_saying = $l_may;
				}
				elseif($month == "06"){
					$month_saying = $l_june;
				}
				elseif($month == "07"){
					$month_saying = $l_july;
				}
				elseif($month == "08"){
					$month_saying = $l_august;
				}
				elseif($month == "09"){
					$month_saying = $l_september;
				}
				elseif($month == "10"){
					$month_saying = $l_october;
				}
				elseif($month == "11"){
					$month_saying = $l_november;
				}
				else{
					$month_saying = $l_december;
				}




				// Get current categroy
				if($get_blog_post_category_id != "0"){
					$query = "SELECT blog_category_id, blog_category_title FROM $t_blog_categories WHERE blog_category_id=$get_blog_post_category_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_blog_category_id, $get_blog_category_title) = $row;
				}
	


				// Thumb small
				if(!(file_exists("../$get_blog_post_image_path/$get_blog_post_image_thumb_small")) OR $get_blog_post_image_thumb_small == ""){
					// Thumb name
					$extension = get_extension($get_blog_post_image_file);
					$extension = strtolower($extension);

					$thumb_name = $get_blog_post_id . "_thumb_" . $blogPostsThumbSmallSizeXSav . "x" . $blogPostsThumbSmallSizeYSav . "." . $extension;
					$thumb_name_mysql = quote_smart($link, $thumb_name);
					resize_crop_image($blogPostsThumbSmallSizeXSav, $blogPostsThumbSmallSizeYSav, "$root/$get_blog_post_image_path/$get_blog_post_image_file", "$root/$get_blog_post_image_path/$thumb_name");

					$result_update = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_thumb_small=$thumb_name_mysql WHERE blog_post_id=$get_blog_post_id") or die(mysqli_error($link));

				}

				// Thumb medium
				if(!(file_exists("../$get_blog_post_image_path/$get_blog_post_image_thumb_medium")) OR $get_blog_post_image_thumb_medium == ""){
					// Thumb name
					$extension = get_extension($get_blog_post_image_file);
					$extension = strtolower($extension);
				
					$thumb_name = $get_blog_post_id . "_thumb_" . $blogPostsThumbMediumSizeXSav . "x" . $blogPostsThumbMediumSizeYSav . "." . $extension;
					$thumb_name_mysql = quote_smart($link, $thumb_name);
					resize_crop_image($blogPostsThumbMediumSizeXSav, $blogPostsThumbMediumSizeYSav, "$root/$get_blog_post_image_path/$get_blog_post_image_file", "$root/$get_blog_post_image_path/$thumb_name");

					$result_update = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_thumb_medium=$thumb_name_mysql WHERE blog_post_id=$get_blog_post_id") or die(mysqli_error($link));

				}

				// Thumb large
				if(!(file_exists("../$get_blog_post_image_path/$get_blog_post_image_thumb_large")) OR $get_blog_post_image_thumb_large == ""){
					// Thumb name
					$extension = get_extension($get_blog_post_image_file);
					$extension = strtolower($extension);
				
					$thumb_name = $get_blog_post_id . "_thumb_" . $blogPostsThumbLargeSizeXSav . "x" . $blogPostsThumbLargeSizeYSav . "." . $extension;
					$thumb_name_mysql = quote_smart($link, $thumb_name);
					resize_crop_image($blogPostsThumbLargeSizeXSav, $blogPostsThumbLargeSizeYSav, "$root/$get_blog_post_image_path/$get_blog_post_image_file", "$root/$get_blog_post_image_path/$thumb_name");

					$result_update = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_thumb_large=$thumb_name_mysql WHERE blog_post_id=$get_blog_post_id") or die(mysqli_error($link));

				}

				if($x == 0){
					echo"
					<div class=\"clear\"></div>
					<hr />
					<div class=\"left_right_left\">
					";
				}
				elseif($x == 1){
					echo"
					<div class=\"left_right_right\" style=\"float: right;\">
					";
				}

				echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\">
					<a href=\"view_post.php?post_id=$get_blog_post_id&amp;l=$l\"><img src=\"$root/$get_blog_post_image_path/$get_blog_post_image_thumb_medium\" alt=\"$get_blog_post_image_file\" /></a><br />
					<a href=\"view_post.php?post_id=$get_blog_post_id&amp;l=$l\" class=\"h2\">$get_blog_post_title</a><br />
					</p>

					<div class=\"view_posts_author\">
						<p class=\"grey_small\" style=\"padding: 4px 0px 0px 0px;margin:0;\">
						$l_by 
						<a href=\"users/index.php?page=view_profile&amp;user_id=$get_blog_post_user_id&amp;l=$l\" class=\"grey_small\">$get_current_user_alias</a>
						</p>
					</div>
					<div class=\"view_posts_date\">
						<p class=\"grey_small\" style=\"padding: 4px 0px 0px 0px;margin:0;\">
						$day
						$month_saying
						$year
						</p>
					</div>
					<div class=\"view_posts_comments\">
						<p class=\"grey_small\" style=\"padding: 4px 0px 0px 0px;margin:0;\">";
						if($get_blog_post_ad == "1"){
							echo"$l_ad &middot; ";
						}
						echo"
						<a href=\"view_post.php?post_id=$get_blog_post_id&amp;l=$l#comments\" class=\"grey_small\">$get_blog_post_comments
						$l_comments</a>
						</p>
					</div>
				</div>
				";

			
				if($x == 1){ 
					$x = -1;
				}
				$x++;
			} // can show
		} // loop
		echo"
		<!-- //Show last posts -->

		";
	} // blog found
} // category found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>