<?php 
/**
*
* File: blog/my_blog_entries.php
* Version 1.0.0
* Date 21:04 13.03.2019
* Copyright (c) 2019 S. A. Ditlefsen
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

/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_entries - $l_my_blog - $l_blog";
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

		echo"
		<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?l=$l\">
	

		<p>$l_creating_your_blog</p>
		";
	}
	else{
		echo"
		<h1>$l_my_blog</h1>
	
		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_blog</a>
			&gt;
			<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
			&gt;
			<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
			&gt;
			<a href=\"my_blog_entries.php?l=$l\">$l_entries</a>
			</p>
		<!-- Where am I ? -->

		<!-- Actions -->
			<p>
			<a href=\"my_blog_new_post.php?l=$l\" class=\"btn_default\">$l_new_post</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Actions -->

		<!-- My posts -->

			";


			$query = "SELECT blog_post_id, blog_post_title, blog_post_category_id, blog_post_introduction, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_updated, blog_post_comments, blog_post_views FROM $t_blog_posts WHERE blog_post_user_id=$my_user_id_mysql AND blog_post_language=$l_mysql ORDER BY blog_post_id DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_blog_post_id, $get_blog_post_title, $get_blog_post_category_id, $get_blog_post_introduction, $get_blog_post_image_path, $get_blog_post_image_thumb_small, $get_blog_post_image_thumb_medium, $get_blog_post_image_thumb_large, $get_blog_post_image_file, $get_blog_post_image_ext, $get_blog_post_updated, $get_blog_post_comments, $get_blog_post_views) = $row;


				if(isset($style) && $style == "bodycell"){
					$style = "subcell";
				}
				else{
					$style = "bodycell";
				}

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
	

			
				if($get_blog_post_image_file != "" && file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_file")){

					// Thumb small
					if(!(file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_thumb_small")) OR $get_blog_post_image_thumb_small == ""){
						// Thumb name
						$extension = get_extension($get_blog_post_image_file);
						$extension = strtolower($extension);

						$thumb_name = $get_blog_post_id . "_thumb_" . $blogPostsThumbSmallSizeXSav . "x" . $blogPostsThumbSmallSizeYSav . "." . $extension;
						$thumb_name_mysql = quote_smart($link, $thumb_name);
						// echo"$blogPostsThumbSmallSizeXSav, $blogPostsThumbSmallSizeYSav, $root/$get_blog_post_image_path/$get_blog_post_image_file, $root/$get_blog_post_image_path/$thumb_name<br />";
						resize_crop_image($blogPostsThumbSmallSizeXSav, $blogPostsThumbSmallSizeYSav, "$root/$get_blog_post_image_path/$get_blog_post_image_file", "$root/$get_blog_post_image_path/$thumb_name");

						$result_update = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_thumb_small=$thumb_name_mysql WHERE blog_post_id=$get_blog_post_id") or die(mysqli_error($link));

					}

					// Thumb medium
					if(!(file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_thumb_medium")) OR $get_blog_post_image_thumb_medium == ""){
						// Thumb name
						$extension = get_extension($get_blog_post_image_file);
						$extension = strtolower($extension);
				
						$thumb_name = $get_blog_post_id . "_thumb_" . $blogPostsThumbMediumSizeXSav . "x" . $blogPostsThumbMediumSizeYSav . "." . $extension;
						$thumb_name_mysql = quote_smart($link, $thumb_name);
						// echo"resize_crop_image($blogPostsThumbMediumSizeXSav, $blogPostsThumbMediumSizeYSav, $root/$get_blog_post_image_path/$get_blog_post_image_file, $root/$get_blog_post_image_path/$thumb_name);<br />";
						resize_crop_image($blogPostsThumbMediumSizeXSav, $blogPostsThumbMediumSizeYSav, "$root/$get_blog_post_image_path/$get_blog_post_image_file", "$root/$get_blog_post_image_path/$thumb_name");

						$result_update = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_thumb_medium=$thumb_name_mysql WHERE blog_post_id=$get_blog_post_id") or die(mysqli_error($link));

					}

					// Thumb large
					if(!(file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_thumb_large")) OR $get_blog_post_image_thumb_large == ""){
						// Thumb name
						$extension = get_extension($get_blog_post_image_file);
						$extension = strtolower($extension);
				
						$thumb_name = $get_blog_post_id . "_thumb_" . $blogPostsThumbLargeSizeXSav . "x" . $blogPostsThumbLargeSizeYSav . "." . $extension;
						$thumb_name_mysql = quote_smart($link, $thumb_name);
						// echo"resize_crop_image($blogPostsThumbLargeSizeXSav, $blogPostsThumbLargeSizeYSav, $root/$get_blog_post_image_path/$get_blog_post_image_file, $root/$get_blog_post_image_path/$thumb_name);<br />";
						resize_crop_image($blogPostsThumbLargeSizeXSav, $blogPostsThumbLargeSizeYSav, "$root/$get_blog_post_image_path/$get_blog_post_image_file", "$root/$get_blog_post_image_path/$thumb_name");

						$result_update = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_thumb_large=$thumb_name_mysql WHERE blog_post_id=$get_blog_post_id") or die(mysqli_error($link));

					}
				} // img exists


				// Intro
				$get_blog_post_introduction = str_replace("&lt;p&gt;", "", $get_blog_post_introduction);
				$get_blog_post_introduction = str_replace("&lt;/p&gt;", "", $get_blog_post_introduction);
				$get_blog_post_introduction_len = strlen($get_blog_post_introduction);
				if($get_blog_post_introduction_len > 240){
					$get_blog_post_introduction = substr($get_blog_post_introduction, 0, 235);
					$get_blog_post_introduction = $get_blog_post_introduction . "...";
				}

				echo"
				<div class=\"my_blog_entries_$style\">
					<table style=\"width: 100%;\">
					 <tr>
					  <td class=\"my_blog_entries_img\" style=\"width: 100px\">
						
						<p>";
						if($get_blog_post_image_file != "" && file_exists("$root/$get_blog_post_image_path/$get_blog_post_image_file")){

							// 950 x 640
							// 
							echo"
							<a href=\"view_post.php?post_id=$get_blog_post_id&amp;l=$l\"><img src=\"$root/$get_blog_post_image_path/$get_blog_post_image_thumb_medium\" alt=\"$get_blog_post_image_file\" /></a>
							";
						}
						echo"
						</p>
					  </td>
					  <td class=\"my_blog_entries_text\">
						<table style=\"width: 100%;\">
						 <tr>
						  <td>
							<p style=\"padding-bottom: 0;margin-bottom:0;\">
							<a href=\"view_post.php?post_id=$get_blog_post_id&amp;l=$l\">$get_blog_post_title</a><br />
							$get_blog_post_introduction
							</p>
						  </td>
						  <td style=\"text-align: right;\">
							<p style=\"padding-bottom: 0;margin-bottom:0;\">
							<a href=\"my_blog_edit_post.php?post_id=$get_blog_post_id&amp;l=$l\" title=\"$l_edit\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" /></a>
							<a href=\"my_blog_delete_post.php?post_id=$get_blog_post_id&amp;l=$l\" title=\"$l_delete\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" /></a>
							</p>
						  </td>
						 </tr>
						</table>
						
						<p>
						<img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye_dark_grey.png\" /> $get_blog_post_views
						&nbsp; &nbsp;
						<img src=\"_gfx/icons/discuss.png\" alt=\"discuss.png\" /> $get_blog_post_comments
						</p>
					  </td>
					 </tr>
					</table>	
				</div>
				";
			}
			echo"
		<!-- //My posts -->
		";
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>