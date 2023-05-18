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


/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");

/*- Title ------------------------------------------------------------------------------------ */
$query_t = "SELECT title_id, title_value FROM $t_blog_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_value) = $row_t;


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


/*- Functions ------------------------------------------------------------------------ */
include("_bbcode/BBCode.php");
include("_bbcode/Tag.php");



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['post_id'])){
	$post_id = $_GET['post_id'];
	$post_id = output_html($post_id);
}
else{
	$post_id = "";
}

// Get post
$post_id_mysql = quote_smart($link, $post_id);
$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, blog_post_views, blog_post_views_ipblock, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$post_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_title_pre, $get_current_blog_post_title, $get_current_blog_post_language, $get_current_blog_post_status, $get_current_blog_post_category_id, $get_current_blog_post_category_title, $get_current_blog_post_introduction, $get_current_blog_post_privacy_level, $get_current_blog_post_text, $get_current_blog_post_image_path, $get_current_blog_post_image_thumb_small, $get_current_blog_post_image_thumb_medium, $get_current_blog_post_image_thumb_large, $get_current_blog_post_image_file, $get_current_blog_post_image_ext, $get_current_blog_post_image_text, $get_current_blog_post_ad, $get_current_blog_post_created, $get_current_blog_post_created_rss, $get_current_blog_post_updated, $get_current_blog_post_updated_rss, $get_current_blog_post_allow_comments, $get_current_blog_post_comments, $get_current_blog_post_views, $get_current_blog_post_views_ipblock, $get_current_blog_post_user_ip) = $row;

if($get_current_blog_post_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_title_value - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Blog post not found.</p>";
	
}
else{
	// Get blog
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock, blog_new_comments_email_warning, blog_unsubscribe_password FROM $t_blog_info WHERE blog_user_id=$get_current_blog_post_user_id AND blog_language='$get_current_blog_post_language'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_views_ipblock, $get_current_blog_new_comments_email_warning, $get_current_blog_unsubscribe_password) = $row;
	
	if($get_current_blog_info_id == ""){

		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_title_value - 404";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"<p>Blog not found.</p>";
	}
	else{


		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_blog_post_title - $get_current_blog_title - $get_current_title_value";
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

			$query = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_60) = $row;
		}

		// My IP (used for views, write comments, vote)
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);



		// Privacy?
		if($get_current_blog_post_privacy_level == "everyone"){
			$show_post = "true";
		}
		else{
			if($get_current_blog_post_privacy_level == "private" && isset($my_user_id) && $my_user_id == "$get_current_blog_post_user_id"){
				$show_post = "true";
			}
			else{
				if($get_current_blog_post_privacy_level == "friends" && isset($my_user_id)){
					if($my_user_id == "$get_current_blog_post_user_id"){
						$show_post = "true";
					}
					else{
						// Are we friends? (me = a, post author = b)
						$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b FROM $t_users_friends WHERE friend_user_id_a=$get_my_user_id AND friend_user_id_b=$get_current_blog_post_user_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_friend_id, $get_friend_user_id_a, $get_friend_user_id_b) = $row;

						if($get_friend_id == ""){
							$show_post = "false";

							// Are we friends? (me = b, post author = a)
							$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b FROM $t_users_friends WHERE friend_user_id_a=$get_current_blog_post_user_id AND friend_user_id_b=$get_my_user_id";
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


		if($show_post == "true"){		

			// Unique hits blog
			$inp_date = date("ymd");

			$ip_block_array = explode("\n", $get_current_blog_views_ipblock);
			$ip_block_array_size = sizeof($ip_block_array);
	
			if($ip_block_array_size > 30){
				$ip_block_array_size = 20;
			}
	
			$has_seen_this_before = 0;

			for($x=0;$x<$ip_block_array_size;$x++){
				if($ip_block_array[$x] == "$my_ip$inp_date"){
					$has_seen_this_before = 1;
					break;
				}
			}
		
			if($has_seen_this_before == 0){
				$ip_block = $my_ip.$inp_date . "\n" . $get_current_blog_views_ipblock;
				$ip_block = substr($ip_block, 0, 200);
				$ip_block_mysql = quote_smart($link, $ip_block);
				$inp_unique_hits = $get_current_blog_views + 1;
				$result = mysqli_query($link, "UPDATE $t_blog_info SET blog_views=$inp_unique_hits, blog_views_ipblock=$ip_block_mysql WHERE blog_info_id=$get_current_blog_info_id") or die(mysqli_error($link));
			}




			// Get current categroy
			if($get_current_blog_post_category_id != "" && $get_current_blog_post_category_id != "0"){
				$query = "SELECT blog_category_id, blog_category_title FROM $t_blog_categories WHERE blog_category_id=$get_current_blog_post_category_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_blog_category_id, $get_current_blog_category_title) = $row;
			}
	
			// Get current user
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$get_current_blog_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_rank) = $row;
		
			// Author image
			$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_current_blog_user_id' AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_photo_id, $get_current_photo_destination, $get_current_photo_thumb_40, $get_current_photo_thumb_50, $get_current_photo_thumb_60, $get_current_photo_thumb_200) = $row;

			// Date
			$year = substr($get_current_blog_post_updated, 0, 4);
			$month = substr($get_current_blog_post_updated, 5, 2);
			$day = substr($get_current_blog_post_updated, 8, 2);

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


			// Unique hits post

			$ip_block_array = explode("\n", $get_current_blog_post_views_ipblock);
			$ip_block_array_size = sizeof($ip_block_array);

			if($ip_block_array_size > 30){
				$ip_block_array_size = 20;
			}
	
			$has_seen_this_before = 0;

			for($x=0;$x<$ip_block_array_size;$x++){
				if($ip_block_array[$x] == "$my_ip"){
					$has_seen_this_before = 1;
					break;
				}
			}
	
			if($has_seen_this_before == 0){
				$ip_block = $my_ip . "\n" . $get_current_blog_post_views_ipblock;
				$ip_block = substr($ip_block, 0, 200);
				$ip_block_mysql = quote_smart($link, $ip_block);
				$inp_unique_hits = $get_current_blog_post_views + 1;
				$result = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_views=$inp_unique_hits, blog_post_views_ipblock=$ip_block_mysql WHERE blog_post_id=$post_id_mysql") or die(mysqli_error($link));
			}


			// Text
			if($blogEditModeSav == "bbcode"){
				$bbcode = new ChrisKonnertz\BBCode\BBCode();
				$get_current_blog_post_text = $bbcode->render($get_current_blog_post_text);
			}

			// Post
			if($process != "1"){
				echo"
				<!-- Headline left + headline right -->
				<div class=\"headline_left\">
					<h1>$get_current_blog_post_title</h1>
				</div>
				<div class=\"headline_right\">
					<p>
					<a href=\"$root/blog/my_blog.php?l=$l\" class=\"btn_default\">$l_my_blog</a>
					<a href=\"$root/blog/my_blog_new_post.php?l=$l\" class=\"btn_default\">$l_new_post</a>\n";
					if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == "$get_current_blog_user_id"){
						echo"					<a href=\"$root/blog/my_blog_edit_post.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_edit</a>\n";
						echo"					<a href=\"$root/blog/my_blog_delete_post.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_delete</a>\n";
					}
					echo"
					</p>
				</div>
				<!-- //Headline left + headline right -->

				<!-- Where am I? -->
					<div class=\"clear\"></div>
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$get_current_title_value</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\">$get_current_blog_title</a>
					&gt;
					<a href=\"view_post.php?post_id=$get_current_blog_post_id&amp;l=$l\">$get_current_blog_post_title</a>
					</p>
				<!-- //Where am I? -->


				<!-- Existing image? -->
					";
					if($get_current_blog_post_image_file != "" && file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file")){
						// 950 x 640
						echo"
						<figure class=\"view_post_image\">
							<img src=\"$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file\" alt=\"$get_current_blog_post_image_file\" /><br />
							<figcaption>$get_current_blog_post_image_text</figcaption>
						</figure>
						";
					}
					echo"
				<!-- //Existing image? -->


				<!-- Author and metadata -->
				<div class=\"view_post_metadata_left\">
					<table>
					 <tr>
					  <td style=\"padding-right: 10px;\">
						<p class=\"grey\">$l_by</p>
					  </td>
					<!-- Img -->";
					if(file_exists("$root/_uploads/users/images/$get_current_blog_user_id/$get_current_photo_destination") && $get_current_photo_destination != ""){

					if(!(file_exists("$root/_uploads/users/images/$get_current_blog_user_id/$get_current_photo_thumb_40"))){
						$inp_new_x = 40; // 950
						$inp_new_y = 40; // 640
						resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_current_blog_user_id/$get_current_photo_destination", "$root/_uploads/users/images/$get_current_blog_user_id/$get_current_photo_thumb_40");
						
					}

					echo"
					  <td style=\"padding-right: 10px;\">
						<a href=\"$root/users/view_profile.php?user_id=$get_current_blog_user_id&amp;l=$l\"><img src=\"$root/_uploads/users/images/$get_current_blog_user_id/$get_current_photo_thumb_40\" alt=\"$get_current_photo_thumb_40\" class=\"by_author_img\" /></a>
					  </td>\n";
					}
					echo"
					<!-- //Img -->
					  <td>
						<p>
						<a href=\"$root/users/view_profile.php?user_id=$get_current_blog_user_id&amp;l=$l\">$get_current_user_alias</a>
						</p>
					  </td>
					 </tr>
					</table>
				</div>
				<div class=\"view_post_metadata_right\">
	
					<!-- Metadata -->
						<p class=\"grey\">
						$day $month_saying $year
						";
						if($get_current_blog_post_ad == "1"){
							echo"$l_this_post_has_ad_links";
						}
						echo"
						 &middot; <a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\" class=\"grey\">$get_current_blog_title</a>
						";
						echo"
						</p>
					<!-- //Metadata -->
				</div>
				<div class=\"clear\"></div>
				<hr />
				<!-- //Author and metadata -->

				<!-- Ad -->
					";
					include("$root/ad/_includes/ad_main_below_headline.php");
					echo"
				<!-- //Ad -->


				<!-- Text -->	
					$get_current_blog_post_text
				<!-- //Text -->
				";

			} // process != 1

			// New comment and read comments
			if($process != "1"){
				echo"
				<!-- Comments -->
					<a id=\"comments\"></a>

					<!-- Feedback -->
						";
						if(isset($_GET['ft_comment']) && isset($_GET['fm_comment'])){
							$ft_comment = $_GET['ft_comment'];
							$ft_comment = output_html($ft_comment);
							$fm_comment = $_GET['fm_comment'];
							$fm_comment = output_html($fm_comment);
							$fm_comment = str_replace("_", " ", $fm_comment);
							$fm_comment = ucfirst($fm_comment);
							echo"<div class=\"$ft_comment\"><span>$fm_comment</span></div>";
						}
						echo"	
					<!-- //Feedback -->

				";
			}
				if($get_current_blog_post_allow_comments == "1"){
					include("view_post_include_new_comment.php");
					include("view_post_include_fetch_comments.php");
				}
				echo"
			<!-- //Comments -->
	

			<!-- Other posts from same category -->
				<hr />
				<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_more_from <a href=\"view_category.php?category_id=$get_current_blog_category_id&amp;l=$l\" class=\"h2\">$get_current_blog_category_title</a></h2>

				";
				$x = 0;
				$query_w = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_category_id, blog_post_privacy_level, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_ad, blog_post_updated, blog_post_comments FROM $t_blog_posts WHERE blog_post_category_id=$get_current_blog_category_id ORDER BY blog_post_id DESC LIMIT 0,3";
				$result_w = mysqli_query($link, $query_w);
				while($row_w = mysqli_fetch_row($result_w)) {
					list($get_blog_post_id, $get_blog_post_user_id, $get_blog_post_title, $get_blog_post_category_id, $get_blog_post_privacy_level, $get_blog_post_image_path, $get_blog_post_image_thumb_small, $get_blog_post_image_thumb_medium, $get_blog_post_image_thumb_large, $get_blog_post_image_file, $get_blog_post_ad, $get_blog_post_updated, $get_blog_post_comments) = $row_w;

					if($x < 2 && $get_blog_post_id != "$get_current_blog_post_id"){
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
							</div>
							";

						
							$x++;
						} // can show

					} // x == 0 OR x == 1 + post not same as beeing viewed

				} // loop
				if($x == "2"){
					echo"
					<div class=\"clear\"></div>
					";
				}
				echo"
			<!-- //Other posts from same category -->

			";
		} // show post == true

	} // blog foud
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>