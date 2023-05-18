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
if(!(isset($get_current_download_id))){
	echo"error";
	die;
}

$count_comments = 0;
$query = "SELECT comment_id, comment_download_id, comment_text, comment_by_user_id, comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, comment_likes, comment_dislikes, comment_read_blog_owner, comment_reported FROM $t_downloads_comments WHERE comment_download_id=$get_current_download_id";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_comment_id, $get_comment_download_id, $get_comment_text, $get_comment_by_user_id, $get_comment_by_user_name, $get_comment_by_user_image_path, $get_comment_by_user_image_file, $get_comment_by_user_image_thumb_60, $get_comment_by_user_ip, $get_comment_created, $get_comment_created_saying, $get_comment_created_timestamp, $get_comment_updated, $get_comment_updated_saying, $get_comment_likes, $get_comment_dislikes, $get_comment_read_blog_owner, $get_comment_reported) = $row;

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

		<!-- Like, dislike, report spam + owner actions -->
			<p>\n";
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
					<a href=\"comment_edit.php?comment_id=$get_comment_id&amp;l=$l\">$l_edit</a> &middot;
					<a href=\"comment_delete.php?comment_id=$get_comment_id&amp;l=$l\">$l_delete</a> &middot;
					";
				}

				echo"<a href=\"comment_report.php?comment_id=$get_comment_id&amp;l=$l\">$l_report</a>";
			}
			else{
				echo"<a href=\"$root/users/login.php?l=$l&amp;referer=downloads/comment_report.php?comment_id=$get_comment_id\">$l_report</a>\n";
			}
			echo"
			</p>
		<!-- //Like, dislike, reply, report spam + owner actions -->
	  </td>
	 </tr>
	</table>
	";


	$count_comments++;
} // comments			
?>