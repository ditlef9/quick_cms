<?php 
/**
*
* File: blog/view_post_include_fetch_comments.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($get_current_blog_post_id))){
	echo"error";
	die;
}

$count_comments = 0;
$query = "SELECT comment_id, comment_blog_post_id, comment_blog_info_id, comment_text, comment_by_user_id, comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, comment_likes, comment_dislikes, comment_read_blog_owner, comment_reported FROM $t_blog_posts_comments WHERE comment_blog_post_id=$get_current_blog_post_id";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_comment_id, $get_comment_blog_post_id, $get_comment_blog_info_id, $get_comment_text, $get_comment_by_user_id, $get_comment_by_user_name, $get_comment_by_user_image_path, $get_comment_by_user_image_file, $get_comment_by_user_image_thumb_60, $get_comment_by_user_ip, $get_comment_created, $get_comment_created_saying, $get_comment_created_timestamp, $get_comment_updated, $get_comment_updated_saying, $get_comment_likes, $get_comment_dislikes, $get_comment_read_blog_owner, $get_comment_reported) = $row;

	if($count_comments == "0"){
		echo"
		<a id=\"comments\"></a>
		<hr />
		<h2>$l_comments</h2>
		";
	}
	echo"
	<a id=\"comment$get_comment_id\"></a>
	<table>
	 <tr>
	  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
		<p>
		";
		if(file_exists("$root/$get_comment_by_user_image_path/$get_comment_by_user_image_thumb_60") && $get_comment_by_user_image_thumb_60 != ""){
			
			echo"
			<a href=\"users/view_profile.php?user_id=$get_comment_by_user_id&amp;l=$l\"><img src=\"$root/$get_comment_by_user_image_path/$get_comment_by_user_image_thumb_60\" alt=\"$get_comment_by_user_image_thumb_60\" /></a>
			<br />
			";
		}
		echo"
		
		</p>
	  </td>
	  <td style=\"vertical-align: top;\">
		<p>
		<a href=\"users/view_profile.php?user_id=$get_comment_by_user_id&amp;l=$l\" style=\"font-weight: bold;\">$get_comment_by_user_name</a> 
		$get_comment_created_saying<br />
		</p>

		<p>
		$get_comment_text
		</p>

		<!-- Like, dislike, reply, report spam + owner actions -->
			<table>
			 <tr>
			  <td style=\"padding-right: 10px;\">
				<p>
				";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					echo"<a href=\"comment_reply.php?comment_id=$get_comment_id&amp;l=$l\">$l_reply</a>\n";
				}
				else{
					echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/comment_reply.php?comment_id=$get_comment_id\">$l_reply</a>\n";
				}
				echo"
				</p>
			  </td>
			  <td style=\"padding-right: 10px;\">
				<p>
				";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					echo"<a href=\"comment_like.php?comment_id=$get_comment_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/likes.png\" alt=\"likes.png\" /></a>\n";
				}
				else{
					echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/comment_like.php?comment_id=$get_comment_id\"><img src=\"_gfx/icons/likes.png\" alt=\"likes.png\" /></a>\n";
				}
				echo"$get_comment_likes
				</p>
			  </td>
			  <td style=\"padding-right: 10px;\">
				<p>
				";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					echo"<a href=\"comment_dislike.php?comment_id=$get_comment_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/dislikes.png\" alt=\"dislikes.png\" /></a>\n";
				}
				else{
					echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/comment_dislike.php?comment_id=$get_comment_id\"><img src=\"_gfx/icons/dislikes.png\" alt=\"dislikes.png\" /></a>\n";
				}
				echo"$get_comment_dislikes
				</p>
			  </td>";
			if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
				$can_edit = 0;
				if($get_my_user_id == "$get_comment_by_user_id"){
					$can_edit = 1;
				}
				if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
					$can_edit = 1;
				}

				if($can_edit == "1"){
					echo"
					  <td style=\"padding-right: 10px;\">
						<p>
						<a href=\"comment_edit.php?comment_id=$get_comment_id&amp;l=$l\">$l_edit</a>
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p>
						<a href=\"comment_delete.php?comment_id=$get_comment_id&amp;l=$l\">$l_delete</a>
						</p>
					  </td>
					";
				}
			}
			echo"
			  <td style=\"padding-right: 10px;\">
				<p>
				";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					echo"<a href=\"comment_report.php?comment_id=$get_comment_id&amp;l=$l\">$l_report</a>\n";
				}
				else{
					echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/comment_report.php?comment_id=$get_comment_id\">$l_report</a>\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
		<!-- //Like, dislike, reply, report spam + owner actions -->
	  </td>
	 </tr>
	</table>
	";

	// Fetch replies
	$query_replies = "SELECT reply_id, reply_comment_id, reply_blog_post_id, reply_blog_info_id, reply_text, reply_by_user_id, reply_by_user_name, reply_by_user_image_path, reply_by_user_image_file, reply_by_user_image_thumb_60, reply_by_user_ip, reply_created, reply_created_saying, reply_created_timestamp, reply_updated, reply_updated_saying, reply_likes, reply_dislikes, reply_number_of_replies, reply_read_blog_owner, reply_reported FROM $t_blog_posts_comments_replies WHERE reply_comment_id=$get_comment_id";
	$result_replies = mysqli_query($link, $query_replies);
	while($row_replies = mysqli_fetch_row($result_replies)) {
		list($get_reply_id, $get_reply_comment_id, $get_reply_blog_post_id, $get_reply_blog_info_id, $get_reply_text, $get_reply_by_user_id, $get_reply_by_user_name, $get_reply_by_user_image_path, $get_reply_by_user_image_file, $get_reply_by_user_image_thumb_60, $get_reply_by_user_ip, $get_reply_created, $get_reply_created_saying, $get_reply_created_timestamp, $get_reply_updated, $get_reply_updated_saying, $get_reply_likes, $get_reply_dislikes, $get_reply_number_of_replies, $get_reply_read_blog_owner, $get_reply_reported) = $row_replies;


		echo"
		<a id=\"reply$get_reply_id\"></a>
		<table>
		 <tr>
		  <td style=\"vertical-align: top;padding: 0px 10px 0px 70px;text-align:center;\">
			<p>
			";
			if(file_exists("$root/$get_reply_by_user_image_path/$get_reply_by_user_image_thumb_60") && $get_reply_by_user_image_thumb_60 != ""){
				echo"
				<a href=\"users/view_profile.php?user_id=$get_reply_by_user_id&amp;l=$l\"><img src=\"$root/$get_reply_by_user_image_path/$get_reply_by_user_image_thumb_60\" alt=\"$get_reply_by_user_image_thumb_60\" /></a>
				<br />
				";
			}
			echo"
			</p>
		  </td>
		  <td style=\"vertical-align: top;\">
			<p>
			<a href=\"users/view_profile.php?user_id=$get_reply_by_user_id&amp;l=$l\" style=\"font-weight: bold;\">$get_reply_by_user_name</a> 
			$get_reply_created_saying<br />
			</p>

			<p>
			$get_reply_text
			</p>

			<!-- Like, dislike, reply, report spam + owner actions -->
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p>
					";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						echo"<a href=\"comment_reply.php?comment_id=$get_comment_id&amp;reply_id=$get_reply_id&amp;l=$l\">$l_reply</a>\n";
					}
					else{
						echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/comment_reply.php?comment_id=$get_comment_id&amp;reply_id=$get_reply_id\">$l_reply</a>\n";
					}
					echo"
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p>
					";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						echo"<a href=\"reply_like.php?reply_id=$get_reply_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/likes.png\" alt=\"likes.png\" /></a>\n";
					}
					else{
						echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/reply_like.php?reply_id=$get_reply_id\"><img src=\"_gfx/icons/likes.png\" alt=\"likes.png\" /></a>\n";
					}
					echo"$get_reply_likes
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p>
					";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						echo"<a href=\"reply_dislike.php?reply_id=$get_reply_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/dislikes.png\" alt=\"dislikes.png\" /></a>\n";
					}
					else{
						echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/reply_dislike.php?reply_id=$get_reply_id\"><img src=\"_gfx/icons/dislikes.png\" alt=\"dislikes.png\" /></a>\n";
					}
					echo"$get_reply_dislikes
					</p>
				  </td>";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					$can_edit = 0;
					if($get_my_user_id == "$get_reply_by_user_id"){
						$can_edit = 1;
					}
					if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
						$can_edit = 1;
					}

					if($can_edit == "1"){
						echo"
						  <td style=\"padding-right: 10px;\">
							<p>
							<a href=\"reply_edit.php?reply_id=$get_reply_id&amp;l=$l\">$l_edit</a>
							</p>
						  </td>
						  <td style=\"padding-right: 10px;\">
							<p>
							<a href=\"reply_delete.php?reply_id=$get_reply_id&amp;l=$l\">$l_delete</a>
							</p>
						  </td>
						";
					}
				}
				echo"
				  <td style=\"padding-right: 10px;\">
					<p>
					";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						echo"<a href=\"reply_report.php?reply_id=$get_reply_id&amp;l=$l\">$l_report</a>\n";
					}
					else{
						echo"<a href=\"users/login.php?l=$l&amp;referer=../blog/reply_report.php?reply_id=$get_reply_id\">$l_report</a>\n";
					}
					echo"
					</p>
				  </td>
				 </tr>
				</table>
			<!-- //Like, dislike, reply, report spam + owner actions -->
		  </td>
		 </tr>
		</table>
		";
	} // replies

	$count_comments++;
} // comments			
?>