<?php 
/**
*
* File: food/view_food_include_fetch_comments.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($get_current_food_id))){
	echo"error";
	die;
}




$count_ratings = 0;
$query = "SELECT rating_id, rating_food_id, rating_title, rating_text, rating_by_user_id, rating_by_user_name, rating_by_user_image_path, rating_by_user_image_file, rating_by_user_image_thumb_60, rating_by_user_ip, rating_stars, rating_created, rating_created_saying, rating_created_timestamp, rating_updated, rating_updated_saying, rating_likes, rating_dislikes, rating_number_of_replies, rating_read_blog_owner, rating_reported, rating_reported_by_user_id, rating_reported_reason, rating_reported_checked FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_rating_id, $get_rating_food_id, $get_rating_title, $get_rating_text, $get_rating_by_user_id, $get_rating_by_user_name, $get_rating_by_user_image_path, $get_rating_by_user_image_file, $get_rating_by_user_image_thumb_60, $get_rating_by_user_ip, $get_rating_stars, $get_rating_created, $get_rating_created_saying, $get_rating_created_timestamp, $get_rating_updated, $get_rating_updated_saying, $get_rating_likes, $get_rating_dislikes, $get_rating_number_of_replies, $get_rating_read_blog_owner, $get_rating_reported, $get_rating_reported_by_user_id, $get_rating_reported_reason, $get_rating_reported_checked) = $row;

	if($count_ratings == "0"){
		echo"
		<a id=\"ratings\"></a>
		<hr />
		<h2>$l_ratings</h2>
		";
	}
	echo"
	<a id=\"rating$get_rating_id\"></a>
	<table>
	 <tr>
	  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
		<p>
		";
		if(file_exists("$root/$get_rating_by_user_image_path/$get_rating_by_user_image_thumb_60") && $get_rating_by_user_image_thumb_60 != ""){
			
			echo"
			<a href=\"users/view_profile.php?user_id=$get_rating_by_user_id&amp;l=$l\"><img src=\"$root/$get_rating_by_user_image_path/$get_rating_by_user_image_thumb_60\" alt=\"$get_rating_by_user_image_thumb_60\" /></a>
			<br />
			";
		}
		echo"
		</p>
	  </td>
	  <td style=\"vertical-align: top;\">


		<!-- Stars, title and menu -->
			<table style=\"width: 100%;\">
			 <tr>
			  <td>
				<p style=\"margin:0;padding:0;\">
				";
				if($get_rating_stars == "1"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				elseif($get_rating_stars == "2"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				elseif($get_rating_stars == "3"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				elseif($get_rating_stars == "4"){
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" />
					";
				}
				else{
					echo"
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" />
					";
				}
				echo"
				<b style=\"padding-left: 10px;\">$get_rating_title</b>
				</p>
			  </td>
			  <td style=\"text-align: right;padding-left: 10px;\">
				<!-- Like, dislike, report spam + owner actions -->
					<p>\n";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						$can_edit = 0;
						if($get_my_user_id == "$get_rating_by_user_id"){
							$can_edit = 1;
						}
						if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
							$can_edit = 1;
						}

						if($can_edit == "1"){
							echo"
							<a href=\"rating_edit.php?rating_id=$get_rating_id&amp;l=$l\" class=\"grey_small\">$l_edit</a> &middot;
							<a href=\"rating_delete.php?rating_id=$get_rating_id&amp;l=$l\" class=\"grey_small\">$l_delete</a> &middot;
							";
						}

						echo"<a href=\"rating_report.php?rating_id=$get_rating_id&amp;l=$l\" class=\"grey_small\">$l_report</a>";
					}
					else{
						echo"<a href=\"users/login.php?l=$l&amp;referer=food/rating_report.php?rating_id=$get_rating_id\" class=\"grey_small\">$l_report</a>\n";
					}
					echo"
					</p>
				<!-- //Like, dislike, reply, report spam + owner actions -->
			  </td>
			 </tr>
			</table>
		<!-- //Stars, title and menu -->


		<!-- Author + date -->
			<p style=\"margin:0;padding:0;\">
			<span class=\"food_comment_by\">$l_by</span>
			<a href=\"$root/users/view_profile.php?user_id=$get_rating_by_user_id&amp;l=$l\" class=\"food_comment_author\">$get_rating_by_user_name</a>
			<a href=\"#rating$get_rating_id\" class=\"food_comment_date\">$get_rating_created_saying</a></span>
			</p>
		<!-- //Author + date -->

		<!-- Rating -->
			<p style=\"margin-top: 0px;padding-top: 0;\">$get_rating_text</p>
		<!-- Rating -->


	  </td>
	 </tr>
	</table>
	";


	$count_ratings++;
} // comments			
?>